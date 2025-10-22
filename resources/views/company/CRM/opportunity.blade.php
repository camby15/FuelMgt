@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    @php
        use App\Helpers\CurrencyHelper;
    @endphp     

    

@endsection

@php
    $opportunities = $opportunities ?? collect();
    $opportunityStats = $opportunityStats ?? [
        'total_value' => 0,
        'open_opportunities' => 0,
        'open_opportunities_percentage' => 0,
        'win_rate' => 0,
        'avg_deal_size' => 0
    ];
@endphp

<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card sales" id="totalValueCard">
            <i class="stat-icon fas fa-dollar-sign"></i>
            <div class="stat-title">Total Value</div>
            <div class="stat-value">{{ CurrencyHelper::format($opportunityStats['total_value']) }}</div>
            <div class="stat-change">
                <i class="fas fa-chart-pie"></i>
                <span>Across all opportunities</span>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card pipeline" id="openOpportunitiesCard">
            <i class="stat-icon fas fa-rocket"></i>
            <div class="stat-title">Open Opportunities</div>
            <div class="stat-value">{{ $opportunityStats['open_opportunities'] }}</div>
            <div class="stat-change">
                <i class="fas fa-chart-line"></i>
                <span>{{ $opportunityStats['open_opportunities_percentage'] }}% of total</span>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card deal" id="winRateCard">
            <i class="stat-icon fas fa-trophy"></i>
            <div class="stat-title">Win Rate</div>
            <div class="stat-value">{{ $opportunityStats['win_rate'] }}%</div>
            <div class="stat-change">
                <i class="fas fa-arrow-up"></i>
                <span>Opportunities Converted</span>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card conversion" id="avgDealSizeCard">
            <i class="stat-icon fas fa-calculator"></i>
            <div class="stat-title">Avg. Deal Size</div>
            <div class="stat-value">{{ CurrencyHelper::format($opportunityStats['avg_deal_size']) }}</div>
            <div class="stat-change">
                <i class="fas fa-chart-line"></i>
                <span>Closed Won Opportunities</span>
            </div>
        </div>
    </div>
</div>

<!-- Notifications Area -->
<div id="notificationArea" class="mb-3"></div>



<!-- Export Opportunities Modal -->
<div class="modal fade" id="exportOpportunitiesModal" tabindex="-1" aria-labelledby="exportOpportunitiesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportOpportunitiesModalLabel">Export Opportunities</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="exportFormat" class="form-label">Select Export Format</label>
                    <select class="form-select" id="exportFormat">
                        <option value="csv">CSV</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="confirmExport">Export</button>
            </div>
        </div>
    </div>
</div>

<!-- Export Opportunities Button -->
<!--<button id="exportOpportunities" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exportOpportunitiesModal">Export Opportunities</button>--><!--

<!-- Opportunities Table -->
<div class="row">
    <div class="col-12">
        <div class="card p-3 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
                    <h4 class="header-title mb-0">Opportunities</h4>
                    <div class="d-flex flex-wrap align-items-center gap-2 ms-auto">
                        <button class="btn btn-primary" data-toggle="tooltip" title="New Opportunity" data-bs-toggle="modal" data-bs-target="#addOpportunityModal">
                            <i class="fas fa-plus me-1"></i> New Opportunity
                        </button>
                        <button id="exportOpportunities" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#exportOpportunitiesModal">
                            Export Opportunities
                        </button>
                        <div class="form-floating" style="width: 250px;">
                            <input type="text" id="searchOpportunity" class="form-control" placeholder=" " aria-label="Search">
                            <label for="searchOpportunity">Search Opportunities...</label>
                        </div>
                    </div>
                </div>
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @php
                    $opportunities = $opportunities ?? collect();
                @endphp


                @if($opportunities->isEmpty())
                    <div class="text-center py-4">
                        <p class="text-muted">No opportunities found. Click "New Opportunity" to get started.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-centered table-hover dt-responsive nowrap w-100" id="opportunities-table">
                            <thead>
                                <tr>
                                    <th>Opportunity Name</th>
                                    <th>Account</th>
                                    <th>Stage</th>
                                    <th>Amount</th>
                                    <th>Expected Revenue</th>
                                    <th>Close Date</th>
                                    <th>Probability</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                              
                            </tbody>
                        </table>

                        <nav aria-label="Page navigation example">
                            <ul class="pagination" id="opportunity-pagination">
                        
                            </ul>
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Opportunity Modal -->
<div class="modal fade" id="addOpportunityModal" tabindex="-1" aria-labelledby="addOpportunityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addOpportunityModalLabel">Add New Opportunity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="opportunityForm" class="needs-validation" action="{{ route('company.opportunities.store') }}" method="POST" novalidate>
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="opportunityName" name="name" placeholder=" " required>
                            <label for="opportunityName">Opportunity Name</label>
                            <div class="invalid-feedback">Please enter an opportunity name.</div>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="account" name="account" placeholder=" " required>
                            <label for="account">Account</label>
                            <div class="invalid-feedback">Please enter an account name.</div>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-select" id="stage" name="stage" required>
                                <option value="">Select Stage</option>
                                <option value="Prospecting">Prospecting</option>
                                <option value="Qualification">Qualification</option>
                                <option value="Proposal">Proposal</option>
                                <option value="Negotiation">Negotiation</option>
                                <option value="Closed Won">Closed Won</option>
                                <option value="Closed Lost">Closed Lost</option>
                            </select>
                            <label for="stage">Opportunity Stage</label>
                            <div class="invalid-feedback">Please select a stage.</div>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="amount" name="amount" placeholder=" " required min="0" step="0.01">
                            <label for="amount">Amount</label>
                            <div class="invalid-feedback">Please enter a valid amount.</div>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="expectedRevenue" name="expected_revenue" placeholder=" " min="0" step="0.01">
                            <label for="expectedRevenue">Expected Revenue (Optional)</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" id="opportunityCloseDate" name="close_date" placeholder=" " required>
                            <label for="opportunityCloseDate">Close Date</label>
                            <div class="invalid-feedback">Please select a close date.</div>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="probability" name="probability" placeholder=" " min="0" max="100" step="1" required>
                            <label for="probability">Probability (%)</label>
                            <div class="invalid-feedback">Please enter a probability between 0 and 100.</div>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="description" name="description" placeholder=" " style="height: 100px"></textarea>
                            <label for="description">Description (Optional)</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="submitOpportunity" class="btn btn-primary">Create Opportunity</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Floating Label Styles */
    #addOpportunityModal .form-floating {
        position: relative;
        margin-bottom: 1rem;
    }
    #addOpportunityModal .form-floating input.form-control,
    #addOpportunityModal .form-floating select.form-select,
    #addOpportunityModal .form-floating textarea.form-control {
        height: 40px;
        border: 1px solid #2f2f2f;
        border-radius: 10px;
        background-color: transparent;
        font-size: 1rem;
        padding: 0.5rem 0.75rem;
        transition: all 0.8s;
    }
    #addOpportunityModal .form-floating textarea.form-control {
        min-height: 100px;
        height: auto;
        padding-top: 1.625rem;
    }
    #addOpportunityModal .form-floating label {
        position: absolute;
        top: 0;
        left: 0;
        margin-left: 10px;
        height: 100%;
        padding: 0.5rem 0.75rem;
        color: #2f2f2f;
        transition: all 0.8s;
        pointer-events: none;
        z-index: 1;
    }
    #addOpportunityModal .form-floating input.form-control:focus,
    #addOpportunityModal .form-floating input.form-control:not(:placeholder-shown),
    #addOpportunityModal .form-floating select.form-select:focus,
    #addOpportunityModal .form-floating select.form-select:not([value=""]),
    #addOpportunityModal .form-floating textarea.form-control:focus,
    #addOpportunityModal .form-floating textarea.form-control:not(:placeholder-shown) {
        border-color: #033c42;
        box-shadow: none;
    }
    #addOpportunityModal .form-floating input.form-control:focus ~ label,
    #addOpportunityModal .form-floating input.form-control:not(:placeholder-shown) ~ label,
    #addOpportunityModal .form-floating select.form-select:focus ~ label,
    #addOpportunityModal .form-floating select.form-select:not([value=""]) ~ label,
    #addOpportunityModal .form-floating textarea.form-control:focus ~ label,
    #addOpportunityModal .form-floating textarea.form-control:not(:placeholder-shown) ~ label {
        height: auto;
        padding: 0 0.5rem;
        margin-left: 15px;
        transform: translateY(-60%) translateX(0.5rem) scale(0.85);
        color: white;
        border-radius: 5px;
        z-index: 5;
    }
    #addOpportunityModal .form-floating input.form-control:focus ~ label::before,
    #addOpportunityModal .form-floating input.form-control:not(:placeholder-shown) ~ label::before,
    #addOpportunityModal .form-floating select.form-select:focus ~ label::before,
    #addOpportunityModal .form-floating select.form-select:not([value=""]) ~ label::before,
    #addOpportunityModal .form-floating textarea.form-control:focus ~ label::before,
    #addOpportunityModal .form-floating textarea.form-control:not(:placeholder-shown) ~ label::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: #033c42;
        border-radius: 5px;
        z-index: -1;
    }
    #addOpportunityModal .form-floating input.form-control:focus::placeholder {
        color: transparent;
    }

    /* Dark mode styles */
    [data-bs-theme="dark"] #addOpportunityModal .form-floating input.form-control,
    [data-bs-theme="dark"] #addOpportunityModal .form-floating select.form-select,
    [data-bs-theme="dark"] #addOpportunityModal .form-floating textarea.form-control {
        border-color: #6c757d;
        color: #e9ecef;
    }
    
    [data-bs-theme="dark"] #addOpportunityModal .form-floating label {
        color: #adb5bd;
    }

    [data-bs-theme="dark"] #addOpportunityModal .form-floating input.form-control:focus,
    [data-bs-theme="dark"] #addOpportunityModal .form-floating input.form-control:not(:placeholder-shown),
    [data-bs-theme="dark"] #addOpportunityModal .form-floating select.form-select:focus,
    [data-bs-theme="dark"] #addOpportunityModal .form-floating select.form-select:not([value=""]),
    [data-bs-theme="dark"] #addOpportunityModal .form-floating textarea.form-control:focus,
    [data-bs-theme="dark"] #addOpportunityModal .form-floating textarea.form-control:not(:placeholder-shown) {
        border-color: #0dcaf0;
    }

    [data-bs-theme="dark"] #addOpportunityModal .form-floating input.form-control:focus ~ label::before,
    [data-bs-theme="dark"] #addOpportunityModal .form-floating input.form-control:not(:placeholder-shown) ~ label::before,
    [data-bs-theme="dark"] #addOpportunityModal .form-floating select.form-select:focus ~ label::before,
    [data-bs-theme="dark"] #addOpportunityModal .form-floating select.form-select:not([value=""]) ~ label::before,
    [data-bs-theme="dark"] #addOpportunityModal .form-floating textarea.form-control:focus ~ label::before,
    [data-bs-theme="dark"] #addOpportunityModal .form-floating textarea.form-control:not(:placeholder-shown) ~ label::before {
        background: #0dcaf0;
    }

    [data-bs-theme="dark"] #addOpportunityModal select.form-select option {
        background-color: #212529;
        color: #e9ecef;
    }

    /* Reset and simplify select styles */
    #addOpportunityModal .form-floating select.form-select {
        display: block;
        width: 100%;
        height: 40px;
        padding: 0.5rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #2f2f2f;
        background-color: transparent;
        border: 1px solid #2f2f2f;
        border-radius: 10px;
        transition: all 0.8s;
        appearance: none;
        background: url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'><path fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/></svg>") no-repeat right 0.75rem center/16px 12px;
    }

    [data-bs-theme="dark"] #addOpportunityModal .form-floating select.form-select {
        background: url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'><path fill='none' stroke='%23adb5bd' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/></svg>") no-repeat right 0.75rem center/16px 12px;
        background-color: transparent;
    }

    #addOpportunityModal .form-floating select.form-select:focus {
        border-color: #033c42;
        outline: 0;
        box-shadow: none;
    }

    #addOpportunityModal .form-floating select.form-select ~ label {
        padding: 0.5rem 0.75rem;
    }
</style>

<!-- Edit Opportunity Modal -->
<div class="modal fade" id="editOpportunityModal" tabindex="-1" aria-labelledby="editOpportunityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editOpportunityModalLabel">Edit Opportunity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Modal form fields -->
                <form id="editOpportunityForm" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="opportunity_id" id="editOpportunityId" value="">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="editOpportunityName" name="name" placeholder=" " required>
                                <label for="editOpportunityName">Opportunity Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="editAccount" name="account" placeholder=" " required>
                                <label for="editAccount">Account</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="editStage" name="stage" required>
                                    <option value="">Select Stage</option>
                                    <option value="Prospecting">Prospecting</option>
                                    <option value="Qualification">Qualification</option>
                                    <option value="Proposal">Proposal</option>
                                    <option value="Negotiation">Negotiation</option>
                                    <option value="Closed Won">Closed Won</option>
                                    <option value="Closed Lost">Closed Lost</option>
                                </select>
                                <label for="editStage">Stage</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="editOpportunityAmount" name="amount" step="0.01" placeholder=" " required>
                                <label for="editOpportunityAmount">Amount</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="editExpectedRevenue" name="expected_revenue" step="0.01" placeholder=" ">
                                <label for="editExpectedRevenue">Expected Revenue</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" id="editCloseDate" name="close_date" placeholder=" " required>
                                <label for="editCloseDate">Close Date</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="editProbability" name="probability" min="0" max="100" step="1" placeholder=" " required>
                                <label for="editProbability">Probability (%)</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <textarea class="form-control" id="editDescription" name="description" style="height: 100px" placeholder=" "></textarea>
                                <label for="editDescription">Description</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="editOpportunityForm">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Opportunity Modal -->
<div class="modal fade" id="deleteOpportunityModal" tabindex="-1" aria-labelledby="deleteOpportunityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteOpportunityModalLabel">Delete Opportunity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteOpportunityForm" method="POST" action="">
                @method('DELETE')
                @csrf
                <input type="hidden" name="opportunity_id" id="deleteOpportunityId">
                <div class="modal-body">
                    <p>Are you sure you want to delete this opportunity? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Opportunity Modal -->
<div class="modal fade" id="viewOpportunityModal" tabindex="-1" aria-labelledby="viewOpportunityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewOpportunityModalLabel">Opportunity Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Details about the selected opportunity will be shown here.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    #notificationArea {
        display: none;
        /* Initially hidden until a notification is shown */
    }

    .alert {
        margin-bottom: 1rem;
    }
</style>

@section('head')
    <!-- SheetJS for Excel export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <style>
        /* Your existing styles */
    </style>
@endsection

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('script')
<script>
// Function to fetch opportunities data
function fetchopportunityData(page = 1) {
    $.ajax({
        url: `/company/opportunities/all?page=${page}`, 
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log(response);
            let tableBody = '';
            
            if (response.data.length === 0) {
                tableBody = `<tr>
                    <td colspan="8" class="text-center">No opportunities found</td>
                </tr>`;
            } else {
                response.data.forEach(opportunity => {
                    // Determine badge class based on stage
                    let stageBadgeClass = '';
                    switch(opportunity.stage) {
                        case 'Prospecting': 
                            stageBadgeClass = 'bg-info text-white'; 
                            break;
                        case 'Qualification': 
                            stageBadgeClass = 'bg-warning text-white'; 
                            break;
                        case 'Proposal': 
                            stageBadgeClass = 'bg-primary text-white'; 
                            break;
                        case 'Negotiation': 
                            stageBadgeClass = 'bg-secondary text-white'; 
                            break;
                        case 'Closed Won': 
                            stageBadgeClass = 'bg-success text-white'; 
                            break;
                        case 'Closed Lost': 
                            stageBadgeClass = 'bg-danger text-white'; 
                            break;
                        default: 
                            stageBadgeClass = 'bg-light text-dark';
                    }
                    
                    // Format close date
                    let closeDate = opportunity.close_date ? 
                        new Date(opportunity.close_date).toLocaleDateString('en-US', {
                            month: 'short', 
                            day: 'numeric', 
                            year: 'numeric'
                        }) : 'N/A';
                    
                    // Format created date
                    let createdDate = new Date(opportunity.created_at);
                    let formattedCreatedDate = createdDate.toLocaleDateString('en-US', {
                        month: 'short', 
                        day: 'numeric', 
                        year: 'numeric'
                    });
                    
                    tableBody += `<tr data-opportunity-id="${opportunity.id}">
                        <td>
                            <a href="#" class="text-body fw-bold">${opportunity.name}</a>
                            <p class="text-muted mb-0">Created ${formattedCreatedDate}</p>
                        </td>
                        <td>${opportunity.account || 'N/A'}</td>
                        <td>
                            <span class="badge ${stageBadgeClass}">
                                ${opportunity.stage}
                            </span>
                        </td>
                        <td>${CurrencyHelper.format(opportunity.amount)}</td>
                        <td>${CurrencyHelper.format(opportunity.expected_revenue || 0)}</td>
                        <td>${closeDate}</td>
                        <td>
                            <div class="progress" style="width: 100px;">
                                <div class="progress-bar ${opportunity.probability <= 33 ? 'bg-danger' : opportunity.probability <= 66 ? 'bg-warning' : opportunity.probability <= 100 ? 'bg-success' : 'bg-success'}" role="progressbar" style="width: ${opportunity.probability}%"></div>
                            </div>
                            <span class="ms-2">${opportunity.probability}%</span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-primary btn-sm edit-opportunity me-1" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editOpportunityModal"
                                data-opportunity-id="${opportunity.id}"
                                data-opportunity-name="${opportunity.name}"
                                data-opportunity-account="${opportunity.account || ''}"
                                data-opportunity-amount="${opportunity.amount}"
                                data-opportunity-expected-revenue="${opportunity.expected_revenue || ''}"
                                data-opportunity-close-date="${opportunity.close_date ? opportunity.close_date.split('T')[0] : ''}"
                                data-opportunity-stage="${opportunity.stage}"
                                data-opportunity-probability="${opportunity.probability || ''}"
                                data-opportunity-description="${opportunity.description || ''}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm delete-opportunity" 
                                data-opportunity-id="${opportunity.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;
                });
            }

            $('#opportunities-table tbody').html(tableBody);

            // Generate Pagination
            let paginationHtml = '';
            
            //if (response.pagination.last_page > 1) {
                paginationHtml = `<li class="page-item ${response.pagination.prev_page_url ? '' : 'disabled'}">
                    <a class="page-link" href="#" onclick="return changePageOpportunity(event, ${response.pagination.current_page - 1})">Previous</a>
                </li>`;

                for (let i = 1; i <= response.pagination.last_page; i++) {
                    paginationHtml += `<li class="page-item ${response.pagination.current_page === i ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="return changePageOpportunity(event, ${i})">${i}</a>
                    </li>`;
                }

                paginationHtml += `<li class="page-item ${response.pagination.next_page_url ? '' : 'disabled'}">
                    <a class="page-link" href="#" onclick="return changePageOpportunity(event, ${response.pagination.current_page + 1})">Next</a>
                </li>`;
            //}

            $('#opportunity-pagination').html(paginationHtml);
            
            // Initialize tooltips for action buttons
            $('[data-toggle="tooltip"]').tooltip();
        },
        error: function(xhr, status, error) {
            console.error("Error:", status, error, xhr.responseText);
            // Show error message to user
            alert('Failed to load opportunities data. Please try again.');
        }
    });
}

// Page change function
function changePageOpportunity(event, page) {
    event.preventDefault();
    fetchopportunityData(page);
}


$(document).ready(function() {
    let isSubmitting = false;

    fetchopportunityData();
    function updateStats(stats) {
        // Update total value
        $('#totalValueCard .stat-value').text('{{ CurrencyHelper::symbol() }}' + parseFloat(stats.total_value).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        
        // Update open opportunities
        $('#openOpportunitiesCard .stat-value').text(stats.open_opportunities);
        $('#openOpportunitiesCard .stat-change span').text(stats.open_opportunities_percentage + '% of total');
        
        // Update win rate
        $('#winRateCard .stat-value').text(stats.win_rate + '%');
        
        // Update average deal size
        $('#avgDealSizeCard .stat-value').text('{{ CurrencyHelper::symbol() }}' + parseFloat(stats.avg_deal_size).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
    }

    // Remove any existing submit handlers
    $('#opportunityForm').off('submit');

    // Add form validation styles
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    function formatCurrency(amount) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 2
        }).format(amount);
    }

    function getStageClass(stage) {
        switch(stage) {
            case 'Prospecting': return 'bg-info text-white';
            case 'Qualification': return 'bg-warning text-white';
            case 'Proposal': return 'bg-primary text-white';
            case 'Negotiation': return 'bg-secondary text-white';
            case 'Closed Won': return 'bg-success text-white';
            case 'Closed Lost': return 'bg-danger text-white';
            default: return 'bg-secondary text-white';
        }
    }

    function getActionButtons(opportunity) {
        return `
            <button class="btn btn-primary btn-sm edit-opportunity me-1" 
                data-bs-toggle="modal" 
                data-bs-target="#editOpportunityModal"
                data-opportunity-id="${opportunity.id}"
                data-opportunity-name="${opportunity.name}"
                data-opportunity-account="${opportunity.account}"
                data-opportunity-amount="${opportunity.amount}"
                data-opportunity-expected-revenue="${opportunity.expected_revenue}"
                data-opportunity-close-date="${opportunity.close_date}"
                data-opportunity-stage="${opportunity.stage}"
                data-opportunity-probability="${opportunity.probability}">
                <i class="fas fa-edit"></i>
            </button>
            <button class="btn btn-danger btn-sm delete-opportunity" 
                data-opportunity-id="${opportunity.id}">
                <i class="fas fa-trash"></i>
            </button>
        `;
    }

    function updateOpportunityStats(opportunity) {
        // Get current stats
        let totalValue = parseFloat($('.stat-card.sales .stat-value').text().replace(/[^0-9.-]+/g, ''));
        let openOpportunities = parseInt($('.stat-card.pipeline .stat-value').text());
        let totalOpportunities = $('#opportunities-table tbody tr').length + 1;
        
        // Update total value
        totalValue += parseFloat(opportunity.amount);
        $('.stat-card.sales .stat-value').text('{{ CurrencyHelper::symbol() }}' + parseFloat(totalValue).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));

        // Update open opportunities if not closed
        if (!['Closed Won', 'Closed Lost'].includes(opportunity.stage)) {
            openOpportunities++;
            $('.stat-card.pipeline .stat-value').text(openOpportunities);
            
            // Update open opportunities percentage
            let openPercentage = (openOpportunities / totalOpportunities) * 100;
            $('.stat-card.pipeline .stat-change span').text(`${openPercentage.toFixed(1)}% of total`);
        }

        // Recalculate average deal size
        let avgDealSize = totalOpportunities > 0 
            ? totalValue / totalOpportunities 
            : 0;

        // Update the stats UI
        updateStats({
            total_value: totalValue,
            open_opportunities: openOpportunities,
            open_opportunities_percentage: openPercentage,
            avg_deal_size: avgDealSize,
            win_rate: $('#winRateCard .stat-value').text().replace('%', '')
        });

        // Show success message
        Swal.fire({
            title: 'Success!',
            text: 'Opportunity created successfully',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    }

    function addOpportunityToTable(opportunity) {
        const now = new Date();
        const closeDate = new Date(opportunity.close_date);
        
        const row = `
            <tr data-opportunity-id="${opportunity.id}">
                <td>
                    <a href="#" class="text-body fw-bold">${opportunity.name}</a>
                    <p class="text-muted mb-0">Created just now</p>
                </td>
                <td>${opportunity.account}</td>
                <td>
                    <span class="badge ${getStageClass(opportunity.stage)}">
                        ${opportunity.stage}
                    </span>
                </td>
                <td>${opportunity.amount_converted}</td>
                <td>${opportunity.expected_revenue_converted}</td>
                <td>${new Date(opportunity.close_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</td>
                <td>
                    <div class="progress" style="width: 100px;">
                        <div class="progress-bar ${opportunity.probability <= 33 ? 'bg-danger' : opportunity.probability <= 66 ? 'bg-warning' : opportunity.probability <= 100 ? 'bg-success' : 'bg-success'}" role="progressbar" style="width: ${opportunity.probability}%"></div>
                    </div>
                    <span class="ms-2">${opportunity.probability}%</span>
                </td>
                <td class="text-center">
                    ${getActionButtons(opportunity)}
                </td>
            </tr>
        `;
        
        // Add the new row to the beginning of the table
        $('#opportunities-table tbody').prepend(row);
    }

    function resetModalState() {
        // Remove the backdrop
        $('.modal-backdrop').remove();
        
        // Reset body classes and styles
        $('body')
            .removeClass('modal-open')
            .css({
                'padding-right': '',
                'overflow': 'auto',
                'height': 'auto'
            });
            
        // Reset html overflow
        $('html').css('overflow', 'auto');
        
        // Make sure modal is hidden
        $('.modal').modal('hide');
    }

    $('#opportunityForm').on('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const form = this;
        const formData = new FormData(form);
        const submitButton = $(form).find('button[type="submit"]');
        const originalText = submitButton.text();

        if (isSubmitting) {
            console.log('Form is already being submitted');
            return;
        }

        isSubmitting = true;
        submitButton.prop('disabled', true).text('Creating...');

        $.ajax({
            url: $(form).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-Request-ID': generateUUID(),
                'X-Submission-Time': new Date().toISOString()
            },
            success: function(response) {
                console.log('Success response:', response);
                isSubmitting = false;
                submitButton.prop('disabled', false).text(originalText);

                if (response.success) {
                    // Hide modal
                    $('#addOpportunityModal').modal('hide');
                    
                    // Clean up modal and restore scrolling
                    resetModalState();
                    
                    // Reset form
                    form.reset();
                    
                    // Refresh the table data to show the new opportunity
                    fetchopportunityData();
                    // Add opportunity to table and update stats
                    //addOpportunityToTable(response.opportunity);
                    //if (response.stats) {
                        //updateStats(response.stats);
                    //}

                    // Show success message
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr) {
                console.error('Error response:', xhr);
                isSubmitting = false;
                submitButton.prop('disabled', false).text(originalText);

                let errorMessage = 'An error occurred while creating the opportunity.';
                
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.errors) {
                        const errors = Object.values(xhr.responseJSON.errors).flat();
                        errorMessage = errors.join('\n');
                    } else if (xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                }

                Swal.fire({
                    title: 'Error!',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Also clean up when modal is hidden by other means (like clicking outside)
    $('#addOpportunityModal').on('hidden.bs.modal', function() {
        resetModalState();
    });

    function generateUUID() {
        return 'req_' + Math.random().toString(36).substr(2, 9) + '_' + Date.now();
    }

    $(document).on('click', '.delete-opportunity', function(e) {
        e.preventDefault();
        const opportunityId = $(this).data('opportunity-id');
        const $row = $(`tr[data-opportunity-id="${opportunityId}"]`);
        const amount = parseFloat($row.find('td:eq(3)').text().replace(/[^0-9.-]+/g, ''));
        const stage = $row.find('td:eq(2) .badge').text().trim();
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("company.opportunities.destroy", ["id" => "__ID__"]) }}'.replace('__ID__', opportunityId),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Remove the row from the table
                        $row.remove();

                        // Update stats
                        const totalOpportunities = $('#opportunities-table tbody tr').length;
                        const totalValue = parseFloat($('#totalValueCard .stat-value').text().replace(/[^0-9.-]+/g, '')) - amount;
                        const openOpportunities = !['Closed Won', 'Closed Lost'].includes(stage) 
                            ? parseInt($('#openOpportunitiesCard .stat-value').text()) - 1 
                            : parseInt($('#openOpportunitiesCard .stat-value').text());
                        const openPercentage = totalOpportunities > 0 
                            ? ((openOpportunities / totalOpportunities) * 100).toFixed(1) 
                            : 0;
                        const avgDealSize = totalOpportunities > 0 
                            ? totalValue / totalOpportunities 
                            : 0;

                        // Update the stats UI
                        updateStats({
                            total_value: totalValue,
                            open_opportunities: openOpportunities,
                            open_opportunities_percentage: openPercentage,
                            avg_deal_size: avgDealSize,
                            win_rate: response.win_rate || $('#winRateCard .stat-value').text().replace('%', '')
                        });

                        // Show success message
                        Swal.fire(
                            'Deleted!',
                            response.message || 'Opportunity has been deleted.',
                            'success'
                        );
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            xhr.responseJSON?.message || 'Failed to delete opportunity.',
                            'error'
                        );
                    }
                });
            }
        });
    });

    // Edit opportunity modal population
    $(document).on('click', '[data-bs-target="#editOpportunityModal"]', function() {
        const opportunity = {
            id: $(this).data('opportunity-id'),
            name: $(this).data('opportunity-name'),
            account: $(this).data('opportunity-account'),
            amount: $(this).data('opportunity-amount'),
            expected_revenue: $(this).data('opportunity-expected-revenue'),
            close_date: $(this).data('opportunity-close-date'),
            stage: $(this).data('opportunity-stage'),
            probability: $(this).data('opportunity-probability'),
            description: $(this).data('opportunity-description')
        };

        // Populate the form fields
        $('#editOpportunityId').val(opportunity.id);
        $('#editOpportunityName').val(opportunity.name);
        $('#editAccount').val(opportunity.account);
        $('#editOpportunityAmount').val(opportunity.amount);
        $('#editExpectedRevenue').val(opportunity.expected_revenue);
        $('#editCloseDate').val(opportunity.close_date);
        $('#editStage').val(opportunity.stage);
        $('#editProbability').val(opportunity.probability);
        $('#editDescription').val(opportunity.description);
    });

    // Handle edit form submission
    $('#editOpportunityForm').on('submit', function(e) {
        e.preventDefault();
        if (isSubmitting) return;
        
        isSubmitting = true;
        const form = $(this);
        const opportunityId = $('#editOpportunityId').val();
        
        $.ajax({
            url: `/company/opportunities/${opportunityId}`,
            method: 'PUT',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    // Update the row in the table
                    const opportunity = response.opportunity;
                    const row = $(`tr[data-opportunity-id="${opportunity.id}"]`);
                    
                    row.find('td:eq(0) a').text(opportunity.name);
                    row.find('td:eq(1)').text(opportunity.account);
                    row.find('td:eq(2) .badge')
                        .removeClass()
                        .addClass(`badge ${getStageClass(opportunity.stage)}`)
                        .text(opportunity.stage);
                    row.find('td:eq(3)').text(CurrencyHelper.format(opportunity.amount));
                    row.find('td:eq(4)').text(CurrencyHelper.format(opportunity.expected_revenue || 0));
                    row.find('td:eq(5)').text(new Date(opportunity.close_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }));
                    row.find('td:eq(6) .progress-bar').css('width', opportunity.probability + '%');
                    row.find('td:eq(6) span').text(opportunity.probability + '%');
                    
                    // Close modal and cleanup
                    resetModalState();

                    // Refresh the table data
                    fetchopportunityData();
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Opportunity updated successfully',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    
                    // Update stats if needed
                    if (response.stats) {
                        updateStats(response.stats);
                    }
                }
            },
            error: function(xhr) {
                // Cleanup modal state even on error
                resetModalState();
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: xhr.responseJSON?.message || 'Failed to update opportunity',
                });
            },
            complete: function() {
                isSubmitting = false;
            }
        });
    });

    // Also cleanup when modal is hidden
    $('#editOpportunityModal').on('hidden.bs.modal', function () {
        resetModalState();
    });

    // Search functionality
    $('#searchOpportunity').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        
        $('#opportunities-table tbody tr').each(function() {
            const row = $(this);
            const name = row.find('td:eq(0) a').text().toLowerCase();
            const account = row.find('td:eq(1)').text().toLowerCase();
            const stage = row.find('td:eq(2)').text().toLowerCase();
            const amount = row.find('td:eq(3)').text().toLowerCase();
            
            // Check if any of the fields contain the search term
            const matches = name.includes(searchTerm) || 
                          account.includes(searchTerm) || 
                          stage.includes(searchTerm) || 
                          amount.includes(searchTerm);
            
            // Show/hide row based on match
            row.toggle(matches);
        });
        
        // Update "No opportunities found" message
        const visibleRows = $('#opportunities-table tbody tr:visible').length;
        if (visibleRows === 0) {
            if ($('#no-results-message').length === 0) {
                $('#opportunities-table').after(
                    '<div id="no-results-message" class="alert alert-info text-center mt-3">' +
                    'No opportunities found matching your search.' +
                    '</div>'
                );
            }
        } else {
            $('#no-results-message').remove();
        }
        
        // Update stats based on visible rows
        updateStatsFromVisibleRows();
    });

    function updateStatsFromVisibleRows() {
        const visibleRows = $('#opportunities-table tbody tr:visible');
        
        // Calculate stats from visible rows
        let totalValue = 0;
        let openOpportunities = 0;
        let closedWon = 0;
        let closedLost = 0;
        
        visibleRows.each(function() {
            const row = $(this);
            const amount = parseFloat(row.find('td:eq(3)').text().replace(/[^0-9.-]+/g, ''));
            const stage = row.find('td:eq(2)').text().trim();
            
            totalValue += amount;
            
            if (!['Closed Won', 'Closed Lost'].includes(stage)) {
                openOpportunities++;
            }
            if (stage === 'Closed Won') {
                closedWon++;
            }
            if (stage === 'Closed Lost') {
                closedLost++;
            }
        });
        
        // Calculate percentages and averages
        const totalOpportunities = visibleRows.length;
        const openPercentage = totalOpportunities > 0 
            ? (openOpportunities / totalOpportunities) * 100 
            : 0;
        const avgDealSize = totalOpportunities > 0 
            ? totalValue / totalOpportunities 
            : 0;
        const winRate = (closedWon + closedLost) > 0 
            ? (closedWon / (closedWon + closedLost)) * 100 
            : 0;
            
        // Update the stats UI
        $('.stat-card.sales .stat-value').text('{{ CurrencyHelper::symbol() }}' + parseFloat(totalValue).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        $('.stat-card.pipeline .stat-value').text(openOpportunities);
        $('.stat-card.pipeline .stat-change span').text(`${openPercentage.toFixed(1)}% of total`);
        $('.stat-card.conversion .stat-value').text('{{ CurrencyHelper::symbol() }}' + parseFloat(avgDealSize).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        $('#winRateCard .stat-value').text(`${winRate.toFixed(1)}%`);
    }

    // Handle export
    let isExporting = false;
    $('#confirmExport').on('click', function(e) {
        e.preventDefault();
        if (isExporting) return;
        isExporting = true;
        
        const $exportBtn = $(this);
        const $modal = $('#exportOpportunitiesModal');
        $exportBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Exporting...');

        // Create and submit a form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("company.opportunities.export") }}';
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);
        
        // Add format
        const formatInput = document.createElement('input');
        formatInput.type = 'hidden';
        formatInput.name = 'format';
        formatInput.value = $('#exportFormat').val();
        form.appendChild(formatInput);
        
        // Add form to body and submit
        document.body.appendChild(form);
        form.submit();
        
        // Clean up form and modal
        setTimeout(() => {
            document.body.removeChild(form);
            isExporting = false;
            $exportBtn.prop('disabled', false).html('Export');
            
            // Properly hide modal and remove backdrop
            const modalInstance = bootstrap.Modal.getInstance($modal[0]);
            if (modalInstance) {
                modalInstance.hide();
            }
            
            // Remove any lingering backdrop
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').css('padding-right', '');
            
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Export started successfully',
                timer: 2000,
                showConfirmButton: false
            });
        }, 1000);
    });
});
    // Make CurrencyHelper available globally
   // Make CurrencyHelper available globally
   window.CurrencyHelper = {
            format: function(amount) {
                return '{{ CurrencyHelper::symbol() }}' + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }
    };


</script>
@endsection