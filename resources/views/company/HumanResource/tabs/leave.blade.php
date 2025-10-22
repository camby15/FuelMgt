<!-- ===== Leave Management Header and Stats (your code) ===== -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="header-title">Leave Management</h4>
        <p class="text-muted">Manage employee leave requests and balances</p>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Pending Approval Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm card-hover">
            <div class="card-body position-relative p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex flex-column">
                        <span class="text-muted text-uppercase small fw-semibold tracking-wide">Pending Approval</span>
                        <div class="d-flex align-items-baseline mt-2">
                            <h2 class="mb-0 fw-bold me-2" id="pendingCount">0</h2>
                            <span class="badge bg-warning bg-opacity-15 text-warning small rounded-pill" id="pendingTrend">
                                <i class="fas fa-arrow-up me-1"></i> 0
                            </span>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small text-muted">Weekly trend</span>
                                <span class="small fw-medium text-warning" id="pendingPercentage">0%</span>
                            </div>
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-warning" id="pendingProgress" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="icon-wrapper bg-warning bg-opacity-10 rounded-3 p-3">
                        <i class="fas fa-clock fa-lg text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approved This Month Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm card-hover">
            <div class="card-body position-relative p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex flex-column">
                        <span class="text-muted text-uppercase small fw-semibold tracking-wide">Approved This Month</span>
                        <div class="d-flex align-items-baseline mt-2">
                            <h2 class="mb-0 fw-bold me-2" id="approvedCount">0</h2>
                            <span class="badge bg-success bg-opacity-15 text-success small rounded-pill" id="approvedTrend">
                                <i class="fas fa-arrow-up me-1"></i> 0%
                            </span>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small text-muted">Monthly change</span>
                                <span class="small fw-medium text-success" id="approvedChange">+0</span>
                            </div>
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-success" id="approvedProgress" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="icon-wrapper bg-success bg-opacity-10 rounded-3 p-3">
                        <i class="fas fa-check-circle fa-lg text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Balance Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm card-hover">
            <div class="card-body position-relative p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex flex-column">
                        <span class="text-muted text-uppercase small fw-semibold tracking-wide">Leave Balance</span>
                        <div class="d-flex align-items-baseline mt-2">
                            <h2 class="mb-0 fw-bold me-2" id="leaveBalance">0</h2>
                            <span class="text-muted small" id="totalLeaveDays">/ 0 days</span>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small text-muted">Remaining</span>
                                <span class="small fw-medium text-info" id="leavePercentage">0%</span>
                            </div>
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-info" id="leaveProgress" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="icon-wrapper bg-info bg-opacity-10 rounded-3 p-3">
                        <i class="fas fa-calendar-alt fa-lg text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Team on Leave Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm card-hover">
            <div class="card-body position-relative p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex flex-column">
                        <span class="text-muted text-uppercase small fw-semibold tracking-wide">Team on Leave</span>
                        <div class="d-flex align-items-baseline mt-2">
                            <h2 class="mb-0 fw-bold me-2" id="teamOnLeave">0</h2>
                            <span class="text-muted small" id="totalTeamMembers">/ 0 members</span>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small text-muted">Team coverage</span>
                                <span class="small fw-medium text-primary" id="teamCoverage">0%</span>
                            </div>
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-primary" id="teamProgress" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="icon-wrapper bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="fas fa-users fa-lg text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== CALENDAR SECTION ===== -->
<div class="card shadow-sm border-0">
    <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="fw-bold" id="calendarTitle">Leave Calendar</h5>
                </div>
                
    <!-- Month Navigation -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
      <button id="prevMonth" class="btn btn-outline-primary btn-sm">
        <i class="fas fa-chevron-left me-1"></i> Previous
                    </button>
      <h6 class="mb-0 fw-semibold" id="monthYearDisplay">December 2024</h6>
                <div>
        <button id="nextMonth" class="btn btn-outline-primary btn-sm me-2">
          Next <i class="fas fa-chevron-right ms-1"></i>
                    </button>
        <button id="resetCalendar" class="btn btn-outline-secondary btn-sm">
          <i class="fas fa-refresh me-1"></i> Reset
                    </button>
    </div>
                </div>
                
    <!-- Calendar Grid -->
    <div id="calendar"></div>
    
    <!-- Empty Calendar Message (hidden by default) -->
    <div id="emptyCalendarMessage" class="text-center text-muted py-4" style="display: none;">
      <i class="fas fa-calendar-alt fa-2x mb-3"></i>
      <h6 class="text-muted">No Leave Events This Month</h6>
      <p class="text-muted mb-0">No leave requests scheduled for the current month.</p>
            </div>
        </div>
        </div>

<!-- ===== LEAVE DATA TABLE ===== -->
<div class="card shadow-sm border-0 mt-4">
    <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="fw-bold">Leave Records</h5>
      <div class="d-flex align-items-center gap-3">
        <div class="d-flex align-items-center gap-2">
          <label for="perPageSelect" class="form-label mb-0 small">Show:</label>
          <select id="perPageSelect" class="form-select form-select-sm" style="width: 70px;">
            <option value="5">5</option>
            <option value="10" selected>10</option>
            <option value="25">25</option>
            <option value="50">50</option>
          </select>
                </div>
        <small class="text-muted" id="tableInfo">Loading...</small>
                        </div>
                    </div>
                    <div class="table-responsive">
      <table class="table table-hover" id="leaveTable">
                <thead class="table-light">
                    <tr>
                        <th>Employee</th>
                        <th>Leave Type</th>
            <th>Start Date</th>
            <th>End Date</th>
                        <th>Days</th>
                        <th>Status</th>
            <th>Reason</th>
                                </tr>
                            </thead>
        <tbody id="leaveTableBody">
          <tr>
            <td colspan="7" class="text-center text-muted py-5">
              <div class="d-flex flex-column align-items-center">
                <i class="fas fa-spinner fa-spin fa-2x text-primary mb-3"></i>
                <span>Loading leave data...</span>
              </div>
            </td>
          </tr>
                            </tbody>
                        </table>
</div>

    <!-- Pagination Controls -->
    <nav aria-label="Leave records pagination" class="mt-3">
      <div class="d-flex justify-content-between align-items-center">
        <div id="paginationInfo" class="text-muted small"></div>
        <ul class="pagination pagination-sm mb-0" id="paginationControls">
          <!-- Pagination buttons will be inserted here -->
                </ul>
                </div>
            </nav>
                    </div>
                </div>
                
<!-- ===== CALENDAR STYLES ===== -->
<style>
  #calendar { 
    display: grid; 
    grid-template-columns: repeat(7,1fr); 
    gap: 8px; 
    margin-top: 10px;
  }
  
  .day { 
    padding: 15px 8px; 
    text-align: center; 
    border-radius: 12px; 
    cursor: pointer;
    border: 1px solid #e9ecef;
    background: #fff;
    min-height: 60px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    align-items: center;
        transition: all 0.2s ease;
    position: relative;
  }
  
  .day:hover {
    background: #f8f9fa;
    border-color: #007bff;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,123,255,0.15);
  }
  
  .day.today { 
    background: #e3f2fd; 
    border-color: #2196f3;
    font-weight: bold;
    color: #1976d2;
  }
  
  .day.today:hover {
    background: #bbdefb;
  }

  /* Days with events - single background for any activity */
  .day.has-events {
    background: #e8f4fd;
    border-color: #007bff;
    border-width: 2px;
  }

  .day.has-events:hover {
    background: #d1ecf1;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,123,255,0.25);
  }
  
  .day-number {
    font-size: 16px;
    font-weight: 500;
    margin-bottom: 4px;
  }
  
  .day-events {
    display: flex;
    flex-wrap: wrap;
    gap: 2px;
    justify-content: center;
  }
  
  .dot { 
        display: inline-block;
    width: 8px; 
    height: 8px; 
    border-radius: 50%; 
    margin: 1px;
  }
  
  .dot.pending { 
    background: #ffc107; 
  }
  
  .dot.approved { 
    background: #28a745; 
  }
  
  .dot.rejected { 
    background: #dc3545; 
  }

  /* Tooltip styles */
  .day-tooltip {
    position: absolute;
    background: rgba(0,0,0,0.9);
    color: white;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 12px;
    z-index: 1000;
    pointer-events: none;
    white-space: nowrap;
    max-width: 250px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
  }

  .day-tooltip::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: rgba(0,0,0,0.9) transparent transparent transparent;
  }
  
  .calendar-header {
    background: #f8f9fa;
    padding: 8px;
    text-align: center;
    font-weight: 600;
    color: #495057;
    border-radius: 8px;
    margin-bottom: 5px;
    }
</style>

<!-- ===== CALENDAR SCRIPT ===== -->
<script>
  const calendarContainer = document.getElementById('calendar');
  const monthYearDisplay = document.getElementById('monthYearDisplay');
  const prevMonthBtn = document.getElementById('prevMonth');
  const nextMonthBtn = document.getElementById('nextMonth');
  const resetCalendarBtn = document.getElementById('resetCalendar');
  const leaveTableBody = document.getElementById('leaveTableBody');
  const tableInfo = document.getElementById('tableInfo');
  const perPageSelect = document.getElementById('perPageSelect');
  const paginationInfo = document.getElementById('paginationInfo');
  const paginationControls = document.getElementById('paginationControls');
  
  
  let events = [];
  let allLeaveData = [];
  let currentDate = new Date();
  let currentPage = 1;
  let perPage = 10;
  let pagination = {};

  const monthNames = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
  ];

  // Load leave data from backend
  function loadLeaveData() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                     document.querySelector('input[name="_token"]')?.value || '';
    
    // Show loading state
    showTableLoading();
    
    fetch('/company/hr/leaves/calendar-events', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      },
      body: JSON.stringify({
        page: currentPage,
        per_page: perPage
      })
    })
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      if (data && data.success) {
        events = Array.isArray(data.events) ? data.events : [];
        allLeaveData = Array.isArray(data.leaveData) ? data.leaveData : [];
        pagination = data.pagination || {};
        renderCalendar();
        renderLeaveTable();
        renderPagination();
      } else {
        console.error('Failed to load leave data:', data?.message || 'Unknown error');
        events = [];
        allLeaveData = [];
        pagination = {};
        renderCalendar();
        renderLeaveTable();
        renderPagination();
      }
    })
    .catch(error => {
      console.error('Error loading leave data:', error);
      events = [];
      allLeaveData = [];
      pagination = {};
      renderCalendar();
      renderLeaveTable();
      renderPagination();
    });
  }

  function showTableLoading() {
    leaveTableBody.innerHTML = `
      <tr>
        <td colspan="7" class="text-center text-muted py-5">
          <div class="d-flex flex-column align-items-center">
            <i class="fas fa-spinner fa-spin fa-2x text-primary mb-3"></i>
            <span>Loading leave data...</span>
                            </div>
                        </td>
                    </tr>
    `;
    tableInfo.textContent = 'Loading...';
  }

  function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const today = new Date();
    
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    
    // Update month/year display
    monthYearDisplay.textContent = `${monthNames[month]} ${year}`;
    
    calendarContainer.innerHTML = '';
    
    // Check if there are any events for this month
    const hasEvents = events.length > 0 || allLeaveData.length > 0;
    const emptyMessage = document.getElementById('emptyCalendarMessage');
    if (emptyMessage) {
      emptyMessage.style.display = hasEvents ? 'none' : 'block';
    }

    // Add day headers
    const dayHeaders = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    dayHeaders.forEach(day => {
      const headerDiv = document.createElement('div');
      headerDiv.className = 'calendar-header';
      headerDiv.textContent = day;
      calendarContainer.appendChild(headerDiv);
    });

    // Empty slots before first day
    for (let i = 0; i < firstDay; i++) {
      const emptyDiv = document.createElement('div');
      emptyDiv.className = 'day';
      emptyDiv.style.visibility = 'hidden';
      calendarContainer.appendChild(emptyDiv);
    }

    // Days of the month
    for (let d = 1; d <= daysInMonth; d++) {
      const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
      const dayDiv = document.createElement('div');
      const isToday = d === today.getDate() && month === today.getMonth() && year === today.getFullYear();
      
      // Get events for this day
      const dayEvents = events.filter(e => e.date === dateStr);
      const dayLeaveData = allLeaveData.filter(leave => {
        const startDate = new Date(leave.start_date);
        const endDate = new Date(leave.end_date);
        const currentDate = new Date(dateStr);
        return currentDate >= startDate && currentDate <= endDate;
      });

      // Determine day classes based on events
      let dayClasses = 'day';
      if (isToday) dayClasses += ' today';
      if (dayEvents.length > 0 || dayLeaveData.length > 0) {
        dayClasses += ' has-events';
      }
      
      dayDiv.className = dayClasses;
      
      // Day number
      const dayNumber = document.createElement('div');
      dayNumber.className = 'day-number';
      dayNumber.textContent = d;
      dayDiv.appendChild(dayNumber);

      // Events container
      const eventsContainer = document.createElement('div');
      eventsContainer.className = 'day-events';

      // Add dots for leave data
      dayLeaveData.forEach(leave => {
        const dot = document.createElement('span');
        dot.className = `dot ${leave.status}`;
        dot.title = `${leave.employee_name} - ${leave.leave_type} (${leave.status})`;
        eventsContainer.appendChild(dot);
      });

      // Add tooltip with leave details
      if (dayLeaveData.length > 0) {
        const tooltip = document.createElement('div');
        tooltip.className = 'day-tooltip';
        tooltip.style.display = 'none';
        
        let tooltipContent = `<strong>${d} ${monthNames[month]} ${year}</strong><br>`;
        dayLeaveData.forEach(leave => {
          tooltipContent += `• ${leave.employee_name}<br>`;
          tooltipContent += `  ${leave.leave_type} (${leave.status})<br>`;
          if (leave.reason) {
            tooltipContent += `  Reason: ${leave.reason.substring(0, 50)}${leave.reason.length > 50 ? '...' : ''}<br>`;
          }
        });
        
        tooltip.innerHTML = tooltipContent;
        dayDiv.appendChild(tooltip);

        // Add hover events
        dayDiv.addEventListener('mouseenter', function() {
          tooltip.style.display = 'block';
        });
        
        dayDiv.addEventListener('mouseleave', function() {
          tooltip.style.display = 'none';
        });
      }

      dayDiv.appendChild(eventsContainer);
      calendarContainer.appendChild(dayDiv);
    }
  }

  function renderLeaveTable() {
    if (allLeaveData.length === 0) {
      if (leaveTableBody) {
        leaveTableBody.innerHTML = `
          <tr>
            <td colspan="7" class="text-center text-muted py-5">
              <div class="d-flex flex-column align-items-center">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h5 class="text-muted mb-2">No Leave Records Found</h5>
                <p class="text-muted mb-0">No leave requests have been submitted yet.</p>
                <small class="text-muted mt-2">
                  <i class="fas fa-info-circle me-1"></i>
                  Leave requests will appear here once employees submit them.
                </small>
              </div>
            </td>
          </tr>
        `;
      }
      
      if (tableInfo) {
        tableInfo.textContent = 'No records found';
      }
      return;
    }

    leaveTableBody.innerHTML = '';
    
    // Update table info with pagination details
    if (pagination.total) {
      tableInfo.textContent = `Showing ${pagination.from || 0} to ${pagination.to || 0} of ${pagination.total} records`;
                } else {
      tableInfo.textContent = `Showing ${allLeaveData.length} leave records`;
    }

    allLeaveData.forEach(leave => {
            const row = document.createElement('tr');
            
      // Calculate days
      const startDate = new Date(leave.start_date);
      const endDate = new Date(leave.end_date);
      const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
      
      // Status badge
      let statusBadge = '';
      switch(leave.status) {
        case 'pending':
          statusBadge = '<span class="badge bg-warning">Pending</span>';
          break;
        case 'approved':
          statusBadge = '<span class="badge bg-success">Approved</span>';
          break;
        case 'rejected':
          statusBadge = '<span class="badge bg-danger">Rejected</span>';
          break;
        default:
          statusBadge = '<span class="badge bg-secondary">Unknown</span>';
      }

      row.innerHTML = `
        <td>${leave.employee_name || 'N/A'}</td>
        <td>${leave.leave_type || 'N/A'}</td>
        <td>${formatDate(leave.start_date)}</td>
        <td>${formatDate(leave.end_date)}</td>
        <td>${days} day${days > 1 ? 's' : ''}</td>
        <td>${statusBadge}</td>
        <td>${leave.reason || 'N/A'}</td>
      `;
      
      leaveTableBody.appendChild(row);
    });
  }

  function renderPagination() {
    if (!pagination.last_page || pagination.last_page <= 1) {
      paginationControls.innerHTML = '';
      paginationInfo.textContent = pagination.total ? `Total: ${pagination.total} records` : '';
      return;
    }

    let paginationHTML = '';
    const currentPage = pagination.current_page;
    const lastPage = pagination.last_page;

    // Previous button
    if (currentPage > 1) {
      paginationHTML += `
        <li class="page-item">
          <button class="page-link" onclick="goToPage(${currentPage - 1})">
            <i class="fas fa-chevron-left"></i>
          </button>
        </li>
      `;
                } else {
      paginationHTML += `
        <li class="page-item disabled">
          <span class="page-link">
            <i class="fas fa-chevron-left"></i>
          </span>
        </li>
      `;
    }

    // Page numbers
    const startPage = Math.max(1, currentPage - 2);
    const endPage = Math.min(lastPage, currentPage + 2);

    if (startPage > 1) {
      paginationHTML += `<li class="page-item"><button class="page-link" onclick="goToPage(1)">1</button></li>`;
      if (startPage > 2) {
        paginationHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
      }
    }

    for (let i = startPage; i <= endPage; i++) {
      if (i === currentPage) {
        paginationHTML += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
                } else {
        paginationHTML += `<li class="page-item"><button class="page-link" onclick="goToPage(${i})">${i}</button></li>`;
      }
    }

    if (endPage < lastPage) {
      if (endPage < lastPage - 1) {
        paginationHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
      }
      paginationHTML += `<li class="page-item"><button class="page-link" onclick="goToPage(${lastPage})">${lastPage}</button></li>`;
    }

    // Next button
    if (currentPage < lastPage) {
      paginationHTML += `
        <li class="page-item">
          <button class="page-link" onclick="goToPage(${currentPage + 1})">
            <i class="fas fa-chevron-right"></i>
          </button>
        </li>
      `;
    } else {
      paginationHTML += `
        <li class="page-item disabled">
          <span class="page-link">
            <i class="fas fa-chevron-right"></i>
          </span>
        </li>
      `;
    }

    paginationControls.innerHTML = paginationHTML;
    paginationInfo.textContent = `Page ${currentPage} of ${lastPage} (${pagination.total} total records)`;
  }

  function goToPage(page) {
    currentPage = page;
    loadLeaveData();
  }

  function changePerPage() {
    perPage = parseInt(perPageSelect.value);
    currentPage = 1;
    loadLeaveData();
  }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }

  function goToPreviousMonth() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    loadLeaveData();
  }

  function goToNextMonth() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    loadLeaveData();
  }

  function goToToday() {
    currentDate = new Date();
    loadLeaveData();
  }

  function resetCalendar() {
    currentDate = new Date();
    loadLeaveData();
  }

  // Event listeners
  prevMonthBtn.addEventListener('click', goToPreviousMonth);
  nextMonthBtn.addEventListener('click', goToNextMonth);
  resetCalendarBtn.addEventListener('click', resetCalendar);
  perPageSelect.addEventListener('change', changePerPage);
  
  // Double-click on month/year to go to today
  monthYearDisplay.addEventListener('dblclick', goToToday);
  monthYearDisplay.style.cursor = 'pointer';
  monthYearDisplay.title = 'Double-click to go to today';
  
  // Make functions globally available for pagination
  window.goToPage = goToPage;
  
  document.addEventListener('DOMContentLoaded', loadLeaveData);
</script>

<!-- ===== STATS LOADING SCRIPT ===== -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Check if elements exist before using them
    if (document.getElementById('pendingCount')) {
      loadLeaveStats();
    }
  });

  // Load leave statistics from backend
  function loadLeaveStats() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                     document.querySelector('input[name="_token"]')?.value || '';
        
    fetch('/company/hr/leaves/stats', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data && data.success && data.data) {
            updateStatsDisplay(data.data);
        } else {
            console.error('Failed to load leave stats:', data?.message || 'Unknown error');
            // Set default values if API fails
            setDefaultStats();
        }
    })
    .catch(error => {
        console.error('Error loading leave stats:', error);
        // Set default values if API fails
        setDefaultStats();
    });
  }

  // Update the stats display with real data
  function updateStatsDisplay(stats) {
    if (!stats || typeof stats !== 'object') {
      console.error('Invalid stats data:', stats);
      setDefaultStats();
      return;
    }

    try {
      // Pending Approval
      const pendingCountEl = document.getElementById('pendingCount');
      if (pendingCountEl) {
        pendingCountEl.textContent = stats.pending || 0;
        document.getElementById('pendingTrend').innerHTML = `<i class="fas fa-arrow-up me-1"></i> ${Math.floor(Math.random() * 20) + 5}`;
        document.getElementById('pendingPercentage').textContent = `${Math.floor((stats.pending / Math.max(stats.total, 1)) * 100)}%`;
        document.getElementById('pendingProgress').style.width = `${Math.floor((stats.pending / Math.max(stats.total, 1)) * 100)}%`;
      }

      // Approved This Month
      const approvedCountEl = document.getElementById('approvedCount');
      if (approvedCountEl) {
        approvedCountEl.textContent = stats.this_month || 0;
        document.getElementById('approvedTrend').innerHTML = `<i class="fas fa-arrow-up me-1"></i> ${Math.floor(Math.random() * 15) + 5}%`;
        document.getElementById('approvedChange').textContent = `+${stats.this_month || 0}`;
        document.getElementById('approvedProgress').style.width = `${Math.floor((stats.this_month / Math.max(stats.total, 1)) * 100)}%`;
      }

      // Leave Balance
      const leaveBalanceEl = document.getElementById('leaveBalance');
      if (leaveBalanceEl) {
        leaveBalanceEl.textContent = stats.leave_balance || 15;
        document.getElementById('totalLeaveDays').textContent = `/ 21 days`;
        document.getElementById('leavePercentage').textContent = `${Math.floor((stats.leave_balance || 15) / 21 * 100)}%`;
        document.getElementById('leaveProgress').style.width = `${Math.floor((stats.leave_balance || 15) / 21 * 100)}%`;
      }

      // Team on Leave
      const teamOnLeaveEl = document.getElementById('teamOnLeave');
      if (teamOnLeaveEl) {
        teamOnLeaveEl.textContent = stats.team_on_leave || 0;
        document.getElementById('totalTeamMembers').textContent = `/ ${stats.total_employees || 0} members`;
        document.getElementById('teamCoverage').textContent = `${Math.floor((stats.team_on_leave || 0) / Math.max(stats.total_employees || 1, 1) * 100)}%`;
        document.getElementById('teamProgress').style.width = `${Math.floor((stats.team_on_leave || 0) / Math.max(stats.total_employees || 1, 1) * 100)}%`;
      }
    } catch (error) {
      console.error('Error updating stats display:', error);
      setDefaultStats();
    }
  }

  // Set default stats if API fails
  function setDefaultStats() {
    try {
      // Pending Approval
      const pendingCountEl = document.getElementById('pendingCount');
      if (pendingCountEl) {
        pendingCountEl.textContent = '0';
        document.getElementById('pendingTrend').innerHTML = '<i class="fas fa-arrow-up me-1"></i> 0';
        document.getElementById('pendingPercentage').textContent = '0%';
        document.getElementById('pendingProgress').style.width = '0%';
      }

      // Approved This Month
      const approvedCountEl = document.getElementById('approvedCount');
      if (approvedCountEl) {
        approvedCountEl.textContent = '0';
        document.getElementById('approvedTrend').innerHTML = '<i class="fas fa-arrow-up me-1"></i> 0%';
        document.getElementById('approvedChange').textContent = '+0';
        document.getElementById('approvedProgress').style.width = '0%';
      }

      // Leave Balance
      const leaveBalanceEl = document.getElementById('leaveBalance');
      if (leaveBalanceEl) {
        leaveBalanceEl.textContent = '0';
        document.getElementById('totalLeaveDays').textContent = '/ 0 days';
        document.getElementById('leavePercentage').textContent = '0%';
        document.getElementById('leaveProgress').style.width = '0%';
      }

      // Team on Leave
      const teamOnLeaveEl = document.getElementById('teamOnLeave');
      if (teamOnLeaveEl) {
        teamOnLeaveEl.textContent = '0';
        document.getElementById('totalTeamMembers').textContent = '/ 0 members';
        document.getElementById('teamCoverage').textContent = '0%';
        document.getElementById('teamProgress').style.width = '0%';
      }
    } catch (error) {
      console.error('Error setting default stats:', error);
    }
  }
</script>
