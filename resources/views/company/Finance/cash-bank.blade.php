@extends('layouts.vertical', ['page_title' => 'Cash & Bank'])

@section('css')
    <style>
        :root {
            --cb-deep-navy: #061739;
            --cb-azure: #2563eb;
            --cb-cyan: #22d3ee;
            --cb-emerald: #0ea5e9;
            --cb-lime: #22c55e;
            --cb-surface: #f5f8ff;
            --cb-panel: #ffffff;
            --cb-muted: rgba(6, 23, 57, 0.64);
            --cb-border: rgba(6, 23, 57, 0.12);
            --cb-border-strong: rgba(6, 23, 57, 0.18);
            --cb-glow: 0 28px 52px rgba(6, 23, 57, 0.18);
            --cb-pill-bg: rgba(37, 99, 235, 0.12);
            --cb-pill-text: #1d4ed8;
            --cb-success: #16a34a;
            --cb-warning: #f59e0b;
            --cb-danger: #dc2626;
        }

        .cb-hero {
            background: linear-gradient(140deg, rgba(37, 99, 235, 0.85), rgba(14, 165, 233, 0.88));
            border-radius: 28px;
            padding: 0.1rem;
            box-shadow: var(--cb-glow);
        }

        .cb-hero__surface {
            background: var(--cb-surface);
            border-radius: 27px;
            padding: 2.6rem 3rem;
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            align-items: flex-start;
            justify-content: space-between;
        }

        .cb-hero__intro {
            max-width: 620px;
        }

        .cb-hero__eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--cb-pill-text);
            background: rgba(37, 99, 235, 0.16);
            border-radius: 999px;
            padding: 0.4rem 1rem;
        }

        .cb-hero__title {
            margin: 1.2rem 0 0.7rem;
            font-size: 2.05rem;
            font-weight: 700;
            color: var(--cb-deep-navy);
            letter-spacing: 0.02em;
        }

        .cb-hero__subtitle {
            color: var(--cb-muted);
            font-size: 0.96rem;
            line-height: 1.65;
            margin-bottom: 1.6rem;
        }

        .cb-hero__chips {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .cb-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            border-radius: 16px;
            padding: 0.5rem 1.15rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            font-size: 0.7rem;
            background: rgba(14, 165, 233, 0.12);
            color: rgba(6, 23, 57, 0.8);
        }

        .cb-hero__actions {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            align-items: flex-end;
        }

        .cb-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            border-radius: 999px;
            padding: 0.75rem 1.65rem;
            border: 1px solid transparent;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            font-size: 0.82rem;
            background: linear-gradient(95deg, rgba(14, 165, 233, 0.92), rgba(34, 197, 94, 0.92));
            color: #052e3f;
            box-shadow: 0 22px 36px rgba(14, 165, 233, 0.22);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .cb-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 26px 42px rgba(34, 197, 94, 0.24);
        }

        .cb-card {
            background: linear-gradient(135deg, rgba(6, 23, 57, 0.08), rgba(34, 197, 94, 0.08));
            border-radius: 24px;
            padding: 0.12rem;
            box-shadow: var(--cb-glow);
        }

        .cb-card__surface {
            background: var(--cb-panel);
            border-radius: 23px;
            padding: 2rem;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .cb-card__header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1.4rem;
            margin-bottom: 1.6rem;
        }

        .cb-card__eyebrow {
            text-transform: uppercase;
            letter-spacing: 0.18em;
            font-size: 0.7rem;
            font-weight: 600;
            color: rgba(6, 23, 57, 0.58);
        }

        .cb-card__title {
            margin: 0.4rem 0 0.45rem;
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--cb-deep-navy);
        }

        .cb-card__description {
            margin: 0;
            font-size: 0.86rem;
            color: var(--cb-muted);
        }

        .cb-card__toolbar {
            display: flex;
            gap: 0.6rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .cb-card__toolbar .btn {
            border-radius: 14px;
            padding: 0.45rem 1.1rem;
            font-size: 0.76rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .cb-table-wrapper {
            border-radius: 18px;
            border: 1px solid var(--cb-border);
            overflow: hidden;
        }

        .cb-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .cb-table thead th {
            background: rgba(37, 99, 235, 0.08);
            border-bottom: none;
            padding: 0.95rem 1.2rem;
            font-size: 0.72rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            font-weight: 600;
            color: rgba(6, 23, 57, 0.68);
        }

        .cb-table tbody tr {
            background: rgba(255, 255, 255, 0.95);
            transition: background 0.2s ease;
        }

        .cb-table tbody tr + tr td {
            border-top: 1px dashed var(--cb-border);
        }

        .cb-table tbody tr:hover {
            background: rgba(34, 197, 94, 0.08);
        }

        .cb-table tbody td {
            padding: 1rem 1.2rem;
            vertical-align: middle;
            color: var(--cb-deep-navy);
        }

        .cb-table-title {
            font-weight: 600;
        }

        .cb-table-subtext {
            margin-top: 0.15rem;
            font-size: 0.78rem;
            color: var(--cb-muted);
        }

        .cb-balance {
            font-weight: 700;
            font-size: 0.94rem;
        }

        .cb-balance--positive {
            color: var(--cb-success);
        }

        .cb-balance--warning {
            color: var(--cb-warning);
        }

        .cb-pill-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            border: none;
            border-radius: 999px;
            padding: 0.55rem 1.2rem;
            background: rgba(37, 99, 235, 0.12);
            color: var(--cb-pill-text);
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            transition: all 0.2s ease;
        }

        .cb-pill-btn:hover {
            background: rgba(37, 99, 235, 0.18);
        }

        .cb-pill-btn--accent {
            background: linear-gradient(95deg, rgba(14, 165, 233, 0.9), rgba(34, 197, 94, 0.9));
            color: #06302d;
            box-shadow: 0 14px 26px rgba(14, 165, 233, 0.18);
        }

        .cb-pill-btn--accent:hover {
            box-shadow: 0 18px 30px rgba(34, 197, 94, 0.18);
        }

        .cb-status {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            border-radius: 12px;
            padding: 0.25rem 0.8rem;
            font-size: 0.72rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 600;
        }

        .cb-status--ok {
            background: rgba(34, 197, 94, 0.16);
            color: #047857;
        }

        .cb-status--pending {
            background: rgba(245, 158, 11, 0.18);
            color: #92400e;
        }

        .cb-status--alert {
            background: rgba(220, 38, 38, 0.16);
            color: #7f1d1d;
        }

        .cb-modal .modal-dialog {
            max-width: 820px;
        }

        .cb-modal .modal-content {
            border-radius: 26px;
            border: none;
            padding: 1.6px;
            background: linear-gradient(140deg, rgba(37, 99, 235, 0.25), rgba(14, 165, 233, 0.16));
            box-shadow: 0 32px 58px rgba(6, 23, 57, 0.28);
        }

        .cb-modal__surface {
            background: var(--cb-panel);
            border-radius: 24px;
            overflow: hidden;
        }

        .cb-modal__surface .modal-header {
            padding: 1.6rem 1.8rem;
            border-bottom: 1px solid var(--cb-border);
            background: linear-gradient(125deg, rgba(37, 99, 235, 0.12), rgba(6, 23, 57, 0.06));
        }

        .cb-modal__surface .modal-body {
            padding: 1.75rem 1.9rem;
            background: linear-gradient(180deg, rgba(245, 248, 255, 0.98), rgba(237, 243, 255, 0.94));
        }

        .cb-modal__surface .modal-footer {
            padding: 1.3rem 1.8rem;
            border-top: 1px solid var(--cb-border);
            background: rgba(37, 99, 235, 0.08);
        }

        .cb-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 1rem;
            margin-bottom: 1.4rem;
        }

        .cb-summary__tile {
            background: rgba(37, 99, 235, 0.1);
            border-radius: 18px;
            padding: 1rem 1.1rem;
        }

        .cb-summary__label {
            font-size: 0.72rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(6, 23, 57, 0.55);
            font-weight: 600;
        }

        .cb-summary__value {
            margin-top: 0.45rem;
            font-size: 1.02rem;
            font-weight: 700;
            color: var(--cb-deep-navy);
        }

        .cb-form {
            display: flex;
            flex-direction: column;
            gap: 1.6rem;
        }

        .cb-form-section {
            border: 1px solid var(--cb-border);
            border-radius: 20px;
            padding: 1.4rem 1.6rem;
            background: rgba(37, 99, 235, 0.06);
        }

        .cb-form-section__heading {
            margin-bottom: 1rem;
        }

        .cb-form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem 1.2rem;
        }

        .cb-field {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .cb-field-label {
            font-size: 0.75rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            font-weight: 600;
            color: rgba(6, 23, 57, 0.68);
        }

        .cb-input {
            position: relative;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid var(--cb-border);
            border-radius: 1rem;
            padding: 0.6rem 0.95rem 0.6rem 2.8rem;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
        }

        .cb-input:focus-within {
            border-color: rgba(37, 99, 235, 0.4);
            box-shadow: 0 0 0 0.15rem rgba(37, 99, 235, 0.16);
        }

        .cb-input__icon {
            position: absolute;
            left: 1rem;
            font-size: 1.1rem;
            color: rgba(6, 23, 57, 0.5);
        }

        .cb-input__control {
            border: none;
            background: transparent;
            padding: 0;
            width: 100%;
            box-shadow: none !important;
        }

        .cb-input__control:focus {
            box-shadow: none;
        }

        .cb-input--textarea {
            align-items: flex-start;
            padding-top: 1rem;
        }

        .cb-input--textarea .cb-input__icon {
            top: 1rem;
        }

        .cb-input--textarea .cb-input__control {
            min-height: 120px;
            resize: vertical;
        }

        .cb-ledger-table thead th {
            background: rgba(6, 23, 57, 0.08);
        }

        .cb-ledger-table tbody tr:hover {
            background: rgba(14, 165, 233, 0.06);
        }

        .cb-list {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .cb-list__item {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 16px;
            background: rgba(37, 99, 235, 0.08);
        }

        .cb-list__title {
            font-weight: 600;
        }

        .cb-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.3rem 0.75rem;
            border-radius: 12px;
            font-size: 0.7rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            background: rgba(6, 23, 57, 0.12);
            color: rgba(6, 23, 57, 0.72);
        }

        .cb-radio-group {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .cb-radio-pill {
            border-radius: 16px;
            padding: 0.55rem 1.1rem;
            border: 1px solid rgba(6, 23, 57, 0.16);
            background: rgba(6, 23, 57, 0.04);
            font-size: 0.82rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 600;
            color: var(--cb-deep-navy);
            transition: all 0.2s ease;
        }

        .cb-radio-pill:hover {
            background: rgba(37, 99, 235, 0.12);
            border-color: rgba(37, 99, 235, 0.22);
        }

        .btn-check:checked + .cb-radio-pill {
            background: linear-gradient(95deg, rgba(14, 165, 233, 0.92), rgba(34, 197, 94, 0.9));
            border-color: transparent;
            color: #06302d;
            box-shadow: 0 16px 30px rgba(14, 165, 233, 0.18);
        }

        @media (max-width: 992px) {
            .cb-hero__surface {
                padding: 2.2rem 2.2rem;
            }

            .cb-hero__actions {
                flex-direction: row;
                align-items: center;
            }

            .cb-card__surface {
                padding: 1.7rem 1.8rem;
            }
        }

        @media (max-width: 768px) {
            .cb-hero__surface {
                padding: 1.9rem 1.7rem;
            }

            .cb-card__surface {
                padding: 1.55rem 1.5rem;
            }

            .cb-card__header {
                flex-direction: column;
                align-items: flex-start;
            }

            .cb-form-grid {
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            }
        }

        @media (max-width: 576px) {
            .cb-hero {
                border-radius: 24px;
            }

            .cb-hero__surface {
                padding: 1.6rem 1.4rem;
            }

            .cb-card {
                border-radius: 22px;
            }

            .cb-card__surface {
                padding: 1.35rem 1.3rem;
            }

            .cb-table-wrapper {
                border-radius: 16px;
            }

            .cb-form-section {
                padding: 1.2rem 1.1rem;
                border-radius: 18px;
            }

            .cb-form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    @php
        $bankAccounts = collect([
            [
                'bank' => 'CalBank Ltd',
                'account_no' => '001-298734-01',
                'balance' => 182450.55,
                'currency' => 'GHS',
                'branch' => 'Independence Ave',
                'last_reconciled' => '2025-10-30',
                'pending' => 2450.75,
            ],
            [
                'bank' => 'Stanbic Bank',
                'account_no' => '904-223198-10',
                'balance' => 98210.12,
                'currency' => 'GHS',
                'branch' => 'Airport City',
                'last_reconciled' => '2025-10-25',
                'pending' => 0,
            ],
            [
                'bank' => 'Fidelity Bank',
                'account_no' => '221-997654-88',
                'balance' => 45120.88,
                'currency' => 'USD',
                'branch' => 'Tema Harbour',
                'last_reconciled' => '2025-11-01',
                'pending' => 112.35,
            ],
        ]);

        $wallets = collect([
            [
                'provider' => 'MTN MoMo',
                'wallet_number' => '024 700 8899',
                'balance' => 17540.65,
                'charges_today' => 48.20,
                'charges_month' => 612.40,
                'owner' => 'Northern Fuel Depot',
            ],
            [
                'provider' => 'Vodafone Cash',
                'wallet_number' => '020 330 1900',
                'balance' => 9320.10,
                'charges_today' => 15.00,
                'charges_month' => 288.90,
                'owner' => 'Eastern Corridor Operations',
            ],
            [
                'provider' => 'AirtelTigo Money',
                'wallet_number' => '026 953 4481',
                'balance' => 4825.55,
                'charges_today' => 8.50,
                'charges_month' => 145.70,
                'owner' => 'Gulf Delivery Fleet',
            ],
        ]);

        $cashTransactions = collect([
            [
                'date' => '2025-11-12',
                'type' => 'Bank Transfer',
                'amount' => 2450.00,
                'charges' => 12.50,
                'description' => 'Replenish Kumasi station float',
            ],
            [
                'date' => '2025-11-11',
                'type' => 'MoMo Payout',
                'amount' => 880.00,
                'charges' => 8.80,
                'description' => 'Dealer commission payout for Navrongo hub',
            ],
            [
                'date' => '2025-11-10',
                'type' => 'Bank Deposit',
                'amount' => 1650.50,
                'charges' => 0.00,
                'description' => 'Cash deposit from Larabanga station sales',
            ],
            [
                'date' => '2025-11-09',
                'type' => 'Bank Charges',
                'amount' => 62.40,
                'charges' => 62.40,
                'description' => 'Monthly account maintenance fee - CalBank',
            ],
        ]);
    @endphp

    <div class="container-fluid">
        <div class="cb-hero mb-4">
            <div class="cb-hero__surface">
                <div class="cb-hero__intro">
                    <span class="cb-hero__eyebrow"><i class="ri-bank-line"></i> Treasury Control</span>
                    <h2 class="cb-hero__title">Cash &amp; Bank Operations Hub</h2>
                    <p class="cb-hero__subtitle">Monitor all liquidity channels, reconcile bank statements, and track real-time wallet balances from one professional cockpit designed for finance leaders.</p>
                    <div class="cb-hero__chips">
                        <span class="cb-chip"><i class="ri-building-2-line"></i> Bank Accounts</span>
                        <span class="cb-chip"><i class="ri-smartphone-line"></i> Mobile Money Wallets</span>
                    </div>
                </div>
                <div class="cb-hero__actions">
                    <button type="button" class="cb-action-btn" data-bs-toggle="modal" data-bs-target="#addAccountModal">
                        <i class="ri-add-line"></i>
                        <span>Add Account</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xxl-6">
                <div class="cb-card h-100">
                    <div class="cb-card__surface">
                        <div class="cb-card__header">
                            <div>
                                <span class="cb-card__eyebrow">Banking</span>
                                <h5 class="cb-card__title">Operational Bank Accounts</h5>
                                <p class="cb-card__description">Track balances, pending reconciliations, and drill into ledgers instantly.</p>
                            </div>
                            <div class="cb-card__toolbar">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAccountModal">Add Account</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm">Export</button>
                            </div>
                        </div>

                        <div class="cb-table-wrapper">
                            <div class="table-responsive">
                                <table class="table cb-table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Bank Name</th>
                                            <th>Account No</th>
                                            <th>Balance</th>
                                            <th>Reconcile</th>
                                            <th>View Ledger</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bankAccounts as $account)
                                            <tr>
                                                <td>
                                                    <div class="cb-table-title">{{ $account['bank'] }}</div>
                                                    <div class="cb-table-subtext">{{ $account['branch'] }}</div>
                                                </td>
                                                <td>
                                                    <div class="cb-table-title">{{ $account['account_no'] }}</div>
                                                    <div class="cb-table-subtext">Last reconciled {{ \Carbon\Carbon::parse($account['last_reconciled'])->format('d M, Y') }}</div>
                                                </td>
                                                <td>
                                                    <span class="cb-balance {{ $account['pending'] > 0 ? 'cb-balance--warning' : 'cb-balance--positive' }}">
                                                        {{ $account['currency'] }} {{ number_format($account['balance'], 2) }}
                                                    </span>
                                                    @if ($account['pending'] > 0)
                                                        <div class="cb-table-subtext">Pending: {{ $account['currency'] }} {{ number_format($account['pending'], 2) }}</div>
                                                    @else
                                                        <div class="cb-table-subtext">All reconciled</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button type="button"
                                                        class="cb-pill-btn cb-pill-btn--accent"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#reconcileAccountModal"
                                                        data-account='@json($account)'>
                                                        <i class="ri-refresh-line"></i>
                                                        <span>Reconcile</span>
                                                    </button>
                                                </td>
                                                <td>
                                                    <button type="button"
                                                        class="cb-pill-btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#viewLedgerModal"
                                                        data-account='@json($account)'>
                                                        <i class="ri-file-list-3-line"></i>
                                                        <span>Ledger</span>
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

            <div class="col-xxl-6">
                <div class="cb-card h-100">
                    <div class="cb-card__surface">
                        <div class="cb-card__header">
                            <div>
                                <span class="cb-card__eyebrow">Mobile Money</span>
                                <h5 class="cb-card__title">MoMo Wallets</h5>
                                <p class="cb-card__description">Financial oversight for digital wallets, usage fees, and owner context.</p>
                            </div>
                            <div class="cb-card__toolbar">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAccountModal">Link Wallet</button>
                            </div>
                        </div>

                        <div class="cb-table-wrapper">
                            <div class="table-responsive">
                                <table class="table cb-table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Provider</th>
                                            <th>Wallet Number</th>
                                            <th>Balance</th>
                                            <th>Charges Report</th>
                                            <th>View Transactions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($wallets as $wallet)
                                            <tr>
                                                <td>
                                                    <div class="cb-table-title">{{ $wallet['provider'] }}</div>
                                                    <div class="cb-table-subtext">Owner: {{ $wallet['owner'] }}</div>
                                                </td>
                                                <td>
                                                    <div class="cb-table-title">{{ $wallet['wallet_number'] }}</div>
                                                    <div class="cb-table-subtext">Today charges: GHS {{ number_format($wallet['charges_today'], 2) }}</div>
                                                </td>
                                                <td>
                                                    <span class="cb-balance cb-balance--positive">GHS {{ number_format($wallet['balance'], 2) }}</span>
                                                    <div class="cb-table-subtext">Monthly fees: GHS {{ number_format($wallet['charges_month'], 2) }}</div>
                                                </td>
                                                <td>
                                                    <button type="button"
                                                        class="cb-pill-btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#walletChargesModal"
                                                        data-wallet='@json($wallet)'>
                                                        <i class="ri-bar-chart-2-line"></i>
                                                        <span>Charges</span>
                                                    </button>
                                                </td>
                                                <td>
                                                    <button type="button"
                                                        class="cb-pill-btn cb-pill-btn--accent"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#walletTransactionsModal"
                                                        data-wallet='@json($wallet)'>
                                                        <i class="ri-history-line"></i>
                                                        <span>View</span>
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

        <div class="row g-4 mt-1">
            <div class="col-12">
                <div class="cb-card">
                    <div class="cb-card__surface">
                        <div class="cb-card__header">
                            <div>
                                <span class="cb-card__eyebrow">Cash Movement</span>
                                <h5 class="cb-card__title">Recent Cash Transactions</h5>
                                <p class="cb-card__description">Audit trail of manual cash and digital payouts across treasury pipelines.</p>
                            </div>
                            <div class="cb-card__toolbar">
                                <button type="button" class="btn btn-outline-primary btn-sm">Download</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm">Filter</button>
                            </div>
                        </div>

                        <div class="cb-table-wrapper">
                            <div class="table-responsive">
                                <table class="table cb-table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>Charges</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cashTransactions as $transaction)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($transaction['date'])->format('d M, Y') }}</td>
                                                <td>{{ $transaction['type'] }}</td>
                                                <td><span class="cb-balance cb-balance--positive">GHS {{ number_format($transaction['amount'], 2) }}</span></td>
                                                <td>GHS {{ number_format($transaction['charges'], 2) }}</td>
                                                <td>{{ $transaction['description'] }}</td>
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

    <!-- Add Account Modal -->
    <div class="modal fade cb-modal" id="addAccountModal" tabindex="-1" aria-labelledby="addAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="cb-modal__surface">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAccountModalLabel">Add Account or Wallet</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="cb-form" id="addAccountForm">
                            <div class="cb-form-section">
                                <div class="cb-form-section__heading">
                                    <span class="cb-field-label">Select Channel</span>
                                </div>
                                <div class="cb-radio-group">
                                    <input class="btn-check" type="radio" name="account_type" id="accountTypeBank" value="bank" checked>
                                    <label class="cb-radio-pill" for="accountTypeBank"><i class="ri-bank-line me-1"></i> Bank Account</label>

                                    <input class="btn-check" type="radio" name="account_type" id="accountTypeWallet" value="wallet">
                                    <label class="cb-radio-pill" for="accountTypeWallet"><i class="ri-smartphone-line me-1"></i> Mobile Money Wallet</label>
                                </div>
                            </div>

                            <div class="cb-form-section" data-account-section="bank">
                                <div class="cb-form-section__heading">
                                    <span class="cb-field-label">Bank Account Details</span>
                                </div>
                                <div class="cb-form-grid">
                                    <div class="cb-field">
                                        <label class="cb-field-label" for="bankName">Bank Name</label>
                                        <div class="cb-input">
                                            <span class="cb-input__icon"><i class="ri-building-4-line"></i></span>
                                            <input type="text" class="form-control cb-input__control" id="bankName" placeholder="Enter bank name">
                                        </div>
                                    </div>
                                    <div class="cb-field">
                                        <label class="cb-field-label" for="bankAccountNumber">Account Number</label>
                                        <div class="cb-input">
                                            <span class="cb-input__icon"><i class="ri-hashtag"></i></span>
                                            <input type="text" class="form-control cb-input__control" id="bankAccountNumber" placeholder="000-000000-00">
                                        </div>
                                    </div>
                                    <div class="cb-field">
                                        <label class="cb-field-label" for="bankCurrency">Currency</label>
                                        <div class="cb-input">
                                            <span class="cb-input__icon"><i class="ri-money-dollar-circle-line"></i></span>
                                            <select id="bankCurrency" class="form-select cb-input__control">
                                                <option value="GHS">GHS</option>
                                                <option value="USD">USD</option>
                                                <option value="EUR">EUR</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="cb-field">
                                        <label class="cb-field-label" for="bankBranch">Branch / Location</label>
                                        <div class="cb-input">
                                            <span class="cb-input__icon"><i class="ri-map-pin-line"></i></span>
                                            <input type="text" class="form-control cb-input__control" id="bankBranch" placeholder="Enter branch location">
                                        </div>
                                    </div>
                                    <div class="cb-field">
                                        <label class="cb-field-label" for="bankOpeningBalance">Opening Balance</label>
                                        <div class="cb-input">
                                            <span class="cb-input__icon"><i class="ri-cash-line"></i></span>
                                            <input type="number" class="form-control cb-input__control" id="bankOpeningBalance" placeholder="0.00" step="0.01">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="cb-form-section d-none" data-account-section="wallet">
                                <div class="cb-form-section__heading">
                                    <span class="cb-field-label">Wallet Details</span>
                                </div>
                                <div class="cb-form-grid">
                                    <div class="cb-field">
                                        <label class="cb-field-label" for="walletProvider">Provider</label>
                                        <div class="cb-input">
                                            <span class="cb-input__icon"><i class="ri-smartphone-line"></i></span>
                                            <select id="walletProvider" class="form-select cb-input__control">
                                                <option value="MTN MoMo">MTN MoMo</option>
                                                <option value="Vodafone Cash">Vodafone Cash</option>
                                                <option value="AirtelTigo Money">AirtelTigo Money</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="cb-field">
                                        <label class="cb-field-label" for="walletNumber">Wallet Number</label>
                                        <div class="cb-input">
                                            <span class="cb-input__icon"><i class="ri-hashtag"></i></span>
                                            <input type="text" class="form-control cb-input__control" id="walletNumber" placeholder="024 000 0000">
                                        </div>
                                    </div>
                                    <div class="cb-field">
                                        <label class="cb-field-label" for="walletOwner">Account Owner</label>
                                        <div class="cb-input">
                                            <span class="cb-input__icon"><i class="ri-user-line"></i></span>
                                            <input type="text" class="form-control cb-input__control" id="walletOwner" placeholder="Enter entity owner">
                                        </div>
                                    </div>
                                    <div class="cb-field">
                                        <label class="cb-field-label" for="walletOpeningBalance">Opening Balance</label>
                                        <div class="cb-input">
                                            <span class="cb-input__icon"><i class="ri-cash-line"></i></span>
                                            <input type="number" class="form-control cb-input__control" id="walletOpeningBalance" placeholder="0.00" step="0.01">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="cb-form-section">
                                <div class="cb-form-grid">
                                    <div class="cb-field">
                                        <label class="cb-field-label" for="accountNotes">Internal Notes</label>
                                        <div class="cb-input cb-input--textarea">
                                            <span class="cb-input__icon"><i class="ri-sticky-note-line"></i></span>
                                            <textarea id="accountNotes" class="form-control cb-input__control" placeholder="Add notes that the finance team should know"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" data-action="save-account" data-modal="#addAccountModal">Save Account</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reconcile Account Modal -->
    <div class="modal fade cb-modal" id="reconcileAccountModal" tabindex="-1" aria-labelledby="reconcileAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="cb-modal__surface">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reconcileAccountModalLabel">Reconcile Bank Account</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="cb-summary">
                            <div class="cb-summary__tile">
                                <span class="cb-summary__label">Bank</span>
                                <span class="cb-summary__value" data-reconcile-field="bank">—</span>
                            </div>
                            <div class="cb-summary__tile">
                                <span class="cb-summary__label">Account Number</span>
                                <span class="cb-summary__value" data-reconcile-field="account_no">—</span>
                            </div>
                            <div class="cb-summary__tile">
                                <span class="cb-summary__label">Current Balance</span>
                                <span class="cb-summary__value" data-reconcile-field="balance">GHS 0.00</span>
                            </div>
                            <div class="cb-summary__tile">
                                <span class="cb-summary__label">Pending Items</span>
                                <span class="cb-summary__value" data-reconcile-field="pending">GHS 0.00</span>
                            </div>
                        </div>

                        <form class="cb-form">
                            <div class="cb-form-section">
                                <div class="cb-form-grid">
                                    <div class="cb-field">
                                        <label class="cb-field-label" for="statementClosingBalance">Statement Closing Balance</label>
                                        <div class="cb-input">
                                            <span class="cb-input__icon"><i class="ri-cash-line"></i></span>
                                            <input type="number" class="form-control cb-input__control" id="statementClosingBalance" placeholder="0.00" step="0.01">
                                        </div>
                                    </div>
                                    <div class="cb-field">
                                        <label class="cb-field-label" for="statementDate">Statement Date</label>
                                        <div class="cb-input">
                                            <span class="cb-input__icon"><i class="ri-calendar-event-line"></i></span>
                                            <input type="date" class="form-control cb-input__control" id="statementDate">
                                        </div>
                                    </div>
                                    <div class="cb-field">
                                        <label class="cb-field-label" for="reconcileNotes">Notes</label>
                                        <div class="cb-input cb-input--textarea">
                                            <span class="cb-input__icon"><i class="ri-sticky-note-line"></i></span>
                                            <textarea id="reconcileNotes" class="form-control cb-input__control" placeholder="Capture reconciliation highlights"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" data-action="complete-reconcile" data-modal="#reconcileAccountModal">Complete Reconciliation</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Ledger Modal -->
    <div class="modal fade cb-modal" id="viewLedgerModal" tabindex="-1" aria-labelledby="viewLedgerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="cb-modal__surface">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewLedgerModalLabel">Bank Ledger</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="cb-summary">
                            <div class="cb-summary__tile">
                                <span class="cb-summary__label">Account</span>
                                <span class="cb-summary__value" data-ledger-field="account">—</span>
                            </div>
                            <div class="cb-summary__tile">
                                <span class="cb-summary__label">Branch</span>
                                <span class="cb-summary__value" data-ledger-field="branch">—</span>
                            </div>
                            <div class="cb-summary__tile">
                                <span class="cb-summary__label">Balance</span>
                                <span class="cb-summary__value" data-ledger-field="balance">GHS 0.00</span>
                            </div>
                            <div class="cb-summary__tile">
                                <span class="cb-summary__label">Last Reconciled</span>
                                <span class="cb-summary__value" data-ledger-field="reconciled">—</span>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table cb-ledger-table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Reference</th>
                                        <th>Debit (GHS)</th>
                                        <th>Credit (GHS)</th>
                                        <th>Balance (GHS)</th>
                                    </tr>
                                </thead>
                                <tbody data-ledger-field="lines">
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Ledger entries load when a bank account is selected.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Export Ledger</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wallet Charges Modal -->
    <div class="modal fade cb-modal" id="walletChargesModal" tabindex="-1" aria-labelledby="walletChargesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="cb-modal__surface">
                    <div class="modal-header">
                        <h5 class="modal-title" id="walletChargesModalLabel">Wallet Charges Report</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="cb-summary">
                            <div class="cb-summary__tile">
                                <span class="cb-summary__label">Provider</span>
                                <span class="cb-summary__value" data-wallet-field="provider">—</span>
                            </div>
                            <div class="cb-summary__tile">
                                <span class="cb-summary__label">Wallet Number</span>
                                <span class="cb-summary__value" data-wallet-field="number">—</span>
                            </div>
                            <div class="cb-summary__tile">
                                <span class="cb-summary__label">Charges Today</span>
                                <span class="cb-summary__value" data-wallet-field="charges_today">GHS 0.00</span>
                            </div>
                            <div class="cb-summary__tile">
                                <span class="cb-summary__label">Monthly Charges</span>
                                <span class="cb-summary__value" data-wallet-field="charges_month">GHS 0.00</span>
                            </div>
                        </div>

                        <div class="cb-list" data-wallet-field="charges-list">
                            <div class="cb-list__item">
                                <div>
                                    <div class="cb-list__title">No breakdown yet</div>
                                    <div class="cb-table-subtext">Choose a wallet to load detailed charges.</div>
                                </div>
                                <span class="cb-tag">Awaiting</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Download Report</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wallet Transactions Modal -->
    <div class="modal fade cb-modal" id="walletTransactionsModal" tabindex="-1" aria-labelledby="walletTransactionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="cb-modal__surface">
                    <div class="modal-header">
                        <h5 class="modal-title" id="walletTransactionsModalLabel">Wallet Transactions</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="cb-summary">
                            <div class="cb-summary__tile">
                                <span class="cb-summary__label">Provider</span>
                                <span class="cb-summary__value" data-wallet-tx-field="provider">—</span>
                            </div>
                            <div class="cb-summary__tile">
                                <span class="cb-summary__label">Wallet Number</span>
                                <span class="cb-summary__value" data-wallet-tx-field="number">—</span>
                            </div>
                            <div class="cb-summary__tile">
                                <span class="cb-summary__label">Balance</span>
                                <span class="cb-summary__value" data-wallet-tx-field="balance">GHS 0.00</span>
                            </div>
                        </div>

                        <div class="cb-list" data-wallet-tx-field="entries">
                            <div class="cb-list__item">
                                <div>
                                    <div class="cb-list__title">Transactions load when a wallet is selected</div>
                                    <div class="cb-table-subtext">Review top-ups, payouts, and fees instantly.</div>
                                </div>
                                <span class="cb-tag">Info</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Export Transactions</button>
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

            const addAccountModal = document.getElementById('addAccountModal');
            const accountTypeRadios = document.querySelectorAll('input[name="account_type"]');
            const accountSections = document.querySelectorAll('[data-account-section]');

            const toggleAccountSections = (type) => {
                accountSections.forEach(section => {
                    section.classList.toggle('d-none', section.getAttribute('data-account-section') !== type);
                });
            };

            accountTypeRadios.forEach(radio => {
                radio.addEventListener('change', () => toggleAccountSections(radio.value));
            });

            toggleAccountSections(document.querySelector('input[name="account_type"]:checked')?.value || 'bank');

            const handleModalPopulation = (modalId, callback) => {
                const modal = document.getElementById(modalId);
                if (!modal) return;
                modal.addEventListener('show.bs.modal', event => {
                    const trigger = event.relatedTarget;
                    if (!trigger) return;
                    const payload = JSON.parse(trigger.getAttribute('data-account') || trigger.getAttribute('data-wallet') || '{}');
                    callback(modal, payload);
                });
            };

            handleModalPopulation('reconcileAccountModal', (modal, account) => {
                modal.querySelector('[data-reconcile-field="bank"]').textContent = account.bank || account.provider || '—';
                modal.querySelector('[data-reconcile-field="account_no"]').textContent = account.account_no || '—';
                modal.querySelector('[data-reconcile-field="balance"]').textContent = `${account.currency || 'GHS'} ${Number(account.balance || 0).toLocaleString(undefined, { minimumFractionDigits: 2 })}`;
                modal.querySelector('[data-reconcile-field="pending"]').textContent = `${account.currency || 'GHS'} ${Number(account.pending || 0).toLocaleString(undefined, { minimumFractionDigits: 2 })}`;
                modal.querySelector('#statementClosingBalance').value = account.balance || '';
                modal.querySelector('#statementDate').value = account.last_reconciled || '';
                modal.querySelector('#reconcileNotes').value = '';
            });

            handleModalPopulation('viewLedgerModal', (modal, account) => {
                modal.querySelector('[data-ledger-field="account"]').textContent = `${account.bank || '—'} • ${account.account_no || ''}`;
                modal.querySelector('[data-ledger-field="branch"]').textContent = account.branch || '—';
                modal.querySelector('[data-ledger-field="balance"]').textContent = `${account.currency || 'GHS'} ${Number(account.balance || 0).toLocaleString(undefined, { minimumFractionDigits: 2 })}`;
                modal.querySelector('[data-ledger-field="reconciled"]').textContent = account.last_reconciled ? new Date(account.last_reconciled).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : '—';

                const ledgerBody = modal.querySelector('[data-ledger-field="lines"]');
                ledgerBody.innerHTML = '';
                const sampleLedger = [
                    { date: '2025-11-12', reference: 'Fuel purchase - Navrongo', debit: 0, credit: 3250.00, balance: (Number(account.balance || 0) + 3250).toFixed(2) },
                    { date: '2025-11-11', reference: 'Payroll disbursement', debit: 9820.50, credit: 0, balance: (Number(account.balance || 0) - 6570.50).toFixed(2) },
                    { date: '2025-11-08', reference: 'Depot supply settlement', debit: 0, credit: 5200.00, balance: (Number(account.balance || 0) - 1370.50).toFixed(2) },
                    { date: '2025-11-05', reference: 'Branch utilities', debit: 620.30, credit: 0, balance: (Number(account.balance || 0) - 1990.8).toFixed(2) },
                ];

                sampleLedger.forEach(line => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${new Date(line.date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })}</td>
                        <td>${line.reference}</td>
                        <td class="text-danger fw-semibold">${line.debit ? Number(line.debit).toLocaleString(undefined, { minimumFractionDigits: 2 }) : '—'}</td>
                        <td class="text-success fw-semibold">${line.credit ? Number(line.credit).toLocaleString(undefined, { minimumFractionDigits: 2 }) : '—'}</td>
                        <td class="fw-semibold">${Number(line.balance).toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                    `;
                    ledgerBody.appendChild(row);
                });
            });

            handleModalPopulation('walletChargesModal', (modal, wallet) => {
                modal.querySelector('[data-wallet-field="provider"]').textContent = wallet.provider || '—';
                modal.querySelector('[data-wallet-field="number"]').textContent = wallet.wallet_number || '—';
                modal.querySelector('[data-wallet-field="charges_today"]').textContent = `GHS ${Number(wallet.charges_today || 0).toLocaleString(undefined, { minimumFractionDigits: 2 })}`;
                modal.querySelector('[data-wallet-field="charges_month"]').textContent = `GHS ${Number(wallet.charges_month || 0).toLocaleString(undefined, { minimumFractionDigits: 2 })}`;

                const list = modal.querySelector('[data-wallet-field="charges-list"]');
                list.innerHTML = '';
                const sampleCharges = [
                    { label: 'Payout fees', value: wallet.charges_today || 0, tag: 'Today' },
                    { label: 'Instant cash-out', value: (wallet.charges_month || 0) * 0.42, tag: 'Week' },
                    { label: 'Bulk disbursement fees', value: (wallet.charges_month || 0) * 0.58, tag: 'Month' },
                ];

                sampleCharges.forEach(item => {
                    const el = document.createElement('div');
                    el.className = 'cb-list__item';
                    el.innerHTML = `
                        <div>
                            <div class="cb-list__title">${item.label}</div>
                            <div class="cb-table-subtext">Generated from reconciliation insights</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-semibold">GHS ${Number(item.value).toLocaleString(undefined, { minimumFractionDigits: 2 })}</div>
                            <span class="cb-tag">${item.tag}</span>
                        </div>
                    `;
                    list.appendChild(el);
                });
            });

            handleModalPopulation('walletTransactionsModal', (modal, wallet) => {
                modal.querySelector('[data-wallet-tx-field="provider"]').textContent = wallet.provider || '—';
                modal.querySelector('[data-wallet-tx-field="number"]').textContent = wallet.wallet_number || '—';
                modal.querySelector('[data-wallet-tx-field="balance"]').textContent = `GHS ${Number(wallet.balance || 0).toLocaleString(undefined, { minimumFractionDigits: 2 })}`;

                const txList = modal.querySelector('[data-wallet-tx-field="entries"]');
                txList.innerHTML = '';
                const sampleTransactions = [
                    { title: 'Top-up from HQ treasury', amount: 2500.00, when: '12 Nov 2025 • 09:20', type: 'credit' },
                    { title: 'Station payout – Navrongo', amount: 1260.50, when: '11 Nov 2025 • 17:40', type: 'debit' },
                    { title: 'Commission payout – Bamvin dealer', amount: 640.00, when: '11 Nov 2025 • 14:05', type: 'debit' },
                    { title: 'Aggregated MoMo collections', amount: 3175.90, when: '10 Nov 2025 • 20:14', type: 'credit' },
                ];

                sampleTransactions.forEach(tx => {
                    const el = document.createElement('div');
                    el.className = 'cb-list__item';
                    el.innerHTML = `
                        <div>
                            <div class="cb-list__title">${tx.title}</div>
                            <div class="cb-table-subtext">${tx.when}</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-semibold ${tx.type === 'credit' ? 'text-success' : 'text-danger'}">${tx.type === 'credit' ? '+' : '-'} GHS ${Number(tx.amount).toLocaleString(undefined, { minimumFractionDigits: 2 })}</div>
                            <span class="cb-tag">${tx.type === 'credit' ? 'Credit' : 'Debit'}</span>
                        </div>
                    `;
                    txList.appendChild(el);
                });
            });

            const actionButtons = [
                { selector: '[data-action="save-account"]', title: 'Account Saved', text: 'Your new channel has been catalogued for treasury operations.' },
                { selector: '[data-action="complete-reconcile"]', title: 'Reconciliation Logged', text: 'Statement closing balance recorded successfully.' }
            ];

            actionButtons.forEach(({ selector, title, text }) => {
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
                                        icon: 'success',
                                        title,
                                        text,
                                        confirmButtonColor: '#2563eb',
                                        backdrop: 'rgba(6, 23, 57, 0.55)'
                                    });
                                }
                            }, { once: true });
                        }
                    });
                });
            });
        });
    </script>
@endsection
