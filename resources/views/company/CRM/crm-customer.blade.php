@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    @php
        use App\Helpers\CurrencyHelper;
    @endphp     

@endsection

<style>
    /* Floating Label Styles */
    .form-floating {
        position: relative;
        margin-bottom: 1rem;
    }
    .form-floating input.form-control,
    .form-floating select.form-select,
    .form-floating textarea.form-control {
        height: 40px;
        border: 1px solid #2f2f2f;
        border-radius: 10px;
        background-color: transparent;
        font-size: 1rem;
        padding: 0.5rem 0.75rem;
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
        padding: 0.5rem 0.75rem;
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
    .form-floating input.form-control:focus ~ label,
    .form-floating input.form-control:not(:placeholder-shown) ~ label,
    .form-floating select.form-select:focus ~ label,
    .form-floating select.form-select:not([value=""]) ~ label,
    .form-floating textarea.form-control:focus ~ label,
    .form-floating textarea.form-control:not(:placeholder-shown) ~ label {
        height: auto;
        padding: 0 0.5rem;
        transform: translateY(-60%) translateX(0.5rem) scale(0.85);
        color: white;
        border-radius: 5px;
        z-index: 5;
    }
    .form-floating input.form-control:focus ~ label::before,
    .form-floating input.form-control:not(:placeholder-shown) ~ label::before,
    .form-floating select.form-select:focus ~ label::before,
    .form-floating select.form-select:not([value=""]) ~ label::before,
    .form-floating textarea.form-control:focus ~ label::before,
    .form-floating textarea.form-control:not(:placeholder-shown) ~ label::before {
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

    [data-bs-theme="dark"] .form-floating input.form-control:focus ~ label::before,
    [data-bs-theme="dark"] .form-floating input.form-control:not(:placeholder-shown) ~ label::before,
    [data-bs-theme="dark"] .form-floating select.form-select:focus ~ label::before,
    [data-bs-theme="dark"] .form-floating select.form-select:not([value=""]) ~ label::before,
    [data-bs-theme="dark"] .form-floating textarea.form-control:focus ~ label::before,
    [data-bs-theme="dark"] .form-floating textarea.form-control:not(:placeholder-shown) ~ label::before {
        background: #0dcaf0;
    }

    [data-bs-theme="dark"] select.form-select option {
        background-color: #212529;
        color: #e9ecef;
    }

    /* Reset and simplify select styles */
    .form-floating select.form-select {
        display: block;
        width: 100%;
        height: 30px;
        padding: 0.5rem 0.75rem;
        font-size: 0.5rem;
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

    [data-bs-theme="dark"] .form-floating select.form-select {
        background: url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'><path fill='none' stroke='%23adb5bd' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/></svg>") no-repeat right 0.75rem center/16px 12px;
        background-color: transparent;
    }

    .form-floating select.form-select:focus {
        border-color: #033c42;
        outline: 0;
        box-shadow: none;
    }

    .form-floating select.form-select ~ label {
        padding: 0.5rem 0.75rem;
    }

    /* Modal body styles */
    .modal-body {
        background: none;
        padding: 1.5rem;
    }

    /* Action Button Styles */
    .action-btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }
    
    .action-btn i {
        font-size: 0.875rem;
    }

    /* Stat Card Styles */
    .stat-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        padding: 25px;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-card.sales {
        border-left: 4px solid #007bff;
    }

    .stat-card.conversion {
        border-left: 4px solid #28a745;
    }

    .stat-card.deal {
        border-left: 4px solid #ffc107;
    }

    .stat-card.pipeline {
        border-left: 4px solid #dc3545;
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
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
    [data-bs-theme="dark"] .form-floating input.form-control:focus ~ label::before,
    [data-bs-theme="dark"] .form-floating input.form-control:not(:placeholder-shown) ~ label::before,
    [data-bs-theme="dark"] .form-floating select.form-select:focus ~ label::before,
    [data-bs-theme="dark"] .form-floating select.form-select:not([value=""]) ~ label::before {
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
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .main-timeline .timeline .icon:before {
        border: 2px solid white;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .main-timeline .date-outer {
        background: white;
        border-radius: 50%;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .main-timeline .timeline-content {
        background-color: #f8f9fc;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
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
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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

@php
    use App\Http\Controllers\ActivityLogController;
    $recentActivities = ActivityLogController::getRecentActivities();
@endphp

<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card sales">
            <i class="stat-icon fas fa-users"></i>
            <div class="stat-title">Total Customers</div>
            <div class="stat-value">{{ $customers->count() }}</div>
            <div class="stat-change">
                <i class="fas fa-arrow-up"></i>
                <span>{{ number_format(($customers->count() / max(1, $customers->count() - 1)) * 100 - 100, 1) }}% vs last month</span>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card conversion">
            <i class="stat-icon fas fa-building"></i>
            <div class="stat-title">Corporate Customers</div>
            <div class="stat-value">{{ $customers->where('customer_type', 'corporate')->count() }}</div>
            <div class="stat-change">
                <i class="fas fa-chart-pie"></i>
                <span>{{ number_format(($customers->where('customer_type', 'corporate')->count() / max(1, $customers->count())) * 100, 1) }}% of total</span>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card pipeline">
            <i class="stat-icon fas fa-user"></i>
            <div class="stat-title">Individual Customers</div>
            <div class="stat-value">{{ $customers->where('customer_type', 'individual')->count() }}</div>
            <div class="stat-change">
                <i class="fas fa-chart-pie"></i>
                <span>{{ number_format(($customers->where('customer_type', 'individual')->count() / max(1, $customers->count())) * 100, 1) }}% of total</span>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card deal">
            <i class="stat-icon fas fa-dollar-sign"></i>
            <div class="stat-title">Customer Value</div>
            <div class="stat-value">{{ CurrencyHelper::format($customers->sum('value')) }}</div>
            <div class="stat-change">
                <i class="fas fa-arrow-up"></i>
                <span>{{ number_format(($customers->sum('value') / max(1, $customers->sum('value') - 1)) * 100 - 100, 1) }}% vs last month</span>
            </div>
        </div>
    </div>
</div>

    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Customer Management</h6>
                <div class="dropdown no-arrow">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#searchCustomerModal">
                        <i class="fa-solid fa-plus me-1"></i> Add Customer
                    </button>

                    <button type="button" class="btn btn-secondary me2" data-bs-toggle="modal" data-bs-target="#searchFilterModal">
                        <i class="fa-solid fa-upload me-1"></i> Bulk Upload
                    </button>

                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-download me-1"></i> Download Template
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('company.customers.download-template', ['type' => 'corporate']) }}">
                                    <i class="fas fa-building me-1"></i> Corporate Customer Template
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('company.customers.download-template', ['type' => 'individual']) }}">
                                    <i class="fas fa-user me-1"></i> Individual Customer Template
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <!-- 
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#searchFilterModal">
                            <i class="fa-solid fa-filter me-1"></i> Search and Filter
                        </button>  -->
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table" id="customerTable">
                        <thead>
                            <tr>
                                   
                                <th>#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Category</th>
                                <th>Contact</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Source</th>
                                <th>Status</th>
                                <th>Value ({{ CurrencyHelper::symbol() }})</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                          
                        </tbody>
                    </table>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination" id="customersspagination">
                    
                        </ul>
                    </nav>
                   
                </div>
            </div>
        </div>
    </div>

    <!-- Account Activity Timeline -->
    <div class="col-lg-12">
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
                                <span class="badge bg-purple me-2" style="background-color: #6f42c1;">Bulk Upload</span>
                            </div>
                            <div class="form-floating" style="width: 300px;">
                                <input type="text" id="activitySearch" class="form-control" placeholder=" " required>
                                <label for="activitySearch">Search activities</label>
                            </div>
                        </div>
                    </h5>
                    <div class="timeline-container">
                        <div class="main-timeline">
                            @forelse($recentActivities as $index => $activity)
                            @php
                                $activityClass = '';
                                if (str_contains(strtolower($activity->description), 'created')) {
                                    $activityClass = 'activity-create';
                                } elseif (str_contains(strtolower($activity->description), 'updated')) {
                                    $activityClass = 'activity-update';
                                } elseif (str_contains(strtolower($activity->description), 'deleted')) {
                                    $activityClass = 'activity-delete';
                                } elseif (str_contains(strtolower($activity->description), 'bulk')) {
                                    $activityClass = 'activity-bulk';
                                }
                            @endphp
                            <div class="timeline {{ $activityClass }}">
                                <div class="icon"></div>
                                <div class="date-content">
                                    <div class="date-outer">
                                        <span class="date">
                                            <span class="month">{{ $activity->created_at->format('M') }}</span>
                                            <span class="year">{{ $activity->created_at->format('Y') }}</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="timeline-content">
                                    <div class="activity-date text-muted mb-2">
                                        {{ $activity->created_at->format('M d, Y H:i') }}
                                    </div>
                                    <h5 class="title">{{ $activity->description }}</h5>
                                    <p class="description">
                                        @if($activity->company)
                                            By {{ $activity->company->company_name ?? 'Unknown Company' }}
                                        @else
                                            By System
                                        @endif
                                        <br>
                                        <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Contacts Modal -->
<div class="modal fade" id="contactsModal" tabindex="-1" aria-labelledby="contactsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactsModalLabel">Manage Contacts</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add/Edit Contact Form -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0" id="formTitle">Add New Contact</h6>
                    </div>
                    <div class="card-body">
                        <form id="contactForm" class="row g-3">
                            @csrf
                            <input type="hidden" id="contactId" name="id">
                            <div class="col-md-4">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="contactName" name="name" placeholder=" " required>
                                    <label for="contactName">Contact Name</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="contactEmail" name="email" placeholder=" ">
                                    <label for="contactEmail">Email</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating mb-3">
                                    <input type="tel" class="form-control" id="contactPhone" name="phone" placeholder=" ">
                                    <label for="contactPhone">Phone</label>
                                </div>
                            </div>
                            <div class="col-md-1 d-flex align-items-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="isDefault" name="is_default">
                                    <label class="form-check-label" for="isDefault">Default</label>
                                </div>
                            </div>
                            <div class="col-12 text-end">
                                <button type="button" class="btn btn-secondary" id="cancelEditBtn" style="display: none;">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </button>
                                <button type="submit" class="btn btn-primary" id="submitContactBtn">
                                    <i class="fas fa-save me-1"></i> Save Contact
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Contacts Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Customer Contacts</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-striped" id="contactsTable">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th class="text-center">Default</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-inbox fa-2x mb-2 text-muted"></i>
                                            <p class="mb-0">No contacts found</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Search Customer Modal -->
<div class="modal fade" id="searchCustomerModal" tabindex="-1" aria-labelledby="searchCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="searchCustomerModalLabel">Search Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
           
                <form id="searchCustomerForm">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="customerSearchInput" 
                               placeholder="Search Insured Code, Name, or Phone" 
                               name="customer_search">
                        <label for="customerSearchInput">Search Customer</label>
                    </div>
                </form>

                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    Before creating a new customer, please search to ensure they don't already have an account.
                    Search by: Customer Name, Email, Company Name, Phone, Sector, or Status.
                    <br>
                    <strong>Search by:</strong> Customer Name,
                </div>

                <div id="searchResultsContainer" class="mt-3">
                    <!-- AJAX search results will be dynamically populated here -->
                </div>
                
            </div>
            <div class="modal-footer">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-12">
                            <button type="button" class="btn btn-primary w-100" id="performCustomerSearch">Search</button>
                        </div>
                    </div>
                    <div class="row d-none" id="addCustomerButtonsRow">
                        <div class="col-6">
                            <button type="button" class="btn btn-success w-100" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#addCustomerModal">Add Cooperate Customer</button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-success w-100" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#addIndividualCustomerModal">Add Individual Customer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Add Corporate Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCustomerModalLabel">New Corporate Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addCustomerForm" action="{{ route('company.customers.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Company Profile Section -->
                    <div class="row">
                        <div class="col-12">
                            <h4 class="bg-primary text-white p-2 rounded">Company Profile</h4>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="company_name" id="company_name" placeholder=" " required>
                                <label for="company_name">Company Name</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" name="corporate_email" id="corporate_email" placeholder=" " required>
                                <label for="corporate_email">Corporate Email</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="tel" class="form-control" name="corporate_telephone" id="corporate_telephone" placeholder=" " required>
                                <label for="corporate_telephone">Corporate Phone</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <textarea class="form-control" name="headquarters_address" id="headquarters_address" placeholder=" " style="height: 100px;" required></textarea>
                                <label for="headquarters_address">Headquarters Address</label>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Company Details -->
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" name="commencement_date" id="commencement_date" placeholder=" " required>
                                <label for="commencement_date">Commencement Date</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="sector" id="sector" required>
                                    <option value="">Select Sector</option>
                                    <option value="Agriculture">Agriculture & Farming</option>
                                    <option value="Technology">Information Technology</option>
                                    <option value="Healthcare">Healthcare & Medical</option>
                                    <option value="Education">Education & Training</option>
                                    <option value="Engineering">Engineering</option>
                                    <option value="Finance">Finance & Banking</option>
                                    <option value="Manufacturing">Manufacturing & Industrial</option>
                                    <option value="Retail">Retail & E-commerce</option>
                                    <option value="Construction">Construction & Real Estate</option>
                                    <option value="Energy">Energy & Utilities</option>
                                    <option value="Media">Media & Entertainment</option>
                                    <option value="Transportation">Transportation & Logistics</option>
                                    <option value="Consumer Goods">Consumer Goods & Services</option>
                                    <option value="Hospitality">Hospitality & Tourism</option>
                                    <option value="Telecommunications">Telecommunications</option>
                                    <option value="Mining">Mining & Natural Resources</option>
                                    <option value="Professional">Professional Services</option>
                                    <option value="Government">Government & Public Sector</option>
                                    <option value="NGO">Non-Profit & NGO</option>
                                    <option value="Others">Others</option>
                                </select>
                                <label for="sector">Sector</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" name="number_of_employees" id="number_of_employees" placeholder=" " required>
                                <label for="number_of_employees">Number of Employees</label>
                            </div>
                        </div>
                    </div>

                    <!-- Primary Contact Section -->
                    <div class="row">
                        <div class="col-12">
                            <h4 class="bg-primary text-white p-2 rounded mt-3">Primary Contact</h4>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="primary_contact_name" id="primary_contact_name" placeholder=" " required>
                                <label for="primary_contact_name">Primary Contact Name</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="primary_contact_position" id="primary_contact_position" placeholder=" " required>
                                <label for="primary_contact_position">Position</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="tel" class="form-control" name="primary_contact_number" id="primary_contact_number" placeholder=" " required>
                                <label for="primary_contact_number">Contact Number</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" name="primary_contact_email" id="primary_contact_email" placeholder=" " required>
                                <label for="primary_contact_email">Contact Email</label>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="primary_contact_address" id="primary_contact_address" placeholder=" " required>
                                <label for="primary_contact_address">Address</label>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Management Section -->
                    <div class="row">
                        <div class="col-12">
                            <h4 class="bg-primary text-white p-2 rounded mt-3">Customer Management</h4>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="number" step="0.01" class="form-control" name="value" id="customer_value" placeholder=" " required>
                                <label for="customer_value">Customer Value ({{ CurrencyHelper::symbol() }})</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="customer_category" id="customer_category" required>
                                    <option value="">Select Customer Category</option>
                                    <option value="Standard">Standard</option>
                                    <option value="VIP">VIP</option>
                                    <option value="HVC">HVC</option>
                                </select>
                                <label for="customer_category">Customer Category</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="status" id="customer_status" required>
                                    <option value="">Select Customer Status</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Suspended">Suspended</option>
                                </select>
                                <label for="customer_status">Customer Status</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="source_of_acquisition" id="source_of_acquisition" required>
                                    <option value="">Select Source of Acquisition</option>
                                    <option value="Referral">Referral</option>
                                    <option value="Direct Marketing">Direct Marketing</option>
                                    <option value="Digital Marketing">Digital Marketing</option>
                                    <option value="Event">Event</option>
                                    <option value="Cold Call">Cold Call</option>
                                    <option value="Partner">Partner</option>
                                    <option value="Other">Other</option>
                                </select>
                                <label for="source_of_acquisition">Source of Acquisition</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="change_type" id="change_type" required>
                                    <option value="">Select Change Type</option>
                                    <option value="New">New</option>
                                    <option value="Renewal">Renewal</option>
                                    <option value="Upgrade">Upgrade</option>
                                    <option value="Downgrade">Downgrade</option>
                                </select>
                                <label for="change_type">Change Type</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="assigned_branch" id="assigned_branch" required>
                                    <option value="">Select Assigned Branch</option>
                                    <option value="Main">Main Branch</option>
                                    <option value="North">North Branch</option>
                                    <option value="South">South Branch</option>
                                    <option value="East">East Branch</option>
                                    <option value="West">West Branch</option>
                                </select>
                                <label for="assigned_branch">Assigned Branch</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="channel" id="channel" required>
                                    <option value="">Select Channel</option>
                                    <option value="Online">Online</option>
                                    <option value="Offline">Offline</option>
                                    <option value="Hybrid">Hybrid</option>
                                </select>
                                <label for="channel">Channel</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="company_group_code" id="company_group_code" placeholder=" " required>
                                <label for="company_group_code">Company Group Code</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="mode_of_communication" id="mode_of_communication" required>
                                    <option value="">Select Mode of Communication</option>
                                    <option value="Email">Email</option>
                                    <option value="Phone">Phone</option>
                                    <option value="SMS">SMS</option>
                                    <option value="WhatsApp">WhatsApp</option>
                                    <option value="Post">Post</option>
                                    <option value="Fax">Fax</option>
                                    <option value="Multiple">Multiple</option>
                                </select>
                                <label for="mode_of_communication">Mode of Communication</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitCustomerBtn">Add Corporate Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Individual Customer Modal -->
<div class="modal fade" id="addIndividualCustomerModal" tabindex="-1" aria-labelledby="addIndividualCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addIndividualCustomerModalLabel">New Individual Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addIndividualCustomerForm" action="{{ route('company.customers.store.individual') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Personal Details Section -->
                    <div class="row">
                        <div class="col-12">
                            <h4 class="bg-primary text-white p-2 rounded">Personal Details</h4>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-2">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="title" id="individual_title" required>
                                    <option value="">Select Title</option>
                                    <option value="Mr">Mr</option>
                                    <option value="Mrs">Mrs</option>
                                    <option value="Miss">Miss</option>
                                </select>
                                <label for="individual_title">Title</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="full_name" id="individual_full_name" placeholder=" " required>
                                <label for="individual_full_name">Full Name</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="gender" id="individual_gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                                <label for="individual_gender">Gender</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" name="date_of_birth" id="individual_dob" placeholder=" " required>
                                <label for="individual_dob">Date of Birth</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="marital_status" id="individual_marital_status" required>
                                    <option value="">Select Status</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Divorced">Divorced</option>
                                    <option value="Widowed">Widowed</option>
                                </select>
                                <label for="individual_marital_status">Marital Status</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="business_profession" id="individual_profession" placeholder=" " required>
                                <label for="individual_profession">Business/Profession/Industry</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input type="tel" class="form-control" name="primary_contact" id="individual_contact" placeholder=" " required>
                                <label for="individual_contact">Primary Contact No</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" name="email" id="individual_email" placeholder=" " required>
                                <label for="individual_email">Email Address</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="nearest_landmark" id="individual_landmark" placeholder=" " required>
                                <label for="individual_landmark">Nearest Landmark</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <textarea class="form-control" name="postal_address" id="individual_address" placeholder=" " required style="height: 100px;"></textarea>
                                <label for="individual_address">Postal Address</label>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Management Info Section -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h4 class="bg-primary text-white p-2 rounded">Customer Management Info</h4>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="customer_category" id="individual_category" required>
                                    <option value="">Select Category</option>
                                    <option value="Standard">Standard</option>
                                    <option value="VIP">VIP</option>
                                    <option value="HVC">HVC</option>
                                </select>
                                <label for="individual_category">Customer Category</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input type="number" step="0.01" class="form-control" name="value" id="individual_value" placeholder=" " required>
                                <label for="individual_value">Customer Value ({{ CurrencyHelper::symbol() }})</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="mode_of_communication" id="individual_communication" required>
                                    <option value="">Select Mode</option>
                                    <option value="Email">Email</option>
                                    <option value="Phone">Phone</option>
                                    <option value="SMS">SMS</option>
                                    <option value="WhatsApp">WhatsApp</option>
                                </select>
                                <label for="individual_communication">Mode of Communication</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="source_of_acquisition" id="individual_source" required>
                                    <option value="">Select Source</option>
                                    <option value="Direct">Direct</option>
                                    <option value="Referral">Referral</option>
                                    <option value="Marketing">Marketing</option>
                                    <option value="Online">Online</option>
                                    <option value="Other">Other</option>
                                </select>
                                <label for="individual_source">Source of Acquisition</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="change_type" id="individual_change_type" required>
                                    <option value="">Select Type</option>
                                    <option value="New">New</option>
                                    <option value="Existing">Existing</option>
                                    <option value="Transfer">Transfer</option>
                                </select>
                                <label for="individual_change_type">Change Type</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="assign_branch" id="individual_branch" required>
                                    <option value="">Select Branch</option>
                                    <option value="Main">Main Branch</option>
                                    <option value="North">North Branch</option>
                                    <option value="South">South Branch</option>
                                </select>
                                <label for="individual_branch">Assign Branch</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="channel" id="individual_channel" required>
                                    <option value="">Select Channel</option>
                                    <option value="Direct">Direct</option>
                                    <option value="Agent">Agent</option>
                                    <option value="Broker">Broker</option>
                                    <option value="Online">Online</option>
                                </select>
                                <label for="individual_channel">Channel</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="company_group_code" id="individual_group_code" placeholder=" " required>
                                <label for="individual_group_code">Company Group Code</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitIndividualCustomerBtn">Add Individual Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Search Filter Modal  but not search modal  -->
<div class="modal fade" id="searchFilterModal" tabindex="-1" aria-labelledby="searchFilterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="searchFilterModalLabel">Bulk Upload Customers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('company.customers.bulk-upload') }}" method="POST" enctype="multipart/form-data" id="bulkUploadForm">
                @csrf
                <input type="hidden" name="customer_type" value="corporate">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fa-solid fa-info-circle me-1"></i>
                        Download the template first and fill it with your user data. Make sure to follow the format exactly.
                    </div>
                    <div class="mb-3">
                        <label for="bulk_upload_file" class="form-label">Upload CSV File</label>
                        <input type="file" class="form-control" id="bulk_upload_file" name="file" required>
                    </div>
                    <a href="{{ route('company.customers.download-template', ['type' => 'corporate']) }}" class="btn btn-link">Download Template</a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>


              
          <div class="d-flex justify-content-end" style="display: none">
            <button type="button" class="btn btn-link" id="addContactBtn" style="display: none"><i class="fas fa-plus"></i> Add Contact</button>
          </div>
      <div class="modal-footer" style="display: none">
        <button type="button" class="btn btn-primary" id="saveContactsBtn">Save Contacts</button>
        
      </div>
</div>



@section('script')
    @parent
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Global flag to prevent multiple alerts
        window.isAlertShowing = false;
        let currentCustomerId = null;
        let isEditing = false;
        let currentEditId = null;

        // Set up CSRF token for all AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Function to open contacts modal
        async function openContactsModal(customerName, customerId) {
            try {
                // Set global variables
                currentCustomerId = customerId;
                isEditing = false;
                currentEditId = null;
                
                // Reset and prepare the form
                const form = document.getElementById('contactForm');
                form.reset();
                form.setAttribute('data-customer-id', customerId);
                
                // Update UI elements
                document.getElementById('formTitle').textContent = `Manage Contacts - ${customerName}`;
                document.getElementById('submitContactBtn').innerHTML = '<i class="fas fa-plus me-1"></i> Add Contact';
                document.getElementById('cancelEditBtn').style.display = 'none';
                
                // Load existing contacts in the table
                await loadContactsTable(customerId);
                
                // Show the modal
                const modal = new bootstrap.Modal(document.getElementById('contactsModal'));
                modal.show();
                
            } catch (error) {
                console.error('Error in openContactsModal:', error);
                showSafeSweetAlert({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load contacts. Please try again.',
                    timer: 3000
                });
            }
        }
        
        // Handle contact form submission
        document.getElementById('contactForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const form = e.target;
            const formData = new FormData(form);
            const data = {
                name: formData.get('name'),
                email: formData.get('email'),
                phone: formData.get('phone'),
                is_default: document.getElementById('isDefault').checked
            };
            
            try {
                let url = `/company/customers/${currentCustomerId}/contacts`;
                let method = 'POST';
                
                if (isEditing && currentEditId) {
                    url += `/${currentEditId}`;
                    method = 'PUT';
                }
                
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                
                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Failed to save contact');
                }
                
                // Refresh the contacts table
                await loadContactsTable(currentCustomerId);
                
                // Reset form
                form.reset();
                isEditing = false;
                currentEditId = null;
                document.getElementById('submitContactBtn').innerHTML = '<i class="fas fa-plus me-1"></i> Add Contact';
                document.getElementById('cancelEditBtn').style.display = 'none';
                
                showSafeSweetAlert({
                    icon: 'success',
                    title: 'Success',
                    text: isEditing ? 'Contact updated successfully' : 'Contact added successfully',
                    timer: 3000
                });
                
            } catch (error) {
                console.error('Error saving contact:', error);
                showSafeSweetAlert({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Failed to save contact. Please try again.',
                    timer: 3000
                });
            }
        });
        
        // Handle edit contact
        function handleEditContact(e) {
            const btn = e.currentTarget;
            const id = btn.dataset.id;
            const name = btn.dataset.name;
            const email = btn.dataset.email;
            const phone = btn.dataset.phone;
            const isDefault = btn.dataset.isDefault === 'true';
            
            // Set form values
            document.getElementById('contactId').value = id;
            document.getElementById('contactName').value = name;
            document.getElementById('contactEmail').value = email || '';
            document.getElementById('contactPhone').value = phone || '';
            document.getElementById('isDefault').checked = isDefault;
            
            // If setting as default, uncheck any other default contacts
            if (isDefault) {
                document.querySelectorAll('.edit-contact').forEach(editBtn => {
                    if (editBtn.dataset.id !== id) {
                        editBtn.dataset.isDefault = 'false';
                    }
                });
            }
            
            // Update UI
            isEditing = true;
            currentEditId = id;
            document.getElementById('formTitle').textContent = 'Edit Contact';
            document.getElementById('submitContactBtn').innerHTML = '<i class="fas fa-save me-1"></i> Update Contact';
            document.getElementById('cancelEditBtn').style.display = 'inline-block';
            
            // Scroll to form
            document.getElementById('contactForm').scrollIntoView({ behavior: 'smooth' });
        }
        
        // Handle delete contact
        async function handleDeleteContact(e) {
            const btn = e.currentTarget;
            const id = btn.dataset.id;
            
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: 'This will permanently delete this contact.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d'
            });
            
            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/company/customers/${currentCustomerId}/contacts/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Failed to delete contact');
                    }
                    
                    // Refresh the contacts table
                    await loadContactsTable(currentCustomerId);
                    
                    showSafeSweetAlert({
                        icon: 'success',
                        title: 'Success',
                        text: 'Contact deleted successfully',
                        timer: 3000
                    });
                    
                } catch (error) {
                    console.error('Error deleting contact:', error);
                    showSafeSweetAlert({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Failed to delete contact. Please try again.',
                        timer: 3000
                    });
                }
            }
        }
        
        // Handle cancel edit
        document.getElementById('cancelEditBtn').addEventListener('click', function() {
            const form = document.getElementById('contactForm');
            form.reset();
            isEditing = false;
            currentEditId = null;
            document.getElementById('formTitle').textContent = 'Add New Contact';
            document.getElementById('submitContactBtn').innerHTML = '<i class="fas fa-plus me-1"></i> Add Contact';
            this.style.display = 'none';
        });
        
        // Function to load contacts in the table (excluding soft-deleted ones)
        async function loadContactsTable(customerId) {
            try {
                const response = await fetch(`/company/customers/${customerId}/contacts?trashed=0`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Failed to load contacts');
                }
                
                const contacts = await response.json();
                const tbody = document.querySelector('#contactsTable tbody');
                tbody.innerHTML = '';
                
                if (contacts.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="fas fa-inbox fa-2x mb-2 text-muted"></i>
                                <p class="mb-0">No contacts found</p>
                            </td>
                        </tr>
                    `;
                    return;
                }
                
                contacts.forEach((contact, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${contact.name || 'N/A'}</td>
                        <td>${contact.email || '-'}</td>
                        <td>${contact.phone || '-'}</td>
                        <td class="text-center">
                            ${contact.is_default ? 
                              '<span class="badge bg-success">Yes</span>' : 
                              '<span class="badge bg-secondary">No</span>'}
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary edit-contact" 
                                    data-id="${contact.id}"
                                    data-name="${contact.name}"
                                    data-email="${contact.email || ''}"
                                    data-phone="${contact.phone || ''}"
                                    data-is-default="${contact.is_default}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-outline-danger delete-contact" 
                                    data-id="${contact.id}">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
                
                // Add event listeners for edit and delete buttons
                document.querySelectorAll('.edit-contact').forEach(btn => {
                    btn.addEventListener('click', handleEditContact);
                });
                
                document.querySelectorAll('.delete-contact').forEach(btn => {
                    btn.addEventListener('click', handleDeleteContact);
                });
                
            } catch (error) {
                console.error('Error loading contacts table:', error);
                throw error;
            }
        }

        // Function to add a new contact field
        document.getElementById('addContactBtn').addEventListener('click', function() {
            const container = document.getElementById('contactsContainer');
            const existingContacts = container.querySelectorAll('.contact-item');
            const newContactIndex = existingContacts.length;

            const newContactHtml = `
                <div class="contact-item mb-3 p-3 border rounded">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="contacts[${newContactIndex}][name]" id="contactName${newContactIndex}" placeholder=" ">
                                <label for="contactName${newContactIndex}">Contact Name</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" name="contacts[${newContactIndex}][email]" id="contactEmail${newContactIndex}" placeholder=" ">
                                <label for="contactEmail${newContactIndex}">Email</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="contacts[${newContactIndex}][phone]" id="contactPhone${newContactIndex}" placeholder=" ">
                                <label for="contactPhone${newContactIndex}">Phone</label>
                            </div>
                        </div>
                        <div class="col-md-1 d-flex align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="default_contact" id="defaultContact${newContactIndex}" value="${newContactIndex}">
                                <label class="form-check-label" for="defaultContact${newContactIndex}">Default</label>
                            </div>
                        </div>
                        <div class="col-md-1 d-flex align-items-center">
                            <button type="button" class="btn btn-danger btn-sm remove-contact" onclick="removeContact(this)">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', newContactHtml);
        });

        // Function to remove a contact field
        function removeContact(button) {
            const contactItem = button.closest('.contact-item');
            contactItem.remove();
        }

        // Save contacts form submission
        document.getElementById('saveContactsBtn').addEventListener('click', function() {
            const form = document.getElementById('contactsForm');
            const customerId = form.getAttribute('data-customer-id');
            
            if (!customerId) {
                console.error('Customer ID not found');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Customer ID not found. Please try again.'
                });
                return;
            }

            // Get all contact fields
            const contacts = [];
            const contactItems = document.querySelectorAll('.contact-item');
            
            contactItems.forEach((item, index) => {
                // Get the name field - it might be either contacts[index][name] or contacts[new][name]
                const nameField = item.querySelector('input[name^="contacts["]');
                const emailField = item.querySelector('input[type="email"]');
                const phoneField = item.querySelector('input[type="tel"]');
                const defaultField = item.querySelector('input[type="checkbox"]');

                if (nameField) {
                    const name = nameField.value.trim();
                    const email = emailField ? emailField.value.trim() : '';
                    const phone = phoneField ? phoneField.value.trim() : '';
                    const isDefault = defaultField ? defaultField.checked : false;

                    if (name) { // Only add if name has data
                        contacts.push({
                            name: name,
                            email: email,
                            phone: phone,
                            is_default: isDefault
                        });
                    }
                }
            });

            // Send AJAX request to save contacts
            fetch(`/company/customers/${customerId}/contacts`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    customer_id: customerId,
                    contacts: contacts
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('contactsModal'));
                    modal.hide();
                    
                    // Reset form
                    document.getElementById('contactsForm').reset();
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Contacts saved successfully!',
                        timer: 2000
                    });
                } else {
                    throw new Error(data.message || 'Failed to save contacts');
                }
            })
            .catch(error => {
                console.error('Error saving contacts:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to save contacts. Please try again.'
                });
            });
        });

        // Page change function - defined globally
        function changePageCustomer(event, page) {
            event.preventDefault();
            fetchCustomerData(page);
            return false;
        }

    // Fetch customer data function - defined globally
    function fetchCustomerData(page = 1) {
    $.ajax({
        url: `/company/customers/all?page=${page}`, 
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log(response);
            let tableBody = '';
            
            if (response.data.length === 0) {
                tableBody = `<tr>
                    <td colspan="11" class="text-center">No customers found</td>
                </tr>`;
            } else {
                response.data.forEach((customer, index) => {
                    // Determine badge classes
                    let typeBadgeClass = customer.customer_type === 'corporate' ? 'bg-primary' : 'bg-secondary';
                    let statusBadgeClass = customer.status === 'Active' ? 'bg-success' : 
                                          (customer.status === 'Inactive' ? 'bg-danger' : 
                                          (customer.status === 'Suspended' ? 'bg-warning' : 'bg-info'));
                    
                    // Get contact information
                    let contact = customer.phone || customer.primary_contact_number || 'N/A';
                    let email = customer.email || customer.corporate_email || 'N/A';
                    let address = customer.address || customer.headquarters_address || customer.postal_address || 'N/A';
                    
                    tableBody += `<tr>
                        <td>${index + 1}</td>
                        <td>${customer.name}</td>
                        <td class="text-center">
                            <span class="badge ${typeBadgeClass}">
                                ${customer.customer_type.charAt(0).toUpperCase() + customer.customer_type.slice(1)}
                            </span>
                        </td>
                        <td>${customer.customer_category}</td>
                        <td>${contact}</td>
                        <td>${email}</td>
                        <td>${address}</td>
                        <td>${customer.source_of_acquisition}</td>
                        <td>
                            <span class="badge ${statusBadgeClass}">
                                ${customer.status}
                            </span>
                        </td>
                        <td>${customer.value}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center gap-2 customer-actions">
                                <a href="/company/customers/${customer.id}" 
                                   class="btn btn-sm btn-info" 
                                   title="View Customer">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/company/customers/${customer.id}/edit" 
                                   class="btn btn-sm btn-warning" 
                                   title="Edit Customer">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" 
                                   class="btn btn-sm btn-primary" 
                                   title="Contacts"
                                   onclick="event.preventDefault(); openContactsModal('${customer.name}', ${customer.id})">
                                    <i class="fas fa-address-book"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-danger delete-customer" 
                                        data-id="${customer.id}"
                                        data-name="${customer.name}"
                                        title="Delete Customer"
                                        onclick="handleDeleteCustomer(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>`;
                });
            }

            $('#customerTable tbody').html(tableBody);

            // Generate Pagination
            let paginationHtml = '';
            
            // if (response.pagination.last_page > 1) {
                paginationHtml = `<li class="page-item ${response.pagination.prev_page_url ? '' : 'disabled'}">
                    <a class="page-link" href="#" onclick="changePageCustomer(event, ${response.pagination.current_page - 1})">Previous</a>
                </li>`;

                for (let i = 1; i <= response.pagination.last_page; i++) {
                    paginationHtml += `<li class="page-item ${i === response.pagination.current_page ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="changePageCustomer(event, ${i})">${i}</a>
                    </li>`;
                }

                paginationHtml += `<li class="page-item ${response.pagination.next_page_url ? '' : 'disabled'}">
                    <a class="page-link" href="#" onclick="changePageCustomer(event, ${response.pagination.current_page + 1})">Next</a>
                </li>`;
            // }

            $('#customersspagination').html(paginationHtml);
            
            // Initialize tooltips for action buttons
            $('[title]').tooltip();
        },
        error: function(xhr, status, error) {
            console.error("Error:", status, error, xhr.responseText);
            // Show error message to user
            alert('Failed to load customers data. Please try again.');
        }
    });
}

        // Utility function to show SweetAlert safely
        function showSafeSweetAlert(options) {
            if (!window.isAlertShowing) {
                window.isAlertShowing = true;
                return Swal.fire(options).finally(() => {
                    window.isAlertShowing = false;
                });
            }
            return Promise.resolve();
        }

        // Consolidated search function
        function performCustomerSearch() {
            const searchBtn = document.getElementById('performCustomerSearch');
            const searchResultsContainer = document.getElementById('searchResultsContainer');
            const addCustomerButtonsRow = document.getElementById('addCustomerButtonsRow');
            const searchTerm = document.getElementById('customerSearchInput').value.trim();

            // Validate search input
            if (!searchTerm) {
                showSafeSweetAlert({
                    icon: 'warning',
                    title: 'Invalid Search',
                    text: 'Please enter a search term.'
                });
                return;
            }

            // Disable search button and show loading
            searchBtn.disabled = true;
            searchBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Searching...';

            // Clear previous results
            searchResultsContainer.innerHTML = '';
            
            // Always show add customer buttons when search is performed
            addCustomerButtonsRow.classList.remove('d-none');

            // Perform AJAX search
            const url = new URL('{{ route('company.customers.search') }}');
            url.searchParams.append('customer_search', searchTerm);

            fetch(url.toString(), {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.customers && data.customers.length > 0) {
                    const filterHtml = `
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control" id="resultFilterInput" placeholder="Filter results">
                            <label for="resultFilterInput">Filter Results</label>
                        </div>`;
                    const resultsHtml = `
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Category</th>
                                        <th>Contact</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Source</th>
                                        <th>Status</th>
                                        <th>Value ({{ CurrencyHelper::symbol() }})</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${data.customers.map(customer => `
                                        <tr>
                                            <td>${data.customers.indexOf(customer) + 1}</td>
                                            <td>${customer.name}</td>
                                            <td class="text-center">
                                                <span class="badge bg-${customer.customer_type === 'corporate' ? 'primary' : 'secondary'}">
                                                    ${customer.customer_type === 'corporate' ? 'Corporate' : 'Individual'}
                                                </span>
                                            </td>
                                            <td class="text-center">${customer.customer_category || 'N/A'}</td>
                                            <td class="text-center">${customer.phone || customer.primary_contact_number || 'N/A'}</td>
                                            <td>${customer.email || customer.corporate_email || 'N/A'}</td>
                                            <td>${customer.address || customer.headquarters_address || customer.postal_address || 'N/A'}</td>
                                            <td class="text-center">${customer.source_of_acquisition}</td>
                                            <td>
                                                <span class="badge bg-${customer.status === 'Active' ? 'success' : 
                                                    (customer.status === 'Inactive' ? 'danger' : 
                                                    (customer.status === 'Suspended' ? 'warning' : 'info')) }">
                                                    ${customer.status}
                                                </span>
                                            </td>
                                            <td>${customer.value}</td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="/company/customers/${customer.id}" 
                                                       class="btn btn-sm btn-info me-1" 
                                                       title="View Customer">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="/company/customers/${customer.id}/edit" 
                                                       class="btn btn-sm btn-warning" 
                                                       title="Edit Customer">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="#" 
                                                       class="btn btn-sm btn-primary" 
                                                       title="Contacts"
                                                       onclick="event.preventDefault(); openContactsModal('${customer.name}', ${customer.id})">
                                                        <i class="fas fa-address-book"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    `;
                    // Render filter input + results table
                    searchResultsContainer.innerHTML = filterHtml + resultsHtml;
                    // Attach filter logic
                    document.getElementById('resultFilterInput').addEventListener('input', function() {
                        const filterVal = this.value.toLowerCase();
                        document.querySelectorAll('#searchResultsContainer table tbody tr').forEach(row => {
                            row.style.display = Array.from(row.cells).some(td => td.textContent.toLowerCase().includes(filterVal)) ? '' : 'none';
                        });
                    });
                } else {
                    // No results found
                    searchResultsContainer.innerHTML = `
                        <div class="alert alert-info text-center" role="alert">
                            No customers found matching your search.
                        </div>
                    `;
                }
            })
            .catch(error => {
                // Show error message
                searchResultsContainer.innerHTML = `
                    <div class="alert alert-danger text-center" role="alert">
                        ${error.message || 'An error occurred while searching for customers.'}
                    </div>
                `;
                
                showSafeSweetAlert({
                    icon: 'error',
                    title: 'Search Error',
                    text: error.message || 'An error occurred while searching for customers.',
                    showConfirmButton: true
                });
            })
            .finally(() => {
                // Re-enable search button
                searchBtn.disabled = false;
                searchBtn.innerHTML = 'Search';
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initial Call
            fetchCustomerData();

            // Handle form submissions
            document.getElementById('addCustomerForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                showSafeSweetAlert({
                    title: 'Creating Customer...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
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
                        showSafeSweetAlert({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            showConfirmButton: true
                        }).then(() => {
                            // Close modal and refresh data
                            const modal = bootstrap.Modal.getInstance(document.getElementById('addCustomerModal'));
                            modal.hide();
                            // Redirect if redirect_url is provided
                            if (data.redirect_url) {
                                window.location.href = data.redirect_url;
                            } else {
                                fetchCustomerData();
                                updateStats();
                            }
                        });
                    } else if (data.errors) {
                        showSafeSweetAlert({
                            icon: 'error',
                            title: 'Validation Error!',
                            text: Object.values(data.errors).flat().join('\n'),
                            showConfirmButton: true
                        });
                    } else {
                        showSafeSweetAlert({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'Failed to create customer',
                            showConfirmButton: true
                        });
                    }
                })
                .catch(error => {
                    showSafeSweetAlert({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An unexpected error occurred. Please try again.',
                        showConfirmButton: true
                    });
                    console.error('Error:', error);
                });
            });

            document.getElementById('addIndividualCustomerForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                showSafeSweetAlert({
                    title: 'Creating Individual Customer...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
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
                        showSafeSweetAlert({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            showConfirmButton: true
                        }).then(() => {
                            // Close modal and refresh data
                            const modal = bootstrap.Modal.getInstance(document.getElementById('addIndividualCustomerModal'));
                            modal.hide();
                            // Redirect if redirect_url is provided
                            if (data.redirect_url) {
                                window.location.href = data.redirect_url;
                            } else {
                                fetchCustomerData();
                                updateStats();
                            }
                        });
                    } else if (data.errors) {
                        showSafeSweetAlert({
                            icon: 'error',
                            title: 'Validation Error!',
                            text: Object.values(data.errors).flat().join('\n'),
                            showConfirmButton: true
                        });
                    } else {
                        showSafeSweetAlert({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'Failed to create customer',
                            showConfirmButton: true
                        });
                    }
                })
                .catch(error => {
                    showSafeSweetAlert({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An unexpected error occurred. Please try again.',
                        showConfirmButton: true
                    });
                    console.error('Error:', error);
                });
            });

            // Attach search event to button click
            document.getElementById('performCustomerSearch').addEventListener('click', function(e) {
                e.preventDefault();
                performCustomerSearch();
            });

            // Allow search on pressing Enter in the input
            document.getElementById('customerSearchInput').addEventListener('keypress', function(e) {
                if (e.which === 13) { // Enter key
                    e.preventDefault();
                    performCustomerSearch();
                }
            });
        });

        // Handle delete customer
        function handleDeleteCustomer(button) {
            const customerId = button.dataset.id;
            const customerName = button.dataset.name;
            
            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete customer "${customerName}". This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/company/customers/${customerId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: data.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                button.closest('tr').remove();
                                // If no rows left, show the empty message
                                if (document.querySelectorAll('#customerTable tbody tr').length === 0) {
                                    document.querySelector('#customerTable tbody').innerHTML = '<tr><td colspan="11" class="text-center">No customers found</td></tr>';
                                }
                                // Update stats after row is removed
                                updateStats();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message || 'Failed to delete customer'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: error.message || 'An error occurred while deleting the customer'
                        });
                    });
                }
            });
        }

        // Function to update stats after deletion
        function updateStats() {
            const totalCustomers = document.querySelectorAll('#customerTable tbody tr').length;
            const inactiveCustomers = document.querySelectorAll('#customerTable tbody tr .badge-danger').length;
            const totalValue = Array.from(document.querySelectorAll('#customerTable tbody tr')).reduce((sum, row) => {
                const valueText = row.querySelector('td:nth-child(9)').textContent.replace('$', '').replace(',', '');
                return sum + (parseFloat(valueText) || 0);
            }, 0);
            const prevTotalValue = parseFloat(document.querySelector('.stat-card.sales .stat-value').textContent.replace('$', '').replace(',', '')) || 0;

            // Update total customers
            document.querySelector('.stat-card.sales .stat-value').textContent = totalCustomers;
            
            // Update inactive customers
            document.querySelector('.stat-card.pipeline .stat-value').textContent = inactiveCustomers;
            
            // Update percentages
            const inactivePercentage = totalCustomers > 0 ? (inactiveCustomers / totalCustomers * 100).toFixed(1) : 0;
            document.querySelector('.stat-card.pipeline .stat-change span').textContent = `${inactivePercentage}%`;
            
            const valueChange = prevTotalValue > 0 ? ((totalValue / prevTotalValue) * 100 - 100).toFixed(1) : 0;
            document.querySelector('.stat-card.sales .stat-change span').textContent = `${valueChange}% vs last month`;
        }

        // Handle view customer button clicks
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('view-customer')) {
                const customerId = e.target.closest('tr').querySelector('td').textContent;
                window.location.href = `/company/customers/${customerId}`;
            }
        });

        // Activity search functionality
        $('#activitySearch').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('.timeline').each(function() {
                const $timeline = $(this);
                const description = $timeline.find('.title').text().toLowerCase();
                const company = $timeline.find('.description').text().toLowerCase();
                
                if (description.includes(searchTerm) || company.includes(searchTerm)) {
                    $timeline.show();
                } else {
                    $timeline.hide();
                }
            });
        });

        // Make CurrencyHelper available globally
        window.CurrencyHelper = {
            format: function(amount) {
                return '{{ CurrencyHelper::symbol() }}' + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }
    };
    </script>
@endsection