@extends('layouts.vertical', [
    'page_title' => 'Station Managers',
    'mode' => session('theme_mode', 'light'),
])

@section('css')
    <style>
        :root {
            --mgr-gradient-start: #081424;
            --mgr-gradient-end: #122c54;
            --mgr-surface: #ffffff;
            --mgr-soft-surface: #f3f6ff;
            --mgr-text-primary: #0e1c3a;
            --mgr-text-secondary: #465570;
            --mgr-text-muted: #7e8aa6;
            --mgr-accent: #2563eb;
            --mgr-accent-soft: rgba(37, 99, 235, 0.15);
            --mgr-success: #0ea5e9;
            --mgr-danger: #ef4444;
            --mgr-border: rgba(15, 23, 42, 0.08);
            --mgr-border-strong: rgba(15, 23, 42, 0.16);
            --mgr-radius-lg: 28px;
            --mgr-radius-md: 18px;
            --mgr-radius-sm: 12px;
            --mgr-shadow-lg: 0 40px 80px rgba(15, 23, 42, 0.18);
            --mgr-shadow-md: 0 22px 42px rgba(15, 23, 42, 0.16);
            --mgr-shadow-sm: 0 14px 30px rgba(15, 23, 42, 0.12);
        }

        .manager-dashboard {
            min-height: 100vh;
            background:
                radial-gradient(circle at 12% 18%, rgba(59, 130, 246, 0.26), transparent 45%),
                radial-gradient(circle at 86% -6%, rgba(14, 165, 233, 0.18), transparent 48%),
                linear-gradient(160deg, var(--mgr-gradient-start) 0%, var(--mgr-gradient-end) 100%);
            padding: 3.5rem 0 4.2rem;
            font-family: "Inter", "Segoe UI", sans-serif;
            color: var(--mgr-text-primary);
        }

        .manager-dashboard__inner {
            width: min(1240px, 94vw);
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 2.25rem;
        }

        .manager-hero {
            position: relative;
            background: var(--mgr-surface);
            border-radius: var(--mgr-radius-lg);
            box-shadow: var(--mgr-shadow-lg);
            padding: 2.6rem 2.8rem;
            overflow: hidden;
        }

        .manager-hero::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 88% 18%, rgba(37, 99, 235, 0.18), transparent 54%),
                radial-gradient(circle at -12% 100%, rgba(14, 165, 233, 0.22), transparent 48%);
            pointer-events: none;
        }

        .manager-hero__layout {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 1.6rem;
            z-index: 1;
        }

        .manager-hero__intro {
            max-width: 520px;
            display: flex;
            flex-direction: column;
            gap: 1.1rem;
        }

        .manager-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.72rem;
            letter-spacing: 0.24em;
            text-transform: uppercase;
            color: var(--mgr-text-muted);
        }

        .manager-eyebrow::before {
            content: "";
            width: 34px;
            height: 2px;
            border-radius: 999px;
            background: var(--mgr-accent);
        }

        .manager-hero__intro h1 {
            margin: 0;
            font-size: clamp(2.1rem, 2.9vw, 2.95rem);
            font-weight: 700;
            letter-spacing: -0.015em;
        }

        .manager-hero__intro p {
            margin: 0;
            font-size: 1rem;
            line-height: 1.7;
            color: var(--mgr-text-secondary);
        }

        .manager-toolbar {
            background: rgba(17, 24, 39, 0.32);
            border-radius: var(--mgr-radius-md);
            border: 1px solid rgba(226, 232, 240, 0.2);
            padding: 1.35rem 1.6rem;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1.2rem 1.5rem;
        }

        .manager-toolbar__primary,
        .manager-toolbar__secondary {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .manager-input-group {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            background: var(--mgr-surface);
            border-radius: var(--mgr-radius-sm);
            border: 1px solid var(--mgr-border);
            box-shadow: var(--mgr-shadow-sm);
            padding: 0 1.1rem;
        }

        .manager-input-group i {
            font-size: 1.06rem;
            color: var(--mgr-text-muted);
        }

        .manager-input-group input,
        .manager-input-group select {
            border: none;
            padding: 0.78rem 0;
            font-size: 0.9rem;
            color: var(--mgr-text-primary);
            background: transparent;
            min-width: 180px;
        }

        .manager-input-group select {
            min-width: 160px;
        }

        .manager-input-group input:focus,
        .manager-input-group select:focus {
            outline: none;
        }

        .manager-viewport-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            color: rgba(226, 232, 240, 0.92);
            font-size: 0.82rem;
        }

        .manager-viewport-info strong {
            font-size: 0.96rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .manager-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            border-radius: var(--mgr-radius-sm);
            border: 1px solid transparent;
            padding: 0.78rem 1.5rem;
            font-size: 0.78rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .manager-btn--primary {
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
            color: #ffffff;
            box-shadow: 0 24px 40px rgba(37, 99, 235, 0.28);
        }

        .manager-btn--primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 32px 48px rgba(37, 99, 235, 0.32);
        }

        .manager-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1.25rem;
        }

        .manager-card {
            position: relative;
            background: linear-gradient(155deg, rgba(255, 255, 255, 0.95) 0%, rgba(243, 246, 255, 0.92) 100%);
            border-radius: calc(var(--mgr-radius-md) - 4px);
            border: 1px solid rgba(148, 163, 184, 0.22);
            box-shadow: 0 18px 32px rgba(15, 23, 42, 0.12);
            padding: 1.35rem 1.2rem;
            display: flex;
            flex-direction: column;
            gap: 1.1rem;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
            overflow: hidden;
        }

        .manager-card::before {
            content: "";
            position: absolute;
            inset: -45% -45% auto;
            height: 160px;
            background: radial-gradient(circle at 20% 40%, rgba(37, 99, 235, 0.24) 0%, transparent 65%);
            opacity: 0.7;
            pointer-events: none;
        }

        .manager-card::after {
            content: "";
            position: absolute;
            inset: auto -40% -55%;
            height: 160px;
            background: radial-gradient(circle at 80% 60%, rgba(14, 165, 233, 0.18) 0%, transparent 70%);
            opacity: 0.6;
            pointer-events: none;
        }

        .manager-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 26px 48px rgba(15, 23, 42, 0.16);
        }

        .manager-card__header {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            z-index: 1;
        }

        .manager-identity {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .manager-avatar {
            width: 52px;
            height: 52px;
            border-radius: 18px;
            overflow: hidden;
            border: 1.5px solid rgba(37, 99, 235, 0.3);
            box-shadow: 0 12px 20px rgba(37, 99, 235, 0.18);
            flex-shrink: 0;
            backdrop-filter: blur(4px);
        }

        .manager-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .manager-profile__thumb {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(148, 163, 184, 0.24);
            background: rgba(255, 255, 255, 0.6);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .manager-profile__thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .manager-identity h3 {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .manager-identity span {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.7rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--mgr-text-muted);
        }

        .manager-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.38rem 0.75rem;
            border-radius: 999px;
            font-size: 0.68rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.16) 0%, rgba(37, 99, 235, 0.06) 100%);
            color: var(--mgr-accent);
            font-weight: 600;
            border: 1px solid rgba(37, 99, 235, 0.26);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.56);
        }

        .manager-card__body {
            position: relative;
            display: grid;
            gap: 0.9rem;
            z-index: 1;
        }

        .manager-detail-list {
            display: grid;
            gap: 0.75rem;
        }

        .manager-detail-item {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 0.45rem;
            font-size: 0.82rem;
            color: var(--mgr-text-secondary);
            align-items: start;
        }

        .manager-detail-item span:first-child {
            text-transform: uppercase;
            letter-spacing: 0.12em;
            font-size: 0.66rem;
            color: var(--mgr-text-muted);
        }

        .manager-detail-item span:last-child {
            font-weight: 500;
            color: var(--mgr-text-primary);
        }

        .manager-card__footer {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
            z-index: 1;
        }

        .manager-contact {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem 1rem;
            font-size: 0.83rem;
        }

        .manager-contact a {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            color: var(--mgr-accent);
            text-decoration: none;
            padding: 0.4rem 0.55rem;
            border-radius: 0.75rem;
            background: rgba(37, 99, 235, 0.08);
            transition: background 0.18s ease, transform 0.18s ease;
        }

        .manager-contact a:hover {
            background: rgba(37, 99, 235, 0.16);
            transform: translateY(-1px);
        }

        .manager-card__actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
        }

        .manager-icon-button {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            border: 1px solid rgba(148, 163, 184, 0.28);
            background: rgba(255, 255, 255, 0.72);
            color: var(--mgr-text-secondary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 10px 18px rgba(15, 23, 42, 0.12);
            transition: transform 0.16s ease, box-shadow 0.16s ease, background 0.16s ease;
        }

        .manager-icon-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 30px rgba(15, 23, 42, 0.18);
            background: rgba(255, 255, 255, 0.92);
        }

        .manager-icon-button.is-danger {
            border-color: rgba(239, 68, 68, 0.35);
            color: var(--mgr-danger);
            background: rgba(254, 226, 226, 0.5);
        }

        .manager-empty {
            display: none;
            background: rgba(17, 24, 39, 0.32);
            border: 1px solid rgba(226, 232, 240, 0.24);
            border-radius: var(--mgr-radius-md);
            padding: 2.2rem 1.9rem;
            align-items: center;
            justify-content: center;
            text-align: center;
            flex-direction: column;
            gap: 1.1rem;
            color: rgba(226, 232, 240, 0.92);
        }

        .manager-empty.is-visible {
            display: flex;
        }

        .manager-empty i {
            font-size: 2rem;
        }

        .manager-modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(12, 18, 32, 0.74);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            z-index: 1050;
        }

        .manager-modal-backdrop.is-visible {
            display: flex;
        }

        .manager-modal {
            width: min(720px, 95vw);
            max-height: 94vh;
            background: var(--mgr-surface);
            border-radius: var(--mgr-radius-md);
            overflow: hidden;
            box-shadow: 0 38px 82px rgba(15, 23, 42, 0.32);
            display: flex;
            flex-direction: column;
            animation: modalFadeIn 0.28s ease-out;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-18px) scale(0.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .manager-modal__header {
            padding: 1.5rem 1.8rem;
            background: linear-gradient(135deg, #1f2a44 0%, #1d3b68 90%);
            color: #f8fafc;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .manager-modal__header h3 {
            margin: 0;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            font-size: 1.05rem;
        }

        .manager-modal__close {
            border: none;
            background: rgba(248, 250, 255, 0.18);
            color: #f8fafc;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            font-size: 1.35rem;
            cursor: pointer;
            transition: background 0.18s ease;
        }

        .manager-modal__close:hover {
            background: rgba(248, 250, 255, 0.32);
        }

        .manager-modal__body {
            padding: 1.8rem 1.8rem 1.4rem;
            flex: 1;
            overflow-y: auto;
            background: var(--mgr-soft-surface);
        }

        .manager-modal__footer {
            padding: 1.3rem 1.8rem;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            background: var(--mgr-surface);
            border-top: 1px solid var(--mgr-border);
        }

        .manager-modal__cancel,
        .manager-modal__submit {
            border: none;
            border-radius: var(--mgr-radius-sm);
            padding: 0.7rem 1.4rem;
            font-size: 0.76rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            font-weight: 600;
            cursor: pointer;
        }

        .manager-modal__cancel {
            background: rgba(15, 23, 42, 0.08);
            color: var(--mgr-text-primary);
        }

        .manager-modal__submit {
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
            color: #ffffff;
            box-shadow: 0 18px 34px rgba(37, 99, 235, 0.28);
        }

        .manager-form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.25rem 1.5rem;
        }

        .manager-form-group {
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
        }

        .manager-form-group label {
            letter-spacing: 0.14em;
            text-transform: uppercase;
            font-size: 0.74rem;
            color: var(--mgr-text-muted);
        }

        .manager-input,
        .manager-select,
        .manager-textarea {
            width: 100%;
            border-radius: var(--mgr-radius-sm);
            border: 1px solid var(--mgr-border-strong);
            background: var(--mgr-surface);
            padding: 0.7rem 0.85rem;
            font-size: 0.9rem;
            color: var(--mgr-text-primary);
            transition: border-color 0.16s ease, box-shadow 0.16s ease;
        }

        .manager-input:focus,
        .manager-select:focus,
        .manager-textarea:focus {
            outline: none;
            border-color: rgba(37, 99, 235, 0.6);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.14);
        }

        .manager-textarea {
            min-height: 110px;
            resize: vertical;
        }

        .manager-detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.2rem 1.5rem;
        }

        .manager-detail-card {
            background: var(--mgr-surface);
            border-radius: var(--mgr-radius-sm);
            padding: 1.1rem 1.25rem;
            box-shadow: var(--mgr-shadow-sm);
            border: 1px solid var(--mgr-border);
        }

        .manager-detail-card span {
            display: block;
            font-size: 0.68rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: var(--mgr-text-muted);
            margin-bottom: 0.35rem;
        }

        .manager-detail-card strong {
            font-size: 0.95rem;
            color: var(--mgr-text-primary);
        }

        .manager-confirm {
            background: rgba(254, 226, 226, 0.42);
            border: 1px solid rgba(239, 68, 68, 0.32);
            border-radius: var(--mgr-radius-sm);
            padding: 1.4rem;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
            color: #7f1d1d;
        }

        .manager-confirm h4 {
            margin: 0;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .manager-sms-form {
            display: grid;
            gap: 1rem;
        }

        .manager-sms-preview {
            background: rgba(37, 99, 235, 0.08);
            border: 1px solid rgba(37, 99, 235, 0.24);
            border-radius: var(--mgr-radius-sm);
            padding: 1rem 1.1rem;
            display: grid;
            gap: 0.4rem;
            color: var(--mgr-text-primary);
        }

        .manager-sms-preview span {
            font-size: 0.68rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--mgr-text-muted);
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        @media (max-width: 940px) {
            .manager-toolbar__secondary {
                flex: 1 1 100%;
                justify-content: space-between;
            }

            .manager-hero__meta {
                width: 100%;
            }
        }

        @media (max-width: 680px) {
            .manager-dashboard {
                padding: 2.6rem 0 3.1rem;
            }

            .manager-hero {
                padding: 2.1rem 1.85rem;
            }

            .manager-toolbar {
                padding: 1.2rem 1.3rem;
            }

            .manager-input-group input,
            .manager-input-group select {
                min-width: 0;
            }

            .manager-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 500px) {
            .manager-dashboard__inner {
                gap: 1.6rem;
            }

            .manager-toolbar__primary,
            .manager-toolbar__secondary {
                flex-direction: column;
                align-items: stretch;
            }

            .manager-btn--primary {
                width: 100%;
                justify-content: center;
            }

            .manager-card__header {
                flex-direction: column;
                align-items: flex-start;
            }

            .manager-card__actions {
                justify-content: flex-start;
            }
        }
    </style>
@endsection

@section('content')
    @php
        use Illuminate\Support\Facades\Storage;

        $avatarPlaceholder = asset('images/users/avatar-placeholder.png');

        $managerCollection = collect($managers ?? [])->map(function ($manager) use ($avatarPlaceholder) {
            $station = $manager->station;
            $dobRaw = optional($manager->date_of_birth)->format('Y-m-d');
            $assignRaw = optional($manager->assigned_at)->format('Y-m-d');
            $phone = $manager->phone;
            $phoneRaw = $phone ? preg_replace('/\s+/', '', $phone) : '';

            $avatarUrl = $avatarPlaceholder;
            if ($manager->avatar_path && Storage::disk('public')->exists($manager->avatar_path)) {
                $avatarUrl = asset('storage/' . ltrim($manager->avatar_path, '/'));
            }

            return [
                'id' => $manager->id,
                'full_name' => $manager->full_name,
                'address' => $manager->address,
                'location' => $manager->location ?? optional($station)->location,
                'dob_raw' => $dobRaw,
                'dob_display' => $dobRaw ? optional($manager->date_of_birth)->format('d M Y') : '—',
                'gender' => $manager->gender,
                'phone' => $phone,
                'phone_raw' => $phoneRaw,
                'email' => $manager->email,
                'station_name' => optional($station)->name,
                'station_id' => $manager->fuel_station_id,
                'assign_date_raw' => $assignRaw,
                'assign_date_display' => $assignRaw ? optional($manager->assigned_at)->format('d M Y') : '—',
                'avatar_url' => $avatarUrl,
                'status' => $manager->status,
                'route_edit' => route('company.fuel.station-managers.update', $manager),
                'route_terminate' => route('company.fuel.station-managers.terminate', $manager),
                'route_delete' => route('company.fuel.station-managers.destroy', $manager),
                'route_sms' => route('company.fuel.station-managers.sms', $manager),
            ];
        })->keyBy('id');

        $stationOptions = collect($stations ?? [])
            ->pluck('name', 'id')
            ->filter()
            ->unique()
            ->sort();

        $stationFilterOptions = $stationOptions->values();

        $activeManagerCount = $managerCollection->count();
        $stationCount = $stationOptions->count();
        $lastSyncAbsolute = ($lastSyncedAt ?? now())->format('d M Y · h:i A');
    @endphp

    <div class="manager-dashboard">
        <div class="manager-dashboard__inner">
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="manager-hero">
                <div class="manager-hero__layout">
                    <div class="manager-hero__intro">
                        <span class="manager-eyebrow">Station leadership</span>
                        <h1>Station Manager Registry</h1>
                    </div>
                </div>
            </section>

            <section class="manager-toolbar">
                <div class="manager-toolbar__primary">
                    <div class="manager-input-group">
                        <i class="ri-search-line" aria-hidden="true"></i>
                        <input id="manager-search" type="search" placeholder="Search by name, station, or location" aria-label="Search station managers" autocomplete="off" data-filter-search>
                    </div>
                    <div class="manager-input-group">
                        <i class="ri-map-pin-2-line" aria-hidden="true"></i>
                        <select id="manager-filter-station" aria-label="Filter by station" data-filter-station>
                            <option value="">All stations</option>
                            @foreach ($stationFilterOptions as $stationOption)
                                <option value="{{ $stationOption }}">{{ $stationOption }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="manager-input-group">
                        <i class="ri-group-line" aria-hidden="true"></i>
                        <select id="manager-filter-gender" aria-label="Filter by gender" data-filter-gender>
                            <option value="">All genders</option>
                            <option value="Female">Female</option>
                            <option value="Male">Male</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="manager-toolbar__secondary">
                    <div class="manager-viewport-info">
                        <strong>Directory overview</strong>
                        <span><span data-manager-visible-count>{{ $activeManagerCount }}</span> of {{ $activeManagerCount }} managers · Last sync {{ $lastSyncAbsolute }}</span>
                    </div>
                    <button class="manager-btn manager-btn--primary" type="button" data-open-modal="manager-add">
                        <i class="ri-user-add-line" aria-hidden="true"></i>
                        Add Manager
                    </button>
                </div>
            </section>

            <div class="manager-grid" data-manager-collection>
                @forelse ($managerCollection as $manager)
                    @php
                        $managerIdDisplay = 'STMN-' . str_pad($manager['id'], 3, '0', STR_PAD_LEFT);
                        $stationName = $manager['station_name'] ?? 'Unassigned';
                    @endphp
                    <article
                        class="manager-card"
                        data-manager-record
                        data-manager-id="{{ $manager['id'] }}"
                        data-manager-name="{{ e($manager['full_name']) }}"
                        data-manager-address="{{ e($manager['address']) }}"
                        data-manager-location="{{ e($manager['location']) }}"
                        data-manager-dob="{{ e($manager['dob_display']) }}"
                        data-manager-dob-raw="{{ e($manager['dob_raw']) }}"
                        data-manager-gender="{{ e($manager['gender']) }}"
                        data-manager-phone="{{ e($manager['phone']) }}"
                        data-manager-phone-raw="{{ e($manager['phone_raw']) }}"
                        data-manager-email="{{ e($manager['email']) }}"
                        data-manager-station="{{ e($manager['station_name']) }}"
                        data-manager-station-id="{{ e($manager['station_id']) }}"
                        data-manager-assign-date="{{ e($manager['assign_date_display']) }}"
                        data-manager-assign-date-raw="{{ e($manager['assign_date_raw']) }}"
                        data-manager-avatar="{{ e($manager['avatar_url']) }}"
                        data-manager-edit-action="{{ $manager['route_edit'] }}"
                        data-manager-terminate-action="{{ $manager['route_terminate'] }}"
                        data-manager-delete-action="{{ $manager['route_delete'] }}"
                        data-manager-sms-action="{{ $manager['route_sms'] }}"
                    >
                        <div class="manager-card__header">
                            <div class="manager-identity">
                                <div class="manager-avatar">
                                    <img src="{{ $manager['avatar_url'] }}" alt="{{ $manager['full_name'] }} thumbnail">
                                </div>
                                <div>
                                    <h3>{{ $manager['full_name'] }}</h3>
                                    <span>{{ $managerIdDisplay }}</span>
                                </div>
                            </div>
                            <span class="manager-chip">
                                <i class="ri-gas-station-line" aria-hidden="true"></i>
                                {{ $stationName }}
                            </span>
                        </div>
                        <div class="manager-card__body">
                            <div class="manager-detail-list">
                                <div class="manager-detail-item">
                                    <span>Location</span>
                                    <span>{{ $manager['location'] ?? '—' }}</span>
                                </div>
                                <div class="manager-detail-item">
                                    <span>Address</span>
                                    <span>{{ $manager['address'] }}</span>
                                </div>
                                <div class="manager-detail-item">
                                    <span>Date of Birth</span>
                                    <span>{{ $manager['dob_display'] }}</span>
                                </div>
                                <div class="manager-detail-item">
                                    <span>Gender</span>
                                    <span>{{ $manager['gender'] }}</span>
                                </div>
                                <div class="manager-detail-item">
                                    <span>Assign Date</span>
                                    <span>{{ $manager['assign_date_display'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="manager-card__footer">
                            <div class="manager-contact">
                                @if ($manager['phone_raw'])
                                    <a href="tel:{{ $manager['phone_raw'] }}">
                                        <i class="ri-phone-line" aria-hidden="true"></i>
                                        {{ $manager['phone'] }}
                                    </a>
                                @endif
                                <a href="mailto:{{ $manager['email'] }}">
                                    <i class="ri-phone-line" aria-hidden="true"></i>
                                    {{ $manager['email'] }}
                                </a>
                            </div>
                            <div class="manager-card__actions">
                                <button class="manager-icon-button" type="button" data-open-modal="manager-view">
                                    <i class="ri-eye-line" aria-hidden="true"></i>
                                    <span class="sr-only">View {{ $manager['full_name'] }}</span>
                                </button>
                                <button class="manager-icon-button" type="button" data-open-modal="manager-edit">
                                    <i class="ri-pencil-line" aria-hidden="true"></i>
                                    <span class="sr-only">Edit {{ $manager['full_name'] }}</span>
                                </button>
                                <button class="manager-icon-button" type="button" data-open-modal="manager-sms">
                                    <i class="ri-chat-3-line" aria-hidden="true"></i>
                                    <span class="sr-only">Send SMS to {{ $manager['full_name'] }}</span>
                                </button>
                                <button class="manager-icon-button is-danger" type="button" data-open-modal="manager-terminate">
                                    <i class="ri-user-unfollow-line" aria-hidden="true"></i>
                                    <span class="sr-only">Terminate {{ $manager['full_name'] }}</span>
                                </button>
                                <button class="manager-icon-button is-danger" type="button" data-open-modal="manager-delete">
                                    <i class="ri-delete-bin-line" aria-hidden="true"></i>
                                    <span class="sr-only">Delete {{ $manager['full_name'] }}</span>
                                </button>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="manager-empty is-visible">
                        <i class="ri-user-search-line" aria-hidden="true"></i>
                        <p>No station managers found yet. Add your first manager to get started.</p>
                        <button class="manager-btn manager-btn--primary" type="button" data-open-modal="manager-add">
                            <i class="ri-user-add-line" aria-hidden="true"></i>
                            Add Manager
                        </button>
                    </div>
                @endforelse
            </div>

            <div class="manager-empty" data-manager-empty>
                <i class="ri-user-search-line" aria-hidden="true"></i>
                <p>No station managers match your current filters.</p>
                <button class="manager-btn manager-btn--primary" type="button" data-clear-filters>
                    Reset filters
                </button>
            </div>
        </div>

        {{-- Add Manager Modal --}}
        <div class="manager-modal-backdrop" data-modal="manager-add">
            <div class="manager-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title-manager-add">
                <div class="manager-modal__header">
                    <h3 id="modal-title-manager-add">Add New Station Manager</h3>
                    <button class="manager-modal__close" type="button" data-close-modal aria-label="Close add manager modal">
                        ×
                    </button>
                </div>
                <form action="{{ route('company.fuel.station-managers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="manager-modal__body">
                        <div class="manager-form-grid">
                            <div class="manager-form-group">
                                <label for="manager-avatar">Thumbnail Image <span style="font-weight:400;color:rgba(7,28,63,0.5);">(optional)</span></label>
                                <input class="manager-input" type="file" id="manager-avatar" name="avatar" accept="image/*">
                            </div>

                            <div class="manager-form-group">
                                <label for="manager-name">Full Name *</label>
                                <input class="manager-input" type="text" id="manager-name" name="name" placeholder="Enter full name" value="{{ old('name') }}" required>
                            </div>

                            <div class="manager-form-group">
                                <label for="manager-gender">Gender *</label>
                                <select class="manager-select" id="manager-gender" name="gender" required>
                                    <option value="">Select gender</option>
                                    <option value="Female" @selected(old('gender') === 'Female')>Female</option>
                                    <option value="Male" @selected(old('gender') === 'Male')>Male</option>
                                    <option value="Other" @selected(old('gender') === 'Other')>Other</option>
                                </select>
                            </div>

                            <div class="manager-form-group">
                                <label for="manager-dob">Date of Birth *</label>
                                <input class="manager-input" type="date" id="manager-dob" name="dob" value="{{ old('dob') }}" required>
                            </div>

                            <div class="manager-form-group">
                                <label for="manager-phone">Contact Number *</label>
                                <input class="manager-input" type="tel" id="manager-phone" name="phone" placeholder="+233 XX XXX XXXX" value="{{ old('phone') }}" required>
                            </div>

                            <div class="manager-form-group">
                                <label for="manager-email">Email Address *</label>
                                <input class="manager-input" type="email" id="manager-email" name="email" placeholder="name@fuelmgt.com" value="{{ old('email') }}" required>
                            </div>

                            <div class="manager-form-group">
                                <label for="manager-station">Assign to Station *</label>
                                <select class="manager-select" id="manager-station" name="station_id">
                                    <option value="">Select station</option>
                                    @foreach ($stationOptions as $stationId => $stationOption)
                                        <option value="{{ $stationId }}" @selected(old('station_id') == $stationId)>{{ $stationOption }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="manager-form-group">
                                <label for="manager-assign-date">Assign Date *</label>
                                <input class="manager-input" type="date" id="manager-assign-date" name="assign_date" value="{{ old('assign_date') }}" required>
                            </div>

                            <div class="manager-form-group" style="grid-column: span 2;">
                                <label for="manager-address">Residential Address *</label>
                                <textarea class="manager-textarea" id="manager-address" name="address" placeholder="Enter complete residential address" required>{{ old('address') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="manager-modal__footer">
                        <button type="button" class="manager-modal__cancel" data-close-modal>Cancel</button>
                        <button type="submit" class="manager-modal__submit">Save Manager</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- View Manager Modal --}}
        <div class="manager-modal-backdrop" data-modal="manager-view">
            <div class="manager-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title-manager-view">
                <div class="manager-modal__header">
                    <h3 id="modal-title-manager-view">Manager Details</h3>
                    <button class="manager-modal__close" type="button" data-close-modal aria-label="Close view manager modal">
                        ×
                    </button>
                </div>
                <div class="manager-modal__body">
                    <div class="manager-detail-grid">
                        <div class="manager-detail-card" style="grid-column: span 2; display: flex; align-items: center; gap: 1rem;">
                            <div class="manager-profile__thumb" style="width: 60px; height: 60px;">
                                <img src="" alt="" data-modal-field="avatar">
                            </div>
                            <div>
                                <strong data-modal-field="name">—</strong>
                                <span data-modal-field="station" style="display:block; margin-top:0.35rem; font-size:0.72rem; letter-spacing:0.14em; text-transform:uppercase; color:rgba(7,28,63,0.6);">—</span>
                            </div>
                        </div>
                        <div class="manager-detail-card">
                            <span>Email Address</span>
                            <strong data-modal-field="email">—</strong>
                        </div>
                        <div class="manager-detail-card">
                            <span>Contact Number</span>
                            <strong><a href="#" data-modal-field="phone-link">—</a></strong>
                        </div>
                        <div class="manager-detail-card">
                            <span>Gender</span>
                            <strong data-modal-field="gender">—</strong>
                        </div>
                        <div class="manager-detail-card">
                            <span>Date of Birth</span>
                            <strong data-modal-field="dob">—</strong>
                        </div>
                        <div class="manager-detail-card">
                            <span>Assign Date</span>
                            <strong data-modal-field="assign_date">—</strong>
                        </div>
                        <div class="manager-detail-card">
                            <span>Location</span>
                            <strong data-modal-field="location">—</strong>
                        </div>
                        <div class="manager-detail-card large">
                            <span>Residential Address</span>
                            <strong data-modal-field="address">—</strong>
                        </div>
                    </div>
                </div>
                <div class="manager-modal__footer">
                    <button type="button" class="manager-modal__cancel" data-close-modal>Close</button>
                </div>
            </div>
        </div>

        {{-- Edit Manager Modal --}}
        <div class="manager-modal-backdrop" data-modal="manager-edit">
            <div class="manager-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title-manager-edit">
                <div class="manager-modal__header">
                    <h3 id="modal-title-manager-edit">Edit Manager Record</h3>
                    <button class="manager-modal__close" type="button" data-close-modal aria-label="Close edit manager modal">
                        ×
                    </button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data" data-manager-edit-form>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="manager_id" data-modal-field="edit-id">
                    <input type="hidden" name="_action" data-modal-field="edit-action">
                    <div class="manager-modal__body">
                        <div class="manager-form-grid">
                            <div class="manager-form-group">
                                <label for="edit-manager-avatar">Thumbnail Image</label>
                                <input class="manager-input" type="file" id="edit-manager-avatar" name="avatar" accept="image/*">
                                <small style="font-size:0.65rem; color:rgba(7,28,63,0.55); letter-spacing:0.1em; text-transform:uppercase;">Leave blank to keep existing image.</small>
                            </div>

                            <div class="manager-form-group">
                                <label for="edit-manager-name">Full Name *</label>
                                <input class="manager-input" type="text" id="edit-manager-name" name="name" required data-modal-field="edit-name">
                            </div>

                            <div class="manager-form-group">
                                <label for="edit-manager-gender">Gender *</label>
                                <select class="manager-select" id="edit-manager-gender" name="gender" required data-modal-field="edit-gender">
                                    <option value="Female">Female</option>
                                    <option value="Male">Male</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div class="manager-form-group">
                                <label for="edit-manager-dob">Date of Birth *</label>
                                <input class="manager-input" type="date" id="edit-manager-dob" name="dob" required data-modal-field="edit-dob">
                            </div>

                            <div class="manager-form-group">
                                <label for="edit-manager-phone">Contact Number *</label>
                                <input class="manager-input" type="tel" id="edit-manager-phone" name="phone" required data-modal-field="edit-phone">
                            </div>

                            <div class="manager-form-group">
                                <label for="edit-manager-email">Email Address *</label>
                                <input class="manager-input" type="email" id="edit-manager-email" name="email" required data-modal-field="edit-email">
                            </div>

                            <div class="manager-form-group">
                                <label for="edit-manager-station">Assign to Station *</label>
                                <select class="manager-select" id="edit-manager-station" name="station_id" required data-modal-field="edit-station">
                                    <option value="">Select station</option>
                                    @foreach ($stationOptions as $stationId => $stationOption)
                                        <option value="{{ $stationId }}">{{ $stationOption }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="manager-form-group">
                                <label for="edit-manager-assign-date">Assign Date *</label>
                                <input class="manager-input" type="date" id="edit-manager-assign-date" name="assign_date" required data-modal-field="edit-assign-date">
                            </div>

                            <div class="manager-form-group" style="grid-column: span 2;">
                                <label for="edit-manager-address">Residential Address *</label>
                                <textarea class="manager-textarea" id="edit-manager-address" name="address" required data-modal-field="edit-address"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="manager-modal__footer">
                        <button type="button" class="manager-modal__cancel" data-close-modal>Cancel</button>
                        <button type="submit" class="manager-modal__submit">Update Record</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Send SMS Modal --}}
        <div class="manager-modal-backdrop" data-modal="manager-sms">
            <div class="manager-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title-manager-sms">
                <div class="manager-modal__header">
                    <h3 id="modal-title-manager-sms">Send SMS to Manager</h3>
                    <button class="manager-modal__close" type="button" data-close-modal aria-label="Close send SMS modal">
                        ×
                    </button>
                </div>
                <form action="" method="POST" data-manager-sms-form>
                    @csrf
                    <input type="hidden" name="manager_id" data-modal-field="sms-id">
                    <input type="hidden" name="_action" data-modal-field="sms-action">
                    <div class="manager-modal__body">
                        <div class="manager-sms-form">
                            <div class="manager-form-group">
                                <label>Recipient</label>
                                <input class="manager-input" type="text" readonly data-modal-field="sms-recipient">
                            </div>
                            <div class="manager-form-group">
                                <label>Recipient Number</label>
                                <input class="manager-input" type="text" readonly data-modal-field="sms-phone">
                            </div>
                            <div class="manager-form-group">
                                <label for="manager-sms-message">Message *</label>
                                <textarea class="manager-textarea" id="manager-sms-message" name="message" placeholder="Enter message for station manager" required></textarea>
                            </div>
                            <div class="manager-sms-preview">
                                <span>Preview</span>
                                <strong data-modal-field="sms-preview">No message composed yet.</strong>
                            </div>
                        </div>
                    </div>
                    <div class="manager-modal__footer">
                        <button type="button" class="manager-modal__cancel" data-close-modal>Cancel</button>
                        <button type="submit" class="manager-modal__submit">Send SMS</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Terminate Manager Modal --}}
        <div class="manager-modal-backdrop" data-modal="manager-terminate">
            <div class="manager-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title-manager-terminate">
                <div class="manager-modal__header" style="background: linear-gradient(120deg, #8a1c1c 0%, #c63e3e 100%);">
                    <h3 id="modal-title-manager-terminate">Terminate Manager Assignment</h3>
                    <button class="manager-modal__close" type="button" data-close-modal aria-label="Close terminate modal">
                        ×
                    </button>
                </div>
                <form action="" method="POST" data-manager-terminate-form>
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="manager_id" data-modal-field="terminate-id">
                    <input type="hidden" name="_action" data-modal-field="terminate-action">
                    <div class="manager-modal__body">
                        <div class="manager-confirm" data-terminate-modal>
                            <h4 data-modal-field="terminate-title">Terminate Manager</h4>
                            <p>
                                You are about to terminate <strong data-modal-field="terminate-name">—</strong> from
                                <strong data-modal-field="terminate-station">—</strong>.
                            </p>
                            <p>
                                Please provide a termination note for audit purposes.
                            </p>
                            <textarea class="manager-textarea" name="terminate_reason" placeholder="Specify termination reason" required></textarea>
                        </div>
                    </div>
                    <div class="manager-modal__footer">
                        <button type="button" class="manager-modal__cancel" data-close-modal>Cancel</button>
                        <button type="submit" class="manager-modal__submit" style="background: linear-gradient(120deg, #b42323 0%, #d95a5a 100%); box-shadow: 0 10px 22px rgba(212, 78, 78, 0.28);">Terminate</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Delete Manager Modal --}}
        <div class="manager-modal-backdrop" data-modal="manager-delete">
            <div class="manager-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title-manager-delete">
                <div class="manager-modal__header" style="background: linear-gradient(120deg, #6b1111 0%, #9f1b1b 100%);">
                    <h3 id="modal-title-manager-delete">Delete Manager Record</h3>
                    <button class="manager-modal__close" type="button" data-close-modal aria-label="Close delete manager modal">
                        ×
                    </button>
                </div>
                <form action="" method="POST" data-manager-delete-form>
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="manager_id" data-modal-field="delete-id">
                    <input type="hidden" name="_action" data-modal-field="delete-action">
                    <div class="manager-modal__body">
                        <div class="manager-confirm">
                            <h4>Confirm Record Deletion</h4>
                            <p>
                                This will permanently remove <strong data-modal-field="delete-name">—</strong>
                                from the station manager registry and revoke all associated permissions.
                            </p>
                            <p>
                                Assigned Station: <strong data-modal-field="delete-station">—</strong><br>
                                Email: <strong data-modal-field="delete-email">—</strong>
                            </p>
                            <input class="manager-input" type="text" placeholder="Type DELETE to confirm" name="delete_confirm" required>
                        </div>
                    </div>
                    <div class="manager-modal__footer">
                        <button type="button" class="manager-modal__cancel" data-close-modal>Cancel</button>
                        <button type="submit" class="manager-modal__submit" style="background: linear-gradient(120deg, #a51414 0%, #d83434 100%); box-shadow: 0 10px 22px rgba(186, 45, 45, 0.28);">Delete Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
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

            const modalBackdrops = Array.from(document.querySelectorAll('[data-modal]'));
            const openModalButtons = Array.from(document.querySelectorAll('[data-open-modal]'));
            const closeModalButtons = Array.from(document.querySelectorAll('[data-close-modal]'));
            const stationEndpoint = @json(route('company.fuel.station-managers.stations'));
            const stationSelectTargets = ['#manager-station', '#edit-manager-station'];
            let stationOptionsCache = null;
            let stationRequestInFlight = false;

            const modalRegistry = new Map();
            modalBackdrops.forEach((backdrop) => {
                const name = backdrop.dataset.modal;
                if (name) {
                    modalRegistry.set(name, backdrop);
                }
            });

            const toggleModal = (modalName, shouldShow) => {
                const backdrop = modalRegistry.get(modalName);
                if (!backdrop) return;
                backdrop.classList.toggle('is-visible', shouldShow);
            };

            const populateModal = (modalName, dataset = {}) => {
                const modal = modalRegistry.get(modalName);
                if (!modal) return;

                if (modalName === 'manager-add') {
                    const form = modal.querySelector('form');
                    form?.reset();
                    hydrateStationSelects();
                    return;
                }

                if (modalName === 'manager-view') {
                    const setText = (field, value = '—') => {
                        const el = modal.querySelector(`[data-modal-field="${field}"]`);
                        if (!el) return;

                        if (field === 'avatar' && el.tagName === 'IMG') {
                            el.src = value || '';
                            el.alt = dataset.managerName ? `${dataset.managerName} thumbnail` : 'Manager thumbnail';
                            return;
                        }

                        if (field === 'phone-link' && el.tagName === 'A') {
                            el.textContent = value || '—';
                            el.setAttribute('href', dataset.managerPhoneRaw ? `tel:${dataset.managerPhoneRaw}` : '#');
                            return;
                        }

                        el.textContent = value || '—';
                    };

                    setText('avatar', dataset.managerAvatar);
                    setText('name', dataset.managerName);
                    setText('station', dataset.managerStation);
                    setText('email', dataset.managerEmail);
                    setText('phone-link', dataset.managerPhone);
                    setText('gender', dataset.managerGender);
                    setText('dob', dataset.managerDob);
                    setText('assign_date', dataset.managerAssignDate);
                    setText('location', dataset.managerLocation);
                    setText('address', dataset.managerAddress);
                    return;
                }

                if (modalName === 'manager-edit') {
                    hydrateStationSelects(() => {
                        const editSelect = modal.querySelector('[data-modal-field="edit-station"]');
                        if (editSelect) {
                            editSelect.value = dataset.managerStationId || '';
                        }
                    });

                    const setValue = (field, value = '') => {
                        const el = modal.querySelector(`[data-modal-field="${field}"]`);
                        if (!el) return;
                        el.value = value || '';
                    };

                    const editForm = modal.querySelector('[data-manager-edit-form]');
                    if (editForm && dataset.managerEditAction) {
                        editForm.setAttribute('action', dataset.managerEditAction);
                    }

                    const editActionField = modal.querySelector('[data-modal-field="edit-action"]');
                    if (editActionField) {
                        editActionField.value = dataset.managerEditAction || '';
                    }

                    setValue('edit-id', dataset.managerId);
                    setValue('edit-name', dataset.managerName);
                    setValue('edit-gender', dataset.managerGender);
                    setValue('edit-dob', dataset.managerDobRaw);
                    setValue('edit-phone', dataset.managerPhone);
                    setValue('edit-email', dataset.managerEmail);
                    setValue('edit-station', dataset.managerStationId);
                    setValue('edit-assign-date', dataset.managerAssignDateRaw);
                    setValue('edit-address', dataset.managerAddress);
                    return;
                }

                if (modalName === 'manager-sms') {
                    const recipientField = modal.querySelector('[data-modal-field="sms-recipient"]');
                    const phoneField = modal.querySelector('[data-modal-field="sms-phone"]');
                    const previewField = modal.querySelector('[data-modal-field="sms-preview"]');
                    const idField = modal.querySelector('[data-modal-field="sms-id"]');
                    const messageArea = modal.querySelector('#manager-sms-message');
                    const smsForm = modal.querySelector('[data-manager-sms-form]');
                    if (smsForm && dataset.managerSmsAction) {
                        smsForm.setAttribute('action', dataset.managerSmsAction);
                    }

                    const smsActionField = modal.querySelector('[data-modal-field="sms-action"]');
                    if (smsActionField) {
                        smsActionField.value = dataset.managerSmsAction || '';
                    }

                    if (recipientField) recipientField.value = dataset.managerName || '';
                    if (phoneField) phoneField.value = dataset.managerPhone || '';
                    if (idField) idField.value = dataset.managerId || '';
                    if (messageArea) {
                        messageArea.value = '';
                        messageArea.dispatchEvent(new Event('input'));
                    }
                    if (previewField) {
                        previewField.textContent = 'No message composed yet.';
                    }
                    return;
                }

                if (modalName === 'manager-terminate') {
                    const idField = modal.querySelector('[data-modal-field="terminate-id"]');
                    const nameField = modal.querySelector('[data-modal-field="terminate-name"]');
                    const stationField = modal.querySelector('[data-modal-field="terminate-station"]');
                    const titleField = modal.querySelector('[data-modal-field="terminate-title"]');
                    const reasonField = modal.querySelector('textarea[name="terminate_reason"]');
                    const terminateForm = modal.querySelector('[data-manager-terminate-form]');

                    if (terminateForm && dataset.managerTerminateAction) {
                        terminateForm.setAttribute('action', dataset.managerTerminateAction);
                    }

                    const terminateActionField = modal.querySelector('[data-modal-field="terminate-action"]');
                    if (terminateActionField) {
                        terminateActionField.value = dataset.managerTerminateAction || '';
                    }

                    if (idField) idField.value = dataset.managerId || '';
                    if (nameField) nameField.textContent = dataset.managerName || '—';
                    if (stationField) stationField.textContent = dataset.managerStation || '—';
                    if (titleField) titleField.textContent = dataset.managerName ? `Terminate ${dataset.managerName}` : 'Terminate Manager';
                    if (reasonField) reasonField.value = '';
                    return;
                }

                if (modalName === 'manager-delete') {
                    const idField = modal.querySelector('[data-modal-field="delete-id"]');
                    const nameField = modal.querySelector('[data-modal-field="delete-name"]');
                    const stationField = modal.querySelector('[data-modal-field="delete-station"]');
                    const emailField = modal.querySelector('[data-modal-field="delete-email"]');
                    const confirmInput = modal.querySelector('input[name="delete_confirm"]');
                    const deleteForm = modal.querySelector('[data-manager-delete-form]');

                    if (deleteForm && dataset.managerDeleteAction) {
                        deleteForm.setAttribute('action', dataset.managerDeleteAction);
                    }

                    const deleteActionField = modal.querySelector('[data-modal-field="delete-action"]');
                    if (deleteActionField) {
                        deleteActionField.value = dataset.managerDeleteAction || '';
                    }

                    if (idField) idField.value = dataset.managerId || '';
                    if (nameField) nameField.textContent = dataset.managerName || '—';
                    if (stationField) stationField.textContent = dataset.managerStation || '—';
                    if (emailField) emailField.textContent = dataset.managerEmail || '—';
                    if (confirmInput) confirmInput.value = '';
                }
            };

            const hydrateStationSelects = (callback) => {
                const selects = stationSelectTargets
                    .map((selector) => document.querySelector(selector))
                    .filter((el) => el);

                if (!stationEndpoint || selects.length === 0) {
                    if (typeof callback === 'function') callback();
                    return;
                }

                const applyOptions = (stations) => {
                    selects.forEach((select) => {
                        const currentValue = select.value;
                        select.innerHTML = '<option value="">Select station</option>';

                        stations.forEach((station) => {
                            const option = document.createElement('option');
                            option.value = station.id;
                            option.textContent = `${station.name}`;
                            if (select.dataset.modalField === 'edit-station') {
                                option.selected = station.id === Number(select.dataset.selectedStationId);
                            }
                            select.appendChild(option);
                        });

                        if (currentValue) {
                            select.value = currentValue;
                        }
                    });

                    if (typeof callback === 'function') callback();
                };

                if (stationOptionsCache) {
                    applyOptions(stationOptionsCache);
                    return;
                }

                if (stationRequestInFlight) {
                    const waitForStations = () => {
                        if (stationOptionsCache) {
                            applyOptions(stationOptionsCache);
                        } else {
                            requestAnimationFrame(waitForStations);
                        }
                    };
                    waitForStations();
                    return;
                }

                stationRequestInFlight = true;

                fetch(stationEndpoint, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error('Failed to load station list');
                        }
                        return response.json();
                    })
                    .then((payload) => {
                        if (!payload?.success || !Array.isArray(payload.data)) {
                            throw new Error('Invalid station payload');
                        }
                        stationOptionsCache = payload.data;
                        applyOptions(stationOptionsCache);
                    })
                    .catch((error) => {
                        console.error('Station list fetch failed:', error);
                    })
                    .finally(() => {
                        stationRequestInFlight = false;
                    });
            };

            openModalButtons.forEach((button) => {
                button.addEventListener('click', (event) => {
                    const modalName = event.currentTarget.dataset.openModal;
                    if (!modalName) return;

                    const record = event.currentTarget.closest('[data-manager-record]');
                    const dataset = record ? { ...record.dataset } : {};

                    populateModal(modalName, dataset);
                    toggleModal(modalName, true);
                });
            });

            closeModalButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    const backdrop = button.closest('[data-modal]');
                    if (!backdrop) return;
                    toggleModal(backdrop.dataset.modal, false);
                });
            });

            modalBackdrops.forEach((backdrop) => {
                backdrop.addEventListener('click', (event) => {
                    if (event.target === backdrop) {
                        toggleModal(backdrop.dataset.modal, false);
                    }
                });
            });

            const smsModal = modalRegistry.get('manager-sms');
            if (smsModal) {
                const smsMessageArea = smsModal.querySelector('#manager-sms-message');
                const smsPreviewField = smsModal.querySelector('[data-modal-field="sms-preview"]');

                if (smsMessageArea && smsPreviewField) {
                    smsMessageArea.addEventListener('input', () => {
                        const text = smsMessageArea.value.trim();
                        smsPreviewField.textContent = text || 'No message composed yet.';
                    });
                }
            }

            const searchInput = document.querySelector('[data-filter-search]');
            const stationSelect = document.querySelector('[data-filter-station]');
            const genderSelect = document.querySelector('[data-filter-gender]');
            const clearFiltersButton = document.querySelector('[data-clear-filters]');
            const managerCards = Array.from(document.querySelectorAll('[data-manager-record]'));
            const emptyState = document.querySelector('[data-manager-empty]');
            const visibleCountEl = document.querySelector('[data-manager-visible-count]');

            const applyFilters = () => {
                const searchTerm = (searchInput?.value || '').trim().toLowerCase();
                const stationFilter = stationSelect?.value || '';
                const genderFilter = genderSelect?.value || '';

                let visibleCount = 0;

                managerCards.forEach((card) => {
                    const name = (card.dataset.managerName || '').toLowerCase();
                    const station = card.dataset.managerStation || '';
                    const location = (card.dataset.managerLocation || '').toLowerCase();
                    const gender = card.dataset.managerGender || '';

                    const matchesSearch = !searchTerm || name.includes(searchTerm) || station.toLowerCase().includes(searchTerm) || location.includes(searchTerm);
                    const matchesStation = !stationFilter || station === stationFilter;
                    const matchesGender = !genderFilter || gender === genderFilter;

                    const isVisible = matchesSearch && matchesStation && matchesGender;
                    card.style.display = isVisible ? '' : 'none';

                    if (isVisible) {
                        visibleCount += 1;
                    }
                });

                if (visibleCountEl) {
                    visibleCountEl.textContent = visibleCount;
                }

                if (emptyState) {
                    emptyState.classList.toggle('is-visible', visibleCount === 0);
                }
            };

            searchInput?.addEventListener('input', applyFilters);
            stationSelect?.addEventListener('change', applyFilters);
            genderSelect?.addEventListener('change', applyFilters);

            clearFiltersButton?.addEventListener('click', () => {
                if (searchInput) searchInput.value = '';
                if (stationSelect) stationSelect.value = '';
                if (genderSelect) genderSelect.value = '';
                applyFilters();
            });

            applyFilters();
        });
    </script>
@endpush
