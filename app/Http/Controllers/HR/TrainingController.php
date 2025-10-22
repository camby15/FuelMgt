<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TrainingController extends Controller
{
    /**
     * Get training statistics
     */
    public function getStats(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            // Initialize stats with zeros
            $stats = [
                'total_programs' => 0,
                'active_programs' => 0,
                'completed_programs' => 0,
                'total_participants' => 0,
                'this_month' => 0,
                'by_type' => []
            ];

            try {
                // Get training counts safely
                $stats['total_programs'] = Training::where('company_id', $companyId)->count();
                $stats['active_programs'] = Training::where('company_id', $companyId)
                    ->where('status', 'active')->count();
                $stats['completed_programs'] = Training::where('company_id', $companyId)
                    ->where('status', 'completed')->count();
                
                // Total participants
                $stats['total_participants'] = Training::where('company_id', $companyId)
                    ->sum('participant_count');

                // Monthly stats
                $currentMonth = now()->format('Y-m');
                $stats['this_month'] = Training::where('company_id', $companyId)
                    ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [$currentMonth])
                    ->count();

                // Training type breakdown
                $stats['by_type'] = Training::where('company_id', $companyId)
                    ->select('type', DB::raw('count(*) as count'))
                    ->groupBy('type')
                    ->get()
                    ->pluck('count', 'type');
            } catch (\Exception $e) {
                Log::error('Error getting training stats: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Training statistics retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to fetch training stats: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch training statistics.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get training programs list
     */
    public function index(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            $query = Training::where('company_id', $companyId);

            // Apply filters
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            if ($request->has('type') && $request->type !== 'all') {
                $query->where('type', $request->type);
            }

            if ($request->has('search') && $request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%')
                      ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            }

            // Pagination
            $perPage = $request->get('per_page', 10);
            $trainings = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $trainings->items(),
                'pagination' => [
                    'current_page' => $trainings->currentPage(),
                    'last_page' => $trainings->lastPage(),
                    'per_page' => $trainings->perPage(),
                    'total' => $trainings->total()
                ],
                'message' => 'Training programs retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to fetch training programs: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch training programs.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test method to debug form data
     */
    public function test(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Test successful',
            'data' => $request->all(),
            'company_id' => Session::get('selected_company_id'),
            'user_id' => Auth::id()
        ]);
    }

    /**
     * Store a new training program
     */
    public function store(Request $request)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            // Debug: Log the incoming request data
            Log::info('Training Store Request Data:', [
                'request_data' => $request->all(),
                'company_id' => $companyId,
                'user_id' => Auth::id()
            ]);

            $validator = \Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'type' => 'required|string|in:workshop,seminar,course,certification,online',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after:start_date',
                'participant_count' => 'required|integer|min:1',
                'instructor' => 'required|string|max:255',
                'location' => 'nullable|string|max:255',
                'status' => 'required|string|in:planned,active,completed,cancelled'
            ]);

            if ($validator->fails()) {
                Log::error('Training Validation Failed:', [
                    'errors' => $validator->errors(),
                    'request_data' => $request->all()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $training = Training::create([
                'company_id' => $companyId,
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'participant_count' => $request->participant_count,
                'instructor' => $request->instructor,
                'location' => $request->location,
                'status' => $request->status,
                'created_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'data' => $training,
                'message' => 'Training program created successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to create training program: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create training program.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific training program
     */
    public function show($id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            $training = Training::where('company_id', $companyId)->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $training,
                'message' => 'Training program retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to fetch training program: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Training program not found.',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update a training program
     */
    public function update(Request $request, $id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            $training = Training::where('company_id', $companyId)->findOrFail($id);

            $validator = \Validator::make($request->all(), [
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'type' => 'sometimes|required|string|in:workshop,seminar,course,certification,online',
                'start_date' => 'sometimes|required|date',
                'end_date' => 'sometimes|required|date|after:start_date',
                'participant_count' => 'sometimes|required|integer|min:1',
                'instructor' => 'sometimes|required|string|max:255',
                'location' => 'nullable|string|max:255',
                'status' => 'sometimes|required|string|in:planned,active,completed,cancelled'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $training->update($request->only([
                'title', 'description', 'type', 'start_date', 'end_date',
                'participant_count', 'instructor', 'location', 'status'
            ]));

            return response()->json([
                'success' => true,
                'data' => $training,
                'message' => 'Training program updated successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to update training program: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update training program.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a training program
     */
    public function destroy($id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company selected.'
                ], 400);
            }

            $training = Training::where('company_id', $companyId)->findOrFail($id);
            $training->delete();

            return response()->json([
                'success' => true,
                'message' => 'Training program deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to delete training program: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete training program.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
