@extends('layouts.vertical', ['page_title' => 'Accounts Receivable'])

@section('css')
    <style>
        :root {
            --ar-gradient-start: #071a36;
            --ar-gradient-mid: #114b8b;
            --ar-gradient-end: #0c274d;
            --ar-surface: #f5f7ff;
            --ar-panel: #ffffff;
            --ar-border-soft: rgba(13, 43, 92, 0.08);
            --ar-border-strong: rgba(13, 43, 92, 0.16);
            --ar-shadow-card: 0 28px 48px rgba(9, 32, 72, 0.22);
            --ar-shadow-panel: 0 20px 40px rgba(12, 40, 86, 0.16);
            --ar-text-strong: #0b1f3f;
            --ar-text-muted: rgba(11, 31, 63, 0.68);
            --ar-chip-bg: rgba(59, 130, 246, 0.18);
            --ar-chip-color: #1d4ed8;
            --ar-accent: #38bdf8;
            --ar-accent-end: #14b8a6;
            --ar-success: #059669;
            --ar-warning: #f59e0b;
            --ar-danger: #dc2626;
        }

        .ar-hero {
            background: linear-gradient(135deg, var(--ar-gradient-start), var(--ar-gradient-mid));
            padding: 1.2px;
            border-radius: 28px;
            box-shadow: var(--ar-shadow-card);
        }

        .ar-hero__surface {
            background: var(--ar-surface);
            border-radius: 27px;
            padding: 2.6rem 3rem;
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
            gap: 2rem;
        }

        .ar-hero__intro {
            max-width: 620px;
        }

        .ar-hero__eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.38rem 0.95rem;
            border-radius: 999px;
            background: rgba(37, 99, 235, 0.16);
            color: #2450c8;
            font-weight: 600;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            font-size: 0.72rem;
        }

        .ar-hero__title {
            margin-top: 1.1rem;
            margin-bottom: 0.75rem;
            font-weight: 700;
            font-size: 2rem;
            letter-spacing: 0.02em;
            color: var(--ar-text-strong);
        }

        .ar-hero__subtitle {
            margin-bottom: 1.5rem;
            color: var(--ar-text-muted);
            font-size: 0.96rem;
            line-height: 1.65;
        }

        .ar-header-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--ar-chip-bg);
            color: var(--ar-chip-color);
            border-radius: 999px;
            padding: 0.5rem 1.15rem;
            letter-spacing: 0.16em;
            font-size: 0.72rem;
            text-transform: uppercase;
            font-weight: 600;
        }

        .ar-hero__actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 1rem;
        }

        .ar-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            border-radius: 999px;
            padding: 0.75rem 1.6rem;
            border: 1px solid transparent;
            font-weight: 600;
            letter-spacing: 0.04em;
            font-size: 0.85rem;
            text-transform: uppercase;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .ar-action-btn i {
            font-size: 1.1rem;
        }

        .ar-action-btn--outline {
            background: rgba(37, 99, 235, 0.12);
            border-color: rgba(37, 99, 235, 0.2);
            color: #2154c2;
            box-shadow: inset 0 0 0 1px rgba(37, 99, 235, 0.18);
        }

        .ar-action-btn--outline:hover {
            background: rgba(37, 99, 235, 0.18);
            transform: translateY(-2px);
            box-shadow: 0 16px 28px rgba(29, 78, 216, 0.18);
        }

        .ar-action-btn--accent {
            background: linear-gradient(95deg, var(--ar-accent) 0%, var(--ar-accent-end) 100%);
            color: #062c3a;
            box-shadow: 0 20px 38px rgba(56, 189, 248, 0.28);
        }

        .ar-action-btn--accent:hover {
            transform: translateY(-2px);
            box-shadow: 0 24px 42px rgba(20, 184, 166, 0.34);
        }

        .ar-card {
            background: linear-gradient(135deg, rgba(17, 75, 139, 0.12), rgba(20, 184, 166, 0.08));
            border-radius: 24px;
            padding: 1.2px;
            box-shadow: var(--ar-shadow-panel);
        }

        .ar-card__surface {
            background: var(--ar-panel);
            border-radius: 23px;
            padding: 2rem;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .ar-card__header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1.4rem;
            margin-bottom: 1.6rem;
        }

        .ar-card__eyebrow {
            text-transform: uppercase;
            letter-spacing: 0.18em;
            font-size: 0.72rem;
            font-weight: 600;
            color: rgba(10, 34, 74, 0.66);
        }

        .ar-card__title {
            margin: 0.4rem 0 0.45rem;
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            color: var(--ar-text-strong);
        }

        .ar-card__description {
            margin: 0;
            color: var(--ar-text-muted);
            font-size: 0.88rem;
        }

        .ar-card__toolbar {
            display: flex;
            gap: 0.65rem;
            align-items: center;
        }

        .ar-card__toolbar .btn {
            font-size: 0.75rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            border-radius: 999px;
            padding: 0.4rem 1rem;
        }

        .ar-card__toolbar .btn-outline-primary {
            border-color: rgba(37, 99, 235, 0.28);
            color: #1d4ed8;
        }

        .ar-card__toolbar .btn-outline-primary:hover {
            background: rgba(37, 99, 235, 0.08);
        }

        .ar-table-wrapper {
            border-radius: 18px;
            border: 1px solid var(--ar-border-soft);
            overflow: hidden;
        }

        .ar-table {
            width: 100%;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .ar-table thead th {
            background: rgba(17, 75, 139, 0.08);
            border-bottom: none;
            padding: 0.9rem 1.15rem;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.16em;
            font-weight: 600;
            color: rgba(10, 28, 58, 0.7);
        }

        .ar-table tbody tr {
            background: rgba(255, 255, 255, 0.94);
            transition: background 0.2s ease;
        }

        .ar-table tbody tr + tr td {
            border-top: 1px dashed var(--ar-border-soft);
        }

        .ar-table tbody tr:hover {
            background: rgba(56, 189, 248, 0.08);
        }

        .ar-table tbody td {
            padding: 1rem 1.15rem;
            vertical-align: middle;
            color: var(--ar-text-strong);
        }

        .ar-table-cell-title {
            font-weight: 600;
            letter-spacing: 0.01em;
        }

        .ar-table-cell-subtext {
            font-size: 0.78rem;
            color: var(--ar-text-muted);
            margin-top: 0.2rem;
        }

        .ar-balance {
            font-weight: 600;
            font-size: 0.9rem;
            letter-spacing: 0.03em;
        }

        .ar-balance--due {
            color: var(--ar-danger);
        }

        .ar-balance--clear {
            color: var(--ar-success);
        }

        .ar-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            border-radius: 999px;
            padding: 0.3rem 0.85rem;
            font-size: 0.74rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            font-weight: 600;
        }

        .ar-status-badge--paid {
            background: rgba(5, 150, 105, 0.18);
            color: #065f46;
        }

        .ar-status-badge--partial {
            background: rgba(245, 158, 11, 0.18);
            color: #78350f;
        }

        .ar-status-badge--due {
            background: rgba(37, 99, 235, 0.18);
            color: #1d4ed8;
        }

        .ar-status-badge--overdue {
            background: rgba(220, 38, 38, 0.18);
            color: #7f1d1d;
        }

        .ar-status-badge--active {
            background: rgba(34, 197, 94, 0.18);
            color: #047857;
        }

        .ar-status-badge--strategic {
            background: rgba(56, 189, 248, 0.18);
            color: #0369a1;
        }

        .ar-status-badge--watchlist {
            background: rgba(245, 158, 11, 0.2);
            color: #854d0e;
        }

        .ar-status-badge--hold {
            background: rgba(220, 38, 38, 0.2);
            color: #7f1d1d;
        }

        .ar-icon-btn {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            border: 1px solid rgba(37, 99, 235, 0.18);
            background: rgba(37, 99, 235, 0.08);
            color: #1d4ed8;
            transition: all 0.2s ease;
        }

        .ar-icon-btn:hover {
            background: rgba(37, 99, 235, 0.18);
            border-color: rgba(37, 99, 235, 0.28);
            transform: translateY(-2px);
        }

        .ar-pill-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            border-radius: 999px;
            border: none;
            background: linear-gradient(92deg, rgba(14, 165, 233, 0.95) 0%, rgba(59, 130, 246, 0.92) 100%);
            color: #052639;
            font-weight: 600;
            padding: 0.6rem 1.35rem;
            font-size: 0.82rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            box-shadow: 0 18px 32px rgba(14, 165, 233, 0.2);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .ar-pill-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 22px 36px rgba(59, 130, 246, 0.26);
        }

        .ar-modal .modal-dialog {
            max-width: 780px;
        }

        .ar-modal .modal-content {
            border: none;
            border-radius: 26px;
            padding: 1.5px;
            background: linear-gradient(135deg, rgba(17, 75, 139, 0.22), rgba(8, 45, 92, 0.14));
            box-shadow: 0 32px 60px rgba(9, 32, 72, 0.3);
        }

        .ar-modal__surface {
            background: var(--ar-panel);
            border-radius: 24px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .ar-modal__surface .modal-header {
            padding: 1.5rem 1.9rem;
            background: linear-gradient(125deg, rgba(37, 99, 235, 0.16), rgba(17, 75, 139, 0.08));
            border-bottom: 1px solid var(--ar-border-soft);
        }

        .ar-modal__surface .modal-body {
            padding: 1.75rem 1.9rem;
            background: linear-gradient(180deg, rgba(246, 248, 255, 0.98), rgba(238, 243, 255, 0.94));
        }

        .ar-modal__surface .modal-footer {
            padding: 1.25rem 1.9rem;
            background: rgba(37, 99, 235, 0.08);
            border-top: 1px solid var(--ar-border-soft);
        }

        .ar-modal .btn-close {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 999px;
            opacity: 1;
            padding: 0.55rem;
            box-shadow: 0 10px 22px rgba(9, 32, 72, 0.2);
        }

        .ar-modal .btn-close:hover {
            background: rgba(255, 255, 255, 0.95);
            transform: rotate(90deg);
            transition: transform 0.2s ease;
        }

        .ar-field-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .ar-field-label {
            font-size: 0.75rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            font-weight: 600;
            color: rgba(11, 31, 63, 0.68);
        }

        .form-control-modern,
        .form-select-modern,
        .form-textarea-modern {
            border-radius: 0.85rem;
            border: 1px solid rgba(11, 31, 63, 0.12);
            background-color: rgba(248, 250, 255, 0.96);
            padding: 0.7rem 0.95rem;
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
            box-shadow: 0 0 0 0.15rem rgba(37, 99, 235, 0.2);
        }

        .ar-method-pills {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .ar-method-pill {
            border-radius: 16px;
            padding: 0.7rem 1.2rem;
            border: 1px solid rgba(11, 31, 63, 0.12);
            background: rgba(11, 31, 63, 0.05);
            font-size: 0.82rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 600;
            color: var(--ar-text-strong);
            transition: all 0.2s ease;
        }

        .ar-method-pill:hover {
            background: rgba(37, 99, 235, 0.12);
            border-color: rgba(37, 99, 235, 0.24);
        }

        .btn-check:checked + .ar-method-pill {
            background: linear-gradient(92deg, rgba(14, 165, 233, 0.95) 0%, rgba(20, 184, 166, 0.92) 100%);
            border-color: transparent;
            color: #064e3b;
            box-shadow: 0 18px 32px rgba(20, 184, 166, 0.24);
        }

        .ar-form {
            display: flex;
            flex-direction: column;
            gap: 1.6rem;
        }

        .ar-form-section {
            background: linear-gradient(135deg, rgba(17, 75, 139, 0.08), rgba(56, 189, 248, 0.06));
            border: 1px solid rgba(13, 43, 92, 0.08);
            border-radius: 20px;
            padding: 1.4rem 1.6rem;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.35);
        }

        .ar-form-section__heading {
            margin-bottom: 1.1rem;
        }

        .ar-form-section__eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            font-size: 0.68rem;
            font-weight: 600;
            color: rgba(10, 34, 74, 0.6);
        }

        .ar-form-section__title {
            margin: 0.45rem 0 0.2rem;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--ar-text-strong);
        }

        .ar-form-section__subtitle {
            margin: 0;
            color: rgba(11, 31, 63, 0.7);
            font-size: 0.86rem;
        }

        .ar-form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem 1.2rem;
        }

        .ar-form-field {
            display: flex;
            flex-direction: column;
            gap: 0.55rem;
        }

        .ar-input {
            position: relative;
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(11, 31, 63, 0.12);
            border-radius: 0.95rem;
            padding: 0.55rem 0.9rem 0.55rem 2.9rem;
            transition: all 0.25s ease;
        }

        .ar-input:focus-within {
            border-color: rgba(37, 99, 235, 0.55);
            box-shadow: 0 0 0 0.15rem rgba(37, 99, 235, 0.2);
            background: #fff;
        }

        .ar-input__icon {
            position: absolute;
            left: 1rem;
            font-size: 1.1rem;
            color: rgba(11, 31, 63, 0.55);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .ar-input__control {
            border: none;
            background: transparent;
            padding: 0;
            box-shadow: none !important;
        }

        .ar-input__control:focus {
            box-shadow: none;
        }

        .ar-input--textarea {
            align-items: flex-start;
            padding-top: 1rem;
        }

        .ar-input--textarea .ar-input__icon {
            top: 1.05rem;
        }

        .ar-input--textarea .ar-input__control {
            min-height: 120px;
            resize: vertical;
        }

        .ar-form-divider {
            height: 1px;
            background: linear-gradient(90deg, rgba(13, 43, 92, 0), rgba(13, 43, 92, 0.18), rgba(13, 43, 92, 0));
            margin: 0.2rem 0 1rem;
        }

        .ar-form-helper {
            font-size: 0.72rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(11, 31, 63, 0.48);
            font-weight: 600;
        }

        .ar-summary-tiles {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 1.1rem;
            margin-bottom: 1.4rem;
        }

        .ar-summary-tile {
            border-radius: 18px;
            padding: 1rem 1.15rem;
            background: rgba(37, 99, 235, 0.1);
        }

        .ar-summary-tile span {
            display: block;
        }

        .ar-summary-tile .label {
            font-size: 0.72rem;
            letter-spacing: 0.14em;
            color: rgba(11, 31, 63, 0.55);
            text-transform: uppercase;
            font-weight: 600;
        }

        .ar-summary-tile .value {
            margin-top: 0.5rem;
            font-size: 1.02rem;
            font-weight: 700;
            color: var(--ar-text-strong);
        }

        @media (max-width: 1200px) {
            .ar-hero__surface {
                padding: 2.2rem 2.4rem;
            }

            .ar-card__surface {
                padding: 1.75rem 1.9rem;
            }
        }

        @media (max-width: 992px) {
            .ar-hero__actions {
                flex-direction: row;
                justify-content: flex-start;
                flex-wrap: wrap;
                align-items: center;
            }

            .ar-hero__surface {
                padding: 2rem 1.85rem;
            }

            .ar-form-section {
                padding: 1.3rem 1.4rem;
            }
        }

        @media (max-width: 768px) {
            .ar-hero__surface {
                padding: 1.85rem 1.65rem;
            }

            .ar-card__surface {
                padding: 1.6rem 1.5rem;
            }

            .ar-card__header {
                flex-direction: column;
                align-items: flex-start;
            }

            .ar-form-grid {
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            }
        }

        @media (max-width: 576px) {
            .ar-hero {
                border-radius: 24px;
            }

            .ar-hero__surface {
                padding: 1.6rem 1.4rem;
            }

            .ar-card {
                border-radius: 22px;
            }

            .ar-card__surface {
                padding: 1.4rem 1.35rem;
            }

            .ar-table-wrapper {
                border-radius: 16px;
            }

            .ar-form-section {
                border-radius: 18px;
                padding: 1.2rem 1.15rem;
            }

            .ar-form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    @php
        $customers = collect([
            [
                'name' => 'Empire Transport Ltd',
                'tin' => 'TIN-0084512369',
                'phone' => '+233 24 812 4501',
                'email' => 'finance@empiretransport.com',
                'city' => 'Accra, Ghana',
                'balance' => 12480.50,
                'since' => '2019',
                'status' => 'Active',
                'credit_limit' => 25000.00,
                'address' => '12 Liberation Road, Airport City, Accra',
            ],
            [
                'name' => 'Savannah Agro Oil',
                'tin' => 'TIN-0045823691',
                'phone' => '+233 20 445 7810',
                'email' => 'accounts@savannahagro.com',
                'city' => 'Tamale, Ghana',
                'balance' => 0.00,
                'since' => '2021',
                'status' => 'Strategic',
                'credit_limit' => 18000.00,
                'address' => 'Plot 8, Nyohene Road, Tamale',
            ],
            [
                'name' => 'Northern Corridor Logistics',
                'tin' => 'TIN-0021589746',
                'phone' => '+233 27 210 9654',
                'email' => 'billing@nclogistics.com',
                'city' => 'Bolgatanga, Ghana',
                'balance' => 5875.30,
                'since' => '2018',
                'status' => 'Watchlist',
                'credit_limit' => 12000.00,
                'address' => 'No. 44 Ring Road, Bolgatanga',
            ],
            [
                'name' => 'BlueWave Marine Supplies',
                'tin' => 'TIN-0098745631',
                'phone' => '+233 30 992 1144',
                'email' => 'receivables@bluewave.com',
                'city' => 'Tema, Ghana',
                'balance' => 3240.00,
                'since' => '2020',
                'status' => 'On Hold',
                'credit_limit' => 9000.00,
                'address' => 'Harbour Front Loop, Meridian Enclave, Tema',
            ],
        ]);

        $salesInvoices = collect([
            [
                'number' => 'SINV-2025-104',
                'customer' => 'Empire Transport Ltd',
                'subtotal' => 9800.00,
                'vat' => 1470.00,
                'nhil' => 367.50,
                'getfund' => 367.50,
                'total' => 12005.00,
                'balance' => 6205.00,
                'payment_status' => 'Partial',
                'due_date' => '2025-11-22',
            ],
            [
                'number' => 'SINV-2025-111',
                'customer' => 'Savannah Agro Oil',
                'subtotal' => 5140.00,
                'vat' => 771.00,
                'nhil' => 192.75,
                'getfund' => 192.75,
                'total' => 6296.50,
                'balance' => 0.00,
                'payment_status' => 'Paid',
                'due_date' => '2025-10-18',
            ],
            [
                'number' => 'SINV-2025-125',
                'customer' => 'Northern Corridor Logistics',
                'subtotal' => 3625.50,
                'vat' => 543.83,
                'nhil' => 135.96,
                'getfund' => 135.96,
                'total' => 4441.25,
                'balance' => 4441.25,
                'payment_status' => 'Due',
                'due_date' => '2025-11-05',
            ],
            [
                'number' => 'SINV-2025-138',
                'customer' => 'BlueWave Marine Supplies',
                'subtotal' => 2800.00,
                'vat' => 420.00,
                'nhil' => 105.00,
                'getfund' => 105.00,
                'total' => 3430.00,
                'balance' => 3430.00,
                'payment_status' => 'Overdue',
                'due_date' => '2025-10-12',
            ],
        ]);
    @endphp

    <div class="container-fluid">
        <div class="ar-hero mb-4">
            <div class="ar-hero__surface">
                <div class="ar-hero__intro">
                    <span class="ar-hero__eyebrow"><i class="ri-hand-coin-line"></i> Receivables</span>
                    <h2 class="ar-hero__title">Customer Revenue Control Hub</h2>
                    <p class="ar-hero__subtitle">Monitor every outstanding invoice, nurture healthy cash flow, and orchestrate timely collections from a workspace crafted for modern finance teams.</p>
                    <span class="ar-header-chip"><i class="ri-user-smile-line"></i> Customers</span>
                </div>
                <div class="ar-hero__actions">
                    <button type="button" class="ar-action-btn ar-action-btn--outline" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                        <i class="ri-user-add-line"></i>
                        <span>Add Customer</span>
                    </button>
                    <button type="button" class="ar-action-btn ar-action-btn--accent" data-bs-toggle="modal" data-bs-target="#addSalesInvoiceModal">
                        <i class="ri-file-add-line"></i>
                        <span>Add Sales Invoice</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xxl-6">
                <div class="ar-card h-100">
                    <div class="ar-card__surface">
                        <div class="ar-card__header">
                            <div>
                                <span class="ar-card__eyebrow">Customer Directory</span>
                                <h5 class="ar-card__title">Active Customers</h5>
                                <p class="ar-card__description">Review client profiles, balances, and initiate quick follow-ups with ease.</p>
                            </div>
                            <div class="ar-card__toolbar">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCustomerModal">Add Customer</button>
                            </div>
                        </div>

                        <div class="ar-table-wrapper">
                            <div class="table-responsive">
                                <table class="table ar-table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>TIN</th>
                                            <th>Phone</th>
                                            <th>Balance</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($customers as $customer)
                                            <tr>
                                                <td>
                                                    <div class="ar-table-cell-title">{{ $customer['name'] }}</div>
                                                    <div class="ar-table-cell-subtext">{{ $customer['email'] }}</div>
                                                </td>
                                                <td>
                                                    <div class="ar-table-cell-title">{{ $customer['tin'] }}</div>
                                                    <div class="ar-table-cell-subtext">{{ $customer['city'] }}</div>
                                                </td>
                                                <td>
                                                    <div class="ar-table-cell-title">{{ $customer['phone'] }}</div>
                                                    <div class="ar-table-cell-subtext">Customer since {{ $customer['since'] }}</div>
                                                </td>
                                                <td>
                                                    <span class="ar-balance {{ $customer['balance'] > 0 ? 'ar-balance--due' : 'ar-balance--clear' }}">
                                                        GHS {{ number_format($customer['balance'], 2) }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <div class="btn-group" role="group" aria-label="Customer actions">
                                                        <button type="button" class="ar-icon-btn" title="View Customer" data-bs-toggle="modal" data-bs-target="#viewCustomerModal" data-customer='@json($customer)'>
                                                            <i class="ri-eye-line"></i>
                                                            <span class="visually-hidden">View customer</span>
                                                        </button>
                                                        <button type="button" class="ar-icon-btn" title="Edit Customer" data-bs-toggle="modal" data-bs-target="#editCustomerModal" data-customer='@json($customer)'>
                                                            <i class="ri-edit-line"></i>
                                                            <span class="visually-hidden">Edit customer</span>
                                                        </button>
                                                        <button type="button" class="ar-icon-btn" title="Delete Customer" data-bs-toggle="modal" data-bs-target="#deleteCustomerModal" data-customer='@json($customer)'>
                                                            <i class="ri-delete-bin-6-line"></i>
                                                            <span class="visually-hidden">Delete customer</span>
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
                <div class="ar-card h-100">
                    <div class="ar-card__surface">
                        <div class="ar-card__header">
                            <div>
                                <span class="ar-card__eyebrow">Sales Invoices</span>
                                <h5 class="ar-card__title">Outstanding Receivables</h5>
                                <p class="ar-card__description">Spot VAT exposure, monitor totals, and capture payments without leaving this dashboard.</p>
                            </div>
                            <div class="ar-card__toolbar">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSalesInvoiceModal">Add Sales Invoice</button>
                            </div>
                        </div>

                        <div class="ar-table-wrapper">
                            <div class="table-responsive">
                                <table class="table ar-table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Invoice No</th>
                                            <th>Customer</th>
                                            <th>Subtotal</th>
                                            <th>VAT/NHIL/GETFund</th>
                                            <th>Total</th>
                                            <th>Payment Status</th>
                                            <th>Receive Payment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($salesInvoices as $invoice)
                                            <tr>
                                                <td>
                                                    <div class="ar-table-cell-title">{{ $invoice['number'] }}</div>
                                                    <div class="ar-table-cell-subtext">Due {{ \Carbon\Carbon::parse($invoice['due_date'])->format('d M, Y') }}</div>
                                                </td>
                                                <td>
                                                    <div class="ar-table-cell-title">{{ $invoice['customer'] }}</div>
                                                    <div class="ar-table-cell-subtext">Balance: GHS {{ number_format($invoice['balance'], 2) }}</div>
                                                </td>
                                                <td>GHS {{ number_format($invoice['subtotal'], 2) }}</td>
                                                <td>
                                                    <div class="ar-table-cell-title">GHS {{ number_format($invoice['vat'], 2) }}</div>
                                                    <div class="ar-table-cell-subtext">NHIL {{ number_format($invoice['nhil'], 2) }} · GETFund {{ number_format($invoice['getfund'], 2) }}</div>
                                                </td>
                                                <td>
                                                    <span class="ar-balance ar-balance--clear">GHS {{ number_format($invoice['total'], 2) }}</span>
                                                </td>
                                                <td>
                                                    @php
                                                        $statusClass = match (strtolower($invoice['payment_status'])) {
                                                            'paid' => 'ar-status-badge ar-status-badge--paid',
                                                            'partial' => 'ar-status-badge ar-status-badge--partial',
                                                            'overdue' => 'ar-status-badge ar-status-badge--overdue',
                                                            default => 'ar-status-badge ar-status-badge--due',
                                                        };
                                                    @endphp
                                                    <span class="{{ $statusClass }}">
                                                        <i class="ri-checkbox-circle-line"></i>{{ $invoice['payment_status'] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <button type="button"
                                                        class="ar-pill-btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#receivePaymentModal"
                                                        data-invoice='@json($invoice)'>
                                                        <i class="ri-wallet-3-line"></i>
                                                        <span>Receive</span>
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

    <!-- Add Customer Modal -->
    <div class="modal fade ar-modal" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="ar-modal__surface">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCustomerModalLabel">Add Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="ar-form">
                            <div class="ar-form-section">
                                <div class="ar-form-section__heading">
                                    <span class="ar-form-section__eyebrow"><i class="ri-id-card-line"></i> Identity</span>
                                    <h6 class="ar-form-section__title">Company Snapshot</h6>
                                    <p class="ar-form-section__subtitle">Capture the essentials for billing and compliance.</p>
                                </div>
                                <div class="ar-form-grid">
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="customerName">Customer Name</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-building-line"></i></span>
                                            <input type="text" class="form-control form-control-modern ar-input__control" id="customerName" placeholder="Enter customer name">
                                        </div>
                                    </div>
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="customerTin">Tax Identification Number</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-scales-2-line"></i></span>
                                            <input type="text" class="form-control form-control-modern ar-input__control" id="customerTin" placeholder="Enter TIN">
                                        </div>
                                    </div>
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="customerCity">City / Region</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-map-pin-line"></i></span>
                                            <input type="text" class="form-control form-control-modern ar-input__control" id="customerCity" placeholder="e.g. Accra, Ghana">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ar-form-section">
                                <div class="ar-form-section__heading">
                                    <span class="ar-form-section__eyebrow"><i class="ri-contacts-book-2-line"></i> Contact & Credit</span>
                                    <h6 class="ar-form-section__title">Engagement Details</h6>
                                    <p class="ar-form-section__subtitle">Keep contact and credit parameters aligned with your policies.</p>
                                </div>
                                <div class="ar-form-grid">
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="customerPhone">Phone Number</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-phone-line"></i></span>
                                            <input type="tel" class="form-control form-control-modern ar-input__control" id="customerPhone" placeholder="Enter phone number">
                                        </div>
                                    </div>
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="customerEmail">Email Address</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-mail-line"></i></span>
                                            <input type="email" class="form-control form-control-modern ar-input__control" id="customerEmail" placeholder="Enter email address">
                                        </div>
                                    </div>
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="customerBalance">Opening Balance (GHS)</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-currency-line"></i></span>
                                            <input type="number" class="form-control form-control-modern ar-input__control" id="customerBalance" placeholder="0.00" step="0.01">
                                        </div>
                                    </div>
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="customerCreditLimit">Credit Limit (GHS)</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-safe-line"></i></span>
                                            <input type="number" class="form-control form-control-modern ar-input__control" id="customerCreditLimit" placeholder="0.00" step="0.01">
                                        </div>
                                    </div>
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="customerCycle">Billing Cycle</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-calendar-line"></i></span>
                                            <select id="customerCycle" class="form-select form-select-modern ar-input__control">
                                                <option value="30">Net 30</option>
                                                <option value="45">Net 45</option>
                                                <option value="60">Net 60</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ar-form-section">
                                <div class="ar-form-section__heading">
                                    <span class="ar-form-section__eyebrow"><i class="ri-route-line"></i> Address</span>
                                    <h6 class="ar-form-section__title">Billing Destination</h6>
                                    <p class="ar-form-section__subtitle">Provide the invoicing address and delivery instructions.</p>
                                </div>
                                <div class="ar-form-field">
                                    <label class="ar-field-label" for="customerAddress">Billing Address</label>
                                    <div class="ar-input ar-input--textarea">
                                        <span class="ar-input__icon"><i class="ri-compass-3-line"></i></span>
                                        <textarea class="form-control form-textarea-modern ar-input__control" id="customerAddress" placeholder="Enter billing address"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" data-action="save-customer" data-modal="#addCustomerModal">Save Customer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Customer Modal -->
    <div class="modal fade ar-modal" id="viewCustomerModal" tabindex="-1" aria-labelledby="viewCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="ar-modal__surface">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewCustomerModalLabel">Customer Overview</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="mb-1" data-customer-field="name">Customer Name</h5>
                                <div class="text-muted" data-customer-field="email">Email</div>
                            </div>
                            <span class="ar-status-badge ar-status-badge--active" data-customer-field="status-badge">Status</span>
                        </div>
                        <div class="ar-summary-tiles">
                            <div class="ar-summary-tile">
                                <span class="label">Outstanding Balance</span>
                                <span class="value" data-customer-field="balance">GHS 0.00</span>
                            </div>
                            <div class="ar-summary-tile">
                                <span class="label">Credit Limit</span>
                                <span class="value" data-customer-field="credit_limit">GHS 0.00</span>
                            </div>
                            <div class="ar-summary-tile">
                                <span class="label">TIN</span>
                                <span class="value" data-customer-field="tin">TIN</span>
                            </div>
                            <div class="ar-summary-tile">
                                <span class="label">Customer Since</span>
                                <span class="value" data-customer-field="since">—</span>
                            </div>
                        </div>
                        <dl class="row g-3 mt-3">
                            <div class="col-md-6">
                                <dt class="text-uppercase text-muted fs-12 fw-semibold mb-2">Primary Contact</dt>
                                <dd class="mb-0" data-customer-field="phone">Phone</dd>
                            </div>
                            <div class="col-md-6">
                                <dt class="text-uppercase text-muted fs-12 fw-semibold mb-2">City / Region</dt>
                                <dd class="mb-0" data-customer-field="city">City</dd>
                            </div>
                            <div class="col-12">
                                <dt class="text-uppercase text-muted fs-12 fw-semibold mb-2">Email Address</dt>
                                <dd class="mb-0" data-customer-field="email-detail">Email</dd>
                            </div>
                            <div class="col-12">
                                <dt class="text-uppercase text-muted fs-12 fw-semibold mb-2">Billing Address</dt>
                                <dd class="mb-0" data-customer-field="address">Address</dd>
                            </div>
                        </dl>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editCustomerModal" data-forward="edit">Edit Customer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Customer Modal -->
    <div class="modal fade ar-modal" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="ar-modal__surface">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCustomerModalLabel">Edit Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="ar-form">
                            <div class="ar-form-section">
                                <div class="ar-form-section__heading">
                                    <span class="ar-form-section__eyebrow"><i class="ri-id-card-line"></i> Identity</span>
                                    <h6 class="ar-form-section__title">Customer Profile</h6>
                                    <p class="ar-form-section__subtitle">Refresh customer records before issuing invoices.</p>
                                </div>
                                <div class="ar-form-grid">
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="editCustomerName">Customer Name</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-building-line"></i></span>
                                            <input type="text" class="form-control form-control-modern ar-input__control" id="editCustomerName" placeholder="Update customer name">
                                        </div>
                                    </div>
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="editCustomerTin">Tax Identification Number</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-scales-2-line"></i></span>
                                            <input type="text" class="form-control form-control-modern ar-input__control" id="editCustomerTin" placeholder="Update TIN">
                                        </div>
                                    </div>
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="editCustomerCity">City / Region</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-map-pin-line"></i></span>
                                            <input type="text" class="form-control form-control-modern ar-input__control" id="editCustomerCity" placeholder="Update city or region">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ar-form-section">
                                <div class="ar-form-section__heading">
                                    <span class="ar-form-section__eyebrow"><i class="ri-contacts-book-2-line"></i> Contact & Credit</span>
                                    <h6 class="ar-form-section__title">Operational Details</h6>
                                    <p class="ar-form-section__subtitle">Fine-tune contact, credit, and lifecycle information.</p>
                                </div>
                                <div class="ar-form-grid">
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="editCustomerPhone">Phone Number</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-phone-line"></i></span>
                                            <input type="tel" class="form-control form-control-modern ar-input__control" id="editCustomerPhone" placeholder="Update phone number">
                                        </div>
                                    </div>
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="editCustomerEmail">Email Address</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-mail-line"></i></span>
                                            <input type="email" class="form-control form-control-modern ar-input__control" id="editCustomerEmail" placeholder="Update email address">
                                        </div>
                                    </div>
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="editCustomerSince">Customer Since</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-hourglass-line"></i></span>
                                            <input type="number" min="2000" max="2099" class="form-control form-control-modern ar-input__control" id="editCustomerSince" placeholder="YYYY">
                                        </div>
                                    </div>
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="editCustomerStatus">Status</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-pulse-line"></i></span>
                                            <select id="editCustomerStatus" class="form-select form-select-modern ar-input__control">
                                                <option value="Active">Active</option>
                                                <option value="Strategic">Strategic</option>
                                                <option value="Watchlist">Watchlist</option>
                                                <option value="On Hold">On Hold</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="editCustomerBalance">Outstanding Balance (GHS)</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-currency-line"></i></span>
                                            <input type="number" class="form-control form-control-modern ar-input__control" id="editCustomerBalance" placeholder="0.00" step="0.01">
                                        </div>
                                    </div>
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="editCustomerLimit">Credit Limit (GHS)</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-safe-line"></i></span>
                                            <input type="number" class="form-control form-control-modern ar-input__control" id="editCustomerLimit" placeholder="0.00" step="0.01">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ar-form-section">
                                <div class="ar-form-section__heading">
                                    <span class="ar-form-section__eyebrow"><i class="ri-route-line"></i> Address</span>
                                    <h6 class="ar-form-section__title">Billing Destination</h6>
                                    <p class="ar-form-section__subtitle">Keep your shipping and invoicing address fresh.</p>
                                </div>
                                <div class="ar-form-field">
                                    <label class="ar-field-label" for="editCustomerAddress">Billing Address</label>
                                    <div class="ar-input ar-input--textarea">
                                        <span class="ar-input__icon"><i class="ri-compass-3-line"></i></span>
                                        <textarea class="form-control form-textarea-modern ar-input__control" id="editCustomerAddress" placeholder="Update billing address"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" data-action="update-customer" data-modal="#editCustomerModal">Update Customer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Customer Modal -->
    <div class="modal fade ar-modal" id="deleteCustomerModal" tabindex="-1" aria-labelledby="deleteCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="ar-modal__surface">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteCustomerModalLabel">Delete Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-3">You are about to remove <strong data-customer-field="delete-name">Customer Name</strong> from your receivables directory.</p>
                        <p class="text-muted mb-0">This action archives historical invoices but preserves financial records for audit. Are you sure you want to continue?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" data-action="delete-customer" data-modal="#deleteCustomerModal">Delete Customer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Sales Invoice Modal -->
    <div class="modal fade ar-modal" id="addSalesInvoiceModal" tabindex="-1" aria-labelledby="addSalesInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="ar-modal__surface">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSalesInvoiceModalLabel">Add Sales Invoice</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="ar-form">
                            <div class="ar-form-section">
                                <div class="ar-form-section__heading">
                                    <span class="ar-form-section__eyebrow"><i class="ri-file-list-3-line"></i> Invoice Basics</span>
                                    <h6 class="ar-form-section__title">Document Details</h6>
                                    <p class="ar-form-section__subtitle">Outline who you are invoicing and when payment is due.</p>
                                </div>
                                <div class="ar-form-grid">
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="salesInvoiceNumber">Invoice Number</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-hashtag"></i></span>
                                            <input type="text" class="form-control form-control-modern ar-input__control" id="salesInvoiceNumber" placeholder="Enter invoice number">
                                        </div>
                                    </div>
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="salesInvoiceCustomer">Customer</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-user-star-line"></i></span>
                                            <select id="salesInvoiceCustomer" class="form-select form-select-modern ar-input__control">
                                                <option value="">Select Customer</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer['name'] }}">{{ $customer['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="salesInvoiceDate">Invoice Date</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-calendar-event-line"></i></span>
                                            <input type="date" class="form-control form-control-modern ar-input__control" id="salesInvoiceDate" placeholder="Select invoice date">
                                        </div>
                                    </div>
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="salesInvoiceDueDate">Due Date</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-time-line"></i></span>
                                            <input type="date" class="form-control form-control-modern ar-input__control" id="salesInvoiceDueDate" placeholder="Select due date">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ar-form-section">
                                <div class="ar-form-section__heading">
                                    <span class="ar-form-section__eyebrow"><i class="ri-percent-line"></i> Tax Breakdown</span>
                                    <h6 class="ar-form-section__title">Revenue Summary</h6>
                                    <p class="ar-form-section__subtitle">Establish levy breakdowns before sending to customers.</p>
                                </div>
                                <div class="ar-form-grid">
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="salesInvoiceSubtotal">Subtotal (GHS)</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-currency-line"></i></span>
                                            <input type="number" class="form-control form-control-modern ar-input__control" id="salesInvoiceSubtotal" placeholder="0.00" step="0.01">
                                        </div>
                                    </div>
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="salesInvoiceVat">VAT (GHS)</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-mac-line"></i></span>
                                            <input type="number" class="form-control form-control-modern ar-input__control" id="salesInvoiceVat" placeholder="0.00" step="0.01">
                                        </div>
                                    </div>
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="salesInvoiceNhil">NHIL (GHS)</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-government-line"></i></span>
                                            <input type="number" class="form-control form-control-modern ar-input__control" id="salesInvoiceNhil" placeholder="0.00" step="0.01">
                                        </div>
                                    </div>
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="salesInvoiceGetfund">GETFund (GHS)</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-graduation-cap-line"></i></span>
                                            <input type="number" class="form-control form-control-modern ar-input__control" id="salesInvoiceGetfund" placeholder="0.00" step="0.01">
                                        </div>
                                    </div>
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="salesInvoiceTotal">Total (GHS)</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-calculator-line"></i></span>
                                            <input type="number" class="form-control form-control-modern ar-input__control" id="salesInvoiceTotal" placeholder="0.00" step="0.01">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ar-form-section">
                                <div class="ar-form-section__heading">
                                    <span class="ar-form-section__eyebrow"><i class="ri-sticky-note-line"></i> Notes</span>
                                    <h6 class="ar-form-section__title">Internal Commentary</h6>
                                    <p class="ar-form-section__subtitle">Leave internal nudges for the receivables team.</p>
                                </div>
                                <div class="ar-form-field">
                                    <label class="ar-field-label" for="salesInvoiceNotes">Internal Notes</label>
                                    <div class="ar-input ar-input--textarea">
                                        <span class="ar-input__icon"><i class="ri-ball-pen-line"></i></span>
                                        <textarea class="form-control form-textarea-modern ar-input__control" id="salesInvoiceNotes" placeholder="Add any internal notes"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" data-action="save-invoice" data-modal="#addSalesInvoiceModal">Save Invoice</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Receive Payment Modal -->
    <div class="modal fade ar-modal" id="receivePaymentModal" tabindex="-1" aria-labelledby="receivePaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="ar-modal__surface">
                    <div class="modal-header">
                        <h5 class="modal-title" id="receivePaymentModalLabel">Receive Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="ar-summary-tiles">
                            <div class="ar-summary-tile">
                                <span class="label">Invoice Number</span>
                                <span class="value" data-invoice-field="number">SINV-0000</span>
                            </div>
                            <div class="ar-summary-tile">
                                <span class="label">Customer</span>
                                <span class="value" data-invoice-field="customer">Customer</span>
                            </div>
                            <div class="ar-summary-tile">
                                <span class="label">Balance Due</span>
                                <span class="value" data-invoice-field="balance">GHS 0.00</span>
                            </div>
                            <div class="ar-summary-tile">
                                <span class="label">Due Date</span>
                                <span class="value" data-invoice-field="due-date">--</span>
                            </div>
                        </div>

                        <form>
                            <div class="mb-3">
                                <label class="form-label text-uppercase fw-semibold text-muted">Payment Method</label>
                                <div class="ar-method-pills">
                                    <input class="btn-check" type="radio" name="payment_method" id="paymentMethodMoMo" value="MoMo" checked>
                                    <label class="ar-method-pill" for="paymentMethodMoMo"><i class="ri-smartphone-line me-1"></i> MoMo</label>

                                    <input class="btn-check" type="radio" name="payment_method" id="paymentMethodBank" value="Bank">
                                    <label class="ar-method-pill" for="paymentMethodBank"><i class="ri-bank-card-line me-1"></i> Bank</label>

                                    <input class="btn-check" type="radio" name="payment_method" id="paymentMethodCash" value="Cash">
                                    <label class="ar-method-pill" for="paymentMethodCash"><i class="ri-money-dollar-circle-line me-1"></i> Cash</label>
                                </div>
                            </div>

                            <div class="ar-form-section">
                                <div class="ar-form-section__heading">
                                    <span class="ar-form-section__eyebrow"><i class="ri-wallet-3-line"></i> Transaction</span>
                                    <h6 class="ar-form-section__title">Payment Details</h6>
                                    <p class="ar-form-section__subtitle">Define the amount received and any charges applied.</p>
                                </div>
                                <div class="ar-form-grid">
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="paymentAmount">Amount (GHS)</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-cash-line"></i></span>
                                            <input type="number" class="form-control form-control-modern ar-input__control" id="paymentAmount" placeholder="0.00" step="0.01">
                                        </div>
                                    </div>
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="paymentCharges">MoMo Charges (automatic)</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-smartphone-line"></i></span>
                                            <input type="number" class="form-control form-control-modern ar-input__control" id="paymentCharges" placeholder="0.00" step="0.01" readonly>
                                        </div>
                                    </div>
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="paymentDate">Payment Date</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-calendar-check-line"></i></span>
                                            <input type="date" class="form-control form-control-modern ar-input__control" id="paymentDate" placeholder="Select payment date">
                                        </div>
                                    </div>
                                    <div class="ar-form-field">
                                        <label class="ar-field-label" for="paymentReference">Reference</label>
                                        <div class="ar-input">
                                            <span class="ar-input__icon"><i class="ri-file-paper-2-line"></i></span>
                                            <input type="text" class="form-control form-control-modern ar-input__control" id="paymentReference" placeholder="Enter payment reference">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ar-form-section">
                                <div class="ar-form-section__heading">
                                    <span class="ar-form-section__eyebrow"><i class="ri-sticky-note-line"></i> Notes</span>
                                    <h6 class="ar-form-section__title">Internal Notes</h6>
                                    <p class="ar-form-section__subtitle">Summarise payment context for reconciliation.</p>
                                </div>
                                <div class="ar-form-field">
                                    <label class="ar-field-label" for="paymentNotes">Notes</label>
                                    <div class="ar-input ar-input--textarea">
                                        <span class="ar-input__icon"><i class="ri-ball-pen-line"></i></span>
                                        <textarea class="form-control form-textarea-modern ar-input__control" id="paymentNotes" placeholder="Add internal notes"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" data-action="confirm-payment" data-modal="#receivePaymentModal">Confirm Receipt</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const swalAvailable = typeof Swal !== 'undefined';
            const receivePaymentModal = document.getElementById('receivePaymentModal');
            const paymentAmountInput = document.getElementById('paymentAmount');
            const paymentChargesInput = document.getElementById('paymentCharges');
            const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
            const momoRate = 0.0175; // 1.75% MoMo transaction fee

            const currencyFormatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'GHS',
                minimumFractionDigits: 2
            });

            const customerStatusClassMap = {
                'active': 'ar-status-badge ar-status-badge--active',
                'strategic': 'ar-status-badge ar-status-badge--strategic',
                'watchlist': 'ar-status-badge ar-status-badge--watchlist',
                'on hold': 'ar-status-badge ar-status-badge--hold',
                'default': 'ar-status-badge ar-status-badge--due'
            };

            const computeCharges = () => {
                const selectedMethod = document.querySelector('input[name="payment_method"]:checked')?.value || 'MoMo';
                const amount = Number(paymentAmountInput.value || 0);
                const charges = selectedMethod === 'MoMo' ? amount * momoRate : 0;
                paymentChargesInput.value = charges ? charges.toFixed(2) : '0.00';
                paymentChargesInput.toggleAttribute('readonly', selectedMethod === 'MoMo');
                if (selectedMethod !== 'MoMo' && !paymentChargesInput.hasAttribute('readonly')) {
                    paymentChargesInput.focus();
                }
            };

            paymentAmountInput?.addEventListener('input', computeCharges);
            paymentMethodRadios.forEach(radio => radio.addEventListener('change', () => {
                if (radio.value !== 'MoMo') {
                    paymentChargesInput.value = '0.00';
                }
                computeCharges();
            }));

            if (receivePaymentModal) {
                receivePaymentModal.addEventListener('show.bs.modal', event => {
                    const trigger = event.relatedTarget;
                    if (!trigger) return;
                    const invoice = JSON.parse(trigger.getAttribute('data-invoice') || '{}');

                    receivePaymentModal.querySelector('[data-invoice-field="number"]').textContent = invoice.number || '—';
                    receivePaymentModal.querySelector('[data-invoice-field="customer"]').textContent = invoice.customer || '—';
                    receivePaymentModal.querySelector('[data-invoice-field="balance"]').textContent = currencyFormatter.format(Number(invoice.balance || 0));
                    const dueDateField = receivePaymentModal.querySelector('[data-invoice-field="due-date"]');
                    if (invoice.due_date) {
                        const formattedDate = window.moment ? window.moment(invoice.due_date).format('DD MMM, YYYY') : new Date(invoice.due_date).toLocaleDateString('en-GB', {
                            day: '2-digit', month: 'short', year: 'numeric'
                        });
                        dueDateField.textContent = formattedDate;
                    } else {
                        dueDateField.textContent = '—';
                    }

                    paymentAmountInput.value = Number(invoice.balance || 0).toFixed(2);
                    document.getElementById('paymentMethodMoMo').checked = true;
                    paymentChargesInput.readOnly = true;
                    computeCharges();
                });

                receivePaymentModal.addEventListener('hidden.bs.modal', () => {
                    paymentAmountInput.value = '';
                    paymentChargesInput.value = '0.00';
                    document.getElementById('paymentMethodMoMo').checked = true;
                });
            }

            const viewCustomerModal = document.getElementById('viewCustomerModal');
            const editCustomerModal = document.getElementById('editCustomerModal');
            const deleteCustomerModal = document.getElementById('deleteCustomerModal');
            const addCustomerModal = document.getElementById('addCustomerModal');
            const addInvoiceModal = document.getElementById('addSalesInvoiceModal');

            const handleActionClick = (selector, successConfig) => {
                document.querySelectorAll(selector).forEach(button => {
                    button.addEventListener('click', () => {
                        const modalSelector = button.getAttribute('data-modal');
                        const modalElement = modalSelector ? document.querySelector(modalSelector) : null;
                        if (modalElement) {
                            const modalInstance = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                            modalInstance.hide();
                            modalElement.addEventListener('hidden.bs.modal', () => {
                                if (swalAvailable) {
                                    Swal.fire({
                                        icon: successConfig.icon,
                                        title: successConfig.title,
                                        text: successConfig.text,
                                        confirmButtonColor: '#1d4ed8',
                                        backdrop: 'rgba(11, 31, 63, 0.55)'
                                    });
                                }
                            }, { once: true });
                        } else if (swalAvailable) {
                            Swal.fire({
                                icon: successConfig.icon,
                                title: successConfig.title,
                                text: successConfig.text,
                                confirmButtonColor: '#1d4ed8',
                                backdrop: 'rgba(11, 31, 63, 0.55)'
                            });
                        }
                    });
                });
            };

            handleActionClick('[data-action="save-customer"]', {
                icon: 'success',
                title: 'Customer Saved',
                text: 'Your customer profile has been filed successfully.'
            });

            handleActionClick('[data-action="update-customer"]', {
                icon: 'success',
                title: 'Customer Updated',
                text: 'Customer details were refreshed without a hitch.'
            });

            document.querySelectorAll('[data-action="delete-customer"]').forEach(button => {
                button.addEventListener('click', () => {
                    if (!swalAvailable) {
                        const modalSelector = button.getAttribute('data-modal');
                        const modalElement = modalSelector ? document.querySelector(modalSelector) : null;
                        modalElement && bootstrap.Modal.getInstance(modalElement)?.hide();
                        return;
                    }

                    Swal.fire({
                        title: 'Archive Customer?',
                        text: 'This customer will be archived but invoices remain for audit. Continue?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, archive',
                        cancelButtonText: 'No, keep',
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#1d4ed8',
                        backdrop: 'rgba(11, 31, 63, 0.55)'
                    }).then(result => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Customer Archived',
                                text: 'Records retained and the customer has been archived.',
                                confirmButtonColor: '#1d4ed8',
                                backdrop: 'rgba(11, 31, 63, 0.55)'
                            });
                            const modalSelector = button.getAttribute('data-modal');
                            const modalElement = modalSelector ? document.querySelector(modalSelector) : null;
                            modalElement && bootstrap.Modal.getInstance(modalElement)?.hide();
                        }
                    });
                });
            });

            handleActionClick('[data-action="save-invoice"]', {
                icon: 'success',
                title: 'Invoice Saved',
                text: 'Sales invoice locked in and ready to share.'
            });

            handleActionClick('[data-action="confirm-payment"]', {
                icon: 'success',
                title: 'Payment Received',
                text: 'Payment recorded. Receivables are up to date.'
            });

            if (viewCustomerModal) {
                viewCustomerModal.addEventListener('show.bs.modal', event => {
                    const trigger = event.relatedTarget;
                    if (!trigger) return;
                    const customer = JSON.parse(trigger.getAttribute('data-customer') || '{}');

                    viewCustomerModal.querySelector('[data-customer-field="name"]').textContent = customer.name || '—';
                    viewCustomerModal.querySelector('[data-customer-field="email"]').textContent = customer.email || '—';
                    viewCustomerModal.querySelector('[data-customer-field="email-detail"]').textContent = customer.email || '—';
                    viewCustomerModal.querySelector('[data-customer-field="phone"]').textContent = customer.phone || '—';
                    viewCustomerModal.querySelector('[data-customer-field="city"]').textContent = customer.city || '—';
                    viewCustomerModal.querySelector('[data-customer-field="tin"]').textContent = customer.tin || '—';
                    viewCustomerModal.querySelector('[data-customer-field="since"]').textContent = customer.since ? `Since ${customer.since}` : '—';
                    viewCustomerModal.querySelector('[data-customer-field="balance"]').textContent = currencyFormatter.format(Number(customer.balance || 0));
                    viewCustomerModal.querySelector('[data-customer-field="credit_limit"]').textContent = currencyFormatter.format(Number(customer.credit_limit || 0));

                    const statusBadge = viewCustomerModal.querySelector('[data-customer-field="status-badge"]');
                    const normalizedStatus = (customer.status || '').toLowerCase();
                    statusBadge.textContent = customer.status || 'Active';
                    statusBadge.className = customerStatusClassMap[normalizedStatus] || customerStatusClassMap.default;

                    const editButton = viewCustomerModal.querySelector('[data-forward="edit"]');
                    if (editButton) {
                        editButton.setAttribute('data-customer', JSON.stringify(customer));
                    }
                });
            }

            if (editCustomerModal) {
                editCustomerModal.addEventListener('show.bs.modal', event => {
                    const trigger = event.relatedTarget;
                    const forwardedData = trigger?.getAttribute('data-customer') || trigger?.getAttribute('data-forward');
                    if (!forwardedData) return;
                    const customer = JSON.parse(trigger.getAttribute('data-customer') || viewCustomerModal?.querySelector('[data-forward="edit"]')?.getAttribute('data-customer') || '{}');

                    editCustomerModal.querySelector('#editCustomerName').value = customer.name || '';
                    editCustomerModal.querySelector('#editCustomerTin').value = customer.tin || '';
                    editCustomerModal.querySelector('#editCustomerPhone').value = customer.phone || '';
                    editCustomerModal.querySelector('#editCustomerEmail').value = customer.email || '';
                    editCustomerModal.querySelector('#editCustomerCity').value = customer.city || '';
                    editCustomerModal.querySelector('#editCustomerSince').value = customer.since || '';
                    editCustomerModal.querySelector('#editCustomerBalance').value = customer.balance !== undefined ? Number(customer.balance).toFixed(2) : '';
                    editCustomerModal.querySelector('#editCustomerLimit').value = customer.credit_limit !== undefined ? Number(customer.credit_limit).toFixed(2) : '';

                    const statusSelect = editCustomerModal.querySelector('#editCustomerStatus');
                    if (statusSelect) {
                        const options = Array.from(statusSelect.options).map(opt => opt.value.toLowerCase());
                        const normalizedStatus = (customer.status || '').toLowerCase();
                        statusSelect.value = options.includes(normalizedStatus) ? customer.status : 'Active';
                    }
                });
            }

            if (deleteCustomerModal) {
                deleteCustomerModal.addEventListener('show.bs.modal', event => {
                    const trigger = event.relatedTarget;
                    if (!trigger) return;
                    const customer = JSON.parse(trigger.getAttribute('data-customer') || '{}');
                    const nameField = deleteCustomerModal.querySelector('[data-customer-field="delete-name"]');
                    if (nameField) {
                        nameField.textContent = customer.name || 'this customer';
                    }
                });
            }
        });
    </script>
@endsection
