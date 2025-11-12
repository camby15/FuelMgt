@extends('layouts.vertical', [
    'page_title' => 'Fuel Dashboard',
    'mode' => $mode ?? '',
    'demo' => $demo ?? '',
])

@section('css')
    <style>
        :root {
            --dash-gradient-start: #031739;
            --dash-gradient-mid: #0a3a8a;
            --dash-gradient-end: #041a45;
            --dash-surface: #f6f8ff;
            --dash-panel-bg: #ffffff;
            --dash-text: #0a1d44;
            --dash-muted: rgba(9, 33, 78, 0.6);
            --dash-accent: #ff7a1a;
            --dash-accent-end: #ffb347;
            --dash-border-soft: rgba(12, 38, 96, 0.08);
            --dash-border-strong: rgba(12, 38, 96, 0.18);
            --dash-shadow-card: 0 26px 44px rgba(3, 26, 67, 0.34);
            --dash-shadow-panel: 0 18px 42px rgba(7, 32, 86, 0.14);
            --dash-shadow-button: 0 12px 22px rgba(255, 135, 54, 0.32);
        }

        .fuel-dashboard-card {
            background: linear-gradient(135deg, var(--dash-gradient-start) 0%, var(--dash-gradient-mid) 100%);
            padding: 1px;
            border-radius: 24px;
            box-shadow: var(--dash-shadow-card);
        }

        .fuel-dashboard {
            background: var(--dash-surface);
            border-radius: 23px;
            overflow: hidden;
            padding: 0 0 2.8rem;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            display: flex;
            flex-direction: column;
            color: var(--dash-text);
        }

        .fuel-header {
            background: linear-gradient(94deg, rgba(3, 23, 63, 0.96) 0%, rgba(10, 58, 138, 0.98) 55%, rgba(3, 23, 63, 0.96) 100%);
            padding: 2rem 2.6rem 1.8rem;
            color: #ffffff;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .fuel-header h2 {
            margin: 0;
            font-size: 1.65rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: inherit;
        }

        .fuel-header__date {
            font-size: 0.78rem;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: rgba(232, 241, 255, 0.78);
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .fuel-header__date strong {
            color: #ffffff;
        }

        .fuel-metrics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.4rem;
            padding: 2.2rem 2.6rem 0;
        }

        .fuel-metric {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
            padding: 1.55rem 1.6rem;
            border-radius: 22px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 20px 42px rgba(7, 37, 96, 0.24);
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .fuel-metric::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.35), transparent 60%);
            opacity: 0;
            transition: opacity 0.25s ease;
        }

        .fuel-metric:hover,
        .fuel-metric:focus-within {
            transform: translateY(-4px);
            box-shadow: 0 26px 52px rgba(7, 37, 96, 0.32);
            outline: none;
        }

        .fuel-metric:hover::after,
        .fuel-metric:focus-within::after {
            opacity: 1;
        }

        .fuel-metric__icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.18);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.45rem;
            backdrop-filter: blur(8px);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.24);
        }

        .fuel-metric__content {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            position: relative;
            z-index: 1;
        }

        .fuel-metric__label {
            font-size: 0.76rem;
            letter-spacing: 0.26em;
            text-transform: uppercase;
            display: inline-flex;
            gap: 0.45rem;
            align-items: center;
        }

        .fuel-metric__value {
            font-size: 1.82rem;
            font-weight: 700;
            letter-spacing: 0.05em;
        }

        .fuel-metric__meta {
            font-size: 0.78rem;
            letter-spacing: 0.08em;
            line-height: 1.5;
        }

        .fuel-metric--primary {
            background: linear-gradient(120deg, rgba(15, 60, 130, 0.95) 0%, rgba(48, 141, 255, 0.92) 58%, rgba(5, 30, 78, 0.9) 100%);
            color: #ffffff;
        }

        .fuel-metric--sunset {
            background: linear-gradient(118deg, rgba(255, 138, 92, 0.96) 0%, rgba(255, 79, 129, 0.92) 58%, rgba(133, 32, 69, 0.92) 100%);
            color: #ffffff;
        }

        .fuel-metric--teal {
            background: linear-gradient(118deg, rgba(43, 192, 167, 0.95) 0%, rgba(74, 217, 193, 0.9) 52%, rgba(16, 118, 105, 0.9) 100%);
            color: #063940;
        }

        .fuel-metric--emerald {
            background: linear-gradient(118deg, rgba(52, 211, 153, 0.95) 0%, rgba(5, 150, 105, 0.9) 52%, rgba(5, 82, 57, 0.92) 100%);
            color: #053222;
        }

        .fuel-metric--indigo {
            background: linear-gradient(118deg, rgba(76, 91, 212, 0.96) 0%, rgba(59, 63, 191, 0.92) 58%, rgba(25, 33, 123, 0.9) 100%);
            color: #f5f6ff;
        }

        .fuel-metric--magenta {
            background: linear-gradient(118deg, rgba(217, 70, 239, 0.95) 0%, rgba(147, 51, 234, 0.9) 52%, rgba(87, 22, 134, 0.9) 100%);
            color: #ffffff;
        }

        .fuel-metric--primary .fuel-metric__icon,
        .fuel-metric--sunset .fuel-metric__icon,
        .fuel-metric--teal .fuel-metric__icon,
        .fuel-metric--emerald .fuel-metric__icon,
        .fuel-metric--indigo .fuel-metric__icon,
        .fuel-metric--magenta .fuel-metric__icon {
            color: inherit;
        }

        .fuel-metric--primary .fuel-metric__label,
        .fuel-metric--sunset .fuel-metric__label,
        .fuel-metric--indigo .fuel-metric__label,
        .fuel-metric--magenta .fuel-metric__label {
            color: rgba(255, 255, 255, 0.78);
        }

        .fuel-metric--primary .fuel-metric__meta,
        .fuel-metric--sunset .fuel-metric__meta,
        .fuel-metric--indigo .fuel-metric__meta,
        .fuel-metric--magenta .fuel-metric__meta {
            color: rgba(255, 255, 255, 0.8);
        }

        .fuel-metric--teal .fuel-metric__label,
        .fuel-metric--emerald .fuel-metric__label {
            color: rgba(6, 57, 64, 0.72);
        }

        .fuel-metric--teal .fuel-metric__meta,
        .fuel-metric--emerald .fuel-metric__meta {
            color: rgba(6, 57, 64, 0.64);
        }

        .fuel-metric:focus-within .fuel-metric__label::after {
            content: '';
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .fuel-panels {
            padding: 0 2.6rem;
        }

        .fuel-panel {
            background: var(--dash-panel-bg);
            border-radius: 20px;
            padding: 1.9rem 2rem;
            box-shadow: var(--dash-shadow-panel);
            border: 1px solid var(--dash-border-soft);
        }

        .fuel-panel + .fuel-panel {
            margin-top: 1.6rem;
        }

        .fuel-panel--chart {
            position: relative;
            overflow: hidden;
            padding: 2rem 2.4rem 2.4rem;
            background: linear-gradient(135deg, rgba(58, 122, 242, 0.16), rgba(140, 92, 255, 0.12));
            border: 1px solid rgba(255, 255, 255, 0.32);
        }

        .fuel-panel--chart::before,
        .fuel-panel--chart::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            z-index: 0;
        }

        .fuel-panel--chart::before {
            width: 340px;
            height: 340px;
            background: radial-gradient(circle at center, rgba(58, 122, 242, 0.34), rgba(58, 122, 242, 0));
            top: -200px;
            right: -140px;
        }

        .fuel-panel--chart::after {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle at center, rgba(147, 51, 234, 0.22), rgba(147, 51, 234, 0));
            bottom: -160px;
            left: -120px;
        }

        .fuel-panel--chart > * {
            position: relative;
            z-index: 1;
        }

        .fuel-panel__title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.2rem;
            margin-bottom: 1.4rem;
            text-transform: uppercase;
            letter-spacing: 0.16em;
            font-size: 0.86rem;
            color: var(--dash-text);
        }

        .fuel-panel__title span:first-child {
            font-weight: 600;
        }

        .fuel-panel__meta {
            font-size: 0.74rem;
            letter-spacing: 0.12em;
            color: var(--dash-muted);
        }

        .fuel-panel__title-actions {
            display: inline-flex;
            align-items: center;
            gap: 0.85rem;
            flex-wrap: wrap;
        }

        .fuel-panel__action {
            border: none;
            background: linear-gradient(88deg, var(--dash-accent) 0%, var(--dash-accent-end) 100%);
            color: #0a1d44;
            padding: 0.45rem 1.05rem;
            border-radius: 999px;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            cursor: pointer;
            box-shadow: var(--dash-shadow-button);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .fuel-panel__action:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 26px rgba(255, 135, 54, 0.4);
        }

        .fuel-panel__action:focus-visible {
            outline: 2px solid rgba(255, 135, 54, 0.5);
            outline-offset: 2px;
        }

        .fuel-chart__insights {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.2rem;
            margin-bottom: 1.8rem;
        }

        .fuel-chart__pill {
            display: flex;
            align-items: flex-start;
            gap: 0.8rem;
            padding: 1.05rem 1.25rem;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.78);
            border: 1px solid rgba(213, 226, 255, 0.6);
            box-shadow: 0 22px 38px rgba(47, 109, 224, 0.14);
        }

        .fuel-chart__pill-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: rgba(58, 122, 242, 0.14);
            color: rgba(47, 109, 224, 0.92);
            font-size: 1.18rem;
        }

        .fuel-chart__pill--highlight .fuel-chart__pill-icon {
            background: rgba(217, 70, 239, 0.14);
            color: #d946ef;
        }

        .fuel-chart__pill--neutral .fuel-chart__pill-icon {
            background: rgba(34, 197, 94, 0.14);
            color: #16a34a;
        }

        .fuel-chart__pill-label {
            display: block;
            font-size: 0.72rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: var(--dash-muted);
        }

        .fuel-chart__pill-value {
            display: block;
            margin-top: 0.25rem;
            font-size: 1.12rem;
            font-weight: 600;
            color: var(--dash-text);
            letter-spacing: 0.03em;
        }

        .fuel-chart__pill-meta {
            display: block;
            margin-top: 0.3rem;
            font-size: 0.78rem;
            letter-spacing: 0.05em;
            color: rgba(9, 33, 78, 0.7);
        }

        .fuel-chart {
            position: relative;
            height: 380px;
            width: 100%;
            border-radius: 22px;
            overflow: hidden;
            border: 1px solid rgba(213, 226, 255, 0.65);
            background: linear-gradient(163deg, rgba(255, 255, 255, 0.94), rgba(228, 235, 255, 0.84));
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.45), 0 26px 42px rgba(41, 76, 158, 0.14);
            backdrop-filter: blur(6px);
        }

        .fuel-chart::before,
        .fuel-chart::after {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
        }

        .fuel-chart::before {
            background-image: radial-gradient(circle at 12% 18%, rgba(58, 122, 242, 0.22) 0, rgba(58, 122, 242, 0) 60%),
                radial-gradient(circle at 82% 12%, rgba(217, 70, 239, 0.18) 0, rgba(217, 70, 239, 0) 52%),
                radial-gradient(circle at 50% 92%, rgba(76, 91, 212, 0.22) 0, rgba(76, 91, 212, 0) 58%);
            opacity: 0.78;
        }

        .fuel-chart::after {
            background: radial-gradient(circle at 50% 0%, rgba(255, 255, 255, 0.26), transparent 58%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.18), rgba(255, 255, 255, 0));
            mix-blend-mode: screen;
            opacity: 0.65;
        }

        .fuel-chart canvas {
            position: absolute;
            inset: 0;
            display: block;
            width: 100% !important;
            height: 100% !important;
        }

        .fuel-tables {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.6rem;
            padding: 2.4rem 2.6rem 0;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .fuel-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.86rem;
            color: var(--dash-text);
        }

        .fuel-table thead {
            background: rgba(11, 46, 111, 0.88);
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            font-size: 0.74rem;
        }

        .fuel-table th,
        .fuel-table td {
            padding: 0.78rem 0.9rem;
            border-bottom: 1px solid rgba(16, 44, 98, 0.12);
            white-space: nowrap;
        }

        .fuel-table tbody tr:nth-child(odd) {
            background: #ffffff;
        }

        .fuel-table tbody tr:nth-child(even) {
            background: #f5f7ff;
        }

        .fuel-table tfoot td {
            font-weight: 600;
            background: rgba(11, 46, 111, 0.08);
        }

        .fuel-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 1rem;
            font-size: 0.75rem;
            color: var(--dash-muted);
        }

        .fuel-modal-open {
            overflow: hidden;
        }

        .fuel-modal {
            position: fixed;
            inset: 0;
            background: rgba(17, 33, 72, 0.55);
            backdrop-filter: blur(7px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
            z-index: 1500;
        }

        .fuel-modal.is-open {
            opacity: 1;
            visibility: visible;
        }

        .fuel-modal__dialog {
            background: var(--dash-panel-bg);
            border-radius: 20px;
            border: 1px solid var(--dash-border-soft);
            box-shadow: var(--dash-shadow-panel);
            max-width: 720px;
            width: 100%;
            padding: 2rem 2.2rem 2.4rem;
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 1.4rem;
        }

        .fuel-modal__close {
            position: absolute;
            top: 1.2rem;
            right: 1.2rem;
            border: none;
            background: rgba(47, 109, 224, 0.12);
            color: var(--dash-text);
            width: 38px;
            height: 38px;
            border-radius: 50%;
            font-size: 1.2rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s ease;
        }

        .fuel-modal__close:hover {
            background: rgba(47, 109, 224, 0.22);
        }

        .fuel-modal__header {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .fuel-modal__header h3 {
            margin: 0;
            font-size: 1.08rem;
            text-transform: uppercase;
            letter-spacing: 0.16em;
            color: var(--dash-text);
        }

        .fuel-modal__header p {
            margin: 0;
            font-size: 0.82rem;
            color: var(--dash-muted);
            letter-spacing: 0.05em;
        }

        .fuel-modal__body {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .fuel-modal__summary-row {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            font-size: 0.82rem;
            color: var(--dash-muted);
        }

        .fuel-modal__summary-row strong {
            color: var(--dash-text);
            letter-spacing: 0.08em;
        }

        .fuel-modal__table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.84rem;
            color: var(--dash-text);
        }

        .fuel-modal__table thead {
            background: rgba(11, 46, 111, 0.88);
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.16em;
            font-size: 0.72rem;
        }

        .fuel-modal__table th,
        .fuel-modal__table td {
            padding: 0.7rem 0.85rem;
            border-bottom: 1px solid rgba(16, 44, 98, 0.12);
            text-align: left;
        }

        .fuel-modal__table td.text-end {
            text-align: right;
        }

        .fuel-modal__table tbody tr:nth-child(odd) {
            background: #ffffff;
        }

        .fuel-modal__table tbody tr:nth-child(even) {
            background: #f5f7ff;
        }

        .fuel-modal__footer {
            display: flex;
            justify-content: flex-end;
        }

        .fuel-modal__footer button {
            border: none;
            background: rgba(47, 109, 224, 0.14);
            color: #2b6def;
            border-radius: 999px;
            padding: 0.48rem 1.1rem;
            font-size: 0.72rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            cursor: pointer;
        }

        .fuel-modal__footer button:hover {
            background: rgba(47, 109, 224, 0.22);
        }

        .visually-hidden {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            border: 0;
        }

        @media (max-width: 1200px) {
            .fuel-metrics {
                padding: 2rem 2rem 0;
            }

            .fuel-panels,
            .fuel-tables {
                    padding: 2rem 2rem 0;
            }
        }

        @media (max-width: 992px) {
            .fuel-header {
                padding: 1.8rem 2rem 1.6rem;
            }

            .fuel-metrics {
                padding: 1.8rem 1.6rem 0;
            }

            .fuel-panels,
            .fuel-tables {
                padding: 1.8rem 1.6rem 0;
            }

            .fuel-chart {
                height: 330px;
            }
        }

        @media (max-width: 768px) {
            .fuel-header {
                padding: 1.6rem 1.4rem;
            }

            .fuel-header h2 {
                font-size: 1.4rem;
            }

            .fuel-metrics,
            .fuel-panels,
            .fuel-tables {
                padding: 1.6rem 1.2rem 0;
            }

            .fuel-panel {
                padding: 1.6rem 1.4rem;
            }

            .fuel-panel__title {
                flex-direction: column;
                align-items: flex-start;
            }

            .fuel-panel__title-actions {
                width: 100%;
                justify-content: space-between;
            }
        }

        @media (max-width: 576px) {
            .fuel-dashboard-card {
                border-radius: 20px;
            }

            .fuel-dashboard {
                padding-bottom: 2.2rem;
            }

            .fuel-metric {
                padding: 1.35rem 1.4rem;
            }

            .fuel-panel__title-actions {
                flex-direction: column;
                align-items: flex-start;
            }

            .fuel-panel__action {
                width: 100%;
                text-align: center;
            }

            .fuel-modal__dialog {
                padding: 1.6rem 1.4rem;
            }

            .fuel-chart {
                height: 280px;
            }
        }
    </style>
@endsection

@section('content')
    @php
        $stations = collect([
            ['name' => 'Waiga', 'product' => 'PMS', 'sales' => 0.00, 'deductions' => 0.00, 'bank' => 0.00, 'stock' => 5200],
            ['name' => 'Wapuli', 'product' => 'AGO', 'sales' => 0.00, 'deductions' => 0.00, 'bank' => 0.00, 'stock' => 6100],
            ['name' => 'Paga Annex', 'product' => 'PMS', 'sales' => 21690.82, 'deductions' => 150.00, 'bank' => 18000.00, 'stock' => 8350],
            ['name' => 'Navrongo 2', 'product' => 'PMS', 'sales' => 1918.38, 'deductions' => 45.00, 'bank' => 1500.00, 'stock' => 9100],
            ['name' => 'Navrongo Main', 'product' => 'AGO', 'sales' => 0.00, 'deductions' => 0.00, 'bank' => 0.00, 'stock' => 5600],
            ['name' => 'Larabanga', 'product' => 'PMS', 'sales' => 0.00, 'deductions' => 0.00, 'bank' => 0.00, 'stock' => 5400],
            ['name' => 'Amoako', 'product' => 'AGO', 'sales' => 0.00, 'deductions' => 0.00, 'bank' => 0.00, 'stock' => 8000],
            ['name' => 'Pwalugu', 'product' => 'AGO', 'sales' => 3529.81, 'deductions' => 80.00, 'bank' => 2500.00, 'stock' => 7200],
            ['name' => 'Bamvin', 'product' => 'PMS', 'sales' => 0.00, 'deductions' => 0.00, 'bank' => 0.00, 'stock' => 6700],
            ['name' => 'Bububele', 'product' => 'PMS', 'sales' => 0.00, 'deductions' => 0.00, 'bank' => 0.00, 'stock' => 5800],
        ]);

        $reportDate = now()->format('d M, Y');
        $salesTotal = $stations->sum('sales');
        $deductionsTotal = $stations->sum('deductions');
        $bankTotal = $stations->sum('bank');
        $totalCash = 26609.01;
        $paymentsReceivedTotal = 0;

        $stationCount = $stations->count();
        $bankCoverage = $salesTotal ? round(($bankTotal / $salesTotal) * 100) : 0;
        $deductionShare = $salesTotal ? round(($deductionsTotal / $salesTotal) * 100, 1) : 0;
        $bankShortfall = max($salesTotal - $bankTotal, 0);

        $totalSalesLiters = 0;
        $totalAgoLiters = 0;
        $totalPmsLiters = 0;
        $totalVarianceLiters = 0;

        $summaryMetrics = collect([
            [
                'label' => 'Total Sales (L)',
                'value' => $totalSalesLiters,
                'unit' => 'L',
                'icon' => 'ri-fuel-station-line',
                'meta' => $stationCount . ' stations reporting',
                'variant' => 'primary',
            ],
            [
                'label' => 'Total AGO Sold (L)',
                'value' => $totalAgoLiters,
                'unit' => 'L',
                'icon' => 'ri-oil-line',
                'meta' => 'Diesel volume dispatched',
                'variant' => 'sunset',
            ],
            [
                'label' => 'Total PMS Sold (L)',
                'value' => $totalPmsLiters,
                'unit' => 'L',
                'icon' => 'ri-gas-station-line',
                'meta' => 'Premium volume dispatched',
                'variant' => 'emerald',
            ],
            [
                'label' => 'Total Variance',
                'value' => $totalVarianceLiters,
                'unit' => 'L',
                'icon' => 'ri-exchange-dollar-line',
                'meta' => 'Variance across all stations',
                'variant' => 'indigo',
            ],
            [
                'label' => 'Total Bank',
                'value' => $bankTotal,
                'unit' => 'GHS',
                'icon' => 'ri-bank-card-line',
                'meta' => 'Bank coverage ' . $bankCoverage . '%',
                'variant' => 'teal',
            ],
        ]);

        $stationsRankedBySales = $stations
            ->sortByDesc('sales')
            ->values();

        $stationSalesLabels = $stationsRankedBySales->pluck('name');
        $stationSalesValues = $stationsRankedBySales
            ->map(fn ($station) => round($station['sales'] ?? 0, 2));
        $stationSalesShares = $stationsRankedBySales->map(function ($station) use ($salesTotal) {
            if ($salesTotal <= 0) {
                return 0;
            }

            return round((($station['sales'] ?? 0) / $salesTotal) * 100, 2);
        });
        $topPerformingStation = $stationsRankedBySales->first();
        $topStationShare = $stationSalesShares->first() ?? 0;
        $averageStationSales = $stationCount > 0 ? round($salesTotal / $stationCount, 2) : 0;
        $stationsReporting = $stations->filter(fn ($station) => ($station['sales'] ?? 0) > 0)->count();
        $topVsAverage = $averageStationSales > 0
            ? round(($topPerformingStation['sales'] ?? 0) / $averageStationSales, 1)
            : 0;
    @endphp

    <div class="container-fluid">
        <div class="fuel-dashboard-card">
            <div class="fuel-dashboard">
                <div class="fuel-header">
                    <h2>Dashboard</h2>
                    <div class="fuel-header__date">
                        Report Date:
                        <strong>{{ $reportDate }}</strong>
                    </div>
                </div>

                <div class="fuel-metrics">
                    @foreach ($summaryMetrics as $metric)
                        <div class="fuel-metric fuel-metric--{{ $metric['variant'] }}">
                            <span class="fuel-metric__icon">
                                <i class="{{ $metric['icon'] }}"></i>
                            </span>
                            <div class="fuel-metric__content">
                                <span class="fuel-metric__label">{{ $metric['label'] }}</span>
                                <span class="fuel-metric__value">{{ number_format($metric['value'], 2) }}</span>
                                @if (!empty($metric['meta']))
                                    <span class="fuel-metric__meta">{{ $metric['meta'] }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="fuel-panels">
                    <div class="fuel-panel fuel-panel--chart">
                        <div class="fuel-panel__title">
                            <span>Sales By Station</span>
                            <div class="fuel-panel__title-actions">
                                <span class="fuel-panel__meta">Last updated {{ $reportDate }}</span>
                                <button class="fuel-panel__action" type="button" data-modal-open="stationSalesModal">View insight</button>
                            </div>
                        </div>

                        <div class="fuel-chart__insights">
                            <div class="fuel-chart__pill fuel-chart__pill--highlight">
                                <span class="fuel-chart__pill-icon">
                                    <i class="ri-brilliance-line" aria-hidden="true"></i>
                                </span>
                                <div>
                                    <span class="fuel-chart__pill-label">Top performer</span>
                                    <span class="fuel-chart__pill-value">
                                        {{ $topPerformingStation['name'] ?? 'No data' }}
                                    </span>
                                    <span class="fuel-chart__pill-meta">
                                        @if ($topPerformingStation)
                                            {{ number_format($topPerformingStation['sales'] ?? 0, 2) }} GHS · {{ number_format($topStationShare, 1) }}% share
                                        @else
                                            Awaiting station performance
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="fuel-chart__pill">
                                <span class="fuel-chart__pill-icon">
                                    <i class="ri-bar-chart-2-line" aria-hidden="true"></i>
                                </span>
                                <div>
                                    <span class="fuel-chart__pill-label">Average sales</span>
                                    <span class="fuel-chart__pill-value">{{ number_format($averageStationSales, 2) }} GHS</span>
                                    <span class="fuel-chart__pill-meta">
                                        Across {{ $stationCount }} stations · {{ $stationsReporting }} reporting
                                    </span>
                                </div>
                            </div>

                            <div class="fuel-chart__pill fuel-chart__pill--neutral">
                                <span class="fuel-chart__pill-icon">
                                    <i class="ri-line-chart-line" aria-hidden="true"></i>
                                </span>
                                <div>
                                    <span class="fuel-chart__pill-label">Performance gap</span>
                                    <span class="fuel-chart__pill-value">
                                        @if ($topPerformingStation)
                                            {{ number_format($topVsAverage, 1) }}×
                                        @else
                                            0×
                                        @endif
                                    </span>
                                    <span class="fuel-chart__pill-meta">
                                        @if ($topPerformingStation)
                                            Top station vs. fleet average
                                        @else
                                            Track once sales are reported
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="fuel-chart">
                            <canvas id="salesByStationChart" aria-label="Sales by station bar chart" role="img"></canvas>
                        </div>
                    </div>
                </div>

                <div class="fuel-tables">
                    <div class="fuel-panel table-responsive">
                        <div class="fuel-panel__title">
                            <span>Sales Summary</span>
                            <span class="fuel-panel__meta">All stations</span>
                        </div>
                        <table class="fuel-table">
                            <thead>
                                <tr>
                                    <th>Station</th>
                                    <th class="text-end">Total Sales (GHS)</th>
                                    <th class="text-end">Total Deductions (GHS)</th>
                                    <th class="text-end">Total Bank (GHS)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stations as $station)
                                    <tr>
                                        <td>{{ $station['name'] }}</td>
                                        <td class="text-end">{{ number_format($station['sales'], 2) }}</td>
                                        <td class="text-end">{{ number_format($station['deductions'], 2) }}</td>
                                        <td class="text-end">{{ number_format($station['bank'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>Total</td>
                                    <td class="text-end">{{ number_format($salesTotal, 2) }}</td>
                                    <td class="text-end">{{ number_format($deductionsTotal, 2) }}</td>
                                    <td class="text-end">{{ number_format($bankTotal, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="fuel-pagination">
                            <span>Page 1 of 1 ({{ $stations->count() }} items)</span>
                            <span>&bull;</span>
                            <span>Sorted by station name</span>
                        </div>
                    </div>

                    <div class="fuel-panel table-responsive">
                        <div class="fuel-panel__title">
                            <span>Sales By Station</span>
                            <span class="fuel-panel__meta">Stock snapshot</span>
                        </div>
                        <table class="fuel-table">
                            <thead>
                                <tr>
                                    <th>Station</th>
                                    <th>Product</th>
                                    <th class="text-end">Total Stock (L)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stations as $station)
                                    <tr>
                                        <td>{{ $station['name'] }}</td>
                                        <td>{{ strtoupper($station['product']) }}</td>
                                        <td class="text-end">{{ number_format($station['stock']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">Total Stock</td>
                                    <td class="text-end">{{ number_format($stations->sum('stock')) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="fuel-pagination">
                            <span>Page 1 of 1 ({{ $stations->count() }} items)</span>
                            <span>&bull;</span>
                            <span>Showing latest stock</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="fuel-modal" id="stationSalesModal" aria-hidden="true">
        <div class="fuel-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="stationSalesModalTitle">
            <button class="fuel-modal__close" type="button" data-modal-close>
                <span aria-hidden="true">&times;</span>
                <span class="visually-hidden">Close insight modal</span>
            </button>
            <div class="fuel-modal__header">
                <h3 id="stationSalesModalTitle">Station Sales Insight</h3>
                <p>A deeper look at gross sales, deductions, and banking across stations.</p>
            </div>
            <div class="fuel-modal__body">
                <div class="fuel-modal__summary-row">
                    <span>Total sales: <strong>GHS {{ number_format($salesTotal, 2) }}</strong></span>
                    <span>Total deductions: <strong>GHS {{ number_format($deductionsTotal, 2) }}</strong></span>
                    <span>Total banking: <strong>GHS {{ number_format($bankTotal, 2) }}</strong></span>
                </div>
                <div class="table-responsive">
                    <table class="fuel-modal__table">
                        <thead>
                            <tr>
                                <th>Station</th>
                                <th class="text-end">Sales (GHS)</th>
                                <th class="text-end">Deductions (GHS)</th>
                                <th class="text-end">Bank (GHS)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stations as $station)
                                <tr>
                                    <td>{{ $station['name'] }}</td>
                                    <td class="text-end">{{ number_format($station['sales'], 2) }}</td>
                                    <td class="text-end">{{ number_format($station['deductions'], 2) }}</td>
                                    <td class="text-end">{{ number_format($station['bank'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>Total</td>
                                <td class="text-end">{{ number_format($salesTotal, 2) }}</td>
                                <td class="text-end">{{ number_format($deductionsTotal, 2) }}</td>
                                <td class="text-end">{{ number_format($bankTotal, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="fuel-modal__footer">
                <button type="button" data-modal-close>Close</button>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
    <script defer src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const initializeSalesChart = () => {
            if (typeof Chart === 'undefined') {
                return;
            }

            const canvas = document.getElementById('salesByStationChart');
            if (!canvas || canvas.dataset.chartInitialized === 'true') {
                return;
            }

            const context = canvas.getContext('2d');
            if (!context) {
                console.error('Unable to access canvas context for sales chart');
                return;
            }

            const labels = @json($stationSalesLabels);
            const values = @json($stationSalesValues).map(Number);
            const shares = @json($stationSalesShares).map(Number);
            const topStationIndex = values.reduce((bestIndex, currentValue, currentIndex) => {
                if (currentValue === null || Number.isNaN(currentValue)) {
                    return bestIndex;
                }
                const bestValue = values[bestIndex] ?? Number.NEGATIVE_INFINITY;
                return currentValue > bestValue ? currentIndex : bestIndex;
            }, 0);
            const formatCurrency = value => new Intl.NumberFormat('en-GH', {
                style: 'currency',
                currency: 'GHS',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(value ?? 0);
            const formatShare = value => `${(value ?? 0).toFixed(1)}%`;

            const gradientCache = {
                bar: new Map(),
                barHover: new Map(),
                lineStroke: null,
                lineFill: null,
            };

            const getBarGradient = (ctx, cacheKey, isTop, palette) => {
                const { chart } = ctx;
                const { ctx: chartCtx, chartArea } = chart;
                if (!chartArea) {
                    return palette.fallback;
                }

                const cache = gradientCache[cacheKey];
                const key = `${chartArea.left}|${chartArea.right}|${chartArea.top}|${chartArea.bottom}|${isTop ? 't' : 'n'}`;
                if (cache.has(key)) {
                    return cache.get(key);
                }

                const gradient = chartCtx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                const stops = isTop ? palette.top : palette.default;
                stops.forEach(([offset, color]) => gradient.addColorStop(offset, color));

                cache.set(key, gradient);
                return gradient;
            };

            const getLineGradient = (cacheKey, colors) => {
                if (!gradientCache[cacheKey]) {
                    const gradient = context.createLinearGradient(0, 0, canvas.offsetWidth || canvas.width, 0);
                    colors.forEach(([offset, color]) => gradient.addColorStop(offset, color));
                    gradientCache[cacheKey] = gradient;
                }
                return gradientCache[cacheKey];
            };

            const getLineFillGradient = () => {
                if (!gradientCache.lineFill) {
                    const gradient = context.createLinearGradient(0, 0, 0, canvas.offsetHeight || canvas.height);
                    gradient.addColorStop(0, 'rgba(217, 70, 239, 0.24)');
                    gradient.addColorStop(1, 'rgba(217, 70, 239, 0.02)');
                    gradientCache.lineFill = gradient;
                }
                return gradientCache.lineFill;
            };

            const valueLabelPlugin = {
                id: 'valueLabelPlugin',
                afterDatasetsDraw(chart) {
                    const { ctx } = chart;
                    const dataset = chart.data.datasets[0];
                    const meta = chart.getDatasetMeta(0);
                    ctx.save();
                    meta.data.forEach((bar, index) => {
                        const rawValue = dataset.data[index];
                        if (rawValue === null || rawValue === undefined) {
                            return;
                        }
                        const { x, y } = bar.tooltipPosition();
                        const labelY = Math.max(chart.chartArea.top + 24, y - 14);
                        ctx.textAlign = 'center';

                        ctx.font = '600 12px "Inter", "Segoe UI", sans-serif';
                        ctx.fillStyle = index === topStationIndex ? '#15356f' : '#1f2f4d';
                        ctx.textBaseline = 'bottom';
                        ctx.fillText(formatCurrency(rawValue), x, labelY);

                        ctx.font = '500 11px "Inter", "Segoe UI", sans-serif';
                        ctx.fillStyle = index === topStationIndex ? '#8b9dd3' : '#6c7a93';
                        ctx.textBaseline = 'top';
                        ctx.fillText(`${formatShare(shares[index])} share`, x, labelY + 4);
                    });
                    ctx.restore();
                }
            };

            const hoverGuidelinePlugin = {
                id: 'hoverGuidelinePlugin',
                afterDatasetsDraw(chart) {
                    const { ctx, chartArea } = chart;
                    const active = chart.getActiveElements();
                    if (!active.length) {
                        return;
                    }
                    ctx.save();
                    const { x } = active[0].element.tooltipPosition();
                    ctx.beginPath();
                    ctx.moveTo(x, chartArea.top + 8);
                    ctx.lineTo(x, chartArea.bottom - 6);
                    ctx.strokeStyle = 'rgba(47, 109, 224, 0.38)';
                    ctx.lineWidth = 1.2;
                    ctx.setLineDash([6, 4]);
                    ctx.stroke();
                    ctx.restore();
                }
            };

            const topBarGlowPlugin = {
                id: 'topBarGlowPlugin',
                afterDatasetsDraw(chart, args, pluginOptions) {
                    const { ctx } = chart;
                    const meta = chart.getDatasetMeta(0);
                    const element = meta.data[topStationIndex];
                    if (!element) {
                        return;
                    }

                    const { x, y, base, width } = element.getProps(['x', 'y', 'base', 'width'], true);
                    const height = base - y;

                    ctx.save();
                    ctx.translate(0, 2);
                    ctx.fillStyle = 'rgba(255, 255, 255, 0.12)';
                    ctx.shadowColor = 'rgba(47, 109, 224, 0.45)';
                    ctx.shadowBlur = 24;
                    ctx.shadowOffsetY = 14;
                    ctx.beginPath();
                    ctx.roundRect(x - width / 2, y, width, height, 14);
                    ctx.fill();
                    ctx.restore();
                }
            };

            canvas.dataset.chartInitialized = 'true';

            new Chart(context, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        type: 'bar',
                        label: 'Sales (GHS)',
                        data: values,
                        backgroundColor: ctx => getBarGradient(ctx, 'bar', ctx.dataIndex === topStationIndex, {
                            fallback: 'rgba(47, 109, 224, 0.82)',
                            default: [
                                [0, 'rgba(47, 109, 224, 0.92)'],
                                [1, 'rgba(73, 155, 255, 0.55)'],
                            ],
                            top: [
                                [0, 'rgba(255, 198, 93, 0.92)'],
                                [0.45, 'rgba(255, 145, 77, 0.74)'],
                                [1, 'rgba(232, 79, 162, 0.52)'],
                            ],
                        }),
                        hoverBackgroundColor: ctx => getBarGradient(ctx, 'barHover', ctx.dataIndex === topStationIndex, {
                            fallback: 'rgba(37, 99, 235, 1)',
                            default: [
                                [0, 'rgba(37, 99, 235, 0.96)'],
                                [1, 'rgba(59, 130, 246, 0.72)'],
                            ],
                            top: [
                                [0, 'rgba(255, 205, 115, 0.96)'],
                                [0.36, 'rgba(255, 149, 95, 0.82)'],
                                [1, 'rgba(232, 79, 162, 0.62)'],
                            ],
                        }),
                        borderColor: ctx => ctx.dataIndex === topStationIndex ? 'rgba(255, 175, 95, 0.92)' : 'rgba(47, 109, 224, 0.85)',
                        borderWidth: 1.5,
                        borderRadius: { topLeft: 12, topRight: 12 },
                        borderSkipped: false,
                        maxBarThickness: 48,
                        barPercentage: 0.62,
                        categoryPercentage: 0.58,
                        clip: 0,
                        animation: {
                            duration: 900,
                            delay: ctx => ctx.dataIndex * 80,
                            easing: 'easeOutQuart',
                        }
                    }, {
                        type: 'line',
                        label: 'Sales Share (%)',
                        data: shares,
                        yAxisID: 'yPercent',
                        borderColor: ctx => getLineGradient('lineStroke', [
                            [0, 'rgba(217, 70, 239, 0.95)'],
                            [0.5, 'rgba(58, 122, 242, 0.9)'],
                            [1, 'rgba(67, 233, 123, 0.85)'],
                        ]),
                        backgroundColor: getLineFillGradient(),
                        borderWidth: 2,
                        tension: 0.35,
                        pointRadius: 4,
                        pointHoverRadius: 5,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: 'rgba(217, 70, 239, 0.85)',
                        pointHoverBackgroundColor: 'rgba(217, 70, 239, 1)',
                        pointHoverBorderColor: '#fff',
                        pointBorderWidth: 2,
                        fill: 'origin',
                        fill: false,
                        animation: {
                            duration: 1050,
                            delay: ctx => ctx.dataIndex * 90 + 200,
                            easing: 'easeOutCubic',
                        }
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: { top: 26, right: 18, bottom: 8, left: 18 }
                    },
                    animation: {
                        duration: 900,
                        easing: 'easeOutQuart'
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 16,
                                usePointStyle: true,
                                padding: 16,
                                font: { family: 'Inter', size: 12 },
                                color: '#1f2f4d',
                            },
                            align: 'end'
                        },
                        title: {
                            display: true,
                            text: 'Sales by Station',
                            align: 'start',
                            color: '#1f2f4d',
                            font: { family: 'Inter', size: 18, weight: '600' },
                            padding: { bottom: 12 }
                        },
                        subtitle: {
                            display: true,
                            text: 'Gross sales reported across active locations',
                            align: 'start',
                            color: '#6c7a93',
                            font: { family: 'Inter', size: 12 },
                            padding: { bottom: 16 }
                        },
                        tooltip: {
                            backgroundColor: '#1f2f4d',
                            displayColors: false,
                            titleFont: { size: 13, family: 'Inter' },
                            bodyFont: { size: 12, family: 'Inter' },
                            padding: 12,
                            cornerRadius: 10,
                            callbacks: {
                                beforeBody: ctx => {
                                    const index = ctx[0].dataIndex;
                                    return `Share: ${formatShare(shares[index])}`;
                                },
                                label: ctx => {
                                    if (ctx.dataset.yAxisID === 'yPercent') {
                                        return `${ctx.dataset.label}: ${formatShare(ctx.raw)}`;
                                    }
                                    return `${ctx.dataset.label}: ${formatCurrency(ctx.raw)}`;
                                },
                                title: ctx => ctx[0]?.label ?? '',
                                footer: ctx => {
                                    const index = ctx[0].dataIndex;
                                    return index === topStationIndex ? '★ Top performing station' : '';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Stations',
                                color: '#425073',
                                font: { family: 'Inter', size: 12, weight: '600' },
                                padding: { top: 12 }
                            },
                            ticks: {
                                color: '#1f2f4d',
                                font: { family: 'Inter', size: 12, weight: '500' },
                                maxRotation: 0,
                                autoSkipPadding: 10
                            },
                            grid: {
                                display: true,
                                drawBorder: false,
                                color: index => index.tick?.value === 0 ? 'rgba(58, 122, 242, 0.18)' : 'rgba(58, 122, 242, 0.08)',
                                lineWidth: index => index.tick?.value === 0 ? 1.1 : 0.6,
                                drawOnChartArea: false,
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Sales (GHS)',
                                color: '#425073',
                                font: { family: 'Inter', size: 12, weight: '600' },
                                padding: { bottom: 6 }
                            },
                            ticks: {
                                color: '#6c7a93',
                                font: { family: 'Inter', size: 11 },
                                padding: 8,
                                callback: value => formatCurrency(value)
                            },
                            grid: {
                                color: context => context.index === 0
                                    ? 'rgba(58, 122, 242, 0.22)'
                                    : 'rgba(58, 122, 242, 0.08)',
                                drawBorder: false,
                                drawTicks: false
                            },
                            beginAtZero: true
                        },
                        yPercent: {
                            position: 'right',
                            beginAtZero: true,
                            suggestedMax: 100,
                            title: {
                                display: true,
                                text: 'Sales Share (%)',
                                color: '#af3ad9',
                                font: { family: 'Inter', size: 12, weight: '600' },
                                padding: { bottom: 6 }
                            },
                            grid: {
                                drawOnChartArea: false,
                                drawTicks: false,
                                drawBorder: false
                            },
                            ticks: {
                                color: '#d946ef',
                                font: { family: 'Inter', size: 11 },
                                padding: 8,
                                callback: value => formatShare(value)
                            }
                        }
                    }
                },
                plugins: [valueLabelPlugin, hoverGuidelinePlugin, topBarGlowPlugin]
            });
        };

        const waitForChartJs = (attempt = 0) => {
            if (typeof Chart !== 'undefined') {
                initializeSalesChart();
                return;
            }

            if (attempt >= 10) {
                console.error('Chart.js failed to load');
                return;
            }

            setTimeout(() => waitForChartJs(attempt + 1), 150);
        };

        const bootstrapFuelDashboard = () => {
            waitForChartJs();

            /* ---------- Modal handling ---------- */
            let activeModal = null;

            const openModal = id => {
                const modal = document.getElementById(id);
                if (!modal) return;
                if (activeModal && activeModal !== modal) closeModal();
                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('fuel-modal-open');
                const dialog = modal.querySelector('.fuel-modal__dialog');
                if (dialog) {
                    dialog.setAttribute('tabindex', '-1');
                    dialog.focus({ preventScroll: true });
                }
                activeModal = modal;
            };

            const closeModal = () => {
                if (!activeModal) return;
                activeModal.classList.remove('is-open');
                activeModal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('fuel-modal-open');
                activeModal = null;
            };

            document.querySelectorAll('[data-modal-open]').forEach(el => {
                el.addEventListener('click', e => {
                    e.preventDefault();
                    openModal(el.dataset.modalOpen);
                });
            });

            document.querySelectorAll('[data-modal-close]').forEach(el => {
                el.addEventListener('click', closeModal);
            });

            document.querySelectorAll('.fuel-modal').forEach(modal => {
                modal.addEventListener('click', e => {
                    if (e.target === modal) closeModal();
                });
            });

            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') closeModal();
            });
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', bootstrapFuelDashboard, { once: true, passive: true });
        } else {
            bootstrapFuelDashboard();
        }
    </script>
@endpush