@extends('layouts.vertical', [
    'page_title' => 'All Accounts',
    'mode' => session('theme_mode', 'light'),
])

@section('css')
    <style>
        :root {
            --accounts-gradient-start: #031739;
            --accounts-gradient-mid: #0a3a8a;
            --accounts-gradient-end: #041a45;
            --accounts-surface: #f6f8ff;
            --accounts-panel-bg: #ffffff;
            --accounts-text: #0a1d44;
            --accounts-muted: rgba(9, 33, 78, 0.6);
            --accounts-accent: #ff7a1a;
            --accounts-accent-end: #ffb347;
            --accounts-border-soft: rgba(12, 38, 96, 0.08);
            --accounts-border-strong: rgba(12, 38, 96, 0.18);
            --accounts-shadow-card: 0 26px 44px rgba(3, 26, 67, 0.34);
            --accounts-shadow-panel: 0 18px 42px rgba(7, 32, 86, 0.14);
            --accounts-shadow-button: 0 12px 22px rgba(255, 135, 54, 0.32);
        }

        .accounts-page {
            background: linear-gradient(180deg, rgba(4, 20, 56, 0.05) 0%, rgba(13, 48, 119, 0.1) 100%);
            min-height: 100vh;
            padding: 2.6rem 2.4rem 3rem;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            color: var(--accounts-text);
        }

        .accounts-card {
            background: linear-gradient(135deg, var(--accounts-gradient-start) 0%, var(--accounts-gradient-mid) 100%);
            padding: 1px;
            border-radius: 24px;
            box-shadow: var(--accounts-shadow-card);
        }

        .accounts-card__inner {
            background: var(--accounts-surface);
            border-radius: 23px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .accounts-card__header {
            background: linear-gradient(94deg, rgba(3, 23, 63, 0.96) 0%, rgba(10, 58, 138, 0.98) 55%, rgba(3, 23, 63, 0.96) 100%);
            padding: 1.9rem 2.6rem;
            color: #ffffff;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1.4rem;
            flex-wrap: wrap;
        }

        .accounts-card__header-main {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .accounts-card__title {
            margin: 0;
            font-size: 1.7rem;
            font-weight: 700;
            letter-spacing: 1.1px;
            text-transform: uppercase;
        }

        .accounts-card__subtitle {
            margin: 0;
            font-size: 0.86rem;
            letter-spacing: 0.45px;
            text-transform: uppercase;
            color: rgba(232, 241, 255, 0.82);
        }

        .accounts-breadcrumb ol {
            display: flex;
            gap: 0.65rem;
            padding: 0;
            margin: 0;
            list-style: none;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.32em;
            color: rgba(232, 241, 255, 0.65);
        }

        .accounts-breadcrumb a {
            color: inherit;
            text-decoration: none;
        }

        .accounts-card__actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .accounts-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.62rem 1.4rem;
            border-radius: 12px;
            font-size: 0.82rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .accounts-btn--primary {
            background: linear-gradient(88deg, var(--accounts-accent) 0%, var(--accounts-accent-end) 100%);
            color: #0a1d44;
            box-shadow: var(--accounts-shadow-button);
        }

        .accounts-btn--primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 26px rgba(255, 135, 54, 0.4);
        }

        .accounts-panel {
            margin: 2.4rem 2.4rem 0;
            background: var(--accounts-panel-bg);
            border-radius: 20px;
            padding: 1.9rem 2.2rem;
            box-shadow: var(--accounts-shadow-panel);
            border: 1px solid var(--accounts-border-soft);
        }

        .accounts-panel--summary {
            margin-top: 2.4rem;
        }

        .accounts-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.6rem;
        }

        .accounts-summary__item {
            position: relative;
            border-radius: 22px;
            padding: 1.6rem 1.8rem;
            color: #ffffff;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
            background: linear-gradient(120deg, rgba(4, 31, 94, 0.92) 0%, rgba(16, 90, 203, 0.85) 52%, rgba(4, 31, 94, 0.9) 100%);
            box-shadow: 0 20px 42px rgba(7, 37, 96, 0.28);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .accounts-summary__item::after {
            content: '';
            position: absolute;
            inset: 1px;
            border-radius: 21px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            pointer-events: none;
        }

        .accounts-summary__item[data-variant="total"] {
            background: linear-gradient(118deg, rgba(15, 60, 130, 0.95) 0%, rgba(48, 141, 255, 0.9) 58%, rgba(5, 30, 78, 0.9) 100%);
        }

        .accounts-summary__item[data-variant="bank"] {
            background: linear-gradient(118deg, rgba(51, 9, 94, 0.95) 0%, rgba(171, 78, 224, 0.88) 54%, rgba(29, 9, 59, 0.9) 100%);
        }

        .accounts-summary__item[data-variant="cash"] {
            background: linear-gradient(118deg, rgba(100, 27, 126, 0.95) 0%, rgba(221, 96, 176, 0.9) 54%, rgba(51, 8, 67, 0.9) 100%);
        }

        .accounts-summary__item[data-variant="stations"] {
            background: linear-gradient(118deg, rgba(13, 90, 82, 0.95) 0%, rgba(68, 200, 188, 0.85) 54%, rgba(10, 66, 60, 0.9) 100%);
        }

        .accounts-summary__meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 1;
        }

        .accounts-summary__label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.48px;
            color: rgba(232, 242, 255, 0.8);
        }

        .accounts-summary__icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.28);
        }

        .accounts-summary__value {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 0.42px;
            color: #ffffff;
        }

        .accounts-summary__footer {
            font-size: 0.68rem;
            letter-spacing: 0.45px;
            text-transform: uppercase;
            color: rgba(232, 244, 255, 0.8);
        }

        .accounts-toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1.1rem;
        }

        .accounts-toolbar__search {
            flex: 1 1 280px;
        }

        .accounts-search {
            position: relative;
            display: flex;
            align-items: center;
            background: #f5f8ff;
            border-radius: 12px;
            border: 1px solid var(--accounts-border-strong);
            padding: 0.55rem 0.75rem 0.55rem 2.6rem;
        }

        .accounts-search i {
            position: absolute;
            left: 1.1rem;
            color: #2b6def;
            font-size: 1.1rem;
        }

        .accounts-search input {
            width: 100%;
            border: none;
            background: transparent;
            font-size: 0.86rem;
            color: var(--accounts-text);
        }

        .accounts-search input:focus {
            outline: none;
        }

        .accounts-toolbar__filters {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .accounts-filter-chip {
            border: 1px solid rgba(43, 109, 239, 0.35);
            background: #ffffff;
            color: #2b6def;
            font-size: 0.78rem;
            font-weight: 600;
            border-radius: 999px;
            padding: 0.4rem 1.1rem;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            transition: all 0.2s ease;
        }

        .accounts-filter-chip:hover,
        .accounts-filter-chip.is-active {
            background: #2b6def;
            color: #ffffff;
            box-shadow: 0 12px 24px rgba(43, 109, 239, 0.25);
        }

        .accounts-panel--table {
            margin-top: 2rem;
        }

        .accounts-table-shell {
            overflow-x: auto;
        }

        .accounts-table-container {
            border-radius: 18px;
            border: 1px solid rgba(16, 44, 98, 0.12);
            overflow: hidden;
            background: #ffffff;
        }

        .accounts-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 960px;
            color: var(--accounts-text);
            font-size: 0.78rem;
        }

        .accounts-table th {
            background: #0b2e6f;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.45px;
            font-weight: 600;
            padding: 0.75rem 0.85rem;
            text-align: left;
            border-right: 1px solid rgba(255, 255, 255, 0.12);
            white-space: nowrap;
        }

        .accounts-table th:last-child {
            border-right: none;
        }

        .accounts-table tbody tr:nth-child(odd) {
            background: #ffffff;
        }

        .accounts-table tbody tr:nth-child(even) {
            background: #f5f7ff;
        }

        .accounts-table tbody tr:hover {
            background: rgba(43, 109, 239, 0.1);
        }

        .accounts-cell {
            padding: 0.78rem 0.85rem;
            border: 1px solid rgba(16, 44, 98, 0.12);
            vertical-align: middle;
        }

        .accounts-cell--highlight {
            font-weight: 600;
            color: #0b1f4f;
        }

        .accounts-cell--muted {
            color: rgba(9, 33, 78, 0.7);
        }

        .accounts-row--active .accounts-cell--highlight,
        .accounts-row--active .accounts-cell--highlight a {
            background: #1b57b5;
            color: #ffffff;
        }

        .accounts-code {
            color: inherit;
            text-decoration: none;
        }

        .accounts-code:hover {
            text-decoration: underline;
        }

        .accounts-action-cell {
            text-align: center;
            width: 90px;
        }

        .accounts-action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: rgba(43, 109, 239, 0.16);
            color: #2b6def;
            text-decoration: none;
            transition: background 0.2s ease, color 0.2s ease;
            font-size: 1.05rem;
        }

        .accounts-action-btn:hover {
            background: #2b6def;
            color: #ffffff;
        }

        .add-account-modal {
            border: none;
            box-shadow: 0 24px 52px rgba(9, 24, 64, 0.32);
            border-radius: 18px;
            overflow: hidden;
        }

        .add-account-modal .modal-header {
            background: linear-gradient(95deg, #0b2e6f 0%, #1f63c7 100%);
            color: #ffffff;
            align-items: center;
            justify-content: center;
            padding: 1.4rem 1.8rem;
            border-bottom: 4px solid rgba(255, 255, 255, 0.18);
        }

        .add-account-modal .modal-title {
            font-size: 1.28rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .add-account-modal .modal-body {
            background: #ffffff;
            padding: 2rem;
        }

        .modal-action-buttons {
            gap: 1.2rem !important;
        }

        .modal-secondary-btn,
        .modal-danger-btn {
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.12em;
            border-radius: 10px;
            padding: 0.68rem 1.9rem;
            border: none;
            min-width: 170px;
            box-shadow: 0 12px 24px rgba(15, 36, 76, 0.22);
        }

        .modal-secondary-btn {
            background: linear-gradient(118deg, #ffb74d 0%, #fb8c00 100%);
            color: #1b1b1b;
        }

        .modal-secondary-btn:hover {
            background: linear-gradient(118deg, #ffa02f 0%, #f57c00 100%);
        }

        .modal-danger-btn {
            background: linear-gradient(118deg, #ff4d4f 0%, #d32f2f 100%);
            color: #ffffff;
        }

        .modal-danger-btn:hover {
            background: linear-gradient(118deg, #ff3b3d 0%, #c62828 100%);
        }

        .summary-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid rgba(12, 38, 96, 0.12);
            box-shadow: 0 18px 36px rgba(12, 34, 80, 0.12);
            overflow: hidden;
        }

        .summary-card + .summary-card {
            margin-top: 1.6rem;
        }

        .summary-card-header {
            padding: 1rem 1.6rem;
            background: #f5f7fb;
            color: #1f63c7;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            border-bottom: 2px solid rgba(12, 38, 96, 0.12);
        }

        .summary-card-body {
            padding: 1.4rem 1.6rem 1.2rem;
        }

        .summary-row {
            display: grid;
            grid-template-columns: 150px 1fr 150px 1fr;
            gap: 1rem 1.25rem;
            align-items: center;
            margin-bottom: 1.2rem;
        }

        .summary-row:last-child {
            margin-bottom: 0;
        }

        .summary-label {
            font-weight: 700;
            color: #1f2937;
            text-transform: uppercase;
            font-size: 0.84rem;
        }

        .summary-field .form-control,
        .summary-field .form-select {
            border-radius: 10px;
            border: 1px solid rgba(12, 38, 96, 0.18);
            background: #fdfdfd;
            height: 44px;
            font-weight: 600;
            color: #103a8f;
        }

        .summary-field .form-control:focus,
        .summary-field .form-select:focus {
            border-color: #1f63c7;
            box-shadow: 0 0 0 3px rgba(31, 99, 199, 0.2);
        }

        .site-selection {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            border: 1px solid rgba(12, 38, 96, 0.14);
            border-radius: 12px;
            overflow: hidden;
        }

        .site-list-header {
            background: #a8ce3b;
            color: #1f2a16;
            padding: 0.7rem 1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .site-list {
            min-height: 220px;
            border: none;
            border-radius: 0;
            background: #f2fae6;
            color: #1f2937;
            font-weight: 600;
            padding: 0;
        }

        .site-list option {
            padding: 0.65rem 1rem;
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
        }

        .add-account-modal .modal-footer {
            padding: 1.25rem 1.6rem;
            background: #f1f4f9;
            border-top: 1px solid rgba(12, 38, 96, 0.12);
        }

        @media (max-width: 1200px) {
            .accounts-panel {
                margin: 2rem 1.8rem 0;
            }
        }

        @media (max-width: 992px) {
            .accounts-panel {
                margin: 1.8rem 1.6rem 0;
                padding: 1.6rem 1.6rem;
            }

            .accounts-toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .accounts-toolbar__filters {
                justify-content: flex-start;
            }
        }

        @media (max-width: 768px) {
            .accounts-page {
                padding: 2rem 1.6rem 2.4rem;
            }

            .accounts-card__header {
                padding: 1.6rem 1.8rem;
            }

            .accounts-panel {
                margin: 1.6rem 1.2rem 0;
                padding: 1.5rem 1.3rem;
            }

            .accounts-summary {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .accounts-page {
                padding: 1.6rem 1rem 2rem;
            }

            .accounts-card {
                border-radius: 20px;
            }

            .accounts-btn {
                width: 100%;
            }

            .summary-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div class="accounts-page">
        <div class="accounts-card">
            <div class="accounts-card__inner">
                <div class="accounts-card__header">
                    <div class="accounts-card__header-main">
                        <h2 class="accounts-card__title">Accounts Control Center</h2>
                        <p class="accounts-card__subtitle">Review balances, manage bank details and control access across stations.</p>
                        <nav aria-label="Breadcrumb" class="accounts-breadcrumb">
                            <ol>
                                <li><a href="#">Dashboard</a></li>
                                <li aria-current="page">All Accounts</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="accounts-card__actions">
                        <button type="button" class="accounts-btn accounts-btn--primary" data-bs-toggle="modal" data-bs-target="#addAccountModal">
                            <i class="ri-add-line"></i>
                            <span>New Account</span>
                        </button>
                    </div>
                </div>

                <div class="accounts-panel accounts-panel--summary">
                    <div class="accounts-summary">
                        <div class="accounts-summary__item" data-variant="total">
                            <div class="accounts-summary__meta">
                                <span class="accounts-summary__label">Total Accounts</span>
                                <span class="accounts-summary__icon">
                                    <i class="ri-folder-open-line"></i>
                                </span>
                            </div>
                            <div class="accounts-summary__value">12</div>
                            <div class="accounts-summary__footer">Across all stations</div>
                        </div>
                        <div class="accounts-summary__item" data-variant="bank">
                            <div class="accounts-summary__meta">
                                <span class="accounts-summary__label">Bank Accounts</span>
                                <span class="accounts-summary__icon">
                                    <i class="ri-bank-line"></i>
                                </span>
                            </div>
                            <div class="accounts-summary__value">4</div>
                            <div class="accounts-summary__footer">Active settlements</div>
                        </div>
                        <div class="accounts-summary__item" data-variant="cash">
                            <div class="accounts-summary__meta">
                                <span class="accounts-summary__label">Cash Accounts</span>
                                <span class="accounts-summary__icon">
                                    <i class="ri-hand-coin-line"></i>
                                </span>
                            </div>
                            <div class="accounts-summary__value">8</div>
                            <div class="accounts-summary__footer">Customer &amp; sales</div>
                        </div>
                        <div class="accounts-summary__item" data-variant="stations">
                            <div class="accounts-summary__meta">
                                <span class="accounts-summary__label">Stations Covered</span>
                                <span class="accounts-summary__icon">
                                    <i class="ri-map-pin-line"></i>
                                </span>
                            </div>
                            <div class="accounts-summary__value">6</div>
                            <div class="accounts-summary__footer">Navrongo to Paga</div>
                        </div>
                    </div>
                </div>

                <div class="accounts-panel accounts-panel--filters">
                    <div class="accounts-toolbar">
                        <div class="accounts-toolbar__search">
                            <div class="accounts-search">
                                <i class="ri-search-line"></i>
                                <input type="text" placeholder="Search by name, code or station...">
                            </div>
                        </div>
                        <div class="accounts-toolbar__filters">
                            <button type="button" class="accounts-filter-chip is-active">All</button>
                            <button type="button" class="accounts-filter-chip">Bank</button>
                            <button type="button" class="accounts-filter-chip">Cash</button>
                            <button type="button" class="accounts-filter-chip">Mobile Money</button>
                        </div>
                    </div>
                </div>

                <div class="accounts-panel accounts-panel--table">
                    <div class="accounts-table-shell">
                        <div class="accounts-table-container">
                            <table class="accounts-table">
                                <thead>
                                    <tr>
                                        <th>Account Number</th>
                                        <th>Account Name</th>
                                        <th>Account Type</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="accounts-row accounts-row--active">
                                        <td class="accounts-cell accounts-cell--highlight">
                                            <a href="#" class="accounts-code">ADB-108101057689201</a>
                                        </td>
                                        <td class="accounts-cell accounts-cell--highlight text-uppercase">YASS PETROLEUM COMPANY LIMITED-ADB</td>
                                        <td class="accounts-cell accounts-cell--muted">Bank</td>
                                        <td class="accounts-cell accounts-cell--highlight text-uppercase">YASS PETROLEUM COMPANY LIMITED-ADB</td>
                                        <td class="accounts-action-cell">
                                            <a href="#" class="accounts-action-btn" aria-label="View account">
                                                <i class="ri-eye-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="accounts-row">
                                        <td class="accounts-cell">AMOAKO-Customer Payments</td>
                                        <td class="accounts-cell">AMOAKO-Customer Payments</td>
                                        <td class="accounts-cell">Cash</td>
                                        <td class="accounts-cell">AMOAKO-Customer Payments Account</td>
                                        <td class="accounts-action-cell">
                                            <a href="#" class="accounts-action-btn" aria-label="View account">
                                                <i class="ri-eye-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="accounts-row">
                                        <td class="accounts-cell">AMOAKO-Fuel Sales</td>
                                        <td class="accounts-cell">AMOAKO-Fuel Sales</td>
                                        <td class="accounts-cell">Cash</td>
                                        <td class="accounts-cell">AMOAKO-Fuel Sales Account</td>
                                        <td class="accounts-action-cell">
                                            <a href="#" class="accounts-action-btn" aria-label="View account">
                                                <i class="ri-eye-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="accounts-row">
                                        <td class="accounts-cell">AMOAKO-Inventory Sales</td>
                                        <td class="accounts-cell">AMOAKO-Inventory Sales</td>
                                        <td class="accounts-cell">Cash</td>
                                        <td class="accounts-cell">AMOAKO-Inventory Sales Account</td>
                                        <td class="accounts-action-cell">
                                            <a href="#" class="accounts-action-btn" aria-label="View account">
                                                <i class="ri-eye-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="accounts-row">
                                        <td class="accounts-cell">BAMVIM-Customer Payments</td>
                                        <td class="accounts-cell">BAMVIM-Customer Payments</td>
                                        <td class="accounts-cell">Cash</td>
                                        <td class="accounts-cell">BAMVIM-Customer Payments Account</td>
                                        <td class="accounts-action-cell">
                                            <a href="#" class="accounts-action-btn" aria-label="View account">
                                                <i class="ri-eye-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="accounts-row">
                                        <td class="accounts-cell">BAMVIM-Fuel Sales</td>
                                        <td class="accounts-cell">BAMVIM-Fuel Sales</td>
                                        <td class="accounts-cell">Cash</td>
                                        <td class="accounts-cell">BAMVIM-Fuel Sales Account</td>
                                        <td class="accounts-action-cell">
                                            <a href="#" class="accounts-action-btn" aria-label="View account">
                                                <i class="ri-eye-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="accounts-row">
                                        <td class="accounts-cell">BAMVIM-Inventory Sales</td>
                                        <td class="accounts-cell">BAMVIM-Inventory Sales</td>
                                        <td class="accounts-cell">Cash</td>
                                        <td class="accounts-cell">BAMVIM-Inventory Sales Account</td>
                                        <td class="accounts-action-cell">
                                            <a href="#" class="accounts-action-btn" aria-label="View account">
                                                <i class="ri-eye-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="accounts-row">
                                        <td class="accounts-cell">CBG-2157316100001</td>
                                        <td class="accounts-cell">YASS PETROLEUM COMPANY LIMITED-CBG</td>
                                        <td class="accounts-cell">Bank</td>
                                        <td class="accounts-cell">YASS PETROLEUM COMPANY LIMITED</td>
                                        <td class="accounts-action-cell">
                                            <a href="#" class="accounts-action-btn" aria-label="View account">
                                                <i class="ri-eye-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="accounts-row">
                                        <td class="accounts-cell">KINTAMPO-Customer Payments</td>
                                        <td class="accounts-cell">KINTAMPO-Customer Payments</td>
                                        <td class="accounts-cell">Cash</td>
                                        <td class="accounts-cell">KINTAMPO-Customer Payments Account</td>
                                        <td class="accounts-action-cell">
                                            <a href="#" class="accounts-action-btn" aria-label="View account">
                                                <i class="ri-eye-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="accounts-row">
                                        <td class="accounts-cell">KINTAMPO-Fuel Sales</td>
                                        <td class="accounts-cell">KINTAMPO-Fuel Sales</td>
                                        <td class="accounts-cell">Cash</td>
                                        <td class="accounts-cell">KINTAMPO-Fuel Sales Account</td>
                                        <td class="accounts-action-cell">
                                            <a href="#" class="accounts-action-btn" aria-label="View account">
                                                <i class="ri-eye-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="accounts-row">
                                        <td class="accounts-cell">KINTAMPO-Inventory Sales</td>
                                        <td class="accounts-cell">KINTAMPO-Inventory Sales</td>
                                        <td class="accounts-cell">Cash</td>
                                        <td class="accounts-cell">KINTAMPO-Inventory Sales Account</td>
                                        <td class="accounts-action-cell">
                                            <a href="#" class="accounts-action-btn" aria-label="View account">
                                                <i class="ri-eye-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Account Modal -->
    <div class="modal fade" id="addAccountModal" tabindex="-1" aria-labelledby="addAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content add-account-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAccountModalLabel">View New Account</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="modal-action-buttons d-flex flex-wrap gap-3 justify-content-center mb-4">
                            <button type="button" class="modal-secondary-btn">Edit Account</button>
                            <button type="button" class="modal-secondary-btn">Edit Station</button>
                            <button type="button" class="modal-danger-btn">Delete</button>
                        </div>
                        <div class="row g-4">
                            <div class="col-lg-8">
                                <div class="summary-card">
                                    <div class="summary-card-header">Account</div>
                                    <div class="summary-card-body">
                                        <div class="summary-row">
                                            <label class="summary-label">Account Type:</label>
                                            <div class="summary-field">
                                                <select class="form-select" name="account_type">
                                                    <option value="">Select Account Type</option>
                                                    <option value="bank">Bank</option>
                                                    <option value="cash">Cash</option>
                                                    <option value="mobile_money">Mobile Money</option>
                                                </select>
                                            </div>
                                            <label class="summary-label">Account Code:</label>
                                            <div class="summary-field">
                                                <input type="text" name="account_code" class="form-control" placeholder="e.g. ADB-108101057689201">
                                            </div>
                                        </div>
                                        <div class="summary-row">
                                            <label class="summary-label">Account Name:</label>
                                            <div class="summary-field">
                                                <input type="text" name="account_name" class="form-control" placeholder="Enter account name">
                                            </div>
                                            <label class="summary-label">Description:</label>
                                            <div class="summary-field">
                                                <input type="text" name="description" class="form-control" placeholder="Enter description">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="summary-card">
                                    <div class="summary-card-header">Bank</div>
                                    <div class="summary-card-body">
                                        <div class="summary-row">
                                            <label class="summary-label">Bank:</label>
                                            <div class="summary-field">
                                                <input type="text" name="bank_name" class="form-control" placeholder="Enter bank name">
                                            </div>
                                            <label class="summary-label">Account No:</label>
                                            <div class="summary-field">
                                                <input type="text" name="bank_account_no" class="form-control" placeholder="Enter account number">
                                            </div>
                                        </div>
                                        <div class="summary-row">
                                            <label class="summary-label">Bank Branch:</label>
                                            <div class="summary-field">
                                                <input type="text" name="bank_branch" class="form-control" placeholder="Enter branch">
                                            </div>
                                            <div class="summary-label"></div>
                                            <div class="summary-field"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="summary-card">
                                    <div class="summary-card-header">Site</div>
                                    <div class="summary-card-body">
                                        <div class="site-selection">
                                            <div class="site-list-header">Station</div>
                                            <select multiple class="form-select site-list" id="site_list" name="stations[]">
                                                <option value="navrongo-main">NAVRONGO-MAIN</option>
                                                <option value="paga-annex">PAGA-ANNEX</option>
                                                <option value="larabanga">LARABANGA</option>
                                                <option value="wapuli">WAPULI</option>
                                                <option value="bamvim">BAMVIM</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection