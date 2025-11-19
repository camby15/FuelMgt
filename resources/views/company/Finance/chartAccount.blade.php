@extends('layouts.vertical', ['page_title' => 'Chart of Accounts'])

@section('css')
    <style>
        .page-title-box {
            background: linear-gradient(135deg, rgba(15, 118, 110, 0.08), rgba(37, 99, 235, 0.08));
            border-radius: 1.25rem;
            padding: 1.75rem 2rem;
            border: 1px solid rgba(15, 23, 42, 0.05);
        }

        .page-title-box .page-title {
            margin-bottom: 0.35rem;
        }

        :root {
            --coa-gradient-start: #031739;
            --coa-gradient-mid: #0a3a8a;
            --coa-gradient-end: #041a45;
            --coa-surface: #f6f8ff;
            --coa-shadow-wrapper: 0 22px 40px rgba(3, 26, 67, 0.28);
        }

        .coa-summary-wrapper {
            background: linear-gradient(135deg, var(--coa-gradient-start) 0%, var(--coa-gradient-mid) 55%, var(--coa-gradient-end) 100%);
            padding: 1px;
            border-radius: 24px;
            box-shadow: var(--coa-shadow-wrapper);
        }

        .coa-summary-surface {
            background: var(--coa-surface);
            border-radius: 23px;
            padding: 2rem 2.4rem;
        }

        .coa-summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.4rem;
        }

        .coa-summary-card {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 0.7rem;
            padding: 1.6rem 1.75rem;
            border-radius: 22px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 20px 42px rgba(7, 37, 96, 0.22);
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .coa-summary-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.35), transparent 65%);
            opacity: 0;
            transition: opacity 0.25s ease;
        }

        .coa-summary-card:hover,
        .coa-summary-card:focus-within {
            transform: translateY(-4px);
            box-shadow: 0 26px 52px rgba(7, 37, 96, 0.3);
        }

        .coa-summary-card:hover::after,
        .coa-summary-card:focus-within::after {
            opacity: 1;
        }

        .coa-summary-card__icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.18);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.45rem;
            backdrop-filter: blur(8px);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.24);
            color: inherit;
        }

        .coa-summary-card__content {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
        }

        .coa-summary-card__label {
            font-size: 0.78rem;
            letter-spacing: 0.24em;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            opacity: 0.86;
        }

        .coa-summary-card__value {
            font-size: 1.9rem;
            font-weight: 700;
            letter-spacing: 0.05em;
        }

        .coa-summary-card__meta {
            font-size: 0.82rem;
            letter-spacing: 0.08em;
        }

        .coa-summary-card--primary {
            background: linear-gradient(120deg, rgba(15, 60, 130, 0.96) 0%, rgba(48, 141, 255, 0.92) 58%, rgba(5, 30, 78, 0.9) 100%);
            color: #ffffff;
        }

        .coa-summary-card--sunset {
            background: linear-gradient(118deg, rgba(255, 138, 92, 0.96) 0%, rgba(255, 79, 129, 0.92) 58%, rgba(133, 32, 69, 0.92) 100%);
            color: #ffffff;
        }

        .coa-summary-card--teal {
            background: linear-gradient(118deg, rgba(43, 192, 167, 0.95) 0%, rgba(74, 217, 193, 0.9) 52%, rgba(16, 118, 105, 0.9) 100%);
            color: #063940;
        }

        .coa-summary-card--magenta {
            background: linear-gradient(118deg, rgba(217, 70, 239, 0.95) 0%, rgba(147, 51, 234, 0.9) 52%, rgba(87, 22, 134, 0.9) 100%);
            color: #ffffff;
        }

        .coa-summary-card--teal .coa-summary-card__label,
        .coa-summary-card--teal .coa-summary-card__meta {
            color: rgba(6, 57, 64, 0.7);
        }

        @media (max-width: 992px) {
            .coa-summary-surface {
                padding: 1.8rem;
            }
        }

        @media (max-width: 768px) {
            .coa-summary-surface {
                padding: 1.6rem 1.4rem;
            }

            .coa-summary-card {
                padding: 1.45rem 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .coa-summary-wrapper {
                border-radius: 20px;
            }

            .coa-summary-surface {
                padding: 1.4rem;
            }

            .coa-summary-grid {
                gap: 1.1rem;
            }
        }

        .tree-card {
            border-radius: 1.25rem;
            border: 1px solid rgba(15, 23, 42, 0.05);
            box-shadow: 0 1rem 2.5rem rgba(15, 23, 42, 0.08);
        }

        .tree-search .input-group-text {
            border-radius: 0.85rem 0 0 0.85rem;
            background: transparent;
            border: none;
            padding-left: 0.25rem;
        }

        .tree-search .form-control-modern {
            border-radius: 0 0.85rem 0.85rem 0;
        }

        .filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.35rem 0.85rem;
            border-radius: 2rem;
            border: 1px solid transparent;
            background-color: rgba(59, 130, 246, 0.12);
            color: var(--bs-body-color);
            font-weight: 500;
            transition: all 0.25s ease;
        }

        .filter-chip:hover,
        .filter-chip.active {
            background-color: var(--bs-primary);
            color: #fff;
            border-color: var(--bs-primary);
        }

        .accounts-tree {
            background: linear-gradient(145deg, rgba(15, 118, 110, 0.05), rgba(30, 64, 175, 0.04));
            border-radius: 1.25rem;
            border: 1px solid rgba(15, 23, 42, 0.06);
            padding: 0.25rem 0.75rem 1.5rem;
        }

        .tree-toggle {
            border: none;
            background: rgba(15, 118, 110, 0.15);
            color: var(--bs-primary);
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .tree-toggle.collapsed {
            background: rgba(37, 99, 235, 0.15);
        }

        .tree-item {
            padding: 0.75rem 0.5rem;
            border-radius: 0.85rem;
            transition: background 0.2s ease;
        }

        .tree-item:hover {
            background: rgba(148, 163, 184, 0.18);
        }

        .tree-children {
            border-left: 2px dashed rgba(100, 116, 139, 0.35);
            margin-left: 1.5rem;
            padding-left: 1.25rem;
        }

        .account-leaf {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.75rem 0.85rem;
            border-radius: 0.85rem;
            background-color: rgba(248, 250, 252, 0.8);
            border: 1px solid rgba(15, 23, 42, 0.04);
            transition: all 0.2s ease;
        }

        .account-leaf:hover {
            transform: translateX(5px);
            background-color: rgba(59, 130, 246, 0.15);
        }

        .account-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.25rem 0.7rem;
            border-radius: 2rem;
            background: rgba(30, 64, 175, 0.08);
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-soft-success {
            background-color: rgba(16, 185, 129, 0.18);
            color: #14532d;
        }

        .badge-soft-warning {
            background-color: rgba(250, 204, 21, 0.2);
            color: #854d0e;
        }

        .badge-soft-danger {
            background-color: rgba(248, 113, 113, 0.2);
            color: #991b1b;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.9rem;
            border-radius: 1rem;
            border: 1px dashed rgba(30, 64, 175, 0.2);
            background-color: rgba(248, 250, 252, 0.75);
        }

        .legend-dot {
            width: 0.875rem;
            height: 0.875rem;
            border-radius: 50%;
        }

        .insight-card {
            border: none;
            border-radius: 1rem;
            background: linear-gradient(135deg, rgba(15, 118, 110, 0.07), rgba(37, 99, 235, 0.05));
            box-shadow: 0 0.75rem 2rem rgba(15, 23, 42, 0.1);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .insight-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 1.1rem 2.5rem rgba(15, 23, 42, 0.14);
        }

        .timeline-item {
            position: relative;
            padding-left: 1.85rem;
            margin-bottom: 1.75rem;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0.35rem;
            top: 0.35rem;
            width: 0.75rem;
            height: 0.75rem;
            border-radius: 50%;
            background: var(--bs-primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .timeline-item::after {
            content: '';
            position: absolute;
            left: 0.7rem;
            top: 1.6rem;
            bottom: -1.6rem;
            width: 2px;
            background: rgba(100, 116, 139, 0.25);
        }

        .timeline-item:last-child::after {
            display: none;
        }

        .modal-header,
        .modal-footer {
            border-color: rgba(15, 23, 42, 0.05);
        }

        .modal-title {
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .coa-field {
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
            margin-bottom: 1.15rem;
        }

        .coa-field:last-child {
            margin-bottom: 0;
        }

        .coa-field label {
            font-weight: 600;
            letter-spacing: 0.02em;
            color: var(--bs-body-color);
        }

        .form-control-modern,
        .form-select-modern,
        .form-textarea-modern {
            border-radius: 0.85rem;
            border: 1px solid rgba(15, 23, 42, 0.12);
            background-color: rgba(248, 250, 252, 0.95);
            padding: 0.65rem 0.9rem;
            transition: all 0.3s ease;
            box-shadow: none;
        }

        .form-select-modern {
            padding-right: 2.5rem;
        }

        .form-textarea-modern {
            min-height: 120px;
            resize: vertical;
        }

        .form-control-modern:focus,
        .form-select-modern:focus,
        .form-textarea-modern:focus {
            border-color: var(--bs-primary);
            background-color: #fff;
            box-shadow: 0 0 0 0.2rem rgba(13, 148, 136, 0.12);
        }

        ::placeholder {
            color: rgba(100, 116, 139, 0.75) !important;
        }

        .coa-switch-card {
            border-radius: 1rem;
            background: linear-gradient(135deg, rgba(13, 148, 136, 0.12), rgba(37, 99, 235, 0.12));
            border: 1px solid rgba(15, 23, 42, 0.06);
            padding: 1.25rem;
        }

        .coa-switch-card p {
            margin-bottom: 0;
        }

        .switch-pill .form-check-input {
            width: 3.25rem;
            height: 1.5rem;
            border-radius: 99px;
            border: 1px solid rgba(15, 23, 42, 0.15);
            background-size: 60% 60%;
        }

        .switch-pill .form-check-input:checked {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        @media (max-width: 991.98px) {
            .stat-card {
                margin-bottom: 1rem;
            }

            .page-title-box {
                padding: 1.35rem 1.5rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>
                        <h4 class="page-title mb-1">Chart of Accounts</h4>
                        <p class="text-muted mb-0">Organize and monitor the financial structure of your business in one intuitive view.</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#newCategoryModal">
                            <i class="ri-folders-line me-1"></i> Add Category
                        </button>
                        <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#newGroupModal">
                            <i class="ri-node-tree me-1"></i> Add Group
                        </button>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAccountModal">
                            <i class="ri-add-line me-1"></i> Add Account
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @php
            $coaSummaryCards = [
                [
                    'label' => 'Total Accounts',
                    'value' => number_format(284),
                    'meta' => 'Up by 12 this month',
                    'icon' => 'ri-bank-card-2-line',
                    'variant' => 'primary',
                ],
                [
                    'label' => 'Active Categories',
                    'value' => number_format(6),
                    'meta' => 'All frameworks aligned',
                    'icon' => 'ri-line-chart-line',
                    'variant' => 'sunset',
                ],
                [
                    'label' => 'Cash Accounts',
                    'value' => number_format(38),
                    'meta' => 'Tracked across 5 currencies',
                    'icon' => 'ri-exchange-dollar-line',
                    'variant' => 'teal',
                ],
                [
                    'label' => 'Pending Reviews',
                    'value' => number_format(5),
                    'meta' => 'Reconciliation scheduled',
                    'icon' => 'ri-alert-fill',
                    'variant' => 'magenta',
                ],
            ];
        @endphp

        <div class="coa-summary-wrapper mb-4">
            <div class="coa-summary-surface">
                <div class="coa-summary-grid">
                    @foreach ($coaSummaryCards as $card)
                        <div class="coa-summary-card coa-summary-card--{{ $card['variant'] }}">
                            <span class="coa-summary-card__icon">
                                <i class="{{ $card['icon'] }}"></i>
                            </span>
                            <div class="coa-summary-card__content">
                                <span class="coa-summary-card__label">{{ $card['label'] }}</span>
                                <span class="coa-summary-card__value">{{ $card['value'] }}</span>
                                @if (!empty($card['meta']))
                                    <span class="coa-summary-card__meta">{{ $card['meta'] }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xxl-5 col-xl-6">
                <div class="card tree-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">Accounts Structure</h5>
                            <button class="btn btn-soft-primary btn-sm">
                                <i class="ri-download-2-line me-1"></i> Export Tree
                            </button>
                        </div>
                        <div class="tree-search mb-3">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ri-search-line text-muted"></i>
                                </span>
                                <input type="search" class="form-control form-control-modern" placeholder="Quick search by account code or name">
                                <button class="btn btn-outline-secondary">Advanced Filters</button>
                            </div>
                        </div>
                        <div class="mb-3 d-flex flex-wrap gap-2">
                            <span class="filter-chip active"><i class="ri-stack-line"></i> All</span>
                            <span class="filter-chip"><i class="ri-money-dollar-circle-line"></i> Cash Accounts</span>
                            <span class="filter-chip"><i class="ri-line-chart-line"></i> Revenue</span>
                            <span class="filter-chip"><i class="ri-arrow-right-up-line"></i> Recently Added</span>
                        </div>

                        <div class="accounts-tree">
                            <div class="tree-item">
                                <div class="d-flex align-items-center gap-2">
                                    <button class="tree-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#categoryAssets" aria-expanded="true">
                                        <i class="ri-arrow-down-s-line"></i>
                                    </button>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0">Assets</h6>
                                                <small class="text-muted">Total balance: GHS 2,480,000</small>
                                            </div>
                                            <span class="badge badge-soft-success">82 accounts</span>
                                        </div>
                                    </div>
                                </div>
                                <div id="categoryAssets" class="collapse show mt-3">
                                    <div class="tree-children">
                                        <div class="tree-item">
                                            <div class="d-flex align-items-center gap-2">
                                                <button class="tree-toggle collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#groupCurrentAssets" aria-expanded="false">
                                                    <i class="ri-add-line"></i>
                                                </button>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="mb-0">Current Assets</h6>
                                                            <small class="text-muted">Short-term resources</small>
                                                        </div>
                                                        <span class="account-chip"><i class="ri-honour-line"></i> 24 accounts</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="groupCurrentAssets" class="collapse mt-2">
                                                <div class="tree-children">
                                                    <div class="account-leaf mb-2">
                                                        <div>
                                                            <span class="fw-semibold">1001 · Cash on Hand</span>
                                                            <div class="text-muted small">Primary vault account</div>
                                                        </div>
                                                        <span class="badge badge-soft-success">Cash</span>
                                                    </div>
                                                    <div class="account-leaf mb-2">
                                                        <div>
                                                            <span class="fw-semibold">1012 · Bank - Main Branch</span>
                                                            <div class="text-muted small">Corporate operating account</div>
                                                        </div>
                                                        <span class="badge badge-soft-success">Cash</span>
                                                    </div>
                                                    <div class="account-leaf">
                                                        <div>
                                                            <span class="fw-semibold">1104 · Accounts Receivable</span>
                                                            <div class="text-muted small">Outstanding customer invoices</div>
                                                        </div>
                                                        <span class="badge badge-soft-warning">Control</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tree-item mt-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <button class="tree-toggle collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#groupBankAccounts" aria-expanded="false">
                                                    <i class="ri-add-line"></i>
                                                </button>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="mb-0">Bank Accounts</h6>
                                                            <small class="text-muted">Multi-currency banking</small>
                                                        </div>
                                                        <span class="account-chip"><i class="ri-global-line"></i> 8 accounts</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="groupBankAccounts" class="collapse mt-2">
                                                <div class="tree-children">
                                                    <div class="account-leaf mb-2">
                                                        <div>
                                                            <span class="fw-semibold">1020 · GHS Current Account</span>
                                                            <div class="text-muted small">Primary settlement account</div>
                                                        </div>
                                                        <span class="badge badge-soft-success">Cash</span>
                                                    </div>
                                                    <div class="account-leaf mb-2">
                                                        <div>
                                                            <span class="fw-semibold">1023 · USD Settlement</span>
                                                            <div class="text-muted small">International receivables</div>
                                                        </div>
                                                        <span class="badge badge-soft-success">Cash</span>
                                                    </div>
                                                    <div class="account-leaf">
                                                        <div>
                                                            <span class="fw-semibold">1029 · Petty Cash</span>
                                                            <div class="text-muted small">Head office petty cash float</div>
                                                        </div>
                                                        <span class="badge badge-soft-success">Cash</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tree-item mt-3">
                                <div class="d-flex align-items-center gap-2">
                                    <button class="tree-toggle collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#categoryLiabilities" aria-expanded="false">
                                        <i class="ri-add-line"></i>
                                    </button>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0">Liabilities</h6>
                                                <small class="text-muted">Obligations and payables</small>
                                            </div>
                                            <span class="badge badge-soft-warning">54 accounts</span>
                                        </div>
                                    </div>
                                </div>
                                <div id="categoryLiabilities" class="collapse mt-3">
                                    <div class="tree-children">
                                        <div class="tree-item">
                                            <div class="d-flex align-items-center gap-2">
                                                <button class="tree-toggle collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#groupPayables" aria-expanded="false">
                                                    <i class="ri-add-line"></i>
                                                </button>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="mb-0">Accounts Payable</h6>
                                                            <small class="text-muted">Supplier obligations</small>
                                                        </div>
                                                        <span class="account-chip"><i class="ri-briefcase-4-line"></i> 12 accounts</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="groupPayables" class="collapse mt-2">
                                                <div class="tree-children">
                                                    <div class="account-leaf mb-2">
                                                        <div>
                                                            <span class="fw-semibold">2001 · Accounts Payable</span>
                                                            <div class="text-muted small">General supplier invoices</div>
                                                        </div>
                                                        <span class="badge badge-soft-danger">Control</span>
                                                    </div>
                                                    <div class="account-leaf">
                                                        <div>
                                                            <span class="fw-semibold">2010 · Accrued Expenses</span>
                                                            <div class="text-muted small">Month-end accruals</div>
                                                        </div>
                                                        <span class="badge badge-soft-warning">Accrual</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tree-item mt-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <button class="tree-toggle collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#groupLoans" aria-expanded="false">
                                                    <i class="ri-add-line"></i>
                                                </button>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="mb-0">Loans & Financing</h6>
                                                            <small class="text-muted">Long-term arrangements</small>
                                                        </div>
                                                        <span class="account-chip"><i class="ri-secure-payment-line"></i> 9 accounts</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="groupLoans" class="collapse mt-2">
                                                <div class="tree-children">
                                                    <div class="account-leaf mb-2">
                                                        <div>
                                                            <span class="fw-semibold">2201 · Bank Loan - GCB</span>
                                                            <div class="text-muted small">5-year facility</div>
                                                        </div>
                                                        <span class="badge badge-soft-warning">Long-term</span>
                                                    </div>
                                                    <div class="account-leaf">
                                                        <div>
                                                            <span class="fw-semibold">2210 · Deferred Revenue</span>
                                                            <div class="text-muted small">Unearned income</div>
                                                        </div>
                                                        <span class="badge badge-soft-warning">Deferred</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tree-item mt-3">
                                <div class="d-flex align-items-center gap-2">
                                    <button class="tree-toggle collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#categoryIncome" aria-expanded="false">
                                        <i class="ri-add-line"></i>
                                    </button>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0">Income</h6>
                                                <small class="text-muted">Revenue generating accounts</small>
                                            </div>
                                            <span class="badge badge-soft-success">74 accounts</span>
                                        </div>
                                    </div>
                                </div>
                                <div id="categoryIncome" class="collapse mt-3">
                                    <div class="tree-children">
                                        <div class="tree-item">
                                            <div class="d-flex align-items-center gap-2">
                                                <button class="tree-toggle collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#groupSales" aria-expanded="false">
                                                    <i class="ri-add-line"></i>
                                                </button>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="mb-0">Sales Revenue</h6>
                                                            <small class="text-muted">Product & service income</small>
                                                        </div>
                                                        <span class="account-chip"><i class="ri-bar-chart-2-line"></i> 18 accounts</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="groupSales" class="collapse mt-2">
                                                <div class="tree-children">
                                                    <div class="account-leaf mb-2">
                                                        <div>
                                                            <span class="fw-semibold">4000 · Fuel Sales</span>
                                                            <div class="text-muted small">Retail pump revenue</div>
                                                        </div>
                                                        <span class="badge badge-soft-success">Core</span>
                                                    </div>
                                                    <div class="account-leaf">
                                                        <div>
                                                            <span class="fw-semibold">4015 · Lubricant Sales</span>
                                                            <div class="text-muted small">Premium lubricants</div>
                                                        </div>
                                                        <span class="badge badge-soft-success">Core</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tree-item mt-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <button class="tree-toggle collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#groupOtherIncome" aria-expanded="false">
                                                    <i class="ri-add-line"></i>
                                                </button>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="mb-0">Other Operating Income</h6>
                                                            <small class="text-muted">Non-core revenue streams</small>
                                                        </div>
                                                        <span class="account-chip"><i class="ri-star-smile-line"></i> 6 accounts</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="groupOtherIncome" class="collapse mt-2">
                                                <div class="tree-children">
                                                    <div class="account-leaf mb-2">
                                                        <div>
                                                            <span class="fw-semibold">4201 · Rental Income</span>
                                                            <div class="text-muted small">Leased asset revenue</div>
                                                        </div>
                                                        <span class="badge badge-soft-success">Recurring</span>
                                                    </div>
                                                    <div class="account-leaf">
                                                        <div>
                                                            <span class="fw-semibold">4210 · Fuel Surcharge</span>
                                                            <div class="text-muted small">Surcharge adjustments</div>
                                                        </div>
                                                        <span class="badge badge-soft-warning">Variable</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tree-item mt-3">
                                <div class="d-flex align-items-center gap-2">
                                    <button class="tree-toggle collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#categoryExpenses" aria-expanded="false">
                                        <i class="ri-add-line"></i>
                                    </button>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0">Expenses</h6>
                                                <small class="text-muted">Operating costs & overheads</small>
                                            </div>
                                            <span class="badge badge-soft-danger">74 accounts</span>
                                        </div>
                                    </div>
                                </div>
                                <div id="categoryExpenses" class="collapse mt-3">
                                    <div class="tree-children">
                                        <div class="tree-item">
                                            <div class="d-flex align-items-center gap-2">
                                                <button class="tree-toggle collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#groupOperations" aria-expanded="false">
                                                    <i class="ri-add-line"></i>
                                                </button>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="mb-0">Operations</h6>
                                                            <small class="text-muted">Station-level expenses</small>
                                                        </div>
                                                        <span class="account-chip"><i class="ri-fire-line"></i> 19 accounts</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="groupOperations" class="collapse mt-2">
                                                <div class="tree-children">
                                                    <div class="account-leaf mb-2">
                                                        <div>
                                                            <span class="fw-semibold">5008 · Fuel Handling Costs</span>
                                                            <div class="text-muted small">Daily dispensing expenses</div>
                                                        </div>
                                                        <span class="badge badge-soft-warning">Variable</span>
                                                    </div>
                                                    <div class="account-leaf">
                                                        <div>
                                                            <span class="fw-semibold">5014 · Station Utilities</span>
                                                            <div class="text-muted small">Power & water</div>
                                                        </div>
                                                        <span class="badge badge-soft-warning">Fixed</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tree-item mt-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <button class="tree-toggle collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#groupAdministration" aria-expanded="false">
                                                    <i class="ri-add-line"></i>
                                                </button>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="mb-0">Administration</h6>
                                                            <small class="text-muted">Corporate overheads</small>
                                                        </div>
                                                        <span class="account-chip"><i class="ri-user-settings-line"></i> 23 accounts</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="groupAdministration" class="collapse mt-2">
                                                <div class="tree-children">
                                                    <div class="account-leaf mb-2">
                                                        <div>
                                                            <span class="fw-semibold">5200 · Staff Salaries</span>
                                                            <div class="text-muted small">Payroll expenses</div>
                                                        </div>
                                                        <span class="badge badge-soft-danger">Fixed</span>
                                                    </div>
                                                    <div class="account-leaf">
                                                        <div>
                                                            <span class="fw-semibold">5215 · Professional Fees</span>
                                                            <div class="text-muted small">External consultants</div>
                                                        </div>
                                                        <span class="badge badge-soft-warning">Variable</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-7 col-xl-6">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="card tree-card h-100">
                            <div class="card-body">
                                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                                    <div>
                                        <h5 class="card-title mb-1">Account Insights</h5>
                                        <p class="text-muted mb-0">Monitor performance with real-time snapshots.</p>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-soft-secondary btn-sm"><i class="ri-refresh-line me-1"></i>Refresh</button>
                                        <button class="btn btn-soft-primary btn-sm"><i class="ri-settings-3-line me-1"></i>Configure</button>
                                    </div>
                                </div>
                                <div class="row g-3 mt-3">
                                    <div class="col-md-4">
                                        <div class="insight-card p-3 h-100">
                                            <h6 class="mb-1">Top Performing Group</h6>
                                            <p class="text-muted small mb-2">Current Assets</p>
                                            <h4 class="mb-0">GHS 1.4M</h4>
                                            <span class="badge bg-success-subtle text-success mt-2"><i class="ri-arrow-up-line me-1"></i> +6.4%</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="insight-card p-3 h-100">
                                            <h6 class="mb-1">Accounts Needing Review</h6>
                                            <p class="text-muted small mb-2">Inactive or unassigned</p>
                                            <h4 class="mb-0">12</h4>
                                            <span class="badge bg-warning-subtle text-warning mt-2"><i class="ri-alert-line me-1"></i>Action required</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="insight-card p-3 h-100">
                                            <h6 class="mb-1">Cash Account Coverage</h6>
                                            <p class="text-muted small mb-2">Across all stations</p>
                                            <h4 class="mb-0">92%</h4>
                                            <span class="badge bg-info-subtle text-info mt-2"><i class="ri-equalizer-line me-1"></i>Balanced</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h6 class="mb-3">Recently Updated Accounts</h6>
                                    <div class="table-responsive">
                                        <table class="table align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Account</th>
                                                    <th>Group</th>
                                                    <th>Currency</th>
                                                    <th>Status</th>
                                                    <th class="text-end">Updated</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <strong>1001 · Cash on Hand</strong>
                                                        <div class="text-muted small">Vault</div>
                                                    </td>
                                                    <td>Current Assets</td>
                                                    <td>
                                                        <span class="badge bg-primary-subtle text-primary">GHS</span>
                                                    </td>
                                                    <td><span class="badge badge-soft-success">Active</span></td>
                                                    <td class="text-end text-muted small">5 mins ago</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <strong>2010 · Accrued Expenses</strong>
                                                        <div class="text-muted small">Month-end accruals</div>
                                                    </td>
                                                    <td>Accounts Payable</td>
                                                    <td>
                                                        <span class="badge bg-info-subtle text-info">USD</span>
                                                    </td>
                                                    <td><span class="badge badge-soft-warning">Monitoring</span></td>
                                                    <td class="text-end text-muted small">32 mins ago</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <strong>4000 · Fuel Sales</strong>
                                                        <div class="text-muted small">Retail pump revenue</div>
                                                    </td>
                                                    <td>Sales Revenue</td>
                                                    <td>
                                                        <span class="badge bg-success-subtle text-success">USD</span>
                                                    </td>
                                                    <td><span class="badge badge-soft-success">Active</span></td>
                                                    <td class="text-end text-muted small">1 hour ago</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card tree-card h-100">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Governance Timeline</h5>
                                <div class="timeline-item">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="mb-1">Q4 Review Cycle Initiated</h6>
                                            <p class="text-muted mb-0 small">All cash accounts to be reconciled ahead of audit season.</p>
                                        </div>
                                        <span class="badge bg-primary-subtle text-primary">Now</span>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="mb-1">New Group Template Published</h6>
                                            <p class="text-muted mb-0 small">Retail expansion group added under Assets.</p>
                                        </div>
                                        <span class="badge bg-success-subtle text-success">1 day ago</span>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="mb-1">Compliance Notice</h6>
                                            <p class="text-muted mb-0 small">Liability accounts must be tagged with maturity date.</p>
                                        </div>
                                        <span class="badge bg-warning-subtle text-warning">3 days ago</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Classification Guide</h5>
                                <div class="d-flex flex-column gap-3">
                                    <div class="legend-item">
                                        <span class="legend-dot" style="background: rgba(16, 185, 129, 0.85);"></span>
                                        <div>
                                            <h6 class="mb-1">Cash & Cash Equivalents</h6>
                                            <p class="text-muted small mb-0">Mark accounts that directly impact immediate liquidity for real-time dashboards.</p>
                                        </div>
                                    </div>
                                    <div class="legend-item">
                                        <span class="legend-dot" style="background: rgba(59, 130, 246, 0.85);"></span>
                                        <div>
                                            <h6 class="mb-1">Control Accounts</h6>
                                            <p class="text-muted small mb-0">Used for reconciliation workflows and drill-through reporting.</p>
                                        </div>
                                    </div>
                                    <div class="legend-item">
                                        <span class="legend-dot" style="background: rgba(250, 204, 21, 0.85);"></span>
                                        <div>
                                            <h6 class="mb-1">Regulatory Sensitive</h6>
                                            <p class="text-muted small mb-0">Ensure these accounts include compliance attributes for audit exports.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Account Modal -->
    <div class="modal fade" id="addAccountModal" tabindex="-1" aria-labelledby="addAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="addAccountModalLabel">Create New Account</h5>
                        <p class="text-muted mb-0 small">Define the hierarchy placement, currency, and cash designation to enable routing and reporting.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="coa-field">
                                    <label for="accountCode" class="form-label">Account Code</label>
                                    <input type="text" class="form-control form-control-modern" id="accountCode" placeholder="Enter account code">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="coa-field">
                                    <label for="accountName" class="form-label">Account Name</label>
                                    <input type="text" class="form-control form-control-modern" id="accountName" placeholder="Enter account name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="coa-field">
                                    <label for="accountCategory" class="form-label">Category</label>
                                    <select class="form-select form-select-modern" id="accountCategory" aria-label="Account Category">
                                        <option value="">Select category</option>
                                        <option value="assets">Assets</option>
                                        <option value="liabilities">Liabilities</option>
                                        <option value="equity">Equity</option>
                                        <option value="income">Income</option>
                                        <option value="expenses">Expenses</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="coa-field">
                                    <label for="accountGroup" class="form-label">Group</label>
                                    <select class="form-select form-select-modern" id="accountGroup" aria-label="Account Group">
                                        <option value="">Select group</option>
                                        <option value="current-assets">Current Assets</option>
                                        <option value="bank-accounts">Bank Accounts</option>
                                        <option value="payables">Accounts Payable</option>
                                        <option value="revenue">Sales Revenue</option>
                                        <option value="operations">Operations</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="coa-field">
                                    <label for="accountCurrency" class="form-label">Currency</label>
                                    <select class="form-select form-select-modern" id="accountCurrency" aria-label="Currency">
                                        <option value="">Choose currency</option>
                                        <option value="ghs">GHS · Ghanaian Cedi</option>
                                        <option value="usd">USD · US Dollar</option>
                                        <option value="eur">EUR · Euro</option>
                                        <option value="gbp">GBP · Pound Sterling</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="coa-switch-card h-100 d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <h6 class="mb-1">Cash Account?</h6>
                                        <p class="text-muted small">Enable treasury tracking and daily reconciliation workflows.</p>
                                    </div>
                                    <div class="form-check form-switch switch-pill mb-0">
                                        <input class="form-check-input" type="checkbox" role="switch" id="cashAccountSwitch">
                                        <label class="form-check-label visually-hidden" for="cashAccountSwitch">Cash Account</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="coa-field">
                                    <label for="accountDescription" class="form-label">Description & Usage Notes</label>
                                    <textarea class="form-control form-textarea-modern" placeholder="Add guidance on how this account should be used" id="accountDescription"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="coa-field">
                                    <label for="accountStatus" class="form-label">Status</label>
                                    <select class="form-select form-select-modern" id="accountStatus" aria-label="Account Status">
                                        <option value="">Choose status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="pending">Pending approval</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="coa-field">
                                    <label for="approvalOwner" class="form-label">Approval Owner</label>
                                    <input type="text" class="form-control form-control-modern" id="approvalOwner" placeholder="e.g. Finance Lead">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-soft-info"><i class="ri-save-3-line me-1"></i> Save Draft</button>
                    </div>
                    <button type="button" class="btn btn-primary">
                        <i class="ri-check-line me-1"></i>Create Account
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- New Category Modal -->
    <div class="modal fade" id="newCategoryModal" tabindex="-1" aria-labelledby="newCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="newCategoryModalLabel">Add Account Category</h5>
                        <p class="text-muted small mb-0">Create high-level financial buckets to organize your chart of accounts.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="coa-field">
                            <label for="categoryName" class="form-label">Category Name</label>
                            <input type="text" class="form-control form-control-modern" id="categoryName" placeholder="Enter category name">
                        </div>
                        <div class="coa-field">
                            <label for="categoryNature" class="form-label">Nature</label>
                            <select class="form-select form-select-modern" id="categoryNature">
                                <option value="">Select nature</option>
                                <option value="debit">Debit (Assets / Expenses)</option>
                                <option value="credit">Credit (Liabilities / Income)</option>
                            </select>
                        </div>
                        <div class="coa-field mb-0">
                            <label for="categoryNotes" class="form-label">Description / Governance Notes</label>
                            <textarea class="form-control form-textarea-modern" placeholder="Outline the purpose, usage limits, or approval workflows" id="categoryNotes"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success"><i class="ri-add-line me-1"></i> Save Category</button>
                </div>
            </div>
        </div>
    </div>

    <!-- New Group Modal -->
    <div class="modal fade" id="newGroupModal" tabindex="-1" aria-labelledby="newGroupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="newGroupModalLabel">Create Account Group</h5>
                        <p class="text-muted small mb-0">Group accounts for reporting segments, consolidation, and automation.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="coa-field">
                            <label for="groupCategorySelect" class="form-label">Parent Category</label>
                            <select class="form-select form-select-modern" id="groupCategorySelect">
                                <option value="">Select parent category</option>
                                <option value="assets">Assets</option>
                                <option value="liabilities">Liabilities</option>
                                <option value="equity">Equity</option>
                                <option value="income">Income</option>
                                <option value="expenses">Expenses</option>
                            </select>
                        </div>
                        <div class="coa-field">
                            <label for="groupName" class="form-label">Group Name</label>
                            <input type="text" class="form-control form-control-modern" id="groupName" placeholder="Enter group name">
                        </div>
                        <div class="coa-field mb-0">
                            <label for="groupDescription" class="form-label">Usage Notes / Responsibilities</label>
                            <textarea class="form-control form-textarea-modern" placeholder="Describe the accounts that belong here and ownership expectations" id="groupDescription"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-info"><i class="ri-node-tree me-1"></i> Save Group</button>
                </div>
            </div>
        </div>
    </div>
@endsection
