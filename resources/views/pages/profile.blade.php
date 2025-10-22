@extends('layouts.vertical', ['page_title' => 'Profile', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('css')
    
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">GESL</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Employee</a></li>
                            <li class="breadcrumb-item active">My Profile</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Employee Profile</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-4 col-lg-5">
                <div class="card text-center">
                    <div class="card-body">
                            <div class="position-relative d-inline-block">
                                <label for="profileImageUpload" class="d-block cursor-pointer">
                                    <img
                                        src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : '/images/users/avatar-1.jpg' }}"
                                        class="rounded-circle avatar-xl img-thumbnail bg-white p-2"
                                        alt="Profile Image"
                                        id="profileImage"
                                        style="border: 2px solid #0d6efd;" />
                                    <div class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-1 border border-2 border-white" style="cursor: pointer;">
                                        <i class="ri-camera-line text-white"></i>
                                    </div>
                                </label>
                                <input type="file" id="profileImageUpload" class="d-none" accept="image/*">
                            </div>
                            
                            <!-- Hidden form for image upload -->
                            <form id="profileImageForm" action="" method="POST" enctype="multipart/form-data" class="d-none">
                                @csrf
                                <input type="file" name="profile_image" id="profileImageInput" accept="image/*">
                            </form>

                        <h4 class="mb-1 mt-2">{{ Auth::user()->name ?? 'Employee' }}</h4>
                        <p class="text-muted">{{ Auth::user()->department ?? 'Global Enertech & Solutions Ltd' }}</p>

                        <button type="button" class="btn btn-success btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit</button>
                        <button type="button" class="btn btn-danger btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#messageModal">Message</button>

                        <div class="text-start mt-3">
                            <h4 class="fs-13 text-uppercase">About Me :</h4>
                            <p class="text-muted mb-3">
                                {{ Auth::user()->bio ?? 'Empowering sustainable energy solutions through innovation and technology. Committed to excellence in energy technology and services.' }}
                            </p>
                            <p class="text-muted mb-2">
                                <strong>Full Name :</strong>
                                <span class="ms-2">{{ Auth::user()->name ?? 'Employee' }}</span>
                            </p>

                            <p class="text-muted mb-2">
                                <strong>Mobile :</strong>
                                <span class="ms-2">{{ Auth::user()->phone ?? 'Not set' }}</span>
                            </p>

                            <p class="text-muted mb-2">
                                <strong>Email :</strong>
                                <span class="ms-2">{{ Auth::user()->email ?? 'No email' }}</span>
                            </p>

                            <p class="text-muted mb-2">
                                <strong>Department :</strong>
                                <span class="ms-2">{{ Auth::user()->department ?? 'Not specified' }}</span>
                            </p>
                        </div>

                        <!-- Company Values -->
                        <div class="mt-4 pt-3 border-top">
                            <h5 class="fs-14 mb-3">Our Values</h5>
                            <div class="d-flex align-items-center mb-2">
                                <i class="ri-checkbox-circle-fill text-success me-2"></i>
                                <span class="text-muted">Innovation in Energy Technology</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="ri-checkbox-circle-fill text-success me-2"></i>
                                <span class="text-muted">Sustainable Solutions</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="ri-checkbox-circle-fill text-success me-2"></i>
                                <span class="text-muted">Operational Excellence</span>
                            </div>
                        </div>

                        <!-- Company Information Section -->
                        <div class="mt-4 pt-3 border-top">
                            <h5 class="fs-14 mb-3">Company Information</h5>
                            <div class="d-flex align-items-center mb-2">
                                <i class="ri-building-line text-muted me-2"></i>
                                <span class="text-muted">Global Enertech & Solutions Ltd</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="ri-map-pin-line text-muted me-2"></i>
                                <span class="text-muted">Energy Technology Solutions Provider</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="ri-global-line text-muted me-2"></i>
                                <a href="https://www.globalenertechsource.com" target="_blank" class="text-primary">www.globalenertechsource.com</a>
                            </div>
                        </div>
                    </div>
                    <!-- end card-body -->
                </div>
                <!-- end card -->

                <!-- Messages-->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h4 class="header-title">Messages</h4>
                            <div class="dropdown">
                                <a
                                    href="#"
                                    class="dropdown-toggle arrow-none card-drop"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="ri-more-2-fill"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item">Settings</a>
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item">Action</a>
                                </div>
                            </div>
                        </div>

                        <div class="inbox-widget">
                            <div class="inbox-item">
                                <div class="inbox-item-img">
                                    <img src="/images/users/avatar-2.jpg" class="rounded-circle" alt="" />
                                </div>
                                <p class="inbox-item-author">Tomaslau</p>
                                <p class="inbox-item-text">I've finished it! See you so...</p>
                                <p class="inbox-item-date">
                                    <a href="#" class="btn btn-sm btn-link text-info fs-13">Reply</a>
                                </p>
                            </div>
                            <div class="inbox-item">
                                <div class="inbox-item-img">
                                    <img src="/images/users/avatar-3.jpg" class="rounded-circle" alt="" />
                                </div>
                                <p class="inbox-item-author">Stillnotdavid</p>
                                <p class="inbox-item-text">This theme is awesome!</p>
                                <p class="inbox-item-date">
                                    <a href="#" class="btn btn-sm btn-link text-info fs-13">Reply</a>
                                </p>
                            </div>
                            <div class="inbox-item">
                                <div class="inbox-item-img">
                                    <img src="/images/users/avatar-4.jpg" class="rounded-circle" alt="" />
                                </div>
                                <p class="inbox-item-author">Kurafire</p>
                                <p class="inbox-item-text">Nice to meet you</p>
                                <p class="inbox-item-date">
                                    <a href="#" class="btn btn-sm btn-link text-info fs-13">Reply</a>
                                </p>
                            </div>

                            <div class="inbox-item">
                                <div class="inbox-item-img">
                                    <img src="/images/users/avatar-5.jpg" class="rounded-circle" alt="" />
                                </div>
                                <p class="inbox-item-author">Shahedk</p>
                                <p class="inbox-item-text">Hey! there I'm available...</p>
                                <p class="inbox-item-date">
                                    <a href="#" class="btn btn-sm btn-link text-info fs-13">Reply</a>
                                </p>
                            </div>
                            <div class="inbox-item">
                                <div class="inbox-item-img">
                                    <img src="/images/users/avatar-6.jpg" class="rounded-circle" alt="" />
                                </div>
                                <p class="inbox-item-author">Adhamdannaway</p>
                                <p class="inbox-item-text">This theme is awesome!</p>
                                <p class="inbox-item-date">
                                    <a href="#" class="btn btn-sm btn-link text-info fs-13">Reply</a>
                                </p>
                            </div>
                        </div>
                        <!-- end inbox-widget -->
                    </div>
                    <!-- end card-body-->
                </div>
                <!-- end card-->
            </div>
            <!-- end col-->

            <div class="col-xl-8 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                            <li class="nav-item">
                                <a
                                    href="#aboutme"
                                    data-bs-toggle="tab"
                                    aria-expanded="false"
                                    class="nav-link rounded-start rounded-0 active">
                                    About
                                </a>
                            </li>
                            <li class="nav-item">
                                <a
                                    href="#documents"
                                    data-bs-toggle="tab"
                                    aria-expanded="true"
                                    class="nav-link rounded-0">
                                    <i class="ri-folder-2-line me-1"></i> Documents
                                </a>
                            </li>
                            <li class="nav-item">
                                <a
                                    href="#settings"
                                    data-bs-toggle="tab"
                                    aria-expanded="false"
                                    class="nav-link rounded-end rounded-0">
                                    Settings
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane show active" id="aboutme">
                                <h5 class="text-uppercase mb-3">
                                    <i class="ri-briefcase-line me-1"></i>
                                    Projects
                                </h5>
                                <div class="table-responsive">
                                    <table class="table table-sm table-centered table-hover table-borderless mb-0">
                                        <thead class="border-top border-bottom bg-light-subtle border-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Clients</th>
                                                <th>Project Name</th>
                                                <th>Start Date</th>
                                                <th>Due Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>
                                                    <img
                                                        src="/images/users/avatar-2.jpg"
                                                        alt="table-user"
                                                        class="me-2 rounded-circle"
                                                        height="24" />
                                                    GESL Energy Solutions
                                                </td>
                                                <td>Solar Power Plant Installation - 10MW</td>
                                                <td>15/03/2023</td>
                                                <td>30/09/2024</td>
                                                <td>
                                                    <span class="badge bg-info-subtle text-info">In Progress</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>
                                                    <img
                                                        src="/images/users/avatar-3.jpg"
                                                        alt="table-user"
                                                        class="me-2 rounded-circle"
                                                        height="24" />
                                                    National Grid Corporation
                                                </td>
                                                <td>Grid Modernization & Smart Metering</td>
                                                <td>01/05/2023</td>
                                                <td>31/12/2024</td>
                                                <td><span class="badge bg-info-subtle text-info">In Progress</span></td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>
                                                    <img
                                                        src="/images/users/avatar-4.jpg"
                                                        alt="table-user"
                                                        class="me-2 rounded-circle"
                                                        height="24" />
                                                    GreenTech Industries
                                                </td>
                                                <td>Energy Storage System Implementation</td>
                                                <td>10/01/2023</td>
                                                <td>30/06/2023</td>
                                                <td><span class="badge bg-success-subtle text-success">Completed</span></td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>
                                                    <img
                                                        src="/images/users/avatar-6.jpg"
                                                        alt="table-user"
                                                        class="me-2 rounded-circle"
                                                        height="24" />
                                                    Urban Development Authority
                                                </td>
                                                <td>Smart City Energy Management</td>
                                                <td>01/04/2023</td>
                                                <td>31/12/2025</td>
                                                <td>
                                                    <span class="badge bg-info-subtle text-info">In Progress</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>
                                                    <img
                                                        src="/images/users/avatar-5.jpg"
                                                        alt="table-user"
                                                        class="me-2 rounded-circle"
                                                        height="24" />
                                                    Industrial Power Solutions
                                                </td>
                                                <td>Energy Efficiency Retrofit Program</td>
                                                <td>01/07/2023</td>
                                                <td>30/06/2024</td>
                                                <td>
                                                    <span class="badge bg-warning-subtle text-warning">
                                                        Planning Phase
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <h5 class="text-uppercase mt-4">
                                    <i class="ri-macbook-line me-1"></i>
                                    Experience
                                </h5>

                                <div class="timeline-alt pb-0">
                                    <div class="timeline-item">
                                        <i class="ri-record-circle-line text-bg-info timeline-icon"></i>
                                        <div class="timeline-item-info">
                                            <h5 class="mt-0 mb-1">Senior Energy Solutions Architect</h5>
                                            <p class="fs-14">
                                                GESL Energy Solutions
                                                <span class="ms-2 fs-12">2020 - Present</span>
                                            </p>
                                            <p class="text-muted mt-2 mb-0 pb-3">
                                                Leading the design and implementation of large-scale renewable energy projects, including solar and wind power plants. Specializing in grid integration and energy storage solutions. Successfully delivered over 200MW of clean energy capacity across multiple projects.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="timeline-item">
                                        <i class="ri-record-circle-line text-bg-primary timeline-icon"></i>
                                        <div class="timeline-item-info">
                                            <h5 class="mt-0 mb-1">Energy Systems Engineer</h5>
                                            <p class="fs-14">
                                                GreenTech Innovations
                                                <span class="ms-2 fs-12">2017 - 2020</span>
                                            </p>
                                            <p class="text-muted mt-2 mb-0 pb-3">
                                                Designed and optimized energy systems for commercial and industrial clients. Implemented smart grid technologies and energy management systems that reduced client energy costs by an average of 30%. Specialized in microgrid development and demand response systems.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="timeline-item">
                                        <i class="ri-record-circle-line text-bg-info timeline-icon"></i>
                                        <div class="timeline-item-info">
                                            <h5 class="mt-0 mb-1">Renewable Energy Consultant</h5>
                                            <p class="fs-14">
                                                Sustainable Power Solutions
                                                <span class="ms-2 fs-12">2014 - 2017</span>
                                            </p>
                                            <p class="text-muted mt-2 mb-0 pb-2">
                                                Provided expert consultation on renewable energy projects, including feasibility studies, site assessments, and technology selection. Played a key role in developing sustainability strategies for corporate clients, helping them transition to clean energy solutions and reduce their carbon footprint.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <!-- end timeline -->
                            </div>
                            <!-- end tab-pane -->
                            <!-- end about me section content -->

                            <div class="tab-pane" id="documents">
                                <!-- Document Upload Card -->
                                <div class="card mb-3 border">
                                    <div class="card-body">
                                        <div class="dropzone dz-clickable" id="documentDropzone">
                                            <div class="dz-message needsclick">
                                                <i class="ri-upload-cloud-2-line display-4 text-muted"></i>
                                                <h5>Drop files here or click to upload.</h5>
                                                <span class="text-muted font-13">(This is just a demo dropzone. Selected files are <strong>not</strong> actually uploaded.)</span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="shareWithTeam">
                                                <label class="form-check-label" for="shareWithTeam">Share with team</label>
                                            </div>
                                            <button type="button" class="btn btn-primary btn-sm">
                                                <i class="ri-upload-line me-1"></i> Upload Files
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Document List -->
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="card-title mb-0">My Documents</h5>
                                            <div class="dropdown">
                                                <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ri-more-2-fill"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="#"><i class="ri-download-line me-2"></i>Download All</a>
                                                    <a class="dropdown-item" href="#"><i class="ri-share-line me-2"></i>Share</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>Delete</a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-centered table-nowrap table-hover mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="selectAll">
                                                            </div>
                                                        </th>
                                                        <th>Name</th>
                                                        <th>Uploaded</th>
                                                        <th>Size</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm me-2">
                                                                    <div class="avatar-title bg-soft-primary text-primary rounded">
                                                                        <i class="ri-file-pdf-line"></i>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <a href="#" class="text-body fw-semibold">Project-Requirements.pdf</a>
                                                                    <p class="text-muted mb-0">PDF Document</p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>2 days ago</td>
                                                        <td>2.3 MB</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a href="#" class="font-18 text-muted" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="ri-more-fill"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" href="#"><i class="ri-download-line me-2"></i>Download</a>
                                                                    <a class="dropdown-item" href="#"><i class="ri-share-line me-2"></i>Share</a>
                                                                    <a class="dropdown-item" href="#"><i class="ri-pencil-line me-2"></i>Rename</a>
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>Delete</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm me-2">
                                                                    <div class="avatar-title bg-soft-success text-success rounded">
                                                                        <i class="ri-file-excel-line"></i>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <a href="#" class="text-body fw-semibold">Expense-Report.xlsx</a>
                                                                    <p class="text-muted mb-0">Excel Spreadsheet</p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>1 week ago</td>
                                                        <td>1.8 MB</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a href="#" class="font-18 text-muted" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="ri-more-fill"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" href="#"><i class="ri-download-line me-2"></i>Download</a>
                                                                    <a class="dropdown-item" href="#"><i class="ri-share-line me-2"></i>Share</a>
                                                                    <a class="dropdown-item" href="#"><i class="ri-pencil-line me-2"></i>Rename</a>
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>Delete</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm me-2">
                                                                    <div class="avatar-title bg-soft-warning text-warning rounded">
                                                                        <i class="ri-file-word-line"></i>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <a href="#" class="text-body fw-semibold">Project-Proposal.docx</a>
                                                                    <p class="text-muted mb-0">Word Document</p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>2 weeks ago</td>
                                                        <td>3.1 MB</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a href="#" class="font-18 text-muted" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="ri-more-fill"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" href="#"><i class="ri-download-line me-2"></i>Download</a>
                                                                    <a class="dropdown-item" href="#"><i class="ri-share-line me-2"></i>Share</a>
                                                                    <a class="dropdown-item" href="#"><i class="ri-pencil-line me-2"></i>Rename</a>
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>Delete</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm me-2">
                                                                    <div class="avatar-title bg-soft-info text-info rounded">
                                                                        <i class="ri-image-line"></i>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <a href="#" class="text-body fw-semibold">Screenshot.png</a>
                                                                    <p class="text-muted mb-0">PNG Image</p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>3 weeks ago</td>
                                                        <td>1.2 MB</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a href="#" class="font-18 text-muted" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="ri-more-fill"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" href="#"><i class="ri-download-line me-2"></i>Download</a>
                                                                    <a class="dropdown-item" href="#"><i class="ri-share-line me-2"></i>Share</a>
                                                                    <a class="dropdown-item" href="#"><i class="ri-pencil-line me-2"></i>Rename</a>
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>Delete</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Table Pagination -->
                                        <div class="row mt-3">
                                            <div class="col-sm-12">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="text-muted">
                                                        Showing <span>1</span> to <span>5</span> of <span>24</span> entries
                                                    </div>
                                                    <ul class="pagination pagination-rounded mb-0">
                                                        <li class="page-item disabled">
                                                            <a class="page-link" href="#" aria-label="Previous">
                                                                <span aria-hidden="true">&laquo;</span>
                                                            </a>
                                                        </li>
                                                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                        <li class="page-item">
                                                            <a class="page-link" href="#" aria-label="Next">
                                                                <span aria-hidden="true">&raquo;</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Table Pagination -->
                                    </div>
                                </div>
                                <!-- End Document List Card -->
                            </div>
                            <!-- end timeline content-->

                            <div class="tab-pane" id="settings">
                                <form>
                                    <h5 class="mb-4 text-uppercase">
                                        <i class="ri-contacts-book-2-line me-1"></i>
                                        Personal Info
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="firstname" class="form-label">First Name</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="firstname"
                                                    placeholder="Enter first name" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="lastname" class="form-label">Last Name</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="lastname"
                                                    placeholder="Enter last name" />
                                            </div>
                                        </div>
                                        <!-- end col -->
                                    </div>
                                    <!-- end row -->

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="userbio" class="form-label">Bio</label>
                                                <textarea
                                                    class="form-control"
                                                    id="userbio"
                                                    rows="4"
                                                    placeholder="Write something..."></textarea>
                                            </div>
                                        </div>
                                        <!-- end col -->
                                    </div>
                                    <!-- end row -->

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="useremail" class="form-label">Email Address</label>
                                                <input
                                                    type="email"
                                                    class="form-control"
                                                    id="useremail"
                                                    placeholder="Enter email" />
                                                <span class="form-text text-muted">
                                                    <small>
                                                        If you want to change email please
                                                        <a href="javascript: void(0);">click</a>
                                                        here.
                                                    </small>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="userpassword" class="form-label">Password</label>
                                                <input
                                                    type="password"
                                                    class="form-control"
                                                    id="userpassword"
                                                    placeholder="Enter password" />
                                                <span class="form-text text-muted">
                                                    <small>
                                                        If you want to change password please
                                                        <a href="javascript: void(0);">click</a>
                                                        here.
                                                    </small>
                                                </span>
                                            </div>
                                        </div>
                                        <!-- end col -->
                                    </div>
                                    <!-- end row -->

                                    <h5 class="mb-3 text-uppercase bg-light p-2">
                                        <i class="ri-building-line me-1"></i>
                                        Company Info
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="companyname" class="form-label">Company Name</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="companyname"
                                                    placeholder="Enter company name" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="cwebsite" class="form-label">Website</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="cwebsite"
                                                    placeholder="Enter website url" />
                                            </div>
                                        </div>
                                        <!-- end col -->
                                    </div>
                                    <!-- end row -->

                                    <h5 class="mb-3 text-uppercase bg-light p-2">
                                        <i class="ri-global-line me-1"></i>
                                        Social
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="social-fb" class="form-label">Facebook</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="ri-facebook-fill"></i>
                                                    </span>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        id="social-fb"
                                                        placeholder="Url" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="social-tw" class="form-label">Twitter</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="ri-twitter-line"></i>
                                                    </span>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        id="social-tw"
                                                        placeholder="Username" />
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end col -->
                                    </div>
                                    <!-- end row -->

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="social-insta" class="form-label">Instagram</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="ri-instagram-line"></i>
                                                    </span>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        id="social-insta"
                                                        placeholder="Url" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="social-lin" class="form-label">Linkedin</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="ri-linkedin-fill"></i>
                                                    </span>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        id="social-lin"
                                                        placeholder="Url" />
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end col -->
                                    </div>
                                    <!-- end row -->

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="social-sky" class="form-label">Skype</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="ri-skype-line"></i></span>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        id="social-sky"
                                                        placeholder="@username" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="social-gh" class="form-label">Github</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="ri-github-line"></i></span>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        id="social-gh"
                                                        placeholder="Username" />
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end col -->
                                    </div>
                                    <!-- end row -->

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success mt-2">
                                            <i class="ri-save-line"></i>
                                            Save
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <!-- end settings content-->
                        </div>
                        <!-- end tab-content -->
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row-->
    </div>
    <!-- container -->

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProfileForm" enctype="multipart/form-data">
                        <!-- Profile Picture Upload -->
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                <img src="/images/users/avatar-1.jpg" class="rounded-circle avatar-xl img-thumbnail" id="profileImage" alt="profile-image">
                                <div class="position-absolute bottom-0 end-0">
                                    <label for="profileImageUpload" class="btn btn-sm btn-light rounded-circle p-0" style="width: 32px; height: 32px; line-height: 32px; cursor: pointer;">
                                        <i class="ri-camera-line"></i>
                                    </label>
                                    <input type="file" id="profileImageUpload" class="d-none" accept="image/*">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="fullName" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="fullName" name="full_name" value="Tosha K. Minner" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-mail-line"></i></span>
                                        <input type="email" class="form-control" id="email" name="email" value="tosha.minner@example.com" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-phone-line"></i></span>
                                        <input type="tel" class="form-control" id="phone" name="phone" value="+1 (123) 456-7890">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="location" class="form-label">Location</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-map-pin-line"></i></span>
                                        <input type="text" class="form-control" id="location" name="location" placeholder="Enter your location">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Title/Position</label>
                            <input type="text" class="form-control" id="title" name="title" value="Founder">
                        </div>

                        <div class="mb-3">
                            <label for="aboutMe" class="form-label">About Me</label>
                            <textarea class="form-control" id="aboutMe" name="about_me" rows="3" placeholder="Tell us about yourself...">Hi I'm Tosha Minner, has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type.</textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveProfileChanges">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Modal -->
    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="messageModalLabel">Messages</h5>
                    <div>
                        <button type="button" class="btn btn-primary btn-sm" id="composeNewMessage">
                            <i class="ri-pencil-line me-1"></i> New Message
                        </button>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body p-0">
                    <div class="d-flex" style="min-height: 400px;">
                        <!-- Message List -->
                        <div class="border-end" style="width: 300px; overflow-y: auto;">
                            <div class="list-group list-group-flush">
                                <!-- Message Thread Item -->
                                <a href="#" class="list-group-item list-group-item-action border-0 p-3 message-thread active">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-2">
                                            <img src="/images/users/avatar-2.jpg" class="rounded-circle" width="40" alt="user">
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h6 class="mb-0 text-truncate">John Doe</h6>
                                            <p class="text-muted text-truncate mb-0 small">Meeting tomorrow at 10 AM</p>
                                            <small class="text-muted">10:30 AM</small>
                                        </div>
                                        <span class="badge bg-danger rounded-circle" style="width: 8px; height: 8px;"></span>
                                    </div>
                                </a>
                                <!-- More message threads would go here -->
                            </div>
                        </div>

                        <!-- Message Content or Compose New Message -->
                        <div class="flex-grow-1 d-flex flex-column" id="messageContentArea">
                            <!-- Default view - Message Content -->
                            <div id="viewMessageContent">
                                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="/images/users/avatar-2.jpg" class="rounded-circle me-2" width="36" alt="user">
                                        <div>
                                            <h6 class="mb-0">John Doe</h6>
                                            <small class="text-muted">to me</small>
                                        </div>
                                    </div>
                                    <div>
                                        <button class="btn btn-sm btn-link text-muted" id="replyToMessage">
                                            <i class="ri-reply-line"></i> Reply
                                        </button>
                                        <button class="btn btn-sm btn-link text-muted">
                                            <i class="ri-more-2-fill"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="p-3 border-bottom">
                                    <h5>Meeting tomorrow at 10 AM</h5>
                                    <p class="text-muted">Hi there,</p>
                                    <p>Just a quick reminder about our meeting tomorrow at 10 AM. Please bring your project updates and any questions you might have.</p>
                                    <p>Best regards,<br>John</p>
                                </div>
                            </div>

                            <!-- Compose New Message (initially hidden) -->
                            <div id="composeMessageContent" style="display: none;">
                                <form id="newMessageForm" class="h-100 d-flex flex-column">
                                    <div class="p-3 border-bottom">
                                        <div class="mb-3">
                                            <label for="recipient" class="form-label">To</label>
                                            <select class="form-select" id="recipient" required>
                                                <option value="">Select recipient</option>
                                                <option value="1">John Doe</option>
                                                <option value="2">Jane Smith</option>
                                                <option value="3">Robert Johnson</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="messageSubject" class="form-label">Subject</label>
                                            <input type="text" class="form-control" id="messageSubject" required>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 p-3">
                                        <textarea class="form-control h-100" id="messageContent" placeholder="Type your message here..." required></textarea>
                                    </div>
                                    <div class="p-3 border-top d-flex justify-content-between">
                                        <button type="button" class="btn btn-light" id="cancelCompose">
                                            <i class="ri-close-line me-1"></i> Discard
                                        </button>
                                        <div>
                                            <button type="button" class="btn btn-light me-2">
                                                <i class="ri-attachment-line"></i>
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ri-send-plane-line me-1"></i> Send
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Reply Form (initially hidden) -->
                            <div id="replyMessageContent" style="display: none;">
                                <form id="replyMessageForm" class="h-100 d-flex flex-column">
                                    <div class="p-3 border-bottom">
                                        <div class="mb-3">
                                            <label for="replyTo" class="form-label">To</label>
                                            <input type="text" class="form-control" id="replyTo" value="John Doe" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="replySubject" class="form-label">Subject</label>
                                            <input type="text" class="form-control" id="replySubject" value="Re: Meeting tomorrow at 10 AM" readonly>
                                        </div>
                                    </div>
                                    <div class="p-3 border-bottom bg-light">
                                        <p class="mb-1 small"><strong>Original Message:</strong></p>
                                        <p class="text-muted small mb-0">Hi there, Just a quick reminder about our meeting tomorrow at 10 AM. Please bring your project updates and any questions you might have.</p>
                                    </div>
                                    <div class="flex-grow-1 p-3">
                                        <textarea class="form-control h-100" id="replyContent" placeholder="Type your reply here..." required></textarea>
                                    </div>
                                    <div class="p-3 border-top d-flex justify-content-between">
                                        <button type="button" class="btn btn-light" id="cancelReply">
                                            <i class="ri-close-line me-1"></i> Cancel
                                        </button>
                                        <div>
                                            <button type="button" class="btn btn-light me-2">
                                                <i class="ri-attachment-line"></i>
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ri-send-plane-line me-1"></i> Send
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Message Modal -->
    <div class="modal fade" id="newMessageModal" tabindex="-1" aria-labelledby="newMessageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="newMessageModalLabel">New Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="newMessageForm">
                        <div class="mb-3">
                            <label for="recipient" class="form-label">To</label>
                            <select class="form-select" id="recipient" required>
                                <option value="">Select recipient</option>
                                <option value="1">John Doe</option>
                                <option value="2">Jane Smith</option>
                                <option value="3">Robert Johnson</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="messageSubject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="messageSubject" required>
                        </div>
                        <div class="mb-3">
                            <label for="messageContent" class="form-label">Message</label>
                            <textarea class="form-control" id="messageContent" rows="5" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Discard</button>
                    <button type="submit" form="newMessageForm" class="btn btn-primary">Send Message</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reply Modal -->
    <div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="replyModalLabel">Reply</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="replyMessageForm">
                        <div class="mb-3">
                            <label for="replyTo" class="form-label">To</label>
                            <input type="text" class="form-control" id="replyTo" value="John Doe" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="replySubject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="replySubject" value="Re: Meeting tomorrow at 10 AM" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="replyContent" class="form-label">Message</label>
                            <div class="p-3 bg-light rounded mb-2">
                                <p class="mb-1"><strong>Original Message:</strong></p>
                                <p class="text-muted small mb-0">Hi there, Just a quick reminder about our meeting tomorrow at 10 AM...</p>
                            </div>
                            <textarea class="form-control" id="replyContent" rows="5" placeholder="Type your reply here..." required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="replyMessageForm" class="btn btn-primary">Send Reply</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @vite(['resources/js/pages/demo.profile.js'])
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Profile Image Upload
            const profileImage = document.getElementById('profileImage');
            const profileImageInput = document.getElementById('profileImageInput');
            const profileImageForm = document.getElementById('profileImageForm');
            
            // When clicking on the profile image, trigger the file input
            if (profileImage) {
                profileImage.addEventListener('click', function() {
                    profileImageInput.click();
                });
            }
            
            // When a new image is selected
            if (profileImageInput) {
                profileImageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        // Check file type
                        const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
                        if (!validTypes.includes(file.type)) {
                            alert('Please select a valid image (JPEG, PNG, or GIF)');
                            return;
                        }
                        
                        // Check file size (max 2MB)
                        if (file.size > 2 * 1024 * 1024) {
                            alert('Image size should not exceed 2MB');
                            return;
                        }
                        
                        // Show preview
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            profileImage.src = event.target.result;
                            
                            // Submit the form
                            const formData = new FormData(profileImageForm);
                            formData.append('_token', '{{ csrf_token() }}');
                            
                            fetch(profileImageForm.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Update the image source with the new path
                                    profileImage.src = data.path + '?t=' + new Date().getTime();
                                    // Show success message
                                    toastr.success('Profile image updated successfully');
                                } else {
                                    toastr.error(data.message || 'Failed to update profile image');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                toastr.error('An error occurred while uploading the image');
                            });
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
            // Message Interface Controls
            const messageModal = document.getElementById('messageModal');
            const composeBtn = document.getElementById('composeNewMessage');
            const replyBtn = document.getElementById('replyToMessage');
            const cancelComposeBtn = document.getElementById('cancelCompose');
            const cancelReplyBtn = document.getElementById('cancelReply');
            
            // Message Content Areas
            const viewMessageContent = document.getElementById('viewMessageContent');
            const composeMessageContent = document.getElementById('composeMessageContent');
            const replyMessageContent = document.getElementById('replyMessageContent');
            
            // Message Forms
            const newMessageForm = document.getElementById('newMessageForm');
            const replyMessageForm = document.getElementById('replyMessageForm');
            
            // Show compose message
            if (composeBtn) {
                composeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    viewMessageContent.style.display = 'none';
                    replyMessageContent.style.display = 'none';
                    composeMessageContent.style.display = 'block';
                    document.getElementById('messageSubject').focus();
                });
            }
            
            // Show reply form
            if (replyBtn) {
                replyBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    viewMessageContent.style.display = 'none';
                    composeMessageContent.style.display = 'none';
                    replyMessageContent.style.display = 'block';
                    document.getElementById('replyContent').focus();
                });
            }
            
            // Cancel compose
            if (cancelComposeBtn) {
                cancelComposeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (confirm('Discard this message?')) {
                        composeMessageContent.style.display = 'none';
                        viewMessageContent.style.display = 'block';
                        newMessageForm.reset();
                    }
                });
            }
            
            // Cancel reply
            if (cancelReplyBtn) {
                cancelReplyBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    replyMessageContent.style.display = 'none';
                    viewMessageContent.style.display = 'block';
                    replyMessageForm.reset();
                });
            }
            
            // Handle new message form submission
            if (newMessageForm) {
                newMessageForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    // Here you would typically send the message via AJAX
                    const formData = new FormData(newMessageForm);
                    console.log('New message data:', Object.fromEntries(formData));
                    
                    // Show success message
                    alert('Message sent successfully!');
                    
                    // Reset form and show message list
                    newMessageForm.reset();
                    composeMessageContent.style.display = 'none';
                    viewMessageContent.style.display = 'block';
                });
            }
            
            // Handle reply form submission
            if (replyMessageForm) {
                replyMessageForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    // Here you would typically send the reply via AJAX
                    const formData = new FormData(replyMessageForm);
                    console.log('Reply data:', Object.fromEntries(formData));
                    
                    // Show success message
                    alert('Reply sent successfully!');
                    
                    // Reset form and show message list
                    replyMessageForm.reset();
                    replyMessageContent.style.display = 'none';
                    viewMessageContent.style.display = 'block';
                });
            }
            
            // Reset to default view when modal is closed
            if (messageModal) {
                messageModal.addEventListener('hidden.bs.modal', function () {
                    viewMessageContent.style.display = 'block';
                    composeMessageContent.style.display = 'none';
                    replyMessageContent.style.display = 'none';
                    newMessageForm.reset();
                    replyMessageForm.reset();
                });
            }
            // Profile image handling
            const profileImage = document.getElementById('profileImage');
            const profileImageUpload = document.getElementById('profileImageUpload');
            const editProfileForm = document.getElementById('editProfileForm');
            
            // Handle profile image preview
            if (profileImageUpload) {
                profileImageUpload.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        // Validate file type
                        const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
                        if (!validTypes.includes(file.type)) {
                            alert('Please select a valid image file (JPEG, PNG, GIF)');
                            return;
                        }
                        
                        // Validate file size (max 2MB)
                        const maxSize = 2 * 1024 * 1024; // 2MB
                        if (file.size > maxSize) {
                            alert('Image size should not exceed 2MB');
                            return;
                        }
                        
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            profileImage.src = event.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
            
            // Handle form submission
            if (editProfileForm) {
                editProfileForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Get form data
                    const formData = new FormData(editProfileForm);
                    
                    // Add the profile image if changed
                    const fileInput = document.getElementById('profileImageUpload');
                    if (fileInput.files.length > 0) {
                        formData.append('profile_image', fileInput.files[0]);
                    }
                    
                    // Add CSRF token
                    formData.append('_token', '{{ csrf_token() }}');
                    
                    // Disable the submit button
                    const submitBtn = document.getElementById('saveProfileChanges');
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
                    
                    // Here you would typically send the form data to your server
                    console.log('Form data:', Object.fromEntries(formData));
                    
                    // Example AJAX request (uncomment and configure as needed)
                    /*
                    fetch('/profile/update', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            alert('Profile updated successfully!');
                            // Close the modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));
                            modal.hide();
                            // Optionally, refresh the page or update the UI
                            // location.reload();
                        } else {
                            throw new Error(data.message || 'Failed to update profile');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error: ' + error.message);
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = 'Save Changes';
                    });
                    */
                    
                    // For demo purposes, just show a success message
                    setTimeout(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = 'Save Changes';
                        alert('Profile updated successfully! (Demo)');
                    }, 1000);
                });
            }
        });
    </script>
@endsection
