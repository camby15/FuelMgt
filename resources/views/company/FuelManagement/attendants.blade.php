@extends('layouts.vertical', [
    'page_title' => 'Fuel Attendants',
    'mode' => session('theme_mode', 'light'),
])

@section('css')
    <style>
        :root {
            --att-blue: #0b56c4;
            --att-blue-dark: #053377;
            --att-surface: #ffffff;
            --att-soft: #f4f6fb;
            --att-label: #1a2c4f;
            --att-muted: #6c7a96;
        }

        .attendant-dashboard {
            min-height: calc(100vh - 120px);
            background:
                radial-gradient(circle at 8% 16%, rgba(11, 86, 196, 0.12), transparent 55%),
                radial-gradient(circle at 92% 8%, rgba(5, 51, 119, 0.16), transparent 50%),
                linear-gradient(180deg, #ffffff 0%, #eef3ff 100%);
            padding: 3.2rem 1.6rem 4rem;
            font-family: "Inter", "Segoe UI", sans-serif;
        }

        .attendant-hero {
            width: min(1180px, 95vw);
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.75rem;
            align-items: center;
            background: linear-gradient(135deg, rgba(11, 86, 196, 0.95), rgba(5, 51, 119, 0.9));
            color: #ffffff;
            padding: 2.6rem 2.8rem;
            border-radius: 22px;
            box-shadow: 0 26px 52px rgba(10, 28, 70, 0.24);
        }

        .attendant-hero__meta {
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.24em;
            color: rgba(255, 255, 255, 0.68);
        }

        .attendant-hero h1 {
            margin: 0.75rem 0 0.6rem;
            font-size: clamp(2rem, 2.6vw, 2.6rem);
            font-weight: 700;
            letter-spacing: -0.015em;
        }

        .attendant-primary-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            border: none;
            border-radius: 12px;
            padding: 0.95rem 1.9rem;
            font-size: 0.92rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 700;
            background: #ffffff;
            color: var(--att-blue-dark);
            cursor: pointer;
            box-shadow: 0 18px 36px rgba(10, 32, 70, 0.22);
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .attendant-primary-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 24px 46px rgba(10, 32, 70, 0.28);
        }

        .attendant-insight-grid {
            width: min(1180px, 95vw);
            margin: 2rem auto 0;
            display: grid;
            gap: 1.4rem;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }

        .attendant-insight-card {
            background: var(--att-surface);
            border-radius: 16px;
            padding: 1.6rem 1.8rem;
            border: 1px solid rgba(17, 24, 39, 0.05);
            box-shadow: 0 18px 28px rgba(15, 23, 42, 0.08);
        }

        .attendant-insight-card span {
            display: block;
        }

        .attendant-insight-card__label {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            color: var(--att-muted);
            margin-bottom: 0.5rem;
        }

        .attendant-insight-card__value {
            font-size: 1.72rem;
            font-weight: 700;
            color: #0b1f3d;
        }

        .attendant-table-card {
            width: min(1180px, 95vw);
            margin: 2.4rem auto 0;
            background: var(--att-surface);
            border-radius: 20px;
            border: 1px solid rgba(17, 24, 39, 0.06);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.1);
            overflow: hidden;
        }

        .attendant-table-card__header {
            padding: 1.6rem 2rem 1.2rem;
            border-bottom: 1px solid rgba(17, 24, 39, 0.06);
        }

        .attendant-table-card__header h2 {
            font-size: 1.28rem;
            margin-bottom: 0.3rem;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .attendant-table-card__header span {
            font-size: 0.82rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--att-muted);
        }

        .attendant-table-card__header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .attendant-table-card__header-actions .btn,
        .attendant-table-card__header-actions .attendant-action-btn {
            flex-shrink: 0;
        }

        .attendant-table-card__meta {
            font-size: 0.78rem;
            color: var(--att-muted);
        }

        .attendant-search {
            position: relative;
            flex: 1 1 auto;
            max-width: 240px;
        }

        .attendant-search input {
            width: 100%;
            padding: 0.55rem 0.85rem 0.55rem 2.4rem;
            border-radius: 999px;
            border: 1px solid rgba(17, 24, 39, 0.08);
            background: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
        }

        .attendant-search input:focus {
            outline: none;
            border-color: var(--att-blue);
            box-shadow: 0 0 0 3px rgba(11, 86, 196, 0.18);
        }

        .attendant-search i {
            position: absolute;
            left: 0.95rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1rem;
            color: var(--att-muted);
        }

        .attendant-quick-filter {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 0.9rem;
            border-radius: 999px;
            border: 1px solid rgba(11, 86, 196, 0.16);
            background: rgba(11, 86, 196, 0.06);
            color: var(--att-blue-dark);
            font-size: 0.82rem;
            font-weight: 600;
        }

        .attendant-quick-filter i {
            font-size: 1rem;
        }

        .visually-hidden {
            position: absolute !important;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        .attendant-table {
            margin-bottom: 0;
            min-width: 100%;
        }

        .attendant-table thead th {
            font-size: 0.68rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--att-muted);
            border-bottom-width: 1px;
            padding: 0.85rem 1.25rem;
            white-space: nowrap;
        }

        .attendant-table tbody td {
            padding: 1.1rem 1.25rem;
            vertical-align: middle;
            border-color: rgba(17, 24, 39, 0.04) !important;
        }

        .attendant-table tbody tr {
            transition: background-color 0.18s ease, transform 0.18s ease;
        }

        .attendant-table tbody tr:hover {
            background: rgba(11, 86, 196, 0.05);
        }

        .attendant-table tbody tr:hover td:first-child .attendant-avatar {
            transform: scale(1.04);
        }

        .attendant-avatar {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(11, 86, 196, 0.18), rgba(5, 51, 119, 0.24));
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--att-blue-dark);
            letter-spacing: 0.04em;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .attendant-avatar--image {
            background: none;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 12px 26px rgba(11, 86, 196, 0.18);
        }

        .attendant-avatar--image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .attendant-actions .btn {
            padding-left: 0.2rem;
            padding-right: 0.2rem;
        }

        .attendant-action-btn {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--att-blue-dark);
            background: rgba(11, 86, 196, 0.08);
            transition: transform 0.18s ease, background 0.18s ease, box-shadow 0.18s ease;
        }

        .attendant-action-btn:hover {
            transform: translateY(-1px);
            background: rgba(11, 86, 196, 0.16);
            box-shadow: 0 10px 18px rgba(11, 86, 196, 0.18);
        }

        .attendant-action-btn--view {
            color: var(--att-blue-dark);
        }

        .attendant-action-btn--delete {
            color: #dc3545;
            background: rgba(220, 53, 69, 0.12);
        }

        .attendant-action-btn--delete:hover {
            background: rgba(220, 53, 69, 0.18);
            box-shadow: 0 10px 18px rgba(220, 53, 69, 0.18);
        }

        .attendant-status {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.78rem;
            font-weight: 600;
            color: #198754;
            background: rgba(25, 135, 84, 0.12);
            padding: 0.35rem 0.7rem;
            border-radius: 999px;
        }

        .attendant-status--inactive {
            color: #dc3545;
            background: rgba(220, 53, 69, 0.12);
        }

        .attendant-status--leave {
            color: #ffc107;
            background: rgba(255, 193, 7, 0.16);
        }

        .attendant-status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: currentColor;
        }

        .attendant-quick-filter.is-active {
            background: linear-gradient(135deg, var(--att-blue), var(--att-blue-dark));
            color: #ffffff;
            border-color: transparent;
            box-shadow: 0 12px 24px rgba(11, 86, 196, 0.24);
        }

        .attendant-site-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            background: rgba(11, 86, 196, 0.08);
            color: var(--att-blue-dark);
            font-size: 0.82rem;
            font-weight: 600;
        }

        .attendant-site-pill i {
            font-size: 1rem;
        }

        .attendant-shift-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.3rem 0.55rem;
            border-radius: 999px;
            background: rgba(5, 51, 119, 0.08);
            color: var(--att-blue-dark);
            font-size: 0.75rem;
            font-weight: 600;
        }

        .attendant-shift-tag i {
            font-size: 0.9rem;
        }

        .attendant-empty {
            text-align: center;
            padding: 2.5rem 1.5rem;
            color: var(--att-muted);
            font-size: 0.95rem;
        }

        .attendant-sample-banner {
            background: rgba(11, 86, 196, 0.08);
            border: 1px dashed rgba(11, 86, 196, 0.3);
            border-radius: 12px;
            padding: 1rem 1.4rem;
            margin: 1rem 2rem 0;
            font-size: 0.85rem;
            color: var(--att-blue-dark);
        }

        .attendant-detail-header {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            padding-bottom: 1.2rem;
            border-bottom: 1px solid rgba(17, 24, 39, 0.08);
        }

        .attendant-detail-photo {
            width: 72px;
            height: 72px;
            border-radius: 16px;
            background: rgba(11, 86, 196, 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            letter-spacing: 0.06em;
            color: var(--att-blue-dark);
            background-size: cover;
            background-position: center;
        }

        .attendant-detail-photo.is-image {
            background: none;
            padding: 0;
        }

        .attendant-detail-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 16px;
        }

        .attendant-detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.4rem;
            padding-top: 1.4rem;
        }

        .attendant-detail-card {
            background: rgba(241, 245, 255, 0.45);
            border-radius: 14px;
            padding: 1rem 1.2rem;
            box-shadow: inset 0 0 0 1px rgba(11, 86, 196, 0.06);
        }

        .attendant-card-stack {
            display: none;
        }

        .attendant-detail-card h6 {
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.16em;
            color: var(--att-muted);
            margin-bottom: 0.6rem;
        }

        .attendant-detail-card dl {
            margin: 0;
        }

        .attendant-detail-card dt {
            font-size: 0.78rem;
            color: var(--att-muted);
            margin-bottom: 0.18rem;
        }

        .attendant-detail-card dd {
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: #0b1f3d;
        }

        .attendant-detail-card dd:last-child {
            margin-bottom: 0;
        }

        .attendant-modal .modal-content {
            border-radius: 18px;
            border: none;
            overflow: hidden;
            box-shadow: 0 34px 68px rgba(20, 32, 60, 0.28);
        }

        .attendant-modal__header {
            background: linear-gradient(135deg, var(--att-blue), var(--att-blue-dark));
            color: #ffffff;
            padding: 1.25rem 2rem;
        }

        .attendant-modal__body {
            background: linear-gradient(180deg, var(--att-surface) 0%, var(--att-soft) 100%);
            padding: 2.2rem 2.6rem 2.6rem;
        }

        .attendant-form-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 1rem;
        }

        .attendant-form-table td {
            padding: 0.35rem 0.6rem;
            vertical-align: middle;
        }

        .attendant-label {
            width: 17%;
            font-weight: 600;
            color: var(--att-label);
            font-size: 0.94rem;
            white-space: nowrap;
        }

        .attendant-label span {
            color: #d43737;
        }

        .attendant-input-wrapper {
            width: 33%;
        }

        .attendant-input,
        .attendant-select,
        .attendant-textarea {
            width: 100%;
            border: 1px solid rgba(17, 24, 39, 0.08);
            border-radius: 8px;
            padding: 0.65rem 0.85rem;
            font-size: 0.92rem;
            color: #0b1f3d;
            background: #ffffff;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .attendant-input:focus,
        .attendant-select:focus,
        .attendant-textarea:focus {
            outline: none;
            border-color: var(--att-blue);
            box-shadow: 0 0 0 2px rgba(11, 86, 196, 0.15);
        }

        .attendant-select {
            appearance: none;
            background-image: url('data:image/svg+xml,%3csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%230b56c4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"%3e%3cpolyline points="6 9 12 15 18 9"/%3e%3c/svg%3e');
            background-repeat: no-repeat;
            background-position: right 0.85rem center;
            background-size: 16px;
        }

        .attendant-textarea {
            min-height: 70px;
            resize: vertical;
        }

        .attendant-sections {
            display: flex;
            flex-wrap: wrap;
            gap: 1.4rem;
            margin-top: 2rem;
        }

        .attendant-section {
            flex: 1 1 260px;
            background: var(--att-surface);
            border: 1px solid rgba(17, 24, 39, 0.08);
            border-radius: 12px;
            padding: 1.4rem 1.6rem;
            box-shadow: 0 16px 26px rgba(15, 23, 42, 0.08);
        }

        .attendant-section__title {
            font-size: 0.95rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--att-blue);
            margin-bottom: 1rem;
        }

        .attendant-upload-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .attendant-upload-thumbnail {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            cursor: pointer;
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .attendant-thumbnail--filled {
            border-style: solid;
            border-color: rgba(11, 86, 196, 0.4);
            background: #ffffff;
        }

        .attendant-thumbnail--filled i,
        .attendant-thumbnail--filled span {
            display: none !important;
        }

        .attendant-upload-thumbnail:hover {
            border-color: var(--att-blue);
            background: #f0f4f9;
        }

        .attendant-upload-thumbnail i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: #adb5bd;
        }

        .attendant-upload-thumbnail span {
            font-size: 0.75rem;
            text-align: center;
            max-width: 90%;
        }

        .attendant-upload-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .attendant-form-actions {
            margin-top: 2.4rem;
            display: flex;
            gap: 1rem;
        }

        .attendant-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 140px;
            padding: 0.8rem 1.75rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.9rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            border: none;
            cursor: pointer;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .attendant-btn--primary {
            background: linear-gradient(135deg, var(--att-blue), var(--att-blue-dark));
            color: #ffffff;
            box-shadow: 0 18px 32px rgba(11, 86, 196, 0.26);
        }

        .attendant-btn--secondary {
            background: linear-gradient(135deg, #10316b, #0d1f4b);
            color: #ffffff;
            box-shadow: 0 18px 32px rgba(16, 49, 107, 0.2);
        }

        .attendant-btn:hover {
            transform: translateY(-2px);
        }

        @media (max-width: 992px) {
            .attendant-dashboard {
                padding: 2.6rem 1.2rem 3.2rem;
            }

            .attendant-hero {
                padding: 2.2rem;
            }

            .attendant-modal__body {
                padding: 2rem 1.6rem 2.3rem;
            }

            .attendant-form-table tr {
                display: grid;
                grid-template-columns: 1fr;
                row-gap: 0.3rem;
                margin-bottom: 1rem;
            }

            .attendant-label,
            .attendant-input-wrapper {
                width: 100%;
            }
        }

        @media (max-width: 992px) {
            .attendant-hero {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 1.4rem;
            }

            .attendant-hero .attendant-primary-btn {
                margin: 0 auto;
            }

            .attendant-insight-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .attendant-table-card {
                border-radius: 16px;
            }

            .attendant-table thead {
                display: none;
            }

            .attendant-table tbody {
                display: block;
            }

            .attendant-table tbody tr {
                display: block;
                margin: 0 1.2rem 1.3rem;
                padding: 1.2rem 1.1rem;
                border-radius: 16px;
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(236, 242, 255, 0.95));
                box-shadow: 0 16px 30px rgba(15, 23, 42, 0.12);
            }

            .attendant-table tbody td {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 1.4rem;
                padding: 0.55rem 0;
                border: none !important;
            }

            .attendant-table tbody td::before {
                content: attr(data-label);
                font-size: 0.72rem;
                text-transform: uppercase;
                letter-spacing: 0.16em;
                color: var(--att-muted);
                flex: 0 0 45%;
            }

            .attendant-table tbody td:first-child {
                display: block;
                margin-bottom: 0.8rem;
            }

            .attendant-table tbody td:first-child::before {
                display: none;
            }

            .attendant-actions {
                justify-content: flex-end;
            }

            .attendant-card-stack {
                display: inline-flex;
                gap: 0.35rem;
                margin-left: 0.25rem;
            }
        }

        @media (max-width: 576px) {
            .attendant-dashboard {
                padding: 2.4rem 1rem 3rem;
            }

            .attendant-hero {
                padding: 1.8rem;
            }

            .attendant-table-card__header {
                padding: 1.4rem 1.4rem 1.1rem;
            }

            .attendant-table tbody tr {
                margin: 0 0.8rem 1.3rem;
            }

            .attendant-action-btn {
                width: 36px;
                height: 36px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="attendant-dashboard">
        <div class="attendant-hero">
            <div>
                <span class="attendant-hero__meta">Fuel Operations</span>
                <h1>Fuel Attendants</h1>
                <p class="mb-0 small text-white-75">
                    Maintain frontline personnel records, opening balances, and emergency contacts in a single structured capture flow.
                </p>
            </div>
            <div class="d-flex flex-column gap-3">
                <button type="button" class="attendant-primary-btn" data-bs-toggle="modal" data-bs-target="#addAttendantModal">
                    <i class="ri-add-line"></i>
                    Add Fuel Attendant
                </button>
                <span class="small text-white-50">Capture new attendants as soon as they’re onboarded to keep pump accountability current.</span>
            </div>
        </div>

        <div class="attendant-insight-grid">
            <div class="attendant-insight-card">
                <span class="attendant-insight-card__label">Active attendants</span>
                <span class="attendant-insight-card__value">{{ $activeAttendantsCount ?? 0 }}</span>
                <span class="attendant-insight-card__meta">Across {{ $stations->count() }} station{{ $stations->count() !== 1 ? 's' : '' }}.</span>
            </div>
            <div class="attendant-insight-card">
                <span class="attendant-insight-card__label">Average onboarding time</span>
                <span class="attendant-insight-card__value">22 mins</span>
                <span class="attendant-insight-card__meta">Complete profiles faster with pre-filled station defaults.</span>
            </div>
            <div class="attendant-insight-card">
                <span class="attendant-insight-card__label">Compliance status</span>
                <span class="attendant-insight-card__value text-success">{{ $compliancePercentage ?? 0 }}%</span>
                <span class="attendant-insight-card__meta">Active attendants compliance rate.</span>
            </div>
        </div>

        @php
            $providedAttendants = collect($attendants ?? []);
            $usingSampleData = $providedAttendants->isEmpty();

            $sampleAttendants = collect([
                [
                    'id' => 'sample-1',
                    'staff_id' => 'FA-001',
                    'first_name' => 'Patricia',
                    'other_names' => 'Mensima',
                    'gender' => 'female',
                    'date_of_birth' => '1991-05-14',
                    'address' => 'House 14, Navrongo Estate, Upper East',
                    'phone_number_1' => '+233 24 111 2233',
                    'phone_number_2' => '+233 50 855 8899',
                    'contact_name' => 'Samuel Mensima',
                    'contact_relationship' => 'Spouse',
                    'contact_phone' => '+233 24 667 8899',
                    'contact_address' => 'Navrongo Estate, Upper East Region',
                    'created_at' => now()->subDays(8),
                    'status' => 'active',
                    'shift' => 'Morning shift',
                    'fuel_station_id' => 1,
                    'station_name' => 'Navrongo Main',
                    'station_code' => 'NM001',
                    'profile_photo_url' => 'https://i.pravatar.cc/160?img=47',
                ],
                [
                    'id' => 'sample-2',
                    'staff_id' => 'FA-017',
                    'first_name' => 'Issah',
                    'other_names' => 'Yakubu',
                    'gender' => 'male',
                    'date_of_birth' => '1987-11-02',
                    'address' => 'Wapuli Township, North East Region',
                    'phone_number_1' => '+233 26 872 1156',
                    'phone_number_2' => null,
                    'contact_name' => 'Amina Yakubu',
                    'contact_relationship' => 'Sibling',
                    'contact_phone' => '+233 26 441 2389',
                    'contact_address' => 'Tamale, Northern Region',
                    'created_at' => now()->subDays(21),
                    'status' => 'active',
                    'shift' => 'Morning',
                    'fuel_station_id' => 2,
                    'station_name' => 'Wapuli',
                    'station_code' => 'WP001',
                ],
            ])->map(fn ($row) => (object) $row);

            $attendantCollection = $usingSampleData ? $sampleAttendants : $providedAttendants;
            $attendantCount = $attendantCollection->count();
        @endphp

        <div class="attendant-table-card">
            <div class="attendant-table-card__header">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
                    <div>
                        <span>Attendants roster</span>
                        <h2 class="mb-1">Registered attendants ({{ $attendantCount }})</h2>
                        <p class="attendant-table-card__meta mb-0">Get a panoramic view of frontline coverage across every
                            station and shift.</p>
                    </div>
                    <div class="attendant-table-card__header-actions flex-wrap">
                        <label class="attendant-search mb-0">
                            <i class="ri-search-2-line"></i>
                            <span class="visually-hidden">Search attendants</span>
                            <input type="search" name="attendant_search" placeholder="Search roster">
                        </label>
                        <span class="attendant-quick-filter is-active">
                            <i class="ri-shield-check-line"></i>
                            Active on duty
                        </span>
                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addAttendantModal">
                            <i class="ri-user-add-line me-1"></i>
                            New Attendant
                        </button>
                    </div>
                </div>
            </div>

            @if ($usingSampleData)
                <div class="attendant-sample-banner">
                    <i class="ri-information-line me-2"></i>
                    Sample roster displayed for preview. Connect the attendants datasource to replace with live records.
                </div>
            @endif

            <div class="table-responsive">
                <table class="table align-middle attendant-table">
                    <thead>
                        <tr>
                            <th scope="col">Attendant</th>
                            <th scope="col">Site</th>
                            <th scope="col">Status</th>
                            <th scope="col">Shift</th>
                            <th scope="col">Primary contact</th>
                            <th scope="col">Created</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendantCollection as $attendant)
                            @php
                                $firstName = $attendant->first_name ?? '';
                                $otherNames = $attendant->other_names ?? '';
                                $fullName = trim(($firstName ?: '') . ' ' . ($otherNames ?: ''));
                                $initials = mb_strtoupper(trim(mb_substr($firstName, 0, 1) . mb_substr($otherNames, 0, 1)));
                                $staffId = $attendant->staff_id ?? '—';
                                $site = $attendant->station_name ?? '—';
                                $siteCode = $attendant->station_code ?? '';
                                $fuelStationId = $attendant->fuel_station_id;
                                $phonePrimary = $attendant->phone_number_1 ?? $attendant->phone ?? '—';
                                $createdAt = $attendant->created_at ?? null;
                                $createdAtDisplay = $createdAt instanceof \Carbon\Carbon ? $createdAt->format('d M Y') : ($createdAt ?: '—');
                                $dateOfBirth = $attendant->date_of_birth ?? null;
                                if ($dateOfBirth instanceof \Carbon\Carbon) {
                                    $dateOfBirthRaw = $dateOfBirth->format('Y-m-d');
                                    $dateOfBirthDisplay = $dateOfBirth->format('d M Y');
                                } else {
                                    $dateOfBirthRaw = is_string($dateOfBirth) ? $dateOfBirth : null;
                                    $dateOfBirthDisplay = $dateOfBirthRaw ?: '—';
                                }
                                $statusRaw = strtolower((string) ($attendant->status ?? 'active'));
                                $statusClass = match ($statusRaw) {
                                    'inactive', 'off_duty' => 'attendant-status--inactive',
                                    'on_leave', 'leave' => 'attendant-status--leave',
                                    default => '',
                                };
                                $statusLabel = match ($statusRaw) {
                                    'inactive', 'off_duty' => 'Inactive',
                                    'on_leave', 'leave' => 'On leave',
                                    'active', 'on_duty' => 'On duty',
                                    default => ucwords(str_replace(['_', '-'], ' ', $statusRaw)),
                                };
                                $shift = $attendant->shift ?? '—';
                                $profilePhotoUrl = $attendant->profile_photo_url ?? $attendant->profile_photo ?? null;
                                $detailPayload = [
                                    'id' => (string) ($attendant->id ?? ''),
                                    'fuel_station_id' => (string) $fuelStationId,
                                    'staff_id' => (string) $staffId,
                                    'site' => (string) $site,
                                    'site_code' => (string) $siteCode,
                                    'full_name' => (string) ($fullName !== '' ? $fullName : $staffId),
                                    'initials' => (string) ($initials !== '' ? $initials : 'NA'),
                                    'gender' => (string) ($attendant->gender ?? '—'),
                                    'date_of_birth' => (string) $dateOfBirthRaw,
                                    'date_of_birth_display' => (string) $dateOfBirthDisplay,
                                    'address' => (string) ($attendant->address ?? '—'),
                                    'phone_primary' => (string) $phonePrimary,
                                    'phone_secondary' => (string) ($attendant->phone_number_2 ?? '—'),
                                    'contact_name' => (string) ($attendant->contact_name ?? '—'),
                                    'contact_relationship' => (string) ($attendant->contact_relationship ?? '—'),
                                    'contact_phone' => (string) ($attendant->contact_phone ?? '—'),
                                    'contact_address' => (string) ($attendant->contact_address ?? '—'),
                                    'created_at_display' => (string) $createdAtDisplay,
                                    'profile_photo_url' => (string) $profilePhotoUrl,
                                    'status' => (string) $statusLabel,
                                    'status_state' => (string) $statusRaw,
                                    'shift' => (string) $shift,
                                    'edit_url' => "/company/fuel-management/attendants/$attendant->id",
                                ];
                                $deleteUrl = "/company/fuel-management/attendants/$attendant->id";
                                $payloadJson = e(json_encode($detailPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                                $displayName = $fullName !== '' ? $fullName : $staffId;
                                $avatarInitials = $initials !== '' ? $initials : 'NA';
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if ($profilePhotoUrl)
                                            <span class="attendant-avatar attendant-avatar--image">
                                                <img src="{{ $profilePhotoUrl }}" alt="{{ $displayName }}">
                                            </span>
                                        @else
                                            <span class="attendant-avatar">{{ $avatarInitials }}</span>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $displayName }}</div>
                                            <small class="text-muted">{{ $staffId }} • {{ ucfirst($attendant->gender ?? '—') }}</small>
                                            <div class="attendant-card-stack">
                                                <span class="attendant-site-pill"><i class="ri-map-pin-2-line"></i>{{ $site }}</span>
                                                <span class="attendant-shift-tag"><i class="ri-timer-line"></i>{{ $shift }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Site">
                                    <span class="attendant-site-pill"><i class="ri-map-pin-2-line"></i>{{ $site }}</span>
                                </td>
                                <td data-label="Status">
                                    <span class="attendant-status {{ $statusClass }}">
                                        <span class="attendant-status-dot"></span>
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td data-label="Shift">
                                    <span class="attendant-shift-tag"><i class="ri-timer-line"></i>{{ $shift }}</span>
                                </td>
                                <td data-label="Primary contact">{{ $phonePrimary }}</td>
                                <td data-label="Created">{{ $createdAtDisplay }}</td>
                                <td class="text-end attendant-actions" data-label="Actions">
                                    <div class="btn-group" role="group" aria-label="Attendant actions">
                                        <button type="button" class="attendant-action-btn attendant-action-btn--view attendant-view-btn"
                                            data-attendant-details="{{ $payloadJson }}" aria-label="View attendant profile">
                                            <i class="ri-eye-line"></i>
                                            <span class="visually-hidden">View</span>
                                        </button>
                                        <button type="button"
                                            class="attendant-action-btn attendant-edit-btn"
                                            data-edit-attendant="{{ $payloadJson }}" 
                                            data-edit-url="/company/fuel-management/attendants/{{ $attendant->id }}"
                                            aria-label="Edit attendant">
                                            <i class="ri-edit-line"></i>
                                            <span class="visually-hidden">Edit</span>
                                        </button>
                                        <button type="button"
                                            class="attendant-action-btn attendant-action-btn--delete attendant-delete-btn"
                                            data-delete-url="{{ $deleteUrl }}"
                                            data-attendant-name="{{ $displayName }}" aria-label="Delete attendant">
                                            <i class="ri-delete-bin-6-line"></i>
                                            <span class="visually-hidden">Delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="attendant-empty">
                                        <i class="ri-team-line d-block fs-3 mb-2"></i>
                                        No attendants captured yet. Use the “Add Fuel Attendant” button to create the first
                                        record.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade attendant-modal" id="addAttendantModal" tabindex="-1" aria-labelledby="addAttendantModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="attendant-modal__header d-flex align-items-center justify-content-between">
                    <h5 class="modal-title" id="addAttendantModalLabel">Add Fuel Attendant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="attendant-modal__body">
                    <form action="{{ route('company.fuel.attendants.store') }}" method="POST" enctype="multipart/form-data" id="fuelAttendantForm">
                        @csrf

                        <table class="attendant-form-table">
                            <tbody>
                                <tr>
                                    <td class="attendant-label">Site:<span>*</span></td>
                                    <td class="attendant-input-wrapper">
                                        <select class="attendant-select" name="fuel_station_id" required>
                                            <option value="">Select a site</option>
                                            @foreach(($stations ?? collect()) as $station)
                                                <option value="{{ $station->id }}">{{ $station->name }} ({{ $station->code }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="attendant-label">Staff ID:<span>*</span></td>
                                    <td class="attendant-input-wrapper">
                                        <input type="text" name="staff_id" class="attendant-input" placeholder="e.g. PA0027" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="attendant-label">First Name:<span>*</span></td>
                                    <td class="attendant-input-wrapper">
                                        <input type="text" name="first_name" class="attendant-input" required>
                                    </td>
                                    <td class="attendant-label">Other Names:<span>*</span></td>
                                    <td class="attendant-input-wrapper">
                                        <input type="text" name="other_names" class="attendant-input" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="attendant-label">Gender:<span>*</span></td>
                                    <td class="attendant-input-wrapper">
                                        <select class="attendant-select" name="gender" required>
                                            <option value="">Select gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </td>
                                    <td class="attendant-label">Date of Birth:</td>
                                    <td class="attendant-input-wrapper">
                                        <input type="date" name="date_of_birth" class="attendant-input">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="attendant-label">Address:<span>*</span></td>
                                    <td class="attendant-input-wrapper" colspan="3">
                                        <textarea name="address" class="attendant-textarea" required></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="attendant-label">Phone Number1:<span>*</span></td>
                                    <td class="attendant-input-wrapper">
                                        <input type="tel" name="phone_number_1" class="attendant-input" required>
                                    </td>
                                    <td class="attendant-label">Phone Number2:</td>
                                    <td class="attendant-input-wrapper">
                                        <input type="tel" name="phone_number_2" class="attendant-input">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="attendant-label">Contact Name:</td>
                                    <td class="attendant-input-wrapper">
                                        <input type="text" name="contact_name" class="attendant-input">
                                    </td>
                                    <td class="attendant-label">Contact Relationship:</td>
                                    <td class="attendant-input-wrapper">
                                        <input type="text" name="contact_relationship" class="attendant-input">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="attendant-label">Contact Address:</td>
                                    <td class="attendant-input-wrapper">
                                        <textarea name="contact_address" class="attendant-textarea"></textarea>
                                    </td>
                                    <td class="attendant-label">Phone Number:</td>
                                    <td class="attendant-input-wrapper">
                                        <input type="tel" name="contact_phone" class="attendant-input">
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="attendant-sections">
                            <div class="attendant-section">
                                <div class="attendant-section__title">Profile Picture</div>
                                <div class="attendant-upload-wrapper">
                                    <div class="attendant-upload-thumbnail" id="attendantThumbnail">
                                        <i class="ri-user-line"></i>
                                        <span>Upload Image</span>
                                    </div>
                                    <input type="file" name="profile_photo" id="profilePhotoInput" accept="image/*" class="d-none">
                                    <small class="d-block mt-2 text-muted">Max 2MB • JPG, PNG</small>
                                </div>
                            </div>
                        </div>

                        <div class="attendant-form-actions">
                            <button type="submit" class="attendant-btn attendant-btn--primary">Submit</button>
                            <button type="reset" class="attendant-btn attendant-btn--secondary">Refresh</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade attendant-modal" id="editAttendantModal" tabindex="-1" aria-labelledby="editAttendantModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="attendant-modal__header d-flex align-items-center justify-content-between">
                    <h5 class="modal-title" id="editAttendantModalLabel">Edit Fuel Attendant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="attendant-modal__body">
                    <form action="{{ route('company.fuel.attendants.update', ':id') }}" method="POST" enctype="multipart/form-data" id="editAttendantForm">
                        @csrf
                        @method('PUT')

                        <table class="attendant-form-table">
                            <tbody>
                                <tr>
                                    <td class="attendant-label">Site:<span>*</span></td>
                                    <td class="attendant-input-wrapper">
                                        <select class="attendant-select" name="fuel_station_id" id="edit_fuel_station_id" required>
                                            <option value="">Select a site</option>
                                            @foreach($stations as $station)
                                                <option value="{{ $station->id }}">{{ $station->name }} ({{ $station->code }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="attendant-label">Staff ID:<span>*</span></td>
                                    <td class="attendant-input-wrapper">
                                        <input type="text" name="staff_id" id="edit_staff_id" class="attendant-input" placeholder="e.g. PA0027" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="attendant-label">First Name:<span>*</span></td>
                                    <td class="attendant-input-wrapper">
                                        <input type="text" name="first_name" id="edit_first_name" class="attendant-input" required>
                                    </td>
                                    <td class="attendant-label">Other Names:<span>*</span></td>
                                    <td class="attendant-input-wrapper">
                                        <input type="text" name="other_names" id="edit_other_names" class="attendant-input" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="attendant-label">Gender:<span>*</span></td>
                                    <td class="attendant-input-wrapper">
                                        <select class="attendant-select" name="gender" id="edit_gender" required>
                                            <option value="">Select gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </td>
                                    <td class="attendant-label">Date of Birth:</td>
                                    <td class="attendant-input-wrapper">
                                        <input type="date" name="date_of_birth" id="edit_date_of_birth" class="attendant-input">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="attendant-label">Address:<span>*</span></td>
                                    <td class="attendant-input-wrapper" colspan="3">
                                        <textarea name="address" id="edit_address" class="attendant-textarea" required></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="attendant-label">Phone Number1:<span>*</span></td>
                                    <td class="attendant-input-wrapper">
                                        <input type="tel" name="phone_number_1" id="edit_phone_number_1" class="attendant-input" required>
                                    </td>
                                    <td class="attendant-label">Phone Number2:</td>
                                    <td class="attendant-input-wrapper">
                                        <input type="tel" name="phone_number_2" id="edit_phone_number_2" class="attendant-input">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="attendant-label">Contact Name:</td>
                                    <td class="attendant-input-wrapper">
                                        <input type="text" name="contact_name" id="edit_contact_name" class="attendant-input">
                                    </td>
                                    <td class="attendant-label">Contact Relationship:</td>
                                    <td class="attendant-input-wrapper">
                                        <input type="text" name="contact_relationship" id="edit_contact_relationship" class="attendant-input">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="attendant-label">Contact Address:</td>
                                    <td class="attendant-input-wrapper">
                                        <textarea name="contact_address" id="edit_contact_address" class="attendant-textarea"></textarea>
                                    </td>
                                    <td class="attendant-label">Phone Number:</td>
                                    <td class="attendant-input-wrapper">
                                        <input type="tel" name="contact_phone" id="edit_contact_phone" class="attendant-input">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="attendant-label">Status:</td>
                                    <td class="attendant-input-wrapper">
                                        <select class="attendant-select" name="status" id="edit_status">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                            <option value="on_leave">On Leave</option>
                                        </select>
                                    </td>
                                    <td class="attendant-label">Shift:</td>
                                    <td class="attendant-input-wrapper">
                                        <input type="text" name="shift" id="edit_shift" class="attendant-input" placeholder="e.g. Morning, Evening">
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="attendant-sections">
                            <div class="attendant-section">
                                <div class="attendant-section__title">Profile Picture (Leave empty to keep current)</div>
                                <div class="attendant-upload-wrapper">
                                    <div class="attendant-upload-thumbnail" id="editAttendantThumbnail">
                                        <i class="ri-user-line"></i>
                                        <span>Upload New Image</span>
                                    </div>
                                    <input type="file" name="profile_photo" id="editProfilePhotoInput" accept="image/*" class="d-none">
                                    <small class="d-block mt-2 text-muted">Max 2MB • JPG, PNG • Leave empty to keep current photo</small>
                                </div>
                            </div>
                        </div>

                        <div class="attendant-form-actions">
                            <button type="submit" class="attendant-btn attendant-btn--primary">Update Attendant</button>
                            <button type="reset" class="attendant-btn attendant-btn--secondary">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="attendantDetailModal" tabindex="-1" aria-labelledby="attendantDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attendantDetailModalLabel">Attendant profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="attendant-detail-header">
                        <div class="attendant-detail-photo" id="attendantDetailPhoto">NA</div>
                        <div>
                            <h4 class="mb-1" id="attendantDetailName">—</h4>
                            <div class="text-muted small">Staff ID: <span id="attendantDetailStaffId">—</span></div>
                            <div class="text-muted small">Created: <span id="attendantDetailCreatedAt">—</span></div>
                        </div>
                    </div>

                    <div class="attendant-detail-grid">
                        <div class="attendant-detail-card">
                            <h6>Core details</h6>
                            <dl>
                                <dt>Site</dt>
                                <dd id="attendantDetailSite">—</dd>
                                <dt>Gender</dt>
                                <dd id="attendantDetailGender">—</dd>
                                <dt>Date of birth</dt>
                                <dd id="attendantDetailDob">—</dd>
                                <dt>Status</dt>
                                <dd id="attendantDetailStatus">—</dd>
                                <dt>Current shift</dt>
                                <dd id="attendantDetailShift">—</dd>
                                <dt>Address</dt>
                                <dd id="attendantDetailAddress">—</dd>
                            </dl>
                        </div>
                        <div class="attendant-detail-card">
                            <h6>Contacts</h6>
                            <dl>
                                <dt>Primary phone</dt>
                                <dd id="attendantDetailPhonePrimary">—</dd>
                                <dt>Secondary phone</dt>
                                <dd id="attendantDetailPhoneSecondary">—</dd>
                                <dt>Emergency contact</dt>
                                <dd>
                                    <div id="attendantDetailContactName">—</div>
                                    <small class="text-muted" id="attendantDetailContactRelationship">—</small>
                                </dd>
                                <dt>Emergency contact phone</dt>
                                <dd id="attendantDetailContactPhone">—</dd>
                            </dl>
                        </div>
                        <div class="attendant-detail-card">
                            <h6>Emergency details</h6>
                            <dl>
                                <dt>Emergency contact address</dt>
                                <dd id="attendantDetailContactAddress">—</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteAttendantModal" tabindex="-1" aria-labelledby="deleteAttendantModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title" id="deleteAttendantModalLabel">Delete attendant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to delete <strong id="deleteAttendantName">this attendant</strong>? This
                        action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('company.fuel.attendants.destroy', ['attendant' => ':id']) }}" id="deleteAttendantForm" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const attendantModal = document.getElementById('addAttendantModal');
            const attendantForm = document.getElementById('fuelAttendantForm');
            const thumbnail = document.getElementById('attendantThumbnail');
            const fileInput = document.getElementById('profilePhotoInput');
            const defaultThumbnailMarkup = thumbnail ? thumbnail.innerHTML : '';

            // Edit modal elements
            const editModal = document.getElementById('editAttendantModal');
            const editForm = document.getElementById('editAttendantForm');
            const editThumbnail = document.getElementById('editAttendantThumbnail');
            const editFileInput = document.getElementById('editProfilePhotoInput');
            const editDefaultThumbnailMarkup = editThumbnail ? editThumbnail.innerHTML : '';

            const resetThumbnail = () => {
                if (!thumbnail) return;
                thumbnail.innerHTML = defaultThumbnailMarkup;
                thumbnail.classList.remove('attendant-thumbnail--filled');
            };

            const resetEditThumbnail = () => {
                if (!editThumbnail) return;
                editThumbnail.innerHTML = editDefaultThumbnailMarkup;
                editThumbnail.classList.remove('attendant-thumbnail--filled');
            };

            if (thumbnail && fileInput) {
                thumbnail.addEventListener('click', () => fileInput.click());

                fileInput.addEventListener('change', event => {
                    const [file] = event.target.files || [];
                    if (!file) {
                        resetThumbnail();
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = e => {
                        thumbnail.innerHTML = '';
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        thumbnail.appendChild(img);
                        thumbnail.classList.add('attendant-thumbnail--filled');
                    };
                    reader.readAsDataURL(file);
                });
            }

            // Edit modal file upload
            if (editThumbnail && editFileInput) {
                editThumbnail.addEventListener('click', () => editFileInput.click());

                editFileInput.addEventListener('change', event => {
                    const [file] = event.target.files || [];
                    if (!file) {
                        resetEditThumbnail();
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = e => {
                        editThumbnail.innerHTML = '';
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        editThumbnail.appendChild(img);
                        editThumbnail.classList.add('attendant-thumbnail--filled');
                    };
                    reader.readAsDataURL(file);
                });
            }

            if (attendantModal && attendantForm) {
                attendantModal.addEventListener('hidden.bs.modal', () => {
                    attendantForm.reset();
                    if (fileInput) {
                        fileInput.value = '';
                    }
                    resetThumbnail();
                });
            }

            const detailModalElement = document.getElementById('attendantDetailModal');
            const editModalElement = document.getElementById('editAttendantModal');
            const deleteModalElement = document.getElementById('deleteAttendantModal');

            const getModalController = element => {
                if (!element) return null;
                if (window.bootstrap?.Modal) {
                    return window.bootstrap.Modal.getOrCreateInstance(element);
                }
                if (window.jQuery && typeof window.jQuery(element).modal === 'function') {
                    return {
                        show: () => window.jQuery(element).modal('show'),
                        hide: () => window.jQuery(element).modal('hide'),
                    };
                }
                return null;
            };

            const detailFields = {
                photo: document.getElementById('attendantDetailPhoto'),
                name: document.getElementById('attendantDetailName'),
                staffId: document.getElementById('attendantDetailStaffId'),
                createdAt: document.getElementById('attendantDetailCreatedAt'),
                site: document.getElementById('attendantDetailSite'),
                gender: document.getElementById('attendantDetailGender'),
                dob: document.getElementById('attendantDetailDob'),
                status: document.getElementById('attendantDetailStatus'),
                shift: document.getElementById('attendantDetailShift'),
                address: document.getElementById('attendantDetailAddress'),
                phonePrimary: document.getElementById('attendantDetailPhonePrimary'),
                phoneSecondary: document.getElementById('attendantDetailPhoneSecondary'),
                contactName: document.getElementById('attendantDetailContactName'),
                contactRelationship: document.getElementById('attendantDetailContactRelationship'),
                contactPhone: document.getElementById('attendantDetailContactPhone'),
                contactAddress: document.getElementById('attendantDetailContactAddress'),
            };

            const populateDetailModal = data => {
                const {
                    full_name: fullName = '—',
                    staff_id: staffId = '—',
                    created_at_display: createdAt = '—',
                    site = '—',
                    gender = '—',
                    date_of_birth_display: dobDisplay = '—',
                    address = '—',
                    status: statusLabel = '—',
                    shift: shiftLabel = '—',
                    phone_primary: phonePrimary = '—',
                    phone_secondary: phoneSecondary = '—',
                    contact_name: contactName = '—',
                    contact_relationship: contactRelationship = '—',
                    contact_phone: contactPhone = '—',
                    contact_address: contactAddress = '—',
                    initials = 'NA',
                    profile_photo_url: photoUrl = null,
                } = data;

                if (detailFields.name) detailFields.name.textContent = fullName;
                if (detailFields.staffId) detailFields.staffId.textContent = staffId;
                if (detailFields.createdAt) detailFields.createdAt.textContent = createdAt;
                if (detailFields.site) detailFields.site.textContent = site;
                if (detailFields.gender) detailFields.gender.textContent = gender;
                if (detailFields.dob) detailFields.dob.textContent = dobDisplay;
                if (detailFields.status) detailFields.status.textContent = statusLabel;
                if (detailFields.shift) detailFields.shift.textContent = shiftLabel;
                if (detailFields.address) detailFields.address.textContent = address;
                if (detailFields.phonePrimary) detailFields.phonePrimary.textContent = phonePrimary;
                if (detailFields.phoneSecondary) detailFields.phoneSecondary.textContent = phoneSecondary;
                if (detailFields.contactName) detailFields.contactName.textContent = contactName;
                if (detailFields.contactRelationship) detailFields.contactRelationship.textContent = contactRelationship;
                if (detailFields.contactPhone) detailFields.contactPhone.textContent = contactPhone;
                if (detailFields.contactAddress) detailFields.contactAddress.textContent = contactAddress;

                if (detailFields.photo) {
                    if (photoUrl) {
                        detailFields.photo.classList.add('is-image');
                        detailFields.photo.style.backgroundImage = 'none';
                        detailFields.photo.innerHTML = '';
                        const img = document.createElement('img');
                        img.src = photoUrl;
                        img.alt = fullName;
                        detailFields.photo.appendChild(img);
                    } else {
                        detailFields.photo.classList.remove('is-image');
                        detailFields.photo.style.backgroundImage = 'none';
                        detailFields.photo.innerHTML = initials || 'NA';
                    }
                }

                const detailModalInstance = getModalController(detailModalElement);
                detailModalInstance?.show?.();
            };

            document.querySelectorAll('.attendant-view-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const payload = button.getAttribute('data-attendant-details');
                    if (!payload) return;

                    try {
                        const parsed = JSON.parse(payload);
                        populateDetailModal(parsed);
                    } catch (error) {
                        console.error('Unable to parse attendant details payload', error);
                    }
                });
            });

            // Debug: Check if view buttons exist
            console.log('View buttons found:', document.querySelectorAll('.attendant-view-btn').length);
            console.log('Detail modal element:', detailModalElement);
            console.log('Bootstrap available:', typeof window.bootstrap !== 'undefined');

            const populateEditModal = data => {
                const editForm = document.getElementById('editAttendantForm');
                if (!editForm) return;

                // Update form action with attendant ID
                const updateUrl = data.edit_url || '#';
                editForm.setAttribute('action', updateUrl);

                // Populate form fields
                const fields = {
                    fuel_station_id: data.fuel_station_id || data.site_id || '',
                    staff_id: data.staff_id || '',
                    first_name: data.first_name || '',
                    other_names: data.other_names || '',
                    gender: data.gender || '',
                    date_of_birth: data.date_of_birth || '',
                    address: data.address || '',
                    phone_number_1: data.phone_primary || data.phone_number_1 || '',
                    phone_number_2: data.phone_secondary || data.phone_number_2 || '',
                    contact_name: data.contact_name || '',
                    contact_relationship: data.contact_relationship || '',
                    contact_phone: data.contact_phone || '',
                    contact_address: data.contact_address || '',
                    status: data.status_state || data.status || 'active',
                    shift: data.shift || ''
                };

                // Set form field values
                Object.keys(fields).forEach(fieldName => {
                    const element = document.getElementById(`edit_${fieldName}`);
                    if (element) {
                        element.value = fields[fieldName];
                    }
                });

                // Handle profile photo display
                const thumbnail = document.getElementById('editAttendantThumbnail');
                if (thumbnail && data.profile_photo_url) {
                    thumbnail.innerHTML = '';
                    const img = document.createElement('img');
                    img.src = data.profile_photo_url;
                    img.alt = data.full_name || 'Profile photo';
                    thumbnail.appendChild(img);
                    thumbnail.classList.add('attendant-thumbnail--filled');
                }

                const editModalInstance = getModalController(editModalElement);
                editModalInstance?.show?.();
            };

            document.querySelectorAll('.attendant-edit-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const payload = button.getAttribute('data-edit-attendant');
                    const editUrl = button.getAttribute('data-edit-url');
                    if (!payload) return;

                    try {
                        const parsed = JSON.parse(payload);
                        parsed.edit_url = editUrl;
                        populateEditModal(parsed);
                    } catch (error) {
                        console.error('Unable to parse attendant edit payload', error);
                    }
                });
            });

            const deleteForm = document.getElementById('deleteAttendantForm');
            const deleteNameField = document.getElementById('deleteAttendantName');

            const openDeleteModal = (name, url) => {
                if (!deleteForm) return;
                deleteForm.setAttribute('action', url || '#');
                if (deleteNameField) {
                    deleteNameField.textContent = name || 'this attendant';
                }
                const deleteModalInstance = getModalController(deleteModalElement);
                deleteModalInstance?.show?.();
            };

            document.querySelectorAll('.attendant-delete-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const attendantName = button.getAttribute('data-attendant-name');
                    const deleteUrl = button.getAttribute('data-delete-url');
                    openDeleteModal(attendantName, deleteUrl);
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const successMessage = @json(session('success'));
            const errorMessage = @json(session('error'));

            const canUseSwal = typeof Swal !== 'undefined';

            if (successMessage) {
                if (canUseSwal) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: successMessage,
                        confirmButtonText: 'OK'
                    });
                } else {
                    console.info('Success:', successMessage);
                }
            }

            if (errorMessage) {
                if (canUseSwal) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: errorMessage,
                        confirmButtonText: 'Try Again'
                    });
                } else {
                    console.error('Error:', errorMessage);
                }
            }
        });
    </script>
@endpush
