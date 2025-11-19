@extends('layouts.vertical', ['page_title' => 'Analytic Reports'])

@section('css')
    <style>
        :root {
            --ar-surface: #f7f8fc;
            --ar-card: #ffffff;
            --ar-border: rgba(17, 24, 39, 0.08);
            --ar-shadow: 0 24px 48px rgba(15, 23, 42, 0.08);
            --ar-shadow-soft: 0 12px 30px rgba(15, 23, 42, 0.06);
            --ar-text: #0f172a;
            --ar-muted: rgba(15, 23, 42, 0.65);
            --ar-primary: #2563eb;
            --ar-primary-soft: rgba(37, 99, 235, 0.12);
            --ar-accent: #f97316;
            --ar-success: #16a34a;
            --ar-warning: #facc15;
            --ar-danger: #ef4444;
            --ar-gradient: linear-gradient(135deg, #1d4ed8, #6366f1);
        }

        .analytics-reports {
            background: var(--ar-surface);
            min-height: 100vh;
            padding: 1.5rem 0 3rem;
        }

        .analytics-hero {
            background: var(--ar-gradient);
            border-radius: 28px;
            padding: 1px;
            box-shadow: var(--ar-shadow);
            margin-bottom: 2.5rem;
        }

        .analytics-hero__inner {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.85));
            border-radius: 27px;
            padding: 2.5rem 2.8rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .analytics-hero__eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.45rem 1.1rem;
            border-radius: 999px;
            background: var(--ar-primary-soft);
            color: var(--ar-primary);
            text-transform: uppercase;
            letter-spacing: 0.18em;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .analytics-hero__title {
            font-weight: 700;
            font-size: 2rem;
            color: var(--ar-text);
            letter-spacing: 0.01em;
            margin: 0;
        }

        .analytics-hero__subtitle {
            max-width: 680px;
            color: var(--ar-muted);
            font-size: 0.95rem;
            line-height: 1.7;
        }

        .analytics-hero__meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .analytics-hero__meta-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            background: rgba(37, 99, 235, 0.08);
            padding: 0.65rem 1.1rem;
            border-radius: 16px;
            color: var(--ar-text);
            font-weight: 600;
        }

        .section-card {
            background: var(--ar-card);
            border: 1px solid var(--ar-border);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: var(--ar-shadow-soft);
            margin-bottom: 2rem;
        }

        .section-card__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.6rem;
            gap: 1rem;
        }

        .section-card__header-title {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .section-card__header-title h5 {
            margin: 0;
            font-weight: 700;
            color: var(--ar-text);
        }

        .section-card__header-title span {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: var(--ar-muted);
        }

        .category-card {
            position: relative;
            border-radius: 22px;
            padding: 1.6rem;
            border: 1px solid rgba(37, 99, 235, 0.06);
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.08), rgba(255, 255, 255, 0.95));
            min-height: 220px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .category-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--ar-shadow);
        }

        .category-card__icon {
            width: 54px;
            height: 54px;
            border-radius: 18px;
            background: rgba(37, 99, 235, 0.12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--ar-primary);
            font-size: 1.45rem;
        }

        .category-card__title {
            font-size: 1.05rem;
            font-weight: 700;
            margin: 1rem 0 0.5rem;
            color: var(--ar-text);
        }

        .category-card__description {
            color: var(--ar-muted);
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .category-card__footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 1.4rem;
            font-size: 0.82rem;
            color: var(--ar-muted);
        }

        .category-card__footer .trend {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-weight: 600;
        }

        .category-card__footer .trend.positive {
            color: var(--ar-success);
        }

        .category-card__footer .trend.neutral {
            color: var(--ar-primary);
        }

        .category-card__footer .trend.negative {
            color: var(--ar-danger);
        }

        .category-card__footer button {
            border: none;
            background: transparent;
            color: var(--ar-primary);
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .filter-card {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .filter-card__group {
            display: flex;
            flex-direction: column;
            gap: 0.55rem;
        }

        .filter-card label {
            font-weight: 600;
            color: var(--ar-text);
            font-size: 0.85rem;
        }

        .filter-card .form-control,
        .filter-card .form-select {
            border-radius: 14px;
            border: 1px solid rgba(148, 163, 184, 0.4);
            padding: 0.65rem 0.9rem;
            font-size: 0.92rem;
        }

        .filter-card__actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .filter-card__actions .btn {
            border-radius: 999px;
            padding: 0.6rem 1.4rem;
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        .report-viewer__header {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.6rem;
        }

        .report-viewer__header-actions {
            display: flex;
            gap: 0.8rem;
            flex-wrap: wrap;
        }

        .report-viewer__header-actions .btn {
            border-radius: 999px;
            font-weight: 600;
            padding: 0.55rem 1.3rem;
        }

        .report-chart {
            position: relative;
            height: 320px;
            margin-bottom: 2rem;
            border-radius: 24px;
            background: radial-gradient(circle at 10% 20%, rgba(37, 99, 235, 0.12), rgba(37, 99, 235, 0));
            padding: 1.5rem;
        }

        .table-responsive {
            border-radius: 18px;
            border: 1px solid rgba(148, 163, 184, 0.2);
            overflow: hidden;
        }

        .table thead {
            background: rgba(15, 23, 42, 0.02);
            font-size: 0.8rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .table thead th {
            padding: 0.85rem;
            border-bottom: none;
        }

        .table tbody td {
            padding: 0.95rem;
            vertical-align: middle;
            border-color: rgba(148, 163, 184, 0.2);
            font-size: 0.9rem;
        }

        .table tbody tr:hover {
            background-color: rgba(37, 99, 235, 0.06);
        }

        .report-viewer__footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.5rem;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .insight-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(37, 99, 235, 0.08);
            color: var(--ar-primary);
            padding: 0.45rem 0.9rem;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 600;
        }

        .modal-insight {
            border-radius: 24px;
            border: none;
            box-shadow: var(--ar-shadow);
        }

        .modal-insight .modal-header {
            border: none;
            padding-bottom: 0;
        }

        .modal-insight .modal-body {
            padding-top: 0.5rem;
        }

        .data-point {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px dashed rgba(148, 163, 184, 0.25);
        }

        .data-point:last-child {
            border-bottom: none;
        }

        .viewer-modal__metrics {
            display: grid;
            gap: 0.75rem;
        }

        .viewer-modal__metric {
            background: rgba(37, 99, 235, 0.08);
            border-radius: 16px;
            padding: 0.9rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .viewer-modal__metric-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--ar-muted);
            font-weight: 600;
            display: block;
            margin-bottom: 0.2rem;
        }

        .viewer-modal__metric-value {
            font-weight: 700;
            color: var(--ar-text);
            font-size: 1.05rem;
        }

        .viewer-modal__highlight {
            padding: 0.85rem 1rem;
            border-radius: 16px;
            background: rgba(15, 23, 42, 0.04);
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
        }

        .viewer-modal__highlight-icon {
            width: 38px;
            height: 38px;
            border-radius: 14px;
            background: var(--ar-primary-soft);
            color: var(--ar-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .viewer-modal__chart {
            position: relative;
            height: 320px;
            border-radius: 20px;
            background: radial-gradient(circle at 14% 24%, rgba(37, 99, 235, 0.12), rgba(37, 99, 235, 0));
            padding: 1.5rem;
        }

        @media (max-width: 991.98px) {
            .analytics-hero__inner {
                padding: 2rem 1.8rem;
            }

            .section-card {
                padding: 1.6rem;
            }

            .report-viewer__header,
            .report-viewer__footer {
                flex-direction: column;
                align-items: flex-start;
            }

            .report-chart {
                height: 280px;
            }
        }

        @media (max-width: 575.98px) {
            .analytics-reports {
                padding: 1rem 0 2.2rem;
            }

            .analytics-hero__title {
                font-size: 1.65rem;
            }

            .category-card {
                min-height: 200px;
                padding: 1.3rem;
            }

            .report-chart {
                height: 240px;
                padding: 1.1rem;
            }
        }
    </style>
@endsection

@section('content')
    @php
        $reportCategories = [
            [
                'key' => 'trial-balance',
                'name' => 'Trial Balance',
                'description' => 'Instantly reconcile debits and credits with exception tracking.',
                'icon' => 'fas fa-balance-scale',
                'trend' => '+2.3%',
                'trend_label' => 'vs last quarter',
                'trend_type' => 'positive',
            ],
            [
                'key' => 'profit-loss',
                'name' => 'Profit & Loss',
                'description' => 'Monitor revenue, expenses, and EBITDA in real-time slices.',
                'icon' => 'fas fa-chart-line',
                'trend' => '+8.1%',
                'trend_label' => 'net income growth',
                'trend_type' => 'positive',
            ],
            [
                'key' => 'balance-sheet',
                'name' => 'Balance Sheet',
                'description' => 'Track assets, liabilities, and equity snapshots across periods.',
                'icon' => 'fas fa-layer-group',
                'trend' => 'Stable',
                'trend_label' => 'leverage ratio',
                'trend_type' => 'neutral',
            ],
            [
                'key' => 'cash-flow',
                'name' => 'Cash Flow',
                'description' => 'Spot liquidity gaps with operational, investing, and financing flows.',
                'icon' => 'fas fa-water',
                'trend' => '-1.4%',
                'trend_label' => 'operating cash trend',
                'trend_type' => 'negative',
            ],
            [
                'key' => 'vendor-ledger',
                'name' => 'Vendor Ledger',
                'description' => 'View supplier balances, payment cycles, and credit utilization.',
                'icon' => 'fas fa-truck-loading',
                'trend' => '+4 days',
                'trend_label' => 'average payment term',
                'trend_type' => 'negative',
            ],
            [
                'key' => 'customer-ledger',
                'name' => 'Customer Ledger',
                'description' => 'Analyze customer receivables, aging buckets, and credit notes.',
                'icon' => 'fas fa-user-friends',
                'trend' => '-12%',
                'trend_label' => 'DSO improvement',
                'trend_type' => 'positive',
            ],
            [
                'key' => 'momo-ledger',
                'name' => 'MoMo Ledger',
                'description' => 'Consolidate mobile money inflows, payouts, and reconciliation logs.',
                'icon' => 'fas fa-mobile-alt',
                'trend' => '+18%',
                'trend_label' => 'transaction volume',
                'trend_type' => 'positive',
            ],
            [
                'key' => 'tax-returns',
                'name' => 'Tax Returns (GRA)',
                'description' => 'Generate VAT, NHIL, GETFund, and corporate filings with ease.',
                'icon' => 'fas fa-file-invoice-dollar',
                'trend' => 'Due 24 Nov',
                'trend_label' => 'next submission window',
                'trend_type' => 'neutral',
            ],
        ];

        $departments = ['Finance', 'Operations', 'Sales & Distribution', 'Procurement', 'Human Capital'];
        $currencies = ['GHS', 'USD', 'EUR', 'GBP'];
        $tableRows = [
            ['date' => '2025-10-01', 'reference' => 'INV-004512', 'description' => 'Wholesale Fuel Delivery - Navrongo Main', 'debit' => '₵120,450.00', 'credit' => '₵0.00', 'balance' => '₵320,780.00'],
            ['date' => '2025-10-02', 'reference' => 'BILL-008782', 'description' => 'Vendor Settlement - Wapuli Station', 'debit' => '₵0.00', 'credit' => '₵42,650.00', 'balance' => '₵278,130.00'],
            ['date' => '2025-10-05', 'reference' => 'JV-002345', 'description' => 'Reclassification Adjustment - Accrued VAT', 'debit' => '₵18,720.00', 'credit' => '₵0.00', 'balance' => '₵296,850.00'],
            ['date' => '2025-10-08', 'reference' => 'RCPT-006901', 'description' => 'Customer Payment - Larabanga Depot', 'debit' => '₵0.00', 'credit' => '₵75,120.00', 'balance' => '₵221,730.00'],
            ['date' => '2025-10-10', 'reference' => 'PAY-009876', 'description' => 'Payroll - Finance Department', 'debit' => '₵64,500.00', 'credit' => '₵0.00', 'balance' => '₵286,230.00'],
        ];
    @endphp

    <div class="container-fluid analytics-reports">
        <div class="analytics-hero">
            <div class="analytics-hero__inner">
                <span class="analytics-hero__eyebrow"><i class="fas fa-chart-pie"></i> Finance Intelligence</span>
                <div>
                    <h2 class="analytics-hero__title">Analytic Reports</h2>
                    <p class="analytics-hero__subtitle">
                        Explore consolidated ledgers, compliance-ready tax packs, and executive summaries in one responsive workspace. Craft board-grade visuals, export polished statements, and surface actionable insights in seconds.
                    </p>
                </div>
                <div class="analytics-hero__meta">
                    <div class="analytics-hero__meta-item">
                        <i class="fas fa-clock"></i>
                        Last synced: <strong>2 mins ago</strong>
                    </div>
                    <div class="analytics-hero__meta-item">
                        <i class="fas fa-database"></i>
                        8 report streams linked
                    </div>
                    <div class="analytics-hero__meta-item">
                        <i class="fas fa-shield-alt"></i>
                        SOC 2 Type II secured
                    </div>
                </div>
            </div>
        </div>

        <div class="section-card" id="report-categories">
            <div class="section-card__header">
                <div class="section-card__header-title">
                    <div class="avatar-sm bg-primary bg-soft rounded-circle d-flex align-items-center justify-content-center">
                        <i class="fas fa-stream text-primary"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Report Categories</h5>
                        <span><i class="fas fa-layer-group"></i> Pick a statement to explore</span>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-soft-primary" data-bs-toggle="modal" data-bs-target="#categoryManagerModal">
                        <i class="fas fa-sliders-h me-1"></i> Configure Categories
                    </button>
                    <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#categoryLibraryModal">
                        <i class="fas fa-book me-1"></i> Saved Packs
                    </button>
                </div>
            </div>
            <div class="row g-3">
                @foreach ($reportCategories as $category)
                    <div class="col-sm-6 col-lg-3">
                        <div class="category-card">
                            <div>
                                <div class="category-card__icon">
                                    <i class="{{ $category['icon'] }}"></i>
                                </div>
                                <h6 class="category-card__title">{{ $category['name'] }}</h6>
                                <p class="category-card__description">{{ $category['description'] }}</p>
                            </div>
                            <div class="category-card__footer">
                                <span class="trend {{ $category['trend_type'] }}">
                                    <i class="fas fa-sparkles"></i> {{ $category['trend'] }}
                                </span>
                                <button type="button"
                                    class="category-card__cta"
                                    data-bs-toggle="modal"
                                    data-bs-target="#categoryInsightModal"
                                    data-category-key="{{ $category['key'] }}"
                                    data-category-name="{{ $category['name'] }}"
                                    data-category-description="{{ $category['description'] }}"
                                    data-category-trend="{{ $category['trend'] }}"
                                    data-category-trend-label="{{ $category['trend_label'] }}">
                                    View insights <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="section-card h-100" id="report-filters">
                    <div class="section-card__header">
                        <div class="section-card__header-title">
                            <div class="avatar-sm bg-success bg-soft rounded-circle d-flex align-items-center justify-content-center">
                                <i class="fas fa-filter text-success"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">Filter Panel</h5>
                                <span><i class="fas fa-sliders-h"></i> Craft your dataset</span>
                            </div>
                        </div>
                        <button class="btn btn-soft-success" data-bs-toggle="modal" data-bs-target="#filterPresetModal">
                            <i class="fas fa-bookmark me-1"></i> Save Preset
                        </button>
                    </div>
                    <form class="filter-card">
                        <div class="filter-card__group">
                            <label for="dateRangeStart">Date Range</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="date" id="dateRangeStart" class="form-control" value="{{ now()->subMonths(1)->format('Y-m-d') }}">
                                </div>
                                <div class="col-6">
                                    <input type="date" id="dateRangeEnd" class="form-control" value="{{ now()->format('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                        <div class="filter-card__group">
                            <label for="departmentSelect">Department</label>
                            <select id="departmentSelect" class="form-select">
                                <option value="">All Departments</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department }}">{{ $department }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-card__group">
                            <label for="currencySelect">Currency</label>
                            <select id="currencySelect" class="form-select">
                                @foreach ($currencies as $currency)
                                    <option value="{{ $currency }}" {{ $loop->first ? 'selected' : '' }}>{{ $currency }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-card__group">
                            <label for="accountRangeStart">Account Range</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="text" id="accountRangeStart" class="form-control" placeholder="1000">
                                </div>
                                <div class="col-6">
                                    <input type="text" id="accountRangeEnd" class="form-control" placeholder="3999">
                                </div>
                            </div>
                        </div>
                        <div class="filter-card__actions">
                            <button type="button" class="btn btn-primary" id="applyFilterBtn">
                                <i class="fas fa-play me-1"></i> Run Report
                            </button>
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="fas fa-undo me-1"></i> Reset
                            </button>
                            <button type="button" class="btn btn-link text-decoration-none" data-bs-toggle="modal" data-bs-target="#filterAdvancedModal">
                                Advanced Filters
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="section-card" id="report-viewer">
                    <div class="report-viewer__header">
                        <div>
                            <h5 class="mb-1">Report Viewer</h5>
                            <span class="text-muted small"><i class="fas fa-bolt text-warning me-1"></i> Realtime analytics preview</span>
                        </div>
                        <div class="report-viewer__header-actions">
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#viewerLayoutModal">
                                <i class="fas fa-table me-1"></i> Layout
                            </button>
                            <button type="button" class="btn btn-soft-primary" data-bs-toggle="modal" data-bs-target="#exportModal">
                                <i class="fas fa-file-export me-1"></i> Export
                            </button>
                            <button type="button" class="btn btn-primary" id="toggleChartModalBtn" data-bs-toggle="modal" data-bs-target="#chartModal">
                                <i class="fas fa-chart-area me-1"></i> Expand Chart
                            </button>
                        </div>
                    </div>
                    <div class="report-chart">
                        <canvas id="reportChart"></canvas>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Reference</th>
                                    <th scope="col">Description</th>
                                    <th scope="col" class="text-end">Debit</th>
                                    <th scope="col" class="text-end">Credit</th>
                                    <th scope="col" class="text-end">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tableRows as $row)
                                    <tr>
                                        <td>{{ $row['date'] }}</td>
                                        <td class="fw-semibold">{{ $row['reference'] }}</td>
                                        <td>{{ $row['description'] }}</td>
                                        <td class="text-end text-danger">{{ $row['debit'] }}</td>
                                        <td class="text-end text-success">{{ $row['credit'] }}</td>
                                        <td class="text-end fw-semibold">{{ $row['balance'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="report-viewer__footer">
                        <div class="insight-badge">
                            <i class="fas fa-lightbulb"></i> Top Insight: Receivables improved 12% in October
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#printPreviewModal"><i class="fas fa-print me-1"></i> Print Preview</button>
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#shareModal"><i class="fas fa-share-alt me-1"></i> Share Snapshot</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Insight Modal -->
    <div class="modal fade" id="categoryInsightModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content modal-insight">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="categoryInsightTitle">Category Insight</h5>
                        <p class="text-muted mb-0" id="categoryInsightSubtitle"></p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-7">
                            <div class="report-chart" style="height: 260px;">
                                <canvas id="categoryInsightChart"></canvas>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="p-3 bg-light rounded-4 border border-dashed">
                                <h6 class="fw-bold mb-3">Key Highlights</h6>
                                <div id="categoryInsightHighlights"></div>
                            </div>
                            <div class="mt-4 d-grid gap-2">
                                <button class="btn btn-primary" id="categoryOpenViewerBtn"><i class="fas fa-play me-1"></i> Open in Viewer</button>
                                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Viewer Modal -->
    <div class="modal fade" id="categoryViewerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content modal-insight">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="categoryViewerTitle">Report Viewer</h5>
                        <p class="text-muted mb-0" id="categoryViewerSubtitle"></p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-xl-4">
                            <div class="p-3 bg-light rounded-4 border border-dashed h-100 d-flex flex-column">
                                <h6 class="fw-bold mb-3">Key Metrics</h6>
                                <div class="viewer-modal__metrics" id="categoryViewerMetrics"></div>
                                <div class="mt-4">
                                    <h6 class="fw-bold mb-2">Highlights</h6>
                                    <div class="d-flex flex-column gap-2" id="categoryViewerHighlights"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-8">
                            <div class="viewer-modal__chart mb-4">
                                <canvas id="categoryViewerChart"></canvas>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">Date</th>
                                            <th scope="col">Reference</th>
                                            <th scope="col">Description</th>
                                            <th scope="col" class="text-end">Debit</th>
                                            <th scope="col" class="text-end">Credit</th>
                                            <th scope="col" class="text-end">Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody id="categoryViewerTableBody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" id="categoryViewerLaunchBtn"><i class="fas fa-external-link-alt me-1"></i> Launch Full Dashboard</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Manager Modal -->
    <div class="modal fade" id="categoryManagerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content modal-insight">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title">Customize Report Categories</h5>
                        <p class="text-muted mb-0">Enable or disable analytic streams for your finance team.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        @foreach ($reportCategories as $category)
                            <div class="col-md-6">
                                <div class="border rounded-4 p-3 d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $category['name'] }}</h6>
                                        <p class="text-muted small mb-2">{{ $category['description'] }}</p>
                                        <span class="badge bg-primary-subtle text-primary">{{ ucfirst(str_replace('-', ' ', $category['key'])) }}</span>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="switch-{{ $category['key'] }}" checked>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-end">
                        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary ms-2"><i class="fas fa-save me-1"></i> Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Library Modal -->
    <div class="modal fade" id="categoryLibraryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-insight">
                <div class="modal-header">
                    <h5 class="modal-title">Saved Category Packs</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="data-point">
                        <span>Executive Board Pack</span>
                        <span class="badge bg-primary-subtle text-primary">Last used 1d ago</span>
                    </div>
                    <div class="data-point">
                        <span>Tax Compliance Suite</span>
                        <span class="badge bg-warning-subtle text-warning">Auto-updating</span>
                    </div>
                    <div class="data-point">
                        <span>Cash Liquidity Pulse</span>
                        <span class="badge bg-success-subtle text-success">Pinned</span>
                    </div>
                    <div class="mt-4 d-flex justify-content-between">
                        <button class="btn btn-outline-secondary">Manage Library</button>
                        <button class="btn btn-primary">Load Selection</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Preset Modal -->
    <div class="modal fade" id="filterPresetModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-insight">
                <div class="modal-header">
                    <h5 class="modal-title">Save Filter Preset</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="presetName" class="form-label">Preset name</label>
                        <input type="text" class="form-control" id="presetName" placeholder="eg. Q4 Compliance Scan">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Share with teams</label>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-primary-subtle text-primary">Finance</span>
                            <span class="badge bg-success-subtle text-success">Executive</span>
                            <span class="badge bg-secondary-subtle text-secondary">Audit</span>
                        </div>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="1" id="presetDefault" checked>
                        <label class="form-check-label" for="presetDefault">
                            Set as default for my workspace
                        </label>
                    </div>
                    <div class="text-end">
                        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary ms-2"><i class="fas fa-save me-1"></i> Save Preset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters Modal -->
    <div class="modal fade" id="filterAdvancedModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content modal-insight">
                <div class="modal-header">
                    <h5 class="modal-title">Advanced Filter Options</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Branch</label>
                            <select class="form-select">
                                <option>All branches</option>
                                <option>Navrongo Main</option>
                                <option>Wapuli</option>
                                <option>Larabanga</option>
                                <option>Amoako</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Statement Frequency</label>
                            <select class="form-select">
                                <option>Monthly</option>
                                <option>Quarterly</option>
                                <option>Annually</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Include Draft Entries</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="draftEntries" id="draftYes" value="yes" checked>
                                <label class="form-check-label" for="draftYes">Yes, include drafts</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="draftEntries" id="draftNo" value="no">
                                <label class="form-check-label" for="draftNo">No, approved entries only</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Materiality Threshold</label>
                            <input type="number" class="form-control" placeholder="10000">
                            <small class="text-muted">Only show balances exceeding this threshold.</small>
                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary ms-2"><i class="fas fa-play me-1"></i> Apply Advanced Filters</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-insight">
                <div class="modal-header">
                    <h5 class="modal-title">Export Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="list-group list-group-flush">
                        <button class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-file-pdf text-danger me-2"></i> PDF Document</span>
                            <span class="badge bg-danger-subtle text-danger">Recommended</span>
                        </button>
                        <button class="list-group-item list-group-item-action">
                            <i class="fas fa-file-excel text-success me-2"></i> Excel Workbook
                        </button>
                        <button class="list-group-item list-group-item-action">
                            <i class="fas fa-file-csv text-primary me-2"></i> CSV Dataset
                        </button>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Email distribution (optional)</label>
                        <input type="email" class="form-control" placeholder="finance.reports@company.com">
                    </div>
                    <div class="mt-4 text-end">
                        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary ms-2"><i class="fas fa-download me-1"></i> Export</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Modal -->
    <div class="modal fade" id="chartModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content modal-insight">
                <div class="modal-header">
                    <h5 class="modal-title">Expanded Chart View</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="report-chart" style="height: 420px;">
                        <canvas id="reportChartExpanded"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Layout Modal -->
    <div class="modal fade" id="viewerLayoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-insight">
                <div class="modal-header">
                    <h5 class="modal-title">Viewer Layout Options</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="layoutOptions" id="layoutSummary" checked>
                        <label class="form-check-label" for="layoutSummary">Summary cards with chart</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="layoutOptions" id="layoutTable">
                        <label class="form-check-label" for="layoutTable">Full table view</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="layoutOptions" id="layoutDual">
                        <label class="form-check-label" for="layoutDual">Dual chart & ledger split</label>
                    </div>
                    <div class="text-end mt-3">
                        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary ms-2"><i class="fas fa-check me-1"></i> Apply Layout</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Print Preview Modal -->
    <div class="modal fade" id="printPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content modal-insight">
                <div class="modal-header">
                    <h5 class="modal-title">Print Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe src="about:blank" title="Print Preview" style="width: 100%; height: 500px; border: none; border-radius: 18px; background: #f8fafc;"></iframe>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary"><i class="fas fa-print me-1"></i> Print</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Share Modal -->
    <div class="modal fade" id="shareModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-insight">
                <div class="modal-header">
                    <h5 class="modal-title">Share Snapshot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Recipient emails</label>
                        <textarea class="form-control" rows="2" placeholder="finance.director@company.com, audit.lead@company.com"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" rows="3" placeholder="Hi team, sharing the latest MoMo ledger snapshot."></textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="shareIncludeChart" checked>
                        <label class="form-check-label" for="shareIncludeChart">Attach chart visualization</label>
                    </div>
                    <div class="text-end">
                        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary ms-2"><i class="fas fa-paper-plane me-1"></i> Share Snapshot</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartPalette = {
            background: ['rgba(37, 99, 235, 0.18)', 'rgba(99, 102, 241, 0.18)'],
            border: ['rgba(37, 99, 235, 0.6)', 'rgba(99, 102, 241, 0.6)']
        };

        const baseChartConfig = {
            type: 'line',
            data: {
                labels: ['May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
                datasets: [
                    {
                        label: 'Debit Movement',
                        data: [310000, 365000, 342500, 378200, 352800, 389500],
                        fill: true,
                        borderColor: chartPalette.border[0],
                        backgroundColor: chartPalette.background[0],
                        tension: 0.35,
                        borderWidth: 3,
                        pointBackgroundColor: '#2563eb'
                    },
                    {
                        label: 'Credit Movement',
                        data: [295000, 322400, 331200, 340100, 352300, 360500],
                        fill: true,
                        borderColor: chartPalette.border[1],
                        backgroundColor: chartPalette.background[1],
                        tension: 0.35,
                        borderWidth: 3,
                        pointBackgroundColor: '#6366f1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            font: {
                                size: 12,
                                family: 'Inter, "Segoe UI", sans-serif'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        borderWidth: 0,
                        padding: 12,
                        titleFont: { size: 13, family: 'Inter, "Segoe UI", sans-serif' },
                        bodyFont: { size: 12, family: 'Inter, "Segoe UI", sans-serif' },
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed.y || 0;
                                return `${context.dataset.label}: ₵${value.toLocaleString()}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) {
                                return '₵' + value.toLocaleString();
                            }
                        },
                        grid: {
                            color: 'rgba(148, 163, 184, 0.2)',
                            borderDash: [4, 4]
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        };

        const defaultViewerRows = [
            { date: '2025-10-01', reference: 'TXN-1045', description: 'Opening balance reconciliation', debit: '₵120,450.00', credit: '₵0.00', balance: '₵320,780.00' },
            { date: '2025-10-04', reference: 'TXN-1142', description: 'Supplier settlement - Wapuli', debit: '₵0.00', credit: '₵42,650.00', balance: '₵278,130.00' },
            { date: '2025-10-08', reference: 'TXN-1260', description: 'Customer payment - Larabanga', debit: '₵0.00', credit: '₵75,120.00', balance: '₵221,730.00' },
            { date: '2025-10-12', reference: 'TXN-1305', description: 'Payroll funding - Finance', debit: '₵64,500.00', credit: '₵0.00', balance: '₵286,230.00' },
            { date: '2025-10-18', reference: 'TXN-1421', description: 'VAT remittance - GRA', debit: '₵18,720.00', credit: '₵0.00', balance: '₵267,510.00' }
        ];

        const categoryViewerData = {
            default: {
                title: 'Report Viewer',
                subtitle: 'Review detailed ledger movements and visual trends in one workspace.',
                metrics: [
                    { label: 'Total Debits', value: '₵389,500' },
                    { label: 'Total Credits', value: '₵360,500' },
                    { label: 'Net Position', value: '₵28,900' }
                ],
                highlights: [
                    {
                        icon: 'fas fa-check-circle',
                        title: 'Balanced Entries',
                        description: 'All ledgers validated with exception threshold below 2%.'
                    },
                    {
                        icon: 'fas fa-clipboard-list',
                        title: 'Compliance Ready',
                        description: 'Supporting schedules generated for audit & statutory filings.'
                    }
                ],
                chart: {
                    labels: ['May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
                    datasets: [
                        { data: [310000, 365000, 342500, 378200, 352800, 389500] },
                        { data: [295000, 322400, 331200, 340100, 352300, 360500] }
                    ]
                },
                table: defaultViewerRows
            },
            'trial-balance': {
                title: 'Trial Balance Viewer',
                subtitle: 'Instantly reconcile debits and credits with exception tracking.',
                metrics: [
                    { label: 'Active Ledgers', value: '128' },
                    { label: 'Out-of-Balance Items', value: '3' },
                    { label: 'Variance vs Prior Month', value: '+₵18,240' }
                ],
                highlights: [
                    {
                        icon: 'fas fa-scale-balanced',
                        title: 'Tight Control',
                        description: '99.7% of account balances fall within tolerance bands.'
                    },
                    {
                        icon: 'fas fa-hourglass-half',
                        title: 'Closing Speed',
                        description: 'Close cycle down to 3.2 days with automated postings.'
                    }
                ],
                chart: {
                    labels: ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                    datasets: [
                        { data: [298400, 325900, 336200, 348900, 354200, 361500] },
                        { data: [297100, 322400, 333500, 347200, 352900, 360400] }
                    ]
                },
                table: defaultViewerRows
            },
            'profit-loss': {
                title: 'Profit & Loss Viewer',
                subtitle: 'Monitor revenue, expenses, and EBITDA in real-time slices.',
                metrics: [
                    { label: 'Net Revenue', value: '₵1.28M' },
                    { label: 'Operating Margin', value: '18.4%' },
                    { label: 'EBITDA', value: '₵236K' }
                ],
                highlights: [
                    {
                        icon: 'fas fa-chart-line',
                        title: 'Growth Trend',
                        description: 'Revenue up 8.1% QoQ led by distribution channel mix.'
                    },
                    {
                        icon: 'fas fa-lightbulb',
                        title: 'Cost Efficiency',
                        description: 'Expense variance narrowed to 1.9% vs budget.'
                    }
                ],
                chart: {
                    labels: ['Q2-24', 'Q3-24', 'Q4-24', 'Q1-25'],
                    datasets: [
                        { label: 'Revenue', data: [950000, 1025000, 1084000, 1280000] },
                        { label: 'Expenses', data: [720000, 785000, 812000, 1044000] }
                    ]
                },
                table: [
                    { date: '2025-07-31', reference: 'REV-8841', description: 'Distribution revenue - Navrongo', debit: '₵0.00', credit: '₵285,500.00', balance: '₵285,500.00' },
                    { date: '2025-08-15', reference: 'COS-6615', description: 'Supply chain expenses - Wapuli', debit: '₵118,200.00', credit: '₵0.00', balance: '₵167,300.00' },
                    { date: '2025-09-09', reference: 'EXP-7721', description: 'Marketing activation - Northern cluster', debit: '₵42,840.00', credit: '₵0.00', balance: '₵124,460.00' },
                    { date: '2025-10-01', reference: 'REV-9025', description: 'Retail uplift - Larabanga depot', debit: '₵0.00', credit: '₵312,640.00', balance: '₵437,100.00' },
                    { date: '2025-10-10', reference: 'COS-6990', description: 'Staff costs - finance division', debit: '₵96,280.00', credit: '₵0.00', balance: '₵340,820.00' }
                ]
            },
            'balance-sheet': {
                title: 'Balance Sheet Viewer',
                subtitle: 'Track assets, liabilities, and equity snapshots across periods.',
                metrics: [
                    { label: 'Total Assets', value: '₵8.42M' },
                    { label: 'Total Liabilities', value: '₵4.95M' },
                    { label: 'Equity Position', value: '₵3.47M' }
                ],
                highlights: [
                    {
                        icon: 'fas fa-university',
                        title: 'Capital Adequacy',
                        description: 'Debt-to-equity ratio stable at 1.42x vs policy of 1.8x.'
                    },
                    {
                        icon: 'fas fa-sitemap',
                        title: 'Asset Mix',
                        description: '70% of assets categorized as productive operating assets.'
                    }
                ],
                chart: {
                    labels: ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
                    datasets: [
                        { label: 'Assets', data: [7540000, 7700000, 7910000, 8085000, 8240000, 8420000] },
                        { label: 'Liabilities', data: [4530000, 4620000, 4705000, 4810000, 4912000, 4950000] }
                    ]
                },
                table: defaultViewerRows
            },
            'cash-flow': {
                title: 'Cash Flow Viewer',
                subtitle: 'Spot liquidity gaps across operational, investing, and financing activities.',
                metrics: [
                    { label: 'Operating Cash', value: '₵182K' },
                    { label: 'Investing Cash', value: '-₵74K' },
                    { label: 'Financing Cash', value: '₵28K' }
                ],
                highlights: [
                    {
                        icon: 'fas fa-faucet',
                        title: 'Liquidity Pulse',
                        description: 'Net operating cash slipped 1.4% versus seasonal benchmark.'
                    },
                    {
                        icon: 'fas fa-wallet',
                        title: 'Funding Headroom',
                        description: 'Available credit lines at 62% utilization.'
                    }
                ],
                chart: {
                    labels: ['May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
                    datasets: [
                        { label: 'Cash In', data: [215000, 228400, 240100, 232800, 226400, 219700] },
                        { label: 'Cash Out', data: [189000, 204900, 215600, 223900, 231800, 217200] }
                    ]
                },
                table: defaultViewerRows
            },
            'vendor-ledger': {
                title: 'Vendor Ledger Viewer',
                subtitle: 'View supplier balances, payment cycles, and credit utilization.',
                metrics: [
                    { label: 'Open Payables', value: '₵482K' },
                    { label: 'Avg. Payment Days', value: '34 days' },
                    { label: 'Credit Utilization', value: '76%' }
                ],
                highlights: [
                    {
                        icon: 'fas fa-truck-loading',
                        title: 'Supplier Health',
                        description: 'Top 5 vendors account for 58% of open payables.'
                    },
                    {
                        icon: 'fas fa-shield-alt',
                        title: 'Risk Watch',
                        description: 'Two suppliers flagged for extended payment cycles.'
                    }
                ],
                chart: {
                    labels: ['May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
                    datasets: [
                        { label: 'Invoices', data: [92000, 101300, 110500, 118200, 123400, 129800] },
                        { label: 'Payments', data: [87000, 96500, 100200, 112400, 116800, 125600] }
                    ]
                },
                table: [
                    { date: '2025-09-02', reference: 'BILL-7812', description: 'Fuel supply - Amoako Logistics', debit: '₵0.00', credit: '₵62,400.00', balance: '₵62,400.00' },
                    { date: '2025-09-18', reference: 'PMT-9012', description: 'Partial settlement - Bububele', debit: '₵31,200.00', credit: '₵0.00', balance: '₵31,200.00' },
                    { date: '2025-10-04', reference: 'BILL-8120', description: 'Depot maintenance - Navrongo-2', debit: '₵0.00', credit: '₵28,950.00', balance: '₵60,150.00' },
                    { date: '2025-10-11', reference: 'PMT-9154', description: 'Settlement - Wapuli Supplies', debit: '₵18,700.00', credit: '₵0.00', balance: '₵41,450.00' },
                    { date: '2025-10-16', reference: 'BILL-8340', description: 'Fleet servicing - Bamvin Services', debit: '₵0.00', credit: '₵19,840.00', balance: '₵61,290.00' }
                ]
            },
            'customer-ledger': {
                title: 'Customer Ledger Viewer',
                subtitle: 'Analyze customer receivables, aging buckets, and credit notes.',
                metrics: [
                    { label: 'Outstanding AR', value: '₵612K' },
                    { label: 'DSO', value: '42 days' },
                    { label: 'Past Due (>60d)', value: '₵48K' }
                ],
                highlights: [
                    {
                        icon: 'fas fa-user-friends',
                        title: 'Receivable Health',
                        description: 'Receivables improved 12% with focused collections.'
                    },
                    {
                        icon: 'fas fa-envelope-open-text',
                        title: 'Engagement',
                        description: 'Automated reminders reduced disputes by 28%.'
                    }
                ],
                chart: {
                    labels: ['May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
                    datasets: [
                        { label: 'Collections', data: [152000, 166500, 175800, 189200, 201400, 214600] },
                        { label: 'Outstanding', data: [248000, 239400, 226300, 219100, 207800, 198500] }
                    ]
                },
                table: [
                    { date: '2025-08-22', reference: 'INV-5512', description: 'Wholesale delivery - Wiaga Depot', debit: '₵0.00', credit: '₵84,600.00', balance: '₵84,600.00' },
                    { date: '2025-09-05', reference: 'RCPT-6114', description: 'Payment received - Paga Annex', debit: '₵42,300.00', credit: '₵0.00', balance: '₵42,300.00' },
                    { date: '2025-09-28', reference: 'INV-5930', description: 'Commercial order - Navrongo Main', debit: '₵0.00', credit: '₵61,200.00', balance: '₵103,500.00' },
                    { date: '2025-10-03', reference: 'RCPT-6221', description: 'Settlement - Kintampo Transit', debit: '₵36,800.00', credit: '₵0.00', balance: '₵66,700.00' },
                    { date: '2025-10-14', reference: 'CRN-907', description: 'Credit note - Navrongo-2', debit: '₵12,450.00', credit: '₵0.00', balance: '₵54,250.00' }
                ]
            },
            'momo-ledger': {
                title: 'MoMo Ledger Viewer',
                subtitle: 'Consolidate mobile money inflows, payouts, and reconciliation logs.',
                metrics: [
                    { label: 'Transaction Volume', value: '18% ↑' },
                    { label: 'Daily Settlement', value: '₵42,600' },
                    { label: 'Avg. Ticket Size', value: '₵264' }
                ],
                highlights: [
                    {
                        icon: 'fas fa-mobile-alt',
                        title: 'Digital Uptake',
                        description: 'Mobile inflows trending upward for fourth consecutive month.'
                    },
                    {
                        icon: 'fas fa-lock',
                        title: 'Reconciliation',
                        description: 'Automated match rate sustained at 99.1%.'
                    }
                ],
                chart: {
                    labels: ['May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
                    datasets: [
                        { label: 'Inflows', data: [142000, 151400, 163200, 175900, 189400, 208700] },
                        { label: 'Outflows', data: [118200, 126400, 135900, 144800, 152600, 166200] }
                    ]
                },
                table: defaultViewerRows
            },
            'tax-returns': {
                title: 'GRA Tax Returns Viewer',
                subtitle: 'Generate VAT, NHIL, GETFund, and corporate filings with ease.',
                metrics: [
                    { label: 'VAT Due', value: '₵86,420' },
                    { label: 'NHIL & GETFund', value: '₵32,110' },
                    { label: 'Corporate Tax Accrual', value: '₵54,900' }
                ],
                highlights: [
                    {
                        icon: 'fas fa-file-invoice-dollar',
                        title: 'Submission Window',
                        description: 'Next filing due 24 Nov with draft packs ready for review.'
                    },
                    {
                        icon: 'fas fa-shield-check',
                        title: 'Validation Score',
                        description: 'Return data passes 34 compliance checks with zero exceptions.'
                    }
                ],
                chart: {
                    labels: ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                    datasets: [
                        { label: 'VAT', data: [74200, 78100, 81200, 83400, 86200, 86420] },
                        { label: 'NHIL/GETFUND', data: [27400, 28600, 29800, 30500, 31600, 32110] }
                    ]
                },
                table: defaultViewerRows
            }
        };

        const chartCanvas = document.getElementById('reportChart');
        const expandedCanvas = document.getElementById('reportChartExpanded');
        const categoryCanvas = document.getElementById('categoryInsightChart');
        const categoryViewerCanvas = document.getElementById('categoryViewerChart');

        let reportChartInstance = null;
        let reportChartExpandedInstance = null;
        let categoryChartInstance = null;
        let categoryViewerChartInstance = null;
        let categoryViewerChartOverride = null;

        const initChart = (canvas, existingInstance, overrides = null) => {
            if (!canvas) {
                return null;
            }
            if (existingInstance) {
                existingInstance.destroy();
            }
            const context = canvas.getContext('2d');
            const config = JSON.parse(JSON.stringify(baseChartConfig));
            if (overrides?.labels) {
                config.data.labels = overrides.labels;
            }
            if (overrides?.datasets) {
                overrides.datasets.forEach((dataset, index) => {
                    if (!config.data.datasets[index]) {
                        config.data.datasets[index] = {};
                    }
                    config.data.datasets[index] = {
                        ...config.data.datasets[index],
                        ...dataset
                    };
                });
            }
            return new Chart(context, config);
        };

        const initAllCharts = () => {
            if (chartCanvas) {
                reportChartInstance = initChart(chartCanvas, reportChartInstance);
            }
            if (expandedCanvas) {
                reportChartExpandedInstance = initChart(expandedCanvas, reportChartExpandedInstance);
            }
        };

        document.addEventListener('DOMContentLoaded', initAllCharts);

        document.getElementById('chartModal')?.addEventListener('shown.bs.modal', () => {
            reportChartExpandedInstance = initChart(expandedCanvas, reportChartExpandedInstance);
        });

        document.getElementById('categoryInsightModal')?.addEventListener('shown.bs.modal', () => {
            categoryChartInstance = initChart(categoryCanvas, categoryChartInstance);
        });

        const insightModal = document.getElementById('categoryInsightModal');
        const categoryViewerModal = document.getElementById('categoryViewerModal');
        const categoryViewerTitle = document.getElementById('categoryViewerTitle');
        const categoryViewerSubtitle = document.getElementById('categoryViewerSubtitle');
        const categoryViewerMetrics = document.getElementById('categoryViewerMetrics');
        const categoryViewerHighlights = document.getElementById('categoryViewerHighlights');
        const categoryViewerTableBody = document.getElementById('categoryViewerTableBody');

        insightModal?.addEventListener('show.bs.modal', event => {
            const trigger = event.relatedTarget;
            if (!trigger) {
                return;
            }
            const name = trigger.getAttribute('data-category-name');
            const description = trigger.getAttribute('data-category-description');
            const trend = trigger.getAttribute('data-category-trend');
            const trendLabel = trigger.getAttribute('data-category-trend-label');
            const key = trigger.getAttribute('data-category-key');

            document.getElementById('categoryInsightTitle').textContent = name;
            document.getElementById('categoryInsightSubtitle').textContent = description;
            insightModal.setAttribute('data-category-key', key || 'default');
            insightModal.setAttribute('data-category-name', name || 'Report Viewer');

            const highlights = [
                `${trend} change (${trendLabel})`,
                'Forecast accuracy: 97.2%',
                'Data quality score: A+'
            ];

            const highlightContainer = document.getElementById('categoryInsightHighlights');
            highlightContainer.innerHTML = '';
            highlights.forEach(item => {
                const node = document.createElement('div');
                node.classList.add('data-point');
                node.innerHTML = `<span><i class="fas fa-check-circle text-success me-2"></i>${item}</span><span class="badge bg-light text-dark">Verified</span>`;
                highlightContainer.appendChild(node);
            });
        });

        const renderCategoryViewer = key => {
            const fallbackKey = key && categoryViewerData[key] ? key : 'default';
            const metadata = categoryViewerData[fallbackKey];
            const nameFromInsight = insightModal?.getAttribute('data-category-name');

            if (categoryViewerTitle) {
                categoryViewerTitle.textContent = metadata.title || nameFromInsight || 'Report Viewer';
            }
            if (categoryViewerSubtitle) {
                categoryViewerSubtitle.textContent = metadata.subtitle || '';
            }

            if (categoryViewerMetrics) {
                categoryViewerMetrics.innerHTML = '';
                metadata.metrics?.forEach(metric => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'viewer-modal__metric';
                    wrapper.innerHTML = `
                        <div>
                            <span class="viewer-modal__metric-label">${metric.label || ''}</span>
                            <span class="viewer-modal__metric-value">${metric.value || ''}</span>
                        </div>
                        ${metric.delta ? `<span class="badge bg-light text-dark">${metric.delta}</span>` : ''}
                    `;
                    categoryViewerMetrics.appendChild(wrapper);
                });
            }

            if (categoryViewerHighlights) {
                categoryViewerHighlights.innerHTML = '';
                metadata.highlights?.forEach(highlight => {
                    const container = document.createElement('div');
                    container.className = 'viewer-modal__highlight';
                    container.innerHTML = `
                        <div class="viewer-modal__highlight-icon">
                            <i class="${highlight.icon || 'fas fa-info-circle'}"></i>
                        </div>
                        <div>
                            <div class="fw-semibold text-dark">${highlight.title || ''}</div>
                            <p class="text-muted mb-0 small">${highlight.description || ''}</p>
                        </div>
                    `;
                    categoryViewerHighlights.appendChild(container);
                });
            }

            if (categoryViewerTableBody) {
                categoryViewerTableBody.innerHTML = '';
                (metadata.table || []).forEach(row => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${row.date || ''}</td>
                        <td class="fw-semibold">${row.reference || ''}</td>
                        <td>${row.description || ''}</td>
                        <td class="text-end text-danger">${row.debit || '₵0.00'}</td>
                        <td class="text-end text-success">${row.credit || '₵0.00'}</td>
                        <td class="text-end fw-semibold">${row.balance || '₵0.00'}</td>
                    `;
                    categoryViewerTableBody.appendChild(tr);
                });
            }

            categoryViewerChartOverride = metadata.chart || null;
        };

        document.getElementById('categoryOpenViewerBtn')?.addEventListener('click', () => {
            const key = insightModal?.getAttribute('data-category-key');
            renderCategoryViewer(key);

            if (insightModal) {
                const insightBootstrap = bootstrap.Modal.getInstance(insightModal);
                const viewerBootstrap = categoryViewerModal ? bootstrap.Modal.getOrCreateInstance(categoryViewerModal) : null;
                if (viewerBootstrap) {
                    if (insightBootstrap) {
                        insightModal.addEventListener(
                            'hidden.bs.modal',
                            () => viewerBootstrap.show(),
                            { once: true }
                        );
                        insightBootstrap.hide();
                    } else {
                        viewerBootstrap.show();
                    }
                }
            }
        });

        categoryViewerModal?.addEventListener('shown.bs.modal', () => {
            categoryViewerChartInstance = initChart(categoryViewerCanvas, categoryViewerChartInstance, categoryViewerChartOverride);
        });

        document.getElementById('applyFilterBtn')?.addEventListener('click', () => {
            const toast = document.createElement('div');
            toast.className = 'toast align-items-center text-bg-primary border-0 position-fixed top-0 end-0 m-3';
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-circle-notch fa-spin me-2"></i> Running report with current filters...
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>`;
            document.body.appendChild(toast);
            const bsToast = new bootstrap.Toast(toast, { delay: 2400 });
            bsToast.show();
            toast.addEventListener('hidden.bs.toast', () => toast.remove());
        });
    </script>
@endsection
