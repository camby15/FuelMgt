@extends('layouts.vertical', [
    'page_title' => 'Fuel Stations',
    'mode' => session('theme_mode', 'light'),
])

@section('css')
    <style>
        .station-board {
            background: #eef2fb;
            min-height: 100vh;
            padding: 2.5rem 2rem 3rem;
            font-family: "Inter", "Segoe UI", sans-serif;
        }

        .station-board__header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .station-board__title {
            color: #0b1f44;
        }

        .station-board__title h1 {
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            margin: 0;
        }

        .station-board__title span {
            display: block;
            margin-top: 0.35rem;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.35em;
            color: rgba(11, 31, 68, 0.55);
        }

        .station-board__meta {
            display: flex;
            gap: 0.85rem;
            flex-wrap: wrap;
        }

        .station-board__actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1.75rem;
        }

        .station-board__filters {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .station-filter-btn {
            border: 1px solid rgba(4, 52, 110, 0.25);
            background: #ffffff;
            color: #04346e;
            font-weight: 600;
            font-size: 0.74rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            padding: 0.6rem 1.1rem;
            border-radius: 999px;
            transition: all 0.25s ease;
            cursor: pointer;
        }

        .station-filter-btn.is-active,
        .station-filter-btn:hover {
            background: linear-gradient(120deg, #0a3f8c 0%, #0c64c0 100%);
            color: #ffffff;
            border-color: transparent;
        }

        .station-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            border: none;
            background: linear-gradient(90deg, #f36c21 0%, #ff912f 100%);
            color: #ffffff;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            padding: 0.62rem 1.3rem;
            border-radius: 12px;
            box-shadow: 0 10px 24px rgba(243, 108, 33, 0.28);
            cursor: pointer;
            font-size: 0.7rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .station-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 30px rgba(243, 108, 33, 0.32);
        }

        .station-action-btn.is-secondary {
            background: rgba(10, 63, 140, 0.12);
            color: #0a3f8c;
            box-shadow: none;
            letter-spacing: 0.16em;
        }

        .station-action-btn.is-danger {
            background: rgba(198, 62, 62, 0.14);
            color: #c63e3e;
            box-shadow: none;
            letter-spacing: 0.16em;
        }

        .station-row-actions {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
        }

        .station-chip {
            padding: 0.45rem 1rem;
            border-radius: 999px;
            background: rgba(3, 51, 109, 0.08);
            color: #053168;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .station-card {
            background: #ffffff;
            border-radius: 22px;
            border: 1px solid rgba(12, 42, 87, 0.08);
            box-shadow: 0 18px 46px rgba(9, 42, 97, 0.1);
            overflow: hidden;
        }

        .station-card__banner {
            padding: 1.75rem 2.2rem;
            background: linear-gradient(90deg, #001f49 0%, #023b8a 52%, #002a63 100%);
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #ffffff;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .station-card__banner h2 {
            margin: 0;
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .station-card__banner p {
            margin: 0;
            font-size: 0.85rem;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            opacity: 0.7;
        }

        .station-card__banner .badge {
            background: rgba(255, 255, 255, 0.18);
            padding: 0.55rem 1.1rem;
            border-radius: 999px;
            font-size: 0.75rem;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            font-weight: 600;
        }

        .station-table-wrapper {
            padding: 2.25rem 2.2rem 2.6rem;
            background: #f8faff;
        }

        .station-table-container {
            width: 100%;
            overflow-x: auto;
            border-radius: 18px;
            box-shadow: inset 0 0 0 1px rgba(12, 44, 96, 0.12);
        }

        .station-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 720px;
        }

        .station-table thead tr {
            background: linear-gradient(90deg, #021a49 0%, #083c85 100%);
            color: #ffffff;
        }

        .station-table thead th {
            padding: 0.85rem 0.75rem;
            font-size: 0.75rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            font-weight: 600;
            border-right: 1px solid rgba(255, 255, 255, 0.12);
            text-align: left;
            white-space: nowrap;
        }

        .station-table tbody tr:nth-child(even) {
            background: #f3f6ff;
        }

        .station-table tbody td {
            padding: 0.75rem 0.75rem;
            font-size: 0.82rem;
            color: #103566;
            font-weight: 500;
            text-align: left;
            border-right: 1px solid rgba(12, 44, 96, 0.12);
            border-bottom: 1px solid rgba(12, 44, 96, 0.12);
            white-space: normal;
            line-height: 1.45;
        }

        .station-table tbody td:first-child {
            font-weight: 600;
            color: #0b2f66;
        }

        .station-name {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
        }

        .station-name__meta {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.18rem 0.6rem;
            border-radius: 999px;
            background: rgba(4, 51, 110, 0.08);
            font-size: 0.62rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(4, 44, 98, 0.85);
            font-weight: 600;
        }

        .station-phone {
            color: #0c4d9c;
            font-weight: 600;
            letter-spacing: 0.04em;
        }

        .station-phone a {
            color: inherit;
            text-decoration: none;
        }

        .station-phone a:hover {
            text-decoration: underline;
        }

        .station-address {
            max-width: 320px;
        }

        .station-table tfoot td {
            padding: 0.85rem 0.6rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.18em;
            color: #ffffff;
            background: #021a49;
            border-right: 1px solid rgba(255, 255, 255, 0.16);
        }

        .station-legend {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            flex-wrap: wrap;
            margin-top: 0.8rem;
            color: rgba(13, 45, 95, 0.7);
            font-size: 0.68rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            font-weight: 600;
        }

        .station-legend span {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .legend-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .legend-ago {
            background: #f36c21;
        }

        .legend-pms {
            background: #0c74a0;
        }

        /* ==================== MODAL STYLES – ENHANCED RESPONSIVENESS ==================== */
        .station-modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(6, 24, 58, 0.75);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1050;
            padding: 1rem;
            overflow-y: auto;
        }

        .station-modal-backdrop.is-visible {
            display: flex;
        }

        .station-modal {
            width: min(660px, 95vw);
            max-height: 95vh;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(5, 23, 61, 0.35);
            display: flex;
            flex-direction: column;
            animation: modalFadeIn 0.3s ease-out;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .station-modal__header {
            padding: 1.4rem 1.8rem;
            background: linear-gradient(120deg, #021a49 0%, #0c4d9c 100%);
            color: #ffffff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            flex-shrink: 0;
        }

        .station-modal__header h3 {
            margin: 0;
            font-size: 1.15rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .station-modal__close {
            border: none;
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            font-size: 1.3rem;
            cursor: pointer;
            transition: background 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .station-modal__close:hover {
            background: rgba(255, 255, 255, 0.28);
        }

        .station-modal__body {
            padding: 1.8rem 1.8rem 1.2rem;
            flex: 1;
            overflow-y: auto;
            background: #f8faff;
        }

        .station-form-grid {
            display: grid;
            gap: 1.3rem;
            grid-template-columns: repeat(2, 1fr);
        }

        .station-form-grid .station-form-group:last-child {
            grid-column: span 2;
        }

        .station-form-group {
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
        }

        .station-form-group label {
            font-size: 0.74rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(11, 31, 68, 0.78);
            font-weight: 700;
        }

        .station-input,
        .station-select,
        .station-textarea {
            border: 1px solid rgba(4, 52, 110, 0.18);
            border-radius: 12px;
            padding: 0.78rem 1rem;
            font-size: 0.88rem;
            color: #0d2c5f;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            background: #ffffff;
            width: 100%;
        }

        .station-input:focus,
        .station-select:focus,
        .station-textarea:focus {
            border-color: #0c4d9c;
            box-shadow: 0 0 0 3px rgba(12, 77, 156, 0.22);
            outline: none;
        }

        .station-textarea {
            min-height: 110px;
            resize: vertical;
        }

        .station-modal__footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.9rem;
            padding: 1rem 1.8rem;
            background: #ffffff;
            border-top: 1px solid rgba(12, 44, 96, 0.12);
            flex-shrink: 0;
        }

        .station-modal__footer button {
            border: none;
            border-radius: 12px;
            padding: 0.75rem 1.6rem;
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            cursor: pointer;
            min-width: 100px;
        }

        .station-modal__cancel {
            background: #e3e9f7;
            color: #0d2c5f;
        }

        .station-modal__submit {
            background: linear-gradient(120deg, #0a3f8c 0%, #0c64c0 100%);
            color: #ffffff;
            box-shadow: 0 10px 24px rgba(11, 59, 128, 0.28);
        }

        .station-detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1.1rem;
        }

        .station-detail-item {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
            background: rgba(4, 52, 110, 0.04);
            border-radius: 12px;
            padding: 0.85rem 1rem;
        }

        .station-detail-item span {
            font-size: 0.64rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(11, 31, 68, 0.6);
            font-weight: 700;
        }

        .station-detail-item strong {
            font-size: 0.9rem;
            color: #0d2c5f;
            font-weight: 700;
            letter-spacing: 0.04em;
        }

        .station-detail-item a {
            color: inherit;
            text-decoration: none;
        }

        .station-detail-item a:hover {
            text-decoration: underline;
        }

        .station-delete-warning {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            background: rgba(198, 62, 62, 0.08);
            border: 1px solid rgba(198, 62, 62, 0.18);
            border-radius: 14px;
            padding: 1rem 1.1rem;
            color: #8f1f1f;
        }

        .station-delete-warning h4 {
            margin: 0;
            font-size: 0.95rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .station-delete-warning p {
            margin: 0;
            font-size: 0.82rem;
            line-height: 1.5;
        }

        .station-delete-warning strong {
            color: #5f1111;
        }

        /* ==================== RESPONSIVE BREAKPOINTS ==================== */
        @media (max-width: 768px) {
            .station-board {
                padding: 1.75rem 1.25rem 2.5rem;
            }

            .station-board__header {
                flex-direction: column;
                align-items: stretch;
            }

            .station-board__actions {
                width: 100%;
            }

            .station-board__actions .station-action-btn {
                width: 100% !important;
                justify-content: center;
            }

            .station-card__banner,
            .station-table-wrapper {
                padding: 1.5rem;
            }

            .station-table {
                min-width: 100%;
            }

            .station-row-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .station-row-actions .station-action-btn {
                width: 100%;
                justify-content: center;
            }

            .station-address {
                max-width: none;
            }

            .station-form-grid {
                grid-template-columns: 1fr;
            }

            .station-form-grid .station-form-group:last-child {
                grid-column: auto;
            }

            .station-modal {
                width: min(100%, 95vw);
                max-height: 92vh;
            }

            .station-modal__header {
                padding: 1.2rem 1.4rem;
            }

            .station-modal__header h3 {
                font-size: 1rem;
            }

            .station-modal__body {
                padding: 1.4rem 1.4rem 1rem;
            }

            .station-modal__footer {
                padding: 0.9rem 1.4rem;
                flex-direction: column;
            }

            .station-modal__footer button {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .station-board__title h1 {
                font-size: 1.4rem;
            }

            .station-action-btn {
                font-size: 0.68rem !important;
                padding: 0.7rem 1rem !important;
                letter-spacing: 0.12em;
            }

            .station-row-actions .station-action-btn {
                font-size: 0.65rem;
                padding: 0.7rem 1rem;
            }

            .station-modal__header {
                padding: 1.1rem 1.2rem;
            }

            .station-modal__body {
                padding: 1.2rem 1.2rem 1rem;
            }

            .station-form-group label {
                font-size: 0.7rem;
            }

            .station-input,
            .station-select,
            .station-textarea {
                font-size: 0.84rem;
                padding: 0.65rem 0.85rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="station-board">
        @php
            $stationRows = [
                ['name' => 'Wiaga','product' => 'AGO','code' => 'ST-001','location' => 'Builsa North, Upper East','address' => 'Station Road, Wiaga Township','manager' => 'Abena Kwakye','phone' => '+233 20 111 2233'],
                ['name' => 'Pwalugu','product' => 'PMS','code' => 'ST-002','location' => 'Talensi, Upper East','address' => 'Bolga–Tamale Hwy, Pwalugu Junction','manager' => 'Isaac Ndebugri','phone' => '+233 24 555 6677'],
                ['name' => 'Navrongo Main','product' => 'AGO','code' => 'ST-003','location' => 'Kassena-Nankana, Upper East','address' => 'Central Market Ring Road, Navrongo','manager' => 'Helen Bawa','phone' => '+233 50 998 4411'],
                ['name' => 'Wapuli','product' => 'PMS','code' => 'ST-004','location' => 'Saboba, Northern Region','address' => 'Opp. Wapuli Transport Yard, Tamale-Bimbilla Rd','manager' => 'Samuel Tia','phone' => '+233 27 803 5566'],
                ['name' => 'Kintampo','product' => 'AGO','code' => 'ST-005','location' => 'Kintampo North, Bono East','address' => 'Techiman-Kintampo Hwy, Kintampo Rest Stop','manager' => 'Anita Jabari','phone' => '+233 26 123 8899'],
                ['name' => 'Amoako','product' => 'PMS','code' => 'ST-006','location' => 'East Mamprusi, North East','address' => 'Amoako Lorry Park, Nalerigu Rd','manager' => 'Daniel Esubonteng','phone' => '+233 24 330 7711'],
                ['name' => 'Larabanga','product' => 'AGO','code' => 'ST-007','location' => 'West Gonja, Savannah','address' => 'Larabanga Junction, Mole Park Access Rd','manager' => 'Rahim Sulemana','phone' => '+233 20 700 4410'],
                ['name' => 'Bugubele','product' => 'PMS','code' => 'ST-008','location' => 'Builsa South, Upper East','address' => 'Bugubele Community Centre Street','manager' => 'Mabel Akosua','phone' => '+233 27 556 9981'],
                ['name' => 'Navrongo 2','product' => 'AGO','code' => 'ST-009','location' => 'Kassena-Nankana, Upper East','address' => 'Navrongo-Airstrip Road, Estate Area','manager' => 'Isaac Bangnab','phone' => '+233 55 881 7744'],
                ['name' => 'Paga Annex','product' => 'PMS','code' => 'ST-010','location' => 'Kassena-Nankana West, Upper East','address' => 'Border Market Lane, Paga','manager' => 'Lydia Obeng','phone' => '+233 24 990 6623'],
                ['name' => 'Bamvin','product' => 'AGO','code' => 'ST-011','location' => 'Sawla-Tuna-Kalba, Savannah','address' => 'Bamvin High Street, Opp. Community Clinic','manager' => 'Jonah Laar','phone' => '+233 20 332 1144'],
            ];

            $managerNames = array_unique(array_column($stationRows, 'manager'));
        @endphp

        <div class="station-board__actions">
            <button class="station-action-btn" type="button" data-open-modal="station-add">
                + Add Station
            </button>
        </div>

        <div class="station-card">
            <div class="station-card__banner">
                <span class="badge">Last Sync: {{ now()->format('h:i A') }}</span>
            </div>

            <div class="station-table-wrapper">
                <div class="station-table-container">
                    <table class="station-table" data-station-table>
                        <thead>
                            <tr>
                                <th>Station</th>
                                <th>Location</th>
                                <th>Address</th>
                                <th>Manager</th>
                                <th>Telephone</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stationRows as $station)
                                <tr data-product="{{ $station['product'] }}">
                                    <td>
                                        <div class="station-name">
                                            <strong>{{ $station['name'] }}</strong>
                                            <span class="station-name__meta">
                                                {{ $station['code'] }} · {{ $station['product'] }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>{{ $station['location'] }}</td>
                                    <td class="station-address">{{ $station['address'] }}</td>
                                    <td>{{ $station['manager'] }}</td>
                                    <td class="station-phone">
                                        <a href="tel:{{ preg_replace('/\s+/', '', $station['phone']) }}">{{ $station['phone'] }}</a>
                                    </td>
                                    <td>
                                        <div class="station-row-actions">
                                            <button
                                                class="station-action-btn is-secondary"
                                                type="button"
                                                data-open-modal="station-view"
                                                data-station-name="{{ e($station['name']) }}"
                                                data-station-code="{{ e($station['code']) }}"
                                                data-station-product="{{ e($station['product']) }}"
                                                data-station-location="{{ e($station['location']) }}"
                                                data-station-address="{{ e($station['address']) }}"
                                                data-station-manager="{{ e($station['manager']) }}"
                                                data-station-phone="{{ e($station['phone']) }}"
                                                data-station-phone-raw="{{ preg_replace('/\s+/', '', $station['phone']) }}"
                                            >
                                                View
                                            </button>
                                            <button
                                                class="station-action-btn is-danger"
                                                type="button"
                                                data-open-modal="station-delete"
                                                data-station-name="{{ e($station['name']) }}"
                                                data-station-code="{{ e($station['code']) }}"
                                                data-station-manager="{{ e($station['manager']) }}"
                                                data-station-location="{{ e($station['location']) }}"
                                            >
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Add Station Modal --}}
        <div class="station-modal-backdrop" data-modal="station-add">
            <div class="station-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title-add">
                <div class="station-modal__header">
                    <h3 id="modal-title-add">Add New Station</h3>
                    <button class="station-modal__close" type="button" data-close-modal aria-label="Close modal">
                        ×
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="station-modal__body">
                        <div class="station-form-grid">
                            <div class="station-form-group">
                                <label for="station-name">Station Name *</label>
                                <input type="text" id="station-name" name="name" class="station-input" placeholder="Enter station name" required />
                            </div>

                            <div class="station-form-group">
                                <label for="station-code">Station Code *</label>
                                <input type="text" id="station-code" name="code" class="station-input" placeholder="ST-XXX" required />
                            </div>

                            <div class="station-form-group">
                                <label for="station-product">Product Type *</label>
                                <select id="station-product" name="product" class="station-select" required>
                                    <option value="">Select product type</option>
                                    <option value="AGO">AGO (Diesel)</option>
                                    <option value="PMS">PMS (Petrol)</option>
                                </select>
                            </div>

                            <div class="station-form-group">
                                <label for="station-location">Location *</label>
                                <input type="text" id="station-location" name="location" class="station-input" placeholder="District, Region" required />
                            </div>

                            <div class="station-form-group">
                                <label for="station-manager">Manager Name *</label>
                                <select id="station-manager" name="manager" class="station-select" required>
                                    <option value="">Select manager</option>
                                    @foreach ($managerNames as $managerName)
                                        <option value="{{ $managerName }}">{{ $managerName }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="station-form-group">
                                <label for="station-phone">Telephone *</label>
                                <input type="tel" id="station-phone" name="phone" class="station-input" placeholder="+233 XX XXX XXXX" required />
                            </div>

                            <div class="station-form-group">
                                <label for="station-address">Full Address *</label>
                                <textarea id="station-address" name="address" class="station-textarea" placeholder="Enter complete station address" required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="station-modal__footer">
                        <button type="button" class="station-modal__cancel" data-close-modal>Cancel</button>
                        <button type="submit" class="station-modal__submit">Save</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- View Station Modal --}}
        <div class="station-modal-backdrop" data-modal="station-view">
            <div class="station-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title-view">
                <div class="station-modal__header">
                    <h3 id="modal-title-view">Station Details</h3>
                    <button class="station-modal__close" type="button" data-close-modal aria-label="Close modal">
                        ×
                    </button>
                </div>
                <div class="station-modal__body">
                    <div class="station-detail-grid">
                        <div class="station-detail-item">
                            <span>Station Name</span>
                            <strong data-modal-field="name">—</strong>
                        </div>
                        <div class="station-detail-item">
                            <span>Station Code</span>
                            <strong data-modal-field="code">—</strong>
                        </div>
                        <div class="station-detail-item">
                            <span>Product</span>
                            <strong data-modal-field="product">—</strong>
                        </div>
                        <div class="station-detail-item">
                            <span>Manager</span>
                            <strong data-modal-field="manager">—</strong>
                        </div>
                        <div class="station-detail-item">
                            <span>Telephone</span>
                            <strong>
                                <a href="#" data-modal-field="phone-link">—</a>
                            </strong>
                        </div>
                        <div class="station-detail-item">
                            <span>Location</span>
                            <strong data-modal-field="location">—</strong>
                        </div>
                        <div class="station-detail-item" style="grid-column: span 2;">
                            <span>Address</span>
                            <strong data-modal-field="address">—</strong>
                        </div>
                    </div>
                </div>
                <div class="station-modal__footer">
                    <button type="button" class="station-modal__cancel" data-close-modal>Close</button>
                </div>
            </div>
        </div>

        {{-- Delete Station Modal --}}
        <div class="station-modal-backdrop" data-modal="station-delete">
            <div class="station-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title-delete">
                <div class="station-modal__header">
                    <h3 id="modal-title-delete">Delete Station</h3>
                    <button class="station-modal__close" type="button" data-close-modal aria-label="Close modal">
                        ×
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="station-modal__body" data-delete-modal>
                        <div class="station-delete-warning">
                            <h4>Confirm Deletion</h4>
                            <p>
                                You are about to remove <strong data-modal-field="name">—</strong> from the station list.
                                This action cannot be undone and will also remove associated performance records.
                            </p>
                            <p>
                                Manager: <strong data-modal-field="manager">—</strong><br>
                                Location: <strong data-modal-field="location">—</strong><br>
                                Station Code: <strong data-modal-field="code">—</strong>
                            </p>
                        </div>

                        <input type="hidden" name="station_code" data-modal-field="code-input" value="">
                    </div>
                    <div class="station-modal__footer">
                        <button type="button" class="station-modal__cancel" data-close-modal>Cancel</button>
                        <button type="submit" class="station-modal__submit">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const filterGroup = document.querySelector('[data-filter-group]');
            const tableRows = document.querySelectorAll('[data-station-table] tbody tr');
            const modalBackdrops = document.querySelectorAll('[data-modal]');
            const openModalButtons = document.querySelectorAll('[data-open-modal]');
            const closeModalButtons = document.querySelectorAll('[data-close-modal]');

            // Filter functionality
            if (filterGroup) {
                filterGroup.addEventListener('click', (e) => {
                    const btn = e.target.closest('[data-filter]');
                    if (!btn) return;

                    filterGroup.querySelectorAll('.station-filter-btn').forEach((b) =>
                        b.classList.toggle('is-active', b === btn)
                    );

                    const filter = btn.dataset.filter;
                    tableRows.forEach((row) => {
                        const matches = filter === 'all' || row.dataset.product === filter;
                        row.style.display = matches ? '' : 'none';
                    });
                });
            }

            const modalRegistry = new Map();

            const populateModal = (modalName, dataset = {}) => {
                const modal = modalRegistry.get(modalName);
                if (!modal) return;

                if (modalName === 'station-add') {
                    modal.querySelector('form')?.reset();
                    return;
                }

                const data = { ...dataset };

                if (modalName === 'station-view') {
                    const setText = (field, value = '—') => {
                        const el = modal.querySelector(`[data-modal-field="${field}"]`);
                        if (!el) return;
                        el.textContent = value || '—';
                    };

                    setText('name', data.stationName);
                    setText('code', data.stationCode);
                    setText('product', data.stationProduct);
                    setText('manager', data.stationManager);
                    setText('location', data.stationLocation);
                    setText('address', data.stationAddress);

                    const phoneLink = modal.querySelector('[data-modal-field="phone-link"]');
                    if (phoneLink) {
                        phoneLink.textContent = data.stationPhone || '—';
                        phoneLink.setAttribute('href', data.stationPhoneRaw ? `tel:${data.stationPhoneRaw}` : '#');
                    }
                }

                if (modalName === 'station-delete') {
                    const setText = (field, value = '—') => {
                        const el = modal.querySelector(`[data-modal-field="${field}"]`);
                        if (!el) return;
                        el.textContent = value || '—';
                    };

                    setText('name', data.stationName);
                    setText('manager', data.stationManager);
                    setText('location', data.stationLocation);
                    setText('code', data.stationCode);

                    const codeInput = modal.querySelector('[data-modal-field="code-input"]');
                    if (codeInput) {
                        codeInput.value = data.stationCode || '';
                    }
                }
            };

            const toggleModal = (modalName, show, dataset = {}) => {
                const modal = modalRegistry.get(modalName);
                if (!modal) return;

                if (show) {
                    populateModal(modalName, dataset);
                    modal.classList.add('is-visible');
                    document.body.style.overflow = 'hidden';
                } else {
                    modal.classList.remove('is-visible');
                    if (![...modalRegistry.values()].some((el) => el.classList.contains('is-visible'))) {
                        document.body.style.overflow = '';
                    }
                }
            };

            modalBackdrops.forEach((modal) => {
                const modalName = modal.dataset.modal;
                if (!modalName) return;
                modalRegistry.set(modalName, modal);

                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        toggleModal(modalName, false);
                    }
                });

                modal.querySelector('.station-modal')?.addEventListener('click', (event) => event.stopPropagation());
            });

            openModalButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    const targetModal = button.dataset.openModal;
                    if (!targetModal) return;
                    toggleModal(targetModal, true, button.dataset);
                });
            });

            closeModalButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    const parentModal = button.closest('[data-modal]');
                    if (!parentModal) return;
                    toggleModal(parentModal.dataset.modal, false);
                });
            });
        });
    </script>
@endpush