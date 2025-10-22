<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\CompanyPartners;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CompanyPartnersController extends Controller
{
    /**
     * Display a paginated list of all company partners
     */
    public function index()
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return redirect()->route('auth.login')->with('error', 'Company session expired. Please login again.');
            }

            // Fetch partners for the current company
            $partners = CompanyPartners::where('company_id', $companyId)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('company.user-management.partners', compact('partners'));
        } catch (\Exception $e) {
            Log::error('Error fetching partners: ' . $e->getMessage());
            return back()->with('error', 'Unable to fetch partners');
        }
    }

    /**
     * Show the form for creating a new partner
     */
    public function create()
    {
        return view('company.user-management.create-partner');
    }

    /**
     * Store a newly created partner in the database
     */
    public function store(Request $request)
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return redirect()->route('auth.login')->with('error', 'Company session expired. Please login again.');
            }

            // Add company_id and user_id to request
            $request->merge([
                'company_id' => $companyId,
                'user_id' => Auth::id(),
                'status' => true
            ]);

            // Use the validation rules defined in the CompanyPartners model
            $validator = Validator::make($request->all(),
                CompanyPartners::validationRules()
            );

            // Add custom validation for unique phone and email within the same company
            $validator->after(function ($validator) use ($request, $companyId) {
                if ($request->filled('phone')) {
                    $existingPhonePartner = CompanyPartners::where('phone', $request->input('phone'))
                        ->where('company_id', $companyId)
                        ->first();
                    if ($existingPhonePartner) {
                        $validator->errors()->add('phone', 'This telephone number is already registered with another partner.');
                    }
                }

                $existingEmailPartner = CompanyPartners::where('email', $request->input('email'))
                    ->where('company_id', $companyId)
                    ->first();
                if ($existingEmailPartner) {
                    $validator->errors()->add('email', 'This email is already registered with another partner.');
                }
            });

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please correct the errors below.');
            }

            // Create a new partner using validated data
            $partner = CompanyPartners::create($validator->validated());

            return redirect()->route('company.partners.index')
                ->with('success', 'Partner created successfully');
        } catch (\Exception $e) {
            Log::error('Error creating partner: ' . $e->getMessage());
            return back()->with('error', 'Unable to create partner');
        }
    }

    /**
     * Display detailed information about a specific partner
     */
    public function show($id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            $partner = CompanyPartners::where('id', $id)
                ->where('company_id', $companyId)
                ->firstOrFail();

            return view('company.user-management.show-partner', compact('partner'));
        } catch (\Exception $e) {
            Log::error('Error fetching partner details: ' . $e->getMessage());
            return back()->with('error', 'Partner not found');
        }
    }

    /**
     * Show the form for editing an existing partner
     */
    public function edit($id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            $partner = CompanyPartners::where('id', $id)
                ->where('company_id', $companyId)
                ->firstOrFail();

            return view('company.user-management.edit-partner', compact('partner'));
        } catch (\Exception $e) {
            Log::error('Error preparing partner edit: ' . $e->getMessage());
            return back()->with('error', 'Partner not found');
        }
    }

    /**
     * Update partner status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return redirect()->route('auth.login')->with('error', 'Company session expired. Please login again.');
            }

            $partner = CompanyPartners::where('id', $id)
                ->where('company_id', $companyId)
                ->firstOrFail();

            $partner->update([
                'status' => $request->input('status', false)
            ]);

            return redirect()->route('company.partners.index')
                ->with('success', 'Partner status updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating partner status: ' . $e->getMessage());
            return back()->with('error', 'Unable to update partner status');
        }
    }

    /**
     * Update an existing partner's information
     */
    public function update(Request $request, $id)
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('auth.login')->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return redirect()->route('auth.login')->with('error', 'Company session expired. Please login again.');
            }

            $partner = CompanyPartners::where('id', $id)
                ->where('company_id', $companyId)
                ->firstOrFail();

            // Check if this is a status update
            if ($request->has('status') && $request->only(['status', '_token'])) {
                return $this->updateStatus($request, $id);
            }

            $validatedData = $request->validate([
                'company_name' => 'required|string|max:255',
                'contact_person' => 'required|string|max:255',
                'email' => [
                    'required', 
                    'email', 
                    Rule::unique('company_partners', 'email')
                        ->ignore($partner->id)
                        ->where('company_id', $companyId)
                ],
                'phone' => [
                    'nullable', 
                    'string', 
                    Rule::unique('company_partners', 'phone')
                        ->ignore($partner->id)
                        ->where('company_id', $companyId)
                ]
            ]);

            $partner->update($validatedData);

            return redirect()->route('company.partners.index')
                ->with('success', 'Partner updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating partner: ' . $e->getMessage());
            return back()->with('error', 'Unable to update partner');
        }
    }

    /**
     * Remove a specific partner from the database
     */
    public function destroy($id)
    {
        try {
            $companyId = Session::get('selected_company_id');
            $partner = CompanyPartners::where('id', $id)
                ->where('company_id', $companyId)
                ->firstOrFail();

            $partner->delete();

            return redirect()->route('company.partners.index')
                ->with('success', 'Partner deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting partner: ' . $e->getMessage());
            return back()->with('error', 'Unable to delete partner');
        }
    }

    /**
     * Download CSV template for bulk partner upload
     */
    public function downloadTemplate()
    {
        try {
            $filename = 'Partner Bulk Upload Template.csv';
            $tempFile = tempnam(sys_get_temp_dir(), $filename);
            $handle = fopen($tempFile, 'w');

            // Write the header row
            $headers = ['Company Name', 'Contact Person', 'Email', 'Phone'];
            fputcsv($handle, $headers);

            // Write an example row
            $exampleRow = [
                'Example Company Ltd',
                'John Doe',
                'john.doe@example.com',
                '0243000000'
            ];
            fputcsv($handle, $exampleRow);

            fclose($handle);

            return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Error creating template: ' . $e->getMessage());
            return back()->with('error', 'Unable to download template');
        }
    }

    /**
     * Bulk upload partners from a CSV file
     */
    public function bulkUpload(Request $request)
    {
        try {
            if (!Auth::check()) {
                return redirect()->back()->with('error', 'Please login to continue');
            }

            $companyId = Session::get('selected_company_id');
            if (!$companyId) {
                return redirect()->back()->with('error', 'Company session expired');
            }

            $request->validate([
                'bulk_upload_file' => 'required|file|mimes:csv,txt|max:2048'
            ]);

            $file = $request->file('bulk_upload_file');
            $handle = fopen($file->getPathname(), 'r');
            $headers = fgetcsv($handle);
            
            $requiredHeaders = ['Company Name', 'Contact Person', 'Email', 'Phone'];
            foreach ($requiredHeaders as $requiredHeader) {
                if (!in_array($requiredHeader, $headers)) {
                    return redirect()->back()->with('error', "Missing required column: $requiredHeader");
                }
            }

            $successCount = 0;
            $failedPartners = [];

            while (($row = fgetcsv($handle)) !== false) {
                $partnerData = array_combine($headers, $row);
                $partnerData['company_id'] = $companyId;
                $partnerData['user_id'] = Auth::id();
                $partnerData['status'] = true;

                $validator = Validator::make($partnerData, [
                    'Company Name' => 'required|string|max:255',
                    'Contact Person' => 'required|string|max:255',
                    'Email' => [
                        'required',
                        'email',
                        Rule::unique('company_partners', 'email')->where('company_id', $companyId)
                    ],
                    'Phone' => [
                        'required',
                        Rule::unique('company_partners', 'phone')->where('company_id', $companyId)
                    ]
                ]);

                if ($validator->fails()) {
                    $failedPartners[] = [
                        'data' => $partnerData,
                        'errors' => $validator->errors()->all()
                    ];
                    continue;
                }

                CompanyPartners::create([
                    'company_name' => $partnerData['Company Name'],
                    'contact_person' => $partnerData['Contact Person'],
                    'email' => $partnerData['Email'],
                    'phone' => $partnerData['Phone'],
                    'company_id' => $companyId,
                    'user_id' => Auth::id(),
                    'status' => true
                ]);

                $successCount++;
            }

            fclose($handle);

            $message = "Successfully imported $successCount partners.";
            if (!empty($failedPartners)) {
                $message .= " " . count($failedPartners) . " partners failed to import.";
                Log::warning('Bulk partner import partial failure', ['failed_imports' => $failedPartners]);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Bulk upload error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Bulk upload failed: ' . $e->getMessage());
        }
    }
}
