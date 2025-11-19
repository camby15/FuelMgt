@extends('layouts.vertical', ['page_title' => 'Tax Management'])

@section('css')
    <style>
        :root {
            --tm-gradient-start: #02162f;
            --tm-gradient-mid: #0b3a7b;
            --tm-gradient-end: #1c5fde;
            --tm-surface: #f6f9ff;
            --tm-card-surface: #ffffff;
            --tm-border-subtle: rgba(8, 37, 92, 0.08);
            --tm-border-strong: rgba(8, 37, 92, 0.18);
            --tm-shadow-soft: 0 20px 52px rgba(13, 46, 110, 0.18);
            --tm-shadow-card: 0 18px 46px rgba(17, 57, 140, 0.12);
            --tm-text-strong: #06224f;
            --tm-text-muted: rgba(6, 34, 79, 0.68);
            --tm-accent: #5ab9f6;
            --tm-accent-strong: #0f75f4;
            --tm-badge-paid: rgba(22, 163, 74, 0.16);
            --tm-badge-unpaid: rgba(239, 68, 68, 0.16);
        }

        .tm-page {
            display: flex;
            flex-direction: column;
            gap: 2.2rem;
        }

        .tm-hero {
            padding: 1px;
            border-radius: 28px;
            background: linear-gradient(135deg, var(--tm-gradient-start), var(--tm-gradient-mid));
            box-shadow: var(--tm-shadow-soft);
        }

        .tm-hero__surface {
            border-radius: 27px;
            background: var(--tm-surface);
            padding: 2.6rem 3rem;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
        }

        .tm-hero__content {
            max-width: 620px;
        }

        .tm-hero__eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.35rem 0.9rem;
            border-radius: 999px;
            background: rgba(28, 95, 222, 0.14);
            color: var(--tm-accent-strong);
            font-weight: 600;
            letter-spacing: 0.18em;
            font-size: 0.74rem;
            text-transform: uppercase;
        }

        .tm-hero__title {
            margin: 1.2rem 0 0.8rem;
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 0.01em;
            color: var(--tm-text-strong);
        }

        .tm-hero__subtitle {
            margin: 0;
            font-size: 0.96rem;
            line-height: 1.65;
            color: var(--tm-text-muted);
        }

        .tm-hero__actions {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
            align-items: flex-end;
        }

        .tm-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            border-radius: 999px;
            padding: 0.7rem 1.5rem;
            border: 1px solid transparent;
            font-weight: 600;
            letter-spacing: 0.08em;
            font-size: 0.8rem;
            text-transform: uppercase;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .tm-action-btn i {
            font-size: 1.05rem;
        }

        .tm-action-btn--primary {
            background: linear-gradient(90deg, var(--tm-gradient-mid), var(--tm-gradient-end));
            color: #fff;
            box-shadow: 0 18px 42px rgba(17, 57, 140, 0.26);
        }

        .tm-action-btn--primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 22px 50px rgba(17, 57, 140, 0.34);
        }

        .tm-action-btn--neutral {
            background: rgba(21, 63, 140, 0.1);
            color: var(--tm-accent-strong);
            border-color: rgba(31, 92, 191, 0.28);
        }

        .tm-action-btn--neutral:hover {
            background: rgba(21, 63, 140, 0.16);
            transform: translateY(-2px);
            box-shadow: 0 18px 36px rgba(21, 63, 140, 0.18);
        }

        .tm-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
        }

        .tm-card {
            position: relative;
            padding: 1px;
            border-radius: 24px;
            background: linear-gradient(145deg, rgba(90, 185, 246, 0.18), rgba(28, 95, 222, 0.22));
            box-shadow: var(--tm-shadow-card);
            overflow: hidden;
        }

        .tm-card::before {
            content: '';
            position: absolute;
            inset: -40% -40% auto auto;
            width: 160px;
            height: 160px;
            background: radial-gradient(circle at center, rgba(93, 191, 255, 0.32), transparent 70%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .tm-card:hover::before {
            opacity: 1;
        }

        .tm-card__surface {
            position: relative;
            border-radius: 23px;
            background: var(--tm-card-surface);
            padding: 1.7rem 2rem;
            display: flex;
            flex-direction: column;
            gap: 0.95rem;
            overflow: hidden;
        }

        .tm-card__surface::after {
            content: '';
            position: absolute;
            inset: auto -20% 0 auto;
            width: 95px;
            height: 95px;
            background: linear-gradient(145deg, rgba(28, 95, 222, 0.24), rgba(12, 44, 108, 0));
            border-radius: 50%;
            opacity: 0.6;
        }

        .tm-card__title {
            margin: 0;
            font-size: 0.78rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(6, 34, 79, 0.7);
            font-weight: 700;
        }

        .tm-card__amount {
            font-size: 1.72rem;
            font-weight: 700;
            color: var(--tm-text-strong);
            letter-spacing: 0.01em;
        }

        .tm-card__meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.82rem;
            color: var(--tm-text-muted);
        }

        .tm-card__trend {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            border-radius: 999px;
            padding: 0.24rem 0.7rem;
            font-weight: 600;
            background: rgba(22, 163, 74, 0.15);
            color: #0e6b35;
        }

        .tm-card__trend--down {
            background: rgba(239, 68, 68, 0.18);
            color: #7b1b1b;
        }

        .tm-card__indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.74rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(6, 34, 79, 0.55);
        }

        .tm-metric-card {
            background: var(--tm-card-surface);
            border-radius: 22px;
            border: 1px solid rgba(8, 37, 92, 0.08);
            box-shadow: 0 18px 42px rgba(13, 46, 110, 0.12);
            padding: 1.5rem 1.7rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            height: 100%;
        }

        .tm-metric-card .tm-metric-label {
            text-transform: uppercase;
            letter-spacing: 0.12em;
            font-size: 0.72rem;
            font-weight: 700;
            color: rgba(6, 34, 79, 0.65);
        }

        .tm-metric-card .tm-metric-value {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--tm-text-strong);
            line-height: 1.2;
        }

        .tm-metric-card .tm-metric-meta {
            font-size: 0.86rem;
            color: var(--tm-text-muted);
        }

        .tm-metric-card .tm-metric-footnote {
            font-size: 0.78rem;
            color: rgba(6, 34, 79, 0.6);
        }

        .tm-metric-card .tm-metric-progress {
            height: 8px;
            border-radius: 999px;
            background: rgba(28, 95, 222, 0.12);
            overflow: hidden;
        }

        .tm-metric-card .tm-metric-progress-bar {
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(135deg, var(--tm-gradient-mid), var(--tm-accent));
        }

        .tm-metric-icon {
            width: 50px;
            height: 50px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(12, 44, 108, 0.08), rgba(28, 95, 222, 0.22));
            color: var(--tm-accent-strong);
            font-size: 1.3rem;
        }

        .tm-metric-card .badge {
            font-size: 0.7rem;
            letter-spacing: 0.08em;
            padding: 0.35rem 0.7rem;
        }

        .tm-deadline-card {
            background: var(--tm-card-surface);
            border-radius: 22px;
            border: 1px solid rgba(8, 37, 92, 0.1);
            box-shadow: 0 18px 42px rgba(13, 46, 110, 0.12);
            padding: 1.8rem 1.9rem;
            height: 100%;
        }

        .tm-deadline-item {
            display: flex;
            align-items: flex-start;
            gap: 1.1rem;
            padding: 1.1rem 0;
            border-bottom: 1px solid rgba(8, 37, 92, 0.08);
        }

        .tm-deadline-item:first-child {
            padding-top: 0;
        }

        .tm-deadline-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .tm-deadline-indicator {
            width: 44px;
            height: 44px;
            border-radius: 15px;
            background: rgba(28, 95, 222, 0.12);
            color: var(--tm-accent-strong);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .tm-deadline-item strong {
            color: var(--tm-text-strong);
            font-weight: 700;
        }

        .tm-deadline-item .tm-deadline-meta {
            color: var(--tm-text-muted);
            font-size: 0.82rem;
        }

        .tm-flow-card {
            background: var(--tm-card-surface);
            border-radius: 22px;
            border: 1px solid rgba(8, 37, 92, 0.1);
            box-shadow: 0 18px 42px rgba(13, 46, 110, 0.12);
            padding: 1.8rem 2rem;
            height: 100%;
        }

        .tm-approval-flow {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 1.8rem;
        }

        .tm-approval-step {
            position: relative;
            padding-left: 2.8rem;
        }

        .tm-approval-step::before {
            content: '';
            position: absolute;
            left: 1.1rem;
            top: 1.8rem;
            bottom: -1.8rem;
            width: 2px;
            background: rgba(8, 37, 92, 0.12);
        }

        .tm-approval-step:last-child::before {
            display: none;
        }

        .tm-approval-step__icon {
            position: absolute;
            left: 0;
            top: 0;
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            background: rgba(12, 44, 108, 0.12);
            color: var(--tm-text-strong);
        }

        .tm-approval-step.completed .tm-approval-step__icon {
            background: rgba(22, 163, 74, 0.16);
            color: #15803d;
        }

        .tm-approval-step.pending .tm-approval-step__icon {
            background: rgba(234, 179, 8, 0.18);
            color: #b45309;
        }

        .tm-approval-step.upcoming .tm-approval-step__icon {
            background: rgba(12, 44, 108, 0.12);
            color: rgba(6, 34, 79, 0.8);
        }

        .tm-approval-step__title {
            font-size: 1.06rem;
            font-weight: 600;
            color: var(--tm-text-strong);
        }

        .tm-approval-step__meta {
            font-size: 0.78rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(6, 34, 79, 0.58);
        }

        .tm-approval-step__body {
            margin-top: 0.45rem;
            font-size: 0.86rem;
            color: var(--tm-text-muted);
        }

        .tm-panel {
            padding: 1px;
            border-radius: 24px;
            background: linear-gradient(135deg, rgba(28, 95, 222, 0.16), rgba(12, 44, 108, 0.18));
            box-shadow: var(--tm-shadow-card);
        }

        .tm-panel__surface {
            background: var(--tm-card-surface);
            border-radius: 23px;
            padding: 2rem 2.2rem;
        }

        .tm-panel__header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.6rem;
        }

        .tm-panel__title {
            margin: 0;
            font-size: 1.18rem;
            font-weight: 700;
            color: var(--tm-text-strong);
            letter-spacing: 0.01em;
        }

        .tm-panel__subtitle {
            margin: 0.25rem 0 0;
            color: var(--tm-text-muted);
            font-size: 0.86rem;
        }

        .tm-panel__actions {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
        }

        .tm-table-wrapper {
            border-radius: 20px;
            border: 1px solid var(--tm-border-subtle);
            overflow: hidden;
        }

        .tm-table {
            width: 100%;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .tm-table thead th {
            background: rgba(28, 95, 222, 0.1);
            color: rgba(6, 34, 79, 0.7);
            font-size: 0.7rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            padding: 0.85rem 1.05rem;
            border-bottom: none;
        }

        .tm-table tbody tr {
            background: #ffffff;
            transition: background 0.2s ease;
        }

        .tm-table tbody tr + tr td {
            border-top: 1px dashed var(--tm-border-subtle);
        }

        .tm-table tbody tr:hover {
            background: rgba(28, 95, 222, 0.08);
        }

        .tm-table td {
            padding: 1rem 1.05rem;
            color: var(--tm-text-strong);
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .tm-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            border-radius: 999px;
            padding: 0.28rem 0.75rem;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.12em;
        }

        .tm-badge--paid {
            background: var(--tm-badge-paid);
            color: #166534;
        }

        .tm-badge--unpaid {
            background: var(--tm-badge-unpaid);
            color: #7f1d1d;
        }

        .tm-config-modal .modal-dialog {
            max-width: 760px;
        }

        .tm-config-modal .modal-content {
            border-radius: 28px;
            padding: 1.4px;
            background: linear-gradient(145deg, rgba(12, 44, 108, 0.3), rgba(28, 95, 222, 0.2));
            box-shadow: 0 36px 72px rgba(8, 28, 72, 0.34);
        }

        .tm-config-modal .tm-modal__surface {
            border-radius: 27px;
        }

        .tm-config-intro {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1.2rem;
            background: rgba(28, 95, 222, 0.08);
            border: 1px solid rgba(28, 95, 222, 0.18);
            border-radius: 18px;
            padding: 1.2rem 1.4rem;
            margin-bottom: 1.4rem;
        }

        .tm-config-intro__title {
            margin: 0;
            font-size: 0.82rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            font-weight: 700;
            color: rgba(6, 34, 79, 0.66);
        }

        .tm-config-intro__description {
            margin: 0.2rem 0 0;
            color: var(--tm-text-muted);
            font-size: 0.86rem;
        }

        .tm-config-intro__badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            border-radius: 999px;
            padding: 0.35rem 0.8rem;
            background: rgba(90, 185, 246, 0.18);
            color: var(--tm-accent-strong);
            font-size: 0.74rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            font-weight: 600;
        }

        .tm-config-grid {
            display: grid;
            gap: 1.1rem;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            margin-bottom: 1.2rem;
        }

        .tm-config-note {
            border-radius: 18px;
            padding: 1.2rem 1.4rem;
            background: rgba(6, 34, 79, 0.04);
            border: 1px dashed rgba(6, 34, 79, 0.18);
            display: flex;
            gap: 0.9rem;
            align-items: flex-start;
            color: rgba(6, 34, 79, 0.7);
        }

        .tm-config-note i {
            font-size: 1.3rem;
            color: var(--tm-accent-strong);
        }

        .tm-config-note__title {
            font-weight: 600;
            margin-bottom: 0.35rem;
            color: var(--tm-text-strong);
        }

        .tm-config-controls {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            align-items: center;
            justify-content: space-between;
            margin-top: 0.6rem;
        }

        .tm-switch {
            padding: 0.65rem 0.95rem;
            border-radius: 16px;
            border: 1px solid rgba(28, 95, 222, 0.2);
            background: rgba(28, 95, 222, 0.08);
        }

        .tm-switch .form-check-input {
            cursor: pointer;
        }

        .tm-modal .modal-dialog {
            max-width: 640px;
        }

        .tm-modal .modal-content {
            border: none;
            border-radius: 26px;
            padding: 1.2px;
            background: linear-gradient(140deg, rgba(12, 44, 108, 0.28), rgba(28, 95, 222, 0.18));
            box-shadow: 0 32px 68px rgba(10, 38, 92, 0.34);
        }

        .tm-modal__surface {
            background: var(--tm-card-surface);
            border-radius: 25px;
            overflow: hidden;
        }

        .tm-modal__surface .modal-header {
            padding: 1.6rem 1.9rem;
            background: linear-gradient(125deg, rgba(28, 95, 222, 0.16), rgba(12, 44, 108, 0.08));
            border-bottom: 1px solid rgba(8, 37, 92, 0.08);
        }

        .tm-modal__surface .modal-body {
            padding: 1.9rem;
            background: linear-gradient(180deg, rgba(246, 249, 255, 0.98), rgba(240, 245, 255, 0.95));
        }

        .tm-modal__surface .modal-footer {
            padding: 1.4rem 1.9rem;
            background: rgba(28, 95, 222, 0.08);
            border-top: 1px solid rgba(8, 37, 92, 0.08);
        }

        .tm-form-group {
            margin-bottom: 1.15rem;
        }

        .tm-form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: rgba(6, 34, 79, 0.75);
            margin-bottom: 0.4rem;
        }

        .tm-form-control {
            border-radius: 14px;
            border: 1px solid rgba(12, 44, 108, 0.2);
            padding: 0.75rem 1rem;
            font-size: 0.92rem;
            color: var(--tm-text-strong);
            background: #fdfefe;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .tm-form-control:focus {
            border-color: rgba(28, 95, 222, 0.6);
            box-shadow: 0 0 0 4px rgba(28, 95, 222, 0.12);
        }

        .tm-submit-btn {
            border-radius: 14px;
            border: none;
            padding: 0.8rem 1.8rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            background: linear-gradient(95deg, rgba(28, 95, 222, 0.98), rgba(13, 52, 127, 0.96));
            color: #fff;
            box-shadow: 0 20px 44px rgba(17, 57, 140, 0.22);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .tm-submit-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 24px 52px rgba(17, 57, 140, 0.26);
        }

        .tm-close-btn {
            border-radius: 14px;
            border: 1px solid rgba(28, 95, 222, 0.26);
            padding: 0.8rem 1.6rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            background: rgba(28, 95, 222, 0.1);
            color: var(--tm-accent-strong);
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .tm-close-btn:hover {
            background: rgba(28, 95, 222, 0.18);
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .tm-hero__surface {
                padding: 2.2rem;
            }

            .tm-hero__actions {
                width: 100%;
                align-items: stretch;
            }

            .tm-hero__actions .tm-action-btn {
                justify-content: center;
            }

            .tm-panel__surface {
                padding: 1.6rem;
            }

            .tm-panel__header {
                align-items: stretch;
            }

            .tm-panel__actions {
                width: 100%;
                justify-content: space-between;
            }

            .tm-metric-card {
                padding: 1.2rem 1.35rem;
                gap: 0.85rem;
            }

            .tm-metric-icon {
                width: 42px;
                height: 42px;
                border-radius: 14px;
                font-size: 1.1rem;
            }

            .tm-deadline-card,
            .tm-flow-card {
                padding: 1.5rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid tm-page">
        <div class="tm-hero">
            <div class="tm-hero__surface">
                <div class="tm-hero__content">
                    <span class="tm-hero__eyebrow">
                        <i class="ri-equalizer-line"></i>
                        Tax Intelligence Suite
                    </span>
                    <h1 class="tm-hero__title">Stay ahead of every statutory tax obligation</h1>
                    <p class="tm-hero__subtitle">
                        Monitor collections in real time, reconcile obligations, and file returns with confidence.
                        Every card summarizes liability health while the ledger keeps you audit-ready.
                    </p>
                </div>
                <div class="tm-hero__actions">
                    <button class="tm-action-btn tm-action-btn--neutral" data-bs-toggle="modal"
                        data-bs-target="#configureGraTaxModal">
                        <i class="ri-settings-4-line"></i>
                        Configure GRA Tax
                    </button>
                    <button class="tm-action-btn tm-action-btn--neutral" data-bs-toggle="modal"
                        data-bs-target="#fileTaxReturnModal">
                        <i class="ri-calendar-check-line"></i>
                        Tax Returns
                    </button>
                    <button class="tm-action-btn tm-action-btn--primary">
                        <i class="ri-exchange-dollar-line"></i>
                        Export GRA Format
                    </button>
                </div>
            </div>
        </div>

        <div class="row g-3 g-lg-4">
            <div class="col-sm-6 col-xl-3">
                <div class="tm-metric-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="tm-metric-label">Filing Cycle</span>
                        <span class="badge bg-info-subtle text-info">Open</span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="tm-metric-icon"><i class="ri-calendar-event-line"></i></span>
                        <div>
                            <div class="tm-metric-value">Q3 · 2025</div>
                            <div class="tm-metric-meta">Cut-off in 5 days</div>
                        </div>
                    </div>
                    <div class="tm-metric-progress">
                        <div class="tm-metric-progress-bar" style="width: 68%;"></div>
                    </div>
                    <div class="tm-metric-footnote">Returns lodged · 68% of required filings</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="tm-metric-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="tm-metric-label">Liabilities Settled</span>
                        <span class="badge bg-success-subtle text-success">+3.4%</span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="tm-metric-icon"><i class="ri-bank-card-line"></i></span>
                        <div>
                            <div class="tm-metric-value">₵358,770</div>
                            <div class="tm-metric-meta">Paid YTD to GRA</div>
                        </div>
                    </div>
                    <div class="tm-metric-footnote">Variance vs last quarter · ₵11,850</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="tm-metric-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="tm-metric-label">Notices</span>
                        <span class="badge bg-warning-subtle text-warning">Attention</span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="tm-metric-icon"><i class="ri-notification-3-line"></i></span>
                        <div>
                            <div class="tm-metric-value">3 Open</div>
                            <div class="tm-metric-meta">2 reminders · 1 variance query</div>
                        </div>
                    </div>
                    <div class="tm-metric-footnote">Resolve escalations before 18 Sep</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="tm-metric-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="tm-metric-label">Compliance Score</span>
                        <span class="badge bg-primary-subtle text-primary">92%</span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="tm-metric-icon"><i class="ri-shield-check-line"></i></span>
                        <div>
                            <div class="tm-metric-value">Low Risk</div>
                            <div class="tm-metric-meta">Benchmarked across GRA filings</div>
                        </div>
                    </div>
                    <div class="tm-metric-footnote">Increase score by clearing pending notices</div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-4">
                <div class="tm-deadline-card h-100">
                    <h2 class="tm-panel__title mb-3">Upcoming Milestones</h2>
                    <span class="tm-panel__subtitle d-block mb-3">Keep statutory submissions aligned to due dates.</span>
                    <div class="tm-deadline-item">
                        <span class="tm-deadline-indicator"><i class="ri-file-paper-2-line"></i></span>
                        <div>
                            <strong>VAT Return · August</strong>
                            <p class="tm-deadline-meta mb-1">Due 20 Sep · Draft awaiting finance validation.</p>
                            <span class="badge bg-light text-dark">5 days left</span>
                        </div>
                    </div>
                    <div class="tm-deadline-item">
                        <span class="tm-deadline-indicator"><i class="ri-health-book-line"></i></span>
                        <div>
                            <strong>NHIL Statement</strong>
                            <p class="tm-deadline-meta mb-1">Due 22 Sep · Supporting schedules under review.</p>
                            <span class="badge bg-warning-subtle text-warning">Pending review</span>
                        </div>
                    </div>
                    <div class="tm-deadline-item">
                        <span class="tm-deadline-indicator"><i class="ri-briefcase-3-line"></i></span>
                        <div>
                            <strong>Withholding Tax Batch</strong>
                            <p class="tm-deadline-meta mb-1">Due 26 Sep · Bank confirmation required.</p>
                            <span class="badge bg-primary-subtle text-primary">Awaiting bank</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="tm-flow-card h-100">
                    <h2 class="tm-panel__title mb-3">Compliance Workflow</h2>
                    <span class="tm-panel__subtitle d-block mb-3">Monitor sign-offs before returns reach the GRA.</span>
                    <div class="tm-approval-flow">
                        <div class="tm-approval-step completed">
                            <div class="tm-approval-step__icon"><i class="ri-check-line"></i></div>
                            <div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="tm-approval-step__title">Data Consolidation</span>
                                    <span class="tm-approval-step__meta">Completed · 09 Sep</span>
                                </div>
                                <div class="tm-approval-step__body">ERP extracts reconciled with manual adjustments for VAT, NHIL, and GETFund.</div>
                            </div>
                        </div>
                        <div class="tm-approval-step completed">
                            <div class="tm-approval-step__icon"><i class="ri-check-line"></i></div>
                            <div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="tm-approval-step__title">Finance Review</span>
                                    <span class="tm-approval-step__meta">Completed · 12 Sep</span>
                                </div>
                                <div class="tm-approval-step__body">Variance report circulated with commentary on payment trends.</div>
                            </div>
                        </div>
                        <div class="tm-approval-step pending">
                            <div class="tm-approval-step__icon"><i class="ri-time-line"></i></div>
                            <div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="tm-approval-step__title">CFO Endorsement</span>
                                    <span class="tm-approval-step__meta">Awaiting · Due 18 Sep</span>
                                </div>
                                <div class="tm-approval-step__body">CFO to sign off on withholding variance response and payment plan.</div>
                            </div>
                        </div>
                        <div class="tm-approval-step upcoming">
                            <div class="tm-approval-step__icon"><i class="ri-plane-line"></i></div>
                            <div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="tm-approval-step__title">GRA Submission</span>
                                    <span class="tm-approval-step__meta">Scheduled · 20 Sep</span>
                                </div>
                                <div class="tm-approval-step__body">Submit return bundle with proof of payment and variance memo to the tax office.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tm-summary">
            @foreach ([
                ['label' => 'VAT', 'amount' => '₵82,450.30', 'trend' => '+4.6%', 'trend_class' => ''],
                ['label' => 'NHIL', 'amount' => '₵24,890.15', 'trend' => '+1.9%', 'trend_class' => ''],
                ['label' => 'GETFund', 'amount' => '₵18,302.00', 'trend' => '-0.6%', 'trend_class' => 'tm-card__trend--down'],
                ['label' => 'WHT', 'amount' => '₵15,980.72', 'trend' => '+3.2%', 'trend_class' => ''],
                ['label' => 'PAYE', 'amount' => '₵214,500.00', 'trend' => '+5.4%', 'trend_class' => '']
            ] as $card)
                <div class="tm-card">
                    <div class="tm-card__surface">
                        <p class="tm-card__title">{{ $card['label'] }}</p>
                        <div class="tm-card__amount">{{ $card['amount'] }}</div>
                        <div class="tm-card__meta">
                            <span class="tm-card__indicator">
                                <i class="ri-line-chart-line"></i>
                                Current Liability
                            </span>
                            <span class="tm-card__trend {{ $card['trend_class'] }}">
                                <i class="ri-arrow-up-line"></i>
                                {{ $card['trend'] }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="tm-panel">
            <div class="tm-panel__surface">
                <div class="tm-panel__header">
                    <div>
                        <h2 class="tm-panel__title">Tax Transactions</h2>
                        <p class="tm-panel__subtitle">Chronological ledger of filings, remittances, and audits.</p>
                    </div>
                    <div class="tm-panel__actions">
                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#fileTaxReturnModal">
                            <i class="ri-add-circle-line me-1"></i>
                            File Return
                        </button>
                        <button class="btn btn-primary btn-sm">
                            <i class="ri-filter-3-line me-1"></i>
                            Filters
                        </button>
                    </div>
                </div>

                <div class="tm-table-wrapper">
                    <table class="table tm-table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Tax Type</th>
                                <th scope="col">Base Amount</th>
                                <th scope="col">Tax Amount</th>
                                <th scope="col">Period</th>
                                <th scope="col">Paid/Unpaid</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <span class="fw-semibold">15 Sep 2025</span>
                                    <div class="text-muted small">Filed by K. Addo</div>
                                </td>
                                <td>
                                    <span class="fw-semibold">VAT</span>
                                    <div class="text-muted small">Standard rate 15%</div>
                                </td>
                                <td>₵520,000.00</td>
                                <td>₵78,000.00</td>
                                <td>
                                    <span class="fw-semibold">Aug 2025</span>
                                    <div class="text-muted small">Return #VAT-0825-01</div>
                                </td>
                                <td>
                                    <span class="tm-badge tm-badge--paid">
                                        <i class="ri-checkbox-circle-line"></i>
                                        Paid
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="fw-semibold">11 Sep 2025</span>
                                    <div class="text-muted small">Filed by M. Sackey</div>
                                </td>
                                <td>
                                    <span class="fw-semibold">PAYE</span>
                                    <div class="text-muted small">Payroll cycle #34</div>
                                </td>
                                <td>₵1,250,000.00</td>
                                <td>₵212,500.00</td>
                                <td>
                                    <span class="fw-semibold">Aug 2025</span>
                                    <div class="text-muted small">GRA Reference RH-3821</div>
                                </td>
                                <td>
                                    <span class="tm-badge tm-badge--paid">
                                        <i class="ri-checkbox-circle-line"></i>
                                        Paid
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="fw-semibold">04 Sep 2025</span>
                                    <div class="text-muted small">Filed by Y. Duah</div>
                                </td>
                                <td>
                                    <span class="fw-semibold">WHT</span>
                                    <div class="text-muted small">Supplier batch #219</div>
                                </td>
                                <td>₵150,000.00</td>
                                <td>₵22,500.00</td>
                                <td>
                                    <span class="fw-semibold">Aug 2025</span>
                                    <div class="text-muted small">Remittance schedule</div>
                                </td>
                                <td>
                                    <span class="tm-badge tm-badge--unpaid">
                                        <i class="ri-error-warning-line"></i>
                                        Unpaid
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="fw-semibold">30 Aug 2025</span>
                                    <div class="text-muted small">Filed by L. Tuffour</div>
                                </td>
                                <td>
                                    <span class="fw-semibold">NHIL</span>
                                    <div class="text-muted small">Hospitality division</div>
                                </td>
                                <td>₵310,900.00</td>
                                <td>₵27,981.00</td>
                                <td>
                                    <span class="fw-semibold">Jul 2025</span>
                                    <div class="text-muted small">Return submitted late</div>
                                </td>
                                <td>
                                    <span class="tm-badge tm-badge--paid">
                                        <i class="ri-checkbox-circle-line"></i>
                                        Paid
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade tm-modal tm-config-modal" id="configureGraTaxModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content tm-modal__surface">
                <div class="modal-header align-items-start">
                    <div>
                        <h5 class="modal-title">Configure GRA Tax</h5>
                        <p class="mb-0 text-muted small">Align tax codes, rates, and accounts with GRA expectations.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="tm-config-intro">
                        <div>
                            <p class="tm-config-intro__title">Configuration Snapshot</p>
                            <p class="tm-config-intro__description">Tune statutory rates, effective periods, and ledger
                                mappings so that downstream filings stay accurate.</p>
                        </div>
                        <span class="tm-config-intro__badge">
                            <i class="ri-shield-check-line"></i>
                            GRA Certified
                        </span>
                    </div>

                    <form>
                        <div class="tm-config-grid">
                            <div class="tm-form-group">
                                <label class="tm-form-label" for="gra-tax-category">Tax Category</label>
                                <select id="gra-tax-category" class="form-select tm-form-control">
                                    <option selected disabled>Select category</option>
                                    <option value="VAT">Value Added Tax (VAT)</option>
                                    <option value="NHIL">National Health Insurance Levy (NHIL)</option>
                                    <option value="GETFund">GETFund Levy</option>
                                    <option value="WHT">Withholding Tax</option>
                                    <option value="PAYE">PAYE</option>
                                </select>
                            </div>
                            <div class="tm-form-group">
                                <label class="tm-form-label" for="gra-code">GRA Code</label>
                                <input type="text" id="gra-code" class="form-control tm-form-control"
                                    placeholder="e.g. VAT-ST-01">
                            </div>
                            <div class="tm-form-group">
                                <label class="tm-form-label" for="gra-rate">Rate (%)</label>
                                <input type="number" step="0.01" id="gra-rate" class="form-control tm-form-control"
                                    placeholder="Enter rate">
                            </div>
                            <div class="tm-form-group">
                                <label class="tm-form-label" for="gra-effective-date">Effective Date</label>
                                <input type="date" id="gra-effective-date" class="form-control tm-form-control">
                            </div>
                            <div class="tm-form-group">
                                <label class="tm-form-label" for="gra-frequency">Filing Frequency</label>
                                <select id="gra-frequency" class="form-select tm-form-control">
                                    <option selected disabled>Choose frequency</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="quarterly">Quarterly</option>
                                    <option value="annually">Annually</option>
                                </select>
                            </div>
                            <div class="tm-form-group">
                                <label class="tm-form-label" for="gra-account">Account Mapping</label>
                                <input type="text" id="gra-account" class="form-control tm-form-control"
                                    placeholder="GL account or cost center">
                            </div>
                            <div class="tm-form-group">
                                <label class="tm-form-label" for="gra-threshold">Withholding Threshold (₵)</label>
                                <input type="number" id="gra-threshold" class="form-control tm-form-control"
                                    placeholder="0.00">
                            </div>
                            <div class="tm-form-group">
                                <label class="tm-form-label" for="gra-penalty">Penalty Interest (%)</label>
                                <input type="number" step="0.01" id="gra-penalty" class="form-control tm-form-control"
                                    placeholder="Enter rate">
                            </div>
                        </div>

                        <div class="tm-config-controls">
                            <div class="tm-switch">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" id="gra-auto-accrual" checked>
                                    <label class="form-check-label" for="gra-auto-accrual">Enable automatic accruals</label>
                                </div>
                            </div>
                            <div class="tm-switch">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" id="gra-reminder-alerts" checked>
                                    <label class="form-check-label" for="gra-reminder-alerts">Send reminder alerts</label>
                                </div>
                            </div>
                        </div>

                        <div class="tm-config-note mt-3">
                            <i class="ri-information-line"></i>
                            <div>
                                <div class="tm-config-note__title">Tip</div>
                                <div class="small">Map taxes to the same chart accounts used in your remittance exports so
                                    the GRA CSV matches ledger postings automatically.</div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="tm-close-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="tm-submit-btn">Save Configuration</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade tm-modal" id="fileTaxReturnModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content tm-modal__surface">
                <div class="modal-header align-items-start">
                    <div>
                        <h5 class="modal-title">File Tax Return</h5>
                        <p class="mb-0 text-muted small">Submit a statutory return for reconciliation and compliance.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="tm-form-group">
                                    <label class="tm-form-label" for="tax-period">Tax Period</label>
                                    <input type="month" id="tax-period" class="form-control tm-form-control"
                                        placeholder="Select period">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="tm-form-group">
                                    <label class="tm-form-label" for="tax-type">Tax Type</label>
                                    <select id="tax-type" class="form-select tm-form-control">
                                        <option selected disabled>Choose type</option>
                                        <option value="VAT">VAT</option>
                                        <option value="NHIL">NHIL</option>
                                        <option value="GETFund">GETFund</option>
                                        <option value="COVID Levy">COVID Levy</option>
                                        <option value="WHT">WHT</option>
                                        <option value="PAYE">PAYE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="tm-form-group">
                                    <label class="tm-form-label" for="tax-amount">Amount</label>
                                    <input type="number" id="tax-amount" class="form-control tm-form-control"
                                        placeholder="Enter amount">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="tm-form-group">
                                    <label class="tm-form-label" for="payment-reference">Payment Reference</label>
                                    <input type="text" id="payment-reference" class="form-control tm-form-control"
                                        placeholder="GRA receipt or bank reference">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="tm-close-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="tm-submit-btn">Submit Return</button>
                </div>
            </div>
        </div>
    </div>
@endsection
