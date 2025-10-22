@extends('layouts.vertical', ['page_title' => 'GESL Project Management'])

@section('css')
@vite([
    'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css',
    'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css',
    'node_modules/select2/dist/css/select2.min.css',
    'node_modules/flatpickr/dist/flatpickr.min.css',
    'node_modules/dropzone/dist/min/dropzone.min.css',
    'node_modules/apexcharts/dist/apexcharts.css'
])
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<!-- Reports Component Styles -->
@include('company.ProjectManagement.components.reports-styles')

<style>
    :root {
        --gesl-primary: #0056b3;       /* GESL Blue */
        --gesl-secondary: #ffc107;     /* GESL Yellow */
        --gesl-dark: #212529;          /* Dark Gray for text */
        --gesl-light: #f8f9fa;         /* Light Gray for backgrounds */
        --gesl-success: #28a745;       /* Green for completed/active */
        --gesl-warning: #fd7e14;       /* Orange for warnings */
        --gesl-danger: #dc3545;        /* Red for critical/errors */
    }

    body {
        background-color: var(--gesl-light);
        color: var(--gesl-dark);
    }

    .project-container { 
        background-color: #f0f4f8; 
        min-height: calc(100vh - 70px); 
        padding: 20px; 
    }
    
    .project-tabs {
        background: #fff; 
        border-radius: 8px 8px 0 0;
        box-shadow: 0 2px 10px rgba(0, 86, 179, 0.1);
        margin-bottom: 0; 
        padding: 0 15px;
        border: none;
    }
    
    .project-tabs .nav-link {
        color:rgb(6, 64, 115); 
        font-weight: 600; 
        padding: 15px 25px;
        border: none; 
        position: relative; 
        transition: all 0.3s ease;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    
    .project-tabs .nav-link.active { 
        color: var(--gesl-primary); 
        background: transparent; 
    }
    
    .project-tabs .nav-link.active:after {
        content: ''; 
        position: absolute; 
        bottom: 0;
        left: 25%; 
        right: 25%; 
        height: 3px; 
        background: var(--gesl-primary);
        border-radius: 3px 3px 0 0;
    }
    
    .btn-gesl-primary {
        background-color: var(--gesl-primary);
        border-color: var(--gesl-primary);
        color: white;
        font-weight: 500;
        letter-spacing: 0.3px;
        padding: 0.5rem 1.25rem;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease-in-out, transform 0.1s ease;
        position: relative;
        overflow: hidden;
    }
    
    .btn-gesl-primary:hover {
        background-color: #004494;
        border-color: #004494;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    
    .btn-gesl-primary:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .btn-gesl-primary i {
        transition: transform 0.2s ease-in-out;
    }
    
    .btn-gesl-primary:hover i {
        transform: translateX(2px);
    }
    
    /* Delete button styling - more specific selectors */
    .btn.btn-danger,
    .btn-sm.btn-danger,
    .action-btn.btn-danger {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
        color: white !important;
    }
    
    .btn.btn-danger:hover,
    .btn-sm.btn-danger:hover,
    .action-btn.btn-danger:hover {
        background-color: #c82333 !important;
        border-color: #bd2130 !important;
        color: white !important;
    }
    
    .btn.btn-danger:focus,
    .btn-sm.btn-danger:focus,
    .action-btn.btn-danger:focus {
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.5) !important;
    }
    
    /* Even more specific for the delete button */
    button.btn.btn-sm.btn-danger.action-btn {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
        color: white !important;
    }
    
    button.btn.btn-sm.btn-danger.action-btn:hover {
        background-color: #c82333 !important;
        border-color: #bd2130 !important;
        color: white !important;
    }
    
    .text-gesl-primary {
        color: var(--gesl-primary) !important;
    }
    
    .bg-gesl-light {
        background-color: var(--gesl-light);
    }
    
    /* Card styles */
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }
    
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    
    .card-title {
        font-weight: 600;
        color: var(--gesl-dark);
    }
    
    /* Avatar styles */
    .avatar-xs {
        width: 24px;
        height: 24px;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .avatar-sm {
        width: 36px;
        height: 36px;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .avatar-group {
        display: flex;
        flex-wrap: wrap;
    }
    
    .avatar-group-item {
        margin-left: -8px;
        transition: all 0.2s ease;
    }
    
    .avatar-group-item:hover {
        z-index: 1;
        transform: translateY(-2px);
    }
    
    /* Badge styles */
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
    }
    
    /* Table styles */
    .table {
        --bs-table-striped-bg: rgba(0, 0, 0, 0.01);
    }
    
    .table > :not(caption) > * > * {
        padding: 1rem 1.25rem;
        border-bottom-width: 1px;
    }
    
    .table > thead > tr > th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--bs-border-color);
    }
    
    /* Progress bar */
    .progress {
        height: 6px;
        border-radius: 3px;
    }
    
    /* Activity feed */
    .activity-feed .avatar-xs {
        margin-top: 2px;
    }
    
    .activity-feed h6 {
        font-size: 0.85rem;
        margin-bottom: 0.25rem;
    }
    
    .activity-feed p {
        font-size: 0.8rem;
        margin-bottom: 0.25rem;
    }
    
    .activity-feed small {
        font-size: 0.7rem;
    }
</style>
@endsection

@section('content')
<!-- Toast Container -->
<div id="toastContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 11">
    <!-- Toast messages will be inserted here -->
</div>

<div class="project-container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">GESL</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Project Management</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
                <h4 class="page-title">Project Management</h4>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs project-tabs" id="projectTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ session('active_tab') === 'tasks' ? '' : 'active' }}" id="projects-tab" data-bs-toggle="tab" data-bs-target="#projects" type="button" role="tab" aria-controls="projects" aria-selected="{{ session('active_tab') === 'tasks' ? 'false' : 'true' }}">
                <i class="fas fa-project-diagram me-1"></i> Projects
            </button>
        </li>
        <!-- <li class="nav-item" role="presentation">
            <button class="nav-link" id="teams-tab" data-bs-toggle="tab" data-bs-target="#teams" type="button" role="tab" aria-controls="teams" aria-selected="false">
                <i class="fas fa-users me-1"></i> Teams
            </button>
        </li> -->
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ session('active_tab') === 'tasks' ? 'active' : '' }}" id="tasks-tab" data-bs-toggle="tab" data-bs-target="#tasks" type="button" role="tab" aria-controls="tasks" aria-selected="{{ session('active_tab') === 'tasks' ? 'true' : 'false' }}">
                <i class="fas fa-tasks me-1"></i> Tasks
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reports" type="button" role="tab" aria-controls="reports" aria-selected="false">
                <i class="fas fa-chart-bar me-1"></i> Reports
            </button>
        </li>
    </ul>
    
    <!-- Tab Content -->
    <div class="tab-content p-3 bg-white rounded-bottom">
        @include('company.ProjectManagement.components.projects')
        
        <!-- Teams Tab Content -->
        <div class="tab-pane fade" id="teams" role="tabpanel" aria-labelledby="teams-tab">
            @include('company.ProjectManagement.components.teams')
        </div>
        
        @include('company.ProjectManagement.components.tasks')
        @include('company.ProjectManagement.components.reports', ['analytics' => $analytics ?? []])
    </div>
</div>

@endsection

@push('scripts')
<!-- DataTables CDN -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<!-- Select2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Other dependencies CDN -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.44.0/dist/apexcharts.min.js"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

<script>
// Global variables
let currentProjectId = null;
let chartInstances = {};

// Initialize UI components when document is ready
// Edit Project Function
function editProject(event, id, name, description, typeId, managerId, startDate, endDate, budget, progress, status) {
    event.preventDefault();
    
    // Set form values
    document.getElementById('editProjectId').value = id;
    document.getElementById('editProjectName').value = name;
    document.getElementById('editProjectDescription').value = description;
    document.getElementById('editStartDate').value = startDate;
    document.getElementById('editEndDate').value = endDate;
    document.getElementById('editProjectBudget').value = budget;
    document.getElementById('editProjectProgress').value = progress;
    document.getElementById('editProjectProgressValue').textContent = progress + '%';
    
    // Set dropdowns
    if (typeId) document.getElementById('editProjectType').value = typeId;
    if (managerId) document.getElementById('editProjectManager').value = managerId;
    
    // Set status radio button
    if (status) {
        const statusId = 'editStatus' + status.charAt(0).toUpperCase() + status.slice(1).replace('_', '');
        document.getElementById(statusId).checked = true;
    }
    
    // Set form action
    document.getElementById('editProjectForm').action = '/company/projects/' + id;
    
    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('editProjectModal'));
    modal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    
    // Make editProject function globally available
    window.editProject = editProject;
    
    // Initialize popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl));
    
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
        dropdownParent: $('body')
    });
    
    // Initialize date pickers
    $('.datepicker').flatpickr({
        dateFormat: 'Y-m-d',
        allowInput: true
    });
    
    // Initialize DataTables
    $('.datatable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search..."
        },
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
    });
    
    // Initialize form validation
    $('.needs-validation').on('submit', function(event) {
        if (this.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        }
        $(this).addClass('was-validated');
    });
    
    // Initialize Dropzone for file uploads
    if (typeof Dropzone !== 'undefined') {
        Dropzone.autoDiscover = false;
        $('.dropzone').each(function() {
            new Dropzone(this, {
                url: $(this).data('url') || '/upload',
                maxFilesize: 10, // MB
                acceptedFiles: $(this).data('accepted-files') || 'image/*,.pdf,.doc,.docx,.xls,.xlsx',
                maxFiles: $(this).data('max-files') || 5,
                addRemoveLinks: true,
                dictDefaultMessage: 'Drop files here or click to upload',
                dictRemoveFile: 'Remove file',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                init: function() {
                    this.on('success', function(file, response) {
                        $(file.previewElement).find('.dz-remove').attr('data-file-id', response.id);
                    });
                }
            });
        });
    }
    
    });
    
    // Sample data removed - using real data from database
    
    // Essential utility functions only
    function formatDate(date) {
        if (!date) return 'N/A';
        try {
            return new Date(date).toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            });
        } catch (e) {
            console.error('Error formatting date:', e);
            return 'Invalid Date';
        }
    }
    
    function formatCurrency(amount) {
        if (amount === null || amount === undefined) return 'N/A';
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(amount);
    }
    
</script>

<!-- SweetAlert2 Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Fallback SweetAlert2 Script -->
<script>
if (typeof Swal === 'undefined') {
    console.log('Loading fallback SweetAlert...');
    var script = document.createElement('script');
    script.src = 'https://unpkg.com/sweetalert2@11';
    document.head.appendChild(script);
}
</script>

<script>
// Handle session messages with SweetAlert
document.addEventListener('DOMContentLoaded', function() {
    // Test if SweetAlert is working
    console.log('SweetAlert loaded:', typeof Swal);
    
    // Test SweetAlert functionality
    if (typeof Swal !== 'undefined') {
        console.log('SweetAlert is available');
        
        // SweetAlert is working - no test needed
    } else {
        console.error('SweetAlert is not loaded!');
    }
    
    // Debug session data
    console.log('Session success:', '{{ session('success') }}');
    console.log('Session error:', '{{ session('error') }}');
    console.log('Session active_tab:', '{{ session('active_tab') }}');
    
    @if(session('success'))
        console.log('Success message received:', '{{ session('success') }}');
        Swal.fire({
            title: 'Success!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonColor: '#28a745',
            confirmButtonText: 'OK',
            timer: 3000,
            timerProgressBar: true
        });
    @endif

    @if(session('error'))
        Swal.fire({
            title: 'Error!',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'OK'
        });
    @endif

    @if($errors->any())
        Swal.fire({
            title: 'Validation Error!',
            text: '{{ $errors->first() }}',
            icon: 'error',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'OK'
        });
    @endif
});
</script>
@endpush