@extends('layouts.vertical', ['page_title' => 'Accounts Payable'])

@section('css')
    <style>
        :root {
            --ap-gradient-start: #031739;
            --ap-gradient-mid: #0a3a8a;
            --ap-gradient-end: #041a45;
            --ap-surface: #f6f8ff;
            --ap-panel: #ffffff;
            --ap-border-soft: rgba(12, 38, 96, 0.08);
            --ap-border-strong: rgba(12, 38, 96, 0.16);
            --ap-shadow-card: 0 26px 46px rgba(7, 32, 86, 0.18);
            --ap-shadow-panel: 0 16px 34px rgba(9, 36, 88, 0.16);
            --ap-text-strong: #07194b;
            --ap-text-muted: rgba(7, 25, 75, 0.68);
            --ap-chip-bg: rgba(58, 122, 242, 0.15);
            --ap-chip-color: #2b5cd9;
            --ap-accent: #ff7a1a;
            --ap-accent-end: #ffb347;
            --ap-success: #16a34a;
            --ap-warning: #f97316;
            --ap-danger: #ef4444;
        }

        .ap-hero {
            background: linear-gradient(135deg, var(--ap-gradient-start), var(--ap-gradient-mid));
            padding: 1px;
            border-radius: 26px;
            box-shadow: var(--ap-shadow-card);
        }

        .ap-hero__surface {
            background: var(--ap-surface);
            border-radius: 25px;
            padding: 2.4rem 2.8rem;
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1.8rem;
        }

        .ap-hero__intro {
            max-width: 620px;
        }

        .ap-hero__eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.35rem 0.85rem;
            border-radius: 999px;
            background: rgba(47, 87, 180, 0.1);
            color: #2a4db7;
            font-weight: 600;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            font-size: 0.72rem;
        }

        .ap-hero__title {
            margin-top: 1rem;
            margin-bottom: 0.65rem;
            font-weight: 700;
            font-size: 1.9rem;
            letter-spacing: 0.02em;
            color: var(--ap-text-strong);
        }

        .ap-hero__subtitle {
            margin-bottom: 1.4rem;
            color: var(--ap-text-muted);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .ap-header-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background: var(--ap-chip-bg);
            color: var(--ap-chip-color);
            border-radius: 999px;
            padding: 0.45rem 1rem;
            letter-spacing: 0.16em;
            font-size: 0.72rem;
            text-transform: uppercase;
            font-weight: 600;
        }

        .ap-hero__actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.9rem;
        }

        .ap-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            border-radius: 999px;
            padding: 0.65rem 1.4rem;
            border: 1px solid transparent;
            font-weight: 600;
            letter-spacing: 0.02em;
            font-size: 0.85rem;
            text-transform: uppercase;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .ap-action-btn i {
            font-size: 1.1rem;
        }

        .ap-action-btn--outline {
            background: rgba(47, 87, 180, 0.1);
            border-color: rgba(47, 87, 180, 0.2);
            color: #2a4db7;
            box-shadow: inset 0 0 0 1px rgba(47, 87, 180, 0.18);
        }

        .ap-action-btn--outline:hover {
            background: rgba(47, 87, 180, 0.16);
            transform: translateY(-2px);
            box-shadow: 0 16px 26px rgba(31, 64, 155, 0.14);
        }

        .ap-action-btn--accent {
            background: linear-gradient(88deg, var(--ap-accent) 0%, var(--ap-accent-end) 100%);
            color: #1f1f1f;
            box-shadow: 0 18px 32px rgba(255, 135, 54, 0.28);
        }

        .ap-action-btn--accent:hover {
            transform: translateY(-2px);
            box-shadow: 0 22px 40px rgba(255, 135, 54, 0.36);
        }

        .ap-card {
            background: linear-gradient(135deg, rgba(31, 64, 155, 0.08), rgba(76, 212, 255, 0.04));
            border-radius: 23px;
            padding: 1px;
            box-shadow: var(--ap-shadow-panel);
        }

        .ap-card__surface {
            background: var(--ap-panel);
            border-radius: 22px;
            padding: 1.8rem 2rem;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .ap-card__header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1.2rem;
            margin-bottom: 1.4rem;
        }

        .ap-card__eyebrow {
            text-transform: uppercase;
            letter-spacing: 0.18em;
            font-size: 0.7rem;
            font-weight: 600;
            color: rgba(7, 25, 75, 0.58);
        }

        .ap-card__title {
            margin: 0.35rem 0 0.4rem;
            font-size: 1.22rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            color: var(--ap-text-strong);
        }

        .ap-card__description {
            margin: 0;
            color: var(--ap-text-muted);
            font-size: 0.85rem;
        }

        .ap-card__toolbar {
            display: flex;
            gap: 0.6rem;
            align-items: center;
        }

        .ap-card__toolbar .btn {
            font-size: 0.75rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            border-radius: 999px;
            padding: 0.35rem 0.9rem;
        }

        .ap-card__toolbar .btn-outline-primary {
            border-color: rgba(47, 87, 180, 0.28);
            color: #2a4db7;
        }

        .ap-card__toolbar .btn-outline-primary:hover {
            background: rgba(47, 87, 180, 0.08);
        }

        .ap-table-wrapper {
            border-radius: 18px;
            border: 1px solid rgba(12, 38, 96, 0.08);
            overflow: hidden;
        }

        .ap-table {
            width: 100%;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .ap-table thead th {
            background: rgba(31, 64, 155, 0.08);
            border-bottom: none;
            padding: 0.85rem 1.1rem;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.16em;
            font-weight: 600;
            color: rgba(7, 25, 75, 0.7);
        }

        .ap-table tbody tr {
            background: rgba(255, 255, 255, 0.92);
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .ap-table tbody tr + tr td {
            border-top: 1px dashed rgba(12, 38, 96, 0.08);
        }

        .ap-table tbody tr:hover {
            background: rgba(58, 122, 242, 0.06);
        }

        .ap-table tbody td {
            padding: 0.95rem 1.1rem;
            vertical-align: middle;
            color: var(--ap-text-strong);
        }

        .ap-table-cell-title {
            font-weight: 600;
            letter-spacing: 0.01em;
        }

        .ap-table-cell-subtext {
            font-size: 0.78rem;
            color: var(--ap-text-muted);
            margin-top: 0.15rem;
        }

        .ap-balance {
            font-weight: 600;
            font-size: 0.88rem;
            letter-spacing: 0.03em;
        }

        .ap-balance--due {
            color: var(--ap-danger);
        }

        .ap-balance--clear {
            color: var(--ap-success);
        }

        .ap-icon-btn {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            border: 1px solid rgba(47, 87, 180, 0.14);
            background: rgba(47, 87, 180, 0.06);
            color: #2a4db7;
            transition: all 0.2s ease;
        }

        .ap-icon-btn:hover {
            background: rgba(47, 87, 180, 0.16);
            border-color: rgba(47, 87, 180, 0.26);
            transform: translateY(-2px);
        }

        .ap-pill-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            border-radius: 999px;
            border: none;
            background: linear-gradient(88deg, rgba(43, 192, 167, 0.95) 0%, rgba(74, 217, 193, 0.92) 100%);
            color: #053222;
            font-weight: 600;
            padding: 0.55rem 1.2rem;
            font-size: 0.8rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            box-shadow: 0 16px 28px rgba(16, 118, 105, 0.18);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .ap-pill-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 34px rgba(16, 118, 105, 0.24);
        }

        .ap-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            border-radius: 999px;
            padding: 0.25rem 0.75rem;
            font-size: 0.72rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 600;
        }

        .ap-status-badge--success {
            background: rgba(22, 163, 74, 0.15);
            color: #056b2e;
        }

        .ap-status-badge--warning {
            background: rgba(249, 115, 22, 0.16);
            color: #7a2e07;
        }

        .ap-status-badge--danger {
            background: rgba(239, 68, 68, 0.16);
            color: #7f1d1d;
        }

        .ap-status-badge--neutral {
            background: rgba(37, 99, 235, 0.12);
            color: #1d4ed8;
        }

        .ap-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: rgba(37, 99, 235, 0.12);
            color: rgba(7, 25, 75, 0.8);
            border-radius: 12px;
            padding: 0.3rem 0.7rem;
            font-size: 0.72rem;
            font-weight: 600;
        }

        .ap-modal .modal-dialog {
            max-width: 780px;
        }

        .ap-modal .modal-content {
            border: none;
            border-radius: 26px;
            padding: 1.5px;
            background: linear-gradient(135deg, rgba(31, 64, 155, 0.22), rgba(4, 26, 69, 0.12));
            box-shadow: 0 32px 58px rgba(7, 32, 86, 0.28);
        }

        .ap-modal__surface {
            background: var(--ap-panel);
            border-radius: 24px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .ap-modal__surface .modal-header {
            padding: 1.45rem 1.8rem;
            background: linear-gradient(125deg, rgba(37, 99, 235, 0.18), rgba(15, 60, 130, 0.08));
            border-bottom: 1px solid rgba(12, 38, 96, 0.08);
        }

        .ap-modal__surface .modal-body {
            padding: 1.6rem 1.8rem;
            background: linear-gradient(180deg, rgba(247, 250, 255, 0.98), rgba(240, 245, 255, 0.94));
        }

        .ap-modal__surface .modal-footer {
            padding: 1.25rem 1.8rem;
            background: rgba(37, 99, 235, 0.08);
            border-top: 1px solid rgba(12, 38, 96, 0.08);
        }

        .ap-modal .modal-title {
            font-weight: 700;
            letter-spacing: 0.01em;
            color: var(--ap-text-strong);
        }

        .ap-modal .btn-close {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 999px;
            opacity: 1;
            padding: 0.55rem;
            box-shadow: 0 10px 22px rgba(7, 32, 86, 0.18);
        }

        .ap-modal .btn-close:hover {
            background: rgba(255, 255, 255, 0.95);
            transform: rotate(90deg);
            transition: transform 0.2s ease;
        }

        .ap-field-group {
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
        }

        .ap-field-label {
            font-size: 0.75rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            font-weight: 600;
            color: rgba(7, 25, 75, 0.65);
        }

        .ap-method-pills {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .ap-method-pill {
            border-radius: 16px;
            padding: 0.65rem 1.15rem;
            border: 1px solid rgba(7, 25, 75, 0.12);
            background: rgba(7, 25, 75, 0.04);
            font-size: 0.82rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 600;
            color: var(--ap-text-strong);
            transition: all 0.2s ease;
        }

        .ap-method-pill:hover {
            background: rgba(37, 99, 235, 0.12);
            border-color: rgba(37, 99, 235, 0.24);
        }

        .btn-check:checked + .ap-method-pill {
            background: linear-gradient(88deg, rgba(43, 192, 167, 0.95) 0%, rgba(74, 217, 193, 0.92) 100%);
            border-color: transparent;
            color: #053222;
            box-shadow: 0 18px 32px rgba(16, 118, 105, 0.2);
        }

        .ap-meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1.2rem;
        }

        .ap-meta-grid dt {
            font-size: 0.72rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: rgba(7, 25, 75, 0.55);
            margin-bottom: 0.35rem;
            font-weight: 600;
        }

        .ap-meta-grid dd {
            margin: 0;
            font-weight: 600;
            color: var(--ap-text-strong);
            letter-spacing: 0.02em;
        }

        .ap-summary-tiles {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 1rem;
            margin-bottom: 1.4rem;
        }

        .ap-summary-tile {
            border-radius: 18px;
            padding: 1rem 1.1rem;
            background: rgba(37, 99, 235, 0.08);
        }

        .ap-summary-tile span {
            display: block;
        }

        .ap-summary-tile .label {
            font-size: 0.72rem;
            letter-spacing: 0.14em;
            color: rgba(7, 25, 75, 0.55);
            text-transform: uppercase;
            font-weight: 600;
        }

        .ap-summary-tile .value {
            margin-top: 0.45rem;
            font-size: 1rem;
            font-weight: 700;
            color: var(--ap-text-strong);
        }

        .form-control-modern,
        .form-select-modern,
        .form-textarea-modern {
            border-radius: 0.85rem;
            border: 1px solid rgba(7, 25, 75, 0.12);
            background-color: rgba(248, 250, 255, 0.95);
            padding: 0.65rem 0.9rem;
            transition: all 0.25s ease;
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
            border-color: rgba(37, 99, 235, 0.55);
            background-color: #fff;
            box-shadow: 0 0 0 0.15rem rgba(37, 99, 235, 0.18);
        }

        ::placeholder {
            color: rgba(100, 116, 139, 0.75) !important;
        }

        .visually-hidden {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            border: 0;
        }

        @media (max-width: 1200px) {
            .ap-hero__surface {
                padding: 2.2rem 2.2rem;
            }

            .ap-card__surface {
                padding: 1.6rem 1.8rem;
            }
        }

        @media (max-width: 992px) {
            .ap-hero__actions {
                flex-direction: row;
                justify-content: flex-start;
                flex-wrap: wrap;
                align-items: center;
            }

            .ap-hero__surface {
                padding: 2rem 1.8rem;
            }
        }

        @media (max-width: 768px) {
            .ap-hero__surface {
                padding: 1.8rem 1.6rem;
            }

            .ap-card__surface {
                padding: 1.5rem 1.4rem;
            }

            .ap-card__header {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 576px) {
            .ap-hero {
                border-radius: 22px;
            }

            .ap-hero__surface {
                padding: 1.6rem 1.4rem;
            }

            .ap-card {
                border-radius: 20px;
            }

            .ap-card__surface {
                padding: 1.35rem 1.3rem;
            }

            .ap-table-wrapper {
                border-radius: 16px;
            }
        }
    </style>
@endsection

@section('content')
    @php
        $vendors = collect([
            [
                'name' => 'Allied Petro Supplies',
                'tin' => 'TIN-0012456789',
                'phone' => '+233 24 567 8123',
                'email' => 'accounts@alliedpetro.com',
                'address' => 'Plot 12, Tema Industrial Area, Accra',
                'balance' => 4580.75,
                'status' => 'Active',
            ],
            [
                'name' => 'North Ridge Lubes',
                'tin' => 'TIN-0045871256',
                'phone' => '+233 20 112 9045',
                'email' => 'finance@northridgelubes.com',
                'address' => 'No. 44 Ayi Mensah Road, Accra',
                'balance' => 0.00,
                'status' => 'Active',
            ],
            [
                'name' => 'Savannah Transport Ltd',
                'tin' => 'TIN-0058841290',
                'phone' => '+233 27 765 4410',
                'email' => 'billing@savannahtransport.com',
                'address' => 'Northern Bypass, Tamale',
                'balance' => 8290.15,
                'status' => 'On Hold',
            ],
            [
                'name' => 'Heritage Energy Services',
                'tin' => 'TIN-0098123456',
                'phone' => '+233 30 298 7765',
                'email' => 'payables@heritageenergy.com',
                'address' => '8 Independence Ave, Ridge, Accra',
                'balance' => 1250.00,
                'status' => 'Pending Review',
            ],
        ]);

        $purchaseInvoices = collect([
            [
                'number' => 'INV-2025-001',
                'vendor' => 'Allied Petro Supplies',
                'date' => '2025-10-12',
                'due_date' => '2025-11-10',
                'vat' => 540.00,
                'nhil' => 202.50,
                'getfund' => 202.50,
                'total' => 7215.00,
                'balance' => 3215.00,
                'status' => 'Due in 12 days',
            ],
            [
                'number' => 'INV-2025-014',
                'vendor' => 'Savannah Transport Ltd',
                'date' => '2025-09-28',
                'due_date' => '2025-10-28',
                'vat' => 320.00,
                'nhil' => 120.00,
                'getfund' => 120.00,
                'total' => 4520.00,
                'balance' => 4520.00,
                'status' => 'Overdue',
            ],
            [
                'number' => 'INV-2025-030',
                'vendor' => 'Heritage Energy Services',
                'date' => '2025-10-05',
                'due_date' => '2025-11-04',
                'vat' => 280.00,
                'nhil' => 105.00,
                'getfund' => 105.00,
                'total' => 3790.00,
                'balance' => 950.00,
                'status' => 'Partial',
            ],
            [
                'number' => 'INV-2025-042',
                'vendor' => 'North Ridge Lubes',
                'date' => '2025-10-18',
                'due_date' => '2025-11-17',
                'vat' => 180.00,
                'nhil' => 67.50,
                'getfund' => 67.50,
                'total' => 2430.00,
                'balance' => 2430.00,
                'status' => 'Due in 22 days',
            ],
        ]);
    @endphp

    <div class="container-fluid">
        <div class="ap-hero mb-4">
            <div class="ap-hero__surface">
                <div class="ap-hero__intro">
                    <span class="ap-hero__eyebrow"><i class="ri-archive-2-line"></i> Payables</span>
                    <h2 class="ap-hero__title">Vendor &amp; Invoice Control Center</h2>
                    <p class="ap-hero__subtitle">Keep vendor relationships healthy, reconcile balances instantly, and stay ahead of every payable with a workspace built for modern finance teams.</p>
                    <span class="ap-header-chip"><i class="ri-user-smile-line"></i> Vendors</span>
                </div>
                <div class="ap-hero__actions">
                    <button type="button" class="ap-action-btn ap-action-btn--outline" data-bs-toggle="modal" data-bs-target="#addVendorModal">
                        <i class="ri-user-add-line"></i>
                        <span>Add Vendor</span>
                    </button>
                    <button type="button" class="ap-action-btn ap-action-btn--accent" data-bs-toggle="modal" data-bs-target="#addInvoiceModal">
                        <i class="ri-file-add-line"></i>
                        <span>Add Purchase Invoice</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xxl-6">
                <div class="ap-card h-100">
                    <div class="ap-card__surface">
                        <div class="ap-card__header">
                            <div>
                                <span class="ap-card__eyebrow">Vendor Directory</span>
                                <h5 class="ap-card__title">All Suppliers</h5>
                                <p class="ap-card__description">Review your supplier portfolio, outstanding balances, and quick actions.</p>
                            </div>
                            <div class="ap-card__toolbar">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addVendorModal">Add Vendor</button>
                            </div>
                        </div>

                        <div class="ap-table-wrapper">
                            <div class="table-responsive">
                                <table class="table ap-table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Vendor Name</th>
                                            <th>TIN</th>
                                            <th>Phone</th>
                                            <th>Balance</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($vendors as $vendor)
                                            <tr>
                                                <td>
                                                    <div class="ap-table-cell-title">{{ $vendor['name'] }}</div>
                                                    <div class="ap-table-cell-subtext">{{ $vendor['email'] }}</div>
                                                </td>
                                                <td>
                                                    <div class="ap-table-cell-title">{{ $vendor['tin'] }}</div>
                                                    <div class="ap-table-cell-subtext">{{ $vendor['status'] }}</div>
                                                </td>
                                                <td>
                                                    <div class="ap-table-cell-title">{{ $vendor['phone'] }}</div>
                                                    <div class="ap-table-cell-subtext">{{ $vendor['address'] }}</div>
                                                </td>
                                                <td>
                                                    <span class="ap-balance {{ $vendor['balance'] > 0 ? 'ap-balance--due' : 'ap-balance--clear' }}">
                                                        GHS {{ number_format($vendor['balance'], 2) }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <div class="btn-group" role="group" aria-label="Vendor actions">
                                                        <button type="button" class="ap-icon-btn" title="View Vendor" data-bs-toggle="modal" data-bs-target="#viewVendorModal" data-vendor='@json($vendor)'>
                                                            <i class="ri-eye-line"></i>
                                                            <span class="visually-hidden">View vendor</span>
                                                        </button>
                                                        <button type="button" class="ap-icon-btn" title="Edit Vendor" data-bs-toggle="modal" data-bs-target="#editVendorModal" data-vendor='@json($vendor)'>
                                                            <i class="ri-edit-line"></i>
                                                            <span class="visually-hidden">Edit vendor</span>
                                                        </button>
                                                        <button type="button" class="ap-icon-btn" title="Delete Vendor" data-bs-toggle="modal" data-bs-target="#deleteVendorModal" data-vendor='@json($vendor)'>
                                                            <i class="ri-delete-bin-6-line"></i>
                                                            <span class="visually-hidden">Delete vendor</span>
                                                        </button>
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

            <div class="col-xxl-6">
                <div class="ap-card h-100">
                    <div class="ap-card__surface">
                        <div class="ap-card__header">
                            <div>
                                <span class="ap-card__eyebrow">Purchase Invoices</span>
                                <h5 class="ap-card__title">Outstanding Payables</h5>
                                <p class="ap-card__description">Track tax breakdowns, due dates, and initiate payments in one click.</p>
                            </div>
                            <div class="ap-card__toolbar">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addInvoiceModal">Add Invoice</button>
                            </div>
                        </div>

                        <div class="ap-table-wrapper">
                            <div class="table-responsive">
                                <table class="table ap-table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Invoice No</th>
                                            <th>Vendor</th>
                                            <th>Date</th>
                                            <th>VAT</th>
                                            <th>NHIL</th>
                                            <th>GETFund</th>
                                            <th>Total</th>
                                            <th>Balance</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($purchaseInvoices as $invoice)
                                            <tr>
                                                <td>
                                                    <div class="ap-table-cell-title">{{ $invoice['number'] }}</div>
                                                    <div class="ap-table-cell-subtext">Due {{ \Carbon\Carbon::parse($invoice['due_date'])->format('d M, Y') }}</div>
                                                </td>
                                                <td>
                                                    <div class="ap-table-cell-title">{{ $invoice['vendor'] }}</div>
                                                    <div class="ap-table-cell-subtext">{{ $invoice['status'] }}</div>
                                                </td>
                                                <td>
                                                    <div class="ap-table-cell-title">{{ \Carbon\Carbon::parse($invoice['date'])->format('d M, Y') }}</div>
                                                </td>
                                                <td>GHS {{ number_format($invoice['vat'], 2) }}</td>
                                                <td>GHS {{ number_format($invoice['nhil'], 2) }}</td>
                                                <td>GHS {{ number_format($invoice['getfund'], 2) }}</td>
                                                <td>
                                                    <span class="ap-balance ap-balance--clear">GHS {{ number_format($invoice['total'], 2) }}</span>
                                                </td>
                                                <td>
                                                    <span class="ap-balance {{ $invoice['balance'] > 0 ? 'ap-balance--due' : 'ap-balance--clear' }}">GHS {{ number_format($invoice['balance'], 2) }}</span>
                                                </td>
                                                <td class="text-end">
                                                    <button type="button" class="ap-pill-btn" data-bs-toggle="modal" data-bs-target="#payInvoiceModal" data-invoice='@json($invoice)'>
                                                        <i class="ri-wallet-3-line"></i>
                                                        <span>Pay Invoice</span>
                                                    </button>
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

    <!-- Add Vendor Modal -->
    <div class="modal fade ap-modal" id="addVendorModal" tabindex="-1" aria-labelledby="addVendorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="ap-modal__surface">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addVendorModalLabel">Add Vendor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="vendorName">Vendor Name</label>
                                        <input type="text" class="form-control form-control-modern" id="vendorName" placeholder="Enter vendor name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="vendorTin">Tax Identification Number</label>
                                        <input type="text" class="form-control form-control-modern" id="vendorTin" placeholder="Enter TIN">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="vendorPhone">Phone Number</label>
                                        <input type="tel" class="form-control form-control-modern" id="vendorPhone" placeholder="Enter phone number">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="vendorEmail">Email Address</label>
                                        <input type="email" class="form-control form-control-modern" id="vendorEmail" placeholder="Enter email address">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="vendorAddress">Primary Address</label>
                                        <textarea class="form-control form-textarea-modern" id="vendorAddress" placeholder="Enter vendor address"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="vendorOpeningBalance">Opening Balance (GHS)</label>
                                        <input type="number" class="form-control form-control-modern" id="vendorOpeningBalance" placeholder="0.00" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="vendorStatus">Status</label>
                                        <select id="vendorStatus" class="form-select form-select-modern">
                                            <option value="active" selected>Active</option>
                                            <option value="on_hold">On Hold</option>
                                            <option value="suspended">Suspended</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary">Save Vendor</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Vendor Modal -->
    <div class="modal fade ap-modal" id="viewVendorModal" tabindex="-1" aria-labelledby="viewVendorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="ap-modal__surface">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewVendorModalLabel">Vendor Overview</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="mb-1" data-vendor-field="name">Vendor Name</h5>
                                <div class="text-muted" data-vendor-field="email">Email</div>
                            </div>
                            <span class="ap-status-badge ap-status-badge--neutral" data-vendor-field="status-badge">Status</span>
                        </div>
                        <div class="ap-summary-tiles">
                            <div class="ap-summary-tile">
                                <span class="label">Outstanding Balance</span>
                                <span class="value" data-vendor-field="balance">GHS 0.00</span>
                            </div>
                            <div class="ap-summary-tile">
                                <span class="label">TIN</span>
                                <span class="value" data-vendor-field="tin">TIN</span>
                            </div>
                            <div class="ap-summary-tile">
                                <span class="label">Contact</span>
                                <span class="value" data-vendor-field="phone">Phone</span>
                            </div>
                        </div>
                        <dl class="ap-meta-grid mt-4">
                            <div>
                                <dt>Email Address</dt>
                                <dd data-vendor-field="email-detail">Email</dd>
                            </div>
                            <div>
                                <dt>Phone Number</dt>
                                <dd data-vendor-field="phone-detail">Phone</dd>
                            </div>
                            <div>
                                <dt>Primary Address</dt>
                                <dd data-vendor-field="address">Address</dd>
                            </div>
                        </dl>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editVendorModal" data-forward="edit">Edit Vendor</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Vendor Modal -->
    <div class="modal fade ap-modal" id="editVendorModal" tabindex="-1" aria-labelledby="editVendorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="ap-modal__surface">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editVendorModalLabel">Edit Vendor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="editVendorName">Vendor Name</label>
                                        <input type="text" class="form-control form-control-modern" id="editVendorName" placeholder="Update vendor name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="editVendorTin">Tax Identification Number</label>
                                        <input type="text" class="form-control form-control-modern" id="editVendorTin" placeholder="Update TIN">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="editVendorPhone">Phone Number</label>
                                        <input type="tel" class="form-control form-control-modern" id="editVendorPhone" placeholder="Update phone number">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="editVendorEmail">Email Address</label>
                                        <input type="email" class="form-control form-control-modern" id="editVendorEmail" placeholder="Update email address">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="editVendorAddress">Primary Address</label>
                                        <textarea class="form-control form-textarea-modern" id="editVendorAddress" placeholder="Update vendor address"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="editVendorBalance">Outstanding Balance</label>
                                        <input type="number" class="form-control form-control-modern" id="editVendorBalance" placeholder="0.00" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="editVendorStatus">Status</label>
                                        <select id="editVendorStatus" class="form-select form-select-modern">
                                            <option value="Active">Active</option>
                                            <option value="On Hold">On Hold</option>
                                            <option value="Pending Review">Pending Review</option>
                                            <option value="Suspended">Suspended</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary">Update Vendor</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Vendor Modal -->
    <div class="modal fade ap-modal" id="deleteVendorModal" tabindex="-1" aria-labelledby="deleteVendorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="ap-modal__surface">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteVendorModalLabel">Delete Vendor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-3">You are about to remove <strong data-vendor-field="delete-name">Vendor Name</strong> from your payables directory.</p>
                        <p class="text-muted mb-0">This action will archive historical transactions but preserve financial records. Are you sure you want to continue?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger">Delete Vendor</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Purchase Invoice Modal -->
    <div class="modal fade ap-modal" id="addInvoiceModal" tabindex="-1" aria-labelledby="addInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="ap-modal__surface">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addInvoiceModalLabel">Add Purchase Invoice</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="invoiceNumber">Invoice Number</label>
                                        <input type="text" class="form-control form-control-modern" id="invoiceNumber" placeholder="Enter invoice number">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="invoiceVendor">Vendor</label>
                                        <select id="invoiceVendor" class="form-select form-select-modern">
                                            <option value="">Select Vendor</option>
                                            @foreach ($vendors as $vendor)
                                                <option value="{{ $vendor['name'] }}">{{ $vendor['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="invoiceDate">Invoice Date</label>
                                        <input type="date" class="form-control form-control-modern" id="invoiceDate" placeholder="Select invoice date">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="invoiceDueDate">Due Date</label>
                                        <input type="date" class="form-control form-control-modern" id="invoiceDueDate" placeholder="Select due date">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="invoiceVat">VAT (GHS)</label>
                                        <input type="number" class="form-control form-control-modern" id="invoiceVat" placeholder="0.00" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="invoiceNhil">NHIL (GHS)</label>
                                        <input type="number" class="form-control form-control-modern" id="invoiceNhil" placeholder="0.00" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="invoiceGetfund">GETFund (GHS)</label>
                                        <input type="number" class="form-control form-control-modern" id="invoiceGetfund" placeholder="0.00" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="invoiceTotal">Total (GHS)</label>
                                        <input type="number" class="form-control form-control-modern" id="invoiceTotal" placeholder="0.00" step="0.01">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="invoiceNotes">Internal Notes</label>
                                        <textarea class="form-control form-textarea-modern" id="invoiceNotes" placeholder="Add any internal notes"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary">Save Invoice</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pay Invoice Modal -->
    <div class="modal fade ap-modal" id="payInvoiceModal" tabindex="-1" aria-labelledby="payInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="ap-modal__surface">
                    <div class="modal-header">
                        <h5 class="modal-title" id="payInvoiceModalLabel">Make Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="ap-summary-tiles">
                            <div class="ap-summary-tile">
                                <span class="label">Invoice Number</span>
                                <span class="value" data-invoice-field="number">INV-0000</span>
                            </div>
                            <div class="ap-summary-tile">
                                <span class="label">Vendor</span>
                                <span class="value" data-invoice-field="vendor">Vendor</span>
                            </div>
                            <div class="ap-summary-tile">
                                <span class="label">Balance Due</span>
                                <span class="value" data-invoice-field="balance">GHS 0.00</span>
                            </div>
                            <div class="ap-summary-tile">
                                <span class="label">Due Date</span>
                                <span class="value" data-invoice-field="due-date">--</span>
                            </div>
                        </div>
                        <form>
                            <div class="mb-3">
                                <label class="form-label text-uppercase fw-semibold text-muted">Payment Method</label>
                                <div class="ap-method-pills">
                                    <input class="btn-check" type="radio" name="payment_method" id="paymentMethodMoMo" value="MoMo" checked>
                                    <label class="ap-method-pill" for="paymentMethodMoMo"><i class="ri-smartphone-line me-1"></i> MoMo</label>

                                    <input class="btn-check" type="radio" name="payment_method" id="paymentMethodBank" value="Bank">
                                    <label class="ap-method-pill" for="paymentMethodBank"><i class="ri-bank-card-line me-1"></i> Bank</label>

                                    <input class="btn-check" type="radio" name="payment_method" id="paymentMethodCash" value="Cash">
                                    <label class="ap-method-pill" for="paymentMethodCash"><i class="ri-money-dollar-circle-line me-1"></i> Cash</label>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="paymentAmount">Amount (GHS)</label>
                                        <input type="number" class="form-control form-control-modern" id="paymentAmount" placeholder="0.00" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="paymentCharges">Charges (GHS)</label>
                                        <input type="number" class="form-control form-control-modern" id="paymentCharges" placeholder="0.00" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="paymentDate">Payment Date</label>
                                        <input type="date" class="form-control form-control-modern" id="paymentDate" placeholder="Select payment date">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="paymentReference">Reference</label>
                                        <input type="text" class="form-control form-control-modern" id="paymentReference" placeholder="Enter payment reference">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="ap-field-group">
                                        <label class="ap-field-label" for="paymentNotes">Internal Notes</label>
                                        <textarea class="form-control form-textarea-modern" id="paymentNotes" placeholder="Add internal notes"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary">Confirm Payment</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const vendorStatusClassMap = {
                'active': 'ap-status-badge ap-status-badge--success',
                'on hold': 'ap-status-badge ap-status-badge--warning',
                'pending review': 'ap-status-badge ap-status-badge--warning',
                'suspended': 'ap-status-badge ap-status-badge--danger',
                'default': 'ap-status-badge ap-status-badge--neutral'
            };

            const currencyFormatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'GHS',
                minimumFractionDigits: 2
            });

            const viewVendorModal = document.getElementById('viewVendorModal');
            if (viewVendorModal) {
                viewVendorModal.addEventListener('show.bs.modal', event => {
                    const trigger = event.relatedTarget;
                    if (!trigger) return;
                    const vendor = JSON.parse(trigger.getAttribute('data-vendor') || '{}');

                    viewVendorModal.querySelector('[data-vendor-field="name"]').textContent = vendor.name || '—';
                    viewVendorModal.querySelector('[data-vendor-field="email"]').textContent = vendor.email || '—';
                    viewVendorModal.querySelector('[data-vendor-field="email-detail"]').textContent = vendor.email || '—';
                    viewVendorModal.querySelector('[data-vendor-field="phone"]').textContent = vendor.phone || '—';
                    viewVendorModal.querySelector('[data-vendor-field="phone-detail"]').textContent = vendor.phone || '—';
                    viewVendorModal.querySelector('[data-vendor-field="tin"]').textContent = vendor.tin || '—';
                    viewVendorModal.querySelector('[data-vendor-field="address"]').textContent = vendor.address || '—';
                    viewVendorModal.querySelector('[data-vendor-field="balance"]').textContent = currencyFormatter.format(Number(vendor.balance || 0));

                    const statusText = (vendor.status || 'Active');
                    const statusBadge = viewVendorModal.querySelector('[data-vendor-field="status-badge"]');
                    const normalizedStatus = statusText.toLowerCase();
                    statusBadge.textContent = statusText;
                    statusBadge.className = vendorStatusClassMap[normalizedStatus] || vendorStatusClassMap.default;

                    const editButton = viewVendorModal.querySelector('[data-forward="edit"]');
                    if (editButton) {
                        editButton.setAttribute('data-vendor', JSON.stringify(vendor));
                    }
                });
            }

            const editVendorModal = document.getElementById('editVendorModal');
            if (editVendorModal) {
                editVendorModal.addEventListener('show.bs.modal', event => {
                    const trigger = event.relatedTarget;
                    const vendorData = trigger?.getAttribute('data-vendor') || trigger?.getAttribute('data-forward');
                    if (!vendorData) return;
                    const vendor = JSON.parse(trigger?.getAttribute('data-vendor') || viewVendorModal.querySelector('[data-forward="edit"]').getAttribute('data-vendor') || '{}');

                    editVendorModal.querySelector('#editVendorName').value = vendor.name || '';
                    editVendorModal.querySelector('#editVendorTin').value = vendor.tin || '';
                    editVendorModal.querySelector('#editVendorPhone').value = vendor.phone || '';
                    editVendorModal.querySelector('#editVendorEmail').value = vendor.email || '';
                    editVendorModal.querySelector('#editVendorAddress').value = vendor.address || '';
                    editVendorModal.querySelector('#editVendorBalance').value = vendor.balance !== undefined ? Number(vendor.balance).toFixed(2) : '';
                    const statusSelect = editVendorModal.querySelector('#editVendorStatus');
                    if (statusSelect) {
                        const options = Array.from(statusSelect.options).map(opt => opt.value.toLowerCase());
                        const normalizedStatus = (vendor.status || '').toLowerCase();
                        statusSelect.value = options.includes(normalizedStatus) ? vendor.status : 'Active';
                    }
                });
            }

            const deleteVendorModal = document.getElementById('deleteVendorModal');
            if (deleteVendorModal) {
                deleteVendorModal.addEventListener('show.bs.modal', event => {
                    const trigger = event.relatedTarget;
                    if (!trigger) return;
                    const vendor = JSON.parse(trigger.getAttribute('data-vendor') || '{}');
                    deleteVendorModal.querySelector('[data-vendor-field="delete-name"]').textContent = vendor.name || 'this vendor';
                });
            }

            const payInvoiceModal = document.getElementById('payInvoiceModal');
            if (payInvoiceModal) {
                payInvoiceModal.addEventListener('show.bs.modal', event => {
                    const trigger = event.relatedTarget;
                    if (!trigger) return;
                    const invoice = JSON.parse(trigger.getAttribute('data-invoice') || '{}');

                    payInvoiceModal.querySelector('[data-invoice-field="number"]').textContent = invoice.number || '—';
                    payInvoiceModal.querySelector('[data-invoice-field="vendor"]').textContent = invoice.vendor || '—';
                    payInvoiceModal.querySelector('[data-invoice-field="due-date"]').textContent = invoice.due_date ? new Date(invoice.due_date).toLocaleDateString() : '—';
                    payInvoiceModal.querySelector('[data-invoice-field="balance"]').textContent = currencyFormatter.format(Number(invoice.balance || 0));

                    const amountInput = payInvoiceModal.querySelector('#paymentAmount');
                    const chargesInput = payInvoiceModal.querySelector('#paymentCharges');
                    if (amountInput) {
                        amountInput.value = invoice.balance !== undefined ? Number(invoice.balance).toFixed(2) : '';
                    }
                    if (chargesInput) {
                        chargesInput.value = '0.00';
                    }
                    const referenceInput = payInvoiceModal.querySelector('#paymentReference');
                    if (referenceInput) {
                        referenceInput.value = invoice.number ? `PAY-${invoice.number}` : '';
                    }
                    const paymentDateInput = payInvoiceModal.querySelector('#paymentDate');
                    if (paymentDateInput) {
                        paymentDateInput.valueAsDate = new Date();
                    }
                });
            }
        });
    </script>
@endsection
