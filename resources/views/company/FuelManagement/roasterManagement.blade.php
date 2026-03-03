@extends('layouts.vertical', [
    'page_title' => 'Roster Management',
    'mode' => session('theme_mode', 'light'),
])

@section('css')
    <style>
        :root {
            --rm-primary: #0b56c4;
            --rm-primary-dark: #053377;
            --rm-surface: #ffffff;
            --rm-muted: #6c7a96;
            --rm-border: rgba(17, 24, 39, 0.08);
            --rm-soft: #f1f5ff;
        }

        .roster-dashboard {
            min-height: calc(100vh - 120px);
            background: linear-gradient(180deg, rgba(241, 245, 255, 0.6) 0%, rgba(255, 255, 255, 0.92) 100%);
            padding: 3rem 1.6rem 4rem;
            font-family: "Inter", "Segoe UI", sans-serif;
        }

        .roster-hero {
            width: min(1180px, 96vw);
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            align-items: stretch;
            gap: 1.8rem;
            padding: 2.8rem 2.8rem;
            border-radius: 28px;
            background:
                radial-gradient(circle at 12% 18%, rgba(255, 255, 255, 0.22), transparent 65%),
                linear-gradient(135deg, rgba(11, 86, 196, 0.94), rgba(5, 51, 119, 0.88));
            box-shadow: 0 34px 68px rgba(11, 28, 70, 0.28);
            color: #ffffff;
            position: relative;
            overflow: hidden;
        }

        .roster-hero::after {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 78% 30%, rgba(93, 180, 255, 0.28), transparent 45%);
            opacity: 0.9;
        }

        .roster-hero > * {
            position: relative;
            z-index: 2;
        }

        .roster-hero__meta {
            text-transform: uppercase;
            font-size: 0.72rem;
            letter-spacing: 0.24em;
            color: rgba(255, 255, 255, 0.75);
        }

        .roster-hero h1 {
            margin: 0.75rem 0 0.4rem;
            font-size: clamp(2.2rem, 3vw, 3rem);
            letter-spacing: -0.02em;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .roster-hero__stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 1rem;
        }

        .roster-hero__cta-row {
            margin-top: 1.3rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.85rem;
        }

        .roster-hero__cta-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.85rem 1.75rem;
            border-radius: 999px;
            border: none;
            background: #ffffff;
            color: var(--rm-primary-dark);
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            cursor: pointer;
            box-shadow: 0 18px 32px rgba(255, 255, 255, 0.22);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .roster-hero__cta-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 22px 42px rgba(255, 255, 255, 0.32);
        }

        .roster-hero__cta-link {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            color: rgba(255, 255, 255, 0.82);
            font-weight: 600;
            text-decoration: none;
        }

        .roster-hero__cta-link:hover {
            color: #ffffff;
        }

        .roster-stat-card {
            background: rgba(255, 255, 255, 0.16);
            border-radius: 18px;
            padding: 1.25rem 1.45rem;
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            position: relative;
            overflow: hidden;
        }

        .roster-stat-card::after {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 24% 24%, rgba(255, 255, 255, 0.24), transparent 55%);
            opacity: 0.8;
        }

        .roster-stat-card > * {
            position: relative;
            z-index: 2;
        }

        .roster-stat-card span {
            display: block;
        }

        .roster-stat-card__label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.22em;
            color: rgba(255, 255, 255, 0.72);
            margin-bottom: 0.35rem;
        }

        .roster-stat-card__value {
            font-size: 1.92rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .roster-stat-card__icon {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.16);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .roster-controls {
            width: min(1180px, 96vw);
            margin: 2rem auto 0;
            display: flex;
            flex-wrap: wrap;
            gap: 1.2rem;
            align-items: stretch;
        }

        .roster-control-panel {
            flex: 1 1 280px;
            background: var(--rm-surface);
            border-radius: 18px;
            border: 1px solid rgba(11, 86, 196, 0.12);
            box-shadow: 0 14px 32px rgba(15, 23, 42, 0.1);
            padding: 1.6rem 1.8rem;
        }

        .roster-control-panel__title {
            font-size: 0.88rem;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            color: var(--rm-muted);
            margin-bottom: 1.1rem;
        }

        .roster-field + .roster-field {
            margin-top: 1.1rem;
        }

        .roster-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: #102347;
            margin-bottom: 0.35rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .roster-label small {
            font-weight: 500;
            color: var(--rm-muted);
        }

        .roster-select,
        .roster-input {
            width: 100%;
            border-radius: 12px;
            border: 1px solid var(--rm-border);
            padding: 0.75rem 1rem;
            font-size: 0.92rem;
            background: #ffffff;
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
        }

        .roster-select:focus,
        .roster-input:focus {
            outline: none;
            border-color: var(--rm-primary);
            box-shadow: 0 0 0 3px rgba(11, 86, 196, 0.2);
        }

        .roster-toggle-group {
            display: flex;
            gap: 0.65rem;
            flex-wrap: wrap;
        }

        .roster-toggle {
            flex: 1 1 120px;
            padding: 0.65rem 1rem;
            border-radius: 12px;
            border: 1px solid rgba(11, 86, 196, 0.18);
            background: linear-gradient(135deg, rgba(11, 86, 196, 0.08), rgba(5, 51, 119, 0.04));
            color: var(--rm-primary-dark);
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .roster-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 22px rgba(11, 86, 196, 0.18);
        }

        .roster-toggle.is-active {
            background: linear-gradient(135deg, var(--rm-primary), var(--rm-primary-dark));
            color: #ffffff;
            box-shadow: 0 16px 32px rgba(11, 86, 196, 0.26);
        }

        .roster-auto-assign {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            flex: 1 1 260px;
            background: linear-gradient(135deg, rgba(11, 86, 196, 0.92), rgba(5, 51, 119, 0.88));
            border-radius: 18px;
            color: #ffffff;
            padding: 1.8rem 2rem;
            box-shadow: 0 20px 42px rgba(15, 32, 70, 0.22);
            position: relative;
            overflow: hidden;
        }

        .roster-auto-assign::after {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 18% 24%, rgba(255, 255, 255, 0.24), transparent 55%),
                radial-gradient(circle at 80% 72%, rgba(255, 255, 255, 0.18), transparent 40%);
            opacity: 0.9;
        }

        .roster-auto-assign > * {
            position: relative;
            z-index: 2;
        }

        .roster-auto-assign button {
            align-self: flex-start;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            border-radius: 12px;
            border: none;
            padding: 0.85rem 1.8rem;
            background: #ffffff;
            color: var(--rm-primary-dark);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            cursor: pointer;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .roster-auto-assign button:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 28px rgba(255, 255, 255, 0.32);
        }

        .roster-auto-assign p {
            margin: 0;
            color: rgba(255, 255, 255, 0.8);
        }

        .roster-grid {
            width: min(1180px, 96vw);
            margin: 2.6rem auto 0;
            background: var(--rm-surface);
            border-radius: 20px;
            border: 1px solid rgba(11, 86, 196, 0.12);
            box-shadow: 0 22px 44px rgba(15, 23, 42, 0.12);
            overflow: hidden;
        }

        .roster-grid__header {
            padding: 1.4rem 2rem;
            border-bottom: 1px solid rgba(11, 86, 196, 0.12);
            display: flex;
            flex-wrap: wrap;
            gap: 0.9rem;
            justify-content: space-between;
            align-items: center;
        }

        .roster-grid__legend {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .roster-legend-item {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.4rem 0.75rem;
            border-radius: 999px;
            border: 1px solid rgba(11, 86, 196, 0.12);
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--rm-muted);
        }

        .roster-legend-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .roster-shift-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 600;
            border: 1px solid transparent;
        }

        .roster-shift-chip--morning {
            background: rgba(39, 174, 96, 0.12);
            color: #117a48;
            border-color: rgba(39, 174, 96, 0.18);
        }

        .roster-shift-chip--evening {
            background: rgba(255, 159, 67, 0.14);
            color: #ad5508;
            border-color: rgba(255, 159, 67, 0.22);
        }

        .roster-shift-chip--off {
            background: rgba(108, 122, 150, 0.12);
            color: #303b52;
            border-color: rgba(108, 122, 150, 0.2);
        }

        .roster-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .roster-table thead th {
            font-size: 0.74rem;
            text-transform: uppercase;
            letter-spacing: 0.16em;
            color: var(--rm-muted);
            padding: 0.95rem 1rem;
            background: var(--rm-soft);
            border-bottom: 1px solid rgba(11, 86, 196, 0.08);
        }

        .roster-table thead th:first-child {
            border-top-left-radius: 20px;
        }

        .roster-table thead th:last-child {
            border-top-right-radius: 20px;
        }

        .roster-table tbody td {
            padding: 1.1rem 1rem;
            border-bottom: 1px solid rgba(11, 86, 196, 0.08);
            text-align: center;
            vertical-align: middle;
        }

        .roster-table tbody tr:last-child td {
            border-bottom: none;
        }

        .roster-table tbody td:first-child {
            text-align: left;
            font-weight: 600;
            color: #102347;
        }

        .roster-table tbody tr:hover td {
            background: rgba(11, 86, 196, 0.035);
        }

        .roster-attendant-avatar {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(11, 86, 196, 0.18), rgba(5, 51, 119, 0.32));
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.08em;
        }

        .roster-assignment-actions {
            display: inline-flex;
            gap: 0.4rem;
            align-items: center;
            justify-content: center;
        }

        .roster-assignment-btn {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            border: 1px solid rgba(11, 86, 196, 0.18);
            background: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--rm-primary-dark);
            cursor: pointer;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .roster-assignment-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 22px rgba(11, 86, 196, 0.15);
        }

        .roster-week-controls {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .roster-week-btn {
            width: 36px;
            height: 36px;
            border-radius: 999px;
            border: 1px solid rgba(11, 86, 196, 0.18);
            background: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--rm-primary-dark);
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .roster-week-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(11, 86, 196, 0.16);
        }

        @media (max-width: 992px) {
            .roster-dashboard {
                padding: 2.6rem 1.2rem 3rem;
            }

            .roster-grid__header {
                padding: 1.2rem 1.4rem;
            }

            .roster-controls {
                flex-direction: column;
            }

            .roster-grid {
                border-radius: 18px;
            }
        }

        @media (max-width: 768px) {
            .roster-hero {
                padding: 2.2rem 2rem;
                text-align: center;
            }

            .roster-hero__stats {
                order: -1;
            }

            .roster-grid__legend {
                width: 100%;
                justify-content: center;
            }

            .roster-table thead {
                display: none;
            }

            .roster-table tbody tr {
                display: grid;
                grid-template-columns: 1fr;
                gap: 0.75rem;
                padding: 1rem 1.2rem;
            }

            .roster-table tbody td {
                text-align: left;
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 1.2rem;
                border-bottom: none;
                padding: 0.35rem 0;
            }

            .roster-table tbody td::before {
                content: attr(data-heading);
                font-size: 0.72rem;
                text-transform: uppercase;
                letter-spacing: 0.16em;
                color: var(--rm-muted);
            }

            .roster-table tbody td:first-child::before {
                content: '';
            }

            .roster-assignment-actions {
                justify-content: flex-end;
            }
        }

        @media (max-width: 576px) {
            .roster-dashboard {
                padding: 2.3rem 1rem 2.6rem;
            }

            .roster-auto-assign {
                padding: 1.6rem;
            }

            .roster-grid {
                border-radius: 16px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="roster-dashboard">
        <div class="roster-hero">
            <div>
                <span class="roster-hero__meta">Operations schedule</span>
                <h1>
                    <span class="roster-stat-card__icon"><i class="ri-calendar-check-line"></i></span>
                    Weekly Shift Roster
                </h1>
                <p class="mb-2">Keep morning and evening coverage balanced across every forecourt while guaranteeing a well-earned off
                    day for each attendant.</p>
                <div class="roster-hero__cta-row">
                    <button type="button" class="roster-hero__cta-btn" data-bs-toggle="modal"
                        data-bs-target="#previewRosterModal">
                        <i class="ri-play-circle-line"></i>
                        Preview roster
                    </button>
                    <button type="button" class="btn btn-link p-0 roster-hero__cta-link" data-bs-toggle="modal"
                        data-bs-target="#autoAssignModal">
                        <i class="ri-lightbulb-flash-line"></i>
                        Auto assign in one click
                    </button>
                </div>
            </div>
            <div class="roster-hero__stats">
                <div class="roster-stat-card">
                    <span class="roster-stat-card__label">Attendants scheduled</span>
                    <span class="roster-stat-card__value">
                        <span class="roster-stat-card__icon"><i class="ri-team-line"></i></span>
                        {{ $scheduledAttendants ?? 0 }}
                    </span>
                    <span class="small">Across {{ ($stations ?? collect())->count() }} station{{ ($stations ?? collect())->count() !== 1 ? 's' : '' }}</span>
                </div>
                <div class="roster-stat-card">
                    <span class="roster-stat-card__label">Coverage score</span>
                    <span class="roster-stat-card__value text-success">
                        <span class="roster-stat-card__icon"><i class="ri-shield-star-line"></i></span>
                        {{ $coverageScore ?? 0 }}%
                    </span>
                    <span class="small">Morning & evening filled</span>
                </div>
                <div class="roster-stat-card">
                    <span class="roster-stat-card__label">Off days honored</span>
                    <span class="roster-stat-card__value">
                        <span class="roster-stat-card__icon"><i class="ri-hotel-bed-line"></i></span>
                        100%
                    </span>
                    <span class="small">Within current rotation</span>
                </div>
            </div>
        </div>

        <div class="roster-controls">
            <div class="roster-control-panel">
                <div class="roster-control-panel__title">Station scope</div>
                <div class="roster-field">
                    <label class="roster-label" for="rosterStation">Station <small>Select to filter attendants</small></label>
                    <div class="position-relative">
                        <select class="roster-select pe-5" id="rosterStation" name="station_id">
                            <option value="">All Stations</option>
                            @foreach (($stations ?? collect()) as $station)
                                <option value="{{ $station->id }}" {{ $stationId == $station->id ? 'selected' : '' }}>
                                    {{ $station->name }} ({{ $station->code }})
                                </option>
                            @endforeach
                        </select>
                        <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted">
                            <i class="ri-building-line"></i>
                        </span>
                    </div>
                    <small class="text-muted d-block mt-2">Select a station to view and manage attendants for that location only.</small>
                </div>
                <div class="roster-field">
                    <label class="roster-label" for="rosterWeek">Week of rotation <small>ISO week</small></label>
                    <input type="week" class="roster-input" id="rosterWeek" value="{{ $weekStartDate ?? now()->startOfWeek()->format('Y-m-d') }}">
                </div>
                <div class="roster-field">
                    <span class="roster-label">Publish settings</span>
                    <div class="roster-toggle-group">
                        <span class="roster-toggle is-active">Draft view</span>
                        <span class="roster-toggle">Publish roster</span>
                    </div>
                </div>
            </div>

            <div class="roster-control-panel">
                <div class="roster-control-panel__title">Shift templates</div>
                <div class="roster-field">
                    <label class="roster-label" for="rosterTemplate">Rotation pattern</label>
                    <select class="roster-select" id="rosterTemplate">
                        <option value="balanced">Balanced (Morning/Evening)</option>
                        <option value="frontload">Morning focus</option>
                        <option value="evening">Evening heavy</option>
                        <option value="custom">Custom</option>
                    </select>
                </div>
                <div class="roster-field">
                    <span class="roster-label">Daily shift options</span>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted small">Morning shift</span>
                            <span class="roster-shift-chip roster-shift-chip--morning">06:00 - 14:00</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted small">Evening shift</span>
                            <span class="roster-shift-chip roster-shift-chip--evening">14:00 - 22:00</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted small">Off day</span>
                            <span class="roster-shift-chip roster-shift-chip--off">Rest</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="roster-auto-assign">
                <h5 class="mb-1">Smart auto-assign</h5>
                <p>Let the system distribute attendants across shifts, honoring mandated rest days and preventing double
                    placements.</p>
                <button type="button" data-bs-toggle="modal" data-bs-target="#autoAssignModal">
                    <i class="ri-magic-line"></i>
                    Auto assign roster
                </button>
                <p class="small mb-0">Uses past 3 weeks of attendance trends and coverage rules to balance workloads.</p>
            </div>
        </div>

        <div class="roster-grid">
            <div class="roster-grid__header">
                <div class="roster-week-controls">
                    <button type="button" class="roster-week-btn" aria-label="Previous week">
                        <i class="ri-arrow-left-s-line"></i>
                    </button>
                    <div>
                        <span class="text-muted small text-uppercase">Week</span>
                        <div class="fw-semibold">18 - 24 March 2025</div>
                    </div>
                    <button type="button" class="roster-week-btn" aria-label="Next week">
                        <i class="ri-arrow-right-s-line"></i>
                    </button>
                </div>

                <div class="roster-grid__legend">
                    <span class="roster-legend-item">
                        <span class="roster-legend-dot" style="background: rgba(39, 174, 96, 0.9);"></span>
                        Morning shift
                    </span>
                    <span class="roster-legend-item">
                        <span class="roster-legend-dot" style="background: rgba(255, 159, 67, 0.9);"></span>
                        Evening shift
                    </span>
                    <span class="roster-legend-item">
                        <span class="roster-legend-dot" style="background: rgba(108, 122, 150, 0.9);"></span>
                        Off day
                    </span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="roster-table">
                    <thead>
                        <tr>
                            <th>Attendant</th>
                            <th>Monday</th>
                            <th>Tuesday</th>
                            <th>Wednesday</th>
                            <th>Thursday</th>
                            <th>Friday</th>
                            <th>Saturday</th>
                            <th>Sunday</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $dayLabels = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

                            // Build roster data from controller
                            $rosterData = [];
                            foreach (($attendants ?? collect()) as $attendant) {
                                $initials = strtoupper(substr($attendant->first_name ?? '', 0, 1) . substr($attendant->other_names ?? '', 0, 1));
                                $name = trim(($attendant->first_name ?? '') . ' ' . ($attendant->other_names ?? ''));

                                // Get assignments for this attendant for the week
                                $assignments = [];
                                $attendantRosters = ($rostersByAttendant ?? collect())->get($attendant->id, collect());

                                for ($day = 1; $day <= 7; $day++) {
                                    $roster = $attendantRosters->firstWhere('day_of_week', $day);
                                    $assignments[] = $roster->shift_type ?? 'off';
                                }

                                $rosterData[] = [
                                    'initials' => $initials ?: 'NA',
                                    'name' => $name ?: 'Unknown',
                                    'assignments' => $assignments,
                                    'attendant' => $attendant,
                                ];
                            }
                        @endphp

                        @if (empty($rosterData))
                            <tr>
                                <td colspan="7">
                                    <div class="text-center text-muted py-4">
                                        <i class="ri-calendar-line d-block fs-3 mb-2"></i>
                                        No roster data available. Use the "Auto assign" button to create a schedule.
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach ($rosterData as $row)
                                <tr>
                                    <td data-heading="Attendant">
                                        <div class="d-flex align-items-center">
                                            <span class="roster-attendant-avatar">{{ $row['initials'] }}</span>
                                            <div>
                                                <div>{{ $row['name'] }}</div>
                                                <span class="text-muted small">{{ $row['attendant']->station->name ?? 'Unknown Station' }} · Active</span>
                                            </div>
                                        </div>
                                    </td>
                                    @foreach ($row['assignments'] as $index => $assignment)
                                        @php
                                            $chipClasses = [
                                                'morning' => 'roster-shift-chip roster-shift-chip--morning',
                                                'evening' => 'roster-shift-chip roster-shift-chip--evening',
                                                'off' => 'roster-shift-chip roster-shift-chip--off',
                                            ];
                                            $chipIcons = [
                                                'morning' => 'ri-sun-foggy-line',
                                                'evening' => 'ri-moon-clear-line',
                                                'off' => 'ri-hotel-bed-line',
                                            ];
                                            $chipLabels = [
                                                'morning' => 'Morning',
                                                'evening' => 'Evening',
                                                'off' => 'Off day',
                                            ];
                                            $chipClass = $chipClasses[$assignment] ?? $chipClasses['morning'];
                                        @endphp
                                        <td data-heading="{{ $dayLabels[$index] }}">
                                            <div class="d-flex flex-column align-items-center gap-2">
                                                <span class="{{ $chipClass }}">
                                                    <i class="{{ $chipIcons[$assignment] ?? 'ri-sun-foggy-line' }}"></i>
                                                    {{ $chipLabels[$assignment] ?? 'Morning' }}
                                                </span>
                                                <div class="roster-assignment-actions">
                                                    <button type="button" class="roster-assignment-btn" aria-label="Swap shift"
                                                        data-bs-toggle="modal" data-bs-target="#swapShiftModal">
                                                        <i class="ri-swap-line"></i>
                                                    </button>
                                                    <button type="button" class="roster-assignment-btn" aria-label="Mark off day"
                                                        data-bs-toggle="modal" data-bs-target="#assignOffDayModal">
                                                        <i class="ri-hotel-bed-line"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="previewRosterModal" tabindex="-1" aria-labelledby="previewRosterModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="previewRosterModalLabel">Roster preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Preview a fully formatted schedule before publishing to attendants. This view mirrors what
                        will be available in the attendants portal and email summaries.</p>
                    <div class="table-responsive rounded-4 border">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Attendant</th>
                                    <th>Morning shifts</th>
                                    <th>Evening shifts</th>
                                    <th>Off days</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Patricia Mensima</td>
                                    <td>3</td>
                                    <td>3</td>
                                    <td>1</td>
                                </tr>
                                <tr>
                                    <td>Issah Yakubu</td>
                                    <td>3</td>
                                    <td>3</td>
                                    <td>1</td>
                                </tr>
                                <tr>
                                    <td>Regina Fuseini</td>
                                    <td>3</td>
                                    <td>3</td>
                                    <td>1</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Export PDF</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="autoAssignModal" tabindex="-1" aria-labelledby="autoAssignModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="autoAssignModalLabel">Auto assign roster</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Confirm that you want the system to generate the week's roster for <strong>{{ $managerStation ?? 'Navrongo Main' }}</strong>.
                        All attendants will receive an even distribution across morning and evening shifts with their mandatory
                        off day.</p>
                    <ul class="list-unstyled small text-muted">
                        <li><i class="ri-check-line text-success me-1"></i> Honors rest-day policy</li>
                        <li><i class="ri-check-line text-success me-1"></i> Prevents double-booking shifts</li>
                        <li><i class="ri-check-line text-success me-1"></i> Considers recent leave requests</li>
                    </ul>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Run auto assign</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="swapShiftModal" tabindex="-1" aria-labelledby="swapShiftModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="swapShiftModalLabel">Swap shift</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Choose a new shift for <strong class="text-primary">Patricia Mensima</strong> on
                        <strong>Wednesday</strong>.</p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-success">Assign Morning (06:00 - 14:00)</button>
                        <button type="button" class="btn btn-outline-warning">Assign Evening (14:00 - 22:00)</button>
                        <button type="button" class="btn btn-outline-secondary">Mark as off day</button>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save change</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="assignOffDayModal" tabindex="-1" aria-labelledby="assignOffDayModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="assignOffDayModalLabel">Assign off day</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Confirm that <strong class="text-primary">Patricia Mensima</strong> should rest on
                        <strong>Friday</strong>. The system will reschedule her previous shift automatically.</p>
                    <div class="alert alert-info" role="alert">
                        <i class="ri-information-line me-1"></i> Off days adjust total hours and inform supervisors immediately.
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Confirm off day</button>
                </div>
            </div>
        </div>
    </div>

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

            // Handle station selection change
            const stationSelect = document.getElementById('rosterStation');
            if (stationSelect) {
                stationSelect.addEventListener('change', function() {
                    const url = new URL(window.location.href);
                    if (this.value) {
                        url.searchParams.set('station_id', this.value);
                    } else {
                        url.searchParams.delete('station_id');
                    }
                    window.location.href = url.toString();
                });
            }

            // Handle week navigation
            const weekInput = document.getElementById('rosterWeek');
            const prevWeekBtn = document.querySelector('.roster-week-btn[aria-label="Previous week"]');
            const nextWeekBtn = document.querySelector('.roster-week-btn[aria-label="Next week"]');

            if (weekInput) {
                weekInput.addEventListener('change', function() {
                    const url = new URL(window.location.href);
                    url.searchParams.set('week_start_date', this.value);
                    window.location.href = url.toString();
                });
            }

            if (prevWeekBtn) {
                prevWeekBtn.addEventListener('click', function() {
                    const currentWeek = weekInput.value || '{{ $weekStartDate ?? now()->startOfWeek()->format('Y-m-d') }}';
                    const date = new Date(currentWeek);
                    date.setDate(date.getDate() - 7);
                    const newWeek = date.toISOString().split('T')[0];
                    const url = new URL(window.location.href);
                    url.searchParams.set('week_start_date', newWeek);
                    window.location.href = url.toString();
                });
            }

            if (nextWeekBtn) {
                nextWeekBtn.addEventListener('click', function() {
                    const currentWeek = weekInput.value || '{{ $weekStartDate ?? now()->startOfWeek()->format('Y-m-d') }}';
                    const date = new Date(currentWeek);
                    date.setDate(date.getDate() + 7);
                    const newWeek = date.toISOString().split('T')[0];
                    const url = new URL(window.location.href);
                    url.searchParams.set('week_start_date', newWeek);
                    window.location.href = url.toString();
                });
            }
        });
    </script>
@endsection
