@extends('layouts.vertical', [
    'page_title' => 'Sales Report',
    'mode' => $mode ?? '',
    'demo' => $demo ?? '',
])

@section('css')
    <style>
        .report-wrapper {
            padding: 1.8rem 0 3rem;
            background: linear-gradient(180deg, #f6f8fc 0%, #eef2fb 100%);
        }

        .report-hero {
            position: relative;
            border-radius: 26px;
            overflow: hidden;
            border: none;
            background: linear-gradient(135deg, #091f82ff 0%, #764ba2 25%, #f093fb 50%, #f7b934ff 75%, #ff6b6b 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            color: #f6f9ff;
            box-shadow: 0 32px 68px rgba(102, 126, 234, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.1);
            transform: translateY(0);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .report-hero:hover {
            transform: translateY(-2px);
            box-shadow: 0 40px 80px rgba(102, 126, 234, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.15);
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .report-hero__inner {
            display: flex;
            gap: 2.2rem;
            align-items: center;
            justify-content: space-between;
            padding: 3rem 3.2rem;
            position: relative;
            z-index: 2;
            flex-wrap: wrap;
        }
        
        .report-hero__inner::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.08) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
        }

        .report-hero__content {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            max-width: 620px;
            animation: fadeInUp 0.8s ease;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .report-hero__title {
            font-size: 2.4rem;
            font-weight: 900;
            letter-spacing: 0.08rem;
            text-transform: uppercase;
            margin: 0;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 50%, #ffffff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 2px 20px rgba(255, 255, 255, 0.3);
            line-height: 1.2;
        }

        .report-hero__subtitle {
            font-size: 1.05rem;
            line-height: 1.7;
            color: rgba(246, 249, 255, 0.92);
            font-weight: 400;
            letter-spacing: 0.02rem;
        }

        .report-hero__stats {
            display: flex;
            gap: 1.5rem;
            align-items: stretch;
            flex-wrap: wrap;
            justify-content: flex-start;
        }

        .report-hero__stat {
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.16);
            backdrop-filter: blur(16px);
            padding: 1rem 1.15rem;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.28);
        }

        .report-hero__stat-label {
            font-size: 0.74rem;
            text-transform: uppercase;
            letter-spacing: 0.22rem;
            color: rgba(246, 249, 255, 0.72);
        }

        .report-hero__stat-value {
            font-size: 1.58rem;
            font-weight: 700;
            letter-spacing: 0.06rem;
        }

        .report-hero__content,
        .report-hero__content * {
            color: #fff;
        }

        .report-hero__stat small {
            color: rgba(255, 255, 255, 0.85) !important;
        }

        .report-hero__actions .btn-hero-secondary {
            color: #ffffff;
        }

        .report-hero__actions {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            min-width: 240px;
            animation: fadeInRight 0.8s ease 0.2s both;
        }
        
        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .report-hero__actions .btn {
            border-radius: 14px;
            font-weight: 600;
            letter-spacing: 0.08rem;
            text-transform: uppercase;
            padding: 0.75rem 1.2rem;
            border: none;
        }

        .btn-hero-primary {
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 50%, #ffd700 100%);
            color: #1a1a2e;
            box-shadow: 0 8px 25px rgba(255, 215, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.2);
            transform: translateY(0);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-hero-primary::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.3) 0%, transparent 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .btn-hero-primary:hover {
            background: linear-gradient(135deg, #ffed4e 0%, #fff59d 100%);
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(255, 215, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.3);
        }
        
        .btn-hero-primary:hover::before {
            opacity: 1;
        }

        .btn-hero-secondary {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.3), 0 4px 15px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            transform: translateY(0);
            transition: all 0.3s ease;
        }
        
        .btn-hero-secondary:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-1px);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.4), 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .report-card {
            border: none;
            border-radius: 22px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 18px 48px rgba(34, 51, 89, 0.1);
        }

        .report-card__filters {
            background: linear-gradient(180deg, #f9faff 0%, #eef3ff 100%);
            padding: 1.6rem 2.2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1.4rem 2rem;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
        }

        .filter-group label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.26rem;
            font-weight: 600;
            color: #516083;
        }

        .filter-group input,
        .filter-group select {
            border-radius: 10px;
            border: 1px solid #c7d3ee;
            background: #ffffff;
            font-size: 0.9rem;
            padding: 0.6rem 0.85rem;
            min-height: 44px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            outline: none;
            border-color: #2f6fff;
            box-shadow: 0 0 0 3px rgba(47, 111, 255, 0.18);
        }

        .report-card__actions {
            padding: 0 2.2rem 1.7rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.9rem;
            align-items: center;
        }

        .report-card__actions .btn {
            border-radius: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08rem;
            padding: 0.65rem 1.1rem;
        }

        .report-card__body {
            padding: 2.2rem 2.4rem 2.6rem;
        }

        .report-metrics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
        }

        .report-metric-card {
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(47, 111, 255, 0.09) 0%, rgba(47, 111, 255, 0.02) 100%);
            border: 1px solid rgba(47, 111, 255, 0.18);
            padding: 1.4rem;
            display: flex;
            flex-direction: column;
            gap: 0.95rem;
            position: relative;
            overflow: hidden;
        }

        .report-metric-card::after {
            content: "";
            position: absolute;
            right: -36px;
            top: -36px;
            width: 110px;
            height: 110px;
            background: radial-gradient(circle at center, rgba(47, 111, 255, 0.22), transparent 70%);
        }

        .report-metric-card__icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            background: rgba(47, 111, 255, 0.16);
            color: #1d4ed8;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .report-metric-card__label {
            font-size: 0.76rem;
            letter-spacing: 0.18rem;
            text-transform: uppercase;
            color: #4a5778;
            font-weight: 700;
        }

        .report-metric-card__value {
            font-size: 1.72rem;
            font-weight: 700;
            color: #11224e;
        }

        .report-metric-card__meta {
            font-size: 0.82rem;
            color: rgba(17, 34, 78, 0.64);
        }

        .report-table-card {
            margin-top: 2.4rem;
            border-radius: 22px;
            border: 1px solid rgba(205, 216, 239, 0.8);
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 18px 40px rgba(19, 41, 97, 0.08);
        }

        .report-table-card__header {
            padding: 1.6rem 2.2rem 1.1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .report-table-card__title {
            margin: 0;
            font-size: 1.22rem;
            font-weight: 700;
            color: #1d2c4f;
            letter-spacing: 0.08rem;
        }

        .report-table {
            border-collapse: separate;
            width: 100%;
        }

        .report-table thead th {
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.22rem;
            color: #66759c;
            border: none;
            background: rgba(240, 243, 252, 0.9);
            padding: 0.95rem 1.2rem;
        }

        .report-table tbody tr {
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .report-table tbody tr:hover {
            background: rgba(47, 111, 255, 0.08);
            transform: translateY(-2px);
        }

        .report-table tbody td {
            border-top: 1px solid rgba(221, 229, 249, 0.7);
            padding: 1rem 1.2rem;
            color: #25365f;
            font-size: 0.9rem;
        }

        .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            border-radius: 999px;
            padding: 0.35rem 0.85rem;
            font-size: 0.72rem;
            letter-spacing: 0.16rem;
            text-transform: uppercase;
            font-weight: 700;
        }

        .status-chip.success {
            background: rgba(16, 185, 129, 0.14);
            color: #047857;
        }

        .status-chip.pending {
            background: rgba(234, 179, 8, 0.16);
            color: #92400e;
        }

        .report-insights {
            margin-top: 2.6rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.8rem;
        }

        .insight-card {
            border-radius: 22px;
            background: linear-gradient(140deg, #fff6f0 0%, #ffe3cc 100%);
            border: 1px solid rgba(255, 177, 109, 0.28);
            padding: 1.6rem 1.8rem;
            box-shadow: 0 22px 44px rgba(255, 159, 67, 0.22);
            display: flex;
            flex-direction: column;
            gap: 1.1rem;
        }

        .insight-card.alt {
            background: linear-gradient(140deg, #edf8ff 0%, #d9ebff 100%);
            border: 1px solid rgba(73, 166, 255, 0.26);
            box-shadow: 0 22px 44px rgba(73, 166, 255, 0.22);
        }

        .insight-card__title {
            font-size: 1.08rem;
            font-weight: 700;
            color: #1d243a;
            letter-spacing: 0.06rem;
            margin: 0;
        }

        .insight-card__list {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .insight-card__list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
            color: #2a3653;
            background: rgba(255, 255, 255, 0.48);
            border-radius: 14px;
            padding: 0.55rem 0.8rem;
        }

        .insight-card__cta {
            margin-top: auto;
        }

        .insight-card__cta .btn {
            border-radius: 12px;
            font-weight: 600;
            letter-spacing: 0.08rem;
            text-transform: uppercase;
        }

        .print-preview-card {
            border-radius: 22px;
            background: linear-gradient(140deg, #121f3f 0%, #233a74 100%);
            color: #f4f8ff;
            padding: 1.8rem;
            box-shadow: 0 22px 52px rgba(18, 31, 63, 0.32);
            position: relative;
            overflow: hidden;
        }

        .print-preview-card::after {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.2), transparent 55%);
            pointer-events: none;
        }

        .print-preview-card__title {
            font-size: 1.12rem;
            font-weight: 700;
            letter-spacing: 0.08rem;
            margin-bottom: 0.9rem;
        }

        .print-preview-card__body {
            background: rgba(255, 255, 255, 0.12);
            border-radius: 16px;
            padding: 1rem 1.2rem;
            display: flex;
            flex-direction: column;
            gap: 0.7rem;
            font-size: 0.88rem;
        }

        .print-preview-card__footer {
            margin-top: 1.1rem;
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        #summaryModal .print-preview-pane {
            background: linear-gradient(140deg, #14274d 0%, #1f3e7c 100%);
            color: #f4f8ff;
            border: none;
            box-shadow: 0 18px 40px rgba(20, 39, 77, 0.35);
        }

        #summaryModal .print-preview-pane .print-preview-pane__title,
        #summaryModal .print-preview-pane p,
        #summaryModal .print-preview-pane ul li,
        #summaryModal .print-preview-pane span,
        #summaryModal .print-preview-pane strong {
            color: #f6f9ff;
        }

        #summaryModal .print-preview-pane .badge {
            background: rgba(255, 255, 255, 0.22);
            color: #f6f9ff;
        }

        .modal-glass {
            background: rgba(17, 34, 78, 0.82);
            backdrop-filter: blur(16px);
        }

        .modal-content {
            border-radius: 22px;
            border: none;
            overflow: hidden;
        }

        .modal-header {
            border-bottom: none;
            padding: 1.6rem 1.9rem 0.8rem;
        }

        .modal-title {
            font-weight: 700;
            letter-spacing: 0.08rem;
            text-transform: uppercase;
            color: #1d2c4f;
        }

        .modal-body {
            padding: 0 1.9rem 1.9rem;
        }

        .modal-footer {
            border-top: none;
            padding: 1rem 1.9rem 1.6rem;
        }

        .modal-footer .btn {
            border-radius: 12px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.08rem;
        }

        .print-preview-pane {
            background: #ffffff;
            border-radius: 16px;
            padding: 1.4rem;
            box-shadow: 0 18px 32px rgba(17, 34, 78, 0.12);
        }

        .print-preview-pane__header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.2rem;
        }

        .print-preview-pane__title {
            font-size: 1.12rem;
            font-weight: 700;
        }

        .preview-table {
            width: 100%;
            border-collapse: collapse;
        }

        .preview-table thead th,
        .preview-table tbody td {
            border: 1px solid #d8e0f2;
            padding: 0.55rem 0.75rem;
            font-size: 0.85rem;
        }

        .preview-table thead th {
            background: #edf2ff;
            text-transform: uppercase;
            letter-spacing: 0.1rem;
            font-size: 0.74rem;
        }

        @media (max-width: 992px) {
            .report-hero__inner {
                padding: 2.5rem 2rem;
                gap: 2rem;
            }
            
            .report-hero__content {
                max-width: 100%;
                flex: 1;
            }
            
            .report-hero__actions {
                min-width: auto;
                flex-direction: row;
                flex-wrap: wrap;
            }
            
            .report-hero__actions .btn {
                flex: 1;
                min-width: 140px;
            }
        }

        @media (max-width: 768px) {
            .report-hero__inner {
                padding: 2rem 1.5rem;
                gap: 1.5rem;
                flex-direction: column;
                text-align: center;
            }
            
            .report-hero__title {
                font-size: 1.8rem;
                letter-spacing: 0.06rem;
            }
            
            .report-hero__subtitle {
                font-size: 0.95rem;
            }
            
            .report-hero__actions {
                flex-direction: column;
                width: 100%;
                max-width: 300px;
                margin: 0 auto;
            }
            
            .report-hero__actions .btn {
                width: 100%;
                min-width: auto;
            }

            .report-card__filters {
                grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            }

            .report-table-card__header {
                flex-direction: column;
                align-items: flex-start;
            }

            .print-preview-card {
                padding: 1.4rem;
            }
        }

        @media (max-width: 576px) {
            .report-wrapper {
                padding: 1.2rem 0 2rem;
            }

            .report-card__filters {
                padding: 1.2rem 1.1rem;
            }

            .report-card__body {
                padding: 1.6rem 1.2rem 2rem;
            }

            .report-table tbody td {
                font-size: 0.82rem;
            }
        }
    @media (max-width: 480px) {
            .report-hero__stats {
                overflow-x: auto;
                flex-wrap: nowrap;
                gap: 1rem;
                padding-bottom: 0.5rem;
            }
            
            .report-hero__stat {
                min-width: 160px;
                flex-shrink: 0;
            }
        }
    </style>
@endsection

@section('content')
    <div class="report-wrapper container-fluid">
        <div class="report-hero card">
            <div class="report-hero__inner">
                <div class="report-hero__content">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-warning text-dark px-3 py-2 text-uppercase" style="letter-spacing: 0.18rem; font-weight: 700;">
                            <i class="ri-bar-chart-box-line me-1"></i> Analytics
                        </span>
                        <button class="btn btn-light btn-sm text-uppercase" data-bs-toggle="modal" data-bs-target="#summaryModal">
                            <i class="ri-eye-line me-1"></i> View Summary
                        </button>
                    </div>
                    <h1 class="report-hero__title">Comprehensive Sales Report</h1>
                    <p class="report-hero__subtitle">
                        <i class="ri-dashboard-line me-2"></i>
                        Monitor performance across every station, compare periods, and export insights with a single click.
                        Tailor the report to a specific station or explore network-wide trends before printing or exporting.
                    </p>
                </div>
                <div class="report-hero__actions">
                    <button class="btn btn-hero-primary" data-bs-toggle="modal" data-bs-target="#printPreviewModal">
                        <i class="ri-printer-line me-2"></i> Print Preview
                    </button>
                    <button class="btn btn-hero-secondary" data-bs-toggle="modal" data-bs-target="#exportModal">
                        <i class="ri-download-cloud-2-line me-2"></i> Export Report
                    </button>
                    <button class="btn btn-hero-secondary" data-bs-toggle="modal" data-bs-target="#stationDetailModal">
                        <i class="ri-store-3-line me-2"></i> Station Insights
                    </button>
                </div>
            </div>
        </div>

        <div class="report-card mt-4">
            <div class="report-card__filters">
                <div class="filter-group">
                    <label for="filterStation">Station</label>
                    <select id="filterStation" class="form-select">
                        <option selected>All Stations</option>
                        <option>Navrongo Main</option>
                        <option>Wapuli</option>
                        <option>Bamvin</option>
                        <option>Paga Anex</option>
                        <option>Larabanga</option>
                        <option>Amoako</option>
                        <option>Navrongo-2</option>
                        <option>Bububele</option>
                        <option>Wiaga</option>
                        <option>Kintampo</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="filterProduct">Product</label>
                    <select id="filterProduct" class="form-select">
                        <option selected>All Products</option>
                        <option>Diesel (AGO)</option>
                        <option>Petrol (PMS)</option>
                        <option>Kerosene</option>
                        <option>Gas</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="filterPeriod">Period</label>
                    <select id="filterPeriod" class="form-select">
                        <option selected>This Month</option>
                        <option>Last Month</option>
                        <option>Last Quarter</option>
                        <option>Year to Date</option>
                        <option>Custom Range</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="filterStart">Start Date</label>
                    <input type="date" id="filterStart" class="form-control">
                </div>
                <div class="filter-group">
                    <label for="filterEnd">End Date</label>
                    <input type="date" id="filterEnd" class="form-control">
                </div>
                <div class="filter-group">
                    <label for="filterPayment">Payment Method</label>
                    <select id="filterPayment" class="form-select">
                        <option selected>All Methods</option>
                        <option>Cash</option>
                        <option>POS</option>
                        <option>Mobile Money</option>
                        <option>Corporate Account</option>
                    </select>
                </div>
            </div>
            <div class="report-card__actions">
                <button class="btn btn-primary">
                    <i class="ri-equalizer-line me-2"></i> Apply Filters
                </button>
                <button class="btn btn-outline-secondary">
                    <i class="ri-refresh-line me-2"></i> Reset
                </button>
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#advancedFilterModal">
                    <i class="ri-sliders-2-line me-2"></i> Advanced Filters
                </button>
                <div class="ms-auto d-flex gap-2 flex-wrap">
                    <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#exportModal">
                        <i class="ri-file-download-line me-2"></i> Export
                    </button>
                    <button class="btn btn-outline-warning text-dark" data-bs-toggle="modal" data-bs-target="#printPreviewModal">
                        <i class="ri-printer-line me-2"></i> Print
                    </button>
                </div>
            </div>
            <div class="report-card__body">
                <div class="report-metrics">
                    <div class="report-metric-card">
                        <div class="report-metric-card__icon">
                            <i class="ri-funds-box-line"></i>
                        </div>
                        <div class="report-metric-card__label">Average Daily Sales</div>
                        <div class="report-metric-card__value">₵ 18,325.27</div>
                        <div class="report-metric-card__meta">Across all stations for the selected window.</div>
                        <button class="btn btn-sm btn-outline-primary w-auto" data-bs-toggle="modal" data-bs-target="#trendModal">View Trend</button>
                    </div>
                    <div class="report-metric-card">
                        <div class="report-metric-card__icon">
                            <i class="ri-gas-station-line"></i>
                        </div>
                        <div class="report-metric-card__label">Station Coverage</div>
                        <div class="report-metric-card__value">11 Active</div>
                        <div class="report-metric-card__meta">Stations with recorded transactions this period.</div>
                        <button class="btn btn-sm btn-outline-primary w-auto" data-bs-toggle="modal" data-bs-target="#stationDetailModal">Station Matrix</button>
                    </div>
                    <div class="report-metric-card">
                        <div class="report-metric-card__icon">
                            <i class="ri-gas-station-line"></i>
                        </div>
                        <div class="report-metric-card__label">PMS Sold (L)</div>
                        <div class="report-metric-card__value">10,245 L</div>
                        <div class="report-metric-card__meta">Total petrol volume sold across all stations this period.</div>
                        <button class="btn btn-sm btn-outline-primary w-auto" data-bs-toggle="modal" data-bs-target="#pmsModal">Station Breakdown</button>
                    </div>
                    <div class="report-metric-card">
                        <div class="report-metric-card__icon">
                            <i class="ri-drop-line"></i>
                        </div>
                        <div class="report-metric-card__label">AGO Sold (L)</div>
                        <div class="report-metric-card__value">8,000 L</div>
                        <div class="report-metric-card__meta">Total diesel volume sold across all stations this period.</div>
                        <button class="btn btn-sm btn-outline-primary w-auto" data-bs-toggle="modal" data-bs-target="#agoModal">Station Breakdown</button>
                    </div>
                    <div class="report-metric-card">
                        <div class="report-metric-card__icon">
                            <i class="ri-currency-fill"></i>
                        </div>
                        <div class="report-metric-card__label">Receivables</div>
                        <div class="report-metric-card__value">₵ 42,610.45</div>
                        <div class="report-metric-card__meta">Outstanding corporate invoices awaiting settlement.</div>
                        <button class="btn btn-sm btn-outline-primary w-auto" data-bs-toggle="modal" data-bs-target="#receivableModal">View Aging</button>
                    </div>
                </div>

                <div class="report-table-card">
                    <div class="report-table-card__header">
                        <h5 class="report-table-card__title mb-0">Detailed Sales Ledger</h5>
                        <div class="d-flex gap-2 flex-wrap">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#exportModal">
                                <i class="ri-download-2-line me-1"></i> Quick Export
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#columnModal">
                                <i class="ri-table-line me-1"></i> Columns
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table report-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Station</th>
                                    <th>Product</th>
                                    <th>Volume (L)</th>
                                    <th>Unit Rate (₵)</th>
                                    <th>Total Amount (₵)</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>06 Nov 2025</td>
                                    <td>Navrongo Main</td>
                                    <td>Diesel (AGO)</td>
                                    <td>3,240</td>
                                    <td>18.65</td>
                                    <td>60,366.00</td>
                                    <td>Corporate Account</td>
                                    <td><span class="status-chip success">Completed</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#stationDetailModal">View</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>06 Nov 2025</td>
                                    <td>Larabanga</td>
                                    <td>Petrol (PMS)</td>
                                    <td>2,120</td>
                                    <td>17.85</td>
                                    <td>37,842.00</td>
                                    <td>POS</td>
                                    <td><span class="status-chip success">Completed</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#stationDetailModal">View</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>05 Nov 2025</td>
                                    <td>Wapuli</td>
                                    <td>Diesel (AGO)</td>
                                    <td>1,640</td>
                                    <td>18.55</td>
                                    <td>30,442.00</td>
                                    <td>Mobile Money</td>
                                    <td><span class="status-chip pending">Pending</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#stationDetailModal">View</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>05 Nov 2025</td>
                                    <td>Bamvin</td>
                                    <td>Kerosene</td>
                                    <td>860</td>
                                    <td>16.25</td>
                                    <td>13,975.00</td>
                                    <td>Cash</td>
                                    <td><span class="status-chip success">Completed</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#stationDetailModal">View</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>04 Nov 2025</td>
                                    <td>Wiaga</td>
                                    <td>Diesel (AGO)</td>
                                    <td>1,220</td>
                                    <td>18.60</td>
                                    <td>22,692.00</td>
                                    <td>Cash</td>
                                    <td><span class="status-chip success">Completed</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#stationDetailModal">View</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="report-insights">
                    <div class="insight-card">
                        <h6 class="insight-card__title">Top Performing Stations</h6>
                        <ul class="insight-card__list">
                            <li>
                                <span>Navrongo Main</span>
                                <span class="fw-bold">₵ 60,366</span>
                            </li>
                            <li>
                                <span>Paga Anex</span>
                                <span class="fw-bold">₵ 48,210</span>
                            </li>
                            <li>
                                <span>Larabanga</span>
                                <span class="fw-bold">₵ 37,842</span>
                            </li>
                        </ul>
                        <div class="insight-card__cta">
                            <button class="btn btn-outline-dark w-100" data-bs-toggle="modal" data-bs-target="#stationDetailModal">Station Breakdown</button>
                        </div>
                    </div>
                    <div class="insight-card alt">
                        <h6 class="insight-card__title">Variance Watchlist</h6>
                        <ul class="insight-card__list">
                            <li>
                                <span>Navrongo-2</span>
                                <span class="text-danger fw-bold">-4.3%</span>
                            </li>
                            <li>
                                <span>Amoako</span>
                                <span class="text-warning fw-bold">-2.1%</span>
                            </li>
                            <li>
                                <span>Bububele</span>
                                <span class="text-success fw-bold">+1.4%</span>
                            </li>
                        </ul>
                        <div class="insight-card__cta">
                            <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#varianceModal">Investigate Variance</button>
                        </div>
                    </div>
                    <div class="print-preview-card">
                        <div class="print-preview-card__title">Ready to Print?</div>
                        <div class="print-preview-card__body">
                            <div class="d-flex justify-content-between">
                                <span>Selected Station</span>
                                <strong id="previewSelectedStation">All Stations</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Period</span>
                                <strong id="previewSelectedPeriod">01 - 06 Nov 2025</strong>
                            </div>
                            <div>
                                <small>Preview the layout, tweak columns, and print with confidence.</small>
                            </div>
                        </div>
                        <div class="print-preview-card__footer">
                            <button class="btn btn-light text-dark" data-bs-toggle="modal" data-bs-target="#printPreviewModal">
                                <i class="ri-eye-line me-2"></i> Preview Report
                            </button>
                            <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#printPreviewModal">
                                <i class="ri-printer-line me-2"></i> Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Modal --}}
    <div class="modal fade" id="summaryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Network Summary</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="print-preview-pane">
                                <div class="print-preview-pane__header">
                                    <h6 class="print-preview-pane__title mb-0">Revenue Breakdown</h6>
                                    <span class="badge bg-primary">₵ 254,830</span>
                                </div>
                                <p class="mb-2">Top three stations account for 62% of total revenue this period.</p>
                                <ul class="list-unstyled mb-0 small">
                                    <li class="d-flex justify-content-between py-1">
                                        <span>Navrongo Main</span>
                                        <strong>₵ 60,366</strong>
                                    </li>
                                    <li class="d-flex justify-content-between py-1">
                                        <span>Paga Anex</span>
                                        <strong>₵ 48,210</strong>
                                    </li>
                                    <li class="d-flex justify-content-between py-1">
                                        <span>Larabanga</span>
                                        <strong>₵ 37,842</strong>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="print-preview-pane">
                                <div class="print-preview-pane__header">
                                    <h6 class="print-preview-pane__title mb-0">Performance Signals</h6>
                                    <span class="badge bg-success">On Track</span>
                                </div>
                                <ul class="list-unstyled small mb-0">
                                    <li class="py-1">+12.6% revenue growth vs. prior period.</li>
                                    <li class="py-1">Corporate invoices aging at 18 days on average.</li>
                                    <li class="py-1">Navrongo-2 flagged for volume variance.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-bs-target="#exportModal" data-bs-toggle="modal">Export Summary</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Station Detail Modal --}}
    <div class="modal fade" id="stationDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Station Insight</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="print-preview-pane mb-3">
                        <div class="print-preview-pane__header">
                            <h6 class="print-preview-pane__title mb-0" id="stationInsightTitle">Navrongo Main</h6>
                            <span class="badge bg-primary">Flagship</span>
                        </div>
                        <div class="row g-3 small">
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between">
                                    <span>Volume Sold</span>
                                    <strong>8,420 L</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Revenue</span>
                                    <strong>₵ 146,820</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Average Rate</span>
                                    <strong>₵ 17.43</strong>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between">
                                    <span>Cash</span>
                                    <strong>₵ 46,500</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>POS</span>
                                    <strong>₵ 54,200</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Momo</span>
                                    <strong>₵ 46,120</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table preview-table mb-0">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Volume (L)</th>
                                    <th>Amount (₵)</th>
                                    <th>Variance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Mon</td>
                                    <td>1,620</td>
                                    <td>28,440</td>
                                    <td class="text-success">+4.2%</td>
                                </tr>
                                <tr>
                                    <td>Tue</td>
                                    <td>1,520</td>
                                    <td>26,840</td>
                                    <td class="text-success">+1.9%</td>
                                </tr>
                                <tr>
                                    <td>Wed</td>
                                    <td>1,480</td>
                                    <td>26,140</td>
                                    <td class="text-danger">-2.1%</td>
                                </tr>
                                <tr>
                                    <td>Thu</td>
                                    <td>1,410</td>
                                    <td>25,095</td>
                                    <td class="text-warning">-0.6%</td>
                                </tr>
                                <tr>
                                    <td>Fri</td>
                                    <td>1,808</td>
                                    <td>32,305</td>
                                    <td class="text-success">+6.3%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-outline-primary" data-bs-target="#exportModal" data-bs-toggle="modal">Export Station</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Advanced Filter Modal --}}
    <div class="modal fade" id="advancedFilterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Advanced Filter Builder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-uppercase small fw-bold">Station Clusters</label>
                            <select class="form-select" multiple>
                                <option selected>Northern Corridor</option>
                                <option>Upper East</option>
                                <option>Upper West</option>
                                <option>Central</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-uppercase small fw-bold">Attendant</label>
                            <input type="text" class="form-control" placeholder="Search attendant">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-uppercase small fw-bold">Volume Threshold (L)</label>
                            <input type="number" class="form-control" placeholder="Eg. greater than 2000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-uppercase small fw-bold">Variance Flag</label>
                            <select class="form-select">
                                <option selected>All</option>
                                <option>Positive Variance</option>
                                <option>Negative Variance</option>
                                <option>Within Tolerance</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="form-label text-uppercase small fw-bold">Saved Configurations</label>
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge bg-primary-subtle text-primary px-3 py-2">Weekend Performance</span>
                            <span class="badge bg-warning-subtle text-warning px-3 py-2">Corporate Accounts</span>
                            <span class="badge bg-success-subtle text-success px-3 py-2">Variance Watch</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Apply Filters</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Export Modal --}}
    <div class="modal fade" id="exportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Export Sales Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-uppercase small fw-bold">Station</label>
                        <select class="form-select">
                            <option selected>All Stations</option>
                            <option>Navrongo Main</option>
                            <option>Wapuli</option>
                            <option>Bamvin</option>
                            <option>Paga Anex</option>
                            <option>Larabanga</option>
                            <option>Amoako</option>
                            <option>Navrongo-2</option>
                            <option>Bububele</option>
                            <option>Wiaga</option>
                            <option>Kintampo</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-uppercase small fw-bold">Format</label>
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-outline-primary flex-fill">Excel (.xlsx)</button>
                            <button class="btn btn-outline-success flex-fill">CSV (.csv)</button>
                            <button class="btn btn-outline-danger flex-fill">PDF (.pdf)</button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-uppercase small fw-bold">Include</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="includeMetrics" checked>
                            <label class="form-check-label" for="includeMetrics">Summary metrics</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="includeTransactions" checked>
                            <label class="form-check-label" for="includeTransactions">Transaction ledger</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="includeVariance">
                            <label class="form-check-label" for="includeVariance">Variance watchlist</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Export Now</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Print Preview Modal --}}
    <div class="modal fade" id="printPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Print Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="print-preview-pane">
                        <div class="print-preview-pane__header">
                            <div>
                                <h6 class="print-preview-pane__title mb-0">Fuel Management - Sales Report</h6>
                                <small>Period: <strong>01 Nov 2025 - 06 Nov 2025</strong> | Station: <strong>All Stations</strong></small>
                            </div>
                            <img src="https://dummyimage.com/120x40/1d4ed8/ffffff&text=Fuel+Co" alt="Brand" class="img-fluid rounded" />
                        </div>
                        <table class="preview-table mb-4">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Station</th>
                                    <th>Product</th>
                                    <th>Volume (L)</th>
                                    <th>Amount (₵)</th>
                                    <th>Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>06 Nov 2025</td>
                                    <td>Navrongo Main</td>
                                    <td>Diesel (AGO)</td>
                                    <td>3,240</td>
                                    <td>60,366.00</td>
                                    <td>Corporate Account</td>
                                </tr>
                                <tr>
                                    <td>06 Nov 2025</td>
                                    <td>Larabanga</td>
                                    <td>Petrol (PMS)</td>
                                    <td>2,120</td>
                                    <td>37,842.00</td>
                                    <td>POS</td>
                                </tr>
                                <tr>
                                    <td>05 Nov 2025</td>
                                    <td>Wapuli</td>
                                    <td>Diesel (AGO)</td>
                                    <td>1,640</td>
                                    <td>30,442.00</td>
                                    <td>Mobile Money</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-between align-items-center small text-muted">
                            <span>Generated on: 19 Nov 2025 16:58 GMT</span>
                            <span>Prepared by: <strong>Fuel Management Suite</strong></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close Preview</button>
                    <button type="button" class="btn btn-primary">Print Report</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Additional Insights Modals --}}
    <div class="modal fade" id="trendModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Daily Sales Trend</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Visual trend placeholder. Integrate charts to compare daily revenue and volume movement.</p>
                    <div class="print-preview-pane text-center">
                        <img src="https://dummyimage.com/720x320/eef3ff/2f6fff&text=Daily+Trend+Chart" alt="Trend Chart" class="img-fluid rounded">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="receivableModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Receivables Aging</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="print-preview-pane">
                        <table class="preview-table mb-4">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Outstanding (₵)</th>
                                    <th>Days</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Ghana Minerals</td>
                                    <td>18,240</td>
                                    <td>22</td>
                                    <td class="text-warning">Follow Up</td>
                                </tr>
                                <tr>
                                    <td>Sunrise Farms</td>
                                    <td>12,400</td>
                                    <td>14</td>
                                    <td class="text-success">On Track</td>
                                </tr>
                                <tr>
                                    <td>BlueLogistics</td>
                                    <td>11,970</td>
                                    <td>28</td>
                                    <td class="text-danger">Escalate</td>
                                </tr>
                            </tbody>
                        </table>
                        <p class="small mb-0">Plan follow-up for clients beyond 21 days to improve cash flow.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="varianceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Variance Investigation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Prepare follow-up actions for stations below threshold performance.</p>
                    <ul class="list-unstyled small mb-0">
                        <li class="py-1"><strong>Navrongo-2:</strong> Review pump calibration records.</li>
                        <li class="py-1"><strong>Amoako:</strong> Confirm delivery reconciliation.</li>
                        <li class="py-1"><strong>Bububele:</strong> Monitor weekend staffing levels.</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Create Task</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="columnModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Choose Columns</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-grid gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="colVolume" checked>
                            <label class="form-check-label" for="colVolume">Volume (L)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="colRate" checked>
                            <label class="form-check-label" for="colRate">Unit Rate (₵)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="colPayment" checked>
                            <label class="form-check-label" for="colPayment">Payment Method</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="colStatus" checked>
                            <label class="form-check-label" for="colStatus">Status</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="colAttendant">
                            <label class="form-check-label" for="colAttendant">Attendant</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- PMS Modal -->
    <div class="modal fade" id="pmsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">PMS Station Breakdown</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="print-preview">
                        <div class="print-preview__header">
                            <h6>Petrol Sales by Station</h6>
                            <small class="text-muted">Total Volume: 10,245 Liters</small>
                        </div>
                        <div class="print-preview__pane">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Station</th>
                                        <th>Volume (L)</th>
                                        <th>Revenue (₵)</th>
                                        <th>% of Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Navrongo Main</td>
                                        <td>2,450</td>
                                        <td>12,250.00</td>
                                        <td>23.9%</td>
                                    </tr>
                                    <tr>
                                        <td>Wapuli</td>
                                        <td>1,890</td>
                                        <td>9,450.00</td>
                                        <td>18.4%</td>
                                    </tr>
                                    <tr>
                                        <td>Bamvin</td>
                                        <td>1,675</td>
                                        <td>8,375.00</td>
                                        <td>16.3%</td>
                                    </tr>
                                    <tr>
                                        <td>Paga Anex</td>
                                        <td>1,440</td>
                                        <td>7,200.00</td>
                                        <td>14.1%</td>
                                    </tr>
                                    <tr>
                                        <td>Larabanga</td>
                                        <td>1,230</td>
                                        <td>6,150.00</td>
                                        <td>12.0%</td>
                                    </tr>
                                    <tr>
                                        <td>Amoako</td>
                                        <td>980</td>
                                        <td>4,900.00</td>
                                        <td>9.6%</td>
                                    </tr>
                                    <tr>
                                        <td>Navrongo-2</td>
                                        <td>580</td>
                                        <td>2,900.00</td>
                                        <td>5.7%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Export Details</button>
                </div>
            </div>
        </div>
    </div>

    <!-- AGO Modal -->
    <div class="modal fade" id="agoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">AGO Station Breakdown</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="print-preview">
                        <div class="print-preview__header">
                            <h6>Diesel Sales by Station</h6>
                            <small class="text-muted">Total Volume: 8,000 Liters</small>
                        </div>
                        <div class="print-preview__pane">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Station</th>
                                        <th>Volume (L)</th>
                                        <th>Revenue (₵)</th>
                                        <th>% of Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Navrongo Main</td>
                                        <td>2,100</td>
                                        <td>10,500.00</td>
                                        <td>26.3%</td>
                                    </tr>
                                    <tr>
                                        <td>Wapuli</td>
                                        <td>1,650</td>
                                        <td>8,250.00</td>
                                        <td>20.6%</td>
                                    </tr>
                                    <tr>
                                        <td>Bamvin</td>
                                        <td>1,320</td>
                                        <td>6,600.00</td>
                                        <td>16.5%</td>
                                    </tr>
                                    <tr>
                                        <td>Paga Anex</td>
                                        <td>1,100</td>
                                        <td>5,500.00</td>
                                        <td>13.8%</td>
                                    </tr>
                                    <tr>
                                        <td>Larabanga</td>
                                        <td>890</td>
                                        <td>4,450.00</td>
                                        <td>11.1%</td>
                                    </tr>
                                    <tr>
                                        <td>Amoako</td>
                                        <td>720</td>
                                        <td>3,600.00</td>
                                        <td>9.0%</td>
                                    </tr>
                                    <tr>
                                        <td>Navrongo-2</td>
                                        <td>220</td>
                                        <td>1,100.00</td>
                                        <td>2.8%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Export Details</button>
                </div>
            </div>
        </div>
    </div>
@endsection
