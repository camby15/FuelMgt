@extends('layouts.vertical', [
    'page_title' => 'Station Map',
    'mode' => session('theme_mode', 'light'),
])

@section('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        .station-map-board {
            background: #f3f6ff;
            min-height: 100vh;
            padding: 2.5rem 2rem 3rem;
            font-family: "Inter", "Segoe UI", sans-serif;
            color: #0b1f44;
        }

        .station-map__header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 1.5rem;
            align-items: flex-end;
            margin-bottom: 1.5rem;
        }

        .station-map__title h1 {
            margin: 0;
            font-size: 1.9rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .station-map__title span {
            display: block;
            margin-top: 0.35rem;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.3em;
            color: rgba(11, 31, 68, 0.55);
        }

        .station-map__meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .station-map__meta-chip {
            padding: 0.6rem 1.05rem;
            border-radius: 999px;
            background: rgba(12, 77, 156, 0.1);
            color: #0c4d9c;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.22em;
            text-transform: uppercase;
        }

        .station-map__card {
            background: #ffffff;
            border-radius: 22px;
            box-shadow: 0 20px 50px rgba(9, 42, 97, 0.12);
            overflow: hidden;
        }

        .station-map__card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem 2rem;
            background: linear-gradient(104deg, #021a49 0%, #0b4a9b 90%);
            color: #ffffff;
        }

        .station-map__card-header h2 {
            margin: 0;
            font-size: 1.1rem;
            letter-spacing: 0.3em;
            text-transform: uppercase;
        }

        .station-map__body {
            padding: 1.5rem 2rem 2rem;
            background: #f8faff;
        }

        .station-map__actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-bottom: 1.25rem;
        }

        .station-map__action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            border: none;
            border-radius: 14px;
            padding: 0.7rem 1.35rem;
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.16em;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
            color: #ffffff;
            background: linear-gradient(120deg, #0a3f8c 0%, #0c64c0 100%);
            box-shadow: 0 12px 28px rgba(11, 59, 128, 0.22);
        }

        .station-map__action-btn.is-secondary {
            background: linear-gradient(120deg, #f36c21 0%, #ff902f 100%);
            box-shadow: 0 12px 26px rgba(243, 108, 33, 0.24);
        }

        .station-map__action-btn.is-ghost {
            background: rgba(10, 63, 140, 0.12);
            color: #0a3f8c;
            box-shadow: none;
        }

        .station-map__action-btn:hover {
            transform: translateY(-2px);
        }

        .station-map__action-btn:active {
            transform: translateY(0);
            opacity: 0.85;
        }

        .station-map__action-btn i {
            font-size: 1rem;
        }

        .station-directory-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.1rem;
        }

        .station-directory-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 1rem 1.15rem;
            box-shadow: 0 14px 32px rgba(7, 28, 68, 0.14);
            border: 1px solid rgba(12, 44, 96, 0.08);
            display: flex;
            flex-direction: column;
            gap: 0.55rem;
        }

        .station-directory-card h4 {
            margin: 0;
            font-size: 1rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #0b1f44;
        }

        .station-directory-card span {
            display: block;
            font-size: 0.74rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(11, 31, 68, 0.68);
        }

        .station-directory-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .station-directory-meta .chip {
            padding: 0.35rem 0.65rem;
            border-radius: 999px;
            background: rgba(12, 77, 156, 0.1);
            color: #0c4d9c;
            font-size: 0.65rem;
            letter-spacing: 0.14em;
            font-weight: 600;
        }

        .station-layer-options {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .station-layer-toggle {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.85rem 1rem;
            border-radius: 14px;
            border: 1px solid rgba(12, 44, 96, 0.16);
            background: rgba(4, 36, 88, 0.04);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .station-layer-toggle.is-active {
            background: linear-gradient(120deg, #0a3f8c 0%, #0c64c0 100%);
            border-color: transparent;
            color: #ffffff;
            box-shadow: 0 12px 28px rgba(11, 59, 128, 0.28);
        }

        .station-layer-toggle .toggle-chip {
            padding: 0.25rem 0.55rem;
            border-radius: 8px;
            background: rgba(11, 31, 68, 0.16);
            font-size: 0.65rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .station-layer-toggle.is-active .toggle-chip {
            background: rgba(255, 255, 255, 0.22);
        }

        .station-share-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
        }

        .station-share-card {
            border-radius: 16px;
            padding: 1rem 1.1rem;
            background: rgba(12, 44, 96, 0.05);
            border: 1px solid rgba(12, 44, 96, 0.1);
            display: flex;
            flex-direction: column;
            gap: 0.55rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .station-share-card.is-active,
        .station-share-card:hover {
            background: linear-gradient(120deg, #f36c21 0%, #ff902f 100%);
            border-color: transparent;
            color: #ffffff;
            box-shadow: 0 16px 34px rgba(243, 108, 33, 0.28);
        }

        .station-share-card i {
            font-size: 1.4rem;
        }

        .station-modal__description {
            margin-bottom: 1.2rem;
            font-size: 0.78rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(11, 31, 68, 0.6);
        }

        .station-map__map {
            position: relative;
            height: clamp(420px, 70vh, 640px);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: inset 0 0 0 1px rgba(9, 42, 97, 0.08);
        }

        .leaflet-popup-content-wrapper {
            border-radius: 16px;
            box-shadow: 0 16px 40px rgba(5, 23, 61, 0.2);
        }

        .station-popup {
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
            font-family: "Inter", "Segoe UI", sans-serif;
        }

        .station-popup__title {
            font-size: 1rem;
            font-weight: 700;
            color: #0b1f44;
            margin: 0;
        }

        .station-popup__meta {
            display: inline-flex;
            gap: 0.5rem;
            font-size: 0.72rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(11, 31, 68, 0.7);
        }

        .station-popup__actions {
            display: flex;
            gap: 0.6rem;
        }

        .station-popup__btn {
            border: none;
            border-radius: 10px;
            cursor: pointer;
            padding: 0.55rem 0.8rem;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .station-popup__btn.is-primary {
            background: linear-gradient(120deg, #0a3f8c 0%, #0c64c0 100%);
            color: #ffffff;
            box-shadow: 0 12px 26px rgba(11, 59, 128, 0.28);
        }

        .station-popup__btn.is-secondary {
            background: rgba(243, 108, 33, 0.12);
            color: #f36c21;
        }

        .station-popup__btn:hover {
            transform: translateY(-1px);
        }

        .station-modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(6, 24, 58, 0.8);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1050;
            padding: 1.2rem;
        }

        .station-modal-backdrop.is-visible {
            display: flex;
        }

        .station-modal {
            width: min(640px, 96vw);
            max-height: 94vh;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(5, 23, 61, 0.35);
            display: flex;
            flex-direction: column;
            animation: modalFadeIn 0.25s ease-out;
        }

        .station-modal__header {
            padding: 1.4rem 1.8rem;
            background: linear-gradient(120deg, #021a49 0%, #0c4d9c 100%);
            color: #ffffff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
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
            background: rgba(255, 255, 255, 0.18);
            color: #ffffff;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            font-size: 1.3rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s ease;
        }

        .station-modal__close:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .station-modal__body {
            padding: 1.8rem 1.8rem 1.2rem;
            background: #f8faff;
            overflow-y: auto;
        }

        .station-detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1.1rem;
        }

        .station-detail-item {
            background: rgba(4, 52, 110, 0.06);
            border-radius: 14px;
            padding: 0.85rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .station-detail-item span {
            font-size: 0.64rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(11, 31, 68, 0.6);
            font-weight: 700;
        }

        .station-detail-item strong {
            font-size: 0.92rem;
            color: #0d2c5f;
            font-weight: 700;
        }

        .station-modal__footer {
            padding: 1rem 1.8rem 1.5rem;
            background: #ffffff;
            display: flex;
            justify-content: flex-end;
        }

        .station-modal__footer button {
            border: none;
            border-radius: 12px;
            padding: 0.75rem 1.6rem;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.16em;
            cursor: pointer;
            background: rgba(255, 255, 255, 0.94);
            color: #0b1f44;
            box-shadow: 0 10px 24px rgba(11, 59, 128, 0.18);
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .station-map-board {
                padding: 1.9rem 1.35rem 2.5rem;
            }

            .station-map__card-header,
            .station-map__body {
                padding: 1.35rem 1.5rem;
            }

            .station-map__header {
                align-items: stretch;
            }

            .station-map__title h1 {
                font-size: 1.5rem;
            }

            .station-map__map {
                height: clamp(360px, 62vh, 540px);
            }

            .station-modal__body {
                padding: 1.4rem 1.4rem 1rem;
            }
        }

        @media (max-width: 520px) {
            .station-map__title h1 {
                font-size: 1.35rem;
            }

            .station-map__legend,
            .station-map__meta-chip {
                letter-spacing: 0.12em;
            }

            .station-modal__header {
                padding: 1.15rem 1.3rem;
            }

            .station-modal__body {
                padding: 1.2rem 1.2rem 0.9rem;
            }
        }
    </style>
@endsection

@section('content')
    @php
        $stations = [
            ['name' => 'Wiaga', 'code' => 'ST-001', 'location' => 'Builsa North, Upper East', 'address' => 'Station Road, Wiaga Township', 'manager' => 'Abena Kwakye', 'phone' => '+233 20 111 2233', 'lat' => 10.7827, 'lng' => -0.8622],
            ['name' => 'Pwalugu', 'code' => 'ST-002', 'location' => 'Talensi, Upper East', 'address' => 'Bolga–Tamale Hwy, Pwalugu Junction', 'manager' => 'Isaac Ndebugri', 'phone' => '+233 24 555 6677', 'lat' => 10.6841, 'lng' => -0.9245],
            ['name' => 'Navrongo Main', 'code' => 'ST-003', 'location' => 'Kassena-Nankana, Upper East', 'address' => 'Central Market Ring Road, Navrongo', 'manager' => 'Helen Bawa', 'phone' => '+233 50 998 4411', 'lat' => 10.8965, 'lng' => -0.8937],
            ['name' => 'Wapuli', 'code' => 'ST-004', 'location' => 'Saboba, Northern Region', 'address' => 'Opp. Wapuli Transport Yard, Tamale-Bimbilla Rd', 'manager' => 'Samuel Tia', 'phone' => '+233 27 803 5566', 'lat' => 9.8456, 'lng' => -0.1234],
            ['name' => 'Kintampo', 'code' => 'ST-005', 'location' => 'Kintampo North, Bono East', 'address' => 'Techiman-Kintampo Hwy, Kintampo Rest Stop', 'manager' => 'Anita Jabari', 'phone' => '+233 26 123 8899', 'lat' => 8.0456, 'lng' => -1.7234],
            ['name' => 'Amoako', 'code' => 'ST-006', 'location' => 'East Mamprusi, North East', 'address' => 'Amoako Lorry Park, Nalerigu Rd', 'manager' => 'Daniel Esubonteng', 'phone' => '+233 24 330 7711', 'lat' => 10.3456, 'lng' => -0.5234],
            ['name' => 'Larabanga', 'code' => 'ST-007', 'location' => 'West Gonja, Savannah', 'address' => 'Larabanga Junction, Mole Park Access Rd', 'manager' => 'Rahim Sulemana', 'phone' => '+233 20 700 4410', 'lat' => 9.2234, 'lng' => -2.1234],
            ['name' => 'Bugubele', 'code' => 'ST-008', 'location' => 'Builsa South, Upper East', 'address' => 'Bugubele Community Centre Street', 'manager' => 'Mabel Akosua', 'phone' => '+233 27 556 9981', 'lat' => 10.6234, 'lng' => -0.7234],
            ['name' => 'Navrongo 2', 'code' => 'ST-009', 'location' => 'Kassena-Nankana, Upper East', 'address' => 'Navrongo-Airstrip Road, Estate Area', 'manager' => 'Isaac Bangnab', 'phone' => '+233 55 881 7744', 'lat' => 10.9065, 'lng' => -0.9037],
            ['name' => 'Paga Annex', 'code' => 'ST-010', 'location' => 'Kassena-Nankana West, Upper East', 'address' => 'Border Market Lane, Paga', 'manager' => 'Lydia Obeng', 'phone' => '+233 24 990 6623', 'lat' => 10.9876, 'lng' => -1.0234],
            ['name' => 'Bamvin', 'code' => 'ST-011', 'location' => 'Sawla-Tuna-Kalba, Savannah', 'address' => 'Bamvin High Street, Opp. Community Clinic', 'manager' => 'Jonah Laar', 'phone' => '+233 20 332 1144', 'lat' => 9.4567, 'lng' => -2.3456],
        ];
    @endphp

    <div class="station-map-board" data-station-map-board>
        <div class="station-map__header">
            <div class="station-map__title">
                <h1>Network Map</h1>
                <span>Fuel Stations · Coverage Overview</span>
            </div>
            <div class="station-map__meta">
                <span class="station-map__meta-chip">Total Stations: {{ count($stations) }}</span>
                <span class="station-map__meta-chip">Last Sync: {{ now()->format('M d, Y · h:i A') }}</span>
            </div>
        </div>

        <div class="station-map__card">
            <div class="station-map__card-header">
                <h2>Live Coverage Map</h2>
            </div>

            <div class="station-map__body">
                <div class="station-map__actions">
                    <button class="station-map__action-btn" type="button" data-open-modal="station-directory">
                        <i class="ri-list-check"></i>
                        <span>Station Directory</span>
                    </button>
                    <button class="station-map__action-btn is-secondary" type="button" data-open-modal="station-layers">
                        <i class="ri-sliders-2-line"></i>
                        <span>Map Layers</span>
                    </button>
                    <button class="station-map__action-btn is-ghost" type="button" data-open-modal="station-share">
                        <i class="ri-share-forward-line"></i>
                        <span>Share View</span>
                    </button>
                </div>

                <div id="station-map" class="station-map__map" role="region" aria-label="Station map"></div>
            </div>
        </div>
    </div>

    <div class="station-modal-backdrop" data-modal="station-detail" aria-hidden="true">
        <div class="station-modal" role="dialog" aria-modal="true" aria-labelledby="station-modal-title">
            <div class="station-modal__header">
                <h3 id="station-modal-title">Station Details</h3>
                <button class="station-modal__close" type="button" data-close-modal>&times;</button>
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
                        <span>Manager</span>
                        <strong data-modal-field="manager">—</strong>
                    </div>
                    <div class="station-detail-item">
                        <span>Telephone</span>
                        <strong><a href="#" data-modal-field="phone-link">—</a></strong>
                    </div>
                    <div class="station-detail-item">
                        <span>Location</span>
                        <strong data-modal-field="location">—</strong>
                    </div>
                    <div class="station-detail-item">
                        <span>Address</span>
                        <strong data-modal-field="address">—</strong>
                    </div>
                    <div class="station-detail-item">
                        <span>GPS Coordinates</span>
                        <strong data-modal-field="coordinates">—</strong>
                    </div>
                </div>
            </div>
            <div class="station-modal__footer">
                <button type="button" data-close-modal>Close</button>
            </div>
        </div>
    </div>

    <div class="station-modal-backdrop" data-modal="station-directory" aria-hidden="true">
        <div class="station-modal" role="dialog" aria-modal="true" aria-labelledby="station-directory-title">
            <div class="station-modal__header">
                <h3 id="station-directory-title">Station Directory</h3>
                <button class="station-modal__close" type="button" data-close-modal>&times;</button>
            </div>
            <div class="station-modal__body">
                <p class="station-modal__description">Browse every location and jump straight into detailed views.</p>
                <div class="station-directory-list">
                    @foreach ($stations as $station)
                        <article class="station-directory-card">
                            <h4>{{ $station['name'] }}</h4>
                            <span>{{ $station['location'] }}</span>
                            <div class="station-directory-meta">
                                <div class="chip">{{ $station['code'] }}</div>
                                <div class="chip">Mgr: {{ $station['manager'] }}</div>
                            </div>
                            <div class="station-directory-meta">
                                <div class="chip">{{ $station['phone'] }}</div>
                            </div>
                            <button
                                type="button"
                                class="station-popup__btn is-primary"
                                data-directory-view="{{ $loop->index }}"
                            >
                                Focus &amp; View
                            </button>
                        </article>
                    @endforeach
                </div>
            </div>
            <div class="station-modal__footer">
                <button type="button" data-close-modal>Close</button>
            </div>
        </div>
    </div>

    <div class="station-modal-backdrop" data-modal="station-layers" aria-hidden="true">
        <div class="station-modal" role="dialog" aria-modal="true" aria-labelledby="station-layers-title">
            <div class="station-modal__header">
                <h3 id="station-layers-title">Map Layers</h3>
                <button class="station-modal__close" type="button" data-close-modal>&times;</button>
            </div>
            <div class="station-modal__body">
                <p class="station-modal__description">Toggle visual overlays to tailor the coverage map to your needs.</p>
                <div class="station-layer-options">
                    <button type="button" class="station-layer-toggle is-active" data-layer-toggle>
                        <span>Station Pins</span>
                        <span class="toggle-chip">Visible</span>
                    </button>
                    <button type="button" class="station-layer-toggle" data-layer-toggle>
                        <span>Catchment Radius</span>
                        <span class="toggle-chip">Hidden</span>
                    </button>
                    <button type="button" class="station-layer-toggle" data-layer-toggle>
                        <span>Performance Heatmap</span>
                        <span class="toggle-chip">Hidden</span>
                    </button>
                </div>
            </div>
            <div class="station-modal__footer">
                <button type="button" data-close-modal>Done</button>
            </div>
        </div>
    </div>

    <div class="station-modal-backdrop" data-modal="station-share" aria-hidden="true">
        <div class="station-modal" role="dialog" aria-modal="true" aria-labelledby="station-share-title">
            <div class="station-modal__header">
                <h3 id="station-share-title">Share This Map</h3>
                <button class="station-modal__close" type="button" data-close-modal>&times;</button>
            </div>
            <div class="station-modal__body">
                <p class="station-modal__description">Choose how you want to circulate this view with your wider team.</p>
                <div class="station-share-grid">
                    <div class="station-share-card is-active" data-share-option>
                        <i class="ri-links-line"></i>
                        <strong>Copy Link</strong>
                        <span>Generate a shareable dashboard link</span>
                    </div>
                    <div class="station-share-card" data-share-option>
                        <i class="ri-mail-send-line"></i>
                        <strong>Email Snapshot</strong>
                        <span>Send a quick snapshot to stakeholders</span>
                    </div>
                    <div class="station-share-card" data-share-option>
                        <i class="ri-file-download-line"></i>
                        <strong>Export Map</strong>
                        <span>Download a high-resolution map image</span>
                    </div>
                </div>
            </div>
            <div class="station-modal__footer">
                <button type="button" data-close-modal>Close</button>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const stations = @json($stations);
            const mapElement = document.getElementById('station-map');

            if (!mapElement) {
                return;
            }

            const map = L.map(mapElement, {
                zoomControl: false,
                scrollWheelZoom: true,
                attributionControl: true,
            });

            const bounds = [];

            L.control.zoom({ position: 'topright' }).addTo(map);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            }).addTo(map);

            const markers = stations.map((station) => {
                const marker = L.circleMarker([station.lat, station.lng], {
                    radius: 10,
                    color: '#0a3f8c',
                    fillColor: '#0a3f8c',
                    fillOpacity: 0.85,
                    weight: 2,
                });

                marker.bindPopup(createPopupContent(station), {
                    minWidth: 220,
                    autoClose: false,
                    closeButton: true,
                    className: 'station-popup-wrapper',
                });

                marker.on('click', () => {
                    showStationModal(station);
                });

                marker.addTo(map);
                bounds.push([station.lat, station.lng]);

                return { marker, station };
            });

            if (bounds.length) {
                map.fitBounds(bounds, { padding: [40, 40] });
            } else {
                map.setView([7.9465, -1.0232], 6);
            }

            const modalElements = Array.from(document.querySelectorAll('.station-modal-backdrop'));
            const modalMap = modalElements.reduce((accumulator, modalEl) => {
                const name = modalEl.getAttribute('data-modal');
                if (name) {
                    accumulator[name] = modalEl;
                }
                return accumulator;
            }, {});

            document.querySelectorAll('[data-open-modal]').forEach((button) => {
                button.addEventListener('click', () => {
                    const targetModal = button.getAttribute('data-open-modal');
                    openModal(targetModal);
                });
            });

            document.querySelectorAll('[data-close-modal]').forEach((button) => {
                button.addEventListener('click', () => {
                    const modalEl = button.closest('.station-modal-backdrop');
                    closeModal(modalEl);
                });
            });

            modalElements.forEach((modalEl) => {
                modalEl.addEventListener('click', (event) => {
                    if (event.target === modalEl) {
                        closeModal(modalEl);
                    }
                });
            });

            const directoryButtons = document.querySelectorAll('[data-directory-view]');
            directoryButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    const targetIndex = Number(button.getAttribute('data-directory-view'));
                    const entry = markers[targetIndex];

                    if (!entry) {
                        return;
                    }

                    closeModal(modalMap['station-directory']);
                    map.flyTo([entry.station.lat, entry.station.lng], 11, {
                        animate: true,
                        duration: 0.8,
                    });
                    showStationModal(entry.station);
                });
            });

            const layerToggles = document.querySelectorAll('[data-layer-toggle]');
            layerToggles.forEach((toggle) => {
                toggle.addEventListener('click', () => {
                    const isActive = toggle.classList.toggle('is-active');
                    const chip = toggle.querySelector('.toggle-chip');

                    if (chip) {
                        chip.textContent = isActive ? 'Visible' : 'Hidden';
                    }
                });
            });

            const shareOptions = document.querySelectorAll('[data-share-option]');
            shareOptions.forEach((option) => {
                option.addEventListener('click', () => {
                    shareOptions.forEach((opt) => opt.classList.remove('is-active'));
                    option.classList.add('is-active');
                });
            });

            function openModal(name) {
                const modalEl = modalMap[name];
                if (!modalEl) {
                    return;
                }

                modalEl.classList.add('is-visible');
                modalEl.setAttribute('aria-hidden', 'false');
            }

            function closeModal(modalEl) {
                if (!modalEl) {
                    return;
                }

                modalEl.classList.remove('is-visible');
                modalEl.setAttribute('aria-hidden', 'true');
            }

            function createPopupContent(station) {
                const container = document.createElement('div');
                container.className = 'station-popup';

                container.innerHTML = `
                    <h4 class="station-popup__title">${station.name}</h4>
                    <div class="station-popup__meta">
                        <span>${station.code}</span>
                        <span>${station.location}</span>
                    </div>
                    <div class="station-popup__actions">
                        <button class="station-popup__btn is-primary" type="button" data-modal-launch>
                            View Details
                        </button>
                    </div>
                `;

                container.querySelector('[data-modal-launch]').addEventListener('click', () => {
                    showStationModal(station);
                });
                return container;
            }

            function showStationModal(station) {
                const modalBackdrop = modalMap['station-detail'];
                if (!modalBackdrop) {
                    return;
                }

                modalBackdrop.querySelector('[data-modal-field="name"]').textContent = station.name;
                modalBackdrop.querySelector('[data-modal-field="code"]').textContent = station.code;
                modalBackdrop.querySelector('[data-modal-field="manager"]').textContent = station.manager;
                modalBackdrop.querySelector('[data-modal-field="location"]').textContent = station.location;
                modalBackdrop.querySelector('[data-modal-field="address"]').textContent = station.address;
                modalBackdrop.querySelector('[data-modal-field="coordinates"]').textContent = `${station.lat.toFixed(4)}, ${station.lng.toFixed(4)}`;

                const phoneLink = modalBackdrop.querySelector('[data-modal-field="phone-link"]');
                const phoneNormalized = station.phone.replace(/\s+/g, '');
                phoneLink.textContent = station.phone;
                phoneLink.href = `tel:${phoneNormalized}`;

                openModal('station-detail');
            }
        });
    </script>
@endsection
