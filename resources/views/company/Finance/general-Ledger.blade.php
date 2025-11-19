@extends('layouts.vertical', ['page_title' => 'General Ledger'])

@section('css')
    <style>
        :root {
            --gl-surface: #ffffff;
            --gl-surface-alt: #f6f8fd;
            --gl-border: rgba(15, 23, 42, 0.08);
            --gl-border-strong: rgba(15, 23, 42, 0.14);
            --gl-text: #0f172a;
            --gl-muted: rgba(15, 23, 42, 0.65);
            --gl-primary: #2563eb;
            --gl-primary-soft: rgba(37, 99, 235, 0.12);
            --gl-accent: #38bdf8;
            --gl-success: #16a34a;
            --gl-warning: #f59e0b;
            --gl-danger: #ef4444;
        }

        .general-ledger-screen {
            background: linear-gradient(145deg, rgba(37, 99, 235, 0.08), rgba(15, 23, 42, 0.03));
            min-height: 100vh;
            padding: 1.8rem 0 3.8rem;
        }

        .ledger-page-title {
            background: transparent;
            border: none;
            padding: 0;
            margin-bottom: 1.25rem;
        }

        .ledger-page-title .breadcrumb {
            margin-bottom: 0.4rem;
        }

        .ledger-page-title .breadcrumb-item a,
        .ledger-page-title .breadcrumb-item.active {
            color: var(--gl-muted);
        }

        .ledger-page-title .page-title {
            font-weight: 700;
            color: var(--gl-text);
        }

        .ledger-hero {
            position: relative;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(246, 248, 255, 0.88));
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 28px;
            box-shadow: 0 32px 60px rgba(15, 23, 42, 0.1);
            padding: 2.5rem 2.4rem;
            overflow: hidden;
        }

        .ledger-hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 82% 18%, rgba(37, 99, 235, 0.18), transparent 58%);
            pointer-events: none;
        }

        .ledger-hero__layout {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        @media (min-width: 992px) {
            .ledger-hero__layout {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
        }

        .ledger-hero__copy {
            max-width: 560px;
            display: flex;
            flex-direction: column;
            gap: 1.1rem;
        }

        .ledger-hero__eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.5rem 1.2rem;
            border-radius: 999px;
            background: var(--gl-primary-soft);
            color: var(--gl-primary);
            font-weight: 600;
            letter-spacing: 0.13em;
            text-transform: uppercase;
            font-size: 0.72rem;
        }

        .ledger-hero__title {
            margin: 0;
            font-weight: 700;
            font-size: 2.1rem;
            color: var(--gl-text);
        }

        .ledger-hero__subtitle {
            color: var(--gl-muted);
            font-size: 0.98rem;
            line-height: 1.75;
            margin-bottom: 0;
        }

        .ledger-hero__meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .ledger-meta-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 14px;
            background: rgba(37, 99, 235, 0.08);
            color: var(--gl-text);
            font-weight: 600;
            font-size: 0.85rem;
        }

        .ledger-meta-chip i {
            color: var(--gl-primary);
        }

        .ledger-hero__actions {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 1.2rem;
        }

        .ledger-hero__actions .btn {
            border-radius: 14px;
            padding: 0.65rem 1.4rem;
            font-weight: 600;
            box-shadow: none;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .ledger-hero__actions .btn-primary {
            box-shadow: 0 18px 32px rgba(37, 99, 235, 0.25);
        }

        .ledger-hero__actions .btn-outline-secondary {
            border: 1px solid rgba(37, 99, 235, 0.22);
            color: var(--gl-primary);
        }

        .ledger-hero__actions .btn:hover {
            transform: translateY(-2px);
        }

        .ledger-hero__quick {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
        }

        .ledger-hero__quick .btn {
            padding: 0.45rem 1.1rem;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .ledger-hero__stats {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            position: relative;
            z-index: 1;
        }

        .ledger-hero__stat {
            background: rgba(255, 255, 255, 0.75);
            border: 1px solid rgba(37, 99, 235, 0.12);
            border-radius: 20px;
            padding: 1rem 1.2rem;
            box-shadow: 0 15px 35px rgba(15, 23, 42, 0.08);
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .ledger-hero__stat-label {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--gl-muted);
            font-weight: 600;
        }

        .ledger-hero__stat-value {
            font-size: 1.45rem;
            font-weight: 700;
            color: var(--gl-text);
        }

        .ledger-hero__stat-trend {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .ledger-hero__stat-trend.positive {
            color: var(--gl-success);
        }

        .ledger-hero__stat-trend.neutral {
            color: var(--gl-primary);
        }

        .ledger-hero__stat-trend.negative {
            color: var(--gl-danger);
        }

        .ledger-kpi-cards {
            margin-top: 2rem;
            margin-bottom: 2.4rem;
            display: grid;
            gap: 1.25rem;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }

        .ledger-kpi-card {
            background: var(--gl-surface);
            border: 1px solid var(--gl-border);
            border-radius: 22px;
            padding: 1.4rem 1.55rem;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            position: relative;
            overflow: hidden;
        }

        .ledger-kpi-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 80% 20%, rgba(37, 99, 235, 0.12), transparent 60%);
            opacity: 0.6;
            pointer-events: none;
        }

        .ledger-kpi-card__icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: var(--gl-primary-soft);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--gl-primary);
            font-size: 1.3rem;
            position: relative;
            z-index: 1;
        }

        .ledger-kpi-card__label {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--gl-muted);
            font-weight: 600;
            position: relative;
            z-index: 1;
        }

        .ledger-kpi-card__value {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--gl-text);
            position: relative;
            z-index: 1;
        }

        .ledger-kpi-card__meta {
            font-size: 0.85rem;
            color: var(--gl-muted);
            position: relative;
            z-index: 1;
        }

        .ledger-kpi-card__trend {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.8rem;
            font-weight: 600;
            position: relative;
            z-index: 1;
        }

        .ledger-kpi-card__trend.positive {
            color: var(--gl-success);
        }

        .ledger-kpi-card__trend.neutral {
            color: var(--gl-primary);
        }

        .ledger-filters-card {
            border-radius: 24px;
            border: 1px solid var(--gl-border);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(245, 248, 255, 0.9));
            box-shadow: 0 24px 48px rgba(15, 23, 42, 0.08);
        }

        .ledger-filters__header {
            display: flex;
            flex-wrap: wrap;
            gap: 1.1rem;
            align-items: center;
            justify-content: space-between;
        }

        .ledger-filters__header h6 {
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: var(--gl-text);
        }

        .ledger-filters__header p {
            margin: 0;
            font-size: 0.87rem;
            color: var(--gl-muted);
        }

        .ledger-filter-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
        }

        .ledger-filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 0.95rem;
            border-radius: 12px;
            background: rgba(37, 99, 235, 0.1);
            color: var(--gl-primary);
            font-weight: 600;
            font-size: 0.78rem;
        }

        .ledger-filter-chip i {
            font-size: 1rem;
        }

        .ledger-filters {
            margin-top: 1.5rem;
        }

        .ledger-filter-group {
            background: rgba(37, 99, 235, 0.06);
            border: 1px solid rgba(37, 99, 235, 0.15);
            border-radius: 16px;
            padding: 1rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .ledger-filter-group .form-label,
        .ledger-filter-group .filter-legend {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.08em;
            color: var(--gl-muted);
            margin-bottom: 0;
        }

        .ledger-filter-group .input-with-icon {
            position: relative;
        }

        .ledger-filter-group .input-with-icon .ri {
            position: absolute;
            top: 50%;
            left: 0.95rem;
            transform: translateY(-50%);
            font-size: 1.05rem;
            color: rgba(37, 99, 235, 0.65);
            pointer-events: none;
        }

        .ledger-filter-group .form-control,
        .ledger-filter-group .form-select {
            border-radius: 12px;
            border: 1px solid rgba(37, 99, 235, 0.25);
            background-color: rgba(255, 255, 255, 0.9);
            color: var(--gl-text);
            padding: 0.7rem 1rem;
            transition: all 0.2s ease;
        }

        .ledger-filter-group .input-with-icon .form-control,
        .ledger-filter-group .input-with-icon .form-select {
            padding-left: 2.7rem;
        }

        .ledger-filter-group .form-control:focus,
        .ledger-filter-group .form-select:focus {
            border-color: var(--gl-primary);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.15);
        }

        .ledger-filter-actions button {
            border-radius: 12px;
            padding: 0.7rem 1rem;
            font-weight: 600;
        }

        .ledger-filter-actions .btn-outline-secondary {
            border: 1px solid rgba(37, 99, 235, 0.25);
        }

        .ledger-filter-toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 0.65rem;
        }

        .ledger-filter-toolbar .btn {
            border-radius: 999px;
            padding: 0.45rem 1.1rem;
            font-weight: 600;
            font-size: 0.78rem;
        }

        .ledger-table-card {
            border: none;
            border-radius: 26px;
            background: var(--gl-surface);
            box-shadow: 0 28px 55px rgba(15, 23, 42, 0.1);
        }

        .ledger-table-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .ledger-table-header h5 {
            margin-bottom: 0.35rem;
            font-weight: 700;
            color: var(--gl-text);
        }

        .ledger-table-header p {
            margin: 0;
            font-size: 0.9rem;
            color: var(--gl-muted);
        }

        .ledger-table-toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
        }

        .ledger-table-toolbar .btn {
            border-radius: 12px;
            padding: 0.55rem 1.1rem;
            font-weight: 600;
        }

        .ledger-status-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 0.55rem;
            margin-bottom: 1.25rem;
        }

        .ledger-status-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.85rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 600;
            background: rgba(37, 99, 235, 0.08);
            color: var(--gl-text);
        }

        .ledger-status-chip.posted {
            background: rgba(22, 163, 74, 0.12);
            color: var(--gl-success);
        }

        .ledger-status-chip.draft {
            background: rgba(245, 158, 11, 0.14);
            color: var(--gl-warning);
        }

        .ledger-status-chip.pending {
            background: rgba(56, 189, 248, 0.18);
            color: var(--gl-accent);
        }

        .ledger-table thead {
            background: rgba(37, 99, 235, 0.06);
        }

        .ledger-table thead th {
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.08em;
            color: var(--gl-muted);
            border-bottom: none;
            padding-top: 1rem;
            padding-bottom: 0.75rem;
        }

        .ledger-table tbody tr {
            transition: background-color 0.25s ease, transform 0.25s ease;
        }

        .ledger-table tbody tr:hover {
            background-color: rgba(37, 99, 235, 0.08);
            transform: translateY(-2px);
        }

        .ledger-table td {
            vertical-align: middle;
            padding: 0.95rem 0.75rem;
        }

        .ledger-status-badge {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.74rem;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.3rem 0.75rem;
            border-radius: 999px;
        }

        .ledger-status-posted {
            background-color: rgba(22, 163, 74, 0.15);
            color: var(--gl-success);
        }

        .ledger-status-draft {
            background-color: rgba(245, 158, 11, 0.18);
            color: var(--gl-warning);
        }

        .ledger-summary-card {
            background-color: rgba(37, 99, 235, 0.1);
            border: 1px solid rgba(37, 99, 235, 0.25);
            border-radius: 16px;
            padding: 1.15rem 1.3rem;
        }

        .ledger-summary-card .summary-value {
            font-size: 1.35rem;
            font-weight: 700;
        }

        .ledger-summary-card .equal-indicator {
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--gl-primary);
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .ledger-offcanvas {
            max-width: 520px;
            background: linear-gradient(160deg, rgba(37, 99, 235, 0.08), var(--gl-surface-alt));
        }

        .ledger-offcanvas .card {
            border-radius: 18px;
            border: 1px solid var(--gl-border);
            box-shadow: 0 22px 40px rgba(15, 23, 42, 0.12);
        }

        .ledger-offcanvas .offcanvas-header {
            border-bottom: 1px solid var(--gl-border);
        }

        .ledger-offcanvas .offcanvas-title {
            font-weight: 700;
        }

        .ledger-offcanvas .btn-success {
            box-shadow: 0 18px 32px rgba(22, 163, 74, 0.35);
        }

        .ledger-modal .modal-content {
            border-radius: 20px;
            border: 1px solid var(--gl-border);
            box-shadow: 0 30px 60px rgba(15, 23, 42, 0.15);
        }

        .ledger-modal .modal-header {
            border-bottom: 1px solid var(--gl-border);
        }

        .ledger-modal .modal-title {
            font-weight: 700;
        }

        .ledger-modal .form-control,
        .ledger-modal .form-select {
            border-radius: 12px;
            border: 1px solid rgba(37, 99, 235, 0.25);
            padding: 0.7rem 1rem;
        }

        .ledger-modal .form-control:focus,
        .ledger-modal .form-select:focus {
            border-color: var(--gl-primary);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.15);
        }

        .ledger-modal .journal-lines-table th {
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.08em;
            color: rgba(15, 23, 42, 0.55);
        }

        .ledger-modal .journal-lines-table td {
            vertical-align: middle;
        }

        .ledger-modal .journal-totals-card {
            background: rgba(37, 99, 235, 0.08);
            border: 1px dashed rgba(37, 99, 235, 0.4);
            border-radius: 16px;
            padding: 1rem;
        }

        .ledger-modal .btn-add-line {
            border-style: dashed;
            border-width: 1px;
            border-radius: 12px;
            padding: 0.65rem 1rem;
        }

        .btn-soft-primary {
            background: var(--gl-primary-soft);
            color: var(--gl-primary);
            border: 1px solid rgba(37, 99, 235, 0.18);
        }

        .btn-soft-primary:hover {
            background: rgba(37, 99, 235, 0.18);
            color: var(--gl-primary);
        }

        .table-ledger-lines tbody tr td {
            vertical-align: middle;
        }

        .ledger-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.82rem;
        }

        .bg-soft-primary {
            background: var(--gl-primary-soft) !important;
            color: var(--gl-primary) !important;
        }

        .ledger-table-footer {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
        }

        @media (max-width: 767.98px) {
            .ledger-hero {
                padding: 2rem 1.6rem;
            }

            .ledger-hero__title {
                font-size: 1.75rem;
            }

            .ledger-filters {
                margin-top: 1rem;
            }

            .ledger-hero__actions {
                width: 100%;
            }

            .ledger-hero__actions .btn {
                width: 100%;
                justify-content: center;
            }

            .ledger-filter-toolbar {
                width: 100%;
            }

            .ledger-filter-toolbar .btn {
                flex: 1;
                justify-content: center;
            }
        }
    </style>
@endsection

@section('content')
    <section class="general-ledger-screen">
        <div class="container-fluid">
            <div class="row gy-4">
                <div class="col-12">
                    <div class="ledger-page-title">
                        <div class="page-title-right d-flex flex-wrap align-items-center gap-2">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                <li class="breadcrumb-item">Finance</li>
                                <li class="breadcrumb-item active">General Ledger</li>
                            </ol>
                            <span class="badge rounded-pill bg-soft-primary fw-semibold">
                                <i class="ri-live-line me-1"></i> Real-time sync active
                            </span>
                        </div>
                        <div class="ledger-hero mt-3">
                            <div class="ledger-hero__layout">
                                <div class="ledger-hero__copy">
                                    <span class="ledger-hero__eyebrow">
                                        <i class="ri-pulse-line"></i> finance control tower
                                    </span>
                                    <h1 class="ledger-hero__title">General Ledger Workspace</h1>
                                    <p class="ledger-hero__subtitle">
                                        Close books faster with guided approvals, unified journals, and intelligent variance detection.
                                        Monitor live postings across Accounts Payable, Receivable, Payroll, and MoMo streams.
                                    </p>
                                    <div class="ledger-hero__meta">
                                        <div class="ledger-meta-chip">
                                            <i class="ri-time-line"></i> Close window: <strong>Day +4</strong>
                                        </div>
                                        <div class="ledger-meta-chip">
                                            <i class="ri-shield-check-line"></i> Compliance: <strong>SOC 2 · IFRS</strong>
                                        </div>
                                        <div class="ledger-meta-chip">
                                            <i class="ri-database-2-line"></i> Active ledgers: <strong>248</strong>
                                        </div>
                                    </div>
                                    <div class="ledger-hero__quick">
                                        <button class="btn btn-soft-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#approveJournalModal">
                                            <i class="ri-star-smile-line me-1"></i> Approvals summary
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm">
                                            <i class="ri-dashboard-line me-1"></i> Go to dashboards
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm">
                                            <i class="ri-history-line me-1"></i> Audit trail
                                        </button>
                                    </div>
                                </div>
                                <div class="ledger-hero__actions">
                                    <div class="d-flex flex-wrap gap-2">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addJournalModal">
                                            <i class="ri-add-line me-1"></i> New Journal Entry
                                        </button>
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                            id="ledgerExportMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-upload-cloud-2-line me-1"></i> Export
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="ledgerExportMenu">
                                            <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-file-pdf-line me-1"></i> PDF</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-file-excel-line me-1"></i> Excel</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-database-line me-1"></i> CSV dataset</a></li>
                                        </ul>
                                        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#ledgerFilters" aria-expanded="true" aria-controls="ledgerFilters">
                                            <i class="ri-equalizer-line me-1"></i> Advanced filters
                                        </button>
                                    </div>
                                    <div class="ledger-hero__stats">
                                        <div class="ledger-hero__stat">
                                            <span class="ledger-hero__stat-label">Open approvals</span>
                                            <span class="ledger-hero__stat-value">6 journals</span>
                                            <span class="ledger-hero__stat-trend negative">
                                                <i class="ri-alarm-warning-line"></i> 2 overdue
                                            </span>
                                        </div>
                                        <div class="ledger-hero__stat">
                                            <span class="ledger-hero__stat-label">Posting accuracy</span>
                                            <span class="ledger-hero__stat-value">99.2%</span>
                                            <span class="ledger-hero__stat-trend positive">
                                                <i class="ri-arrow-right-up-line"></i> +0.8% vs last close
                                            </span>
                                        </div>
                                        <div class="ledger-hero__stat">
                                            <span class="ledger-hero__stat-label">Data freshness</span>
                                            <span class="ledger-hero__stat-value">12 streams</span>
                                            <span class="ledger-hero__stat-trend neutral">
                                                <i class="ri-refresh-line"></i> Updated 3 mins ago
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="ledger-kpi-cards">
                    <div class="ledger-kpi-card">
                        <span class="ledger-kpi-card__icon"><i class="ri-scale-line"></i></span>
                        <span class="ledger-kpi-card__label">trial balance health</span>
                        <span class="ledger-kpi-card__value">Balanced</span>
                        <span class="ledger-kpi-card__meta">Exceptions under threshold across 128 active ledgers.</span>
                        <span class="ledger-kpi-card__trend positive">
                            <i class="ri-checkbox-circle-line"></i> 0 unresolved exceptions
                        </span>
                    </div>
                    <div class="ledger-kpi-card">
                        <span class="ledger-kpi-card__icon"><i class="ri-calendar-check-line"></i></span>
                        <span class="ledger-kpi-card__label">closing timeline</span>
                        <span class="ledger-kpi-card__value">Day +4</span>
                        <span class="ledger-kpi-card__meta">Accelerated close schedule with automation triggers.</span>
                        <span class="ledger-kpi-card__trend positive">
                            <i class="ri-flashlight-line"></i> 2.1 days faster than last quarter
                        </span>
                    </div>
                    <div class="ledger-kpi-card">
                        <span class="ledger-kpi-card__icon"><i class="ri-shield-check-line"></i></span>
                        <span class="ledger-kpi-card__label">control status</span>
                        <span class="ledger-kpi-card__value">Green</span>
                        <span class="ledger-kpi-card__meta">All approval workflows and segregation controls are compliant.</span>
                        <span class="ledger-kpi-card__trend neutral">
                            <i class="ri-shield-star-line"></i> 4 policies auto-validated
                        </span>
                    </div>
                    <div class="ledger-kpi-card">
                        <span class="ledger-kpi-card__icon"><i class="ri-exchange-dollar-line"></i></span>
                        <span class="ledger-kpi-card__label">net movement</span>
                        <span class="ledger-kpi-card__value">₵48,920</span>
                        <span class="ledger-kpi-card__meta">Yesterday's net journal movement across all entities.</span>
                        <span class="ledger-kpi-card__trend positive">
                            <i class="ri-arrow-right-up-line"></i> Stable variance window
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="collapse show" id="ledgerFilters">
                    <div class="ledger-filters-card mb-4">
                        <div class="card-body">
                            <div class="ledger-filters__header">
                                <div>
                                    <h6>Filter panel</h6>
                                    <p>Select periods, accounts, and approval states to shape your ledger view.</p>
                                </div>
                                <div class="ledger-filter-toolbar">
                                    <button class="btn btn-soft-primary">
                                        <i class="ri-bookmark-line me-1"></i> Save preset
                                    </button>
                                    <button class="btn btn-outline-secondary">
                                        <i class="ri-folder-shield-2-line me-1"></i> Audit filters
                                    </button>
                                    <button class="btn btn-outline-secondary">
                                        <i class="ri-refresh-line me-1"></i> Reset all
                                    </button>
                                </div>
                            </div>

                            <div class="ledger-filter-tags mt-3">
                                <span class="ledger-filter-chip"><i class="ri-building-4-line"></i> Holding company</span>
                                <span class="ledger-filter-chip"><i class="ri-currency-line"></i> Reporting currency: GHS</span>
                                <span class="ledger-filter-chip"><i class="ri-cloud-line"></i> 12 data streams synced</span>
                            </div>

                            <div class="row g-3 ledger-filters mt-2">
                                <div class="col-sm-6 col-lg-3">
                                    <div class="ledger-filter-group">
                                        <label for="ledgerDateFrom" class="form-label">Date range start</label>
                                        <div class="input-with-icon">
                                            <i class="ri-calendar-event-line"></i>
                                            <input type="date" class="form-control" id="ledgerDateFrom">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="ledger-filter-group">
                                        <label for="ledgerDateTo" class="form-label">Date range end</label>
                                        <div class="input-with-icon">
                                            <i class="ri-calendar-check-line"></i>
                                            <input type="date" class="form-control" id="ledgerDateTo">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="ledger-filter-group">
                                        <label for="ledgerAccount" class="form-label">Ledger account</label>
                                        <div class="input-with-icon">
                                            <i class="ri-bank-card-line"></i>
                                            <select class="form-select" id="ledgerAccount">
                                                <option value="">All accounts</option>
                                                <option value="1001">1001 - Cash</option>
                                                <option value="2001">2001 - Accounts Payable</option>
                                                <option value="3001">3001 - Equity</option>
                                                <option value="4001">4001 - Revenue</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="ledger-filter-group">
                                        <label for="ledgerSourceModule" class="form-label">Source module</label>
                                        <div class="input-with-icon">
                                            <i class="ri-apps-2-line"></i>
                                            <select class="form-select" id="ledgerSourceModule">
                                                <option value="">All modules</option>
                                                <option value="ap">Accounts Payable (AP)</option>
                                                <option value="ar">Accounts Receivable (AR)</option>
                                                <option value="momo">MoMo</option>
                                                <option value="payroll">Payroll</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="ledger-filter-group">
                                        <label for="ledgerStatus" class="form-label">Approval status</label>
                                        <div class="input-with-icon">
                                            <i class="ri-bubble-chart-line"></i>
                                            <select class="form-select" id="ledgerStatus">
                                                <option value="">All status</option>
                                                <option value="posted">Posted</option>
                                                <option value="draft">Draft</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="ledger-filter-group">
                                        <label class="form-label" for="ledgerEntity">Entity</label>
                                        <div class="input-with-icon">
                                            <i class="ri-global-line"></i>
                                            <select class="form-select" id="ledgerEntity">
                                                <option value="">Consolidated</option>
                                                <option value="retail">Retail & Distribution</option>
                                                <option value="upstream">Upstream Operations</option>
                                                <option value="logistics">Logistics & Supply</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="ledger-filter-group">
                                        <label class="form-label" for="ledgerReviewer">Reviewer</label>
                                        <div class="input-with-icon">
                                            <i class="ri-user-star-line"></i>
                                            <select class="form-select" id="ledgerReviewer">
                                                <option value="">Any reviewer</option>
                                                <option value="mary">Mary Owusu</option>
                                                <option value="kwesi">Kwesi Boadu</option>
                                                <option value="ama">Ama Tetteh</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="ledger-filter-group ledger-filter-actions">
                                        <span class="filter-legend">Quick actions</span>
                                        <button class="btn btn-primary w-100">
                                            <i class="ri-search-line me-1"></i> Apply filters
                                        </button>
                                        <button class="btn btn-outline-secondary w-100">
                                            <i class="ri-refresh-line me-1"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="ledger-table-card card border-0 p-lg-4 p-3">
                    <div class="ledger-table-header">
                        <div>
                            <h5>Ledger entries</h5>
                            <p>Most recent journal postings across integrated finance modules.</p>
                        </div>
                        <div class="ledger-status-legend">
                            <span class="ledger-status-chip posted"><i class="ri-checkbox-circle-line"></i> Posted</span>
                            <span class="ledger-status-chip draft"><i class="ri-draft-line"></i> Draft</span>
                            <span class="ledger-status-chip pending"><i class="ri-time-line"></i> Pending approval</span>
                        </div>
                    </div>
                    <div class="ledger-table-toolbar mb-3">
                        <button class="btn btn-soft-primary btn-sm">
                            <i class="ri-slideshow-line me-1"></i> Compact view
                        </button>
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="ri-filter-3-line me-1"></i> Column visibility
                        </button>
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="ri-pie-chart-2-line me-1"></i> Insights
                        </button>
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="ri-notification-2-line me-1"></i> Alerts
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-striped align-middle dt-responsive nowrap w-100 ledger-table"
                            id="general-ledger-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Journal no</th>
                                    <th>Description</th>
                                    <th>Source module</th>
                                    <th>Posted by</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2025-01-05</td>
                                    <td>JRN-000234</td>
                                    <td>Supplier invoice payment</td>
                                    <td>Accounts Payable</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="ledger-avatar bg-soft-primary">MO</span>
                                            <span>Mary Owusu</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="ledger-status-badge ledger-status-posted">
                                            <i class="ri-checkbox-circle-line"></i> Posted
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <button class="btn btn-soft-primary btn-sm" data-bs-toggle="offcanvas"
                                                data-bs-target="#journalEntryDrawer">
                                                <i class="ri-eye-line"></i> View
                                            </button>
                                            <button class="btn btn-soft-primary btn-sm">
                                                <i class="ri-printer-line"></i> Print
                                            </button>
                                            <button class="btn btn-soft-success btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#approveJournalModal">
                                                <i class="ri-check-double-line"></i> Approve
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2025-01-03</td>
                                    <td>JRN-000233</td>
                                    <td>Sales revenue recognition</td>
                                    <td>Accounts Receivable</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="ledger-avatar bg-soft-primary">KB</span>
                                            <span>Kwesi Boadu</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="ledger-status-badge ledger-status-draft">
                                            <i class="ri-draft-line"></i> Draft
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <button class="btn btn-soft-primary btn-sm" data-bs-toggle="offcanvas"
                                                data-bs-target="#journalEntryDrawer">
                                                <i class="ri-eye-line"></i> View
                                            </button>
                                            <button class="btn btn-soft-primary btn-sm">
                                                <i class="ri-printer-line"></i> Print
                                            </button>
                                            <button class="btn btn-soft-success btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#approveJournalModal">
                                                <i class="ri-check-double-line"></i> Approve
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2025-01-02</td>
                                    <td>JRN-000232</td>
                                    <td>Monthly payroll posting</td>
                                    <td>Payroll</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="ledger-avatar bg-soft-primary">AT</span>
                                            <span>Ama Tetteh</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="ledger-status-badge ledger-status-posted">
                                            <i class="ri-checkbox-circle-line"></i> Posted
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <button class="btn btn-soft-primary btn-sm" data-bs-toggle="offcanvas"
                                                data-bs-target="#journalEntryDrawer">
                                                <i class="ri-eye-line"></i> View
                                            </button>
                                            <button class="btn btn-soft-primary btn-sm">
                                                <i class="ri-printer-line"></i> Print
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="ledger-table-footer mt-3">
                        <div class="text-muted small">Showing 1 – 3 of 482 journal entries • Last synced 3 mins ago</div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-secondary btn-sm">
                                <i class="ri-skip-back-mini-line"></i>
                            </button>
                            <button class="btn btn-outline-secondary btn-sm">
                                <i class="ri-arrow-left-s-line"></i>
                            </button>
                            <button class="btn btn-soft-primary btn-sm">1</button>
                            <button class="btn btn-outline-secondary btn-sm">2</button>
                            <button class="btn btn-outline-secondary btn-sm">3</button>
                            <button class="btn btn-outline-secondary btn-sm">
                                <i class="ri-arrow-right-s-line"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="offcanvas offcanvas-end ledger-offcanvas" tabindex="-1" id="journalEntryDrawer"
        aria-labelledby="journalEntryDrawerLabel">
        <div class="offcanvas-header">
            <div>
                <h5 class="offcanvas-title" id="journalEntryDrawerLabel">Journal Entry Details</h5>
                <p class="text-muted mb-1">JRN-000234 &middot; 05 Jan 2025</p>
                <div class="d-flex flex-wrap gap-2">
                    <span class="ledger-status-chip posted"><i class="ri-checkbox-circle-line"></i> Posted</span>
                    <span class="ledger-status-chip"><i class="ri-user-3-line"></i> Prepared by Mary Owusu</span>
                </div>
            </div>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="ledger-filter-chip"><i class="ri-apps-line"></i> Accounts Payable</span>
                        <span class="ledger-filter-chip"><i class="ri-stack-line"></i> Batch: AP-2025-01</span>
                        <span class="ledger-filter-chip"><i class="ri-time-line"></i> Approved 15 mins ago</span>
                    </div>
                    <h6 class="fw-semibold mb-3">Journal Header</h6>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Journal No</small>
                            <span class="fw-medium">JRN-000234</span>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Posting Date</small>
                            <span class="fw-medium">05 Jan 2025</span>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Source Module</small>
                            <span class="fw-medium">Accounts Payable</span>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Reviewed By</small>
                            <span class="fw-medium">Kwesi Boadu</span>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">Description</small>
                            <span class="fw-medium">Supplier invoice payment for January inventory restock.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Journal Lines</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-ledger-lines">
                            <thead>
                                <tr>
                                    <th>Account</th>
                                    <th>Description</th>
                                    <th class="text-end">Debit</th>
                                    <th class="text-end">Credit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1001 - Cash</td>
                                    <td>Payment to supplier</td>
                                    <td class="text-end">-</td>
                                    <td class="text-end">12,500.00</td>
                                </tr>
                                <tr>
                                    <td>5002 - Cost of Goods Sold</td>
                                    <td>Inventory expenses</td>
                                    <td class="text-end">12,500.00</td>
                                    <td class="text-end">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="ledger-summary-card mt-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <span class="text-muted d-block">Total Debit</span>
                                <span class="summary-value">12,500.00</span>
                            </div>
                            <div class="equal-indicator">
                                <i class="ri-equal-line"></i> Balanced
                            </div>
                            <div class="text-end">
                                <span class="text-muted d-block">Total Credit</span>
                                <span class="summary-value">12,500.00</span>
                            </div>
                        </div>
                        <div class="mt-3 d-flex flex-wrap gap-2">
                            <span class="ledger-filter-chip"><i class="ri-link-unlink-m"></i> Auto-matched to invoice INV-5510</span>
                            <span class="ledger-filter-chip"><i class="ri-secure-payment-line"></i> Policy check passed</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <button class="btn btn-success flex-grow-1">
                    <i class="ri-checkbox-circle-line"></i> Approve journal
                </button>
                <button class="btn btn-outline-secondary flex-grow-1">
                    <i class="ri-printer-line"></i> Print snapshot
                </button>
                <button class="btn btn-outline-secondary flex-grow-1">
                    <i class="ri-share-forward-box-line"></i> Share
                </button>
            </div>
        </div>
    </div>

    <div class="modal fade ledger-modal" id="addJournalModal" tabindex="-1" aria-labelledby="addJournalModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="addJournalModalLabel">Create Journal Entry</h5>
                        <p class="text-muted mb-0">Capture header details and balanced line items for this posting.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-lg-4">
                            <label class="form-label" for="journalDate">Journal Date</label>
                            <input type="date" class="form-control" id="journalDate">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="journalNumber">Journal Number</label>
                            <input type="text" class="form-control" id="journalNumber" placeholder="Auto-generated">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="journalSourceModule">Source Module</label>
                            <select class="form-select" id="journalSourceModule">
                                <option value="">Select module</option>
                                <option value="ap">Accounts Payable (AP)</option>
                                <option value="ar">Accounts Receivable (AR)</option>
                                <option value="momo">MoMo</option>
                                <option value="payroll">Payroll</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="journalPreparedBy">Prepared By</label>
                            <input type="text" class="form-control" id="journalPreparedBy" placeholder="Enter name">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="journalStatus">Journal Status</label>
                            <select class="form-select" id="journalStatus">
                                <option value="draft">Draft</option>
                                <option value="posted">Posted</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="journalDescription">Description</label>
                            <textarea class="form-control" id="journalDescription" rows="3"
                                placeholder="Brief description of this journal"></textarea>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-semibold mb-0">Journal Lines</h6>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-secondary btn-sm">
                                <i class="ri-upload-2-line me-1"></i> Import CSV
                            </button>
                            <button class="btn btn-outline-primary btn-add-line">
                                <i class="ri-add-line"></i> Add Line
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive mb-3">
                        <table class="table table-bordered journal-lines-table">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 22%;">Account</th>
                                    <th>Description</th>
                                    <th style="width: 12%;" class="text-end">Debit</th>
                                    <th style="width: 12%;" class="text-end">Credit</th>
                                    <th style="width: 10%;" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select class="form-select">
                                            <option value="">Select account</option>
                                            <option value="1001">1001 - Cash</option>
                                            <option value="5002">5002 - Cost of Goods Sold</option>
                                            <option value="4001">4001 - Revenue</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" placeholder="Line description">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control text-end" placeholder="0.00">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control text-end" placeholder="0.00">
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-outline-danger btn-sm">
                                            <i class="ri-delete-bin-6-line"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="table-active">
                                    <td colspan="5" class="text-center text-muted">
                                        Additional journal lines can be added here.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row g-3 align-items-center">
                        <div class="col-md-4">
                            <div class="ledger-modal journal-totals-card h-100">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <span class="text-muted d-block">Total Debit</span>
                                        <span class="fw-bold fs-5">0.00</span>
                                    </div>
                                    <div class="text-end">
                                        <span class="text-muted d-block">Total Credit</span>
                                        <span class="fw-bold fs-5">0.00</span>
                                    </div>
                                </div>
                                <div class="mt-2 d-flex align-items-center gap-2 text-primary">
                                    <i class="ri-equal-line"></i>
                                    <span class="text-uppercase fw-semibold">Balanced</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="alert alert-info border-0 mb-0">
                                <div class="d-flex align-items-start gap-3">
                                    <i class="ri-information-line fs-4"></i>
                                    <div>
                                        <h6 class="fw-semibold mb-1">Posting Reminder</h6>
                                        <p class="mb-0">Ensure total debits equal total credits before approving the
                                            journal entry. You can save as draft if additional review is needed.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex flex-wrap gap-2 w-100 justify-content-between">
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-outline-secondary">Save as Draft</button>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary">
                                <i class="ri-task-line me-1"></i> Validate
                            </button>
                            <button type="button" class="btn btn-primary">
                                <i class="ri-checkbox-circle-line"></i> Post Journal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade ledger-modal" id="approveJournalModal" tabindex="-1"
        aria-labelledby="approveJournalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="approveJournalModalLabel">Approve Journal Entry</h5>
                        <p class="text-muted mb-0">Review exceptions and confirm posting to finalize the ledger.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="avatar-sm rounded-circle bg-success bg-opacity-10 text-success">
                            <i class="ri-checkbox-circle-line"></i>
                        </span>
                        <div>
                            <h6 class="fw-semibold mb-0">Confirm approval of this journal entry.</h6>
                            <small class="text-muted">JRN-000234 &middot; Posted by Mary Owusu</small>
                        </div>
                    </div>
                    <div class="ledger-filter-tags mb-3">
                        <span class="ledger-filter-chip"><i class="ri-shield-check-line"></i> Policy compliant</span>
                        <span class="ledger-filter-chip"><i class="ri-attachment-2"></i> 3 supporting docs</span>
                    </div>
                    <p class="mb-2">Ensure the following before approving:</p>
                    <ul class="mb-0">
                        <li>All supporting documents are attached and verified.</li>
                        <li>Total debit equals total credit.</li>
                        <li>Appropriate approval workflow has been followed.</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary">
                            <i class="ri-mail-send-line"></i> Request changes
                        </button>
                        <button type="button" class="btn btn-success">
                            <i class="ri-check-double-line"></i> Approve &amp; Post
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
