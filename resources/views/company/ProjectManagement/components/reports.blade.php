
<div class="tab-pane fade" id="reports" role="tabpanel" aria-labelledby="reports-tab">
    @php
        // Get analytics data directly in the view - SIMPLE APPROACH like CRM
        $companyId = session('selected_company_id');
        $totalProjects = \App\Models\ProjectManagement\Project::where('company_id', $companyId)->count();
        
        // Active Tasks = pending + in_progress tasks
        $activeTasks = \App\Models\ProjectManagement\Task::whereHas('project', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->whereIn('status', ['pending', 'in_progress'])->count();
        
        // Budget Spent = total budget (since actual_cost is 0)
        $budgetSpent = \App\Models\ProjectManagement\Project::where('company_id', $companyId)->sum('budget');
        
        $teamMembers = \App\Models\TeamParing::where('company_id', $companyId)->count();
        
        // Project Status Distribution for pie chart
        $completedProjects = \App\Models\ProjectManagement\Project::where('company_id', $companyId)->where('status', 'completed')->count();
        $inProgressProjects = \App\Models\ProjectManagement\Project::where('company_id', $companyId)->where('status', 'in_progress')->count();
        $onHoldProjects = \App\Models\ProjectManagement\Project::where('company_id', $companyId)->where('status', 'on_hold')->count();
        $notStartedProjects = \App\Models\ProjectManagement\Project::where('company_id', $companyId)->where('status', 'not_started')->count();
        
        // Task Status Distribution for timeline
        $completedTasks = \App\Models\ProjectManagement\Task::whereHas('project', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->where('status', 'completed')->count();
        $pendingTasks = \App\Models\ProjectManagement\Task::whereHas('project', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->where('status', 'pending')->count();
        $inProgressTasks = \App\Models\ProjectManagement\Task::whereHas('project', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->where('status', 'in_progress')->count();
        
        // Task Priority Distribution
        $highPriorityTasks = \App\Models\ProjectManagement\Task::whereHas('project', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->where('priority', 'high')->count();
        $mediumPriorityTasks = \App\Models\ProjectManagement\Task::whereHas('project', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->where('priority', 'medium')->count();
        $lowPriorityTasks = \App\Models\ProjectManagement\Task::whereHas('project', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->where('priority', 'low')->count();
        
        // Get actual priority tasks for modal
        $highPriorityTasksData = \App\Models\ProjectManagement\Task::whereHas('project', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->where('priority', 'high')->with(['project', 'assignedTeam'])->get();
        
        $mediumPriorityTasksData = \App\Models\ProjectManagement\Task::whereHas('project', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->where('priority', 'medium')->with(['project', 'assignedTeam'])->get();
        
        $lowPriorityTasksData = \App\Models\ProjectManagement\Task::whereHas('project', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->where('priority', 'low')->with(['project', 'assignedTeam'])->get();
        
        // Calculate changes (simple approach - can be enhanced later)
        $highPriorityChange = $highPriorityTasks > 0 ? '+' . $highPriorityTasks : '0';
        $mediumPriorityChange = $mediumPriorityTasks > 0 ? '+' . $mediumPriorityTasks : '0';
        $lowPriorityChange = $lowPriorityTasks > 0 ? '+' . $lowPriorityTasks : '0';
    @endphp
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Project Analytics & Reports</h4>
                <div class="btn-group">
                    <button class="btn btn-outline-danger btn-sm" onclick="exportReports('pdf')">
                        <i class="fas fa-file-pdf me-1"></i> Export PDF
                    </button>
                    <button class="btn btn-outline-success btn-sm" onclick="exportReports('excel')">
                        <i class="fas fa-file-excel me-1"></i> Export Excel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card card-analytics h-100" style="--card-bg: var(--card-1-bg); --card-icon: var(--card-1-icon);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="stat-label">Total Projects</span>
                            <h3 class="stat-number mb-3">{{ $totalProjects }}</h3>
                            <span class="stat-change positive">
                                <i class="fas fa-arrow-up me-1"></i> 25% completion rate
                            </span>
                        </div>
                        <div class="icon-wrapper" style="color: var(--card-1-icon);">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                    </div>
                </div>
                <div class="card-wave"></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-analytics h-100" style="--card-bg: var(--card-2-bg); --card-icon: var(--card-2-icon);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="stat-label">Active Tasks</span>
                            <h3 class="stat-number mb-3">{{ $activeTasks }}</h3>
                            <span class="stat-change positive">
                                <i class="fas fa-arrow-up me-1"></i> 30% completion rate
                            </span>
                        </div>
                        <div class="icon-wrapper" style="color: var(--card-2-icon);">
                            <i class="fas fa-tasks"></i>
                        </div>
                    </div>
                </div>
                <div class="card-wave"></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-analytics h-100" style="--card-bg: var(--card-3-bg); --card-icon: var(--card-3-icon);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="stat-label">Total Budget</span>
                            <h3 class="stat-number mb-3">₵{{ number_format($budgetSpent, 2) }}</h3>
                            <span class="stat-change {{ $budgetSpent > 0 ? 'positive' : 'negative' }}">
                                <i class="fas fa-arrow-up me-1"></i> Just now
                            </span>
                        </div>
                        <div class="icon-wrapper" style="color: var(--card-3-icon);">
                            <i class="fas fa-coins"></i>
                        </div>
                    </div>
                </div>
                <div class="card-wave"></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-analytics h-100" style="--card-bg: var(--card-4-bg); --card-icon: var(--card-4-icon);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="stat-label">Teams</span>
                            <h3 class="stat-number mb-3">{{ $teamMembers }}</h3>
                            <span class="stat-meta">
                                <i class="fas fa-user-clock me-1"></i> 0 on leave
                            </span>
                        </div>
                        <div class="icon-wrapper" style="color: var(--card-4-icon);">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="card-wave"></div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-md-8">
            <div class="card card-analytics border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <i class="fas fa-chart-line text-primary me-2"></i>
                        Project Timeline
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="p-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-primary-light text-primary me-2">
                                    <i class="fas fa-circle me-1"></i> Active
                                </span>
                                <span class="text-muted small">Last updated: Just now</span>
                            </div>
                            {{-- <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="timelineFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter me-1"></i> Filter
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="timelineFilter">
                                    <li><a class="dropdown-item active" href="#">Last 6 Months</a></li>
                                    <li><a class="dropdown-item" href="#">This Year</a></li>
                                    <li><a class="dropdown-item" href="#">All Time</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">Custom Range</a></li>
                                </ul>
                            </div> --}}
                        </div>
                    </div>
                    <div class="chart-container p-3" style="position: relative; height: 300px;">
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);" id="chartLoader">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <canvas id="timelineChart" style="display: none;"></canvas>
                    </div>
                    <div class="card-footer bg-white border-top py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="d-flex align-items-center me-3">
                                    <span class="legend-indicator bg-success me-2" style="width: 10px; height: 10px; border-radius: 50%;"></span>
                                    <small class="text-muted">Completed</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="legend-indicator bg-primary me-2" style="width: 10px; height: 10px; border-radius: 50%;"></span>
                                    <small class="text-muted">Total</small>
                                </div>
                            </div>
                            <button class="btn btn-sm btn-link text-decoration-none">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Details Modal -->
            <div class="modal fade" id="timelineModal" tabindex="-1" aria-labelledby="timelineModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="timelineModalLabel">Timeline Details - <span id="selectedMonth"></span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-2 text-muted">Completed Tasks</h6>
                                            <h3 id="completedTasks" class="mb-0">0</h3>
                                            <small class="text-success">
                                                <i class="fas fa-arrow-up"></i> <span id="completedChange">0%</span> from previous month
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-2 text-muted">Total Tasks</h6>
                                            <h3 id="totalTasks" class="mb-0">0</h3>
                                            <small class="text-primary">
                                                <i class="fas fa-tasks"></i> <span id="completionRate">0%</span> completion rate
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h6>Task Breakdown</h6>
                                <div class="progress mb-2" style="height: 25px;">
                                    <div id="progressCompleted" class="progress-bar bg-success" role="progressbar" style="width: 0%">Completed</div>
                                    <div id="progressPending" class="progress-bar bg-warning" role="progressbar" style="width: 0%">Pending</div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">Completed: <span id="completedCount">0</span></small>
                                    <small class="text-muted">Pending: <span id="pendingCount">0</span></small>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">View Detailed Report</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-analytics h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <i class="fas fa-chart-pie text-primary me-2"></i>
                        Project Status
                        <button class="btn btn-sm btn-link ms-auto" data-bs-toggle="modal" data-bs-target="#statusModal">
                            <i class="fas fa-expand"></i>
                        </button>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="p-3">
                        <div class="chart-container" style="height: 200px;">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                    <div class="p-3 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success me-2">{{ $completedProjects }}</span>
                                <small>Completed</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-primary me-2">{{ $inProgressProjects }}</span>
                                <small>In Progress</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-warning me-2">{{ $onHoldProjects }}</span>
                                <small>On Hold</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Details Modal -->
        <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="statusModalLabel">Project Status Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="chart-container" style="height: 300px;">
                                    <canvas id="statusModalChart"></canvas>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex flex-column h-100 justify-content-center">
                                    <div class="mb-4">
                                        <h6 class="text-uppercase text-muted mb-3">Status Distribution</h6>
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="bg-success rounded me-2" style="width: 15px; height: 15px;"></div>
                                            <span class="me-3">Completed</span>
                                            <span class="ms-auto fw-bold">50%</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="bg-primary rounded me-2" style="width: 15px; height: 15px;"></div>
                                            <span class="me-3">In Progress</span>
                                            <span class="ms-auto fw-bold">33%</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="bg-warning rounded me-2" style="width: 15px; height: 15px;"></div>
                                            <span class="me-3">On Hold</span>
                                            <span class="ms-auto fw-bold">13%</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-secondary rounded me-2" style="width: 15px; height: 15px;"></div>
                                            <span class="me-3">Not Started</span>
                                            <span class="ms-auto fw-bold">4%</span>
                                        </div>
                                    </div>
                                    <div class="mt-auto">
                                        <div class="progress mb-2">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 33%" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 13%" aria-valuenow="13" aria-valuemin="0" aria-valuemax="100"></div>
                                            <div class="progress-bar bg-secondary" role="progressbar" style="width: 4%" aria-valuenow="4" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="text-center text-muted small">
                                            <div>Total Projects: <strong>24</strong></div>
                                            <div>On Track: <span class="text-success">12</span> | At Risk: <span class="text-warning">3</span> | Behind: <span class="text-danger">2</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h6 class="text-uppercase text-muted mb-3">Recent Activity</h6>
                            <div class="list-group list-group-flush">
                                <div class="list-group-item px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-primary bg-opacity-10 rounded-circle me-3">
                                            <i class="fas fa-check-circle text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">Website Redesign</h6>
                                                <small class="text-muted">2h ago</small>
                                            </div>
                                            <p class="mb-0 small text-muted">Marked as completed by John Doe</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-warning bg-opacity-10 rounded-circle me-3">
                                            <i class="fas fa-exclamation-triangle text-warning"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">Mobile App Development</h6>
                                                <small class="text-muted">5h ago</small>
                                            </div>
                                            <p class="mb-0 small text-muted">Delayed - Awaiting client feedback</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Export Report</button>
                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#sampleModal">
                            <i class="fas fa-question-circle me-1"></i> Show Sample
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Distribution -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-analytics">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Task Distribution by Priority</h5>
                        {{-- <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="priorityFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                This Month
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="priorityFilter">
                                <li><a class="dropdown-item active" href="#">This Month</a></li>
                                <li><a class="dropdown-item" href="#">Last Month</a></li>
                                <li><a class="dropdown-item" href="#">Last 3 Months</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Custom Range</a></li>
                            </ul>
                        </div> --}}
                    </div>
                    <div class="chart-container" style="height: 250px; position: relative;">
                        <div id="priorityChartLoader" class="text-center" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <canvas id="priorityChart" style="display: none;"></canvas>
                    </div>
                    <div class="mt-3">
                        <div class="priority-item mb-2 p-2 rounded hover-bg-light cursor-pointer" data-priority="high">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-danger me-2">
                                        <i class="fas fa-exclamation-circle me-1"></i> High
                                    </span>
                                    <span class="text-muted">{{ $highPriorityTasks }} tasks</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-light text-dark me-2">
                                        <i class="fas fa-arrow-up text-danger me-1"></i>{{ $highPriorityChange }}
                                    </span>
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </div>
                            </div>
                        </div>
                        <div class="priority-item mb-2 p-2 rounded hover-bg-light cursor-pointer" data-priority="medium">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-warning me-2">
                                        <i class="fas fa-exclamation me-1"></i> Medium
                                    </span>
                                    <span class="text-muted">{{ $mediumPriorityTasks }} tasks</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-light text-dark me-2">
                                        <i class="fas fa-arrow-up text-warning me-1"></i>{{ $mediumPriorityChange }}
                                    </span>
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </div>
                            </div>
                        </div>
                        <div class="priority-item p-2 rounded hover-bg-light cursor-pointer" data-priority="low">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-info me-2">
                                        <i class="far fa-arrow-down me-1"></i> Low
                                    </span>
                                    <span class="text-muted">{{ $lowPriorityTasks }} tasks</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-light text-dark me-2">
                                        <i class="fas fa-arrow-up text-info me-1"></i>{{ $lowPriorityChange }}
                                    </span>
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Priority Tasks Modal -->
    <div class="modal fade" id="priorityTasksModal" tabindex="-1" aria-labelledby="priorityTasksModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="priorityTasksModalLabel">
                        <i class="fas fa-tasks me-2"></i>
                        <span id="modalPriorityTitle">High Priority Tasks</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-tasks me-1"></i>
                                <span id="modalTaskCount">12</span> Tasks
                            </span>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#exportModal" data-export-type="tasks">
                                <i class="fas fa-download me-1"></i> Export
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Task</th>
                                    <th>Project</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Assignee</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="priorityTasksList">
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Showing <span id="showingCount">5</span> of <span id="totalCount">12</span> tasks
                        </div>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Sample Modal -->
    <div class="modal fade" id="sampleModal" tabindex="-1" aria-labelledby="sampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="sampleModalLabel">
                        <i class="fas fa-info-circle me-2"></i>Sample Information
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="avatar avatar-xl bg-primary bg-opacity-10 rounded-circle mb-3">
                            <i class="fas fa-chart-pie text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <h4>Project Status Overview</h4>
                        <p class="text-muted">This is a sample modal showing project status information</p>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="text-primary mb-2">
                                        <i class="fas fa-check-circle fa-2x"></i>
                                    </div>
                                    <h3 class="mb-1">12</h3>
                                    <p class="text-muted mb-0">Completed</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="text-warning mb-2">
                                        <i class="fas fa-spinner fa-spin fa-2x"></i>
                                    </div>
                                    <h3 class="mb-1">8</h3>
                                    <p class="text-muted mb-0">In Progress</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h6>Quick Actions</h6>
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary">
                                <i class="fas fa-plus me-2"></i>Add New Project
                            </button>
                            <button class="btn btn-outline-secondary">
                                <i class="fas fa-download me-2"></i>Export Data
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Close
                    </button>
                    <button type="button" class="btn btn-primary">
                        <i class="fas fa-check me-1"></i> Got it!
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportModalLabel">
                    <i class="fas fa-file-export me-2"></i>
                    <span id="exportTitle">Export Tasks</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="exportForm">
                    <input type="hidden" id="exportType" name="type" value="">
                    
                    <div class="mb-3">
                        <label class="form-label">Format</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="format" id="formatCsv" value="csv" checked>
                            <label class="form-check-label d-flex align-items-center" for="formatCsv">
                                <i class="fas fa-file-csv text-info me-2"></i> CSV File
                            </label>
                        </div>
                    </div>
                    
                    {{-- <div class="mb-3">
                        <label for="dateRange" class="form-label">Date Range</label>
                        <select class="form-select" id="dateRange">
                            <option value="all">All Time</option>
                            <option value="today" selected>Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="quarter">This Quarter</option>
                            <option value="year">This Year</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div> --}}
                    
                    {{-- <div class="row mb-3" id="customDateRange" style="display: none;">
                        <div class="col-md-6">
                            <label for="startDate" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="startDate">
                        </div>
                        <div class="col-md-6">
                            <label for="endDate" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="endDate">
                        </div>
                    </div> --}}
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="includeCompleted" checked>
                        <label class="form-check-label" for="includeCompleted">Include Completed Tasks</label>
                    </div>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="includeDetails" checked>
                        <label class="form-check-label" for="includeDetails">Include Task Details</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="exportSubmit">
                    <i class="fas fa-download me-1"></i> Export
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery, Chart.js, and SweetAlert2 -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Export Reports Function - Global -->
<script>
let isExporting = false;

function exportReports(format) {
    console.log('Export Reports function called with format:', format);
    
    if (isExporting) {
        console.log('Export already in progress');
        return;
    }
    
            // Show confirmation dialog first
            Swal.fire({
                title: 'Export Project Analytics & Reports',
                text: `Do you want to export all Project Analytics & Reports as ${format.toUpperCase()}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, export',
                cancelButtonText: 'Cancel',
                backdrop: false,
                allowOutsideClick: true
            }).then((result) => {
        if (result.isConfirmed) {
            isExporting = true;
            
            // Show SweetAlert loading
            Swal.fire({
                title: 'Exporting...',
                text: `Exporting Project Analytics & Reports as ${format.toUpperCase()}`,
                icon: 'info',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                backdrop: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Create form and submit to export endpoint
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("company.pmreports.export") }}';
            form.style.display = 'none';
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Add export data
            const exportData = {
                type: 'reports',
                format: format,
                priority: 'all',
                dateRange: 'all',
                includeCompleted: true,
                includeDetails: true
            };
            
            // Add form data
            Object.keys(exportData).forEach(key => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = exportData[key];
                form.appendChild(input);
            });
            
            document.body.appendChild(form);
            form.submit();
            
            // Show success message after a short delay
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Export Started!',
                    text: 'Your Project Analytics & Reports export has been initiated. The file download should start shortly.',
                    timer: 3000,
                    showConfirmButton: false,
                    timerProgressBar: true,
                    backdrop: false
                });
                
                // Clean up form
                document.body.removeChild(form);
                isExporting = false;
                
                // Reload page after export to return to normal state
                setTimeout(() => {
                    window.location.reload();
                }, 3500);
            }, 1500);
        }
    });
}
</script>

<!-- Analytics data from backend -->
<script>
window.analyticsData = {
    statusDistribution: {
        completed: {{ $completedProjects }},
        in_progress: {{ $inProgressProjects }},
        on_hold: {{ $onHoldProjects }},
        not_started: {{ $notStartedProjects }}
    },
    taskDistribution: {
        completed: {{ $completedTasks }},
        pending: {{ $pendingTasks }},
        in_progress: {{ $inProgressTasks }}
    },
    priorityDistribution: {
        high: {{ $highPriorityTasks }},
        medium: {{ $mediumPriorityTasks }},
        low: {{ $lowPriorityTasks }}
    },
    priorityTasksData: {
        high: {!! json_encode($highPriorityTasksData->map(function($task) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'project' => $task->project->name ?? 'No Project',
                'dueDate' => $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : 'No due date',
                'status' => ucfirst(str_replace('_', ' ', $task->status)),
                'assignee' => $task->assignedTeam->team_name ?? 'Unassigned'
            ];
        })) !!},
        medium: {!! json_encode($mediumPriorityTasksData->map(function($task) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'project' => $task->project->name ?? 'No Project',
                'dueDate' => $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : 'No due date',
                'status' => ucfirst(str_replace('_', ' ', $task->status)),
                'assignee' => $task->assignedTeam->team_name ?? 'Unassigned'
            ];
        })) !!},
        low: {!! json_encode($lowPriorityTasksData->map(function($task) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'project' => $task->project->name ?? 'No Project',
                'dueDate' => $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : 'No due date',
                'status' => ucfirst(str_replace('_', ' ', $task->status)),
                'assignee' => $task->assignedTeam->team_name ?? 'Unassigned'
            ];
        })) !!}
    },
    timelineData: {
        months: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        completed: [{{ $completedTasks }}, 0, 0, 0, 0, 0],
        in_progress: [{{ $inProgressTasks }}, 0, 0, 0, 0, 0],
        planned: [{{ $pendingTasks }}, 0, 0, 0, 0, 0],
        delayed: [0, 0, 0, 0, 0, 0]
    }
};
</script>

<script>

$(document).ready(function() {
    // Project Status Chart
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'In Progress', 'On Hold', 'Not Started'],
                datasets: [{
                    data: [
                        window.analyticsData.statusDistribution.completed,
                        window.analyticsData.statusDistribution.in_progress,
                        window.analyticsData.statusDistribution.on_hold,
                        window.analyticsData.statusDistribution.not_started
                    ],
                    backgroundColor: ['#28a745', '#007bff', '#ffc107', '#6c757d']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    } else {
        console.error('Status chart canvas element not found!');
    }

    // Timeline Chart
    const timelineCanvas = document.getElementById('timelineChart');
    if (timelineCanvas) {
        const timelineCtx = timelineCanvas.getContext('2d');
        const months = window.analyticsData.timelineData.months;
        const completedData = window.analyticsData.timelineData.completed;
        const inProgressData = window.analyticsData.timelineData.in_progress;
        const plannedData = window.analyticsData.timelineData.planned;
        const delayedData = window.analyticsData.timelineData.delayed;
        const milestones = [
            { month: 1, label: 'Phase 1', description: 'Requirements Finalized' },
            { month: 3, label: 'Phase 2', description: 'Development Started' },
            { month: 5, label: 'Phase 3', description: 'Testing Phase' }
        ];

        timelineCanvas.style.display = 'block';
        document.getElementById('chartLoader').style.display = 'none';

        const timelineChart = new Chart(timelineCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Completed',
                        data: completedData,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        borderWidth: 2,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#28a745',
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: '#28a745',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        order: 3
                    },
                    {
                        label: 'In Progress',
                        data: inProgressData,
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#007bff',
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: '#007bff',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 2,
                        tension: 0.3,
                        fill: false,
                        order: 2
                    },
                    {
                        label: 'Planned',
                        data: plannedData,
                        borderColor: '#6c757d',
                        backgroundColor: 'rgba(108, 117, 125, 0.1)',
                        borderWidth: 1,
                        borderDash: [3, 3],
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#6c757d',
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: '#6c757d',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 2,
                        tension: 0.3,
                        fill: false,
                        order: 1
                    },
                    {
                        label: 'Delayed',
                        data: delayedData,
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        borderWidth: 2,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#dc3545',
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: '#dc3545',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 2,
                        tension: 0.1,
                        fill: false,
                        order: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: 'index' },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Number of Tasks', font: { weight: 'bold' } },
                        grid: { display: true, color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: { stepSize: 10 }
                    },
                    x: {
                        grid: { display: false },
                        title: { display: true, text: 'Timeline (2023)', font: { weight: 'bold' } }
                    }
                },
                plugins: {
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.85)',
                        titleFont: { size: 14, weight: 'bold', family: 'system-ui, -apple-system, sans-serif' },
                        bodyFont: { size: 13, family: 'system-ui, -apple-system, sans-serif' },
                        padding: 12,
                        displayColors: true,
                        usePointStyle: true,
                        callbacks: {
                            label: function(context) {
                                const label = context.dataset.label || '';
                                const value = context.parsed.y;
                                let additional = '';
                                if (context.dataset.label === 'Completed' && context.datasetIndex === 0) {
                                    const total = plannedData[context.dataIndex];
                                    const percentage = Math.round((value / total) * 100);
                                    additional = ` (${percentage}% of planned)`;
                                }
                                if (context.dataset.label === 'Delayed' && value > 0) {
                                    additional = ' - Needs attention!';
                                }
                                return `${label}: ${value}${additional}`;
                            },
                            afterLabel: function(context) {
                                const milestone = milestones.find(m => m.month === context.dataIndex);
                                if (milestone) {
                                    return [`📌 ${milestone.label}`, milestone.description];
                                }
                                return null;
                            }
                        }
                    },
                    legend: {
                        position: 'bottom',
                        labels: { usePointStyle: true, padding: 20, boxWidth: 10, font: { size: 12 } }
                    },
                    annotation: {
                        annotations: {
                            line1: {
                                type: 'line',
                                yMin: 0,
                                yMax: 0,
                                borderColor: 'rgba(255, 99, 132, 0.5)',
                                borderWidth: 2,
                                borderDash: [5, 5],
                                label: {
                                    content: 'Today',
                                    enabled: true,
                                    position: 'top',
                                    backgroundColor: 'rgba(255, 99, 132, 0.8)',
                                    color: '#fff',
                                    font: { weight: 'bold', size: 12 },
                                    padding: 5,
                                    borderRadius: 4
                                }
                            }
                        }
                    }
                },
                onClick: (e) => {
                    const points = timelineChart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, true);
                    if (points.length) {
                        const firstPoint = points[0];
                        const monthIndex = firstPoint.index;
                        const completed = completedData[monthIndex];
                        const inProgress = inProgressData[monthIndex];
                        const planned = plannedData[monthIndex];
                        const delayed = delayedData[monthIndex];
                        const completionRate = Math.round((completed / planned) * 100);
                        const prevMonthCompleted = monthIndex > 0 ? completedData[monthIndex - 1] : completed;
                        const change = monthIndex > 0 ? Math.round(((completed - prevMonthCompleted) / prevMonthCompleted) * 100) : 0;

                        document.getElementById('selectedMonth').textContent = months[monthIndex];
                        document.getElementById('completedTasks').textContent = completed;
                        document.getElementById('totalTasks').textContent = planned;
                        document.getElementById('completedChange').textContent = change + '%';
                        document.getElementById('completedChange').parentElement.className = change >= 0 ? 'text-success' : 'text-danger';
                        document.getElementById('completedChange').previousElementSibling.className = change >= 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down';
                        document.getElementById('completionRate').textContent = completionRate + '%';
                        document.getElementById('completedCount').textContent = completed;
                        document.getElementById('pendingCount').textContent = planned - completed;
                        document.getElementById('progressCompleted').style.width = completionRate + '%';
                        document.getElementById('progressPending').style.width = (100 - completionRate) + '%';

                        const modal = new bootstrap.Modal(document.getElementById('timelineModal'), {
                            backdrop: 'static',
                            keyboard: true
                        });
                        modal.show();
                    }
                }
            }
        });
    } else {
        console.error('Timeline chart canvas element not found!');
    }

    // Status Modal Chart
    const statusModal = document.getElementById('statusModal');
    let statusModalChart = null;
    if (statusModal) {
        statusModal.addEventListener('shown.bs.modal', function () {
            const statusModalCtx = document.getElementById('statusModalChart');
            if (statusModalCtx && !statusModalChart) {
                statusModalChart = new Chart(statusModalCtx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Completed', 'In Progress', 'On Hold', 'Not Started'],
                        datasets: [{
                            data: [12, 8, 3, 1],
                            backgroundColor: ['#28a745', '#007bff', '#ffc107', '#6c757d'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleFont: { size: 14, weight: 'bold' },
                                bodyFont: { size: 13 },
                                padding: 12,
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = Math.round((value / total) * 100);
                                        return `${label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    } else {
        console.error('Status modal element not found!');
    }

    // Priority Chart
    const priorityCtx = document.getElementById('priorityChart');
    if (priorityCtx) {
        const priorityData = { 
            high: window.analyticsData.priorityDistribution.high, 
            medium: window.analyticsData.priorityDistribution.medium, 
            low: window.analyticsData.priorityDistribution.low 
        };
        const totalTasks = Object.values(priorityData).reduce((a, b) => a + b, 0);

        setTimeout(() => {
            document.getElementById('priorityChartLoader').style.display = 'none';
            priorityCtx.style.display = 'block';

            const priorityChart = new Chart(priorityCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['High', 'Medium', 'Low'],
                    datasets: [{
                        data: [priorityData.high, priorityData.medium, priorityData.low],
                        backgroundColor: ['#dc3545', '#ffc107', '#0dcaf0'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    layout: { padding: 10 },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw;
                                    const percentage = Math.round((value / totalTasks) * 100);
                                    return `${label}: ${value} tasks (${percentage}%)`;
                                }
                            }
                        }
                    },
                    onClick: (e, elements) => {
                        if (elements.length > 0) {
                            const index = elements[0].index;
                            const priority = ['high', 'medium', 'low'][index];
                            
                            // Show confirmation dialog first
                            Swal.fire({
                                title: 'View Priority Tasks',
                                text: `Do you want to view ${priority} priority tasks?`,
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, view tasks',
                                cancelButtonText: 'Cancel',
                                backdrop: false,
                                allowOutsideClick: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    openPriorityModal(priority);
                                }
                            });
                        }
                    }
                }
            });
            window.priorityChart = priorityChart;
        }, 500);
    } else {
        console.error('Priority chart canvas element not found!');
    }

    // Task Data
    // Task Data - Using real data from database
    const priorityTasks = window.analyticsData.priorityTasksData;

    const statusClasses = {
        'To Do': 'bg-light text-dark',
        'In Progress': 'bg-primary text-white',
        'In Review': 'bg-info text-dark',
        'Done': 'bg-success text-white',
        'Overdue': 'bg-danger text-white',
        'Planning': 'bg-secondary text-white'
    };

    function openPriorityModal(priority) {
        document.getElementById('modalPriorityTitle').textContent = `${priority.charAt(0).toUpperCase() + priority.slice(1)} Priority Tasks`;
        document.getElementById('modalTaskCount').textContent = priorityTasks[priority].length;
        renderTasks(priority);
        new bootstrap.Modal(document.getElementById('priorityTasksModal')).show();
    }

    function renderTasks(priority) {
        const tasks = priorityTasks[priority] || [];
        const tbody = document.getElementById('priorityTasksList');
        tbody.innerHTML = '';
        tasks.forEach(task => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="task-${task.id}">
                        <label class="form-check-label" for="task-${task.id}">${task.title}</label>
                    </div>
                </td>
                <td>${task.project}</td>
                <td>${task.dueDate} <span class="text-muted small">${getDaysRemaining(task.dueDate)}</span></td>
                <td><span class="badge ${statusClasses[task.status] || 'bg-light text-dark'}">${task.status}</span></td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-xs me-2">
                            <span class="avatar-text rounded-circle bg-primary text-white">${getInitials(task.assignee)}</span>
                        </div>
                        ${task.assignee}
                    </div>
                </td>
                <td class="text-end">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-link text-muted p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="far fa-edit me-2"></i>Edit</a></li>
                            <li><a class="dropdown-item" href="#"><i class="far fa-trash-alt me-2"></i>Delete</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i class="far fa-flag me-2"></i>Flag</a></li>
                        </ul>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
        document.getElementById('showingCount').textContent = Math.min(5, tasks.length);
        document.getElementById('totalCount').textContent = tasks.length;
    }

    function getDaysRemaining(dueDate) {
        const today = new Date();
        const due = new Date(dueDate);
        const diffTime = due - today;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        if (diffDays === 0) return '(Today)';
        if (diffDays === 1) return '(Tomorrow)';
        if (diffDays < 0) return `(${Math.abs(diffDays)} days overdue)`;
        return `(in ${diffDays} days)`;
    }

    function getInitials(name) {
        return name.split(' ').map(n => n[0]).join('').toUpperCase();
    }

    // Export Reports Function - moved to global scope above

        // Initialize export modal
    const exportModal = document.getElementById('exportModal');
    if (exportModal) {
        // Handle date range visibility (commented out since date range is disabled)
        // document.getElementById('dateRange').addEventListener('change', function() {
        //     document.getElementById('customDateRange').style.display = 
        //         this.value === 'custom' ? 'flex' : 'none';
        // });

        // Handle export button click
        document.getElementById('exportSubmit').addEventListener('click', function() {
            const format = document.querySelector('input[name="format"]:checked').value;
            const dateRange = 'all'; // Default to all time since date range is commented out
            const includeCompleted = document.getElementById('includeCompleted').checked;
            const includeDetails = document.getElementById('includeDetails').checked;
            
            // Get the export type from the button that opened the modal
            const exportType = document.getElementById('exportType').value;
            
            // Get current priority from the modal title or data attribute
            const modalTitle = document.getElementById('modalPriorityTitle').textContent;
            let priority = 'all';
            if (modalTitle.includes('High')) priority = 'high';
            else if (modalTitle.includes('Medium')) priority = 'medium';
            else if (modalTitle.includes('Low')) priority = 'low';
            
            // Close the modal first
            const modal = bootstrap.Modal.getInstance(exportModal);
            modal.hide();
            
            // Show confirmation dialog first
            Swal.fire({
                title: 'Export Priority Tasks',
                text: `Do you want to export ${priority} priority tasks as ${format.toUpperCase()}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, export',
                cancelButtonText: 'Cancel',
                backdrop: false,
                allowOutsideClick: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Prepare export data
                    const exportData = {
                        type: exportType,
                        format: format,
                        priority: priority,
                        dateRange: dateRange,
                        includeCompleted: includeCompleted,
                        includeDetails: includeDetails
                    };
                    
                    // Show SweetAlert loading
                    Swal.fire({
                        title: 'Exporting...',
                        text: `Exporting ${priority} priority tasks as ${format.toUpperCase()}`,
                        icon: 'info',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        backdrop: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Create form and submit to export endpoint
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("company.pmreports.export") }}';
                    form.style.display = 'none';
                    
                    // Add CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    // Add form data
                    Object.keys(exportData).forEach(key => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = exportData[key];
                        form.appendChild(input);
                    });
                    
                    document.body.appendChild(form);
                    form.submit();
                    
                    // Show success message after a short delay
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Export Started!',
                            text: `Your ${priority} priority tasks export has been initiated. The file download should start shortly.`,
                            timer: 3000,
                            showConfirmButton: false,
                            timerProgressBar: true,
                            backdrop: false
                        });
                        
                        // Clean up form
                        document.body.removeChild(form);
                        
                        // Reload page after export to return to normal state
                        setTimeout(() => {
                            window.location.reload();
                        }, 3500);
                    }, 1500);
                }
            });
        });
        
        // Set up modal show event to update the export type
        exportModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const exportType = button.getAttribute('data-export-type') || 'data';
            document.getElementById('exportType').value = exportType;
            document.getElementById('exportTitle').textContent = `Export ${exportType.charAt(0).toUpperCase() + exportType.slice(1)}`;
        });
        
        // Set default dates for custom date range
        const today = new Date();
        const oneWeekAgo = new Date();
        oneWeekAgo.setDate(today.getDate() - 7);
        
        document.getElementById('startDate').valueAsDate = oneWeekAgo;
        document.getElementById('endDate').valueAsDate = today;
    }
    
    // Initialize priority item click handlers
    document.querySelectorAll('.priority-item').forEach(item => {
        item.addEventListener('click', function() {
            const priority = this.getAttribute('data-priority');
            
            // Show confirmation dialog first
            Swal.fire({
                title: 'View Priority Tasks',
                text: `Do you want to view ${priority} priority tasks?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, view tasks',
                cancelButtonText: 'Cancel',
                backdrop: false,
                allowOutsideClick: true
            }).then((result) => {
                if (result.isConfirmed) {
                    openPriorityModal(priority);
                }
            });
        });
    });
});
</script>
