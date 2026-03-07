    @extends('layouts.vertical', [
    'page_title' => 'Stock Management',
    'mode' => $mode ?? '',
    'demo' => $demo ?? '',
])

@section('css')
    <style>
        .stock-card {
            background: linear-gradient(135deg, #031739 0%, #083b8a 100%);
            padding: 1px;
            border-radius: 24px;
            box-shadow: 0 26px 44px rgba(3, 26, 67, 0.34);
        }

        .stock-card__inner {
            background: #f6f8ff;
            border-radius: 23px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .stock-card__header {
            background: linear-gradient(94deg, rgba(3, 23, 63, 0.96) 0%, rgba(10, 58, 138, 0.98) 55%, rgba(3, 23, 63, 0.96) 100%);
            padding: 1.8rem 2.6rem;
            color: #ffffff;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1.2rem;
            flex-wrap: wrap;
        }

        .stock-card__header-actions {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            margin-left: auto;
        }

        .stock-card__header-main {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .stock-card__title {
            margin: 0;
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: 1.1px;
            text-transform: uppercase;
        }

        .stock-card__subtitle {
            margin: 0;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.45px;
            color: rgba(232, 241, 255, 0.8);
        }

        .stock-card__station {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.45rem 0.9rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.24), 0 14px 26px rgba(3, 26, 67, 0.28);
            backdrop-filter: blur(6px);
        }

        .stock-card__station-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.46px;
            color: rgba(232, 241, 255, 0.75);
        }

        .stock-card__station-name {
            font-size: 0.92rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #ffffff;
        }

        .stock-alert-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            border-radius: 999px;
            padding: 0.55rem 1rem;
            border: 1px solid rgba(255, 255, 255, 0.38);
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            letter-spacing: 0.5px;
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 600;
            cursor: pointer;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.18), 0 12px 26px rgba(3, 26, 67, 0.32);
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .stock-alert-btn i {
            font-size: 1rem;
        }

        .stock-alert-btn__badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 22px;
            height: 22px;
            padding: 0 0.4rem;
            border-radius: 999px;
            background: linear-gradient(90deg, #ff5f6d 0%, #ffc371 100%);
            color: #0a1d44;
            font-size: 0.68rem;
            font-weight: 700;
            box-shadow: 0 6px 14px rgba(255, 111, 90, 0.42);
        }

        .stock-alert-btn:hover {
            transform: translateY(-1px);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.24), 0 16px 28px rgba(3, 26, 67, 0.42);
        }

        .stock-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.6rem;
            margin: 2.4rem;
            margin-bottom: 1.1rem;
        }

        .stock-summary__item {
            position: relative;
            border-radius: 22px;
            padding: 1.6rem 1.8rem;
            color: #ffffff;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
            background: linear-gradient(120deg, rgba(4, 31, 94, 0.94) 0%, rgba(16, 90, 203, 0.88) 52%, rgba(4, 31, 94, 0.92) 100%);
            box-shadow: 0 20px 42px rgba(7, 37, 96, 0.28);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transform: translateY(0);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .stock-summary__item::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.35), transparent 55%);
            opacity: 0.9;
            pointer-events: none;
        }

        .stock-summary__item::after {
            content: '';
            position: absolute;
            inset: 1px;
            border-radius: 21px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            pointer-events: none;
        }

        .stock-summary__item:hover {
            transform: translateY(-6px);
            box-shadow: 0 28px 54px rgba(7, 37, 96, 0.4);
        }

        .stock-summary__meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 1;
        }

        .stock-summary__icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: inherit;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.2);
        }

        .stock-summary__label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.55px;
            color: rgba(232, 242, 255, 0.75);
            margin: 0;
        }

        .stock-summary__value {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 0.4px;
            color: #ffffff;
            position: relative;
            z-index: 1;
        }

        .stock-summary__footer {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.45px;
            color: rgba(232, 244, 255, 0.8);
            position: relative;
            z-index: 1;
        }

        .stock-summary__item[data-product="AGO"] {
            background: linear-gradient(120deg, rgba(15, 60, 130, 0.95) 0%, rgba(48, 141, 255, 0.94) 58%, rgba(5, 30, 78, 0.9) 100%);
        }

        .stock-summary__item[data-product="PMS"] {
            background: linear-gradient(118deg, rgba(100, 27, 126, 0.95) 0%, rgba(221, 96, 176, 0.94) 54%, rgba(51, 8, 67, 0.9) 100%);
        }

        .stock-form {
            margin: 0 2.4rem 2.2rem;
            background: #ffffff;
            border-radius: 20px;
            padding: 1.8rem 2rem;
            box-shadow: 0 18px 42px rgba(7, 32, 86, 0.14);
            border: 1px solid rgba(12, 38, 96, 0.08);
        }

        .stock-form__grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.2rem 1.6rem;
        }

        .stock-form label {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.48px;
            color: #0a2048;
            display: block;
            margin-bottom: 0.4rem;
        }

        .stock-form input,
        .stock-form select {
            width: 100%;
            border-radius: 10px;
            border: 1px solid rgba(12, 36, 79, 0.18);
            background: #f5f8ff;
            padding: 0.55rem 0.75rem;
            font-size: 0.84rem;
            color: #0a1d44;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .stock-form input:focus,
        .stock-form select:focus {
            outline: none;
            border-color: #2b6def;
            box-shadow: 0 0 0 3px rgba(43, 109, 239, 0.2);
        }

        .stock-form__station-readonly {
            background: rgba(9, 28, 64, 0.06);
            cursor: not-allowed;
            font-weight: 600;
        }

        .stock-form__actions {
            margin-top: 1.6rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.9rem;
            justify-content: flex-end;
        }

        .stock-btn {
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

        .stock-btn--sm {
            min-width: auto;
            padding: 0.45rem 0.9rem;
            font-size: 0.72rem;
        }

        .stock-btn--primary {
            background: linear-gradient(88deg, #ff7a1a 0%, #ffb347 100%);
            color: #0a1d44;
            box-shadow: 0 12px 22px rgba(255, 135, 54, 0.32);
        }

        .stock-btn--primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 26px rgba(255, 135, 54, 0.4);
        }

        .stock-btn--ghost {
            background: transparent;
            border: 1px solid rgba(9, 28, 64, 0.25);
            color: #0b1c3f;
        }

        .stock-btn--ghost:hover {
            transform: translateY(-1px);
            border-color: rgba(9, 28, 64, 0.45);
            box-shadow: 0 8px 18px rgba(8, 29, 73, 0.16);
        }

        .stock-ledger {
            margin: 0 2.4rem 2.8rem;
            background: #ffffff;
            border-radius: 20px;
            padding: 1.4rem 1.8rem 2rem;
            box-shadow: 0 20px 46px rgba(7, 34, 86, 0.14);
            border: 1px solid rgba(12, 38, 96, 0.08);
        }

        .stock-ledger__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1.2rem;
        }

        .stock-ledger__title {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 700;
            color: #0a1d44;
            letter-spacing: 0.4px;
        }

        .stock-ledger__actions {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            flex-wrap: wrap;
        }

        .stock-ledger .table-responsive {
            overflow-x: auto;
        }

        .stock-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1100px;
            color: #0a1d44;
            font-size: 0.78rem;
        }

        .stock-table th,
        .stock-table td {
            border: 1px solid rgba(16, 44, 98, 0.12);
            padding: 0.55rem 0.6rem;
            text-align: left;
        }

        .stock-table th {
            background: #0b2e6f;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.45px;
            font-weight: 600;
        }

        .stock-table tbody tr:nth-child(even) {
            background: #f5f7ff;
        }

        .stock-table tbody tr:nth-child(odd) {
            background: #ffffff;
        }

        .stock-empty {
            text-align: center;
            padding: 1.6rem;
            color: rgba(9, 31, 74, 0.55);
            font-style: italic;
        }

        @media (max-width: 1200px) {
            .stock-summary {
                margin: 2rem 1.8rem 1rem;
            }

            .stock-form,
            .stock-ledger {
                margin: 0 1.8rem 2.4rem;
            }
        }

        @media (max-width: 768px) {
            .stock-card__header {
                padding: 1.6rem 1.8rem;
            }

            .stock-summary,
            .stock-form,
            .stock-ledger {
                margin: 0 1.2rem 1.8rem;
            }

            .stock-form__grid {
                grid-template-columns: 1fr;
            }

            .stock-form__actions {
                justify-content: stretch;
            }

            .stock-btn {
                flex: 1 1 auto;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="stock-card">
            <div class="stock-card__inner">
                @php
                    $subUser = Auth::guard('company_sub_user')->user();
                    $assignedStationName = $managerStationName
                        ?? $stationName
                        ?? optional(Auth::user())->station
                        ?? ($subUser && $subUser->fuelStation ? $subUser->fuelStation->name : null)
                        ?? optional(Auth::guard('sub_user')->user())->station;
                @endphp
                <div class="stock-card__header">
                    <div class="stock-card__header-main">
                        <h2 class="stock-card__title">Recieving Stock</h2>
                        <p class="stock-card__subtitle">{{ !empty($isManagerRestricted) ? 'Track receipts and available stock for your station' : 'Track receipts and available stock across all stations' }}</p>
                    </div>
                    <div class="stock-card__header-actions">
                        @if ($assignedStationName)
                            <div class="stock-card__station" data-role="assigned-station">
                                <span class="stock-card__station-label">Station</span>
                                <span class="stock-card__station-name">{{ $assignedStationName }}</span>
                            </div>
                        @endif
                        <button type="button" class="stock-alert-btn" title="View Notifications">
                            <i class="ri-notification-3-line"></i>
                            <span>Alerts</span>
                            <span class="stock-alert-btn__badge">5</span>
                        </button>
                    </div>
                </div>

                <div class="stock-summary" id="stockSummary">
                    <div class="stock-summary__item" data-product="AGO">
                        <div class="stock-summary__meta">
                            <div class="stock-summary__label">AGO (Diesel) in Stock</div>
                            <div class="stock-summary__icon">
                                <i class="ri-oil-line"></i>
                            </div>
                        </div>
                        <div class="stock-summary__value" data-role="stock-balance-AGO">{{ number_format($agoBalance ?? 0, 2) }} L</div>
                        <div class="stock-summary__footer">Latest deliveries update running balance instantly</div>
                    </div>
                    <div class="stock-summary__item" data-product="PMS">
                        <div class="stock-summary__meta">
                            <div class="stock-summary__label">PMS (Petrol) in Stock</div>
                            <div class="stock-summary__icon">
                                <i class="ri-gas-station-line"></i>
                            </div>
                        </div>
                        <div class="stock-summary__value" data-role="stock-balance-PMS">{{ number_format($pmsBalance ?? 0, 2) }} L</div>
                        <div class="stock-summary__footer">Monitor pump-ready volumes across stations</div>
                    </div>
                </div>

                <div class="stock-form">
                    <form id="stockIntakeForm" action="{{ route('company.fuel.stocks.store') }}" method="POST">
                        @csrf
                        <div class="stock-form__grid">
                            <div>
                                <label for="deliveryDate">Product Discharged Date</label>
                                <input type="date" id="deliveryDate" name="delivery_date" required>
                            </div>
                            <div>
                                <label for="brvNumber">BRV Number</label>
                                <input type="text" id="brvNumber" name="brv_number" placeholder="Enter BRV" required>
                            </div>
                            <div>
                                <label for="driverName">Driver Name</label>
                                <input type="text" id="driverName" name="driver_name" placeholder="Driver Name" required>
                            </div>
                            <div>
                                <label for="driverPhone">Driver Phone</label>
                                <input type="tel" id="driverPhone" name="driver_phone" placeholder="e.g. +233 20 000 0000" required>
                            </div>
                            <div>
                                <label for="invoiceNumber">Invoice Number</label>
                                <input type="text" id="invoiceNumber" name="invoice_number" placeholder="e.g. INV-00123A" required>
                            </div>
                            <div>
                                <label for="productType">Product Type</label>
                                <select id="productType" name="product_type" required>
                                    <option value="" disabled selected>Select product</option>
                                    <option value="AGO">AGO</option>
                                    <option value="PMS">PMS</option>
                                </select>
                            </div>
                            <div>
                                <label for="dispatched_quantity">Dispatched Quantity (Litres)</label>
                                <input type="number" id="dispatched_quantity" name="dispatched_quantity" min="0" step="0.01" placeholder="0.00">
                            </div>
                            <div>
                                <label for="quantity">Received Quantity (Litres)</label>
                                <input type="number" id="quantity" name="received_quantity" min="0" step="0.01" placeholder="0.00" required>
                            </div>
                            <div>
                                <label for="station">Receiving Station</label>
                                @if (!empty($isManagerRestricted) && !empty($managerStationId))
                                    <input type="text" id="stationDisplay" class="stock-form__station-readonly" value="{{ $managerStationName ?? 'Your station' }} ({{ $stations->first()->code ?? '' }})" readonly>
                                    <input type="hidden" name="station_id" value="{{ $managerStationId }}">
                                @else
                                    <select id="station" name="station_id" required>
                                        <option value="" disabled selected>Select station</option>
                                        @foreach (($stations ?? collect()) as $station)
                                            <option value="{{ $station->id }}"
                                                    data-manager="{{ $station->activeManager ? $station->activeManager->full_name : '' }}">
                                                {{ $station->name }} ({{ $station->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                            <div>
                                <label for="inspectedBy">Inspected By (Station Manager)</label>
                                @php
                                    $defaultInspectedBy = '';
                                    if (!empty($isManagerRestricted) && ($stations ?? collect())->isNotEmpty()) {
                                        $firstStation = $stations->first();
                                        $defaultInspectedBy = $firstStation && $firstStation->activeManager ? $firstStation->activeManager->full_name : '';
                                    }
                                @endphp
                                <input type="text" id="inspectedBy" name="inspected_by" placeholder="Station Manager" value="{{ $defaultInspectedBy }}" required>
                            </div>
                        </div>
                        <div class="stock-form__actions">
                            <button type="reset" class="stock-btn stock-btn--ghost">Clear</button>
                            <button type="submit" class="stock-btn stock-btn--primary">Record Stock</button>
                        </div>
                    </form>
                </div>

                <div class="stock-ledger">
                    <div class="stock-ledger__header">
                        <h3 class="stock-ledger__title">Stock Receipts Ledger</h3>
                        <div class="stock-ledger__actions">
                            <button type="button" class="stock-btn stock-btn--ghost stock-btn--sm" id="printStockLedgerBtn">
                                <span class="me-1">Print</span>
                                <i class="ri-printer-line"></i>
                            </button>
                            <button type="button" class="stock-btn stock-btn--ghost stock-btn--sm" id="exportStockExcelBtn">
                                <span class="me-1">Export Excel</span>
                                <i class="ri-file-excel-2-line"></i>
                            </button>
                            <button type="button" class="stock-btn stock-btn--primary stock-btn--sm" id="exportStockPdfBtn">
                                <span class="me-1">Export PDF</span>
                                <i class="ri-file-download-line"></i>
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive" id="stockTableWrapper">
                        <table class="stock-table" id="stockTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Discharged Date</th>
                                    <th>BRV Number</th>
                                    <th>Driver Name</th>
                                    <th>Invoice Number</th>
                                    <th>Product</th>
                                    <th>Dispatched (L)</th>
                                    <th>Received (L)</th>
                                    <th>Receiving Station</th>
                                    <th>Driver Phone</th>
                                    <th>Inspected By</th>
                                    <th>Running Balance (L)</th>
                                </tr>
                            </thead>
                            <tbody data-role="stock-tbody">
                                @if(isset($stocks) && count($stocks) > 0)
                                    @foreach ($stocks as $index => $stock)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $stock->delivery_date ? $stock->delivery_date->format('Y-m-d') : '-' }}</td>
                                            <td>{{ $stock->brv_number }}</td>
                                            <td>{{ $stock->driver_name }}</td>
                                            <td>{{ $stock->invoice_number }}</td>
                                            <td>
                                                <span class="badge {{ $stock->product_type === 'AGO' ? 'bg-primary' : 'bg-danger' }}">
                                                    {{ $stock->product_type }}
                                                </span>
                                            </td>
                                            <td>{{ number_format($stock->dispatched_quantity, 2) }}</td>
                                            <td>{{ number_format($stock->received_quantity, 2) }}</td>
                                            <td>{{ $stock->station ? $stock->station->name : '-' }}</td>
                                            <td>{{ $stock->driver_phone }}</td>
                                            <td>{{ $stock->inspected_by }}</td>
                                            <td>{{ number_format($stock->running_balance, 2) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="stock-empty">
                                        <td colspan="11">No stock receipts recorded yet.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="printPreviewModal" tabindex="-1" aria-labelledby="printPreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="printPreviewModalLabel">Stock Ledger Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-center align-items-center py-5" id="printPreviewLoading">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Generating preview...</span>
                        </div>
                    </div>
                    <iframe id="printPreviewFrame" class="w-100 border-0 d-none" style="min-height: 65vh;"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="stock-btn stock-btn--ghost stock-btn--sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="stock-btn stock-btn--primary stock-btn--sm" id="printPreviewConfirmBtn" disabled>
                        <span class="me-1">Print</span>
                        <i class="ri-printer-line"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const stationSelect = document.getElementById('station');
            const inspectedByInput = document.getElementById('inspectedBy');

            if (stationSelect && inspectedByInput) {
                stationSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const managerName = selectedOption.getAttribute('data-manager') || '';
                    inspectedByInput.value = managerName;
                });

                if (stationSelect.value) {
                    const selectedOption = stationSelect.options[stationSelect.selectedIndex];
                    const managerName = selectedOption.getAttribute('data-manager') || '';
                    inspectedByInput.value = managerName;
                }
            }

            const printPreviewModalEl = document.getElementById('printPreviewModal');
            const printPreviewFrame = document.getElementById('printPreviewFrame');
            const printStockLedgerBtn = document.getElementById('printStockLedgerBtn');
            const exportStockPdfBtn = document.getElementById('exportStockPdfBtn');
            const exportStockExcelBtn = document.getElementById('exportStockExcelBtn');
            const stockTableWrapper = document.getElementById('stockTableWrapper');
            const stockTable = document.getElementById('stockTable');

            let printPreviewModal = null;
            let currentPreviewUrl = null;

            if (printPreviewModalEl && window.bootstrap && window.bootstrap.Modal) {
                printPreviewModal = new window.bootstrap.Modal(printPreviewModalEl);
                printPreviewModalEl.addEventListener('hidden.bs.modal', () => {
                    if (currentPreviewUrl) { URL.revokeObjectURL(currentPreviewUrl); currentPreviewUrl = null; }
                    if (printPreviewFrame) printPreviewFrame.src = 'about:blank';
                });
            }

            if (printStockLedgerBtn && stockTableWrapper) {
                printStockLedgerBtn.addEventListener('click', function() {
                    const content = stockTableWrapper.innerHTML;
                    const printWindow = window.open('', '_blank');
                    printWindow.document.write('<!DOCTYPE html><html><head><title>Stock Ledger</title><style>body{font-family:Arial,sans-serif;padding:20px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #ddd;padding:8px;text-align:left}th{background:#f2f2f2}</style></head><body><h1>Stock Ledger</h1>' + content + '</body></html>');
                    printWindow.document.close();
                    printWindow.print();
                });
            }

            function escapeCsvCell(str) {
                if (str == null) return '';
                str = String(str).trim();
                if (/[",\n\r]/.test(str)) return '"' + str.replace(/"/g, '""') + '"';
                return str;
            }

            if (exportStockExcelBtn && stockTable) {
                exportStockExcelBtn.addEventListener('click', function() {
                    var rows = [];
                    var thead = stockTable.querySelector('thead tr');
                    var tbody = stockTable.querySelector('tbody');
                    if (thead) {
                        var headerCells = thead.querySelectorAll('th');
                        var headerRow = [].map.call(headerCells, function(th) { return escapeCsvCell(th.textContent); }).join(',');
                        rows.push(headerRow);
                    }
                    if (tbody) {
                        var dataRows = tbody.querySelectorAll('tr:not(.stock-empty)');
                        dataRows.forEach(function(tr) {
                            var cells = tr.querySelectorAll('td');
                            if (cells.length) {
                                var row = [].map.call(cells, function(td) {
                                    var text = td.textContent || '';
                                    var badge = td.querySelector('.badge');
                                    if (badge) text = badge.textContent || text;
                                    return escapeCsvCell(text);
                                }).join(',');
                                rows.push(row);
                            }
                        });
                    }
                    if (rows.length === 0) {
                        if (typeof Swal !== 'undefined') Swal.fire({ icon: 'info', title: 'No data', text: 'No stock receipts to export.' });
                        else alert('No stock receipts to export.');
                        return;
                    }
                    var csv = '\uFEFF' + rows.join('\r\n');
                    var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                    var link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = 'stock-receipts-ledger-' + new Date().toISOString().slice(0, 10) + '.csv';
                    link.click();
                    URL.revokeObjectURL(link.href);
                    if (typeof Swal !== 'undefined') Swal.fire({ icon: 'success', title: 'Done', text: 'Excel file downloaded.', timer: 2000, showConfirmButton: false });
                });
            }

            function loadPdfLibrary(callback) {
                if (typeof html2pdf !== 'undefined') { callback(); return; }
                if (window._stockPdfLoading) { window._stockPdfLoading.push(callback); return; }
                window._stockPdfLoading = [callback];
                var s = document.createElement('script');
                s.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js';
                s.crossOrigin = 'anonymous';
                s.onload = function() { (window._stockPdfLoading || []).forEach(function(cb) { cb(); }); window._stockPdfLoading = null; };
                s.onerror = function() {
                    window._stockPdfLoading = null;
                    if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Export failed', text: 'Could not load PDF library. Try Export Excel instead.' });
                    else alert('Could not load PDF library. Try Export Excel instead.');
                };
                document.head.appendChild(s);
            }

            if (exportStockPdfBtn && stockTableWrapper) {
                exportStockPdfBtn.addEventListener('click', function() {
                    var btn = this;
                    var originalHtml = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = '<span class="me-1">Loading...</span><i class="ri-loader-4-line ri-spin"></i>';

                    loadPdfLibrary(function() {
                        btn.innerHTML = '<span class="me-1">Exporting...</span><i class="ri-loader-4-line ri-spin"></i>';
                        var opt = {
                            margin: [0.5, 0.5, 0.5, 0.5],
                            filename: 'stock-receipts-ledger-' + new Date().toISOString().slice(0, 10) + '.pdf',
                            image: { type: 'jpeg', quality: 0.98 },
                            html2canvas: { scale: 2, useCORS: true, allowTaint: true, logging: false, scrollX: 0, scrollY: 0, windowWidth: stockTableWrapper.scrollWidth, windowHeight: stockTableWrapper.scrollHeight },
                            jsPDF: { unit: 'in', format: 'a4', orientation: 'landscape' },
                            pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
                        };
                        var clone = stockTableWrapper.cloneNode(true);
                        clone.style.overflow = 'visible';
                        clone.style.width = stockTableWrapper.offsetWidth + 'px';
                        clone.style.position = 'absolute';
                        clone.style.left = '-9999px';
                        clone.style.top = '0';
                        document.body.appendChild(clone);
                        html2pdf().set(opt).from(clone).save()
                            .then(function() {
                                if (clone.parentNode) document.body.removeChild(clone);
                                btn.disabled = false;
                                btn.innerHTML = originalHtml;
                                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'success', title: 'Done', text: 'PDF downloaded successfully.', timer: 2000, showConfirmButton: false });
                            })
                            .catch(function(err) {
                                if (clone.parentNode) document.body.removeChild(clone);
                                btn.disabled = false;
                                btn.innerHTML = originalHtml;
                                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Export failed', text: (err && err.message) || 'Could not generate PDF. Try Export Excel.' });
                                else alert('Could not generate PDF. Try Export Excel.');
                            });
                    });
                });
            }

            var successMessage = @json(session('success'));
            var errorMessage = @json(session('error'));
            var canUseSwal = typeof Swal !== 'undefined';
            if (successMessage) { if (canUseSwal) Swal.fire({ icon: 'success', title: 'Success!', text: successMessage, confirmButtonText: 'OK' }); else console.info('Success:', successMessage); }
            if (errorMessage) { if (canUseSwal) Swal.fire({ icon: 'error', title: 'Oops...', text: errorMessage, confirmButtonText: 'Try Again' }); else console.error('Error:', errorMessage); }
        });
    </script>
@endpush