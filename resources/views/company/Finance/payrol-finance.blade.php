@extends('layouts.vertical', ['page_title' => 'Payroll Finance'])

@section('css')
    <style>
        :root {
            --payroll-gradient-start: #051937;
            --payroll-gradient-mid: #19486a;
            --payroll-gradient-end: #2d8ca3;
            --payroll-surface: #f5f7fb;
            --payroll-card-bg: #ffffff;
            --payroll-text-strong: #0c2442;
            --payroll-text-muted: rgba(12, 36, 66, 0.68);
            --payroll-accent: #22b8cf;
            --payroll-success: #16a34a;
        }

        .payroll-dashboard {
            background: var(--payroll-surface);
            min-height: 100vh;
        }

        .payroll-hero {
            background: linear-gradient(135deg, var(--payroll-gradient-start), var(--payroll-gradient-end));
            padding: 2px;
            border-radius: 26px;
            box-shadow: 0 24px 38px rgba(5, 25, 55, 0.24);
        }

        .payroll-hero__surface {
            background: rgba(255, 255, 255, 0.92);
            border-radius: 24px;
            padding: 2.4rem 2.8rem;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: flex-start;
            gap: 2rem;
        }

        .payroll-hero__intro h1 {
            font-size: 2.1rem;
            font-weight: 700;
            color: var(--payroll-text-strong);
            margin-bottom: 0.75rem;
        }

        .payroll-hero__intro p {
            color: var(--payroll-text-muted);
            margin-bottom: 1.5rem;
            max-width: 560px;
            line-height: 1.6;
        }

        .payroll-header-actions .action-card {
            background: rgba(28, 72, 122, 0.08);
            border-radius: 18px;
            padding: 1rem 1.3rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
            color: var(--payroll-text-strong);
        }

        .payroll-header-actions .action-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 26px rgba(5, 25, 55, 0.16);
        }

        .payroll-header-actions .action-card i {
            font-size: 1.5rem;
            color: var(--payroll-accent);
        }

        .payroll-card {
            background: var(--payroll-card-bg);
            border-radius: 20px;
            box-shadow: 0 16px 24px rgba(15, 36, 64, 0.12);
            border: 1px solid rgba(12, 36, 66, 0.06);
        }

        .payroll-card__header {
            padding: 1.6rem 1.8rem 1.2rem;
            border-bottom: 1px solid rgba(12, 36, 66, 0.06);
        }

        .payroll-card__header h2 {
            font-size: 1.35rem;
            margin: 0;
            color: var(--payroll-text-strong);
            font-weight: 700;
        }

        .payroll-card__header span {
            display: block;
            color: var(--payroll-text-muted);
            margin-top: 0.4rem;
        }

        .payroll-table thead {
            background: linear-gradient(135deg, rgba(5, 25, 55, 0.9), rgba(45, 140, 163, 0.85));
        }

        .payroll-table thead th {
            padding: 0.9rem 1rem;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #ffffff;
        }

        .payroll-table .table-actions .btn {
            border-radius: 999px;
            padding: 0.45rem 0.95rem;
            font-size: 0.78rem;
            font-weight: 600;
        }

        .payroll-table .table-actions .btn i {
            font-size: 0.9rem;
        }

        .payroll-table tbody tr {
            transition: background 0.2s ease;
        }

        .payroll-table tbody tr:hover {
            background: rgba(45, 140, 163, 0.08);
        }

        .payroll-summary {
            display: flex;
            flex-wrap: wrap;
            gap: 1.2rem;
            padding: 1.4rem 1.8rem 0;
        }

        .payroll-summary .summary-chip {
            background: rgba(34, 184, 207, 0.12);
            color: var(--payroll-text-strong);
            border-radius: 14px;
            padding: 0.6rem 1.1rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .payroll-summary .summary-chip i {
            color: var(--payroll-accent);
        }

        .payroll-metric-card {
            background: var(--payroll-card-bg);
            border-radius: 18px;
            padding: 1.4rem 1.6rem;
            box-shadow: 0 14px 26px rgba(15, 36, 64, 0.08);
            border: 1px solid rgba(12, 36, 66, 0.05);
            height: 100%;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .payroll-metric-card .metric-label {
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-size: 0.7rem;
            color: var(--payroll-text-muted);
            font-weight: 600;
        }

        .payroll-metric-card .metric-value {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--payroll-text-strong);
            line-height: 1.2;
        }

        .payroll-metric-card .metric-meta {
            color: var(--payroll-text-muted);
            font-size: 0.85rem;
        }

        .payroll-metric-card .metric-footnote {
            font-size: 0.78rem;
            color: var(--payroll-text-muted);
        }

        .payroll-metric-card .metric-progress {
            height: 8px;
            border-radius: 999px;
            background: rgba(34, 184, 207, 0.15);
            overflow: hidden;
        }

        .payroll-metric-card .metric-progress-bar {
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(135deg, var(--payroll-gradient-mid), var(--payroll-accent));
        }

        .metric-icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(5, 25, 55, 0.08), rgba(34, 184, 207, 0.25));
            color: var(--payroll-accent);
            font-size: 1.25rem;
        }

        .payroll-metric-card .badge {
            font-size: 0.7rem;
            padding: 0.35rem 0.6rem;
            letter-spacing: 0.06em;
        }

        .deadline-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(12, 36, 66, 0.06);
        }

        .deadline-item:last-child {
            border-bottom: none;
        }

        .deadline-indicator {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(34, 184, 207, 0.12);
            color: var(--payroll-accent);
            flex-shrink: 0;
        }

        .deadline-item strong {
            color: var(--payroll-text-strong);
            font-weight: 700;
        }

        .deadline-item .deadline-meta {
            color: var(--payroll-text-muted);
            font-size: 0.82rem;
        }

        .approval-flow {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 1.6rem;
        }

        .approval-step {
            position: relative;
            padding-left: 2.6rem;
        }

        .approval-step::before {
            content: '';
            position: absolute;
            left: 0.9rem;
            top: 1.6rem;
            bottom: -1.6rem;
            width: 2px;
            background: rgba(12, 36, 66, 0.1);
        }

        .approval-step:last-child::before {
            display: none;
        }

        .approval-step__icon {
            position: absolute;
            left: 0;
            top: 0;
            width: 1.8rem;
            height: 1.8rem;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            background: rgba(12, 36, 66, 0.08);
            color: var(--payroll-text-strong);
        }

        .approval-step.completed .approval-step__icon {
            background: rgba(22, 163, 74, 0.15);
            color: var(--payroll-success);
        }

        .approval-step.pending .approval-step__icon {
            background: rgba(234, 179, 8, 0.18);
            color: #d97706;
        }

        .approval-step .approval-step__title {
            font-weight: 600;
            color: var(--payroll-text-strong);
            font-size: 1.05rem;
        }

        .approval-step .approval-step__meta {
            font-size: 0.78rem;
            color: var(--payroll-text-muted);
        }

        .approval-step .approval-step__body {
            margin-top: 0.35rem;
            font-size: 0.85rem;
            color: var(--payroll-text-muted);
        }

        .export-actions .btn {
            border-radius: 14px;
            padding: 0.85rem 1.4rem;
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        .export-actions .btn i {
            font-size: 1.1rem;
        }

        .modal .modal-header {
            border-bottom: none;
            padding-bottom: 0;
        }

        .modal .modal-footer {
            border-top: none;
        }

        .modal-title-group h5 {
            margin-bottom: 0.3rem;
            color: var(--payroll-text-strong);
            font-weight: 700;
        }

        .modal-title-group span {
            color: var(--payroll-text-muted);
            font-size: 0.9rem;
        }

        @media (max-width: 1200px) {
            .payroll-hero__surface {
                flex-direction: column;
                align-items: stretch;
            }

            .payroll-header-actions {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .payroll-hero__surface {
                padding: 1.8rem;
            }

            .payroll-hero__intro h1 {
                font-size: 1.6rem;
            }

            .payroll-card__header {
                padding: 1.3rem 1.4rem 1rem;
            }

            .payroll-card__header h2 {
                font-size: 1.15rem;
            }

            .payroll-summary {
                padding: 1.2rem;
            }

            .payroll-summary .summary-chip {
                width: 100%;
                justify-content: space-between;
            }

            .payroll-table thead {
                display: none;
            }

            .payroll-table tbody tr {
                display: block;
                margin-bottom: 1.4rem;
                border-radius: 16px;
                padding: 1rem;
                background: #ffffff;
                box-shadow: 0 10px 20px rgba(15, 36, 64, 0.08);
            }

            .payroll-table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.5rem 0;
                font-size: 0.92rem;
            }

            .payroll-table tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--payroll-text-muted);
                text-transform: uppercase;
                letter-spacing: 0.06em;
            }

            .payroll-table .table-actions {
                justify-content: flex-end;
            }
        }

        @media (max-width: 576px) {
            .payroll-header-actions .action-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .payroll-header-actions .action-card i:last-child {
                align-self: flex-end;
            }

            .export-actions .btn {
                width: 100%;
                justify-content: center;
            }

            .payroll-summary .summary-chip {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.3rem;
            }

            .payroll-metric-card {
                padding: 1.1rem 1.3rem;
                gap: 0.8rem;
            }

            .metric-icon {
                width: 40px;
                height: 40px;
                border-radius: 12px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4 payroll-dashboard">
        <div class="payroll-hero mb-4">
            <div class="payroll-hero__surface">
                <div class="payroll-hero__intro">
                    <h1>Payroll Intelligence Center</h1>
                    <p>
                        Seamlessly review compliance-critical payroll artefacts, collaborate with finance, and export
                        filings that keep you ahead of regulatory deadlines. Choose a focus area below to dive deeper.
                    </p>
                    <div class="d-flex gap-2 align-items-center flex-wrap">
                        <span class="badge text-bg-light text-uppercase fw-semibold px-3 py-2">period: August 2025</span>
                        <span class="badge text-bg-primary text-uppercase fw-semibold px-3 py-2">Processing Window</span>
                    </div>
                </div>
                <div class="payroll-header-actions d-grid gap-3" style="min-width: 280px;">
                    <div class="action-card" data-bs-toggle="modal" data-bs-target="#payeReportModal">
                        <i class="fa-solid fa-scale-balanced"></i>
                        <div>
                            <h5 class="mb-1">PAYE Report</h5>
                            <small class="text-muted">Validate PAYE bands &amp; reconcile tax deductions.</small>
                        </div>
                        <i class="fa-solid fa-chevron-right ms-auto text-secondary"></i>
                    </div>
                    <div class="action-card" data-bs-toggle="modal" data-bs-target="#ssnitReportModal">
                        <i class="fa-solid fa-shield-heart"></i>
                        <div>
                            <h5 class="mb-1">SSNIT Report</h5>
                            <small class="text-muted">Preview employee &amp; employer contributions.</small>
                        </div>
                        <i class="fa-solid fa-chevron-right ms-auto text-secondary"></i>
                    </div>
                    <div class="action-card" data-bs-toggle="modal" data-bs-target="#payrollSummaryModal">
                        <i class="fa-solid fa-chart-line"></i>
                        <div>
                            <h5 class="mb-1">Monthly Payroll Summary</h5>
                            <small class="text-muted">Review totals, variances, and payroll posture.</small>
                        </div>
                        <i class="fa-solid fa-chevron-right ms-auto text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 g-lg-4 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="payroll-metric-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="metric-label">Payroll Run</span>
                        <span class="badge bg-success-subtle text-success">On Track</span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="metric-icon"><i class="fa-solid fa-clipboard-check"></i></span>
                        <div>
                            <div class="metric-value">Run #482</div>
                            <div class="metric-meta">Closing window in 3 days</div>
                        </div>
                    </div>
                    <div class="metric-progress">
                        <div class="metric-progress-bar" style="width: 72%;"></div>
                    </div>
                    <div class="metric-footnote">Approvals captured · 72% complete</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="payroll-metric-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="metric-label">Headcount</span>
                        <span class="badge bg-info-subtle text-info">Stable</span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="metric-icon"><i class="fa-solid fa-users"></i></span>
                        <div>
                            <div class="metric-value">48 Employees</div>
                            <div class="metric-meta">Recruitment freeze lifted in Q4</div>
                        </div>
                    </div>
                    <div class="metric-footnote">Turnover rate · 1 exit this cycle</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="payroll-metric-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="metric-label">Gross Payroll</span>
                        <span class="badge bg-primary-subtle text-primary">+3.2%</span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="metric-icon"><i class="fa-solid fa-coins"></i></span>
                        <div>
                            <div class="metric-value">₵27,100.00</div>
                            <div class="metric-meta">Variance vs July · ₵845.00</div>
                        </div>
                    </div>
                    <div class="metric-footnote">Drivers: overtime uplift & allowances</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="payroll-metric-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="metric-label">Compliance Health</span>
                        <span class="badge bg-warning-subtle text-warning">Action Needed</span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="metric-icon"><i class="fa-solid fa-shield-halved"></i></span>
                        <div>
                            <div class="metric-value">2 Open Items</div>
                            <div class="metric-meta">Awaiting SSNIT schedule confirmation</div>
                        </div>
                    </div>
                    <div class="metric-footnote">Escalate outstanding submissions before 12 Sep</div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-xl-4">
                <div class="payroll-card h-100">
                    <div class="payroll-card__header">
                        <h2>Upcoming Deadlines</h2>
                        <span>Stay ahead of statutory submission dates.</span>
                    </div>
                    <div class="p-4">
                        <div class="deadline-item">
                            <span class="deadline-indicator"><i class="fa-solid fa-landmark"></i></span>
                            <div>
                                <strong>PAYE Return · August</strong>
                                <p class="deadline-meta mb-1">Due 15 Sep · Draft awaiting CFO signature.</p>
                                <span class="badge bg-light text-dark">3 days left</span>
                            </div>
                        </div>
                        <div class="deadline-item">
                            <span class="deadline-indicator"><i class="fa-solid fa-handshake"></i></span>
                            <div>
                                <strong>SSNIT Remittance</strong>
                                <p class="deadline-meta mb-1">Due 18 Sep · Schedule flagged for variance review.</p>
                                <span class="badge bg-warning-subtle text-warning">Pending review</span>
                            </div>
                        </div>
                        <div class="deadline-item">
                            <span class="deadline-indicator"><i class="fa-solid fa-file-invoice"></i></span>
                            <div>
                                <strong>Payroll Audit Pack</strong>
                                <p class="deadline-meta mb-1">Due 22 Sep · Share pack with internal audit.</p>
                                <span class="badge bg-primary-subtle text-primary">Scheduled</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="payroll-card h-100">
                    <div class="payroll-card__header">
                        <h2>Approval Flow</h2>
                        <span>Visualise sign-offs needed before payroll release.</span>
                    </div>
                    <div class="p-4">
                        <div class="approval-flow">
                            <div class="approval-step completed">
                                <div class="approval-step__icon"><i class="fa-solid fa-check"></i></div>
                                <div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="approval-step__title">HR Validation</span>
                                        <span class="approval-step__meta">Completed · 11 Aug</span>
                                    </div>
                                    <div class="approval-step__body">Employee records reconciled and payroll register locked.</div>
                                </div>
                            </div>
                            <div class="approval-step completed">
                                <div class="approval-step__icon"><i class="fa-solid fa-check"></i></div>
                                <div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="approval-step__title">Finance Review</span>
                                        <span class="approval-step__meta">Completed · 14 Aug</span>
                                    </div>
                                    <div class="approval-step__body">Variance analysis submitted with supporting commentary.</div>
                                </div>
                            </div>
                            <div class="approval-step pending">
                                <div class="approval-step__icon"><i class="fa-solid fa-hourglass-half"></i></div>
                                <div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="approval-step__title">CFO Approval</span>
                                        <span class="approval-step__meta">Awaiting · Due 12 Sep</span>
                                    </div>
                                    <div class="approval-step__body">CFO to confirm SSNIT variance resolution before sign-off.</div>
                                </div>
                            </div>
                            <div class="approval-step">
                                <div class="approval-step__icon"><i class="fa-solid fa-paper-plane"></i></div>
                                <div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="approval-step__title">Disbursement</span>
                                        <span class="approval-step__meta">Scheduled · 15 Sep</span>
                                    </div>
                                    <div class="approval-step__body">Payroll file release to banks once CFO approval is logged.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <div class="payroll-card">
                    <div class="payroll-card__header">
                        <h2>Payroll Register</h2>
                        <span>Snapshot of employee earnings, statutory deductions, and employer obligations.</span>
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <button class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#filtersModal">
                                <i class="fa-solid fa-sliders"></i> Filters
                            </button>
                            <button class="btn btn-outline-primary btn-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#varianceModal">
                                <i class="fa-solid fa-wave-square"></i> Variance Watch
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0 payroll-table">
                            <thead>
                                <tr>
                                    <th scope="col">Employee</th>
                                    <th scope="col">Basic</th>
                                    <th scope="col">SSNIT (5.5%)</th>
                                    <th scope="col">Taxable Income</th>
                                    <th scope="col">PAYE</th>
                                    <th scope="col">Net Pay</th>
                                    <th scope="col">Employer SSNIT (13%)</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-sm rounded-circle bg-light d-flex align-items-center justify-content-center">
                                                <span class="fw-bold text-primary">AK</span>
                                            </div>
                                            <div>
                                                <span class="fw-semibold d-block">Akosua Koomson</span>
                                                <small class="text-muted">Finance Analyst</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Basic">₵9,200.00</td>
                                    <td data-label="SSNIT (5.5%)">₵506.00</td>
                                    <td data-label="Taxable Income">₵8,694.00</td>
                                    <td data-label="PAYE">₵1,095.50</td>
                                    <td data-label="Net Pay" class="fw-semibold text-success">₵7,598.50</td>
                                    <td data-label="Employer SSNIT (13%)">₵1,196.00</td>
                                    <td data-label="Action">
                                        <div class="d-flex table-actions gap-2">
                                            <button class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#viewEmployeeAkosua">
                                                <i class="fa-solid fa-eye"></i> View
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#deleteEmployeeAkosua">
                                                <i class="fa-solid fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-sm rounded-circle bg-light d-flex align-items-center justify-content-center">
                                                <span class="fw-bold text-primary">DM</span>
                                            </div>
                                            <div>
                                                <span class="fw-semibold d-block">David Mensah</span>
                                                <small class="text-muted">Operations Lead</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Basic">₵7,400.00</td>
                                    <td data-label="SSNIT (5.5%)">₵407.00</td>
                                    <td data-label="Taxable Income">₵6,993.00</td>
                                    <td data-label="PAYE">₵863.60</td>
                                    <td data-label="Net Pay" class="fw-semibold text-success">₵6,129.40</td>
                                    <td data-label="Employer SSNIT (13%)">₵962.00</td>
                                    <td data-label="Action">
                                        <div class="d-flex table-actions gap-2">
                                            <button class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#viewEmployeeDavid">
                                                <i class="fa-solid fa-eye"></i> View
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#deleteEmployeeDavid">
                                                <i class="fa-solid fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-sm rounded-circle bg-light d-flex align-items-center justify-content-center">
                                                <span class="fw-bold text-primary">EL</span>
                                            </div>
                                            <div>
                                                <span class="fw-semibold d-block">Esi Lamptey</span>
                                                <small class="text-muted">Depot Supervisor</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Basic">₵5,600.00</td>
                                    <td data-label="SSNIT (5.5%)">₵308.00</td>
                                    <td data-label="Taxable Income">₵5,292.00</td>
                                    <td data-label="PAYE">₵621.55</td>
                                    <td data-label="Net Pay" class="fw-semibold text-success">₵4,670.45</td>
                                    <td data-label="Employer SSNIT (13%)">₵728.00</td>
                                    <td data-label="Action">
                                        <div class="d-flex table-actions gap-2">
                                            <button class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#viewEmployeeEsi">
                                                <i class="fa-solid fa-eye"></i> View
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#deleteEmployeeEsi">
                                                <i class="fa-solid fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-sm rounded-circle bg-light d-flex align-items-center justify-content-center">
                                                <span class="fw-bold text-primary">JB</span>
                                            </div>
                                            <div>
                                                <span class="fw-semibold d-block">James Boateng</span>
                                                <small class="text-muted">Station Manager</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Basic">₵4,900.00</td>
                                    <td data-label="SSNIT (5.5%)">₵269.50</td>
                                    <td data-label="Taxable Income">₵4,630.50</td>
                                    <td data-label="PAYE">₵502.70</td>
                                    <td data-label="Net Pay" class="fw-semibold text-success">₵4,127.80</td>
                                    <td data-label="Employer SSNIT (13%)">₵637.00</td>
                                    <td data-label="Action">
                                        <div class="d-flex table-actions gap-2">
                                            <button class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#viewEmployeeJames">
                                                <i class="fa-solid fa-eye"></i> View
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#deleteEmployeeJames">
                                                <i class="fa-solid fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="payroll-summary">
                        <span class="summary-chip">
                            <i class="fa-solid fa-wallet"></i>
                            Total Net Pay: <strong>₵22,525.15</strong>
                        </span>
                        <span class="summary-chip">
                            <i class="fa-solid fa-piggy-bank"></i>
                            PAYE Liability: <strong>₵3,083.35</strong>
                        </span>
                        <span class="summary-chip">
                            <i class="fa-solid fa-people-group"></i>
                            SSNIT Due (Emp + Empr): <strong>₵3,639.50</strong>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="payroll-card h-100">
                    <div class="payroll-card__header">
                        <h2>Compliance Checklist</h2>
                        <span>Track statutory submissions and ensure on-time delivery.</span>
                    </div>
                    <div class="p-3">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex align-items-start gap-3">
                                <i class="fa-solid fa-file-signature text-primary pt-1"></i>
                                <div>
                                    <strong>PAYE Declaration</strong>
                                    <p class="text-muted mb-1">Submit to GRA before the 15th of the subsequent month.</p>
                                    <span class="badge bg-success-subtle text-success">Ready</span>
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-start gap-3">
                                <i class="fa-solid fa-hands-holding-circle text-primary pt-1"></i>
                                <div>
                                    <strong>SSNIT Schedule</strong>
                                    <p class="text-muted mb-1">Ensure both employee and employer contributions are reconciled.</p>
                                    <span class="badge bg-warning-subtle text-warning">Pending Review</span>
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-start gap-3">
                                <i class="fa-solid fa-chart-pie text-primary pt-1"></i>
                                <div>
                                    <strong>Variance Analysis</strong>
                                    <p class="text-muted mb-1">Investigate payroll swings exceeding ±5% month-on-month.</p>
                                    <span class="badge bg-info-subtle text-info">Monitoring</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="payroll-card h-100">
                    <div class="payroll-card__header">
                        <h2>Exports &amp; Filing</h2>
                        <span>Generate statutory artefacts in the formats your regulators expect.</span>
                    </div>
                    <div class="p-4 export-actions d-grid gap-3">
                        <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#exportPdfModal">
                            <i class="fa-solid fa-file-pdf"></i> Export PAYE Bundle (PDF)
                        </button>
                        <button class="btn btn-outline-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#exportExcelModal">
                            <i class="fa-solid fa-file-excel"></i> Download Payroll Sheet (Excel)
                        </button>
                        <button class="btn btn-outline-dark d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#graReturnModal">
                            <i class="fa-solid fa-landmark"></i> Prepare GRA Payroll Return
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Detail Modals -->
    <div class="modal fade" id="viewEmployeeAkosua" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title-group">
                        <h5 class="modal-title">Employee Snapshot · Akosua Koomson</h5>
                        <span>Finance Analyst · PAYN-482-01</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label">Basic Salary</label>
                            <div class="fw-semibold">₵9,200.00</div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Net Pay</label>
                            <div class="fw-semibold text-success">₵7,598.50</div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">SSNIT (5.5%)</label>
                            <div>₵506.00</div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Employer SSNIT (13%)</label>
                            <div>₵1,196.00</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Remarks</label>
                            <p class="mb-0 text-muted">Eligible for professional allowance review in Q4.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Download Slip</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteEmployeeAkosua" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Remove Akosua from Run?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">This will mark Akosua Koomson for exclusion in payroll run <strong>#482</strong>.</p>
                    <p class="small text-muted mb-0">Ensure a manual adjustment is logged for compliance before deleting.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger">Confirm Deletion</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewEmployeeDavid" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title-group">
                        <h5 class="modal-title">Employee Snapshot · David Mensah</h5>
                        <span>Operations Lead · PAYN-482-07</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label">Basic Salary</label>
                            <div class="fw-semibold">₵7,400.00</div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Net Pay</label>
                            <div class="fw-semibold text-success">₵6,129.40</div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">SSNIT (5.5%)</label>
                            <div>₵407.00</div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Employer SSNIT (13%)</label>
                            <div>₵962.00</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Remarks</label>
                            <p class="mb-0 text-muted">Flagged for overtime variance investigation (+7.8%).</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Download Slip</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteEmployeeDavid" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Remove David from Run?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">This will mark David Mensah for exclusion in payroll run <strong>#482</strong>.</p>
                    <p class="small text-muted mb-0">Notify operations leaders before finalising deletion.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger">Confirm Deletion</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewEmployeeEsi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title-group">
                        <h5 class="modal-title">Employee Snapshot · Esi Lamptey</h5>
                        <span>Depot Supervisor · PAYN-482-12</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label">Basic Salary</label>
                            <div class="fw-semibold">₵5,600.00</div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Net Pay</label>
                            <div class="fw-semibold text-success">₵4,670.45</div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">SSNIT (5.5%)</label>
                            <div>₵308.00</div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Employer SSNIT (13%)</label>
                            <div>₵728.00</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Remarks</label>
                            <p class="mb-0 text-muted">No anomalies detected for this period.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Download Slip</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteEmployeeEsi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Remove Esi from Run?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">This will mark Esi Lamptey for exclusion in payroll run <strong>#482</strong>.</p>
                    <p class="small text-muted mb-0">Remember to document an HR memo if this is a termination.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger">Confirm Deletion</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewEmployeeJames" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title-group">
                        <h5 class="modal-title">Employee Snapshot · James Boateng</h5>
                        <span>Station Manager · PAYN-482-19</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label">Basic Salary</label>
                            <div class="fw-semibold">₵4,900.00</div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Net Pay</label>
                            <div class="fw-semibold text-success">₵4,127.80</div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">SSNIT (5.5%)</label>
                            <div>₵269.50</div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Employer SSNIT (13%)</label>
                            <div>₵637.00</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Remarks</label>
                            <p class="mb-0 text-muted">Retail allowances trending down (-3.5%) month-on-month.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Download Slip</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteEmployeeJames" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Remove James from Run?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">This will mark James Boateng for exclusion in payroll run <strong>#482</strong>.</p>
                    <p class="small text-muted mb-0">Coordinate with station leadership to confirm replacement coverage.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger">Confirm Deletion</button>
                </div>
            </div>
        </div>
    </div>

    <!-- PAYE Report Modal -->
    <div class="modal fade" id="payeReportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title-group">
                        <h5 class="modal-title">PAYE Compliance Report</h5>
                        <span>Analyse tax liabilities by band, allowance, and relief.</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Assessment Month</label>
                            <select class="form-select">
                                <option>August 2025</option>
                                <option>July 2025</option>
                                <option>June 2025</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Office Filing Branch</label>
                            <input type="text" class="form-control" placeholder="e.g. Accra North Medium Tax" />
                        </div>
                        <div class="col-12">
                            <label class="form-label">Summary Insight</label>
                            <textarea class="form-control" rows="3" placeholder="Capture remarks or outstanding actions"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <div class="text-muted small">Auto-generated figures sourced from payroll run #482.</div>
                    <button type="button" class="btn btn-primary">Generate PAYE Report</button>
                </div>
            </div>
        </div>
    </div>

    <!-- SSNIT Report Modal -->
    <div class="modal fade" id="ssnitReportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title-group">
                        <h5 class="modal-title">SSNIT Contribution Report</h5>
                        <span>Cross-check employee and employer shares before submission.</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Contribution Period</label>
                            <select class="form-select">
                                <option>August 2025</option>
                                <option>July 2025</option>
                                <option>June 2025</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Total Employees Covered</label>
                            <input type="number" class="form-control" value="48" />
                        </div>
                        <div class="col-12">
                            <label class="form-label">Upload Signed Schedule (optional)</label>
                            <input type="file" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <div class="text-muted small">Remember to attach prose for late submissions if applicable.</div>
                    <button type="button" class="btn btn-primary">Compile SSNIT Schedule</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Payroll Summary Modal -->
    <div class="modal fade" id="payrollSummaryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title-group">
                        <h5 class="modal-title">Monthly Payroll Summary</h5>
                        <span>High-level view of headcount, gross, and statutory deductions.</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Total Headcount</span>
                            <span class="fw-semibold">48 Employees</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Gross Payroll</span>
                            <span class="fw-semibold">₵27,100.00</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Total Net Pay</span>
                            <span class="fw-semibold">₵22,525.15</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">PAYE Liability</span>
                            <span class="fw-semibold">₵3,083.35</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">SSNIT Combined</span>
                            <span class="fw-semibold">₵3,639.50</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <div class="text-muted small">Variances vs July: <span class="text-success">+3.2%</span></div>
                    <button type="button" class="btn btn-outline-primary">Share Snapshot</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Modal -->
    <div class="modal fade" id="filtersModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title-group">
                        <h5 class="modal-title">Payroll Filters</h5>
                        <span>Drill into teams, stations, or salary bands instantly.</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label">Department</label>
                            <select class="form-select">
                                <option value="">All Departments</option>
                                <option>Finance</option>
                                <option>Operations</option>
                                <option>Retail Stations</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Station</label>
                            <select class="form-select">
                                <option value="">All Stations</option>
                                <option>Navrongo Main</option>
                                <option>Wapuli</option>
                                <option>Bamvin</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pay Group</label>
                            <select class="form-select">
                                <option value="">Select Pay Group</option>
                                <option>Monthly</option>
                                <option>Weekly</option>
                                <option>Contract</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Run Reference</label>
                            <input type="text" class="form-control" placeholder="e.g. PRN-2025-08" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Variance Threshold (%)</label>
                            <input type="number" class="form-control" value="5" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Apply Filters</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Variance Modal -->
    <div class="modal fade" id="varianceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title-group">
                        <h5 class="modal-title">Variance Watch</h5>
                        <span>Keep an eye on material fluctuations in the payroll run.</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                <strong>Operations Overtime</strong>
                                <p class="text-muted mb-0">+7.8% vs last month</p>
                            </div>
                            <span class="badge text-bg-warning">Investigate</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                <strong>Retail Allowances</strong>
                                <p class="text-muted mb-0">-3.5% vs last month</p>
                            </div>
                            <span class="badge text-bg-success">On Track</span>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary">Send Alert</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Export PDF Modal -->
    <div class="modal fade" id="exportPdfModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title-group">
                        <h5 class="modal-title">Export PAYE Bundle (PDF)</h5>
                        <span>Send a paginated PDF pack to leadership or tax authorities.</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Recipient Email</label>
                        <input type="email" class="form-control" placeholder="finance@company.com" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Include Variance Appendix?</label>
                        <select class="form-select">
                            <option>Yes, include appendix</option>
                            <option>No, summary only</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Export PDF</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Excel Modal -->
    <div class="modal fade" id="exportExcelModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title-group">
                        <h5 class="modal-title">Download Payroll Sheet (Excel)</h5>
                        <span>Get the structured workbook for further modelling or reconciliations.</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Workbook Template</label>
                        <select class="form-select">
                            <option>Detailed (with pivot-friendly tabs)</option>
                            <option>Summary (single worksheet)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Protect?</label>
                        <input type="password" class="form-control" placeholder="Optional password" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Download Excel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- GRA Payroll Return Modal -->
    <div class="modal fade" id="graReturnModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title-group">
                        <h5 class="modal-title">Prepare GRA Payroll Return</h5>
                        <span>Assemble the monthly return package with digital signatures.</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Filing Branch</label>
                            <input type="text" class="form-control" placeholder="e.g. Accra North MTO" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Submission Date</label>
                            <input type="date" class="form-control" />
                        </div>
                        <div class="col-12">
                            <label class="form-label">Attach Signed Declaration</label>
                            <input type="file" class="form-control" />
                        </div>
                        <div class="col-12">
                            <label class="form-label">Comments</label>
                            <textarea class="form-control" rows="3" placeholder="Include additional notes for the commissioner"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Generate Return Packet</button>
                </div>
            </div>
        </div>
    </div>
@endsection
