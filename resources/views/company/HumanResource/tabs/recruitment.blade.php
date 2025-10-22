<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="header-title">Recruitment & Onboarding</h4>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#reportsModal">
                <i class="fas fa-chart-bar me-1"></i> Reports
            </button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addJobOpeningModal">
                <i class="fas fa-plus me-1"></i> Add Job Opening
            </button>
        </div>
    </div>

  <!-- Status Cards -->
<div class="row mb-4 g-3">
    <div class="col-md-2 col-6">
        <div class="card h-100 border-0 shadow-sm rounded-lg overflow-hidden">
            <div class="card-body position-relative p-4">
                <div class="position-absolute top-0 end-0 m-3">
                    <div class="icon-shape bg-soft-primary text-primary rounded-circle p-2">
                        <i class="fas fa-file-alt fs-5"></i>
                    </div>
                </div>
                <h6 class="text-uppercase text-muted mb-1 small fw-semibold">Draft</h6>
                <h2 class="mb-0 fw-bold" id="draftCount">0</h2>
                <div class="progress mt-3" style="height: 4px;">
                    <div class="progress-bar bg-primary" id="draftProgress" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card h-100 border-0 shadow-sm rounded-lg overflow-hidden">
            <div class="card-body position-relative p-4">
                <div class="position-absolute top-0 end-0 m-3">
                    <div class="icon-shape bg-soft-info text-info rounded-circle p-2">
                        <i class="fas fa-briefcase fs-5"></i>
                    </div>
                </div>
                <h6 class="text-uppercase text-muted mb-1 small fw-semibold">Open</h6>
                <h2 class="mb-0 fw-bold" id="openCount">0</h2>
                <div class="progress mt-3" style="height: 4px;">
                    <div class="progress-bar bg-info" id="openProgress" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card h-100 border-0 shadow-sm rounded-lg overflow-hidden">
            <div class="card-body position-relative p-4">
                <div class="position-absolute top-0 end-0 m-3">
                    <div class="icon-shape bg-soft-warning text-warning rounded-circle p-2">
                        <i class="fas fa-user-tie fs-5"></i>
                    </div>
                </div>
                <h6 class="text-uppercase text-muted mb-1 small fw-semibold">Interviewing</h6>
                <h2 class="mb-0 fw-bold" id="interviewingCount">0</h2>
                <div class="progress mt-3" style="height: 4px;">
                    <div class="progress-bar bg-warning" id="interviewingProgress" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card h-100 border-0 shadow-sm rounded-lg overflow-hidden">
            <div class="card-body position-relative p-4">
                <div class="position-absolute top-0 end-0 m-3">
                    <div class="icon-shape bg-soft-purple text-purple rounded-circle p-2">
                        <i class="fas fa-handshake fs-5"></i>
                    </div>
                </div>
                <h6 class="text-uppercase text-muted mb-1 small fw-semibold">Offered</h6>
                <h2 class="mb-0 fw-bold" id="offeredCount">0</h2>
                <div class="progress mt-3" style="height: 4px;">
                    <div class="progress-bar bg-purple" id="offeredProgress" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card h-100 border-0 shadow-sm rounded-lg overflow-hidden">
            <div class="card-body position-relative p-4">
                <div class="position-absolute top-0 end-0 m-3">
                    <div class="icon-shape bg-soft-success text-success rounded-circle p-2">
                        <i class="fas fa-user-check fs-5"></i>
                    </div>
                </div>
                <h6 class="text-uppercase text-muted mb-1 small fw-semibold">Onboarded</h6>
                <h2 class="mb-0 fw-bold" id="onboardedCount">0</h2>
                <div class="progress mt-3" style="height: 4px;">
                    <div class="progress-bar bg-success" id="onboardedProgress" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card h-100 border-0 shadow-sm rounded-lg overflow-hidden">
            <div class="card-body position-relative p-4">
                <div class="position-absolute top-0 end-0 m-3">
                    <div class="icon-shape bg-soft-danger text-danger rounded-circle p-2">
                        <i class="fas fa-archive fs-5"></i>
                    </div>
                </div>
                <h6 class="text-uppercase text-muted mb-1 small fw-semibold">Closed</h6>
                <h2 class="mb-0 fw-bold" id="closedCount">0</h2>
                <div class="progress mt-3" style="height: 4px;">
                    <div class="progress-bar bg-danger" id="closedProgress" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<ul class="nav nav-tabs nav-bordered mb-3">
    <li class="nav-item">
        <a href="#open-positions" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
            <i class="fas fa-briefcase me-1"></i> Open Positions
        </a>
    </li>
    <li class="nav-item">
        <a href="#candidates" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
            <i class="fas fa-users me-1"></i> Candidates
        </a>
    </li>
    <li class="nav-item">
        <a href="#onboarding" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
            <i class="fas fa-user-check me-1"></i> Onboarding
        </a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane show active" id="open-positions">
        <div class="table-responsive">
            <table class="table table-centered table-striped dt-responsive nowrap w-100" id="jobs-datatable">
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Department</th>
                        <th>Location</th>
                        <th>Type</th>
                        <th>Applications</th>
                        <th>Posted Date</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                   
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="tab-pane" id="candidates">
        <div class="table-responsive">
            <table class="table table-centered table-striped dt-responsive nowrap w-100" id="candidates-datatable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Applied For</th>
                        <th>Status</th>
                        <th>Applied Date</th>
                        <th>Experience</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Applications will be loaded dynamically -->
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="tab-pane" id="onboarding">
       <div class="row">
           <div class="col-12">
               <div class="card mb-4">
                   <div class="card-body">
                       <h4 class="header-title mb-3">Onboarding Progress</h4>
                       <div class="onboarding-steps" id="onboardingSteps">
                           <!-- Onboarding steps will be loaded dynamically -->
                           <div class="text-center text-muted">
                               <i class="fas fa-spinner fa-spin fa-2x"></i>
                               <p class="mt-2">Loading onboarding data...</p>
                           </div>
                       </div>
                   </div>
               </div>
                
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="header-title">Onboarding Pipeline</h4>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addOnboardingTaskModal">
                                <i class="fas fa-plus me-1"></i> Add Task
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-centered table-striped dt-responsive nowrap w-100" id="onboarding-datatable">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Position</th>
                                        <th>Start Date</th>
                                        <th>Progress</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="onboardingTableBody">
                                    <!-- Onboarding data will be loaded dynamically -->
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            <i class="fas fa-spinner fa-spin"></i> Loading onboarding data...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Job Opening Modal -->



    <!-- Add Quill CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    
    
    <!-- Add Job Opening Modal -->
<div class="modal fade" id="addJobOpeningModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Job Opening</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addJobForm">
                    <div class="row">
                        <!-- Title -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="positionTitle" class="form-label">Position Title</label>
                                <input type="text" class="form-control" id="positionTitle" placeholder="Position Title" required>
                            </div>
                        </div>

                        <!-- Department -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jobdepartment" class="form-label">Department</label>
                                <select class="form-select" id="jobdepartment" required>
                                    <option value="">Select Department</option>
                                    <option value="IT">IT</option>
                                    <option value="HR">Human Resources</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Marketing">Marketing</option>
                                    <option value="Operations">Operations</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Location -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control" id="location" placeholder="Enter Location" required>
                            </div>
                        </div>

                        <!-- Job Type -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jobType" class="form-label">Job Type</label>
                                <select class="form-select" id="jobType" required>
                                    <option value="">Select Type</option>
                                    <option value="Full-Time">Full-Time</option>
                                    <option value="Part-Time">Part-Time</option>
                                    <option value="Contract">Contract</option>
                                    <option value="Internship">Internship</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Hiring Manager -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="hiringManager" class="form-label">Hiring Manager</label>
                                <select class="form-select" id="hiringManager" required>
                                    <option value="">Select Hiring Manager</option>
                                    <option value="1">John Doe (IT Manager)</option>
                                    <option value="2">Jane Smith (HR Director)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jobstatus" class="form-label">Status</label>
                                <select class="form-select" id="jobstatus" required>
                                    <option value="draft">Draft</option>
                                    <option value="open">Open</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Posted Date -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="openingDate" class="form-label">Posted Date</label>
                                <input type="date" class="form-control" id="openingDate" required>
                            </div>
                        </div>

                        <!-- Closing Date -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="closingDate" class="form-label">Closing Date (Optional)</label>
                                <input type="date" class="form-control" id="closingDate">
                            </div>
                        </div>
                    </div>

                    <!-- Job Description -->
                    <div class="mb-3">
                        <label for="jobDescription" class="form-label">Job Description</label>
                        <div id="jobDescriptionEditor" style="height: 200px; background: #fff;"></div>
                        <input type="hidden" id="jobDescription" name="description">
                    </div>

                    <!-- Requirements -->
                    <div class="mb-3">
                        <label for="requirements" class="form-label">Requirements</label>
                        <div id="requirementsEditor" style="height: 150px; background: #fff;"></div>
                        <input type="hidden" id="requirements" name="requirements">
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="saveJobBtn">Save Job Opening</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    
    <!-- Include Quill library -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    
    <!-- View Job Modal -->
    <div class="modal fade" id="viewJobModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Job Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <h4 id="viewJobTitle" class="mb-3"></h4>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge bg-soft-primary" id="viewJobDepartment"></span>
                            <span class="badge bg-soft-info" id="viewJobLocation"></span>
                            <span class="badge bg-soft-success" id="viewJobType"></span>
                            <span class="badge bg-soft-warning" id="viewJobStatus"></span>
                        </div>
                        <p class="text-muted mb-4" id="viewJobPostedDate"></p>
                    </div>
                    
                    <div class="mb-4">
                        <h6>Job Description</h6>
                        <div id="viewJobDescription" class="border p-3 rounded bg-light"></div>
                    </div>
                    
                    <div class="mb-4">
                        <h6>Requirements</h6>
                        <div id="viewJobRequirements" class="border p-3 rounded bg-light"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="editJobBtnm">Edit Job</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit Job Modal -->
    <div class="modal fade" id="editJobModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Job Opening</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editJobForm">
                        <input type="hidden" id="editJobId">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editPositionTitle" class="form-label">Position Title</label>
                                    <input type="text" class="form-control" id="editPositionTitle" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editDepartment" class="form-label">Department</label>
                                    <select class="form-select" name="jobeditDepartmen" id="jobeditDepartment" required>
                                        <option value="">Select Department</option>
                                        <option value="Engineering">Engineering</option>
                                        <option value="Design">Design</option>
                                        <option value="Marketing">Marketing</option>
                                        <option value="Sales">Sales</option>
                                        <option value="HR">Human Resources</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editLocation" class="form-label">Location</label>
                                    <input type="text" class="form-control" id="editLocation" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editJobType" class="form-label">Job Type</label>
                                    <select class="form-select" id="editJobType" required>
                                        <option value="Full-time">Full-time</option>
                                        <option value="Part-time">Part-time</option>
                                        <option value="Contract">Contract</option>
                                        <option value="Internship">Internship</option>
                                        <option value="Remote">Remote</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                         <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jobeditStatus" class="form-label">Status</label>
                                <select class="form-select" id="jobeditStatus" required>
                                    <option value="">Select Status</option>
                                    <option value="draft">Draft</option>
                                    <option value="open">Open</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editJobDescription" class="form-label">Job Description</label>
                            <div id="editJobDescriptionEditor" style="height: 200px; background: #fff;"></div>
                            <input type="hidden" name="edit_job_description" id="editJobDescription">
                        </div>
                        
                        <div class="mb-3">
                            <label for="editRequirements" class="form-label">Requirements</label>
                            <div id="editRequirementsEditor" style="height: 150px; background: #fff;"></div>
                            <input type="hidden" name="edit_requirements" id="editRequirements">
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Job</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Job Confirmation Modal -->
    <div class="modal fade" id="deleteJobModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this job opening? This action cannot be undone.</p>
                    <input type="hidden" id="deleteJobId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteJob">Delete Job</button>
                </div>
            </div>
        </div>
    </div>
    
   
    
    <!-- Add Candidate Modal -->
    <div class="modal fade" id="addCandidateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Candidate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addCandidateForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="candidateName" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="candidateName" placeholder="Full Name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="candidateEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="candidateEmail" placeholder="Email" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="candidatePhone" class="form-label">Phone</label>
                                    <input type="tel" class="form-control" id="candidatePhone" placeholder="Phone">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="appliedPosition" class="form-label">Applied Position</label>
                                    <select class="form-select" id="appliedPosition" required>
                                        <option value="">Select Position</option>
                                        <option value="1">Senior Developer</option>
                                        <option value="2">HR Manager</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="resume" class="form-label">Resume/CV</label>
                            <input class="form-control" type="file" id="resume" accept=".pdf,.doc,.docx">
                            <div class="form-text">Accepted formats: PDF, DOC, DOCX (Max: 5MB)</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="coverLetter" class="form-label">Cover Letter</label>
                            <textarea class="form-control" id="coverLetter" rows="3"></textarea>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Candidate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Reports Modal -->
    <div class="modal fade" id="reportsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Recruitment Reports</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Time-to-Hire Metrics</h5>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Position</th>
                                                    <th>Avg. Time</th>
                                                    <th>Candidates</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Senior Developer</td>
                                                    <td>24 days</td>
                                                    <td>8</td>
                                                </tr>
                                                <tr>
                                                    <td>HR Manager</td>
                                                    <td>18 days</td>
                                                    <td>5</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Conversion Metrics</h5>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Stage</th>
                                                    <th>Count</th>
                                                    <th>Conversion</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Applications</td>
                                                    <td>50</td>
                                                    <td>100%</td>
                                                </tr>
                                                <tr>
                                                    <td>Phone Screen</td>
                                                    <td>30</td>
                                                    <td>60%</td>
                                                </tr>
                                                <tr>
                                                    <td>Interviews</td>
                                                    <td>15</td>
                                                    <td>30%</td>
                                                </tr>
                                                <tr>
                                                    <td>Offers</td>
                                                    <td>5</td>
                                                    <td>10%</td>
                                                </tr>
                                                <tr>
                                                    <td>Hires</td>
                                                    <td>3</td>
                                                    <td>6%</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Recruitment Funnel</h5>
                            <div id="recruitmentFunnelChart" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">
                        <i class="fas fa-download me-1"></i> Export Report
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .onboarding-steps .step {
            display: flex;
            margin-bottom: 1.5rem;
            position: relative;
        }
        .onboarding-steps .step:last-child {
            margin-bottom: 0;
        }
        .onboarding-steps .step:not(:last-child):before {
            content: '';
            position: absolute;
            left: 20px;
            top: 35px;
            height: calc(100% - 10px);
            width: 1px;
            background-color: #e9ecef;
        }
        .onboarding-steps .step-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #6c757d;
        }
        .onboarding-steps .step.completed .step-icon {
            background-color: #0acf97;
            color: white;
            border-color: #0acf97;
        }
        .onboarding-steps .step.active .step-icon {
            background-color: #727cf5;
            color: white;
            border-color: #727cf5;
        }
        .step-content {
            padding-top: 5px;
        }
        .step-content h6 {
            margin-bottom: 0.25rem;
            font-weight: 500;
        }
    </style>

  

    <!-- View Job Modal -->
    <div class="modal fade" id="viewJobModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">Job Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3">Senior Frontend Developer</h5>
                            <p class="text-muted">#JOB-001 | Posted: 15 Jun 2023</p>
                            
                            <div class="mb-4">
                                <h6 class="text-uppercase text-muted small fw-bold mb-3">Job Details</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-briefcase me-2 text-primary"></i>
                                        <span class="text-muted">Department:</span> Engineering
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                        <span class="text-muted">Location:</span> Nairobi, Kenya
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-clock me-2 text-primary"></i>
                                        <span class="text-muted">Type:</span> Full-time
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-users me-2 text-primary"></i>
                                        <span class="text-muted">Positions:</span> 2
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-tag me-2 text-primary"></i>
                                        <span class="text-muted">Experience:</span> 5+ years
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-uppercase text-muted small fw-bold mb-3">Quick Stats</h6>
                                    <div class="row text-center">
                                        <div class="col-6 mb-3">
                                            <h4 class="mb-1">24</h4>
                                            <small class="text-muted">Applications</small>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <h4 class="mb-1">8</h4>
                                            <small class="text-muted">Screened</small>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="mb-1">5</h4>
                                            <small class="text-muted">Interviews</small>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="mb-1">2</h4>
                                            <small class="text-muted">Hired</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h6 class="text-uppercase text-muted small fw-bold mb-3">Job Description</h6>
                        <p>We are looking for an experienced Frontend Developer to join our team. The ideal candidate will be responsible for building user interfaces and implementing features using modern JavaScript frameworks.</p>
                        
                        <h6 class="text-uppercase text-muted small fw-bold mt-4 mb-3">Requirements</h6>
                        <ul class="ps-3">
                            <li>5+ years of experience with JavaScript, HTML, and CSS</li>
                            <li>Strong proficiency in React.js or Vue.js</li>
                            <li>Experience with state management libraries</li>
                            <li>Familiarity with RESTful APIs</li>
                            <li>Knowledge of modern authorization mechanisms</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">View All Applicants</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Interview Modal -->
    <div class="modal fade" id="scheduleInterviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">Schedule Interview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Candidate</label>
                            <input type="text" class="form-control" value="John Doe" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Position</label>
                            <input type="text" class="form-control" value="Senior Frontend Developer" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Interview Type</label>
                            <select class="form-select">
                                <option>Technical Interview</option>
                                <option>HR Interview</option>
                                <option>Team Interview</option>
                                <option>Final Interview</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Interview Date</label>
                            <input type="datetime-local" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Interviewers</label>
                            <select class="form-select" multiple>
                                <option selected>Sarah Johnson (Lead Developer)</option>
                                <option>Michael Brown (CTO)</option>
                                <option>Emily Davis (HR Manager)</option>
                                <option>David Wilson (Team Lead)</option>
                            </select>
                            <small class="text-muted">Hold Ctrl/Cmd to select multiple interviewers</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Meeting Link/Details</label>
                            <input type="text" class="form-control" placeholder="Zoom/Google Meet link or physical location">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" rows="3" placeholder="Any special instructions or notes for the candidate"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Schedule Interview</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Candidate Modal -->
    <div class="modal fade" id="viewCandidateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">Candidate Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <img src="https://ui-avatars.com/api/?name=John+Doe&background=0D8ABC&color=fff&size=100" alt="John Doe" class="rounded-circle mb-2" width="100">
                        <h4>John Doe</h4>
                        <p class="text-muted">Senior Frontend Developer</p>
                        <div>
                            <span class="badge bg-soft-warning">Screening</span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted small fw-bold mb-3">Contact Information</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-envelope me-2 text-primary"></i>
                                    john.doe@example.com
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-phone me-2 text-primary"></i>
                                    +254 712 345 678
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                    Nairobi, Kenya
                                </li>
                                <li class="mb-2">
                                    <i class="fab fa-linkedin me-2 text-primary"></i>
                                    linkedin.com/in/johndoe
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted small fw-bold mb-3">Application Details</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <span class="text-muted">Applied for:</span> Senior Frontend Developer
                                </li>
                                <li class="mb-2">
                                    <span class="text-muted">Applied on:</span> 20 Jun 2023
                                </li>
                                <li class="mb-2">
                                    <span class="text-muted">Experience:</span> 5 years
                                </li>
                                <li class="mb-2">
                                    <span class="text-muted">Current Salary:</span> $60,000
                                </li>
                                <li class="mb-2">
                                    <span class="text-muted">Expected Salary:</span> $75,000
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h6 class="text-uppercase text-muted small fw-bold mb-3">Cover Letter</h6>
                        <div class="p-3 bg-light rounded">
                            <p>Dear Hiring Manager,</p>
                            <p>I am excited to apply for the Senior Frontend Developer position at your company. With over 5 years of experience in building responsive web applications, I believe I would be a great fit for your team.</p>
                            <p>In my current role at XYZ Corp, I've led the development of several high-traffic applications using React and Node.js. I'm particularly drawn to your company's focus on creating user-centric products.</p>
                            <p>I look forward to the possibility of discussing this exciting opportunity with you.</p>
                            <p class="mb-0">Best regards,<br>John Doe</p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h6 class="text-uppercase text-muted small fw-bold mb-3">Attachments</h6>
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-file-pdf me-1"></i> Resume.pdf
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-file-word me-1"></i> Cover_Letter.doc
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-image me-1"></i> Portfolio.pdf
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Download CV</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Onboarding Modal -->
    <div class="modal fade" id="viewOnboardingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">Onboarding Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <img src="https://ui-avatars.com/api/?name=John+Doe&background=0D8ABC&color=fff&size=100" alt="John Doe" class="rounded-circle mb-2" width="100">
                        <h4>John Doe</h4>
                        <p class="text-muted">Senior Frontend Developer</p>
                        <div class="mb-3">
                            <span class="badge bg-soft-success">Onboarding In Progress</span>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted small fw-bold mb-3">Employee Information</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <span class="text-muted">Employee ID:</span> EMP-001
                                </li>
                                <li class="mb-2">
                                    <span class="text-muted">Department:</span> Engineering
                                </li>
                                <li class="mb-2">
                                    <span class="text-muted">Start Date:</span> 01 Jul 2023
                                </li>
                                <li class="mb-2">
                                    <span class="text-muted">Manager:</span> Sarah Johnson
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted small fw-bold mb-3">Onboarding Progress</h6>
                            <div class="progress mb-2" style="height: 10px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="text-muted small mb-0">2 of 4 tasks completed</p>
                        </div>
                    </div>
                    
                    <h6 class="text-uppercase text-muted small fw-bold mb-3">Onboarding Checklist</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Task</th>
                                    <th>Assigned To</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Employment Contract Signed</td>
                                    <td>HR Department</td>
                                    <td>25 Jun 2023</td>
                                    <td><span class="badge bg-soft-success">Completed</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-soft-primary">View</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Background Check</td>
                                    <td>HR Department</td>
                                    <td>28 Jun 2023</td>
                                    <td><span class="badge bg-soft-success">Completed</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-soft-primary">View</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Laptop & Equipment Setup</td>
                                    <td>IT Department</td>
                                    <td>30 Jun 2023</td>
                                    <td><span class="badge bg-soft-warning">In Progress</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-soft-primary">View</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>First Day Orientation</td>
                                    <td>HR Department</td>
                                    <td>01 Jul 2023</td>
                                    <td><span class="badge bg-soft-secondary">Pending</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-soft-primary">View</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        <h6 class="text-uppercase text-muted small fw-bold mb-3">Onboarding Documents</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <div class="border rounded p-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-file-pdf text-danger me-2"></i>
                                    <div>
                                        <p class="mb-0 small">Employment_Contract.pdf</p>
                                        <small class="text-muted">250 KB</small>
                                    </div>
                                </div>
                            </div>
                            <div class="border rounded p-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-file-word text-primary me-2"></i>
                                    <div>
                                        <p class="mb-0 small">Company_Policies.docx</p>
                                        <small class="text-muted">180 KB</small>
                                    </div>
                                </div>
                            </div>
                            <div class="border rounded p-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-file-excel text-success me-2"></i>
                                    <div>
                                        <p class="mb-0 small">Benefits_Enrollment.xlsx</p>
                                        <small class="text-muted">320 KB</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Print Checklist</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Onboarding Modal -->
    <div class="modal fade" id="editOnboardingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">Edit Onboarding</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Employee</label>
                            <input type="text" class="form-control" value="John Doe" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Position</label>
                            <input type="text" class="form-control" value="Senior Frontend Developer" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" value="2023-07-01">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select">
                                <option>Not Started</option>
                                <option selected>In Progress</option>
                                <option>Completed</option>
                                <option>On Hold</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Manager</label>
                            <select class="form-select">
                                <option selected>Sarah Johnson</option>
                                <option>Michael Brown</option>
                                <option>Emily Davis</option>
                                <option>David Wilson</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" rows="3" placeholder="Any special notes or instructions"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Onboarding Task Modal -->
    <div class="modal fade" id="addOnboardingTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">Add Onboarding Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Task Name</label>
                            <input type="text" class="form-control" placeholder="E.g., Laptop Setup, Email Creation, etc.">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Assigned To</label>
                            <select class="form-select">
                                <option>IT Department</option>
                                <option>HR Department</option>
                                <option>Team Lead</option>
                                <option>Office Manager</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Due Date</label>
                            <input type="date" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Priority</label>
                            <select class="form-select">
                                <option>Low</option>
                                <option selected>Medium</option>
                                <option>High</option>
                                <option>Critical</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" rows="3" placeholder="Task details and instructions"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Add Task</button>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Add this in your <head> or before your script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
        // Initialize rich text editors
        let jobDescriptionEditor, requirementsEditor;
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize job description editor
           jobDescriptionEditor = new Quill('#jobDescriptionEditor', {
                theme: 'snow',
                placeholder: 'Enter job description here...',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link', 'clean']
                    ]
                }
            });

            // Initialize requirements editor
            requirementsEditor = new Quill('#requirementsEditor', {
                theme: 'snow',
                placeholder: 'Enter job requirements here...',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'bullet' }],
                        ['clean']
                    ]
                }
            });

    
               


            // Initialize recruitment funnel chart
            if (document.getElementById('recruitmentFunnelChart')) {
                const options = {
                    series: [{
                        name: 'Candidates',
                        data: [50, 30, 15, 5, 3]
                    }],
                    chart: {
                        type: 'bar',
                        height: 300,
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 0,
                            horizontal: true,
                            barHeight: '80%',
                            isFunnel: true,
                        },
                    },
                    colors: ['#727cf5'],
                    dataLabels: {
                        enabled: true,
                        formatter: function (val, opt) {
                            return opt.w.globals.labels[opt.dataPointIndex] + ':  ' + val;
                        },
                        dropShadow: {
                            enabled: false,
                        }
                    },
                    xaxis: {
                        categories: ['Applications', 'Phone Screen', 'Interviews', 'Offers', 'Hires'],
                    },
                    legend: {
                        show: false
                    },
                    fill: {
                        opacity: 0.8,
                    },
                };

                const chart = new ApexCharts(document.querySelector("#recruitmentFunnelChart"), options);
                chart.render();
            }
        });



  
let editJobDescriptionEditor, editRequirementsEditor;

document.addEventListener('DOMContentLoaded', function() {

    editJobDescriptionEditor = new Quill('#editJobDescriptionEditor', {
        theme: 'snow',
        placeholder: 'Enter job description...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, false] }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                ['clean']
            ]
        }
    });

    editRequirementsEditor = new Quill('#editRequirementsEditor', {
        theme: 'snow',
        placeholder: 'Enter job requirements...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{ 'list': 'bullet' }],
                ['clean']
            ]
        }
    });
});

    
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// SweetAlert2 wrapper (as provided)
function showAlert(type, message) {
    Swal.fire({
        icon: type,
        title: type.charAt(0).toUpperCase() + type.slice(1),
        text: message,
        confirmButtonText: 'OK',
        confirmButtonColor: type === 'success' ? '#0acf97' : '#ff3d60'
    });
}

$(document).ready(function() {
 

   const $jobForm = $('#addJobForm');
if ($jobForm.length) {
    $jobForm.on('submit', function(e) {
        e.preventDefault();

    $('#jobDescription').val(jobDescriptionEditor.root.innerHTML);
$('#requirements').val(requirementsEditor.root.innerHTML);

    const formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        title: $('#positionTitle').val().trim(),
        department: $('#jobdepartment').val(),
        location: $('#location').val(),
        type: $('#jobType').val(),
        status: $('#jobstatus').val(),
        posted_date: $('#openingDate').val(),
        description: $('#jobDescription').val(),      
        requirements: $('#requirements').val(),       
        hiring_manager: $('#hiringManager').val()
    };


        console.log(formData);

        // Validate required fields before submission
        if (!formData.title || !formData.department || !formData.location || 
            !formData.type || !formData.posted_date) {
            showAlert('error', 'Please fill all required fields');
            return;
        }

        $.ajax({
            url: '/company/hr/jobs/store',
            method: 'POST',
            data: formData,
            success: function(response) {
                console.log(response);
                if (response.success) {
                    showAlert('success', `Job created successfully! Shareable link: ${response.shareable_link}`);
                    const modal = bootstrap.Modal.getInstance($('#addJobOpeningModal')[0]);
                    modal.hide();
                    $jobForm[0].reset();
                    refreshJobsTable();
                       refreshJobStatusCounts();
                } else {
                    // Handle server-side validation errors
                    if (response.errors) {
                        const errorMessages = Object.values(response.errors).join('<br>');
                        showAlert('error', errorMessages);
                    } else {
                        showAlert('error', response.error || 'Unknown error');
                    }
                }
            },
            error: function(xhr) {
                console.log(xhr);
                showAlert('error', xhr.responseJSON?.error || 'Failed to create job');
            }
        });
    });
}

// Attach edit handler AFTER appending

   $(document).off('click', '.btn-edit-job').on('click', '.btn-edit-job', function () {
        const token = $(this).data('token');
        console.log(token); // Confirm token is correct

        

        $.ajax({
            url: '/company/hr/jobs/showByToken',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                token: token
            },
            success: function(response) {
                if (response.success) {
                    const job = response.data;

                    $('#editJobId').val(job.id);
                    $('#editPositionTitle').val(job.title);
                    $('#jobeditDepartment').val(job.department);
                    $('#editLocation').val(job.location);
                    $('#editJobType').val(job.type);
                    $("#jobeditStatus").val(job.status);

                    console.log('Department from backend:', job.department);
console.log('Job Type from backend:', job.type)
console.log('Status from backend:', job.status);

                    // If using Quill editors
                    editJobDescriptionEditor.root.innerHTML = job.description || '';
                    editRequirementsEditor.root.innerHTML = job.requirements || '';

                    const editModal = new bootstrap.Modal($('#editJobModal')[0]);
                    editModal.show();
                } else {
                    showAlert('error', response.error || 'Job not found');
                }
            },
            error: function(xhr) {
                showAlert('error', xhr.responseJSON?.error || 'Failed to load job');
            }
        });
    });

    const $viewJobModal = $('#viewJobModal');
    if ($viewJobModal.length) {
        $viewJobModal.on('show.bs.modal', function(event) {
            const $button = $(event.relatedTarget);
            const $row = $button.closest('tr');
            const jobToken = $row.find('.job-token').text();

            $.ajax({
                url: '/company/hr/jobs/showByToken',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    token: jobToken
                },
                success: function(response) {

                    if (response.success) {
                        const job = response.data;
                        $('#viewJobTitle').text(job.title);
                        $('#viewJobDepartment').text(job.department);
                        $('#viewJobLocation').text(job.location);
                        $('#viewJobType').text(job.type);
                        $('#viewJobStatus').text(job.status);
                        $('#viewJobPostedDate').text(`Posted on ${job.posted_date} • ${job.applications} applications`);
                        $('#viewJobDescription').html(`<p>${job.description.replace(/\n/g, '<br>')}</p>`);
                        $('#viewJobRequirements').html(`<p>${job.requirements.replace(/\n/g, '<br>')}</p>`);

                        const shareableLink = `${window.location.origin}/job?token=${job.token}`;
                        const $shareLinkContainer = $('#viewJobShareLink');
                        if ($shareLinkContainer.length) {
                            $shareLinkContainer.val(shareableLink);
                        }

                       $('#editJobBtn').on('click', function() {
                            const editModal = new bootstrap.Modal($('#editJobModal')[0]);
                            
                            console.log(job);
                            $('#editJobId').val(job.id);
                            $('#editPositionTitle').val(job.title);
                            $('#jobeditDepartment').val(job.department);
                            $('#editLocation').val(job.location);
                            $('#editJobType').val(job.type);

                            // Set Quill editors' contents
                            editJobDescriptionEditor.root.innerHTML = job.description || '';
                            editRequirementsEditor.root.innerHTML = job.requirements || '';

                            bootstrap.Modal.getInstance($viewJobModal[0]).hide();
                            editModal.show();
                        });

                    } else {
                        showAlert('error', response.error || 'Unknown error');
                    }
                },
                error: function(xhr) {
                    showAlert('error', xhr.responseJSON?.error || 'Failed to load job');
                }
            });
        });
    }

const $editJobForm = $('#editJobForm');
if ($editJobForm.length) {
    $editJobForm.on('submit', function(e) {
        e.preventDefault();

        // Sync Quill editor content into hidden inputs
        $('#editJobDescription').val(editJobDescriptionEditor.root.innerHTML);
        $('#editRequirements').val(editRequirementsEditor.root.innerHTML);

        const jobId = $('#editJobId').val();
        const formData = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            id: jobId,
            title: $('#editPositionTitle').val(),
            department: $('#jobeditDepartment').val(),
            location: $('#editLocation').val(),
            type: $('#editJobType').val(),
            status: $('#jobeditStatus').val(),
            description: $('#editJobDescription').val(),
            requirements: $('#editRequirements').val()
        };

        // console.log('Submitting edit job form:', formData);
        // return;

        $.ajax({
            url: '/company/hr/jobs/update',
            method: 'POST',
            data: formData,
            success: function(response) {
                console.log(response);
                if (response.success) {
                    showAlert('success', 'Job updated successfully!');
                    const modal = bootstrap.Modal.getInstance($('#editJobModal')[0]);
                    modal.hide();
                    refreshJobsTable();
                       refreshJobStatusCounts();
     
                } else {
                    showAlert('error', response.error || 'Unknown error');
                }
            },
            error: function(xhr) {
                console.log(xhr);
                showAlert('error', xhr.responseJSON?.error || 'Failed to update job');
            }
        });
    });
}

    $('#jobs-datatable .btn-soft-danger').on('click', function() {
        const jobId = $(this).closest('tr').find('.job-id').text().replace('#', '');
        $('#deleteJobId').val(jobId);

        const deleteModal = new bootstrap.Modal($('#deleteJobModal')[0]);
        deleteModal.show();
    });

    const $confirmDeleteBtn = $('#confirmDeleteJob');
    if ($confirmDeleteBtn.length) {
        $confirmDeleteBtn.on('click', function() {
            const jobId = $('#deleteJobId').val();

            $.ajax({
                url: '/company/hr/jobs/delete',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: jobId
                },
                success: function(response) {
                    if (response.success) {
                        showAlert('success', 'Job soft deleted successfully!');
                        const modal = bootstrap.Modal.getInstance($('#deleteJobModal')[0]);
                        modal.hide();
                        refreshJobsTable();
                    } else {
                        showAlert('error', response.error || 'Unknown error');
                    }
                },
                error: function(xhr) {
                    showAlert('error', xhr.responseJSON?.error || 'Failed to soft delete job');
                }
            });
        });
    }

 
    function refreshJobsTable() {
        $.ajax({
            url: '/company/hr/jobs/all',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    const jobs = response.data;
                    const $tbody = $('#jobs-datatable tbody');
                    $tbody.empty();
                    if (jobs.length === 0) {
                        $tbody.html('<tr><td colspan="8" class="text-center text-muted">No jobs found.</td></tr>');
                    } else {
                        $.each(jobs, function(index, job) {
                            const row = `
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-2">
                                                <i class="fas fa-code text-primary"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">${job.title}</h6>
                                                <small class="text-muted job-id">#${job.id}</small>
                                                <small class="text-muted job-token d-none">${job.token}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${job.department}</td>
                                    <td>${job.location}</td>
                                    <td><span class="badge bg-soft-primary text-primary">${job.type}</span></td>
                                    <td><span class="badge bg-soft-info text-info">${job.applications}</span></td>
                                    <td>${job.posted_date}</td>
                                    <td><span class="badge bg-soft-success">${job.status}</span></td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-soft-primary" data-bs-toggle="modal" data-bs-target="#viewJobModal">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                              <button type="button" class="btn btn-sm btn-soft-info btn-edit-job" data-token="${job.token}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                            <button type="button" class="btn btn-sm btn-soft-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            `;
                            $tbody.append(row);
                        });
                    }

                    $('#jobs-datatable .btn-soft-danger').on('click', function() {
                        const jobId = $(this).closest('tr').find('.job-id').text().replace('#', '');
                        $('#deleteJobId').val(jobId);
                        const deleteModal = new bootstrap.Modal($('#deleteJobModal')[0]);
                        deleteModal.show();
                    });
                } else {
                    showAlert('error', response.error || 'Failed to fetch jobs');
                }
            },
            error: function(xhr) {
                showAlert('error', xhr.responseJSON?.error || 'Failed to fetch jobs');
            }
        });
    }

    function checkForJobToken() {
        const urlParams = new URLSearchParams(window.location.search);
        const token = urlParams.get('token');
        if (token) {
            $.ajax({
                url: '/company/hr/jobs/showByToken',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    token: token
                },
                success: function(response) {
                    if (response.success) {
                        const job = response.data;
                        const viewJobModal = new bootstrap.Modal($('#viewJobModal')[0]);
                        $('#viewJobTitle').text(job.title);
                        $('#viewJobDepartment').text(job.department);
                        $('#viewJobLocation').text(job.location);
                        $('#viewJobType').text(job.type);
                        $('#viewJobStatus').text(job.status);
                        $('#viewJobPostedDate').text(`Posted on ${job.posted_date} • ${job.applications} applications`);
                        $('#viewJobDescription').html(`<p>${job.description.replace(/\n/g, '<br>')}</p>`);
                        $('#viewJobRequirements').html(`<p>${job.requirements.replace(/\n/g, '<br>')}</p>`);

                        const shareableLink = `${window.location.origin}/job?token=${job.token}`;
                        const $shareLinkContainer = $('#viewJobShareLink');
                        if ($shareLinkContainer.length) {
                            $shareLinkContainer.val(shareableLink);
                        }

                        viewJobModal.show();
                    } else {
                        showAlert('error', response.error || 'Invalid or expired job link');
                    }
                },
                error: function(xhr) {
                    showAlert('error', xhr.responseJSON?.error || 'Invalid or expired job link');
                }
            });
        }
    }
    $confirmDeleteBtn




    function refreshJobStatusCounts() {
    $.post('/company/hr/jobs/status-counts', {
        _token: $('meta[name="csrf-token"]').attr('content')
    }, function(response) {
        if (response.success) {
            const counts = response.data;

            $('#draftCount').text(counts.draft);
            $('#openCount').text(counts.open);
            $('#interviewingCount').text(counts.interviewing);
            $('#offeredCount').text(counts.offered);
            $('#onboardedCount').text(counts.onboarded);
            $('#closedCount').text(counts.closed);

            // Optional: You can calculate percentages to update progress bars
            const total = Object.values(counts).reduce((a, b) => a + b, 0) || 1;

            $('#draftProgress').css('width', `${(counts.draft / total) * 100}%`);
            $('#openProgress').css('width', `${(counts.open / total) * 100}%`);
            $('#interviewingProgress').css('width', `${(counts.interviewing / total) * 100}%`);
            $('#offeredProgress').css('width', `${(counts.offered / total) * 100}%`);
            $('#onboardedProgress').css('width', `${(counts.onboarded / total) * 100}%`);
            $('#closedProgress').css('width', `${(counts.closed / total) * 100}%`);
        }
    });
}

    function copyShareLink() {
        const $shareLinkInput = $('#viewJobShareLink');
        $shareLinkInput[0].select();
        document.execCommand('copy');
        showAlert('success', 'Link copied to clipboard!');
    }

    refreshJobsTable();
    checkForJobToken();
    refreshJobStatusCounts();

    // Load job applications
    function loadJobApplications() {
        $.ajax({
            url: '/company/hr/jobs/applications',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    const applications = response.data;
                    const $tbody = $('#candidates-datatable tbody');
                    $tbody.empty();

                    if (applications.length === 0) {
                        $tbody.html('<tr><td colspan="6" class="text-center text-muted">No applications found.</td></tr>');
                    } else {
                        applications.forEach(application => {
                            const avatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(application.first_name + ' ' + application.last_name)}&background=0D8ABC&color=fff&size=36`;
                            const appliedDate = new Date(application.applied_at).toLocaleDateString();
                            const experienceText = application.experience_years ? `${application.experience_years} years` : 'Not specified';

                            let statusBadge = '<span class="badge bg-soft-secondary">New</span>';
                            if (application.status === 'reviewed') {
                                statusBadge = '<span class="badge bg-soft-info">Reviewed</span>';
                            } else if (application.status === 'interviewed') {
                                statusBadge = '<span class="badge bg-soft-warning">Interviewed</span>';
                            } else if (application.status === 'hired') {
                                statusBadge = '<span class="badge bg-soft-success">Hired</span>';
                            } else if (application.status === 'rejected') {
                                statusBadge = '<span class="badge bg-soft-danger">Rejected</span>';
                            }

                            const row = `
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-2">
                                                <img src="${avatarUrl}" alt="${application.first_name} ${application.last_name}" class="rounded-circle" width="36">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">${application.first_name} ${application.last_name}</h6>
                                                <small class="text-muted">${application.email}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${application.job ? application.job.title : 'N/A'}</td>
                                    <td>${statusBadge}</td>
                                    <td>${appliedDate}</td>
                                    <td>${experienceText}</td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-soft-primary view-application" data-id="${application.id}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-soft-success schedule-interview" data-id="${application.id}">
                                                <i class="fas fa-calendar-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-soft-danger reject-application" data-id="${application.id}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            `;
                            $tbody.append(row);
                        });
                    }
                } else {
                    showAlert('error', response.error || 'Failed to load applications');
                }
            },
            error: function(xhr) {
                showAlert('error', xhr.responseJSON?.error || 'Failed to load applications');
            }
        });
    }

    // Load applications when candidates tab is shown
    $('a[href="#candidates"]').on('shown.bs.tab', function() {
        loadJobApplications();
    });

    // Load onboarding data when onboarding tab is shown
    $('a[href="#onboarding"]').on('shown.bs.tab', function() {
        loadOnboardingData();
    });

    // Function to load onboarding data
    function loadOnboardingData() {
        $.ajax({
            url: '/company/hr/jobs/onboarding',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    const onboardingData = response.data;
                    renderOnboardingSteps(onboardingData);
                    renderOnboardingTable(onboardingData);
                } else {
                    showAlert('error', response.error || 'Failed to load onboarding data');
                }
            },
            error: function(xhr) {
                showAlert('error', xhr.responseJSON?.error || 'Failed to load onboarding data');
            }
        });
    }

    // Function to render onboarding steps
    function renderOnboardingSteps(onboardingData) {
        const stepsContainer = $('#onboardingSteps');

        if (onboardingData.length === 0) {
            stepsContainer.html(`
                <div class="text-center text-muted">
                    <i class="fas fa-users fa-3x mb-3"></i>
                    <h5>No Onboarding Records</h5>
                    <p>No employees are currently in the onboarding process.</p>
                </div>
            `);
            return;
        }

        // Use the first onboarding record for the steps display
        const onboarding = onboardingData[0];
        const employee = onboarding.employee;

        const stepsHtml = `
            <div class="step ${onboarding.offer_accepted_date ? 'completed' : ''}">
                <div class="step-icon ${onboarding.offer_accepted_date ? 'bg-soft-success text-success' : 'bg-soft-secondary'}">
                    <i class="fas fa-${onboarding.offer_accepted_date ? 'check' : 'handshake'}"></i>
                </div>
                <div class="step-content">
                    <h6 class="mb-1">Offer Accepted</h6>
                    <p class="text-muted mb-0">
                        ${onboarding.offer_accepted_date
                            ? `${employee.first_name} ${employee.last_name} - ${new Date(onboarding.offer_accepted_date).toLocaleDateString()}`
                            : 'Not completed'
                        }
                    </p>
                </div>
            </div>
            <div class="step ${onboarding.documents_uploaded_status === 'completed' ? 'completed' : ''}">
                <div class="step-icon ${onboarding.documents_uploaded_status === 'completed' ? 'bg-soft-success text-success' : onboarding.documents_uploaded_status === 'in_progress' ? 'bg-soft-warning text-warning' : 'bg-soft-secondary'}">
                    <i class="fas fa-${onboarding.documents_uploaded_status === 'completed' ? 'check' : 'file-upload'}"></i>
                </div>
                <div class="step-content">
                    <h6 class="mb-1">Documents Uploaded</h6>
                    <p class="text-muted mb-0">
                        ${onboarding.documents_uploaded_status === 'completed'
                            ? `Completed - ${new Date(onboarding.documents_uploaded_date).toLocaleDateString()}`
                            : onboarding.documents_uploaded_status === 'in_progress'
                            ? 'In Progress'
                            : 'Not Started'
                        }
                    </p>
                </div>
            </div>
            <div class="step ${onboarding.staff_id_assigned_status === 'completed' ? 'completed' : ''}">
                <div class="step-icon ${onboarding.staff_id_assigned_status === 'completed' ? 'bg-soft-success text-success' : onboarding.staff_id_assigned_status === 'in_progress' ? 'bg-soft-warning text-warning' : 'bg-soft-secondary'}">
                    <i class="fas fa-${onboarding.staff_id_assigned_status === 'completed' ? 'check' : 'id-card'}"></i>
                </div>
                <div class="step-content">
                    <h6 class="mb-1">Assigned Staff ID</h6>
                    <p class="text-muted mb-0">
                        ${onboarding.staff_id_assigned_status === 'completed'
                            ? `Completed - ${new Date(onboarding.staff_id_assigned_date).toLocaleDateString()}`
                            : onboarding.staff_id_assigned_status === 'in_progress'
                            ? 'In Progress'
                            : 'Pending'
                        }
                    </p>
                </div>
            </div>
            <div class="step ${onboarding.first_day_checklist_status === 'completed' ? 'completed' : ''}">
                <div class="step-icon ${onboarding.first_day_checklist_status === 'completed' ? 'bg-soft-success text-success' : onboarding.first_day_checklist_status === 'in_progress' ? 'bg-soft-warning text-warning' : 'bg-soft-secondary'}">
                    <i class="fas fa-${onboarding.first_day_checklist_status === 'completed' ? 'check' : 'clipboard-check'}"></i>
                </div>
                <div class="step-content">
                    <h6 class="mb-1">First Day Checklist</h6>
                    <p class="text-muted mb-0">
                        ${onboarding.first_day_checklist_status === 'completed'
                            ? `Completed - ${new Date(onboarding.first_day_checklist_date).toLocaleDateString()}`
                            : onboarding.first_day_checklist_status === 'in_progress'
                            ? 'In Progress'
                            : 'Not Started'
                        }
                    </p>
                </div>
            </div>
        `;

        stepsContainer.html(stepsHtml);
    }

    // Function to render onboarding table
    function renderOnboardingTable(onboardingData) {
        const tbody = $('#onboardingTableBody');

        if (onboardingData.length === 0) {
            tbody.html('<tr><td colspan="6" class="text-center text-muted">No onboarding records found.</td></tr>');
            return;
        }

        let tableHtml = '';

        onboardingData.forEach(onboarding => {
            const employee = onboarding.employee;
            const avatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(employee.first_name + ' ' + employee.last_name)}&background=0D8ABC&color=fff&size=36`;
            const startDate = new Date(onboarding.start_date).toLocaleDateString();
            const progressPercent = onboarding.progress_percentage || 0;
            const completedTasks = onboarding.completed_tasks || 0;

            let statusBadge = '<span class="badge bg-soft-secondary">Not Started</span>';
            if (onboarding.overall_status === 'in_progress') {
                statusBadge = '<span class="badge bg-soft-warning">In Progress</span>';
            } else if (onboarding.overall_status === 'completed') {
                statusBadge = '<span class="badge bg-soft-success">Completed</span>';
            }

            tableHtml += `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-2">
                                <img src="${avatarUrl}" alt="${employee.first_name} ${employee.last_name}" class="rounded-circle" width="36">
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">${employee.first_name} ${employee.last_name}</h6>
                                <small class="text-muted">ID: ${employee.staff_id}</small>
                            </div>
                        </div>
                    </td>
                    <td>${employee.position}</td>
                    <td>${startDate}</td>
                    <td>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-${progressPercent === 100 ? 'success' : progressPercent > 50 ? 'info' : 'warning'}"
                                 role="progressbar"
                                 style="width: ${progressPercent}%;"
                                 aria-valuenow="${progressPercent}"
                                 aria-valuemin="0"
                                 aria-valuemax="100"></div>
                        </div>
                        <small class="mt-1 d-block text-muted">${completedTasks} of 4 tasks completed</small>
                    </td>
                    <td>${statusBadge}</td>
                    <td class="text-end">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-soft-primary view-onboarding" data-id="${onboarding.id}">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-soft-info edit-onboarding" data-id="${onboarding.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-soft-danger delete-onboarding" data-id="${onboarding.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        tbody.html(tableHtml);
    }

    // View onboarding details
    $(document).on('click', '.view-onboarding', function() {
        const onboardingId = $(this).data('id');
        // For now, show the existing view onboarding modal
        // You can enhance this to load specific onboarding data
        const modal = new bootstrap.Modal($('#viewOnboardingModal')[0]);
        modal.show();
    });

    // Edit onboarding
    $(document).on('click', '.edit-onboarding', function() {
        const onboardingId = $(this).data('id');
        // For now, show the existing edit onboarding modal
        // You can enhance this to load specific onboarding data
        const modal = new bootstrap.Modal($('#editOnboardingModal')[0]);
        modal.show();
    });

    // Delete onboarding
    $(document).on('click', '.delete-onboarding', function() {
        const onboardingId = $(this).data('id');

        if (confirm('Are you sure you want to delete this onboarding record?')) {
            $.ajax({
                url: `/company/hr/jobs/onboarding/${onboardingId}`,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showAlert('success', 'Onboarding record deleted successfully');
                        loadOnboardingData();
                    } else {
                        showAlert('error', response.error || 'Failed to delete onboarding record');
                    }
                },
                error: function(xhr) {
                    showAlert('error', xhr.responseJSON?.error || 'Failed to delete onboarding record');
                }
            });
        }
    });

    // View application details
    $(document).on('click', '.view-application', function() {
        const applicationId = $(this).data('id');

        $.ajax({
            url: `/company/hr/jobs/applications/${applicationId}`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    const application = response.data;

                    // Populate view candidate modal with application data
                    $('#viewCandidateModal .modal-title').text('Application Details');
                    $('#viewCandidateModal img').attr('src', `https://ui-avatars.com/api/?name=${encodeURIComponent(application.first_name + ' ' + application.last_name)}&background=0D8ABC&color=fff&size=100`);
                    $('#viewCandidateModal h4').text(`${application.first_name} ${application.last_name}`);
                    $('#viewCandidateModal .text-muted').first().text(application.job ? application.job.title : 'N/A');

                    // Update contact information
                    const contactHtml = `
                        <li class="mb-2">
                            <i class="fas fa-envelope me-2 text-primary"></i>
                            ${application.email}
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-phone me-2 text-primary"></i>
                            ${application.phone || 'Not provided'}
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                            ${application.city ? `${application.city}, ${application.region || ''} ${application.country || ''}`.trim() : 'Not provided'}
                        </li>
                    `;
                    $('#viewCandidateModal .col-md-6').first().find('ul').html(contactHtml);

                    // Update application details
                    const detailsHtml = `
                        <li class="mb-2">
                            <span class="text-muted">Applied for:</span> ${application.job ? application.job.title : 'N/A'}
                        </li>
                        <li class="mb-2">
                            <span class="text-muted">Applied on:</span> ${new Date(application.applied_at).toLocaleDateString()}
                        </li>
                        <li class="mb-2">
                            <span class="text-muted">Experience:</span> ${application.experience_years ? `${application.experience_years} years` : 'Not specified'}
                        </li>
                        <li class="mb-2">
                            <span class="text-muted">Education:</span> ${application.education_level || 'Not specified'}
                        </li>
                    `;
                    $('#viewCandidateModal .col-md-6').last().find('ul').html(detailsHtml);

                    // Update cover letter
                    $('#viewCandidateModal .cover-letter-content').html(application.cover_letter || 'No cover letter provided.');

                    // Show modal
                    const modal = new bootstrap.Modal($('#viewCandidateModal')[0]);
                    modal.show();
                } else {
                    showAlert('error', response.error || 'Failed to load application details');
                }
            },
            error: function(xhr) {
                showAlert('error', xhr.responseJSON?.error || 'Failed to load application details');
            }
        });
    });

    // Schedule interview
    $(document).on('click', '.schedule-interview', function() {
        const applicationId = $(this).data('id');
        // For now, just show the existing schedule interview modal
        // You can enhance this to pre-populate with application data
        const modal = new bootstrap.Modal($('#scheduleInterviewModal')[0]);
        modal.show();
    });

    // Reject application
    $(document).on('click', '.reject-application', function() {
        const applicationId = $(this).data('id');

        if (confirm('Are you sure you want to reject this application?')) {
            $.ajax({
                url: `/company/hr/jobs/applications/${applicationId}/reject`,
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showAlert('success', 'Application rejected successfully');
                        loadJobApplications();
                    } else {
                        showAlert('error', response.error || 'Failed to reject application');
                    }
                },
                error: function(xhr) {
                    showAlert('error', xhr.responseJSON?.error || 'Failed to reject application');
                }
            });
        }
    });
});
</script>