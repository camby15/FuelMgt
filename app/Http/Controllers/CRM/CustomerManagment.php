<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ActivityLogController;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomersExport;
use Carbon\Carbon;
use App\Helpers\CurrencyHelper;

class CustomerManagment extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $companyId = session('selected_company_id');
            Log::info('Company ID from session:', ['company_id' => $companyId]);

            if (!$companyId) {
                Log::warning('No company ID found in session');
                return view('company.CRM.crm', [
                    'customers' => collect(),
                    'error' => 'No company selected. Please select a company first.',
                    'activeTab' => 'customer'
                ]);
            }

            // Get all customers for this company
            $customers = Customer::where('company_id', $companyId)
                ->orderBy('created_at', 'desc')
                ->get();

            Log::info('Fetched customers:', [
                'count' => $customers->count(),
                'company_id' => $companyId
            ]);

            return view('company.CRM.crm', [
                'customers' => $customers,
                'activeTab' => 'customer'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching customers:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('company.CRM.crm', [
                'customers' => collect(),
                'error' => 'An error occurred while fetching customers.',
                'activeTab' => 'customer'
            ]);
        }
    }


    public function showall()
    {
        try {
            $companyId = session('selected_company_id');
            Log::info('Company ID from session:', ['company_id' => $companyId]);

            if (!$companyId) {
                Log::warning('No company ID found in session');
                return view('company.CRM.crm', [
                    'customers' => collect(),
                    'error' => 'No company selected. Please select a company first.',
                    'activeTab' => 'customer'
                ]);
            }

            // Get all customers for this company
            $customers = Customer::where('company_id', $companyId)
                ->orderBy('created_at', 'desc')
                ->paginate(5);

            // Format currency values

            $customers->getCollection()->transform(function($cust) {
                $cust->value = CurrencyHelper::format($cust->value);
                return $cust;
            });

            Log::info('Fetched customers:', [
                'count' => $customers->count(),
                'company_id' => $companyId
            ]);

            return response()->json([
                'error' => false,
                'message' => 'Customers fetched successfully',
                'data' => $customers->items(), // Leads data
                'pagination' => [
                    'total' => $customers->total(),
                    'per_page' => $customers->perPage(), 
                    'current_page' => $customers->currentPage(),
                    'last_page' => $customers->lastPage(),
                    'next_page_url' => $customers->nextPageUrl(),
                    'prev_page_url' => $customers->previousPageUrl()
                ]
            ]);

       
        } catch (\Exception $e) {
            Log::error('Error fetching customers:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Return JSON error response for AJAX
            return response()->json([
                'error' => true,
                'message' => 'An error occurred while fetching customers.'
            ], 500);
        }
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create(): View
    {
        return view('company.CRM.create-customer', [
            'page_title' => 'Create Customer'
        ]);
    }

    /**
     * Create a new customer.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $company_id = session('selected_company_id');
        $is_corporate = $request->has('company_name');
        
        Log::info('Starting ' . ($is_corporate ? 'corporate' : 'individual') . ' customer creation', [
            'request_data' => $request->all(),
            'company_id' => $company_id,
            'session_data' => session()->all()
        ]);

        if (!$company_id) {
            Log::error('Missing company_id in session', ['session_data' => session()->all()]);
            return response()->json([
                'success' => false,
                'message' => 'Company ID not found in session'
            ], 422);
        }

        // Prepare customer data based on type
        $requestData = array_merge($request->all(), [
            'company_id' => $company_id,
            'customer_type' => $is_corporate ? 'corporate' : 'individual'
        ]);

        if ($is_corporate) {
            $requestData = array_merge($requestData, [
                'name' => $request->company_name,
                'email' => $request->corporate_email,
                'phone' => $request->corporate_telephone,
                'address' => $request->headquarters_address,
                
                // Add all corporate fields
                'company_name' => $request->company_name,
                'corporate_telephone' => $request->corporate_telephone,
                'corporate_email' => $request->corporate_email,
                'headquarters_address' => $request->headquarters_address,
                'commencement_date' => $request->commencement_date,
                'sector' => $request->sector,
                'number_of_employees' => $request->number_of_employees,
                'primary_contact_name' => $request->primary_contact_name,
                'primary_contact_position' => $request->primary_contact_position,
                'primary_contact_number' => $request->primary_contact_number,
                'primary_contact_email' => $request->primary_contact_email,
                'primary_contact_address' => $request->primary_contact_address,
                'source_of_acquisition' => $request->source_of_acquisition,
                'change_type' => $request->change_type,
                'assigned_branch' => $request->assigned_branch,
                'channel' => $request->channel,
                'company_group_code' => $request->company_group_code,
                'mode_of_communication' => $request->mode_of_communication,
                'customer_category' => $request->customer_category,
                'value' => $request->value,
                'status' => $request->status
            ]);
        }

        Log::info('Prepared customer data', ['data' => $requestData]);

        // Build validation rules
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('customers')->where(function ($query) use ($company_id) {
                    return $query->where('company_id', $company_id);
                })
            ],
            'phone' => 'required|string',
            'address' => 'required|string',
            'company_id' => 'required|exists:company_profiles,id',
            'customer_type' => 'required|in:corporate,individual',
            'customer_category' => 'required|string|in:Standard,VIP,HVC',
            'value' => 'required|numeric',
            'status' => 'required|string|in:Active,Inactive,Pending,On Hold,Suspended,Blacklisted,VIP,Regular,New',
            'change_type' => 'nullable|string|max:50',
            'assigned_branch' => 'nullable|string|max:100',
            'channel' => 'nullable|string|max:100',
            'company_group_code' => 'nullable|string|max:50',
            'mode_of_communication' => 'nullable|string|max:50',
            'source_of_acquisition' => 'nullable|string|max:100',

            // Corporate-specific fields (only required if corporate customer)
            'company_name' => 'nullable|string|max:255',
            'sector' => 'nullable|string|max:100',
            'commencement_date' => 'nullable|date',
            'number_of_employees' => 'nullable|integer',
            'corporate_telephone' => 'nullable|string|max:20',
            'corporate_email' => 'nullable|email|max:255',
            'headquarters_address' => 'nullable|string|max:1000',
            'primary_contact_name' => 'nullable|string|max:255',
            'primary_contact_position' => 'nullable|string|max:255',
            'primary_contact_number' => 'nullable|string|max:20',
            'primary_contact_email' => 'nullable|email|max:255',
            'primary_contact_address' => 'nullable|string|max:500'
        ];

        // Add corporate-specific validation rules if it's a corporate customer
        if ($is_corporate) {
            $corporateRules = [
                'company_name' => 'required|string|max:255',
                'corporate_telephone' => 'required|string',
                'corporate_email' => [
                    'required',
                    'email',
                    Rule::unique('customers', 'corporate_email')->where(function ($query) use ($company_id) {
                        return $query->where('company_id', $company_id);
                    })
                ],
                'headquarters_address' => 'required|string',
                'commencement_date' => 'required|date',
                'sector' => 'required|string',
                'number_of_employees' => 'required|integer',
                'primary_contact_name' => 'required|string|max:255',
                'primary_contact_position' => 'required|string|max:255',
                'primary_contact_number' => 'required|string',
                'primary_contact_email' => 'required|email',
                'primary_contact_address' => 'required|string',
                'source_of_acquisition' => 'required|string',
                'change_type' => 'required|string',
                'assigned_branch' => 'required|string',
                'channel' => 'required|string',
                'company_group_code' => 'required|string',
                'mode_of_communication' => 'required|string'
            ];
            $validationRules = array_merge($validationRules, $corporateRules);
        }

        $validator = Validator::make($requestData, $validationRules);

        if ($validator->fails()) {
            Log::error('Validation failed', [
                'errors' => $validator->errors()->toArray(),
                'request_data' => $requestData
            ]);
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Log::info('Creating customer with validated data', ['data' => $requestData]);
            $customer = Customer::create($requestData);
            Log::info('Customer created successfully', [
                'customer_id' => $customer->id,
                'customer_type' => $requestData['customer_type']
            ]);

            // Log activity
            ActivityLogController::logActivity(
                'create',
                Customer::class,
                $customer->id,
                'Created new ' . $requestData['customer_type'] . ' customer',
                $requestData
            );

            return response()->json([
                'success' => true,
                'message' => "Customer '{$customer->name}' created successfully",
                'redirect_url' => route('company.customers.index')
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating customer', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $requestData
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create customer',
                'debug_message' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Store an individual customer.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function storeIndividual(Request $request)
    {
        // Get the current company ID from the session
        $companyId = session('selected_company_id');
        Log::info('Starting individual customer creation', ['request_data' => $request->all()]);
        
        // Merge company_id into the request data
        $requestData = array_merge($request->all(), [
            'company_id' => $companyId,
            'customer_type' => Customer::TYPE_INDIVIDUAL
        ]);
        Log::info('Merged request data', ['merged_data' => $requestData]);

        $validator = Validator::make($requestData, [
            // Personal Details
            'title' => 'nullable|string|max:10',
            'full_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('customers')->where(function ($query) use ($companyId) {
                    return $query->where('company_id', $companyId);
                })
            ],
            'primary_contact' => 'required|string|max:20',
            'postal_address' => 'required|string|max:500',
            'business_profession' => 'required|string|max:100',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'marital_status' => 'nullable|string|max:50',
            'nearest_landmark' => 'nullable|string|max:255',

            // Customer Management
            'source_of_acquisition' => 'nullable|string|max:100',
            'customer_category' => 'required|string|in:Standard,VIP,HVC',
            'value' => 'required|numeric',
            'change_type' => 'nullable|string|max:50',
            'assign_branch' => 'required|string|max:100',
            'channel' => 'nullable|string|max:100',
            'mode_of_communication' => 'nullable|string|max:50',
            'company_id' => 'required|exists:company_profiles,id',
            'customer_type' => 'required|in:individual,corporate'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            Log::error('Individual customer validation failed', [
                'errors' => $validator->errors()->toArray(),
                'request_data' => $requestData
            ]);
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Prepare data for individual customer
            $customerData = array_merge($requestData, [
                'name' => $requestData['full_name'],
                'phone' => $requestData['primary_contact'],
                'address' => $requestData['postal_address'],
                'customer_type' => 'individual',
                'value' => $requestData['value'],
                'status' => 'Active'
            ]);

            Log::info('Creating individual customer with validated data', ['data' => $customerData]);
            $customer = Customer::create($customerData);
            Log::info('Individual customer created successfully', [
                'customer_id' => $customer->id,
                'customer_name' => $customer->name
            ]);

            // Log activity
            ActivityLogController::logActivity(
                'create',
                Customer::class,
                $customer->id,
                'Created new individual customer',
                $customerData
            );

            return response()->json([
                'success' => true,
                'message' => "Customer '{$customer->name}' created successfully",
                'redirect_url' => route('company.customers.index')
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating individual customer', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $customerData
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create customer',
                'debug_message' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $customer = Customer::where('id', $id)
                ->where('company_id', session('selected_company_id'))
                ->firstOrFail();

            // Determine which view to render based on customer type
            $view = $customer->customer_type === 'individual' 
                ? 'company.CRM.edit-individual-customer' 
                : 'company.CRM.edit-customer';

            return view($view, compact('customer'));
        } catch (\Exception $e) {
            Log::error('Error viewing customer edit:', [
                'customer_id' => $id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Error viewing customer edit');
        }
    }

    /**
     * Update the specified customer.
     */
    public function update(Request $request, $id)
    {
        try {
            $companyId = session('selected_company_id');

            // Find the customer with company ID constraint
            $customer = Customer::where('company_id', $companyId)
                ->where('id', $id)
                ->firstOrFail();

            // Get validated data
            $data = $request->validate([
                // Personal Details
                'name' => 'required|string|max:255',
                'title' => 'nullable|string|max:10',
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('customers')->where(function ($query) use ($companyId) {
                        return $query->where('company_id', $companyId);
                    })->ignore($customer->id)
                ],
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:500',
                'date_of_birth' => 'nullable|date',
                'gender' => 'nullable|string|in:Male,Female,Other',
                'marital_status' => 'nullable|string|max:50',
                'occupation' => 'nullable|string|max:100',

                // Customer Management
                'customer_category' => 'required|string|in:Standard,VIP,HVC',
                'value' => 'nullable|numeric|min:0',
                'status' => 'required|string|in:Active,Inactive,Pending,On Hold,Suspended,Blacklisted,VIP,Regular,New',
                'change_type' => 'nullable|string|max:50',
                'assigned_branch' => 'nullable|string|max:100',
                'channel' => 'nullable|string|max:100',
                'company_group_code' => 'nullable|string|max:50',
                'mode_of_communication' => 'nullable|string|max:50',
                'source_of_acquisition' => 'nullable|string|max:100',

                // Corporate-specific fields (only required if corporate customer)
                'company_name' => 'nullable|string|max:255',
                'sector' => 'nullable|string|max:100',
                'commencement_date' => 'nullable|date',
                'number_of_employees' => 'nullable|integer|min:0',
                'corporate_telephone' => 'nullable|string|max:20',
                'corporate_email' => 'nullable|email|max:255',
                'headquarters_address' => 'nullable|string|max:1000',
                'primary_contact_name' => 'nullable|string|max:255',
                'primary_contact_position' => 'nullable|string|max:255',
                'primary_contact_number' => 'nullable|string|max:20',
                'primary_contact_email' => 'nullable|email|max:255',
                'primary_contact_address' => 'nullable|string|max:500'
            ], [
                'email.unique' => 'This email is already registered for your company.',
                'customer_category.in' => 'Please select a valid category.',
                'status.in' => 'Please select a valid status.',
                'company_name.required' => 'The company name field is required.',
                'headquarters_address.required' => 'The headquarters address field is required.'
            ]);

            // If it's a corporate customer, validate required corporate fields
            if ($customer->customer_type === 'corporate') {
                $requiredCorporateFields = [
                    'company_name' => 'required',
                    'sector' => 'required',
                    'headquarters_address' => 'required'
                ];

                $validator = Validator::make($data, $requiredCorporateFields);
                if ($validator->fails()) {
                    throw new \Illuminate\Validation\ValidationException($validator);
                }
            }

            // Store the original data before update
            $oldData = $customer->getOriginal();

            // Update the customer
            $customer->update($data);

            // Log the update activity with detailed changes
            $changes = array_diff_assoc($data, $oldData);
            ActivityLogController::logActivity(
                'update',
                Customer::class,
                $customer->id,
                'Customer updated: ' . $customer->name,
                [
                    'changes' => $changes,
                    'old_data' => $oldData,
                    'new_data' => $data
                ]
            );

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer updated successfully!',
                    'data' => $customer->fresh()
                ]);
            }

            return redirect()->route('company.customers.index')
                ->with('success', 'Customer updated successfully.')
                ->with('activeTab', 'customer');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error updating customer:', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput()
                ->with('activeTab', 'customer');

        } catch (\Exception $e) {
            Log::error('Error updating customer:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while updating the customer.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'An error occurred while updating the customer: ' . $e->getMessage())
                ->withInput()
                ->with('activeTab', 'customer');
        }
    }

    /**
     * Permanently delete a customer
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            // Find the customer
            $customer = Customer::withTrashed()->findOrFail($id);

            // Check if the customer belongs to the current company
            $currentCompanyId = session('selected_company_id');
            if ($customer->company_id !== $currentCompanyId) {
                // If it's an AJAX request, return JSON
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You are not authorized to delete this customer.'
                    ], 403);
                }

                // If it's a regular request, redirect with error
                return redirect()->route('company.customers.index')
                    ->with('error', 'You are not authorized to delete this customer.')
                    ->with('activeTab', 'customer');
            }

            // Store customer data before soft deletion
            $customerName = $customer->name;
            $customerData = $customer->toArray();

            // Soft delete the customer
            $customer->delete();

            // Log the delete activity
            try {
                ActivityLogController::logActivity(
                    'soft_delete',
                    Customer::class,
                    $id,
                    'Customer soft deleted: ' . $customerName,
                    ['soft_deleted_customer_data' => $customerData]
                );
            } catch (\Exception $logError) {
                // Log the error but don't interrupt the main process
                Log::error('Failed to log soft delete activity', [
                    'error' => $logError->getMessage(),
                    'customer_id' => $id
                ]);
            }

            // If it's an AJAX request, return JSON
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Customer '{$customerName}' has been soft deleted.",
                    'redirect_url' => route('company.customers.index')
                ]);
            }

            // If it's a regular request, redirect with success
            return redirect()->route('company.customers.index')
                ->with('success', "Customer '{$customerName}' has been soft deleted.")
                ->with('activeTab', 'customer');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Customer soft deletion failed', [
                'error' => $e->getMessage(),
                'customer_id' => $id,
                'user_id' => Auth::id()
            ]);

            // If it's an AJAX request, return JSON
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to soft delete customer. Please try again.'
                ], 500);
            }

            // If it's a regular request, redirect with error
            return redirect()->route('company.customers.index')
                ->with('error', 'Failed to soft delete customer. Please try again.')
                ->with('activeTab', 'customer');
        }
    }

    /**
     * Display the specified customer.
     *
     * @param string $id
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $customer = Customer::where('id', $id)
                ->where('company_id', session('selected_company_id'))
                ->firstOrFail();

            // Determine which view to render based on customer type
            $view = $customer->customer_type === 'individual' 
                ? 'company.CRM.show-individual-customer' 
                : 'company.CRM.show-customer';

            return view($view, compact('customer'));
        } catch (\Exception $e) {
            Log::error('Error viewing customer:', [
                'customer_id' => $id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Error viewing customer details');
        }
    }

    /**
     * Search customers with filters or modal search.
     */
    public function search(Request $request)
    {
        $searchTerm = $request->input('customer_search');
        
        if (empty($searchTerm)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please enter a search term'
            ], 400);
        }

        try {
            // Get the current company
            $companyId = session('selected_company_id');
            if (!$companyId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Company ID not found'
                ], 400);
            }

            Log::info('Customer search initiated', [
                'search_term' => $searchTerm,
                'company_id' => $companyId
            ]);

            // First, check if we can find any customers with this company ID
            $allCustomers = Customer::where('company_id', $companyId)->get();
            Log::info('Total customers for company', [
                'count' => $allCustomers->count(),
                'company_id' => $companyId
            ]);

            // Perform a search by name
            $query = Customer::where('company_id', $companyId)
                ->where(function($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('company_name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('phone', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('sector', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('status', 'LIKE', "%{$searchTerm}%");
                })
                ->limit(10); // Limit to 10 results to prevent overwhelming the UI

            Log::info('Customer search query built', [
                'query' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);

            // Execute the query
            $customers = $query->get();

            // Log the actual results
            Log::info('Customer search results', [
                'found' => $customers->count(),
                'search_term' => $searchTerm,
                'company_id' => $companyId,
                'customers' => $customers->map(function($customer) {
                    return [
                        'id' => $customer->id,
                        'name' => $customer->name,
                        'company_id' => $customer->company_id
                    ];
                })->toArray()
            ]);

            // Transform results for frontend
            $formattedCustomers = $customers->map(function($customer) {
                // Determine the correct address based on customer type
                $address = $customer->isCorporate() ? $customer->headquarters_address : $customer->address;
                
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'email' => $customer->email,
                    'company_name' => $customer->company_name,
                    'status' => $customer->status,
                    'customer_type' => $customer->customer_type,
                    'customer_category' => $customer->customer_category,
                    'source_of_acquisition' => $customer->source_of_acquisition,
                    'value' => $customer->value,
                    'address' => $address,
                    'is_corporate' => $customer->isCorporate(),
                    'is_individual' => $customer->isIndividual(),
                    'primary_contact_number' => $customer->primary_contact_number,
                    'corporate_email' => $customer->corporate_email,
                    'postal_address' => $customer->postal_address,
                    'headquarters_address' => $customer->headquarters_address
                ];
            });

            return response()->json([
                'status' => 'success',
                'customers' => $formattedCustomers,
                'total' => $customers->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Customer search failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'search_term' => $searchTerm,
                'company_id' => $companyId
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while searching for customers'
            ], 500);
        }
    }

    /**
     * Get data for DataTables.
     */
    public function getData(Request $request)
    {
        try {
            $companyId = session('selected_company_id');
            
            $query = Customer::where('company_id', $companyId);

            // Get total count before filtering
            $totalRecords = $query->count();

            // Apply search
            if ($request->has('search') && !empty($request->get('search')['value'])) {
                $searchValue = $request->get('search')['value'];
                $query->where(function($q) use ($searchValue) {
                    $q->where('name', 'like', "%{$searchValue}%")
                        ->orWhere('email', 'like', "%{$searchValue}%")
                        ->orWhere('phone', 'like', "%{$searchValue}%")
                        ->orWhere('sector', 'like', "%{$searchValue}%")
                        ->orWhere('status', 'like', "%{$searchValue}%");
                });
            }

            // Get filtered count
            $filteredRecords = $query->count();

            // Apply order
            if ($request->has('order')) {
                $order = $request->get('order')[0];
                $columnIndex = $order['column'];
                $columnName = $request->get('columns')[$columnIndex]['data'];
                $direction = $order['dir'];

                if ($columnName && $columnName !== 'actions') {
                    $query->orderBy($columnName, $direction);
                }
            } else {
                $query->orderBy('created_at', 'desc');
            }

            // Apply pagination
            $start = $request->get('start', 0);
            $length = $request->get('length', 10);
            $query->skip($start)->take($length);

            // Get results
            $customers = $query->get();

            // Format data for DataTables
            $data = $customers->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'company' => $customer->company,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'address' => $customer->address,
                    'sector' => $customer->sector,
                    'category' => $customer->category,
                    'status' => $customer->status,
                    'value' => $customer->value,
                ];
            });

            return response()->json([
                'draw' => $request->get('draw'),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting customer data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'draw' => $request->get('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Error retrieving customer data'
            ]);
        }
    }

    /**
     * Get dashboard customer statistics
     *
     * @return array
     */
    public function getDashboardStats()
    {
        try {
            // Get the current company ID
            $companyId = session('selected_company_id');

            // Total accounts (including soft deleted)
            $totalAccounts = Customer::withTrashed()
                ->where('company_id', $companyId)
                ->count();

            // Active accounts
            $activeAccounts = Customer::where('company_id', $companyId)
                ->where('status', 'Active')
                ->count();

            // Inactive accounts (only those with 'Inactive' status)
            $inactiveAccountsQuery = Customer::where('company_id', $companyId)
                ->where('status', 'Inactive');

            // Log detailed query information
            Log::info('Inactive Accounts Debug', [
                'company_id' => $companyId,
                'query_sql' => $inactiveAccountsQuery->toSql(),
                'query_bindings' => $inactiveAccountsQuery->getBindings(),
                'inactive_accounts_list' => $inactiveAccountsQuery->get(['id', 'name', 'status'])->toArray()
            ]);

            $inactiveAccounts = $inactiveAccountsQuery->count();

            // Add logging to help diagnose
            Log::info('Dashboard Inactive Accounts Calculation', [
                'company_id' => $companyId,
                'inactive_statuses' => ['Inactive'],
                'inactive_accounts_count' => $inactiveAccounts,
                'total_accounts' => $totalAccounts,
                'detailed_status_breakdown' => Customer::where('company_id', $companyId)
                    ->select('status', DB::raw('COUNT(*) as count'))
                    ->groupBy('status')
                    ->get()
                    ->toArray()
            ]);

            // Total revenue generated (sum of customer values)
            $totalRevenue = Customer::where('company_id', $companyId)
                ->sum('value');

            return [
                'total_accounts' => $totalAccounts,
                'active_accounts' => $activeAccounts,
                'inactive_accounts' => $inactiveAccounts,
                'total_revenue' => $totalRevenue
            ];
        } catch (\Exception $e) {
            // Log the error
            Log::error('Failed to fetch dashboard customer stats', [
                'error' => $e->getMessage(),
                'company_id' => $companyId ?? 'N/A'
            ]);

            // Return default values in case of error
            return [
                'total_accounts' => 0,
                'active_accounts' => 0,
                'inactive_accounts' => 0,
                'total_revenue' => 0
            ];
        }
    }

    /**
     * Handle bulk upload of customers from a CSV file
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function bulkUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        Log::info('Bulk Upload Started', [
            'user_id' => auth()->id(),
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'file_mime' => $file->getMimeType(),
            'expects_json' => $request->expectsJson(),
            'file_path' => $file->getPathname()
        ]);

        $csv = array_map('str_getcsv', file($file));
        $headers = array_shift($csv);
        
        Log::info('Bulk Upload Headers', [
            'headers' => $headers,
            'file_path' => $file->getPathname(),
            'file_name' => $file->getClientOriginalName()
        ]);

        // Determine customer type based on headers
        $isCorporate = in_array('Company Name', $headers);
        Log::info('Customer type detection', [
            'is_corporate' => $isCorporate,
            'headers' => $headers
        ]);

        $companyId = session('selected_company_id');
        Log::info('Company ID from session', ['company_id' => $companyId]);

        $successCount = 0;
        $failureCount = 0;
        $errorMessages = [];

        foreach ($csv as $rowNumber => $row) {
            try {
                if (empty($row)) continue;

                Log::info('Raw Row Data', [
                    'row_number' => $rowNumber + 1,
                    'row_contents' => $row,
                    'row_count' => count($row)
                ]);

                // Map the row data to an associative array using headers
                $data = array_combine($headers, $row);
                
                // Map the data to our customer model structure
                $mappedData = $this->mapCustomerData($data, $isCorporate, $companyId);

                // Check for existing customer
                $existingCustomer = Customer::where('company_id', $companyId)
                    ->where(function ($query) use ($isCorporate, $mappedData) {
                        if ($isCorporate) {
                            $query->where('company_name', $mappedData['company_name']);
                        } else {
                            $query->where('name', $mappedData['name']);
                        }
                    })
                    ->first();

                if ($existingCustomer) {
                    Log::warning('Duplicate customer found in row ' . $rowNumber, [
                        'existing_customer_id' => $existingCustomer->id,
                        'row_data' => $mappedData
                    ]);

                    // Update existing customer
                    $existingCustomer->update($mappedData);
                    Log::info('Customer updated successfully', [
                        'customer_id' => $existingCustomer->id,
                        'row_number' => $rowNumber + 1,
                        'customer_data' => $mappedData
                    ]);
                    
                    // Log activity for customer update
                    ActivityLogController::logActivity(
                        'update',
                        Customer::class,
                        $existingCustomer->id,
                        'Customer updated via bulk upload',
                        ['row_number' => $rowNumber + 1]
                    );
                } else {
                    // Create new customer
                    $customer = Customer::create($mappedData);
                    Log::info('Customer saved successfully', [
                        'customer_id' => $customer->id,
                        'row_number' => $rowNumber + 1,
                        'customer_data' => $mappedData
                    ]);
                    
                    // Log activity for customer creation
                    ActivityLogController::logActivity(
                        'create',
                        Customer::class,
                        $customer->id,
                        'Customer created via bulk upload',
                        ['row_number' => $rowNumber + 1]
                    );
                }

                $successCount++;
            } catch (\Exception $e) {
                Log::error('Error processing row ' . $rowNumber, [
                    'error' => $e->getMessage(),
                    'row_data' => $row
                ]);
                $failureCount++;
                $errorMessages[] = 'Error in row ' . ($rowNumber + 1) . ': ' . $e->getMessage();
            }
        }

        Log::info('Bulk Customer Import Completed', [
            'total_rows' => count($csv),
            'successful_rows' => $successCount,
            'failed_rows' => $failureCount
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => $failureCount === 0,
                'total' => count($csv),
                'successful' => $successCount,
                'failed' => $failureCount,
                'errors' => $errorMessages,
                'message' => "Bulk upload completed.\n" .
                            "Total rows processed: " . count($csv) . "\n" .
                            "New customers created: " . ($successCount - count($errorMessages)) . "\n" .
                            "Customers updated: " . count($errorMessages) . "\n" .
                            "Failed rows: " . $failureCount
            ]);
        }

        return redirect()->back()
            ->with('success', 'Bulk upload completed successfully. ' . 
                "Total rows processed: " . count($csv) . "\n" .
                "New customers created: " . ($successCount - count($errorMessages)) . "\n" .
                "Customers updated: " . count($errorMessages) . "\n" .
                "Failed rows: " . $failureCount)
            ->with('errors', $errorMessages);
    }

    private function mapCustomerData(array $data, bool $isCorporate, int $companyId): array
    {
        $mappedData = [
            'company_id' => $companyId,
            'customer_type' => $isCorporate ? 'corporate' : 'individual',
            'status' => 'Active',
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ];

        if ($isCorporate) {
            $mappedData['company_name'] = $data['Company Name'] ?? '';
            $mappedData['corporate_email'] = $data['Corporate Email'] ?? '';
            $mappedData['corporate_telephone'] = $data['Corporate Phone'] ?? '';
            $mappedData['headquarters_address'] = $data['Headquarters Address'] ?? '';
            
            // Handle commencement_date - use current date if not provided
            $commencementDate = $data['Commencement Date'] ?? '';
            $mappedData['commencement_date'] = $commencementDate ? 
                Carbon::parse($commencementDate)->format('Y-m-d') :
                Carbon::now()->format('Y-m-d');
            
            $mappedData['sector'] = $data['Sector'] ?? '';
            $numberEmployees = $data['Number of Employees'] ?? '';
            $mappedData['number_of_employees'] = filter_var($numberEmployees, FILTER_VALIDATE_INT) !== false 
                ? $numberEmployees 
                : null;
            
            $mappedData['primary_contact_name'] = $data['Primary Contact Name'] ?? '';
            $mappedData['primary_contact_position'] = $data['Primary Contact Position'] ?? '';
            $mappedData['primary_contact_number'] = $data['Primary Contact Phone'] ?? '';
            $mappedData['primary_contact_email'] = $data['Primary Contact Email'] ?? '';
            $mappedData['primary_contact_address'] = $data['Primary Contact Address'] ?? '';
            
            $mappedData['company_group_code'] = $data['Company Group Code'] ?? '';
            $mappedData['source_of_acquisition'] = $data['Source of Acquisition'] ?? '';
            $mappedData['mode_of_communication'] = $data['Mode of Communication'] ?? '';
            $mappedData['channel'] = $data['Channel'] ?? '';
            $mappedData['customer_category'] = $data['Customer Category'] ?? '';
            $mappedData['value'] = $data['Value'] ?? '';
            $mappedData['status'] = $data['Status'] ?? 'Active';
            
            // Map fields that were previously missing
            $mappedData['email'] = $data['Corporate Email'] ?? '';
            $mappedData['phone'] = $data['Corporate Phone'] ?? '';
            $mappedData['change_type'] = $data['Change Type'] ?? '';
            $mappedData['assigned_branch'] = $data['Assigned Branch'] ?? '';
            
            // Copy some fields for search purposes
            $mappedData['name'] = $mappedData['company_name'];
            $mappedData['address'] = $mappedData['headquarters_address'];
        } else {
            // Map individual customer data
            $mappedData['title'] = $data['Title'] ?? '';
            $mappedData['name'] = $data['Full Name'] ?? '';
            $mappedData['gender'] = $data['Gender'] ?? '';
            $mappedData['date_of_birth'] = $data['Date of Birth'] ? 
                Carbon::parse($data['Date of Birth'])->format('Y-m-d') : null;
            $mappedData['marital_status'] = $data['Marital Status'] ?? '';
            $mappedData['email'] = $data['Email'] ?? '';
            $mappedData['phone'] = $data['Phone'] ?? '';
            $mappedData['address'] = $data['Postal Address'] ?? '';
            $mappedData['occupation'] = $data['Business/Profession/Industry'] ?? '';
            
            $mappedData['customer_category'] = $data['Customer Category'] ?? '';
            $mappedData['value'] = $data['Value'] ?? '';
            $mappedData['channel'] = $data['Channel'] ?? '';
            $mappedData['source_of_acquisition'] = $data['Source of Acquisition'] ?? '';
            $mappedData['mode_of_communication'] = $data['Mode of Communication'] ?? '';
            $mappedData['status'] = $data['Status'] ?? 'Active';
        }

        return $mappedData;
    }

    /**
     * Generate a CSV template for bulk customer upload
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadTemplate(Request $request)
    {
        try {
            $type = $request->query('type', 'corporate');
            
            if ($type === 'corporate') {
                $headers = [
                    'Company Name',
                    'Corporate Email',
                    'Corporate Phone',
                    'Headquarters Address',
                    'Commencement Date',
                    'Sector',
                    'Number of Employees',
                    'Primary Contact Name',
                    'Primary Contact Position',
                    'Primary Contact Phone',
                    'Primary Contact Email',
                    'Primary Contact Address',
                    'Source of Acquisition',
                    'Change Type',
                    'Assigned Branch',
                    'Channel',
                    'Company Group Code',
                    'Mode of Communication',
                    'Customer Category',
                    'Value',
                    'Status'
                ];

                $exampleRow = [
                    'Acme Corporation',
                    'info@acme.com',
                    '+1 (555) 123-4567',
                    '123 Business St, Suite 100, City, State, ZIP',
                    '2025-01-01',
                    'Technology',
                    '50',
                    'John Smith',
                    'CEO',
                    '+1 (555) 123-4568',
                    'john.smith@acme.com',
                    '123 Business St, Suite 100, City, State, ZIP',
                    'Referral',
                    'New',
                    'Main Branch',
                    'Direct',
                    'GRP123',
                    'Email',
                    'Standard',
                    '50000',
                    'Active'
                ];

                $filename = 'corporate_customer_bulk_upload_template.csv';
            } else {
                $headers = [
                    'Title',
                    'Full Name',
                    'Gender',
                    'Date of Birth',
                    'Marital Status',
                    'Business/Profession/Industry',
                    'Email',
                    'Phone',
                    'Postal Address',
                    'Customer Category',
                    'Value',
                    'Status',
                    'Channel',
                    'Source of Acquisition',
                    'Mode of Communication'
                ];

                $exampleRow = [
                    'Mr',
                    'John Smith',
                    'Male',
                    '1990-01-01',
                    'Single',
                    'Software Engineer',
                    'john.smith@example.com',
                    '+1 (555) 123-4567',
                    '123 Main St, City, State, ZIP',
                    'Standard',
                    '5000',
                    'Active',
                    'Direct',
                    'Referral',
                    'Email'
                ];

                $filename = 'individual_customer_bulk_upload_template.csv';
            }

            $filepath = storage_path('app/' . $filename);

            $file = fopen($filepath, 'w');
            fputcsv($file, $headers);
            fputcsv($file, $exampleRow);
            fclose($file);

            return response()->download($filepath, $filename, array(
                'Content-Type' => 'text/csv',
            ))->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Error generating customer template:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'An error occurred while generating the template.')
                ->with('activeTab', 'customer');
        }
    }

    /**
     * Get customers data for DataTables.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomersData(Request $request)
    {
        try {
            $companyId = session('selected_company_id');
            
            $query = Customer::where('company_id', $companyId);

            // Apply filters
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            if ($request->has('category')) {
                $query->where('customer_category', $request->category);
            }
            if ($request->has('value_min') && $request->has('value_max')) {
                $query->whereBetween('value', [$request->value_min, $request->value_max]);
            }

            // Search
            if ($request->has('search')) {
                $searchTerm = $request->search['value'];
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%")
                      ->orWhere('company_name', 'like', "%{$searchTerm}%")
                      ->orWhere('email', 'like', "%{$searchTerm}%")
                      ->orWhere('phone', 'like', "%{$searchTerm}%");
                });
            }

            // Total records
            $totalRecords = $query->count();

            // Apply ordering
            if ($request->has('order')) {
                $order = $request->order[0];
                $columnIndex = $order['column'];
                $columnName = $request->columns[$columnIndex]['data'];
                $direction = $order['dir'];

                if ($columnName && $columnName !== 'actions') {
                    $query->orderBy($columnName, $direction);
                }
            }

            // Pagination
            $start = $request->start;
            $length = $request->length;
            $customers = $query->skip($start)->take($length)->get();

            return response()->json([
                'draw' => $request->draw,
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $customers->map(function($customer) {
                    return [
                        'id' => $customer->id,
                        'name' => $customer->name,
                        'company_name' => $customer->company_name,
                        'email' => $customer->email,
                        'phone' => $customer->phone,
                        'status' => $customer->status_badge,
                        'value' => CurrencyHelper::format($customer->value),
                        'relationship_age' => $customer->relationship_age,
                        'actions' => view('company.CRM.partials.customer-actions', ['customer' => $customer])->render()
                    ];
                })
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching customers data:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'An error occurred while fetching customers data.'
            ], 500);
        }
    }

    /**
     * Export customers data to CSV/Excel.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        try {
            $companyId = session('selected_company_id');
            $format = $request->format ?? 'csv';
            
            $customers = Customer::where('company_id', $companyId)
                ->when($request->status, fn($q) => $q->where('status', $request->status))
                ->when($request->category, fn($q) => $q->where('customer_category', $request->category))
                ->get();

            $headers = [
                'Name', 'Company Name', 'Email', 'Phone', 'Status',
                'Category', 'Value', 'Commencement Date', 'Sector'
            ];

            $data = $customers->map(function($customer) {
                return [
                    $customer->name,
                    $customer->company_name,
                    $customer->email,
                    $customer->phone,
                    $customer->status,
                    $customer->customer_category,
                    CurrencyHelper::format($customer->value),
                    $customer->commencement_date,
                    $customer->sector
                ];
            });

            $filename = 'customers_export_' . now()->format('Y-m-d_His');
            
            if ($format === 'excel') {
                return Excel::download(new CustomersExport($headers, $data), $filename . '.xlsx');
            }

            return response()->streamDownload(function() use ($headers, $data) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $headers);
                foreach ($data as $row) {
                    fputcsv($file, $row);
                }
                fclose($file);
            }, $filename . '.csv', [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"'
            ]);
        } catch (\Exception $e) {
            Log::error('Error exporting customers:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'An error occurred while exporting customers.')
                ->with('activeTab', 'customer');
        }
    }

    /**
     * Get customer statistics.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatistics()
    {
        try {
            $companyId = session('selected_company_id');
            
            $totalCustomers = Customer::where('company_id', $companyId)->count();
            $activeCustomers = Customer::where('company_id', $companyId)->active()->count();
            $vipCustomers = Customer::where('company_id', $companyId)->vip()->count();
            $totalValue = Customer::where('company_id', $companyId)->sum('value');
            
            $recentCustomers = Customer::where('company_id', $companyId)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            $customersByCategory = Customer::where('company_id', $companyId)
                ->select('customer_category', DB::raw('count(*) as total'))
                ->groupBy('customer_category')
                ->get();

            return response()->json([
                'total_customers' => $totalCustomers,
                'active_customers' => $activeCustomers,
                'vip_customers' => $vipCustomers,
                'total_value' => CurrencyHelper::format($totalValue),
                'recent_customers' => $recentCustomers,
                'customers_by_category' => $customersByCategory
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching customer statistics:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'An error occurred while fetching customer statistics.'
            ], 500);
        }
    }
}
