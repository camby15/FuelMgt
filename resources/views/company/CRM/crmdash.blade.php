@extends('layouts.vertical', ['page_title' => 'CRM Dashboard'])
@section('css')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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

        /* Dashboard specific styles */
        .stat-card {
            border-radius: 15px;
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        .chart-container {
            height: 300px;
        }
        .activity-timeline {
            position: relative;
            padding-left: 30px;
        }
        .activity-timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 2px;
            background: var(--bs-border-color);
        }
        .activity-item {
            position: relative;
            padding-bottom: 1.5rem;
        }
        .activity-item::before {
            content: '';
            position: absolute;
            left: -34px;
            top: 0;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--bs-primary);
        }
        /* Enhanced Kanban Board Styles */
        .kanban-board {
            display: flex;
            gap: 1.5rem;
            overflow-x: auto;
            padding: 1.5rem;
            min-height: 500px;
            scrollbar-width: thin;
            scrollbar-color: var(--bs-primary) var(--bs-light);
        }
        .kanban-board::-webkit-scrollbar {
            height: 8px;
        }
        .kanban-board::-webkit-scrollbar-track {
            background: var(--bs-light);
            border-radius: 4px;
        }
        .kanban-board::-webkit-scrollbar-thumb {
            background: var(--bs-primary);
            border-radius: 4px;
        }
        .kanban-column {
            min-width: 320px;
            background: var(--bs-light);
            border-radius: 10px;
            padding: 1rem;
            display: flex;
            flex-direction: column;
        }
        [data-bs-theme="dark"] .kanban-column {
            background: var(--bs-dark);
            border: 1px solid var(--bs-border-color);
        }
        .kanban-column-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--bs-border-color);
        }
        .kanban-column-header h5 {
            margin: 0;
            font-weight: 600;
        }
        .column-badge {
            background: var(--bs-primary);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.875rem;
        }
        .task-cards-container {
            flex-grow: 1;
            min-height: 200px;
        }
        .task-card {
            background: var(--bs-body-bg);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            cursor: pointer;
            border: 1px solid var(--bs-border-color);
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .task-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .task-card.dragging {
            opacity: 0.5;
            transform: scale(0.95);
        }
        .task-card h6 {
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        .task-card-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 0.75rem;
            font-size: 0.875rem;
        }
        .task-card-owner {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .task-card-owner img {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            object-fit: cover;
        }
        .add-deal-button {
            width: 100%;
            border: 2px dashed var(--bs-border-color);
            background: transparent;
            border-radius: 8px;
            padding: 0.75rem;
            color: var(--bs-primary);
            margin-top: 0.5rem;
            transition: all 0.3s ease;
        }
        .add-deal-button:hover {
            background: var(--bs-primary);
            color: white;
            border-style: solid;
        }
        .lead-sources-chart {
            height: 400px;
        }
        .sales-performance-card {
            min-height: 490px;
        }
        .sales-performance-card .card-body {
            min-height: 400px;
            display: flex;
            flex-direction: column;
        }
        .sales-performance-card .chart-container {
            flex: 1;
            min-height: 220px;
        }
        .no-data-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #6c757d;
        }
        .no-data-placeholder i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

/* Subtle row background and hover for Recent Leads */
.table-hover > tbody > tr.bg-light {
    background-color: #f4f6f8 !important; /* softer gray */
}
.table-hover > tbody > tr.table-success {
    background-color: #e6f4ea !important; /* gentle green for new leads */
}
.table-hover > tbody > tr:hover,
.table-hover > tbody > tr.bg-light:hover,
.table-hover > tbody > tr.table-success:hover {
    background-color: #e9ecef !important; /* subtle blue-gray on hover */
    transition: background 0.2s;
}
.table thead.sticky-top {
    background: #f8fafc !important;
}  

#currency{
    border: 1px solid #2F2F2F;
    color: #2FF2F2F;
}

</style>

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mb-3 align-items-center">
            <div class="col">
                <div class="page-title-box">
                    <h4 class="page-title">CRM Dashboard</h4>
                </div>
            </div>
            <div class="col-auto" style="margin-top: 1%">
                <form method="POST" action="{{ route('setCurrency') }}" class="d-flex align-items-center mb-0">
                    @csrf
                    <div class="form-floating w-auto">

                        <select class="form-select" name="currency" id="currency" onchange="this.form.submit()" aria-label="Currency">
                            @foreach(\App\Helpers\CurrencyHelper::$symbols as $code => $symbol)
                                <option value="{{ $code }}" {{ session('currency','USD') == $code ? 'selected' : '' }}>
                                    {{ $code }} ({{ $symbol }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Total Leads</h6>
                                @php
                                    $totalLeads = \App\Models\CrmLeads::where('company_id', session('selected_company_id'))->count();
                                    $previousMonthLeadsCount = \App\Models\CrmLeads::where('company_id', session('selected_company_id'))
                                        ->whereMonth('created_at', now()->subMonth()->month)
                                        ->count();
                                    $leadsPercentageChange = number_format((($totalLeads / max(1, $previousMonthLeadsCount)) * 100) - 100, 1);
                                @endphp
                                <h3 class="mb-0">
                                    @if($totalLeads > 0)
                                        {{ $totalLeads }}
                                    @else
                                       <span> No Data</span>
                                    @endif
                                </h3>
                                @if($totalLeads == 0 && $previousMonthLeadsCount == 0)
                                    <span class="badge bg-secondary" id="">No Data</span>
                                @else
                                    <span class="badge bg-success">{{ $leadsPercentageChange }}% <i class="fas fa-arrow-up"></i></span>
                                @endif
                            </div>
                            <div class="stat-icon text-primary">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Deals Won</h6>
                                @php
                                    $totalDeals = \App\Models\Opportunity::where('company_id', session('selected_company_id'))->count();
                                    $previousMonthDealsCount = \App\Models\Opportunity::where('company_id', session('selected_company_id'))
                                        ->whereMonth('created_at', now()->subMonth()->month)
                                        ->count();
                                    $dealsPercentageChange = number_format((($totalDeals / max(1, $previousMonthDealsCount)) * 100) - 100, 1);
                                @endphp
                                <h3 class="mb-0">
                                    @if($totalDeals > 0)
                                        {{ $totalDeals }}
                                    @else
                                        No Data
                                    @endif
                                </h3>
                                @if($totalDeals == 0 && $previousMonthDealsCount == 0)
                                    <span class="badge bg-secondary">No Data</span>
                                @else
                                    <span class="badge bg-success">{{ $dealsPercentageChange }}% <i class="fas fa-arrow-up"></i></span>
                                @endif
                            </div>
                            <div class="stat-icon text-success">
                                <i class="fas fa-crown"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Total Sales</h6>
                                @php
                                    $totalRevenue = \App\Models\crm_sales::where('company_id', session('selected_company_id'))
                                        ->where('deleted', 0)
                                        ->sum('deal_value');
                                    $previousMonthRevenue = \App\Models\crm_sales::where('company_id', session('selected_company_id'))
                                        ->where('deleted', 0)
                                        ->whereMonth('created_at', now()->subMonth()->month)
                                        ->sum('deal_value');
                                    $revenuePercentageChange = number_format((($totalRevenue / max(1, $previousMonthRevenue)) * 100) - 100, 1);
                                @endphp
                                <h3 class="mb-0">
                                    @if($totalRevenue > 0)
                                    {{ \App\Helpers\CurrencyHelper::format($totalRevenue) }}
                                    @else
                                        No Data
                                    @endif
                                </h3>
                                @if($totalRevenue == 0 && $previousMonthRevenue == 0)
                                    <span class="badge bg-secondary">No Data</span>
                                @else
                                    <span class="badge bg-success">{{ $revenuePercentageChange }}% <i class="fas fa-arrow-up"></i></span>
                                @endif
                            </div>
                            <div class="stat-icon text-primary">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Total Customers</h6>
                                @php
                                    $totalCustomers = \App\Models\Customer::where('company_id', session('selected_company_id'))->count();
                                    $previousMonthCount = \App\Models\Customer::where('company_id', session('selected_company_id'))
                                        ->whereMonth('created_at', now()->subMonth()->month)
                                        ->count();
                                    $percentageChange = number_format((($totalCustomers / max(1, $previousMonthCount)) * 100) - 100, 1);
                                @endphp
                                <h3 class="mb-0">
                                    @if($totalCustomers > 0)
                                        {{ $totalCustomers }}
                                    @else
                                        No Data
                                    @endif
                                </h3>
                                @if($totalCustomers == 0 && $previousMonthCount == 0)
                                    <span class="badge bg-secondary">No Data</span>
                                @else
                                    <span class="badge bg-success">{{ $percentageChange }}% <i class="fas fa-arrow-up"></i></span>
                                @endif
                            </div>
                            <div class="stat-icon text-info">
                                <i class="fas fa-smile"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <div class="col-xl-8">
                <div class="card sales-performance-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="header-title mb-2">Sales Performance</h4>
                                <div class="d-flex align-items-center">
                                    @php
                                        $totalRevenue = (float) \App\Models\crm_sales::where('company_id', session('selected_company_id'))
                                            ->where('deleted', 0)
                                            ->sum('deal_value');
                                        $totalCommission = (float) \App\Models\crm_sales::where('company_id', session('selected_company_id'))
                                            ->where('deleted', 0)
                                            ->sum('sales_commission');
                                        $totalDeals = (int) \App\Models\crm_sales::where('company_id', session('selected_company_id'))
                                            ->where('deleted', 0)
                                            ->count();
                                        $activeDeals = (int) \App\Models\crm_sales::where('company_id', session('selected_company_id'))
                                            ->where('stage', '!=', 'Closed Won')
                                            ->where('stage', '!=', 'Closed Lost')
                                            ->where('deleted', 0)
                                            ->count();
                                        $averageDealValue = $totalDeals > 0 ? $totalRevenue / $totalDeals : 0;
                                    @endphp
                                    <h2 class="mb-0">{{ \App\Helpers\CurrencyHelper::format($totalRevenue) }}</h2>&nbsp;
                                    <span class="badge bg-success">Total Revenue</span>
                                </div>
                            </div>
                        </div>

                        @if($totalDeals > 0)
                            <div class="row g-0 text-center mb-4">
                                <div class="col-4">
                                    <div class="p-3">
                                        <h5 class="mb-1">{{ \App\Helpers\CurrencyHelper::format($totalCommission) }}</h5>
                                        <p class="text-muted mb-0">
                                            <i class="fas fa-arrow-up text-success me-1"></i>
                                            Total Commission
                                        </p>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3">
                                        <h5 class="mb-1">{{ \App\Helpers\CurrencyHelper::format($averageDealValue) }}</h5>
                                        <p class="text-muted mb-0">
                                            <i class="fas fa-arrow-up text-success me-1"></i>
                                            Avg. Deal Value
                                        </p>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3">
                                        <h5 class="mb-0">{{ $activeDeals }}</h5>
                                        <p class="text-muted mb-0">
                                            <i class="fas fa-arrow-up text-success me-1"></i>
                                            Active Deals
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="chart-container">
                                @php
                                    $salesData = \App\Models\crm_sales::where('company_id', session('selected_company_id'))
                                        ->where('deleted', 0)
                                        ->select(
                                            \DB::raw('MONTH(created_at) as month'),
                                            \DB::raw('SUM(deal_value) as total')
                                        )
                                        ->groupBy('month')
                                        ->orderBy('month')
                                        ->get();

                                    $monthlyRevenue = array_fill(1, 12, 0);

                                    foreach ($salesData as $data) {
                                        $monthlyRevenue[$data->month] = $data->total;
                                    }
                                @endphp
                                <canvas id="revenueChart"></canvas>
                            </div>
                        @else
                            <div class="no-data-placeholder">
                                <i class="fas fa-chart-line text-muted"></i>
                                <h5>No Sales Data Available</h5>
                                <p class="text-muted">Start closing deals to see performance metrics</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

       <!-- Lead Sources Chart Section -->
<div class="col-xl-4">
    <div class="card">
        <div class="card-body">
            <h4 class="header-title mb-3">Lead Sources</h4>
            <div class="chart-container lead-sources-chart">
                @php
                    // Debugging: Log the leads count
                    $totalLeadsCount = \App\Models\CrmLeads::where('company_id', session('selected_company_id'))
                        ->whereNull('deleted_at')
                        ->count();

                    // Get lead sources with counts
                    $leadSources = \App\Models\CrmLeads::select(
                            \DB::raw('COALESCE(source, "Unknown") as source'),
                            \DB::raw('count(*) as count')
                        )
                        ->where('company_id', session('selected_company_id'))
                        ->whereNull('deleted_at')
                        ->groupBy('source')
                        ->get()
                        ->map(function ($item) {
                            return [
                                'source' => $item->source,
                                'count' => $item->count
                            ];
                        });

                    // If no leads at all, create a dummy entry
                    if($leadSources->isEmpty()) {
                        $leadSources = collect([['source' => 'No Leads', 'count' => 1]]);
                    }

                    // Debug output (remove in production)
                    // dump($leadSources);
                @endphp

                @if($totalLeadsCount > 0)
                    <canvas id="leadSourceChart"></canvas>
                @else
                    <div class="no-data-placeholder">
                        <i class="fas fa-chart-pie text-muted"></i>
                        <h5>No Leads Data Available</h5>
                        <p class="text-muted">Start adding leads to see sources distribution</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

      <!-- Leads Pipeline -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="header-title">Leads Pipeline</h4>
                    <div>
                    </div>
                </div>
                @php
    $leads = \App\Models\CrmLeads::where('company_id', session('selected_company_id'))
        ->with('assignedTo')
        ->whereNull('deleted_at')
        ->orderBy('created_at', 'desc')
        ->get();
@endphp

                @if($leads->count() > 0)
                    <div class="row g-4" style="max-height: 400px; overflow-y: auto;">
                        @foreach($leads as $lead)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm lead-card"
                                style="background-color: {{
                                    match($lead->status) {
                                        'New' => '#e8f0fe',
                                        'Qualified' => '#e3f2fd',
                                        'Proposal' => '#fff3e0',
                                        'Negotiation' => '#fce4ec',
                                        'Closed Won' => '#e8f5e9',
                                        default => '#f8f9fa'
                                    }
                                }};
                                border-left: 4px solid {{
                                    match($lead->status) {
                                        'New' => '#64b5f6',
                                        'Qualified' => '#2196f3',
                                        'Proposal' => '#ff9800',
                                        'Negotiation' => '#e53935',
                                        'Closed Won' => '#4caf50',
                                        default => '#6c757d'
                                    }
                                }};
                                transition: all 0.3s ease; margin-bottom: 1rem;"
                                onmouseover="this.style.transform='scale(1.015)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.18)';"
                                onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.09)';">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-primary text-white rounded-circle me-2" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <h5 class="card-title mb-0">
                                            <span class="badge bg-{{ $lead->status === 'Closed Won' ? 'success' : ($lead->status === 'Negotiation' ? 'danger' : ($lead->status === 'Proposal' ? 'warning' : 'info')) }} me-2">
                                                {{ $lead->status }}
                                            </span>
                                            {{ $lead->name }}
                                            @if($lead->hasAppointment() && $lead->appointment_date->isToday())
                                                <span class="badge bg-danger ms-2">Today</span>
                                            @elseif($lead->hasAppointment() && $lead->appointment_date->isPast())
                                                <span class="badge bg-warning text-dark ms-2">Overdue</span>
                                            @endif
                                        </h5>
                                    </div>

                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-column mb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-phone me-2 text-muted"></i>
                                            <span class="text-truncate" style="max-width: 200px;">{{ $lead->phone }}</span>
                                        </div>
                                        <div class="progress mb-2" style="height: 7px;">
                                            <div class="progress-bar bg-{{ $lead->status === 'Closed Won' ? 'success' : ($lead->status === 'Negotiation' ? 'danger' : ($lead->status === 'Proposal' ? 'warning' : 'info')) }}" role="progressbar" style="width: {{
                                                match($lead->status) {
                                                    'New' => '10%',
                                                    'Qualified' => '35%',
                                                    'Proposal' => '60%',
                                                    'Negotiation' => '85%',
                                                    'Closed Won' => '100%',
                                                    default => '10%'
                                                }
                                            }};" aria-valuenow="{{
                                                match($lead->status) {
                                                    'New' => '10',
                                                    'Qualified' => '35',
                                                    'Proposal' => '60',
                                                    'Negotiation' => '85',
                                                    'Closed Won' => '100',
                                                    default => '10'
                                                }
                                            }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    @if($lead->hasAppointment())
                                    <div class="appointment-info mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-calendar me-2 text-muted"></i>
                                            <span>{{ $lead->appointment_date->format('M d') }}</span>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="lead-notes">
                                        <p class="mb-0 text-truncate" style="max-width: 300px;">
                                            {{ Str::limit($lead->notes ?? 'No notes provided', 50) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-top">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            Created: {{ $lead->created_at->format('M d, Y') }}
                                        </small>
                                        <small class="text-muted">
                                            Source: <span class="badge bg-light text-dark">{{ $lead->source }}</span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="no-data-placeholder py-5">
                        <i class="fas fa-users text-muted" style="font-size: 3rem;"></i>
                        <h5>No Leads Available</h5>
                        <p class="text-muted">Start adding leads to see them in your pipeline</p>

                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Leads Section -->
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3">Recent Leads</h4>
                @if($leads->count() > 0)
                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-hover align-middle">
                            <thead class="bg-primary text-white sticky-top" style="z-index:1;">
                                <tr>
                                    <th class="col-2">Name</th>
                                    <th class="col-2">Contact</th>
                                    <th class="col-2">Status</th>
                                    <th class="col-3">Notes</th>
                                    <th class="col-1">Created</th>
                                    <th class="col-2">Source</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leads as $lead)
                                <tr class="{{ $loop->even ? 'bg-light' : '' }} {{ $lead->created_at->gt(now()->subDay()) ? 'table-success' : '' }} clickable-row" style="cursor:pointer;" data-lead-id="{{ $lead->id }}">
                                    <td class="col-2">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-primary text-white rounded-circle me-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 0.95rem;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <h6 class="mb-0" style="color: #{{ substr(md5($lead->name), 0, 6) }};">{{ $lead->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="col-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-phone text-success me-2"></i>
                                            <span class="text-truncate" style="max-width: 120px; color: #495057;">{{ $lead->phone }}</span>
                                        </div>
                                    </td>
                                    <td class="col-2">
                                        <span class="badge bg-{{ $lead->status === 'Closed Won' ? 'success' : ($lead->status === 'Negotiation' ? 'danger' : ($lead->status === 'Proposal' ? 'warning' : 'info')) }}">{{ $lead->status }}</span>
                                    </td>
                                    <td class="col-3">
                                        <p class="mb-0 text-truncate" style="max-width: 220px; color: #495057;">
                                            {{ Str::limit($lead->notes ?? 'No notes provided', 80) }}
                                        </p>
                                    </td>
                                    <td class="col-1">
                                        <small class="text-muted" style="color: #868e96;">
                                            {{ $lead->created_at->format('M d, Y') }}
                                        </small>
                                    </td>
                                    <td class="col-2">
                                        <small class="text-muted" style="color: #868e96;">
                                            <span class="badge bg-light text-dark">{{ $lead->source }}</span>
                                        </small>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="no-data-placeholder py-5">
                            <i class="fas fa-table text-muted" style="font-size: 3rem;"></i>
                            <h5>No Recent Leads</h5>
                            <p class="text-muted">When you add leads, they will appear here</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
@endsection

@section('script')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Currency formatting function for JavaScript
        function formatCurrency(amount) {
            const symbol = '{{ \App\Helpers\CurrencyHelper::symbol() }}';
            const rate = {{ \App\Helpers\CurrencyHelper::$rates[session('currency', 'USD')] ?? 1 }};
            const converted = amount * rate;
            return symbol + converted.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Revenue Chart if it exists
            const revenueChartEl = document.getElementById('revenueChart');
            if (revenueChartEl) {
                const revenueCtx = revenueChartEl.getContext('2d');
                const revenueChart = new Chart(revenueCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                            label: 'Revenue',
                            data: {!! json_encode(array_values($monthlyRevenue ?? [])) !!},
                            borderColor: '#727cf5',
                            backgroundColor: 'rgba(114, 124, 245, 0.1)',
                            tension: 0.3,
                            fill: true,
                            pointBackgroundColor: '#727cf5',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                usePointStyle: true,
                                callbacks: {
                                    label: function(context) {
                                        return formatCurrency(context.parsed.y);
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return formatCurrency(value);
                                    }
                                },
                                grid: {
                                    borderDash: [2, 2],
                                    drawBorder: false
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            // Initialize Lead Source Chart with improved error handling
            const leadSourceCtx = document.getElementById('leadSourceChart');
            if (leadSourceCtx) {
                try {
                    // Destroy previous chart instance if it exists
                    if (leadSourceCtx.chart) {
                        leadSourceCtx.chart.destroy();
                    }

                    const leadSources = {!! json_encode($leadSources) !!};
                    console.log('Lead Sources Data:', leadSources);

                    const labels = leadSources.map(item => item.source);
                    const data = leadSources.map(item => item.count);

                    const isSingleSource = leadSources.length === 1;

                    // Create new chart instance and store it on the canvas element
                    leadSourceCtx.chart = new Chart(leadSourceCtx.getContext('2d'), {
                        type: isSingleSource ? 'doughnut' : 'pie',
                        data: {
                            labels: labels,
                            datasets: [{
                            data: data,
                            backgroundColor: [
                                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                                '#FF9F40', '#8AC24A', '#607D8B', '#E91E63', '#9C27B0'
                            ].slice(0, Math.max(1, leadSources.length)),
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                usePointStyle: true,
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = Math.round((value / total) * 100);
                                        return `${label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        cutout: isSingleSource ? '50%' : 0
                    }
                });
            } catch (error) {
                console.error('Error initializing lead source chart:', error);
                leadSourceCtx.parentElement.innerHTML = `
                    <div class="no-data-placeholder">
                        <i class="fas fa-chart-pie text-muted"></i>
                        <h5>Chart Could Not Load</h5>
                        <p class="text-muted">${error.message}</p>
                    </div>
                `;
            }
        }

        // Make lead rows clickable
        document.querySelectorAll('.clickable-row').forEach(row => {
            row.addEventListener('click', function() {
                const leadId = this.getAttribute('data-lead-id');
                if (leadId) {
                    window.location.href = `/company/leads/${leadId}`;
                }
            });
        });

        // Toast notification function
        function showToast(icon, title) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });

            Toast.fire({
                icon: icon,
                title: title
            });
        }

        // Check for success/error messages in session
        @if(session('success'))
            showToast('success', '{{ session('success') }}');
        @endif

        @if(session('error'))
            showToast('error', '{{ session('error') }}');
        @endif

        // Currency selector change handler
        document.getElementById('currency')?.addEventListener('change', function() {
            // Show loading indicator
            const loadingToast = Swal.fire({
                title: 'Switching Currency',
                html: 'Please wait while we update currency settings...',
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit the form
            this.form.submit();
        });
    });
</script>
@endsection