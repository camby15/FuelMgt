<!-- Header with Actions -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="header-title mb-1">Attendance Management</h4>
        <p class="text-muted">Track and manage employee attendance records</p>
    </div>
    <div class="d-flex flex-column flex-sm-row w-100 w-md-auto gap-2">
      <div class="input-group flex-grow-1" style="min-width: 220px;">
    <span class="input-group-text bg-white"><i class="fas fa-calendar-alt text-muted"></i></span>
    <input type="date" class="form-control border-start-0" id="attendanceDate">
</div>

        <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-sm-auto">
            <button class="btn btn-primary d-flex align-items-center justify-content-center" id="markAttendanceBtn" data-bs-toggle="modal" data-bs-target="#markAttendanceModal">
                <i class="fas fa-user-check me-1"></i>
                <span class="d-none d-sm-inline">Mark Attendance</span>
            </button>
            <button class="btn btn-outline-primary d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#bulkAttendanceModal">
                <i class="fas fa-users me-1"></i>
                <span class="d-none d-sm-inline">Bulk Update</span>
            </button>
            <button class="btn btn-outline-primary d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#syncDeviceModal">
                <i class="fas fa-sync-alt me-1"></i>
                <span class="d-none d-sm-inline">Sync Device</span>
            </button>
            <button class="btn btn-outline-primary d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#importAttendanceModal">
                <i class="fas fa-file-import me-1"></i>
                <span class="d-none d-sm-inline">Import</span>
            </button>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <!-- Present Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 overflow-hidden position-relative" style="background: linear-gradient(45deg, #f8f9ff, #eef2ff);">
            <div class="position-absolute top-0 end-0 m-3">
                <span class="badge rounded-pill bg-success bg-opacity-10 text-success" id="presentTrend">
                    <i class="fas fa-caret-up me-1"></i>5.2%
                </span>
            </div>
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-muted small fw-semibold mb-2">Present Today</h6>
                        <h2 class="mb-1 text-primary" id="presentCount"></h2>
                        <p class="text-muted small mb-0">
                            <span class="text-success"><i class="fas fa-user-check me-1"></i>On Time: <span id="onTimeCount"></span></span>
                            <span class="ms-2">
                                <i class="fas fa-user-clock text-warning me-1"></i>Late: <span id="lateCount">0</span>
                            </span>
                        </p>
                    </div>
                    <div class="icon-shape bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                        <i class="fas fa-user-check fs-3"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top">
                    <div class="progress bg-soft-primary" style="height: 6px;">
                        <div class="progress-bar bg-primary" role="progressbar" id="presentProgress" style="width: 85%;" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-muted" id="presentPercentage">85% of team</small>
                        <small class="text-muted" id="presentVsYesterday">vs 79.8% yesterday</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Late Arrivals Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 overflow-hidden position-relative" style="background: linear-gradient(45deg, #fff8f5, #fff0eb);">
            <div class="position-absolute top-0 end-0 m-3">
                <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning" id="lateTrend">
                    <i class="fas fa-caret-up me-1"></i>2
                </span>
            </div>
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-muted small fw-semibold mb-2">Late Arrivals</h6>
                        <h2 class="mb-1 text-warning" id="lateTotal">0</h2>
                        <p class="text-muted small mb-0">
                            <span class="text-warning"><i class="fas fa-clock me-1"></i>Avg. <span id="avgLateMinutes">12</span> min late</span>
                        </p>
                    </div>
                    <div class="icon-shape bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                        <i class="fas fa-clock fs-3"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top">
                    <div class="progress bg-soft-warning" style="height: 6px;">
                        <div class="progress-bar bg-warning" role="progressbar" id="lateProgress" style="width: 15%;" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-muted" id="latePercentage">15% of team</small>
                        <small class="text-muted" id="lateVsYesterday">vs 13% yesterday</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Absent Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 overflow-hidden position-relative" style="background: linear-gradient(45deg, #fff5f8, #ffebf1);">
            <div class="position-absolute top-0 end-0 m-3">
                <span class="badge rounded-pill bg-success bg-opacity-10 text-success" id="absentTrend">
                    <i class="fas fa-caret-down me-1"></i>3
                </span>
            </div>
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-muted small fw-semibold mb-2">Absent Today</h6>
                        <h2 class="mb-1 text-danger" id="absentCount">0</h2>
                        <p class="text-muted small mb-0">
                            <span class="text-danger"><i class="fas fa-user-slash me-1"></i>No notice: <span id="noNoticeCount">2</span></span>
                        </p>
                    </div>
                    <div class="icon-shape bg-danger bg-opacity-10 text-danger rounded-3 p-3">
                        <i class="fas fa-user-times fs-3"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top">
                    <div class="progress bg-soft-danger" style="height: 6px;">
                        <div class="progress-bar bg-danger" role="progressbar" id="absentProgress" style="width: 10%;" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-muted" id="absentPercentage">10% of team</small>
                        <small class="text-muted" id="absentVsYesterday">vs 13% yesterday</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- On Leave Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 overflow-hidden position-relative" style="background: linear-gradient(45deg, #f0f9ff, #e6f4ff);">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-muted small fw-semibold mb-2">On Leave</h6>
                        <h2 class="mb-1 text-info" id="onLeaveCount">0</h2>
                        <p class="text-muted small mb-0">
                            <span class="text-info"><i class="fas fa-umbrella-beach me-1"></i>Annual: <span id="annualLeaveCount">2</span></span>
                            <span class="ms-2">
                                <i class="fas fa-procedures text-info me-1"></i>Sick: <span id="sickLeaveCount">1</span>
                            </span>
                        </p>
                    </div>
                    <div class="icon-shape bg-info bg-opacity-10 text-info rounded-3 p-3">
                        <i class="fas fa-umbrella-beach fs-3"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Leave Balance</h6>
                            <div class="d-flex align-items-center">
                                <div class="progress bg-soft-info flex-grow-1 me-2" style="height: 6px; width: 80px;">
                                    <div class="progress-bar bg-info" role="progressbar" id="leaveProgress" style="width: 65%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted" id="leavePercentage">65%</small>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-soft-info" data-bs-toggle="modal" data-bs-target="#leaveCalendarModal" data-bs-toggle="tooltip" title="View Leave Calendar">
                            <i class="fas fa-calendar-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Table Section -->
<div class="row g-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center p-4 border-bottom">
                    <div class="mb-3 mb-md-0">
                        <h5 class="card-title mb-1">Today's Attendance</h5>
                        <p class="text-muted small" id="tableDate">Sunday, June 22, 2025</p>
                    </div>
                    <div class="d-flex flex-column flex-sm-row w-100 w-md-auto gap-2">
                        <div class="input-group input-group-sm flex-grow-1" style="min-width: 200px;">
                            <span class="input-group-text bg-transparent"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" class="form-control" id="searchEmployee" placeholder="Search employee...">
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary d-flex align-items-center justify-content-center gap-1" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-filter"></i>
                                <span>Filter</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                                <li><h6 class="dropdown-header">Status</h6></li>
                                <li><a class="dropdown-item filter-status" href="#" data-status="present"><i class="fas fa-circle text-success me-2 small"></i>Present</a></li>
                                <li><a class="dropdown-item filter-status" href="#" data-status="late"><i class="fas fa-circle text-warning me-2 small"></i>Late</a></li>
                                <li><a class="dropdown-item filter-status" href="#" data-status="absent"><i class="fas fa-circle text-danger me-2 small"></i>Absent</a></li>
                                <li><a class="dropdown-item filter-status" href="#" data-status="on_leave"><i class="fas fa-circle text-info me-2 small"></i>On Leave</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Department</h6></li>
                                <li><a class="dropdown-item filter-department" href="#" data-department="all"><i class="fas fa-building me-2 small text-muted"></i>All Departments</a></li>
                                <li><a class="dropdown-item filter-department" href="#" data-department="engineering"><i class="fas fa-code me-2 small text-muted"></i>Engineering</a></li>
                                <li><a class="dropdown-item filter-department" href="#" data-department="design"><i class="fas fa-paint-brush me-2 small text-muted"></i>Design</a></li>
                                <li><a class="dropdown-item filter-department" href="#" data-department="marketing"><i class="fas fa-bullhorn me-2 small text-muted"></i>Marketing</a></li>
                                <li><a class="dropdown-item filter-department" href="#" data-department="hr"><i class="fas fa-users me-2 small text-muted"></i>HR</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4" style="min-width: 280px;">Employee</th>
                                <th class="d-none d-lg-table-cell" style="width: 120px;">Department</th>
                                <th class="d-none d-xl-table-cell" style="width: 140px;">Shift</th>
                                <th style="width: 120px;">Clock In</th>
                                <th style="width: 120px;">Clock Out</th>
                                <th style="width: 120px;">Status</th>
                                <th class="text-end pe-4" style="width: 80px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0" id="attendanceTableBody">
                            <!-- Table rows will be dynamically populated -->
                        </tbody>
                    </table>
                </div>
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center border-top p-3">
                    <div class="text-muted small mb-2 mb-sm-0" id="tableInfo">
                        Showing <span class="fw-semibold">1</span> to <span class="fw-semibold">4</span> of <span class="fw-semibold">42</span> employees
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0" id="pagination">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Update Modal -->
<div class="modal fade" id="bulkAttendanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Update Attendance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="bulkAttendanceForm" method="post">
                    <div class="mb-3">
                        <label class="form-label">Select Employees</label>
                        <select class="form-select"  id="bulkEmployees" required>
                            <option value="all">All Employees</option>
                            {{-- <option value="department">By Department</option> --}}
                            <option value="selected">Select Manually</option>
                        </select>
                    </div>
                  
                    <div class="mb-3 d-none" id="departmentSelectWrapper">
                        <label class="form-label">Select Department</label>
                        <select class="form-select" id="departmentSelect">
                            <option value="">-- Select Department --</option>
                            <option value="hr">HR</option>
                            <option value="engineering">Engineering</option>
                            <option value="sales">Sales</option>
                            <!-- Add more departments dynamically if needed -->
                        </select>
                    </div>

                   
                    <div class="mb-3 d-none" id="manualEmployeeWrapper">
                        <label class="form-label">Select Employees</label>
                        <div id="manualEmployeeList" class="row row-cols-2 g-2">
                            <!-- Dynamically filled checkboxes -->
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="bulkDate" class="form-label">Date</label>
                            <input type="date" class="form-control" id="bulkDate" required>
                        </div>
                        <div class="col-md-6">
                            <label for="bulkStatus" class="form-label">Status</label>
                            <select class="form-select" id="bulkStatus" required>
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="half_day">Half Day</option>
                                <option value="on_leave">On Leave</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="bulkNote" class="form-label">Note (Optional)</label>
                        <textarea class="form-control" id="bulkNote" rows="3" placeholder="Add a note for this bulk update"></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update All</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Import Attendance Modal -->
<div class="modal fade" id="importAttendanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Attendance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Download the template file and fill in the attendance data.
                </div>
                
                <div class="text-center mb-4">
                    <a href="{{ asset('attendance_templates/sample_attendance_import.xlsx') }}" class="btn btn-outline-primary" download>
                        <i class="fas fa-download me-1"></i> Download Template
                    </a>
                </div>
                <form id="importAttendanceForm">
                    <div class="mb-3">
                        <label for="importFile" class="form-label">Select File</label>
                        <input class="form-control" type="file" id="importFile" name="importFile" accept=".xlsx, .xls, .csv" required>
                        <div class="form-text">Supported formats: .xlsx, .xls, .csv (Max 5MB)</div>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="overwriteExisting" name="overwriteExisting">
                        <label class="form-check-label" for="overwriteExisting">
                            Overwrite existing attendance records
                        </label>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Mark Attendance Modal -->
<div class="modal fade" id="markAttendanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark Attendance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="markAttendanceForm">
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Employee</label>
                        <select class="form-select" id="employee_id" name="employee_id" required>

                            <option value="">Select Employee</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="clockIn" class="form-label">Check In Time</label>
                                <input type="time" class="form-control" id="clockIn" name="clockIn" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="clockOut" class="form-label">Check Out Time</label>
                                <input type="time" class="form-control" id="clockOut" name="clockOut">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="attendancestatus" name="status" required>
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="half_day">Half Day</option>
                            <option value="on_leave">On Leave</option>
                            <option value="holiday">Holiday</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Note (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" style="height: 80px;" placeholder="Add note (optional)"></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Attendance</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Attendance Modal -->
<div class="modal fade" id="editAttendanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Attendance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editAttendanceForm">
                    <input type="hidden" id="editAttendanceId" name="id">
                    <div class="alert alert-info d-flex align-items-center mb-4">
                        <div class="flex-shrink-0 me-2">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="flex-grow-1">
                            <small>Editing attendance for <strong id="editEmployeeName">John Doe</strong> on <strong id="editDate">June 22, 2025</strong></small>
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="editClockIn" class="form-label">Check In</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="far fa-clock"></i></span>
                                <input type="time" class="form-control" id="editClockIn" name="clockIn" value="08:45" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="editClockOut" class="form-label">Check Out</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="far fa-clock"></i></span>
                                <input type="time" class="form-control" id="editClockOut" name="clockOut" value="17:30">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editStatus" class="form-label">Status</label>
                        <select class="form-select" id="editStatus" name="status" required>
                            <option value="present">Present</option>
                            <option value="late">Late Arrival</option>
                            <option value="half_day">Half Day</option>
                            <option value="absent">Absent</option>
                            <option value="on_leave">On Leave</option>
                            <option value="holiday">Holiday</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editNotes" class="form-label">Notes</label>
                        <textarea class="form-control" id="editNotes" name="notes" rows="3" placeholder="Add any notes about this attendance record"></textarea>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="notifyEmployee" name="notifyEmployee" checked>
                            <label class="form-check-label" for="notifyEmployee">Notify Employee</label>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- View History Modal -->
<div class="modal fade" id="viewHistoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historyEmployeeName">Attendance History - John Doe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="d-flex flex-column h-100">
                    <!-- Summary Card -->
                    <div class="p-4 border-bottom">
                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <div class="text-center">
                                    <div class="h4 mb-1 text-primary" id="historyPresent">0</div>
                                    <div class="text-muted small">Days Present</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-center">
                                    <div class="h4 mb-1 text-warning" id="historyLate">0</div>
                                    <div class="text-muted small">Late Arrivals</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-center">
                                    <div class="h4 mb-1 text-danger" id="historyAbsent">0</div>
                                    <div class="text-muted small">Absences</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-center">
                                    <div class="h4 mb-1 text-info" id="historyOnLeave">0</div>
                                    <div class="text-muted small">Leaves</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filter and Search -->
                    <div class="p-3 bg-light border-bottom">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <select class="form-select form-select-sm" id="historyPeriod">
                                    <option value="this_month">This Month</option>
                                    <option value="last_month">Last Month</option>
                                    <option value="last_3_months">Last 3 Months</option>
                                    <option value="this_year">This Year</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select form-select-sm" id="historyStatus">
                                    <option value="all">All Status</option>
                                    <option value="present">Present</option>
                                    <option value="late">Late</option>
                                    <option value="absent">Absent</option>
                                    <option value="on_leave">On Leave</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" id="historySearch" placeholder="Search...">
                                    <button class="btn btn-outline-secondary" type="button" id="historySearchBtn">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- History Table -->
                    <div class="table-responsive" style="max-height: 300px;">
                        <table class="table table-hover table-borderless mb-0">
                            <thead class="sticky-top bg-white" style="top: 0;">
                                <tr class="text-uppercase small text-muted">
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Hours</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="historyTableBody">
                                <!-- History rows will be dynamically populated -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Export and Pagination -->
                    <div class="d-flex justify-content-between align-items-center p-3 border-top">
                        <button class="btn btn-sm btn-outline-secondary" id="exportHistory">
                            <i class="fas fa-download me-1"></i> Export
                        </button>
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leave Calendar Modal -->
<div class="modal fade" id="leaveCalendarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="far fa-calendar-alt me-2"></i>Leave Calendar</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Calendar Section -->
                    <div class="col-12 col-lg-8">
                        <div id="leaveCalendar" style="min-height: 500px;"></div>
                    </div>
                    
                    <!-- Sidebar -->
                    <div class="col-12 col-lg-4 border-start">
                        <div class="p-3">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h6 class="mb-0">Leave Types</h6>
                                <button class="btn btn-sm btn-outline-primary" id="addLeaveTypeBtn" data-bs-toggle="modal" data-bs-target="#addLeaveTypeModal">
                                    <i class="fas fa-plus me-1"></i> Add
                                </button>
                            </div>
                            
                            <div class="leave-legend mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="legend-color" style="background-color: #4e73df;"></span>
                                    <span class="ms-2 small">Annual Leave</span>
                                    <span class="badge bg-light text-dark ms-auto">12/20 days</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="legend-color" style="background-color: #1cc88a;"></span>
                                    <span class="ms-2 small">Sick Leave</span>
                                    <span class="badge bg-light text-dark ms-auto">3/10 days</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="legend-color" style="background-color: #f6c23e;"></span>
                                    <span class="ms-2 small">Maternity Leave</span>
                                    <span class="badge bg-light text-dark ms-auto">45/90 days</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="legend-color" style="background-color: #e74a3b;"></span>
                                    <span class="ms-2 small">Unpaid Leave</span>
                                    <span class="badge bg-light text-dark ms-auto">Unlimited</span>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <h6 class="mb-3">Upcoming Leaves</h6>
                            <div class="upcoming-leaves" id="upcomingLeaves">
                                <div class="d-flex mb-3">
                                    <div class="bg-soft-primary rounded p-2 me-3 text-center" style="min-width: 60px;">
                                        <div class="text-uppercase fw-bold text-primary" style="font-size: 0.7rem;">JUN</div>
                                        <div class="h4 mb-0 fw-bold">25</div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Summer Vacation</h6>
                                        <p class="text-muted small mb-0">Annual Leave - 5 days</p>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="bg-soft-success rounded p-2 me-3 text-center" style="min-width: 60px;">
                                        <div class="text-uppercase fw-bold text-success" style="font-size: 0.7rem;">JUL</div>
                                        <div class="h4 mb-0 fw-bold">10</div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Medical Checkup</h6>
                                        <p class="text-muted small mb-0">Sick Leave - 1 day</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="requestLeaveBtn">Request Leave</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Leave Type Modal -->
<div class="modal fade" id="addLeaveTypeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Leave Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addLeaveTypeForm">
                    <div class="mb-3">
                        <label class="form-label">Leave Type</label>
                        <input type="text" class="form-control" id="leaveTypeName" name="name" placeholder="e.g., Paternity Leave">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Color</label>
                        <input type="hidden" id="leaveTypeColor" name="color" value="#4e73df">
                        <div class="d-flex">
                            <div class="color-option me-2 active" style="background-color: #4e73df;" data-color="#4e73df"></div>
                            <div class="color-option me-2" style="background-color: #1cc88a;" data-color="#1cc88a"></div>
                            <div class="color-option me-2" style="background-color: #f6c23e;" data-color="#f6c23e"></div>
                            <div class="color-option me-2" style="background-color: #e74a3b;" data-color="#e74a3b"></div>
                            <div class="color-option me-2" style="background-color: #6f42c1;" data-color="#6f42c1"></div>
                            <div class="color-option" style="background-color: #fd7e14;" data-color="#fd7e14"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Days Allowed</label>
                        <input type="number" class="form-control" id="leaveTypeDays" name="days" placeholder="Number of days">
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Sync Device Modal -->
<div class="modal fade" id="syncDeviceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sync Device Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-sync-alt fa-3x text-primary mb-3"></i>
                        <h5>Sync Attendance Device</h5>
                        <p class="text-muted">Connect and sync data from your biometric device</p>
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-center gap-3 mb-3">
                            <button type="button" class="btn btn-outline-primary" id="connectDeviceBtn">
                                <i class="fas fa-plug me-2"></i>Connect Device
                            </button>
                            <button type="button" class="btn btn-primary" id="syncNowBtn">
                                <i class="fas fa-sync-alt me-2"></i>Sync Now
                            </button>
                        </div>
                        <div class="form-check form-switch d-flex justify-content-center">
                            <input class="form-check-input me-2" type="checkbox" id="autoSync" name="autoSync" checked>
                            <label class="form-check-label" for="autoSync">Auto-sync every 15 minutes</label>
                        </div>
                    </div>
                    
                    <div class="border rounded p-3 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Device Status:</span>
                            <span class="badge bg-danger" id="deviceStatus">Disconnected</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Last Sync:</span>
                            <span class="fw-medium" id="lastSyncTime">Never</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Records Synced:</span>
                            <span class="badge bg-success" id="recordsSynced">0</span>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning small mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Ensure the device is properly connected before syncing.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet">
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fullToday = new Date();
    const todayString = fullToday.toISOString().split('T')[0];

    // Main filter input: only today
    const attendanceDate = document.getElementById('attendanceDate');
    if (attendanceDate) {
        attendanceDate.value = todayString;
        attendanceDate.min = todayString;
        attendanceDate.max = todayString;
    }

    // Modal form input: only today
    const modalDateInput = document.getElementById('date');
    if (modalDateInput) {
        modalDateInput.value = todayString;
        modalDateInput.min = todayString;
        modalDateInput.max = todayString;
    }

    // Bulk attendance: only today
    const bulkDateInput = document.getElementById('bulkDate');
    if (bulkDateInput) {
        bulkDateInput.value = todayString;
        bulkDateInput.min = todayString;
        bulkDateInput.max = todayString;
    }
});





    $(document).ready(function() {
        // CSRF Token for POST requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Helper function for showing SweetAlert notifications
        function showAlert(icon, title, text, timer = 3000) {
            Swal.fire({
                icon: icon,
                title: title,
                text: text,
                timer: timer,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }

        // Helper function for confirmation dialogs
        function showConfirm(title, text, confirmButtonText = 'Yes') {
            return Swal.fire({
                title: title,
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: confirmButtonText
            });
        }

        // Load employees for modals
        function loadEmployees() {
            $.ajax({
                url: '/company/hr/attendance/employees',
                method: 'POST',
                success: function(response) {
                    // console.log(response,"uuuuuuu");
                    if (response.success) {
                        const employeeSelect = $('#employee_id');
                      
                        employeeSelect.empty().append('<option value="">Select Employee</option>');
                    
                        if (response.data && Array.isArray(response.data)) {
                            response.data.forEach(employee => {
                                employeeSelect.append(`<option value="${employee.value}">${employee.label}</option>`);
                            });
                        }
                    } else {
                        showAlert('error', 'Error', 'Error loading employees: ' + response.message);
                    }
                },
                error: function(xhr) {
                    console.log(xhr);
                    showAlert('error', 'Error', 'Failed to load employees: ' + (xhr.responseJSON?.message || 'Unknown error'));
                }
            });
        }

        // Load attendance data
        function loadAttendance(date, page = 1, status = '', department = '') {
            $.ajax({
                url: '/company/hr/attendance/index',
                method: 'POST',
                data: { date: date, page: page, status: status, department: department },
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                    console.log(response,"rrrrrrrrr");
                        updateAttendanceTable(response.data);
                        updatePagination(response.data);
                    } else {
                        showAlert('error', 'Error', 'Error loading attendance: ' + response.message);
                    }
                },
                error: function(xhr) {
                    console.log(xhr, "rrrrrrr");
                    showAlert('error', 'Error', 'Failed to load attendance: ' + (xhr.responseJSON?.message || 'Unknown error'));
                }
            });
        }

        // Load stats
        function loadStats(date) {
            $.ajax({
                url: '/company/hr/attendance/stats',
                method: 'POST',
                data: { date: date },
                success: function(response) {
                    if (response.success) {
                        updateStatsCards(response.data);
                    } else {
                        showAlert('error', 'Error', 'Error loading stats: ' + response.message);
                    }
                },
                error: function(xhr) {
                    showAlert('error', 'Error', 'Failed to load stats: ' + (xhr.responseJSON?.message || 'Unknown error'));
                }
            });
        }

        // Update attendance table
        // function updateAttendanceTable(data) {
        //     console.log(data, "dataaaaaaa");
        //     const tbody = $('#attendanceTableBody');
        //     tbody.empty();
        //     if (data && data.length > 0) {
        //         data.data.forEach(record => {
        //             const employee = record.personalInfo;
        //             const employment = record.employmentInfo;
        //             const statusBadge = getStatusBadge(record.status);
        //             const clockIn = record.clock_in || '--:-- --';
        //             const clockOut = record.clock_out || '--:-- --';
        //             const department = employment?.department || 'N/A';
        //             const shift = employment?.shift ? `${employment.shift.start} - ${employment.shift.end}` : 'N/A';
        //             const shiftDuration = employment?.shift ? '8h shift' : 'N/A';
        //             const avatar = `https://ui-avatars.com/api/?name=${encodeURIComponent(employee.first_name + ' ' + employee.last_name)}&background=${getAvatarColor(record.status)}&color=fff`;
        //             const statusDot = getStatusDot(record.status);

        //             tbody.append(`
        //                 <tr>
        //                     <td class="ps-4">
        //                         <div class="d-flex align-items-center">
        //                             <div class="position-relative">
        //                                 <img src="${avatar}" alt="${employee.first_name} ${employee.last_name}" class="rounded-circle me-3" width="40" style="opacity: ${record.status === 'absent' || record.status === 'on_leave' ? 0.7 : 1};">
        //                                 <span class="position-absolute bottom-0 end-0 ${statusDot} rounded-circle border border-2 border-white" style="width: 10px; height: 10px;"></span>
        //                             </div>
        //                             <div>
        //                                 <h6 class="mb-0 fw-semibold">${employee.first_name} ${employee.last_name}</h6>
        //                                 <small class="text-muted">${employee.employee_id} • ${department}</small>
        //                             </div>
        //                         </div>
        //                     </td>
        //                     <td class="d-none d-lg-table-cell">
        //                         <span class="badge bg-soft-primary text-primary">${department}</span>
        //                     </td>
        //                     <td class="d-none d-xl-table-cell">
        //                         <small class="d-block">${shift}</small>
        //                         <small class="text-muted">${shiftDuration}</small>
        //                     </td>
        //                     <td>
        //                         <div class="d-flex flex-column">
        //                             <span class="${record.status === 'late' ? 'text-warning' : 'text-success'} fw-medium">${clockIn}</span>
        //                             <small class="text-muted">${record.status === 'late' ? 'Late' : 'On Time'}</small>
        //                         </div>
        //                     </td>
        //                     <td>${clockOut}</td>
        //                     <td>
        //                         ${statusBadge}
        //                     </td>
        //                     <td class="text-end pe-4">
        //                         <div class="btn-group">
        //                             <button type="button" class="btn btn-sm btn-icon btn-ghost-primary rounded-circle edit-attendance" data-id="${record.id}" data-employee="${employee.first_name} ${employee.last_name}" data-date="${record.date}" data-clock-in="${record.clock_in || ''}" data-clock-out="${record.clock_out || ''}" data-status="${record.status}" data-notes="${record.notes || ''}" title="Edit">
        //                                 <i class="fas fa-pencil-alt"></i>
        //                             </button>
        //                             <button type="button" class="btn btn-sm btn-icon btn-ghost-info rounded-circle view-history-btn" data-employee-id="${employee.employee_id}" data-employee-name="${employee.first_name} ${employee.last_name}" title="View History">
        //                                 <i class="fas fa-history"></i>
        //                             </button>
        //                         </div>
        //                     </td>
        //                 </tr>
        //             `);
        //         });
        //         $('#tableInfo').html(`Showing <span class="fw-semibold">${data.from || 0}</span> to <span class="fw-semibold">${data.to || 0}</span> of <span class="fw-semibold">${data.total || 0}</span> employees`);
        //     } else {
        //         tbody.append('<tr><td colspan="7" class="text-center">No records found</td></tr>');
        //         $('#tableInfo').html('Showing <span class="fw-semibold">0</span> to <span class="fw-semibold">0</span> of <span class="fw-semibold">0</span> employees');
        //     }
        // }
     function updateAttendanceTable(data) {
    console.log(data, "dataaaaaaa");
    const tbody = $('#attendanceTableBody');
    tbody.empty();
    if (data && data.length > 0) {
        data.forEach(record => {
            const employee = record.personal_info;
            const employment = record.employment_info;
            const statusBadge = getStatusBadge(record.status);
            const clockIn = record.clock_in || '--:-- --';
            const clockOut = record.clock_out || '--:-- --';
            const department = employment?.department || 'N/A';
            const shift = employment?.shift ? `${employment.shift.start} - ${employment.shift.end}` : 'N/A';
            const shiftDuration = employment?.shift ? '8h shift' : 'N/A';
            const avatar = `https://ui-avatars.com/api/?name=${encodeURIComponent(employee.first_name + ' ' + employee.last_name)}&background=${getAvatarColor(record.status)}&color=fff`;
            const statusDot = getStatusDot(record.status);

            tbody.append(`
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center">
                            <div class="position-relative">
                                <img src="${avatar}" alt="${employee.first_name} ${employee.last_name}" class="rounded-circle me-3" width="40" style="opacity: ${record.status === 'absent' || record.status === 'on_leave' ? 0.7 : 1};">
                                <span class="position-absolute bottom-0 end-0 ${statusDot} rounded-circle border border-2 border-white" style="width: 10px; height: 10px;"></span>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-semibold">${employee.first_name} ${employee.last_name}</h6>
                                <small class="text-muted">${employee.employee_id} • ${department}</small>
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <span class="badge bg-soft-primary text-primary">${department}</span>
                    </td>
                    <td class="d-none d-xl-table-cell">
                        <small class="d-block">${shift}</small>
                        <small class="text-muted">${shiftDuration}</small>
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="${record.status === 'late' ? 'text-warning' : 'text-success'} fw-medium">${clockIn}</span>
                            <small class="text-muted">${record.status === 'late' ? 'Late' : 'On Time'}</small>
                        </div>
                    </td>
                    <td>${clockOut}</td>
                    <td>${statusBadge}</td>
                    <td class="text-end pe-4">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-icon btn-ghost-primary rounded-circle edit-attendance"
                                data-id="${record.id}"
                                data-employee="${employee.first_name} ${employee.last_name}"
                                data-date="${record.date}"
                                data-clock-in="${record.clock_in || ''}"
                                data-clock-out="${record.clock_out || ''}"
                                data-status="${record.status}"
                                data-notes="${record.notes || ''}"
                                title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-icon btn-ghost-info rounded-circle view-history-btn"
                                data-employee-id="${employee.employee_id}"
                                data-employee-name="${employee.first_name} ${employee.last_name}"
                                title="View History">
                                <i class="fas fa-history"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `);
        });
        $('#tableInfo').html(`Showing <span class="fw-semibold">${data.from || 0}</span> to <span class="fw-semibold">${data.to || data.length}</span> of <span class="fw-semibold">${data.total || data.length}</span> employees`);
    } else {
        tbody.append('<tr><td colspan="7" class="text-center">No records found</td></tr>');
        $('#tableInfo').html('Showing <span class="fw-semibold">0</span> to <span class="fw-semibold">0</span> of <span class="fw-semibold">0</span> employees');
    }
}

        // // Update pagination
        // function updatePagination(data) {
        //     const pagination = $('#pagination');
        //     pagination.empty();
        //     if (data.last_page > 1) {
        //         pagination.append(`
        //             <li class="page-item ${data.current_page === 1 ? 'disabled' : ''}">
        //                 <a class="page-link" href="#" data-page="${data.current_page - 1}"><i class="fas fa-chevron-left"></i></a>
        //             </li>
        //         `);
        //         for (let i = 1; i <= data.last_page; i++) {
        //             pagination.append(`
        //                 <li class="page-item ${i === data.current_page ? 'active' : ''}">
        //                     <a class="page-link" href="#" data-page="${i}">${i}</a>
        //                 </li>
        //             `);
        //         }
        //         pagination.append(`
        //             <li class="page-item ${data.current_page === data.last_page ? 'disabled' : ''}">
        //                 <a class="page-link" href="#" data-page="${data.current_page + 1}"><i class="fas fa-chevron-right"></i></a>
        //             </li>
        //         `);
        //     }
        // }
function updatePagination(data) {
    const pagination = $('#pagination');
    pagination.empty();

    if (data.last_page && data.last_page > 1) {
        // Previous button
        pagination.append(`
            <li class="page-item ${data.current_page === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${data.current_page - 1}">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>
        `);

        // Page numbers
        for (let i = 1; i <= data.last_page; i++) {
            pagination.append(`
                <li class="page-item ${i === data.current_page ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `);
        }

        // Next button
        pagination.append(`
            <li class="page-item ${data.current_page === data.last_page ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${data.current_page + 1}">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
        `);
    }
}

        // Update stats cards
        function updateStatsCards(data) {
            const total = data.total || 0;
            $('#presentCount').text(data.present || 0);
            $('#onTimeCount').text((data.present || 0) - (data.late || 0));
            $('#lateCount').text(data.late || 0);
            $('#lateTotal').text(data.late || 0);
            $('#absentCount').text(data.absent || 0);
            $('#noNoticeCount').text(data.no_notice || 0);
            $('#onLeaveCount').text(data.on_leave || 0);
            $('#annualLeaveCount').text(data.annual_leave || 0);
            $('#sickLeaveCount').text(data.sick_leave || 0);
            $('#avgLateMinutes').text(data.avg_late_minutes || 0);

            const presentPercent = total ? ((data.present / total) * 100).toFixed(1) : 0;
            const latePercent = total ? ((data.late / total) * 100).toFixed(1) : 0;
            const absentPercent = total ? ((data.absent / total) * 100).toFixed(1) : 0;
            const onLeavePercent = total ? ((data.on_leave / total) * 100).toFixed(1) : 0;

            $('#presentProgress').css('width', `${presentPercent}%`).attr('aria-valuenow', presentPercent);
            $('#lateProgress').css('width', `${latePercent}%`).attr('aria-valuenow', latePercent);
            $('#absentProgress').css('width', `${absentPercent}%`).attr('aria-valuenow', absentPercent);
            $('#leaveProgress').css('width', `${onLeavePercent}%`).attr('aria-valuenow', onLeavePercent);

            $('#presentPercentage').text(`${presentPercent}% of team`);
            $('#latePercentage').text(`${latePercent}% of team`);
            $('#absentPercentage').text(`${absentPercent}% of team`);
            $('#leavePercentage').text(`${onLeavePercent}%`);

            // Update trends if previous-day data is provided
            if (data.previous_day) {
                const presentTrend = ((data.present - data.previous_day.present) / (data.previous_day.present || 1) * 100).toFixed(1);
                const lateTrend = data.late - (data.previous_day.late || 0);
                const absentTrend = data.absent - (data.previous_day.absent || 0);
                $('#presentTrend').html(`<i class="fas fa-caret-${presentTrend >= 0 ? 'up' : 'down'} me-1"></i>${Math.abs(presentTrend)}%`);
                $('#lateTrend').html(`<i class="fas fa-caret-${lateTrend >= 0 ? 'up' : 'down'} me-1"></i>${Math.abs(lateTrend)}`);
                $('#absentTrend').html(`<i class="fas fa-caret-${absentTrend >= 0 ? 'up' : 'down'} me-1"></i>${Math.abs(absentTrend)}`);
                $('#presentVsYesterday').text(`vs ${data.previous_day.present_percent || 0}% yesterday`);
                $('#lateVsYesterday').text(`vs ${data.previous_day.late_percent || 0}% yesterday`);
                $('#absentVsYesterday').text(`vs ${data.previous_day.absent_percent || 0}% yesterday`);
            } else {
                $('#presentTrend').html('<i class="fas fa-caret-up me-1"></i>0%');
                $('#lateTrend').html('<i class="fas fa-caret-up me-1"></i>0');
                $('#absentTrend').html('<i class="fas fa-caret-down me-1"></i>0');
                $('#presentVsYesterday').text('vs 0% yesterday');
                $('#lateVsYesterday').text('vs 0% yesterday');
                $('#absentVsYesterday').text('vs 0% yesterday');
            }
        }

        // Update history modal
        function updateHistoryModal(data, stats) {
            $('#historyEmployeeName').text(`Attendance History - ${data[0]?.personalInfo?.first_name || ''} ${data[0]?.personalInfo?.last_name || ''}`);
            $('#historyPresent').text(stats.present || 0);
            $('#historyLate').text(stats.late || 0);
            $('#historyAbsent').text(stats.absent || 0);
            $('#historyOnLeave').text(stats.on_leave || 0);

            const tbody = $('#historyTableBody');
            tbody.empty();
            if (data && data.length > 0) {
                data.forEach(record => {
                    const clockIn = record.clock_in || '--:--';
                    const clockOut = record.clock_out || '--:--';
                    const hours = calculateHours(record.clock_in, record.clock_out);
                    const statusBadge = getStatusBadge(record.status);
                    tbody.append(`
                        <tr>
                            <td>${new Date(record.date).toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' })}</td>
                            <td>${statusBadge}</td>
                            <td>${clockIn}</td>
                            <td>${clockOut}</td>
                            <td>${hours}</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-link p-0 text-primary edit-attendance" data-id="${record.id}" data-employee="${record.personalInfo.first_name} ${record.personalInfo.last_name}" data-date="${record.date}" data-clock-in="${record.clock_in || ''}" data-clock-out="${record.clock_out || ''}" data-status="${record.status}" data-notes="${record.notes || ''}" title="Edit">
                                    <i class="far fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                });
            } else {
                tbody.append('<tr><td colspan="6" class="text-center">No records found</td></tr>');
            }
        }

        // Helper to get status badge
        function getStatusBadge(status) {
            const statusMap = {
                'present': '<span class="badge bg-success bg-opacity-10 text-success d-inline-flex align-items-center"><i class="fas fa-circle text-success me-1" style="font-size: 6px;"></i>Present</span>',
                'late': '<span class="badge bg-warning bg-opacity-10 text-warning d-inline-flex align-items-center"><i class="fas fa-circle text-warning me-1" style="font-size: 6px;"></i>Late</span>',
                'absent': '<span class="badge bg-danger bg-opacity-10 text-danger d-inline-flex align-items-center"><i class="fas fa-circle text-danger me-1" style="font-size: 6px;"></i>Absent</span>',
                'on_leave': '<span class="badge bg-info bg-opacity-10 text-info d-inline-flex align-items-center"><i class="fas fa-circle text-info me-1" style="font-size: 6px;"></i>On Leave</span>',
                'half_day': '<span class="badge bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center"><i class="fas fa-circle text-primary me-1" style="font-size: 6px;"></i>Half Day</span>',
                'holiday': '<span class="badge bg-secondary bg-opacity-10 text-secondary d-inline-flex align-items-center"><i class="fas fa-circle text-secondary me-1" style="font-size: 6px;"></i>Holiday</span>'
            };
            return statusMap[status] || '<span class="badge bg-secondary bg-opacity-10 text-secondary">Unknown</span>';
        }

        // Helper to get status dot color
        function getStatusDot(status) {
            const dotMap = {
                'present': 'bg-success',
                'late': 'bg-warning',
                'absent': 'bg-danger',
                'on_leave': 'bg-info',
                'half_day': 'bg-primary',
                'holiday': 'bg-secondary'
            };
            return dotMap[status] || 'bg-secondary';
        }

        // Helper to get avatar color
        function getAvatarColor(status) {
            const colorMap = {
                'present': '0D8ABC',
                'late': '7B68EE',
                'absent': 'FF6347',
                'on_leave': '20B2AA',
                'half_day': '4682B4',
                'holiday': '6B7280'
            };
            return colorMap[status] || '6B7280';
        }

        // Helper to calculate hours
        function calculateHours(clockIn, clockOut) {
            if (!clockIn || !clockOut) return '--';
            const start = new Date(`1970-01-01T${clockIn}:00Z`);
            const end = new Date(`1970-01-01T${clockOut}:00Z`);
            const diff = (end - start) / 1000 / 60 / 60;
            return diff.toFixed(2);
        }

        // Initialize FullCalendar
        function initCalendar() {
            const calendarEl = document.getElementById('leaveCalendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: function(fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: '/company/hr/leaves/calendar-events',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            start: fetchInfo.startStr,
                            end: fetchInfo.endStr
                        },
                        success: function(response) {
                            if (response.success) {
                                // Handle new API response structure with events and leaveData
                                const events = response.events && Array.isArray(response.events) ? response.events : [];
                                const leaveData = response.leaveData && Array.isArray(response.leaveData) ? response.leaveData : [];
                                
                                // Map events for FullCalendar
                                const calendarEvents = events.map(leave => ({
                                    title: `${leave.employee_name || 'Unknown'} - ${leave.leave_type || 'Leave'}`,
                                    start: leave.start_date,
                                    end: leave.end_date,
                                    backgroundColor: leave.color || '#007bff',
                                    borderColor: leave.color || '#007bff'
                                }));
                                
                                successCallback(calendarEvents);
                                updateUpcomingLeaves(leaveData);
                            } else {
                                console.error('API request failed:', response.message || 'Unknown error');
                                successCallback([]);
                                updateUpcomingLeaves([]);
                            }
                        },
                        error: function(xhr) {
                            failureCallback(new Error(xhr.responseJSON?.message || 'Failed to load leaves'));
                        }
                    });
                },
                eventClick: function(info) {
                    Swal.fire({
                        title: 'Leave Details',
                        html: `<strong>Leave:</strong> ${info.event.title}<br>
                               <strong>From:</strong> ${info.event.start.toLocaleDateString()}<br>
                               <strong>To:</strong> ${info.event.end ? info.event.end.toLocaleDateString() : info.event.start.toLocaleDateString()}`,
                        icon: 'info'
                    });
                }
            });
            calendar.render();
        }

        // Update upcoming leaves
        function updateUpcomingLeaves(leaves) {
            const container = $('#upcomingLeaves');
            container.empty();
            leaves.filter(leave => new Date(leave.start_date) >= new Date()).slice(0, 5).forEach(leave => {
                const date = new Date(leave.start_date);
                container.append(`
                    <div class="d-flex mb-3">
                        <div class="bg-soft-primary rounded p-2 me-3 text-center" style="min-width: 60px;">
                            <div class="text-uppercase fw-bold text-primary" style="font-size: 0.7rem;">${date.toLocaleString('en-US', { month: 'short' }).toUpperCase()}</div>
                            <div class="h4 mb-0 fw-bold">${date.getDate()}</div>
                        </div>
                        <div>
                            <h6 class="mb-1">${leave.employee_name}</h6>
                            <p class="text-muted small mb-0">${leave.type} - ${leave.days} days</p>
                        </div>
                    </div>
                `);
            });
        }

        // Leave Type color selection
        $('.color-option').click(function() {
            $('.color-option').removeClass('active');
            $(this).addClass('active');
            $('#leaveTypeColor').val($(this).data('color'));
        });

        // Date change handler
        $('#attendanceDate').change(function() {
            const date = $(this).val();
            $('#tableDate').text(new Date(date).toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' }));
            loadAttendance(date);
            loadStats(date);
        });

        // Filter by status
        $('.filter-status').click(function(e) {
            e.preventDefault();
            const status = $(this).data('status');
            $('.filter-status').removeClass('active');
            $(this).addClass('active');
            const date = $('#attendanceDate').val();
            loadAttendance(date, 1, status);
        });

        // Filter by department
        $('.filter-department').click(function(e) {
            e.preventDefault();
            const department = $(this).data('department');
            $('.filter-department').removeClass('active');
            $(this).addClass('active');
            const date = $('#attendanceDate').val();
            loadAttendance(date, 1, '', department);
        });

        // Search employee
        $('#searchEmployee').on('input', function() {
            const search = $(this).val().toLowerCase();
            $('#attendanceTableBody tr').each(function() {
                const employeeName = $(this).find('h6').text().toLowerCase();
                $(this).toggle(employeeName.includes(search));
            });
        });

        // Pagination click
        $(document).on('click', '.page-link', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            const date = $('#attendanceDate').val();
            const status = $('.filter-status.active')?.data('status') || '';
            const department = $('.filter-department.active')?.data('department') || '';
            if (page) loadAttendance(date, page, status, department);
        });

    //     Mark Attendance form submission
     $('#markAttendanceForm').submit(function(e) {
        e.preventDefault();

        const form = this; 

        $.ajax({
            url: '/company/hr/attendance',
            method: 'POST',
            data: $(form).serialize(),
            success: function(response) {
                console.log(response);
                if (response.success) {
                    const date = $('#attendanceDate').val();
                    loadAttendance(date);
                    loadStats(date);
                    $('#markAttendanceModal').modal('hide');

                    form.reset(); // ✅ this now works

                    showAlert('success', 'Success', 'Attendance recorded successfully');
                } else {
                    showAlert('error', 'Error', response.message);
                }
            },
            error: function(xhr) {
                console.log(xhr);
                showAlert('error', 'Error', 'Failed to save attendance: ' + (xhr.responseJSON?.message || 'Unknown error'));
            }
        });
    });




    $('#bulkAttendanceForm').submit(function(e) {
    e.preventDefault();

    let selection = $('#bulkEmployees').val();
    let employee_ids = [];

    if (selection === 'all') {
        employee_ids.push('all');
    } else if (selection === 'department') {
        const department = $('#departmentSelect').val();
        if (department) {
            employee_ids.push('department:' + department);
        }
    } else if (selection === 'selected') {
        console.log("Selected ddddddd");
        $('#manualEmployeeList input[type="checkbox"]:checked').each(function () {
            employee_ids.push($(this).val());
            console.log($(this).val(), "Selected value");
        });
    }

    const date = $('#bulkDate').val();
    const status = $('#bulkStatus').val();
    const notes = $('#bulkNote').val();

    if (!date || !status || employee_ids.length === 0) {
        showAlert('error', 'Error', 'Please fill all required fields.');
        return;
    }

    const formData = {
        employee_ids: employee_ids,
        date: date,
        status: status,
        notes: notes
    };

    showConfirm('Confirm', 'Are you sure you want to update attendance for all selected employees?', 'Update All')
        .then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    url: '/company/hr/attendance/bulk',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        console.log(response);
                        if (response.success) {
                            $('#bulkAttendanceModal').modal('hide');
                            $('#bulkAttendanceForm')[0].reset();
                            showAlert('success', 'Success', response.message);
                        } else {
                            showAlert('error', 'Error', response.message || 'Update failed');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr);
                        const msg = xhr.responseJSON?.message || 'Unknown error';
                        showAlert('error', 'Error', 'Failed to save bulk attendance: ' + msg);
                    }
                });
            }
        });
});



        $('#bulkEmployees').on('change', function () {
    const selected = $(this).val();

    // alert("Dd");

    // Hide all conditional fields by default
    $('#departmentSelectWrapper').addClass('d-none');
    $('#manualEmployeeWrapper').addClass('d-none');

    if (selected === 'department') {
        $('#departmentSelectWrapper').removeClass('d-none');
    } else if (selected === 'selected') {
        $('#manualEmployeeWrapper').removeClass('d-none');
        loadManualEmployeeCheckboxes(); // Function to load checkboxes
    }
   });


   function loadManualEmployeeCheckboxes() {
    $.ajax({
        url: '/company/hr/attendance/employees',
        method: 'POST',
        success: function(response) {
            if (response.success && response.data && Array.isArray(response.data)) {
                const list = $('#manualEmployeeList');
                list.empty();
                response.data.forEach(employee => {
                    list.append(`
                        <div class="form-check col">
                            <input class="form-check-input" type="checkbox" name="employee_ids[]" value="${employee.value}" id="emp_${employee.value}">
                            <label class="form-check-label" for="emp_${employee.value}">
                                ${employee.label}
                            </label>
                        </div>
                    `);
                });
            } else {
                console.error('Invalid response data for employees:', response);
            }
        }
    });
}


        // Bulk Update form submission
        // $('#bulkAttendanceForm').submit(function(e) {
        //     e.preventDefault();
        //     showConfirm('Confirm', 'Are you sure you want to update attendance for all selected employees?', 'Update All')
        //         .then((result) => {
        //             if (result.isConfirmed) {
        //                 $.ajax({
        //                     url: '/company/hr/attendance/bulk',
        //                     method: 'POST',
        //                     data: $(this).serialize(),
        //                     success: function(response) {
        //                         console.log(response);
        //                         if (response.success) {
        //                             const date = $('#attendanceDate').val();
        //                             loadAttendance(date);
        //                             loadStats(date);
        //                             $('#bulkAttendanceModal').modal('hide');
        //                             $(this)[0].reset();
        //                             showAlert('success', 'Success', 'Bulk attendance updated successfully');
        //                         } else {
        //                             showAlert('error', 'Error', response.message);
        //                         }
        //                     },
        //                     error: function(xhr) {
        //                         console.log(xhr);
        //                         showAlert('error', 'Error', 'Failed to save bulk attendance: ' + (xhr.responseJSON?.message || 'Unknown error'));
        //                     }
        //                 });
        //             }
        //         });
        // });

        // Edit Attendance button click
        $(document).on('click', '.edit-attendance', function() {
            const id = $(this).data('id');
            const employee = $(this).data('employee');
            const date = $(this).data('date');
            const clockIn = $(this).data('clock-in');
            const clockOut = $(this).data('clock-out');
            const status = $(this).data('status');
            const notes = $(this).data('notes');

            $('#editAttendanceId').val(id);
            $('#editEmployeeName').text(employee);
            $('#editDate').text(new Date(date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }));
            $('#editClockIn').val(clockIn);
            $('#editClockOut').val(clockOut);
            $('#editStatus').val(status);
            $('#editNotes').val(notes);
            $('#editAttendanceModal').modal('show');
        });

        // Edit Attendance form submission
        $('#editAttendanceForm').submit(function(e) {
            e.preventDefault();
            const id = $('#editAttendanceId').val();
            $.ajax({
                url: `/company/hr/attendance/${id}`,
                method: 'PUT',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        const date = $('#attendanceDate').val();
                        loadAttendance(date);
                        loadStats(date);
                        $('#editAttendanceModal').modal('hide');
                        $(this)[0].reset();
                        showAlert('success', 'Success', 'Attendance updated successfully');
                    } else {
                        showAlert('error', 'Error', response.message);
                    }
                },
                error: function(xhr) {
                    showAlert('error', 'Error', 'Failed to update attendance: ' + (xhr.responseJSON?.message || 'Unknown error'));
                }
            });
        });

        // View History button click
        $(document).on('click', '.view-history-btn', function() {
            const employeeId = $(this).data('employee-id');
            const employeeName = $(this).data('employee-name');
            const period = $('#historyPeriod').val();
            const status = $('#historyStatus').val();
            const startDate = period === 'this_month' ? '{{ now()->startOfMonth()->format('Y-m-d') }}' :
                             period === 'last_month' ? '{{ now()->subMonth()->startOfMonth()->format('Y-m-d') }}' :
                             period === 'last_3_months' ? '{{ now()->subMonths(3)->startOfMonth()->format('Y-m-d') }}' :
                             period === 'this_year' ? '{{ now()->startOfYear()->format('Y-m-d') }}' : '{{ now()->subMonth()->format('Y-m-d') }}';
            const endDate = period === 'custom' ? '{{ now()->format('Y-m-d') }}' : '{{ now()->format('Y-m-d') }}';

            $.ajax({
                url: `/company/hr/employees/${employeeId}/attendance-history`,
                method: 'POST',
                data: { start_date: startDate, end_date: endDate, status: status },
                success: function(response) {
                    if (response.success) {
                        updateHistoryModal(response.data, response.stats);
                        $('#viewHistoryModal').modal('show');
                    } else {
                        showAlert('error', 'Error', 'Error loading history: ' + response.message);
                    }
                },
                error: function(xhr) {
                    showAlert('error', 'Error', 'Failed to load history: ' + (xhr.responseJSON?.message || 'Unknown error'));
                }
            });
        });

        // History period or status change
        $('#historyPeriod, #historyStatus').change(function() {
            const employeeId = $('.view-history-btn').data('employee-id');
            if (employeeId) {
                $('.view-history-btn').trigger('click');
            }
        });

        // History search
        $('#historySearchBtn').click(function() {
            const search = $('#historySearch').val().toLowerCase();
            $('#historyTableBody tr').each(function() {
                const date = $(this).find('td:first').text().toLowerCase();
                $(this).toggle(date.includes(search));
            });
        });

        // Import Attendance form submission
        $('#importAttendanceForm').submit(function(e) {
            e.preventDefault();

            console.log("dddd")
            const formData = new FormData(this);
            showConfirm('Confirm', 'Are you sure you want to import attendance data?', 'Import')
                .then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/company/hr/attendance/import',
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                console.log(response);
                                if (response.success) {
                                    const date = $('#attendanceDate').val();
                                    loadAttendance(date);
                                    loadStats(date);
                                    $('#importAttendanceModal').modal('hide');
                                    $(this)[0].reset();
                                    showAlert('success', 'Success', 'Attendance imported successfully');
                                } else {
                                    showAlert('error', 'Error', response.message);
                                }
                            },
                            error: function(xhr) {
                                console.log(xhr);
                                showAlert('error', 'Error', 'Failed to import attendance: ' + (xhr.responseJSON?.message || 'Unknown error'));
                            }
                        });
                    }
                });
        });

        // Sync Device - Connect
        $('#connectDeviceBtn').click(function() {
            showConfirm('Confirm', 'Are you sure you want to connect to the attendance device?', 'Connect')
                .then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/company/hr/attendance-device/connect',
                            method: 'POST',
                            success: function(response) {
                                if (response.success) {
                                    $('#deviceStatus').removeClass('bg-danger').addClass('bg-success').text('Connected');
                                    showAlert('success', 'Success', 'Device connected successfully');
                                } else {
                                    showAlert('error', 'Error', response.message);
                                }
                            },
                            error: function(xhr) {
                                showAlert('error', 'Error', 'Failed to connect device: ' + (xhr.responseJSON?.message || 'Unknown error'));
                            }
                        });
                    }
                });
        });

        // Sync Device - Sync Now
        $('#syncNowBtn').click(function() {
            showConfirm('Confirm', 'Are you sure you want to sync with the attendance device now?', 'Sync Now')
                .then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/company/hr/attendance-device/sync',
                            method: 'POST',
                            success: function(response) {
                                if (response.success) {
                                    $('#deviceStatus').removeClass('bg-danger').addClass('bg-success').text('Connected');
                                    $('#lastSyncTime').text(new Date().toLocaleString());
                                    $('#recordsSynced').text(response.records || 0);
                                    const date = $('#attendanceDate').val();
                                    loadAttendance(date);
                                    loadStats(date);
                                    showAlert('success', 'Success', 'Device synced successfully');
                                } else {
                                    showAlert('error', 'Error', response.message);
                                }
                            },
                            error: function(xhr) {
                                showAlert('error', 'Error', 'Failed to sync device: ' + (xhr.responseJSON?.message || 'Unknown error'));
                            }
                        });
                    }
                });
        });

        // Auto-sync toggle
        $('#autoSync').change(function() {
            const enabled = $(this).is(':checked');
            $.ajax({
                url: '/company/hr/attendance-device/autosync',
                method: 'POST',
                data: { enabled: enabled },
                success: function(response) {
                    if (response.success) {
                        showAlert('success', 'Success', `Auto-sync ${enabled ? 'enabled' : 'disabled'}`);
                    } else {
                        showAlert('error', 'Error', response.message);
                    }
                },
                error: function(xhr) {
                    showAlert('error', 'Error', 'Failed to update auto-sync: ' + (xhr.responseJSON?.message || 'Unknown error'));
                }
            });
        });

        // Export History
        $('#exportHistory').click(function() {
            const employeeId = $('.view-history-btn').data('employee-id');
            const period = $('#historyPeriod').val();
            const status = $('#historyStatus').val();
            const startDate = period === 'this_month' ? '{{ now()->startOfMonth()->format('Y-m-d') }}' :
                             period === 'last_month' ? '{{ now()->subMonth()->startOfMonth()->format('Y-m-d') }}' :
                             period === 'last_3_months' ? '{{ now()->subMonths(3)->startOfMonth()->format('Y-m-d') }}' :
                             period === 'this_year' ? '{{ now()->startOfYear()->format('Y-m-d') }}' : '{{ now()->subMonth()->format('Y-m-d') }}';
            const endDate = period === 'custom' ? '{{ now()->format('Y-m-d') }}' : '{{ now()->format('Y-m-d') }}';

            $.ajax({
                url: `/company/hr/employees/${employeeId}/export-attendance`,
                method: 'POST',
                data: { start_date: startDate, end_date: endDate, status: status },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(data, status, xhr) {
                    const blob = new Blob([data], { type: 'text/csv' });
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `attendance_history_${employeeId}_${new Date().toISOString().slice(0, 10)}.csv`;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                    showAlert('success', 'Success', 'History exported successfully');
                },
                error: function(xhr) {
                    showAlert('error', 'Error', 'Failed to export history: ' + (xhr.responseJSON?.message || 'Unknown error'));
                }
            });
        });

        // Add Leave Type form submission
        $('#addLeaveTypeForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: '/company/hr/leave-types',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#addLeaveTypeModal').modal('hide');
                        $(this)[0].reset();
                        $('.color-option').removeClass('active').first().addClass('active');
                        $('#leaveTypeColor').val('#4e73df');
                        showAlert('success', 'Success', 'Leave type added successfully');
                        $('#leaveCalendarModal').modal('show'); // Refresh calendar
                    } else {
                        showAlert('error', 'Error', response.message);
                    }
                },
                error: function(xhr) {
                    showAlert('error', 'Error', 'Failed to add leave type: ' + (xhr.responseJSON?.message || 'Unknown error'));
                }
            });
        });

        // Request Leave button
        $('#requestLeaveBtn').click(function() {
            Swal.fire({
                title: 'Request Leave',
                text: 'This feature is not implemented yet. Please contact the administrator.',
                icon: 'info'
            });
        });

        // Initialize page
        loadEmployees();
        const today = $('#attendanceDate').val();
        loadAttendance(today);
        loadStats(today);
        initCalendar();
    });
</script>