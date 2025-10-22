@extends('layouts.vertical', ['page_title' => 'GESL Staff Self-Service'])

@section('css')
    @vite([
        'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css',
        'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css',
    ])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- DateRangePicker CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.1.0/daterangepicker.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            -webkit-font-smoothing: antialiased;
            line-height: 1.6;
            color: #2d3748;
        }
        
        .staff-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(33, 40, 50, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        
        .staff-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 2rem 0 rgba(33, 40, 50, 0.15);
        }
        
        .staff-card .card-body {
            padding: 1.5rem;
        }
        
        .staff-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #2c7be5;
        }
        
        .nav-tabs .nav-link {
            color: #4a5568;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            border: none;
            border-bottom: 3px solid transparent;
        }
        
        .nav-tabs .nav-link.active {
            font-weight: 600;
            background-color: transparent;
            border-color:rgb(9, 79, 171);
            color:rgb(10, 94, 204);
        }
        
        .profile-avatar {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .info-label {
            font-weight: 600;
            color: #4a5568;
        }
        
        .info-value {
            color: #2d3748;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Start Content-->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                            <li class="breadcrumb-item active">Staff Self-Service</li>
                        </ol>
                    </div>
                    <h4 class="page-title">GESL Staff Portal</h4>
                </div>
            </div>
        </div>

        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card staff-card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h3 class="mb-1">Welcome back, {{ Auth::user()->name ?? 'Big Ben' }}!</h3>
                            </div>
                            <div class="d-inline-block">
                                <img
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'GESL') }}&background=2c7be5&color=fff&size=128"
                                    class="rounded-circle avatar-xl img-thumbnail bg-white p-2"
                                    alt="Profile Image"
                                    style="border: 2px solid rgb(22, 43, 177);" />
                            </div>
                            
                            <!-- Hidden form for image upload -->
                            <form id="profileImageForm" action="" method="POST" enctype="multipart/form-data" class="d-none">
                                @csrf
                                <input type="file" name="profile_image" id="profileImageInput" accept="image/*">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row">
            <div class="col-md-6 col-xl-3">
                <div class="card staff-card">
                    <div class="card-body text-center">
                        <i class="fas fa-calendar-alt staff-icon text-primary"></i>
                        <h3 class="mt-0">{{ $dashboardStats['leave_days_remaining'] ?? 0 }}</h3>
                        <p class="text-muted mb-0">Leave Days Remaining</p>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" data-bs-toggle="modal" data-bs-target="#leaveDetailsModal">
                            View Details
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-xl-3">
                <div class="card staff-card">
                    <div class="card-body text-center">
                        <i class="fas fa-tasks staff-icon text-success"></i>
                        <h3 class="mt-0">{{ $dashboardStats['assigned_tasks'] ?? 0 }}</h3>
                        <p class="text-muted mb-0">Assigned Tasks</p>
                        <button type="button" class="btn btn-sm btn-outline-success mt-2" data-bs-toggle="modal" data-bs-target="#assignedTasksModal">
                            View Tasks
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-xl-3">
                <div class="card staff-card">
                    <div class="card-body text-center">
                        <i class="fas fa-tasks staff-icon text-warning"></i>
                        <h3 class="mt-0">{{ $dashboardStats['upcoming_trainings'] ?? 0 }}</h3>
                        <p class="text-muted mb-0">Upcoming Trainings</p>
                        <button type="button" class="btn btn-sm btn-outline-warning mt-2" data-bs-toggle="modal" data-bs-target="#upcomingTrainingsModal">
                            View Schedule
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-xl-3">
                <div class="card staff-card">
                    <div class="card-body text-center">
                        <i class="fas fa-file-alt staff-icon text-info"></i>
                        <h3 class="mt-0">{{ $dashboardStats['new_documents'] ?? 0 }}</h3>
                        <p class="text-muted mb-0">New Documents</p>
                        <button type="button" class="btn btn-sm btn-outline-info mt-2" data-bs-toggle="modal" data-bs-target="#documentsModal">
                            View Documents
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row mt-4">
            <div class="col-lg-8">
                <div class="card staff-card">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-bordered mb-3">
                            <li class="nav-item">
                                <a href="#personal-info" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                    <i class="fas fa-user-circle me-1"></i> Personal Information
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#bank-info" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                    <i class="fas fa-university me-1"></i> Bank Information
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#expense-claims" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                    <i class="fas fa-receipt me-1"></i> Expense Claims
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#documents" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                    <i class="fas fa-file-alt me-1"></i> My Documents
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#training" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                    <i class="fas fa-graduation-cap me-1"></i> Training
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Personal Information Tab -->
                            <div class="tab-pane show active" id="personal-info">
                                <form id="personalInfoForm">
                                    @csrf
                                    <div class="row mb-4">
                                        <div class="col-md-3 text-center">
                                            <div class="position-relative d-inline-block">
                                                <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name ?? 'GESL').'&background=2c7be5&color=fff&size=256' }}" 
                                                    class="rounded-circle profile-avatar" 
                                                    id="profilePicturePreview"
                                                    alt="Profile picture">
                                                <button type="button" class="btn btn-primary btn-sm rounded-circle position-absolute bottom-0 end-0" 
                                                        onclick="document.getElementById('profilePicture').click()">
                                                    <i class="fas fa-camera"></i>
                                                </button>
                                                <input type="file" id="profilePicture" class="d-none" accept="image/*">
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="firstName" class="form-label">First Name</label>
                                                        <input type="text" class="form-control" id="firstName" name="first_name" 
                                                            value="{{ $employee->personalInfo->first_name ?? '' }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="middleName" class="form-label">Middle Name</label>
                                                        <input type="text" class="form-control" id="middleName" name="middle_name"
                                                            value="{{ $employee->personalInfo->middle_name ?? '' }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label for="lastName" class="form-label">Last Name</label>
                                                        <input type="text" class="form-control" id="lastName" name="last_name"
                                                            value="{{ $employee->personalInfo->last_name ?? '' }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="dateOfBirth" class="form-label">Date of Birth</label>
                                                        <input type="date" class="form-control" id="dateOfBirth" name="date_of_birth"
                                                            value="{{ $employee->personalInfo->date_of_birth ?? '' }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="gender" class="form-label">Gender</label>
                                                        <select class="form-select" id="gender" name="gender">
                                                            <option value="" disabled {{ !isset($employee->personalInfo->gender) ? 'selected' : '' }}>Select Gender</option>
                                                            <option value="male" {{ ($employee->personalInfo->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                                            <option value="female" {{ ($employee->personalInfo->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contact Information -->
                                    <div class="card mb-4">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0">Contact Information</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="personalEmail" class="form-label">Personal Email</label>
                                                        <input type="email" class="form-control" id="personalEmail" name="personal_email"
                                                            value="{{ $employee->personalInfo->personal_email ?? '' }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="workEmail" class="form-label">Work Email</label>
                                                        <input type="email" class="form-control" id="workEmail" name="email"
                                                            value="{{ Auth::user()->email ?? '' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="primaryPhone" class="form-label">Primary Phone</label>
                                                        <input type="tel" class="form-control" id="primaryPhone" name="primary_phone"
                                                            value="{{ $employee->personalInfo->primary_phone ?? '' }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="secondaryPhone" class="form-label">Secondary Phone</label>
                                                        <input type="tel" class="form-control" id="secondaryPhone" name="secondary_phone"
                                                            value="{{ $employee->personalInfo->secondary_phone ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="residentialAddress" class="form-label">Residential Address</label>
                                                <textarea class="form-control" id="residentialAddress" name="residential_address" rows="2">{{ $employee->personalInfo->residential_address ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Employment Information -->
                                    <div class="card mb-4">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0">Employment Information</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="employeeId" class="form-label">Employee ID</label>
                                                        <input type="text" class="form-control" id="employeeId" 
                                                            value="{{ $employee->staff_id ?? 'Not assigned' }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="department" class="form-label">Department</label>
                                                        <input type="text" class="form-control" id="department" 
                                                            value="{{ $employee->employmentInfo->department ?? 'Not assigned' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="position" class="form-label">Position</label>
                                                        <input type="text" class="form-control" id="position" 
                                                            value="{{ $employee->employmentInfo->position ?? 'Not specified' }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="employmentDate" class="form-label">Employment Date</label>
                                                        <input type="date" class="form-control" id="employmentDate" 
                                                            value="{{ $employee->employmentInfo->join_date ?? '' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Emergency Contact -->
                                    <div class="card mb-4">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0">Emergency Contact</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="emergencyContactName" class="form-label">Full Name</label>
                                                        <input type="text" class="form-control" id="emergencyContactName" 
                                                            name="emergency_contact_name" value="{{ $employee->emergencyContact->name ?? '' }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="emergencyContactPhone" class="form-label">Phone Number</label>
                                                        <input type="tel" class="form-control" id="emergencyContactPhone" 
                                                            name="emergency_contact_phone" value="{{ $employee->emergencyContact->phone ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="emergencyContactRelationship" class="form-label">Relationship</label>
                                                <input type="text" class="form-control" id="emergencyContactRelationship" 
                                                    name="emergency_contact_relationship" value="{{ $employee->emergencyContact->relationship ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="emergencyContactAddress" class="form-label">Address</label>
                                                <textarea class="form-control" id="emergencyContactAddress" name="emergency_contact_address" rows="2">{{ $employee->emergencyContact->address ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <button type="button" class="btn btn-outline-secondary me-2">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Update Information</button>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- Bank Information Tab -->
                            <div class="tab-pane fade" id="bank-info">
                                <h4 class="header-title mb-3">Bank Account Information</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="bankName" class="form-label">Bank Name</label>
                                            <input type="text" class="form-control" id="bankName" name="bank_name" value="{{ $employee->bankInfo->bank_name ?? '' }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="accountNumber" class="form-label">Account Number</label>
                                            <input type="text" class="form-control" id="accountNumber" name="account_number" value="{{ $employee->bankInfo->account_number ?? '' }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="accountName" class="form-label">Account Name</label>
                                            <input type="text" class="form-control" id="accountName" name="account_name" value="{{ $employee->bankInfo->account_name ?? '' }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="branch" class="form-label">Branch</label>
                                            <input type="text" class="form-control" id="branch" name="branch" value="{{ $employee->bankInfo->branch_name ?? '' }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card mt-3">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">Mobile Money Details (Optional)</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="mobileMoneyProvider" class="form-label">Mobile Money Provider</label>
                                                    <input type="text" class="form-control" id="mobileMoneyProvider" name="mobile_money_provider" value="{{ $employee->bankInfo->mobile_money_provider ?? '' }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="mobileMoneyNumber" class="form-label">Mobile Money Number</label>
                                                    <input type="text" class="form-control" id="mobileMoneyNumber" name="mobile_money_number" value="{{ $employee->bankInfo->mobile_money_number ?? '' }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="mobileMoneyName" class="form-label">Account Name</label>
                                                    <input type="text" class="form-control" id="mobileMoneyName" name="mobile_money_name" value="{{ $employee->bankInfo->mobile_money_name ?? '' }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-3 text-end">
                                    <button type="button" class="btn btn-primary" id="editBankInfoBtn">
                                        <i class="fas fa-edit me-1"></i> Edit Bank Information
                                    </button>
                                    <button type="button" class="btn btn-success d-none" id="saveBankInfoBtn">
                                        <i class="fas fa-save me-1"></i> Save Changes
                                    </button>
                                    <button type="button" class="btn btn-secondary d-none" id="cancelEditBankInfoBtn">
                                        <i class="fas fa-times me-1"></i> Cancel
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Documents Tab -->
                            <div class="tab-pane" id="documents">
                                <div class="table-responsive">
                                    <table class="table table-centered table-nowrap table-hover">
                                        <thead>
                                            <tr>
                                                <th>Document Name</th>
                                                <th>Type</th>
                                                <th>Date Uploaded</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Employment Contract</td>
                                                <td>Contract</td>
                                                <td>Jan 15, 2023</td>
                                                <td><span class="badge bg-success">Signed</span></td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-light"><i class="fas fa-download"></i></a>
                                                    <a href="#" class="btn btn-sm btn-light"><i class="fas fa-print"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>NDA Agreement</td>
                                                <td>Legal</td>
                                                <td>Jan 15, 2023</td>
                                                <td><span class="badge bg-success">Signed</span></td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-light"><i class="fas fa-download"></i></a>
                                                    <a href="#" class="btn btn-sm btn-light"><i class="fas fa-print"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Company Handbook 2023</td>
                                                <td>Policy</td>
                                                <td>Mar 1, 2023</td>
                                                <td><span class="badge bg-warning">Pending Review</span></td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-light"><i class="fas fa-eye"></i> Review</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Training Tab -->
                            <div class="tab-pane" id="training">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h4 class="mb-0">My Training & Development</h4>
                                    <div>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookTrainingModal">
                                            <i class="fas fa-plus me-1"></i> Request Training
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#trainingCatalogModal">
                                            <i class="fas fa-book me-1"></i> Browse Catalog
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <!-- Upcoming Trainings -->
                                    <div class="col-md-6 mb-4">
                                        <div class="card h-100">
                                            <div class="card-header bg-primary text-white">
                                                <h5 class="mb-0">Upcoming Trainings</h5>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="list-group list-group-flush">
                                                    <div class="list-group-item">
                                                        <div class="d-flex w-100 justify-content-between">
                                                            <h6 class="mb-1">Cybersecurity Awareness</h6>
                                                            <span class="badge bg-primary">Tomorrow</span>
                                                        </div>
                                                        <small class="text-muted">
                                                            <i class="far fa-calendar me-1"></i> May 15, 2023 | 10:00 AM - 12:00 PM
                                                        </small>
                                                        <div class="progress mt-2" style="height: 5px;">
                                                            <div class="progress-bar bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <div class="d-flex justify-content-between mt-2">
                                                            <small>Not Started</small>
                                                            <button class="btn btn-sm btn-outline-primary">Join Now</button>
                                                        </div>
                                                    </div>
                                                    <div class="list-group-item">
                                                        <div class="d-flex w-100 justify-content-between">
                                                            <h6 class="mb-1">Project Management</h6>
                                                            <span class="badge bg-info">Next Week</span>
                                                        </div>
                                                        <small class="text-muted">
                                                            <i class="far fa-calendar me-1"></i> June 1, 2023 | 9:00 AM - 5:00 PM
                                                        </small>
                                                        <div class="progress mt-2" style="height: 5px;">
                                                            <div class="progress-bar bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <div class="d-flex justify-content-between mt-2">
                                                            <small>Pre-work: 0/3 completed</small>
                                                            <button class="btn btn-sm btn-outline-secondary">View Details</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Required Trainings -->
                                    <div class="col-md-6 mb-4">
                                        <div class="card h-100">
                                            <div class="card-header bg-warning text-dark">
                                                <h5 class="mb-0">Required Trainings</h5>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="list-group list-group-flush">
                                                    <div class="list-group-item">
                                                        <div class="d-flex w-100 justify-content-between">
                                                            <h6 class="mb-1">Anti-Harassment Training</h6>
                                                            <span class="badge bg-danger">Due in 7 days</span>
                                                        </div>
                                                        <small class="text-muted">Annual compliance training</small>
                                                        <div class="progress mt-2" style="height: 5px;">
                                                            <div class="progress-bar bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <button class="btn btn-sm btn-warning mt-2 w-100">Start Training</button>
                                                    </div>
                                                    <div class="list-group-item">
                                                        <div class="d-flex w-100 justify-content-between">
                                                            <h6 class="mb-1">Data Privacy & Security</h6>
                                                            <span class="badge bg-secondary">Due in 30 days</span>
                                                        </div>
                                                        <small class="text-muted">GDPR and data protection training</small>
                                                        <div class="progress mt-2" style="height: 5px;">
                                                            <div class="progress-bar bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <button class="btn btn-sm btn-outline-secondary mt-2 w-100">View Details</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Training History -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Training History</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Course</th>
                                                        <th>Type</th>
                                                        <th>Completed Date</th>
                                                        <th>Status</th>
                                                        <th>Certificate</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Effective Communication</td>
                                                        <td>Soft Skills</td>
                                                        <td>Mar 15, 2023</td>
                                                        <td><span class="badge bg-success">Completed</span></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-download me-1"></i> Download
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Advanced Excel</td>
                                                        <td>Technical</td>
                                                        <td>Feb 28, 2023</td>
                                                        <td><span class="badge bg-success">Completed</span></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-download me-1"></i> Download
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="text-muted">
                                                Showing 1 to 2 of 12 entries
                                            </div>
                                            <nav>
                                                <ul class="pagination pagination-sm mb-0">
                                                    <li class="page-item disabled">
                                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                                    </li>
                                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                                    <li class="page-item">
                                                        <a class="page-link" href="#">Next</a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Expense Claims Tab -->
                            <div class="tab-pane fade" id="expense-claims">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h4 class="mb-0">Expense Claims & Reimbursements</h4>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newExpenseClaimModal">
                                        <i class="fas fa-plus me-1"></i> New Expense Claim
                                    </button>
                                </div>
                                
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="card-title mb-0">Recent Claims</h5>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="expenseFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-filter me-1"></i> Filter
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="expenseFilterDropdown">
                                                    <li><a class="dropdown-item active" href="#" data-filter="all">All Claims</a></li>
                                                    <li><a class="dropdown-item" href="#" data-filter="pending">Pending</a></li>
                                                    <li><a class="dropdown-item" href="#" data-filter="approved">Approved</a></li>
                                                    <li><a class="dropdown-item" href="#" data-filter="rejected">Rejected</a></li>
                                                    <li><a class="dropdown-item" href="#" data-filter="paid">Paid</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Claim #</th>
                                                        <th>Date</th>
                                                        <th>Description</th>
                                                        <th>Category</th>
                                                        <th>Amount</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Claim 1 -->
                                                    <tr data-status="pending">
                                                        <td>#EC-2023-001</td>
                                                        <td>2023-11-15</td>
                                                        <td>Client Meeting - Travel Expenses</td>
                                                        <td>Travel</td>
                                                        <td>$245.75</td>
                                                        <td><span class="badge bg-warning">Pending</span></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-primary view-expense" data-id="1">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-secondary edit-expense" data-id="1">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <!-- Claim 2 -->
                                                    <tr data-status="approved">
                                                        <td>#EC-2023-002</td>
                                                        <td>2023-11-10</td>
                                                        <td>Office Supplies</td>
                                                        <td>Office</td>
                                                        <td>$89.99</td>
                                                        <td><span class="badge bg-success">Approved</span></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-primary view-expense" data-id="2">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <!-- Claim 3 -->
                                                    <tr data-status="paid">
                                                        <td>#EC-2023-003</td>
                                                        <td>2023-10-28</td>
                                                        <td>Business Conference Registration</td>
                                                        <td>Training</td>
                                                        <td>$450.00</td>
                                                        <td><span class="badge bg-info">Paid on 2023-11-05</span></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-primary view-expense" data-id="3">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-success">
                                                                <i class="fas fa-download"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="text-muted">
                                                Showing <span id="showingClaimsCount">3</span> of 8 claims
                                            </div>
                                            <nav>
                                                <ul class="pagination pagination-sm mb-0">
                                                    <li class="page-item disabled">
                                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                                    </li>
                                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                                    <li class="page-item">
                                                        <a class="page-link" href="#">Next</a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Reimbursement Summary</h5>
                                        <div class="row">
                                            <div class="col-md-3 col-6 mb-3">
                                                <div class="p-3 border rounded text-center">
                                                    <h6 class="text-muted mb-1">Pending Approval</h6>
                                                    <h4 class="mb-0 text-warning">$245.75</h4>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6 mb-3">
                                                <div class="p-3 border rounded text-center">
                                                    <h6 class="text-muted mb-1">Approved (Not Paid)</h6>
                                                    <h4 class="mb-0 text-success">$89.99</h4>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6">
                                                <div class="p-3 border rounded text-center">
                                                    <h6 class="text-muted mb-1">Paid This Month</h6>
                                                    <h4 class="mb-0 text-info">$450.00</h4>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6">
                                                <div class="p-3 border rounded text-center">
                                                    <h6 class="text-muted mb-1">YTD Total</h6>
                                                    <h4 class="mb-0 text-primary">$2,345.67</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="card staff-card mb-4">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Quick Actions</h4>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary btn-block text-start mb-2" data-bs-toggle="modal" data-bs-target="#timeOffModal">
                                <i class="fas fa-plus-circle me-2"></i> Request Time Off
                            </button>
                            <button type="button" class="btn btn-outline-success btn-block text-start mb-2" data-bs-toggle="modal" data-bs-target="#approvalsModal">
                                <i class="fas fa-clipboard-check me-2"></i> Pending Approvals
                            </button>
                            <button type="button" class="btn btn-outline-info btn-block text-start mb-2" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                                <i class="fas fa-file-upload me-2"></i> Upload Document
                            </button>
                            <button type="button" class="btn btn-outline-warning btn-block text-start mb-2" data-bs-toggle="modal" data-bs-target="#bookTrainingModal">
                                <i class="fas fa-calendar-plus me-2"></i> Book Training
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-block text-start mb-2" data-bs-toggle="modal" data-bs-target="#payslipsModal">
                                <i class="fas fa-file-invoice-dollar me-2"></i> View Payslips
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-block text-start" data-bs-toggle="modal" data-bs-target="#emergencyContactModal">
                                <i class="fas fa-phone-alt me-2"></i> Emergency Contact
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Benefits Enrollment and Management -->
                <div class="card staff-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="header-title mb-0">Benefits Management</h4>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#benefitsModal">
                                <i class="fas fa-clipboard-check me-1"></i> Manage Benefits
                            </button>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <h5 class="mb-0">Health Insurance</h5>
                                <p class="text-muted mb-0">Active until Dec 31, 2024</p>
                            </div>
                            <span class="badge bg-success">Active</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <h5 class="mb-0">Retirement Plan</h5>
                                <p class="text-muted mb-0">5% employee contribution</p>
                            </div>
                            <span class="badge bg-success">Enrolled</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="mb-0">Dental Coverage</h5>
                                <p class="text-muted mb-0">Not enrolled</p>
                            </div>
                            <span class="badge bg-secondary">Not Enrolled</span>
                        </div>
                        <div class="mt-3">
                            <a href="#" class="btn btn-sm btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#enrollBenefitsModal">
                                <i class="fas fa-plus-circle me-1"></i> Enroll in Benefits
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Time Off Modal -->
    <div class="modal fade" id="timeOffModal" tabindex="-1" aria-labelledby="timeOffModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="timeOffModalLabel">Scheduled Time Off</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                <input type="text" class="form-control datepicker" id="timeOffDateRange" placeholder="Select date range">
                            </div>
                            <div class="dropdown ms-2">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter me-1"></i> Filter
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item active" href="#" data-filter="all">All Time Off</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#" data-filter="upcoming"><i class="fas fa-clock text-primary me-1"></i> Upcoming</a></li>
                                    <li><a class="dropdown-item" href="#" data-filter="approved"><i class="fas fa-check-circle text-success me-1"></i> Approved</a></li>
                                    <li><a class="dropdown-item" href="#" data-filter="pending"><i class="fas fa-hourglass-half text-warning me-1"></i> Pending</a></li>
                                    <li><a class="dropdown-item" href="#" data-filter="annual"><i class="fas fa-umbrella-beach text-info me-1"></i> Annual Leave</a></li>
                                    <li><a class="dropdown-item" href="#" data-filter="sick"><i class="fas fa-procedures text-danger me-1"></i> Sick Leave</a></li>
                                </ul>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#requestTimeOffModal">
                            <i class="fas fa-plus me-1"></i> Request Time Off
                        </button>
                    </div>

                    <div class="time-off-calendar mb-4">
                        <div id="timeOffCalendar">
                            <!-- Calendar will be rendered here by JavaScript -->
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Loading calendar...</p>
                            </div>
                        </div>
                    </div>

                    <div class="time-off-list">
                        <h5 class="mb-3">My Leave Requests</h5>
                        <div class="table-responsive">
                            <table class="table table-hover table-centered align-middle" id="myLeaveRequestsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Type</th>
                                        <th>Date Range</th>
                                        <th>Days</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <div class="d-flex align-items-center justify-content-center py-4">
                                                <div class="spinner-border text-primary me-3" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <span>Loading your leave requests...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#requestTimeOffModal">
                        <i class="fas fa-plus me-1"></i> New Time Off Request
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Request Time Off Modal -->
    <div class="modal fade" id="requestTimeOffModal" tabindex="-1" aria-labelledby="requestTimeOffModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="requestTimeOffModalLabel">Request Time Off</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="timeOffRequestForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="timeOffType" class="form-label">Type of Leave</label>
                            <select class="form-select" id="timeOffType" name="leave_type" required>
                                <option value="" disabled selected>Select leave type</option>
                                <option value="annual">Annual Leave</option>
                                <option value="sick">Sick Leave</option>
                                <option value="personal">Personal Day</option>
                                <option value="maternity">Maternity Leave</option>
                                <option value="paternity">Paternity Leave</option>
                                <option value="emergency">Emergency Leave</option>
                                <option value="bereavement">Bereavement Leave</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="startDate" name="start_date" required>
                            </div>
                            <div class="col-md-6">
                                <label for="endDate" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="endDate" name="end_date" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="fullDay" checked>
                                <label class="form-check-label" for="fullDay">
                                    Full Day
                                </label>
                            </div>
                            <div id="timeRangeContainer" style="display: none;" class="mt-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Start Time</label>
                                        <input type="time" class="form-control" id="startTime">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">End Time</label>
                                        <input type="time" class="form-control" id="endTime">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="timeOffNotes" class="form-label">Reason for Leave</label>
                            <textarea class="form-control" id="timeOffNotes" name="reason" rows="3" placeholder="Please provide a reason for your leave request..." required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="attachment" class="form-label">Attachment (Optional)</label>
                            <input type="file" class="form-control" id="attachment" name="attachment" accept=".pdf,.doc,.docx,.jpg,.png">
                            <div class="form-text">Upload supporting documents (PDF, DOC, DOCX, JPG, PNG) - Max 2MB</div>
                        </div>
                        
                        <div class="alert alert-info" id="leaveBalanceAlert">
                            <i class="fas fa-info-circle me-2"></i>
                            <span id="leaveBalanceText">Loading leave balance...</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i> Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Documents Modal -->
    <div class="modal fade" id="documentsModal" tabindex="-1" aria-labelledby="documentsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="documentsModalLabel">Company Documents</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center">
                            <div class="input-group input-group-sm" style="width: 300px;">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="documentSearch" placeholder="Search documents...">
                            </div>
                            <div class="dropdown ms-2">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter me-1"></i> Filter
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item active" href="#" data-filter="all">All Documents</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#" data-filter="new"><span class="badge bg-danger rounded-pill me-1">New</span> New Documents</a></li>
                                    <li><a class="dropdown-item" href="#" data-filter="policy"><i class="fas fa-file-contract text-primary me-1"></i> Policies</a></li>
                                    <li><a class="dropdown-item" href="#" data-filter="form"><i class="fas fa-file-alt text-success me-1"></i> Forms</a></li>
                                    <li><a class="dropdown-item" href="#" data-filter="handbook"><i class="fas fa-book text-warning me-1"></i> Handbooks</a></li>
                                    <li><a class="dropdown-item" href="#" data-filter="hr"><i class="fas fa-users text-info me-1"></i> HR Documents</a></li>
                                </ul>
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download me-1"></i> Download All
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-centered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAllDocuments">
                                        </div>
                                    </th>
                                    <th>Document Name</th>
                                    <th>Category</th>
                                    <th>Uploaded</th>
                                    <th>Size</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Document 1 -->
                                <tr class="document-row" data-category="new policy" data-name="Employee Handbook 2025">
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input document-checkbox" type="checkbox">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-file-pdf text-danger me-2" style="font-size: 1.5rem;"></i>
                                            <div>
                                                <h6 class="mb-0">Employee Handbook 2025</h6>
                                                <small class="text-muted">Updated company policies and procedures</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-primary">Policy</span></td>
                                    <td>Sep 5, 2025</td>
                                    <td>2.4 MB</td>
                                    <td><span class="badge bg-danger">New</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary me-1" title="View">
                                            <i class="far fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success me-1" title="Download">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" title="Share">
                                            <i class="fas fa-share-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                
                                <!-- Document 2 -->
                                <tr class="document-row" data-category="new form" data-name="Expense Reimbursement Form">
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input document-checkbox" type="checkbox">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-file-word text-primary me-2" style="font-size: 1.5rem;"></i>
                                            <div>
                                                <h6 class="mb-0">Expense Reimbursement Form</h6>
                                                <small class="text-muted">Updated expense reporting process</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-success">Form</span></td>
                                    <td>Sep 8, 2025</td>
                                    <td>1.1 MB</td>
                                    <td><span class="badge bg-danger">New</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary me-1" title="View">
                                            <i class="far fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success me-1" title="Download">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" title="Share">
                                            <i class="fas fa-share-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                
                                <!-- Document 3 -->
                                <tr class="document-row" data-category="handbook" data-name="IT Security Policy">
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input document-checkbox" type="checkbox">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-file-pdf text-danger me-2" style="font-size: 1.5rem;"></i>
                                            <div>
                                                <h6 class="mb-0">IT Security Policy</h6>
                                                <small class="text-muted">Company security guidelines and best practices</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-warning">Handbook</span></td>
                                    <td>Aug 15, 2025</td>
                                    <td>3.2 MB</td>
                                    <td><span class="badge bg-secondary">Viewed</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary me-1" title="View">
                                            <i class="far fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success me-1" title="Download">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" title="Share">
                                            <i class="fas fa-share-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                
                                <!-- Document 4 -->
                                <tr class="document-row" data-category="hr" data-name="Performance Review Template">
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input document-checkbox" type="checkbox">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-file-excel text-success me-2" style="font-size: 1.5rem;"></i>
                                            <div>
                                                <h6 class="mb-0">Performance Review Template</h6>
                                                <small class="text-muted">Q3 2025 performance review form</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-info">HR Document</span></td>
                                    <td>Aug 1, 2025</td>
                                    <td>1.8 MB</td>
                                    <td><span class="badge bg-secondary">Viewed</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary me-1" title="View">
                                            <i class="far fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success me-1" title="Download">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" title="Share">
                                            <i class="fas fa-share-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Showing <span id="showingCount">4</span> of 12 documents
                        </div>
                        <nav aria-label="Document pagination">
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-between w-100">
                        <div>
                            <span class="text-muted me-2">Selected: <span id="selectedCount">0</span></span>
                            <button class="btn btn-sm btn-outline-secondary me-2" id="downloadSelected" disabled>
                                <i class="fas fa-download me-1"></i> Download
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" id="shareSelected" disabled>
                                <i class="fas fa-share-alt me-1"></i> Share
                            </button>
                        </div>
                        <div>
                            <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Close</button>
                            <a href="#documents" class="btn btn-info">View All Documents</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Upcoming Trainings Modal -->
    <div class="modal fade" id="upcomingTrainingsModal" tabindex="-1" aria-labelledby="upcomingTrainingsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="upcomingTrainingsModalLabel">Upcoming Training Sessions</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" placeholder="Search trainings...">
                            </div>
                            <div class="dropdown ms-2">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Filter by Type
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">All Types</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">Technical Skills</a></li>
                                    <li><a class="dropdown-item" href="#">Soft Skills</a></li>
                                    <li><a class="dropdown-item" href="#">Compliance</a></li>
                                    <li><a class="dropdown-item" href="#">Leadership</a></li>
                                </ul>
                            </div>
                        </div>
                        <div>
                            <span class="badge bg-light text-dark">5 Upcoming</span>
                            <span class="badge bg-success ms-1">2 Registered</span>
                        </div>
                    </div>
                    
                    <div class="list-group">
                        <!-- Training Item 1 -->
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <div class="d-flex">
                                    <div class="me-3 text-center" style="width: 60px;">
                                        <div class="bg-light rounded p-2">
                                            <div class="fw-bold text-primary">SEP</div>
                                            <div class="h5 mb-0 fw-bold">15</div>
                                            <small class="text-muted">Mon</small>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Advanced Project Management</h6>
                                        <p class="mb-1 text-muted">
                                            <i class="far fa-clock me-1"></i> 9:00 AM - 4:00 PM
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-map-marker-alt me-1"></i> Training Room A
                                        </p>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-info me-2">Leadership</span>
                                            <span class="badge bg-success me-2">Registered</span>
                                            <small class="text-muted">Instructor: Sarah Johnson</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button class="btn btn-sm btn-outline-primary">View Details</button>
                                    <button class="btn btn-sm btn-outline-secondary" disabled>Registered</button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Training Item 2 -->
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <div class="d-flex">
                                    <div class="me-3 text-center" style="width: 60px;">
                                        <div class="bg-light rounded p-2">
                                            <div class="fw-bold text-primary">SEP</div>
                                            <div class="h5 mb-0 fw-bold">20</div>
                                            <small class="text-muted">Sat</small>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Cybersecurity Awareness</h6>
                                        <p class="mb-1 text-muted">
                                            <i class="far fa-clock me-1"></i> 10:00 AM - 1:00 PM
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-video me-1"></i> Virtual (Zoom)
                                        </p>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-warning me-2">Compliance</span>
                                            <span class="badge bg-success me-2">Registered</span>
                                            <small class="text-muted">Instructor: IT Security Team</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button class="btn btn-sm btn-outline-primary">View Details</button>
                                    <button class="btn btn-sm btn-outline-secondary" disabled>Registered</button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Training Item 3 -->
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <div class="d-flex">
                                    <div class="me-3 text-center" style="width: 60px;">
                                        <div class="bg-light rounded p-2">
                                            <div class="fw-bold text-primary">SEP</div>
                                            <div class="h5 mb-0 fw-bold">25</div>
                                            <small class="text-muted">Thu</small>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Advanced Excel for Business</h6>
                                        <p class="mb-1 text-muted">
                                            <i class="far fa-clock me-1"></i> 1:00 PM - 5:00 PM
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-map-marker-alt me-1"></i> Computer Lab 3
                                        </p>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-primary me-2">Technical Skills</span>
                                            <span class="badge bg-secondary me-2">Not Registered</span>
                                            <small class="text-muted">Instructor: Michael Chen</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button class="btn btn-sm btn-outline-primary">View Details</button>
                                    <button class="btn btn-sm btn-warning">Register Now</button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Training Item 4 -->
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <div class="d-flex">
                                    <div class="me-3 text-center" style="width: 60px;">
                                        <div class="bg-light rounded p-2">
                                            <div class="fw-bold text-primary">OCT</div>
                                            <div class="h5 mb-0 fw-bold">5</div>
                                            <small class="text-muted">Mon</small>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Effective Communication Skills</h6>
                                        <p class="mb-1 text-muted">
                                            <i class="far fa-clock me-1"></i> 9:30 AM - 12:30 PM
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-video me-1"></i> Virtual (Teams)
                                        </p>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-success me-2">Soft Skills</span>
                                            <span class="badge bg-secondary me-2">Not Registered</span>
                                            <small class="text-muted">Instructor: Lisa Wong</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button class="btn btn-sm btn-outline-primary">View Details</button>
                                    <button class="btn btn-sm btn-warning">Register Now</button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Training Item 5 -->
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <div class="d-flex">
                                    <div class="me-3 text-center" style="width: 60px;">
                                        <div class="bg-light rounded p-2">
                                            <div class="fw-bold text-primary">OCT</div>
                                            <div class="h5 mb-0 fw-bold">12</div>
                                            <small class="text-muted">Mon</small>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">New HR Policies & Procedures</h6>
                                        <p class="mb-1 text-muted">
                                            <i class="far fa-clock me-1"></i> 2:00 PM - 3:30 PM
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-map-marker-alt me-1"></i> Conference Room B
                                        </p>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-warning me-2">Compliance</span>
                                            <span class="badge bg-secondary me-2">Required</span>
                                            <small class="text-muted">Instructor: HR Department</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button class="btn btn-sm btn-outline-primary">View Details</button>
                                    <button class="btn btn-sm btn-warning">Register Now</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <a href="#training" class="btn btn-warning">View Full Training Calendar</a>
                </div>
            </div>
        </div>
    </div>
    <!-- container -->
    
    <script>
        // Expense Claims Management Scripts
        document.addEventListener('DOMContentLoaded', function() {
            // Filter expense claims
            const filterButtons = document.querySelectorAll('[data-filter]');
            filterButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const status = this.getAttribute('data-filter');
                    
                    // Update active state
                    document.querySelectorAll('[data-filter]').forEach(btn => {
                        btn.classList.remove('active');
                    });
                    this.classList.add('active');
                    
                    // Filter rows
                    const rows = document.querySelectorAll('tr[data-status]');
                    rows.forEach(row => {
                        if (status === 'all') {
                            row.style.display = '';
                        } else {
                            row.style.display = row.getAttribute('data-status') === status ? '' : 'none';
                        }
                    });
                    
                    // Update showing count
                    const visibleCount = document.querySelectorAll('tr[data-status][style=""]').length;
                    document.getElementById('showingClaimsCount').textContent = visibleCount;
                });
            });
            
            // Handle expense claim form submission
            const expenseForm = document.getElementById('expenseClaimForm');
            if (expenseForm) {
                expenseForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Here you would typically send the form data to the server
                    // For now, we'll just show a success message and close the modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('newExpenseClaimModal'));
                    modal.hide();
                    
                    // Show success message
                    const toast = document.createElement('div');
                    toast.className = 'position-fixed bottom-0 end-0 p-3';
                    toast.style.zIndex = '11';
                    toast.innerHTML = `
                        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="toast-header bg-success text-white">
                                <strong class="me-auto">Success</strong>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                            <div class="toast-body">
                                <i class="fas fa-check-circle me-2"></i> Your expense claim has been submitted successfully!
                            </div>
                        </div>
                    `;
                    document.body.appendChild(toast);
                    
                    // Remove toast after 5 seconds
                    setTimeout(() => {
                        toast.remove();
                    }, 5000);
                    
                    // Reset form
                    this.reset();
                });
            }
            
            // Handle view expense button
            document.addEventListener('click', function(e) {
                if (e.target.closest('.view-expense')) {
                    const expenseId = e.target.closest('.view-expense').getAttribute('data-id');
                    const row = e.target.closest('tr');
                    
                    // Populate view modal with data from the row
                    document.getElementById('viewClaimNumber').textContent = row.cells[0].textContent;
                    document.getElementById('viewExpenseDate').textContent = row.cells[1].textContent;
                    document.getElementById('viewDescription').textContent = row.cells[2].textContent;
                    document.getElementById('viewCategory').textContent = row.cells[3].textContent;
                    document.getElementById('viewAmount').textContent = row.cells[4].textContent;
                    document.getElementById('viewStatus').textContent = row.cells[5].querySelector('.badge').textContent;
                    document.getElementById('viewStatus').className = 'badge ' + row.cells[5].querySelector('.badge').className;
                    
                    // Show the view modal
                    const modal = new bootstrap.Modal(document.getElementById('viewExpenseModal'));
                    modal.show();
                }
            });
            
            // Handle edit expense button
            document.addEventListener('click', function(e) {
                if (e.target.closest('.edit-expense')) {
                    const expenseId = e.target.closest('.edit-expense').getAttribute('data-id');
                    const row = e.target.closest('tr');
                    
                    // Populate the edit form with data from the row
                    const modal = new bootstrap.Modal(document.getElementById('newExpenseClaimModal'));
                    document.getElementById('newExpenseClaimModalLabel').textContent = 'Edit Expense Claim';
                    
                    // Set form values (in a real app, you would fetch the full record)
                    const expenseDate = row.cells[1].textContent;
                    const description = row.cells[2].textContent;
                    const category = row.cells[3].textContent.toLowerCase();
                    const amount = row.cells[4].textContent.replace('$', '');
                    
                    document.getElementById('expenseDate').value = expenseDate;
                    document.getElementById('expenseDescription').value = description;
                    document.getElementById('expenseCategory').value = category;
                    document.getElementById('expenseAmount').value = amount;
                    
                    // Show the edit modal
                    modal.show();
                }
            });
            
            // Print functionality
            document.addEventListener('click', function(e) {
                if (e.target.closest('.print-expense')) {
                    window.print();
                }
            });
            
            // Time Off Management Scripts
            // Toggle time range fields based on full day selection
            const fullDayCheckbox = document.getElementById('fullDay');
            const timeRangeContainer = document.getElementById('timeRangeContainer');
            
            if (fullDayCheckbox && timeRangeContainer) {
                fullDayCheckbox.addEventListener('change', function() {
                    timeRangeContainer.style.display = this.checked ? 'none' : 'block';
                });
            }
            
            // Handle time off filter dropdown
            document.querySelectorAll('#timeOffModal [data-filter]').forEach(filter => {
                filter.addEventListener('click', function(e) {
                    e.preventDefault();
                    const filterValue = this.getAttribute('data-filter');
                    const rows = document.querySelectorAll('.time-off-row');
                    
                    // Update active state in dropdown
                    document.querySelectorAll('#timeOffModal [data-filter]').forEach(item => {
                        item.classList.remove('active');
                    });
                    this.classList.add('active');
                    
                    // Apply filter
                    rows.forEach(row => {
                        const status = row.getAttribute('data-status');
                        const type = row.getAttribute('data-type');
                        const isVisible = 
                            filterValue === 'all' || 
                            filterValue === status || 
                            filterValue === type ||
                            (filterValue === 'upcoming' && status === 'approved' && new Date(row.getAttribute('data-start')) >= new Date());
                        
                        row.style.display = isVisible ? '' : 'none';
                    });
                });
            });
            
            // Initialize date range picker for time off
            if (typeof $.fn.daterangepicker !== 'undefined') {
                $('#timeOffDateRange').daterangepicker({
                    startDate: moment(),
                    endDate: moment().add(1, 'months'),
                    locale: {
                        format: 'MMM D, YYYY'
                    },
                    ranges: {
                        'Today': [moment(), moment()],
                        'This Week': [moment().startOf('week'), moment().endOf('week')],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Next 30 Days': [moment(), moment().add(30, 'days')],
                        'Next 3 Months': [moment(), moment().add(3, 'months')]
                    }
                });
            }
            
            // Load leave balance when modal opens
            const requestTimeOffModal = document.getElementById('requestTimeOffModal');
            if (requestTimeOffModal) {
                requestTimeOffModal.addEventListener('show.bs.modal', function() {
                    console.log('👤 Staff: Request Time Off modal opened');
                    alert('Staff: Request Time Off modal opened - loading leave balance...');
                    loadLeaveBalance();
                });
            }
            
            // Load leave requests when time off modal opens
            const timeOffModal = document.getElementById('timeOffModal');
            if (timeOffModal) {
                timeOffModal.addEventListener('show.bs.modal', function() {
                    console.log('👤 Staff: Time Off modal opened');
                    alert('Staff: Time Off modal opened - loading leave requests...');
                    loadMyLeaveRequests();
                });
            }
            
            // Handle time off form submission
            const timeOffForm = document.getElementById('timeOffRequestForm');
            if (timeOffForm) {
                timeOffForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    console.log('👤 Staff: Time Off form submitted');
                    alert('Staff: Time Off form submitted - validating...');
                    
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalBtnText = submitBtn.innerHTML;
                    
                    // Validate form
                    if (!validateTimeOffForm()) {
                        alert('Staff: Form validation failed!');
                        return;
                    }
                    
                    // Show loading state
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Submitting...';
                    
                    try {
                        // Prepare form data
                        const formData = new FormData(this);
                        console.log('👤 Staff: Preparing form data...');
                        alert('Staff: Preparing form data...');
                        
                        // Get current user's employee ID (this should come from session/auth)
                        const employeeId = getCurrentEmployeeId();
                        console.log('👤 Staff: Employee ID:', employeeId);
                        if (!employeeId) {
                            alert('Staff: Employee ID not found!');
                            throw new Error('Employee ID not found. Please contact HR.');
                        }
                        
                        formData.append('employee_id', employeeId);
                        console.log('👤 Staff: Submitting to API...');
                        alert('Staff: Submitting to API...');
                        
                        // Submit to API
                        const response = await fetch('/company/hr/leaves/store', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: formData
                        });
                        
                        const result = await response.json();
                        console.log('👤 Staff: API Response:', result);
                        
                        if (result.success) {
                            alert('Staff: Leave request submitted successfully!');
                        // Show success message
                            showToast('Leave request submitted successfully!', 'success');
                        
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('requestTimeOffModal'));
                        if (modal) modal.hide();
                        
                        // Reset form
                        this.reset();
                            
                            // Refresh leave data if on the time off tab
                            refreshLeaveData();
                        } else {
                            alert('Staff: Failed to submit leave request: ' + result.message);
                            // Show error message
                            showToast(result.message || 'Failed to submit leave request', 'danger');
                            
                            // Display validation errors if any
                            if (result.errors) {
                                displayFormErrors(this, result.errors);
                            }
                        }
                    } catch (error) {
                        console.error('Error submitting leave request:', error);
                        alert('Staff: Error submitting leave request: ' + error.message);
                        showToast('An error occurred while submitting your request', 'danger');
                    } finally {
                        // Reset button state
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                    }
                });
            }
            
            // Form validation function
            function validateTimeOffForm() {
                const form = document.getElementById('timeOffRequestForm');
                const startDate = form.querySelector('input[name="start_date"]').value;
                const endDate = form.querySelector('input[name="end_date"]').value;
                const leaveType = form.querySelector('select[name="leave_type"]').value;
                const reason = form.querySelector('textarea[name="reason"]').value;
                
                // Clear previous errors
                clearFormErrors(form);
                
                let isValid = true;
                
                if (!leaveType) {
                    showFieldError(form.querySelector('select[name="leave_type"]'), 'Please select a leave type');
                    isValid = false;
                }
                
                if (!startDate) {
                    showFieldError(form.querySelector('input[name="start_date"]'), 'Please select a start date');
                    isValid = false;
                }
                
                if (!endDate) {
                    showFieldError(form.querySelector('input[name="end_date"]'), 'Please select an end date');
                    isValid = false;
                }
                
                if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
                    showFieldError(form.querySelector('input[name="end_date"]'), 'End date must be after start date');
                    isValid = false;
                }
                
                if (!reason.trim()) {
                    showFieldError(form.querySelector('textarea[name="reason"]'), 'Please provide a reason for your leave request');
                    isValid = false;
                }
                
                // Check if dates are in the past
                const today = new Date().toISOString().split('T')[0];
                if (startDate && startDate < today) {
                    showFieldError(form.querySelector('input[name="start_date"]'), 'Start date cannot be in the past');
                    isValid = false;
                }
                
                return isValid;
            }
            
            // Helper functions
            function getCurrentEmployeeId() {
                // This should get the current user's employee ID from the session or a data attribute
                // For now, we'll try to get it from a data attribute on the body or a hidden input
                const employeeId = document.body.getAttribute('data-employee-id') || 
                                 document.querySelector('input[name="employee_id"]')?.value;
                return employeeId;
            }
            
            function showFieldError(field, message) {
                field.classList.add('is-invalid');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = message;
                field.parentNode.appendChild(errorDiv);
            }
            
            function clearFormErrors(form) {
                form.querySelectorAll('.is-invalid').forEach(field => field.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(error => error.remove());
            }
            
            function displayFormErrors(form, errors) {
                clearFormErrors(form);
                Object.keys(errors).forEach(fieldName => {
                    const field = form.querySelector(`[name="${fieldName}"]`);
                    if (field) {
                        showFieldError(field, errors[fieldName][0]);
                    }
                });
            }
            
            function refreshLeaveData() {
                // Refresh the time off calendar and list if they exist
                if (window.calendar) {
                    window.calendar.refetchEvents();
                }
                
                // Refresh leave balance info
                loadLeaveBalance();
            }
            
            function loadLeaveBalance() {
                console.log('👤 Staff: Loading leave balance...');
                alert('Staff: Loading leave balance...');
                
                // Load the user's current leave balance
                fetch('/company/hr/leaves/balance', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    console.log('👤 Staff: Balance API Response:', response);
                    return response.json();
                })
                .then(data => {
                    console.log('👤 Staff: Balance Data:', data);
                    if (data.success) {
                        alert('Staff: Leave balance loaded successfully!');
                        const balanceText = document.getElementById('leaveBalanceText');
                        if (balanceText) {
                            balanceText.innerHTML = `You have <strong>${data.data.annual_remaining || 0} days</strong> of annual leave remaining this year.`;
                        }
                    } else {
                        alert('Staff: Failed to load leave balance: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error loading leave balance:', error);
                    alert('Staff: Error loading leave balance: ' + error.message);
                    const balanceText = document.getElementById('leaveBalanceText');
                    if (balanceText) {
                        balanceText.innerHTML = 'Unable to load leave balance. Please contact HR.';
                    }
                });
            }
            
            // SweetAlert toast notification function
            function showToast(message, type = 'success') {
                const iconMap = {
                    'success': 'success',
                    'error': 'error',
                    'warning': 'warning',
                    'info': 'info'
                };
                
                Swal.fire({
                    title: message,
                    icon: iconMap[type] || 'info',
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }
            
            // Load employee's leave requests
            function loadMyLeaveRequests() {
                console.log('👤 Staff: Loading my leave requests...');
                
                fetch('/company/hr/leaves', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    console.log('👤 Staff: Leave Requests API Response:', response);
                    return response.json();
                })
                .then(data => {
                    console.log('👤 Staff: Leave Requests Data:', data);
                    if (data.success) {
                        updateMyLeaveRequestsTable(data.data.data || []);
                    } else {
                        console.error('Error loading leave requests:', data.message);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to load leave requests: ' + data.message,
                            icon: 'error',
                            timer: 3000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                        showMyLeaveRequestsError('Failed to load leave requests');
                    }
                })
                .catch(error => {
                    console.error('Error loading leave requests:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Error loading leave requests: ' + error.message,
                        icon: 'error',
                        timer: 3000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                    showMyLeaveRequestsError('An error occurred while loading leave requests');
                });
            }
            
            function updateMyLeaveRequestsTable(leaves) {
                const tbody = document.querySelector('#myLeaveRequestsTable tbody');
                if (!tbody) return;

                tbody.innerHTML = '';

                if (leaves.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center">No leave requests found</td></tr>';
                    return;
                }

                leaves.forEach(leave => {
                    const row = createMyLeaveRequestRow(leave);
                    tbody.appendChild(row);
                });
            }
            
            function createMyLeaveRequestRow(leave) {
                const row = document.createElement('tr');
                const startDate = new Date(leave.start_date);
                const endDate = new Date(leave.end_date);
                const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
                
                row.innerHTML = `
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="bg-soft-${getLeaveTypeColor(leave.leave_type)} rounded p-1 me-2">
                                <i class="fas ${getLeaveTypeIcon(leave.leave_type)} text-${getLeaveTypeColor(leave.leave_type)}"></i>
                            </div>
                            <span>${getLeaveTypeLabel(leave.leave_type)}</span>
                        </div>
                    </td>
                    <td>${formatDateRange(startDate, endDate)}</td>
                    <td>${days} ${days === 1 ? 'day' : 'days'}</td>
                    <td><span class="badge ${getStatusBadgeClass(leave.status)}">${getStatusLabel(leave.status)}</span></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-primary me-1" title="View Details" data-action="view-leave" data-leave-id="${leave.id}">
                            <i class="far fa-eye"></i>
                        </button>
                        ${getMyLeaveActionButtons(leave)}
                    </td>
                `;
                return row;
            }
            
            function getMyLeaveActionButtons(leave) {
                let buttons = '';
                
                if (leave.status === 'pending') {
                    buttons += `
                        <button class="btn btn-sm btn-outline-danger" title="Withdraw Request" data-action="withdraw-leave" data-leave-id="${leave.id}">
                            <i class="fas fa-undo"></i>
                        </button>
                    `;
                }
                
                if (leave.status === 'approved' && new Date(leave.start_date) > new Date()) {
                    buttons += `
                        <button class="btn btn-sm btn-outline-warning" title="Cancel Leave" data-action="cancel-leave" data-leave-id="${leave.id}">
                            <i class="far fa-times-circle"></i>
                        </button>
                    `;
                }
                
                return buttons;
            }
            
            function getLeaveTypeColor(type) {
                const colors = {
                    'annual': 'primary',
                    'sick': 'danger',
                    'personal': 'info',
                    'maternity': 'success',
                    'paternity': 'success',
                    'emergency': 'warning',
                    'bereavement': 'secondary',
                    'other': 'dark'
                };
                return colors[type] || 'secondary';
            }
            
            function getLeaveTypeIcon(type) {
                const icons = {
                    'annual': 'fa-umbrella-beach',
                    'sick': 'fa-procedures',
                    'personal': 'fa-user',
                    'maternity': 'fa-baby',
                    'paternity': 'fa-baby',
                    'emergency': 'fa-exclamation-triangle',
                    'bereavement': 'fa-heart',
                    'other': 'fa-calendar'
                };
                return icons[type] || 'fa-calendar';
            }
            
            function getLeaveTypeLabel(type) {
                const labels = {
                    'annual': 'Annual Leave',
                    'sick': 'Sick Leave',
                    'personal': 'Personal Day',
                    'maternity': 'Maternity Leave',
                    'paternity': 'Paternity Leave',
                    'emergency': 'Emergency Leave',
                    'bereavement': 'Bereavement Leave',
                    'other': 'Other'
                };
                return labels[type] || type;
            }
            
            function formatDateRange(startDate, endDate) {
                const startStr = startDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                const endStr = endDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                return `${startStr} - ${endStr}`;
            }
            
            function showMyLeaveRequestsError(message) {
                const tbody = document.querySelector('#myLeaveRequestsTable tbody');
                if (tbody) {
                    tbody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">${message}</td></tr>`;
                }
            }
        });
        
        // Document Management Scripts
        document.addEventListener('DOMContentLoaded', function() {
            // Document search functionality
            const searchInput = document.getElementById('documentSearch');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const rows = document.querySelectorAll('.document-row');
                    rows.forEach(row => {
                        const documentName = row.getAttribute('data-name').toLowerCase();
                        row.style.display = documentName.includes(searchTerm) ? '' : 'none';
                    });
                });
            }
        });
    </script>

    <!-- View Expense Claim Modal -->
    <div class="modal fade" id="viewExpenseModal" tabindex="-1" aria-labelledby="viewExpenseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="viewExpenseModalLabel">Expense Claim Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Claim #</h6>
                            <p id="viewClaimNumber">-</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <span class="badge bg-warning" id="viewStatus">Pending</span>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Date Submitted</h6>
                            <p id="viewDateSubmitted">-</p>
                            
                            <h6 class="text-muted mt-3">Expense Date</h6>
                            <p id="viewExpenseDate">-</p>
                            
                            <h6 class="text-muted mt-3">Category</h6>
                            <p id="viewCategory">-</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Amount</h6>
                            <h4 id="viewAmount">-</h4>
                            
                            <h6 class="text-muted mt-3">Project/Client</h6>
                            <p id="viewProject">-</p>
                            
                            <h6 class="text-muted mt-3">Billable</h6>
                            <p id="viewBillable">-</p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="text-muted">Description</h6>
                        <p id="viewDescription">-</p>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="text-muted">Receipt</h6>
                        <div class="border rounded p-3 text-center" id="receiptPreview">
                            <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
                            <p class="mb-0">receipt_20231115.pdf</p>
                            <a href="#" class="btn btn-sm btn-outline-primary mt-2">
                                <i class="fas fa-download me-1"></i> Download Receipt
                            </a>
                        </div>
                    </div>
                    
                    <div class="timeline mt-4">
                        <h6 class="text-muted mb-3">Status History</h6>
                        <div class="d-flex">
                            <div class="timeline-badge bg-success">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1">Submitted</h6>
                                <p class="text-muted small mb-0">Nov 15, 2023 09:30 AM</p>
                            </div>
                        </div>
                        <div class="d-flex mt-3">
                            <div class="timeline-badge bg-primary">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1">Under Review</h6>
                                <p class="text-muted small mb-0">Nov 15, 2023 10:15 AM</p>
                                <p class="small text-muted mt-1">Assigned to: John Smith (Finance)</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary print-expense">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- New Expense Claim Modal -->
    <div class="modal fade" id="newExpenseClaimModal" tabindex="-1" aria-labelledby="newExpenseClaimModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="newExpenseClaimModalLabel">New Expense Claim</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="expenseClaimForm">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="expenseDate" class="form-label">Expense Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="expenseDate" required>
                            </div>
                            <div class="col-md-6">
                                <label for="expenseCategory" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select" id="expenseCategory" required>
                                    <option value="" selected disabled>Select a category</option>
                                    <option value="travel">Travel</option>
                                    <option value="meals">Meals & Entertainment</option>
                                    <option value="office">Office Supplies</option>
                                    <option value="training">Training & Education</option>
                                    <option value="transportation">Transportation</option>
                                    <option value="accommodation">Accommodation</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="expenseDescription" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="expenseDescription" rows="2" placeholder="Brief description of the expense" required></textarea>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="expenseAmount" class="form-label">Amount <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="expenseAmount" step="0.01" min="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="expenseCurrency" class="form-label">Currency</label>
                                <select class="form-select" id="expenseCurrency">
                                    <option value="GHS" selected>GHS - Ghana Cedi</option>
                                    <option value="USD">USD - US Dollar</option>
                                    <option value="EUR">EUR - Euro</option>
                                    <option value="GBP">GBP - British Pound</option>
                                    <option value="JPY">JPY - Japanese Yen</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="expenseReceipt" class="form-label">Upload Receipt <span class="text-danger">*</span></label>
                            <input class="form-control" type="file" id="expenseReceipt" accept="image/*,.pdf" required>
                            <div class="form-text">Accepted formats: JPG, PNG, PDF (Max size: 5MB)</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="expenseProject" class="form-label">Project/Client (Optional)</label>
                            <input type="text" class="form-control" id="expenseProject" placeholder="e.g., Project X, Client Y">
                        </div>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="expenseBillable">
                            <label class="form-check-label" for="expenseBillable">This is a billable expense</label>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Please ensure all expenses comply with the company's expense policy. Unusual expenses may require additional approval.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i> Submit Expense
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Approvals Modal -->
    <div class="modal fade" id="approvalsModal" tabindex="-1" aria-labelledby="approvalsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="approvalsModalLabel">Pending Approvals</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs nav-bordered" role="tablist">
                        <li class="nav-item">
                            <a href="#leave-approvals" class="nav-link active" data-bs-toggle="tab" aria-selected="true" role="tab">
                                <i class="fas fa-umbrella-beach me-1"></i> Leave Requests <span class="badge bg-danger rounded-pill ms-1">2</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#expense-approvals" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab">
                                <i class="fas fa-file-invoice-dollar me-1"></i> Expense Reports <span class="badge bg-danger rounded-pill ms-1">1</span>
                            </a>
                        </li>
                    </ul>
                    
                    <div class="tab-content mt-3" id="approvalsTabContent">
                        <!-- Leave Approvals Tab -->
                        <div class="tab-pane fade show active" id="leave-approvals" role="tabpanel" aria-labelledby="leave-tab">
                            <div class="table-responsive">
                                <table class="table table-hover table-centered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Employee</th>
                                            <th>Leave Type</th>
                                            <th>Period</th>
                                            <th>Days</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="/images/users/avatar-2.jpg" class="rounded-circle me-2" width="32" height="32" alt="User">
                                                    <div>John Smith</div>
                                                </div>
                                            </td>
                                            <td>Annual Leave</td>
                                            <td>Sep 15 - Sep 20, 2025</td>
                                            <td>5</td>
                                            <td><span class="badge bg-warning">Pending</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-success me-1">Approve</button>
                                                <button class="btn btn-sm btn-outline-danger">Reject</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="/images/users/avatar-3.jpg" class="rounded-circle me-2" width="32" height="32" alt="User">
                                                    <div>Sarah Johnson</div>
                                                </div>
                                            </td>
                                            <td>Sick Leave</td>
                                            <td>Sep 10 - Sep 12, 2025</td>
                                            <td>2</td>
                                            <td><span class="badge bg-warning">Pending</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-success me-1">Approve</button>
                                                <button class="btn btn-sm btn-outline-danger">Reject</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Expense Approvals Tab -->
                        <div class="tab-pane fade" id="expense-approvals" role="tabpanel" aria-labelledby="expense-tab">
                            <div class="table-responsive">
                                <table class="table table-hover table-centered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Employee</th>
                                            <th>Expense Date</th>
                                            <th>Category</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="/images/users/avatar-4.jpg" class="rounded-circle me-2" width="32" height="32" alt="User">
                                                    <div>Michael Brown</div>
                                                </div>
                                            </td>
                                            <td>Sep 5, 2025</td>
                                            <td>Travel</td>
                                            <td>$245.50</td>
                                            <td><span class="badge bg-warning">Pending</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-success me-1">Approve</button>
                                                <button class="btn btn-sm btn-outline-danger">Reject</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <a href="#approvals" class="btn btn-success">View All Approvals</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Details Modal -->
    <div class="modal fade" id="leaveDetailsModal" tabindex="-1" aria-labelledby="leaveDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="leaveDetailsModalLabel">Leave Balance Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Leave Type</th>
                                    <th class="text-end">Total Days</th>
                                    <th class="text-end">Used</th>
                                    <th class="text-end">Remaining</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Annual Leave</td>
                                    <td class="text-end">21</td>
                                    <td class="text-end">6</td>
                                    <td class="text-end fw-bold text-success">15</td>
                                </tr>
                                <tr>
                                    <td>Sick Leave</td>
                                    <td class="text-end">10</td>
                                    <td class="text-end">2</td>
                                    <td class="text-end fw-bold text-success">8</td>
                                </tr>
                                <tr>
                                    <td>Casual Leave</td>
                                    <td class="text-end">7</td>
                                    <td class="text-end">7</td>
                                    <td class="text-end fw-bold text-danger">0</td>
                                </tr>
                                <tr>
                                    <td>Maternity/Paternity</td>
                                    <td class="text-end">90</td>
                                    <td class="text-end">0</td>
                                    <td class="text-end fw-bold text-success">90</td>
                                </tr>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th>Total</th>
                                    <th class="text-end">128</th>
                                    <th class="text-end">15</th>
                                    <th class="text-end">113</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        <h6>Leave Year: January 1, 2025 - December 31, 2025</h6>
                        <div class="progress mt-2" style="height: 20px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 12%" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100">12% Used</div>
                        </div>
                        <p class="text-muted small mt-2 mb-0">
                            Next leave year starts in 114 days
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <a href="#leave" class="btn btn-primary">View Leave Requests</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Time Off Request Modal -->
    <div class="modal fade" id="timeOffModal" tabindex="-1" aria-labelledby="timeOffModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="timeOffModalLabel">Request Time Off</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="timeOffForm">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="leaveType" class="form-label">Leave Type</label>
                                <select class="form-select" id="leaveType" required>
                                    <option value="">Select Leave Type</option>
                                    <option value="annual">Annual Leave</option>
                                    <option value="sick">Sick Leave</option>
                                    <option value="maternity">Maternity Leave</option>
                                    <option value="paternity">Paternity Leave</option>
                                    <option value="study">Study Leave</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="daysAvailable" class="form-label">Days Available</label>
                                <input type="text" class="form-control" id="daysAvailable" value="15 days" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="startDate" required>
                            </div>
                            <div class="col-md-6">
                                <label for="endDate" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="endDate" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="leaveReason" class="form-label">Reason for Leave</label>
                            <textarea class="form-control" id="leaveReason" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="contactDuringLeave" class="form-label">Contact During Leave</label>
                            <input type="text" class="form-control" id="contactDuringLeave" placeholder="Phone number where you can be reached">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Upload Document Modal -->
    <div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-labelledby="uploadDocumentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="uploadDocumentModalLabel">Upload Document</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="documentUploadForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="documentType" class="form-label">Document Type</label>
                            <select class="form-select" id="documentType" required>
                                <option value="">Select Document Type</option>
                                <option value="certificate">Certificate</option>
                                <option value="contract">Contract</option>
                                <option value="id">ID/Passport</option>
                                <option value="qualification">Qualification</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="documentTitle" class="form-label">Document Title</label>
                            <input type="text" class="form-control" id="documentTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="documentFile" class="form-label">Select File</label>
                            <input class="form-control" type="file" id="documentFile" required>
                            <div class="form-text">Max file size: 5MB. Allowed formats: PDF, DOC, DOCX, JPG, PNG</div>
                        </div>
                        <div class="mb-3">
                            <label for="documentDescription" class="form-label">Description (Optional)</label>
                            <textarea class="form-control" id="documentDescription" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Upload Document</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Book Training Modal -->
    <div class="modal fade" id="bookTrainingModal" tabindex="-1" aria-labelledby="bookTrainingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="bookTrainingModalLabel">Request Training</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="bookTrainingForm">
                    <div class="modal-body">
                        <ul class="nav nav-tabs nav-bordered mb-3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#internalTab" role="tab">
                                    <i class="fas fa-building me-1"></i> Internal Training
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#externalTab" role="tab">
                                    <i class="fas fa-external-link-alt me-1"></i> External Training
                                </a>
                            </li>
                        </ul>
                        
                        <div class="tab-content">
                            <!-- Internal Training Tab -->
                            <div class="tab-pane fade show active" id="internalTab" role="tabpanel">
                                <div class="mb-3">
                                    <label for="trainingProgram" class="form-label">Select Training Program</label>
                                    <select class="form-select" id="trainingProgram" required>
                                        <option value="" selected disabled>Choose a training program</option>
                                        <option value="cyber">Cybersecurity Awareness Training</option>
                                        <option value="project">Project Management Fundamentals</option>
                                        <option value="react">Advanced React Development</option>
                                        <option value="leadership">Leadership Skills Workshop</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="preferredDate" class="form-label">Preferred Date</label>
                                            <input type="date" class="form-control" id="preferredDate" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="preferredTime" class="form-label">Preferred Time</label>
                                            <select class="form-select" id="preferredTime" required>
                                                <option value="morning">Morning (9:00 AM - 12:00 PM)</option>
                                                <option value="afternoon">Afternoon (1:00 PM - 4:00 PM)</option>
                                                <option value="fullday">Full Day (9:00 AM - 4:00 PM)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="trainingJustification" class="form-label">Justification</label>
                                    <textarea class="form-control" id="trainingJustification" rows="2" placeholder="Please explain how this training will benefit your role" required></textarea>
                                </div>
                            </div>
                            <!-- External Training Tab -->
                            <div class="tab-pane fade" id="externalTab" role="tabpanel">
                                <div class="mb-3">
                                    <label for="trainingTitle" class="form-label">Training Title</label>
                                    <input type="text" class="form-control" id="trainingTitle" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="trainingProvider" class="form-label">Training Provider</label>
                                            <input type="text" class="form-control" id="trainingProvider" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="trainingCost" class="form-label">Estimated Cost (GHS)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">GHS</span>
                                                <input type="number" class="form-control" id="trainingCost" step="0.01" min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="startDate" class="form-label">Start Date</label>
                                            <input type="date" class="form-control" id="startDate" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="endDate" class="form-label">End Date</label>
                                            <input type="date" class="form-control" id="endDate" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="trainingDescription" class="form-label">Training Description & Objectives</label>
                                    <textarea class="form-control" id="trainingDescription" rows="3" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="businessCase" class="form-label">Business Case</label>
                                    <textarea class="form-control" id="businessCase" rows="2" placeholder="How will this training benefit the company?" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="trainingLink" class="form-label">Training Website/Link (if available)</label>
                                    <input type="url" class="form-control" id="trainingLink">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="acknowledge" required>
                            <label class="form-check-label" for="acknowledge">
                                I acknowledge that my manager will review and approve this training request
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Payslips Modal -->
    <div class="modal fade" id="payslipsModal" tabindex="-1" aria-labelledby="payslipsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="payslipsModalLabel">My Payslips</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Pay Period</th>
                                    <th>Payment Date</th>
                                    <th>Basic Salary</th>
                                    <th>Allowances</th>
                                    <th>Deductions</th>
                                    <th>Net Pay</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>May 2023</td>
                                    <td>May 31, 2023</td>
                                    <td>GHS 5,000.00</td>
                                    <td>GHS 1,200.00</td>
                                    <td>GHS 1,500.00</td>
                                    <td>GHS 4,700.00</td>
                                    <td><span class="badge bg-success">Paid</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">View</button>
                                        <button class="btn btn-sm btn-outline-secondary">Download</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>April 2023</td>
                                    <td>April 30, 2023</td>
                                    <td>GHS 5,000.00</td>
                                    <td>GHS 1,200.00</td>
                                    <td>GHS 1,500.00</td>
                                    <td>GHS 4,700.00</td>
                                    <td><span class="badge bg-success">Paid</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">View</button>
                                        <button class="btn btn-sm btn-outline-secondary">Download</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>March 2023</td>
                                    <td>March 31, 2023</td>
                                    <td>GHS 5,000.00</td>
                                    <td>GHS 1,200.00</td>
                                    <td>GHS 1,500.00</td>
                                    <td>GHS 4,700.00</td>
                                    <td><span class="badge bg-success">Paid</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">View</button>
                                        <button class="btn btn-sm btn-outline-secondary">Download</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-between w-100">
                        <div>
                            <button class="btn btn-outline-secondary">
                                <i class="fas fa-download me-1"></i> Download All
                            </button>
                        </div>
                        <div>
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-warning">Request Payslip Correction</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assigned Tasks Modal -->
    <div class="modal fade" id="assignedTasksModal" tabindex="-1" aria-labelledby="assignedTasksModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="assignedTasksModalLabel">My Tasks</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" placeholder="Search tasks..." id="taskSearch">
                            </div>
                        </div>
                        <div>
                            <select class="form-select form-select-sm" style="width: 150px;">
                                <option>All Tasks</option>
                                <option>Today</option>
                                <option>This Week</option>
                                <option>Overdue</option>
                                <option>Completed</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-centered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Task</th>
                                    <th>Project</th>
                                    <th>Due Date</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Task 1 -->
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="form-check me-2">
                                                <input class="form-check-input task-checkbox" type="checkbox" id="task1">
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Review Q3 Financial Report</h6>
                                                <small class="text-muted">#TASK-001</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Q3 Financials</td>
                                    <td>Sep 15, 2025</td>
                                    <td><span class="badge bg-danger">High</span></td>
                                    <td><span class="badge bg-warning">In Progress</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary me-1" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" title="Complete">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </td>
                                </tr>
                                <!-- Task 2 -->
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="form-check me-2">
                                                <input class="form-check-input task-checkbox" type="checkbox" id="task2">
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Update Employee Handbook</h6>
                                                <small class="text-muted">#TASK-002</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>HR Policies</td>
                                    <td>Sep 20, 2025</td>
                                    <td><span class="badge bg-warning">Medium</span></td>
                                    <td><span class="badge bg-info">Not Started</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary me-1" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" title="Complete">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </td>
                                </tr>
                                <!-- Task 3 -->
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="form-check me-2">
                                                <input class="form-check-input task-checkbox" type="checkbox" id="task3" checked>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-muted text-decoration-line-through">Team Meeting Minutes</h6>
                                                <small class="text-muted">#TASK-003</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Team Collaboration</td>
                                    <td><span class="text-muted">Sep 5, 2025</span></td>
                                    <td><span class="badge bg-secondary">Low</span></td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary me-1" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" title="Reopen">
                                            <i class="fas fa-redo"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Showing <span id="showingTasksCount">3</span> of 5 tasks
                        </div>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i> New Task
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Benefits Management Modal -->
    <div class="modal fade" id="benefitsModal" tabindex="-1" aria-labelledby="benefitsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content overflow-hidden">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="benefitsModalLabel">Benefits Management</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="row g-0">
                        <!-- Sidebar Navigation -->
                        <div class="col-md-3 bg-light">
                            <div class="d-flex flex-column p-3 h-100">
                                <div class="mb-4">
                                    <h6 class="text-muted text-uppercase small fw-bold mb-3">My Benefits</h6>
                                    <div class="nav flex-column nav-pills" id="benefitsTab" role="tablist">
                                        <button class="nav-link active" id="health-tab" data-bs-toggle="pill" data-bs-target="#health" type="button" role="tab">
                                            <i class="fas fa-heartbeat me-2"></i> Health Insurance
                                        </button>
                                        <button class="nav-link" id="retirement-tab" data-bs-toggle="pill" data-bs-target="#retirement" type="button" role="tab">
                                            <i class="fas fa-piggy-bank me-2"></i> Retirement Plan
                                        </button>
                                        <button class="nav-link" id="dental-tab" data-bs-toggle="pill" data-bs-target="#dental" type="button" role="tab">
                                            <i class="fas fa-tooth me-2"></i> Dental Coverage
                                        </button>
                                        <button class="nav-link" id="vision-tab" data-bs-toggle="pill" data-bs-target="#vision" type="button" role="tab">
                                            <i class="fas fa-eye me-2"></i> Vision Care
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-auto">
                                    <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#enrollBenefitsModal">
                                        <i class="fas fa-plus-circle me-2"></i> Enroll in New Benefits
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Main Content -->
                        <div class="col-md-9 p-4">
                            <div class="tab-content" id="benefitsTabContent">
                                <!-- Health Insurance Tab -->
                                <div class="tab-pane fade show active" id="health" role="tabpanel">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h4 class="mb-0">Health Insurance</h4>
                                        <span class="badge bg-success">Active</span>
                                    </div>
                                    
                                    <div class="row g-4 mb-4">
                                        <div class="col-md-6">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <div class="card-body">
                                                    <h6 class="text-muted text-uppercase small mb-3">Plan Details</h6>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span>Plan Type:</span>
                                                        <strong>Gold PPO</strong>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span>Coverage:</span>
                                                        <strong>Family</strong>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span>Effective Date:</span>
                                                        <strong>Jan 1, 2024</strong>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span>Expiration:</span>
                                                        <strong>Dec 31, 2024</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <div class="card-body">
                                                    <h6 class="text-muted text-uppercase small mb-3">Coverage Summary</h6>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span>Deductible (Individual/Family):</span>
                                                        <strong>$1,500 / $3,000</strong>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span>Out-of-Pocket Max:</span>
                                                        <strong>$6,000 / $12,000</strong>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span>Primary Care Visit:</span>
                                                        <strong>$25 Copay</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <h5 class="mb-3">Dependents</h5>
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Relationship</th>
                                                    <th>Date of Birth</th>
                                                    <th>Status</th>
                                                    <th class="text-end">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>John Smith</td>
                                                    <td>Self</td>
                                                    <td>05/15/1985</td>
                                                    <td><span class="badge bg-success">Active</span></td>
                                                    <td class="text-end">
                                                        <button class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Sarah Smith</td>
                                                    <td>Spouse</td>
                                                    <td>08/22/1988</td>
                                                    <td><span class="badge bg-success">Active</span></td>
                                                    <td class="text-end">
                                                        <button class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="d-flex justify-content-end mt-4">
                                        <button class="btn btn-outline-primary me-2">
                                            <i class="fas fa-file-pdf me-1"></i> Download Plan Documents
                                        </button>
                                        <button class="btn btn-primary">
                                            <i class="fas fa-edit me-1"></i> Update Information
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Other benefit tabs (Retirement, Dental, Vision) would go here with similar structure -->
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enroll in Benefits Modal -->
    <div class="modal fade" id="enrollBenefitsModal" tabindex="-1" aria-labelledby="enrollBenefitsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="enrollBenefitsModalLabel">Enroll in Benefits</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        You are currently in the Open Enrollment period. All changes will be effective from the next pay period.
                    </div>
                    
                    <div class="mb-4">
                        <h5 class="mb-3">Available Benefit Plans</h5>
                        
                        <!-- Health Insurance Plans -->
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Health Insurance Plans</h6>
                                <span class="badge bg-primary">Required</span>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Plan</th>
                                                <th>Coverage</th>
                                                <th>Monthly Cost</th>
                                                <th>Deductible</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="form-check
                                                    <input class="form-check-input" type="radio" name="healthPlan" id="goldPlan" checked>
                                                    <label class="form-check-label fw-bold" for="goldPlan">
                                                        Gold PPO
                                                    </label>
                                                    <p class="small text-muted mb-0">Comprehensive coverage with low copays</p>
                                                </td>
                                                <td>Family</td>
                                                <td>$450/mo</td>
                                                <td>$1,500</td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#goldPlanDetails">
                                                        View Details
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr class="collapse" id="goldPlanDetails">
                                                <td colspan="5" class="bg-light">
                                                    <div class="p-3">
                                                        <h6>Plan Details</h6>
                                                        <ul class="list-unstyled">
                                                            <li><i class="fas fa-check text-success me-2"></i> $25 Primary Care Visit</li>
                                                            <li><i class="fas fa-check text-success me-2"></i> $40 Specialist Visit</li>
                                                            <li><i class="fas fa-check text-success me-2"></i> $250 ER Visit (waived if admitted)</li>
                                                            <li><i class="fas fa-check text-success me-2"></i> $10/$30/$50 Prescriptions</li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- More plans would go here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Dental Plans -->
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Dental Insurance</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="dentalPlan" id="noDental" checked>
                                    <label class="form-check-label" for="noDental">
                                        I do not want dental coverage at this time
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="dentalPlan" id="basicDental">
                                    <label class="form-check-label" for="basicDental">
                                        <strong>Basic Dental Plan</strong> - $15/month
                                        <p class="small text-muted mb-0">Preventive care covered at 100%, basic services at 80%</p>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="dentalPlan" id="premiumDental">
                                    <label class="form-check-label" for="premiumDental">
                                        <strong>Premium Dental Plan</strong> - $30/month
                                        <p class="small text-muted mb-0">Preventive care covered at 100%, basic services at 80%, major services at 50%</p>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Dependents Section -->
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Dependents</h6>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-plus me-1"></i> Add Dependent
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Adding or removing dependents may require documentation for verification.
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Relationship</th>
                                                <th>Date of Birth</th>
                                                <th>Coverage</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Sarah Smith</td>
                                                <td>Spouse</td>
                                                <td>08/22/1988</td>
                                                <td>
                                                    <select class="form-select form-select-sm">
                                                        <option>Same as Employee</option>
                                                        <option>Different Plan</option>
                                                        <option>No Coverage</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Beneficiaries -->
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Beneficiaries</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">Please designate who will receive your benefits in the event of your death.</p>
                                <button class="btn btn-outline-primary">
                                    <i class="fas fa-plus me-1"></i> Add Beneficiary
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                    <button type="button" class="btn btn-success">
                        <i class="fas fa-check-circle me-1"></i> Submit for Approval
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Emergency Contact Modal -->
    <div class="modal fade" id="emergencyContactModal" tabindex="-1" aria-labelledby="emergencyContactModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="emergencyContactModalLabel">Emergency Contact Information</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        In case of emergency, please contact the following:
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold">HR Department</h6>
                        <p class="mb-1"><i class="fas fa-phone-alt me-2"></i> +233 24 123 4567</p>
                        <p class="mb-1"><i class="fas fa-envelope me-2"></i> hr@gesl.com.gh</p>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold">Emergency Services</h6>
                        <p class="mb-1"><i class="fas fa-ambulance me-2"></i> Ambulance: 193 or 112</p>
                        <p class="mb-1"><i class="fas fa-fire-extinguisher me-2"></i> Fire Service: 192 or 112</p>
                        <p class="mb-1"><i class="fas fa-shield-alt me-2"></i> Police: 191 or 112</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="fw-bold">Your Emergency Contact</h6>
                        <p class="mb-1"><i class="fas fa-user me-2"></i> {{ Auth::user()->emergency_contact_name ?? 'Not specified' }}</p>
                        <p class="mb-1"><i class="fas fa-phone me-2"></i> {{ Auth::user()->emergency_contact_phone ?? 'Not specified' }}</p>
                        <p class="mb-1"><i class="fas fa-address-card me-2"></i> {{ Auth::user()->emergency_contact_relationship ?? 'Not specified' }}</p>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Please ensure your emergency contact information is always up to date in your profile.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="#personal-info" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="fas fa-user-edit me-1"></i> Update My Emergency Contact
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle between internal and external training forms
        document.addEventListener('DOMContentLoaded', function() {
            const internalRadio = document.getElementById('internalTraining');
            const externalRadio = document.getElementById('externalTraining');
            const internalSection = document.getElementById('internalTrainingSection');
            const externalSection = document.getElementById('externalTrainingSection');
            
            internalRadio.addEventListener('change', function() {
                if (this.checked) {
                    internalSection.style.display = 'block';
                    externalSection.style.display = 'none';
                }
            });
            
            externalRadio.addEventListener('change', function() {
                if (this.checked) {
                    internalSection.style.display = 'none';
                    externalSection.style.display = 'block';
                }
            });
        });
    </script>
@endsection

@section('script')
    @parent
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <!-- DateRangePicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.1.0/daterangepicker.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // JavaScript is working - no test alerts needed
        
        // Staff Portal Management Class
        class StaffPortal {
            constructor() {
                this.init();
            }

            init() {
                this.bindEvents();
                console.log('Staff portal initialized');
            }

            bindEvents() {
                // Personal info form submission
                const personalInfoForm = document.getElementById('personalInfoForm');
                if (personalInfoForm) {
                    personalInfoForm.addEventListener('submit', (e) => this.handlePersonalInfoSubmit(e));
                }

                // Tab switching events
                const tabLinks = document.querySelectorAll('a[data-bs-toggle="tab"]');
                tabLinks.forEach(tab => {
                    tab.addEventListener('shown.bs.tab', (e) => this.handleTabSwitch(e));
                });
            }

            handlePersonalInfoSubmit(e) {
                e.preventDefault();
                
                const formData = new FormData(e.target);
                
                fetch('/company/hr/staff/personal-info', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Success', 'Personal information updated successfully', 'success');
                    } else {
                        showToast('Error', data.message || 'Failed to update personal information', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error', 'An error occurred while updating personal information', 'danger');
                });
            }

            handleTabSwitch(e) {
                const targetTab = e.target.getAttribute('href');
                
                switch (targetTab) {
                    case '#expense-claims':
                        this.loadExpenseClaims();
                        break;
                    case '#training':
                        this.loadTrainingData();
                        break;
                    case '#documents':
                        this.loadDocuments();
                        break;
                }
            }

            loadExpenseClaims(filter = 'all') {
                fetch('/company/hr/staff/expense-claims', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ filter: filter })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Expense claims loaded:', data.data);
                    }
                })
                .catch(error => {
                    console.error('Error loading expense claims:', error);
                });
            }

            loadTrainingData() {
                fetch('/company/hr/staff/training-data', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Training data loaded:', data.data);
                    }
                })
                .catch(error => {
                    console.error('Error loading training data:', error);
                });
            }

            loadDocuments() {
                fetch('/company/hr/staff/documents', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Documents loaded:', data.data);
                    }
                })
                .catch(error => {
                    console.error('Error loading documents:', error);
                });
            }
        }

        // Initialize staff portal
        window.staffPortal = new StaffPortal();

        document.addEventListener('DOMContentLoaded', function() {
            console.log('👤 Staff: Main DOM Content Loaded - All scripts initialized!');
            
            // Bank Information Edit/Save Functionality
            const editBankInfoBtn = document.getElementById('editBankInfoBtn');
            const saveBankInfoBtn = document.getElementById('saveBankInfoBtn');
            const cancelEditBankInfoBtn = document.getElementById('cancelEditBankInfoBtn');
            const bankInputs = document.querySelectorAll('#bank-info input[type="text"]');
            
            if (editBankInfoBtn) {
                editBankInfoBtn.addEventListener('click', function() {
                    // Make inputs editable
                    bankInputs.forEach(input => {
                        input.readOnly = false;
                        input.classList.add('bg-white');
                    });
                    
                    // Toggle buttons
                    this.classList.add('d-none');
                    saveBankInfoBtn.classList.remove('d-none');
                    cancelEditBankInfoBtn.classList.remove('d-none');
                });
            }
            
            if (cancelEditBankInfoBtn) {
                cancelEditBankInfoBtn.addEventListener('click', function() {
                    // Reset form values (you might want to reload from server here)
                    bankInputs.forEach(input => {
                        input.readOnly = true;
                        input.classList.remove('bg-white');
                        // Reset to original values if needed
                        // input.value = originalValues[input.name];
                    });
                    
                    // Toggle buttons
                    editBankInfoBtn.classList.remove('d-none');
                    saveBankInfoBtn.classList.add('d-none');
                    this.classList.add('d-none');
                });
            }
            
            if (saveBankInfoBtn) {
                saveBankInfoBtn.addEventListener('click', function() {
                    // Show loading state
                    const originalText = this.innerHTML;
                    this.disabled = true;
                    this.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Saving...';
                    
                    // Prepare form data
                    const formData = new FormData();
                    bankInputs.forEach(input => {
                        formData.append(input.name, input.value);
                    });
                    
                    // Add CSRF token
                    formData.append('_token', '{{ csrf_token() }}');
                    
                    // Send data to server (replace with your actual endpoint)
                    fetch('', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Make inputs read-only again
                            bankInputs.forEach(input => {
                                input.readOnly = true;
                                input.classList.remove('bg-white');
                            });
                            
                            // Toggle buttons
                            editBankInfoBtn.classList.remove('d-none');
                            saveBankInfoBtn.classList.add('d-none');
                            cancelEditBankInfoBtn.classList.add('d-none');
                            
                            // Show success message
                            showToast('Success', 'Bank information updated successfully', 'success');
                        } else {
                            throw new Error(data.message || 'Failed to update bank information');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Error', error.message || 'Failed to update bank information', 'error');
                    })
                    .finally(() => {
                        // Reset button state
                        this.disabled = false;
                        this.innerHTML = originalText;
                    });
                });
            }
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Initialize DataTables if needed
            if ($.fn.DataTable.isDataTable('.datatable')) {
                $('.datatable').DataTable();
            }
            
            // Profile Image Upload
            const profileImage = document.getElementById('profileImage');
            const profileImageInput = document.getElementById('profileImageInput');
            const profileImageForm = document.getElementById('profileImageForm');
            const profileImageUpload = document.getElementById('profileImageUpload');
            
            if (profileImageUpload) {
                profileImageUpload.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        // Validate file type
                        const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
                        if (!validTypes.includes(file.type)) {
                            showToast('Error', 'Please select a valid image file (JPEG, PNG, GIF)', 'error');
                            return;
                        }
                        
                        // Validate file size (max 2MB)
                        if (file.size > 2 * 1024 * 1024) {
                            showToast('Error', 'Image size should not exceed 2MB', 'error');
                            return;
                        }
                        
                        // Show preview
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            profileImage.src = event.target.result;
                            
                            // Submit the form
                            profileImageInput.files = e.target.files;
                            const formData = new FormData(profileImageForm);
                            
                            // Show loading state
                            const originalSrc = profileImage.src;
                            profileImage.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9IiNlZWUiLz48dGV4dCB4PSI1MCUiIHk9IjUwJSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjEyIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBhbGlnbm1lbnQtYmFzZWxpbmU9Im1pZGRsZSIgZmlsbD0iIzk5OSI+TG9hZGluZy4uLjwvdGV4dD48L3N2Zz4=';
                            
                            fetch(profileImageForm.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Update the profile picture in the welcome section
                                    const welcomeAvatar = document.querySelector('.welcome-section .profile-avatar');
                                    if (welcomeAvatar) {
                                        welcomeAvatar.src = data.avatar_url + '?' + new Date().getTime();
                                    }
                                    showToast('Success', 'Profile picture updated successfully', 'success');
                                } else {
                                    throw new Error(data.message || 'Failed to update profile picture');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showToast('Error', error.message || 'Failed to update profile picture', 'error');
                                profileImage.src = originalSrc;
                            });
                        };
                        
                        reader.readAsDataURL(file);
                    }
                });
            }
            
            if (profileImageInput) {
                // Handle file selection
                profileImageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        // Validate file type
                        const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
                        if (!validTypes.includes(file.type)) {
                            showToast('Error', 'Please select a valid image file (JPEG, PNG, GIF)', 'error');
                            return;
                        }
                        
                        // Validate file size (max 2MB)
                        if (file.size > 2 * 1024 * 1024) {
                            showToast('Error', 'Image size should not exceed 2MB', 'error');
                            return;
                        }
                        
                        const reader = new FileReader();
                        
                        reader.onload = function(event) {
                            imagePreviewElement.src = event.target.result;
                            imagePreview.classList.remove('d-none');
                            currentProfilePicture.classList.add('d-none');
                            removeButton.disabled = false;
                        };
                        
                        reader.readAsDataURL(file);
                    }
                });
                
                // Remove photo button
                removeButton.addEventListener('click', function() {
                    // Reset to default avatar
                    const defaultAvatar = 'https://ui-avatars.com/api/?name=' + 
                        encodeURIComponent('{{ Auth::user()->name ?? "GESL" }}') + 
                        '&background=2c7be5&color=fff&size=256';
                    
                    currentProfilePicture.src = defaultAvatar;
                    profileImageInput.value = '';
                    imagePreview.classList.add('d-none');
                    currentProfilePicture.classList.remove('d-none');
                    this.disabled = true;
                    
                    // Also update the welcome section avatar
                    const welcomeAvatar = document.querySelector('.welcome-section .profile-avatar');
                    if (welcomeAvatar) {
                        welcomeAvatar.src = defaultAvatar;
                    }
                });
                
                // Save button
                saveButton.addEventListener('click', function() {
                    const formData = new FormData();
                    const file = profileImageInput.files[0];
                    
                    if (file) {
                        formData.append('profile_picture', file);
                    } else if (removeButton.disabled === false && !profileImageInput.files.length) {
                        // User removed the photo
                        formData.append('remove_photo', '1');
                    } else {
                        // No changes made
                        bootstrap.Modal.getInstance(document.getElementById('profilePictureModal')).hide();
                        return;
                    }
                    
                    // Add CSRF token
                    formData.append('_token', '{{ csrf_token() }}');
                    
                    // Show loading state
                    const originalText = saveButton.innerHTML;
                    saveButton.disabled = true;
                    saveButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Saving...';
                    
                    // Simulate API call (replace with actual API endpoint)
                    fetch('', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update the profile picture in the welcome section
                            const welcomeAvatar = document.querySelector('.welcome-section .profile-avatar');
                            if (welcomeAvatar) {
                                welcomeAvatar.src = data.avatar_url + '?' + new Date().getTime();
                            }
                            
                            // Update the current profile picture in the modal
                            if (data.avatar_url) {
                                currentProfilePicture.src = data.avatar_url + '?' + new Date().getTime();
                            }
                            
                            // Show success message
                            showToast('Success', 'Profile picture updated successfully', 'success');
                            
                            // Close the modal
                            bootstrap.Modal.getInstance(document.getElementById('profilePictureModal')).hide();
                        } else {
                            throw new Error(data.message || 'Failed to update profile picture');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Error', error.message || 'Failed to update profile picture', 'error');
                    })
                    .finally(() => {
                        // Reset button state
                        saveButton.disabled = false;
                        saveButton.innerHTML = originalText;
                    });
                });
            }
            
            // Helper function to show toast messages
            function showToast(title, message, type = 'info') {
                // Check if toast container exists, if not create it
                let toastContainer = document.getElementById('toast-container');
                if (!toastContainer) {
                    toastContainer = document.createElement('div');
                    toastContainer.id = 'toast-container';
                    toastContainer.style.position = 'fixed';
                    toastContainer.style.top = '20px';
                    toastContainer.style.right = '20px';
                    toastContainer.style.zIndex = '1100';
                    document.body.appendChild(toastContainer);
                }
                
                // Create toast element
                const toastId = 'toast-' + Date.now();
                const toast = document.createElement('div');
                toast.id = toastId;
                toast.className = `toast align-items-center text-white bg-${type} border-0`;
                toast.role = 'alert';
                toast.setAttribute('aria-live', 'assertive');
                toast.setAttribute('aria-atomic', 'true');
                
                // Toast header
                toast.innerHTML = `
                    <div class="d-flex">
                        <div class="toast-body">
                            <strong>${title}</strong><br>${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                `;
                
                // Add to container
                toastContainer.appendChild(toast);
                
                // Initialize and show toast
                const bsToast = new bootstrap.Toast(toast, { autohide: true, delay: 5000 });
                bsToast.show();
                
                // Remove toast after it's hidden
                toast.addEventListener('hidden.bs.toast', function () {
                    toast.remove();
                });
            }
        });
    </script>
@endsection