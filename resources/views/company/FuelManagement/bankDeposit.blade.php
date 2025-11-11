@extends('layouts.vertical', [
    'page_title' => 'Bank Deposits',
    'mode' => session('theme_mode', 'light'),
])

@php
    $defaultStations = [
        ['value' => 'Wiaga', 'label' => 'Wiaga'],
        ['value' => 'Wapuli', 'label' => 'Wapuli'],
        ['value' => 'Pwalugu', 'label' => 'Pwalugu'],
        ['value' => 'Paga Annex', 'label' => 'Paga Annex'],
        ['value' => 'Bamvin', 'label' => 'Bamvin'],
        ['value' => 'Larabanga', 'label' => 'Larabanga'],
        ['value' => 'Kintampo', 'label' => 'Kintampo'],
        ['value' => 'Amoako', 'label' => 'Amoako'],
        ['value' => 'Navrongo-2', 'label' => 'Navrongo-2'],
        ['value' => 'Navrongo Main', 'label' => 'Navrongo Main'],
        ['value' => 'Bugubele', 'label' => 'Bugubele'],
    ];

    if (! isset($sites) || empty($sites)) {
        $sites = $defaultStations;
    }
@endphp

@section('css')
    <style>
        :root {
            --bank-gradient-start: #031739;
            --bank-gradient-mid: #083b8a;
            --bank-gradient-end: #031739;
            --bank-surface: #f6f8ff;
            --bank-panel-bg: #ffffff;
            --bank-text: #0a1d44;
            --bank-text-light: rgba(232, 241, 255, 0.85);
            --bank-muted: rgba(9, 31, 74, 0.58);
            --bank-accent: #ff7a1a;
            --bank-accent-end: #ffb347;
            --bank-border-soft: rgba(12, 38, 96, 0.08);
            --bank-border-strong: rgba(12, 36, 79, 0.18);
            --bank-shadow-card: 0 26px 44px rgba(3, 26, 67, 0.34);
            --bank-shadow-panel: 0 18px 42px rgba(7, 32, 86, 0.14);
            --bank-shadow-button: 0 12px 22px rgba(255, 135, 54, 0.32);
        }

        .bank-deposits-page {
            background: linear-gradient(180deg, rgba(4, 20, 56, 0.04) 0%, rgba(13, 48, 119, 0.08) 100%);
            min-height: 100vh;
            padding: 2.6rem 2.4rem 3rem;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            color: var(--bank-text);
        }

        .bank-card {
            background: linear-gradient(135deg, var(--bank-gradient-start) 0%, var(--bank-gradient-mid) 100%);
            padding: 1px;
            border-radius: 24px;
            box-shadow: var(--bank-shadow-card);
        }

        .bank-card__inner {
            background: var(--bank-surface);
            border-radius: 23px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .bank-card__header {
            background: linear-gradient(94deg, rgba(3, 23, 63, 0.96) 0%, rgba(10, 58, 138, 0.98) 55%, rgba(3, 23, 63, 0.96) 100%);
            padding: 1.8rem 2.6rem;
            color: #ffffff;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1.2rem;
            flex-wrap: wrap;
        }

        .bank-card__header-main {
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
        }

        .bank-card__title {
            margin: 0;
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: 1.1px;
            text-transform: uppercase;
        }

        .bank-card__subtitle {
            margin: 0;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.45px;
            color: var(--bank-text-light);
        }

        .bank-card__actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .bank-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            padding: 0.6rem 1.3rem;
            border-radius: 12px;
            font-size: 0.78rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .bank-btn--primary {
            background: linear-gradient(88deg, var(--bank-accent) 0%, var(--bank-accent-end) 100%);
            color: #0a1d44;
            box-shadow: var(--bank-shadow-button);
        }

        .bank-btn--primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 26px rgba(255, 135, 54, 0.4);
        }

        .bank-btn--light {
            background: transparent;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.45);
        }

        .bank-btn--light:hover {
            transform: translateY(-1px);
            background: rgba(255, 255, 255, 0.12);
        }

        .bank-panel {
            margin: 2.4rem 2.4rem 0;
            background: var(--bank-panel-bg);
            border-radius: 20px;
            padding: 1.8rem 2rem;
            box-shadow: var(--bank-shadow-panel);
            border: 1px solid var(--bank-border-soft);
        }

        .bank-panel:first-of-type {
            margin-top: 2.4rem;
        }

        .bank-toolbar {
            display: flex;
            flex-direction: column;
            gap: 1.4rem;
        }

        .bank-toolbar__row {
            display: flex;
            flex-wrap: wrap;
            gap: 1.1rem;
            justify-content: space-between;
            align-items: center;
        }

        .bank-filter-group {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            flex-wrap: wrap;
        }

        .bank-filter {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .bank-filter label {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.48px;
            color: #0a2048;
            font-weight: 600;
        }

        .bank-filter input,
        .bank-filter select,
        .bank-search input,
        .bank-page-size select {
            min-width: 180px;
            padding: 0.58rem 0.75rem;
            border-radius: 10px;
            border: 1px solid var(--bank-border-strong);
            background: #f5f8ff;
            font-size: 0.84rem;
            color: var(--bank-text);
            transition: border 0.2s ease, box-shadow 0.2s ease;
        }

        .bank-filter input:focus,
        .bank-filter select:focus,
        .bank-search input:focus,
        .bank-page-size select:focus {
            outline: none;
            border-color: #2b6def;
            box-shadow: 0 0 0 3px rgba(43, 109, 239, 0.2);
        }

        .bank-toolbar__actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .bank-search {
            position: relative;
            width: 100%;
            max-width: 420px;
        }

        .bank-search input {
            width: 100%;
            padding-left: 2.6rem;
        }

        .bank-search i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #2b6def;
            font-size: 1rem;
        }

        .bank-panel--table {
            margin-top: 2rem;
        }

        .bank-table-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.8rem;
            color: var(--bank-muted);
            font-size: 0.75rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            margin-bottom: 1.2rem;
        }

        .bank-page-size {
            display: flex;
            align-items: center;
            gap: 0.55rem;
        }

        .bank-meta-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .bank-page-size span {
            font-weight: 600;
            color: #0a2048;
        }

        .bank-btn--compact {
            padding: 0.45rem 1rem;
            font-size: 0.72rem;
        }

        .bank-table-container {
            border-radius: 18px;
            border: 1px solid rgba(16, 44, 98, 0.12);
            overflow: hidden;
            background: #ffffff;
        }

        .bank-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 960px;
            color: var(--bank-text);
            font-size: 0.78rem;
        }

        .bank-table thead th {
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

        .bank-table thead th:last-child {
            border-right: none;
        }

        .bank-table tbody tr:nth-child(odd) {
            background: #ffffff;
        }

        .bank-table tbody tr:nth-child(even) {
            background: #f5f7ff;
        }

        .bank-table tbody tr:hover {
            background: rgba(43, 109, 239, 0.1);
        }

        .bank-table tbody td {
            padding: 0.72rem 0.8rem;
            border: 1px solid rgba(16, 44, 98, 0.12);
            vertical-align: middle;
        }

        .bank-table tbody td:last-child {
            width: 80px;
        }

        .bank-account {
            display: flex;
            flex-direction: column;
            gap: 0.1rem;
        }

        .bank-account span:first-child {
            font-weight: 600;
        }

        .bank-actions {
            display: inline-flex;
            gap: 0.4rem;
            align-items: center;
        }

        .bank-actions span {
            font-size: 1.25rem;
        }

        .bank-empty {
            padding: 2rem 1rem;
            text-align: center;
            color: var(--bank-muted);
            font-size: 0.9rem;
            letter-spacing: 0.06em;
        }

        @media (max-width: 1200px) {
            .bank-panel {
                margin: 2rem 1.8rem 0;
            }
        }

        @media (max-width: 992px) {
            .bank-toolbar__row {
                flex-direction: column;
                align-items: stretch;
            }

            .bank-toolbar__actions {
                width: 100%;
                justify-content: flex-start;
            }

            .bank-meta-actions {
                justify-content: flex-start;
            }
        }

        @media (max-width: 768px) {
            .bank-deposits-page {
                padding: 2rem 1.6rem 2.4rem;
            }

            .bank-card__header {
                padding: 1.6rem 1.8rem;
            }

            .bank-panel {
                margin: 1.6rem 1.4rem 0;
                padding: 1.5rem 1.4rem;
            }

            .bank-filter input,
            .bank-filter select {
                min-width: 140px;
            }

            .bank-search {
                max-width: 100%;
            }
        }

        @media (max-width: 576px) {
            .bank-deposits-page {
                padding: 1.6rem 1rem 2rem;
            }

            .bank-card {
                border-radius: 20px;
            }

            .bank-panel {
                margin: 1.4rem 1rem 0;
                padding: 1.4rem 1.1rem;
            }

            .bank-card__title {
                font-size: 1.35rem;
            }

            .bank-btn {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="bank-deposits-page">
        <div class="bank-card">
            <div class="bank-card__inner">
                <div class="bank-card__header">
                    <div class="bank-card__header-main">
                        <h2 class="bank-card__title">Bank Deposits</h2>
                        <p class="bank-card__subtitle">Monitor daily cash ups and reconciliation</p>
                    </div>
                    <div class="bank-card__actions">
                        <button type="button" class="bank-btn bank-btn--primary" data-bs-toggle="modal" data-bs-target="#createDepositModal">
                            <i class="ri-add-line"></i>
                            Add Deposit
                        </button>
                    </div>
                </div>

                <div class="bank-panel bank-panel--filters">
                    <div class="bank-toolbar">
                        <form method="GET" action="{{ url()->current() }}" class="w-100" id="filtersForm">
                            <div class="bank-toolbar__row">
                                <div class="bank-filter-group">
                                    <div class="bank-filter">
                                        <label for="fromDate">From</label>
                                        <input type="date" id="fromDate" name="from" value="{{ request('from') }}">
                                    </div>
                                    <div class="bank-filter">
                                        <label for="toDate">To</label>
                                        <input type="date" id="toDate" name="to" value="{{ request('to') }}">
                                    </div>
                                    <div class="bank-filter">
                                        <label for="siteSelect">Station</label>
                                        <select id="siteSelect" name="site">
                                            <option value="">All Stations</option>
                                            @foreach(($sites ?? []) as $site)
                                                <option value="{{ $site['value'] ?? $site }}" @selected(request('site') == ($site['value'] ?? $site))>
                                                    {{ $site['label'] ?? $site }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="bank-toolbar__actions">
                                    <button type="submit" class="bank-btn bank-btn--primary">Search by Date</button>
                                    <button type="button" class="bank-btn bank-btn--light" data-bs-toggle="modal" data-bs-target="#exportDepositModal">
                                        <i class="ri-download-2-line"></i>
                                        Export
                                    </button>
                                </div>
                            </div>
                            <div class="bank-toolbar__row">
                                <div class="bank-search">
                                    <i class="ri-search-line"></i>
                                    <input type="search" name="search" placeholder="Enter text to search..." value="{{ request('search') }}">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @php
                    $isPaginator = isset($deposits) && $deposits instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
                    $providedDeposits = $isPaginator ? $deposits : collect($deposits ?? []);
                    $hasProvidedDeposits = $providedDeposits->count() > 0;

                    if (! $hasProvidedDeposits) {
                        $displayDeposits = collect([
                            [
                                'transaction_date' => '31-Oct-2025',
                                'sales_date' => '30-Oct-2025',
                                'station' => ['name' => 'NAVRONGO-2'],
                                'site' => ['name' => 'NAVRONGO-2'],
                                'account_name' => 'CBG-21573161100001',
                                'account_number' => 'DAILY SALES',
                                'amount' => 'GHS21,000.00',
                                'deposit_by' => 'Samuel Kpentey',
                                'narration' => 'Daily sales banking for cashiers',
                                'details' => 'Daily sales banking for cashiers',
                                'payment_mode' => 'Cash',
                                'transaction_id' => 'B251031000130',
                                'transaction_number' => 'B251031000130',
                                'view_url' => '#',
                            ],
                            [
                                'transaction_date' => '31-Oct-2025',
                                'sales_date' => '28-Oct-2025',
                                'station' => ['name' => 'PAGA-ANNEX'],
                                'site' => ['name' => 'PAGA-ANNEX'],
                                'account_name' => 'ADB-108101057689201',
                                'account_number' => 'Daily Sales',
                                'amount' => 'GHS13,840.50',
                                'deposit_by' => 'Millicent Bawa',
                                'narration' => 'Sales close-out approved',
                                'details' => 'Sales close-out approved',
                                'payment_mode' => 'Cash',
                                'transaction_id' => 'PGA-281005',
                                'transaction_number' => 'PGA-281005',
                                'view_url' => '#',
                            ],
                            [
                                'transaction_date' => '31-Oct-2025',
                                'sales_date' => '30-Oct-2025',
                                'station' => ['name' => 'PWALUGU'],
                                'site' => ['name' => 'PWALUGU'],
                                'account_name' => 'ZENITH-6012608470',
                                'account_number' => 'Sales',
                                'amount' => 'GHS9,120.00',
                                'deposit_by' => 'Robert Koomson',
                                'narration' => 'Zenith bulk sales transfer',
                                'details' => 'Zenith bulk sales transfer',
                                'payment_mode' => 'Cash',
                                'transaction_id' => 'ZNTH-6012608470',
                                'transaction_number' => 'ZNTH-6012608470',
                                'view_url' => '#',
                            ],
                            [
                                'transaction_date' => '31-Oct-2025',
                                'sales_date' => '30-Oct-2025',
                                'station' => ['name' => 'PWALUGU'],
                                'site' => ['name' => 'PWALUGU'],
                                'account_name' => 'MTN-0593245613 - ADAM PARIDAVA',
                                'account_number' => 'Upper Quarry Paid',
                                'amount' => 'GHS6,450.75',
                                'deposit_by' => 'Adam Paridava',
                                'narration' => 'Upper Quarry mobile money settlement',
                                'details' => 'Upper Quarry mobile money settlement',
                                'payment_mode' => 'Mobile Money',
                                'transaction_id' => '0593245613',
                                'transaction_number' => '0593245613',
                                'view_url' => '#',
                            ],
                        ]);
                    } else {
                        $displayDeposits = $providedDeposits;
                    }

                    $totalItems = $isPaginator && $hasProvidedDeposits ? $deposits->total() : $displayDeposits->count();
                    $currentPage = $isPaginator && $hasProvidedDeposits ? $deposits->currentPage() : 1;
                    $lastPage = $isPaginator && $hasProvidedDeposits ? max($deposits->lastPage(), 1) : 1;
                    $perPageDefault = (int) request('per_page', $isPaginator && $hasProvidedDeposits ? $deposits->perPage() : 50);
                    $pageParameter = $isPaginator && $hasProvidedDeposits ? $deposits->getPageName() : 'page';
                    $nextPageUrl = $isPaginator && $hasProvidedDeposits && $deposits->hasMorePages()
                        ? request()->fullUrlWithQuery([$pageParameter => $deposits->currentPage() + 1])
                        : null;
                @endphp

                <div class="bank-panel bank-panel--table">
                    <div class="bank-table-shell">
                        <div class="bank-table-meta">
                            <div>
                                Page {{ $currentPage }} of {{ $lastPage }}
                                <span>( <span id="bankVisibleCount">{{ number_format($totalItems) }}</span> <span id="bankVisibleLabel">{{ \Illuminate\Support\Str::plural('item', $totalItems) }}</span> )</span>
                            </div>
                            <div class="bank-meta-actions">
                                <div class="bank-page-size">
                                    <span>Page size:</span>
                                    <select name="per_page" form="filtersForm">
                                        @foreach([10, 25, 50, 100] as $size)
                                            <option value="{{ $size }}" @selected($perPageDefault === $size)>{{ $size }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if($nextPageUrl)
                                    <a href="{{ $nextPageUrl }}" class="bank-btn bank-btn--primary bank-btn--compact">
                                        Next Page
                                        <i class="ri-arrow-right-line ms-1"></i>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="bank-table-container">
                            <table class="bank-table">
                                <thead>
                                <tr>
                                    <th>Transaction Date</th>
                                    <th>Sales Date</th>
                                    <th>Station</th>
                                    <th>Account</th>
                                    <th>Amount</th>
                                    <th>Transaction ID</th>
                                    <th>Depositer</th>
                                    <th>Narration</th>
                                    <th>Payment Mode</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($displayDeposits as $deposit)
                                    @php
                                        $transactionDate = data_get($deposit, 'transaction_date', '—');
                                        $salesDate = data_get($deposit, 'sales_date', '—');
                                        $stationName = data_get($deposit, 'station.name') ?? data_get($deposit, 'station') ?? data_get($deposit, 'site.name') ?? data_get($deposit, 'site', '—');
                                        $accountName = data_get($deposit, 'account_name', '—');
                                        $accountNumber = data_get($deposit, 'account_number', '—');
                                        $amount = data_get($deposit, 'amount', '—');
                                        $depositor = data_get($deposit, 'deposit_by') ?? data_get($deposit, 'deposited_by') ?? data_get($deposit, 'depositor', '—');
                                        $narration = data_get($deposit, 'narration') ?? data_get($deposit, 'details', '—');
                                        $paymentMode = data_get($deposit, 'payment_mode', '—');
                                        $transactionId = data_get($deposit, 'transaction_id') ?? data_get($deposit, 'transaction_ID') ?? data_get($deposit, 'transaction_number', '—');
                                        $viewUrl = data_get($deposit, 'view_url');
                                        $deleteUrl = data_get($deposit, 'delete_url');
                                        try {
                                            $transactionDateIso = $transactionDate && $transactionDate !== '—'
                                                ? \Illuminate\Support\Carbon::parse($transactionDate)->format('Y-m-d')
                                                : '';
                                        } catch (\Exception $e) {
                                            $transactionDateIso = '';
                                        }

                                        try {
                                            $salesDateIso = $salesDate && $salesDate !== '—'
                                                ? \Illuminate\Support\Carbon::parse($salesDate)->format('Y-m-d')
                                                : '';
                                        } catch (\Exception $e) {
                                            $salesDateIso = '';
                                        }

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
                                                $paymentMode,
                                            ])->filter(fn ($value) => filled($value) && $value !== '—')->implode(' ')
                                        );
                                    @endphp
                                    <tr data-row="deposit"
                                        data-transaction-date="{{ $transactionDateIso }}"
                                        data-sales-date="{{ $salesDateIso }}"
                                        data-station-slug="{{ $stationSlug }}"
                                        data-search="{{ e($searchIndex) }}">
                                        <td>{{ $transactionDate }}</td>
                                        <td>{{ $salesDate }}</td>
                                        <td>{{ $stationName }}</td>
                                        <td>
                                            <div class="bank-account">
                                                <span>{{ $accountName }}</span>
                                                <span>{{ $accountNumber }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $amount }}</td>
                                        <td>{{ $transactionId }}</td>
                                        <td>{{ $depositor }}</td>
                                        <td>{{ $narration }}</td>
                                        <td>{{ $paymentMode }}</td>
                                        <td>
                                            <div class="bank-actions">
                                                <button type="button"
                                                        class="btn btn-link p-0 me-2 text-decoration-none text-primary js-view-deposit"
                                                        aria-label="View deposit"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#viewDepositModal"
                                                        data-transaction-date="{{ e($transactionDate) }}"
                                                        data-sales-date="{{ e($salesDate) }}"
                                                        data-station="{{ e($stationName) }}"
                                                        data-account-name="{{ e($accountName) }}"
                                                        data-account-number="{{ e($accountNumber) }}"
                                                        data-amount="{{ e($amount) }}"
                                                        data-depositor="{{ e($depositor) }}"
                                                        data-narration="{{ e($narration) }}"
                                                        data-payment-mode="{{ e($paymentMode) }}"
                                                        data-transaction-id="{{ e($transactionId) }}"
                                                        data-view-url="{{ $viewUrl ? e($viewUrl) : '' }}">
                                                    <span class="ri-eye-line" aria-hidden="true"></span>
                                                    <span class="visually-hidden">View</span>
                                                </button>
                                                <button type="button"
                                                        class="btn btn-link p-0 text-decoration-none text-danger js-delete-deposit"
                                                        aria-label="Delete deposit"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteDepositModal"
                                                        data-transaction-id="{{ e($transactionId) }}"
                                                        data-depositor="{{ e($depositor) }}"
                                                        data-amount="{{ e($amount) }}"
                                                        data-delete-url="{{ $deleteUrl ? e($deleteUrl) : '' }}">
                                                    <span class="ri-delete-bin-line" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="bank-empty">
                                            No bank deposits found for the selected filters.
                                        </td>
                                    </tr>
                                @endforelse
                                <tr class="bank-empty d-none" id="bankTableNoResults">
                                    <td colspan="10">No bank deposits match the current filters.</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        @if($isPaginator)
                            <div class="mt-3">
                                {{ $deposits->withQueryString()->links('vendor.pagination.bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
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
                    <form id="createDepositForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="transactionDate" class="form-label text-uppercase small fw-semibold">Transaction Date</label>
                                <input type="date" class="form-control" id="transactionDate" name="transaction_date" required>
                            </div>
                            <div class="col-md-6">
                                <label for="salesDate" class="form-label text-uppercase small fw-semibold">Sales Date</label>
                                <input type="date" class="form-control" id="salesDate" name="sales_date" required>
                            </div>
                            <div class="col-md-6">
                                <label for="stationSelect" class="form-label text-uppercase small fw-semibold">Station</label>
                                <select class="form-select" id="stationSelect" name="station" required>
                                    <option value="" selected disabled>Select Station</option>
                                    @foreach(($sites ?? []) as $siteOption)
                                        <option value="{{ $siteOption['value'] ?? $siteOption }}">{{ $siteOption['label'] ?? $siteOption }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="accountName" class="form-label text-uppercase small fw-semibold">Account Name</label>
                                <input type="text" class="form-control" id="accountName" name="account_name" placeholder="e.g. CBG Main Branch" required>
                            </div>
                            <div class="col-md-6">
                                <label for="accountNumber" class="form-label text-uppercase small fw-semibold">Account Number</label>
                                <input type="text" class="form-control" id="accountNumber" name="account_number" placeholder="e.g. CBG-21573161100001" required>
                            </div>
                            <div class="col-md-6">
                                <label for="depositAmount" class="form-label text-uppercase small fw-semibold">Amount</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="depositAmount" name="amount" placeholder="e.g. 21000.00" required>
                            </div>
                            <div class="col-md-6">
                                <label for="depositBy" class="form-label text-uppercase small fw-semibold">Depositer</label>
                                <input type="text" class="form-control" id="depositBy" name="deposit_by" placeholder="Name of depositer" required>
                            </div>
                            <div class="col-md-12">
                                <label for="narration" class="form-label text-uppercase small fw-semibold">Narration</label>
                                <textarea class="form-control" id="narration" name="narration" rows="3" placeholder="Brief description of the deposit" required></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="paymentMode" class="form-label text-uppercase small fw-semibold">Payment Mode</label>
                                <select class="form-select" id="paymentMode" name="payment_mode" required>
                                    <option value="" selected disabled>Select Mode</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Mobile Money">Mobile Money</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="transactionId" class="form-label text-uppercase small fw-semibold">Transaction ID</label>
                                <input type="text" class="form-control" id="transactionId" name="transaction_id" placeholder="Reference or slip number" required>
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
                    <button type="button" class="btn btn-primary" id="saveDepositBtn">
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
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="modal-title" id="viewDepositModalLabel">Deposit Details</h5>
                        <p class="text-muted mb-0 small text-uppercase letter-spacing">Review deposit breakdown</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <h6 class="text-uppercase text-muted small fw-semibold mb-2">Dates</h6>
                                    <p class="mb-1"><span class="fw-semibold">Transaction:</span> <span data-view-field="transaction_date">—</span></p>
                                    <p class="mb-0"><span class="fw-semibold">Sales:</span> <span data-view-field="sales_date">—</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <h6 class="text-uppercase text-muted small fw-semibold mb-2">Station</h6>
                                    <p class="mb-1"><span data-view-field="station">—</span></p>
                                    <h6 class="text-uppercase text-muted small fw-semibold mt-3 mb-2">Payment Mode</h6>
                                    <p class="mb-0"><span data-view-field="payment_mode">—</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <h6 class="text-uppercase text-muted small fw-semibold mb-2">Account</h6>
                                    <p class="mb-1"><span class="fw-semibold" data-view-field="account_name">—</span></p>
                                    <p class="mb-0 text-muted"><span data-view-field="account_number">—</span></p>
                                    <h6 class="text-uppercase text-muted small fw-semibold mt-3 mb-1">Amount</h6>
                                    <p class="mb-0"><span data-view-field="amount">—</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <h6 class="text-uppercase text-muted small fw-semibold mb-2">Depositer</h6>
                                    <p class="mb-2"><span data-view-field="depositor">—</span></p>
                                    <h6 class="text-uppercase text-muted small fw-semibold mb-2">Transaction ID</h6>
                                    <p class="mb-0"><span data-view-field="transaction_id">—</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <h6 class="text-uppercase text-muted small fw-semibold mb-2">Narration</h6>
                                    <p class="mb-0" data-view-field="narration">—</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 justify-content-between">
                    <a href="#" target="_blank" class="btn btn-outline-primary d-none" id="viewDepositExternalLink">
                        Open full record
                    </a>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
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
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const depositModal = document.getElementById('createDepositModal');
            const depositForm = document.getElementById('createDepositForm');
            const saveDepositBtn = document.getElementById('saveDepositBtn');
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

            if (depositModal && depositForm) {
                depositModal.addEventListener('hidden.bs.modal', () => {
                    depositForm.reset();
                });
            }

            if (saveDepositBtn) {
                saveDepositBtn.addEventListener('click', () => {
                    // Placeholder for future AJAX/submit logic
                    saveDepositBtn.disabled = true;
                    const originalText = saveDepositBtn.innerHTML;
                    saveDepositBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

                    setTimeout(() => {
                        saveDepositBtn.disabled = false;
                        saveDepositBtn.innerHTML = originalText;
                        const modalInstance = bootstrap.Modal.getInstance(depositModal);
                        modalInstance?.hide();
                    }, 1200);
                });
            }

            if (exportModal && exportForm) {
                exportModal.addEventListener('hidden.bs.modal', () => {
                    exportForm.reset();
                    exportForm.querySelector('#formatCsv').checked = true;
                    exportForm.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = true);
                });
            }

            if (generateExportBtn) {
                generateExportBtn.addEventListener('click', () => {
                    generateExportBtn.disabled = true;
                    const originalText = generateExportBtn.innerHTML;
                    generateExportBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Preparing...';

                    setTimeout(() => {
                        generateExportBtn.disabled = false;
                        generateExportBtn.innerHTML = originalText;
                        const exportModalInstance = bootstrap.Modal.getInstance(exportModal);
                        exportModalInstance?.hide();
                    }, 1000);
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
                        account_name: 'account-name',
                        account_number: 'account-number',
                        amount: 'amount',
                        depositor: 'depositor',
                        narration: 'narration',
                        payment_mode: 'payment-mode',
                        transaction_id: 'transaction-id'
                    };

                    Object.keys(fieldMap).forEach(key => {
                        const value = trigger.getAttribute(`data-${fieldMap[key]}`) || '—';
                        const target = viewModal.querySelector(`[data-view-field="${key}"]`);
                        if (target) target.textContent = value;
                    });

                    const externalLink = document.getElementById('viewDepositExternalLink');
                    if (externalLink) {
                        const href = trigger.getAttribute('data-view-url');
                        if (href) {
                            externalLink.href = href;
                            externalLink.classList.remove('d-none');
                        } else {
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

                confirmDeleteBtn.addEventListener('click', () => {
                    confirmDeleteBtn.disabled = true;
                    const originalText = confirmDeleteBtn.innerHTML;
                    confirmDeleteBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Deleting...';

                    setTimeout(() => {
                        confirmDeleteBtn.disabled = false;
                        confirmDeleteBtn.innerHTML = originalText;
                        const deleteModalInstance = bootstrap.Modal.getInstance(deleteModal);
                        deleteModalInstance?.hide();
                    }, 1000);
                });
            }
        });
    </script>
@endpush
