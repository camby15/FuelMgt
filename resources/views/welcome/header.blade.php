<div class="slider-container">
    <div class="slider">
        <div class="slide" style="background-image: url('images/Station-3.jpg')">
            <div class="content">
                <h1>
                    Boost Your
                    <span class="text-out">Power your Station with StationMgt</span>
                </h1>
                <p>
                    A comprehensive fuel management solution designed to optimize your station's operations,
                    track inventory, and maximize profitability.
                </p>
            </div>
        </div>
        <div class="slide" style="background-image: url('images/tankfarm.jpg')">
            <div class="content">
                <h1>
                    Best ERP Platform To
                    <span class="text-out">Power Your Business</span>
                </h1>
                <p>
                    A cloud-based enterprise resource planning platform designed to let you manage and boost you
                    business.
                </p>
            </div>
        </div>
        <div class="slide" style="background-image: url('images/Oil-2.jpg')">
            <div class="content">
                <h1>
                    Empower teams
                    <span class="text-out">Achieve excellence.</span>
                </h1>
                <p>
                    A cloud-based enterprise resource planning platform designed to let you manage and boost you
                    business.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Fixed Get Started Button -->
    <div class="fixed-get-started">
        <button class="btn">
            <a href="{{ route('start') }}">Get Started</a>
        </button>
    </div>
    
    <div class="navigation">
        <button class="nav-btn active" data-slide="0"></button>
        <button class="nav-btn" data-slide="1"></button>
        <button class="nav-btn" data-slide="2"></button>
    </div>
</div>

<style>
.fixed-get-started {
    position: absolute;
    top: 55%;
    right: 9rem;
    z-index: 1001;
}

.fixed-get-started .btn {
    background-color: #f0f2f4e7;
    color: rgba(10, 2, 2, 1);
    border: none;
    padding: 15px 35px;
    cursor: pointer;
    font-size: 1.2rem;
    font-weight: 500;
    font-family: 'poppins';
    border-radius: 5px;
    transition: background-color 0.3s;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.fixed-get-started .btn:hover {
    background-color: #011228;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
}

.fixed-get-started .btn a {
    text-decoration: none;
    color: white;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .fixed-get-started {
        right: 2rem;
        top: 50%;
    }
    
    .fixed-get-started .btn {
        padding: 12px 25px;
        font-size: 1rem;
    }
}

@media (max-width: 480px) {
    .fixed-get-started {
        right: 1rem;
        top: 50%;
    }
    
    .fixed-get-started .btn {
        padding: 10px 20px;
        font-size: 0.9rem;
    }
}
</style>
