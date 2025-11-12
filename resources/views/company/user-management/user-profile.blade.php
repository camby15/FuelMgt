@extends('layouts.vertical', ['page_title' => 'User Profiles'])

@section('css')

    <style>
        .profile-card {
            border-left: 4px solid #727cf5;
            transition: transform 0.2s;
        }
        .profile-card:hover {
            transform: translateY(-3px);
        }
        .menu-item {
            padding: 8px;
            margin: 4px 0;
            border-radius: 4px;
            background-color: #f8f9fa;
        }
        .menu-item:hover {
            background-color: #e9ecef;
        }
        .profile-search {
            border-radius: 20px;
            padding-left: 20px;
        }

        /* Floating Label Styles */
        .form-floating {
            position: relative;
            margin-bottom: 1rem;
        }
        .form-floating input.form-control,
        .form-floating select.form-select,
        .form-floating textarea.form-control {
            height: 50px;
            border: 1px solid #2f2f2f;
            border-radius: 10px;
            background-color: transparent;
            font-size: 1rem;
            padding: 1rem 0.75rem;
            transition: all 0.8s;
        }
        .form-floating textarea.form-control {
            min-height: 100px;
            height: auto;
            padding-top: 1.625rem;
        }
        .form-floating label {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            padding: 1rem 0.75rem;
            color: #2f2f2f;
            transition: all 0.8s;
            pointer-events: none;
            z-index: 1;
        }
        .form-floating input.form-control:focus,
        .form-floating input.form-control:not(:placeholder-shown),
        .form-floating select.form-select:focus,
        .form-floating select.form-select:not([value='']),
        .form-floating textarea.form-control:focus,
        .form-floating textarea.form-control:not(:placeholder-shown) {
            border-color: #033c42;
            box-shadow: none;
        }
        .form-floating input.form-control:focus ~ label,
        .form-floating input.form-control:not(:placeholder-shown) ~ label,
        .form-floating select.form-select:focus ~ label,
        .form-floating select.form-select:not([value='']) ~ label,
        .form-floating textarea.form-control:focus ~ label,
        .form-floating textarea.form-control:not(:placeholder-shown) ~ label {
            height: auto;
            padding: 0 0.5rem;
            transform: translateY(-50%) translateX(0.5rem) scale(0.85);
            color: white;
            border-radius: 5px;
            z-index: 5;
        }
        .form-floating input.form-control:focus ~ label::before,
        .form-floating input.form-control:not(:placeholder-shown) ~ label::before,
        .form-floating select.form-select:focus ~ label::before,
        .form-floating select.form-select:not([value='']) ~ label::before,
        .form-floating textarea.form-control:focus ~ label::before,
        .form-floating textarea.form-control:not(:placeholder-shown) ~ label::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: #033c42;
            border-radius: 5px;
            z-index: -1;
        }
        .form-floating input.form-control:focus::placeholder {
            color: transparent;
        }

        /* Dark mode styles */
        [data-bs-theme='dark'] .form-floating input.form-control,
        [data-bs-theme='dark'] .form-floating select.form-select,
        [data-bs-theme='dark'] .form-floating textarea.form-control {
            border-color: #6c757d;
            color: #e9ecef;
        }

        [data-bs-theme='dark'] .form-floating label {
            color: #adb5bd;
        }

        [data-bs-theme='dark'] .form-floating input.form-control:focus,
        [data-bs-theme='dark'] .form-floating input.form-control:not(:placeholder-shown),
        [data-bs-theme='dark'] .form-floating select.form-select:focus,
        [data-bs-theme='dark'] .form-floating select.form-select:not([value='']),
        [data-bs-theme='dark'] .form-floating textarea.form-control:focus,
        [data-bs-theme='dark'] .form-floating textarea.form-control:not(:placeholder-shown) {
            border-color: #0dcaf0;
        }

        [data-bs-theme='dark'] .form-floating input.form-control:focus ~ label::before,
        [data-bs-theme='dark'] .form-floating input.form-control:not(:placeholder-shown) ~ label::before,
        [data-bs-theme='dark'] .form-floating select.form-select:focus ~ label::before,
        [data-bs-theme='dark'] .form-floating select.form-select:not([value='']) ~ label::before,
        [data-bs-theme='dark'] .form-floating textarea.form-control:focus ~ label::before,
        [data-bs-theme='dark'] .form-floating textarea.form-control:not(:placeholder-shown) ~ label::before {
            background: #0dcaf0;
        }

        [data-bs-theme='dark'] select.form-select option {
            background-color: #212529;
            color: #e9ecef;
        }

        /* Reset and simplify select styles */
        .form-floating select.form-select {
            display: block;
            width: 100%;
            height: 50px;
            padding: 1rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #2f2f2f;
            background-color: transparent;
            border: 1px solid #2f2f2f;
            border-radius: 10px;
            transition: all 0.8s;
            appearance: none;
            background: url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'><path fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/></svg>")
                no-repeat right 0.75rem center/16px 12px;
        }

        [data-bs-theme='dark'] .form-floating select.form-select {
            background: url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'><path fill='none' stroke='%23adb5bd' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/></svg>")
                no-repeat right 0.75rem center/16px 12px;
            background-color: transparent;
        }

        .form-floating select.form-select:focus {
            border-color: #033c42;
            outline: 0;
            box-shadow: none;
        }

        .form-floating select.form-select ~ label {
            padding: 1rem 0.75rem;
        }

        .modal-body {
            background: none;
            padding: 1.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                            <li class="breadcrumb-item active">User Profiles</li>
                        </ol>
                    </div>
                    <h4 class="page-title">User Profiles</h4>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <!-- Left Sidebar - Profile List -->
            <div class="col-xl-3 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="header-title mb-0">User Profiles</h4>
                            <button
                                type="button"
                                class="btn btn-primary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#addProfileModal">
                                <i class="ri-add-line"></i>
                                Add Profile
                            </button>
                        </div>

                        <div class="form-floating mb-3">
                            <input
                                type="text"
                                class="form-control"
                                id="searchProfiles"
                                placeholder="Search profiles..." />
                            <label for="searchProfiles">Search Profiles</label>
                        </div>

                        <div class="profile-list">
                            @if (isset($error))
                                <div class="alert alert-danger" role="alert">
                                    {{ $error }}
                                </div>
                            @endif

                            @forelse ($profiles ?? collect() as $profile)
                                <div
                                    class="profile-card p-3 mb-2 cursor-pointer border rounded"
                                    data-profile-id="{{ $profile->id }}">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm">
                                            <span
                                                class="avatar-title rounded-circle {{ $profile->status === 'active' ? 'bg-primary-subtle text-primary' : 'bg-info-subtle text-info' }}">
                                                {{ strtoupper(substr($profile->profile_name, 0, 2)) }}
                                            </span>
                                        </div>
                                        <div class="ms-2 flex-grow-1">
                                            <h5 class="mb-1">{{ $profile->profile_name }}</h5>
                                            <p class="text-danger mb-0 small user-count">
                                                <i class="ri-user-line me-1"></i>
                                                {{ $profile->sub_users_count }}
                                                {{ Str::plural('User', $profile->sub_users_count) }}
                                            </p>
                                        </div>
                                        <div class="ms-2 d-flex align-items-center">
                                            <span
                                                class="badge {{ $profile->status === 'active' ? 'bg-success' : 'bg-warning' }} me-2">
                                                {{ ucfirst($profile->status) }}
                                            </span>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-danger"
                                                onclick="deleteProfile(event, {{ $profile->id }}, '{{ $profile->profile_name }}')"
                                                title="Delete Profile">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @if ($profile->description)
                                        <p class="text-muted small mt-2 mb-0">
                                            {{ Str::limit($profile->description, 100) }}
                                        </p>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="ri-folder-user-line h1 text-muted"></i>
                                    <p class="mt-2">No profiles found</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Content - Profile Details -->
            <div class="col-xl-9 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-bordered mb-3">
                            <li class="nav-item">
                                <a
                                    href="#profile-details"
                                    data-bs-toggle="tab"
                                    aria-expanded="true"
                                    class="nav-link active">
                                    <i class="ri-user-settings-line d-md-none d-block"></i>
                                    <span class="d-none d-md-block">Profile Details</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#menu-access" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                    <i class="ri-menu-line d-md-none d-block"></i>
                                    <span class="d-none d-md-block">Menu Access</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#assigned-users" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                    <i class="ri-team-line d-md-none d-block"></i>
                                    <span class="d-none d-md-block">Assigned Users</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Profile Details Tab -->
                            <div class="tab-pane show active" id="profile-details">
                                <form id="updateProfileForm" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <select
                                                    class="form-select"
                                                    id="profileSelect"
                                                    name="profile_id"
                                                    required>
                                                    <option value="">Select Profile</option>
                                                    @foreach ($profiles as $profile)
                                                        <option
                                                            value="{{ $profile->id }}"
                                                            data-description="{{ $profile->description }}"
                                                            data-status="{{ $profile->status }}">
                                                            {{ $profile->profile_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <label for="profileSelect">Select Profile</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <select class="form-select" id="editStatus" name="status" required>
                                                    <option value="">Select Status</option>
                                                    <option value="active">Active</option>
                                                    <option value="inactive">Inactive</option>
                                                </select>
                                                <label for="editStatus">Status</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <textarea
                                            class="form-control"
                                            id="editDescription"
                                            name="description"
                                            placeholder=" "
                                            rows="3"
                                            style="height: 100px"></textarea>
                                        <label for="editDescription">Description</label>
                                    </div>

                                    <div class="text-end mt-3">
                                        <button type="button" id="resetForm" class="btn btn-light me-2">Cancel</button>
                                        <button type="submit" class="btn btn-success">Update Profile</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Menu Access Tab -->
                            <div class="tab-pane" id="menu-access">
                                <form id="menuAccessForm" method="POST">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <select
                                                    class="form-select"
                                                    id="menuProfileSelect"
                                                    name="profile_id"
                                                    required>
                                                    <option value="">Select Profile</option>
                                                    @foreach ($profiles as $profile)
                                                        <option value="{{ $profile->id }}">
                                                            {{ $profile->profile_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <label for="menuProfileSelect">Select Profile</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <select class="form-select" id="copyFromProfile">
                                                    <option value="">Select a profile to copy from...</option>
                                                    @foreach ($profiles as $profile)
                                                        <option value="{{ $profile->id }}">
                                                            {{ $profile->profile_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <label for="copyFromProfile">Copy Access From Profile</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="menu-list mt-4">
                                        <!-- Navigation -->
                                        <h5 class="mb-3">Navigation</h5>

                                        <!-- Dashboard -->
                                        <div class="menu-section mb-4">
                                            <div class="menu-item">
                                                <div class="form-check">
                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input menu-single"
                                                        id="menu_dashboard"
                                                        data-key="dashboard"
                                                        data-name="Dashboard"
                                                        data-icon="ri-home-4-line"
                                                        data-route="{{ route('any', 'company/index') }}" />
                                                    <label class="form-check-label" for="menu_dashboard">
                                                        <i class="ri-home-4-line me-1"></i>
                                                        Dashboard
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Administration -->
                                        <div class="menu-section mb-4">
                                            <div class="menu-item">
                                                <div class="form-check">
                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input menu-parent"
                                                        id="menu_administration"
                                                        data-key="administration"
                                                        data-name="Administration"
                                                        data-icon="ri-admin-line" />
                                                    <label class="form-check-label" for="menu_administration">
                                                        <i class="ri-admin-line me-1"></i>
                                                        Administration
                                                    </label>
                                                </div>
                                                <div class="ms-4 mt-2 submenu">
                                                    <!-- User Management Submenu -->
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-parent"
                                                            id="menu_user_management"
                                                            data-key="user_management"
                                                            data-name="User Management"
                                                            data-parent="administration"
                                                            data-icon="ri-user-settings-line" />
                                                        <label class="form-check-label" for="menu_user_management">
                                                            <i class="ri-user-settings-line me-1"></i>
                                                            User Management
                                                        </label>
                                                    </div>
                                                    <div class="ms-4 mt-2 submenu">
                                                        <div class="form-check">
                                                            <input
                                                                type="checkbox"
                                                                class="form-check-input menu-child"
                                                                id="menu_users"
                                                                data-key="users"
                                                                data-name="Users"
                                                                data-parent="user_management"
                                                                data-route="{{ route('company-sub-users.index') }}" />
                                                            <label class="form-check-label" for="menu_users">Users</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input
                                                                type="checkbox"
                                                                class="form-check-input menu-child"
                                                                id="menu_user_profiles"
                                                                data-key="user_profiles"
                                                                data-name="User Profiles"
                                                                data-parent="user_management"
                                                                data-route="{{ route('company.user-profiles.index') }}" />
                                                            <label class="form-check-label" for="menu_user_profiles">
                                                                User Profiles
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input
                                                                type="checkbox"
                                                                class="form-check-input menu-child"
                                                                id="menu_partners"
                                                                data-key="partners"
                                                                data-name="Partners"
                                                                data-parent="user_management"
                                                                data-route="{{ route('company.partners.index') }}" />
                                                            <label class="form-check-label" for="menu_partners">
                                                                Partners
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input
                                                                type="checkbox"
                                                                class="form-check-input menu-child"
                                                                id="menu_user_category"
                                                                data-key="user_category"
                                                                data-name="User Category"
                                                                data-parent="user_management"
                                                                data-route="{{ route('company-categories.index') }}" />
                                                            <label class="form-check-label" for="menu_user_category">
                                                                User Category
                                                            </label>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Email Management Submenu -->
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-parent"
                                                            id="menu_email_management"
                                                            data-key="email_management"
                                                            data-name="Email Management"
                                                            data-parent="administration"
                                                            data-icon="ri-mail-settings-line" />
                                                        <label class="form-check-label" for="menu_email_management">
                                                            <i class="ri-mail-settings-line me-1"></i>
                                                            Email Management
                                                        </label>
                                                    </div>
                                                    <div class="ms-4 mt-2 submenu">
                                                        <div class="form-check">
                                                            <input
                                                                type="checkbox"
                                                                class="form-check-input menu-child"
                                                                id="menu_email_templates"
                                                                data-key="email_templates"
                                                                data-name="Email Templates"
                                                                data-parent="email_management"
                                                                data-route="{{ route('any', 'company/Administration/email-templates') }}" />
                                                            <label class="form-check-label" for="menu_email_templates">
                                                                Email Templates
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input
                                                                type="checkbox"
                                                                class="form-check-input menu-child"
                                                                id="menu_mailing_lists"
                                                                data-key="mailing_lists"
                                                                data-name="Mailing Lists"
                                                                data-parent="email_management"
                                                                data-route="{{ route('any', 'company/Administration/mailing-lists') }}" />
                                                            <label class="form-check-label" for="menu_mailing_lists">
                                                                Mailing Lists
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input
                                                                type="checkbox"
                                                                class="form-check-input menu-child"
                                                                id="menu_email_campaigns"
                                                                data-key="email_campaigns"
                                                                data-name="Email Campaigns"
                                                                data-parent="email_management"
                                                                data-route="{{ route('any', 'company/Administration/email-campaigns') }}" />
                                                            <label class="form-check-label" for="menu_email_campaigns">
                                                                Email Campaigns
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input
                                                                type="checkbox"
                                                                class="form-check-input menu-child"
                                                                id="menu_email_analytics"
                                                                data-key="email_analytics"
                                                                data-name="Email Analytics"
                                                                data-parent="email_management"
                                                                data-route="{{ route('any', 'company/Administration/email-analytics') }}" />
                                                            <label class="form-check-label" for="menu_email_analytics">
                                                                Email Analytics
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{--
                                        <!-- Category Management -->
                                        <div class="menu-section mb-4">
                                            <div class="menu-item">
                                                <div class="form-check">
                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input menu-parent"
                                                        id="menu_category_management"
                                                        data-key="category_management"
                                                        data-name="Category Management"
                                                        data-icon="ri-folder-3-line" />
                                                    <label class="form-check-label" for="menu_category_management">
                                                        <i class="ri-folder-3-line me-1"></i>
                                                        Category Management
                                                    </label>
                                                </div>
                                                <div class="ms-4 mt-2 submenu">
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_manage_categories"
                                                            data-key="manage_categories"
                                                            data-name="Manage Categories"
                                                            data-parent="category_management"
                                                            data-route="{{ route('categories.index') }}" />
                                                        <label class="form-check-label" for="menu_manage_categories">
                                                            Manage Categories
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        --}}

                                        <!-- Fuel Sale Management -->
                                        <div class="menu-section mb-4">
                                            <div class="menu-item">
                                                <div class="form-check">
                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input menu-parent"
                                                        id="menu_fuel_management"
                                                        data-key="fuel_management"
                                                        data-name="Fuel Sale Management"
                                                        data-icon="ri-gas-station-line" />
                                                    <label class="form-check-label" for="menu_fuel_management">
                                                        <i class="ri-gas-station-line me-1"></i>
                                                        Fuel Sale Management
                                                    </label>
                                                </div>
                                                <div class="ms-4 mt-2 submenu">
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_station_management"
                                                            data-key="station_management"
                                                            data-name="Stations"
                                                            data-parent="fuel_management"
                                                            data-route="{{ route('any', 'company/FuelManagement/allstations') }}" />
                                                        <label class="form-check-label" for="menu_station_management">
                                                            Stations
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_sales_management"
                                                            data-key="sales_management"
                                                            data-name="Sales"
                                                            data-parent="fuel_management"
                                                            data-route="{{ route('any', 'company/FuelManagement/sales') }}" />
                                                        <label class="form-check-label" for="menu_sales_management">
                                                            Sales
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_station_managers"
                                                            data-key="station_managers"
                                                            data-name="Station Manager"
                                                            data-parent="fuel_management"
                                                            data-route="{{ route('any', 'company/FuelManagement/stationmanager') }}" />
                                                        <label class="form-check-label" for="menu_station_managers">
                                                            Station Manager
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Stock Activity -->
                                        <div class="menu-section mb-4">
                                            <div class="menu-item">
                                                <div class="form-check">
                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input menu-parent"
                                                        id="menu_stock_activity"
                                                        data-key="stock_activity"
                                                        data-name="Stock Activity"
                                                        data-icon="ri-stack-line" />
                                                    <label class="form-check-label" for="menu_stock_activity">
                                                        <i class="ri-stack-line me-1"></i>
                                                        Stock Activity
                                                    </label>
                                                </div>
                                                <div class="ms-4 mt-2 submenu">
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_stock_received"
                                                            data-key="stock_received"
                                                            data-name="Stock Received"
                                                            data-parent="stock_activity"
                                                            data-route="{{ route('any', 'company/FuelManagement/stock') }}" />
                                                        <label class="form-check-label" for="menu_stock_received">
                                                            Stock Received
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_stock_dispatched"
                                                            data-key="stock_dispatched"
                                                            data-name="Stock Dispatched"
                                                            data-parent="stock_activity"
                                                            data-route="{{ route('any', 'company/FuelManagement/DispatchStock') }}" />
                                                        <label class="form-check-label" for="menu_stock_dispatched">
                                                            Stock Dispatched
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_stock_recon"
                                                            data-key="stock_recon"
                                                            data-name="Stock Reconciliation"
                                                            data-parent="stock_activity"
                                                            data-route="{{ route('any', 'company/FuelManagement/stockRecon') }}" />
                                                        <label class="form-check-label" for="menu_stock_recon">
                                                            Stock Reconciliation
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Accounts & Deposits -->
                                        <div class="menu-section mb-4">
                                            <div class="menu-item">
                                                <div class="form-check">
                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input menu-parent"
                                                        id="menu_accounts_deposit"
                                                        data-key="accounts_deposit"
                                                        data-name="Accounts & Deposits"
                                                        data-icon="ri-bank-card-line" />
                                                    <label class="form-check-label" for="menu_accounts_deposit">
                                                        <i class="ri-bank-card-line me-1"></i>
                                                        Accounts & Deposits
                                                    </label>
                                                </div>
                                                <div class="ms-4 mt-2 submenu">
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_bank_deposit"
                                                            data-key="bank_deposit"
                                                            data-name="Bank Deposit"
                                                            data-parent="accounts_deposit"
                                                            data-route="{{ route('any', 'company/FuelManagement/bankdeposit') }}" />
                                                        <label class="form-check-label" for="menu_bank_deposit">
                                                            Bank Deposit
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_all_account"
                                                            data-key="all_account"
                                                            data-name="All Accounts"
                                                            data-parent="accounts_deposit"
                                                            data-route="{{ route('any', 'company/FuelManagement/allaccount') }}" />
                                                        <label class="form-check-label" for="menu_all_account">
                                                            All Accounts
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        {{--
                                        <!-- Master Tracker -->
                                        <div class="menu-section mb-4">
                                            <div class="menu-item">
                                                <div class="form-check">
                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input menu-parent"
                                                        id="menu_master_tracker"
                                                        data-key="master_tracker"
                                                        data-name="Master Tracker"
                                                        data-icon="ri-dashboard-3-line" />
                                                    <label class="form-check-label" for="menu_master_tracker">
                                                        <i class="ri-dashboard-3-line me-1"></i>
                                                        Master Tracker
                                                    </label>
                                                </div>
                                                <div class="ms-4 mt-2 submenu">
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_workforce_fleet"
                                                            data-key="workforce_fleet"
                                                            data-name="Workforce & Fleet"
                                                            data-parent="master_tracker"
                                                            data-route="{{ route('workforce-fleet') }}" />
                                                        <label class="form-check-label" for="menu_workforce_fleet">
                                                            Workforce & Fleet
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_team_pairing"
                                                            data-key="team_pairing"
                                                            data-name="Team Pairing"
                                                            data-parent="master_tracker"
                                                            data-route="{{ route('any', 'company/MasterTracker/team-pairing') }}" />
                                                        <label class="form-check-label" for="menu_team_pairing">
                                                            Team Pairing
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_team_roaster"
                                                            data-key="team_roaster"
                                                            data-name="Team Roaster"
                                                            data-parent="master_tracker"
                                                            data-route="{{ route('any', 'company/MasterTracker/team-roaster') }}" />
                                                        <label class="form-check-label" for="menu_team_roaster">
                                                            Team Roaster
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_gesl_tracker"
                                                            data-key="gesl_tracker"
                                                            data-name="GESL Tracker"
                                                            data-parent="master_tracker"
                                                            data-route="{{ route('any', 'company/MasterTracker/gesl-tracker') }}" />
                                                        <label class="form-check-label" for="menu_gesl_tracker">
                                                            GESL Tracker
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_linfra_tracker"
                                                            data-key="linfra_tracker"
                                                            data-name="Linfra Tracker"
                                                            data-parent="master_tracker"
                                                            data-route="{{ route('any', 'company/MasterTracker/linfra-tracker') }}" />
                                                        <label class="form-check-label" for="menu_linfra_tracker">
                                                            Linfra Tracker
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_kpi_report"
                                                            data-key="kpi_report"
                                                            data-name="KPI Report"
                                                            data-parent="master_tracker"
                                                            data-route="{{ route('any', 'company/MasterTracker/kpi-report') }}" />
                                                        <label class="form-check-label" for="menu_kpi_report">
                                                            KPI Report
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_material_balance"
                                                            data-key="material_balance"
                                                            data-name="Material Balance"
                                                            data-parent="master_tracker"
                                                            data-route="{{ route('any', 'company/MasterTracker/material-balance') }}" />
                                                        <label class="form-check-label" for="menu_material_balance">
                                                            Material Balance
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_ont_restock_tracker"
                                                            data-key="ont_restock_tracker"
                                                            data-name="ONT Restock Tracker"
                                                            data-parent="master_tracker"
                                                            data-route="{{ route('any', 'company/MasterTracker/ont-restock-tracker') }}" />
                                                        <label class="form-check-label" for="menu_ont_restock_tracker">
                                                            ONT Restock Tracker
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_sbc_list_scoring"
                                                            data-key="sbc_list_scoring"
                                                            data-name="SBC List & Scoring"
                                                            data-parent="master_tracker"
                                                            data-route="{{ route('any', 'company/MasterTracker/sbc-list-scoring') }}" />
                                                        <label class="form-check-label" for="menu_sbc_list_scoring">
                                                            SBC List & Scoring
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_aging_dashboard"
                                                            data-key="aging_dashboard"
                                                            data-name="Aging Dashboard"
                                                            data-parent="master_tracker"
                                                            data-route="{{ route('any', 'company/MasterTracker/aging-dashboard') }}" />
                                                        <label class="form-check-label" for="menu_aging_dashboard">
                                                            Aging Dashboard
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_master_tracker_report"
                                                            data-key="MasterTracker_Report"
                                                            data-name="Master Tracker Report"
                                                            data-parent="master_tracker"
                                                            data-route="{{ route('any', 'company/MasterTracker/master-report') }}" />
                                                        <label class="form-check-label" for="menu_master_tracker_report">
                                                            Master Tracker Report
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        --}}

                                        {{--
                                        <!-- Human Resources -->
                                        <div class="menu-section mb-4">
                                            <div class="menu-item">
                                                <div class="form-check">
                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input menu-parent"
                                                        id="menu_human_resources"
                                                        data-key="human_resources"
                                                        data-name="Human Resources"
                                                        data-icon="ri-group-2-line" />
                                                    <label class="form-check-label" for="menu_human_resources">
                                                        <i class="ri-group-2-line me-1"></i>
                                                        Human Resources
                                                    </label>
                                                </div>
                                                <div class="ms-4 mt-2 submenu">
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_hr_desk"
                                                            data-key="hr_desk"
                                                            data-name="HR Desk"
                                                            data-parent="human_resources"
                                                            data-route="{{ route('any', 'company/HumanResource/hr') }}" />
                                                        <label class="form-check-label" for="menu_hr_desk">
                                                            HR Desk
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_staff_desk"
                                                            data-key="staff_desk"
                                                            data-name="Staff Desk"
                                                            data-parent="human_resources"
                                                            data-route="{{ route('any', 'company/HumanResource/staff') }}" />
                                                        <label class="form-check-label" for="menu_staff_desk">
                                                            Staff Desk
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        --}}

                                        {{--
                                        <!-- CRM -->
                                        <div class="menu-section mb-4">
                                            <div class="menu-item">
                                                <div class="form-check">
                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input menu-parent"
                                                        id="menu_crm"
                                                        data-key="crm"
                                                        data-name="CRM"
                                                        data-icon="ri-customer-service-2-line" />
                                                    <label class="form-check-label" for="menu_crm">
                                                        <i class="ri-customer-service-2-line me-1"></i>
                                                        CRM
                                                    </label>
                                                </div>
                                                <div class="ms-4 mt-2 submenu">
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_crm_dashboard"
                                                            data-key="crm_dashboard"
                                                            data-name="CRM Dashboard"
                                                            data-parent="crm"
                                                            data-route="{{ route('any', 'company/CRM/crmdash') }}" />
                                                        <label class="form-check-label" for="menu_crm_dashboard">
                                                            Dashboard
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_crm_main"
                                                            data-key="crm_main"
                                                            data-name="Customer Management"
                                                            data-parent="crm"
                                                            data-route="{{ route('any', 'company/CRM/crm') }}" />
                                                        <label class="form-check-label" for="menu_crm_main">
                                                            Customer Management
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_contract"
                                                            data-key="contract"
                                                            data-name="Contract"
                                                            data-parent="crm"
                                                            data-route="{{ route('any', 'company/CRM/contract') }}" />
                                                        <label class="form-check-label" for="menu_contract">
                                                            Contract
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        --}}

                                        {{--
                                        <!-- Management -->
                                        <div class="menu-section mb-4">
                                            <div class="menu-item">
                                                <div class="form-check">
                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input menu-parent"
                                                        id="menu_management"
                                                        data-key="management"
                                                        data-name="Management"
                                                        data-icon="ri-settings-3-line" />
                                                    <label class="form-check-label" for="menu_management">
                                                        <i class="ri-settings-3-line me-1"></i>
                                                        Management
                                                    </label>
                                                </div>
                                                <div class="ms-4 mt-2 submenu">
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_purchase_order_management"
                                                            data-key="purchase_order_management"
                                                            data-name="Purchase Order Approval"
                                                            data-parent="management"
                                                            data-route="{{ route('management.purchase-order-approval') }}" />
                                                        <label class="form-check-label" for="menu_purchase_order_management">
                                                            Purchase Order Approval
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_requisition_approval"
                                                            data-key="requisition_approval"
                                                            data-name="Requisition Approval"
                                                            data-parent="management"
                                                            data-route="{{ route('management.requisition-approval') }}" />
                                                        <label class="form-check-label" for="menu_requisition_approval">
                                                            Requisition Approval
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        --}}

                                        {{--
                                        <!-- Warehouse Management -->
                                        <div class="menu-section mb-4">
                                            <div class="menu-item">
                                                <div class="form-check">
                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input menu-parent"
                                                        id="menu_warehouse_management"
                                                        data-key="warehouse_management"
                                                        data-name="Warehouse Management"
                                                        data-icon="ri-store-2-line" />
                                                    <label class="form-check-label" for="menu_warehouse_management">
                                                        <i class="ri-store-2-line me-1"></i>
                                                        Warehouse Management
                                                    </label>
                                                </div>
                                                <div class="ms-4 mt-2 submenu">
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_requisition"
                                                            data-key="requisition"
                                                            data-name="Requisition"
                                                            data-parent="warehouse_management"
                                                            data-route="{{ route('any', 'company/InventoryManagement/requisition') }}" />
                                                        <label class="form-check-label" for="menu_requisition">
                                                            Requisition
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_procurement"
                                                            data-key="procurement"
                                                            data-name="Procurement"
                                                            data-parent="warehouse_management"
                                                            data-route="{{ route('any', 'company/InventoryManagement/procurement') }}" />
                                                        <label class="form-check-label" for="menu_procurement">
                                                            Procurement
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_warehouse"
                                                            data-key="warehouse"
                                                            data-name="Warehouse"
                                                            data-parent="warehouse_management"
                                                            data-route="{{ route('any', 'company/InventoryManagement/WarehouseOperations') }}" />
                                                        <label class="form-check-label" for="menu_warehouse">
                                                            Warehouse
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_warehouse_report"
                                                            data-key="warehouse_report"
                                                            data-name="Report"
                                                            data-parent="warehouse_management"
                                                            data-route="{{ route('any', 'company/InventoryManagement/inventory-dash') }}" />
                                                        <label class="form-check-label" for="menu_warehouse_report">
                                                            Report
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        --}}

                                        {{--
                                        <!-- Project Management -->
                                        <div class="menu-section mb-4">
                                            <div class="menu-item">
                                                <div class="form-check">
                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input menu-parent"
                                                        id="menu_project_management"
                                                        data-key="project_management"
                                                        data-name="Project Management"
                                                        data-icon="ri-task-line" />
                                                    <label class="form-check-label" for="menu_project_management">
                                                        <i class="ri-task-line me-1"></i>
                                                        Project Management
                                                    </label>
                                                </div>
                                                <div class="ms-4 mt-2 submenu">
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_gpon"
                                                            data-key="gpon"
                                                            data-name="GPON"
                                                            data-parent="project_management"
                                                            data-route="{{ route('any', 'company/ProjectManagement/pm') }}" />
                                                        <label class="form-check-label" for="menu_gpon">
                                                            GPON
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_home_connection"
                                                            data-key="home_connection"
                                                            data-name="Home Connection"
                                                            data-parent="project_management"
                                                            data-route="{{ route('any', 'company/ProjectManagement/homeConnection') }}" />
                                                        <label class="form-check-label" for="menu_home_connection">
                                                            Home Connection
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_field_update"
                                                            data-key="field_update"
                                                            data-name="Field Update"
                                                            data-parent="project_management"
                                                            data-route="{{ route('any', 'company/ProjectManagement/field-update') }}" />
                                                        <label class="form-check-label" for="menu_field_update">
                                                            Field Update
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_quality_audit"
                                                            data-key="quality_audit"
                                                            data-name="Quality Audit"
                                                            data-parent="project_management"
                                                            data-route="{{ route('any', 'company/ProjectManagement/qualityAudit') }}" />
                                                        <label class="form-check-label" for="menu_quality_audit">
                                                            Quality Audit
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input menu-child"
                                                            id="menu_general_service"
                                                            data-key="general_service"
                                                            data-name="General Service"
                                                            data-parent="project_management"
                                                            data-route="{{ route('any', 'company/ProjectManagement/generalService') }}" />
                                                        <label class="form-check-label" for="menu_general_service">
                                                            General Service
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        --}}

                                    </div>

                                    <div class="text-end mt-3">
                                        <button type="button" id="resetMenuAccess" class="btn btn-light me-2">
                                            Reset
                                        </button>
                                        <button type="submit" class="btn btn-success">Save Menu Access</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Assigned Users Tab -->
                            <div class="tab-pane" id="assigned-users">
                                <div class="row mb-3">
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="searchUsers"
                                                placeholder="Search users..." />
                                            <label for="searchUsers">Search Users</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-centered table-hover" id="subUsersTable">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Assigned Profile</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($subUsers as $user)
                                                <tr>
                                                    <td>{{ $user->fullname }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        <div class="form-floating">
                                                            <select
                                                                class="form-select profile-select"
                                                                id="profile_{{ $user->id }}"
                                                                data-user-id="{{ $user->id }}">
                                                                <option value="">Select Profile</option>
                                                                @foreach ($profiles as $profile)
                                                                    <option
                                                                        value="{{ $profile->id }}"
                                                                        {{ $user->profile_id == $profile->id ? 'selected' : '' }}>
                                                                        {{ $profile->profile_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <label for="profile_{{ $user->id }}">Assign Profile</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button
                                                                type="button"
                                                                class="btn btn-sm btn-info view-access"
                                                                data-user-id="{{ $user->id }}"
                                                                data-user-name="{{ $user->fullname }}"
                                                                title="View Access">
                                                                <i class="ri-eye-line"></i>
                                                            </button>
                                                            @if($user->profile_id)
                                                                <button
                                                                    type="button"
                                                                    class="btn btn-sm btn-warning reset-profile"
                                                                    data-user-id="{{ $user->id }}"
                                                                    data-user-name="{{ $user->fullname }}"
                                                                    title="Remove Profile Assignment">
                                                                    <i class="ri-user-unfollow-line"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Profile Modal -->
    <div class="modal fade" id="addProfileModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="min-height: 350px">
                    <form
                        id="addProfileForm"
                        class="mt-3"
                        method="POST"
                        action="{{ route('company.user-profiles.store') }}">
                        @csrf
                        <div class="form-floating mb-4">
                            <input
                                type="text"
                                class="form-control"
                                id="profileName"
                                name="profile_name"
                                placeholder=" "
                                required />
                            <label for="profileName">Profile Name</label>
                        </div>
                        <div class="form-floating mb-4">
                            <textarea
                                class="form-control"
                                id="description"
                                name="description"
                                placeholder=" "
                                rows="3"
                                style="height: 100px"></textarea>
                            <label for="description">Description</label>
                        </div>
                        <div class="form-floating">
                            <select class="form-select" id="role" name="status" required>
                                <option value="">Select Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="locked">Lock</option>
                            </select>
                            <label for="role">Status</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="addProfileForm" class="btn btn-primary">Create Profile</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Remove any existing event listeners
        document.removeEventListener('DOMContentLoaded', initializeProfileForm);

        function initializeProfileForm() {
            const addProfileForm = document.getElementById('addProfileForm');

            if (addProfileForm) {
                // Remove any existing submit handlers
                const newForm = addProfileForm.cloneNode(true);
                addProfileForm.parentNode.replaceChild(newForm, addProfileForm);

                newForm.addEventListener('submit', async function (e) {
                    e.preventDefault();

                    // Disable the submit button to prevent double submission
                    const submitButton = document.querySelector('button[type="submit"]');
                    if (submitButton) submitButton.disabled = true;

                    try {
                        const response = await fetch(this.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                Accept: 'application/json',
                            },
                            body: new FormData(this),
                        });

                        const data = await response.json();
                        console.log('Response:', { status: response.status, data }); // Debug log

                        if (response.status === 201 || response.status === 200) {
                            // Success case
                            const modal = bootstrap.Modal.getInstance(document.getElementById('addProfileModal'));
                            if (modal) modal.hide();

                            await Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 1500,
                            });

                            window.location.reload();
                            return;
                        }

                        if (response.status === 422) {
                            // Validation error case
                            const errors = data.errors;
                            const errorMessages = Object.values(errors).flat();

                            await Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                html: errorMessages.join('<br>'),
                            });
                            return;
                        }

                        throw new Error(data.message || 'An error occurred');
                    } catch (error) {
                        console.error('Error:', error);
                        await Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: error.message || 'An error occurred while creating the profile',
                        });
                    } finally {
                        // Re-enable the submit button
                        if (submitButton) submitButton.disabled = false;
                    }
                });

                // Reset form when modal is hidden
                const addProfileModal = document.getElementById('addProfileModal');
                if (addProfileModal) {
                    addProfileModal.addEventListener('hidden.bs.modal', function () {
                        newForm.reset();
                    });
                }
            }
        }

        // Add the event listener
        document.addEventListener('DOMContentLoaded', initializeProfileForm);
    </script>

    <script>
        // Profile update functionality
        document.addEventListener('DOMContentLoaded', function () {
            const profileSelect = document.getElementById('profileSelect');
            const editStatus = document.getElementById('editStatus');
            const editDescription = document.getElementById('editDescription');
            const updateProfileForm = document.getElementById('updateProfileForm');
            const resetFormButton = document.getElementById('resetForm');

            // Handle profile selection
            profileSelect.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value) {
                    editStatus.value = selectedOption.dataset.status;
                    editDescription.value = selectedOption.dataset.description || '';
                    updateProfileForm.action = `${window.location.origin}/company/user-profiles/${selectedOption.value}`;
                } else {
                    resetForm();
                }
            });

            // Handle form reset
            resetFormButton.addEventListener('click', resetForm);

            function resetForm() {
                profileSelect.value = '';
                editStatus.value = '';
                editDescription.value = '';
                updateProfileForm.action = '';
            }

            // Handle form submission
            updateProfileForm.addEventListener('submit', function (e) {
                e.preventDefault();

                if (!this.action) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Please select a profile to update.',
                    });
                    return;
                }

                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        Accept: 'application/json',
                    },
                    body: formData,
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 1500,
                            }).then(() => {
                                // Update the profile card in the list
                                const profileCard = document.querySelector(
                                    `[data-profile-id="${profileSelect.value}"]`
                                );
                                if (profileCard) {
                                    const statusBadge = profileCard.querySelector('.badge');
                                    statusBadge.className = `badge ${editStatus.value === 'active' ? 'bg-success' : 'bg-warning'} me-2`;
                                    statusBadge.textContent =
                                        editStatus.value.charAt(0).toUpperCase() + editStatus.value.slice(1);

                                    // Update description if it exists
                                    const description = profileCard.querySelector('p.text-muted.small.mt-2');
                                    if (description) {
                                        description.textContent = editDescription.value;
                                    } else if (editDescription.value) {
                                        profileCard.insertAdjacentHTML(
                                            'beforeend',
                                            `<p class="text-muted small mt-2 mb-0">${editDescription.value}</p>`
                                        );
                                    }
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message,
                            });
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'An error occurred while updating the profile.',
                        });
                    });
            });
        });
    </script>

    <script>
        // Menu Access functionality
        document.addEventListener('DOMContentLoaded', function () {
            const menuProfileSelect = document.getElementById('menuProfileSelect');
            const copyFromProfile = document.getElementById('copyFromProfile');
            const menuAccessForm = document.getElementById('menuAccessForm');
            const resetMenuAccess = document.getElementById('resetMenuAccess');

            // Handle parent menu checkboxes
            document.querySelectorAll('.menu-parent').forEach((parent) => {
                parent.addEventListener('change', function () {
                    const submenu = this.closest('.menu-item').querySelector('.submenu');
                    if (submenu) {
                        submenu.querySelectorAll('.menu-child').forEach((child) => {
                            child.checked = this.checked;
                        });
                    }
                });
            });

            // Handle child menu checkboxes
            document.querySelectorAll('.menu-child').forEach((child) => {
                child.addEventListener('change', function () {
                    const parent = this.closest('.menu-item').querySelector('.menu-parent');
                    if (parent) {
                        const siblings = this.closest('.submenu').querySelectorAll('.menu-child');
                        const allUnchecked = Array.from(siblings).every((sibling) => !sibling.checked);
                        parent.checked = !allUnchecked;
                    }
                });
            });

            // Handle profile selection
            menuProfileSelect.addEventListener('change', function () {
                if (this.value) {
                    // Fetch menu access for selected profile
                    fetch(`${window.location.origin}/company/user-profiles/${this.value}/menu-access`)
                        .then((response) => response.json())
                        .then((data) => {
                            if (data.success) {
                                resetCheckboxes();
                                data.menu_access.forEach((menu) => {
                                    const checkbox = document.querySelector(`[data-key="${menu.menu_key}"]`);
                                    if (checkbox) {
                                        checkbox.checked = menu.is_active;
                                        // If it's a child menu, check parent status
                                        if (checkbox.classList.contains('menu-child')) {
                                            updateParentStatus(checkbox);
                                        }
                                    }
                                });
                            }
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to load menu access.',
                            });
                        });

                    menuAccessForm.action = `${window.location.origin}/company/user-profiles/${this.value}/menu-access`;
                } else {
                    resetCheckboxes();
                    menuAccessForm.action = '';
                }
            });

            // Handle copy from profile
            copyFromProfile.addEventListener('change', function () {
                if (this.value && this.value !== menuProfileSelect.value) {
                    fetch(`${window.location.origin}/company/user-profiles/${this.value}/menu-access`)
                        .then((response) => response.json())
                        .then((data) => {
                            if (data.success) {
                                resetCheckboxes();
                                data.menu_access.forEach((menu) => {
                                    const checkbox = document.querySelector(`[data-key="${menu.menu_key}"]`);
                                    if (checkbox) {
                                        checkbox.checked = menu.is_active;
                                        if (checkbox.classList.contains('menu-child')) {
                                            updateParentStatus(checkbox);
                                        }
                                    }
                                });
                            }
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to copy menu access.',
                            });
                        });
                }
            });

            // Handle form submission
            menuAccessForm.addEventListener('submit', function (e) {
                e.preventDefault();

                if (!this.action) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Please select a profile first.',
                    });
                    return;
                }

                // Collect all checked menu items
                const menuAccess = [];

                // Add parent menus
                document.querySelectorAll('.menu-parent').forEach((parent) => {
                    menuAccess.push({
                        menu_key: parent.dataset.key,
                        menu_name: parent.dataset.name,
                        menu_icon: parent.dataset.icon,
                        is_active: parent.checked,
                    });
                });

                // Add child menus
                document.querySelectorAll('.menu-child').forEach((child) => {
                    menuAccess.push({
                        menu_key: child.dataset.key,
                        menu_name: child.dataset.name,
                        menu_route: child.dataset.route,
                        parent_menu: child.dataset.parent,
                        is_active: child.checked,
                    });
                });

                // Add single menus
                document.querySelectorAll('.menu-single').forEach((single) => {
                    menuAccess.push({
                        menu_key: single.dataset.key,
                        menu_name: single.dataset.name,
                        menu_icon: single.dataset.icon,
                        menu_route: single.dataset.route,
                        is_active: single.checked,
                    });
                });

                // Send the data
                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                    },
                    body: JSON.stringify({ menu_access: menuAccess }),
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 1500,
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message,
                            });
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to save menu access.',
                        });
                    });
            });

            // Handle reset
            resetMenuAccess.addEventListener('click', function () {
                resetCheckboxes();
                menuProfileSelect.value = '';
                copyFromProfile.value = '';
                menuAccessForm.action = '';
            });

            function resetCheckboxes() {
                document.querySelectorAll('.menu-parent, .menu-child, .menu-single').forEach((checkbox) => {
                    checkbox.checked = false;
                });
            }

            function updateParentStatus(childCheckbox) {
                const parent = childCheckbox.closest('.menu-item').querySelector('.menu-parent');
                if (parent) {
                    const siblings = childCheckbox.closest('.submenu').querySelectorAll('.menu-child');
                    const allUnchecked = Array.from(siblings).every((sibling) => !sibling.checked);
                    parent.checked = !allUnchecked;
                }
            }
        });
    </script>

    <script>
        $(document).ready(function () {
            // Handle profile selection change
            $('.profile-select').change(function () {
                const userId = $(this).data('user-id');
                const profileId = $(this).val();
                const userName = $(this).closest('tr').find('td:first').text();

                if (profileId) {
                    Swal.fire({
                        title: 'Confirm Profile Assignment',
                        text: `Are you sure you want to assign this profile to ${userName}?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, assign it',
                        cancelButtonText: 'No, cancel',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading state
                            Swal.fire({
                                title: 'Assigning Profile',
                                text: 'Please wait...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                },
                            });

                            // Make the AJAX request
                            $.ajax({
                                url: '{{ route('company.user-profiles.assign-profile', ['userId' => ':userId']) }}'.replace(
                                    ':userId',
                                    userId
                                ),
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                },
                                data: {
                                    profile_id: profileId,
                                },
                                success: function (response) {
                                    if (response.success) {
                                        // Update profile counts
                                        if (response.profiles) {
                                            response.profiles.forEach(function(profile) {
                                                const profileCard = $(`.profile-card[data-profile-id="${profile.id}"]`);
                                                if (profileCard.length) {
                                                    const countElement = profileCard.find('.user-count');
                                                    countElement.text(profile.sub_users_count + ' ' + (profile.sub_users_count === 1 ? 'User' : 'Users'));
                                                }
                                            });
                                        }
                                        
                                        // Show the reset button for this user
                                        $(`.reset-profile[data-user-id="${userId}"]`).show();
                                        
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success!',
                                            text: response.message,
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error!',
                                            text: response.message || 'Failed to assign profile.',
                                        });
                                    }
                                },
                                error: function (xhr) {
                                    console.error('Error:', xhr);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: 'Failed to assign profile. Please try again.',
                                    });
                                },
                            });
                        } else {
                            // Reset the select to its previous value if user cancels
                            $(this).val($(this).find('option[selected]').val() || '');
                        }
                    });
                }
            });

            // Handle reset profile button click
            $('.reset-profile').click(function () {
                const userId = $(this).data('user-id');
                const userName = $(this).data('user-name');

                Swal.fire({
                    title: 'Remove Profile Assignment',
                    text: `Are you sure you want to remove the profile assignment from ${userName}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, remove it',
                    cancelButtonText: 'No, cancel',
                    confirmButtonColor: '#f39c12',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        Swal.fire({
                            title: 'Removing Profile Assignment',
                            text: 'Please wait...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });

                        // Make the AJAX request
                        $.ajax({
                            url: '{{ route('company.user-profiles.remove-profile', ['userId' => ':userId']) }}'.replace(
                                ':userId',
                                userId
                            ),
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            },
                            success: function (response) {
                                if (response.success) {
                                    // Update profile counts
                                    if (response.profiles) {
                                        response.profiles.forEach(function(profile) {
                                            const profileCard = $(`.profile-card[data-profile-id="${profile.id}"]`);
                                            if (profileCard.length) {
                                                const countElement = profileCard.find('.user-count');
                                                countElement.text(profile.sub_users_count + ' ' + (profile.sub_users_count === 1 ? 'User' : 'Users'));
                                            }
                                        });
                                    }
                                    
                                    // Reset the profile select dropdown
                                    const profileSelect = $(`#profile_${userId}`);
                                    profileSelect.val('');
                                    
                                    // Hide the reset button
                                    $(`.reset-profile[data-user-id="${userId}"]`).hide();
                                    
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: response.message,
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message || 'Failed to remove profile assignment.',
                                    });
                                }
                            },
                            error: function (xhr) {
                                console.error('Error:', xhr);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Failed to remove profile assignment. Please try again.',
                                });
                            },
                        });
                    }
                });
            });

            // Handle view access button click
            $('.view-access').click(function () {
                const userId = $(this).data('user-id');
                const userName = $(this).data('user-name');

                // Show loading state
                Swal.fire({
                    title: 'Loading Menu Access',
                    text: 'Please wait...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                });

                // Make the AJAX request
                $.ajax({
                    url: '{{ route('company.user-profiles.menu-access', ['userId' => ':userId']) }}'.replace(
                        ':userId',
                        userId
                    ),
                    method: 'GET',
                    success: function (response) {
                        if (response.success) {
                            // Format the menu access data for display
                            let menuAccessHtml = '<div class="menu-access-list">';
                            if (response.menuAccess && response.menuAccess.length > 0) {
                                // Group menu items by parent
                                const menuGroups = {};
                                const parentMenus = [];

                                // First pass: organize menus
                                response.menuAccess.forEach(function (menu) {
                                    if (!menu.parent_key) {
                                        parentMenus.push(menu);
                                    } else {
                                        if (!menuGroups[menu.parent_key]) {
                                            menuGroups[menu.parent_key] = [];
                                        }
                                        menuGroups[menu.parent_key].push(menu);
                                    }
                                });

                                // Second pass: display parent menus and their children
                                parentMenus.forEach(function (parentMenu) {
                                    menuAccessHtml += `
                                        <div class="menu-group mb-4">
                                            <div class="menu-parent">
                                                <h5>
                                                    <i class="${parentMenu.icon}"></i>
                                                    ${parentMenu.menu_name}
                                                </h5>
                                                <p class="text-muted">Access: ${parentMenu.access_type}</p>
                                            </div>
                                    `;

                                    // Add child menus if any
                                    const childMenus = menuGroups[parentMenu.menu_key] || [];
                                    if (childMenus.length > 0) {
                                        menuAccessHtml += '<div class="menu-children ps-4 mt-2">';
                                        childMenus.forEach(function (childMenu) {
                                            menuAccessHtml += `
                                                <div class="menu-item mb-2">
                                                    <h6>
                                                        <i class="${childMenu.icon}"></i>
                                                        ${childMenu.menu_name}
                                                    </h6>
                                                    <p class="text-muted mb-0">Access: ${childMenu.access_type}</p>
                                                </div>
                                            `;
                                        });
                                        menuAccessHtml += '</div>';
                                    }

                                    menuAccessHtml += '</div>';
                                });

                                // Add any remaining menus that don't have a parent
                                const standaloneMenus = response.menuAccess.filter(
                                    (menu) => !menu.parent_key && !parentMenus.find((p) => p.menu_key === menu.menu_key)
                                );

                                if (standaloneMenus.length > 0) {
                                    menuAccessHtml += '<div class="menu-standalone mt-4">';
                                    standaloneMenus.forEach(function (menu) {
                                        menuAccessHtml += `
                                            <div class="menu-item mb-3">
                                                <h5>
                                                    <i class="${menu.icon}"></i>
                                                    ${menu.menu_name}
                                                </h5>
                                                <p class="text-muted mb-0">Access: ${menu.access_type}</p>
                                            </div>
                                        `;
                                    });
                                    menuAccessHtml += '</div>';
                                }
                            } else {
                                menuAccessHtml += '<p class="text-muted">No menu access assigned.</p>';
                            }

                            menuAccessHtml += '</div>';

                            Swal.fire({
                                title: `Menu Access for ${userName}`,
                                html: menuAccessHtml,
                                width: '600px',
                                customClass: {
                                    container: 'menu-access-modal',
                                    popup: 'menu-access-popup',
                                },
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message || 'Failed to load menu access.',
                            });
                        }
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to load menu access. Please try again.',
                        });
                    },
                });
            });
        });
    </script>

    <script>
        function deleteProfile(event, profileId, profileName) {
            event.stopPropagation();

            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to delete the profile "${profileName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send delete request
                    fetch(`${window.location.origin}/company/user-profiles/${profileId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            Accept: 'application/json',
                        },
                    })
                        .then((response) => response.json())
                        .then((data) => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: data.message,
                                    showConfirmButton: false,
                                    timer: 1500,
                                }).then(() => {
                                    // Remove the profile card from the DOM
                                    const profileCard = document.querySelector(`[data-profile-id="${profileId}"]`);
                                    if (profileCard) {
                                        profileCard.remove();
                                    }

                                    // If no profiles left, show the empty state
                                    const profileList = document.querySelector('.profile-list');
                                    if (!profileList.querySelector('.profile-card')) {
                                        profileList.innerHTML = `
                                        <div class="text-center py-4">
                                            <i class="ri-folder-user-line h1 text-muted"></i>
                                            <p class="mt-2">No profiles found</p>
                                        </div>
                                    `;
                                    }
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: data.message,
                                });
                            }
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An error occurred while deleting the profile.',
                            });
                        });
                }
            });
        }
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 1500,
            });
        </script>
    @endif
@endsection
