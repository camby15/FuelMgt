@extends('layouts.vertical', [
    'page_title' => 'Bank Deposits',
    'mode' => session('theme_mode', 'light'),
])

@section('css')
    <style>
        .bank-deposits-page {
            background: #f5f7fb;
            min-height: 100vh;
            padding: 2.5rem 1.6rem 3rem;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            color: #122142;
        }

        .deposit-card,
        .deposit-filters,
        .deposit-table-card {
            border: none;
            border-radius: 18px;
            background: #ffffff;
            box-shadow: 0 18px 44px rgba(9, 28, 63, 0.12);
        }

        .deposit-card {
            position: relative;
            overflow: hidden;
            background: linear-gradient(110deg, #102247 0%, #1f5ad6 48%, #33a6ff 100%);
        }

        .deposit-card::before {
            content: '';
            position: absolute;
            inset: -80% 40% 30% -40%;
            background: radial-gradient(circle at center, rgba(255, 255, 255, 0.28), transparent 70%);
            opacity: 0.65;
            transform: rotate(8deg);
        }

        .deposit-card .card-body {
            position: relative;
            padding: 2.1rem 2.4rem;
            display: flex;
            gap: 2.4rem;
            flex-wrap: wrap;
            align-items: center;
            color: #f6f9ff;
        }

        .deposit-title {
            font-size: 1.9rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 0.6rem;
        }

        .deposit-subtitle {
            font-size: 0.88rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(247, 251, 255, 0.76);
        }

        .deposit-actions .btn,
        .deposit-form-actions .btn {
            border-radius: 12px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 0.65rem 1.4rem;
        }

        .deposit-actions .btn i,
        .deposit-form-actions .btn i {
            font-size: 1rem;
        }

        .deposit-hero-aside {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            min-width: 240px;
        }

        .deposit-hero-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.16);
            padding: 0.35rem 0.9rem;
            font-size: 0.75rem;
            letter-spacing: 0.18em;
            font-weight: 600;
            text-transform: uppercase;
        }

        .deposit-hero-metric {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            padding: 1rem 1.3rem;
            border-radius: 16px;
            background: rgba(15, 30, 66, 0.4);
            backdrop-filter: blur(6px);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.12);
        }

        .deposit-hero-metric__label {
            font-size: 0.7rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(246, 249, 255, 0.7);
        }

        .deposit-hero-metric__value {
            font-size: 1.55rem;
            font-weight: 700;
            color: #ffffff;
        }

        .deposit-hero-metric__meta {
            font-size: 0.78rem;
            color: rgba(246, 249, 255, 0.82);
        }

        .deposit-actions {
            display: inline-flex;
            gap: 0.8rem;
            flex-wrap: wrap;
        }

        .deposit-actions .btn-primary {
            background: linear-gradient(94deg, #ffee6f 0%, #ffb347 100%);
            color: #122142;
            border: none;
            box-shadow: 0 16px 32px rgba(255, 198, 70, 0.34);
        }

        .deposit-actions .btn-outline-light {
            color: #f6f9ff;
            border-color: rgba(246, 249, 255, 0.35);
            background: rgba(246, 249, 255, 0.08);
        }

        .deposit-actions .btn-outline-light:hover {
            background: rgba(246, 249, 255, 0.18);
        }

        .deposit-filters .card-body {
            padding: 1.6rem 2rem;
        }

        .view-deposit-modal {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            background: #0f1f3f;
            color: #f8fbff;
            box-shadow: 0 32px 72px rgba(9, 28, 63, 0.35);
        }

        .view-deposit-modal__header {
            padding: 1.9rem 2.1rem 1.4rem;
            background: linear-gradient(120deg, #0f1f3f 0%, #2057d5 55%, #38b6ff 100%);
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1.8rem;
        }

        .view-deposit-modal__chip {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border-radius: 999px;
            padding: 0.4rem 0.9rem;
            background: rgba(248, 251, 255, 0.18);
            font-size: 0.75rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            font-weight: 600;
        }

        .view-deposit-modal__body {
            padding: 1.8rem 2.1rem 1.8rem;
            background: linear-gradient(180deg, rgba(18, 33, 66, 0.96) 0%, rgba(15, 31, 66, 0.86) 100%);
        }

        .view-deposit-modal__footer {
            padding: 1.4rem 2.1rem 1.8rem;
            background: rgba(10, 21, 45, 0.94);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .view-deposit-footer-meta {
            font-size: 0.78rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(248, 251, 255, 0.65);
        }

        .view-deposit-card {
            border-radius: 18px;
            padding: 1.2rem 1.3rem;
            height: 100%;
            color: #0e1b35;
            box-shadow: 0 18px 42px rgba(9, 28, 63, 0.18);
        }

        .view-deposit-card__label {
            font-size: 0.72rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 0.35rem;
        }

        .view-deposit-card__value {
            font-size: 1.32rem;
            font-weight: 700;
        }

        .view-deposit-card__meta {
            font-size: 0.82rem;
            color: rgba(14, 27, 53, 0.7);
        }

        .gradient-blue {
            background: linear-gradient(135deg, #eff4ff, #c7d5ff);
        }

        .gradient-cyan {
            background: linear-gradient(135deg, #e4fbff, #bff2ff);
        }

        .gradient-purple {
            background: linear-gradient(135deg, #f0e8ff, #d5c2ff);
        }

        .gradient-amber {
            background: linear-gradient(135deg, #fff3de, #ffd39c);
        }

        .gradient-mint {
            background: linear-gradient(135deg, #e6fff5, #bdf6de);
        }

        .view-deposit-note {
            border-radius: 16px;
            padding: 1.2rem 1.4rem;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .view-deposit-note__label {
            font-size: 0.72rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(248, 251, 255, 0.72);
            font-weight: 600;
            margin-bottom: 0.35rem;
        }

        .deposit-label {
            font-size: 0.72rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            font-weight: 600;
            color: rgba(18, 33, 66, 0.68);
            margin-bottom: 0.4rem;
        }

        .deposit-input,
        .deposit-filters select {
            border-radius: 12px;
            border: 1px solid rgba(18, 33, 66, 0.16);
            background: #f8f9fc;
            padding: 0.6rem 0.9rem;
            color: #122142;
            font-size: 0.88rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .deposit-input:focus,
        .deposit-filters select:focus {
            border-color: #2f6fe5;
            box-shadow: 0 0 0 3px rgba(47, 111, 229, 0.18);
        }

        .deposit-search {
            position: relative;
        }

        .deposit-search i {
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
            color: #2f6fe5;
            font-size: 1rem;
        }

        .deposit-search input {
            padding-left: 2.6rem;
        }

        .deposit-table-card .card-body {
            padding: 1.8rem 2.1rem;
        }

        .deposit-table-meta {
            font-size: 0.75rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(18, 33, 66, 0.62);
        }

        .deposit-table-meta strong {
            color: #122142;
        }

        .deposit-page-size select {
            border-radius: 10px;
            border: 1px solid rgba(18, 33, 66, 0.16);
            padding: 0.35rem 0.95rem;
            font-size: 0.82rem;
        }

        .deposit-table-shell {
            overflow-x: auto;
        }

        .deposit-table-container {
            border-radius: 18px;
            border: 1px solid rgba(18, 33, 66, 0.12);
            background: #ffffff;
            box-shadow: 0 18px 38px rgba(9, 28, 63, 0.12);
            overflow: hidden;
        }

        .deposit-table {
            width: 100%;
            min-width: 960px;
            border-collapse: collapse;
            font-size: 0.84rem;
            color: #122142;
        }

        .deposit-table thead th {
            background: linear-gradient(92deg, rgba(18, 33, 66, 0.96) 0%, rgba(47, 111, 229, 0.9) 58%, rgba(18, 33, 66, 0.92) 100%);
            color: #ffffff;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            font-weight: 600;
            padding: 0.85rem 1rem;
            text-align: left;
            white-space: nowrap;
        }

        .deposit-table thead th + th {
            border-left: 1px solid rgba(255, 255, 255, 0.18);
        }

        .deposit-table tbody tr:nth-child(odd) {
            background: #ffffff;
        }

        .deposit-table tbody tr:nth-child(even) {
            background: #f5f7ff;
        }

        .deposit-table tbody tr:hover {
            background: rgba(47, 111, 229, 0.1);
        }

        .deposit-cell {
            padding: 0.95rem 1rem;
            border: 1px solid rgba(18, 33, 66, 0.1);
            vertical-align: top;
        }

        .deposit-cell--label {
            font-weight: 700;
            letter-spacing: 0.06em;
        }

        .deposit-cell--muted {
            color: rgba(18, 33, 66, 0.68);
        }

        .deposit-cell--amount {
            text-align: right;
        }

        .deposit-actions-cell {
            text-align: center;
            width: 120px;
        }

        .deposit-icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 12px;
            border: 1px solid rgba(47, 111, 229, 0.24);
            background: rgba(47, 111, 229, 0.08);
            color: #1f4ea8;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            margin-inline: 0.2rem;
        }

        .deposit-icon-btn:hover {
            background: rgba(47, 111, 229, 0.18);
            color: #122142;
            transform: translateY(-1px);
        }

        .deposit-icon-btn--danger {
            border-color: rgba(220, 38, 38, 0.24);
            background: rgba(220, 38, 38, 0.08);
            color: #b91c1c;
        }

        .deposit-icon-btn--danger:hover {
            background: rgba(220, 38, 38, 0.18);
            color: #7f1d1d;
        }

        .payment-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            border-radius: 999px;
            background: rgba(47, 111, 229, 0.12);
            color: #1f4ea8;
            padding: 0.2rem 0.65rem;
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .payment-pill i {
            font-size: 1rem;
        }

        .deposit-meta {
            font-size: 0.78rem;
            color: rgba(18, 33, 66, 0.6);
        }

        .deposit-narration {
            font-size: 0.9rem;
            font-weight: 500;
            color: #122142;
        }

        .deposit-narration + .deposit-meta {
            margin-top: 0.25rem;
        }

        .deposit-empty {
            padding: 3rem 1.5rem;
            border: 1px dashed rgba(18, 33, 66, 0.24);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.75);
        }

        .deposit-actions-cell .btn {
            border-radius: 10px;
            font-size: 0.78rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 0.35rem 0.65rem;
        }

        .deposit-metric-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.4rem;
            margin-bottom: 2rem;
        }

        .deposit-metric-card {
            border-radius: 18px;
            background: linear-gradient(135deg, rgba(18, 33, 66, 0.92), rgba(47, 111, 229, 0.82));
            padding: 1.4px;
            box-shadow: 0 20px 42px rgba(9, 28, 63, 0.18);
        }

        .deposit-metric-card__body {
            border-radius: 17px;
            background: #ffffff;
            padding: 1.4rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
            height: 100%;
        }

        .deposit-metric-card__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.8rem;
        }

        .deposit-metric-card__icon {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: rgba(47, 111, 229, 0.12);
            color: #1f4ea8;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .deposit-metric-card__label {
            font-size: 0.7rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(18, 33, 66, 0.68);
            font-weight: 700;
        }

        .deposit-metric-card__value {
            font-size: 1.78rem;
            font-weight: 700;
            color: #122142;
            line-height: 1.1;
        }

        .deposit-metric-card__meta {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.78rem;
            color: rgba(18, 33, 66, 0.65);
        }

        .deposit-metric-card__footer {
            margin-top: auto;
            font-size: 0.75rem;
            color: rgba(18, 33, 66, 0.58);
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .deposit-metric-card__trend {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            border-radius: 999px;
            padding: 0.28rem 0.7rem;
            font-weight: 600;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.14em;
        }

        .deposit-metric-card__trend--up {
            background: rgba(34, 197, 94, 0.18);
            color: #166534;
        }

        .deposit-metric-card__trend--down {
            background: rgba(239, 68, 68, 0.18);
            color: #7f1d1d;
        }

        .deposit-insight-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.6rem;
            margin-bottom: 2.4rem;
        }

        .deposit-insight-card {
            border-radius: 18px;
            background: #ffffff;
            border: 1px solid rgba(18, 33, 66, 0.08);
            box-shadow: 0 18px 36px rgba(9, 28, 63, 0.12);
            padding: 1.7rem 1.9rem;
            height: 100%;
        }

        .deposit-insight-card__title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #122142;
            margin-bottom: 0.6rem;
        }

        .deposit-insight-card__subtitle {
            font-size: 0.82rem;
            color: rgba(18, 33, 66, 0.65);
            margin-bottom: 1.2rem;
        }

        .deposit-timeline {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 1.4rem;
        }

        .deposit-timeline__item {
            position: relative;
            padding-left: 2.6rem;
        }

        .deposit-timeline__item::before {
            content: '';
            position: absolute;
            left: 1rem;
            top: 0.8rem;
            bottom: -1.4rem;
            width: 2px;
            background: rgba(18, 33, 66, 0.12);
        }

        .deposit-timeline__item:last-child::before {
            display: none;
        }

        .deposit-timeline__icon {
            position: absolute;
            left: 0;
            top: 0;
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(47, 111, 229, 0.12);
            color: #1f4ea8;
            font-size: 0.95rem;
        }

        .deposit-timeline__title {
            font-weight: 600;
            color: #122142;
        }

        .deposit-timeline__meta {
            font-size: 0.75rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: rgba(18, 33, 66, 0.58);
        }

        .deposit-timeline__description {
            font-size: 0.84rem;
            color: rgba(18, 33, 66, 0.7);
            margin-top: 0.35rem;
        }

        .deposit-health-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 0.8rem;
        }

        .deposit-health-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            border-radius: 999px;
            padding: 0.35rem 0.8rem;
            background: rgba(34, 197, 94, 0.16);
            color: #166534;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .deposit-health-chip--warning {
            background: rgba(234, 179, 8, 0.18);
            color: #b45309;
        }

        @media (max-width: 1200px) {
            .deposit-card .card-body,
            .deposit-table-card .card-body {
                padding: 1.6rem;
            }
        }

        @media (max-width: 768px) {
            .bank-deposits-page {
                padding: 2rem 1rem;
            }

            .deposit-actions .btn,
            .deposit-form-actions .btn {
                width: 100%;
            }

            .deposit-table thead {
                display: none;
            }

            .deposit-table tbody {
                display: block;
            }

            .deposit-table tbody tr {
                display: block;
                background: #ffffff;
                border: 1px solid rgba(18, 33, 66, 0.08);
                border-radius: 14px;
                padding: 1rem 1.1rem;
                margin-bottom: 1rem;
                box-shadow: 0 12px 28px rgba(9, 28, 63, 0.08);
            }

            .deposit-table tbody td,
            .deposit-table tbody td.deposit-cell--amount {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 1.1rem;
                text-align: left !important;
                padding: 0.65rem 0;
                border-bottom: 1px solid rgba(18, 33, 66, 0.08);
            }

            .deposit-table tbody td:last-child {
                border-bottom: none;
            }

            .deposit-table tbody td::before {
                content: attr(data-label);
                font-size: 0.66rem;
                text-transform: uppercase;
                letter-spacing: 0.16em;
                font-weight: 600;
                color: rgba(18, 33, 66, 0.58);
            }

            .deposit-table tbody td > * {
                flex: 1;
            }

            .deposit-actions-cell {
                justify-content: flex-end;
            }

            .deposit-actions-cell .deposit-icon-btn {
                margin-left: 0.5rem;
            }
        }

        @media (max-width: 576px) {
            .deposit-card,
            .deposit-filters,
            .deposit-table-card {
                border-radius: 14px;
            }

            .deposit-table thead th {
                font-size: 0.66rem;
                letter-spacing: 0.12em;
            }

            .deposit-table tbody td {
                font-size: 0.82rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="bank-deposits-page">
        <div class="card deposit-card mb-4">
            <div class="card-body">
                <div class="flex-grow-1">
                    <h2 class="deposit-title mb-0 mt-3">Bank Deposits Control Center</h2>
                    <p class="deposit-subtitle mb-0">Live visibility across cash lodgments, proof uploads, and SLA performance.</p>
                </div>
                <div class="deposit-hero-aside">
                    <div class="deposit-hero-metric">
                        <span class="deposit-hero-metric__label">Today's Value</span>
                        <span class="deposit-hero-metric__value">₵{{ number_format((float) ($metrics['today_value'] ?? 0), 2) }}</span>
                        <span class="deposit-hero-metric__meta">Captured across {{ number_format((int) ($metrics['today_stations'] ?? 0)) }} {{ \Illuminate\Support\Str::plural('station', (int) ($metrics['today_stations'] ?? 0)) }}</span>
                    </div>
                    <div class="deposit-actions">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDepositModal">
                            <i class="ri-add-line me-1"></i>
                            Add Deposit
                        </button>
                        <button type="button" class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#dailyBriefModal">
                            <i class="ri-lightbulb-flash-line me-1"></i>
                            Daily Brief
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="deposit-metric-grid">
            <div class="deposit-metric-card">
                <div class="deposit-metric-card__body">
                    <div class="deposit-metric-card__header">
                        <span class="deposit-metric-card__label">Deposits Captured</span>
                        <span class="deposit-metric-card__icon"><i class="ri-bank-line"></i></span>
                    </div>
                    <span class="deposit-metric-card__value">{{ number_format((int) ($metrics['deposits_captured_count'] ?? 0)) }}</span>
                    <div class="deposit-metric-card__meta">
                        <span class="deposit-metric-card__trend deposit-metric-card__trend--up">
                            <i class="ri-bank-line"></i>
                            Today (transaction date)
                        </span>
                        Filtered list may differ
                    </div>
                    <div class="deposit-metric-card__footer">Live from your ledger</div>
                </div>
            </div>
            <div class="deposit-metric-card">
                <div class="deposit-metric-card__body">
                    <div class="deposit-metric-card__header">
                        <span class="deposit-metric-card__label">Pending Proof Uploads</span>
                        <span class="deposit-metric-card__icon"><i class="ri-file-warning-line"></i></span>
                    </div>
                    <span class="deposit-metric-card__value text-danger">{{ number_format((int) ($metrics['pending_proof_count'] ?? 0)) }}</span>
                    <div class="deposit-metric-card__meta">
                        <i class="ri-file-warning-line text-warning"></i>
                        Deposits without an uploaded proof file
                    </div>
                    <div class="deposit-metric-card__footer">Upload proof from Add Deposit</div>
                </div>
            </div>
            <div class="deposit-metric-card">
                <div class="deposit-metric-card__body">
                    <div class="deposit-metric-card__header">
                        <span class="deposit-metric-card__label">Cash vs Transfers</span>
                        <span class="deposit-metric-card__icon"><i class="ri-exchange-dollar-line"></i></span>
                    </div>
                    <span class="deposit-metric-card__value">{{ (int) ($metrics['cash_pct_today'] ?? 0) }}% Cash</span>
                    <div class="deposit-metric-card__meta">
                        <span class="deposit-metric-card__trend deposit-metric-card__trend--up">
                            <i class="ri-exchange-dollar-line"></i>
                            Today’s mix
                        </span>
                        Transfers &amp; cheques ₵{{ number_format((float) ($metrics['transfer_total_today'] ?? 0), 2) }}
                    </div>
                    <div class="deposit-metric-card__footer">Based on today’s deposit rows</div>
                </div>
            </div>
            <div class="deposit-metric-card">
                <div class="deposit-metric-card__body">
                    <div class="deposit-metric-card__header">
                        <span class="deposit-metric-card__label">Clearance Time</span>
                        <span class="deposit-metric-card__icon"><i class="ri-timer-line"></i></span>
                    </div>
                    <span class="deposit-metric-card__value">—</span>
                    <div class="deposit-metric-card__meta">
                        <span class="deposit-metric-card__trend deposit-metric-card__trend--up">
                            <i class="ri-timer-line"></i>
                            Not tracked yet
                        </span>
                        SLA analytics can be added later
                    </div>
                    <div class="deposit-metric-card__footer">Placeholder metric</div>
                </div>
            </div>
        </div>
        <div class="card deposit-filters mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('company.fuel.bank-deposits.index') }}" id="filtersForm" class="deposit-filters__form">
                    <div class="row g-3 align-items-end">
                        <div class="col-sm-6 col-lg-3">
                            <label for="fromDate" class="deposit-label">From</label>
                            <input type="date" id="fromDate" name="from" value="{{ request('from') }}" class="form-control deposit-input">
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <label for="toDate" class="deposit-label">To</label>
                            <input type="date" id="toDate" name="to" value="{{ request('to') }}" class="form-control deposit-input">
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <label for="siteSelect" class="deposit-label">Station</label>
                            <select id="siteSelect" name="site" class="form-select deposit-input">
                                <option value="">All Stations</option>
                                @foreach(($sites ?? []) as $site)
                                    <option value="{{ $site['value'] ?? $site }}" @selected(request('site') == ($site['value'] ?? $site))>
                                        {{ $site['label'] ?? $site }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 align-items-end mt-0 pt-3">
                        <div class="col-lg-6">
                            <label for="depositSearch" class="deposit-label">Search</label>
                            <div class="deposit-search">
                                <i class="ri-search-line"></i>
                                <input type="search" id="depositSearch" name="search" placeholder="Search by station, reference, narration..." value="{{ request('search') }}" class="form-control deposit-input">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="deposit-form-actions d-flex flex-wrap justify-content-lg-end gap-2">
                                <button type="submit" class="btn btn-primary">
                                    Apply Filters
                                </button>
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#exportDepositModal">
                                    <i class="ri-download-2-line"></i>
                                    Export
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @php
            $isPaginator = isset($deposits) && $deposits instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
            $totalItems = $isPaginator ? $deposits->total() : 0;
            $currentPage = $isPaginator ? $deposits->currentPage() : 1;
            $lastPage = $isPaginator ? max($deposits->lastPage(), 1) : 1;
            $perPageDefault = (int) request('per_page', $isPaginator ? $deposits->perPage() : 50);
            $pageParameter = $isPaginator ? $deposits->getPageName() : 'page';
            $nextPageUrl = $isPaginator && $deposits->hasMorePages()
                ? request()->fullUrlWithQuery([$pageParameter => $deposits->currentPage() + 1])
                : null;
        @endphp

        <div class="card deposit-table-card">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                    <div class="deposit-table-meta">
                        Page <strong>{{ $currentPage }}</strong> of <strong>{{ $lastPage }}</strong>
                        <span>( <strong id="bankVisibleCount">{{ number_format($totalItems) }}</strong>
                        <span id="bankVisibleLabel">{{ \Illuminate\Support\Str::plural('item', $totalItems) }}</span> )</span>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <div class="deposit-page-size d-flex align-items-center gap-2">
                            <span class="deposit-label mb-0 text-nowrap">Page size</span>
                            <select name="per_page" form="filtersForm" class="form-select form-select-sm">
                                @foreach([10, 25, 50, 100] as $size)
                                    <option value="{{ $size }}" @selected($perPageDefault === $size)>{{ $size }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if($nextPageUrl)
                            <a href="{{ $nextPageUrl }}" class="btn btn-outline-primary btn-sm">
                                Next Page
                                <i class="ri-arrow-right-line ms-1"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="deposit-table-shell">
                    <div class="deposit-table-container">
                        <table class="deposit-table">
                            <thead>
                                <tr>
                                    <th>Dates</th>
                                    <th>Station</th>
                                    <th>Account</th>
                                    <th>Amount</th>
                                    <th>Payment</th>
                                    <th>Depositer</th>
                                    <th>Narration</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deposits as $deposit)
                                    @php
                                        $transactionDate = $deposit->transaction_date?->format('d-M-Y') ?? '—';
                                        $salesDate = $deposit->sales_date?->format('d-M-Y') ?? '—';
                                        $stationName = $deposit->station?->name ?? '—';
                                        $accountName = $deposit->account_name;
                                        $accountNumber = $deposit->account_number;
                                        $amount = 'GHS' . number_format((float) $deposit->amount, 2);
                                        $depositor = $deposit->deposit_by;
                                        $narration = $deposit->narration;
                                        $additionalDetails = $deposit->details;
                                        $paymentMode = $deposit->payment_mode;
                                        $transactionId = $deposit->transaction_id;
                                        $viewUrl = $deposit->proof_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($deposit->proof_path) : '';
                                        $deleteUrl = route('company.fuel.bank-deposits.destroy', $deposit);
                                        $transactionDateIso = $deposit->transaction_date?->format('Y-m-d') ?? '';
                                        $salesDateIso = $deposit->sales_date?->format('Y-m-d') ?? '';
                                        $varianceMeta = $deposit->proof_path ? 'Proof on file' : 'Proof pending';

                                        $stationSlug = \Illuminate\Support\Str::slug($stationName ?? '', '-');
                                        $searchIndex = \Illuminate\Support\Str::lower(
                                            collect([
                                                $transactionDate,
                                                $salesDate,
                                                $stationName,
                                                $accountName,
                                                $accountNumber,
                                                $amount,
                                                $transactionId,
                                                $depositor,
                                                $narration,
                                                $additionalDetails,
                                                $paymentMode,
                                            ])->filter(fn ($value) => filled($value) && $value !== '—')->implode(' ')
                                        );

                                        $paymentModeIconMap = [
                                            'cash' => 'ri-money-dollar-circle-line',
                                            'cheque' => 'ri-file-list-3-line',
                                            'mobile money' => 'ri-smartphone-line',
                                            'momo' => 'ri-smartphone-line',
                                            'bank transfer' => 'ri-bank-transfer-line',
                                            'transfer' => 'ri-bank-transfer-line',
                                        ];
                                        $paymentModeKey = \Illuminate\Support\Str::lower($paymentMode ?? '');
                                        $paymentModeIcon = $paymentModeIconMap[$paymentModeKey] ?? 'ri-wallet-3-line';
                                    @endphp
                                    <tr data-row="deposit"
                                        data-transaction-date="{{ $transactionDateIso }}"
                                        data-sales-date="{{ $salesDateIso }}"
                                        data-station-slug="{{ $stationSlug }}"
                                        data-search="{{ e($searchIndex) }}">
                                        <td class="deposit-cell deposit-cell--label" data-label="Dates">
                                            <div>{{ $transactionDate }}</div>
                                            <div class="deposit-meta">Sales date · {{ $salesDate }}</div>
                                        </td>
                                        <td class="deposit-cell" data-label="Station">
                                            <div class="fw-semibold text-uppercase">{{ $stationName }}</div>
                                        </td>
                                        <td class="deposit-cell" data-label="Account">
                                            <div class="fw-semibold">{{ $accountName }}</div>
                                            <div class="deposit-meta">{{ $accountNumber }}</div>
                                        </td>
                                        <td class="deposit-cell deposit-cell--amount" data-label="Amount">
                                            <div class="fw-semibold">{{ $amount }}</div>
                                            <div class="deposit-meta text-uppercase">Net deposit</div>
                                        </td>
                                        <td class="deposit-cell" data-label="Payment">
                                            <div class="d-flex flex-column gap-1">
                                                <span class="payment-pill">
                                                    <i class="{{ $paymentModeIcon }}"></i>
                                                    {{ $paymentMode }}
                                                </span>
                                                <span class="deposit-meta">Ref: {{ $transactionId }}</span>
                                            </div>
                                        </td>
                                        <td class="deposit-cell" data-label="Depositer">
                                            <div class="fw-semibold">{{ $depositor }}</div>
                                            <div class="deposit-meta">{{ $stationName }}</div>
                                        </td>
                                        <td class="deposit-cell" data-label="Narration">
                                            <div class="deposit-narration">{{ $narration }}</div>
                                            @if(filled($additionalDetails) && $additionalDetails !== $narration)
                                                <div class="deposit-meta">{{ $additionalDetails }}</div>
                                            @endif
                                        </td>
                                        <td class="deposit-cell deposit-actions-cell" data-label="Actions">
                                            <button type="button"
                                                    class="deposit-icon-btn js-view-deposit"
                                                    aria-label="View deposit"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewDepositModal"
                                                    data-transaction-date="{{ e($transactionDate) }}"
                                                    data-sales-date="{{ e($salesDate) }}"
                                                    data-station="{{ e($stationName) }}"
                                                    data-account="{{ e($accountName) }}"
                                                    data-account-number="{{ e($accountNumber) }}"
                                                    data-amount="{{ e($amount) }}"
                                                    data-depositor="{{ e($depositor) }}"
                                                    data-narration="{{ e($narration) }}"
                                                    data-payment-mode="{{ e($paymentMode) }}"
                                                    data-transaction-id="{{ e($transactionId) }}"
                                                    data-additional="{{ e($additionalDetails) }}"
                                                    data-view-url="{{ $viewUrl }}"
                                                    data-variance-meta="{{ e($varianceMeta) }}"
                                                    data-footer-meta="{{ e($deposit->created_at?->format('d M Y H:i') ?? '—') }}">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                            <button type="button"
                                                    class="deposit-icon-btn deposit-icon-btn--danger js-delete-deposit"
                                                    aria-label="Delete deposit"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteDepositModal"
                                                    data-delete-url="{{ $deleteUrl }}"
                                                    data-depositor="{{ e($depositor) }}"
                                                    data-amount="{{ e($amount) }}"
                                                    data-transaction-id="{{ e($transactionId) }}">
                                                <i class="ri-delete-bin-6-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="deposit-cell">
                                            <div class="deposit-empty text-center">
                                                <i class="ri-database-2-line display-6 text-muted mb-3"></i>
                                                <h6 class="text-uppercase text-muted small fw-semibold">No deposit data</h6>
                                                <p class="text-muted mb-0">Adjust your filters or add a new bank deposit record.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($isPaginator)
                    <div class="pt-3">
                        {{ $deposits->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Create Deposit Modal -->
    <div class="modal fade" id="createDepositModal" tabindex="-1" aria-labelledby="createDepositModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="modal-title" id="createDepositModalLabel">Add New Deposit</h5>
                        <p class="text-muted mb-0 small text-uppercase letter-spacing">Fill in the daily banking details</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-3">
                    <form id="createDepositForm" method="POST" action="{{ route('company.fuel.bank-deposits.store') }}" enctype="multipart/form-data">
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger small mb-3" role="alert">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="transactionDate" class="form-label text-uppercase small fw-semibold">Transaction Date</label>
                                <input type="date" class="form-control" id="transactionDate" name="transaction_date" value="{{ old('transaction_date') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="salesDate" class="form-label text-uppercase small fw-semibold">Sales Date</label>
                                <input type="date" class="form-control" id="salesDate" name="sales_date" value="{{ old('sales_date') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="stationSelect" class="form-label text-uppercase small fw-semibold">Station</label>
                                <select class="form-select" id="stationSelect" name="fuel_station_id" required>
                                    <option value="" selected disabled>Select Station</option>
                                    @foreach(($stations ?? collect()) as $st)
                                        <option value="{{ $st->id }}" @selected(old('fuel_station_id') == $st->id)>{{ $st->name }} ({{ $st->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="accountName" class="form-label text-uppercase small fw-semibold">Account Name</label>
                                <input type="text" class="form-control" id="accountName" name="account_name" value="{{ old('account_name') }}" placeholder="e.g. CBG Main Branch" required>
                            </div>
                            <div class="col-md-6">
                                <label for="accountNumber" class="form-label text-uppercase small fw-semibold">Account Number</label>
                                <input type="text" class="form-control" id="accountNumber" name="account_number" value="{{ old('account_number') }}" placeholder="e.g. CBG-21573161100001" required>
                            </div>
                            <div class="col-md-6">
                                <label for="depositAmount" class="form-label text-uppercase small fw-semibold">Amount</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="depositAmount" name="amount" value="{{ old('amount') }}" placeholder="e.g. 21000.00" required>
                            </div>
                            <div class="col-md-6">
                                <label for="depositBy" class="form-label text-uppercase small fw-semibold">Depositer</label>
                                <input type="text" class="form-control" id="depositBy" name="deposit_by" value="{{ old('deposit_by') }}" placeholder="Name of depositer" required>
                            </div>
                            <div class="col-md-12">
                                <label for="narration" class="form-label text-uppercase small fw-semibold">Narration</label>
                                <textarea class="form-control" id="narration" name="narration" rows="3" placeholder="Brief description of the deposit" required>{{ old('narration') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="paymentMode" class="form-label text-uppercase small fw-semibold">Payment Mode</label>
                                <select class="form-select" id="paymentMode" name="payment_mode" required>
                                    <option value="" disabled @selected(!old('payment_mode'))>Select Mode</option>
                                    @foreach(\App\Models\FuelManagement\AccountDeposits\CompanyFuelBankDeposit::paymentModes() as $mode)
                                        <option value="{{ $mode }}" @selected(old('payment_mode') === $mode)>{{ $mode }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="transactionId" class="form-label text-uppercase small fw-semibold">Transaction ID</label>
                                <input type="text" class="form-control" id="transactionId" name="transaction_id" value="{{ old('transaction_id') }}" placeholder="Reference or slip number" required>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="supportingFile" class="form-label text-uppercase small fw-semibold">Upload Proof (Optional)</label>
                            <input class="form-control" type="file" id="supportingFile" name="file" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" form="createDepositForm">
                        <span class="me-1">Save Deposit</span>
                        <i class="ri-save-3-line"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div class="modal fade" id="exportDepositModal" tabindex="-1" aria-labelledby="exportDepositModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="modal-title" id="exportDepositModalLabel">Export Deposits</h5>
                        <p class="text-muted mb-0 small text-uppercase letter-spacing">Download a copy of this view</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-3">
                    <form id="exportDepositForm">
                        <div class="mb-3">
                            <label class="form-label text-uppercase small fw-semibold">Date Range</label>
                            <div class="row g-2">
                                <div class="col-sm-6">
                                    <input type="date" class="form-control" name="export_from" value="{{ request('from') }}">
                                </div>
                                <div class="col-sm-6">
                                    <input type="date" class="form-control" name="export_to" value="{{ request('to') }}">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-uppercase small fw-semibold">Station</label>
                            <select class="form-select" name="export_station">
                                <option value="">All Stations</option>
                                @foreach(($sites ?? []) as $siteOption)
                                    <option value="{{ $siteOption['value'] ?? $siteOption }}">{{ $siteOption['label'] ?? $siteOption }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-uppercase small fw-semibold">Format</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="export_format" id="formatCsv" value="csv" checked>
                                    <label class="form-check-label" for="formatCsv">CSV</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="export_format" id="formatPdf" value="pdf">
                                    <label class="form-check-label" for="formatPdf">PDF</label>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="form-label text-uppercase small fw-semibold">Include Columns</label>
                            <div class="row g-2 small">
                                @foreach(['Transaction Date','Sales Date','Station','Account','Amount','Depositer','Narration','Payment Mode','Transaction ID'] as $column)
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="columns[]" value="{{ $column }}" id="column-{{ \Illuminate\Support\Str::slug($column) }}" checked>
                                            <label class="form-check-label" for="column-{{ \Illuminate\Support\Str::slug($column) }}">{{ $column }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="generateExportBtn">
                        <span class="me-1">Generate Export</span>
                        <i class="ri-download-cloud-2-line"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Deposit Modal -->
    <div class="modal fade" id="viewDepositModal" tabindex="-1" aria-labelledby="viewDepositModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content view-deposit-modal">
                <div class="view-deposit-modal__header">
                    <div>
                        <span class="view-deposit-modal__chip">Deposit Summary</span>
                        <h5 class="modal-title mt-2" id="viewDepositModalLabel">Treasury Review</h5>
                        <p class="mb-0 small">Cross-check station lodgment, proof status, and SLA compliance</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="view-deposit-modal__body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="view-deposit-card gradient-blue">
                                <div class="view-deposit-card__label">Dates</div>
                                <div class="view-deposit-card__value" data-view-field="transaction_date">—</div>
                                <div class="view-deposit-card__meta">Sales Date · <span data-view-field="sales_date">—</span></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="view-deposit-card gradient-cyan">
                                <div class="view-deposit-card__label">Station</div>
                                <div class="view-deposit-card__value" data-view-field="station">—</div>
                                <div class="view-deposit-card__meta">Payment Mode · <span data-view-field="payment_mode">—</span></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="view-deposit-card gradient-purple">
                                <div class="view-deposit-card__label">Account</div>
                                <div class="view-deposit-card__value" data-view-field="account_name">—</div>
                                <div class="view-deposit-card__meta" data-view-field="account_number">—</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="view-deposit-card gradient-amber">
                                <div class="view-deposit-card__label">Amount</div>
                                <div class="view-deposit-card__value" data-view-field="amount">—</div>
                                <div class="view-deposit-card__meta">Depositer · <span data-view-field="depositor">—</span></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="view-deposit-card gradient-mint">
                                <div class="view-deposit-card__label">Transaction ID</div>
                                <div class="view-deposit-card__value" data-view-field="transaction_id">—</div>
                                <div class="view-deposit-card__meta">Variance Status · <span data-view-field="variance_meta">On track</span></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="view-deposit-note">
                                <div class="view-deposit-note__label">Narration</div>
                                <p class="mb-0" data-view-field="narration">—</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="view-deposit-modal__footer">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
                        <a href="#" class="btn btn-light text-primary d-none" id="viewDepositExternalLink" target="_blank" rel="noopener">
                            <i class="ri-external-link-line me-1"></i>
                            Open Proof
                        </a>
                    </div>
                    <div class="view-deposit-footer-meta">
                        <i class="ri-shield-check-line me-1"></i>
                        Recorded · <span data-view-field="footer_meta">—</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Deposit Modal -->
    <div class="modal fade" id="deleteDepositModal" tabindex="-1" aria-labelledby="deleteDepositModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="modal-title text-danger" id="deleteDepositModalLabel">Delete Deposit</h5>
                        <p class="text-muted mb-0 small text-uppercase letter-spacing">This action cannot be undone</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-3">
                    <p class="mb-2">You are about to remove the deposit recorded for <span class="fw-semibold" data-delete-field="depositor">—</span>.</p>
                    <p class="mb-0">Transaction reference: <span class="fw-semibold" data-delete-field="transaction_id">—</span></p>
                    <p class="mb-0 mt-1">Amount: <span class="fw-semibold" data-delete-field="amount">—</span></p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteDepositBtn" data-delete-url="">
                        <span class="me-1">Delete</span>
                        <i class="ri-delete-bin-7-line"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Brief Modal -->
    <div class="modal fade" id="dailyBriefModal" tabindex="-1" aria-labelledby="dailyBriefModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dailyBriefModalLabel">Daily Brief</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-0">A narrative daily brief will be available once analytics are connected. For now, use the dashboard metrics above and the filtered table.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var successMessage = @json(session('success'));
            var errorMessage = @json(session('error'));
            var canUseSwal = typeof Swal !== 'undefined';
            if (successMessage && canUseSwal) {
                Swal.fire({ icon: 'success', title: 'Success', text: successMessage, confirmButtonText: 'OK' });
            }
            if (errorMessage && canUseSwal) {
                Swal.fire({ icon: 'error', title: 'Oops...', text: errorMessage, confirmButtonText: 'OK' });
            }

            @if ($errors->any())
            var depositModalEl = document.getElementById('createDepositModal');
            if (depositModalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                bootstrap.Modal.getOrCreateInstance(depositModalEl).show();
            }
            @endif

            const depositModal = document.getElementById('createDepositModal');
            const depositForm = document.getElementById('createDepositForm');
            const exportModal = document.getElementById('exportDepositModal');
            const exportForm = document.getElementById('exportDepositForm');
            const generateExportBtn = document.getElementById('generateExportBtn');
            const viewModal = document.getElementById('viewDepositModal');
            const deleteModal = document.getElementById('deleteDepositModal');
            const confirmDeleteBtn = document.getElementById('confirmDeleteDepositBtn');
            const filterForm = document.getElementById('filtersForm');
            const fromInput = document.getElementById('fromDate');
            const toInput = document.getElementById('toDate');
            const siteSelect = document.getElementById('siteSelect');
            const searchInput = filterForm?.querySelector('input[name="search"]');
            const depositRows = Array.from(document.querySelectorAll('tr[data-row="deposit"]'));
            const noResultsRow = document.getElementById('bankTableNoResults');
            const visibleCountEl = document.getElementById('bankVisibleCount');
            const visibleLabelEl = document.getElementById('bankVisibleLabel');

            if (exportModal && exportForm) {
                exportModal.addEventListener('hidden.bs.modal', () => {
                    exportForm.reset();
                    exportForm.querySelector('#formatCsv').checked = true;
                    exportForm.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = true);
                });
            }

            if (generateExportBtn) {
                generateExportBtn.addEventListener('click', () => {
                    if (canUseSwal) {
                        Swal.fire({ icon: 'info', title: 'Export', text: 'CSV/PDF export from the server will be added in a follow-up. Use Apply Filters and your browser for now.', confirmButtonText: 'OK' });
                    }
                    const exportModalInstance = bootstrap.Modal.getInstance(exportModal);
                    exportModalInstance?.hide();
                });
            }

            if (viewModal) {
                viewModal.addEventListener('show.bs.modal', event => {
                    const trigger = event.relatedTarget;
                    if (!trigger) return;

                    const fieldMap = {
                        transaction_date: 'transaction-date',
                        sales_date: 'sales-date',
                        station: 'station',
                        account_name: 'account',
                        account_number: 'account-number',
                        amount: 'amount',
                        depositor: 'depositor',
                        narration: 'narration',
                        payment_mode: 'payment-mode',
                        transaction_id: 'transaction-id',
                        variance_meta: 'variance-meta'
                    };

                    Object.keys(fieldMap).forEach(key => {
                        const value = trigger.getAttribute(`data-${fieldMap[key]}`) || '—';
                        const target = viewModal.querySelector(`[data-view-field="${key}"]`);
                        if (target) target.textContent = value;
                    });

                    const footerMeta = viewModal.querySelector('[data-view-field="footer_meta"]');
                    if (footerMeta) {
                        footerMeta.textContent = trigger.getAttribute('data-footer-meta') || '—';
                    }

                    const externalLink = document.getElementById('viewDepositExternalLink');
                    if (externalLink) {
                        const href = trigger.getAttribute('data-view-url');
                        if (href) {
                            externalLink.href = href;
                            externalLink.classList.remove('d-none');
                        } else {
                            externalLink.href = '#';
                            externalLink.classList.add('d-none');
                        }
                    }
                });
            }

            if (deleteModal && confirmDeleteBtn) {
                deleteModal.addEventListener('show.bs.modal', event => {
                    const trigger = event.relatedTarget;
                    if (!trigger) return;

                    const transactionId = trigger.getAttribute('data-transaction-id') || '—';
                    const depositor = trigger.getAttribute('data-depositor') || '—';
                    const amount = trigger.getAttribute('data-amount') || '—';
                    const deleteUrl = trigger.getAttribute('data-delete-url') || '';

                    const transactionTarget = deleteModal.querySelector('[data-delete-field="transaction_id"]');
                    const depositorTarget = deleteModal.querySelector('[data-delete-field="depositor"]');
                    const amountTarget = deleteModal.querySelector('[data-delete-field="amount"]');

                    if (transactionTarget) transactionTarget.textContent = transactionId;
                    if (depositorTarget) depositorTarget.textContent = depositor;
                    if (amountTarget) amountTarget.textContent = amount;
                    confirmDeleteBtn.dataset.deleteUrl = deleteUrl;
                });

                confirmDeleteBtn.addEventListener('click', function () {
                    const url = confirmDeleteBtn.getAttribute('data-delete-url');
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    if (!url) {
                        if (canUseSwal) {
                            Swal.fire({ icon: 'error', title: 'Cannot delete', text: 'Missing delete link. Close and try again.', confirmButtonText: 'OK' });
                        }
                        return;
                    }
                    if (!token) {
                        if (canUseSwal) {
                            Swal.fire({ icon: 'error', title: 'Session', text: 'Missing CSRF token. Refresh the page.', confirmButtonText: 'OK' });
                        }
                        return;
                    }

                    confirmDeleteBtn.disabled = true;

                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.style.display = 'none';

                    var tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = '_token';
                    tokenInput.value = token;
                    form.appendChild(tokenInput);

                    var methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit();
                });
            }
        });
    </script>
@endpush
