@extends('layouts.vertical', [
    'page_title' => 'Dispatch Stock',
    'mode' => $mode ?? '',
    'demo' => $demo ?? '',
])

@section('css')
    <style>
        .dispatch-card {
            background: linear-gradient(140deg, #031739 0%, #0b3f96 100%);
            padding: 1.2px;
            border-radius: 24px;
            box-shadow: 0 26px 44px rgba(3, 26, 67, 0.34);
        }

        .dispatch-card__inner {
            background: #f5f8ff;
            border-radius: 23px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .dispatch-card__header {
            background: linear-gradient(97deg, rgba(3, 23, 63, 0.96) 0%, rgba(10, 58, 138, 0.98) 55%, rgba(3, 23, 63, 0.96) 100%);
            padding: 1.9rem 2.6rem;
            color: #ffffff;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .dispatch-card__title {
            margin: 0;
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: 1.05px;
            text-transform: uppercase;
        }

        .dispatch-card__subtitle {
            margin: 0.3rem 0 0;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: rgba(232, 241, 255, 0.84);
        }

        .dispatch-card__meta {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
            font-size: 0.72rem;
            letter-spacing: 0.45px;
            text-transform: uppercase;
            color: rgba(232, 241, 255, 0.7);
        }

        .dispatch-card__meta span {
            opacity: 0.8;
        }


        .dispatch-form,
        .dispatch-ledger,
        .dispatch-report {
            margin: 0 2.4rem 2.4rem;
            background: #ffffff;
            border-radius: 20px;
            padding: 1.8rem 2rem;
            box-shadow: 0 18px 42px rgba(7, 32, 86, 0.14);
            border: 1px solid rgba(12, 38, 96, 0.08);
        }

        .dispatch-form__grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.2rem 1.6rem;
        }

        .dispatch-form label {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.48px;
            color: #0a2048;
            display: block;
            margin-bottom: 0.4rem;
        }

        .dispatch-form input,
        .dispatch-form select {
            width: 100%;
            border-radius: 10px;
            border: 1px solid rgba(12, 36, 79, 0.18);
            background: #f5f8ff;
            padding: 0.55rem 0.75rem;
            font-size: 0.84rem;
            color: #0a1d44;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .dispatch-form input:focus,
        .dispatch-form select:focus {
            outline: none;
            border-color: #2b6def;
            box-shadow: 0 0 0 3px rgba(43, 109, 239, 0.18);
        }

        .dispatch-form__file-control {
            position: relative;
        }

        .dispatch-form__file-control input[type="file"] {
            padding: 0.45rem 0.75rem;
            background: #ffffff;
        }

        .dispatch-form__file-meta {
            font-size: 0.68rem;
            color: #31446a;
            margin-top: 0.35rem;
            word-break: break-all;
        }

        .dispatch-form__actions {
            margin-top: 1.6rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.9rem;
            justify-content: flex-end;
        }

        .dispatch-btn {
            min-width: 130px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.45px;
            padding: 0.6rem 1.2rem;
            font-size: 0.78rem;
            cursor: pointer;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .dispatch-btn--sm {
            min-width: auto;
            padding: 0.45rem 0.9rem;
            font-size: 0.72rem;
        }

        .dispatch-btn--primary {
            background: linear-gradient(88deg, #ff7a1a 0%, #ffb347 100%);
            color: #0a1d44;
            box-shadow: 0 12px 22px rgba(255, 135, 54, 0.32);
        }

        .dispatch-btn--primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 26px rgba(255, 135, 54, 0.4);
        }

        .dispatch-btn--ghost {
            background: transparent;
            border: 1px solid rgba(9, 28, 64, 0.25);
            color: #0b1c3f;
        }

        .dispatch-btn--ghost:hover {
            transform: translateY(-1px);
            border-color: rgba(9, 28, 64, 0.45);
            box-shadow: 0 8px 18px rgba(8, 29, 73, 0.16);
        }

        .dispatch-ledger__header,
        .dispatch-report__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1.2rem;
        }

        .dispatch-ledger__title,
        .dispatch-report__title {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 700;
            color: #0a1d44;
            letter-spacing: 0.4px;
        }

        .dispatch-ledger__actions,
        .dispatch-report__actions {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            flex-wrap: wrap;
        }

        .dispatch-ledger .table-responsive,
        .dispatch-report .table-responsive {
            overflow-x: auto;
        }

        .dispatch-table,
        .dispatch-report__table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px;
            color: #0a1d44;
            font-size: 0.78rem;
        }

        .dispatch-table th,
        .dispatch-table td,
        .dispatch-report__table th,
        .dispatch-report__table td {
            border: 1px solid rgba(16, 44, 98, 0.12);
            padding: 0.55rem 0.6rem;
            text-align: left;
        }

        .dispatch-table th,
        .dispatch-report__table th {
            background: #0b2e6f;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.45px;
            font-weight: 600;
        }

        .dispatch-table tbody tr:nth-child(even),
        .dispatch-report__table tbody tr:nth-child(even) {
            background: #f5f7ff;
        }

        .dispatch-table tbody tr:nth-child(odd),
        .dispatch-report__table tbody tr:nth-child(odd) {
            background: #ffffff;
        }

        .dispatch-empty,
        .dispatch-report__empty {
            text-align: center;
            padding: 1.6rem;
            color: rgba(9, 31, 74, 0.55);
            font-style: italic;
        }

        .dispatch-report__filters {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .dispatch-report__filters label {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.48px;
            color: #0a2048;
        }

        .dispatch-report__filters input[type="month"] {
            border-radius: 10px;
            border: 1px solid rgba(12, 36, 79, 0.18);
            background: #f5f8ff;
            padding: 0.55rem 0.75rem;
            font-size: 0.84rem;
            color: #0a1d44;
        }

        .dispatch-report__summary {
            margin-bottom: 1.6rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.2rem;
        }

        .dispatch-report__summary-card {
            position: relative;
            background: linear-gradient(180deg, #ffffff 0%, #edf3ff 100%);
            border-radius: 18px;
            padding: 1rem 1.3rem;
            border: 1px solid rgba(6, 26, 68, 0.08);
            display: grid;
            grid-template-columns: auto 1fr;
            align-items: center;
            gap: 0.9rem;
            box-shadow: 0 12px 28px rgba(7, 32, 86, 0.12);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
            isolation: isolate;
        }

        .dispatch-report__summary-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 36px rgba(7, 32, 86, 0.16);
        }

        .dispatch-report__summary-card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background: linear-gradient(120deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.35) 100%);
            opacity: 0;
            transition: opacity 0.25s ease;
            pointer-events: none;
        }

        .dispatch-report__summary-card:hover::before {
            opacity: 1;
        }

        .dispatch-report__summary-card::after {
            content: '';
            position: absolute;
            width: 140px;
            height: 140px;
            background: radial-gradient(circle at center, rgba(16, 68, 196, 0.18), transparent 68%);
            bottom: -48px;
            right: -52px;
            transform: rotate(18deg);
            pointer-events: none;
            z-index: 0;
        }

        .dispatch-report__summary-card--highlight {
            background: linear-gradient(135deg, rgba(9, 35, 92, 0.96) 0%, rgba(35, 108, 226, 0.92) 100%);
            color: #ffffff;
            border-color: transparent;
            box-shadow: 0 20px 42px rgba(9, 35, 92, 0.32);
        }

        .dispatch-report__summary-card--highlight .dispatch-report__summary-icon {
            background: rgba(255, 255, 255, 0.18);
            color: #ffffff;
        }

        .dispatch-report__summary-card--highlight::after {
            background: radial-gradient(circle at center, rgba(255, 255, 255, 0.26), transparent 70%);
        }

        .dispatch-report__summary-card--highlight .dispatch-report__summary-label,
        .dispatch-report__summary-card--highlight .dispatch-report__summary-caption,
        .dispatch-report__summary-card--highlight .dispatch-report__summary-meta,
        .dispatch-report__summary-card--highlight .dispatch-report__summary-value {
            color: rgba(255, 255, 255, 0.92);
        }

        [data-role="summary-top-station-card"][data-empty="true"] {
            opacity: 0.75;
        }

        .dispatch-report__summary-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #0a2d78;
            background: rgba(17, 81, 196, 0.12);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.2), 0 10px 18px rgba(6, 26, 68, 0.18);
            position: relative;
            z-index: 1;
        }

        .dispatch-report__summary-icon--dispatches {
            background: linear-gradient(135deg, rgba(246, 181, 67, 0.28) 0%, rgba(255, 122, 26, 0.3) 100%);
            color: #9d4700;
        }

        .dispatch-report__summary-icon--volume {
            background: linear-gradient(135deg, rgba(43, 109, 239, 0.28) 0%, rgba(12, 55, 150, 0.3) 100%);
            color: #0a2d78;
        }

        .dispatch-report__summary-icon--ago {
            background: linear-gradient(135deg, rgba(97, 215, 164, 0.32) 0%, rgba(34, 159, 108, 0.32) 100%);
            color: #126848;
        }

        .dispatch-report__summary-icon--pms {
            background: linear-gradient(135deg, rgba(240, 163, 255, 0.34) 0%, rgba(153, 67, 203, 0.34) 100%);
            color: #651c95;
        }

        .dispatch-report__summary-icon--stations {
            background: linear-gradient(135deg, rgba(131, 200, 255, 0.3) 0%, rgba(16, 128, 214, 0.3) 100%);
            color: #0a3a72;
        }

        .dispatch-report__summary-icon--top {
            background: linear-gradient(135deg, rgba(255, 244, 163, 0.4) 0%, rgba(255, 214, 80, 0.42) 100%);
            color: #7a4b00;
        }

        .dispatch-report__summary-content {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            position: relative;
            z-index: 1;
        }

        .dispatch-report__summary-label {
            text-transform: uppercase;
            font-size: 0.68rem;
            letter-spacing: 0.55px;
            color: #1e3565;
        }

        .dispatch-report__summary-value {
            font-size: 1.45rem;
            font-weight: 700;
            color: #0a1d44;
        }

        .dispatch-report__summary-caption {
            font-size: 0.7rem;
            color: rgba(10, 29, 68, 0.65);
            letter-spacing: 0.3px;
            margin-top: -0.1rem;
        }

        .dispatch-report__summary-meta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.4rem;
            font-size: 0.7rem;
            color: rgba(10, 29, 68, 0.7);
        }

        .dispatch-report__summary-meta span + span::before {
            content: '•';
            margin-right: 0.35rem;
            opacity: 0.5;
        }

        .dispatch-report__summary-card--highlight .dispatch-report__summary-meta {
            color: rgba(255, 255, 255, 0.88);
        }

        .dispatch-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.18rem 0.5rem;
            border-radius: 999px;
            font-size: 0.68rem;
            letter-spacing: 0.35px;
            background: rgba(10, 45, 120, 0.1);
            color: #0a2d78;
            font-weight: 600;
        }

        .dispatch-action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: none;
            background: rgba(10, 38, 96, 0.08);
            color: #0a1d44;
            font-size: 1.05rem;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.2s ease, color 0.2s ease;
        }

        .dispatch-action-btn:hover,
        .dispatch-action-btn:focus {
            background: rgba(10, 38, 96, 0.16);
            color: #0b3f96;
            transform: translateY(-1px);
            outline: none;
        }

        .dispatch-modal {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(3, 15, 40, 0.55);
            z-index: 1080;
            padding: 1.5rem;
        }

        .dispatch-modal.is-visible {
            display: flex;
        }

        .dispatch-modal__dialog {
            background: #ffffff;
            border-radius: 18px;
            width: min(720px, 96vw);
            max-height: 90vh;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(10, 29, 68, 0.35);
            display: flex;
            flex-direction: column;
        }

        .dispatch-modal__header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(12, 36, 79, 0.12);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dispatch-modal__title {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 700;
            color: #0a1d44;
            letter-spacing: 0.35px;
        }

        .dispatch-modal__close {
            border: none;
            background: transparent;
            color: #0a1d44;
            font-size: 1.45rem;
            line-height: 1;
            cursor: pointer;
            padding: 0.25rem;
        }

        .dispatch-modal__body {
            padding: 1.5rem;
            overflow-y: auto;
        }

        .dispatch-modal__grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 1rem 1.5rem;
        }

        .dispatch-modal__field {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .dispatch-modal__label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.48px;
            color: rgba(10, 28, 66, 0.68);
            margin: 0;
        }

        .dispatch-modal__value {
            font-size: 0.92rem;
            font-weight: 600;
            color: #071a3c;
            margin: 0;
            word-break: break-word;
        }

        .dispatch-modal__actions {
            margin-top: 1.5rem;
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .dispatch-modal__close-btn,
        .dispatch-modal__waybill-btn {
            padding: 0.55rem 1.1rem;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.45px;
            font-size: 0.78rem;
            cursor: pointer;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .dispatch-modal__close-btn {
            background: rgba(10, 32, 72, 0.08);
            color: #0a1d44;
        }

        .dispatch-modal__close-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(10, 32, 72, 0.18);
        }

        .dispatch-modal__waybill-btn {
            background: linear-gradient(92deg, #0a4ed1 0%, #53a0fd 100%);
            color: #ffffff;
            box-shadow: 0 12px 28px rgba(14, 78, 209, 0.32);
        }

        .dispatch-modal__waybill-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 30px rgba(14, 78, 209, 0.42);
        }

        @media (max-width: 1200px) {
            .dispatch-form,
            .dispatch-ledger,
            .dispatch-report {
                margin: 0 1.8rem 2.4rem;
            }
        }

        @media (max-width: 768px) {
            .dispatch-card__header {
                padding: 1.6rem 1.8rem;
            }

            .dispatch-form,
            .dispatch-ledger,
            .dispatch-report {
                margin: 0 1.2rem 1.8rem;
            }

            .dispatch-form__grid {
                grid-template-columns: 1fr;
            }

            .dispatch-form__actions {
                justify-content: stretch;
            }

            .dispatch-btn {
                flex: 1 1 auto;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="dispatch-card">
            <div class="dispatch-card__inner">
                <div class="dispatch-card__header">
                    <div>
                        <h2 class="dispatch-card__title">Dispatch Stock</h2>
                        <p class="dispatch-card__subtitle">Capture outgoing product movements and keep balances accurate</p>
                    </div>
                    <div class="dispatch-card__meta">
                        <span>BRV = Bulk Road Vehicle</span>
                        <span>Ensure waybill and invoice details are verified before submission</span>
                    </div>
                </div>

                <div class="dispatch-form">
                    <form id="dispatchForm" action="{{ route('company.fuel.dispatches.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="dispatch-form__grid">
                            <div>
                                <label for="dispatchDate">Dispatch Date</label>
                                <input type="date" id="dispatchDate" name="dispatch_date" value="{{ old('dispatch_date', date('Y-m-d')) }}" required>
                            </div>
                            <div>
                                <label for="productType">Product Type</label>
                                <select id="productType" name="product_type" required>
                                    <option value="" disabled selected>Select product</option>
                                    <option value="AGO" @selected(old('product_type') === 'AGO')>AGO (Diesel)</option>
                                    <option value="PMS" @selected(old('product_type') === 'PMS')>PMS (Super)</option>
                                </select>
                            </div>
                            <div>
                                <label for="depot">Loading Depot</label>
                                <input type="text" id="depot" name="depot" placeholder="e.g. TOR" value="{{ old('depot') }}" required>
                            </div>
                            <div>
                                <label for="loadedFrom">BDC</label>
                                <input type="text" id="loadedFrom" name="bdc" placeholder="e.g. Wi Energy Limited" value="{{ old('bdc') }}" required>
                            </div>
                            <div>
                                <label for="quantityDispatched">Quantity Dispatched (Litres)</label>
                                <input type="number" min="0" step="0.01" id="quantityDispatched" name="quantity_dispatched" placeholder="0.00" value="{{ old('quantity_dispatched') }}" required>
                            </div>
                            <div>
                                <label for="brvNumber">BRV</label>
                                <input type="text" id="brvNumber" name="brv_number" placeholder="e.g. GR-1234-24" value="{{ old('brv_number') }}" required>
                            </div>
                            <div>
                                <label for="driverName">Driver Name</label>
                                <input type="text" id="driverName" name="driver_name" placeholder="Driver full name" value="{{ old('driver_name') }}" required>
                            </div>
                            <div>
                                <label for="driverPhone">Driver Contact</label>
                                <input type="tel" id="driverPhone" name="driver_phone" placeholder="e.g. +233 20 000 0000" value="{{ old('driver_phone') }}">
                            </div>
                            <div>
                                <label for="receivingStation">Receiving Station</label>
                                <select id="receivingStation" name="station_id" required>
                                    <option value="" disabled selected>Select station</option>
                                    @foreach ($stations ?? [] as $station)
                                        <option value="{{ $station->id }}" @selected(old('station_id') == $station->id)>{{ $station->name }} @if($station->code)({{ $station->code }})@endif</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="inspectedBy">Inspected By (Liaison Officer)</label>
                                <input type="text" id="inspectedBy" name="inspected_by" placeholder="Officer on duty" value="{{ old('inspected_by') }}" required>
                            </div>
                            <div>
                                <label for="invoiceNumber">Invoice Number</label>
                                <input type="text" id="invoiceNumber" name="invoice_number" placeholder="e.g. INV-000123" value="{{ old('invoice_number') }}" required>
                            </div>
                            <div class="dispatch-form__file-control">
                                <label for="waybillUpload">Waybill Upload</label>
                                <input type="file" id="waybillUpload" name="waybill" accept=".pdf,.jpg,.jpeg,.png">
                                <div class="dispatch-form__file-meta" data-role="waybill-meta">Accepted formats: PDF, JPG, PNG</div>
                            </div>
                        </div>
                        @if($errors->any())
                            <div class="alert alert-danger mb-3" role="alert">
                                <ul class="mb-0 list-unstyled">
                                    @foreach($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="dispatch-form__actions">
                            <button type="reset" class="dispatch-btn dispatch-btn--ghost">Clear</button>
                            <button type="submit" class="dispatch-btn dispatch-btn--primary">Record Dispatch</button>
                        </div>
                    </form>
                </div>

                <div class="dispatch-ledger">
                    <div class="dispatch-ledger__header">
                        <h3 class="dispatch-ledger__title">Dispatch Ledger</h3>
                        <div class="dispatch-ledger__actions">
                            <button type="button" class="dispatch-btn dispatch-btn--ghost dispatch-btn--sm" id="printDispatchLedgerBtn">
                                <span class="me-1">Print</span>
                                <i class="ri-printer-line"></i>
                            </button>
                            <button type="button" class="dispatch-btn dispatch-btn--primary dispatch-btn--sm" id="exportDispatchPdfBtn">
                                <span class="me-1">Export PDF</span>
                                <i class="ri-file-download-line"></i>
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive" id="dispatchTableWrapper">
                        <table class="dispatch-table" id="dispatchTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Dispatch Date</th>
                                    <th>Product</th>
                                    <th>Depot</th>
                                    <th>BDC</th>
                                    <th>Quantity (L)</th>
                                    <th>BRV Number</th>
                                    <th>Driver</th>
                                    <th>Driver Contact</th>
                                    <th>Receiving Station</th>
                                    <th>Inspected By</th>
                                    <th>Invoice #</th>
                                    <th>Waybill</th>
                                </tr>
                            </thead>
                            <tbody data-role="dispatch-tbody">
                                @forelse($dispatches ?? [] as $index => $dispatch)
                                    @php
                                        $waybillUrl = $dispatch->waybill_path ? route('company.fuel.dispatches.waybill', $dispatch) : '';
                                        $formattedDate = $dispatch->dispatch_date ? $dispatch->dispatch_date->format('d-m-Y') : '—';
                                        $dispatchDateRaw = $dispatch->dispatch_date ? $dispatch->dispatch_date->format('Y-m-d') : '';
                                    @endphp
                                    <tr data-role="dispatch-row"
                                        data-dispatch-date="{{ $dispatchDateRaw }}"
                                        data-formatted-date="{{ $formattedDate }}"
                                        data-product-type="{{ $dispatch->product_type }}"
                                        data-depot="{{ e($dispatch->depot) }}"
                                        data-bdc="{{ e($dispatch->bdc) }}"
                                        data-quantity="{{ $dispatch->quantity_dispatched }}"
                                        data-brv-number="{{ e($dispatch->brv_number) }}"
                                        data-driver-name="{{ e($dispatch->driver_name) }}"
                                        data-driver-phone="{{ e($dispatch->driver_phone ?? '') }}"
                                        data-receiving-station="{{ e($dispatch->station ? $dispatch->station->name : '') }}"
                                        data-inspected-by="{{ e($dispatch->inspected_by) }}"
                                        data-invoice-number="{{ e($dispatch->invoice_number) }}"
                                        data-waybill-url="{{ $waybillUrl }}"
                                    >
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $formattedDate }}</td>
                                        <td>{{ $dispatch->product_type }}</td>
                                        <td>{{ $dispatch->depot }}</td>
                                        <td>{{ $dispatch->bdc }}</td>
                                        <td>{{ number_format($dispatch->quantity_dispatched, 2) }}</td>
                                        <td>{{ $dispatch->brv_number }}</td>
                                        <td>{{ $dispatch->driver_name }}</td>
                                        <td>{{ $dispatch->driver_phone ?? '—' }}</td>
                                        <td>{{ $dispatch->station ? $dispatch->station->name : '—' }}</td>
                                        <td>{{ $dispatch->inspected_by }}</td>
                                        <td>{{ $dispatch->invoice_number }}</td>
                                        <td>
                                            @if($waybillUrl)
                                                <a href="{{ $waybillUrl }}" target="_blank" rel="noopener" class="dispatch-action-btn" title="Open waybill"><i class="ri-eye-line"></i></a>
                                            @else
                                                <button type="button" class="dispatch-action-btn" data-role="view-dispatch" title="View details"><i class="ri-eye-line"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="dispatch-empty">
                                        <td colspan="13">No dispatch records captured yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="dispatch-modal" id="dispatchDetailsModal" aria-hidden="true" role="dialog" aria-labelledby="dispatchDetailsTitle">
                    <div class="dispatch-modal__dialog">
                        <div class="dispatch-modal__header">
                            <h4 class="dispatch-modal__title" id="dispatchDetailsTitle">Dispatch Details</h4>
                            <button type="button" class="dispatch-modal__close" data-role="modal-close" aria-label="Close details">&times;</button>
                        </div>
                        <div class="dispatch-modal__body">
                            <div class="dispatch-modal__grid">
                                <div class="dispatch-modal__field">
                                    <p class="dispatch-modal__label">Dispatch Date</p>
                                    <p class="dispatch-modal__value" data-field="dispatchDate">&mdash;</p>
                                </div>
                                <div class="dispatch-modal__field">
                                    <p class="dispatch-modal__label">Product</p>
                                    <p class="dispatch-modal__value" data-field="productType">&mdash;</p>
                                </div>
                                <div class="dispatch-modal__field">
                                    <p class="dispatch-modal__label">Depot</p>
                                    <p class="dispatch-modal__value" data-field="depot">&mdash;</p>
                                </div>
                                <div class="dispatch-modal__field">
                                    <p class="dispatch-modal__label">BDC</p>
                                    <p class="dispatch-modal__value" data-field="bdc">&mdash;</p>
                                </div>
                                <div class="dispatch-modal__field">
                                    <p class="dispatch-modal__label">Quantity (L)</p>
                                    <p class="dispatch-modal__value" data-field="quantity">&mdash;</p>
                                </div>
                                <div class="dispatch-modal__field">
                                    <p class="dispatch-modal__label">BRV Number</p>
                                    <p class="dispatch-modal__value" data-field="brvNumber">&mdash;</p>
                                </div>
                                <div class="dispatch-modal__field">
                                    <p class="dispatch-modal__label">Driver</p>
                                    <p class="dispatch-modal__value" data-field="driverName">&mdash;</p>
                                </div>
                                <div class="dispatch-modal__field">
                                    <p class="dispatch-modal__label">Driver Contact</p>
                                    <p class="dispatch-modal__value" data-field="driverPhone">&mdash;</p>
                                </div>
                                <div class="dispatch-modal__field">
                                    <p class="dispatch-modal__label">Receiving Station</p>
                                    <p class="dispatch-modal__value" data-field="receivingStation">&mdash;</p>
                                </div>
                                <div class="dispatch-modal__field">
                                    <p class="dispatch-modal__label">Inspected By</p>
                                    <p class="dispatch-modal__value" data-field="inspectedBy">&mdash;</p>
                                </div>
                                <div class="dispatch-modal__field">
                                    <p class="dispatch-modal__label">Invoice #</p>
                                    <p class="dispatch-modal__value" data-field="invoiceNumber">&mdash;</p>
                                </div>
                            </div>
                            <div class="dispatch-modal__actions">
                                <a href="#" target="_blank" rel="noopener" class="dispatch-modal__waybill-btn" data-role="modal-waybill" hidden>Open Waybill</a>
                                <button type="button" class="dispatch-modal__close-btn" data-role="modal-close">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dispatch-report">
                    <div class="dispatch-report__header">
                        <h3 class="dispatch-report__title">Monthly Reconciliation</h3>
                        <div class="dispatch-report__filters">
                            <label for="reconciliationMonth" class="mb-0">Select Month</label>
                            <input type="month" id="reconciliationMonth" max="9999-12">
                            <button type="button" class="dispatch-btn dispatch-btn--primary dispatch-btn--sm" id="generateReconciliationBtn">
                                <span class="me-1">Generate</span>
                                <i class="ri-bar-chart-line"></i>
                            </button>
                        </div>
                    </div>
                    <div class="dispatch-report__actions">
                        <div class="dispatch-badge" data-role="reconciliation-month-label">No month selected</div>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="dispatch-btn dispatch-btn--ghost dispatch-btn--sm" id="printReconciliationBtn">
                                <span class="me-1">Print</span>
                                <i class="ri-printer-line"></i>
                            </button>
                            <button type="button" class="dispatch-btn dispatch-btn--primary dispatch-btn--sm" id="exportReconciliationPdfBtn">
                                <span class="me-1">Export PDF</span>
                                <i class="ri-file-download-line"></i>
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive" id="reconciliationTableWrapper">
                        <table class="dispatch-report__table" id="reconciliationTable">
                            <thead>
                                <tr>
                                    <th>Dispatch Date</th>
                                    <th>Receiving Station</th>
                                    <th>Product</th>
                                    <th>Volume (L)</th>
                                    <th>Depot</th>
                                    <th>BDC</th>
                                    <th>BRV Number</th>
                                </tr>
                            </thead>
                            <tbody data-role="reconciliation-tbody">
                                <tr class="dispatch-report__empty">
                                    <td colspan="7">Select a month to generate reconciliation totals.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-YcsIPmK9jGTf3I9P4MBDl2SmS0FZtBx8y8mk4luzFuJdvByCnWJIRedKgNqUK3MUY14CzO2D93BYJk50xKp3+w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endpush

@push('javascript')
    <script>
        function initDispatchModule() {
            const dispatchForm = document.getElementById('dispatchForm');
            const dispatchDateInput = document.getElementById('dispatchDate');
            const waybillInput = document.getElementById('waybillUpload');
            const waybillMeta = document.querySelector('[data-role="waybill-meta"]');
            const dispatchTableBody = document.querySelector('[data-role="dispatch-tbody"]');
            const reconciliationTableBody = document.querySelector('[data-role="reconciliation-tbody"]');

            const reconciliationMonthInput = document.getElementById('reconciliationMonth');
            const reconciliationMonthLabel = document.querySelector('[data-role="reconciliation-month-label"]');
            const generateReconciliationBtn = document.getElementById('generateReconciliationBtn');
            const exportDispatchPdfBtn = document.getElementById('exportDispatchPdfBtn');
            const printDispatchLedgerBtn = document.getElementById('printDispatchLedgerBtn');
            const exportReconciliationPdfBtn = document.getElementById('exportReconciliationPdfBtn');
            const printReconciliationBtn = document.getElementById('printReconciliationBtn');
            const dispatchTableWrapper = document.getElementById('dispatchTableWrapper');
            const reconciliationTableWrapper = document.getElementById('reconciliationTableWrapper');

            let dispatchCounter = 0;
            const dispatchRecords = [];
            let lastReconciliationMonth = null;
            let lastReconciliationRecords = [];

            const detailsModal = document.getElementById('dispatchDetailsModal');
            const detailsWaybillLink = detailsModal?.querySelector('[data-role="modal-waybill"]');
            const modalCloseButtons = detailsModal?.querySelectorAll('[data-role="modal-close"]') || [];
            const detailsFields = detailsModal
                ? {
                      dispatchDate: detailsModal.querySelector('[data-field="dispatchDate"]'),
                      productType: detailsModal.querySelector('[data-field="productType"]'),
                      depot: detailsModal.querySelector('[data-field="depot"]'),
                      bdc: detailsModal.querySelector('[data-field="bdc"]'),
                      quantity: detailsModal.querySelector('[data-field="quantity"]'),
                      brvNumber: detailsModal.querySelector('[data-field="brvNumber"]'),
                      driverName: detailsModal.querySelector('[data-field="driverName"]'),
                      driverPhone: detailsModal.querySelector('[data-field="driverPhone"]'),
                      receivingStation: detailsModal.querySelector('[data-field="receivingStation"]'),
                      inspectedBy: detailsModal.querySelector('[data-field="inspectedBy"]'),
                      invoiceNumber: detailsModal.querySelector('[data-field="invoiceNumber"]'),
                  }
                : {};

            function formatNumber(value) {
                return Number(value || 0).toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                });
            }

            function ensureDateDefault() {
                if (!dispatchDateInput.value) {
                    const today = new Date().toISOString().split('T')[0];
                    dispatchDateInput.value = today;
                }
            }

            function clearLedgerEmptyState() {
                const emptyRow = dispatchTableBody.querySelector('.dispatch-empty');
                if (emptyRow) {
                    emptyRow.remove();
                }
            }

            function resetLedgerEmptyState() {
                if (!dispatchTableBody.children.length) {
                    const row = document.createElement('tr');
                    row.classList.add('dispatch-empty');
                    row.innerHTML = '<td colspan="13">No dispatch records captured yet.</td>';
                    dispatchTableBody.appendChild(row);
                }
            }

            function clearReconciliationEmptyState() {
                const emptyRow = reconciliationTableBody.querySelector('.dispatch-report__empty');
                if (emptyRow) {
                    emptyRow.remove();
                }
            }

            function resetReconciliationEmptyState(message) {
                reconciliationTableBody.innerHTML = '';
                const row = document.createElement('tr');
                row.classList.add('dispatch-report__empty');
                row.innerHTML = `<td colspan="7">${message}</td>`;
                reconciliationTableBody.appendChild(row);
            }

            function updateWaybillMeta() {
                if (!waybillMeta) {
                    return;
                }

                if (!waybillInput || !waybillInput.files.length) {
                    waybillMeta.textContent = 'Accepted formats: PDF, JPG, PNG';
                    return;
                }

                const file = waybillInput.files[0];
                waybillMeta.textContent = `Selected: ${file.name}`;
            }

            function generateRecordId() {
                if (typeof window !== 'undefined') {
                    const cryptoSource = window.crypto || window.msCrypto;
                    if (cryptoSource && typeof cryptoSource.randomUUID === 'function') {
                        return cryptoSource.randomUUID();
                    }
                }

                const randomSegment = () => Math.floor(Math.random() * 0x100000000).toString(16).padStart(8, '0');
                return `dispatch-${Date.now().toString(16)}-${randomSegment()}`;
            }

            function addDispatchRecord(data) {
                dispatchCounter += 1;

                const record = {
                    id: data.id || generateRecordId(),
                    sequence: dispatchCounter,
                    dispatchDate: data.dispatchDate || '',
                    formattedDate: data.formattedDate || (data.dispatchDate ? data.dispatchDate.split('-').reverse().join('-') : '—'),
                    productType: data.productType || '—',
                    loadingFacility: data.loadingFacility || '—',
                    loadedFrom: data.loadedFrom || '—',
                    quantity: Number(data.quantity) || 0,
                    brvNumber: data.brvNumber || '—',
                    driverName: data.driverName || '—',
                    driverPhone: data.driverPhone || '—',
                    receivingStation: data.receivingStation || '—',
                    inspectedBy: data.inspectedBy || '—',
                    invoiceNumber: data.invoiceNumber || '—',
                    waybillUrl: data.waybillUrl || null,
                    isObjectUrl: Boolean(data.isObjectUrl),
                };

                dispatchRecords.push(record);

                clearLedgerEmptyState();
                renderLedgerRow(record);
            }

            function renderLedgerRow(record) {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.sequence}</td>
                    <td>${record.formattedDate}</td>
                    <td>${record.productType}</td>
                    <td>${record.loadingFacility || '—'}</td>
                    <td>${record.loadedFrom || '—'}</td>
                    <td>${formatNumber(record.quantity)}</td>
                    <td>${record.brvNumber || '—'}</td>
                    <td>${record.driverName || '—'}</td>
                    <td>${record.driverPhone || '—'}</td>
                    <td>${record.receivingStation || '—'}</td>
                    <td>${record.inspectedBy || '—'}</td>
                    <td>${record.invoiceNumber || '—'}</td>
                    <td>
                        <button type="button" class="dispatch-action-btn" data-role="view-dispatch">
                            <i class="ri-eye-line"></i>
                        </button>
                    </td>
                `;

                attachRecordToRow(row, record);

                dispatchTableBody.appendChild(row);
            }

            function attachRecordToRow(row, record) {
                if (!row) {
                    return;
                }

                row.dataset.recordId = record.id;
                Object.assign(row.dataset, {
                    dispatchDate: record.dispatchDate || '',
                    formattedDate: record.formattedDate || '—',
                    productType: record.productType || '—',
                    loadingFacility: record.loadingFacility || '—',
                    loadedFrom: record.loadedFrom || '—',
                    quantity: record.quantity || 0,
                    brvNumber: record.brvNumber || '—',
                    driverName: record.driverName || '—',
                    driverPhone: record.driverPhone || '—',
                    receivingStation: record.receivingStation || '—',
                    inspectedBy: record.inspectedBy || '—',
                    invoiceNumber: record.invoiceNumber || '—',
                    waybillUrl: record.waybillUrl || '',
                });

                const actionBtn = row.querySelector('[data-role="view-dispatch"]');
                if (actionBtn) {
                    actionBtn.dataset.recordId = record.id;
                }
            }

            function showDispatchDetails(recordId) {
                if (!detailsModal) {
                    return;
                }

                const record = dispatchRecords.find(item => item.id === recordId);
                if (!record) {
                    return;
                }

                detailsModal.setAttribute('aria-hidden', 'false');
                detailsModal.classList.add('is-visible');

                const assignments = {
                    dispatchDate: record.formattedDate,
                    productType: record.productType,
                    depot: record.depot || record.loadingFacility,
                    bdc: record.bdc || record.loadedFrom,
                    quantity: formatNumber(record.quantity),
                    brvNumber: record.brvNumber,
                    driverName: record.driverName,
                    driverPhone: record.driverPhone,
                    receivingStation: record.receivingStation,
                    inspectedBy: record.inspectedBy,
                    invoiceNumber: record.invoiceNumber,
                };

                Object.entries(assignments).forEach(([key, value]) => {
                    if (detailsFields[key]) {
                        detailsFields[key].textContent = value || '—';
                    }
                });

                if (detailsWaybillLink) {
                    if (record.waybillUrl) {
                        detailsWaybillLink.href = record.waybillUrl;
                        detailsWaybillLink.hidden = false;
                    } else {
                        detailsWaybillLink.href = '#';
                        detailsWaybillLink.hidden = true;
                    }
                }
            }

            function hideDispatchDetails() {
                if (!detailsModal) {
                    return;
                }

                detailsModal.setAttribute('aria-hidden', 'true');
                detailsModal.classList.remove('is-visible');
            }

            function showDispatchDetailsFromRow(row) {
                if (!detailsModal || !row) return;
                const d = row.dataset;
                const assignments = {
                    dispatchDate: d.formattedDate || '—',
                    productType: d.productType || '—',
                    depot: d.depot || '—',
                    bdc: d.bdc || '—',
                    quantity: formatNumber(Number(d.quantity) || 0),
                    brvNumber: d.brvNumber || '—',
                    driverName: d.driverName || '—',
                    driverPhone: d.driverPhone || '—',
                    receivingStation: d.receivingStation || '—',
                    inspectedBy: d.inspectedBy || '—',
                    invoiceNumber: d.invoiceNumber || '—',
                };
                Object.entries(assignments).forEach(function (entry) {
                    const key = entry[0], value = entry[1];
                    if (detailsFields[key]) detailsFields[key].textContent = value;
                });
                if (detailsWaybillLink) {
                    if (d.waybillUrl) {
                        detailsWaybillLink.href = d.waybillUrl;
                        detailsWaybillLink.hidden = false;
                    } else {
                        detailsWaybillLink.href = '#';
                        detailsWaybillLink.hidden = true;
                    }
                }
                detailsModal.setAttribute('aria-hidden', 'false');
                detailsModal.classList.add('is-visible');
            }

            function buildDispatchRecordsFromTable() {
                dispatchRecords.length = 0;
                dispatchCounter = 0;
                const rows = dispatchTableBody ? dispatchTableBody.querySelectorAll('tr[data-role="dispatch-row"]') : [];
                rows.forEach(function (row, idx) {
                    const d = row.dataset;
                    dispatchCounter += 1;
                    dispatchRecords.push({
                        id: 'row-' + idx,
                        sequence: dispatchCounter,
                        dispatchDate: d.dispatchDate || '',
                        formattedDate: d.formattedDate || '—',
                        productType: d.productType || '—',
                        loadingFacility: d.depot || '—',
                        loadedFrom: d.bdc || '—',
                        depot: d.depot || '—',
                        bdc: d.bdc || '—',
                        quantity: Number(d.quantity) || 0,
                        brvNumber: d.brvNumber || '—',
                        driverName: d.driverName || '—',
                        driverPhone: d.driverPhone || '—',
                        receivingStation: d.receivingStation || '—',
                        inspectedBy: d.inspectedBy || '—',
                        invoiceNumber: d.invoiceNumber || '—',
                        waybillUrl: d.waybillUrl || null,
                    });
                });
            }

            function formatMonthLabel(monthValue) {
                if (!monthValue) {
                    return 'No month selected';
                }

                const [year, month] = monthValue.split('-').map(Number);
                const date = new Date(year, (month || 1) - 1, 1);
                return date.toLocaleDateString(undefined, { month: 'long', year: 'numeric' });
            }

            function generateReconciliation(monthValue) {
                if (!monthValue) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'info', title: 'Select month', text: 'Please select a month to generate the reconciliation report.', confirmButtonText: 'OK' });
                    } else {
                        alert('Please select a month to generate the reconciliation report.');
                    }
                    return;
                }

                const monthPrefix = `${monthValue}`;
                const filtered = dispatchRecords.filter(record => record.dispatchDate && record.dispatchDate.startsWith(monthPrefix));

                lastReconciliationMonth = monthValue;
                lastReconciliationRecords = filtered;
                reconciliationMonthLabel.textContent = formatMonthLabel(monthValue);

                if (!filtered.length) {
                    resetReconciliationEmptyState('No dispatch data available for the selected month.');
                    return;
                }

                clearReconciliationEmptyState();

                const rowsFragment = document.createDocumentFragment();

                const sortedRecords = [...filtered].sort((a, b) => {
                    const dateA = a.dispatchDate || '';
                    const dateB = b.dispatchDate || '';
                    if (dateA !== dateB) {
                        return dateA.localeCompare(dateB);
                    }

                    const stationA = (a.receivingStation || '').toLowerCase();
                    const stationB = (b.receivingStation || '').toLowerCase();
                    if (stationA !== stationB) {
                        return stationA.localeCompare(stationB);
                    }

                    return (a.productType || '').localeCompare(b.productType || '');
                });

                sortedRecords.forEach(record => {
                    const row = document.createElement('tr');
                    const formattedDate = record.formattedDate || (record.dispatchDate ? record.dispatchDate.split('-').reverse().join('-') : '—');

                    row.innerHTML = `
                        <td>${formattedDate}</td>
                        <td>${record.receivingStation || '—'}</td>
                        <td>${record.productType || '—'}</td>
                        <td>${formatNumber(record.quantity)}</td>
                        <td>${record.loadingFacility || '—'}</td>
                        <td>${record.loadedFrom || '—'}</td>
                        <td>${record.brvNumber || '—'}</td>
                    `;

                    rowsFragment.appendChild(row);
                });

                reconciliationTableBody.appendChild(rowsFragment);
            }

            dispatchForm.addEventListener('reset', function () {
                setTimeout(() => {
                    ensureDateDefault();
                    updateWaybillMeta();
                }, 0);
            });

            waybillInput?.addEventListener('change', updateWaybillMeta);

            generateReconciliationBtn.addEventListener('click', function () {
                generateReconciliation(reconciliationMonthInput.value);
            });

            exportDispatchPdfBtn?.addEventListener('click', function () {
                if (!dispatchRecords.length) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'info', title: 'No data', text: 'Please record at least one dispatch before exporting.', confirmButtonText: 'OK' });
                    } else {
                        alert('Please record at least one dispatch before exporting.');
                    }
                    return;
                }

                if (typeof html2pdf === 'undefined') {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'error', title: 'Export failed', text: 'PDF library not loaded. Please check your network connection and try again.', confirmButtonText: 'OK' });
                    } else {
                        alert('PDF library not loaded. Please check your network connection and try again.');
                    }
                    return;
                }

                const options = {
                    margin: 0.5,
                    filename: `dispatch-ledger-${new Date().toISOString().slice(0, 10)}.pdf`,
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'in', format: 'letter', orientation: 'landscape' },
                };

                html2pdf().set(options).from(dispatchTableWrapper).save().then(function () {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'success', title: 'Done', text: 'Dispatch ledger PDF downloaded.', timer: 2000, showConfirmButton: false });
                    }
                });
            });

            printDispatchLedgerBtn?.addEventListener('click', function () {
                if (!dispatchRecords.length) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'info', title: 'No data', text: 'There are no dispatch records to print.', confirmButtonText: 'OK' });
                    } else {
                        alert('There are no dispatch records to print.');
                    }
                    return;
                }

                const printWindow = window.open('', '_blank', 'width=1200,height=900');
                if (!printWindow) {
                    return;
                }

                printWindow.document.write(`
                    <html>
                        <head>
                            <title>Dispatch Ledger</title>
                            <style>
                                body { font-family: Arial, sans-serif; padding: 20px; }
                                h2 { margin-bottom: 16px; }
                                table { width: 100%; border-collapse: collapse; font-size: 12px; }
                                th, td { border: 1px solid #333; padding: 6px; text-align: left; }
                                th { background: #0b2e6f; color: #fff; }
                                tbody tr:nth-child(even) { background: #f1f4fb; }
                            </style>
                        </head>
                        <body>
                            <h2>Dispatch Ledger</h2>
                            ${dispatchTableWrapper.innerHTML}
                        </body>
                    </html>
                `);
                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
            });

            exportReconciliationPdfBtn?.addEventListener('click', function () {
                if (!lastReconciliationRecords.length) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'info', title: 'Generate first', text: 'Generate the monthly reconciliation before exporting.', confirmButtonText: 'OK' });
                    } else {
                        alert('Generate the monthly reconciliation before exporting.');
                    }
                    return;
                }

                if (typeof html2pdf === 'undefined') {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'error', title: 'Export failed', text: 'PDF library not loaded. Please check your network connection and try again.', confirmButtonText: 'OK' });
                    } else {
                        alert('PDF library not loaded. Please check your network connection and try again.');
                    }
                    return;
                }

                const options = {
                    margin: 0.5,
                    filename: `dispatch-reconciliation-${lastReconciliationMonth || 'summary'}.pdf`,
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'in', format: 'letter', orientation: 'landscape' },
                };

                html2pdf().set(options).from(reconciliationTableWrapper).save().then(function () {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'success', title: 'Done', text: 'Reconciliation PDF downloaded.', timer: 2000, showConfirmButton: false });
                    }
                });
            });

            printReconciliationBtn?.addEventListener('click', function () {
                if (!lastReconciliationRecords.length) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'info', title: 'Generate first', text: 'Generate the monthly reconciliation before printing.', confirmButtonText: 'OK' });
                    } else {
                        alert('Generate the monthly reconciliation before printing.');
                    }
                    return;
                }

                const printWindow = window.open('', '_blank', 'width=1200,height=900');
                if (!printWindow) {
                    return;
                }

                printWindow.document.write(`
                    <html>
                        <head>
                            <title>Monthly Dispatch Reconciliation</title>
                            <style>
                                body { font-family: Arial, sans-serif; padding: 20px; }
                                h2 { margin-bottom: 4px; }
                                h4 { margin: 8px 0 16px; color: #0b2e6f; }
                                table { width: 100%; border-collapse: collapse; font-size: 12px; }
                                th, td { border: 1px solid #333; padding: 6px; text-align: left; }
                                th { background: #0b2e6f; color: #fff; }
                                tbody tr:nth-child(even) { background: #f1f4fb; }
                            </style>
                        </head>
                        <body>
                            <h2>Monthly Dispatch Reconciliation</h2>
                            <h4>${formatMonthLabel(lastReconciliationMonth)}</h4>
                            ${reconciliationTableWrapper.innerHTML}
                        </body>
                    </html>
                `);
                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
            });

            window.addEventListener('beforeunload', function () {
                dispatchRecords.forEach(record => {
                    if (record.isObjectUrl && record.waybillUrl) {
                        URL.revokeObjectURL(record.waybillUrl);
                    }
                });
            });

            dispatchTableBody.addEventListener('click', function (event) {
                const target = event.target.closest('[data-role="view-dispatch"]');
                if (!target) return;
                const row = target.closest('tr');
                if (row && row.dataset.role === 'dispatch-row') {
                    showDispatchDetailsFromRow(row);
                } else if (target.dataset.recordId) {
                    showDispatchDetails(target.dataset.recordId);
                }
            });

            modalCloseButtons.forEach(button => {
                button.addEventListener('click', hideDispatchDetails);
            });

            detailsModal?.addEventListener('click', function (event) {
                if (event.target === detailsModal) {
                    hideDispatchDetails();
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && detailsModal?.classList.contains('is-visible')) {
                    hideDispatchDetails();
                }
            });

            ensureDateDefault();
            buildDispatchRecordsFromTable();

            var successMessage = @json(session('success'));
            var errorMessage = @json(session('error'));
            var canUseSwal = typeof Swal !== 'undefined';
            if (successMessage) {
                if (canUseSwal) {
                    Swal.fire({ icon: 'success', title: 'Success!', text: successMessage, confirmButtonText: 'OK' });
                } else {
                    console.info('Success:', successMessage);
                }
            }
            if (errorMessage) {
                if (canUseSwal) {
                    Swal.fire({ icon: 'error', title: 'Oops...', text: errorMessage, confirmButtonText: 'Try Again' });
                } else {
                    console.error('Error:', errorMessage);
                }
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initDispatchModule);
        } else {
            initDispatchModule();
        }
    </script>
@endpush
