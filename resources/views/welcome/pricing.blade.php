<section class="pricing">
    <div class="container">
        <h2 class="section-title">Choose Your Plan</h2>
        <p class="section-subtitle">Flexible pricing tailored to your business needs.</p>
        <div class="pricing-cards">
            <!-- Starter Plan -->
            <div class="card">
                <h3 class="plan-title">Starter</h3>
                <p class="price">
                    <span>$</span>
                    49
                    <span>/monthly</span>
                </p>
                <ul class="features">
                    <li>Basic ERP Features</li>
                    <li>Up to 10 Users</li>
                    <li>Email Support</li>
                    <li>Support</li>
                </ul>
                <a href="{{ route('start') }}" class="btn-primary featured" id="get_started">Get Started</a>
            </div>
            <!-- Professional Plan -->
            <div class="card featured">
                <h3 class="plan-title featured">Professional</h3>
                <p class="price featured">
                    <span>$</span>
                    99
                    <span>/monthly</span>
                </p>
                <ul class="features featured">
                    <li>All Starter Features</li>
                    <li>Up to 50 Users</li>
                    <li>Priority Support</li>
                    <li>Advanced Analytics</li>
                </ul>
                <a href="{{ route('start') }}" class="btn-primary featured" id="get_started">Get Started</a>
            </div>
            <!-- Enterprise Plan -->
            <div class="card">
                <h3 class="plan-title">Enterprise</h3>
                <p class="price">Custom Plan</p>
                <ul class="features">
                    <li>Unlimited Users</li>
                    <li>Custom Integrations</li>
                    <li>Account Manager</li>
                    <li>24/7 Support</li>
                </ul>
                <a href="{{ route('start') }}" class="btn-primary featured" id="get_started">Get Started</a>
            </div>
        </div>
    </div>
</section>
