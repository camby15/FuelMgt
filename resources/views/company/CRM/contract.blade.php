@extends('layouts.vertical', ['page_title' => 'CRM Contract'])
@section('css')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
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
        .form-floating select.form-select:not([value=""]),
        .form-floating textarea.form-control:focus,
        .form-floating textarea.form-control:not(:placeholder-shown) {
            border-color: #033c42;
            box-shadow: none;
        }

        .form-floating input.form-control:focus~label,
        .form-floating input.form-control:not(:placeholder-shown)~label,
        .form-floating select.form-select:focus~label,
        .form-floating select.form-select:not([value=""])~label,
        .form-floating textarea.form-control:focus~label,
        .form-floating textarea.form-control:not(:placeholder-shown)~label {
            height: auto;
            padding: 0 0.5rem;
            transform: translateY(-50%) translateX(0.5rem) scale(0.85);
            color: white;
            border-radius: 5px;
            z-index: 5;
        }

        .form-floating input.form-control:focus~label::before,
        .form-floating input.form-control:not(:placeholder-shown)~label::before,
        .form-floating select.form-select:focus~label::before,
        .form-floating select.form-select:not([value=""])~label::before,
        .form-floating textarea.form-control:focus~label::before,
        .form-floating textarea.form-control:not(:placeholder-shown)~label::before {
            content: "";
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
        [data-bs-theme="dark"] .form-floating input.form-control,
        [data-bs-theme="dark"] .form-floating select.form-select,
        [data-bs-theme="dark"] .form-floating textarea.form-control {
            border-color: #6c757d;
            color: #e9ecef;
        }

        [data-bs-theme="dark"] .form-floating label {
            color: #adb5bd;
        }

        [data-bs-theme="dark"] .form-floating input.form-control:focus,
        [data-bs-theme="dark"] .form-floating input.form-control:not(:placeholder-shown),
        [data-bs-theme="dark"] .form-floating select.form-select:focus,
        [data-bs-theme="dark"] .form-floating select.form-select:not([value=""]),
        [data-bs-theme="dark"] .form-floating textarea.form-control:focus,
        [data-bs-theme="dark"] .form-floating textarea.form-control:not(:placeholder-shown) {
            border-color: #0dcaf0;
        }

        [data-bs-theme="dark"] .form-floating input.form-control:focus~label::before,
        [data-bs-theme="dark"] .form-floating input.form-control:not(:placeholder-shown)~label::before,
        [data-bs-theme="dark"] .form-floating select.form-select:focus~label::before,
        [data-bs-theme="dark"] .form-floating select.form-select:not([value=""])~label::before,
        [data-bs-theme="dark"] .form-floating textarea.form-control:focus~label::before,
        [data-bs-theme="dark"] .form-floating textarea.form-control:not(:placeholder-shown)~label::before {
            background: #0dcaf0;
        }

        [data-bs-theme="dark"] select.form-select option {
            background-color: #212529;
            color: #e9ecef;
        }

        /* Modal styles */
        .modal-content {
            border-radius: 15px;
            border: none;
        }

        .modal-header {
            border-bottom: 1px solid #dee2e6;
            background-color: #f8f9fa;
            border-radius: 15px 15px 0 0;
        }

        [data-bs-theme="dark"] .modal-header {
            background-color: #343a40;
            border-color: #373b3e;
        }

        .modal-footer {
            border-top: 1px solid #dee2e6;
            border-radius: 0 0 15px 15px;
        }

        [data-bs-theme="dark"] .modal-footer {
            border-color: #373b3e;
        }

        /* Version Control, Document Storage, and Audit Trails styles */
        .audit-trails {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
        }

        [data-bs-theme="dark"] .audit-trails {
            background-color: #212529;
            border-color: #373b3e;
        }

        .audit-trails .timeline {
            position: relative;
            padding-left: 30px;
        }

        .audit-trails .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 2px;
            background-color: #dee2e6;
        }

        [data-bs-theme="dark"] .audit-trails .timeline::before {
            background-color: #495057;
        }

        .audit-trails .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .audit-trails .timeline-item::before {
            content: '';
            position: absolute;
            left: -34px;
            top: 0;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #0dcaf0;
        }

        .audit-trails .timeline-content {
            padding: 15px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        [data-bs-theme="dark"] .audit-trails .timeline-content {
            background-color: #2c3034;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            /* Center the buttons */
            gap: 10px;
            /* Space between buttons */
        }

        /* Timeline Legend Styles */
        .timeline-legend .badge {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            font-weight: 500;
            border-radius: 2rem;
            margin-right: 0.5rem;
        }

        .timeline-legend .badge-success {
            background-color: #0acf97;
            color: white;
        }

        .timeline-legend .badge-primary {
            background-color: #727cf5;
            color: white;
        }

        .timeline-legend .badge-danger {
            background-color: #fa5c7c;
            color: white;
        }

        .timeline-legend .badge-purple {
            background-color: #6f42c1;
            color: white;
            font-weight: bold;
            padding: 0.6rem 1.2rem;
            font-size: 1rem;
        }

        /* Activity Timeline Styles */
        .timeline {
            position: relative;
            padding: 20px 0;
            border-left: 2px solid #e9ecef;
        }

        .timeline:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: #e9ecef;
        }

        .timeline-content {
            margin-left: 60px;
            font-size: 0.85rem;
        }

        .timeline-content .activity-date {
            font-size: 0.75rem;
        }

        .timeline-content .title {
            font-size: 0.9rem;
        }

        .timeline-content .description {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .timeline-legend {
            font-size: 0.75rem;
        }

        .timeline-legend .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        /* Activity Type Styles */
        .activity-create {
            .icon {
                background-color: #0acf97;
            }
        }

        .activity-update {
            .icon {
                background-color: #727cf5;
            }
        }

        .activity-delete {
            .icon {
                background-color: #fa5c7c;
            }
        }

        .activity-bulk {
            .icon {
                background-color: #7a0bc0;
            }
        }

        /* Icon Styles */
        .icon {
            width: 40px;
            height: 40px;
            background-color: #e9ecef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: -20px;
            z-index: 1;
        }

        .icon::before {
            content: '';
            position: absolute;
            width: 12px;
            height: 12px;
            background-color: #fff;
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        /* Date Content Styles */
        .date-content {
            position: absolute;
            left: -50px;
            top: 50%;
            transform: translateY(-50%);
        }

        .date-outer {
            width: 60px;
            height: 60px;
            background-color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .date {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .date .month {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .date .year {
            font-size: 0.8rem;
            color: #6c757d;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .timeline-content {
                margin-left: 40px;
            }

            .date-content {
                left: -40px;
            }

            .date-outer {
                width: 50px;
                height: 50px;
            }

            .date .month {
                font-size: 1rem;
            }

            .date .year {
                font-size: 0.7rem;
            }
        }
    </style>

    <style>
        .form-floating {
            position: relative;
            margin-bottom: 1rem;
        }

        .form-floating input.form-control,
        .form-floating select.form-select {
            height: 50px;
            border: 1px solid #2f2f2f;
            border-radius: 10px;
            background-color: transparent;
            font-size: 1rem;
            padding: 1rem 0.75rem;
            transition: all 0.8s;
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

        [data-bs-theme="dark"] .form-floating input.form-control:focus~label::before,
        [data-bs-theme="dark"] .form-floating input.form-control:not(:placeholder-shown)~label::before,
        [data-bs-theme="dark"] .form-floating select.form-select:focus~label::before,
        [data-bs-theme="dark"] .form-floating select.form-select:not([value=""])~label::before {
            background: #0dcaf0;
        }

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
            background: url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'><path fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/></svg>") no-repeat right 0.75rem center/16px 12px;
        }
    </style>

    <style>
        /* Color palette for timeline */
        :root {
            --timeline-color-1: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            --timeline-color-2: linear-gradient(135deg, #ff6a88 0%, #ff6a88 100%);
            --timeline-color-3: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --timeline-color-4: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --timeline-color-5: linear-gradient(135deg, #3494e6 0%, #ec6ead 100%);
        }

        .main-timeline .timeline:nth-child(1) .date-outer:before,
        .main-timeline .timeline:nth-child(1) .icon:before {
            background: var(--timeline-color-1) !important;
        }

        .main-timeline .timeline:nth-child(2) .date-outer:before,
        .main-timeline .timeline:nth-child(2) .icon:before {
            background: var(--timeline-color-2) !important;
        }

        .main-timeline .timeline:nth-child(3) .date-outer:before,
        .main-timeline .timeline:nth-child(3) .icon:before {
            background: var(--timeline-color-3) !important;
        }

        .main-timeline .timeline:nth-child(4) .date-outer:before,
        .main-timeline .timeline:nth-child(4) .icon:before {
            background: var(--timeline-color-4) !important;
        }

        .main-timeline .timeline:nth-child(5) .date-outer:before,
        .main-timeline .timeline:nth-child(5) .icon:before {
            background: var(--timeline-color-5) !important;
        }

        .main-timeline .timeline .timeline-content {
            transition: all 0.3s ease;
        }

        .main-timeline .timeline:hover .timeline-content {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .main-timeline .timeline .icon:before {
            border: 2px solid white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .main-timeline .date-outer {
            background: white;
            border-radius: 50%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .main-timeline .timeline-content {
            background-color: #f8f9fc;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .main-timeline .timeline:hover .date-outer:before {
            transform: scale(1.1);
        }
    </style>

    <style>
        body {
            background-color: #f7f7f7;
        }

        .timeline-container {
            max-height: 500px;
            overflow-y: auto;
            padding: 15px;
        }

        .main-timeline {
            position: relative;
        }

        .main-timeline:before {
            content: "";
            width: 2px;
            height: 100%;
            background: #c6c6c6;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        .timeline {
            position: relative;
            margin-bottom: 30px;
        }

        .timeline:after {
            content: '';
            display: block;
            clear: both;
        }

        .icon {
            width: 18px;
            height: 18px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
        }

        .icon:before {
            content: '';
            width: 18px;
            height: 18px;
            background: #fff;
            border: 2px solid #232323;
            border-radius: 50%;
            position: absolute;
        }

        .date-content {
            width: 45%;
            float: left;
            position: relative;
            padding-right: 35px;
        }

        .date-content:before {
            content: '';
            width: 35px;
            height: 2px;
            background: #c6c6c6;
            position: absolute;
            top: 50%;
            right: 0;
        }

        .date-outer {
            width: 100px;
            height: 100px;
            text-align: center;
            margin: auto;
            border: 2px solid #232323;
            border-radius: 50%;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .date {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .month {
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .year {
            font-size: 22px;
            font-weight: 700;
            color: #232323;
        }

        .timeline-content {
            width: 45%;
            padding: 20px;
            float: right;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            font-size: 0.85rem;
        }

        .timeline-content .activity-date {
            font-size: 0.75rem;
        }

        .timeline-content .title {
            font-size: 0.9rem;
        }

        .timeline-content .description {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .timeline:nth-child(2n) .date-content {
            float: right;
            padding-left: 35px;
            padding-right: 0;
        }

        .timeline:nth-child(2n) .date-content:before {
            right: auto;
            left: 0;
        }

        .timeline:nth-child(2n) .timeline-content {
            float: left;
        }

        @media only screen and (max-width: 767px) {
            .main-timeline:before {
                left: 25px;
            }

            .timeline .icon {
                left: 25px;
            }

            .date-content {
                width: 100%;
                padding-left: 50px;
                padding-right: 0;
                float: right;
            }

            .date-content:before {
                left: 27px;
                right: auto;
                width: 25px;
            }

            .timeline-content {
                width: 100%;
                float: right;
            }
        }
    </style>

    <style>
        /* Timeline container styles */
        .timeline-container {
            max-height: 500px;
            overflow-y: auto;
            padding: 15px;
        }

        /* Activity type colors */
        .timeline.activity-create {
            --activity-color: #28a745;
        }

        .timeline.activity-update {
            --activity-color: #007bff;
        }

        .timeline.activity-delete {
            --activity-color: #dc3545;
        }

        .timeline.activity-bulk {
            --activity-color: #7a0bc0;
        }

        .timeline .date-outer {
            border-color: var(--activity-color, #232323);
        }

        .timeline .icon:before {
            border-color: var(--activity-color, #232323);
        }

        .timeline .date-content:before {
            background: var(--activity-color, #c6c6c6);
        }

        .timeline .year {
            color: var(--activity-color, #232323);
        }

        .timeline .timeline-content {
            border-left: 4px solid var(--activity-color, #232323);
        }
    </style>

    <style>
        /* Customer Action Buttons */
        .customer-actions .btn {
            min-width: 32px;
            transition: transform 0.15s ease-in-out;
        }

        .customer-actions .btn:hover {
            transform: translateY(-1px);
        }

        .customer-actions .btn i {
            width: 14px;
            height: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card crm-card">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="header-title mb-0">Contract Management</h4>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addContractModal">
                                <i class="fas fa-plus me-1"></i> Add New Contract
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-centered table-hover dt-responsive nowrap w-100" id="contractTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Contract Title</th>
                                        <th>Customer Name</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contracts as $contract)
                                        <tr>
                                            <td>{{ ($contracts->currentPage() - 1) * $contracts->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $contract->name }}</td>
                                            <td>{{ $contract->customer_name }}</td>
                                            <td>{{ date('Y-m-d', strtotime($contract->start_date)) }}</td>
                                            <td>{{ date('Y-m-d', strtotime($contract->end_date)) }}</td>
                                            <td><span
                                                    class="badge bg-{{ $contract->status === 'draft' ? 'warning' : 'success' }} justify-center">{{ ucfirst($contract->status) }}</span>
                                            </td>
                                            <td>
                                                <div class="action-buttons">

                                                    <button class="btn btn-sm btn-warning action-btn me-1"
                                                        onclick="editContract({{ $contract->id }})">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    @if ($contract->file_path)
                                                        <button class="btn btn-sm btn-success action-btn me-1"
                                                            onclick="downloadContract({{ $contract->id }})">
                                                            <i class="fas fa-download"></i>
                                                        </button>
                                                    @endif
                                                    <button class="btn btn-sm btn-primary action-btn me-1"
                                                        onclick="sendForSignature({{ $contract->id }})"
                                                        data-id="{{ $contract->id }}" data-title="{{ $contract->name }}"
                                                        data-customer-name="{{ $contract->customer_name }}"
                                                        data-customer-email="{{ $contract->email }}">
                                                        <i class="fas fa-signature"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm"
                                                        onclick="deleteContract({{ $contract->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No contracts found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div id="paginationLinks" class="d-flex justify-content-center">
                            {{ $contracts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Audit Trails Section in a Card --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Audit Trails</h5>
        </div>
        <div class="card-body">
            <div class="audit-trails">
                <!-- Filter & Search Controls -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <select class="form-select" id="modalStatusFilter">
                            <option value="all">All Activities</option>
                            <option value="created">Created</option>
                            <option value="modified">Modified</option>
                            <option value="signed">Signed</option>
                            <option value="deleted">Deleted</option>
                        </select>
                    </div>
                </div>

                <!-- Timeline Card -->
                <div class="card">
                    <div class="card-body">
                        <div class="container-fluid p-0">
                            <h5 class="card-title mb-3">
                                Activity Timeline
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="timeline-legend small">
                                        <span class="badge bg-success me-2">Create</span>
                                        <span class="badge bg-primary me-2">Update</span>
                                        <span class="badge bg-danger me-2">Delete</span>
                                        <span class="badge bg-purple me-2"
                                            style="background-color: #ddce009f;">Signed</span>
                                    </div>
                                    <div class="form-floating" style="width: 300px;">
                                        <input type="text" id="activitySearch" class="form-control" placeholder=" "
                                            required>
                                        <label for="activitySearch">Search activities</label>
                                    </div>
                                </div>
                            </h5>

                            <!-- Timeline Items -->
                            <div class="timeline-container">
                                <div class="main-timeline">
                                    @forelse($recentActivities as $activity)
                                        @php
                                            $activityClass = '';
                                            $status = '';

                                            if (str_contains(strtolower($activity->description), 'created')) {
                                                $activityClass = 'activity-create';
                                                $status = 'created';
                                            } elseif (
                                                str_contains(strtolower($activity->description), 'updated') ||
                                                str_contains(strtolower($activity->description), 'modified')
                                            ) {
                                                $activityClass = 'activity-update';
                                                $status = 'updated';
                                            } elseif (str_contains(strtolower($activity->description), 'deleted')) {
                                                $activityClass = 'activity-delete';
                                                $status = 'deleted';
                                            } elseif (str_contains(strtolower($activity->description), 'signed')) {
                                                $activityClass = 'activity-signed';
                                                $status = 'signed';
                                            }

                                            $searchText = strtolower(
                                                $activity->description .
                                                    ' ' .
                                                    ($activity->metadata['contract_name'] ?? '') .
                                                    ' ' .
                                                    ($activity->metadata['customer_name'] ?? 'System'),
                                            );
                                        @endphp

                                        <div class="timeline {{ $activityClass }}" data-search="{{ $searchText }}"
                                            data-status="{{ $status }}">
                                            <div class="icon"></div>
                                            <div class="date-content">
                                                <div class="date-outer">
                                                    <span class="date">
                                                        <span
                                                            class="month">{{ $activity->created_at->format('M') }}</span>
                                                        <span
                                                            class="year">{{ $activity->created_at->format('Y') }}</span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="timeline-content">
                                                <div class="activity-date text-muted mb-2">
                                                    {{ $activity->created_at->format('M d, Y H:i') }}
                                                </div>
                                                <h5 class="title">{{ $activity->description }}</h5>
                                                <strong>Contract Name:
                                                    {{ $activity->metadata['contract_name'] ?? 'N/A' }}</strong>
                                                <br>
                                                <p class="description">
                                                    @if (isset($activity->metadata['customer_name']))
                                                        By {{ $activity->metadata['customer_name'] }}
                                                    @else
                                                        By System
                                                    @endif
                                                    <br>
                                                    <small
                                                        class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                                    <br>
                                                </p>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="timeline">
                                            <div class="icon"></div>
                                            <div class="date-content">
                                                <div class="date-outer">
                                                    <span class="date">
                                                        <span class="month">No</span>
                                                        <span class="year">Activities</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>

                                <!-- No Results Message -->
                                <div id="noResultsMessage" class="text-center text-muted mt-4" style="display: none;">
                                    No activities found.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- JavaScript Filter Logic -->
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const searchInput = document.getElementById("activitySearch");
                        const statusFilter = document.getElementById("modalStatusFilter");
                        const timelineItems = document.querySelectorAll(".main-timeline .timeline");
                        const noResultsMessage = document.getElementById("noResultsMessage");

                        function applyFilters() {
                            const searchTerm = searchInput.value.toLowerCase().trim();
                            const selectedStatus = statusFilter.value;
                            let visibleCount = 0;

                            timelineItems.forEach(item => {
                                const content = item.getAttribute("data-search") || "";
                                const status = item.getAttribute("data-status") || "";

                                const matchesSearch = content.includes(searchTerm);
                                const matchesStatus = selectedStatus === "all" || status === selectedStatus;

                                const shouldShow = matchesSearch && matchesStatus;
                                item.style.display = shouldShow ? "" : "none";

                                if (shouldShow) visibleCount++;
                            });

                            noResultsMessage.style.display = visibleCount === 0 ? "block" : "none";
                        }

                        searchInput.addEventListener("input", applyFilters);
                        statusFilter.addEventListener("change", applyFilters);
                    });
                </script>

                <!-- JavaScript Filter -->
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const searchInput = document.getElementById("activitySearch");
                        const timelineItems = document.querySelectorAll(".main-timeline .timeline");
                        const noResultsMessage = document.getElementById("noResultsMessage");

                        searchInput.addEventListener("input", function() {
                            const searchTerm = this.value.toLowerCase().trim();
                            let visibleCount = 0;

                            timelineItems.forEach(item => {
                                const content = item.getAttribute("data-search") || "";
                                const isMatch = content.includes(searchTerm);
                                item.style.display = isMatch ? "" : "none";
                                if (isMatch) visibleCount++;
                            });

                            noResultsMessage.style.display = visibleCount === 0 ? "block" : "none";
                        });
                    });
                </script>

                <!-- End inner card -->
            </div>
        </div>
    </div>

    {{-- Audit Trail Modal --}}
    <div class="modal fade" id="auditTrailModal" tabindex="-1" aria-labelledby="auditTrailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="auditTrailModalLabel">Contract Audit Trail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <select class="form-select" id="modalStatusFilter">
                                    <option value="all">All Activities</option>
                                    <option value="created">Created</option>
                                    <option value="modified">Modified</option>
                                    <option value="signed">Signed</option>
                                    <option value="deleted">Deleted</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="modalSearchInput"
                                    placeholder="Search activities...">
                            </div>
                        </div>
                    </div>
                    <div class="timeline">
                        <!-- Timeline items will be dynamically added here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Contract Modal --}}
    <div class="modal fade" id="editContractModal" tabindex="-1" aria-labelledby="editContractModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editContractModalLabel">Edit Contract</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editContractForm">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="edit_contract_name"
                                        name="contract_name" placeholder=" " required>
                                    <label for="edit_contract_name">Contract Name *</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="edit_customer_name"
                                        name="customer_name" placeholder=" " required>
                                    <label for="edit_customer_name">Customer Name *</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="edit_email" name="email"
                                        placeholder=" " required>
                                    <label for="edit_email">Email *</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="edit_start_date" name="start_date"
                                        placeholder=" " required>
                                    <label for="edit_start_date">Start Date *</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="edit_end_date" name="end_date"
                                        placeholder=" " required>
                                    <label for="edit_end_date">End Date *</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" id="edit_notes" name="notes" placeholder=" " style="height: 100px"></textarea>
                                    <label for="edit_notes">Notes</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="file" class="form-control" id="edit_contract_file"
                                        name="contract_file" accept=".pdf,.doc,.docx">
                                    <label for="edit_contract_file">Contract File (PDF, DOC, DOCX)</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateContract()">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Send for Signature Modal -->
    <div class="modal fade" id="sendForSignatureModal" tabindex="-1" aria-labelledby="sendForSignatureModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sendForSignatureModalLabel">Send Contract for Signature</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="sendForSignatureForm" method="POST">
    @csrf

    <!-- Hidden input for Contract ID -->
    <input type="hidden" id="contractId" name="contract_id">

    <div class="form-floating mb-3">
        <input type="email" class="form-control" id="signerEmail" name="signer_email"
            placeholder="Signer's Email" required>
        <label for="signerEmail">Customer's Email</label>
    </div>

    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="signerName" name="signer_name"
            placeholder="Signer's Name" required>
        <label for="signerName">Customer's Name</label>
    </div>

    <div class="form-floating mb-3">
        <textarea class="form-control" id="message" name="message" placeholder="Message to Signer" style="height: 100px"></textarea>
        <label for="message">Message to Customer (Optional)</label>
    </div>
</form>

                </div>
                <div class="modal-footer">
                    <button type="button" id="openOnlineSignatureBtn" class="btn btn-success me-auto" data-bs-toggle="modal"
                        data-bs-target="#onlinesignature">
                        <i class="bi bi-pencil-square me-1"></i>
                        Online Signature
                    </button>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitSendForSignature()">Send</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Online Signature Modal -->
    <div class="modal fade" id="onlinesignature" tabindex="-1" aria-labelledby="onlinesignatureLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="onlinesignatureLabel">Online Signature</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

               <form id="signatureForm">
    @csrf
    <!-- Contract ID hidden input -->
    <input type="hidden" id="signatureContractId" name="contract_id">

    <div class="modal-body">
        <!-- Email Fields -->
        <div id="emailFields">
            <div class="mb-3 position-relative">
                <div class="form-floating">
                    <input type="email" name="emails[]" class="form-control"
                        placeholder="example@gmail.com" required>
                    <label>Email *</label>
                </div>
                <button type="button"
                    class="btn btn-outline-success position-absolute top-0 end-0 mt-2 me-2 addEmailBtn"
                    title="Add another email">
                    <i class="bi bi-plus-lg"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" name="submit" class="btn btn-primary">Send</button>
    </div>
</form>

            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
    const emailFieldsContainer = document.getElementById('emailFields');
    const signatureForm = document.getElementById('signatureForm');
    const openOnlineSignatureBtn = document.getElementById('openOnlineSignatureBtn');

    if (openOnlineSignatureBtn) {
        openOnlineSignatureBtn.addEventListener('click', function() {
            const contractId = document.getElementById('contractId').value;
            document.getElementById('signatureContractId').value = contractId;

            // Set the contract name in the signature form
            const signerName = document.getElementById('signerName').value;
            document.getElementById('contract_name').value = signerName;
        });
    }

    // Existing email field management code...
    emailFieldsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.addEmailBtn')) {
            const fieldGroup = document.createElement('div');
            fieldGroup.className = 'mb-3 input-group';

            fieldGroup.innerHTML = `
                <div class="form-floating flex-grow-1">
                    <input type="email" name="emails[]" class="form-control" placeholder="example@gmail.com" required>
                    <label>Email *</label>
                </div>
                <button type="button" class="btn btn-danger btn-sm ms-2 d-flex align-items-center justify-content-center removeEmailBtn" style="height: 58px; width: 42px;" title="Remove this email">
                    <i class="bi bi-dash-lg"></i>
                </button>
            `;
            emailFieldsContainer.appendChild(fieldGroup);
        }

        if (e.target.closest('.removeEmailBtn')) {
            e.target.closest('.input-group').remove();
        }
    });

    signatureForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const form = event.target;
        const formData = new FormData(form);
        const jsonData = {
            contract_id: formData.get('contract_id'),
            emails: formData.getAll('emails[]'),
        };

        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...';

        fetch("{{ route('signature.send') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": form.querySelector('input[name="_token"]').value,
            },
            body: JSON.stringify(jsonData),
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) throw data;

            Swal.fire({
                icon: data.type || 'success',
                title: data.title || 'Success',
                text: data.message || 'Link sent!',
                timer: 3000
            });

            const modal = bootstrap.Modal.getInstance(document.getElementById('onlinesignature'));
            modal.hide();

            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: error.title || 'Error',
                text: error.message || 'Something went wrong.',
            });
            console.error('Error:', error);

            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        });
    });
});

    </script>

    </div>
    </div>
    </div>

    {{-- Add Contract Modal --}}
    <div class="modal fade" id="addContractModal" tabindex="-1" aria-labelledby="addContractModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addContractModalLabel">Add New Contract</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addContractForm" onsubmit="addContract(event)" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="name" id="add_contract_name"
                                        placeholder=" " required>
                                    <label for="contract_name">Contract Name *</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="customer_name" id="customer_name"
                                        placeholder=" " required>
                                    <label for="customer_name">Customer Name *</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="email-input"
                                        placeholder="Type email and press Enter" autocomplete="off" />
                                    <label for="email-input">Emails *</label>
                                </div>
                                <div id="email-tags" class="mt-2 d-flex flex-wrap gap-2"></div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="date" class="form-control" name="start_date" id="start_date"
                                        placeholder=" " required>
                                    <label for="start_date">Start Date *</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="date" class="form-control" name="end_date" id="end_date"
                                        placeholder=" " required>
                                    <label for="end_date">End Date *</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="file" class="form-control" name="signature_file" accept="image/*"
                                        id="signature_file" placeholder=" " required> 
                                    <label for="signature_file">Upload Signature *</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" name="notes" id="notes" placeholder=" " style="height: 100px" required></textarea>
                                    <label for="notes">Notes *</label>
                                </div>
                            </div>

                            <!-- Auto Renewal Checkbox -->
                            <div class="col-12">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="auto_renewal"
                                        id="auto_renewal">
                                    <label class="form-check-label" for="auto_renewal">Auto Renewal</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between align-items-center">
                        <div></div>
                        <div>
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Contract</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- Delete Contract Modal --}}
    <div class="modal fade" id="deleteContractModal" tabindex="-1" aria-labelledby="deleteContractModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteContractModalLabel">Delete Contract</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this contract? This action cannot be undone.</p>
                    <form id="deleteContractForm" method="POST">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteContract">Delete</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Upload Contract Modal --}}
    <div class="modal fade" id="uploadContractModal" tabindex="-1" aria-labelledby="uploadContractModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadContractModalLabel">Upload Contract</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="uploadContractForm" action="#" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="contractTitle" class="form-label">Contract Title</label>
                            <input type="text" class="form-control" id="contractTitle" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="contractFile" class="form-label">Upload File</label>
                            <input type="file" class="form-control" id="contractFile" name="file" required>
                        </div>
                        <div class="mb-3">
                            <label for="contractNotes" class="form-label">Notes</label>
                            <textarea class="form-control" id="contractNotes" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Upload Contract</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Add Contract Function
        function addContract(event) {
            event.preventDefault(); // Prevent default form submission

            const emailTagsContainer = document.getElementById('email-tags');
            // Get emails from data-email attributes
            const emails = Array.from(emailTagsContainer.querySelectorAll('span.badge')).map(span =>
                span.getAttribute('data-email')
            ).filter(email => email && email.trim() !== '');

            const signatureInput = document.getElementById('signature_file');
            const file = signatureInput.files[0];

            const formData = new FormData();
            formData.append('name', document.getElementById('add_contract_name').value);
            formData.append('customer_name', document.getElementById('customer_name').value);
            formData.append('emails', JSON.stringify(emails));
            formData.append('start_date', document.getElementById('start_date').value);
            formData.append('end_date', document.getElementById('end_date').value);
            formData.append('notes', document.getElementById('notes').value);

            if (document.getElementById('auto_renewal').checked) {
                formData.append('auto_renewal', 1);
            }

            if (file) {
                formData.append('signature_file', file);
            }

            const token = document.querySelector('meta[name="csrf-token"]').content;

            // Basic validation
            if (!formData.get('name') || !formData.get('customer_name') || emails.length === 0 ||
                !formData.get('start_date') || !formData.get('end_date')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please fill in all required fields and add at least one email'
                });
                return;
            }

            fetch('/company/CRM/contract', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Contract added successfully'
                        });
                        $('#addContractModal').modal('hide');
                        $('#addContractModal').on('hidden.bs.modal', function() {
                            location.reload();
                        });
                    } else {
                        throw new Error(data.message || 'Failed to add contract');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'An error occurred while adding the contract'
                    });
                });
        }

        // Email Tags Input Logic
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email-input');
            const emailTags = document.getElementById('email-tags');

            function createTag(email) {
                const tag = document.createElement('span');
                tag.className = 'badge bg-secondary me-1 mb-1 d-inline-flex align-items-center';
                tag.setAttribute('data-email', email);
                tag.style.padding = '0.5em 0.75em';
                tag.style.fontSize = '0.9em';

                // Add email text as a separate node
                const emailText = document.createElement('span');
                emailText.textContent = email;
                tag.appendChild(emailText);

                const closeBtn = document.createElement('button');
                closeBtn.type = 'button';
                closeBtn.className = 'btn-close btn-close-white btn-sm ms-2';
                closeBtn.setAttribute('aria-label', 'Remove');

                closeBtn.addEventListener('click', () => {
                    emailTags.removeChild(tag);
                });

                tag.appendChild(closeBtn);
                return tag;
            }

            function validateEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }

            emailInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const email = emailInput.value.trim();

                    if (email && validateEmail(email)) {
                        const existingEmails = Array.from(emailTags.querySelectorAll('span.badge')).map(s =>
                            s.getAttribute('data-email'));
                        if (!existingEmails.includes(email)) {
                            const tag = createTag(email);
                            emailTags.appendChild(tag);
                            emailInput.value = '';
                        } else {
                            Swal.fire('Duplicate Email', 'This email is already added.', 'warning');
                        }
                    } else {
                        Swal.fire('Invalid Email', 'Please enter a valid email address.', 'error');
                    }
                }
            });
        });

        // Edit Contract
        function editContract(id) {
            // Show loading state
            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Get CSRF token
            const token = document.querySelector('meta[name="csrf-token"]').content;

            // Fetch contract details
            fetch(`/company/CRM/contract/${id}/show`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const contract = data.contract;

                        // Format dates for HTML5 date input
                        const formatDate = (dateString) => {
                            const date = new Date(dateString);
                            return date.toISOString().split('T')[0];
                        };

                        // Populate form fields
                        document.getElementById('edit_contract_name').value = contract.name;
                        document.getElementById('edit_customer_name').value = contract.customer_name;
                        document.getElementById('edit_email').value = contract.emails;
                        document.getElementById('edit_start_date').value = formatDate(contract.start_date);
                        document.getElementById('edit_end_date').value = formatDate(contract.end_date);
                        document.getElementById('edit_notes').value = contract.notes || '';

                        // Store contract ID for update
                        document.getElementById('editContractForm').dataset.contractId = contract.id;

                        // Show modal
                        Swal.close();
                        $('#editContractModal').modal('show');
                    } else {
                        throw new Error(data.message || 'Failed to fetch contract details');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Failed to fetch contract details'
                    });
                });
        }

        // Update Contract
        function updateContract() {
            const form = document.getElementById('editContractForm');
            const contractId = form.dataset.contractId;
            const formData = new FormData(form);

            // Show loading state
            Swal.fire({
                title: 'Saving...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Get CSRF token
            const token = document.querySelector('meta[name="csrf-token"]').content;

            // Send update request
            fetch(`/company/CRM/contract/edit/${contractId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Contract updated successfully'
                        }).then(() => {
                            // Close modal and refresh page
                            $('#editContractModal').modal('hide');
                            window.location.reload();
                        });
                    } else {
                        throw new Error(data.message || 'Failed to update contract');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Failed to update contract'
                    });
                });
        }

        function deleteContract(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteContractForm');
                    form.action = `/company/CRM/contract/delete/${id}`;

                    // Submit form with AJAX
                    fetch(form.action, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: data.message || 'Contract has been deleted.',
                                    icon: 'success'
                                }).then(() => {
                                    // Always refresh the page
                                    window.location.reload();
                                });
                            } else {
                                throw new Error(data.message || 'Delete failed');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire(
                                'Error!',
                                error.message || 'There was an error deleting the contract.',
                                'error'
                            );
                        });
                }
            });
        }

        // Initialize send for signature modal
        function sendForSignature(id) {
    const button = document.querySelector(`button[data-id="${id}"]`);
    const customerName = button.getAttribute('data-customer-name');
    const customerEmail = button.getAttribute('data-customer-email');

    // Pre-populate the form fields
    document.getElementById('signerEmail').value = customerEmail;
    document.getElementById('signerName').value = customerName;

    // Set the hidden contract ID
    document.getElementById('contractId').value = id;  // << Add this line

    // Set the form action
    const form = document.getElementById('sendForSignatureForm');
    form.action = `/company/CRM/contract/${id}/send-for-signature`;

    $('#sendForSignatureModal').modal('show');
}

        // Submit signature request
        function submitSendForSignature() {
            const form = document.getElementById('sendForSignatureForm');
            const formData = new FormData(form);

            $.ajax({
                url: form.action,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Contract has been sent for signature.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#sendForSignatureModal').modal('hide');
                        location.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to send contract for signature.'
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to send contract for signature.'
                    });
                }
            });
        }

        // Download Contact 
        function downloadContract(id) {
            window.location.href = `/company/CRM/contract/${id}/download`;
        }

        // Event listener for delete button
        $(document).on('click', '.deleteContractBtn', function() {
            const id = $(this).data('id');
            deleteContract(id);
        });

        document.addEventListener('DOMContentLoaded', function() {
            const filterSelect = document.getElementById('modalStatusFilter');
            filterSelect.addEventListener('change', function() {
                const selectedValue = this.value;
                // Add filter logic here
            });

            const searchInput = document.getElementById('modalSearchInput');
            searchInput.addEventListener('input', function() {
                const searchValue = this.value;
                // Add search logic here
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("activitySearch");
            const timelineItems = document.querySelectorAll(".main-timeline .timeline");

            searchInput.addEventListener("input", function() {
                const searchTerm = this.value.toLowerCase().trim();

                timelineItems.forEach(item => {
                    const content = item.getAttribute("data-search") || "";
                    item.style.display = content.includes(searchTerm) ? "" : "none";
                });
            });
        });
    </script>

@endsection
