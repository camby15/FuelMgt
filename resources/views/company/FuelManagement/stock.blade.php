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
                    $assignedStationName = $stationName
                        ?? optional(Auth::user())->station
                        ?? optional(Auth::guard('company_sub_user')->user())->station
                        ?? optional(Auth::guard('sub_user')->user())->station;
                @endphp
                <div class="stock-card__header">
                    <div class="stock-card__header-main">
                        <h2 class="stock-card__title">Recieving Stock</h2>
                        <p class="stock-card__subtitle">Track receipts and available stock across all stations</p>
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
                        <div class="stock-summary__value" data-role="stock-balance-AGO">0.00 L</div>
                        <div class="stock-summary__footer">Latest deliveries update running balance instantly</div>
                    </div>
                    <div class="stock-summary__item" data-product="PMS">
                        <div class="stock-summary__meta">
                            <div class="stock-summary__label">PMS (Petrol) in Stock</div>
                            <div class="stock-summary__icon">
                                <i class="ri-gas-station-line"></i>
                            </div>
                        </div>
                        <div class="stock-summary__value" data-role="stock-balance-PMS">0.00 L</div>
                        <div class="stock-summary__footer">Monitor pump-ready volumes across stations</div>
                    </div>
                </div>

                <div class="stock-form">
                    <form id="stockIntakeForm">
                        <div class="stock-form__grid">
                            <div>
                                <label for="deliveryDate">Product Discharged Date</label>
                                <input type="date" id="deliveryDate" name="deliveryDate" required>
                            </div>
                            <div>
                                <label for="brvNumber">BRV Number</label>
                                <input type="text" id="brvNumber" name="brvNumber" placeholder="Enter BRV" required>
                            </div>
                            <div>
                                <label for="driverName">Driver Name</label>
                                <input type="text" id="driverName" name="driverName" placeholder="Driver Name" required>
                            </div>
                            <div>
                                <label for="driverPhone">Driver Phone</label>
                                <input type="tel" id="driverPhone" name="driverPhone" placeholder="e.g. +233 20 000 0000" required>
                            </div>
                            <div>
                                <label for="invoiceNumber">Invoice Number</label>
                                <input type="text" id="invoiceNumber" name="invoiceNumber" placeholder="e.g. INV-00123A" required>
                            </div>
                            <div>
                                <label for="productType">Product Type</label>
                                <select id="productType" name="productType" required>
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
                                <input type="number" id="quantity" name="quantity" min="0" step="0.01" placeholder="0.00" required>
                            </div>
                            <div>
                                <label for="station">Receiving Station</label>
                                <select id="station" name="station" required data-manager-station="{{ Auth::user()->station ?? '' }}">
                                    
                                </select>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const stationSelect = document.getElementById('station');
                                        const managerStation = stationSelect.dataset.managerStation;
                                        
                                        if (managerStation) {
                                            // Find and select the manager's station
                                            for (let option of stationSelect.options) {
                                                if (option.value === managerStation) {
                                                    option.selected = true;
                                                    // Make the field read-only after selection
                                                    stationSelect.disabled = true;
                                                    break;
                                                }
                                            }
                                        }
                                    });
                                </script>
                            </div>
                            <div>
                                <label for="inspectedBy">Inspected By (Station Manager)</label>
                                <input type="text" id="inspectedBy" name="inspectedBy" placeholder="Station Manager" required>
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
                                <tr class="stock-empty">
                                    <td colspan="11">No stock receipts recorded yet.</td>
                                </tr>
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

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-YcsIPmK9jGTf3I9P4MBDl2SmS0FZtBx8y8mk4luzFuJdvByCnWJIRedKgNqUK3MUY14CzO2D93BYJk50xKp3+w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const stockForm = document.getElementById('stockIntakeForm');
            const tableBody = document.querySelector('[data-role="stock-tbody"]');
            const deliveryDateInput = document.getElementById('deliveryDate');
            const balanceDisplays = {
                AGO: document.querySelector('[data-role="stock-balance-AGO"]'),
                PMS: document.querySelector('[data-role="stock-balance-PMS"]'),
            };
            const printPreviewModalEl = document.getElementById('printPreviewModal');
            const printPreviewFrame = document.getElementById('printPreviewFrame');
            const printPreviewLoading = document.getElementById('printPreviewLoading');
            const printPreviewConfirmBtn = document.getElementById('printPreviewConfirmBtn');
            const printStockLedgerBtn = document.getElementById('printStockLedgerBtn');
            const exportStockPdfBtn = document.getElementById('exportStockPdfBtn');
            const stockTableWrapper = document.getElementById('stockTableWrapper');

            const balances = {
                AGO: 0,
                PMS: 0,
            };

            const sampleLedgerRecords = [
                {
                    deliveryDate: '2025-03-01',
                    brvNumber: 'BRV-1201',
                    driverName: 'Yaw Adusei',
                    driverPhone: '+233 24 700 1122',
                    invoiceNumber: 'INV-23001',
                    productType: 'AGO',
                    dispatchedQuantity: 12500,
                    quantity: 12380,
                    station: 'Navrongo-2 Station',
                    inspectedBy: 'Helen Bawa',
                },
                {
                    deliveryDate: '2025-03-02',
                    brvNumber: 'BRV-1208',
                    driverName: 'Aminatu Fuseini',
                    driverPhone: '+233 26 445 7788',
                    invoiceNumber: 'INV-23006',
                    productType: 'AGO',
                    dispatchedQuantity: 9800,
                    quantity: 9645,
                    station: 'Wapuli Station',
                    inspectedBy: 'Rahim Sulemana',
                },
                {
                    deliveryDate: '2025-03-03',
                    brvNumber: 'BRV-2214',
                    driverName: 'Priscilla Anane',
                    driverPhone: '+233 20 803 4410',
                    invoiceNumber: 'INV-23011',
                    productType: 'PMS',
                    dispatchedQuantity: 11200,
                    quantity: 11090,
                    station: 'Bamvin Station',
                    inspectedBy: 'Jonah Laar',
                },
            ];

            let counter = 0;
            let printPreviewModal = null;
            let currentPreviewUrl = null;

            if (printPreviewModalEl && window.bootstrap && window.bootstrap.Modal) {
                printPreviewModal = new window.bootstrap.Modal(printPreviewModalEl);

                printPreviewModalEl.addEventListener('hidden.bs.modal', () => {
                    if (currentPreviewUrl) {
                        URL.revokeObjectURL(currentPreviewUrl);
                        currentPreviewUrl = null;
                    }

                    if (printPreviewFrame) {
                        printPreviewFrame.src = 'about:blank';
                        printPreviewFrame.classList.add('d-none');
                    }

                    if (printPreviewLoading) {
                        printPreviewLoading.classList.remove('d-none');
                    }

                    if (printPreviewConfirmBtn) {
                        printPreviewConfirmBtn.disabled = true;
                    }
                });
            }

            function formatNumber(value) {
                return Number(value || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            function refreshSummaries() {
                Object.entries(balanceDisplays).forEach(([product, el]) => {
                    if (el) {
                        el.textContent = `${formatNumber(balances[product])} L`;
                    }
                });
            }

            function ensureDateDefault() {
                if (!deliveryDateInput.value) {
                    const today = new Date().toISOString().split('T')[0];
                    deliveryDateInput.value = today;
                }
            }

            function clearEmptyState() {
                const emptyRow = tableBody.querySelector('.stock-empty');
                if (emptyRow) {
                    emptyRow.remove();
                }
            }

            function formatDisplayDate(value) {
                if (!value) {
                    return '—';
                }

                const parts = value.split('-');
                if (parts.length === 3) {
                    return `${parts[2]}-${parts[1]}-${parts[0]}`;
                }

                return value;
            }

            function addRecord(record) {
                const productType = (record.productType || '').trim();
                const receivedQuantity = Number(record.quantity || 0);

                if (!productType || receivedQuantity <= 0) {
                    return false;
                }

                if (typeof balances[productType] !== 'number') {
                    balances[productType] = 0;
                }

                counter += 1;
                balances[productType] += receivedQuantity;
                refreshSummaries();
                clearEmptyState();

                const dispatchedQuantity = Number(record.dispatchedQuantity || record.quantity || 0);
                const formattedDate = formatDisplayDate(record.deliveryDate);

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${counter}</td>
                    <td>${formattedDate}</td>
                    <td>${record.brvNumber || '—'}</td>
                    <td>${record.driverName || '—'}</td>
                    <td>${record.invoiceNumber || '—'}</td>
                    <td>${productType}</td>
                    <td>${formatNumber(dispatchedQuantity)}</td>
                    <td>${formatNumber(receivedQuantity)}</td>
                    <td>${record.station || '—'}</td>
                    <td>${record.driverPhone || '—'}</td>
                    <td>${record.inspectedBy || '—'}</td>
                    <td>${formatNumber(balances[productType])}</td>
                `;

                tableBody.appendChild(row);
                return true;
            }

            stockForm.addEventListener('submit', function(event) {
                event.preventDefault();

                const formData = new FormData(stockForm);
                const record = {
                    deliveryDate: formData.get('deliveryDate'),
                    brvNumber: formData.get('brvNumber').trim(),
                    driverName: formData.get('driverName').trim(),
                    driverPhone: formData.get('driverPhone').trim(),
                    invoiceNumber: formData.get('invoiceNumber').trim(),
                    productType: formData.get('productType'),
                    dispatchedQuantity: parseFloat(formData.get('dispatched_quantity')) || 0,
                    quantity: parseFloat(formData.get('quantity')) || 0,
                    station: formData.get('station'),
                    inspectedBy: formData.get('inspectedBy').trim(),
                };
                const added = addRecord(record);

                if (!added) {
                    alert('Please provide a valid product type and quantity greater than zero.');
                    return;
                }

                stockForm.reset();
                ensureDateDefault();
            });

            stockForm.addEventListener('reset', function() {
                setTimeout(ensureDateDefault, 0);
            });

            ensureDateDefault();
            refreshSummaries();

            sampleLedgerRecords.forEach(addRecord);

            if (printStockLedgerBtn) {
                printStockLedgerBtn.addEventListener('click', () => {
                    if (!stockTableWrapper) {
                        return;
                    }

                    if (!printPreviewModal) {
                        const printWindow = window.open('', '_blank', 'width=1200,height=900');
                        if (!printWindow) {
                            return;
                        }

                        printWindow.document.write(`
                            <html>
                            <head>
                                <title>Stock Receipts Ledger</title>
                                <style>
                                    body { font-family: Arial, sans-serif; padding: 20px; }
                                    table { width: 100%; border-collapse: collapse; font-size: 12px; }
                                    th, td { border: 1px solid #333; padding: 6px; text-align: left; }
                                    th { background: #0b2e6f; color: #fff; }
                                </style>
                            </head>
                            <body>
                                <h2>Stock Receipts Ledger</h2>
                                ${stockTableWrapper.innerHTML}
                            </body>
                            </html>
                        `);
                        printWindow.document.close();
                        printWindow.focus();
                        printWindow.print();
                        return;
                    }

                    printPreviewModal.show();

                    if (printPreviewFrame) {
                        printPreviewFrame.classList.add('d-none');
                    }

                    if (printPreviewLoading) {
                        printPreviewLoading.classList.remove('d-none');
                    }

                    if (printPreviewConfirmBtn) {
                        printPreviewConfirmBtn.disabled = true;
                    }

                    const options = {
                        margin: 0.5,
                        filename: `stock-ledger-${new Date().toISOString().slice(0, 10)}.pdf`,
                        image: { type: 'jpeg', quality: 0.98 },
                        html2canvas: { scale: 2 },
                        jsPDF: { unit: 'in', format: 'letter', orientation: 'landscape' },
                    };

                    html2pdf()
                        .set(options)
                        .from(stockTableWrapper)
                        .toPdf()
                        .get('pdf')
                        .then(pdf => {
                            if (currentPreviewUrl) {
                                URL.revokeObjectURL(currentPreviewUrl);
                            }

                            const blob = pdf.output('blob');
                            currentPreviewUrl = URL.createObjectURL(blob);

                            if (printPreviewFrame) {
                                const onLoad = () => {
                                    if (printPreviewLoading) {
                                        printPreviewLoading.classList.add('d-none');
                                    }

                                    printPreviewFrame.classList.remove('d-none');

                                    if (printPreviewConfirmBtn) {
                                        printPreviewConfirmBtn.disabled = false;
                                    }

                                    printPreviewFrame.removeEventListener('load', onLoad);
                                };

                                printPreviewFrame.addEventListener('load', onLoad);
                                printPreviewFrame.src = currentPreviewUrl;
                            } else if (printPreviewLoading) {
                                printPreviewLoading.classList.add('d-none');
                            }
                        })
                        .catch(() => {
                            if (printPreviewModal) {
                                printPreviewModal.hide();
                            }
                            alert('Unable to generate the PDF preview. Please try again.');
                        });
                });
            }

            if (printPreviewConfirmBtn && printPreviewFrame) {
                printPreviewConfirmBtn.addEventListener('click', () => {
                    if (printPreviewConfirmBtn.disabled) {
                        return;
                    }

                    const frameWindow = printPreviewFrame.contentWindow;
                    if (frameWindow) {
                        frameWindow.focus();
                        frameWindow.print();
                    }
                });
            }

            if (exportStockPdfBtn) {
                exportStockPdfBtn.addEventListener('click', () => {
                    if (!stockTableWrapper) {
                        return;
                    }

                    const options = {
                        margin: 0.5,
                        filename: `stock-ledger-${new Date().toISOString().slice(0, 10)}.pdf`,
                        image: { type: 'jpeg', quality: 0.98 },
                        html2canvas: { scale: 2 },
                        jsPDF: { unit: 'in', format: 'letter', orientation: 'landscape' },
                    };

                    html2pdf().set(options).from(stockTableWrapper).save();
                });
            }
        });
    </script>
@endpush