@extends('layouts.vertical', [
    'page_title' => 'Daily  Sales',
    'mode' => $mode ?? '',
    'demo' => $demo ?? '',
])

@section('css')
    <style>
        .sales-wrapper {
            padding: 1.5rem 0;
        }

        .sales-card {
            position: relative;
            border: 1px solid #e1e7f5;
            border-radius: 20px;
            box-shadow: 0 18px 42px rgba(18, 35, 72, 0.08);
            overflow: hidden;
            background: #ffffff;
        }

        .sales-card__header {
            background: linear-gradient(120deg, #0f3c94 0%, #1c64d8 48%, #2f8cf5 100%);
            padding: 2rem 2.3rem;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 2.2rem;
            color: #f4f7ff;
        }

        .sales-card__title {
            margin: 0;
            font-size: 1.9rem;
            font-weight: 700;
            letter-spacing: 0.1rem;
            text-transform: uppercase;
        }

        .sales-hero-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border-radius: 999px;
            padding: 0.45rem 1rem;
            background: rgba(244, 247, 255, 0.22);
            font-size: 0.75rem;
            letter-spacing: 0.16em;
            font-weight: 600;
            text-transform: uppercase;
        }

        .sales-hero-metric {
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
            padding: 1.1rem 1.35rem;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.14);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.22);
        }

        .sales-hero-metric__label {
            font-size: 0.72rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: rgba(244, 247, 255, 0.72);
        }

        .sales-hero-metric__value {
            font-size: 1.6rem;
            font-weight: 700;
            color: #ffffff;
        }

        .sales-hero-metric__meta {
            font-size: 0.8rem;
            color: rgba(244, 247, 255, 0.82);
        }

        .sales-hero-aside {
            display: flex;
            flex-direction: column;
            gap: 1.1rem;
            min-width: 250px;
        }

        .sales-card__filters {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem 1.4rem;
            padding: 1rem 1.4rem;
            border-bottom: 1px solid #dce3ef;
            background: #f8faff;
        }

        .sales-card__inner {
            background: #ffffff;
            padding: 2.1rem 2.3rem 2.4rem;
        }

        .sales-card__body {
            margin-top: 2rem;
        }

        .sales-card__filters .filter-group {
            min-width: 160px;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .sales-card__filters label {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.3rem;
            color: #516083;
        }

        .sales-card__filters input,
        .sales-card__filters select {
            border-radius: 6px;
            border: 1px solid #c6d1e3;
            background: #ffffff;
            font-size: 0.85rem;
            padding: 0.48rem 0.75rem;
            min-height: 40px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .sales-card__filters input:focus,
        .sales-card__filters select:focus {
            outline: none;
            border-color: #0c46a0;
            box-shadow: 0 0 0 2px rgba(12, 70, 160, 0.18);
        }

        .sales-card__actions {
            display: flex;
            gap: 0.85rem;
            flex-wrap: wrap;
        }

        .sales-metric-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.4rem;
            margin: 2.2rem 0;
        }

        .sales-metric-card {
            border-radius: 20px;
            padding: 1.8rem 1.6rem;
            border: 1px solid rgba(229, 231, 235, 0.8);
            background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.06), 0 2px 10px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
            height: 100%;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sales-metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3b82f6 0%, #8b5cf6 50%, #ec4899 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sales-metric-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12), 0 4px 20px rgba(0, 0, 0, 0.08);
            border-color: rgba(59, 130, 246, 0.3);
        }

        .sales-metric-card:hover::before {
            opacity: 1;
        }

        .sales-metric-card__body {
            display: flex;
            flex-direction: column;
            gap: 0.95rem;
            height: 100%;
        }

        .sales-metric-card__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.8rem;
        }

        .sales-metric-card__icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.12) 0%, rgba(139, 92, 246, 0.12) 100%);
            color: #3b82f6;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
            transition: all 0.3s ease;
        }

        .sales-metric-card:hover .sales-metric-card__icon {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.25);
        }

        .sales-metric-card__label {
            font-size: 0.75rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 0.3rem;
        }

        .sales-metric-card__value {
            font-size: 1.85rem;
            font-weight: 800;
            color: #1e293b;
            line-height: 1.1;
            background: linear-gradient(135deg, #1e293b 0%, #475569 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .sales-metric-card__meta,
        .sales-metric-card__footer {
            font-size: 0.82rem;
            color: #64748b;
            line-height: 1.4;
        }

        .sales-metric-card__trend {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            border-radius: 999px;
            padding: 0.35rem 0.8rem;
            font-size: 0.68rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .sales-metric-card__trend--up {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.15) 0%, rgba(16, 185, 129, 0.15) 100%);
            color: #059669;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .sales-metric-card__trend--down {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(220, 38, 38, 0.15) 100%);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        /* Specific card type styling */
        .sales-metric-card:nth-child(1) .sales-metric-card__icon {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.15) 0%, rgba(16, 185, 129, 0.15) 100%);
            color: #059669;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);
        }

        .sales-metric-card:nth-child(2) .sales-metric-card__icon {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(220, 38, 38, 0.15) 100%);
            color: #dc2626;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);
        }

        .sales-metric-card:nth-child(3) .sales-metric-card__icon {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(37, 99, 235, 0.15) 100%);
            color: #2563eb;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }

        .sales-metric-card:nth-child(4) .sales-metric-card__icon {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.15) 0%, rgba(147, 51, 234, 0.15) 100%);
            color: #9333ea;
            box-shadow: 0 4px 12px rgba(168, 85, 247, 0.15);
        }

        .report-table-card {
            margin-top: 2.4rem;
            border-radius: 22px;
            border: 1px solid rgba(205, 216, 239, 0.8);
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 18px 40px rgba(19, 41, 97, 0.08);
        }

        .report-table-card__header {
            padding: 1.6rem 2.2rem 1.1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .report-table-card__title {
            margin: 0;
            font-size: 1.22rem;
            font-weight: 700;
            color: #1d2c4f;
            letter-spacing: 0.08rem;
        }

        .sales-table-shell {
            margin-top: 1.5rem;
            overflow-x: auto;
        }

        .sales-table-container {
            border-radius: 12px;
            border: 1px solid rgba(16, 44, 98, 0.1);
            background: #ffffff;
            padding: 0;
        }

        .sales-table {
            border-collapse: separate;
            width: 100%;
        }

        .sales-table thead th {
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.22rem;
            color: #66759c;
            border: none;
            background: rgba(240, 243, 252, 0.9);
            padding: 0.95rem 1.2rem;
        }

        .sales-table tbody tr {
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .sales-table tbody tr:hover {
            background: rgba(47, 111, 255, 0.08);
            transform: translateY(-2px);
        }

        .sales-table tbody td {
            border-top: 1px solid rgba(221, 229, 249, 0.7);
            padding: 1rem 1.2rem;
            color: #25365f;
            font-size: 0.9rem;
        }

        .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            border-radius: 999px;
            padding: 0.35rem 0.85rem;
            font-size: 0.72rem;
            letter-spacing: 0.16rem;
            text-transform: uppercase;
            font-weight: 700;
        }

        .status-chip.success {
            background: rgba(16, 185, 129, 0.14);
            color: #047857;
        }

        .status-chip.pending {
            background: rgba(234, 179, 8, 0.16);
            color: #92400e;
        }

        @media (min-width: 769px) {
            .sales-table thead th:first-child,
            .sales-table tbody td:first-child,
            .sales-table tfoot td:first-child {
                position: sticky;
                left: 0;
                z-index: 2;
                background: #ffffff;
                box-shadow: 8px 0 12px -8px rgba(12, 40, 91, 0.18);
            }

            .sales-table thead th:first-child {
                background: #f1f4fb;
                z-index: 3;
            }

            .sales-table tbody tr:nth-child(even) td:first-child {
                background: #f9fbff;
            }

            .sales-table tbody tr:hover td:first-child {
                background: rgba(15, 88, 193, 0.08);
            }

            .sales-table tfoot td:first-child {
                background: #f1f4fb;
                font-weight: 600;
            }
        }

        .sales-cell {
            padding: 1rem 1.1rem;
            vertical-align: middle;
            line-height: 1.45;
        }

        .sales-cell__value {
            display: inline-block;
            color: #2f4170;
        }

        .sales-cell__value--strong {
            font-weight: 600;
            color: #12254a;
        }

        .sales-cell__value--muted {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.82rem;
            color: #6c7aa6;
        }

        .sales-cell--amount {
            text-align: right;
            font-weight: 600;
        }

        .sales-action-cell {
            text-align: center;
            width: 160px;
            display: flex;
            justify-content: center;
            gap: 0.4rem;
        }

        .sales-action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 10px;
            border: 1px solid rgba(15, 88, 193, 0.22);
            background: rgba(15, 88, 193, 0.06);
            color: #1b4ebb;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
        }

        .sales-action-btn:hover {
            background: rgba(15, 88, 193, 0.14);
            color: #0b2e6f;
            transform: translateY(-1px);
        }

        .sales-action-btn--danger {
            border-color: rgba(220, 38, 38, 0.22);
            background: rgba(220, 38, 38, 0.06);
            color: #9f1d20;
        }

        .sales-action-btn--danger:hover {
            background: rgba(220, 38, 38, 0.14);
            color: #6f1112;
        }

        .sales-action-btn--edit {
            border-color: rgba(251, 191, 36, 0.28);
            background: rgba(250, 204, 21, 0.08);
            color: #b45309;
        }

        .sales-action-btn--edit:hover {
            background: rgba(250, 204, 21, 0.16);
            color: #92400e;
        }

        @media (max-width: 768px) {
            .sales-table-container {
                border-radius: 10px;
            }

            .sales-table-shell {
                overflow-x: visible;
            }

            .sales-table thead {
                display: none;
            }

            .sales-table tbody {
                display: block;
            }

            .sales-table tbody tr {
                display: grid;
                grid-template-columns: 1fr;
                gap: 0.75rem;
                padding: 1rem;
                border-top: 1px solid rgba(16, 44, 98, 0.08);
                background: #ffffff;
            }

            .sales-table tbody td {
                border: 0;
                padding: 0;
            }

            .sales-table tbody td::before {
                content: attr(data-label);
                display: block;
                font-size: 0.7rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                color: #7a8cae;
                margin-bottom: 0.25rem;
            }

            .sales-cell--amount {
                text-align: left;
            }

            .sales-action-cell {
                justify-content: flex-start;
                display: flex;
                gap: 0.5rem;
            }
        }

        .sales-btn {
            min-width: 140px;
            padding: 0.6rem 1.4rem;
            border-radius: 6px;
            border: none;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .sales-btn--primary {
            background: linear-gradient(90deg, #0f58c1, #2274e0);
            color: #ffffff;
            box-shadow: 0 12px 24px rgba(34, 116, 224, 0.25);
        }

        .sales-btn--primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(34, 116, 224, 0.3);
        }

        .sales-btn--ghost {
            background: #f3f6fc;
            color: #2b3c62;
            border: 1px solid #c7d1e4;
        }

        .sales-btn--ghost:hover {
            transform: translateY(-1px);
            border-color: #0f58c1;
            color: #0f58c1;
            box-shadow: 0 6px 16px rgba(15, 88, 193, 0.2);
        }

        .dropdown-menu {
            border-radius: 8px;
            border: 1px solid #d3dbea;
            box-shadow: 0 16px 32px rgba(18, 47, 104, 0.12);
        }

        .dropdown-item {
            font-size: 0.82rem;
            padding: 0.5rem 1rem;
        }

        .dropdown-item:hover {
            background: rgba(15, 88, 193, 0.08);
            color: #0f58c1;
        }

        .sales-table-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .sales-table-actions__note {
            color: #6c7aa6;
            font-size: 0.78rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .table-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            color: #4e5e7f;
            font-size: 0.78rem;
        }

        .table-toolbar .search-input {
            min-width: 240px;
        }

        .table-toolbar .search-input input {
            text-align: left;
        }

        .table-toolbar select {
            min-width: 90px;
            text-align: left;
        }

        .add-sales-modal .modal-header {
            background: #0f58c1;
            color: #ffffff;
            border-bottom: none;
        }

        .add-sales-modal .modal-title {
            text-transform: uppercase;
            letter-spacing: 0.08rem;
            font-size: 1rem;
            font-weight: 700;
        }

        .add-sales-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem 1.4rem;
        }

        .add-sales-form .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .add-sales-form label {
            font-size: 0.78rem;
            font-weight: 600;
            color: #2b3c62;
        }

        .add-sales-form input,
        .add-sales-form select {
            border-radius: 6px;
            border: 1px solid #c6d1e3;
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
        }

        .add-sales-form input:focus,
        .add-sales-form select:focus {
            outline: none;
            border-color: #0f58c1;
            box-shadow: 0 0 0 2px rgba(15, 88, 193, 0.18);
        }

        .add-sales-form .highlight-field {
            background: #f6e96b;
            font-weight: 600;
        }

        .add-sales-modal .modal-footer {
            border-top: none;
            display: flex;
            justify-content: flex-end;
            gap: 0.8rem;
        }

        .view-sale-modal {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            background: #101f3f;
            color: #f4f8ff;
            box-shadow: 0 32px 76px rgba(16, 31, 63, 0.36);
        }

        .view-sale-modal__header {
            padding: 1.8rem 2rem 1.2rem;
            background: linear-gradient(118deg, #0f1f3f 0%, #1f5ad6 52%, #33b1ff 100%);
            display: flex;
            justify-content: space-between;
            gap: 1.6rem;
        }

        .view-sale-modal__body {
            padding: 1.7rem 2rem;
            background: linear-gradient(180deg, rgba(16, 31, 63, 0.96) 0%, rgba(17, 38, 84, 0.88) 100%);
        }

        .view-sale-modal__footer {
            padding: 1.3rem 2rem 1.7rem;
            background: rgba(9, 20, 44, 0.92);
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            font-size: 0.78rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(244, 248, 255, 0.7);
        }

        .view-sale-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border-radius: 999px;
            padding: 0.4rem 0.85rem;
            background: rgba(244, 248, 255, 0.18);
            font-size: 0.75rem;
            letter-spacing: 0.16em;
            font-weight: 600;
            text-transform: uppercase;
        }

        .view-sale-card {
            border-radius: 18px;
            padding: 1.2rem 1.3rem;
            height: 100%;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .view-sale-card__label {
            font-size: 0.72rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: rgba(244, 248, 255, 0.76);
            margin-bottom: 0.35rem;
        }

        .view-sale-card__value {
            font-size: 1.24rem;
            font-weight: 700;
            color: #ffffff;
        }

        .view-sale-card__meta {
            font-size: 0.8rem;
            color: rgba(244, 248, 255, 0.72);
        }

        .add-sales-modal .modal-footer .btn {
            min-width: 120px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.04rem;
        }

        .btn-submit {
    background: linear-gradient(90deg, #0f58c1 0%, #3a8dde 100%);
    color: #fff !important;
    border: none;
    border-radius: 6px;
    padding: 12px 28px;
    font-size: 1rem;
    font-weight: 700;
    box-shadow: 0 2px 8px rgba(15, 88, 193, 0.15);
    text-shadow: 0 1px 4px rgba(0,0,0,0.32), 0 0px 1px #000;
    transition: background 0.2s, box-shadow 0.2s, transform 0.1s;
    outline: none;
    cursor: pointer;
}
.btn-submit:hover, .btn-submit:focus {
    background: linear-gradient(90deg, #1362d6 0%, #5ba3f2 100%);
    box-shadow: 0 4px 16px rgba(15, 88, 193, 0.18);
    transform: translateY(-2px) scale(1.03);
}


        @media (max-width: 992px) {
            .sales-card__header {
                flex-direction: column;
                align-items: flex-start;
            }

            .sales-card__filters .filter-group {
                flex: 1 1 45%;
            }

            .table-toolbar {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid sales-wrapper">
        <div class="sales-card">
            <div class="sales-card__header">
                <div class="flex-grow-1">
                    <span class="sales-hero-chip">
                        <i class="ri-store-2-line"></i>
                        Daily forecourt snapshot
                    </span>
                    <h2 class="sales-card__title mt-3 mb-1">Sales Operations Command</h2>
                    <p class="mb-0 small text-uppercase text-white-50 letter-spacing">Monitor station throughput, pump activity, and attendant performance.</p>
                </div>
                <div class="sales-hero-aside">
                    <div class="sales-hero-metric">
                        <span class="sales-hero-metric__label">Today’s Volume</span>
                        <span class="sales-hero-metric__value">18,420 L</span>
                        <span class="sales-hero-metric__meta">Across 9 active stations</span>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="sales-btn sales-btn--ghost" data-bs-toggle="modal" data-bs-target="#exportOptionsModal">
                            <i class="ri-download-2-line me-1"></i>
                            Export Report
                        </button>
                    </div>
                </div>
            </div>

            <div class="sales-card__inner">
                <form id="stockSalesForm" class="sales-card__filters">
                    <div class="filter-group">
                        <label for="reportDateFrom">From</label>
                        <input type="date" id="reportDateFrom" required>
                    </div>
                    <div class="filter-group">
                        <label for="reportDateTo">To</label>
                        <input type="date" id="reportDateTo" required>
                    </div>
                    <div class="filter-group">
                        <label for="reportStation">Station</label>
                        <select id="reportStation" required>
                            <option value="" selected disabled>Select Station</option>
                            <option value="Wiaga">Wiaga</option>
                            <option value="Kintampo">Kintampo</option>
                            <option value="Navrongo Main">Navrongo Main</option>
                            <option value="Wapuli">Wapuli</option>
                            <option value="Bamvin">Bamvin</option>
                            <option value="Paga Anex">Paga Anex</option>
                            <option value="Larabanga">Larabanga</option>
                            <option value="Amoako">Amoako</option>
                            <option value="Navrongo-2">Navrongo-2</option>
                            <option value="Bububele">Bububele</option>
                        </select>
                    </div>
                    <div class="sales-card__actions">
                        <button type="submit" class="sales-btn sales-btn--primary">Search</button>
                        <button type="button" class="sales-btn sales-btn--ghost" data-bs-toggle="modal" data-bs-target="#dailySalesBrief">Daily Brief</button>
                    </div>
                </form>

                <div class="sales-metric-grid">
                    <div class="sales-metric-card">
                        <div class="sales-metric-card__body">
                            <div class="sales-metric-card__header">
                                <div>
                                    <div class="sales-metric-card__label">Gross takings</div>
                                    <div class="sales-metric-card__value">₵248,610</div>
                                </div>
                                <span class="sales-metric-card__icon">
                                    <i class="ri-funds-line"></i>
                                </span>
                            </div>
                            <div class="sales-metric-card__meta">Cash + POS + Transfers</div>
                            <div class="sales-metric-card__footer">
                                <span class="sales-metric-card__trend sales-metric-card__trend--up">
                                    <i class="ri-arrow-up-s-line"></i>
                                    6.8%
                                </span>
                                vs yesterday
                            </div>
                        </div>
                    </div>
                    <div class="sales-metric-card">
                        <div class="sales-metric-card__body">
                            <div class="sales-metric-card__header">
                                <div>
                                    <div class="sales-metric-card__label">Variance flagged</div>
                                    <div class="sales-metric-card__value">3 stations</div>
                                </div>
                                <span class="sales-metric-card__icon">
                                    <i class="ri-alert-line"></i>
                                </span>
                            </div>
                            <div class="sales-metric-card__meta">Meter vs cash variance above threshold</div>
                            <div class="sales-metric-card__footer text-danger">Larabanga · Paga Anex · Bububele</div>
                        </div>
                    </div>
                    <div class="sales-metric-card">
                        <div class="sales-metric-card__body">
                            <div class="sales-metric-card__header">
                                <div>
                                    <div class="sales-metric-card__label">AGO (Diesel) sold in Liters</div>
                                    <div class="sales-metric-card__value">2,902.89 L</div>
                                </div>
                                <span class="sales-metric-card__icon">
                                    <i class="ri-drop-line"></i>
                                </span>
                            </div>
                            <div class="sales-metric-card__meta">Today's total diesel sales</div>
                            <div class="sales-metric-card__footer">Across 8 stations</div>
                        </div>
                    </div>
                    <div class="sales-metric-card">
                        <div class="sales-metric-card__body">
                            <div class="sales-metric-card__header">
                                <div>
                                    <div class="sales-metric-card__label">PMS (Super) sold in Liters</div>
                                    <div class="sales-metric-card__value">3,156.42 L</div>
                                </div>
                                <span class="sales-metric-card__icon">
                                    <i class="ri-drop-line"></i>
                                </span>
                            </div>
                            <div class="sales-metric-card__meta">Today's total petrol sales</div>
                            <div class="sales-metric-card__footer">Across 6 stations</div>
                        </div>
                    </div>
                </div>

                <div class="sales-card__body">
                    <div class="sales-table-actions">
                        <button type="button" class="sales-btn sales-btn--primary" data-bs-toggle="modal" data-bs-target="#addSalesModal">
                            <i class="ri-add-line me-1"></i>
                            Add Sales
                        </button>
                        <span class="sales-table-actions__note">Monitor and log station activity</span>
                    </div>

                    <div class="table-toolbar">
                        <div class="text-muted small">Showing 1 of 1 records</div>
                        <div class="search-input">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-transparent text-muted"><i class="ri-search-line"></i></span>
                                <input type="text" class="form-control" placeholder="Search station or attendant">
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small">Page size:</span>
                            <select class="form-select form-select-sm" style="width: 90px;">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50" selected>50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>

                    <div class="sales-table-shell">
                        <div class="report-table-card">
                            <div class="report-table-card__header">
                                <h5 class="report-table-card__title mb-0">Daily Sales Entry</h5>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addSalesModal">
                                        <i class="ri-add-line me-1"></i> Add Sales
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#exportModal">
                                        <i class="ri-download-2-line me-1"></i> Export
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table sales-table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Station</th>
                                        <th>Pump</th>
                                        <th>Product</th>
                                        <th>Attendant</th>
                                        <th>Opening Metre</th>
                                        <th>Closing Metre</th>
                                        <th>RTT</th>
                                        <th>Net Qty</th>
                                        <th>Quantity</th>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                        <th>Variance</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>09 Nov 2025</td>
                                        <td>Navrongo Main</td>
                                        <td>Pump A</td>
                                        <td>Diesel</td>
                                        <td>Patience Adongo</td>
                                        <td>18,542.3200</td>
                                        <td>19,897.6500</td>
                                        <td>45.20</td>
                                        <td>1,310.13</td>
                                        <td>1,310.13</td>
                                        <td>₵13.45</td>
                                        <td>₵17,623.26</td>
                                        <td>0.00</td>
                                        <td class="text-end">
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewSaleModal">
                                                    <i class="ri-eye-line"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editSaleModal">
                                                    <i class="ri-edit-line"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteSaleModal">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>09 Nov 2025</td>
                                        <td>Larabanga</td>
                                        <td>Pump B</td>
                                        <td>Petrol</td>
                                        <td>Rukaya Fuseini</td>
                                        <td>9,842.1100</td>
                                        <td>10,728.5600</td>
                                        <td>18.40</td>
                                        <td>868.05</td>
                                        <td>868.05</td>
                                        <td>₵14.39</td>
                                        <td>₵12,487.50</td>
                                        <td>0.00</td>
                                        <td class="text-end">
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewSaleModal">
                                                    <i class="ri-eye-line"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editSaleModal">
                                                    <i class="ri-edit-line"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteSaleModal">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>08 Nov 2025</td>
                                        <td>Wapuli</td>
                                        <td>Pump Y</td>
                                        <td>Diesel</td>
                                        <td>Zainab Mahama</td>
                                        <td>12,476.8800</td>
                                        <td>13,295.7400</td>
                                        <td>22.15</td>
                                        <td>796.71</td>
                                        <td>796.71</td>
                                        <td>₵12.38</td>
                                        <td>₵9,861.30</td>
                                        <td>0.00</td>
                                        <td class="text-end">
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewSaleModal">
                                                    <i class="ri-eye-line"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editSaleModal">
                                                    <i class="ri-edit-line"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteSaleModal">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="table-active fw-bold">
                                        <td colspan="11" class="text-end">TOTAL:</td>
                                        <td id="totalAmount">₵40,022.06</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Sales Modal -->
        <div class="modal fade add-sales-modal" id="addSalesModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <form id="addSalesModalForm">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Sales</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="add-sales-form">
                                <div class="form-group">
                                    <label for="modalSalesDate">Date <span class="text-danger">*</span></label>
                                    <input type="date" id="modalSalesDate" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="modalStationSelect">Station <span class="text-danger">*</span></label>
                                    <select id="modalStationSelect" class="form-select" required>
                                        <option value="" disabled selected>Select Station</option>
                                        <option value="Wiaga">Wiaga</option>
                                        <option value="Kintampo">Kintampo</option>
                                        <option value="Navrongo Main">Navrongo Main</option>
                                        <option value="Wapuli">Wapuli</option>
                                        <option value="Bamvin">Bamvin</option>
                                        <option value="Paga Anex">Paga Anex</option>
                                        <option value="Larabanga">Larabanga</option>
                                        <option value="Amoako">Amoako</option>
                                        <option value="Navrongo-2">Navrongo-2</option>
                                        <option value="Bububele">Bububele</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="modalPump">Pump <span class="text-danger">*</span></label>
                                    <input type="text" id="modalPump" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="modalProduct">Product <span class="text-danger">*</span></label>
                                    <select id="modalProductSelect" class="form-select" required>
                                        <option value="" disabled selected>Select Product</option>
                                        <option value="Diesel">Diesel</option>
                                        <option value="Petrol">Petrol</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="modalAttendant">Attendant <span class="text-danger">*</span></label>
                                    <input type="text" id="modalAttendant" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="modalOpeningMetre">Opening Metre <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" id="modalOpeningMetre" class="form-control" value="0.0000" required>
                                </div>
                                <div class="form-group">
                                    <label for="modalClosingMetre">Closing Metre <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" id="modalClosingMetre" class="form-control" value="0.0000" required>
                                </div>
                                <div class="form-group">
                                    <label for="modalTestQuantity">RTT <span class="text-danger"></span></label>
                                    <input type="number" step="0.01" id="modalTestQuantity" class="form-control" value="0.00">
                                </div>
                                <div class="form-group">
                                    <label for="modalNetQuantity">Net Quantity</label>
                                    <input type="number" step="0.01" id="modalNetQuantity" class="form-control highlight-field" value="0.00">
                                </div>
                                <div class="form-group">
                                    <label for="modalQuantity">Quantity</label>
                                    <input type="number" step="0.01" id="modalQuantity" class="form-control highlight-field" value="0.00">
                                </div>
                                <div class="form-group">
                                    <label for="modalRate">Rate <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" id="modalRate" class="form-control highlight-field" value="0.0000" required>
                                </div>
                                <div class="form-group">
                                    <label for="modalAmount">Amount</label>
                                    <input type="number" step="0.01" id="modalAmount" class="form-control highlight-field" value="0.00">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Daily Brief Modal -->
        <div class="modal fade" id="dailySalesBrief" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content view-sale-modal">
                    <div class="view-sale-modal__header">
                        <div>
                            <span class="view-sale-chip">
                                <i class="ri-pie-chart-2-line"></i>
                                Daily brief
                            </span>
                            <h5 class="modal-title mt-2">Sales Operations Briefing</h5>
                            <p class="mb-0 small">Quick highlights for treasury and operations sync</p>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="view-sale-modal__body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="view-sale-card">
                                    <div class="view-sale-card__label">Peak station</div>
                                    <div class="view-sale-card__value">Navrongo Main</div>
                                    <div class="view-sale-card__meta">₵42,900 takings · 214 RTT variance</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="view-sale-card">
                                    <div class="view-sale-card__label">Variance watch</div>
                                    <div class="view-sale-card__value text-warning">3 stations</div>
                                    <div class="view-sale-card__meta">Investigate Larabanga pump Y, Bububele pump 3</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="view-sale-card">
                                    <div class="view-sale-card__label">Cash compliance</div>
                                    <div class="view-sale-card__value">94.6%</div>
                                    <div class="view-sale-card__meta">POS settlement pending at Wapuli</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="view-sale-card">
                                    <div class="view-sale-card__label">Logistics note</div>
                                    <div class="view-sale-card__value">2 trucks en route</div>
                                    <div class="view-sale-card__meta">Diesel top-up to Kintampo & Bamvin before 18:00</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="view-sale-modal__footer">
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
                            <a href="#" class="btn btn-light text-primary">
                                <i class="ri-share-forward-line me-1"></i>
                                Share brief
                            </a>
                        </div>
                        <div>
                            <i class="ri-shield-check-line me-1"></i>
                            Updated 15:45 · Treasury automation
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Sale Modal -->
        <div class="modal fade" id="viewSaleModal" tabindex="-1" aria-labelledby="viewSaleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content view-sale-modal">
                    <div class="view-sale-modal__header">
                        <div>
                            <span class="view-sale-chip">
                                <i class="ri-eye-line"></i>
                                Sale detail
                            </span>
                            <h5 class="modal-title mt-2" id="viewSaleModalLabel">Forecourt Transaction Review</h5>
                            <p class="mb-0 small">Validate meter readings, cash takings, and attendant accountability.</p>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="view-sale-modal__body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="view-sale-card">
                                    <div class="view-sale-card__label">Station</div>
                                    <div class="view-sale-card__value" data-sale-field="station">—</div>
                                    <div class="view-sale-card__meta">Pump · <span data-sale-field="pump">—</span></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="view-sale-card">
                                    <div class="view-sale-card__label">Date</div>
                                    <div class="view-sale-card__value" data-sale-field="date">—</div>
                                    <div class="view-sale-card__meta">Product · <span data-sale-field="product">—</span></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="view-sale-card">
                                    <div class="view-sale-card__label">Attendant</div>
                                    <div class="view-sale-card__value" data-sale-field="attendant">—</div>
                                    <div class="view-sale-card__meta">Shift · <span data-sale-field="shift">Morning</span></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="view-sale-card">
                                    <div class="view-sale-card__label">Amount</div>
                                    <div class="view-sale-card__value" data-sale-field="amount">—</div>
                                    <div class="view-sale-card__meta">Rate · <span data-sale-field="rate">—</span></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="view-sale-card">
                                    <div class="view-sale-card__label">Meter readings</div>
                                    <div class="view-sale-card__value" data-sale-field="readings">Opening 0.0000 · Closing 0.0000</div>
                                    <div class="view-sale-card__meta">RTT · <span data-sale-field="rtt">0.00</span></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="view-sale-card">
                                    <div class="view-sale-card__label">Net quantity</div>
                                    <div class="view-sale-card__value" data-sale-field="quantity">0.00 L</div>
                                    <div class="view-sale-card__meta">Variance · <span data-sale-field="variance-status">On track</span></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="view-sale-card">
                                    <div class="view-sale-card__label">Remarks</div>
                                    <div class="view-sale-card__value" data-sale-field="remarks">—</div>
                                    <div class="view-sale-card__meta">Captured by <span data-sale-field="captured-by">—</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="view-sale-modal__footer">
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
                            <a href="#" class="btn btn-light text-primary" data-sale-link="export-url">
                                <i class="ri-printer-line me-1"></i>
                                Print slip
                            </a>
                        </div>
                        <div>
                            <i class="ri-time-line me-1"></i>
                            Logged <span data-sale-field="logged-at">30 Oct 2025 · 14:20</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Options Modal -->
        <div class="modal fade" id="exportOptionsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Export Report</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-3">Choose the format you would like to export.</p>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary" id="openCsvModal">Export as CSV</button>
                            <button type="button" class="btn btn-outline-primary" id="openPdfModal">Export as PDF</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export CSV Modal -->
        <div class="modal fade" id="exportCsvModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Export Report (CSV)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-3">Download the current station sales report as a CSV file.</p>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="totals" id="csvIncludeTotals" checked>
                            <label class="form-check-label" for="csvIncludeTotals">Include totals summary</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="remarks" id="csvIncludeRemarks" checked>
                            <label class="form-check-label" for="csvIncludeRemarks">Include remarks column</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="confirmCsvExport">Export CSV</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export PDF Modal -->
        <div class="modal fade" id="exportPdfModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Export Report (PDF)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-3">Generate a PDF version of the current station sales report.</p>
                        <div class="mb-3">
                            <label for="pdfOrientation" class="form-label">Orientation</label>
                            <select class="form-select" id="pdfOrientation">
                                <option value="portrait">Portrait</option>
                                <option value="landscape" selected>Landscape</option>
                            </select>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="summary" id="pdfIncludeSummary" checked>
                            <label class="form-check-label" for="pdfIncludeSummary">Include summary cover page</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="confirmPdfExport">Export PDF</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportModalLabel">
                    <i class="ri-download-2-line me-2"></i>Export Sales Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-4">Choose the format to export your sales data:</p>
                
                <div class="d-grid gap-3">
                    <button class="btn btn-outline-primary d-flex align-items-center justify-content-center p-3" onclick="exportSales('csv')">
                        <i class="ri-file-excel-2-line me-2 fs-5"></i>
                        <div class="text-start">
                            <div class="fw-bold">Export as CSV</div>
                            <small class="text-muted">Excel compatible format</small>
                        </div>
                    </button>
                    
                    <button class="btn btn-outline-danger d-flex align-items-center justify-content-center p-3" onclick="exportSales('pdf')">
                        <i class="ri-file-pdf-line me-2 fs-5"></i>
                        <div class="text-start">
                            <div class="fw-bold">Export as PDF</div>
                            <small class="text-muted">Print-ready document</small>
                        </div>
                    </button>
                    
                    <button class="btn btn-outline-info d-flex align-items-center justify-content-center p-3" onclick="exportSales('excel')">
                        <i class="ri-file-excel-line me-2 fs-5"></i>
                        <div class="text-start">
                            <div class="fw-bold">Export as Excel</div>
                            <small class="text-muted">Microsoft Excel format</small>
                        </div>
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const exportOptionsModalElement = document.getElementById('exportOptionsModal');
    const openCsvModalButton = document.getElementById('openCsvModal');
    const openPdfModalButton = document.getElementById('openPdfModal');
    const confirmCsvExportButton = document.getElementById('confirmCsvExport');
    const confirmPdfExportButton = document.getElementById('confirmPdfExport');
    const csvModalElement = document.getElementById('exportCsvModal');
    const pdfModalElement = document.getElementById('exportPdfModal');

    function hideExportOptionsModal() {
        if (!exportOptionsModalElement) return;
        const instance = bootstrap.Modal.getInstance(exportOptionsModalElement);
        if (instance) instance.hide();
    }

    if (openCsvModalButton && csvModalElement) {
        openCsvModalButton.addEventListener('click', function() {
            hideExportOptionsModal();
            const modal = new bootstrap.Modal(csvModalElement);
            modal.show();
        });
    }

    if (openPdfModalButton && pdfModalElement) {
        openPdfModalButton.addEventListener('click', function() {
            hideExportOptionsModal();
            const modal = new bootstrap.Modal(pdfModalElement);
            modal.show();
        });
    }

    if (confirmCsvExportButton) {
        confirmCsvExportButton.addEventListener('click', function() {
            const includeTotals = document.getElementById('csvIncludeTotals')?.checked;
            const includeRemarks = document.getElementById('csvIncludeRemarks')?.checked;
            alert(`CSV export triggered. Totals: ${includeTotals ? 'Yes' : 'No'}, Remarks: ${includeRemarks ? 'Yes' : 'No'}`);
            const modal = bootstrap.Modal.getInstance(csvModalElement);
            if (modal) modal.hide();
        });
    }

    if (confirmPdfExportButton) {
        confirmPdfExportButton.addEventListener('click', function() {
            const orientation = document.getElementById('pdfOrientation')?.value || 'landscape';
            const includeSummary = document.getElementById('pdfIncludeSummary')?.checked;
            alert(`PDF export triggered. Orientation: ${orientation}, Summary page: ${includeSummary ? 'Yes' : 'No'}`);
            const modal = bootstrap.Modal.getInstance(pdfModalElement);
            if (modal) modal.hide();
        });
    }

    const addSalesModal = document.getElementById('addSalesModal');
    const addSalesButtons = document.querySelectorAll('[data-bs-target="#addSalesModal"]');
    if (addSalesModal && addSalesButtons.length) {
        addSalesButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                if (!window.bootstrap || !bootstrap.Modal) return;
                const modal = bootstrap.Modal.getOrCreateInstance(addSalesModal);
                modal.show();
            });
        });
    }
});

// Auto-calculate total amount
function calculateTotalAmount() {
    const table = document.querySelector('.sales-table');
    const rows = table.querySelectorAll('tbody tr');
    let total = 0;
    
    rows.forEach(row => {
        const amountCell = row.cells[11]; // Amount column (0-indexed)
        const amountText = amountCell.textContent.replace('₵', '').replace(',', '');
        const amount = parseFloat(amountText);
        if (!isNaN(amount)) {
            total += amount;
        }
    });
    
    document.getElementById('totalAmount').textContent = `₵${total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}`;
}

// Calculate total when page loads
calculateTotalAmount();

// Recalculate when table content changes (if dynamic)
const observer = new MutationObserver(calculateTotalAmount);
observer.observe(document.querySelector('.sales-table tbody'), { 
    childList: true, 
    subtree: true 
});

// Export sales data function
function exportSales(format) {
    const modal = bootstrap.Modal.getInstance(document.getElementById('exportModal'));
    modal.hide();
    
    // Show loading indicator
    const exportBtn = event.target.closest('button');
    const originalContent = exportBtn.innerHTML;
    exportBtn.innerHTML = '<i class="ri-loader-4-line spin me-2"></i>Exporting...';
    exportBtn.disabled = true;
    
    // Simulate export process
    setTimeout(() => {
        // Get table data
        const table = document.querySelector('.sales-table');
        const rows = table.querySelectorAll('tbody tr');
        let data = [];
        
        // Headers
        const headers = ['Date', 'Station', 'Pump', 'Product', 'Attendant', 'Opening Metre', 'Closing Metre', 'RTT', 'Net Qty', 'Quantity', 'Rate', 'Amount', 'Variance'];
        data.push(headers);
        
        // Table rows
        rows.forEach(row => {
            const rowData = [];
            for (let i = 0; i < 13; i++) {
                rowData.push(row.cells[i].textContent);
            }
            data.push(rowData);
        });
        
        // Add total row
        const totalAmount = document.getElementById('totalAmount').textContent;
        data.push(['', '', '', '', '', '', '', '', '', '', '', 'TOTAL: ' + totalAmount, '']);
        
        // Create and download file based on format
        let filename = `sales_export_${new Date().toISOString().split('T')[0]}`;
        let content, mimeType;
        
        switch(format) {
            case 'csv':
                content = data.map(row => row.join(',')).join('\n');
                mimeType = 'text/csv';
                filename += '.csv';
                break;
            case 'excel':
                content = data.map(row => row.join('\t')).join('\n');
                mimeType = 'application/vnd.ms-excel';
                filename += '.xls';
                break;
            case 'pdf':
                // For PDF, we'll just show a message for now
                alert('PDF export would be implemented with a server-side library like jsPDF or TCPDF');
                exportBtn.innerHTML = originalContent;
                exportBtn.disabled = false;
                return;
        }
        
        // Download file
        const blob = new Blob([content], { type: mimeType });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
        
        // Reset button
        exportBtn.innerHTML = originalContent;
        exportBtn.disabled = false;
        
        // Show success message
        showNotification(`Sales data exported successfully as ${format.toUpperCase()}`, 'success');
    }, 1500);
}

// Show notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
    notification.style.zIndex = '9999';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}
</script>
@endpush