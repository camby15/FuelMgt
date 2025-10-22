@php
/**
 * Financial Tracking Tab Component
 * 
 * Key Calculations:
 * - Income = Revenue per successful connection
 * - Cost = Salaries + Materials + Fuel + Vehicle Allocation
 * - Net Profit = Income - Total Cost
 */
$teams = [
    'alpha' => ['name' => 'Team Alpha', 'color' => '#3b82f6'],
    'beta' => ['name' => 'Team Beta', 'color' => '#10b981'],
    'gamma' => ['name' => 'Team Gamma', 'color' => '#f59e0b'],
];

// Sample data - Replace with actual data from your database
$revenuePerConnection = 1200; // GHS per connection
$monthlySalaries = 45000; // Total monthly salaries
$vehicleAllocationRate = 0.15; // 15% of vehicle cost allocated to projects
$fuelCostPerKm = 0.85; // GHS per km

// Sample team data
$teamData = [
    'alpha' => [
        'connections' => 42,
        'materials_cost' => 8500,
        'distance_km' => 1250,
        'vehicle_cost' => 4500,
    ],
    'beta' => [
        'connections' => 38,
        'materials_cost' => 7200,
        'distance_km' => 980,
        'vehicle_cost' => 3800,
    ],
    'gamma' => [
        'connections' => 35,
        'materials_cost' => 6500,
        'distance_km' => 1120,
        'vehicle_cost' => 4200,
    ],
];

// Calculate financials
$totalIncome = 0;
$totalCost = 0;

foreach ($teamData as $teamId => $data) {
    $teamData[$teamId]['income'] = $data['connections'] * $revenuePerConnection;
    $teamData[$teamId]['fuel_cost'] = $data['distance_km'] * $fuelCostPerKm;
    $teamData[$teamId]['vehicle_allocation'] = $data['vehicle_cost'] * $vehicleAllocationRate;
    $teamData[$teamId]['salary_allocation'] = $monthlySalaries / count($teams);
    
    $teamData[$teamId]['total_cost'] = 
        $teamData[$teamId]['materials_cost'] + 
        $teamData[$teamId]['fuel_cost'] + 
        $teamData[$teamId]['vehicle_allocation'] + 
        $teamData[$teamId]['salary_allocation'];
    
    $teamData[$teamId]['profit'] = $teamData[$teamId]['income'] - $teamData[$teamId]['total_cost'];
    
    $totalIncome += $teamData[$teamId]['income'];
    $totalCost += $teamData[$teamId]['total_cost'];
}

$totalProfit = $totalIncome - $totalCost;
$profitMargin = $totalIncome > 0 ? ($totalProfit / $totalIncome) * 100 : 0;
@endphp

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Financial Tracking</h5>
        <div class="btn-group">
            <button class="btn btn-sm btn-outline-secondary active" data-period="monthly">Monthly</button>
            <button class="btn btn-sm btn-outline-secondary" data-period="weekly">Weekly</button>
            <button class="btn btn-sm btn-outline-secondary" data-period="daily">Daily</button>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="card bg-light border-0 h-100">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Total Income</h6>
                        <h3 class="mb-0">GHS {{ number_format($totalIncome, 2) }}</h3>
                        <small class="text-success">
                            <i class="fas fa-arrow-up me-1"></i> 
                            {{ number_format($profitMargin, 1) }}% margin
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="card bg-light border-0 h-100">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Total Cost</h6>
                        <h3 class="mb-0">GHS {{ number_format($totalCost, 2) }}</h3>
                        <small class="text-muted">
                            <i class="fas fa-calculator me-1"></i> 
                            {{ number_format(($totalCost / $totalIncome) * 100, 1) }}% of income
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="card bg-light border-0 h-100">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Net Profit</h6>
                        <h3 class="mb-0 {{ $totalProfit >= 0 ? 'text-success' : 'text-danger' }}">
                            GHS {{ number_format(abs($totalProfit), 2) }}
                        </h3>
                        <small class="{{ $totalProfit >= 0 ? 'text-success' : 'text-danger' }}">
                            <i class="fas {{ $totalProfit >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} me-1"></i>
                            {{ number_format($profitMargin, 1) }}% margin
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-light border-0 h-100">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Connections</h6>
                        <h3 class="mb-0">{{ array_sum(array_column($teamData, 'connections')) }}</h3>
                        <small class="text-muted">
                            @ {{ 'GHS ' . number_format($revenuePerConnection, 2) }} per connection
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Team Performance -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0">Team Performance</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Team</th>
                                <th class="text-end">Connections</th>
                                <th class="text-end">Income</th>
                                <th class="text-end">Materials</th>
                                <th class="text-end">Labor</th>
                                <th class="text-end">Fuel</th>
                                <th class="text-end">Vehicle</th>
                                <th class="text-end fw-bold">Net Profit</th>
                                <th class="text-end">Margin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teamData as $teamId => $data)
                            @php
                                $teamProfitMargin = $data['income'] > 0 ? ($data['profit'] / $data['income']) * 100 : 0;
                            @endphp
                            <tr>
                                <td><span class="badge" style="background-color: {{ $teams[$teamId]['color'] }}">{{ $teams[$teamId]['name'] }}</span></td>
                                <td class="text-end">{{ $data['connections'] }}</td>
                                <td class="text-end">GHS {{ number_format($data['income'], 2) }}</td>
                                <td class="text-end">GHS {{ number_format($data['materials_cost'], 2) }}</td>
                                <td class="text-end">GHS {{ number_format($data['salary_allocation'], 2) }}</td>
                                <td class="text-end">GHS {{ number_format($data['fuel_cost'], 2) }}</td>
                                <td class="text-end">GHS {{ number_format($data['vehicle_allocation'], 2) }}</td>
                                <td class="text-end fw-bold {{ $data['profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    GHS {{ number_format($data['profit'], 2) }}
                                </td>
                                <td class="text-end {{ $teamProfitMargin >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($teamProfitMargin, 1) }}%
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-group-divider">
                            <tr class="fw-bold">
                                <td>Total</td>
                                <td class="text-end">{{ array_sum(array_column($teamData, 'connections')) }}</td>
                                <td class="text-end">GHS {{ number_format($totalIncome, 2) }}</td>
                                <td class="text-end">GHS {{ number_format(array_sum(array_column($teamData, 'materials_cost')), 2) }}</td>
                                <td class="text-end">GHS {{ number_format($monthlySalaries, 2) }}</td>
                                <td class="text-end">GHS {{ number_format(array_sum(array_column($teamData, 'fuel_cost')), 2) }}</td>
                                <td class="text-end">GHS {{ number_format(array_sum(array_column($teamData, 'vehicle_allocation')), 2) }}</td>
                                <td class="text-end {{ $totalProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                    GHS {{ number_format($totalProfit, 2) }}
                                </td>
                                <td class="text-end {{ $profitMargin >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($profitMargin, 1) }}%
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Revenue Chart -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Revenue & Profit Overview</h6>
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-secondary active" data-period="monthly">Monthly</button>
                    <button class="btn btn-sm btn-outline-secondary" data-period="weekly">Weekly</button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0">Top Revenue Sources</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Residential Plans</span>
                                <span>GHS 68,450</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 55%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Business Plans</span>
                                <span>GHS 42,130</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 34%"></div>
                            </div>
                        </div>
                        <div class="mb-0">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Enterprise Solutions</span>
                                <span>GHS 14,000</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 11%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Recent Transactions</h6>
                        <button class="btn btn-sm btn-outline-secondary">View All</button>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach([
                                ['id' => 'TXN-1001', 'date' => 'Today', 'amount' => '1,250.00', 'status' => 'success', 'customer' => 'John Smith'],
                                ['id' => 'TXN-1000', 'date' => 'Yesterday', 'amount' => '850.00', 'status' => 'success', 'customer' => 'Acme Corp'],
                                ['id' => 'TXN-999', 'date' => '2 days ago', 'amount' => '2,450.00', 'status' => 'warning', 'customer' => 'Jane Doe'],
                                ['id' => 'TXN-998', 'date' => '3 days ago', 'amount' => '1,750.00', 'status' => 'success', 'customer' => 'Global Tech'],
                            ] as $txn)
                            <div class="list-group-item border-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">#{{ $txn['id'] }}</h6>
                                        <small class="text-muted">{{ $txn['customer'] }}</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold">GHS {{ $txn['amount'] }}</div>
                                        <small class="text-{{ $txn['status'] === 'success' ? 'success' : 'warning' }}">
                                            {{ ucfirst($txn['status']) }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize chart with team data
    const revenueCanvas = document.getElementById('revenueChart');
    if (!revenueCanvas) {
        console.warn('Revenue chart canvas not found');
    } else {
        const ctx = revenueCanvas.getContext('2d');
        const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [
                @foreach($teamData as $teamId => $data)
                {
                    label: '{{ $teams[$teamId]['name'] }} Income',
                    data: Array(6).fill().map(() => Math.floor(Math.random() * 10000) + 5000),
                    borderColor: '{{ $teams[$teamId]['color'] }}',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.3,
                    borderDash: [5, 5],
                    pointBackgroundColor: '{{ $teams[$teamId]['color'] }}'
                },
                {
                    label: '{{ $teams[$teamId]['name'] }} Profit',
                    data: Array(6).fill().map(() => Math.floor(Math.random() * 8000) + 2000),
                    borderColor: '{{ $teams[$teamId]['color'] }}',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.3,
                    pointBackgroundColor: '{{ $teams[$teamId]['color'] }}'
                },
                @endforeach
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'GHS ' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': GHS ' + context.raw.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Period toggle functionality
    document.querySelectorAll('[data-period]').forEach(button => {
        button.addEventListener('click', function() {
            // Update active state
            document.querySelectorAll('[data-period]').forEach(btn => {
                btn.classList.remove('active');
            });
            this.classList.add('active');
            
            // In a real app, you would fetch new data here based on the period
            // For now, we'll just show a message
            console.log('Switched to ' + this.textContent.trim() + ' view');
        });
        });
    }
</script>
@endpush
