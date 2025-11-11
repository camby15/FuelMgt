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
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.65rem;
        }

        .manager-card {
            position: relative;
            background: var(--mgr-surface);
            border-radius: var(--mgr-radius-md);
            border: 1px solid var(--mgr-border);
            box-shadow: var(--mgr-shadow-md);
            padding: 1.6rem 1.55rem;
            display: flex;
            flex-direction: column;
            gap: 1.4rem;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .manager-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 30px 54px rgba(15, 23, 42, 0.18);
        }

        .manager-card__header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1.2rem;
        }

        .manager-identity {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .manager-avatar {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid rgba(37, 99, 235, 0.2);
            box-shadow: 0 14px 24px rgba(37, 99, 235, 0.2);
            flex-shrink: 0;
        }

        .manager-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .manager-profile__thumb {
            width: 58px;
            height: 58px;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid var(--mgr-border);
            background: var(--mgr-soft-surface);
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
            font-size: 1.12rem;
            font-weight: 700;
        }

        .manager-identity span {
            display: block;
            margin-top: 0.3rem;
            font-size: 0.74rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--mgr-text-muted);
        }

        .manager-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 0.85rem;
            border-radius: 999px;
            font-size: 0.7rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            background: var(--mgr-accent-soft);
            color: var(--mgr-accent);
            font-weight: 600;
        }

        .manager-card__body {
            display: grid;
            gap: 1rem;
        }

        .manager-detail-list {
            display: grid;
            gap: 0.85rem;
        }

        .manager-detail-item {
            display: flex;
            justify-content: space-between;
            gap: 0.65rem;
            font-size: 0.82rem;
            color: var(--mgr-text-secondary);
        }

        .manager-detail-item span:first-child {
            text-transform: uppercase;
            letter-spacing: 0.12em;
            font-size: 0.7rem;
            color: var(--mgr-text-muted);
        }

        .manager-card__footer {
            display: flex;
            flex-direction: column;
            gap: 0.9rem;
        }

        .manager-contact {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem 1.2rem;
            font-size: 0.85rem;
        }

        .manager-contact a {
            color: var(--mgr-accent);
            text-decoration: none;
            transition: opacity 0.18s ease;
        }

        .manager-contact a:hover {
            opacity: 0.75;
        }

        .manager-card__actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.45rem;
        }

        .manager-icon-button {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            border: 1px solid var(--mgr-border);
            background: var(--mgr-surface);
            color: var(--mgr-text-secondary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: var(--mgr-shadow-sm);
            transition: transform 0.16s ease, box-shadow 0.16s ease;
        }

        .manager-icon-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 32px rgba(15, 23, 42, 0.18);
        }

        .manager-icon-button.is-danger {
            border-color: rgba(239, 68, 68, 0.3);
            color: var(--mgr-danger);
            background: rgba(254, 226, 226, 0.42);
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
        $managerRows = [
            [
                'id' => 201,
                'avatar' => 'avatar-1.jpg',
                'name' => 'Rahim Sulemana',
                'address' => 'Opp. Wapuli Transport Yard, Tamale-Bimbilla Rd',
                'location' => 'Northern · Saboba District',
                'dob' => '1984-09-21',
                'gender' => 'Male',
                'phone' => '+233 20 700 4410',
                'email' => 'rahim.sulemana@fuelmgt.com',
                'station' => 'Wapuli',
                'assign_date' => '2022-04-15',
            ],
            [
                'id' => 202,
                'avatar' => 'avatar-3.jpg',
                'name' => 'Abena Kwakye',
                'address' => 'Station Road, Wiaga Township',
                'location' => 'Upper East · Builsa North',
                'dob' => '1990-05-11',
                'gender' => 'Female',
                'phone' => '+233 24 111 2233',
                'email' => 'abena.kwakye@fuelmgt.com',
                'station' => 'Wiaga',
                'assign_date' => '2023-01-08',
            ],
            [
                'id' => 203,
                'avatar' => 'avatar-4.jpg',
                'name' => 'Atinga Wusulu',
                'address' => 'Border Market Lane, Paga',
                'location' => 'Upper East · Kassena-Nankana West',
                'dob' => '2014-07-27',
                'gender' => 'Female',
                'phone' => '+233 24 990 6623',
                'email' => 'atinga.wusulu@fuelmgt.com',
                'station' => 'Paga Annex',
                'assign_date' => '2021-07-26',
            ],
            [
                'id' => 204,
                'avatar' => 'avatar-5.jpg',
                'name' => 'Daniel Esubonteng',
                'address' => 'Amoako Lorry Park, Nalerigu Rd',
                'location' => 'North East · East Mamprusi',
                'dob' => '1982-03-05',
                'gender' => 'Male',
                'phone' => '+233 24 330 7711',
                'email' => 'daniel.esubonteng@fuelmgt.com',
                'station' => 'Amoako',
                'assign_date' => '2020-11-02',
            ],
            [
                'id' => 205,
                'avatar' => 'avatar-6.jpg',
                'name' => 'Helen Bawa',
                'address' => 'Central Market Ring Road, Navrongo',
                'location' => 'Upper East · Kassena-Nankana',
                'dob' => '1989-06-28',
                'gender' => 'Female',
                'phone' => '+233 50 998 4411',
                'email' => 'helen.bawa@fuelmgt.com',
                'station' => 'Navrongo Main',
                'assign_date' => '2024-03-12',
            ],
            [
                'id' => 206,
                'avatar' => 'avatar-7.jpg',
                'name' => 'Samuel Tia',
                'address' => 'Larabanga Junction, Mole Park Access Rd',
                'location' => 'Savannah · West Gonja',
                'dob' => '1985-01-17',
                'gender' => 'Male',
                'phone' => '+233 27 803 5566',
                'email' => 'samuel.tia@fuelmgt.com',
                'station' => 'Larabanga',
                'assign_date' => '2022-09-03',
            ],
            [
                'id' => 207,
                'avatar' => 'avatar-8.jpg',
                'name' => 'Jonah Laar',
                'address' => 'Banvim High Street, Opp. Community Clinic',
                'location' => 'Savannah · Sawla-Tuna-Kalba',
                'dob' => '1983-08-09',
                'gender' => 'Male',
                'phone' => '+233 20 332 1144',
                'email' => 'jonah.laar@fuelmgt.com',
                'station' => 'Bamvin',
                'assign_date' => '2019-05-21',
            ],
            [
                'id' => 208,
                'avatar' => 'avatar-2.jpg',
                'name' => 'Grace Pwal',
                'address' => 'River Point Market Square, Pwalugu',
                'location' => 'Upper East · Talensi District',
                'dob' => '1988-02-14',
                'gender' => 'Female',
                'phone' => '+233 26 200 8890',
                'email' => 'grace.pwal@fuelmgt.com',
                'station' => 'Pwalugu',
                'assign_date' => '2023-09-18',
            ],
            [
                'id' => 209,
                'avatar' => 'avatar-9.jpg',
                'name' => 'Richard Mensah',
                'address' => 'Old Cargo Terminal Road, Kintampo',
                'location' => 'Bono East · Kintampo North',
                'dob' => '1981-11-02',
                'gender' => 'Male',
                'phone' => '+233 24 555 2033',
                'email' => 'richard.mensah@fuelmgt.com',
                'station' => 'Kintampo',
                'assign_date' => '2021-02-11',
            ],
            [
                'id' => 210,
                'avatar' => 'avatar-10.jpg',
                'name' => 'Salma Yakubu',
                'address' => 'Canal View Estate, Navrongo Township',
                'location' => 'Upper East · Kassena-Nankana',
                'dob' => '1992-04-08',
                'gender' => 'Female',
                'phone' => '+233 20 880 4477',
                'email' => 'salma.yakubu@fuelmgt.com',
                'station' => 'Navrongo-2',
                'assign_date' => '2024-05-27',
            ],
            [
                'id' => 211,
                'avatar' => 'avatar-11.jpg',
                'name' => 'Michael Bugri',
                'address' => 'Bugubele Main Street, District Assembly Loop',
                'location' => 'Upper East · Builsa South',
                'dob' => '1986-08-22',
                'gender' => 'Male',
                'phone' => '+233 27 410 9982',
                'email' => 'michael.bugri@fuelmgt.com',
                'station' => 'Bugubele',
                'assign_date' => '2020-06-19',
            ],
        ];

        $stationOptions = array_unique(array_map(fn($manager) => $manager['station'], $managerRows));
        sort($stationOptions);

        $activeManagerCount = count($managerRows);
        $stationCount = count($stationOptions);
        $lastSyncAbsolute = now()->format('d M Y · h:i A');
    @endphp

    <div class="manager-dashboard">
        <div class="manager-dashboard__inner">
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
                            @foreach ($stationOptions as $stationOption)
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
                @foreach ($managerRows as $manager)
                    @php
                        $dobFormatted = \Carbon\Carbon::parse($manager['dob'])->format('d M Y');
                        $assignDateFormatted = \Carbon\Carbon::parse($manager['assign_date'])->format('d M Y');
                        $phoneRaw = preg_replace('/\s+/', '', $manager['phone']);
                        $avatarPath = asset('images/users/' . $manager['avatar']);
                        $managerIdDisplay = 'STMN-' . str_pad($manager['id'], 3, '0', STR_PAD_LEFT);
                    @endphp
                    <article
                        class="manager-card"
                        data-manager-record
                        data-manager-id="{{ $manager['id'] }}"
                        data-manager-name="{{ e($manager['name']) }}"
                        data-manager-address="{{ e($manager['address']) }}"
                        data-manager-location="{{ e($manager['location']) }}"
                        data-manager-dob="{{ e($dobFormatted) }}"
                        data-manager-dob-raw="{{ e($manager['dob']) }}"
                        data-manager-gender="{{ e($manager['gender']) }}"
                        data-manager-phone="{{ e($manager['phone']) }}"
                        data-manager-phone-raw="{{ e($phoneRaw) }}"
                        data-manager-email="{{ e($manager['email']) }}"
                        data-manager-station="{{ e($manager['station']) }}"
                        data-manager-assign-date="{{ e($assignDateFormatted) }}"
                        data-manager-assign-date-raw="{{ e($manager['assign_date']) }}"
                        data-manager-avatar="{{ e($avatarPath) }}"
                    >
                        <div class="manager-card__header">
                            <div class="manager-identity">
                                <div class="manager-avatar">
                                    <img src="{{ $avatarPath }}" alt="{{ $manager['name'] }} thumbnail">
                                </div>
                                <div>
                                    <h3>{{ $manager['name'] }}</h3>
                                    <span>{{ $managerIdDisplay }}</span>
                                </div>
                            </div>
                            <span class="manager-chip">
                                <i class="ri-gas-station-line" aria-hidden="true"></i>
                                {{ $manager['station'] }}
                            </span>
                        </div>
                        <div class="manager-card__body">
                            <div class="manager-detail-list">
                                <div class="manager-detail-item">
                                    <span>Location</span>
                                    <span>{{ $manager['location'] }}</span>
                                </div>
                                <div class="manager-detail-item">
                                    <span>Address</span>
                                    <span>{{ $manager['address'] }}</span>
                                </div>
                                <div class="manager-detail-item">
                                    <span>Date of Birth</span>
                                    <span>{{ $dobFormatted }}</span>
                                </div>
                                <div class="manager-detail-item">
                                    <span>Gender</span>
                                    <span>{{ $manager['gender'] }}</span>
                                </div>
                                <div class="manager-detail-item">
                                    <span>Assign Date</span>
                                    <span>{{ $assignDateFormatted }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="manager-card__footer">
                            <div class="manager-contact">
                                <a href="tel:{{ $phoneRaw }}">
                                    <i class="ri-phone-line" aria-hidden="true"></i>
                                    {{ $manager['phone'] }}
                                </a>
                                <a href="mailto:{{ $manager['email'] }}">
                                    <i class="ri-mail-line" aria-hidden="true"></i>
                                    {{ $manager['email'] }}
                                </a>
                            </div>
                            <div class="manager-card__actions">
                                <button class="manager-icon-button" type="button" data-open-modal="manager-view">
                                    <i class="ri-eye-line" aria-hidden="true"></i>
                                    <span class="sr-only">View {{ $manager['name'] }}</span>
                                </button>
                                <button class="manager-icon-button" type="button" data-open-modal="manager-edit">
                                    <i class="ri-pencil-line" aria-hidden="true"></i>
                                    <span class="sr-only">Edit {{ $manager['name'] }}</span>
                                </button>
                                <button class="manager-icon-button" type="button" data-open-modal="manager-sms">
                                    <i class="ri-chat-3-line" aria-hidden="true"></i>
                                    <span class="sr-only">Send SMS to {{ $manager['name'] }}</span>
                                </button>
                                <button class="manager-icon-button is-danger" type="button" data-open-modal="manager-terminate">
                                    <i class="ri-user-unfollow-line" aria-hidden="true"></i>
                                    <span class="sr-only">Terminate {{ $manager['name'] }}</span>
                                </button>
                                <button class="manager-icon-button is-danger" type="button" data-open-modal="manager-delete">
                                    <i class="ri-delete-bin-line" aria-hidden="true"></i>
                                    <span class="sr-only">Delete {{ $manager['name'] }}</span>
                                </button>
                            </div>
                        </div>
                    </article>
                @endforeach
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
                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="manager-modal__body">
                        <div class="manager-form-grid">
                            <div class="manager-form-group">
                                <label for="manager-avatar">Thumbnail Image *</label>
                                <input class="manager-input" type="file" id="manager-avatar" name="avatar" accept="image/*" required>
                            </div>

                            <div class="manager-form-group">
                                <label for="manager-name">Full Name *</label>
                                <input class="manager-input" type="text" id="manager-name" name="name" placeholder="Enter full name" required>
                            </div>

                            <div class="manager-form-group">
                                <label for="manager-gender">Gender *</label>
                                <select class="manager-select" id="manager-gender" name="gender" required>
                                    <option value="">Select gender</option>
                                    <option value="Female">Female</option>
                                    <option value="Male">Male</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div class="manager-form-group">
                                <label for="manager-dob">Date of Birth *</label>
                                <input class="manager-input" type="date" id="manager-dob" name="dob" required>
                            </div>

                            <div class="manager-form-group">
                                <label for="manager-phone">Contact Number *</label>
                                <input class="manager-input" type="tel" id="manager-phone" name="phone" placeholder="+233 XX XXX XXXX" required>
                            </div>

                            <div class="manager-form-group">
                                <label for="manager-email">Email Address *</label>
                                <input class="manager-input" type="email" id="manager-email" name="email" placeholder="name@fuelmgt.com" required>
                            </div>

                            <div class="manager-form-group">
                                <label for="manager-station">Assign to Station *</label>
                                <select class="manager-select" id="manager-station" name="station" required>
                                    <option value="">Select station</option>
                                    @foreach ($stationOptions as $stationOption)
                                        <option value="{{ $stationOption }}">{{ $stationOption }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="manager-form-group">
                                <label for="manager-assign-date">Assign Date *</label>
                                <input class="manager-input" type="date" id="manager-assign-date" name="assign_date" required>
                            </div>

                            <div class="manager-form-group" style="grid-column: span 2;">
                                <label for="manager-address">Residential Address *</label>
                                <textarea class="manager-textarea" id="manager-address" name="address" placeholder="Enter complete residential address" required></textarea>
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
                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="manager_id" data-modal-field="edit-id">
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
                                <select class="manager-select" id="edit-manager-station" name="station" required data-modal-field="edit-station">
                                    @foreach ($stationOptions as $stationOption)
                                        <option value="{{ $stationOption }}">{{ $stationOption }}</option>
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
                <form action="" method="POST">
                    @csrf
                    <input type="hidden" name="manager_id" data-modal-field="sms-id">
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
                <form action="" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="manager_id" data-modal-field="terminate-id">
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
                <form action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="manager_id" data-modal-field="delete-id">
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modalBackdrops = Array.from(document.querySelectorAll('[data-modal]'));
            const openModalButtons = Array.from(document.querySelectorAll('[data-open-modal]'));
            const closeModalButtons = Array.from(document.querySelectorAll('[data-close-modal]'));

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
                    modal.querySelector('form')?.reset();
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
                    const setValue = (field, value = '') => {
                        const el = modal.querySelector(`[data-modal-field="${field}"]`);
                        if (!el) return;
                        el.value = value || '';
                    };

                    setValue('edit-id', dataset.managerId);
                    setValue('edit-name', dataset.managerName);
                    setValue('edit-gender', dataset.managerGender);
                    setValue('edit-dob', dataset.managerDobRaw);
                    setValue('edit-phone', dataset.managerPhone);
                    setValue('edit-email', dataset.managerEmail);
                    setValue('edit-station', dataset.managerStation);
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

                    if (idField) idField.value = dataset.managerId || '';
                    if (nameField) nameField.textContent = dataset.managerName || '—';
                    if (stationField) stationField.textContent = dataset.managerStation || '—';
                    if (emailField) emailField.textContent = dataset.managerEmail || '—';
                    if (confirmInput) confirmInput.value = '';
                }
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
