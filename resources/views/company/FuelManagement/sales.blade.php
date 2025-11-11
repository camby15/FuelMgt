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
            background: #ffffff;
            border: 1px solid #ced6e3;
            border-radius: 10px;
            box-shadow: 0 16px 28px rgba(14, 47, 99, 0.08);
            overflow: hidden;
        }

        .sales-card__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.4rem;
            background: linear-gradient(90deg, #0c46a0 0%, #1769d5 100%);
            color: #ffffff;
        }

        .sales-card__title {
            margin: 0;
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: 0.08rem;
            text-transform: uppercase;
        }

        .sales-card__filters {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem 1.4rem;
            padding: 1rem 1.4rem;
            border-bottom: 1px solid #dce3ef;
            background: #f8faff;
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

        .sales-card__body {
            padding: 1.4rem;
            background: #ffffff;
        }

        .sales-table-wrapper {
            border: 1px solid #dce3ef;
            border-radius: 8px;
            overflow: hidden;
        }

        .sales-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
            color: #13254a;
        }

        .sales-table thead th {
            background: #0c46a0;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            padding: 0.65rem 0.75rem;
            text-align: center;
        }

        .sales-table tbody td {
            padding: 0.6rem 0.75rem;
            border-bottom: 1px solid #e6ebf4;
            vertical-align: middle;
            text-align: center;
        }

        .sales-table tbody tr:nth-child(even) td {
            background: #f5f7fc;
        }

        .sales-table tbody tr:hover td {
            background: #e9f0ff;
        }

        .sales-table tfoot td {
            background: #0f58c1;
            color: #ffffff;
            font-weight: 600;
            padding: 0.65rem 0.75rem;
            text-align: right;
        }

        .sales-table input,
        .sales-table select,
        .sales-table textarea {
            width: 100%;
            border-radius: 6px;
            border: 1px solid #cfd6e5;
            background: #ffffff;
            padding: 0.4rem 0.5rem;
            font-size: 0.8rem;
            text-align: right;
            color: #23365f;
        }

        .sales-table textarea {
            resize: vertical;
            min-height: 60px;
            text-align: left;
        }

        .sales-table input:focus,
        .sales-table select:focus,
        .sales-table textarea:focus {
            outline: none;
            border-color: #0f58c1;
            box-shadow: 0 0 0 2px rgba(15, 88, 193, 0.2);
        }

        .sales-table .highlight-field {
            background: #f6e96b;
            font-weight: 600;
        }

        .sales-card__footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 1.2rem;
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

        .sales-btn--ghost.dropdown-toggle::after {
            margin-left: 0.4rem;
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

        .sales-table td[data-role="display"] {
            background: #e9f0ff;
            font-weight: 600;
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

        .add-sales-modal .modal-footer .btn {
            min-width: 120px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.04rem;
        }

        .btn-submit {
            background: #0f58c1;
            color: #ffffff;
        }

        .btn-refresh {
            background: #0c2799;
            color: #ffffff;
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
                <h2 class="sales-card__title">Sales</h2>
                <button type="button" class="sales-btn sales-btn--primary" data-bs-toggle="modal" data-bs-target="#addSalesModal">Add Sales</button>
            </div>

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
                    <button type="button" class="sales-btn sales-btn--ghost" data-bs-toggle="modal" data-bs-target="#exportOptionsModal">Export Report</button>
                </div>
            </form>

            <div class="sales-card__body">
                <div class="table-toolbar">
                    <div>Page 1 of 1 (1 item)</div>
                    <div class="search-input">
                        <input type="text" class="form-control form-control-sm" placeholder="Search records...">
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span>Page size:</span>
                        <select class="form-select form-select-sm">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50" selected>50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>

                <div class="sales-table-wrapper">
                    <table class="sales-table">
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
                                <th>Net Quantity</th>
                                <th>Quantity</th>
                                <th>Rate</th>
                                <th>Amount</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="date" class="sales-input sales-input--date" data-group="date">
                                </td>
                                <td>
                                    <select class="sales-input" data-group="station">
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
                                </td>
                                <td>
                                    <select class="sales-input" data-group="pump" disabled>
                                        <option value="" disabled selected>Select Pump</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="sales-input" data-group="product" disabled>
                                        <option value="" disabled selected>Select Product</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="sales-input sales-input--text" data-group="attendant" placeholder="Attendant" readonly>
                                </td>
                                <td><input type="number" step="0.0001" value="0.0000" class="sales-input" data-group="opening-metre"></td>
                                <td><input type="number" step="0.0001" value="0.0000" class="sales-input" data-group="closing-metre"></td>
                                <td><input type="number" step="0.01" value="0.00" class="sales-input" data-group="test-quantity"></td>
                                <td><input type="number" step="0.01" value="0.00" class="sales-input highlight-field" data-group="net-quantity"></td>
                                <td><input type="number" step="0.01" value="0.00" class="sales-input highlight-field" data-group="quantity"></td>
                                <td><input type="number" step="0.0001" value="0.0000" class="sales-input highlight-field" data-group="rate"></td>
                                <td><input type="number" step="0.01" value="0.00" class="sales-input highlight-field" data-group="amount"></td>
                                <td><textarea class="sales-textarea" data-group="remarks" placeholder="Observations / remarks"></textarea></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="10" class="text-end">Totals:</td>
                                <td data-total="amount">0.00</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="sales-card__footer">
                    <button type="button" class="sales-btn sales-btn--ghost" id="resetForm">Cancel</button>
                    <button type="button" class="sales-btn sales-btn--primary" id="saveForm">Save</button>
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
                                    <label for="modalTestQuantity">Test Quantity <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" id="modalTestQuantity" class="form-control" value="0.00" required>
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
                            <button type="button" class="btn btn-refresh" id="modalRefresh">Refresh</button>
                            <button type="submit" class="btn btn-submit">Submit</button>
                        </div>
                    </form>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const reportDateFromInput = document.getElementById('reportDateFrom');
    const reportDateToInput = document.getElementById('reportDateTo');
    const tableRow = document.querySelector('.sales-table tbody tr');
    const stationSelect = document.querySelector('.sales-table [data-group="station"]');
    const pumpSelect = document.querySelector('.sales-table [data-group="pump"]');
    const productSelect = document.querySelector('.sales-table [data-group="product"]');
    const attendantField = document.querySelector('.sales-table [data-group="attendant"]');
    const dateField = document.querySelector('.sales-table [data-group="date"]');
    const rateField = document.querySelector('.sales-table [data-group="rate"]');
    const form = document.getElementById('stockSalesForm');

    const BASE_PRODUCTS = [
        { id: 'Diesel', label: 'Diesel', rate: 13.4500 },
        { id: 'Petrol', label: 'Petrol', rate: 12.9000 }
    ];

    const STATION_DATA = {
        'Wiaga': {
            pumps: [
                { id: 'Pump 1', attendant: 'Abigail Asare' },
                { id: 'Pump 2', attendant: 'Samuel Mensah' },
                { id: 'Pump 3', attendant: 'Joseph Owusu' }
            ],
            products: BASE_PRODUCTS
        },
        'Kintampo': {
            pumps: [
                { id: 'Pump 1', attendant: 'Ama Boateng' },
                { id: 'Pump 2', attendant: 'Issah Yakubu' }
            ],
            products: BASE_PRODUCTS
        },
        'Navrongo Main': {
            pumps: [
                { id: 'Pump A', attendant: 'Patience Adongo' },
                { id: 'Pump B', attendant: 'James Ayeriga' },
                { id: 'Pump C', attendant: 'Justina Ayine' }
            ],
            products: BASE_PRODUCTS
        },
        'Wapuli': {
            pumps: [
                { id: 'Pump Alpha', attendant: 'Imoro Alhassan' },
                { id: 'Pump Beta', attendant: 'Rukaya Fuseini' }
            ],
            products: BASE_PRODUCTS
        },
        'Bamvin': {
            pumps: [
                { id: 'Pump Left', attendant: 'David Tanko' },
                { id: 'Pump Right', attendant: 'Regina Fuseini' }
            ],
            products: BASE_PRODUCTS
        },
        'Paga Anex': {
            pumps: [
                { id: 'Pump North', attendant: 'Amin Alhassan' },
                { id: 'Pump South', attendant: 'Janet Atanga' }
            ],
            products: BASE_PRODUCTS
        },
        'Larabanga': {
            pumps: [
                { id: 'Pump X', attendant: 'Aziz Abdulai' },
                { id: 'Pump Y', attendant: 'Zainab Mahama' }
            ],
            products: BASE_PRODUCTS
        },
        'Amoako': {
            pumps: [
                { id: 'Pump 1', attendant: 'Kojo Sarpong' },
                { id: 'Pump 2', attendant: 'Grace Ofori' }
            ],
            products: BASE_PRODUCTS
        },
        'Navrongo-2': {
            pumps: [
                { id: 'Pump A', attendant: 'Eugenia Atiga' },
                { id: 'Pump B', attendant: 'Kwame Ayamba' }
            ],
            products: BASE_PRODUCTS
        },
        'Bububele': {
            pumps: [
                { id: 'Pump 1', attendant: 'Efua Nketiah' },
                { id: 'Pump 2', attendant: 'Yaw Sarfo' },
                { id: 'Pump 3', attendant: 'Linda Ayim' }
            ],
            products: BASE_PRODUCTS
        }
    };

    const rowFields = {};
    if (tableRow) {
        tableRow.querySelectorAll('[data-group]').forEach(field => {
            rowFields[field.dataset.group] = field;
        });
    }

    const addSalesModal = document.getElementById('addSalesModal');
    const addSalesModalForm = document.getElementById('addSalesModalForm');
    const modalRefreshBtn = document.getElementById('modalRefresh');
    const exportOptionsModalElement = document.getElementById('exportOptionsModal');
    const openCsvModalButton = document.getElementById('openCsvModal');
    const openPdfModalButton = document.getElementById('openPdfModal');
    const confirmCsvExportButton = document.getElementById('confirmCsvExport');
    const confirmPdfExportButton = document.getElementById('confirmPdfExport');

    const modalFields = {
        date: document.getElementById('modalSalesDate'),
        station: document.getElementById('modalStationSelect'),
        pump: document.getElementById('modalPump'),
        product: document.getElementById('modalProductSelect'),
        attendant: document.getElementById('modalAttendant'),
        openingMetre: document.getElementById('modalOpeningMetre'),
        closingMetre: document.getElementById('modalClosingMetre'),
        testQuantity: document.getElementById('modalTestQuantity'),
        netQuantity: document.getElementById('modalNetQuantity'),
        quantity: document.getElementById('modalQuantity'),
        rate: document.getElementById('modalRate'),
        amount: document.getElementById('modalAmount')
    };

    let modalQuantityTouched = false;

    const today = new Date();
    const todayStr = today.toISOString().split('T')[0];
    reportDateFromInput.value = todayStr;
    reportDateToInput.value = todayStr;
    if (dateField) {
        dateField.value = todayStr;
    }

    function parseNumber(value) {
        const num = parseFloat(value);
        return Number.isFinite(num) ? num : 0;
    }

    function formatDate(value) {
        if (!value) return '—';
        const [year, month, day] = value.split('-');
        return `${day}-${month}-${year}`;
    }

    function computeNetQuantity(opening, closing, test) {
        return closing - opening - test;
    }

    function populatePumpOptions(selectedStation, selectedPump = '') {
        if (!pumpSelect) return;
        pumpSelect.innerHTML = '<option value="" disabled>Select Pump</option>';
        pumpSelect.disabled = true;

        attendantField.value = '';

        const stationData = STATION_DATA[selectedStation];
        if (!stationData) return;

        const pumps = stationData.pumps || [];
        pumps.forEach(pump => {
            const option = document.createElement('option');
            option.value = pump.id;
            option.textContent = pump.id;
            pumpSelect.appendChild(option);
        });

        if (pumps.length === 0) {
            if (rowFields.pump) rowFields.pump.value = '';
            attendantField.value = '';
            return;
        }

        let resolvedPump = '';
        if (selectedPump && pumps.some(p => p.id === selectedPump)) {
            resolvedPump = selectedPump;
        } else {
            resolvedPump = pumps[0].id;
        }

        pumpSelect.disabled = false;
        pumpSelect.value = resolvedPump;

        if (rowFields.pump) {
            rowFields.pump.value = resolvedPump;
        }
        updateAttendantFromPump(selectedStation, resolvedPump);
    }

    function populateProductOptions(selectedStation, selectedProduct = '') {
        if (!productSelect) return;

        productSelect.innerHTML = '<option value="" disabled>Select Product</option>';
        productSelect.disabled = true;

        if (!selectedStation || !STATION_DATA[selectedStation]) {
            if (rowFields.product) rowFields.product.value = '';
            if (rateField) rateField.value = '0.0000';
            return;
        }

        const products = STATION_DATA[selectedStation].products || [];
        products.forEach(product => {
            const option = document.createElement('option');
            option.value = product.id;
            option.textContent = product.label || product.id;
            productSelect.appendChild(option);
        });

        if (products.length === 0) {
            if (rowFields.product) rowFields.product.value = '';
            if (rateField) rateField.value = '0.0000';
            return;
        }

        let resolvedProduct = '';
        if (selectedProduct && products.some(p => p.id === selectedProduct)) {
            resolvedProduct = selectedProduct;
        } else {
            resolvedProduct = products[0].id;
        }

        productSelect.disabled = false;
        productSelect.value = resolvedProduct;

        if (rowFields.product) {
            rowFields.product.value = resolvedProduct;
        }

        setRateForProduct(selectedStation, resolvedProduct);
    }

    function updateAttendantFromPump(station, pumpId) {
        if (!attendantField) return;
        const stationData = STATION_DATA[station];
        if (!stationData) {
            attendantField.value = '';
            return;
        }

        const pumpData = stationData.pumps.find(p => p.id === pumpId);
        attendantField.value = pumpData ? pumpData.attendant : '';
    }

    function setRateForProduct(station, productId) {
        if (!rateField) return;

        const stationData = STATION_DATA[station];
        const productDetails = stationData?.products?.find(p => p.id === productId);
        const rateValue = productDetails ? Number(productDetails.rate) : 0;

        rateField.value = rateValue.toFixed(4);
        updateRowCalculatedValues();
    }

    function updateTotals() {
        const totalCell = document.querySelector('[data-total="amount"]');
        if (!totalCell) return;
        const amountField = rowFields['amount'];
        const amountValue = amountField ? parseNumber(amountField.value) : 0;
        totalCell.textContent = amountValue.toFixed(2);
    }

    function updateRowCalculatedValues() {
        const opening = parseNumber(rowFields['opening-metre']?.value);
        const closing = parseNumber(rowFields['closing-metre']?.value);
        const test = parseNumber(rowFields['test-quantity']?.value);
        const rateValue = parseNumber(rowFields['rate']?.value);

        const net = computeNetQuantity(opening, closing, test);
        if (rowFields['net-quantity']) {
            rowFields['net-quantity'].value = net.toFixed(2);
        }

        const existingQuantity = rowFields['quantity'] ? parseNumber(rowFields['quantity'].value) : 0;
        if (rowFields['quantity'] && Math.abs(existingQuantity) < 0.00001) {
            rowFields['quantity'].value = net.toFixed(2);
        }

        const quantityValue = rowFields['quantity'] ? parseNumber(rowFields['quantity'].value) : 0;
        const amount = quantityValue * rateValue;
        if (rowFields['amount']) {
            rowFields['amount'].value = amount.toFixed(2);
        }

        updateTotals();
    }

    function collectRowData() {
        const data = {
            date: dateField ? dateField.value : (reportDateToInput.value || reportDateFromInput.value),
            station: rowFields.station ? rowFields.station.value : '',
            pump: rowFields.pump ? rowFields.pump.value : '',
            attendant: rowFields.attendant ? rowFields.attendant.value : ''
        };

        Object.keys(rowFields).forEach(key => {
            data[key] = rowFields[key].value;
        });

        return data;
    }

    function populateModalFromRow() {
        const data = collectRowData();

        if (modalFields.date) {
            modalFields.date.value = data.date || todayStr;
        }
        if (modalFields.station) {
            modalFields.station.value = data.station || '';
        }
        if (modalFields.pump) {
            modalFields.pump.value = data.pump || '';
        }
        if (modalFields.product) {
            modalFields.product.value = data.product || '';
        }
        if (modalFields.attendant) {
            modalFields.attendant.value = data.attendant || '';
        }
        if (modalFields.openingMetre) {
            modalFields.openingMetre.value = parseNumber(data['opening-metre']).toFixed(4);
        }
        if (modalFields.closingMetre) {
            modalFields.closingMetre.value = parseNumber(data['closing-metre']).toFixed(4);
        }
        if (modalFields.testQuantity) {
            modalFields.testQuantity.value = parseNumber(data['test-quantity']).toFixed(2);
        }
        if (modalFields.netQuantity) {
            modalFields.netQuantity.value = parseNumber(data['net-quantity']).toFixed(2);
        }
        if (modalFields.quantity) {
            const quantityValue = parseNumber(data['quantity']);
            modalFields.quantity.value = quantityValue.toFixed(2);
            modalQuantityTouched = Math.abs(quantityValue - parseNumber(data['net-quantity'])) > 0.00001;
        }
        if (modalFields.rate) {
            modalFields.rate.value = parseNumber(data['rate']).toFixed(4);
        }
        if (modalFields.amount) {
            modalFields.amount.value = parseNumber(data['amount']).toFixed(2);
        }

        updateModalDerivedValues(true);
    }

    function updateModalDerivedValues(forceQuantity = false) {
        const opening = parseNumber(modalFields.openingMetre?.value);
        const closing = parseNumber(modalFields.closingMetre?.value);
        const test = parseNumber(modalFields.testQuantity?.value);
        const net = computeNetQuantity(opening, closing, test);

        if (modalFields.netQuantity) {
            modalFields.netQuantity.value = net.toFixed(2);
        }

        if (modalFields.quantity) {
            const current = parseNumber(modalFields.quantity.value);
            if (forceQuantity || (!modalQuantityTouched || Math.abs(current) < 0.00001)) {
                modalFields.quantity.value = net.toFixed(2);
            }
        }

        const quantityValue = parseNumber(modalFields.quantity?.value);
        const rateValue = parseNumber(modalFields.rate?.value);
        const amount = quantityValue * rateValue;
        if (modalFields.amount) {
            modalFields.amount.value = amount.toFixed(2);
        }
    }

    function pushModalDataToRow() {
        if (!tableRow) return;

        if (modalFields.station && rowFields.station) {
            rowFields.station.value = modalFields.station.value;
            if (stationSelect) {
                stationSelect.value = modalFields.station.value;
                handleStationChange({
                    pump: modalFields.pump?.value || '',
                    product: modalFields.product?.value || ''
                });
            }
        }
        if (modalFields.pump && rowFields.pump) {
            rowFields.pump.value = modalFields.pump.value;
            if (pumpSelect) {
                pumpSelect.value = modalFields.pump.value;
                handlePumpChange();
            }
        }
        if (modalFields.product && rowFields.product && productSelect) {
            productSelect.value = modalFields.product.value;
            handleProductChange();
        }
        if (modalFields.attendant && rowFields.attendant) {
            rowFields.attendant.value = modalFields.attendant.value;
        }
        if (modalFields.openingMetre && rowFields['opening-metre']) {
            rowFields['opening-metre'].value = parseNumber(modalFields.openingMetre.value).toFixed(4);
        }
        if (modalFields.closingMetre && rowFields['closing-metre']) {
            rowFields['closing-metre'].value = parseNumber(modalFields.closingMetre.value).toFixed(4);
        }
        if (modalFields.testQuantity && rowFields['test-quantity']) {
            rowFields['test-quantity'].value = parseNumber(modalFields.testQuantity.value).toFixed(2);
        }
        if (modalFields.netQuantity && rowFields['net-quantity']) {
            rowFields['net-quantity'].value = parseNumber(modalFields.netQuantity.value).toFixed(2);
        }
        if (modalFields.quantity && rowFields['quantity']) {
            rowFields['quantity'].value = parseNumber(modalFields.quantity.value).toFixed(2);
        }
        if (modalFields.rate && rowFields['rate']) {
            rowFields['rate'].value = parseNumber(modalFields.rate.value).toFixed(4);
        }
        if (modalFields.amount && rowFields['amount']) {
            rowFields['amount'].value = parseNumber(modalFields.amount.value).toFixed(2);
        }
        if (modalFields.date) {
            const value = modalFields.date.value;
            reportDateToInput.value = value;
            if (dateField) {
                dateField.value = value;
            }
        }

        updateRowCalculatedValues();
    }

    function resetRowValues() {
        Object.keys(rowFields).forEach(key => {
            if (['station', 'pump', 'attendant', 'remarks'].includes(key)) {
                rowFields[key].value = '';
            } else if (['opening-metre', 'closing-metre'].includes(key)) {
                rowFields[key].value = '0.0000';
            } else {
                rowFields[key].value = '0.00';
            }
        });
        if (dateField) {
            dateField.value = todayStr;
        }
        if (stationSelect) {
            stationSelect.selectedIndex = 0;
        }
        if (pumpSelect) {
            pumpSelect.innerHTML = '<option value="" disabled>Select Pump</option>';
            pumpSelect.disabled = true;
        }
        if (productSelect) {
            productSelect.innerHTML = '<option value="" disabled>Select Product</option>';
            productSelect.disabled = true;
        }
        if (attendantField) {
            attendantField.value = '';
        }
        if (rateField) {
            rateField.value = '0.0000';
        }

        updateRowCalculatedValues();
    }

    if (rowFields['opening-metre']) {
        ['opening-metre', 'closing-metre', 'test-quantity', 'quantity', 'rate'].forEach(key => {
            if (rowFields[key]) {
                rowFields[key].addEventListener('input', updateRowCalculatedValues);
            }
        });
    }

    function handleStationChange({ pump = '', product = '' } = {}) {
        const selectedStation = stationSelect?.value || '';
        if (rowFields.station) {
            rowFields.station.value = selectedStation;
        }
        populatePumpOptions(selectedStation, pump);
        populateProductOptions(selectedStation, product);
    }

    function handlePumpChange() {
        const selectedStation = stationSelect?.value || '';
        const selectedPump = pumpSelect?.value || '';
        if (rowFields.pump) {
            rowFields.pump.value = selectedPump;
        }
        updateAttendantFromPump(selectedStation, selectedPump);
        if (rowFields.attendant) {
            rowFields.attendant.value = attendantField.value;
        }
        populateProductOptions(selectedStation, productSelect?.value || '');
    }

    function handleProductChange() {
        const selectedStation = stationSelect?.value || '';
        const selectedProduct = productSelect?.value || '';
        if (rowFields.product) {
            rowFields.product.value = selectedProduct;
        }
        setRateForProduct(selectedStation, selectedProduct);
    }

    function handleDateChange(event) {
        const value = event.target.value;
        if (event.target === dateField) {
            if (reportDateFromInput) reportDateFromInput.value = value;
            if (reportDateToInput) reportDateToInput.value = value;
        } else {
            if (dateField) {
                dateField.value = value;
            }
        }
    }

    if (stationSelect) {
        stationSelect.addEventListener('change', () => handleStationChange());
    }
    if (pumpSelect) {
        pumpSelect.addEventListener('change', handlePumpChange);
    }
    if (productSelect) {
        productSelect.addEventListener('change', handleProductChange);
    }
    if (dateField) {
        dateField.addEventListener('change', handleDateChange);
    }
    if (reportDateFromInput) {
        reportDateFromInput.addEventListener('change', handleDateChange);
    }
    if (reportDateToInput) {
        reportDateToInput.addEventListener('change', handleDateChange);
    }

    const resetFormBtn = document.getElementById('resetForm');
    if (resetFormBtn) {
        resetFormBtn.addEventListener('click', resetRowValues);
    }

    const saveFormBtn = document.getElementById('saveForm');
    if (saveFormBtn) {
        saveFormBtn.addEventListener('click', function() {
            alert('Sales record saved (demo placeholder).');
        });
    }

    if (form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
        });
    }

    if (addSalesModal) {
        addSalesModal.addEventListener('show.bs.modal', function() {
            modalQuantityTouched = false;
            populateModalFromRow();
        });

        addSalesModalForm.addEventListener('submit', function(event) {
            event.preventDefault();
            pushModalDataToRow();
            const modalInstance = bootstrap.Modal.getInstance(addSalesModal);
            if (modalInstance) {
                modalInstance.hide();
            }
        });
    }

    const csvModalElement = document.getElementById('exportCsvModal');
    const pdfModalElement = document.getElementById('exportPdfModal');

    function hideExportOptionsModal() {
        if (!exportOptionsModalElement) return;
        const instance = bootstrap.Modal.getInstance(exportOptionsModalElement);
        if (instance) {
            instance.hide();
        }
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
            if (modal) {
                modal.hide();
            }
        });
    }

    if (confirmPdfExportButton) {
        confirmPdfExportButton.addEventListener('click', function() {
            const orientation = document.getElementById('pdfOrientation')?.value || 'landscape';
            const includeSummary = document.getElementById('pdfIncludeSummary')?.checked;
            alert(`PDF export triggered. Orientation: ${orientation}, Summary page: ${includeSummary ? 'Yes' : 'No'}`);
            const modal = bootstrap.Modal.getInstance(pdfModalElement);
            if (modal) {
                modal.hide();
            }
        });
    }

    if (modalRefreshBtn && addSalesModalForm) {
        modalRefreshBtn.addEventListener('click', function() {
            addSalesModalForm.reset();
            modalQuantityTouched = false;
            if (modalFields.date) {
                modalFields.date.value = reportDateToInput.value || todayStr;
            }
            if (modalFields.station && stationSelect) {
                modalFields.station.value = stationSelect.value || '';
            }
            if (modalFields.product) {
                modalFields.product.value = '';
            }
            if (modalFields.openingMetre) {
                modalFields.openingMetre.value = '0.0000';
            }
            if (modalFields.closingMetre) {
                modalFields.closingMetre.value = '0.0000';
            }
            ['testQuantity', 'netQuantity', 'quantity', 'amount'].forEach(key => {
                if (modalFields[key]) {
                    modalFields[key].value = '0.00';
                }
            });
            if (modalFields.rate) {
                modalFields.rate.value = '0.0000';
            }
            updateModalDerivedValues(true);
        });
    }

    if (modalFields.openingMetre) {
        ['openingMetre', 'closingMetre', 'testQuantity', 'rate'].forEach(key => {
            if (modalFields[key]) {
                modalFields[key].addEventListener('input', () => updateModalDerivedValues());
            }
        });
    }

    if (modalFields.quantity) {
        modalFields.quantity.addEventListener('input', function() {
            modalQuantityTouched = true;
            updateModalDerivedValues();
        });
    }

    if (stationSelect) {
        handleStationChange({
            pump: rowFields.pump?.value || '',
            product: rowFields.product?.value || ''
        });
    }

    updateRowCalculatedValues();
});
</script>
@endpush