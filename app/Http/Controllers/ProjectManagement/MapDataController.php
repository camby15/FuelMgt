<?php

namespace App\Http\Controllers\ProjectManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ProjectManagement\MapIssue;

class MapDataController extends Controller
{
    /**
     * Return map markers for connections and teams.
     */
    public function index(Request $request)
    {
        $companyId = (int) ($request->user()?->company_id ?? session('selected_company_id'));

        // Connections from home_connection_customers
        $connections = DB::table('home_connection_customers')
            ->select([
                'id',
                DB::raw("CAST(latitude AS CHAR) AS latitude"),
                DB::raw("CAST(longitude AS CHAR) AS longitude"),
                'customer_name',
                'status',
                'location',
                'gps_address',
                'msisdn',
            ])
            ->when($companyId, fn($q) => $q->where('company_id', $companyId))
            ->whereNull('deleted_at')
            ->limit(1000)
            ->get()
            ->map(function ($row) {
                // Check if customer has valid GPS coordinates in database
                $hasCoordinatesInDB = !empty($row->latitude) && !empty($row->longitude);
                $lat = $hasCoordinatesInDB ? (float) $row->latitude : null;
                $lng = $hasCoordinatesInDB ? (float) $row->longitude : null;
                $geocoded = false;
                
                // If no coordinates in DB, try to geocode from GPS address or location
                if (!$hasCoordinatesInDB) {
                    // Try GPS address first, then location as fallback
                    $addressToGeocode = !empty($row->gps_address) ? $row->gps_address : $row->location;
                    
                    if (!empty($addressToGeocode)) {
                        Log::info("Attempting to geocode for customer {$row->id}: {$addressToGeocode}");
                        [$geocodedLat, $geocodedLng] = $this->geocodeAddress($addressToGeocode);
                        
                        if ($geocodedLat !== null && $geocodedLng !== null) {
                            $lat = $geocodedLat;
                            $lng = $geocodedLng;
                            $geocoded = true;
                            
                            // Save geocoded coordinates back to database for future use
                            try {
                                DB::table('home_connection_customers')
                                    ->where('id', $row->id)
                                    ->update([
                                        'latitude' => $geocodedLat,
                                        'longitude' => $geocodedLng,
                                        'updated_at' => now()
                                    ]);
                                Log::info("Successfully geocoded and saved customer {$row->id} to: {$lat}, {$lng}");
                            } catch (\Exception $e) {
                                Log::error("Failed to save geocoded coordinates for customer {$row->id}: " . $e->getMessage());
                            }
                        } else {
                            Log::warning("Failed to geocode customer {$row->id} with address: {$addressToGeocode}");
                        }
                    }
                }
                
                // Only skip customer if we have absolutely no location data
                if ($lat === null || $lng === null) {
                    Log::warning("Skipping customer {$row->id} ({$row->customer_name}) - no coordinates and no valid address to geocode");
                    return null;
                }
                
                return [
                    'id' => 'CUST-' . $row->id,
                    'lat' => $lat,
                    'lng' => $lng,
                    'title' => $row->customer_name,
                    'type' => 'connection',
                    'status' => strtolower($row->status ?? 'Active'),
                    'address' => $row->gps_address ?: $row->location,
                    'lastActive' => null,
                    'plan' => null,
                    'msisdn' => $row->msisdn,
                    'hasGPS' => true, // All customers on map have valid locations (either from DB or geocoded)
                    'geocoded' => $geocoded, // Flag to indicate if location was geocoded
                ];
            })
            ->filter() // Remove null entries (customers without any location data)
            ->values();

        // Teams from team_paring. team_location is a string city name.
        // Map city names to their GPS coordinates
        $teamsRaw = DB::table('team_paring')
            ->select(['id', 'team_name', 'team_status', 'team_location', 'contact_number'])
            ->when($companyId, fn($q) => $q->where('company_id', $companyId))
            ->limit(1000)
            ->get();

        $teams = collect();
        foreach ($teamsRaw as $row) {
            // First try to parse as coordinates (lat,lng format)
            [$lat, $lng] = $this->parseLatLng((string) $row->team_location);
            
            // If not coordinates, map city name to default location
            if ($lat === null || $lng === null) {
                [$lat, $lng] = $this->getCityCoordinates(strtolower(trim($row->team_location)));
            }
            
            $teams->push([
                'id' => 'TEAM-' . $row->id,
                'lat' => $lat, // can be null
                'lng' => $lng, // can be null
                'title' => $row->team_name,
                'type' => 'team',
                'status' => strtolower($row->team_status ?? 'active'),
                'members' => null,
                'currentJob' => null,
                'contact' => $row->contact_number,
                'location' => $row->team_location,
            ]);
        }

        // Issues from map_issues
        $issues = MapIssue::query()
            ->when($companyId, fn($q) => $q->where('company_id', $companyId))
            ->latest('id')
            ->limit(1000)
            ->get()
            ->map(function ($row) {
                return [
                    'id' => 'ISSUE-' . $row->code,
                    'lat' => (float) $row->latitude,
                    'lng' => (float) $row->longitude,
                    'title' => $row->title,
                    'type' => 'issue',
                    'status' => strtolower($row->status ?? 'open'),
                    'severity' => strtolower($row->severity ?? 'medium'),
                    'reported' => optional($row->created_at)->diffForHumans(),
                    'description' => $row->description,
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => [
                'connections' => $connections,
                'teams' => $teams->values(),
                'issues' => $issues,
            ],
        ]);
    }

    private function parseLatLng(string $value): array
    {
        // Accept formats like "5.6037, -0.1870" or "5.6037 -0.1870"
        $value = trim($value);
        if ($value === '') return [null, null];
        if (preg_match('/^\s*([-+]?[0-9]*\.?[0-9]+)\s*,\s*([-+]?[0-9]*\.?[0-9]+)\s*$/', $value, $m)) {
            return [(float) $m[1], (float) $m[2]];
        }
        if (preg_match('/^\s*([-+]?[0-9]*\.?[0-9]+)\s+([-+]?[0-9]*\.?[0-9]+)\s*$/', $value, $m)) {
            return [(float) $m[1], (float) $m[2]];
        }
        return [null, null];
    }

    /**
     * Geocode an address to GPS coordinates using multiple strategies
     * Returns [latitude, longitude] or [null, null] if geocoding fails
     */
    private function geocodeAddress(string $address): array
    {
        try {
            $query = trim($address);
            
            if (empty($query)) {
                return [null, null];
            }
            
            // Check if it's already coordinates (e.g., "5.6037, -0.1870")
            if (preg_match('/^\s*(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)\s*$/', $query, $matches)) {
                return [(float) $matches[1], (float) $matches[2]];
            }
            
            // Try multiple geocoding strategies
            $strategies = [
                // Strategy 1: Try exact address
                $query,
                // Strategy 2: Extract area/city from address (e.g., "Adenta" from full address)
                $this->extractAreaFromAddress($query),
                // Strategy 3: Add Ghana if not present
                (str_contains(strtolower($query), 'ghana') ? null : $query . ', Ghana'),
            ];
            
            foreach ($strategies as $searchQuery) {
                if (empty($searchQuery)) continue;
                
                $result = $this->tryGeocoding($searchQuery);
                if ($result[0] !== null && $result[1] !== null) {
                    Log::info("Successfully geocoded '{$address}' using query '{$searchQuery}' to: {$result[0]}, {$result[1]}");
                    return $result;
                }
            }
            
            Log::warning("All geocoding strategies failed for address: {$address}");
            return [null, null];
            
        } catch (\Exception $e) {
            Log::error("Geocoding error for address '{$address}': " . $e->getMessage());
            return [null, null];
        }
    }
    
    /**
     * Extract area/city name from full address
     */
    private function extractAreaFromAddress(string $address): ?string
    {
        // Try to extract area name (e.g., "Adenta" from "CB 40 Dominion Street, NKA Apartments, Adenta -Frafraha")
        // Look for patterns like "Area - City" or just "Area"
        if (preg_match('/,\s*([A-Za-z\s]+)\s*-\s*([A-Za-z\s]+)$/i', $address, $matches)) {
            // Found "Area - City" pattern
            return trim($matches[1]) . ', ' . trim($matches[2]) . ', Ghana';
        }
        
        if (preg_match('/,\s*([A-Za-z\s]+)$/i', $address, $matches)) {
            // Found last part after comma
            return trim($matches[1]) . ', Ghana';
        }
        
        return null;
    }
    
    /**
     * Try geocoding with Nominatim
     */
    private function tryGeocoding(string $query): array
    {
        try {
            // Use Nominatim (OpenStreetMap) geocoding service
            $url = 'https://nominatim.openstreetmap.org/search?' . http_build_query([
                'format' => 'json',
                'limit' => 1,
                'addressdetails' => 1,
                'countrycodes' => 'gh', // Ghana country code
                'q' => $query
            ]);
            
            // Make HTTP request with proper headers
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'header' => [
                        'User-Agent: GESL-ERP-System/1.0',
                        'Accept: application/json'
                    ],
                    'timeout' => 5 // 5 second timeout
                ]
            ]);
            
            $response = @file_get_contents($url, false, $context);
            
            if ($response === false) {
                return [null, null];
            }
            
            $data = json_decode($response, true);
            
            if (!empty($data) && is_array($data) && isset($data[0]['lat'], $data[0]['lon'])) {
                return [(float) $data[0]['lat'], (float) $data[0]['lon']];
            }
            
            return [null, null];
            
        } catch (\Exception $e) {
            return [null, null];
        }
    }

    /**
     * Get GPS coordinates for Ghana cities/towns
     * Returns [latitude, longitude] for known cities, or [null, null] for unknown
     */
    private function getCityCoordinates(string $cityName): array
    {
        // Ghana cities and their GPS coordinates
        $coordinates = [
            // Greater Accra Region
            'accra' => [5.6037, -0.1870],
            'tema' => [5.6698, -0.0166],
            'madina' => [5.6795, -0.1679],
            'ashaiman' => [5.6950, -0.0330],
            'teshie' => [5.5893, -0.1041],
            'lapaz' => [5.6519, -0.2577],
            'la paz' => [5.6519, -0.2577],
            
            // Ashanti Region
            'kumasi' => [6.6885, -1.6244],
            'obuasi' => [6.1972, -1.6669],
            'konongo' => [6.6167, -1.2167],
            'ejura' => [7.3833, -1.3667],
            'mampong' => [7.0667, -1.4000],
            
            // Western Region
            'takoradi' => [4.8845, -1.7554],
            'sekondi' => [4.9344, -1.7033],
            'tarkwa' => [5.3000, -1.9833],
            'prestea' => [5.4333, -2.1500],
            
            // Central Region
            'cape coast' => [5.1053, -1.2466],
            'cape-coast' => [5.1053, -1.2466],
            'elmina' => [5.0833, -1.3500],
            'winneba' => [5.3500, -0.6167],
            'agona swedru' => [5.5333, -0.7000],
            'agona-swedru' => [5.5333, -0.7000],
            
            // Eastern Region
            'koforidua' => [6.0833, -0.2667],
            'nkawkaw' => [6.5500, -0.7667],
            'akim oda' => [5.9333, -0.9833],
            'akim-oda' => [5.9333, -0.9833],
            'nsawam' => [5.8000, -0.3500],
            
            // Northern Region
            'tamale' => [9.4034, -0.8424],
            'yendi' => [9.4425, -0.0167],
            'saboba' => [9.5833, 0.3667],
            
            // Volta Region
            'ho' => [6.6000, 0.4667],
            'hohoe' => [7.1500, 0.4667],
            'kpando' => [7.0000, 0.3000],
            'sogakope' => [6.0167, 0.6000],
            
            // Upper East Region
            'bolgatanga' => [10.7856, -0.8514],
            'navrongo' => [10.8958, -1.0933],
            'bawku' => [11.0600, -0.2400],
            
            // Upper West Region
            'wa' => [10.0600, -2.5000],
            'jirapa' => [10.5167, -2.7333],
            'lawra' => [10.6500, -2.9000],
            
            // Bono Region
            'sunyani' => [7.3333, -2.3333],
            'dormaa ahenkro' => [7.1667, -3.0000],
            'dormaa-ahu' => [7.1667, -3.0000],
            'berekum' => [7.4500, -2.5833],
            
            // Bono East Region
            'techiman' => [7.5833, -1.9333],
            'kintampo' => [8.0500, -1.7333],
            'nkoranza' => [7.5500, -1.7000],
            
            // Ahafo Region
            'goaso' => [6.8000, -2.5333],
            'bechem' => [7.0833, -2.0333],
            'kenyasi' => [6.9667, -2.3333],
            
            // Western North Region
            'sefwi wiawso' => [6.2167, -2.4833],
            'sefwi-wiawso' => [6.2167, -2.4833],
            'bibiani' => [6.4667, -2.3167],
            'juaboso' => [6.3333, -2.7167],
            
            // North East Region
            'nalerigu' => [10.5167, -0.3667],
            'walewale' => [10.3167, -0.8333],
            'chereponi' => [9.9833, 0.0500],
            
            // Savannah Region
            'damongo' => [9.0833, -1.8167],
            'buipe' => [8.2833, -1.7667],
            'salaga' => [8.5500, -0.5167],
            
            // Oti Region
            'dambai' => [8.0833, 0.0833],
            'krachi east' => [7.7667, 0.0500],
            'krachi-east' => [7.7667, 0.0500],
            'nkwanta' => [8.2500, 0.7500],
        ];

        return $coordinates[$cityName] ?? [null, null];
    }
}


