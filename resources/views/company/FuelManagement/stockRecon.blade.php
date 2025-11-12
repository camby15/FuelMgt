@extends('layouts.vertical', [
    'page_title' => 'Station Stock Reconciliation',
    'mode' => $mode ?? '',
    'demo' => $demo ?? '',
])

@section('css')
    <style>
        body.modal-open {
            overflow: hidden;
        }

        .recon-card {
            background: linear-gradient(140deg, #031739 0%, #0b3f96 100%);
            padding: 1.4px;
            border-radius: 26px;
            box-shadow: 0 28px 46px rgba(3, 26, 67, 0.33);
        }

        .recon-ledger__filters {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.9rem;
        }

        .recon-ledger__filters label {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.48px;
            color: #0a2048;
            margin-bottom: 0.3rem;
            display: block;
        }

        .recon-ledger__filters .form-control {
            min-width: 220px;
        }

        .recon-ledger__filter {
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
            min-width: 210px;
        }

        .recon-ledger__filter--action {
            justify-content: flex-end;
            min-width: auto;
        }

        .recon-ledger__filter input,
        .recon-ledger__filter select {
            width: 100%;
        }

        .recon-ledger__actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .recon-ledger__filters input,
        .recon-ledger__filters select {
            border-radius: 10px;
            border: 1px solid rgba(12, 36, 79, 0.18);
            background: #f5f8ff;
            padding: 0.52rem 0.7rem;
            font-size: 0.82rem;
            color: #0a1d44;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .recon-ledger__filters input:focus,
        .recon-ledger__filters select:focus {
            outline: none;
            border-color: #2b6def;
            box-shadow: 0 0 0 3px rgba(43, 109, 239, 0.18);
        }

        .recon-ledger__export-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            border-radius: 10px;
            border: none;
            padding: 0.55rem 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            cursor: pointer;
            background: rgba(9, 28, 64, 0.08);
            color: #0a1d44;
            transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
        }

        .recon-ledger__export-btn:hover {
            transform: translateY(-1px);
            background: rgba(9, 28, 64, 0.16);
            box-shadow: 0 12px 22px rgba(8, 29, 73, 0.18);
        }

        .recon-modal {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(3, 15, 40, 0.55);
            z-index: 1080;
            padding: 1.5rem;
        }

        .recon-modal.is-visible {
            display: flex;
        }

        .recon-modal__dialog {
            background: #ffffff;
            border-radius: 22px;
            width: min(540px, 94vw);
            box-shadow: 0 24px 60px rgba(10, 29, 68, 0.35);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .recon-modal__header {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid rgba(12, 36, 79, 0.12);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .recon-modal__title {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 700;
            color: #0a1d44;
            letter-spacing: 0.4px;
        }

        .recon-modal__close {
            border: none;
            background: transparent;
            color: #0a1d44;
            font-size: 1.45rem;
            line-height: 1;
            cursor: pointer;
            padding: 0.25rem;
        }

        .recon-modal__body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.1rem;
        }

        .recon-modal__field {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .recon-modal__field label {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.48px;
            color: #0a2048;
        }

        .recon-modal__field input,
        .recon-modal__field select {
            border-radius: 10px;
            border: 1px solid rgba(12, 36, 79, 0.18);
            background: #f5f8ff;
            padding: 0.52rem 0.7rem;
            font-size: 0.82rem;
            color: #0a1d44;
        }

        .recon-modal__field input:focus,
        .recon-modal__field select:focus {
            outline: none;
            border-color: #2b6def;
            box-shadow: 0 0 0 3px rgba(43, 109, 239, 0.18);
        }

        .recon-modal__field[data-role="pdf-custom-range"] {
            display: none;
        }

        .recon-modal__field[data-role="pdf-custom-range"].is-visible {
            display: flex;
        }

        .recon-modal__footer {
            padding: 1.1rem 1.5rem;
            border-top: 1px solid rgba(12, 36, 79, 0.12);
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        .recon-modal__description {
            font-size: 0.85rem;
            color: rgba(9, 31, 74, 0.75);
            line-height: 1.6;
        }

        .recon-modal__actions button {
            border-radius: 10px;
            border: none;
            padding: 0.55rem 1.1rem;
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.45px;
            cursor: pointer;
        }

        .recon-modal__btn-close {
            background: rgba(9, 28, 64, 0.08);
            color: #0a1d44;
        }

        .recon-modal__btn-primary {
            background: linear-gradient(92deg, #0a4ed1 0%, #53a0fd 100%);
            color: #ffffff;
            box-shadow: 0 12px 28px rgba(14, 78, 209, 0.32);
        }

        .recon-card__inner {
            background: #f7f9ff;
            border-radius: 25px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .recon-card__header {
            background: linear-gradient(97deg, rgba(3, 23, 63, 0.96) 0%, rgba(10, 58, 138, 0.98) 55%, rgba(3, 23, 63, 0.96) 100%);
            padding: 1.9rem 2.6rem;
            color: #ffffff;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .recon-card__title {
            margin: 0;
            font-size: 1.65rem;
            font-weight: 700;
            letter-spacing: 1.05px;
            text-transform: uppercase;
        }

        .recon-card__subtitle {
            margin: 0.35rem 0 0;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.55px;
            color: rgba(232, 241, 255, 0.82);
        }

        .recon-card__station {
            padding: 0.9rem 1.4rem;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.12);
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            min-width: 220px;
        }

        .recon-card__station-label {
            text-transform: uppercase;
            font-size: 0.68rem;
            letter-spacing: 0.55px;
            color: rgba(232, 241, 255, 0.75);
        }

        .recon-card__station-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.6px;
        }

        .recon-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.4rem;
            margin: 2.2rem 2.6rem 0;
        }

        .recon-summary__item {
            position: relative;
            border-radius: 24px;
            padding: 1.6rem 1.8rem;
            overflow: hidden;
            display: flex;
            align-items: center;
            gap: 1.2rem;
            background: linear-gradient(132deg, rgba(9, 38, 116, 0.96) 0%, rgba(39, 124, 244, 0.9) 50%, rgba(5, 22, 66, 0.95) 100%);
            box-shadow: 0 24px 48px rgba(9, 32, 86, 0.24);
            color: #ffffff;
        }

        .recon-summary__item[data-variant="emerald"] {
            background: linear-gradient(132deg, rgba(9, 92, 79, 0.98) 0%, rgba(54, 192, 155, 0.9) 50%, rgba(4, 54, 46, 0.95) 100%);
        }

        .recon-summary__item[data-variant="royal"] {
            background: linear-gradient(132deg, rgba(61, 29, 133, 0.97) 0%, rgba(147, 95, 255, 0.92) 50%, rgba(33, 13, 84, 0.96) 100%);
        }

        .recon-summary__item[data-variant="amber"] {
            background: linear-gradient(132deg, rgba(179, 78, 0, 0.98) 0%, rgba(255, 165, 56, 0.92) 48%, rgba(102, 41, 0, 0.95) 100%);
        }

        .recon-summary__icon {
            width: 62px;
            height: 62px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.16);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.65rem;
            color: inherit;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.22);
        }

        .recon-summary__content {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .recon-summary__label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.55px;
            color: rgba(232, 241, 255, 0.85);
        }

        .recon-summary__value {
            font-size: 1.55rem;
            font-weight: 700;
            letter-spacing: 0.48px;
        }

        .recon-summary__meta {
            font-size: 0.7rem;
            color: rgba(232, 241, 255, 0.78);
        }

        .recon-form,
        .recon-ledger {
            margin: 2.2rem 2.6rem 2.4rem;
            background: #ffffff;
            border-radius: 22px;
            padding: 1.9rem 2.1rem;
            box-shadow: 0 18px 42px rgba(7, 32, 86, 0.14);
            border: 1px solid rgba(12, 38, 96, 0.08);
        }

        .recon-section__header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1.4rem;
        }

        .recon-section__title {
            margin: 0;
            font-size: 1.12rem;
            font-weight: 700;
            color: #0a1d44;
            letter-spacing: 0.45px;
        }

        .recon-section__caption {
            margin: 0;
            font-size: 0.75rem;
            color: rgba(9, 31, 74, 0.6);
        }

        .recon-form__grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.2rem 1.6rem;
        }

        .recon-form label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.48px;
            color: #0a2048;
            display: block;
            margin-bottom: 0.45rem;
        }

        .recon-form input,
        .recon-form select,
        .recon-form textarea {
            width: 100%;
            border-radius: 12px;
            border: 1px solid rgba(12, 36, 79, 0.18);
            background: #f5f8ff;
            padding: 0.58rem 0.8rem;
            font-size: 0.88rem;
            color: #0a1d44;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .recon-form input:focus,
        .recon-form select:focus,
        .recon-form textarea:focus {
            outline: none;
            border-color: #2b6def;
            box-shadow: 0 0 0 3px rgba(43, 109, 239, 0.18);
        }

        .recon-form textarea {
            min-height: 120px;
            resize: vertical;
        }

        .recon-form__helper {
            font-size: 0.68rem;
            color: rgba(9, 31, 74, 0.55);
            margin-top: 0.35rem;
        }

        .recon-form__actions {
            margin-top: 1.8rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.9rem;
            justify-content: flex-end;
        }

        .recon-btn {
            min-width: 130px;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.45px;
            padding: 0.65rem 1.25rem;
            font-size: 0.78rem;
            cursor: pointer;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .recon-btn--sm {
            min-width: auto;
            padding: 0.5rem 0.95rem;
            font-size: 0.72rem;
        }

        .recon-btn--ghost {
            background: transparent;
            border: 1px solid rgba(9, 28, 64, 0.25);
            color: #0b1c3f;
        }

        .recon-btn--ghost:hover {
            transform: translateY(-1px);
            border-color: rgba(9, 28, 64, 0.45);
            box-shadow: 0 8px 18px rgba(8, 29, 73, 0.16);
        }

        .recon-btn--primary {
            background: linear-gradient(88deg, #ff7a1a 0%, #ffb347 100%);
            color: #0a1d44;
            box-shadow: 0 12px 22px rgba(255, 135, 54, 0.32);
        }

        .recon-btn--primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 26px rgba(255, 135, 54, 0.4);
        }

        .recon-ledger .table-responsive {
            overflow-x: auto;
        }

        .recon-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 980px;
            color: #0a1d44;
            font-size: 0.8rem;
        }

        .recon-table th,
        .recon-table td {
            border: 1px solid rgba(16, 44, 98, 0.12);
            padding: 0.6rem 0.7rem;
            text-align: left;
        }

        .recon-table th {
            background: #0b2e6f;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.48px;
            font-weight: 600;
        }

        .recon-table tbody tr:nth-child(even) {
            background: #f5f7ff;
        }

        .recon-empty {
            text-align: center;
            padding: 1.8rem;
            color: rgba(9, 31, 74, 0.55);
            font-style: italic;
        }

        @media (max-width: 1200px) {
            .recon-summary,
            .recon-form,
            .recon-ledger {
                margin: 2rem 1.9rem 1.9rem;
            }
        }

        @media (max-width: 768px) {
            .recon-card__header {
                padding: 1.6rem 1.9rem;
            }

            .recon-summary,
            .recon-form,
            .recon-ledger {
                margin: 1.6rem 1.3rem;
            }

            .recon-form__grid {
                grid-template-columns: 1fr;
            }

            .recon-form__actions {
                justify-content: stretch;
            }

            .recon-btn {
                flex: 1 1 auto;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="recon-card">
            <div class="recon-card__inner">
                <div class="recon-card__header">
                    <div>
                        <h2 class="recon-card__title">Station Stock Reconciliation</h2>
                        <p class="recon-card__subtitle">Compare physical dipping with system balances to stay compliant</p>
                    </div>
                    <div class="recon-card__station">
                        <span class="recon-card__station-label">Station</span>
                        <span class="recon-card__station-name">{{ $stationName ?? 'Your Station' }}</span>
                    </div>
                </div>

                <div class="recon-summary">
                    <div class="recon-summary__item" data-role="summary-opening" data-variant="emerald">
                        <div class="recon-summary__icon">
                            <i class="ri-archive-stack-line"></i>
                        </div>
                        <div class="recon-summary__content">
                            <div class="recon-summary__label">Opening Stock</div>
                            <div class="recon-summary__value" data-role="summary-opening-value">0.00 L</div>
                            <div class="recon-summary__meta">Beginning balance for the selected date</div>
                        </div>
                    </div>
                    <div class="recon-summary__item" data-role="summary-sales" data-variant="royal">
                        <div class="recon-summary__icon">
                            <i class="ri-shopping-bag-3-line"></i>
                        </div>
                        <div class="recon-summary__content">
                            <div class="recon-summary__label">Sales</div>
                            <div class="recon-summary__value" data-role="summary-sales-value">0.00 L</div>
                            <div class="recon-summary__meta">Total pump sales captured</div>
                        </div>
                    </div>
                    <div class="recon-summary__item" data-role="summary-variance" data-variant="amber">
                        <div class="recon-summary__icon">
                            <i class="ri-contrast-drop-2-line"></i>
                        </div>
                        <div class="recon-summary__content">
                            <div class="recon-summary__label">Variance</div>
                            <div class="recon-summary__value" data-role="summary-variance-value">0.00 L</div>
                            <div class="recon-summary__meta">Difference between expected and dipped</div>
                        </div>
                    </div>
                </div>

                <div class="recon-form">
                    <div class="recon-section__header">
                        <h3 class="recon-section__title">Daily Reconciliation Entry</h3>
                        <p class="recon-section__caption">Fill in the tank movement details for the day</p>
                    </div>

                    <form id="stockReconciliationForm" method="POST" action="#" autocomplete="off">
                        @csrf
                        <div class="recon-form__grid">
                            <div>
                                <label for="reconDate">Date</label>
                                <input type="date" id="reconDate" name="reconDate" required>
                            </div>
                            <div>
                                <label for="tankSelection">Tank</label>
                                <select id="tankSelection" name="tankSelection" required>
                                    <option value="" disabled selected>Select tank</option>
                                    <option value="PMS Tank 1">PMS - Tank 1</option>
                                    <option value="PMS Tank 2">PMS - Tank 2</option>
                                    <option value="AGO Tank 1">AGO - Tank 1</option>
                                    <option value="AGO Tank 2">AGO - Tank 2</option>
                                </select>
                                <div class="recon-form__helper">Tank list grouped by product for clarity</div>
                            </div>
                            <div>
                                <label for="openingStock">Opening Stock (L)</label>
                                <input type="number" min="0" step="0.01" id="openingStock" name="openingStock" placeholder="0.00" required>
                            </div>
                            <div>
                                <label for="addStock">Add Stock (L)</label>
                                <input type="number" min="0" step="0.01" id="addStock" name="addStock" placeholder="0.00" required>
                            </div>
                            <div>
                                <label for="totalStock">Total Stock (L)</label>
                                <input id="totalStock" name="totalStock">
                                <div class="recon-form__helper">Auto-calculated: Opening Stock + Add Stock</div>
                            </div>
                            <div>
                                <label for="salesVolume">Sales (L)</label>
                                <input type="number" min="0" step="0.01" id="salesVolume" name="salesVolume" placeholder="0.00" required>
                            </div>
                            <div>
                                <label for="closingStock">Closing Stock (L)</label>
                                <input type="number" min="0" step="0.01" id="closingStock" name="closingStock" placeholder="0.00" required>
                            </div>
                            <div>
                                <label for="dippingReading">Dipping (mm)</label>
                                <input type="number" min="0" step="0.01" id="dippingReading" name="dippingReading" placeholder="0.00" required>
                            </div>
                            <div>
                                <label for="variance">Variance (L)</label>
                                <input type="number" step="0.01" id="variance" name="variance" placeholder="0.00" required>
                                <div class="recon-form__helper">Calculated: (Opening + Added) - Sales - Closing</div>
                            </div>
                        </div>

                        <div>
                            <label for="reconNotes">Notes / Observation</label>
                            <textarea id="reconNotes" name="reconNotes" placeholder="Optional remarks for audit trail"></textarea>
                        </div>

                        <div class="recon-form__actions">
                            <button type="reset" class="recon-btn recon-btn--ghost">Clear</button>
                            <button type="submit" class="recon-btn recon-btn--primary">Save Reconciliation</button>
                        </div>
                    </form>
                </div>

                <div class="recon-ledger">
                    <div class="recon-section__header">
                        <div>
                            <h3 class="recon-section__title">Stock Reconciliation Ledger</h3>
                            <p class="recon-section__caption">Entries per tank for audit reviews</p>
                        </div>
                        <div class="recon-ledger__actions">
                            <button type="button" class="recon-ledger__export-btn" data-modal-target="csvExportModal">
                                <i class="ri-file-list-3-line"></i>
                                <span>Export CSV</span>
                            </button>
                            <button type="button" class="recon-ledger__export-btn" data-modal-target="pdfExportModal">
                                <i class="ri-file-download-line"></i>
                                <span>Export PDF</span>
                            </button>
                        </div>
                    </div>

                    <div class="recon-ledger__filters">
                        <div class="recon-ledger__filter">
                            <label for="ledgerSearch">Search</label>
                            <input type="text" id="ledgerSearch" name="ledgerSearch" placeholder="Search notes or remarks">
                        </div>
                        <div class="recon-ledger__filter">
                            <label for="ledgerTank">Tank</label>
                            <select id="ledgerTank" name="ledgerTank" class="form-control">
                                <option value="">All Tanks</option>
                                <option value="PMS Tank 1">PMS - Tank 1</option>
                                <option value="PMS Tank 2">PMS - Tank 2</option>
                                <option value="AGO Tank 1">AGO - Tank 1</option>
                                <option value="AGO Tank 2">AGO - Tank 2</option>
                            </select>
                        </div>
                        <div class="recon-ledger__filter">
                            <label for="ledgerDate">Date</label>
                            <input type="date" id="ledgerDate" name="ledgerDate">
                        </div>
                        <div class="recon-ledger__filter recon-ledger__filter--action">
                            <button type="button" class="recon-btn recon-btn--ghost recon-btn--sm" data-role="ledger-filter-clear">
                                <i class="ri-refresh-line"></i>
                                <span>Reset</span>
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="recon-table" id="reconTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Tank</th>
                                    <th>Opening Stock</th>
                                    <th>Add Stock</th>
                                    <th>Total Stock</th>
                                    <th>Sales</th>
                                    <th>Closing Stock</th>
                                    <th>Dipping</th>
                                    <th>Variance</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody data-role="recon-tbody">
                                <tr data-seed-record data-record data-date="2024-11-08" data-tank="PMS Tank 1" data-notes="Balanced with dip reading">
                                    <td>1</td>
                                    <td>08-11-2024</td>
                                    <td>PMS - Tank 1</td>
                                    <td>14,800.00 L</td>
                                    <td>3,500.00 L</td>
                                    <td>18,300.00 L</td>
                                    <td>4,120.00 L</td>
                                    <td>14,180.00 L</td>
                                    <td>1,118 mm</td>
                                    <td>0.00 L</td>
                                    <td>Balanced with dip reading</td>
                                </tr>
                                <tr data-empty-state>
                                    <td colspan="10" class="recon-empty">No reconciliation records captured yet.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="recon-modal" id="csvExportModal" role="dialog" aria-hidden="true" aria-modal="true">
        <div class="recon-modal__dialog">
            <div class="recon-modal__header">
                <h4 class="recon-modal__title">Export Reconciliation CSV</h4>
                <button type="button" class="recon-modal__close" aria-label="Close" data-modal-close>
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <div class="recon-modal__body">
                <p class="recon-modal__description">Prepare a CSV extract for your station reconciliation entries. Choose the filters that best represent the dataset you want to download.</p>
                <div class="recon-modal__field">
                    <label for="csvTank">Tank</label>
                    <select id="csvTank" name="csvTank">
                        <option value="">All Tanks</option>
                        <option value="PMS Tank 1">PMS - Tank 1</option>
                        <option value="PMS Tank 2">PMS - Tank 2</option>
                        <option value="AGO Tank 1">AGO - Tank 1</option>
                        <option value="AGO Tank 2">AGO - Tank 2</option>
                    </select>
                </div>
                <div class="recon-modal__field">
                    <label for="csvFrom">From</label>
                    <input type="date" id="csvFrom" name="csvFrom">
                </div>
                <div class="recon-modal__field">
                    <label for="csvTo">To</label>
                    <input type="date" id="csvTo" name="csvTo">
                </div>
            </div>
            <div class="recon-modal__footer recon-modal__actions">
                <button type="button" class="recon-modal__btn-close" data-modal-close>Cancel</button>
                <button type="button" class="recon-modal__btn-primary" data-role="export-csv">Export CSV</button>
            </div>
        </div>
    </div>

    <div class="recon-modal" id="pdfExportModal" role="dialog" aria-hidden="true" aria-modal="true">
        <div class="recon-modal__dialog">
            <div class="recon-modal__header">
                <h4 class="recon-modal__title">Export Reconciliation PDF</h4>
                <button type="button" class="recon-modal__close" aria-label="Close" data-modal-close>
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <div class="recon-modal__body">
                <p class="recon-modal__description">Generate a PDF snapshot of your reconciliation ledger for sharing or record keeping. Set the desired period below.</p>
                <div class="recon-modal__field">
                    <label for="pdfPeriod">Period</label>
                    <select id="pdfPeriod" name="pdfPeriod">
                        <option value="">Current Month</option>
                        <option value="last-month">Last Month</option>
                        <option value="quarter-to-date">Quarter to Date</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>
                <div class="recon-modal__field" data-role="pdf-custom-range">
                    <label for="pdfFrom">From</label>
                    <input type="date" id="pdfFrom" name="pdfFrom">
                </div>
                <div class="recon-modal__field" data-role="pdf-custom-range">
                    <label for="pdfTo">To</label>
                    <input type="date" id="pdfTo" name="pdfTo">
                </div>
            </div>
            <div class="recon-modal__footer recon-modal__actions">
                <button type="button" class="recon-modal__btn-close" data-modal-close>Cancel</button>
                <button type="button" class="recon-modal__btn-primary" data-role="export-pdf">Export PDF</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const body = document.body;
            const modals = document.querySelectorAll('.recon-modal');
            const modalTriggers = document.querySelectorAll('[data-modal-target]');
            const modalCloseButtons = document.querySelectorAll('[data-modal-close]');
            const filterResetBtn = document.querySelector('[data-role="ledger-filter-clear"]');
            const ledgerSearchInput = document.getElementById('ledgerSearch');
            const ledgerTankSelect = document.getElementById('ledgerTank');
            const ledgerDateInput = document.getElementById('ledgerDate');
            const reconTable = document.getElementById('reconTable');
            const reconTableBody = reconTable ? reconTable.querySelector('tbody') : null;
            const reconRows = reconTableBody ? Array.from(reconTableBody.querySelectorAll('tr[data-record]')) : [];
            const emptyStateRow = reconTableBody ? reconTableBody.querySelector('[data-empty-state]') : null;
            const pdfPeriodSelect = document.getElementById('pdfPeriod');
            const pdfCustomRangeFields = document.querySelectorAll('[data-role="pdf-custom-range"]');
            const stockForm = document.getElementById('stockReconciliationForm');
            const openingStockInput = document.getElementById('openingStock');
            const addedStockInput = document.getElementById('addedStock');
            const totalStockInput = document.getElementById('totalStock');

            const togglePdfCustomRange = () => {
                if (!pdfCustomRangeFields.length) {
                    return;
                }
                const shouldShow = pdfPeriodSelect && pdfPeriodSelect.value === 'custom';
                pdfCustomRangeFields.forEach(field => {
                    if (shouldShow) {
                        field.classList.add('is-visible');
                    } else {
                        field.classList.remove('is-visible');
                        const input = field.querySelector('input');
                        if (input) {
                            input.value = '';
                        }
                    }
                });
            };

            const applyLedgerFilters = () => {
                if (!reconRows.length) {
                    return;
                }

                const searchTerm = ledgerSearchInput?.value.trim().toLowerCase() || '';
                const selectedTank = ledgerTankSelect?.value || '';
                const selectedDate = ledgerDateInput?.value || '';

                let visibleCount = 0;

                reconRows.forEach(row => {
                    const rowDate = row.dataset.date || '';
                    const rowTank = row.dataset.tank || '';
                    const rowNotes = row.dataset.notes || '';
                    const matchSearch = !searchTerm || row.textContent.toLowerCase().includes(searchTerm) || rowNotes.toLowerCase().includes(searchTerm);
                    const matchTank = !selectedTank || rowTank === selectedTank;
                    const matchDate = !selectedDate || rowDate === selectedDate;
                    const isVisible = matchSearch && matchTank && matchDate;

                    row.style.display = isVisible ? '' : 'none';
                    if (isVisible) {
                        visibleCount += 1;
                    }
                });

                if (emptyStateRow) {
                    emptyStateRow.style.display = visibleCount === 0 ? '' : 'none';
                }
            };

            const parseLitres = (input) => {
                if (!input) {
                    return null;
                }
                const value = parseFloat(input.value);
                return Number.isFinite(value) ? value : null;
            };

            const formatLitres = (value) => {
                if (!Number.isFinite(value)) {
                    return '';
                }
                return value.toFixed(2);
            };

            const updateTotalStock = () => {
                if (!totalStockInput) {
                    return;
                }

                const opening = parseLitres(openingStockInput);
                const added = parseLitres(addedStockInput);

                if (opening === null || added === null) {
                    totalStockInput.value = '';
                    return;
                }

                totalStockInput.value = formatLitres(opening + added);
            };

            if (totalStockInput) {
                totalStockInput.readOnly = true;
            }

            if (openingStockInput) {
                openingStockInput.addEventListener('input', updateTotalStock);
                openingStockInput.addEventListener('change', updateTotalStock);
            }

            if (addedStockInput) {
                addedStockInput.addEventListener('input', updateTotalStock);
                addedStockInput.addEventListener('change', updateTotalStock);
            }

            stockForm?.addEventListener('reset', () => {
                window.requestAnimationFrame(updateTotalStock);
            });

            updateTotalStock();

            const openModal = (modalId) => {
                const modal = document.getElementById(modalId);
                if (!modal) {
                    return;
                }
                modal.classList.add('is-visible');
                body.classList.add('modal-open');
            };

            const closeModal = (modal) => {
                modal.classList.remove('is-visible');
                if (!document.querySelector('.recon-modal.is-visible')) {
                    body.classList.remove('modal-open');
                }
            };

            modalTriggers.forEach(trigger => {
                trigger.addEventListener('click', () => {
                    const target = trigger.getAttribute('data-modal-target');
                    if (target) {
                        openModal(target);
                    }
                });
            });

            modalCloseButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const modal = button.closest('.recon-modal');
                    if (modal) {
                        closeModal(modal);
                    }
                });
            });

            modals.forEach(modal => {
                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        closeModal(modal);
                    }
                });
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    const visibleModal = document.querySelector('.recon-modal.is-visible');
                    if (visibleModal) {
                        closeModal(visibleModal);
                    }
                }
            });

            if (filterResetBtn) {
                filterResetBtn.addEventListener('click', () => {
                    const filtersContainer = filterResetBtn.closest('.recon-ledger__filters');
                    if (!filtersContainer) {
                        return;
                    }

                    filtersContainer.querySelectorAll('input').forEach(input => {
                        input.value = '';
                    });

                    filtersContainer.querySelectorAll('select').forEach(select => {
                        select.selectedIndex = 0;
                    });

                    applyLedgerFilters();
                });
            }

            ledgerSearchInput?.addEventListener('input', applyLedgerFilters);
            ledgerTankSelect?.addEventListener('change', applyLedgerFilters);
            ledgerDateInput?.addEventListener('change', applyLedgerFilters);

            if (pdfPeriodSelect) {
                togglePdfCustomRange();
                pdfPeriodSelect.addEventListener('change', togglePdfCustomRange);
            }

            applyLedgerFilters();
        });
    </script>
@endpush
