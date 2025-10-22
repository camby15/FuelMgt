<?php

namespace App\Http\Controllers\WareHouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wh_Supplier;
use App\Models\SupplierRating;
use App\Models\WarehouseLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SupplierController extends Controller
{
    /**
     * Check authentication and company session
     */
    private function checkAuth()
    {
        if (!Auth::check() && !Auth::guard('sub_user')->check() && !Auth::guard('company_sub_user')->check()) {
            return response()->json([
                'error' => true,
                'message' => 'Please login to continue',
                'redirect' => route('auth.login')
            ], 401);
        }

        $companyId = Session::get('selected_company_id');
        
        if (!$companyId) {
            return response()->json([
                'error' => true,
                'message' => 'Company session expired. Please login again.',
                'redirect' => route('auth.login')
            ], 401);
        }

        return null;
    }

    /**
     * Display a listing of suppliers with their ratings
     */
    public function index(Request $request)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        
        $query = Wh_Supplier::where('company_id', $companyId)
            ->with(['ratings' => function($q) {
                $q->with('user:id,fullname')->latest()->take(3); // Get latest 3 ratings with user info
            }])
            ->withCount('ratings')
            ->withAvg('ratings', 'rating')
            ->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('company_name', 'like', "%$search%")
                  ->orWhere('primary_contact', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }

        // Filter by minimum rating if provided
        if ($request->has('min_rating')) {
            $query->having('ratings_avg_rating', '>=', $request->min_rating);
        }

        // Apply additional filters
        if ($request->has('filters')) {
            $filters = $request->filters;
            
            // Debug: Log the filters being applied
            \Log::info('Applying filters:', $filters);
            
            if (isset($filters['status'])) {
                $query->where('status', $filters['status']);
                \Log::info('Filtering by status:', ['status' => $filters['status']]);
            }
            
            if (isset($filters['location'])) {
                $location = $filters['location'];
                $query->where(function($q) use ($location) {
                    $q->where('city', 'like', "%$location%")
                      ->orWhere('region', 'like', "%$location%")
                      ->orWhere(DB::raw("CONCAT(city, ', ', region)"), 'like', "%$location%");
                });
                \Log::info('Filtering by location:', ['location' => $filters['location']]);
            }
        }

        try {
            $result = $query->paginate($request->per_page ?? 10);
            
            // Load purchase orders for each supplier separately
            $result->getCollection()->transform(function($supplier) {
                $latestOrder = \App\Models\Wh_PurchaseOrder::where('supplier_id', $supplier->id)
                    ->select('id', 'po_number', 'supplier_id', 'total_value', 'created_at', 'status')
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                $supplier->purchase_orders = $latestOrder ? [$latestOrder] : [];
                return $supplier;
            });
            
            // Debug: Log the result to see what's being loaded
            \Log::info('Suppliers loaded:', [
                'total_suppliers' => $result->count(),
                'suppliers_data' => $result->map(function($supplier) {
                    return [
                        'id' => $supplier->id,
                        'company_name' => $supplier->company_name,
                        'purchase_orders_count' => count($supplier->purchase_orders),
                        'purchase_orders' => $supplier->purchase_orders
                    ];
                })
            ]);
            
            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load suppliers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search suppliers for dropdown/select2
     */
    public function search(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            // If no company ID in session, try to get from user or use default
            if (!$companyId) {
                if (Auth::check()) {
                    $user = Auth::user();
                    if ($user instanceof \App\Models\User) {
                        $companyId = $user->company_id ?? 1;
                    } elseif ($user instanceof \App\Models\CompanySubUser) {
                        $companyId = $user->company_id ?? 1;
                    } else {
                        $companyId = 1; // Default fallback
                    }
                } else {
                    $companyId = 1; // Default fallback
                }
            }

            $search = $request->input('search');
            $suppliers = Wh_Supplier::where('company_id', $companyId)
                ->when($search, function ($query) use ($search) {
                    $query->where('company_name', 'like', '%' . $search . '%')
                          ->orWhere('primary_contact', 'like', '%' . $search . '%');
                })
                ->select('id', 'company_name as text') // Select 'id' and 'text' for Select2
                ->limit(20)
                ->get();

            return response()->json([
                'success' => true,
                'suppliers' => $suppliers
            ]);

        } catch (\Exception $e) {
            \Log::error('Error searching suppliers: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error searching suppliers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified supplier with full rating details
     */
    public function show($id)
    {
        try {
            if ($response = $this->checkAuth()) {
                return $response;
            }

            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'error' => true,
                    'message' => 'Company session not found. Please login again.'
                ], 401);
            }

            // First, get the supplier without relationships to see if it exists
            $supplier = Wh_Supplier::where('company_id', $companyId)
                ->findOrFail($id);
                
            // Then load the relationships separately to avoid issues
            try {
                $supplier->load(['ratings' => function($q) {
                    $q->with(['user' => function($userQuery) {
                        $userQuery->select('id', 'fullname', 'email');
                    }]);
                }]);
                
                $supplier->loadCount('ratings');
                $supplier->loadAvg('ratings', 'rating');
            } catch (\Exception $e) {
                \Log::warning('Error loading supplier relationships: ' . $e->getMessage());
                // Continue without relationships if there's an error
            }

            return response()->json([
                'success' => true,
                'data' => $supplier
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Supplier not found.'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Error fetching supplier: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Failed to fetch supplier details. Please try again.'
            ], 500);
        }
    }

    /**
     * Store a newly created supplier with optional initial rating
     */
    public function store(Request $request)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $validated = $request->validate([
            // Company Information
            'company_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:255',
            'tin' => 'required|string|max:20|unique:wh__suppliers,tin',
            'vat_number' => 'nullable|string|max:20',
            'ssnit_number' => 'nullable|string|max:20',
            'year_established' => 'nullable|integer|min:1957|max:' . date('Y'),
            'registration_number' => 'nullable|string|max:50',
            'business_sector' => 'required|string|max:255',
            'company_size' => 'required|string|max:255',
            
            // Contact Information
            'primary_contact' => 'required|string|max:255',
            'contact_position' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            
            // Address Information
            'street_address' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            
            // Additional Information
            'payment_terms' => 'required|string|max:255',
            'currency' => 'required|string|max:3',

            // Rating Information (optional)
            'initial_rating' => 'nullable|numeric|min:1|max:5',
            'rating_comment' => 'nullable|string|max:500'
        ]);

        $companyId = Session::get('selected_company_id');

        DB::beginTransaction();
        try {
            // Create the supplier
            $supplier = Wh_Supplier::create([
                ...$validated,
                'company_id' => $companyId,
                'user_id' => Auth::id()
            ]);

            // Add initial rating if provided
            if ($request->filled('initial_rating')) {
                $supplier->ratings()->create([
                    'user_id' => Auth::id(),
                    'company_id' => $companyId,
                    'rating' => $request->initial_rating,
                    'comments' => $request->rating_comment
                ]);

                // Recalculate averages immediately
                $supplier->loadAvg('ratings', 'rating');
            }

            // Log the action
            // WarehouseLog::create([
            //     'model' => 'Wh_Supplier',
            //     'model_id' => $supplier->id,
            //     'action' => 'create_supplier',
            //     'description' => $request->filled('initial_rating') 
            //         ? 'Supplier created with initial rating' 
            //         : 'Supplier created',
            //     'performed_by' => Auth::user()->name,
            //     'performed_at' => now(),
            //     'company_id' => $companyId,
            //     'user_id' => Auth::id()
            // ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $supplier->load(['ratings' => function($q) {
                    $q->with('user:id,fullname');
                }]),
                'message' => $request->filled('initial_rating') 
                    ? 'Supplier created with initial rating' 
                    : 'Supplier created successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create supplier: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified supplier (without rating functionality)
     */
    public function update(Request $request, $id)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        $supplier = Wh_Supplier::where('company_id', $companyId)
            ->findOrFail($id);

        $validated = $request->validate([
            // Company Information
            'company_name' => 'sometimes|required|string|max:255',
            'business_type' => 'sometimes|required|string|max:255',
            'tin' => 'sometimes|required|string|max:20|unique:wh__suppliers,tin,'.$supplier->id,
            'vat_number' => 'nullable|string|max:20',
            'ssnit_number' => 'nullable|string|max:20',
            'year_established' => 'nullable|integer|min:1957|max:' . date('Y'),
            'registration_number' => 'nullable|string|max:50',
            'business_sector' => 'sometimes|required|string|max:255',
            'company_size' => 'sometimes|required|string|max:255',
            
            // Contact Information
            'primary_contact' => 'sometimes|required|string|max:255',
            'contact_position' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255',
            'phone' => 'sometimes|required|string|max:20',
            
            // Address Information
            'street_address' => 'sometimes|required|string|max:255',
            'area' => 'sometimes|required|string|max:255',
            'city' => 'sometimes|required|string|max:255',
            'region' => 'sometimes|required|string|max:255',
            
            // Additional Information
            'payment_terms' => 'sometimes|required|string|max:255',
            'currency' => 'sometimes|required|string|max:3'
        ]);

        DB::beginTransaction();
        try {
            $supplier->update($validated);

            // WarehouseLog::create([
            //     'model' => 'Wh_Supplier',
            //     'model_id' => $supplier->id,
            //     'action' => 'update_supplier',
            //     'description' => 'Supplier details updated',
            //     'performed_by' => Auth::user()->name,
            //     'performed_at' => now(),
            //     'company_id' => $companyId,
            //     'user_id' => Auth::id()
            // ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $supplier->fresh(['ratings' => function($q) {
                    $q->with('user:id,fullname');
                }]),
                'message' => 'Supplier updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update supplier: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified supplier (and all associated ratings)
     */
    public function destroy($id)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        $supplier = Wh_Supplier::where('company_id', $companyId)
            ->findOrFail($id);

        DB::beginTransaction();
        try {
            // Delete all ratings first
            $supplier->ratings()->delete();
            
            // Then delete the supplier
            $supplier->delete();

            // WarehouseLog::create([
            //     'model' => 'Wh_Supplier',
            //     'model_id' => $supplier->id,
            //     'action' => 'delete_supplier',
            //     'description' => 'Supplier and all ratings deleted',
            //     'performed_by' => Auth::user()->name,
            //     'performed_at' => now(),
            //     'company_id' => $companyId,
            //     'user_id' => Auth::id()
            // ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Supplier and all associated ratings deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete supplier: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add or update a rating for the specified supplier
     */
    public function rateSupplier(Request $request)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $validated = $request->validate([
            'supplier_id' => 'required|exists:wh__suppliers,id',
            'rating' => 'required|numeric|min:1|max:5',
            'comments' => 'nullable|string|max:500'
        ]);

        $companyId = Session::get('selected_company_id');
        $supplier = Wh_Supplier::where('company_id', $companyId)
            ->findOrFail($validated['supplier_id']);

        DB::beginTransaction();
        try {
            // Update or create the rating
            $rating = $supplier->ratings()->updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'company_id' => $companyId
                ],
                [
                    'rating' => $validated['rating'],
                    'comments' => $validated['comments']
                ]
            );

            // Log the action
            WarehouseLog::create([
                'model' => 'Wh_Supplier',
                'model_id' => $supplier->id,
                'action' => $rating->wasRecentlyCreated ? 'add_rating' : 'update_rating',
                'description' => ($rating->wasRecentlyCreated ? 'Added' : 'Updated') . 
                                ' rating (' . $validated['rating'] . ' stars)',
                'performed_by' => Auth::id(),
                'performed_at' => now(),
                'company_id' => $companyId,
                'user_id' => Auth::id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $supplier->fresh(['ratings' => function($q) {
                    $q->with('user:id,fullname');
                }]),
                'message' => 'Rating ' . ($rating->wasRecentlyCreated ? 'added' : 'updated') . ' successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update rating: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get ratings for a specific supplier
     */
    public function getSupplierRatings($id)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        $supplier = Wh_Supplier::where('company_id', $companyId)
            ->findOrFail($id);

        $ratings = $supplier->ratings()
            ->with('user:id,fullname')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'ratings' => $ratings
        ]);
    }

    /**
     * Export suppliers to CSV
     */
    public function export(Request $request)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        
        $query = Wh_Supplier::where('company_id', $companyId)
            ->with(['ratings' => function($q) {
                $q->with('user:id,fullname')->latest()->take(3);
            }])
            ->with(['purchaseOrders' => function($q) {
                $q->latest()->take(1);
            }])
            ->withCount('ratings')
            ->withAvg('ratings', 'rating')
            ->orderBy('company_name');

        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('company_name', 'like', "%$search%")
                  ->orWhere('primary_contact', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }

        // Apply additional filters
        if ($request->has('filters')) {
            $filters = $request->filters;
            
            if (isset($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            
            if (isset($filters['category'])) {
                $query->where('business_sector', $filters['category']);
            }
        }

        $suppliers = $query->get();

        // Create CSV content
        $headers = [
            'Company Name',
            'Business Type',
            'Primary Contact',
            'Email',
            'Phone',
            'City',
            'Region',
            'Business Sector',
            'Payment Terms',
            'Average Rating',
            'Total Ratings',
            'Last Order Date',
            'Last Order Value',
            'Created Date'
        ];

        $csvData = [];
        $csvData[] = $headers;

        foreach ($suppliers as $supplier) {
            $lastOrder = $supplier->purchaseOrders->first();
            $lastOrderDate = $lastOrder ? $lastOrder->created_at->format('Y-m-d') : 'No orders';
            $lastOrderValue = $lastOrder ? number_format($lastOrder->total_value, 2) : 'N/A';
            
            $csvData[] = [
                $supplier->company_name,
                $supplier->business_type,
                $supplier->primary_contact,
                $supplier->email,
                $supplier->phone,
                $supplier->city,
                $supplier->region,
                $supplier->business_sector,
                $supplier->payment_terms,
                number_format($supplier->ratings_avg_rating ?? 0, 1),
                $supplier->ratings_count ?? 0,
                $lastOrderDate,
                $lastOrderValue,
                $supplier->created_at->format('Y-m-d')
            ];
        }

        // Generate CSV file
        $filename = 'suppliers_' . date('Y-m-d_H-i-s') . '.csv';
        
        return response()->stream(
            function () use ($csvData) {
                $output = fopen('php://output', 'w');
                foreach ($csvData as $row) {
                    fputcsv($output, $row);
                }
                fclose($output);
            },
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]
        );
    }

    /**
     * Get filter data for suppliers (status counts and categories)
     */
    public function getFilterData(Request $request)
    {
        if ($response = $this->checkAuth()) {
            return $response;
        }

        $companyId = Session::get('selected_company_id');
        
        // Get status counts
        $statusCounts = Wh_Supplier::where('company_id', $companyId)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        // Debug: Log the status counts
        \Log::info('Status counts for company ' . $companyId, ['status_counts' => $statusCounts]);
        
        // Also log all unique status values
        $allStatuses = Wh_Supplier::where('company_id', $companyId)
            ->distinct()
            ->pluck('status')
            ->toArray();
        \Log::info('All unique status values', ['statuses' => $allStatuses]);
        
        // Get distinct locations (city, region)
        $locations = Wh_Supplier::where('company_id', $companyId)
            ->where(function($query) {
                $query->whereNotNull('city')
                      ->orWhereNotNull('region');
            })
            ->select('city', 'region')
            ->distinct()
            ->get()
            ->map(function($supplier) {
                $location = [];
                if ($supplier->city) $location[] = $supplier->city;
                if ($supplier->region) $location[] = $supplier->region;
                return implode(', ', $location);
            })
            ->filter()
            ->unique()
            ->values()
            ->toArray();
        
        return response()->json([
            'success' => true,
            'data' => [
                'status_counts' => $statusCounts,
                'locations' => $locations
            ]
        ]);
    }
}