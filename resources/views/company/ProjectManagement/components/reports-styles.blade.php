<style>
    :root {
        /* Modern color palette */
        --card-1-bg: linear-gradient(135deg, #4361ee 0%, #3f37c9 100%);
        --card-2-bg: linear-gradient(135deg, #4cc9f0 0%, #4895ef 100%);
        --card-3-bg: linear-gradient(135deg, #f8961e 0%, #f3722c 100%);
        --card-4-bg: linear-gradient(135deg, #7209b7 0%, #560bad 100%);
        
        --card-1-icon: #3a56e9;
        --card-2-icon: #3fb6d8;
        --card-3-icon: #e07f17;
        --card-4-icon: #5e0da3;
        
        --card-text: #ffffff;
        --card-text-muted: rgba(255, 255, 255, 0.8);
    }

    .card-analytics {
        border: none;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
        overflow: hidden;
        position: relative;
        color: var(--card-text);
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
    }

    .card-analytics::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0.95;
        z-index: -1;
    }

    .card-analytics .card-body {
        padding: 1.5rem;
        position: relative;
        z-index: 1;
    }

    .card-analytics h5 {
        color: #2b2d42;
        font-weight: 600;
        margin-bottom: 1.25rem;
        position: relative;
        display: inline-block;
    }

    .card-analytics h5::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -8px;
        width: 40px;
        height: 3px;
        background: var(--primary-color);
        border-radius: 3px;
    }

    .stat-number {
        font-size: 2.25rem;
        font-weight: 700;
        color: var(--card-text);
        margin: 0.5rem 0;
        line-height: 1.2;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .stat-label {
        color: var(--card-text-muted);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-weight: 500;
        display: block;
        margin-bottom: 0.5rem;
    }

    .stat-change {
        display: inline-flex;
        align-items: center;
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        margin-top: 0.5rem;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .stat-change.positive {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
    }

    .stat-change.negative {
        background: rgba(0, 0, 0, 0.2);
        color: #ff9aa2;
    }

    .chart-container {
        height: 250px;
        position: relative;
        margin-top: 1rem;
    }

    .icon-wrapper {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.9);
        color: var(--card-1-icon);
        font-size: 1.75rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .card-analytics:hover .icon-wrapper {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    .progress-thin { 
        height: 6px; 
        border-radius: 3px;
    }

    /* Card wave effect */
    .card-wave {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 20px;
        background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1200 120' preserveAspectRatio='none'%3E%3Cpath d='M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z' fill='rgba(255,255,255,0.1)'%3E%3C/path%3E%3C/svg%3E");
        background-size: cover;
        background-position: bottom;
        opacity: 0.5;
    }

    /* Card specific backgrounds */
    .card-analytics[style*='--card-bg']::before {
        background: var(--card-bg);
    }

    /* Stat meta */
    .stat-meta {
        color: var(--card-text-muted);
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .stat-number {
            font-size: 1.75rem;
        }
        
        .card-analytics {
            margin-bottom: 1rem;
        }
        
        .icon-wrapper {
            width: 48px;
            height: 48px;
            font-size: 1.5rem;
        }
    }
    
    /* Hover effects */
    .card-analytics:hover {
        transform: translateY(-5px) scale(1.01);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }
</style>
