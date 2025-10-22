<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Database</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --light-gray: #f8f9fc;
            --border-color: #e3e6f0;
        }
        
        .tab-content .tab-pane {
            display: none;
            padding: 1.5rem;
            background: white;
            border-radius: 0.35rem;
            border: 1px solid var(--border-color);
            margin-top: -1px;
        }
        
        .tab-content .tab-pane.active {
            display: block;
            animation: fadeIn 0.3s ease-in-out;
        }
        
        .avatar-xl {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .form-label {
            font-weight: 600;
            color: #5a5c69;
            margin-bottom: 0.4rem;
        }
        
        .form-control, .form-select {
            border: 1px solid #d1d3e2;
            border-radius: 0.35rem;
            padding: 0.6rem 0.75rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .card {
            border: 1px solid var(--border-color);
            border-radius: 0.35rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }
        
        .card-header {
            background-color: var(--light-gray);
            border-bottom: 1px solid var(--border-color);
            padding: 0.75rem 1.25rem;
        }
        
        .nav-tabs {
            border-bottom: 1px solid var(--border-color);
            margin: -1.25rem -1.25rem 1.5rem -1.25rem;
            padding: 0 1.25rem;
        }
        
        .nav-tabs .nav-link {
            border: none;
            color: var(--secondary-color);
            font-weight: 600;
            padding: 1rem 1.5rem;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            background: transparent;
            border-color: var(--primary-color);
        }
        
        .nav-tabs .nav-link:hover:not(.active) {
            border-color: transparent;
            color: var(--primary-color);
            border-bottom: 3px solid #e3e6f0;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.5rem 1.5rem;
            font-weight: 600;
        }
        
        .btn-outline-secondary {
            color: var(--secondary-color);
            border-color: var(--border-color);
            padding: 0.5rem 1.5rem;
            font-weight: 600;
        }
        
        .btn-outline-secondary:hover {
            background-color: #f8f9fc;
            color: var(--primary-color);
        }
        
        /* Action buttons */
        .btn-action {
            border: none;
            background: transparent;
            padding: 0.25rem 0.5rem;
            color: var(--secondary-color);
            transition: color 0.2s;
        }
        
        .btn-action:hover {
            color: var(--primary-color);
            background: transparent;
        }
        
        .btn-action.text-danger:hover {
            color: #dc3545 !important;
        }
        
        .toast {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .nav-tabs {
                flex-wrap: nowrap;
                overflow-x: auto;
                padding-bottom: 5px;
            }
            
            .nav-tabs .nav-link {
                white-space: nowrap;
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
            }
        }
    </style>
</head>
<body>
<!-- Employee Database Section -->
<section class="employee-database mb-5">
    <!-- Header and Add Employee Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">Employee Database</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal" aria-label="Add New Employee">
            <i class="fas fa-plus me-1"></i> Add Employee
        </button>
    </div>

    <!-- Employee Table Card -->
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <!-- Search and Filters -->
            <div class="row mb-4 align-items-center">
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="input-group search-box">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" id="employeeSearch" placeholder="Search employees..." aria-label="Search employees">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="d-flex justify-content-end gap-2 flex-wrap">
                        <!-- Export Button -->
                        <button class="btn btn-outline-secondary" aria-label="Export Employee Data">
                            <i class="fas fa-download me-1"></i> Export
                        </button>
                    </div>
                </div>
            </div>

            <!-- Employee Table -->
            <div class="table-responsive">
                <table class="table table-centered table-hover" id="employeesTable" aria-label="Employee Database Table">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Staff ID</th>
                            <th scope="col">Full Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Department</th>
                            <th scope="col">Position</th>
                            <th scope="col">Employment Type</th>
                            <th scope="col">Status</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="row mt-4 align-items-center">
                <div class="col-sm-12 col-md-5">
                    <div class="dataTables_info" id="datatable_info" role="status" aria-live="polite">
                        Loading employees...
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <nav class="dataTables_paginate paging_simple_numbers" id="datatable_paginate" aria-label="Table pagination">
                        <ul class="pagination pagination-rounded justify-content-end mb-0">

                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h2 class="modal-title h5" id="addEmployeeModalLabel">Add New Employee</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('hr.employees.import.template') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-download me-1"></i> Download Import Template
                        </a>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <input type="file" id="employeeImportFile" accept=".csv,.xlsx" class="form-control form-control-sm" style="max-width: 260px;">
                        <button type="button" class="btn btn-primary btn-sm" id="importEmployeesBtn">
                            <i class="fas fa-file-import me-1"></i> Import Employees
                        </button>
                    </div>
                </div>
                {{-- <form id="addEmployeeForm" method="POST" enctype="multipart/form-data">
                    <!-- Navigation Tabs -->
                    <ul class="nav nav-tabs nav-justified mb-4" role="tablist" aria-label="Employee Information Tabs">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="personalInfoTab" data-bs-toggle="tab" href="#personalInfo" role="tab" aria-controls="personalInfo" aria-selected="true">
                                <i class="fas fa-user d-sm-none"></i>
                                <span class="d-none d-sm-inline">Personal Information</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="employmentInfoTab" data-bs-toggle="tab" href="#employmentInfo" role="tab" aria-controls="employmentInfo" aria-selected="false">
                                <i class="fas fa-briefcase d-sm-none"></i>
                                <span class="d-none d-sm-inline">Employment Details</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="bankInfoTab" data-bs-toggle="tab" href="#bankInfo" role="tab" aria-controls="bankInfo" aria-selected="false">
                                <i class="fas fa-university d-sm-none"></i>
                                <span class="d-none d-sm-inline">Bank Information</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="emergencyContactTab" data-bs-toggle="tab" href="#emergencyContact" role="tab" aria-controls="emergencyContact" aria-selected="false">
                                <i class="fas fa-phone-alt d-sm-none"></i>
                                <span class="d-none d-sm-inline">Emergency Contact</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="documentsTab" data-bs-toggle="tab" href="#documents" role="tab" aria-controls="documents" aria-selected="false">
                                <i class="fas fa-file-alt d-sm-none"></i>
                                <span class="d-none d-sm-inline">Documents</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content p-3">
                        <!-- Personal Information Tab -->
                        <div class="tab-pane fade show active" id="personalInfo" role="tabpanel" aria-labelledby="personalInfoTab">
                            <!-- Profile Picture -->
                            <div class="row mb-4">
                                <div class="col-md-3 text-center">
                                    <div class="position-relative d-inline-block">
                                        <img src="/images/users/avatar-9.jpg" class="rounded-circle avatar-xl" alt="Profile picture preview" id="profilePicturePreview">
                                        <button type="button" class="btn btn-primary btn-sm rounded-circle position-absolute bottom-0 end-0" aria-label="Upload profile picture" onclick="document.getElementById('profilePicture').click()">
                                            <i class="fas fa-camera"></i>
                                        </button>
                                        <input type="file" id="profilePicture" class="d-none" accept="image/*" aria-label="Profile picture upload">
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="firstName" class="form-label">First Name</label>
                                                <input type="text" class="form-control" id="firstName" placeholder="Enter first name" >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="middleName" class="form-label">Middle Name</label>
                                                <input type="text" class="form-control" id="middleName" placeholder="Enter middle name">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="lastName" class="form-label">Last Name</label>
                                                <input type="text" class="form-control" id="lastName" placeholder="Enter last name" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="dateOfBirth" class="form-label">Date of Birth</label>
                                                <input type="date" class="form-control" id="dateOfBirth" >
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="gender" class="form-label">Gender</label>
                                                <select class="form-select" id="gender" >
                                                    <option value="" disabled selected>Select Gender</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Contact Information -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="primaryPhone" class="form-label">Primary Phone</label>
                                        <input type="tel" class="form-control" id="primaryPhone" placeholder="Enter primary phone" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="secondaryPhone" class="form-label">Secondary Phone</label>
                                        <input type="tel" class="form-control" id="secondaryPhone" placeholder="Enter secondary phone">
                                    </div>
                                </div>
                            </div>
                            <!-- Email and Marital Status -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="personalEmail" class="form-label">Personal Email</label>
                                        <input type="email" class="form-control" id="personalEmail" placeholder="Enter personal email" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="maritalStatus" class="form-label">Marital Status</label>
                                        <select class="form-select" id="maritalStatus" >
                                            <option value="" disabled selected>Select Marital Status</option>
                                            <option value="single">Single</option>
                                            <option value="married">Married</option>
                                            <option value="divorced">Divorced</option>
                                            <option value="widowed">Widowed</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- Nationality -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="nationality" class="form-label">Nationality</label>
                                        <select class="form-select" id="nationality" >
                                            <option value="" disabled selected>Select Nationality</option>
                                            <option value="GH">Ghanaian</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="country" class="form-label">Country</label>
                                        <select class="form-select" id="country" >
                                            <option value="" disabled selected>Select Country</option>
                                            <option value="GH">Ghana</option>
                                            <option value="">Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="region" class="form-label">Region</label>
                                        <select class="form-select" id="region" >
                                            <option value="" disabled selected>Select Region</option>
                                            <option value="Ahafo">Ahafo Region</option>
                                            <option value="Ashanti">Ashanti Region</option>
                                            <option value="Bono">Bono Region</option>
                                            <option value="Bono East">Bono East Region</option>
                                            <option value="Central">Central Region</option>
                                            <option value="Eastern">Eastern Region</option>
                                            <option value="Greater Accra">Greater Accra Region</option>
                                            <option value="North East">North East Region</option>
                                            <option value="Northern">Northern Region</option>
                                            <option value="Oti">Oti Region</option>
                                            <option value="Savannah">Savannah Region</option>
                                            <option value="Upper East">Upper East Region</option>
                                            <option value="Upper West">Upper West Region</option>
                                            <option value="Volta">Volta Region</option>
                                            <option value="Western">Western Region</option>
                                            <option value="Western North">Western North Region</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="city" class="form-label">City</label>
                                        <input type="text" class="form-control" id="city" placeholder="Enter city" >
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Identification -->
                            <div class="card mb-3">
                                <div class="card-header bg-primary text-white">
                                    <h4 class="card-title h6 mb-0">Ghana Identification</h4>
                                </div>
                                <div class="card-body">
                                    <!-- Identification 1 -->
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="idType1" class="form-label">Identification Type 1</label>
                                                <select class="form-select" id="idType1" onchange="toggleIdNumberField('idType1', 'idNumber1')">
                                                    <option value="" selected disabled>Select ID Type</option>
                                                    <option value="ghana_card">Ghana Card</option>
                                                    <option value="passport">Passport</option>
                                                    <option value="driving_license">Driver's License</option>
                                                    <option value="voter_id">Voter ID</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="idNumber1" class="form-label">ID Number</label>
                                                <input type="text" class="form-control" id="idNumber1" placeholder="Enter ID number" disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Identification 2 (Optional) -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="idType2" class="form-label">Identification Type 2 (Optional)</label>
                                                <select class="form-select" id="idType2" onchange="toggleIdNumberField('idType2', 'idNumber2')">
                                                    <option value="" selected disabled>Select ID Type</option>
                                                    <option value="ghana_card">Ghana Card</option>
                                                    <option value="passport">Passport</option>
                                                    <option value="driving_license">Driver's License</option>
                                                    <option value="voter_id">Voter ID</option>
                                                    <option value="other">Other</option>
                                                    <option value="none">None</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="idNumber2" class="form-label">ID Number</label>
                                                <input type="text" class="form-control" id="idNumber2" placeholder="Enter ID number" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group mt-2">
                                                <label for="idNotes" class="form-label">Additional Identification Information</label>
                                                <textarea class="form-control" id="idNotes" rows="3" placeholder="Enter additional identification information"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Tax Information -->
                            <div class="card mt-3">
                                <div class="card-header bg-primary text-white">
                                    <h4 class="card-title h6 mb-0">Tax Information</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="tinNumber" class="form-label">TIN Number</label>
                                                <input type="text" class="form-control" id="tinNumber" placeholder="Enter TIN number" >
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="ssnitNumber" class="form-label">SSNIT ID</label>
                                                <input type="text" class="form-control" id="ssnitNumber" placeholder="Enter SSNIT Number">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="taxStatus" class="form-label">Tax Residency Status</label>
                                                <select class="form-select" id="taxStatus" >
                                                    <option value="" disabled selected>Select Tax Status</option>
                                                    <option value="resident">Resident</option>
                                                    <option value="non-resident">Non-Resident</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="taxExemption" class="form-label">Tax Exemption Status</label>
                                                <select class="form-select" id="taxExemption" >
                                                    <option value="" disabled selected>Select Tax Exemption Status</option>
                                                    <option value="none">None</option>
                                                    <option value="disabled">Disabled</option>
                                                    <option value="dependent">Dependent</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="taxNotes" class="form-label">Additional Tax Information</label>
                                        <textarea class="form-control" id="taxNotes" style="height: 100px;" placeholder="Enter additional tax information"></textarea>
                                        <div class="form-text">
                                            Any additional tax-related information
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Employment Information Tab -->
                        <div class="tab-pane fade" id="employmentInfo" role="tabpanel" aria-labelledby="employmentInfoTab">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="staffId" class="form-label">Staff ID</label>
                                        <input type="text" class="form-control" id="staffId" placeholder="Staff ID"  aria-readonly="true">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="joinDate" class="form-label">Join Date</label>
                                        <input type="date" class="form-control" id="joinDate" >
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="department" class="form-label">Department</label>
                                <select class="form-select" id="department" >
                                    <option value="" disabled selected>Select Department</option>
                                    <option value="it">IT</option>
                                    <option value="hr">Human Resources</option>
                                    <option value="finance">Finance</option>
                                    <option value="marketing">Marketing</option>
                                    <option value="operations">Operations</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="position" class="form-label">Position</label>
                                <select class="form-select" id="position" >
                                    <option value="" disabled selected>Select Position</option>
                                    <option value="manager">Manager</option>
                                    <option value="supervisor">Supervisor</option>
                                    <option value="team_lead">Team Lead</option>
                                    <option value="staff">Staff</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="supervisor" class="form-label">Supervisor</label>
                                <select class="form-select" id="supervisor" >
                                    <option value="" disabled selected>Select Supervisor</option>
                                    <option value="1">John Doe (IT Manager)</option>
                                    <option value="2">Jane Smith (HR Manager)</option>
                                </select>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="employmentType" class="form-label">Employment Type</label>
                                        <select class="form-select" id="employmentType" >
                                            <option value="" disabled selected>Select Type</option>
                                            <option value="full_time">Full-time</option>
                                            <option value="part_time">Part-time</option>
                                            <option value="contract">Contract</option>
                                            <option value="intern">Intern</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="probationStatus" class="form-label">Probation Status</label>
                                        <select class="form-select" id="probationStatus" >
                                            <option value="not_started">Not Started</option>
                                            <option value="in_progress">In Progress</option>
                                            <option value="completed">Completed</option>
                                            <option value="extended">Extended</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="employmentStatus" class="form-label">Employment Status</label>
                                <select class="form-select" id="employmentStatus" >
                                    <option value="active">Active</option>
                                    <option value="on_leave">On Leave</option>
                                    <option value="suspended">Suspended</option>
                                    <option value="resigned">Resigned</option>
                                    <option value="terminated">Terminated</option>
                                </select>
                            </div>
                            </div>
                        <!-- Bank Information Tab -->
                        <div class="tab-pane fade" id="bankInfo" role="tabpanel" aria-labelledby="bankInfoTab">
                            <h3 class="h5 mb-3">Bank Account Information</h3>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="bankName" class="form-label">Bank Name</label>
                                        <select class="form-select" id="bankName" >
                                            <option value="" disabled selected>Select Bank</option>
                                            <option value="GCB Bank">GCB Bank</option>
                                            <option value="Ecobank Ghana">Ecobank Ghana</option>
                                            <option value="Fidelity Bank Ghana">Fidelity Bank Ghana</option>
                                            <option value="CalBank">CalBank</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="branchName" class="form-label">Branch Name</label>
                                        <input type="text" class="form-control" id="branchName" placeholder="Enter branch name" >
                                        <div class="form-text">e.g., Accra Main, Kumasi Branch</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="accountNumber" class="form-label">Account Number</label>
                                        <input type="text" class="form-control" id="accountNumber" placeholder="Enter account number" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="accountType" class="form-label">Account Type</label>
                                        <select class="form-select" id="accountType" >
                                            <option value="" disabled selected>Select Account Type</option>
                                            <option value="savings">Savings Account</option>
                                            <option value="current">Current/Checking Account</option>
                                            <option value="fixed">Fixed Deposit</option>
                                            <option value="dollar">Dollar Account</option>
                                            <option value="euro">Euro Account</option>
                                            <option value="business">Business Account</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="currency" class="form-label">Account Currency</label>
                                        <select class="form-select" id="currency" >
                                            <option value="GHS" selected>Ghana Cedi (GHS)</option>
                                            <option value="USD">US Dollar (USD)</option>
                                            <option value="EUR">Euro (EUR)</option>
                                            <option value="GBP">British Pound (GBP)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="ezwichNumber" class="form-label">E-Zwich Number</label>
                                        <input type="text" class="form-control" id="ezwichNumber" placeholder="Enter E-Zwich number">
                                        <div class="form-text">For Ghana's national switch system</div>
                                    </div>
                                </div>
                            </div>
                            <!-- Mobile Money Details -->
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h4 class="card-title h6 mb-0">Mobile Money Details (Optional)</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="mobileNetwork" class="form-label">Mobile Network</label>
                                                <select class="form-select" id="mobileNetwork">
                                                    <option value="" disabled selected>Select Network</option>
                                                    <option value="mtn">MTN Mobile Money</option>
                                                    <option value="vodafone">Vodafone Cash</option>
                                                    <option value="airteltigo">AirtelTigo Money</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="mobileNumber" class="form-label">Mobile Number</label>
                                                <input type="tel" class="form-control" id="mobileNumber" placeholder="Enter mobile number">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="mobileName" class="form-label">Account Name</label>
                                                <input type="text" class="form-control" id="mobileName" placeholder="Enter account name">
                                                <div class="form-text">Name on mobile money account</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Account Verification -->
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h4 class="card-title h6 mb-0">Account Verification</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="bankStatement" class="form-label">Upload Bank Statement</label>
                                                <input class="form-control" type="file" id="bankStatement" accept=".pdf,.jpg,.jpeg,.png" aria-label="Upload bank statement">
                                                <div class="form-text">Max file size: 5MB (PDF, JPG, PNG)</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Verification Status</label>
                                                <div class="form-control bg-light">
                                                    <span class="badge bg-warning">Pending Verification</span>
                                                    <small class="d-block text-muted mt-1">Will be verified by HR</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Additional Notes -->
                            <div class="form-group">
                                <label for="bankNotes" class="form-label">Additional Notes</label>
                                <textarea class="form-control" id="bankNotes" style="height: 100px;" placeholder="Enter additional notes"></textarea>
                                <div class="form-text">Any special instructions about this account</div>
                            </div>
                        </div>
                        <!-- Emergency Contact Tab -->
                        <div class="tab-pane fade" id="emergencyContact" role="tabpanel" aria-labelledby="emergencyContactTab">
                            <h3 class="h5 mb-4">Emergency Contacts</h3>
                            <!-- Primary Emergency Contact -->
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h4 class="card-title h6 mb-0">Primary Emergency Contact</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="primaryEmergencyName" class="form-label">Full Name</label>
                                                <input type="text" class="form-control" id="primaryEmergencyName" placeholder="Enter full name" >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="primaryEmergencyRelation" class="form-label">Relationship</label>
                                                <select class="form-select" id="primaryEmergencyRelation" >
                                                    <option value="" disabled selected>Select Relationship</option>
                                                    <option value="spouse">Spouse</option>
                                                    <option value="parent">Parent</option>
                                                    <option value="sibling">Sibling</option>
                                                    <option value="child">Child</option>
                                                    <option value="friend">Friend</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="primaryEmergencyPhone" class="form-label">Phone Number</label>
                                                <input type="tel" class="form-control" id="primaryEmergencyPhone" placeholder="Enter phone number">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="primaryEmergencyEmail" class="form-label">Email Address</label>
                                                <input type="email" class="form-control" id="primaryEmergencyEmail" placeholder="Enter email address">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="primaryEmergencyAltPhone" class="form-label">Alternative Phone</label>
                                                <input type="tel" class="form-control" id="primaryEmergencyAltPhone" placeholder="Enter alternative phone">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="primaryEmergencyAddress" class="form-label">Residential Address</label>
                                        <textarea class="form-control" id="primaryEmergencyAddress" style="height: 100px;" placeholder="Enter residential address" ></textarea>
                                    </div>
                                </div>
                            </div>
                            <!-- Secondary Emergency Contact -->
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h4 class="card-title h6 mb-0">Secondary Emergency Contact</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="secondaryEmergencyName" class="form-label">Full Name</label>
                                                <input type="text" class="form-control" id="secondaryEmergencyName" placeholder="Enter full name" >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="secondaryEmergencyRelation" class="form-label">Relationship</label>
                                                <select class="form-select" id="secondaryEmergencyRelation" >
                                                    <option value="" disabled selected>Select Relationship</option>
                                                    <option value="spouse">Spouse</option>
                                                    <option value="parent">Parent</option>
                                                    <option value="sibling">Sibling</option>
                                                    <option value="child">Child</option>
                                                    <option value="friend">Friend</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="secondaryEmergencyPhone" class="form-label">Phone Number</label>
                                                <input type="tel" class="form-control" id="secondaryEmergencyPhone" placeholder="Enter phone number" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="secondaryEmergencyEmail" class="form-label">Email Address</label>
                                                <input type="email" class="form-control" id="secondaryEmergencyEmail" placeholder="Enter email address">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="secondaryEmergencyAltPhone" class="form-label">Alternative Phone</label>
                                                <input type="tel" class="form-control" id="secondaryEmergencyAltPhone" placeholder="Enter alternative phone">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="secondaryEmergencyAddress" class="form-label">Residential Address</label>
                                        <textarea class="form-control" id="secondaryEmergencyAddress" style="height: 100px;" placeholder="Enter residential address" ></textarea>
                                    </div>
                                </div>
                            </div>
                            <!-- Medical Information -->
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h4 class="card-title h6 mb-0">Medical Information</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="bloodGroup" class="form-label">Blood Group</label>
                                                <select class="form-select" id="bloodGroup">
                                                    <option value="" disabled selected>Select Blood Group</option>
                                                    <option value="A+">A+ (A Positive)</option>
                                                    <option value="A-">A- (A Negative)</option>
                                                    <option value="B+">B+ (B Positive)</option>
                                                    <option value="B-">B- (B Negative)</option>
                                                    <option value="AB+">AB+ (AB Positive)</option>
                                                    <option value="AB-">AB- (AB Negative)</option>
                                                    <option value="O+">O+ (O Positive)</option>
                                                    <option value="O-">O- (O Negative)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="nhisNumberMedical" class="form-label">NHIS Number</label>
                                                <input type="text" class="form-control" id="nhisNumberMedical" placeholder="Enter NHIS number">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="form-label">Known Allergies</label>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="allergyNone" value="none">
                                            <label class="form-check-label" for="allergyNone">No known allergies</label>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input allergy-check" type="checkbox" id="allergyFood" value="food">
                                                    <label class="form-check-label" for="allergyFood">Food</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input allergy-check" type="checkbox" id="allergyDrugs" value="drugs">
                                                    <label class="form-check-label" for="allergyDrugs">Drugs/Medication</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input allergy-check" type="checkbox" id="allergyPollen" value="pollen">
                                                    <label class="form-check-label" for="allergyPollen">Pollen</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input allergy-check" type="checkbox" id="allergyDust" value="dust">
                                                    <label class="form-check-label" for="allergyDust">Dust</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input allergy-check" type="checkbox" id="allergyLatex" value="latex">
                                                    <label class="form-check-label" for="allergyLatex">Latex</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" id="allergyOther" value="other">
                                                    <label class="form-check-label" for="allergyOther">Other</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="allergyDetails" class="form-label">Allergy Details</label>
                                            <textarea class="form-control" id="allergyDetails" style="height: 100px;" placeholder="Enter allergy details"></textarea>
                                            <div class="form-text">Details about allergies, including severity and reactions</div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="medicalConditions" class="form-label">Existing Medical Conditions</label>
                                        <textarea class="form-control" id="medicalConditions" style="height: 100px;" placeholder="Enter medical conditions"></textarea>
                                        <div class="form-text">List any chronic illnesses or disabilities</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="specialNeeds" class="form-label">Special Needs or Accommodations</label>
                                        <textarea class="form-control" id="specialNeeds" style="height: 100px;" placeholder="Enter special needs"></textarea>
                                        <div class="form-text">Specify any workplace accommodations required</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Documents Tab -->
                        <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documentsTab">
                            <h3 class="h5 mb-4">Upload Documents</h3>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="resume" class="form-label">Resume/CV</label>
                                        <input type="file" class="form-control" id="resume" accept=".pdf,.doc,.docx" aria-label="Upload resume">
                                        <div class="form-text">Supported formats: PDF, DOC, DOCX</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="coverLetter" class="form-label">Cover Letter</label>
                                        <input type="file" class="form-control" id="coverLetter" accept=".pdf,.doc,.docx" aria-label="Upload cover letter">
                                        <div class="form-text">Supported formats: PDF, DOC, DOCX</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="educationalCertificate" class="form-label">Educational Certificate</label>
                                        <input type="file" class="form-control" id="educationalCertificate" accept=".pdf,.jpg,.png" aria-label="Upload educational certificate">
                                        <div class="form-text">Supported formats: PDF, JPG, PNG</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="otherDocuments" class="form-label">Other Documents</label>
                                        <input type="file" class="form-control" id="otherDocuments" accept="*/*" multiple aria-label="Upload other documents">
                                        <div class="form-text">Multiple files allowed, all formats supported</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="documentNotes" class="form-label">Document Notes</label>
                                <textarea class="form-control" id="documentNotes" rows="4" placeholder="Enter any additional notes about the documents"></textarea>
                                <div class="form-text">Notes about the uploaded documents</div>
                            </div>
                            <!-- Document Status -->
                            <div class="form-group mb-4">
                                <label class="form-label">Document Status</label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="documentsComplete" name="documentsComplete">
                                    <label class="form-check-label" for="documentsComplete">All required documents have been uploaded</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="documentsVerified" name="documentsVerified">
                                    <label class="form-check-label" for="documentsVerified">Documents have been verified</label>
                                </div>
                            </div>
                            <!-- Document Upload Progress -->
                            <div class="card bg-light mb-4">
                                <div class="card-body">
                                    <h5 class="h6 mb-3">Upload Progress</h5>
                                    <div class="progress mb-2" style="height: 8px;">
                                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="d-flex justify-content-between small text-muted">
                                        <span>0% Complete</span>
                                        <span>0/4 documents</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Form Navigation Buttons -->
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Cancel">Cancel</button>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary" id="prevTabBtn" disabled aria-label="Previous Tab">Previous</button>
                            <button type="button" class="btn btn-outline-primary" id="nextTabBtn" aria-label="Next Tab">Next</button>
                            <button type="submit" class="btn btn-primary" id="submitBtn" style="display: none;" aria-label="Save Employee">Save Employee</button>
                        </div>
                    </div>
                </form> --}}


<form id="addEmployeeForm" method="POST" enctype="multipart/form-data">
    @csrf
    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs nav-justified mb-4" role="tablist" aria-label="Employee Information Tabs">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="personalInfoTab" data-bs-toggle="tab" href="#personalInfo" role="tab" aria-controls="personalInfo" aria-selected="true">
                <i class="fas fa-user d-sm-none"></i>
                <span class="d-none d-sm-inline">Personal Information</span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="employmentInfoTab" data-bs-toggle="tab" href="#employmentInfo" role="tab" aria-controls="employmentInfo" aria-selected="false">
                <i class="fas fa-briefcase d-sm-none"></i>
                <span class="d-none d-sm-inline">Employment Details</span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="bankInfoTab" data-bs-toggle="tab" href="#bankInfo" role="tab" aria-controls="bankInfo" aria-selected="false">
                <i class="fas fa-university d-sm-none"></i>
                <span class="d-none d-sm-inline">Bank Information</span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="emergencyContactTab" data-bs-toggle="tab" href="#emergencyContact" role="tab" aria-controls="emergencyContact" aria-selected="false">
                <i class="fas fa-phone-alt d-sm-none"></i>
                <span class="d-none d-sm-inline">Emergency Contact</span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="documentsTab" data-bs-toggle="tab" href="#documents" role="tab" aria-controls="documents" aria-selected="false">
                <i class="fas fa-file-alt d-sm-none"></i>
                <span class="d-none d-sm-inline">Documents</span>
            </a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content p-3">
        <!-- Personal Information Tab -->
        <div class="tab-pane fade show active" id="personalInfo" role="tabpanel" aria-labelledby="personalInfoTab">
            <!-- Profile Picture Upload -->
            <div class="row mb-4">
                <div class="col-md-3 text-center">
                    <div class="position-relative d-inline-block">
                        <!-- Circle placeholder for profile picture -->
                        <div class="rounded-circle avatar-xl d-flex align-items-center justify-content-center bg-light border" id="profilePicturePreview" style="width: 120px; height: 120px; border: 2px dashed #dee2e6 !important; cursor: pointer;" onclick="document.getElementById('profilePicture').click()">
                            <i class="fas fa-user fa-3x text-muted"></i>
                        </div>
                        <div class="mt-3">
                            <input type="file" id="profilePicture" name="profile_picture" class="d-none" accept="image/*" aria-label="Upload profile picture">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('profilePicture').click()">
                                <i class="fas fa-camera me-1"></i> Upload Photo
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="firstName" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="firstName" name="first_name" placeholder="Enter first name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="middleName" class="form-label">Middle Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="middleName" name="middle_name" placeholder="Enter middle name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="lastName" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="lastName" name="last_name" placeholder="Enter last name">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="dateOfBirth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="dateOfBirth" name="date_of_birth">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="" disabled selected>Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Contact Information -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <label for="primaryPhone" class="form-label">Primary Phone <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="primaryPhone" name="primary_phone" placeholder="Enter primary phone">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <label for="secondaryPhone" class="form-label">Secondary Phone <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="secondaryPhone" name="secondary_phone" placeholder="Enter secondary phone">
                    </div>
                </div>
            </div>
            <!-- Email and Marital Status -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <label for="personalEmail" class="form-label">Personal Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="personalEmail" name="personal_email" placeholder="Enter personal email">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <label for="maritalStatus" class="form-label">Marital Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="maritalStatus" name="marital_status">
                            <option value="" disabled selected>Select Marital Status</option>
                            <option value="single">Single</option>
                            <option value="married">Married</option>
                            <option value="divorced">Divorced</option>
                            <option value="widowed">Widowed</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- Nationality -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <label for="nationality" class="form-label">Nationality <span class="text-danger">*</span></label>
                        <select class="form-select" id="nationality" name="nationality">
                            <option value="" disabled selected>Select Nationality</option>
                            <option value="GH">Ghanaian</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                        <select class="form-select" id="country" name="country">
                            <option value="" disabled selected>Select Country</option>
                            <option value="GH">Ghana</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <label for="region" class="form-label">Region <span class="text-danger">*</span></label>
                        <select class="form-select" id="region" name="region">
                            <option value="" disabled selected>Select Region</option>
                            <option value="Ahafo">Ahafo Region</option>
                            <option value="Ashanti">Ashanti Region</option>
                            <option value="Bono">Bono Region</option>
                            <option value="Bono East">Bono East Region</option>
                            <option value="Central">Central Region</option>
                            <option value="Eastern">Eastern Region</option>
                            <option value="Greater Accra">Greater Accra Region</option>
                            <option value="North East">North East Region</option>
                            <option value="Northern">Northern Region</option>
                            <option value="Oti">Oti Region</option>
                            <option value="Savannah">Savannah Region</option>
                            <option value="Upper East">Upper East Region</option>
                            <option value="Upper West">Upper West Region</option>
                            <option value="Volta">Volta Region</option>
                            <option value="Western">Western Region</option>
                            <option value="Western North">Western North Region</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="city" name="city" placeholder="Enter city">
                    </div>
                </div>
            </div>
            <!-- Identification -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title h6 mb-0">Ghana Identification</h4>
                </div>
                <div class="card-body">
                    <!-- Identification 1 -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="idType1" class="form-label">Identification Type 1 <span class="text-danger">*</span></label>
                                <select class="form-select" id="idType1" name="id_type_1" onchange="toggleIdNumberField('idType1', 'idNumber1')">
                                    <option value="" selected disabled>Select ID Type</option>
                                    <option value="ghana_card">Ghana Card</option>
                                    <option value="passport">Passport</option>
                                    <option value="driving_license">Driver's License</option>
                                    <option value="voter_id">Voter ID</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="idNumber1" class="form-label">ID Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="idNumber1" name="id_number_1" placeholder="Enter ID number" disabled>
                            </div>
                        </div>
                    </div>
                    <!-- Identification 2 (Optional) -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="idType2" class="form-label">Identification Type 2 (Optional)</label>
                                <select class="form-select" id="idType2" name="id_type_2" onchange="toggleIdNumberField('idType2', 'idNumber2')">
                                    <option value="" selected disabled>Select ID Type</option>
                                    <option value="ghana_card">Ghana Card</option>
                                    <option value="passport">Passport</option>
                                    <option value="driving_license">Driver's License</option>
                                    <option value="voter_id">Voter ID</option>
                                    <option value="other">Other</option>
                                    <option value="none">None</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="idNumber2" class="form-label">ID Number</label>
                                <input type="text" class="form-control" id="idNumber2" name="id_number_2" placeholder="Enter ID number" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mt-2">
                                <label for="idNotes" class="form-label">Additional Identification Information</label>
                                <textarea class="form-control" id="idNotes" name="id_notes" rows="3" placeholder="Enter additional identification information"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tax Information -->
            <div class="card mt-3">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title h6 mb-0">Tax Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="tinNumber" class="form-label">TIN Number</label>
                                <input type="text" class="form-control" id="tinNumber" name="tin_number" placeholder="Enter TIN number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="ssnitNumber" class="form-label">SSNIT ID</label>
                                <input type="text" class="form-control" id="ssnitNumber" name="ssnit_number" placeholder="Enter SSNIT Number">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="taxStatus" class="form-label">Tax Residency Status</label>
                                <select class="form-select" id="taxStatus" name="tax_status">
                                    <option value="" disabled selected>Select Tax Status</option>
                                    <option value="resident">Resident</option>
                                    <option value="non-resident">Non-Resident</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="taxExemption" class="form-label">Tax Exemption Status</label>
                                <select class="form-select" id="taxExemption" name="tax_exemption">
                                    <option value="" disabled selected>Select Tax Exemption Status</option>
                                    <option value="none">None</option>
                                    <option value="disabled">Disabled</option>
                                    <option value="dependent">Dependent</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="taxNotes" class="form-label">Additional Tax Information</label>
                        <textarea class="form-control" id="taxNotes" name="tax_notes" style="height: 100px;" placeholder="Enter additional tax information"></textarea>
                        <div class="form-text">
                            Any additional tax-related information
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Employment Information Tab -->
        <div class="tab-pane fade" id="employmentInfo" role="tabpanel" aria-labelledby="employmentInfoTab">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="staffId" class="form-label">Staff ID</label>
                        <input type="text" class="form-control" id="staffId" name="staff_id" placeholder="Staff ID" readonly aria-readonly="true">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="joinDate" class="form-label">Join Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="joinDate" name="join_date">
                    </div>
                </div>
            </div>
            <div class="form-group mb-3">
                <label for="department" class="form-label">Department <span class="text-danger">*</span></label>
                <select class="form-select" id="department" name="department">
                    <option value="" disabled selected>Select Department</option>
                    <option value="finance">Finance</option>
                    <option value="home_connection_high_rise">Home Connection/ High Rise</option>
                    <option value="human_resource_administration">Human Resource/ Administration</option>
                    <option value="procurement_warehouse">Procurement/Warehouse</option>
                    <option value="commercial">Commercial</option>
                    <option value="gpon">GPON</option>
                    <option value="qehs">QEHS</option>
                    <option value="public_relations">Public Relations</option>
                    <option value="audit">Audit</option>
                    <option value="consultant_services">Consultant Services</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="position" class="form-label">Position <span class="text-danger">*</span></label>
                <select class="form-select" id="position" name="position">
                    <option value="" disabled selected>Select Position</option>
                    <option value="business_accounts_manager">Business Accounts Manager</option>
                    <option value="driver">Driver</option>
                    <option value="janitor">Janitor</option>
                    <option value="warehouse_supervisor">Warehouse Supervisor</option>
                    <option value="head_of_project">Head of Project</option>
                    <option value="commercial">Commercial</option>
                    <option value="project_team_lead">Project Team Lead</option>
                    <option value="project_administrator">Project Administrator</option>
                    <option value="project_manager">Project Manager</option>
                    <option value="qa_qc_trainee">QA/QC Trainee</option>
                    <option value="site_engineer">Site Engineer</option>
                    <option value="solutions_architect">Solutions Architect</option>
                    <option value="qa_qc_lead">QA/QC Lead</option>
                    <option value="qehs_manager">QEHS Manager</option>
                    <option value="office_hr_manager">Office /HR Manager</option>
                    <option value="procurement_manager">Procurement Manager</option>
                    <option value="project_assistant">Project Assistant</option>
                    <option value="general_services_coordinator">General Services Coordinator</option>
                    <option value="quality_officer_hc_hr">Quality officer-HC/HR</option>
                    <option value="it_officer">IT Officer</option>
                    <option value="customer_service">Customer Service</option>
                    <option value="gm_commercial">GM-Commercial</option>
                    <option value="home_connection_high_rise_manager">Home Connection /High Rise Manager</option>
                    <option value="project_supervisor">Project Supervisor</option>
                    <option value="site_supervisor">Site Supervisor</option>
                    <option value="project_auditor">Project Auditor</option>
                    <option value="accounts_officer">Accounts officer</option>
                    <option value="consultant_manager">Consultant Manager</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="supervisor" class="form-label">Supervisor <span class="text-danger">*</span></label>
                <select class="form-select" id="supervisor" name="supervisor_id">
                    <option value="" disabled selected>Select Supervisor</option>
                    <option value="1">John Doe (IT Manager)</option>
                    <option value="2">Jane Smith (HR Manager)</option>
                </select>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="employmentType" class="form-label">Employment Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="employmentType" name="employment_type">
                            <option value="" disabled selected>Select Type</option>
                            <option value="fixed_term">Fixed Term</option>
                            <option value="ind_contractors">Ind Contractors</option>
                            <option value="national_service">National Service</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="probationStatus" class="form-label">Probation Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="probationStatus" name="probation_status">
                            <option value="not_started">Not Started</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="extended">Extended</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group mb-3">
                <label for="employmentStatus" class="form-label">Employment Status</label>
                <select class="form-select" id="employmentStatus" name="employment_status">
                    <option value="active">Active</option>
                    <option value="on_leave">On Leave</option>
                    <option value="suspended">Suspended</option>
                    <option value="resigned">Resigned</option>
                    <option value="terminated">Terminated</option>
                </select>
            </div>
        </div>
        <!-- Bank Information Tab -->
        <div class="tab-pane fade" id="bankInfo" role="tabpanel" aria-labelledby="bankInfoTab">
            <h3 class="h5 mb-3">Bank Account Information</h3>
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="bankName" class="form-label">Bank Name</label>
                        <select class="form-select" id="bankName" name="bank_name">
                            <option value="" disabled selected>Select Bank</option>
                            <option value="GCB Bank">GCB Bank</option>
                            <option value="Ecobank Ghana">Ecobank Ghana</option>
                            <option value="Fidelity Bank Ghana">Fidelity Bank Ghana</option>
                            <option value="CalBank">CalBank</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="branchName" class="form-label">Branch Name</label>
                        <input type="text" class="form-control" id="branchName" name="branch_name" placeholder="Enter branch name">
                        <div class="form-text">e.g., Accra Main, Kumasi Branch</div>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="accountNumber" class="form-label">Account Number</label>
                        <input type="text" class="form-control" id="accountNumber" name="account_number" placeholder="Enter account number">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="accountType" class="form-label">Account Type</label>
                        <select class="form-select" id="accountType" name="account_type">
                            <option value="" disabled selected>Select Account Type</option>
                            <option value="savings">Savings Account</option>
                            <option value="current">Current/Checking Account</option>
                            <option value="fixed">Fixed Deposit</option>
                            <option value="dollar">Dollar Account</option>
                            <option value="euro">Euro Account</option>
                            <option value="business">Business Account</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="currency" class="form-label">Account Currency</label>
                        <select class="form-select" id="currency" name="currency">
                            <option value="GHS" selected>Ghana Cedi (GHS)</option>
                            <option value="USD">US Dollar (USD)</option>
                            <option value="EUR">Euro (EUR)</option>
                            <option value="GBP">British Pound (GBP)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="ezwichNumber" class="form-label">E-Zwich Number</label>
                        <input type="text" class="form-control" id="ezwichNumber" name="ezwich_number" placeholder="Enter E-Zwich number">
                        <div class="form-text">For Ghana's national switch system</div>
                    </div>
                </div>
            </div>
            <!-- Mobile Money Details -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title h6 mb-0">Mobile Money Details (Optional)</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="mobileNetwork" class="form-label">Mobile Network</label>
                                <select class="form-select" id="mobileNetwork" name="mobile_network">
                                    <option value="" disabled selected>Select Network</option>
                                    <option value="mtn">MTN Mobile Money</option>
                                    <option value="vodafone">Vodafone Cash</option>
                                    <option value="airteltigo">AirtelTigo Money</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="mobileNumber" class="form-label">Mobile Number</label>
                                <input type="tel" class="form-control" id="mobileNumber" name="mobile_number" placeholder="Enter mobile number">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="mobileName" class="form-label">Account Name</label>
                                <input type="text" class="form-control" id="mobileName" name="mobile_name" placeholder="Enter account name">
                                <div class="form-text">Name on mobile money account</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Account Verification -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title h6 mb-0">Account Verification</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="bankStatement" class="form-label">Upload Bank Statement</label>
                                <input class="form-control" type="file" id="bankStatement" name="bank_statement" accept=".pdf,.jpg,.jpeg,.png" aria-label="Upload bank statement">
                                <div class="form-text">Max file size: 5MB (PDF, JPG, PNG)</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Verification Status</label>
                                <div class="form-control bg-light">
                                    <span class="badge bg-warning">Pending Verification</span>
                                    <small class="d-block text-muted mt-1">Will be verified by HR</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Additional Notes -->
            <div class="form-group">
                <label for="bankNotes" class="form-label">Additional Notes</label>
                <textarea class="form-control" id="bankNotes" name="bank_notes" style="height: 100px;" placeholder="Enter additional notes"></textarea>
                <div class="form-text">Any special instructions about this account</div>
            </div>
        </div>
        <!-- Emergency Contact Tab -->
        <div class="tab-pane fade" id="emergencyContact" role="tabpanel" aria-labelledby="emergencyContactTab">
            <h3 class="h5 mb-4">Emergency Contacts</h3>
            <!-- Primary Emergency Contact -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title h6 mb-0">Primary Emergency Contact</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="primaryEmergencyName" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="primaryEmergencyName" name="primary_emergency_name" placeholder="Enter full name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="primaryEmergencyRelation" class="form-label">Relationship <span class="text-danger">*</span></label>
                                <select class="form-select" id="primaryEmergencyRelation" name="primary_emergency_relation">
                                    <option value="" disabled selected>Select Relationship</option>
                                    <option value="spouse">Spouse</option>
                                    <option value="parent">Parent</option>
                                    <option value="sibling">Sibling</option>
                                    <option value="child">Child</option>
                                    <option value="friend">Friend</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="primaryEmergencyPhone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="primaryEmergencyPhone" name="primary_emergency_phone" placeholder="Enter phone number">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="primaryEmergencyEmail" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="primaryEmergencyEmail" name="primary_emergency_email" placeholder="Enter email address">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="primaryEmergencyAltPhone" class="form-label">Alternative Phone <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="primaryEmergencyAltPhone" name="primary_emergency_alt_phone" placeholder="Enter alternative phone">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="primaryEmergencyAddress" class="form-label">Residential Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="primaryEmergencyAddress" name="primary_emergency_address" style="height: 100px;" placeholder="Enter residential address"></textarea>
                    </div>
                </div>
            </div>
            <!-- Secondary Emergency Contact -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title h6 mb-0">Secondary Emergency Contact</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="secondaryEmergencyName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="secondaryEmergencyName" name="secondary_emergency_name" placeholder="Enter full name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="secondaryEmergencyRelation" class="form-label">Relationship</label>
                                <select class="form-select" id="secondaryEmergencyRelation" name="secondary_emergency_relation">
                                    <option value="" disabled selected>Select Relationship</option>
                                    <option value="spouse">Spouse</option>
                                    <option value="parent">Parent</option>
                                    <option value="sibling">Sibling</option>
                                    <option value="child">Child</option>
                                    <option value="friend">Friend</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="secondaryEmergencyPhone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="secondaryEmergencyPhone" name="secondary_emergency_phone" placeholder="Enter phone number">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="secondaryEmergencyEmail" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="secondaryEmergencyEmail" name="secondary_emergency_email" placeholder="Enter email address">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="secondaryEmergencyAltPhone" class="form-label">Alternative Phone</label>
                                <input type="tel" class="form-control" id="secondaryEmergencyAltPhone" name="secondary_emergency_alt_phone" placeholder="Enter alternative phone">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="secondaryEmergencyAddress" class="form-label">Residential Address</label>
                        <textarea class="form-control" id="secondaryEmergencyAddress" name="secondary_emergency_address" style="height: 100px;" placeholder="Enter residential address"></textarea>
                    </div>
                </div>
            </div>
            <!-- Medical Information -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title h6 mb-0">Medical Information</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="bloodGroup" class="form-label">Blood Group</label>
                                <select class="form-select" id="bloodGroup" name="blood_group">
                                    <option value="" disabled selected>Select Blood Group</option>
                                    <option value="A+">A+ (A Positive)</option>
                                    <option value="A-">A- (A Negative)</option>
                                    <option value="B+">B+ (B Positive)</option>
                                    <option value="B-">B- (B Negative)</option>
                                    <option value="AB+">AB+ (AB Positive)</option>
                                    <option value="AB-">AB- (AB Negative)</option>
                                    <option value="O+">O+ (O Positive)</option>
                                    <option value="O-">O- (O Negative)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="nhisNumberMedical" class="form-label">NHIS Number</label>
                                <input type="text" class="form-control" id="nhisNumberMedical" name="nhis_number" placeholder="Enter NHIS number">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label">Known Allergies</label>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="allergyNone" name="known_allergies[]" value="none">
                            <label class="form-check-label" for="allergyNone">No known allergies</label>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check mb-2">
                                    <input class="form-check-input allergy-check" type="checkbox" id="allergyFood" name="known_allergies[]" value="food">
                                    <label class="form-check-label" for="allergyFood">Food</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input allergy-check" type="checkbox" id="allergyDrugs" name="known_allergies[]" value="drugs">
                                    <label class="form-check-label" for="allergyDrugs">Drugs/Medication</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mb-2">
                                    <input class="form-check-input allergy-check" type="checkbox" id="allergyPollen" name="known_allergies[]" value="pollen">
                                    <label class="form-check-label" for="allergyPollen">Pollen</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input allergy-check" type="checkbox" id="allergyDust" name="known_allergies[]" value="dust">
                                    <label class="form-check-label" for="allergyDust">Dust</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mb-2">
                                    <input class="form-check-input allergy-check" type="checkbox" id="allergyLatex" name="known_allergies[]" value="latex">
                                    <label class="form-check-label" for="allergyLatex">Latex</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="allergyOther" name="known_allergies[]" value="other">
                                    <label class="form-check-label" for="allergyOther">Other</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="allergyDetails" class="form-label">Allergy Details</label>
                            <textarea class="form-control" id="allergyDetails" name="allergy_details" style="height: 100px;" placeholder="Enter allergy details"></textarea>
                            <div class="form-text">Details about allergies, including severity and reactions</div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="medicalConditions" class="form-label">Existing Medical Conditions</label>
                        <textarea class="form-control" id="medicalConditions" name="medical_conditions" style="height: 100px;" placeholder="Enter medical conditions"></textarea>
                        <div class="form-text">List any chronic illnesses or disabilities</div>
                    </div>
                    <div class="form-group">
                        <label for="specialNeeds" class="form-label">Special Needs or Accommodations</label>
                        <textarea class="form-control" id="specialNeeds" name="special_needs" style="height: 100px;" placeholder="Enter special needs"></textarea>
                        <div class="form-text">Specify any workplace accommodations required</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Documents Tab -->
        <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documentsTab">
            <h3 class="h5 mb-4">Upload Documents</h3>
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="resume" class="form-label">Resume/CV <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="resume" name="resume" accept=".pdf,.doc,.docx" aria-label="Upload resume">
                        <div class="form-text">Supported formats: PDF, DOC, DOCX</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="coverLetter" class="form-label">Cover Letter <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="coverLetter" name="cover_letter" accept=".pdf,.doc,.docx" aria-label="Upload cover letter">
                        <div class="form-text">Supported formats: PDF, DOC, DOCX</div>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="educationalCertificate" class="form-label">Educational Certificate <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="educationalCertificate" name="educational_certificate" accept=".pdf,.jpg,.png" aria-label="Upload educational certificate">
                        <div class="form-text">Supported formats: PDF, JPG, PNG</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="otherDocuments" class="form-label">Other Documents</label>
                        <input type="file" class="form-control" id="otherDocuments" name="other_documents[]" accept="*/*" multiple aria-label="Upload other documents">
                        <div class="form-text">Multiple files allowed, all formats supported</div>
                    </div>
                </div>
            </div>
            <div class="form-group mb-4">
                <label for="documentNotes" class="form-label">Document Notes</label>
                <textarea class="form-control" id="documentNotes" name="document_notes" rows="4" placeholder="Enter any additional notes about the documents"></textarea>
                <div class="form-text">Notes about the uploaded documents</div>
            </div>
            <!-- Document Status -->
            <div class="form-group mb-4">
                <label class="form-label">Document Status</label>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="documentsComplete" name="documents_complete">
                    <label class="form-check-label" for="documentsComplete">All required documents have been uploaded</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="documentsVerified" name="documents_verified">
                    <label class="form-check-label" for="documentsVerified">Documents have been verified</label>
                </div>
            </div>
            <!-- Document Upload Progress -->
            <div class="card bg-light mb-4">
                <div class="card-body">
                    <h5 class="h6 mb-3">Upload Progress</h5>
                    <div class="progress mb-2" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between small text-muted">
                        <span>0% Complete</span>
                        <span>0/4 documents</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Form Navigation Buttons -->
    <div class="d-flex justify-content-between mt-4">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Cancel">Cancel</button>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary" id="prevTabBtn" disabled aria-label="Previous Tab">Previous</button>
            <button type="button" class="btn btn-outline-primary" id="nextTabBtn" aria-label="Next Tab">Next</button>
            <button type="submit" class="btn btn-primary" id="submitBtn" style="display: none;" aria-label="Save Employee">Save Employee</button>
        </div>
    </div>
</form>

            </div>
        </div>
    </div>
</div>

<!-- Success Toasts -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="editSuccessToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i>
                Employee details updated successfully!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    <div id="messageSuccessToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i>
                Message sent successfully!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>







  <!-- View Employee Modal -->
                            <div class="modal fade" id="viewEmployeeModal" tabindex="-1" aria-labelledby="viewEmployeeModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header bg-light">
                                            <h5 class="modal-title" id="viewEmployeeModalLabel">Employee Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-4 text-center mb-4">
                                                    <img src="https://ui-avatars.com/api/?name=Employee&background=4e73df&color=fff&size=150" alt="Employee Photo" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                                                    <h5 class="mb-1">Spiderman Roach</h5>
                                                    <p class="text-muted mb-0">EMP-001</p>
                                                    <span class="badge bg-success mt-2">Active</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <h6 class="mb-3">Personal Information</h6>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <p class="mb-1"><strong>Email:</strong> spiderman@example.com</p>
                                                            <p class="mb-1"><strong>Phone:</strong> +233 245 678 901</p>
                                                            <p class="mb-1"><strong>Department:</strong> IT</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1"><strong>Position:</strong> Senior Developer</p>
                                                            <p class="mb-1"><strong>Employment Type:</strong> Full-time</p>
                                                            <p class="mb-1"><strong>Join Date:</strong> 2023-06-15</p>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <h6 class="mb-3">Emergency Contact</h6>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <p class="mb-1"><strong>Name:</strong> Mary Roach</p>
                                                            <p class="mb-1"><strong>Relationship:</strong> Mother</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1"><strong>Phone:</strong> +233 245 678 902</p>
                                                            <p class="mb-1"><strong>Address:</strong> 123 Main St, Accra</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary" id="printProfileBtn">Print Profile</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Edit Employee Modal -->
                            <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header bg-light">
                                            <h5 class="modal-title" id="editEmployeeModalLabel">Edit Employee</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form id="editEmployeeForm">
                                            <div class="modal-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-bs-toggle="tab" href="#editPersonalInfo" role="tab">Personal Info</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#editEmploymentInfo" role="tab">Employment</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#editContactInfo" role="tab">Contact</a>
                                                    </li>
                                                    
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#editBankInfo" role="tab">Bank Info</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#editDocuments" role="tab">Documents</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content p-3 border border-top-0">
                                                    <div class="tab-pane fade show active" id="editPersonalInfo" role="tabpanel">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label for="editFirstName" class="form-label">First Name</label>
                                                                <input type="text" class="form-control" id="editFirstName" name="first_name" value="Spiderman" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="editLastName" class="form-label">Last Name</label>
                                                                <input type="text" class="form-control" id="editLastName" name="last_name" value="Roach" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="editEmail" class="form-label">Email</label>
                                                                <input type="email" class="form-control" id="editEmail" name="personal_email" value="spiderman@example.com" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="editPhone" class="form-label">Phone</label>
                                                                <input type="tel" class="form-control" id="editPhone" name="primary_phone" value="+233245678901" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="editEmploymentInfo" role="tabpanel">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label for="editDepartment" class="form-label">Department</label>
                                                                <select class="form-select" name="department" id="editDepartment" required>
                                                                    <option value="finance" selected>Finance</option>
                                                                    <option value="home_connection_high_rise">Home Connection/ High Rise</option>
                                                                    <option value="human_resource_administration">Human Resource/ Administration</option>
                                                                    <option value="procurement_warehouse">Procurement/Warehouse</option>
                                                                    <option value="commercial">Commercial</option>
                                                                    <option value="gpon">GPON</option>
                                                                    <option value="qehs">QEHS</option>
                                                                    <option value="public_relations">Public Relations</option>
                                                                    <option value="audit">Audit</option>
                                                                    <option value="consultant_services">Consultant Services</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="editPosition" class="form-label">Position</label>
                                                                <select class="form-select" id="editPosition" name="position" required>
                                                                    <option value="" disabled selected>Select Position</option>
                                                                    <option value="business_accounts_manager">Business Accounts Manager</option>
                                                                    <option value="driver">Driver</option>
                                                                    <option value="janitor">Janitor</option>
                                                                    <option value="warehouse_supervisor">Warehouse Supervisor</option>
                                                                    <option value="head_of_project">Head of Project</option>
                                                                    <option value="commercial">Commercial</option>
                                                                    <option value="project_team_lead">Project Team Lead</option>
                                                                    <option value="project_administrator">Project Administrator</option>
                                                                    <option value="project_manager">Project Manager</option>
                                                                    <option value="qa_qc_trainee">QA/QC Trainee</option>
                                                                    <option value="site_engineer">Site Engineer</option>
                                                                    <option value="solutions_architect">Solutions Architect</option>
                                                                    <option value="qa_qc_lead">QA/QC Lead</option>
                                                                    <option value="qehs_manager">QEHS Manager</option>
                                                                    <option value="office_hr_manager">Office /HR Manager</option>
                                                                    <option value="procurement_manager">Procurement Manager</option>
                                                                    <option value="project_assistant">Project Assistant</option>
                                                                    <option value="general_services_coordinator">General Services Coordinator</option>
                                                                    <option value="quality_officer_hc_hr">Quality officer-HC/HR</option>
                                                                    <option value="it_officer">IT Officer</option>
                                                                    <option value="customer_service">Customer Service</option>
                                                                    <option value="gm_commercial">GM-Commercial</option>
                                                                    <option value="home_connection_high_rise_manager">Home Connection /High Rise Manager</option>
                                                                    <option value="project_supervisor">Project Supervisor</option>
                                                                    <option value="site_supervisor">Site Supervisor</option>
                                                                    <option value="project_auditor">Project Auditor</option>
                                                                    <option value="accounts_officer">Accounts officer</option>
                                                                    <option value="consultant_manager">Consultant Manager</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="editEmploymentType" class="form-label">Employment Type</label>
                                                                <select class="form-select" id="editEmploymentType" name="employment_type" required>
                                                                    <option value="fixed_term" selected>Fixed Term</option>
                                                                    <option value="ind_contractors">Ind Contractors</option>
                                                                    <option value="national_service">National Service</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="editJoinDate" class="form-label">Join Date</label>
                                                                <input type="date" class="form-control" id="editJoinDate" name="join_date" value="" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="editContactInfo" role="tabpanel">
                                                        <div class="row g-3">
                                                            <div class="col-12">
                                                                <h6>Emergency Contact</h6>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="editEmergencyName" class="form-label">Name</label>
                                                                <input type="text" class="form-control" id="editEmergencyName" name="primary_emergency_name" value="" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="editEmergencyRelation" class="form-label">Relationship</label>
                                                                <input type="text" class="form-control" id="editEmergencyRelation" name="primary_emergency_relation" value="" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="editEmergencyPhone" class="form-label">Phone</label>
                                                                <input type="tel" class="form-control" id="editEmergencyPhone" name="primary_emergency_phone" value="" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="editEmergencyAddress" class="form-label">Address</label>
                                                                <input type="text" class="form-control" id="editEmergencyAddress" name="primary_emergency_address" value="" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="editBankInfo" role="tabpanel">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label for="editBankName" class="form-label">Bank Name</label>
                                                                <input type="text" class="form-control" id="editBankName" name="bank_name" value="">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="editBranchName" class="form-label">Branch Name</label>
                                                                <input type="text" class="form-control" id="editBranchName" name="branch_name" value="">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="editAccountNumber" class="form-label">Account Number</label>
                                                                <input type="text" class="form-control" id="editAccountNumber" name="account_number" value="">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="editAccountType" class="form-label">Account Type</label>
                                                                <select class="form-select" id="editAccountType" name="account_type">
                                                                    <option value="">Select Account Type</option>
                                                                    <option value="savings">Savings</option>
                                                                    <option value="current">Current</option>
                                                                    <option value="fixed">Fixed</option>
                                                                    <option value="dollar">Dollar</option>
                                                                    <option value="euro">Euro</option>
                                                                    <option value="business">Business</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="editCurrency" class="form-label">Currency</label>
                                                                <input type="text" class="form-control" id="editCurrency" name="currency" value="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="editDocuments" role="tabpanel">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label for="editResume" class="form-label">Resume</label>
                                                                <input type="file" class="form-control" id="editResume" name="resume" accept=".pdf,.doc,.docx">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="editCoverLetter" class="form-label">Cover Letter</label>
                                                                <input type="file" class="form-control" id="editCoverLetter" name="cover_letter" accept=".pdf,.doc,.docx">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="editEducationalCertificate" class="form-label">Educational Certificate</label>
                                                                <input type="file" class="form-control" id="editEducationalCertificate" name="educational_certificate" accept=".pdf,.jpg,.png">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="editOtherDocuments" class="form-label">Other Documents</label>
                                                                <input type="file" class="form-control" id="editOtherDocuments" name="other_documents[]" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer d-flex justify-content-between align-items-center">
                                                <div>
                                                    <button type="button" class="btn btn-outline-secondary" id="editPrevTabBtn">Prev</button>
                                                    <button type="button" class="btn btn-outline-primary ms-2" id="editNextTabBtn">Next</button>
                                                </div>
                                                <div>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary" id="editSubmitBtn" style="display: none;">Save Changes</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Send Message Modal -->
                            <div class="modal fade" id="sendMessageModal" tabindex="-1" aria-labelledby="sendMessageModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title" id="sendMessageModalLabel">Send Message</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form id="messageForm" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="recipientEmail" class="form-label">To</label>
                                                    <input type="email" class="form-control" id="recipientEmail" name="email"   readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="messageSubject" class="form-label">Subject</label>
                                                    <input type="text" class="form-control" id="messageSubject" name="subject" placeholder="Enter subject">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="messageBody" class="form-label">Message</label>
                                                    <textarea class="form-control" id="messageBody" rows="5" name="body" placeholder="Type your message here..." ></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="attachment" class="form-label">Attachment (Optional)</label>
                                                    <input class="form-control" type="file" id="attachment" name="attachment">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-paper-plane me-1"></i> Send Message
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Delete Confirmation Modal -->
                            <div class="modal fade" id="deleteEmployeeModal" tabindex="-1" aria-labelledby="deleteEmployeeModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title" id="deleteEmployeeModalLabel">Confirm Deletion</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="text-center mb-4">
                                                <i class="fas fa-exclamation-triangle text-danger" style="font-size: 4rem;"></i>
                                                <h4 class="mt-3">Are you sure?</h4>
                                                <p>You are about to delete <strong><span id="deleteEmployeeName">Loading...</span> (<span id="deleteEmployeeId">Loading...</span>)</strong>. This action cannot be undone.</p>
                                            </div>
                                            <div class="alert alert-warning" role="alert">
                                                <i class="fas fa-exclamation-circle me-2"></i>
                                                <strong>Warning:</strong> This will permanently delete all employee data, including attendance, leave, and payroll records.
                                            </div>
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="confirmDelete">
                                                <label class="form-check-label" for="confirmDelete">
                                                    I understand that this action cannot be undone
                                                </label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-danger" id="confirmDeleteBtn" disabled>
                                                <i class="fas fa-trash-alt me-1"></i> Delete Permanently
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Add this in your <head> or before your script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Tab Navigation Only - only select tabs from the active form
const tabs = document.querySelectorAll('#addEmployeeForm .nav-tabs a');
const prevTabBtn = document.querySelector('#prevTabBtn');
const nextTabBtn = document.querySelector('#nextTabBtn');
const submitBtn = document.querySelector('#submitBtn');
let currentTab = 0;

function updateButtons() {
    prevTabBtn.disabled = currentTab === 0;
    nextTabBtn.disabled = currentTab === tabs.length - 1;
    
    // Show submit button on last tab (Documents tab)
    const isLastTab = currentTab === tabs.length - 1;
    
    if (isLastTab) {
        // On the last tab, always show submit button and hide next button
        submitBtn.style.display = 'inline-block';
        nextTabBtn.style.display = 'none';
    } else {
        // On other tabs, show next button and hide submit button
        submitBtn.style.display = 'none';
        nextTabBtn.style.display = 'inline-block';
    }
}

// Provide a safe fallback for fetchEmployees so callbacks don't break
if (typeof window.fetchEmployees !== 'function') {
    window.fetchEmployees = function () {
        // As a fallback, reload the page to reflect latest data
        try { window.location.reload(); } catch (e) { /* noop */ }
    };
}

// Edit form tab navigation
function updateEditButtons() {
    const $tabs = $('#editEmployeeForm .nav.nav-tabs a');
    const currentIndex = $tabs.index($tabs.filter('.active'));
    const isLast = currentIndex === $tabs.length - 1;
    $('#editPrevTabBtn').prop('disabled', currentIndex <= 0);
    $('#editNextTabBtn').toggle(!isLast);
    $('#editSubmitBtn').toggle(isLast);
}

$(document).on('click', '#editPrevTabBtn', function () {
    const $tabs = $('#editEmployeeForm .nav.nav-tabs a');
    const currentIndex = $tabs.index($tabs.filter('.active'));
    if (currentIndex > 0) {
        $tabs.eq(currentIndex - 1).tab('show');
        updateEditButtons();
    }
});

$(document).on('click', '#editNextTabBtn', function () {
    const $tabs = $('#editEmployeeForm .nav.nav-tabs a');
    const currentIndex = $tabs.index($tabs.filter('.active'));
    if (currentIndex < $tabs.length - 1) {
        $tabs.eq(currentIndex + 1).tab('show');
        updateEditButtons();
    }
});

// Keep buttons in sync on manual tab clicks
$(document).on('shown.bs.tab', '#editEmployeeForm .nav.nav-tabs a', function () {
    updateEditButtons();
});

// Function to get missing fields with their display names
function getMissingFields() {
    // Use the same field structure as validateAllTabs()
    const tabFields = {
        'Personal Information': [
            { id: 'firstName', name: 'First Name' },
            { id: 'lastName', name: 'Last Name' },
            { id: 'dateOfBirth', name: 'Date of Birth' },
            { id: 'gender', name: 'Gender' },
            { id: 'primaryPhone', name: 'Primary Phone' },
            { id: 'personalEmail', name: 'Personal Email' },
            { id: 'maritalStatus', name: 'Marital Status' },
            { id: 'nationality', name: 'Nationality' },
            { id: 'country', name: 'Country' },
            { id: 'region', name: 'Region' },
            { id: 'city', name: 'City' }
        ],
        'Employment Details': [
            { id: 'joinDate', name: 'Join Date' },
            { id: 'department', name: 'Department' },
            { id: 'position', name: 'Position' },
            { id: 'employmentType', name: 'Employment Type' }
        ],
        'Bank Info': [
            // Bank info fields are optional, no required validation
        ],
        'Emergency Contact': [
            { id: 'primaryEmergencyName', name: 'Emergency Contact Name' },
            { id: 'primaryEmergencyRelation', name: 'Emergency Contact Relationship' },
            { id: 'primaryEmergencyPhone', name: 'Emergency Contact Phone' },
            { id: 'primaryEmergencyEmail', name: 'Emergency Contact Email' },
            { id: 'primaryEmergencyAltPhone', name: 'Emergency Contact Alternative Phone' },
            { id: 'primaryEmergencyAddress', name: 'Emergency Contact Address' }
        ],
        'Documents': [
            { id: 'documentsComplete', name: 'Document Upload Confirmation', type: 'checkbox' },
            { id: 'documentsVerified', name: 'Document Verification Confirmation', type: 'checkbox' }
        ]
    };
    
    const missingFields = [];
    
    // Check each tab's required fields
    Object.keys(tabFields).forEach(tabName => {
        const fields = tabFields[tabName];
        
        fields.forEach(field => {
            const element = document.getElementById(field.id);
            if (element) {
                // Handle checkboxes differently
                if (field.type === 'checkbox') {
                    if (!element.checked) {
                        missingFields.push(field);
                    }
                } else {
                    // Handle regular input fields
                    if (!element.value || element.value.trim() === '') {
                        missingFields.push(field);
                    }
                }
            }
        });
    });
    
    return missingFields;
}

// Function to validate all required fields across all tabs
function validateAllRequiredFields() {
    const missingFields = getMissingFields();
    
    if (missingFields.length > 0) {
        console.log('Missing required fields:', missingFields.map(f => f.name));
        return false;
    }
    
    return true;
}

function showTab(index) {
    const tab = new bootstrap.Tab(tabs[index]);
    tab.show();
    currentTab = index;
    updateButtons();
}

prevTabBtn?.addEventListener('click', function () {
    if (currentTab > 0) {
        showTab(currentTab - 1);
    }
});

nextTabBtn?.addEventListener('click', function () {
    if (currentTab < tabs.length - 1) {
        showTab(currentTab + 1);
    }
});

// Initialize button state
updateButtons();

// Listen for tab changes when clicking directly on tab links
tabs.forEach((tab, index) => {
    tab.addEventListener('shown.bs.tab', function() {
        currentTab = index;
        updateButtons();
    });
});



// Profile Picture Upload
document.getElementById('profilePicture')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const previewElement = document.getElementById('profilePicturePreview');
    
    if (file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Please select a valid image file.');
            return;
        }
        
        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('File size must be less than 5MB.');
            return;
        }
        
        // Create image preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewElement.innerHTML = `<img src="${e.target.result}" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;" alt="Uploaded profile picture">`;
        };
        reader.readAsDataURL(file);
    } else {
        // Show the default circle placeholder
        previewElement.innerHTML = '<i class="fas fa-user fa-3x text-muted"></i>';
    }
});

function toggleIdNumberField(selectId, inputId) {
    const selectElement = document.getElementById(selectId);
    const inputElement = document.getElementById(inputId);
    
    if (!selectElement || !inputElement) return;
    
    // Enable/disable input based on selection
    if (selectElement.value && selectElement.value !== 'none') {
        inputElement.disabled = false;
        // inputElement.required = true;
        
        // Set placeholder based on selected ID type
        const idType = selectElement.options[selectElement.selectedIndex].text;
        inputElement.placeholder = `Enter ${idType} number`;

        
        // Pattern validation is handled by JavaScript validateIdNumber function
    } else {
        inputElement.disabled = true;
        // inputElement.required = false;

        inputElement.value = '';
        inputElement.placeholder = 'Select an ID type first';
    }
}

// Form Submission


// Initialize ID number fields
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the ID number fields
    const idType1 = document.getElementById('idType1');
    const idType2 = document.getElementById('idType2');
    
    if (idType1) toggleIdNumberField('idType1', 'idNumber1');
    if (idType2) toggleIdNumberField('idType2', 'idNumber2');
    
    // Initialize document upload progress tracking
    initializeDocumentProgress();
    
    // Add real-time validation to all required fields
    addRealTimeValidation();
});

// Document Upload Progress Tracking
function initializeDocumentProgress() {
    const documentFields = [
        'resume',
        'coverLetter', 
        'educationalCertificate',
        'otherDocuments'
    ];
    
    const progressBar = document.querySelector('.progress-bar');
    const progressText = document.querySelector('.d-flex.justify-content-between.small.text-muted span:first-child');
    const documentCountText = document.querySelector('.d-flex.justify-content-between.small.text-muted span:last-child');
    
    function updateProgress() {
        let uploadedCount = 0;
        let totalFiles = 0;
        
        documentFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                if (fieldId === 'otherDocuments') {
                    // Handle multiple files
                    const files = field.files;
                    if (files && files.length > 0) {
                        uploadedCount += files.length;
                        totalFiles += files.length;
                    }
                } else {
                    // Handle single files
                    totalFiles += 1;
                    if (field.files && field.files.length > 0) {
                        uploadedCount += 1;
                    }
                }
            }
        });
        
        const percentage = totalFiles > 0 ? Math.round((uploadedCount / totalFiles) * 100) : 0;
        
        // Update progress bar
        if (progressBar) {
            progressBar.style.width = percentage + '%';
            progressBar.setAttribute('aria-valuenow', percentage);
            progressBar.textContent = percentage + '%';
        }
        
        // Update progress text
        if (progressText) {
            progressText.textContent = percentage + '% Complete';
        }
        
        // Update document count
        if (documentCountText) {
            documentCountText.textContent = uploadedCount + '/' + totalFiles + ' documents';
        }
        
        // Change progress bar color based on completion
        if (progressBar) {
            progressBar.classList.remove('bg-success', 'bg-warning', 'bg-danger');
            if (percentage === 100) {
                progressBar.classList.add('bg-success');
            } else if (percentage >= 50) {
                progressBar.classList.add('bg-warning');
            } else {
                progressBar.classList.add('bg-danger');
            }
        }
    }
    
    // Add event listeners to all document fields
    documentFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('change', updateProgress);
        }
    });
    
    // Initial update
    updateProgress();
}

// Add real-time validation to all required fields
function addRealTimeValidation() {
    const requiredFields = [
        // Personal Information
        'firstName', 'middleName', 'lastName', 'dateOfBirth', 'gender',
        'primaryPhone', 'secondaryPhone', 'personalEmail', 'maritalStatus',
        'nationality', 'country', 'region', 'city', 'idType1', 'idNumber1',

        // Employment Details
        'joinDate', 'department', 'position', 'supervisor', 'employmentType', 'probationStatus',

        // Emergency Contact
        'primaryContactFullName', 'primaryContactRelationship', 'primaryContactPhoneNumber',
        'primaryContactEmailAddress', 'primaryContactAlternativePhone', 'primaryContactResidentialAddress',

        // Documents
        'resume', 'coverLetter', 'educationalCertificate', 'otherDocuments', 'documentNotes'
    ];
    
    requiredFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            // Add event listeners for real-time validation
            field.addEventListener('input', updateButtons);
            field.addEventListener('change', updateButtons);
            field.addEventListener('blur', updateButtons);
        }
    });
}

// Import Employees (CSV/XLSX)
$(document).on('click', '#importEmployeesBtn', function () {
    const fileInput = document.getElementById('employeeImportFile');
    if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
        showAlert('warning', 'Please choose a CSV or XLSX file to import.');
        return;
    }

    const formData = new FormData();
    formData.append('file', fileInput.files[0]);

    const btn = $(this);
    const originalHtml = btn.html();
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Importing...');

    $.ajax({
        url: '{{ route('hr.employees.import') }}',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (response.success) {
                showAlert('success', response.message || 'Employees imported successfully.');
                fetchEmployees();
                if (fileInput) fileInput.value = '';
            } else {
                showAlert('error', response.error || 'Import failed.');
            }
        },
        error: function (xhr) {
            let message = 'Import failed.';
            if (xhr.responseJSON && (xhr.responseJSON.message || xhr.responseJSON.error)) {
                message = xhr.responseJSON.message || xhr.responseJSON.error;
            }
            showAlert('error', message);
        },
        complete: function () {
            btn.prop('disabled', false).html(originalHtml);
        }
    });
});








$(document).ready(function() {
    // Fetch Employees
    function fetchEmployees(page = 1, search = '', department = '', sortBy = '') {
        $.ajax({
            url: '/company/hr/employees/all',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                page: page,
                search: search,
                department: department,
                sort_by: sortBy,
            },
            success: function(response) {
                console.log(response);
                if (response.success) {
                    const employees = response.data;
                    const pagination = response.pagination;
                    const tbody = $('#employeesTable tbody');
                    tbody.empty();

                    if (employees.length === 0) {
                        tbody.append(`
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-users fa-4x text-muted mb-3"></i>
                                        <h5 class="text-muted">No Employees Found</h5>
                                        <p class="text-muted">There are no employees in the database yet.</p>
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                                            <i class="fas fa-plus me-1"></i> Add First Employee
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `);
                    } else {
                        employees.forEach(employee => {
                             const row = `
                                 <tr>
                                     <td>${employee.staff_id}</td>
                                     <td>${employee.personal_info?.first_name ?? ''} ${employee.personal_info?.last_name ?? ''}</td>
                                     <td>${employee.email}</td>
                                     <td>${employee.personal_info?.primary_phone ?? 'N/A'}</td>
                                     <td>${employee.employment_info?.department ?? 'N/A'}</td>
                                     <td>${employee.employment_info?.position ?? 'N/A'}</td>
                                     <td>${employee.employment_info?.employment_type?.replace('_', ' ').toUpperCase() ?? 'N/A'}</td>
                                     <td><span class="badge bg-${employee.status === 'active' ? 'success' : 'warning'}">${employee.status.toUpperCase()}</span></td>
                                     <td>
                                         <div class="d-flex gap-1">
                                             <button type="button" class="btn-action view-employee" data-id="${employee.id}" title="View">
                                                 <i class="fas fa-eye"></i>
                                             </button>
                                             <button type="button" class="btn-action edit-employee" data-id="${employee.id}" title="Edit">
                                                 <i class="fas fa-edit"></i>
                                             </button>
                                             <button type="button" class="btn-action text-info send-message" data-id="${employee.id}" title="Send Message">
                                                 <i class="fas fa-envelope"></i>
                                             </button>
                                             <button type="button" class="btn-action text-danger delete-employee" data-id="${employee.id}" title="Delete">
                                                 <i class="fas fa-trash-alt"></i>
                                             </button>
                                         </div>
                                     </td>
                                 </tr>`;
                             tbody.append(row);
                         });
                    }

                    updatePagination(pagination, employees.length);
                } else {
                    showAlert('error', response.error);
                }
            },

            error: function() {
                showAlert('error', 'Failed to fetch employees');
            }
        });
    }

    // Update Pagination
    function updatePagination(pagination, employeeCount = 0) {
        const paginate = $('#datatable_paginate ul');
        paginate.empty();

        if (pagination.total > 0) {
            paginate.append(`
                <li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}" id="datatable_previous">
                    <a href="#" class="page-link" aria-label="Previous page" data-page="${pagination.current_page - 1}">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
            `);

            for (let i = 1; i <= pagination.last_page; i++) {
                paginate.append(`
                    <li class="page-item ${pagination.current_page === i ? 'active' : ''}">
                        <a href="#" class="page-link" aria-label="Page ${i}" data-page="${i}">${i}</a>
                    </li>
                `);
            }

            paginate.append(`
                <li class="page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}" id="datatable_next">
                    <a href="#" class="page-link" aria-label="Next page" data-page="${pagination.current_page + 1}">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            `);

            $('#datatable_info').text(`Showing ${(pagination.current_page - 1) * pagination.per_page + 1} to ${Math.min(pagination.current_page * pagination.per_page, pagination.total)} of ${pagination.total} entries`);
        } else {
            $('#datatable_info').text('No employees found');
        }
    }

    // Pagination Click
    $(document).on('click', '#datatable_paginate .page-link', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page) {
            fetchEmployees(page, $('#employeeSearch').val(), '', '');
        }
    });

    // Search
    $('#employeeSearch').on('input', function() {
        fetchEmployees(1, $(this).val(), '', '');
    });

    // // Add Employee
    // $('#addEmployeeForm').on('submit', function(e) {
    //     e.preventDefault();
    //     const formData = new FormData(this);

    //     console.log(formData);

    //     return;

    //     $.ajax({
    //         url: '/company/hr/employees',
    //         method: 'POST',
    //         data: formData,
    //         contentType: false,
    //         processData: false,
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         success: function(response) {
    //             console.log("ffff")
    //             if (response.success) {
    //                 showAlert('success', response.message);
    //                 bootstrap.Modal.getInstance(document.getElementById('addEmployeeModal')).hide();
    //                 $('#addEmployeeForm')[0].reset();
    //                 fetchEmployees();
    //             } else {
    //                 showAlert('error', response.error);
    //             }
    //         },
    //         error: function(xhr) {
    //             console.log(xhr)
    //             showAlert('error', xhr.responseJSON?.errors[0] || 'Failed to add employee');
    //         }
    //     });
    // });

// Complete form reset function
function resetFormCompletely() {
    // Reset the form
    $('#addEmployeeForm')[0].reset();
    
    // Reset profile picture preview
    const profilePreview = document.getElementById('profilePicturePreview');
    if (profilePreview) {
        profilePreview.src = '/images/users/avatar-9.jpg';
    }
    
    // Reset ID number fields to disabled state
    const idNumber1 = document.getElementById('idNumber1');
    const idNumber2 = document.getElementById('idNumber2');
    if (idNumber1) {
        idNumber1.disabled = true;
        idNumber1.placeholder = 'Select an ID type first';
        idNumber1.removeAttribute('required');
    }
    if (idNumber2) {
        idNumber2.disabled = true;
        idNumber2.placeholder = 'Select an ID type first';
        idNumber2.removeAttribute('required');
    }
    
    // Reset tab navigation to first tab
    const firstTab = document.querySelector('#addEmployeeForm .nav-tabs a:first-child');
    if (firstTab) {
        const tab = new bootstrap.Tab(firstTab);
        tab.show();
    }
    
    // Reset button states
    const prevBtn = document.getElementById('prevTabBtn');
    const nextBtn = document.getElementById('nextTabBtn');
    const submitBtn = document.getElementById('submitBtn');
    
    if (prevBtn) prevBtn.disabled = true;
    if (nextBtn) {
        nextBtn.disabled = false;
        nextBtn.style.display = 'inline-block';
    }
    if (submitBtn) {
        submitBtn.style.display = 'none';
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Save Employee';
    }
    
    // Clear any error messages
    $('.field-error').remove();
    $('.is-invalid').removeClass('is-invalid');
    
    // Reset current tab index
    if (typeof currentTab !== 'undefined') {
        currentTab = 0;
    }
    
    console.log('Form completely reset');
}

// Function to validate current form (for individual tab validation)
function validateForm() {
    // For now, just return true since we handle all validation in validateAllTabs
    // This function can be expanded if needed for individual tab validation
    return true;
}

// Function to validate all tabs before submission
function validateAllTabs() {
    let isValid = true;
    let errorMessages = [];
    let missingFieldsByTab = {};
    
    // Clear previous error messages
    $('.field-error').remove();
    $('.is-invalid').removeClass('is-invalid');
    
    // Define required fields by tab (order must match HTML tab order)
    const tabFields = {
        'Personal Information': [
            { id: 'firstName', name: 'First Name' },
            { id: 'lastName', name: 'Last Name' },
            { id: 'dateOfBirth', name: 'Date of Birth' },
            { id: 'gender', name: 'Gender' },
            { id: 'primaryPhone', name: 'Primary Phone' },
            { id: 'personalEmail', name: 'Personal Email' },
            { id: 'maritalStatus', name: 'Marital Status' },
            { id: 'nationality', name: 'Nationality' },
            { id: 'country', name: 'Country' },
            { id: 'region', name: 'Region' },
            { id: 'city', name: 'City' }
        ],
        'Employment Details': [
            { id: 'joinDate', name: 'Join Date' },
            { id: 'department', name: 'Department' },
            { id: 'position', name: 'Position' },
            { id: 'employmentType', name: 'Employment Type' }
        ],
        'Bank Info': [
            // Bank info fields are optional, no required validation
        ],
        'Emergency Contact': [
            { id: 'primaryEmergencyName', name: 'Emergency Contact Name' },
            { id: 'primaryEmergencyRelation', name: 'Emergency Contact Relationship' },
            { id: 'primaryEmergencyPhone', name: 'Emergency Contact Phone' },
            { id: 'primaryEmergencyEmail', name: 'Emergency Contact Email' },
            { id: 'primaryEmergencyAltPhone', name: 'Emergency Contact Alternative Phone' },
            { id: 'primaryEmergencyAddress', name: 'Emergency Contact Address' }
        ],
        'Documents': [
            { id: 'documentsComplete', name: 'Document Upload Confirmation', type: 'checkbox' },
            { id: 'documentsVerified', name: 'Document Verification Confirmation', type: 'checkbox' }
        ]
    };
    
    // Check each tab's required fields
    Object.keys(tabFields).forEach(tabName => {
        const fields = tabFields[tabName];
        const missingFields = [];
        
        fields.forEach(field => {
            const element = document.getElementById(field.id);
            if (element) {
                // Handle checkboxes differently
                if (field.type === 'checkbox') {
                    if (!element.checked) {
                        missingFields.push(field.name);
                        isValid = false;
                    }
                } else {
                    // Handle regular input fields
                    if (!element.value || element.value.trim() === '') {
                        missingFields.push(field.name);
                        isValid = false;
                    }
                }
            }
        });
        
        if (missingFields.length > 0) {
            missingFieldsByTab[tabName] = missingFields;
        }
    });
    
    // Check ID fields - at least one must be filled
    const idType1 = document.getElementById('idType1');
    const idNumber1 = document.getElementById('idNumber1');
    const idType2 = document.getElementById('idType2');
    const idNumber2 = document.getElementById('idNumber2');
    
    let hasValidId1 = false;
    let hasValidId2 = false;
    
    // Check ID 1
    if (idType1 && idNumber1 && idType1.value && idType1.value !== 'none') {
        if (idNumber1.value.trim() === '') {
            if (!missingFieldsByTab['Identification']) {
                missingFieldsByTab['Identification'] = [];
            }
            missingFieldsByTab['Identification'].push('ID Number 1 is required when ID Type 1 is selected');
            isValid = false;
        } else {
            hasValidId1 = true;
        }
    }
    
    // Check ID 2
    if (idType2 && idNumber2 && idType2.value && idType2.value !== 'none') {
        if (idNumber2.value.trim() === '') {
            if (!missingFieldsByTab['Identification']) {
                missingFieldsByTab['Identification'] = [];
            }
            missingFieldsByTab['Identification'].push('ID Number 2 is required when ID Type 2 is selected');
            isValid = false;
        } else {
            hasValidId2 = true;
        }
    }
    
    // At least one ID must be provided
    if (!hasValidId1 && !hasValidId2) {
        if (!missingFieldsByTab['Identification']) {
            missingFieldsByTab['Identification'] = [];
        }
        missingFieldsByTab['Identification'].push('Please provide at least one valid identification document');
        isValid = false;
    }
    
    // Validate email format
    const emailField = document.getElementById('personalEmail');
    if (emailField && emailField.value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailField.value)) {
            if (!missingFieldsByTab['Personal Information']) {
                missingFieldsByTab['Personal Information'] = [];
            }
            missingFieldsByTab['Personal Information'].push('Please enter a valid email address');
            isValid = false;
        }
    }
    
    // Validate phone format
    const phoneField = document.getElementById('primaryPhone');
    if (phoneField && phoneField.value) {
        const phoneRegex = /^[0-9+\-\s()]+$/;
        if (!phoneRegex.test(phoneField.value)) {
            if (!missingFieldsByTab['Personal Information']) {
                missingFieldsByTab['Personal Information'] = [];
            }
            missingFieldsByTab['Personal Information'].push('Please enter a valid phone number');
            isValid = false;
        }
    }
    
    // Validate dates
    const dobField = document.getElementById('dateOfBirth');
    if (dobField && dobField.value) {
        const dob = new Date(dobField.value);
        const today = new Date();
        if (dob >= today) {
            if (!missingFieldsByTab['Personal Information']) {
                missingFieldsByTab['Personal Information'] = [];
            }
            missingFieldsByTab['Personal Information'].push('Date of Birth must be in the past');
            isValid = false;
        }
    }
    
    const joinDateField = document.getElementById('joinDate');
    if (joinDateField && joinDateField.value) {
        const joinDate = new Date(joinDateField.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        if (joinDate < today) {
            if (!missingFieldsByTab['Employment Details']) {
                missingFieldsByTab['Employment Details'] = [];
            }
            missingFieldsByTab['Employment Details'].push('Join Date cannot be in the past');
            isValid = false;
        }
    }
    
    if (!isValid) {
        // Build error message by tab
        let errorHtml = '<div class="text-start"><p class="mb-3">Please complete the following required fields before submitting:</p>';
        
        Object.keys(missingFieldsByTab).forEach(tabName => {
            const fields = missingFieldsByTab[tabName];
            if (fields.length > 0) {
                errorHtml += `<div class="mb-3"><strong>${tabName}:</strong><ul class="list-unstyled ms-3">`;
                fields.forEach(field => {
                    errorHtml += `<li class="mb-1">• ${field}</li>`;
                });
                errorHtml += '</ul></div>';
            }
        });
        
        errorHtml += '</div>';
        
        // Show SweetAlert for validation errors
        Swal.fire({
            icon: 'error',
            title: 'Incomplete Form',
            html: errorHtml,
            confirmButtonText: 'Go Fix These Fields',
            confirmButtonColor: '#d33',
            width: '600px'
        }).then(() => {
            // Find the first tab with missing fields and switch to it
            const firstTabWithErrors = Object.keys(missingFieldsByTab)[0];
            if (firstTabWithErrors) {
                const tabIndex = Object.keys(tabFields).indexOf(firstTabWithErrors);
                if (tabIndex !== -1 && tabs[tabIndex]) {
                    const tab = new bootstrap.Tab(tabs[tabIndex]);
                    tab.show();
                }
            }
        });
    }
    
    return isValid;
}

$('#addEmployeeForm').on('submit', function(e) {
    e.preventDefault();
    console.log('Form submission triggered!');
    console.log('Current tab:', currentTab);
    console.log('Total tabs:', tabs.length);
    
    // First check if we're on the last tab and validate all tabs
    if (currentTab === tabs.length - 1) {
        console.log('On last tab, validating all tabs...');
        // Validate all required fields across all tabs
        // Use getMissingFields() directly instead of validateAllTabs()
        const missingFields = getMissingFields();
        console.log('Missing fields found:', missingFields);
        
        if (missingFields.length > 0) {
            console.log('Validation failed - missing fields detected');
            const fieldList = missingFields.map(field => `• ${field.name}`).join('<br>');
            showAlert('error', `Please fill in the following required fields:<br><br>${fieldList}`, true);
            return;
        }
        
        console.log('All required fields are filled - proceeding with submission');
    } else {
        console.log('Not on last tab, current tab:', currentTab);
    }
    
    // Then validate the current form
    console.log('Validating current form...');
    if (!validateForm()) {
        console.log('Current form validation failed');
        console.log('Calling showAlert for current form validation failure...');
        showAlert('error', 'Please fill in all required fields in the current tab.');
        return;
    }
    
    const formData = new FormData(this);

    // Fix checkbox values for database
    // Convert checkbox values from "on" to 1, and unchecked to 0
    const checkboxes = ['documents_complete', 'documents_verified'];
    checkboxes.forEach(checkboxName => {
        const checkbox = document.querySelector(`input[name="${checkboxName}"]`);
        if (checkbox) {
            formData.set(checkboxName, checkbox.checked ? '1' : '0');
        }
    });

    // Log formData contents
    for (let [key, value] of formData.entries()) {
        console.log(key, value);
    }

    // Or convert to plain object for easier inspection
    const formDataObj = Object.fromEntries(formData.entries());
    console.log('FormData contents:', formDataObj);

    // Get the submit button and disable it with loading animation
    const submitBtn = $('#submitBtn');
    const originalText = submitBtn.html();
    
    // Show loading state
    submitBtn.prop('disabled', true);
    submitBtn.html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving Employee...');

    $.ajax({
        url: '/company/hr/employees/store',
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log(response);
            if (response.success) {
                showAlert('success', response.message);
                
                // Hide modal safely and fix backdrop/scroll issues
                const modalElement = document.getElementById('addEmployeeModal');
                if (modalElement) {
                    // Get existing modal instance or create new one
                    let modal = bootstrap.Modal.getInstance(modalElement);
                    if (!modal) {
                        modal = new bootstrap.Modal(modalElement);
                    }
                    
                    // Hide the modal
                    modal.hide();
                    
                    // Clean up after modal is hidden
                    modalElement.addEventListener('hidden.bs.modal', function() {
                        // Remove backdrop if it exists
                        const backdrop = document.querySelector('.modal-backdrop');
                        if (backdrop) {
                            backdrop.remove();
                        }
                        
                        // Restore body scroll
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                        
                        // Reset form and refresh data
                        resetFormCompletely();
                        fetchEmployees();
                    }, { once: true });
                } else {
                    // Fallback if modal element not found
                    resetFormCompletely();
                    fetchEmployees();
                }
            } else {
                showAlert('error', response.error);
            }
        },
        error: function(xhr, status, error) {
            console.log('Error:', xhr.responseText);
            let errorMessage = 'Failed to save employee. Please try again.';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = xhr.responseJSON.errors;
                const firstError = Object.values(errors)[0];
                errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
            }
            
            showAlert('error', errorMessage);
        },
        complete: function() {
            // Reset button state regardless of success or error
            submitBtn.prop('disabled', false);
            submitBtn.html(originalText);
        }
    });
});
    // View Employee
$(document).on('click', '.view-employee', function() {
    const id = $(this).data('id');
    $.ajax({
        url: `/company/hr/employees/${id}`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log(response);
            if (response.success) {
                const employee = response.data;

                const profilePic = employee.personal_info?.profile_picture
                    ? '/storage/' + employee.personal_info.profile_picture
                    : `https://ui-avatars.com/api/?name=${encodeURIComponent(employee.first_name + ' ' + employee.last_name)}&background=4e73df&color=fff&size=150`;

                // Helpers to show only provided values
                const pi = employee.personal_info || {};
                const ei = employee.employment_info || {};
                const ec = employee.emergency_contact || {};
                const bi = employee.bank_info || {};
                const dc = employee.documents || {};

                const formatType = (t) => t ? String(t).replace('_',' ').toUpperCase() : '';
                const row = (label, value) => (value ? `<p class="mb-1"><strong>${label}:</strong> ${value}</p>` : '');

                const personalLeft = [
                    row('Email', employee.email || pi.personal_email),
                    row('Phone', pi.primary_phone),
                    row('Department', ei.department),
                ].join('');
                const personalRight = [
                    row('Position', ei.position),
                    row('Employment Type', formatType(ei.employment_type)),
                    row('Join Date', ei.join_date),
                ].join('');

                const emergencyLeft = [
                    row('Name', ec.primary_emergency_name),
                    row('Relationship', ec.primary_emergency_relation),
                ].join('');
                const emergencyRight = [
                    row('Phone', ec.primary_emergency_phone),
                    row('Address', ec.primary_emergency_address),
                ].join('');

                const bankLeft = [
                    row('Bank', bi.bank_name),
                    row('Branch', bi.branch_name),
                ].join('');
                const bankRight = [
                    row('Account No.', bi.account_number),
                    row('Type', bi.account_type),
                    row('Currency', bi.currency),
                ].join('');

                const docLink = (path, label) => path ? `<a href="/storage/${path}" target="_blank" rel="noopener">${label}</a>` : '';
                const docsLeft = [
                    row('Resume', docLink(dc.resume, 'View')),
                    row('Cover Letter', docLink(dc.cover_letter, 'View')),
                ].join('');
                const docsRight = [
                    row('Certificate', docLink(dc.educational_certificate, 'View')),
                ].join('');

                function section(title, leftHtml, rightHtml) {
                    if (!leftHtml && !rightHtml) return '';
                    return `
                        <hr>
                        <h6 class="mb-3">${title}</h6>
                        <div class="row g-3">
                            <div class="col-md-6">${leftHtml}</div>
                            <div class="col-md-6">${rightHtml}</div>
                        </div>
                    `;
                }

                const content = `
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <img src="${profilePic}" alt="Employee Photo" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                            <h5 class="mb-1">${pi.first_name ?? ''} ${pi.last_name ?? ''}</h5>
                            ${employee.staff_id ? `<p class="text-muted mb-0">${employee.staff_id}</p>` : ''}
                            ${employee.status ? `<span class="badge bg-${employee.status === 'active' ? 'success' : 'warning'} mt-2">${employee.status.toUpperCase()}</span>` : ''}
                        </div>
                        <div class="col-md-8">
                            ${section('Personal Information', personalLeft, personalRight)}
                            ${section('Emergency Contact', emergencyLeft, emergencyRight)}
                            ${section('Bank Information', bankLeft, bankRight)}
                            ${section('Documents', docsLeft, docsRight)}
                        </div>
                    </div>`;

                $('#viewEmployeeModal .modal-body').html(content);

                $('#viewEmployeeModal').modal('show');
            } else {
                showAlert('error', response.error);
            }
        },
        error: function() {
            showAlert('error', 'Failed to fetch employee details');
        }
    });
});

// Print Profile functionality
$(document).on('click', '#printProfileBtn', function() {
    // Get the employee data from the modal
    const employeeName = $('#viewEmployeeName').text();
    const employeeId = $('#viewEmployeeId').text();
    const employeeEmail = $('#viewEmployeeEmail').text();
    const employeePhone = $('#viewEmployeePhone').text();
    const employeeDepartment = $('#viewEmployeeDepartment').text();
    const employeePosition = $('#viewEmployeePosition').text();
    const employeeStatus = $('#viewEmployeeStatus').text();
    const employeeJoinDate = $('#viewEmployeeJoinDate').text();
    
    // Create a new window for printing
    const printWindow = window.open('', '_blank');
    
    // Create the print content
    const printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Employee Profile - ${employeeName}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
                .profile-section { margin-bottom: 25px; }
                .profile-section h3 { color: #333; border-bottom: 1px solid #ccc; padding-bottom: 5px; }
                .profile-row { display: flex; margin-bottom: 10px; }
                .profile-label { font-weight: bold; width: 150px; }
                .profile-value { flex: 1; }
                .status-active { color: green; font-weight: bold; }
                .status-inactive { color: orange; font-weight: bold; }
                @media print {
                    body { margin: 0; }
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Employee Profile</h1>
                <p>Generated on: ${new Date().toLocaleDateString()}</p>
            </div>
            
            <div class="profile-section">
                <h3>Personal Information</h3>
                <div class="profile-row">
                    <div class="profile-label">Name:</div>
                    <div class="profile-value">${employeeName}</div>
                </div>
                <div class="profile-row">
                    <div class="profile-label">Employee ID:</div>
                    <div class="profile-value">${employeeId}</div>
                </div>
                <div class="profile-row">
                    <div class="profile-label">Email:</div>
                    <div class="profile-value">${employeeEmail}</div>
                </div>
                <div class="profile-row">
                    <div class="profile-label">Phone:</div>
                    <div class="profile-value">${employeePhone}</div>
                </div>
            </div>
            
            <div class="profile-section">
                <h3>Employment Information</h3>
                <div class="profile-row">
                    <div class="profile-label">Department:</div>
                    <div class="profile-value">${employeeDepartment}</div>
                </div>
                <div class="profile-row">
                    <div class="profile-label">Position:</div>
                    <div class="profile-value">${employeePosition}</div>
                </div>
                <div class="profile-row">
                    <div class="profile-label">Status:</div>
                    <div class="profile-value ${employeeStatus.includes('ACTIVE') ? 'status-active' : 'status-inactive'}">${employeeStatus}</div>
                </div>
                <div class="profile-row">
                    <div class="profile-label">Join Date:</div>
                    <div class="profile-value">${employeeJoinDate}</div>
                </div>
            </div>
            
            <div class="profile-section">
                <h3>Additional Information</h3>
                <div class="profile-row">
                    <div class="profile-label">Print Date:</div>
                    <div class="profile-value">${new Date().toLocaleString()}</div>
                </div>
            </div>
        </body>
        </html>
    `;
    
    // Write content to the new window
    printWindow.document.write(printContent);
    printWindow.document.close();
    
    // Wait for content to load, then print
    printWindow.onload = function() {
        printWindow.print();
        printWindow.close();
    };
});

   $(document).on('click', '.edit-employee', function () {
    const id = $(this).data('id');

    // Ensure Edit form mirrors Add form structure exactly
    function ensureEditFormMatchesAdd() {
        const $editForm = $('#editEmployeeForm');
        const $addForm = $('#addEmployeeForm');
        if ($addForm.length === 0 || $editForm.length === 0) return;

        // Clone nav tabs and tab content from Add
        const $addNav = $addForm.find('.nav.nav-tabs').first().clone(true, true);
        const $addContent = $addForm.find('.tab-content').first().clone(true, true);

        // Map of original pane ids to new edit pane ids
        const idMap = {};

        // Update nav hrefs to point to edit_* panes
        $addNav.find('a[data-bs-toggle="tab"]').each(function () {
            const href = $(this).attr('href');
            if (href && href.startsWith('#')) {
                const originalId = href.substring(1);
                const newId = 'edit_' + originalId;
                idMap[originalId] = newId;
                $(this).attr('href', '#' + newId);
                const controls = $(this).attr('aria-controls');
                if (controls) $(this).attr('aria-controls', newId);
            }
        });

        // Update tab panes ids and aria-labelledby
        $addContent.children('.tab-pane').each(function () {
            const originalId = $(this).attr('id');
            if (!originalId) return;
            const newId = idMap[originalId] || ('edit_' + originalId);
            $(this).attr('id', newId);
        });

        // Prefix all element ids inside content with edit*, and update label[for]
        $addContent.find('[id]').each(function () {
            const oldId = $(this).attr('id');
            // Avoid double prefixing
            const newId = oldId.startsWith('edit') ? oldId : ('edit' + oldId.charAt(0).toUpperCase() + oldId.slice(1));
            $(this).attr('id', newId);
        });
        $addContent.find('label[for]').each(function () {
            const oldFor = $(this).attr('for');
            if (!oldFor) return;
            const newFor = oldFor.startsWith('edit') ? oldFor : ('edit' + oldFor.charAt(0).toUpperCase() + oldFor.slice(1));
            $(this).attr('for', newFor);
        });

        // Replace Edit form nav + content
        const $existingNav = $editForm.find('.nav.nav-tabs').first();
        const $existingContent = $editForm.find('.tab-content').first();
        if ($existingNav.length) $existingNav.replaceWith($addNav);
        if ($existingContent.length) $existingContent.replaceWith($addContent);

        // Activate the first tab for edit
        const firstTabLink = $editForm.find('.nav.nav-tabs a').first();
        if (firstTabLink.length) {
            $editForm.find('.nav.nav-tabs a').removeClass('active');
            firstTabLink.addClass('active');
        }
        const firstPane = $editForm.find('.tab-content .tab-pane').first();
        if (firstPane.length) {
            $editForm.find('.tab-content .tab-pane').removeClass('show active');
            firstPane.addClass('show active');
        }
    }

    $.ajax({
        url: `/company/hr/employees/${id}`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (response.success) {
                const employee = response.data;

                // Build edit form from add structure before populating
                ensureEditFormMatchesAdd();
                // Initialize edit nav buttons state
                updateEditButtons();

                // Helper to set value if element exists
                function setV(id, value) {
                    const el = document.getElementById(id);
                    if (!el) return;
                    if (el.tagName === 'SELECT') {
                        $(el).val(value ?? '').trigger('change');
                    } else {
                        el.value = value ?? '';
                    }
                }

                // Personal
                setV('editFirstName', employee.personal_info?.first_name);
                setV('editMiddleName', employee.personal_info?.middle_name);
                setV('editLastName', employee.personal_info?.last_name);
                setV('editPersonalEmail', employee.personal_info?.personal_email || employee.email);
                setV('editEmail', employee.email); // fallback for some templates using editEmail
                setV('editPrimaryPhone', employee.personal_info?.primary_phone);
                setV('editPhone', employee.personal_info?.primary_phone); // fallback
                setV('editSecondaryPhone', employee.personal_info?.secondary_phone);
                setV('editDateOfBirth', employee.personal_info?.date_of_birth);
                setV('editGender', employee.personal_info?.gender);
                setV('editNationality', employee.personal_info?.nationality);
                setV('editCountry', employee.personal_info?.country);
                setV('editRegion', employee.personal_info?.region);
                setV('editCity', employee.personal_info?.city);
                setV('editIdType1', employee.personal_info?.id_type_1);
                setV('editIdNumber1', employee.personal_info?.id_number_1);
                setV('editIdType2', employee.personal_info?.id_type_2);
                setV('editIdNumber2', employee.personal_info?.id_number_2);
                setV('editTinNumber', employee.personal_info?.tin_number);
                setV('editTaxStatus', employee.personal_info?.tax_status);
                setV('editTaxExemption', employee.personal_info?.tax_exemption);
                setV('editTaxNotes', employee.personal_info?.tax_notes);

                // Employment
                setV('editDepartment', employee.employment_info?.department);
                setV('editPosition', employee.employment_info?.position);
                setV('editEmploymentType', employee.employment_info?.employment_type);
                setV('editJoinDate', employee.employment_info?.join_date);
                setV('editProbationStatus', employee.employment_info?.probation_status);
                setV('editEmploymentStatus', employee.employment_info?.employment_status);

                // Emergency Contact
                setV('editEmergencyName', employee.emergency_contact?.primary_emergency_name);
                setV('editEmergencyRelation', employee.emergency_contact?.primary_emergency_relation);
                setV('editEmergencyPhone', employee.emergency_contact?.primary_emergency_phone);
                setV('editEmergencyAddress', employee.emergency_contact?.primary_emergency_address);

                // Bank Info
                setV('editBankName', employee.bank_info?.bank_name);
                setV('editBranchName', employee.bank_info?.branch_name);
                setV('editAccountNumber', employee.bank_info?.account_number);
                setV('editAccountType', employee.bank_info?.account_type);
                setV('editCurrency', employee.bank_info?.currency);

                // Documents: show existing links and allow replacement
                const docs = employee.documents || {};
                function docLink(path, label) {
                    if (!path) return '<span class="text-muted">N/A</span>';
                    const url = '/storage/' + path;
                    return `<a href="${url}" target="_blank" rel="noopener">${label}</a>`;
                }
                const $docsPane = $('#editEmployeeForm .tab-content .tab-pane#edit_Documents, #editDocuments');
                if ($docsPane.length) {
                    const html = `
                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <div class="form-text">Current Resume: ${docLink(docs.resume, 'View')}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-text">Current Cover Letter: ${docLink(docs.cover_letter, 'View')}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-text">Current Certificate: ${docLink(docs.educational_certificate, 'View')}</div>
                            </div>
                        </div>
                    `;
                    // Prepend current docs info above file inputs (avoid duplicating)
                    if ($docsPane.find('.current-docs-info').length === 0) {
                        $docsPane.prepend(`<div class="current-docs-info">${html}</div>`);
                    } else {
                        $docsPane.find('.current-docs-info').html(html);
                    }
                }

                $('#editEmployeeModal').modal('show');

                $('#editEmployeeForm').off('submit').on('submit', function (e) {
                    e.preventDefault();
                    // Basic client-side validation mirroring Add form
                    const requiredFields = [
                        '#editFirstName', '#editLastName', '#editEmail', '#editPhone',
                        '#editDepartment', '#editPosition', '#editEmploymentType', '#editJoinDate',
                        '#editEmergencyName', '#editEmergencyRelation', '#editEmergencyPhone', '#editEmergencyAddress'
                    ];
                    let missing = [];
                    requiredFields.forEach(sel => {
                        const el = document.querySelector(sel);
                        if (!el || !el.value || (el.tagName === 'SELECT' && !el.value)) {
                            missing.push(sel.replace('#edit', '').replace(/([A-Z])/g, ' $1').trim());
                        }
                    });
                    if (missing.length) {
                        showAlert('error', 'Please fill in all required fields before saving.');
                        return;
                    }

                    const formData = new FormData(this);
                    formData.append('_method', 'PUT');

                    $.ajax({
                        url: `/company/hr/employees/${id}`,
                        method: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            if (response.success) {
                                showAlert('success', response.message);
                                bootstrap.Modal.getInstance(document.getElementById('editEmployeeModal')).hide();
                                fetchEmployees();
                            } else {
                                showAlert('error', response.error);
                            }
                        },
                        error: function (xhr) {
                            showAlert('error', xhr.responseJSON?.error || 'Failed to update employee');
                            console.log(xhr);
                        }
                    });
                });
            } else {
                showAlert('error', response.error);
            }
        },
        error: function () {
            showAlert('error', 'Failed to fetch employee details');
        }
    });
});



$('#confirmDelete').on('change', function () {
    $('#confirmDeleteBtn').prop('disabled', !this.checked);
});

    // Delete Employee
    $(document).on('click', '.delete-employee', function() {
        const id = $(this).data('id');
        
        // Show loading state
        $('#deleteEmployeeName').text('Loading...');
        $('#deleteEmployeeId').text('Loading...');
        
        // Fetch employee data to display in modal
        $.ajax({
            url: `/company/hr/employees/${id}`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    const employee = response.data;
                    const fullName = `${employee.personal_info?.first_name ?? ''} ${employee.personal_info?.last_name ?? ''}`.trim();
                    const staffId = employee.staff_id || 'N/A';
                    
                    // Update modal content with actual employee data
                    $('#deleteEmployeeName').text(fullName || 'Unknown Employee');
                    $('#deleteEmployeeId').text(staffId);
                } else {
                    // Fallback if employee data fetch fails
                    $('#deleteEmployeeName').text('Unknown Employee');
                    $('#deleteEmployeeId').text('N/A');
                }
            },
            error: function() {
                // Fallback on error
                $('#deleteEmployeeName').text('Unknown Employee');
                $('#deleteEmployeeId').text('N/A');
            }
        });
        
        $('#deleteEmployeeModal').modal('show');

        console.log("id", id);

        $('#confirmDeleteBtn').off('click').on('click', function() {

            console.log("hhhh")
            $.ajax({
                url: `/company/hr/employees/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        bootstrap.Modal.getInstance(document.getElementById('deleteEmployeeModal')).hide();
                        fetchEmployees();
                    } else {
                        showAlert('error', response.error);
                    }
                },
                error: function() {
                    showAlert('error', 'Failed to delete employee');
                }
            });
        });
    });

    // Send Message

    $('#messageForm').on('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const id = $(this).data('employee-id');

    $.ajax({
        url: `/company/hr/employees/${id}/send-message`,
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log(response);
            if (response.success) {
                showAlert('success', response.message);

                $('#sendMessageModal').modal('hide');

                $('#messageForm')[0].reset();
            } else {
                showAlert('error', response.error || 'Something went wrong');
            }
        },
        error: function(xhr) {
            console.log(xhr);
            showAlert('error', xhr.responseJSON?.error || 'Failed to send message');
        }
    });
});


$(document).on('click', '.send-message', function() {
    const button = $(this);
    const id = button.data('id');

    // Get the email from the same row
    const row = button.closest('tr');
    const email = row.find('td').eq(2).text(); // Assuming email is in 3rd column (index 2)

    console.log("Sending to ID:", id, "Email:", email);

    $('#messageForm').data('employee-id', id);
    $('#recipientEmail').val(email); // set the email in input
    $('#sendMessageModal').modal('show');
});


    // SweetAlert2 Notification
    function showAlert(type, message, isHtml = false) {
        console.log('showAlert called with:', type, message, 'isHtml:', isHtml);
        console.log('Swal available:', typeof Swal !== 'undefined');
        
        if (typeof Swal === 'undefined') {
            console.error('SweetAlert2 is not loaded!');
            alert(message); // Fallback to regular alert
            return;
        }
        
        // Force close any existing alerts first
        Swal.close();
        
        // Add a small delay to ensure any previous alerts are closed
        setTimeout(() => {
            const alertConfig = {
                icon: type,
                title: type.charAt(0).toUpperCase() + type.slice(1),
                timer: 5000, // Increased timer
                timerProgressBar: true,
                showConfirmButton: true, // Show confirm button
                confirmButtonText: 'OK',
                allowOutsideClick: true,
                allowEscapeKey: true,
                backdrop: true,
                customClass: {
                    popup: 'swal2-popup-custom'
                }
            };
            
            // Use html or text based on isHtml parameter
            if (isHtml) {
                alertConfig.html = message;
            } else {
                alertConfig.text = message;
            }
            
            Swal.fire(alertConfig).then((result) => {
                console.log('SweetAlert result:', result);
            }).catch((error) => {
                console.error('SweetAlert error:', error);
            });
        }, 100);
    }

    // Load available staff IDs when modal opens
    $('#addEmployeeModal').on('show.bs.modal', function() {
        // Fetch available staff IDs
        $.ajax({
            url: '/company/hr/employees/available-staff-ids',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    const nextStaffId = response.data.next_staff_id;
                    $('#staffId').val(nextStaffId);
                    console.log('Loaded staff ID:', nextStaffId);
                } else {
                    console.log('Failed to load staff ID:', response);
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error fetching staff IDs:', error);
                console.log('Response:', xhr.responseText);
            }
        });
    });

    // Initial Fetch
    fetchEmployees();
    
    // Debug: Check if form exists and event is bound
    console.log('Form element:', document.getElementById('addEmployeeForm'));
    console.log('Submit button:', document.getElementById('submitBtn'));
    console.log('Form submission event bound:', $('#addEmployeeForm').length > 0);
    
    // Test SweetAlert
    console.log('Testing SweetAlert...');
    setTimeout(() => {
        console.log('SweetAlert test - Swal available:', typeof Swal !== 'undefined');
        if (typeof Swal !== 'undefined') {
            console.log('SweetAlert2 is loaded successfully');
        } else {
            console.error('SweetAlert2 is NOT loaded!');
        }
    }, 1000);
    
    // Add CSS to ensure SweetAlert is visible
    const style = document.createElement('style');
    style.textContent = `
        .swal2-popup {
            z-index: 99999 !important;
        }
        .swal2-backdrop {
            z-index: 99998 !important;
        }
        .swal2-container {
            z-index: 99999 !important;
        }
    `;
    document.head.appendChild(style);
    
    // Test SweetAlert after a delay (commented out for production)
    // setTimeout(() => {
    //     console.log('Testing SweetAlert with a simple alert...');
    //     try {
    //         Swal.fire({
    //             title: 'Test Alert',
    //             text: 'This is a test to see if SweetAlert is working',
    //             icon: 'info',
    //             confirmButtonText: 'OK'
    //         });
    //     } catch (error) {
    //         console.error('SweetAlert test failed:', error);
    //     }
    // }, 2000);
});




</script>

</body>
</html>
</html>