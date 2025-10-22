@extends('layouts.vertical', ['page_title' => 'Menu Manager'])

@section('css')
<link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/libs/nestable2/nestable2.min.css') }}" rel="stylesheet" type="text/css" />
<style>
    .form-floating {
        position: relative;
        margin-bottom: 1rem;
    }
    
    /* Input placeholder styling */
    .form-control::placeholder,
    .form-select::placeholder {
        color: var(--#{$prefix}gray-500);
        opacity: 0.7;
    }
    
    /* Dark mode specific placeholder styling */
    [data-bs-theme="dark"] .form-control::placeholder,
    [data-bs-theme="dark"] .form-select::placeholder {
        color: var(--#{$prefix}gray-400);
        opacity: 0.8;
    }
    
    .form-floating input.form-control,
    .form-floating select.form-select,
    .form-floating textarea.form-control {
        height: 50px;
        border: 1px solid var(--#{$prefix}border-color);
        border-radius: 10px;
        background-color: var(--#{$prefix}input-bg);
        color: var(--#{$prefix}body-color);
        font-size: 1rem;
        padding: 1rem 0.75rem;
        transition: all 0.3s ease;
    }
    
    /* Enhanced focus state for dark mode */
    [data-bs-theme="dark"] .form-floating input.form-control:focus,
    [data-bs-theme="dark"] .form-floating select.form-select:focus,
    [data-bs-theme="dark"] .form-floating textarea.form-control:focus {
        border-color: var(--#{$prefix}primary);
        box-shadow: 0 0 0 0.15rem rgba(var(--#{$prefix}primary-rgb), 0.25);
        background-color: var(--#{$prefix}dark);
    }
    
    .form-floating textarea.form-control {
        height: auto;
        min-height: 100px;
    }
    
    .form-floating label {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        padding: 1rem 0.75rem;
        color: var(--#{$prefix}gray-600);
        transition: all 0.3s ease;
        pointer-events: none;
        z-index: 1;
    }
    
    /* Dark mode label color */
    [data-bs-theme="dark"] .form-floating label {
        color: var(--#{$prefix}gray-400);
    }
    
    .form-floating input.form-control:focus ~ label,
    .form-floating input.form-control:not(:placeholder-shown) ~ label,
    .form-floating select.form-select:focus ~ label,
    .form-floating select.form-select:not([value=""]) ~ label,
    .form-floating textarea.form-control:focus ~ label,
    .form-floating textarea.form-control:not(:placeholder-shown) ~ label {
        height: auto;
        padding: 0 0.5rem;
        transform: translateY(-50%) translateX(0.5rem) scale(0.85);
        color: var(--#{$prefix}primary);
        background-color: var(--#{$prefix}body-bg);
        border-radius: 5px;
        z-index: 5;
    }
    
    /* Menu item styling */
    .menu-item {
        padding: 1rem;
        margin-bottom: 0.5rem;
        border: 1px solid var(--#{$prefix}border-color);
        border-radius: 0.5rem;
        background-color: var(--#{$prefix}card-bg);
        transition: all 0.3s ease;
    }
    
    .menu-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 3px 10px rgba(var(--#{$prefix}dark-rgb), 0.1);
    }
    
    /* Dark mode specific menu item hover */
    [data-bs-theme="dark"] .menu-item:hover {
        background-color: var(--#{$prefix}dark);
        border-color: var(--#{$prefix}primary);
    }
    
    /* Placeholder shimmer effect for loading states */
    @keyframes placeholderShimmer {
        0% {
            background-position: -1000px 0;
        }
        100% {
            background-position: 1000px 0;
        }
    }
    
    .placeholder-shimmer {
        animation: placeholderShimmer 2s infinite linear;
        background: linear-gradient(
            to right,
            var(--#{$prefix}gray-200) 8%,
            var(--#{$prefix}gray-300) 18%,
            var(--#{$prefix}gray-200) 33%
        );
        background-size: 1000px 100%;
    }
    
    /* Dark mode shimmer effect */
    [data-bs-theme="dark"] .placeholder-shimmer {
        background: linear-gradient(
            to right,
            var(--#{$prefix}gray-700) 8%,
            var(--#{$prefix}gray-600) 18%,
            var(--#{$prefix}gray-700) 33%
        );
    }
    
    /* Empty state placeholder */
    .empty-placeholder {
        padding: 2rem;
        text-align: center;
        border: 2px dashed var(--#{$prefix}border-color);
        border-radius: 10px;
        margin: 1rem 0;
        color: var(--#{$prefix}gray-600);
        transition: all 0.3s ease;
    }
    
    /* Dark mode empty placeholder */
    [data-bs-theme="dark"] .empty-placeholder {
        border-color: var(--#{$prefix}gray-700);
        color: var(--#{$prefix}gray-400);
    }
    
    .empty-placeholder:hover {
        border-color: var(--#{$prefix}primary);
        color: var(--#{$prefix}primary);
    }
    
    /* Drag and drop placeholder */
    .sortable-placeholder {
        border: 2px dashed var(--#{$prefix}primary);
        background-color: rgba(var(--#{$prefix}primary-rgb), 0.1);
        border-radius: 0.5rem;
        margin: 0.5rem 0;
        height: 60px;
    }
    
    /* Dark mode sortable placeholder */
    [data-bs-theme="dark"] .sortable-placeholder {
        background-color: rgba(var(--#{$prefix}primary-rgb), 0.15);
    }
    
    /* Status indicators */
    .status-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 0.5rem;
    }
    
    .status-dot.active {
        background-color: var(--#{$prefix}success);
        box-shadow: 0 0 0 3px rgba(var(--#{$prefix}success-rgb), 0.2);
    }
    
    .status-dot.inactive {
        background-color: var(--#{$prefix}danger);
        box-shadow: 0 0 0 3px rgba(var(--#{$prefix}danger-rgb), 0.2);
    }
    
    /* Dark mode status dots */
    [data-bs-theme="dark"] .status-dot {
        filter: brightness(1.2);
    }
    
    .icon-preview {
        font-size: 1.5rem;
        margin: 0.5rem 0;
        color: #033c42;
    }
    .icon-list {
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #e9ecef;
        padding: 1.5rem;
        border-radius: 15px;
        background-color: #fff;
    }
    .icon-list i {
        font-size: 1.2rem;
        margin: 0.5rem;
        cursor: pointer;
        transition: all 0.3s;
        color: #2f2f2f;
    }
    .icon-list i:hover {
        color: #033c42;
        transform: scale(1.2);
    }
    .dd {
        max-width: 100%;
    }
    .dd-item {
        margin-bottom: 8px;
    }
    .dd-handle {
        height: auto;
        padding: 12px 20px;
        border-radius: 10px;
        border: 1px solid #e9ecef;
        background-color: #fff;
        transition: all 0.3s;
    }
    .dd-handle:hover {
        border-color: #033c42;
        background-color: #f8f9fa;
    }
    .menu-actions {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
    }
    .btn-primary {
        background-color: #033c42;
        border-color: #033c42;
    }
    .btn-primary:hover {
        background-color: #022b30;
        border-color: #022b30;
    }
    .btn-light {
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }
    .btn-light:hover {
        background-color: #e9ecef;
        border-color: #dee2e6;
    }
    .select2-container .select2-selection--single,
    .select2-container .select2-selection--multiple {
        height: 50px;
        border: 1px solid #2f2f2f;
        border-radius: 10px;
        background-color: transparent;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 48px;
        padding-left: 0.75rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 48px;
    }
    .form-switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
        margin: 0;
        padding: 0;
    }
    .form-switch input {
        position: absolute;
        width: 100%;
        height: 100%;
        margin: 0;
        cursor: pointer;
        opacity: 0;
        z-index: 1;
    }
    .form-switch .switch-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ef5350;
        transition: .4s;
        border-radius: 34px;
        border: 2px solid transparent;
    }
    .form-switch .switch-slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    .form-switch input:checked + .switch-slider {
        background-color: #4caf50;
    }
    .form-switch input:focus + .switch-slider {
        box-shadow: 0 0 1px #4caf50;
        border-color: rgba(76, 175, 80, 0.4);
    }
    .form-switch input:checked + .switch-slider:before {
        transform: translateX(26px);
    }
    .form-switch .switch-label {
        position: absolute;
        left: 70px;
        top: 50%;
        transform: translateY(-50%);
        font-weight: 500;
        color: #6c757d;
        white-space: nowrap;
        user-select: none;
    }
    .form-switch input:checked ~ .switch-label {
        color: #4caf50;
    }
    .form-switch .switch-label:after {
        content: "Inactive";
    }
    .form-switch input:checked ~ .switch-label:after {
        content: "Active";
    }
    .table {
        border-radius: 15px;
        overflow: hidden;
    }
    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        padding: 1rem;
    }
    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
    }
    .modal-content {
        border-radius: 15px;
        border: none;
    }
    .modal-header {
        border-bottom: 1px solid #e9ecef;
        background-color: #f8f9fa;
        border-radius: 15px 15px 0 0;
    }
    .modal-footer {
        border-top: 1px solid #e9ecef;
        background-color: #f8f9fa;
        border-radius: 0 0 15px 15px;
    }
    .btn-close {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    .btn-close:hover {
        background-color: #b02a37;
        border-color: #b02a37;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active">Menu Manager</li>
                    </ol>
                </div>
                <h4 class="page-title">Menu Manager</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <!-- Menu List -->
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <h4 class="header-title">Menu Structure</h4>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMenuModal">
                                <i class="mdi mdi-plus-circle me-1"></i> Add New Menu
                            </button>
                        </div>
                    </div>

                    <!-- Search and Filters -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="menuSearch" placeholder="Search">
                                <label for="menuSearch">Search menus...</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <select class="form-select" id="menuTypeFilter">
                                    <option value="">All Types</option>
                                    <option value="main">Main Menu</option>
                                    <option value="sub">Sub Menu</option>
                                </select>
                                <label for="menuTypeFilter">Menu Type</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <select class="form-select" id="statusFilter">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <label for="statusFilter">Status</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-light w-100 h-100" id="resetFilters">
                                <i class="mdi mdi-refresh me-1"></i> Reset
                            </button>
                        </div>
                    </div>

                    <!-- Menu List Table -->
                    <div class="table-responsive">
                        <table id="menu-datatable" class="table dt-responsive nowrap">
                            <thead>
                                <tr>
                                    <th>Menu Name</th>
                                    <th>Type</th>
                                    <th>URL/Route</th>
                                    <th>Position</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Menu items will be dynamically populated here -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Nestable Menu Structure -->
                    <div class="mt-4">
                        <h5 class="mb-3">Menu Hierarchy</h5>
                        <div class="dd" id="nestable">
                            <ol class="dd-list">
                                <!-- Nested menu structure will be here -->
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Menu Modal -->
<div class="modal fade" id="addMenuModal" tabindex="-1" aria-labelledby="addMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMenuModalLabel">Add New Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="menuForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="menu_name" name="menu_name" placeholder="Menu Name" required>
                                <label for="menu_name">Menu Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="url" name="url" placeholder="URL/Route" required>
                                <label for="url">URL/Route</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="menu_type" name="menu_type" required>
                                    <option value="">Select Type</option>
                                    <option value="main">Main Menu</option>
                                    <option value="sub">Sub Menu</option>
                                </select>
                                <label for="menu_type">Menu Type</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="parent_menu" name="parent_menu">
                                    <option value="">None</option>
                                    <!-- Parent menu options will be populated dynamically -->
                                </select>
                                <label for="parent_menu">Parent Menu</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="position" name="position" placeholder="Position" min="1">
                                <label for="position">Position</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="icon" name="icon" placeholder="Icon" readonly>
                                    <button class="btn btn-secondary" type="button" id="iconPicker">
                                        <i class="mdi mdi-format-list-bulleted me-1"></i> Choose Icon
                                    </button>
                                </div>
                                <div class="icon-preview mt-2"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox" id="statusSwitch" name="status">
                                    <span class="switch-slider"></span>
                                    <span class="switch-label"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <select class="form-select select2" id="roles" name="roles[]" multiple>
                            <option value="admin">Admin</option>
                            <option value="manager">Manager</option>
                            <option value="user">User</option>
                        </select>
                        <label for="roles">Role Access</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveMenu">Save Menu</button>
            </div>
        </div>
    </div>
</div>

<!-- Icon Picker Modal -->
<div class="modal fade" id="iconPickerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Choose Icon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="iconSearch" placeholder="Search">
                    <label for="iconSearch">Search icons...</label>
                </div>
                <div class="icon-list">
                    <!-- Icons will be populated dynamically -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- Required datatable js -->
<script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/libs/nestable2/nestable2.min.js') }}"></script>
<script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#menu-datatable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            // Add your ajax configuration here
        });

        // Initialize Nestable
        $('#nestable').nestable({
            group: 1,
            maxDepth: 3
        });

        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap',
            width: '100%'
        });

        // Save menu hierarchy on change
        $('#nestable').on('change', function() {
            var data = $('#nestable').nestable('serialize');
            // Add AJAX call to save menu hierarchy
        });

        // Handle menu form submission
        $('#saveMenu').click(function() {
            // Add form submission logic here
        });

        // Icon picker functionality
        $('#iconPicker').click(function() {
            $('#iconPickerModal').modal('show');
        });

        // Search functionality
        $('#menuSearch').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Filter functionality
        $('#menuTypeFilter, #statusFilter').change(function() {
            table.draw();
        });

        // Reset filters
        $('#resetFilters').click(function() {
            $('#menuSearch').val('');
            $('#menuTypeFilter').val('');
            $('#statusFilter').val('');
            table.search('').columns().search('').draw();
        });
    });
</script>
@endsection
