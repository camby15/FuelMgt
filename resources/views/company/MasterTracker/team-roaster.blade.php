@extends('layouts.vertical', ['page_title' => 'Team Roaster', 'mode' => session('theme_mode', 'light')])

@section('css')
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
<script>
// Ensure SweetAlert is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('SweetAlert loaded:', typeof Swal !== 'undefined');
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert failed to load!');
    }
});
</script>

<!-- SweetAlert is now ready for use in form submissions -->
<script>

// Form submission SweetAlert handler - moved here for early availability
window.handleRosterFormSubmission = function() {
    console.log('Setting up form event listener...');
    const createRosterForm = document.getElementById('createRosterForm');
    console.log('Form found:', createRosterForm);
    
    if (createRosterForm) {
        console.log('Adding event listener to form...');
        createRosterForm.addEventListener('submit', function(e) {
            console.log('Create roster form submit event triggered');
            e.preventDefault();
            console.log('Default prevented');

            console.log('Form action:', this.action);
            console.log('CSRF token:', document.querySelector('meta[name="csrf-token"]').content);
            
            const formData = new FormData(this);
            console.log('Form data:');
            for (let [key, value] of formData.entries()) {
                console.log(key, ':', value);
            }
            
            console.log('Making fetch request...');
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                console.log('Fetch response:', response);
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(response => {
                console.log('Response received:', response);
                console.log('Response status field:', response.status);
                console.log('Response success field:', response.success);
                
                const modal = bootstrap.Modal.getInstance(document.getElementById('createRosterModal'));
                modal.hide();

                // Wait for modal to close completely before showing SweetAlert
                setTimeout(() => {
                    console.log('Timeout executed - checking response...');
                    console.log('Response status:', response.status);
                    console.log('Response success:', response.success);
                    console.log('Full response:', response);
                    
                    // Check both possible response formats
                    if (response.status === 'success' || response.success === true) {
                        console.log('Response indicates success - showing SweetAlert');
                        console.log('Swal available:', typeof Swal !== 'undefined');
                        console.log('Swal object:', Swal);
                        
                        // Show SweetAlert immediately without complex error handling
                        if (typeof Swal !== 'undefined' && Swal.fire) {
                            console.log('Showing SweetAlert...');
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message || 'Team roster created successfully!',
                                showConfirmButton: true,
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#3085d6',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                timer: null
                            }).then((result) => {
                                console.log('SweetAlert result:', result);
                                if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                                    console.log('User confirmed - reloading page...');
                                    location.reload();
                                }
                            });
                        } else {
                            console.log('SweetAlert not available, using alert...');
                            alert('Success! Team roster created successfully!');
                            setTimeout(() => location.reload(), 1000);
                        }
                    } else {
                        console.log('Response indicates failure:', response);
                        throw new Error(response.message || 'Something went wrong');
                    }
                }, 300); // Reduced timeout for faster response
            })
            .catch(error => {
                console.log('Error caught:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Failed to create team roster. Please try again.',
                    showConfirmButton: true,
                    confirmButtonColor: '#3085d6'
                });
            });
        });
    } else {
        console.error('Create roster form not found!');
        alert('Error: Create roster form not found!');
    }
};

// Initialize the form handler immediately
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing roster form handler...');
    window.handleRosterFormSubmission();
    
    // Initialize calendar preview
    initializeCalendarPreview();
    
    // Initialize edit calendar preview
    initializeEditCalendarPreview();
    
    // Initialize stats cards animations
    initializeStatsCards();
    
    // Initialize view toggle functionality
    initializeViewToggle();
    
    // Initialize calendar view immediately
    initializeCalendarView();
    
    // Initialize calendar view toggle functionality
    initializeCalendarViewToggle();
});

// View Toggle Function - moved here for early availability
window.toggleView = function(viewType) {
    const calendarView = document.getElementById('calendarViewContainer');
    const tableView = document.getElementById('tableViewContainer');
    
    if (viewType === 'calendar') {
        calendarView.style.display = 'block';
        tableView.style.display = 'none';
        document.getElementById('calendarView').checked = true;
        
        // Initialize calendar if not already done
        if (calendarView && calendarView.innerHTML.trim() === '') {
            initializeCalendarView();
        }
    } else {
        calendarView.style.display = 'none';
        tableView.style.display = 'block';
        document.getElementById('tableView').checked = true;
        
        // Initialize DataTable when switching to table view (with safety check)
        if (typeof $ !== 'undefined' && $.fn.DataTable && !$.fn.DataTable.isDataTable('#rosterDataTable')) {
            initializeRosterDataTable();
        }
    }
};

// Global view functions for Quick Actions
window.viewCalendar = function() {
    $('#calendarView').prop('checked', true);
    currentView = 'calendar';
    toggleView('calendar');
}

window.viewTable = function() {
    $('#tableView').prop('checked', true);
    currentView = 'table';
    toggleView('table');
}

// Initialize calendar view
function initializeCalendarView() {
    const calendarContainer = document.getElementById('calendarViewContainer');
    if (calendarContainer) {
        calendarContainer.innerHTML = `
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-alt me-2 text-primary"></i>Calendar View
                        </h5>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="refreshCalendar()">
                                <i class="fas fa-sync-alt me-1"></i>Refresh
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm" onclick="loadRosterCalendar()">
                                <i class="fas fa-calendar-week me-1"></i>Load Rosters
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="calendar" style="min-height: 600px;">
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Roster Calendar</h5>
                            <p class="text-muted">View your team rosters in calendar format</p>
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRosterModal">
                                    <i class="fas fa-plus me-2"></i>Create Roster
                                </button>
                                <button type="button" class="btn btn-outline-info" onclick="loadRosterCalendar()">
                                    <i class="fas fa-calendar-week me-2"></i>Load Rosters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Load roster calendar data
        loadRosterCalendar();
    }
}

// Load roster calendar data
function loadRosterCalendar() {
    const calendarElement = document.getElementById('calendar');
    if (!calendarElement) return;
    
    // Fetch roster data and display in calendar format
    fetch('/company/MasterTracker/team-roaster')
        .then(response => response.text())
        .then(html => {
            // Extract roster data from the page (since we're on the same page)
            const rosterRows = document.querySelectorAll('#rosterDataTable tbody tr');
            if (rosterRows.length > 0) {
                displayRosterCalendar(rosterRows);
            } else {
                calendarElement.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Rosters Found</h5>
                        <p class="text-muted">Create your first team roster to see it in the calendar</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRosterModal">
                            <i class="fas fa-plus me-2"></i>Create Roster
                        </button>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading roster calendar:', error);
            calendarElement.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h5 class="text-warning">Error Loading Calendar</h5>
                    <p class="text-muted">Unable to load roster data</p>
                    <button type="button" class="btn btn-outline-primary" onclick="loadRosterCalendar()">
                        <i class="fas fa-redo me-2"></i>Retry
                    </button>
                </div>
            `;
        });
}

// Display roster calendar
function displayRosterCalendar(rosterRows) {
    const calendarElement = document.getElementById('calendar');
    if (!calendarElement) return;
    
    // Extract roster data
    const rosters = [];
    rosterRows.forEach(row => {
        const rosterName = row.cells[0]?.querySelector('h6')?.textContent || 'Untitled Roster';
        const teamName = row.cells[1]?.querySelector('.badge')?.textContent || 'N/A';
        const startDate = row.cells[3]?.textContent?.trim() || '';
        const endDate = row.cells[4]?.textContent?.trim() || '';
        const workingDays = Array.from(row.cells[5]?.querySelectorAll('.badge') || []).map(badge => badge.textContent);
        const workingHours = row.cells[6]?.querySelector('.fw-bold')?.textContent || '';
        const status = row.cells[7]?.querySelector('.badge')?.textContent || '';
        
        if (startDate && endDate) {
            rosters.push({
                name: rosterName,
                team: teamName,
                startDate: new Date(startDate),
                endDate: new Date(endDate),
                workingDays,
                workingHours,
                status
            });
        }
    });
    
    // Generate calendar grid
    generateCalendarGrid(rosters);
}

// Generate calendar grid like Google Calendar
function generateCalendarGrid(rosters) {
    const calendarElement = document.getElementById('calendar');
    if (!calendarElement) return;
    
    const today = new Date();
    const currentMonth = today.getMonth();
    const currentYear = today.getFullYear();
    
    // Generate calendar with view options
    const calendarHTML = generateCalendarWithViews(currentYear, currentMonth, rosters);
    calendarElement.innerHTML = calendarHTML;
}

// Generate calendar with view options
function generateCalendarWithViews(year, month, rosters) {
    const monthNames = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];
    
    return `
        <div class="calendar-container">
            <div class="calendar-header mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-white">
                        <i class="fas fa-calendar-alt me-2 text-white"></i>
                        ${monthNames[month]} ${year}
                    </h4>
                    <div class="d-flex align-items-center gap-3">
                        <div class="calendar-view-toggle">
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="calendarView" id="dayView" value="day">
                                <label class="btn btn-outline-white btn-sm" for="dayView">Day</label>
                                
                                <input type="radio" class="btn-check" name="calendarView" id="weekView" value="week">
                                <label class="btn btn-outline-white btn-sm" for="weekView">Week</label>
                                
                                <input type="radio" class="btn-check" name="calendarView" id="monthView" value="month" checked>
                                <label class="btn btn-outline-white btn-sm" for="monthView">Month</label>
                            </div>
                        </div>
                        <div class="calendar-navigation">
                            <button type="button" class="btn btn-outline-white btn-sm me-2" onclick="previousPeriod()">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button type="button" class="btn btn-outline-white btn-sm me-2" onclick="goToCurrentPeriod()">
                                Today
                            </button>
                            <button type="button" class="btn btn-outline-white btn-sm" onclick="nextPeriod()">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="calendarContent">
                ${generateMonthCalendar(year, month, rosters)}
            </div>
        </div>
    `;
}

// Generate a single month calendar
function generateMonthCalendar(year, month, rosters) {
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startingDayOfWeek = firstDay.getDay();
    
    const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    
    let calendarHTML = `
        <div class="calendar-grid month-view">
            <div class="calendar-weekdays">
                ${dayNames.map(day => `<div class="calendar-weekday">${day}</div>`).join('')}
            </div>
            <div class="calendar-days">
    `;
    
    // Add empty cells for days before the first day of the month
    for (let i = 0; i < startingDayOfWeek; i++) {
        calendarHTML += `<div class="calendar-day empty-day"></div>`;
    }
    
    // Add days of the month
    for (let day = 1; day <= daysInMonth; day++) {
        const currentDate = new Date(year, month, day);
        const dayRosters = getRostersForDate(currentDate, rosters);
        const isToday = isSameDay(currentDate, new Date());
        
        calendarHTML += `
            <div class="calendar-day ${isToday ? 'today' : ''}">
                <div class="calendar-day-number">${day}</div>
                <div class="calendar-events">
                    ${dayRosters.map(roster => generateRosterEvent(roster, currentDate)).join('')}
                </div>
            </div>
        `;
    }
    
    calendarHTML += `
            </div>
        </div>
    `;
    
    return calendarHTML;
}

// Generate week view
function generateWeekCalendar(year, month, day, rosters) {
    const startOfWeek = new Date(year, month, day);
    startOfWeek.setDate(startOfWeek.getDate() - startOfWeek.getDay()); // Start from Sunday
    
    const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    
    let calendarHTML = `
        <div class="calendar-grid week-view">
            <div class="calendar-weekdays">
                ${dayNames.map(day => `<div class="calendar-weekday">${day}</div>`).join('')}
            </div>
            <div class="calendar-days">
    `;
    
    // Generate 7 days for the week
    for (let i = 0; i < 7; i++) {
        const currentDate = new Date(startOfWeek);
        currentDate.setDate(startOfWeek.getDate() + i);
        const dayRosters = getRostersForDate(currentDate, rosters);
        const isToday = isSameDay(currentDate, new Date());
        
        calendarHTML += `
            <div class="calendar-day week-day ${isToday ? 'today' : ''}">
                <div class="calendar-day-number">${currentDate.getDate()}</div>
                <div class="calendar-events">
                    ${dayRosters.map(roster => generateRosterEvent(roster, currentDate)).join('')}
                </div>
            </div>
        `;
    }
    
    calendarHTML += `
            </div>
        </div>
    `;
    
    return calendarHTML;
}

// Generate day view
function generateDayCalendar(year, month, day, rosters) {
    const currentDate = new Date(year, month, day);
    const dayRosters = getRostersForDate(currentDate, rosters);
    const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const monthNames = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];
    
    let calendarHTML = `
        <div class="calendar-grid day-view">
            <div class="day-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-day me-2 text-primary"></i>
                    ${dayNames[currentDate.getDay()]}, ${monthNames[month]} ${day}, ${year}
                </h5>
            </div>
            <div class="day-content">
                <div class="day-events">
    `;
    
    if (dayRosters.length > 0) {
        dayRosters.forEach(roster => {
            const statusClass = roster.status.toLowerCase() === 'active' ? 'success' : 
                               roster.status.toLowerCase() === 'draft' ? 'warning' : 'danger';
            
            calendarHTML += `
                <div class="day-event-card card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="mb-0">${roster.name}</h6>
                            <span class="badge bg-${statusClass}">${roster.status}</span>
                        </div>
                        <div class="event-details">
                            <p class="mb-1"><strong>Team:</strong> ${roster.team}</p>
                            <p class="mb-1"><strong>Working Hours:</strong> ${roster.workingHours}</p>
                            <div class="working-days">
                                <strong>Working Days:</strong>
                                <div class="d-flex flex-wrap gap-1 mt-1">
                                    ${roster.workingDays.map(day => `<span class="badge bg-success">${day}</span>`).join('')}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
    } else {
        calendarHTML += `
            <div class="no-events text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Rosters Scheduled</h5>
                <p class="text-muted">No team rosters are scheduled for this day.</p>
            </div>
        `;
    }
    
    calendarHTML += `
                </div>
            </div>
        </div>
    `;
    
    return calendarHTML;
}

// Get rosters that are active on a specific date
function getRostersForDate(date, rosters) {
    return rosters.filter(roster => {
        const startDate = roster.startDate;
        const endDate = roster.endDate;
        const dayOfWeek = date.getDay();
        const dayName = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'][dayOfWeek];
        
        // Check if date is within roster period
        if (date >= startDate && date <= endDate) {
            // Check if this day is a working day
            return roster.workingDays.some(day => 
                day.toLowerCase() === dayName.toLowerCase()
            );
        }
        return false;
    });
}

// Generate roster event HTML
function generateRosterEvent(roster, date) {
    const statusClass = roster.status.toLowerCase() === 'active' ? 'success' : 
                       roster.status.toLowerCase() === 'draft' ? 'warning' : 'danger';
    
    return `
        <div class="calendar-event roster-event bg-${statusClass}" 
             onclick="showRosterEventDetails('${roster.name}', '${roster.team}', '${roster.workingHours}', '${roster.status}')"
             title="${roster.name} - ${roster.team}">
            <div class="event-title">${roster.name}</div>
            <div class="event-details">
                <small>${roster.team} • ${roster.workingHours}</small>
            </div>
        </div>
    `;
}

// Helper functions
function isSameDay(date1, date2) {
    return date1.getDate() === date2.getDate() &&
           date1.getMonth() === date2.getMonth() &&
           date1.getFullYear() === date2.getFullYear();
}

// Calendar state management
let currentCalendarState = {
    year: new Date().getFullYear(),
    month: new Date().getMonth(),
    day: new Date().getDate(),
    view: 'month'
};

// Calendar navigation functions
function previousPeriod() {
    const currentView = document.querySelector('input[name="calendarView"]:checked')?.value || 'month';
    
    if (currentView === 'day') {
        currentCalendarState.day--;
        if (currentCalendarState.day < 1) {
            currentCalendarState.month--;
            if (currentCalendarState.month < 0) {
                currentCalendarState.month = 11;
                currentCalendarState.year--;
            }
            currentCalendarState.day = new Date(currentCalendarState.year, currentCalendarState.month + 1, 0).getDate();
        }
    } else if (currentView === 'week') {
        currentCalendarState.day -= 7;
        if (currentCalendarState.day < 1) {
            currentCalendarState.month--;
            if (currentCalendarState.month < 0) {
                currentCalendarState.month = 11;
                currentCalendarState.year--;
            }
            const daysInMonth = new Date(currentCalendarState.year, currentCalendarState.month + 1, 0).getDate();
            currentCalendarState.day = Math.max(1, daysInMonth + currentCalendarState.day);
        }
    } else { // month
        currentCalendarState.month--;
        if (currentCalendarState.month < 0) {
            currentCalendarState.month = 11;
            currentCalendarState.year--;
        }
    }
    
    refreshCalendarView();
}

function nextPeriod() {
    const currentView = document.querySelector('input[name="calendarView"]:checked')?.value || 'month';
    
    if (currentView === 'day') {
        const daysInMonth = new Date(currentCalendarState.year, currentCalendarState.month + 1, 0).getDate();
        currentCalendarState.day++;
        if (currentCalendarState.day > daysInMonth) {
            currentCalendarState.day = 1;
            currentCalendarState.month++;
            if (currentCalendarState.month > 11) {
                currentCalendarState.month = 0;
                currentCalendarState.year++;
            }
        }
    } else if (currentView === 'week') {
        const daysInMonth = new Date(currentCalendarState.year, currentCalendarState.month + 1, 0).getDate();
        currentCalendarState.day += 7;
        if (currentCalendarState.day > daysInMonth) {
            currentCalendarState.day -= daysInMonth;
            currentCalendarState.month++;
            if (currentCalendarState.month > 11) {
                currentCalendarState.month = 0;
                currentCalendarState.year++;
            }
        }
    } else { // month
        currentCalendarState.month++;
        if (currentCalendarState.month > 11) {
            currentCalendarState.month = 0;
            currentCalendarState.year++;
        }
    }
    
    refreshCalendarView();
}

function goToCurrentPeriod() {
    const today = new Date();
    currentCalendarState.year = today.getFullYear();
    currentCalendarState.month = today.getMonth();
    currentCalendarState.day = today.getDate();
    refreshCalendarView();
}

// Refresh calendar view based on current state
function refreshCalendarView() {
    const calendarContent = document.getElementById('calendarContent');
    if (!calendarContent) return;
    
    // Get roster data
    const rosterRows = document.querySelectorAll('#rosterDataTable tbody tr');
    const rosters = [];
    rosterRows.forEach(row => {
        const rosterName = row.cells[0]?.querySelector('h6')?.textContent || 'Untitled Roster';
        const teamName = row.cells[1]?.querySelector('.badge')?.textContent || 'N/A';
        const startDate = row.cells[3]?.textContent?.trim() || '';
        const endDate = row.cells[4]?.textContent?.trim() || '';
        const workingDays = Array.from(row.cells[5]?.querySelectorAll('.badge') || []).map(badge => badge.textContent);
        const workingHours = row.cells[6]?.querySelector('.fw-bold')?.textContent || '';
        const status = row.cells[7]?.querySelector('.badge')?.textContent || '';
        
        if (startDate && endDate) {
            rosters.push({
                name: rosterName,
                team: teamName,
                startDate: new Date(startDate),
                endDate: new Date(endDate),
                workingDays,
                workingHours,
                status
            });
        }
    });
    
    const currentView = document.querySelector('input[name="calendarView"]:checked')?.value || 'month';
    
    if (currentView === 'day') {
        calendarContent.innerHTML = generateDayCalendar(currentCalendarState.year, currentCalendarState.month, currentCalendarState.day, rosters);
    } else if (currentView === 'week') {
        calendarContent.innerHTML = generateWeekCalendar(currentCalendarState.year, currentCalendarState.month, currentCalendarState.day, rosters);
    } else {
        calendarContent.innerHTML = generateMonthCalendar(currentCalendarState.year, currentCalendarState.month, rosters);
    }
}

// Initialize calendar view toggle
function initializeCalendarViewToggle() {
    // Add event listeners to view toggle buttons
    document.addEventListener('change', function(e) {
        if (e.target.name === 'calendarView') {
            refreshCalendarView();
        }
    });
}

// Refresh calendar function (for the refresh button)
window.refreshCalendar = function() {
    console.log('Refreshing calendar...');
    loadRosterCalendar();
}

// Load roster calendar function
window.loadRosterCalendar = function() {
    console.log('Loading roster calendar...');
    const calendarElement = document.getElementById('calendar');
    if (!calendarElement) return;

    const rosterRows = document.querySelectorAll('#rosterDataTable tbody tr');
    if (rosterRows.length > 0) {
        displayRosterCalendar(rosterRows);
    } else {
        calendarElement.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Rosters Found</h5>
                <p class="text-muted">Create your first team roster to see it in the calendar.</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRosterModal">
                    <i class="fas fa-plus me-2"></i>Create Team Roster
                </button>
            </div>
        `;
    }
}

// Show roster event details
function showRosterEventDetails(name, team, hours, status) {
    Swal.fire({
        title: name,
        html: `
            <div class="roster-event-details">
                <p><strong>Team:</strong> ${team}</p>
                <p><strong>Working Hours:</strong> ${hours}</p>
                <p><strong>Status:</strong> <span class="badge bg-${status.toLowerCase() === 'active' ? 'success' : status.toLowerCase() === 'draft' ? 'warning' : 'danger'}">${status}</span></p>
            </div>
        `,
        showConfirmButton: true,
        confirmButtonText: 'Close',
        confirmButtonColor: '#3085d6'
    });
}

// Helper functions for roster calculations
function calculateDuration(startDate, endDate) {
    if (!startDate || !endDate || startDate === 'N/A' || endDate === 'N/A') return 'N/A';
    
    try {
        const start = new Date(startDate);
        const end = new Date(endDate);
        const diffTime = Math.abs(end - start);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays === 1) return '1 day';
        if (diffDays < 7) return `${diffDays} days`;
        if (diffDays < 30) return `${Math.ceil(diffDays / 7)} weeks`;
        if (diffDays < 365) return `${Math.ceil(diffDays / 30)} months`;
        return `${Math.ceil(diffDays / 365)} years`;
    } catch (e) {
        return 'N/A';
    }
}

function calculateWorkingDaysPerWeek(workingDays) {
    if (!workingDays || workingDays.length === 0) return 0;
    return workingDays.length;
}

function getWorkingDaysPercentage(workingDays) {
    const totalDays = 7;
    const workingDaysCount = workingDays ? workingDays.length : 0;
    return Math.round((workingDaysCount / totalDays) * 100);
}

function getStatusDescription(status) {
    const statusMap = {
        'active': 'This roster is currently active and being used for scheduling',
        'draft': 'This roster is in draft mode and not yet active',
        'inactive': 'This roster has been deactivated and is no longer in use'
    };
    return statusMap[status.toLowerCase()] || 'Status information not available';
}

function getPeriodDescription(period) {
    const periodMap = {
        'weekly': 'This roster follows a weekly schedule pattern',
        'monthly': 'This roster follows a monthly schedule pattern',
        'daily': 'This roster follows a daily schedule pattern'
    };
    return periodMap[period.toLowerCase()] || 'Period information not available';
}

// Generate team members from actual team pairing data filtered by team
function generateTeamMembers(teamName) {
    // Look for team pairing table in the DOM
    const teamPairingTable = document.querySelector('#teamPairingTable, .team-pairing-table, table[data-table="team-pairing"], table[id*="team"], table[class*="team"]') ||
                            document.querySelector('table tbody tr:first-child')?.closest('table');
    
    if (!teamPairingTable) {
        return `
            <div class="no-team-data">
                <i class="fas fa-users fa-2x text-muted mb-3"></i>
                <p class="text-muted">No team pairing data found. Please check if the team pairing table is loaded.</p>
            </div>
        `;
    }
    
    const teamRows = teamPairingTable.querySelectorAll('tbody tr');
    const teamMembers = [];
    
    teamRows.forEach(row => {
        const cells = row.cells;
        if (cells && cells.length > 0) {
            // Extract team name from the row to filter by specific team
            const rowTeamName = cells[1]?.querySelector('.badge, .team-name, .team')?.textContent?.trim() || 
                               cells[1]?.textContent?.trim() || '';
            
            // Only process rows that match the current team
            if (rowTeamName && rowTeamName.toLowerCase().includes(teamName.toLowerCase())) {
                // Extract member data from table cells
                const memberName = cells[0]?.querySelector('h6, .member-name, .name, .fullname, .user-name')?.textContent?.trim() || 
                                 cells[0]?.textContent?.trim() || 'Unknown Member';
                
                const memberRole = cells[2]?.querySelector('.role, .position, .job-title, .designation, .title')?.textContent?.trim() || 
                                 cells[2]?.textContent?.trim() || 'Team Member';
                
                const memberStatus = cells[3]?.querySelector('.badge, .status, .state')?.textContent?.trim() || 
                                   cells[3]?.textContent?.trim() || 'active';
                
                const memberEmail = cells[4]?.querySelector('a, .email, .mail')?.textContent?.trim() || 
                                  cells[4]?.textContent?.trim() || '';
                
                const memberPhone = cells[5]?.textContent?.trim() || '';
                
                // Generate avatar initials
                const nameParts = memberName.split(' ');
                const avatar = nameParts.length >= 2 ? 
                    (nameParts[0][0] + nameParts[1][0]).toUpperCase() : 
                    memberName.substring(0, 2).toUpperCase();
                
                // Only add if we have a valid name
                if (memberName && memberName !== 'Unknown Member') {
                    teamMembers.push({
                        name: memberName,
                        role: memberRole,
                        status: memberStatus.toLowerCase(),
                        email: memberEmail,
                        phone: memberPhone,
                        avatar: avatar,
                        team: rowTeamName
                    });
                }
            }
        }
    });
    
    if (teamMembers.length === 0) {
        return `
            <div class="no-team-data">
                <i class="fas fa-users fa-2x text-muted mb-3"></i>
                <p class="text-muted">No team members found for team: <strong>${teamName}</strong></p>
                <small class="text-muted">Searched ${teamRows.length} rows in team pairing table</small>
            </div>
        `;
    }
    
    return teamMembers.map(member => `
        <div class="team-member-card">
            <div class="member-avatar">${member.avatar}</div>
            <div class="member-info">
                <div class="member-name">${member.name}</div>
                <div class="member-role">${member.role}</div>
                <div class="member-team"><i class="fas fa-users me-1"></i>${member.team}</div>
                ${member.email ? `<div class="member-email"><i class="fas fa-envelope me-1"></i>${member.email}</div>` : ''}
                ${member.phone ? `<div class="member-phone"><i class="fas fa-phone me-1"></i>${member.phone}</div>` : ''}
                <div class="member-status status-${member.status}">
                    <i class="fas fa-circle"></i> ${member.status}
                </div>
            </div>
        </div>
    `).join('');
}

// Generate mini calendar view with actual roster data
function generateMiniCalendar(rosterData) {
    const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    const workingDays = rosterData.workingDays || [];
    
    // Get the actual roster data from the table
    const rosterRows = document.querySelectorAll('#rosterDataTable tbody tr');
    const currentRoster = Array.from(rosterRows).find(row => {
        const rosterName = row.cells[0]?.querySelector('h6')?.textContent?.trim();
        return rosterName === rosterData.name;
    });
    
    let actualWorkingDays = workingDays;
    let actualHours = rosterData.workingHours;
    let actualPeriod = rosterData.period;
    
    if (currentRoster) {
        // Extract actual working days from the table
        const workingDaysFromTable = Array.from(currentRoster.cells[5]?.querySelectorAll('.badge') || [])
            .map(badge => badge.textContent.trim());
        actualWorkingDays = workingDaysFromTable.length > 0 ? workingDaysFromTable : workingDays;
        
        // Extract actual working hours
        actualHours = currentRoster.cells[6]?.querySelector('.fw-bold')?.textContent?.trim() || rosterData.workingHours;
        
        // Extract actual period
        actualPeriod = currentRoster.cells[2]?.querySelector('.badge')?.textContent?.trim() || rosterData.period;
    }
    
    return `
        <div class="mini-calendar-grid">
            <div class="mini-calendar-header">
                <div class="mini-calendar-weekdays">
                    ${dayNames.map(day => `<div class="mini-weekday">${day}</div>`).join('')}
                </div>
            </div>
            <div class="mini-calendar-days">
                ${dayNames.map(day => {
                    // More flexible matching for working days
                    const isWorkingDay = actualWorkingDays.some(workDay => {
                        const workDayLower = workDay.toLowerCase().trim();
                        const dayLower = day.toLowerCase().trim();
                        
                        // Check for exact match
                        if (workDayLower === dayLower) return true;
                        
                        // Check for partial matches (e.g., "monday" matches "mon")
                        if (workDayLower.includes(dayLower) || dayLower.includes(workDayLower)) return true;
                        
                        // Check for common abbreviations
                        const dayMap = {
                            'sun': 'sunday', 'mon': 'monday', 'tue': 'tuesday', 'wed': 'wednesday',
                            'thu': 'thursday', 'fri': 'friday', 'sat': 'saturday'
                        };
                        
                        if (dayMap[dayLower] && workDayLower.includes(dayMap[dayLower])) return true;
                        if (dayMap[workDayLower] && dayLower.includes(dayMap[workDayLower])) return true;
                        
                        return false;
                    });
                    
                    console.log('Day:', day, 'Working Days:', actualWorkingDays, 'Is Working:', isWorkingDay);
                    
                    return `
                        <div class="mini-day ${isWorkingDay ? 'working-day' : 'off-day'}" title="${isWorkingDay ? 'Active Working Day' : 'Non-Working Day'}">
                            <div class="mini-day-name">${day}</div>
                            <div class="mini-day-status">
                                ${isWorkingDay ? 
                                    '<i class="fas fa-check-circle text-success"></i>' : 
                                    '<i class="fas fa-times-circle text-danger"></i>'
                                }
                            </div>
                            <div class="mini-day-label">
                                ${isWorkingDay ? 'Active' : 'Off'}
                            </div>
                        </div>
                    `;
                }).join('')}
            </div>
            <div class="mini-calendar-legend">
                <div class="legend-item">
                    <div class="legend-color working-legend"></div>
                    <span class="legend-text">Active Working Days</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color off-legend"></div>
                    <span class="legend-text">Non-Working Days</span>
                </div>
            </div>
            <div class="mini-calendar-summary">
                <div class="summary-item">
                    <span class="summary-label">Working Days:</span>
                    <span class="summary-value">${actualWorkingDays.length}/7</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Coverage:</span>
                    <span class="summary-value">${getWorkingDaysPercentage(actualWorkingDays)}%</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Hours:</span>
                    <span class="summary-value">${actualHours}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Period:</span>
                    <span class="summary-value">${actualPeriod}</span>
                </div>
            </div>
        </div>
    `;
}

// Tab switching function
window.switchTab = function(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-tab').classList.add('active');
    
    // Add active class to clicked button
    event.target.classList.add('active');
};

// View roster details function
window.viewRosterDetails = function(rosterId) {
    console.log('Viewing roster details for ID:', rosterId);
    
    // Find the roster row in the table
    const rosterRows = document.querySelectorAll('#rosterDataTable tbody tr');
    let rosterData = null;
    
    rosterRows.forEach(row => {
        const viewButton = row.querySelector(`button[onclick*="viewRosterDetails(${rosterId})"]`);
        if (viewButton) {
            const cells = row.cells;
            const createdDate = cells[8]?.querySelector('small')?.textContent || 'N/A';
            const teamCode = cells[1]?.querySelector('small')?.textContent || '';
            const maxHours = cells[6]?.querySelector('small')?.textContent || '';
            
            rosterData = {
                id: rosterId,
                name: cells[0]?.querySelector('h6')?.textContent || 'Unknown',
                team: cells[1]?.querySelector('.badge')?.textContent || 'N/A',
                teamCode: teamCode,
                period: cells[2]?.querySelector('.badge')?.textContent || 'N/A',
                startDate: cells[3]?.textContent?.trim() || 'N/A',
                endDate: cells[4]?.textContent?.trim() || 'N/A',
                workingDays: Array.from(cells[5]?.querySelectorAll('.badge') || []).map(badge => badge.textContent),
                workingHours: cells[6]?.querySelector('.fw-bold')?.textContent || 'N/A',
                maxHours: maxHours,
                status: cells[7]?.querySelector('.badge')?.textContent || 'N/A',
                createdBy: cells[8]?.querySelector('.fw-medium')?.textContent || 'Unknown',
                createdDate: createdDate,
                // Calculate additional details
                duration: calculateDuration(cells[3]?.textContent?.trim(), cells[4]?.textContent?.trim()),
                totalWorkingDays: Array.from(cells[5]?.querySelectorAll('.badge') || []).length,
                workingDaysPerWeek: calculateWorkingDaysPerWeek(Array.from(cells[5]?.querySelectorAll('.badge') || []).map(badge => badge.textContent)),
                isActive: (cells[7]?.querySelector('.badge')?.textContent || '').toLowerCase() === 'active',
                isDraft: (cells[7]?.querySelector('.badge')?.textContent || '').toLowerCase() === 'draft'
            };
        }
    });
    
    if (rosterData) {
        const statusClass = rosterData.status.toLowerCase() === 'active' ? 'success' : 
                           rosterData.status.toLowerCase() === 'draft' ? 'warning' : 'danger';
        
        Swal.fire({
            title: `
                <div class="roster-modal-header">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Roster Details
                </div>
            `,
            html: `
                <div class="roster-details-modal">
                    <!-- Header Section -->
                    <div class="roster-header-section">
                        <div class="roster-title">
                            <h4 class="roster-name">${rosterData.name}</h4>
                            <span class="roster-id">ID: ${rosterData.id}</span>
                        </div>
                        <div class="roster-status">
                            <span class="status-badge status-${statusClass}">
                                <i class="fas fa-circle me-1"></i>${rosterData.status}
                            </span>
                        </div>
                    </div>

                    <!-- Compact Info Bar -->
                    <div class="info-bar">
                        <div class="info-item-compact">
                            <i class="fas fa-users text-primary"></i>
                            <span class="info-label">Team:</span>
                            <span class="info-value">${rosterData.team}</span>
                        </div>
                        <div class="info-item-compact">
                            <i class="fas fa-clock text-success"></i>
                            <span class="info-label">Hours:</span>
                            <span class="info-value">${rosterData.workingHours}</span>
                        </div>
                        <div class="info-item-compact">
                            <i class="fas fa-calendar text-info"></i>
                            <span class="info-label">Duration:</span>
                            <span class="info-value">${rosterData.duration}</span>
                        </div>
                        <div class="info-item-compact">
                            <i class="fas fa-chart-line text-warning"></i>
                            <span class="info-label">Coverage:</span>
                            <span class="info-value">${getWorkingDaysPercentage(rosterData.workingDays)}%</span>
                        </div>
                    </div>

                    <!-- Main Content with Tabs -->
                    <div class="roster-content">
                        <div class="roster-tabs">
                            <button class="tab-button active" onclick="switchTab('overview')">
                                <i class="fas fa-info-circle me-1"></i>Overview
                            </button>
                            <button class="tab-button" onclick="switchTab('team')">
                                <i class="fas fa-users me-1"></i>Team Members
                            </button>
                            <button class="tab-button" onclick="switchTab('calendar')">
                                <i class="fas fa-calendar-alt me-1"></i>Calendar View
                            </button>
                        </div>

                        <!-- Overview Tab -->
                        <div id="overview-tab" class="tab-content active">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="info-card-compact">
                                        <h6><i class="fas fa-calendar text-info me-2"></i>Schedule</h6>
                                        <div class="info-grid">
                                            <div class="info-item-small">
                                                <label>Period</label>
                                                <span class="period-badge">${rosterData.period}</span>
                                            </div>
                                            <div class="info-item-small">
                                                <label>Working Days</label>
                                                <div class="working-days-compact">
                                                    ${rosterData.workingDays.map(day => `<span class="day-badge-small">${day}</span>`).join('')}
                                                </div>
                                            </div>
                                            <div class="info-item-small">
                                                <label>Max Hours</label>
                                                <span class="hours-display-small">${rosterData.maxHours || 'N/A'}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-card-compact">
                                        <h6><i class="fas fa-user text-warning me-2"></i>Details</h6>
                                        <div class="info-grid">
                                            <div class="info-item-small">
                                                <label>Created By</label>
                                                <span class="creator-name-small">${rosterData.createdBy}</span>
                                            </div>
                                            <div class="info-item-small">
                                                <label>Created Date</label>
                                                <span class="created-date-small">${rosterData.createdDate}</span>
                                            </div>
                                            <div class="info-item-small">
                                                <label>Status</label>
                                                <span class="status-info-small">
                                                    <span class="status-indicator status-${statusClass}"></span>
                                                    ${rosterData.status}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Team Members Tab -->
                        <div id="team-tab" class="tab-content">
                            <div class="team-members-section">
                                <h6><i class="fas fa-users text-primary me-2"></i>Team Members</h6>
                                <div class="team-members-grid">
                                    ${generateTeamMembers(rosterData.team)}
                                </div>
                            </div>
                        </div>

                        <!-- Calendar View Tab -->
                        <div id="calendar-tab" class="tab-content">
                            <div class="mini-calendar-section">
                                <h6><i class="fas fa-calendar-alt text-info me-2"></i>Working Schedule</h6>
                                <div class="mini-calendar">
                                    ${generateMiniCalendar(rosterData)}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `,
            width: '800px',
            showConfirmButton: true,
            confirmButtonText: '<i class="fas fa-times me-1"></i>Close',
            confirmButtonColor: '#6c757d',
            customClass: {
                popup: 'roster-details-popup',
                htmlContainer: 'roster-details-content'
            }
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Roster details not found!',
            confirmButtonColor: '#3085d6'
        });
    }
}

// Edit roster function - Show modal directly
function editRoster(rosterId) {
    console.log('Edit function called with ID:', rosterId);
    
    // Get roster data from the table row
    const row = document.querySelector(`button[onclick="editRoster(${rosterId})"]`).closest('tr');
    if (!row) {
        console.error('Could not find roster row for ID:', rosterId);
        alert('Could not find roster data');
        return;
    }
    
    // Extract data from table cells
    const cells = row.querySelectorAll('td');
    if (cells.length < 10) {
        console.error('Invalid table row structure. Expected 10 cells, got:', cells.length);
        alert('Invalid table data');
        return;
    }
    
    // Extract data from specific HTML elements in each cell
    const rosterName = cells[0].querySelector('h6')?.textContent?.trim() || '';
    const teamName = cells[1].querySelector('.badge')?.textContent?.trim() || '';
    const period = cells[2].querySelector('.badge')?.textContent?.trim() || '';
    const startDate = cells[3].querySelector('.fw-medium')?.textContent?.trim() || '';
    const endDate = cells[4].querySelector('.fw-medium')?.textContent?.trim() || '';
    
    // Extract working days from badges
    const workingDaysBadges = cells[5].querySelectorAll('.badge');
    const workingDays = Array.from(workingDaysBadges).map(badge => badge.textContent.trim()).join(', ');
    
    // Extract working hours and max hours
    const workingHoursText = cells[6].querySelector('.fw-bold')?.textContent?.trim() || '';
    const maxHoursText = cells[6].querySelector('small')?.textContent?.trim() || '';
    const maxHours = maxHoursText.replace('h max', '').trim();
    
    // Extract status
    const status = cells[7].querySelector('.badge')?.textContent?.trim() || '';
    
    // Extract notes from data attribute
    const notes = row.getAttribute('data-roster-notes') || '';
    console.log('Raw notes from data attribute:', row.getAttribute('data-roster-notes'));
    console.log('Processed notes:', notes);
    
    // Test: If notes are empty, use a test value to verify the field works
    const testNotes = notes || 'Test notes - if you see this, the field population works!';
    console.log('Test notes:', testNotes);
    
    // Combine dates for the old format
    const dates = startDate && endDate ? `${startDate} - ${endDate}` : '';
    
    console.log('Extracted data:', { 
        rosterName, 
        teamName, 
        period, 
        startDate, 
        endDate, 
        dates,
        workingDays, 
        workingHours: workingHoursText,
        maxHours,
        status,
        notes
    });
    
    // Show the edit modal first - use jQuery approach for better compatibility
    $('#editRosterModal').modal('show');
    
    // Wait for modal to be fully shown, then populate the form
    setTimeout(() => {
        console.log('About to populate form with notes:', testNotes);
        populateEditFormFromTable(rosterId, {
            rosterName,
            teamName,
            period,
            dates,
            startDate,
            endDate,
            workingDays,
            workingHours: maxHours, // Use max hours for the working hours field
            status,
            notes: testNotes
        });
        
        // Double-check notes after population
        setTimeout(() => {
            const notesField = document.getElementById('editRosterNotes');
            console.log('Notes field value after population:', notesField?.value);
        }, 100);
    }, 300); // 300ms delay to ensure modal is fully loaded
}

// Make it globally accessible
window.editRoster = editRoster;

// Populate edit form with roster data (AJAX version)
function populateEditForm(roster) {
    console.log('Populating edit form with:', roster);
    
    // Basic fields
    document.getElementById('editRosterId').value = roster.id;
    document.getElementById('editRosterName').value = roster.roster_name || '';
    document.getElementById('editRosterTeamId').value = roster.team_id || '';
    document.getElementById('editRosterPeriod').value = roster.roster_period || '';
    document.getElementById('editRosterStatus').value = roster.roster_status || '';
    document.getElementById('editStartDate').value = roster.start_date ? roster.start_date.split('T')[0] : '';
    document.getElementById('editEndDate').value = roster.end_date ? roster.end_date.split('T')[0] : '';
    document.getElementById('editWorkStartTime').value = roster.work_start_time || '';
    document.getElementById('editWorkEndTime').value = roster.work_end_time || '';
    document.getElementById('editMaxWorkingHours').value = roster.max_working_hours || '';
    document.getElementById('editLeaveType').value = roster.leave_type || '';
    document.getElementById('editLeaveReason').value = roster.leave_reason || '';
    document.getElementById('editRosterNotes').value = roster.roster_notes || '';
    
    // Working days checkboxes
    const workingDays = Array.isArray(roster.working_days) ? roster.working_days : 
                       (roster.working_days ? JSON.parse(roster.working_days) : []);
    
    // Clear all checkboxes first
    document.querySelectorAll('input[name="working_days[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // Check the appropriate days
    workingDays.forEach(day => {
        const checkbox = document.getElementById(`edit${day.charAt(0).toUpperCase() + day.slice(1)}`);
        if (checkbox) {
            checkbox.checked = true;
        }
    });
}

// Populate edit form with Laravel data (server-side version)
function populateEditFormWithLaravelData(roster) {
    console.log('Populating edit form with Laravel data:', roster);
    
    // Basic fields
    document.getElementById('editRosterId').value = roster.id;
    document.getElementById('editRosterName').value = roster.roster_name || '';
    document.getElementById('editRosterTeamId').value = roster.team_id || '';
    document.getElementById('editRosterPeriod').value = roster.roster_period || '';
    document.getElementById('editRosterStatus').value = roster.roster_status || '';
    document.getElementById('editStartDate').value = roster.start_date ? roster.start_date.split('T')[0] : '';
    document.getElementById('editEndDate').value = roster.end_date ? roster.end_date.split('T')[0] : '';
    document.getElementById('editWorkStartTime').value = roster.work_start_time || '';
    document.getElementById('editWorkEndTime').value = roster.work_end_time || '';
    document.getElementById('editMaxWorkingHours').value = roster.max_working_hours || '';
    document.getElementById('editLeaveType').value = roster.leave_type || '';
    document.getElementById('editLeaveReason').value = roster.leave_reason || '';
    document.getElementById('editRosterNotes').value = roster.roster_notes || '';
    
    // Working days checkboxes
    const workingDays = Array.isArray(roster.working_days) ? roster.working_days : 
                       (roster.working_days ? JSON.parse(roster.working_days) : []);
    
    // Clear all checkboxes first
    document.querySelectorAll('input[name="working_days[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // Check the appropriate days
    workingDays.forEach(day => {
        const checkbox = document.getElementById(`edit${day.charAt(0).toUpperCase() + day.slice(1)}`);
        if (checkbox) {
            checkbox.checked = true;
        }
    });
}

// Populate edit form from table data
function populateEditFormFromTable(rosterId, tableData) {
    console.log('Populating edit form from table data:', tableData);
    
    // Check if the edit modal exists
    const editModal = document.getElementById('editRosterModal');
    if (!editModal) {
        console.error('Edit modal not found!');
        alert('Edit modal not found. Please refresh the page and try again.');
        return;
    }
    
    // Check if the form elements exist
    const rosterIdField = document.getElementById('editRosterId');
    if (!rosterIdField) {
        console.error('editRosterId field not found!');
        alert('Edit form not properly loaded. Please refresh the page and try again.');
        return;
    }
    
    // Set the roster ID
    rosterIdField.value = rosterId;
    console.log('Set roster ID:', rosterId);
    
    // Set basic fields from table data
    const rosterNameField = document.getElementById('editRosterName');
    if (rosterNameField) {
        rosterNameField.value = tableData.rosterName || '';
        console.log('Set roster name:', tableData.rosterName);
    }
    
    // Set team selection - try to find team by name
    const teamField = document.getElementById('editRosterTeamId');
    if (teamField) {
        // Try to find the team option that matches the team name
        const teamOptions = teamField.querySelectorAll('option');
        let teamFound = false;
        teamOptions.forEach(option => {
            if (option.textContent.includes(tableData.teamName)) {
                teamField.value = option.value;
                teamFound = true;
                console.log('Set team:', tableData.teamName, 'to value:', option.value);
            }
        });
        if (!teamFound) {
            console.warn('Team not found:', tableData.teamName);
        }
    }
    
    // Set period
    const periodMap = {
        'Daily': 'daily',
        'Weekly': 'weekly', 
        'Monthly': 'monthly'
    };
    const periodField = document.getElementById('editRosterPeriod');
    if (periodField) {
        periodField.value = periodMap[tableData.period] || '';
        console.log('Set period:', tableData.period, 'to value:', periodMap[tableData.period]);
    }
    
    // Set status
    const statusMap = {
        'Active': 'active',
        'Draft': 'draft',
        'Inactive': 'inactive'
    };
    const statusField = document.getElementById('editRosterStatus');
    if (statusField) {
        statusField.value = statusMap[tableData.status] || '';
        console.log('Set status:', tableData.status, 'to value:', statusMap[tableData.status]);
    }
    
    // Parse dates - handle both separate dates and combined date range
    console.log('Parsing dates:', { startDate: tableData.startDate, endDate: tableData.endDate, dates: tableData.dates });
    
    let startDate, endDate;
    
    // If we have separate start and end dates, use them directly
    if (tableData.startDate && tableData.endDate) {
        try {
            // Format: "Sep 22, 2025" -> "Sep 22, 2025"
            startDate = new Date(tableData.startDate);
            endDate = new Date(tableData.endDate);
            console.log('Using separate dates:', { startDate, endDate });
        } catch (e) {
            console.warn('Separate date parsing failed:', e);
        }
    } else if (tableData.dates) {
        // Fallback to combined date range parsing
        const dateRange = tableData.dates;
        
        if (dateRange.includes(' - ')) {
            // Format: "Sep 22 - Oct 22, 2025"
            const dateParts = dateRange.split(' - ');
            if (dateParts.length === 2) {
                try {
                    const startStr = dateParts[0].includes('2025') ? dateParts[0] : dateParts[0] + ', 2025';
                    const endStr = dateParts[1].includes('2025') ? dateParts[1] : dateParts[1] + ', 2025';
                    startDate = new Date(startStr);
                    endDate = new Date(endStr);
                    console.log('Using combined date range:', { startDate, endDate });
                } catch (e) {
                    console.warn('Combined date parsing failed:', e);
                }
            }
        } else if (dateRange.includes(' to ')) {
            // Format: "2025-09-22 to 2025-10-22"
            const dateParts = dateRange.split(' to ');
            if (dateParts.length === 2) {
                startDate = new Date(dateParts[0]);
                endDate = new Date(dateParts[1]);
                console.log('Using to format:', { startDate, endDate });
            }
        }
    }
    
    const startDateField = document.getElementById('editStartDate');
    const endDateField = document.getElementById('editEndDate');
    
    if (startDateField && startDate && !isNaN(startDate.getTime())) {
        startDateField.value = startDate.toISOString().split('T')[0];
        console.log('Set start date:', startDateField.value);
    }
    if (endDateField && endDate && !isNaN(endDate.getTime())) {
        endDateField.value = endDate.toISOString().split('T')[0];
        console.log('Set end date:', endDateField.value);
    }
    
    // Parse working days - improved matching
    const workingDaysText = tableData.workingDays || '';
    console.log('Parsing working days:', workingDaysText);
    
    const dayMap = {
        'Mon': 'monday',
        'Monday': 'monday',
        'Tue': 'tuesday',
        'Tuesday': 'tuesday',
        'Wed': 'wednesday',
        'Wednesday': 'wednesday',
        'Thu': 'thursday',
        'Thursday': 'thursday',
        'Fri': 'friday',
        'Friday': 'friday',
        'Sat': 'saturday',
        'Saturday': 'saturday',
        'Sun': 'sunday',
        'Sunday': 'sunday'
    };
    
    // Clear all working day checkboxes first
    document.querySelectorAll('input[name="working_days[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // Check the appropriate working days
    Object.keys(dayMap).forEach(dayKey => {
        if (workingDaysText.toLowerCase().includes(dayKey.toLowerCase())) {
            const dayName = dayMap[dayKey];
            const checkbox = document.getElementById(`editWorking${dayName.charAt(0).toUpperCase() + dayName.slice(1)}`);
            if (checkbox) {
                checkbox.checked = true;
                console.log('Checked working day:', dayName);
            }
        }
    });
    
    // Clear all leave day checkboxes first
    document.querySelectorAll('input[name="leave_days[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // Parse working hours - safer parsing
    const workingHours = tableData.workingHours || '';
    console.log('Parsing working hours:', workingHours);
    
    const maxHoursField = document.getElementById('editMaxWorkingHours');
    if (maxHoursField && workingHours) {
        const hoursMatch = workingHours.match(/(\d+)/);
        if (hoursMatch) {
            maxHoursField.value = hoursMatch[1];
            console.log('Set max hours:', hoursMatch[1]);
        } else {
            // Default to 40 if no number found
            maxHoursField.value = '40';
        }
    }
    
    // Set default times
    const startTimeField = document.getElementById('editWorkStartTime');
    const endTimeField = document.getElementById('editWorkEndTime');
    if (startTimeField) {
        startTimeField.value = '08:00';
        console.log('Set default start time: 08:00');
    }
    if (endTimeField) {
        endTimeField.value = '17:00';
        console.log('Set default end time: 17:00');
    }
    
    // Handle leave days (if any are stored in the data)
    // Note: Leave days are not currently stored in the table, so we'll leave them unchecked
    // This can be extended if leave days are added to the table display
    
    // Set notes field
    const notesField = document.getElementById('editRosterNotes');
    console.log('Notes field found:', notesField);
    console.log('Notes data:', tableData.notes);
    if (notesField) {
        notesField.value = tableData.notes || '';
        console.log('Set notes value:', notesField.value);
    } else {
        console.error('Notes field not found!');
    }
    
    // Clear other fields
    const leaveTypeField = document.getElementById('editLeaveType');
    const leaveReasonField = document.getElementById('editLeaveReason');
    if (leaveTypeField) leaveTypeField.value = '';
    if (leaveReasonField) leaveReasonField.value = '';
    
    console.log('Edit form population completed');
    
    // Update the calendar preview for edit modal
    updateEditCalendarPreview();
}

// Initialize edit calendar preview
function initializeEditCalendarPreview() {
    console.log('Initializing edit calendar preview...');
    
    // Add event listeners to all edit form inputs
    const formInputs = [
        'editRosterTeamId', 'editRosterName', 'editRosterPeriod', 'editStartDate', 'editEndDate',
        'editWorkStartTime', 'editWorkEndTime', 'editMaxWorkingHours', 'editLeaveType', 'editLeaveReason',
        'editRosterStatus', 'editRosterNotes'
    ];
    
    // Add listeners to text inputs, selects, and date inputs
    formInputs.forEach(inputId => {
        const element = document.getElementById(inputId);
        if (element) {
            element.addEventListener('input', updateEditCalendarPreview);
            element.addEventListener('change', updateEditCalendarPreview);
        }
    });
    
    // Add listeners to working days checkboxes
    const workingDays = ['editWorkingMonday', 'editWorkingTuesday', 'editWorkingWednesday', 'editWorkingThursday', 'editWorkingFriday', 'editWorkingSaturday', 'editWorkingSunday'];
    workingDays.forEach(dayId => {
        const element = document.getElementById(dayId);
        if (element) {
            element.addEventListener('change', updateEditCalendarPreview);
        }
    });
    
    // Add listeners to leave days checkboxes
    const leaveDays = ['editLeaveMonday', 'editLeaveTuesday', 'editLeaveWednesday', 'editLeaveThursday', 'editLeaveFriday', 'editLeaveSaturday', 'editLeaveSunday'];
    leaveDays.forEach(dayId => {
        const element = document.getElementById(dayId);
        if (element) {
            element.addEventListener('change', updateEditCalendarPreview);
        }
    });
}

// Update edit calendar preview
function updateEditCalendarPreview() {
    console.log('Updating edit calendar preview...');
    
    const calendarContainer = document.getElementById('editRosterPreviewCalendar');
    if (!calendarContainer) return;
    
    // Get form values
    const rosterName = document.getElementById('editRosterName')?.value || 'Untitled Roster';
    const startDate = document.getElementById('editStartDate')?.value;
    const endDate = document.getElementById('editEndDate')?.value;
    const workStartTime = document.getElementById('editWorkStartTime')?.value || '08:00';
    const workEndTime = document.getElementById('editWorkEndTime')?.value || '17:00';
    const rosterPeriod = document.getElementById('editRosterPeriod')?.value;
    const maxHours = document.getElementById('editMaxWorkingHours')?.value || '40';
    const leaveType = document.getElementById('editLeaveType')?.value;
    const rosterStatus = document.getElementById('editRosterStatus')?.value || 'draft';
    
    // Get working days
    const workingDays = [];
    const workingDayIds = ['editWorkingMonday', 'editWorkingTuesday', 'editWorkingWednesday', 'editWorkingThursday', 'editWorkingFriday', 'editWorkingSaturday', 'editWorkingSunday'];
    workingDayIds.forEach(dayId => {
        const checkbox = document.getElementById(dayId);
        if (checkbox && checkbox.checked) {
            workingDays.push(checkbox.value);
        }
    });
    
    // Get leave days
    const leaveDays = [];
    const leaveDayIds = ['editLeaveMonday', 'editLeaveTuesday', 'editLeaveWednesday', 'editLeaveThursday', 'editLeaveFriday', 'editLeaveSaturday', 'editLeaveSunday'];
    leaveDayIds.forEach(dayId => {
        const checkbox = document.getElementById(dayId);
        if (checkbox && checkbox.checked) {
            leaveDays.push(checkbox.value);
        }
    });
    
    // Generate calendar HTML
    let calendarHTML = generateCalendarHTML({
        rosterName,
        startDate,
        endDate,
        workStartTime,
        workEndTime,
        rosterPeriod,
        maxHours,
        leaveType,
        rosterStatus,
        workingDays,
        leaveDays
    });
    
    calendarContainer.innerHTML = calendarHTML;
}

// Initialize stats cards with animations
function initializeStatsCards() {
    console.log('Initializing stats cards...');
    
    // Animate cards on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    
                    // Animate the progress bars
                    const progressBar = entry.target.querySelector('.progress-bar');
                    if (progressBar) {
                        const width = progressBar.style.width;
                        progressBar.style.width = '0%';
                        setTimeout(() => {
                            progressBar.style.width = width;
                        }, 200);
                    }
                    
                    // Animate the card value with counting effect
                    const cardValue = entry.target.querySelector('.card-value');
                    if (cardValue) {
                        const finalValue = parseInt(cardValue.textContent);
                        if (!isNaN(finalValue)) {
                            animateCounter(cardValue, 0, finalValue, 1000);
                        }
                    }
                }, index * 100); // Stagger animation
            }
        });
    }, observerOptions);
    
    // Observe all dashboard cards
    document.querySelectorAll('.dashboard-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
        observer.observe(card);
    });
}

// Animate counter for stats values
function animateCounter(element, start, end, duration) {
    const startTime = performance.now();
    const isNumber = !isNaN(end);
    
    function updateCounter(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        if (isNumber) {
            const current = Math.floor(start + (end - start) * progress);
            element.textContent = current;
        }
        
        if (progress < 1) {
            requestAnimationFrame(updateCounter);
        }
    }
    
    requestAnimationFrame(updateCounter);
}

// Update roster function
window.updateRoster = function() {
    const form = document.getElementById('editRosterForm');
    const formData = new FormData(form);
    const rosterId = document.getElementById('editRosterId').value;
    
    console.log('Updating roster ID:', rosterId);
    console.log('Form data:', Object.fromEntries(formData));
    
    // Show loading
    Swal.fire({
        title: 'Updating...',
        text: 'Please wait while we update the roster.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Submit update request
    fetch(`/company/MasterTracker/team-roaster/${rosterId}/update`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Close modal using jQuery (consistent with how we show it)
            $('#editRosterModal').modal('hide');
            
            // Show success message
            Swal.fire({
                title: 'Updated!',
                text: 'The roster has been updated successfully.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                // Reload the page to refresh the data
                location.reload();
            });
        } else {
            Swal.fire({
                title: 'Error!',
                text: data.message || 'Failed to update the roster.',
                icon: 'error'
            });
        }
    })
    .catch(error => {
        console.error('Error updating roster:', error);
        Swal.fire({
            title: 'Error!',
            text: 'An error occurred while updating the roster.',
            icon: 'error'
        });
    });
};

// Delete roster function with soft delete (moved to global scope)
function deleteRoster(rosterId) {
    console.log('Delete function called with ID:', rosterId);
    console.log('SweetAlert available:', typeof Swal);
    
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert is not loaded!');
        alert('Error: SweetAlert is not loaded. Please refresh the page.');
        return;
    }
    
    Swal.fire({
        title: 'Delete Roster',
        text: 'Are you sure you want to delete this roster? This will soft delete the roster and it can be restored later.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        console.log('SweetAlert result:', result);
        if (result.isConfirmed) {
            console.log('User confirmed deletion');
            // Store current view state before reload
            const currentView = document.querySelector('input[name="viewType"]:checked')?.value || 'table';
            localStorage.setItem('rosterViewState', currentView);
            console.log('View state saved:', currentView);
            
            // Create a hidden form and submit it
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/company/MasterTracker/team-roaster/${rosterId}/delete`;
            form.style.display = 'none';
            
            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            console.log('CSRF Token:', csrfToken);
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
            
            console.log('Form created, submitting to:', form.action);
            
            // Submit the form and handle response
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(form)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Show success message with SweetAlert
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'Team roster deleted successfully!',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Reload the page to refresh the data
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Failed to delete the roster.',
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while deleting the roster.',
                    icon: 'error'
                });
            });
        } else {
            console.log('User cancelled deletion');
        }
    }).catch((error) => {
        console.error('SweetAlert error:', error);
    });
}

// Make delete function globally accessible
window.deleteRoster = deleteRoster;

// Initialize view toggle functionality with state persistence
function initializeViewToggle() {
    // Restore view state from localStorage
    const savedViewState = localStorage.getItem('rosterViewState');
    if (savedViewState) {
        // Set the radio button based on saved state
        const calendarRadio = document.getElementById('calendarView');
        const tableRadio = document.getElementById('tableView');
        
        if (savedViewState === 'calendar' && calendarRadio) {
            calendarRadio.checked = true;
            toggleView('calendar');
        } else if (savedViewState === 'table' && tableRadio) {
            tableRadio.checked = true;
            toggleView('table');
        }
    } else {
        // Default to table view if no saved state
        const tableRadio = document.getElementById('tableView');
        if (tableRadio) {
            tableRadio.checked = true;
            toggleView('table');
        }
    }

    // Add event listeners to view toggle buttons
    document.getElementById('calendarView').addEventListener('change', function() {
        if (this.checked) {
            localStorage.setItem('rosterViewState', 'calendar');
            toggleView('calendar');
        }
    });
    
    document.getElementById('tableView').addEventListener('change', function() {
        if (this.checked) {
            localStorage.setItem('rosterViewState', 'table');
            toggleView('table');
        }
    });
}

// Clear view state function (useful for testing or manual reset)
window.clearRosterViewState = function() {
    localStorage.removeItem('rosterViewState');
    location.reload();
}

// Preview Roster Function - moved here for early availability
window.previewRoster = function() {
    const formData = {
        team_id: $('#rosterTeamId').val(),
        roster_name: $('#rosterName').val(),
        roster_period: $('#rosterPeriod').val(),
        start_date: $('#rosterStartDate').val(),
        end_date: $('#rosterEndDate').val(),
        work_start_time: $('#workStartTime').val(),
        work_end_time: $('#workEndTime').val(),
        working_days: $('input[name="working_days[]"]:checked').map(function() { return this.value; }).get(),
        leave_days: $('input[name="leave_days[]"]:checked').map(function() { return this.value; }).get(),
        leave_type: $('#leaveType').val(),
        leave_reason: $('#leaveReason').val(),
        max_working_hours: $('#maxWorkingHours').val(),
        roster_status: $('#rosterStatus').val(),
        roster_notes: $('#rosterNotes').val()
    };
    
    if (!formData.team_id || !formData.roster_period || !formData.start_date || !formData.end_date) {
        Swal.fire({
            icon: 'warning',
            title: 'Incomplete Data',
            text: 'Please fill in all required fields before previewing.',
            showConfirmButton: false,
            timer: 2000
        });
        return;
    }
    
    showRosterPreviewModal(formData);
};

// Preview Modal Functions
window.showRosterPreviewModal = function(data) {
    // Get team name
    const teamSelect = document.getElementById('rosterTeamId');
    const teamName = teamSelect.options[teamSelect.selectedIndex]?.text || 'Selected Team';
    
    // Generate comprehensive preview HTML
    const previewHTML = generateComprehensivePreview(data, teamName);
    
    // Show in SweetAlert modal
    Swal.fire({
        title: 'Roster Preview',
        html: previewHTML,
        width: '90%',
        maxWidth: '1000px',
        showConfirmButton: true,
        confirmButtonText: 'Close Preview',
        confirmButtonColor: '#3085d6',
        showCancelButton: false,
        allowOutsideClick: true,
        allowEscapeKey: true,
        customClass: {
            popup: 'roster-preview-popup',
            htmlContainer: 'roster-preview-content'
        }
    });
};

window.generateComprehensivePreview = function(data, teamName) {
    const startDate = new Date(data.start_date);
    const endDate = new Date(data.end_date);
    const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    
    // Calculate total days
    const totalDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
    
    // Generate calendar HTML
    let calendarHTML = generateDetailedCalendar(startDate, endDate, data);
    
    // Generate summary statistics
    const workingHoursPerDay = calculateWorkingHours(data.work_start_time, data.work_end_time);
    const totalWorkingHours = data.working_days.length * workingHoursPerDay;
    
    return `
        <div class="roster-preview-modal">
            <!-- Header Section -->
            <div class="preview-header mb-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="text-primary mb-1">
                            <i class="fas fa-calendar-check me-2"></i>${data.roster_name || 'Untitled Roster'}
                        </h4>
                        <p class="text-muted mb-0">${teamName} • ${data.roster_period.charAt(0).toUpperCase() + data.roster_period.slice(1)} Roster</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <span class="badge bg-${getStatusColor(data.roster_status)} fs-6">${data.roster_status.charAt(0).toUpperCase() + data.roster_status.slice(1)}</span>
                    </div>
                </div>
            </div>
            
            <!-- Key Information -->
            <div class="preview-info mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="info-card">
                            <div class="info-icon bg-primary">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="info-content">
                                <h6>Duration</h6>
                                <p class="mb-0">${startDate.toLocaleDateString()} - ${endDate.toLocaleDateString()}</p>
                                <small class="text-muted">${totalDays} days</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-card">
                            <div class="info-icon bg-success">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="info-content">
                                <h6>Working Hours</h6>
                                <p class="mb-0">${data.work_start_time} - ${data.work_end_time}</p>
                                <small class="text-muted">${workingHoursPerDay}h per day</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-card">
                            <div class="info-icon bg-info">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="info-content">
                                <h6>Working Days</h6>
                                <p class="mb-0">${data.working_days.length} days</p>
                                <small class="text-muted">${data.working_days.join(', ')}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-card">
                            <div class="info-icon bg-warning">
                                <i class="fas fa-calendar-times"></i>
                            </div>
                            <div class="info-content">
                                <h6>Leave Days</h6>
                                <p class="mb-0">${data.leave_days.length} days</p>
                                <small class="text-muted">${data.leave_days.length > 0 ? data.leave_days.join(', ') : 'None'}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Calendar View -->
            <div class="preview-calendar mb-4">
                <h5 class="mb-3">
                    <i class="fas fa-calendar-week me-2"></i>Calendar View
                </h5>
                ${calendarHTML}
            </div>
            
            <!-- Summary Statistics -->
            <div class="preview-summary">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="summary-card bg-light p-3 rounded">
                            <div class="d-flex align-items-center">
                                <div class="summary-icon bg-success text-white rounded-circle me-3">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Total Working Hours</h6>
                                    <h4 class="text-success mb-0">${totalWorkingHours}h</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="summary-card bg-light p-3 rounded">
                            <div class="d-flex align-items-center">
                                <div class="summary-icon bg-info text-white rounded-circle me-3">
                                    <i class="fas fa-percentage"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Work Ratio</h6>
                                    <h4 class="text-info mb-0">${Math.round((data.working_days.length / 7) * 100)}%</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="summary-card bg-light p-3 rounded">
                            <div class="d-flex align-items-center">
                                <div class="summary-icon bg-warning text-white rounded-circle me-3">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Max Hours Limit</h6>
                                    <h4 class="text-warning mb-0">${data.max_working_hours}h</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Notes -->
            ${data.roster_notes ? `
                <div class="preview-notes mt-4">
                    <h6><i class="fas fa-sticky-note me-2"></i>Notes</h6>
                    <div class="notes-content p-3 bg-light rounded">
                        <p class="mb-0">${data.roster_notes}</p>
                    </div>
                </div>
            ` : ''}
            
            ${data.leave_type && data.leave_days.length > 0 ? `
                <div class="preview-leave mt-4">
                    <h6><i class="fas fa-calendar-times me-2"></i>Leave Information</h6>
                    <div class="leave-content p-3 bg-warning bg-opacity-10 rounded">
                        <p class="mb-1"><strong>Leave Type:</strong> ${data.leave_type.charAt(0).toUpperCase() + data.leave_type.slice(1)}</p>
                        ${data.leave_reason ? `<p class="mb-0"><strong>Reason:</strong> ${data.leave_reason}</p>` : ''}
                    </div>
                </div>
            ` : ''}
        </div>
    `;
};

window.generateDetailedCalendar = function(startDate, endDate, data) {
    const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    let calendarHTML = '<div class="detailed-calendar"><div class="row g-2">';
    
    const currentDate = new Date(startDate);
    while (currentDate <= endDate) {
        const dayName = daysOfWeek[currentDate.getDay()].toLowerCase();
        const isWorkingDay = data.working_days.includes(dayName);
        const isLeaveDay = data.leave_days.includes(dayName);
        
        let dayClass = 'calendar-day-detailed';
        let dayStatus = '';
        let dayIcon = '';
        let dayColor = '';
        
        if (isLeaveDay) {
            dayClass += ' leave-day-detailed';
            dayStatus = 'Leave';
            dayIcon = 'fas fa-calendar-times';
            dayColor = 'warning';
        } else if (isWorkingDay) {
            dayClass += ' working-day-detailed';
            dayStatus = 'Working';
            dayIcon = 'fas fa-briefcase';
            dayColor = 'success';
        } else {
            dayClass += ' off-day-detailed';
            dayStatus = 'Off';
            dayIcon = 'fas fa-home';
            dayColor = 'secondary';
        }
        
        calendarHTML += `
            <div class="col-md-3 col-sm-6">
                <div class="${dayClass} p-3 border rounded text-center">
                    <div class="day-icon mb-2">
                        <i class="${dayIcon} text-${dayColor} fa-lg"></i>
                    </div>
                    <div class="day-name fw-bold mb-1">${daysOfWeek[currentDate.getDay()].substring(0, 3)}</div>
                    <div class="day-date text-muted mb-1">${currentDate.getDate()}</div>
                    <div class="day-status text-${dayColor} fw-bold mb-1">${dayStatus}</div>
                    ${isWorkingDay ? `<div class="day-time small text-success">${data.work_start_time} - ${data.work_end_time}</div>` : ''}
                </div>
            </div>
        `;
        
        currentDate.setDate(currentDate.getDate() + 1);
    }
    
    calendarHTML += '</div></div>';
    return calendarHTML;
};

window.calculateWorkingHours = function(startTime, endTime) {
    const start = new Date(`2000-01-01 ${startTime}`);
    const end = new Date(`2000-01-01 ${endTime}`);
    return Math.abs(end - start) / (1000 * 60 * 60);
};

window.getStatusColor = function(status) {
    switch(status) {
        case 'active': return 'success';
        case 'draft': return 'warning';
        case 'inactive': return 'danger';
        default: return 'secondary';
    }
};

// Calendar Preview Functionality
function initializeCalendarPreview() {
    console.log('Initializing calendar preview...');
    
    // Add event listeners to all form inputs
    const formInputs = [
        'rosterTeamId', 'rosterName', 'rosterPeriod', 'rosterStartDate', 'rosterEndDate',
        'workStartTime', 'workEndTime', 'maxWorkingHours', 'leaveType', 'leaveReason',
        'rosterStatus', 'rosterNotes'
    ];
    
    // Add listeners to text inputs, selects, and date inputs
    formInputs.forEach(inputId => {
        const element = document.getElementById(inputId);
        if (element) {
            element.addEventListener('input', updateCalendarPreview);
            element.addEventListener('change', updateCalendarPreview);
        }
    });
    
    // Add listeners to working days checkboxes
    const workingDays = ['workingMonday', 'workingTuesday', 'workingWednesday', 'workingThursday', 'workingFriday', 'workingSaturday', 'workingSunday'];
    workingDays.forEach(dayId => {
        const element = document.getElementById(dayId);
        if (element) {
            element.addEventListener('change', updateCalendarPreview);
        }
    });
    
    // Add listeners to leave days checkboxes
    const leaveDays = ['leaveMonday', 'leaveTuesday', 'leaveWednesday', 'leaveThursday', 'leaveFriday', 'leaveSaturday', 'leaveSunday'];
    leaveDays.forEach(dayId => {
        const element = document.getElementById(dayId);
        if (element) {
            element.addEventListener('change', updateCalendarPreview);
        }
    });
    
    // Initial calendar update
    updateCalendarPreview();
}

function updateCalendarPreview() {
    console.log('Updating calendar preview...');
    
    const calendarContainer = document.getElementById('rosterPreviewCalendar');
    if (!calendarContainer) return;
    
    // Get form values
    const rosterName = document.getElementById('rosterName')?.value || 'Untitled Roster';
    const startDate = document.getElementById('rosterStartDate')?.value;
    const endDate = document.getElementById('rosterEndDate')?.value;
    const workStartTime = document.getElementById('workStartTime')?.value || '08:00';
    const workEndTime = document.getElementById('workEndTime')?.value || '17:00';
    const rosterPeriod = document.getElementById('rosterPeriod')?.value;
    const maxHours = document.getElementById('maxWorkingHours')?.value || '40';
    const leaveType = document.getElementById('leaveType')?.value;
    const rosterStatus = document.getElementById('rosterStatus')?.value || 'draft';
    
    // Get working days
    const workingDays = [];
    const workingDayIds = ['workingMonday', 'workingTuesday', 'workingWednesday', 'workingThursday', 'workingFriday', 'workingSaturday', 'workingSunday'];
    workingDayIds.forEach(dayId => {
        const checkbox = document.getElementById(dayId);
        if (checkbox && checkbox.checked) {
            workingDays.push(checkbox.value);
        }
    });
    
    // Get leave days
    const leaveDays = [];
    const leaveDayIds = ['leaveMonday', 'leaveTuesday', 'leaveWednesday', 'leaveThursday', 'leaveFriday', 'leaveSaturday', 'leaveSunday'];
    leaveDayIds.forEach(dayId => {
        const checkbox = document.getElementById(dayId);
        if (checkbox && checkbox.checked) {
            leaveDays.push(checkbox.value);
        }
    });
    
    // Generate calendar HTML
    let calendarHTML = generateCalendarHTML({
        rosterName,
        startDate,
        endDate,
        workStartTime,
        workEndTime,
        rosterPeriod,
        maxHours,
        leaveType,
        rosterStatus,
        workingDays,
        leaveDays
    });
    
    calendarContainer.innerHTML = calendarHTML;
}

function generateCalendarHTML(data) {
    const { rosterName, startDate, endDate, workStartTime, workEndTime, rosterPeriod, maxHours, leaveType, rosterStatus, workingDays, leaveDays } = data;
    
    // If no start date, show placeholder
    if (!startDate) {
        return `
            <div class="text-center text-muted">
                <i class="fas fa-calendar-alt fa-3x mb-3 opacity-50"></i>
                <p class="mb-0">Calendar preview will appear here</p>
                <small class="opacity-75">Configure your roster settings to see the preview</small>
            </div>
        `;
    }
    
    // Generate calendar based on period
    let calendarHTML = `
        <div class="calendar-preview">
            <div class="calendar-header mb-3">
                <h6 class="mb-1 text-primary">${rosterName}</h6>
                <div class="row g-2">
                    <div class="col-md-6">
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            ${startDate ? new Date(startDate).toLocaleDateString() : 'Start Date'} - ${endDate ? new Date(endDate).toLocaleDateString() : 'End Date'}
                        </small>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            ${workStartTime} - ${workEndTime}
                        </small>
                    </div>
                </div>
            </div>
    `;
    
    // Generate weekly view if period is weekly
    if (rosterPeriod === 'weekly' && startDate) {
        calendarHTML += generateWeeklyView(startDate, workingDays, leaveDays, workStartTime, workEndTime);
    } else if (rosterPeriod === 'monthly' && startDate) {
        calendarHTML += generateMonthlyView(startDate, endDate, workingDays, leaveDays, workStartTime, workEndTime);
    } else {
        // Default weekly view
        calendarHTML += generateWeeklyView(startDate, workingDays, leaveDays, workStartTime, workEndTime);
    }
    
    // Add summary
    calendarHTML += `
        <div class="calendar-summary mt-3 pt-3 border-top">
            <div class="row g-2">
                <div class="col-md-4">
                    <small class="text-success">
                        <i class="fas fa-check-circle me-1"></i>
                        Working Days: ${workingDays.length}
                    </small>
                </div>
                <div class="col-md-4">
                    <small class="text-warning">
                        <i class="fas fa-calendar-times me-1"></i>
                        Leave Days: ${leaveDays.length}
                    </small>
                </div>
                <div class="col-md-4">
                    <small class="text-info">
                        <i class="fas fa-clock me-1"></i>
                        Max Hours: ${maxHours}h
                    </small>
                </div>
            </div>
        </div>
    </div>
    `;
    
    return calendarHTML;
}

function generateWeeklyView(startDate, workingDays, leaveDays, workStartTime, workEndTime) {
    const start = new Date(startDate);
    const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    
    let weeklyHTML = '<div class="weekly-calendar"><div class="row g-1">';
    
    // Generate 7 days starting from the start date
    for (let i = 0; i < 7; i++) {
        const currentDate = new Date(start);
        currentDate.setDate(start.getDate() + i);
        const dayName = daysOfWeek[currentDate.getDay()].toLowerCase();
        const isWorkingDay = workingDays.includes(dayName);
        const isLeaveDay = leaveDays.includes(dayName);
        
        let dayClass = 'calendar-day';
        let dayStatus = '';
        let dayIcon = '';
        
        if (isLeaveDay) {
            dayClass += ' leave-day';
            dayStatus = 'Leave';
            dayIcon = '<i class="fas fa-calendar-times text-warning"></i>';
        } else if (isWorkingDay) {
            dayClass += ' working-day';
            dayStatus = 'Working';
            dayIcon = '<i class="fas fa-briefcase text-success"></i>';
        } else {
            dayClass += ' off-day';
            dayStatus = 'Off';
            dayIcon = '<i class="fas fa-home text-muted"></i>';
        }
        
        weeklyHTML += `
            <div class="col">
                <div class="${dayClass} p-2 text-center border rounded">
                    <div class="day-icon mb-1">${dayIcon}</div>
                    <div class="day-name small fw-bold">${daysOfWeek[currentDate.getDay()].substring(0, 3)}</div>
                    <div class="day-date small text-muted">${currentDate.getDate()}</div>
                    <div class="day-status small">${dayStatus}</div>
                    ${isWorkingDay ? `<div class="day-time small text-success">${workStartTime}-${workEndTime}</div>` : ''}
                </div>
            </div>
        `;
    }
    
    weeklyHTML += '</div></div>';
    return weeklyHTML;
}

function generateMonthlyView(startDate, endDate, workingDays, leaveDays, workStartTime, workEndTime) {
    // Simplified monthly view - show first week as example
    return generateWeeklyView(startDate, workingDays, leaveDays, workStartTime, workEndTime) + 
           '<div class="text-center mt-2"><small class="text-muted">Monthly view - showing first week</small></div>';
}
</script>

<!-- Custom SweetAlert CSS to ensure it appears above modals -->
<style>
.swal2-container {
    z-index: 9999 !important;
}
.swal2-popup {
    z-index: 10000 !important;
}

/* Calendar Preview Styles */
.calendar-preview {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.calendar-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 15px;
}

.calendar-header h6 {
    color: white !important;
    margin-bottom: 8px;
}

.calendar-header small {
    color: rgba(255, 255, 255, 0.9) !important;
}

.weekly-calendar .calendar-day {
    min-height: 100px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.weekly-calendar .calendar-day:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.working-day {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    border-color: #28a745 !important;
}

.working-day .day-status {
    color: #155724;
    font-weight: 600;
}

.working-day .day-time {
    color: #155724;
    font-weight: 500;
}

.leave-day {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border-color: #ffc107 !important;
}

.leave-day .day-status {
    color: #856404;
    font-weight: 600;
}

.off-day {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-color: #6c757d !important;
}

.off-day .day-status {
    color: #6c757d;
    font-weight: 500;
}

.day-icon {
    font-size: 1.2em;
}

.day-name {
    font-size: 0.9em;
    margin-bottom: 2px;
}

.day-date {
    font-size: 0.8em;
    margin-bottom: 2px;
}

.day-status {
    font-size: 0.75em;
    margin-bottom: 2px;
}

.day-time {
    font-size: 0.7em;
    margin-top: 2px;
}

.calendar-summary {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 6px;
}

.calendar-summary small {
    font-weight: 500;
}

.calendar-summary .text-success {
    color: #28a745 !important;
}

.calendar-summary .text-warning {
    color: #ffc107 !important;
}

.calendar-summary .text-info {
    color: #17a2b8 !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .weekly-calendar .calendar-day {
        min-height: 80px;
        padding: 8px !important;
    }
    
    .day-icon {
        font-size: 1em;
    }
    
    .day-name {
        font-size: 0.8em;
    }
    
    .day-date {
        font-size: 0.7em;
    }
    
    .day-status {
        font-size: 0.7em;
    }
    
    .day-time {
        font-size: 0.65em;
    }
}

/* Preview Modal Styles */
.roster-preview-popup {
    max-height: 90vh;
    overflow-y: auto;
}

.roster-preview-content {
    max-height: 80vh;
    overflow-y: auto;
}

.roster-preview-modal {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.preview-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.preview-header h4 {
    color: white !important;
    margin-bottom: 5px;
}

.preview-header p {
    color: rgba(255, 255, 255, 0.9) !important;
    margin-bottom: 0;
}

.info-card {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 15px;
    height: 100%;
    transition: all 0.3s ease;
}

.info-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.info-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
}

.info-icon i {
    font-size: 1.2em;
    color: white;
}

.info-content h6 {
    color: #495057;
    font-weight: 600;
    margin-bottom: 5px;
}

.info-content p {
    color: #6c757d;
    font-size: 0.9em;
    margin-bottom: 2px;
}

.info-content small {
    color: #adb5bd;
    font-size: 0.8em;
}

.calendar-day-detailed {
    transition: all 0.3s ease;
    border: 2px solid transparent !important;
}

.calendar-day-detailed:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}

.working-day-detailed {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    border-color: #28a745 !important;
}

.leave-day-detailed {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border-color: #ffc107 !important;
}

.off-day-detailed {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-color: #6c757d !important;
}

.summary-card {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.summary-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.summary-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.summary-icon i {
    font-size: 1.1em;
}

.notes-content {
    border-left: 4px solid #007bff;
}

.leave-content {
    border-left: 4px solid #ffc107;
}

.preview-calendar h5 {
    color: #495057;
    font-weight: 600;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 10px;
}

.preview-summary h6 {
    color: #6c757d;
    font-weight: 500;
}

.preview-summary h4 {
    font-weight: 700;
}

/* Responsive adjustments for preview modal */
@media (max-width: 768px) {
    .roster-preview-popup {
        width: 95% !important;
        margin: 10px auto !important;
    }
    
    .info-card {
        margin-bottom: 15px;
    }
    
    .calendar-day-detailed {
        margin-bottom: 10px;
    }
    
    .summary-card {
        margin-bottom: 15px;
    }
}

/* Calendar Grid Styles - Google Calendar Like */
.calendar-container {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    width: 100%;
    margin: 0;
    padding: 0;
}

/* Calendar grid without day headers */

.calendar-header {
    background: #0d6efd;
    padding: 20px;
    border-bottom: 1px solid #dee2e6;
}

.calendar-navigation .btn {
    border-radius: 6px;
}

/* White outline buttons for calendar header */
.btn-outline-white {
    color: white !important;
    border-color: white !important;
    background-color: transparent !important;
}

.btn-outline-white:hover {
    color: #0d6efd !important;
    background-color: white !important;
    border-color: white !important;
}

.btn-outline-white:focus {
    color: #0d6efd !important;
    background-color: white !important;
    border-color: white !important;
    box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.5) !important;
}

.btn-check:checked + .btn-outline-white {
    color: #0d6efd !important;
    background-color: white !important;
    border-color: white !important;
}

.calendar-grid {
    display: flex !important;
    flex-direction: column !important;
    width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
}

/* Day headers styling */
.calendar-weekdays {
    display: grid !important;
    grid-template-columns: repeat(7, 1fr) !important;
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    margin: 0 !important;
    padding: 0 !important;
    width: 100% !important;
    order: 1 !important;
    flex-shrink: 0 !important;
    box-sizing: border-box !important;
}

.calendar-weekday {
    padding: 8px !important;
    text-align: center !important;
    font-weight: 600;
    color: #495057;
    font-size: 0.9em;
    border-right: 1px solid #dee2e6;
    margin: 0 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    white-space: nowrap !important;
    overflow: hidden;
    box-sizing: border-box !important;
}

.calendar-weekday:last-child {
    border-right: none;
}

.calendar-days {
    display: grid !important;
    grid-template-columns: repeat(7, 1fr) !important;
    min-height: 500px;
    margin: 0 !important;
    padding: 0 !important;
    width: 100% !important;
    order: 2 !important;
    flex: 1 !important;
    box-sizing: border-box !important;
}

.calendar-day {
    border-right: 1px solid #dee2e6;
    border-bottom: 1px solid #dee2e6;
    padding: 8px !important;
    min-height: 100px;
    position: relative;
    background: white;
    transition: background-color 0.2s ease;
    margin: 0 !important;
    display: flex;
    flex-direction: column;
    box-sizing: border-box !important;
}

.calendar-day:hover {
    background: #f8f9fa;
}

.calendar-day:nth-child(7n) {
    border-right: none;
}

.calendar-day.empty-day {
    background: #f8f9fa;
    opacity: 0.5;
}

.calendar-day.today {
    background: #e3f2fd;
    border: 2px solid #2196f3;
}

.calendar-day.today .calendar-day-number {
    background: #2196f3;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.calendar-day-number {
    font-weight: 600;
    color: #495057;
    margin-bottom: 4px;
    font-size: 0.9em;
}

.calendar-events {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.calendar-event {
    border-radius: 4px;
    padding: 4px 6px;
    cursor: pointer;
    transition: all 0.2s ease;
    border-left: 3px solid;
    font-size: 0.75em;
    line-height: 1.2;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.calendar-event:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.roster-event.bg-success {
    background: #d4edda;
    color: #155724;
    border-left-color: #28a745;
}

.roster-event.bg-warning {
    background: #fff3cd;
    color: #856404;
    border-left-color: #ffc107;
}

.roster-event.bg-danger {
    background: #f8d7da;
    color: #721c24;
    border-left-color: #dc3545;
}

.event-title {
    font-weight: 600;
    margin-bottom: 1px;
}

.event-details {
    opacity: 0.8;
    font-size: 0.7em;
}

/* Calendar View Toggle Styles */
.calendar-view-toggle .btn-group {
    border-radius: 6px;
    overflow: hidden;
}

.calendar-view-toggle .btn-check:checked + .btn {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
}

/* Week View Styles */
.calendar-grid.week-view .calendar-days {
    grid-template-columns: repeat(7, 1fr);
    min-height: 400px;
}

.calendar-grid.week-view .calendar-day.week-day {
    min-height: 80px;
    border-right: 1px solid #dee2e6;
    border-bottom: 1px solid #dee2e6;
}

.calendar-grid.week-view .calendar-day.week-day:nth-child(7n) {
    border-right: none;
}

/* Day View Styles */
.calendar-grid.day-view {
    background: white;
    border-radius: 8px;
    overflow: hidden;
}

.day-header {
    background: #f8f9fa;
    padding: 20px;
    border-bottom: 1px solid #dee2e6;
}

.day-content {
    padding: 20px;
    min-height: 400px;
}

.day-event-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.day-event-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
}

.no-events {
    opacity: 0.7;
}

/* Month View Styles */
.calendar-grid.month-view .calendar-days {
    grid-template-columns: repeat(7, 1fr);
    min-height: 500px;
}

/* Responsive calendar */
@media (max-width: 768px) {
    .calendar-day {
        min-height: 80px;
        padding: 4px;
    }
    
    .calendar-weekday {
        padding: 8px 4px;
        font-size: 0.8em;
    }
    
    .calendar-event {
        font-size: 0.7em;
        padding: 2px 4px;
    }
    
    .calendar-header {
        padding: 15px;
    }
    
    .calendar-header h4 {
        font-size: 1.2em;
    }
    
    .calendar-navigation .btn {
        padding: 4px 8px;
        font-size: 0.8em;
    }
    
    .calendar-view-toggle .btn {
        padding: 4px 8px;
        font-size: 0.8em;
    }
    
    .day-header {
        padding: 15px;
    }
    
    .day-content {
        padding: 15px;
    }
    
    .calendar-grid.week-view .calendar-day.week-day {
        min-height: 60px;
    }
}

/* Professional Roster Details Modal Styling */
.roster-details-popup {
    border-radius: 12px !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
    border: none !important;
}

.roster-details-content {
    padding: 0 !important;
}

.roster-details-modal {
    text-align: left;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
}

/* Header Section */
.roster-modal-header {
    display: flex;
    align-items: center;
    font-size: 1.5rem;
    font-weight: 600;
    color: #2c3e50;
}

.roster-header-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.roster-title .roster-name {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0 0 5px 0;
    color: white;
}

.roster-title .roster-id {
    font-size: 0.9rem;
    opacity: 0.8;
    font-weight: 400;
}

.status-badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
}

.status-success {
    background: rgba(40, 167, 69, 0.2);
    color: #28a745;
    border: 1px solid rgba(40, 167, 69, 0.3);
}

.status-warning {
    background: rgba(255, 193, 7, 0.2);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.3);
}

.status-danger {
    background: rgba(220, 53, 69, 0.2);
    color: #dc3545;
    border: 1px solid rgba(220, 53, 69, 0.3);
}

/* Content Section */
.roster-content {
    padding: 30px;
    background: #f8f9fa;
}

.info-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    margin-bottom: 20px;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.info-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
}

.info-card-header {
    background: #f8f9fa;
    padding: 15px 20px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    align-items: center;
    gap: 10px;
}

.info-card-header i {
    font-size: 1.2rem;
}

.info-card-header h6 {
    margin: 0;
    font-weight: 600;
    color: #495057;
    font-size: 1rem;
}

.info-card-body {
    padding: 20px;
}

.info-item {
    margin-bottom: 15px;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-item label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0;
}

.info-value {
    font-size: 1rem;
    color: #2c3e50;
    font-weight: 500;
}

/* Special Value Styling */
.team-badge {
    background: #e3f2fd;
    color: #1976d2;
    padding: 6px 12px;
    border-radius: 6px;
    font-weight: 600;
    display: inline-block;
    width: fit-content;
}

.period-badge {
    background: #f3e5f5;
    color: #7b1fa2;
    padding: 6px 12px;
    border-radius: 6px;
    font-weight: 600;
    display: inline-block;
    width: fit-content;
}

.hours-display {
    background: #e8f5e8;
    color: #2e7d32;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 1.1rem;
    display: inline-block;
    width: fit-content;
    border-left: 4px solid #4caf50;
}

.date-display {
    background: #fff3e0;
    color: #f57c00;
    padding: 6px 12px;
    border-radius: 6px;
    font-weight: 600;
    display: inline-block;
    width: fit-content;
}

.creator-name {
    background: #fff8e1;
    color: #f9a825;
    padding: 6px 12px;
    border-radius: 6px;
    font-weight: 600;
    display: inline-block;
    width: fit-content;
}

.working-days-container {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 5px;
}

.day-badge {
    background: #e8f5e8;
    color: #2e7d32;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: capitalize;
}

/* Compact Modal Styling */
.info-bar {
    background: #f8f9fa;
    padding: 15px 25px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.info-item-compact {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
}

.info-item-compact i {
    font-size: 1rem;
}

.info-label {
    color: #6c757d;
    font-weight: 500;
}

.info-value {
    color: #2c3e50;
    font-weight: 600;
}

/* Tab Styling */
.roster-tabs {
    display: flex;
    border-bottom: 1px solid #e9ecef;
    margin-bottom: 20px;
}

.tab-button {
    background: none;
    border: none;
    padding: 12px 20px;
    cursor: pointer;
    color: #6c757d;
    font-weight: 500;
    border-bottom: 2px solid transparent;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
}

.tab-button:hover {
    color: #495057;
    background: #f8f9fa;
}

.tab-button.active {
    color: #0d6efd;
    border-bottom-color: #0d6efd;
    background: #f8f9fa;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

/* Compact Cards */
.info-card-compact {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
}

.info-card-compact h6 {
    margin: 0 0 15px 0;
    color: #495057;
    font-weight: 600;
    font-size: 0.95rem;
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 10px;
}

.info-item-small {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 5px 0;
    border-bottom: 1px solid #f8f9fa;
}

.info-item-small:last-child {
    border-bottom: none;
}

.info-item-small label {
    font-size: 0.8rem;
    color: #6c757d;
    font-weight: 500;
    margin: 0;
}

/* Team Members Styling */
.team-members-section h6 {
    margin-bottom: 15px;
    color: #495057;
    font-weight: 600;
}

.team-members-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
}

.team-member-card {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.team-member-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.member-avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
}

.member-info {
    flex: 1;
}

.member-name {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 2px;
    font-size: 0.9rem;
}

.member-role {
    color: #6c757d;
    font-size: 0.8rem;
    margin-bottom: 4px;
}

.member-status {
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    gap: 4px;
}

.member-status.status-active {
    color: #28a745;
}

.member-status.status-inactive {
    color: #6c757d;
}

.member-email, .member-phone {
    color: #6c757d;
    font-size: 0.75rem;
    margin-bottom: 2px;
    display: flex;
    align-items: center;
}

.member-email i, .member-phone i {
    font-size: 0.7rem;
    width: 12px;
}

.member-team {
    color: #6c757d;
    font-size: 0.75rem;
    margin-bottom: 2px;
    display: flex;
    align-items: center;
}

.member-team i {
    font-size: 0.7rem;
    width: 12px;
}

.no-team-data {
    text-align: center;
    padding: 30px;
    color: #6c757d;
}

/* Mini Calendar Styling */
.mini-calendar-section h6 {
    margin-bottom: 15px;
    color: #495057;
    font-weight: 600;
}

.mini-calendar {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
}

.mini-calendar-grid {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.mini-calendar-weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 5px;
}

.mini-weekday {
    text-align: center;
    font-size: 0.8rem;
    font-weight: 600;
    color: #6c757d;
    padding: 5px;
}

.mini-calendar-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 5px;
}

.mini-day {
    aspect-ratio: 1;
    border: 2px solid;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 8px;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.mini-day.working-day {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    border-color: #28a745;
    box-shadow: 0 2px 4px rgba(40, 167, 69, 0.2);
}

.mini-day.working-day:hover {
    background: linear-gradient(135deg, #c3e6cb 0%, #b8dfc7 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
}

.mini-day.off-day {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    border-color: #dc3545;
    box-shadow: 0 2px 4px rgba(220, 53, 69, 0.2);
}

.mini-day.off-day:hover {
    background: linear-gradient(135deg, #f5c6cb 0%, #f1b0b7 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
}

.mini-day-name {
    font-size: 0.75rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 3px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.mini-day-status {
    font-size: 0.8rem;
    margin-bottom: 2px;
}

.mini-day-label {
    font-size: 0.6rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 2px 4px;
    border-radius: 3px;
    background: rgba(255, 255, 255, 0.8);
}

.working-day .mini-day-label {
    color: #155724;
}

.off-day .mini-day-label {
    color: #721c24;
}

.mini-calendar-legend {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin: 15px 0 10px 0;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 6px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.legend-color {
    width: 16px;
    height: 16px;
    border-radius: 4px;
    border: 2px solid;
}

.working-legend {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    border-color: #28a745;
}

.off-legend {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    border-color: #dc3545;
}

.legend-text {
    font-size: 0.75rem;
    font-weight: 600;
    color: #495057;
}

.mini-calendar-summary {
    display: flex;
    justify-content: space-between;
    padding-top: 10px;
    border-top: 1px solid #e9ecef;
}

.summary-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
}

.summary-label {
    font-size: 0.7rem;
    color: #6c757d;
    font-weight: 500;
}

.summary-value {
    font-size: 0.9rem;
    font-weight: 600;
    color: #2c3e50;
}

/* Small Value Styling */
.period-badge, .hours-display-small, .creator-name-small, .created-date-small {
    font-size: 0.8rem;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: 600;
}

.period-badge {
    background: #f3e5f5;
    color: #7b1fa2;
}

.hours-display-small {
    background: #e8f5e8;
    color: #2e7d32;
}

.creator-name-small {
    background: #fff8e1;
    color: #f9a825;
}

.created-date-small {
    background: #e3f2fd;
    color: #1976d2;
}

.working-days-compact {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
}

.day-badge-small {
    background: #e8f5e8;
    color: #2e7d32;
    padding: 2px 6px;
    border-radius: 8px;
    font-size: 0.7rem;
    font-weight: 600;
}

.status-info-small {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
}

.team-code, .period-description, .max-hours {
    display: block;
    margin-top: 4px;
    color: #6c757d;
    font-size: 0.8rem;
}

.working-days-stats {
    margin-top: 10px;
}

.progress-bar-container {
    background: #e9ecef;
    height: 6px;
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 5px;
}

.progress-bar {
    background: linear-gradient(90deg, #28a745, #20c997);
    height: 100%;
    transition: width 0.3s ease;
}

.working-percentage {
    color: #6c757d;
    font-size: 0.8rem;
    font-weight: 500;
}

/* Statistics Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
}

.stat-item {
    text-align: center;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.8rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

/* Additional Value Styling */
.duration-display {
    background: #e3f2fd;
    color: #1976d2;
    padding: 6px 12px;
    border-radius: 6px;
    font-weight: 600;
    display: inline-block;
    width: fit-content;
}

.created-date {
    background: #f3e5f5;
    color: #7b1fa2;
    padding: 6px 12px;
    border-radius: 6px;
    font-weight: 600;
    display: inline-block;
    width: fit-content;
}

.status-info {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.status-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
}

.status-indicator.status-success {
    background: #28a745;
}

.status-indicator.status-warning {
    background: #ffc107;
}

.status-indicator.status-danger {
    background: #dc3545;
}

.active-indicator, .draft-indicator {
    font-size: 0.75rem;
    padding: 2px 6px;
    border-radius: 10px;
    font-weight: 500;
}

.active-indicator {
    background: #d4edda;
    color: #155724;
}

.draft-indicator {
    background: #fff3cd;
    color: #856404;
}

.roster-id-display {
    background: #e9ecef;
    color: #495057;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: 600;
    font-family: monospace;
    display: inline-block;
    width: fit-content;
}

/* Responsive Design */
@media (max-width: 768px) {
    .roster-header-section {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .roster-content {
        padding: 20px;
    }
    
    .info-card-body {
        padding: 15px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }
    
    .status-info {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

<!-- DataTables CDN -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />

<!-- Select2 for multi-select -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<style>
    .roaster-container {
        background: #f5f7ff;
        min-height: 100%;
        padding: 1.5rem;
        border-radius: 12px;
    }

    /* Enhanced Dashboard Cards */
    .dashboard-card {
        background: #fff;
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
        height: 100%;
    }

    .dashboard-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .dashboard-card:hover::before {
        opacity: 1;
    }

    .card-content {
        position: relative;
        z-index: 2;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .card-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .card-icon::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), transparent);
        border-radius: 16px;
    }

    .dashboard-card:hover .card-icon {
        transform: scale(1.15) rotate(5deg);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
    }

    .card-title {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 8px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .card-value {
        font-size: 32px;
        font-weight: 800;
        color: #1a202c;
        margin-bottom: 12px;
        line-height: 1.2;
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .card-subtitle {
        font-size: 13px;
        color: #718096;
        margin-bottom: 16px;
        font-weight: 500;
        display: flex;
        align-items: center;
    }

    .card-trend {
        position: absolute;
        top: 20px;
        right: 20px;
    }

    .trend-indicator {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        font-size: 12px;
        font-weight: 600;
    }

    .trend-indicator.positive {
        background: linear-gradient(135deg, #48bb78, #38a169);
        color: white;
    }

    .trend-indicator.negative {
        background: linear-gradient(135deg, #f56565, #e53e3e);
        color: white;
    }

    .trend-indicator.neutral {
        background: linear-gradient(135deg, #a0aec0, #718096);
        color: white;
    }

    .card-progress {
        width: 100%;
        height: 4px;
        background: rgba(0, 0, 0, 0.1);
        border-radius: 2px;
        overflow: hidden;
        margin-top: auto;
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #667eea, #764ba2);
        border-radius: 2px;
        transition: width 0.6s ease;
    }

    /* Specific card styles */
    .card-total-rosters {
        border-left: 4px solid #667eea;
    }

    .card-active-rosters {
        border-left: 4px solid #11998e;
    }

    .card-draft-rosters {
        border-left: 4px solid #f093fb;
    }

    .card-inactive-rosters {
        border-left: 4px solid #4facfe;
    }

    .card-teams-with-rosters {
        border-left: 4px solid #fa709a;
    }

    .card-weekly-rosters {
        border-left: 4px solid #a8edea;
    }

    .card-monthly-rosters {
        border-left: 4px solid #ffecd2;
    }

    .card-this-month-rosters {
        border-left: 4px solid #667eea;
    }

    /* Animation for card values */
    .card-value {
        animation: countUp 0.8s ease-out;
    }

    @keyframes countUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .dashboard-card {
            padding: 20px;
        }
        
        .card-icon {
            width: 48px;
            height: 48px;
            font-size: 20px;
        }
        
        .card-value {
            font-size: 24px;
        }
    }

    .card-change {
        font-size: 12px;
        font-weight: 500;
        display: flex;
        align-items: center;
        color: #6c757d;
    }

    /* Card specific colors */
    .card-teams { border-left-color: #007bff; }
    .card-departments { border-left-color: #28a745; }
    .card-assignments { border-left-color: #ffc107; }
    .card-coverage { border-left-color: #17a2b8; }

    /* Month Navigation */
    .month-navigation {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        padding: 20px;
        margin-bottom: 20px;
    }

    .month-selector {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
    }

    .month-nav-btn {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #495057;
        padding: 8px 12px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .month-nav-btn:hover {
        background: #e9ecef;
        border-color: #adb5bd;
    }

    .current-month {
        font-size: 20px;
        font-weight: 600;
        color: #2c3e50;
        margin: 0 20px;
    }

    /* Calendar Styles */
    .roaster-calendar {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        padding: 20px;
        margin-bottom: 20px;
    }

    .fc-event {
        border: none !important;
        border-radius: 4px !important;
        padding: 2px 6px !important;
        font-size: 11px !important;
        font-weight: 600 !important;
    }

    .fc-event-title {
        font-weight: 600 !important;
    }

    /* Team color coding */
    .team-alpha { background-color: #007bff !important; }
    .team-beta { background-color: #28a745 !important; }
    .team-gamma { background-color: #ffc107 !important; color: #000 !important; }
    .team-delta { background-color: #dc3545 !important; }
    .team-epsilon { background-color: #6f42c1 !important; }
    .team-zeta { background-color: #fd7e14 !important; }

    /* Department sections */
    .department-section {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .department-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 20px;
        font-weight: 600;
    }

    .department-content {
        padding: 20px;
    }

    /* Assignment table */
    .assignment-table {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
    }

    .assignment-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid #dee2e6;
    }

    .assignment-table td {
        vertical-align: middle;
        border-bottom: 1px solid #dee2e6;
    }

    .team-assignment {
        display: inline-block;
        background: #e9ecef;
        color: #495057;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        margin: 2px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .team-assignment:hover {
        background: #007bff;
        color: white;
    }

    .team-assignment.assigned {
        background: #007bff;
        color: white;
    }

    .day-cell {
        min-height: 80px;
        border: 1px solid #dee2e6;
        padding: 8px;
        vertical-align: top;
    }

    .day-number {
        font-weight: 600;
        color: #495057;
        margin-bottom: 5px;
    }

    .weekend {
        background-color: #f8f9fa;
    }

    .today {
        background-color: #fff3cd;
        border-color: #ffc107;
    }

    /* Action buttons */
    .action-btn {
        opacity: 1 !important;
        visibility: visible !important;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        padding: 0 8px;
        cursor: pointer;
        border-radius: 4px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin: 0 2px;
    }
    
    .action-btn:hover {
        opacity: 0.9 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.15);
    }
    
    .action-btn i {
        font-size: 14px;
    }

    .form-floating > label {
        padding: 1rem 0.75rem;
    }

    .required-field::after {
        content: " *";
        color: #e74c3c;
    }

    /* Roster Preview Calendar */
    .roster-preview {
        background: rgba(255,255,255,0.1);
        border-radius: 8px;
        padding: 15px;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
        margin-bottom: 15px;
    }

    .calendar-day {
        background: rgba(255,255,255,0.1);
        border-radius: 6px;
        padding: 8px;
        text-align: center;
        min-height: 60px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .calendar-day.working-day {
        background: rgba(76, 175, 80, 0.3);
        border: 1px solid rgba(76, 175, 80, 0.5);
    }

    .calendar-day.leave-day {
        background: rgba(244, 67, 54, 0.3);
        border: 1px solid rgba(244, 67, 54, 0.5);
    }

    .calendar-day.off-day {
        background: rgba(158, 158, 158, 0.3);
        border: 1px solid rgba(158, 158, 158, 0.5);
    }

    .day-number {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 2px;
    }

    .day-status {
        font-size: 10px;
        font-weight: 500;
        margin-bottom: 2px;
    }

    .day-hours {
        font-size: 9px;
        opacity: 0.8;
    }

    .roster-summary {
        background: rgba(255,255,255,0.1);
        border-radius: 6px;
        padding: 10px;
    }

    .summary-item {
        text-align: center;
    }

    .summary-item small {
        display: block;
        margin-bottom: 4px;
    }

    .summary-item .fw-bold {
        font-size: 16px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .month-selector {
            flex-direction: column;
            text-align: center;
        }

        .current-month {
            margin: 10px 0;
        }

        .dashboard-card {
            margin-bottom: 15px;
            padding: 20px;
        }
        
        .card-value {
            font-size: 24px;
        }

        .calendar-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .calendar-day {
            min-height: 50px;
        }
    }
</style>
@endsection

@section('content')
<div class="roaster-container">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('any', 'company/index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Master Tracker</a></li>
                        <li class="breadcrumb-item active">Team Roaster</li>
                    </ol>
                </div>
                <h4 class="page-title">Team Roaster Management</h4>
                <p class="text-muted mb-0">Manage team assignments by department and schedule</p>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Enhanced Stats Cards -->
    <div class="row mb-4">
        <!-- Total Rosters Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card card-total-rosters">
                <div class="card-content">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="card-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="card-trend">
                            <span class="trend-indicator positive">
                                <i class="fas fa-arrow-up"></i>
                            </span>
                        </div>
                    </div>
                    <h6 class="card-title">Total Rosters</h6>
                    <h3 class="card-value" id="totalRosters">{{ $rosters->count() ?? 0 }}</h3>
                    <div class="card-subtitle">
                        <i class="fas fa-layer-group me-1"></i> All roster entries
                    </div>
                    <div class="card-progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Active Rosters Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card card-active-rosters">
                <div class="card-content">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="card-icon" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="card-trend">
                            <span class="trend-indicator positive">
                                <i class="fas fa-arrow-up"></i>
                            </span>
                        </div>
                    </div>
                    <h6 class="card-title">Active Rosters</h6>
                    <h3 class="card-value" id="activeRosters">{{ $rosters->where('roster_status', 'active')->count() ?? 0 }}</h3>
                    <div class="card-subtitle">
                        <i class="fas fa-play-circle me-1"></i> Currently running
                    </div>
                    <div class="card-progress">
                        <div class="progress-bar" style="width: {{ $rosters->count() > 0 ? ($rosters->where('roster_status', 'active')->count() / $rosters->count() * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Draft Rosters Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card card-draft-rosters">
                <div class="card-content">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="card-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="card-trend">
                            <span class="trend-indicator neutral">
                                <i class="fas fa-minus"></i>
                            </span>
                        </div>
                    </div>
                    <h6 class="card-title">Draft Rosters</h6>
                    <h3 class="card-value" id="draftRosters">{{ $rosters->where('roster_status', 'draft')->count() ?? 0 }}</h3>
                    <div class="card-subtitle">
                        <i class="fas fa-clock me-1"></i> Pending approval
                    </div>
                    <div class="card-progress">
                        <div class="progress-bar" style="width: {{ $rosters->count() > 0 ? ($rosters->where('roster_status', 'draft')->count() / $rosters->count() * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Inactive Rosters Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card card-inactive-rosters">
                <div class="card-content">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="card-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <i class="fas fa-pause-circle"></i>
                        </div>
                        <div class="card-trend">
                            <span class="trend-indicator negative">
                                <i class="fas fa-arrow-down"></i>
                            </span>
                        </div>
                    </div>
                    <h6 class="card-title">Inactive Rosters</h6>
                    <h3 class="card-value" id="inactiveRosters">{{ $rosters->where('roster_status', 'inactive')->count() ?? 0 }}</h3>
                    <div class="card-subtitle">
                        <i class="fas fa-stop-circle me-1"></i> Disabled rosters
                    </div>
                    <div class="card-progress">
                        <div class="progress-bar" style="width: {{ $rosters->count() > 0 ? ($rosters->where('roster_status', 'inactive')->count() / $rosters->count() * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="row mb-4">
        <!-- Teams with Rosters Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card card-teams-with-rosters">
                <div class="card-content">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="card-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                        <i class="fas fa-users"></i>
                    </div>
                        <div class="card-trend">
                            <span class="trend-indicator positive">
                                <i class="fas fa-arrow-up"></i>
                            </span>
                    </div>
                </div>
                    <h6 class="card-title">Teams with Rosters</h6>
                    <h3 class="card-value" id="teamsWithRosters">{{ $rosters->pluck('team_id')->unique()->count() ?? 0 }}</h3>
                    <div class="card-subtitle">
                        <i class="fas fa-user-friends me-1"></i> Active teams
                    </div>
                    <div class="card-progress">
                        <div class="progress-bar" style="width: {{ $teams->count() > 0 ? ($rosters->pluck('team_id')->unique()->count() / $teams->count() * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Weekly Rosters Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card card-weekly-rosters">
                <div class="card-content">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="card-icon" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                            <i class="fas fa-calendar-week"></i>
                    </div>
                        <div class="card-trend">
                            <span class="trend-indicator positive">
                                <i class="fas fa-arrow-up"></i>
                            </span>
                    </div>
                </div>
                    <h6 class="card-title">Weekly Rosters</h6>
                    <h3 class="card-value" id="weeklyRosters">{{ $rosters->where('roster_period', 'weekly')->count() ?? 0 }}</h3>
                    <div class="card-subtitle">
                        <i class="fas fa-calendar me-1"></i> Weekly schedules
                    </div>
                    <div class="card-progress">
                        <div class="progress-bar" style="width: {{ $rosters->count() > 0 ? ($rosters->where('roster_period', 'weekly')->count() / $rosters->count() * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Monthly Rosters Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card card-monthly-rosters">
                <div class="card-content">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="card-icon" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
                            <i class="fas fa-calendar"></i>
                    </div>
                        <div class="card-trend">
                            <span class="trend-indicator positive">
                                <i class="fas fa-arrow-up"></i>
                            </span>
                    </div>
                </div>
                    <h6 class="card-title">Monthly Rosters</h6>
                    <h3 class="card-value" id="monthlyRosters">{{ $rosters->where('roster_period', 'monthly')->count() ?? 0 }}</h3>
                    <div class="card-subtitle">
                        <i class="fas fa-calendar-alt me-1"></i> Monthly schedules
                    </div>
                    <div class="card-progress">
                        <div class="progress-bar" style="width: {{ $rosters->count() > 0 ? ($rosters->where('roster_period', 'monthly')->count() / $rosters->count() * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- This Month's Rosters Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card card-this-month-rosters">
                <div class="card-content">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="card-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas fa-calendar-day"></i>
                    </div>
                        <div class="card-trend">
                            <span class="trend-indicator positive">
                                <i class="fas fa-arrow-up"></i>
                            </span>
                    </div>
                </div>
                    <h6 class="card-title">This Month</h6>
                    <h3 class="card-value" id="thisMonthRosters">{{ $rosters->where('created_at', '>=', now()->startOfMonth())->count() ?? 0 }}</h3>
                    <div class="card-subtitle">
                        <i class="fas fa-plus-circle me-1"></i> Created this month
                    </div>
                    <div class="card-progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRosterModal">
                            <i class="fas fa-calendar-plus me-2"></i>Create Team Roaster
                    </button>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                        <i class="fas fa-upload me-2"></i>Bulk Upload
                    </button>
                    <button type="button" class="btn btn-info" onclick="exportRoaster()">
                        <i class="fas fa-download me-2"></i>Export Data
                    </button>
                </div>
                <div class="text-muted">
                    <small><i class="fas fa-info-circle me-1"></i>Last updated: <span id="lastUpdated">Loading...</span></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0 me-3">Quick Actions:</h6>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-info btn-sm" onclick="viewCalendar()">
                                    <i class="fas fa-calendar me-1"></i> Calendar View
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="viewTable()">
                                    <i class="fas fa-table me-1"></i> Table View
                                </button>
                            </div>
                        </div>
                        <div class="d-flex align-items-center text-muted">
                            <small><i class="fas fa-clock me-1"></i> Auto-refresh: <span class="text-success">Active</span></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Month Navigation -->
  

    <!-- View Toggle -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                <div class="btn-group" role="group" aria-label="View Toggle">
                    <input type="radio" class="btn-check" name="viewType" id="calendarView" value="calendar" checked>
                    <label class="btn btn-outline-primary" for="calendarView">
                        <i class="fas fa-calendar me-1"></i> Calendar View
                    </label>

                    <input type="radio" class="btn-check" name="viewType" id="tableView" value="table">
                    <label class="btn btn-outline-primary" for="tableView">
                        <i class="fas fa-table me-1"></i> Table View
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar View -->
    <div id="calendarViewContainer" class="view-container">
        <div class="roaster-calendar">
            <div id="calendar"></div>
        </div>
    </div>

    <!-- Table View -->
    <div id="tableViewContainer" class="view-container" style="display: none;">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-table me-2 text-primary"></i>Team Rosters
                    </h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="refreshRosterTable()">
                            <i class="fas fa-sync-alt me-1"></i>Refresh
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="exportRosterData()">
                            <i class="fas fa-download me-1"></i>Export
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="rosterDataTable" class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Roster Name</th>
                                <th>Team</th>
                                <th>Period</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Working Days</th>
                                <th>Working Hours</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($rosters) && count($rosters) > 0)
                                @foreach($rosters as $roster)
                                <tr data-roster-notes="{{ $roster->roster_notes ?? '' }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                <i class="fas fa-calendar-alt text-primary"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $roster->roster_name }}</h6>
                                                <small class="text-muted">ID: {{ $roster->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $roster->team->team_name ?? 'N/A' }}</span>
                                        <br>
                                        <small class="text-muted">{{ $roster->team->team_code ?? '' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ ucfirst($roster->roster_period) }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ \Carbon\Carbon::parse($roster->start_date)->format('M d, Y') }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ \Carbon\Carbon::parse($roster->end_date)->format('M d, Y') }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            @if($roster->working_days)
                                                @if(is_array($roster->working_days))
                                                    @foreach($roster->working_days as $day)
                                                        <span class="badge bg-success">{{ ucfirst($day) }}</span>
                                                    @endforeach
                                                @else
                                                    @foreach(json_decode($roster->working_days) as $day)
                                                        <span class="badge bg-success">{{ ucfirst($day) }}</span>
                                                    @endforeach
                                                @endif
                                            @else
                                                <span class="text-muted">No days set</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <span class="fw-bold text-primary">{{ $roster->work_start_time }} - {{ $roster->work_end_time }}</span>
                                            <br>
                                            <small class="text-muted">{{ $roster->max_working_hours }}h max</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($roster->roster_status == 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($roster->roster_status == 'draft')
                                            <span class="badge bg-warning">Draft</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-light rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                <i class="fas fa-user text-muted"></i>
                                            </div>
                                            <div>
                                                <span class="fw-medium">{{ $roster->creator->fullname ?? 'Unknown' }}</span>
                                                <br>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($roster->created_at)->format('M d, Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="viewRosterDetails({{ $roster->id }})" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-warning btn-sm" onclick="editRoster({{ $roster->id }})" title="Edit Roster">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteRoster({{ $roster->id }})" title="Delete Roster">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-calendar-alt fa-3x mb-3 opacity-50"></i>
                                            <h5>No Rosters Found</h5>
                                            <p>Create your first team roster to get started.</p>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRosterModal">
                                                <i class="fas fa-plus me-2"></i>Create Team Roster
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Assign Team Modal -->
<div class="modal fade" id="assignTeamModal" tabindex="-1" aria-labelledby="assignTeamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="assignTeamModalLabel">
                    <i class="fas fa-user-plus me-2"></i>Quick Assign Team
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignTeamForm" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <!-- Team Selection Card -->
                    <div class="card border-0 mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-users text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Team Selection</h6>
                                    <small class="text-muted">Choose team and department</small>
                                </div>
                            </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="department" id="assignDepartment" required>
                                    <option value="">Select Department</option>
                                    <option value="technical">Technical</option>
                                    <option value="operations">Operations</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="administration">Administration</option>
                                    <option value="customer-service">Customer Service</option>
                                </select>
                                <label for="assignDepartment" class="required-field">Department</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="team" id="assignTeam" required>
                                    <option value="">Select Team</option>
                                            @foreach($teams as $team)
                                                <option value="{{ $team->id }}">{{ $team->team_name }} ({{ $team->team_code }})</option>
                                            @endforeach
                                </select>
                                <label for="assignTeam" class="required-field">Team</label>
                            </div>
                        </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assignment Details Card -->
                    <div class="card border-0 mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-calendar-alt text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Assignment Details</h6>
                                    <small class="text-muted">Set date, shift and priority</small>
                                </div>
                            </div>
                            <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="assignment_date" id="assignmentDate" required>
                                <label for="assignmentDate" class="required-field">Assignment Date</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="shift" id="assignShift" required>
                                    <option value="">Select Shift</option>
                                    <option value="morning">Morning (8AM - 4PM)</option>
                                    <option value="afternoon">Afternoon (12PM - 8PM)</option>
                                    <option value="evening">Evening (4PM - 12AM)</option>
                                    <option value="night">Night (8PM - 6AM)</option>
                                    <option value="full-day">Full Day (8AM - 8PM)</option>
                                </select>
                                <label for="assignShift" class="required-field">Shift</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="priority" id="assignPriority">
                                    <option value="normal">Normal</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                                <label for="assignPriority">Priority</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="status" id="assignStatus">
                                    <option value="scheduled">Scheduled</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="in-progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                </select>
                                <label for="assignStatus">Status</label>
                            </div>
                        </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Settings Card -->
                    <div class="card border-0 mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-cog text-info"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Additional Settings</h6>
                                    <small class="text-muted">Notes and repeat options</small>
                                </div>
                            </div>
                            <div class="row g-3">
                        <div class="col-12">
                            <div class="form-floating">
                                        <textarea class="form-control" name="notes" id="assignNotes" placeholder=" " style="height: 80px"></textarea>
                                <label for="assignNotes">Assignment Notes</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="repeatAssignment" name="repeat_assignment">
                                <label class="form-check-label" for="repeatAssignment">
                                            <i class="fas fa-repeat me-1"></i>Repeat this assignment for multiple days
                                </label>
                            </div>
                        </div>
                        <div class="col-12" id="repeatOptions" style="display: none;">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" name="repeat_end_date" id="repeatEndDate">
                                        <label for="repeatEndDate">Repeat Until</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" name="repeat_pattern" id="repeatPattern">
                                            <option value="daily">Daily</option>
                                            <option value="weekdays">Weekdays Only</option>
                                            <option value="weekends">Weekends Only</option>
                                            <option value="weekly">Weekly</option>
                                        </select>
                                        <label for="repeatPattern">Repeat Pattern</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-1"></i>Assign Team
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Team Roaster Modal -->
<div class="modal fade" id="createRosterModal" tabindex="-1" aria-labelledby="createRosterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow-lg">
            <h4 class="modal-title fw-bold" id="createRosterModalLabel">
                <i class="fas fa-calendar-plus me-2"></i>Create New Team Roster
            </h4>
            <div class="modal-header bg-gradient-primary text-white">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createRosterForm" method="POST" action="/company/MasterTracker/team-roaster">
                @csrf
                <div class="modal-body p-4">
                    <!-- Team Selection Card -->
                    <div class="card border-0 mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-users text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Team Selection</h6>
                                    <small class="text-muted">Choose team for roster creation</small>
                                </div>
                            </div>
                    <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" name="team_id" id="rosterTeamId" required>
                                            <option value="">Select Team</option>
                                            @foreach($teams as $team)
                                                <option value="{{ $team->id }}">{{ $team->team_name }} ({{ $team->team_code }})</option>
                                            @endforeach
                                        </select>
                                        <label for="rosterTeamId" class="required-field">Team</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="roster_name" id="rosterName" required placeholder=" ">
                                        <label for="rosterName" class="required-field">Roster Name</label>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        
                    <!-- Period Selection Card -->
                    <div class="card border-0 mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-calendar-alt text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Period Selection</h6>
                                    <small class="text-muted">Define roster period and duration</small>
                                </div>
                            </div>
                            <div class="row g-3">
                        <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" name="roster_period" id="rosterPeriod" required>
                                            <option value="">Select Period</option>
                                            <option value="weekly">Weekly</option>
                                            <option value="monthly">Monthly</option>
                            </select>
                                        <label for="rosterPeriod" class="required-field">Roster Period</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" name="start_date" id="rosterStartDate" required>
                                        <label for="rosterStartDate" class="required-field">Start Date</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" name="end_date" id="rosterEndDate" required>
                                        <label for="rosterEndDate" class="required-field">End Date</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" name="max_working_hours" id="maxWorkingHours" min="1" max="168" value="40" placeholder=" ">
                                        <label for="maxWorkingHours">Max Working Hours</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        
                    <!-- Working Days Configuration Card -->
                    <div class="card border-0 mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-clock text-info"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Working Days Configuration</h6>
                                    <small class="text-muted">Set working days and hours</small>
                                </div>
                            </div>
                            <div class="row g-3">
                        <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="time" class="form-control" name="work_start_time" id="workStartTime" value="08:00" required>
                                        <label for="workStartTime" class="required-field">Work Start Time</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="time" class="form-control" name="work_end_time" id="workEndTime" value="17:00" required>
                                        <label for="workEndTime" class="required-field">Work End Time</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Working Days</label>
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="working_days[]" value="monday" id="workingMonday" checked>
                                                <label class="form-check-label" for="workingMonday">Monday</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="working_days[]" value="tuesday" id="workingTuesday" checked>
                                                <label class="form-check-label" for="workingTuesday">Tuesday</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="working_days[]" value="wednesday" id="workingWednesday" checked>
                                                <label class="form-check-label" for="workingWednesday">Wednesday</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="working_days[]" value="thursday" id="workingThursday" checked>
                                                <label class="form-check-label" for="workingThursday">Thursday</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="working_days[]" value="friday" id="workingFriday" checked>
                                                <label class="form-check-label" for="workingFriday">Friday</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="working_days[]" value="saturday" id="workingSaturday">
                                                <label class="form-check-label" for="workingSaturday">Saturday</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="working_days[]" value="sunday" id="workingSunday">
                                                <label class="form-check-label" for="workingSunday">Sunday</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        
                    <!-- Leave Days Configuration Card -->
                    <div class="card border-0 mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-calendar-times text-warning"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Leave Days Configuration</h6>
                                    <small class="text-muted">Set leave days and reasons</small>
                                </div>
                            </div>
                            <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                        <select class="form-select" name="leave_type" id="leaveType">
                                            <option value="">No Leave</option>
                                            <option value="vacation">Vacation</option>
                                            <option value="sick">Sick Leave</option>
                                            <option value="personal">Personal</option>
                                            <option value="holiday">Holiday</option>
                                            <option value="training">Training</option>
                                        </select>
                                        <label for="leaveType">Leave Type</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="leave_reason" id="leaveReason" placeholder=" ">
                                        <label for="leaveReason">Leave Reason</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Leave Days</label>
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="leave_days[]" value="monday" id="leaveMonday">
                                                <label class="form-check-label" for="leaveMonday">Monday</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="leave_days[]" value="tuesday" id="leaveTuesday">
                                                <label class="form-check-label" for="leaveTuesday">Tuesday</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="leave_days[]" value="wednesday" id="leaveWednesday">
                                                <label class="form-check-label" for="leaveWednesday">Wednesday</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="leave_days[]" value="thursday" id="leaveThursday">
                                                <label class="form-check-label" for="leaveThursday">Thursday</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="leave_days[]" value="friday" id="leaveFriday">
                                                <label class="form-check-label" for="leaveFriday">Friday</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="leave_days[]" value="saturday" id="leaveSaturday">
                                                <label class="form-check-label" for="leaveSaturday">Saturday</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="leave_days[]" value="sunday" id="leaveSunday">
                                                <label class="form-check-label" for="leaveSunday">Sunday</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        
                    <!-- Calendar Preview Card -->
                    <div class="card border-0 mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-secondary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-eye text-secondary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Calendar Preview</h6>
                                    <small class="text-muted">Preview your roster configuration</small>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div id="rosterPreviewCalendar" style="height: 300px; background: #f8f9fa; border-radius: 8px; padding: 15px; border: 1px solid #dee2e6;">
                                        <div class="text-center text-muted">
                                            <i class="fas fa-calendar-alt fa-3x mb-3 opacity-50"></i>
                                            <p class="mb-0">Calendar preview will appear here</p>
                                            <small class="opacity-75">Configure your roster settings to see the preview</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Settings Card -->
                    <div class="card border-0 mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-dark bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-cog text-dark"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Additional Settings</h6>
                                    <small class="text-muted">Status and notes</small>
                                </div>
                            </div>
                            <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                        <select class="form-select" name="roster_status" id="rosterStatus">
                                            <option value="draft">Draft</option>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                        <label for="rosterStatus">Roster Status</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="roster_notes" id="rosterNotes" placeholder=" ">
                                        <label for="rosterNotes">Roster Notes</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                         <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-info" onclick="previewRoster()">
                        <i class="fas fa-eye me-1"></i>Preview
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Create Roster
                    </button>
                </div>
            </form>
        </div>
                            </div>
                        </div>
                        

<!-- Edit Team Roster Modal -->
<div class="modal fade" id="editRosterModal" tabindex="-1" aria-labelledby="editRosterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow-lg">
            <h4 class="modal-title fw-bold" id="editRosterModalLabel">
                <i class="fas fa-edit me-2"></i>Edit Team Roster
            </h4>
            <div class="modal-header bg-gradient-warning text-white">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editRosterForm">
                <input type="hidden" id="editRosterId" name="roster_id">
                <div class="modal-body p-4">
                    <!-- Team Selection Card -->
                    <div class="card border-0 mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-users text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Team Selection</h6>
                                    <small class="text-muted">Choose team for roster editing</small>
                                </div>
                            </div>
                            <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                        <select class="form-select" id="editRosterTeamId" name="team_id" required>
                                            <option value="">Select Team</option>
                                            @foreach($teams as $team)
                                                <option value="{{ $team->id }}">{{ $team->team_name }} ({{ $team->team_code }})</option>
                                            @endforeach
                                </select>
                                        <label for="editRosterTeamId" class="required-field">Team</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="editRosterName" name="roster_name" required placeholder=" ">
                                        <label for="editRosterName" class="required-field">Roster Name</label>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        
                    <!-- Period Selection Card -->
                    <div class="card border-0 mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-calendar-alt text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Period Selection</h6>
                                    <small class="text-muted">Define roster period and duration</small>
                                </div>
                            </div>
                            <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                        <select class="form-select" id="editRosterPeriod" name="roster_period" required>
                                            <option value="">Select Period</option>
                                    <option value="weekly">Weekly</option>
                                            <option value="monthly">Monthly</option>
                                </select>
                                        <label for="editRosterPeriod" class="required-field">Roster Period</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" id="editStartDate" name="start_date" required>
                                        <label for="editStartDate" class="required-field">Start Date</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" id="editEndDate" name="end_date" required>
                                        <label for="editEndDate" class="required-field">End Date</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" id="editMaxWorkingHours" name="max_working_hours" min="1" max="168" value="40" placeholder=" ">
                                        <label for="editMaxWorkingHours">Max Working Hours</label>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        
                    <!-- Working Days Configuration Card -->
                    <div class="card border-0 mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-clock text-info"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Working Days Configuration</h6>
                                    <small class="text-muted">Set working days and hours</small>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="time" class="form-control" id="editWorkStartTime" name="work_start_time" value="08:00" required>
                                        <label for="editWorkStartTime" class="required-field">Work Start Time</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="time" class="form-control" id="editWorkEndTime" name="work_end_time" value="17:00" required>
                                        <label for="editWorkEndTime" class="required-field">Work End Time</label>
                                    </div>
                                </div>
                        <div class="col-12">
                                    <label class="form-label">Working Days</label>
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="working_days[]" value="monday" id="editWorkingMonday">
                                                <label class="form-check-label" for="editWorkingMonday">Monday</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="working_days[]" value="tuesday" id="editWorkingTuesday">
                                                <label class="form-check-label" for="editWorkingTuesday">Tuesday</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="working_days[]" value="wednesday" id="editWorkingWednesday">
                                                <label class="form-check-label" for="editWorkingWednesday">Wednesday</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="working_days[]" value="thursday" id="editWorkingThursday">
                                                <label class="form-check-label" for="editWorkingThursday">Thursday</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="working_days[]" value="friday" id="editWorkingFriday">
                                                <label class="form-check-label" for="editWorkingFriday">Friday</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="working_days[]" value="saturday" id="editWorkingSaturday">
                                                <label class="form-check-label" for="editWorkingSaturday">Saturday</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="working_days[]" value="sunday" id="editWorkingSunday">
                                                <label class="form-check-label" for="editWorkingSunday">Sunday</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Leave Days Configuration Card -->
                    <div class="card border-0 mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-calendar-times text-warning"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Leave Days Configuration</h6>
                                    <small class="text-muted">Set leave days and reasons</small>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                            <div class="form-floating">
                                        <select class="form-select" id="editLeaveType" name="leave_type">
                                            <option value="">No Leave</option>
                                            <option value="vacation">Vacation</option>
                                            <option value="sick">Sick Leave</option>
                                            <option value="personal">Personal</option>
                                            <option value="holiday">Holiday</option>
                                            <option value="training">Training</option>
                                        </select>
                                        <label for="editLeaveType">Leave Type</label>
                            </div>
                        </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="editLeaveReason" name="leave_reason" placeholder=" ">
                                        <label for="editLeaveReason">Leave Reason</label>
                    </div>
                </div>
                                <div class="col-12">
                                    <label class="form-label">Leave Days</label>
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="leave_days[]" value="monday" id="editLeaveMonday">
                                                <label class="form-check-label" for="editLeaveMonday">Monday</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="leave_days[]" value="tuesday" id="editLeaveTuesday">
                                                <label class="form-check-label" for="editLeaveTuesday">Tuesday</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="leave_days[]" value="wednesday" id="editLeaveWednesday">
                                                <label class="form-check-label" for="editLeaveWednesday">Wednesday</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="leave_days[]" value="thursday" id="editLeaveThursday">
                                                <label class="form-check-label" for="editLeaveThursday">Thursday</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="leave_days[]" value="friday" id="editLeaveFriday">
                                                <label class="form-check-label" for="editLeaveFriday">Friday</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="leave_days[]" value="saturday" id="editLeaveSaturday">
                                                <label class="form-check-label" for="editLeaveSaturday">Saturday</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="leave_days[]" value="sunday" id="editLeaveSunday">
                                                <label class="form-check-label" for="editLeaveSunday">Sunday</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Calendar Preview Card -->
                    <div class="card border-0 mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-secondary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-eye text-secondary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Calendar Preview</h6>
                                    <small class="text-muted">Preview your roster configuration</small>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div id="editRosterPreviewCalendar" style="height: 300px; background: #f8f9fa; border-radius: 8px; padding: 15px; border: 1px solid #dee2e6;">
                                        <div class="text-center text-muted">
                                            <i class="fas fa-calendar-alt fa-3x mb-3 opacity-50"></i>
                                            <p class="mb-0">Calendar preview will appear here</p>
                                            <small class="opacity-75">Configure your roster settings to see the preview</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Settings Card -->
                    <div class="card border-0 mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-dark bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-cog text-dark"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Additional Settings</h6>
                                    <small class="text-muted">Status and notes</small>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="editRosterStatus" name="roster_status">
                                            <option value="draft">Draft</option>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                        <label for="editRosterStatus">Roster Status</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="editRosterNotes" name="roster_notes" placeholder=" ">
                                        <label for="editRosterNotes">Roster Notes</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-warning" onclick="updateRoster()">
                        <i class="fas fa-save me-1"></i>Update Roster
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Edit Team Roster Modal -->

<!-- Edit Assignment Modal -->
<div class="modal fade" id="editAssignmentModal" tabindex="-1" aria-labelledby="editAssignmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAssignmentModalLabel">Edit Assignment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editAssignmentForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="assignment_id" id="editAssignmentId">
                <div class="modal-body">
                    <!-- Same fields as assign modal with edit prefix -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="department" id="editDepartment" required>
                                    <option value="">Select Department</option>
                                    <option value="technical">Technical</option>
                                    <option value="operations">Operations</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="administration">Administration</option>
                                    <option value="customer-service">Customer Service</option>
                                </select>
                                <label for="editDepartment" class="required-field">Department</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="team" id="editTeam" required>
                                    <option value="">Select Team</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}">{{ $team->team_name }} ({{ $team->team_code }})</option>
                                    @endforeach
                                </select>
                                <label for="editTeam" class="required-field">Team</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="assignment_date" id="editAssignmentDate" required>
                                <label for="editAssignmentDate" class="required-field">Assignment Date</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="shift" id="editShift" required>
                                    <option value="">Select Shift</option>
                                    <option value="morning">Morning (8AM - 4PM)</option>
                                    <option value="afternoon">Afternoon (12PM - 8PM)</option>
                                    <option value="evening">Evening (4PM - 12AM)</option>
                                    <option value="night">Night (8PM - 6AM)</option>
                                    <option value="full-day">Full Day (8AM - 8PM)</option>
                                </select>
                                <label for="editShift" class="required-field">Shift</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="priority" id="editPriority">
                                    <option value="normal">Normal</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                                <label for="editPriority">Priority</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="status" id="editAssignmentStatus">
                                    <option value="scheduled">Scheduled</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="in-progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                </select>
                                <label for="editAssignmentStatus">Status</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="notes" id="editAssignmentNotes" placeholder=" " style="height: 100px"></textarea>
                                <label for="editAssignmentNotes">Assignment Notes</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Assignment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Team Modal -->
<div class="modal fade" id="addTeamModal" tabindex="-1" aria-labelledby="addTeamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTeamModalLabel">Add New Team</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addTeamForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="team_name" id="teamName" required placeholder=" ">
                                <label for="teamName" class="required-field">Team Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="department" id="teamDepartment" required>
                                    <option value="">Select Department</option>
                                    <option value="technical">Technical</option>
                                    <option value="operations">Operations</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="administration">Administration</option>
                                    <option value="customer-service">Customer Service</option>
                                </select>
                                <label for="teamDepartment" class="required-field">Department</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="team_lead" id="teamLead" placeholder=" ">
                                <label for="teamLead">Team Lead</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="team_size" id="teamSize" min="1" max="50" placeholder=" ">
                                <label for="teamSize">Team Size</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="shift_preference" id="shiftPreference">
                                    <option value="">No Preference</option>
                                    <option value="morning">Morning Shift</option>
                                    <option value="afternoon">Afternoon Shift</option>
                                    <option value="evening">Evening Shift</option>
                                    <option value="night">Night Shift</option>
                                    <option value="flexible">Flexible</option>
                                </select>
                                <label for="shiftPreference">Shift Preference</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="status" id="teamStatus">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="training">Training</option>
                                    <option value="temporary">Temporary</option>
                                </select>
                                <label for="teamStatus">Status</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control" name="contact_email" id="contactEmail" placeholder=" ">
                                <label for="contactEmail">Contact Email</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="tel" class="form-control" name="contact_phone" id="contactPhone" placeholder=" ">
                                <label for="contactPhone">Contact Phone</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="teamSkills" class="form-label">Team Skills/Specializations</label>
                            <select class="form-select" name="skills[]" id="teamSkills" multiple style="width: 100%;">
                                <option value="fiber-installation">Fiber Installation</option>
                                <option value="network-maintenance">Network Maintenance</option>
                                <option value="customer-service">Customer Service</option>
                                <option value="technical-support">Technical Support</option>
                                <option value="equipment-repair">Equipment Repair</option>
                                <option value="project-management">Project Management</option>
                                <option value="quality-assurance">Quality Assurance</option>
                                <option value="training">Training</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="description" id="teamDescription" placeholder=" " style="height: 100px"></textarea>
                                <label for="teamDescription">Team Description</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Team</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Upload Modal -->
<div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-labelledby="bulkUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkUploadModalLabel">Bulk Upload Teams</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="bulkUploadForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-2"></i>Upload Instructions</h6>
                                <ul class="mb-0 ps-3">
                                    <li>Download the template file below and fill in your team data</li>
                                    <li>Supported formats: Excel (.xlsx), CSV (.csv)</li>
                                    <li>Maximum file size: 10MB</li>
                                    <li>Duplicate team names will be updated with new information</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="card border-dashed">
                                <div class="card-body text-center py-4">
                                    <div class="mb-3">
                                        <i class="fas fa-download fa-2x text-primary mb-2"></i>
                                        <h6>Download Template</h6>
                                        <p class="text-muted mb-0">Get the template file with proper column headers</p>
                                    </div>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="/company/MasterTracker/team-roaster/template/excel" class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-file-excel me-1"></i>Excel Template
                                        </a>
                                        <a href="/company/MasterTracker/team-roaster/template/csv" class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-file-csv me-1"></i>CSV Template
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label for="bulkFile" class="form-label required-field">Upload File</label>
                            <div class="input-group">
                                <input type="file" class="form-control" name="bulk_file" id="bulkFile" 
                                       accept=".xlsx,.xls,.csv" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="clearFileInput()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="form-text">
                                <small>Accepted formats: .xlsx, .xls, .csv (Max: 10MB)</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="default_department" id="defaultDepartment">
                                    <option value="">Use File Data</option>
                                    <option value="technical">Technical</option>
                                    <option value="operations">Operations</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="administration">Administration</option>
                                    <option value="customer-service">Customer Service</option>
                                </select>
                                <label for="defaultDepartment">Default Department</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="default_status" id="defaultStatus">
                                    <option value="">Use File Data</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="training">Training</option>
                                    <option value="temporary">Temporary</option>
                                </select>
                                <label for="defaultStatus">Default Status</label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="updateExisting" name="update_existing" checked>
                                <label class="form-check-label" for="updateExisting">
                                    Update existing teams if team name matches
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="skipErrors" name="skip_errors" checked>
                                <label class="form-check-label" for="skipErrors">
                                    Skip rows with errors and continue processing
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-12" id="uploadProgress" style="display: none;">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" style="width: 0%"></div>
                            </div>
                            <small class="text-muted mt-1">Processing file...</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="uploadBtn">
                        <i class="fas fa-upload me-1"></i>Upload Teams
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Import Template Modal -->
<div class="modal fade" id="importTemplateModal" tabindex="-1" aria-labelledby="importTemplateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importTemplateModalLabel">Import Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Choose a template format to download:</p>
                <div class="d-grid gap-2">
                    <a href="/company/MasterTracker/team-roaster/template/excel" class="btn btn-outline-success">
                        <i class="fas fa-file-excel me-2"></i>Excel Template (.xlsx)
                    </a>
                    <a href="/company/MasterTracker/team-roaster/template/csv" class="btn btn-outline-info">
                        <i class="fas fa-file-csv me-2"></i>CSV Template (.csv)
                    </a>
                </div>
                <hr>
                <h6>Template Columns:</h6>
                <small class="text-muted">
                    <strong>Required:</strong> team_name, department<br>
                    <strong>Optional:</strong> team_lead, team_size, shift_preference, status, contact_email, contact_phone, skills, description
                </small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteAssignmentModal" tabindex="-1" aria-labelledby="deleteAssignmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAssignmentModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this team assignment?</p>
                <p><strong>Team:</strong> <span id="deleteTeamName"></span></p>
                <p><strong>Department:</strong> <span id="deleteDepartmentName"></span></p>
                <p><strong>Date:</strong> <span id="deleteAssignmentDate"></span></p>
                <p class="text-danger"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteAssignment">Delete Assignment</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables CDN -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
// Note: Edit functionality now uses direct modal approach instead of Laravel redirect

// Test function will be defined at the end of script section

// Test edit function availability
console.log('Edit function available:', typeof window.editRoster);
if (typeof window.editRoster === 'undefined') {
    console.error('editRoster function is not defined!');
}

// Test delete function availability
console.log('Delete function available:', typeof window.deleteRoster);
if (typeof window.deleteRoster === 'undefined') {
    console.error('deleteRoster function is not defined!');
}

// Test SweetAlert availability
console.log('SweetAlert available:', typeof Swal);
if (typeof Swal === 'undefined') {
    console.error('SweetAlert is not loaded!');
} else {
    console.log('SweetAlert is loaded and ready!');
}


let calendar;
let currentDate = new Date();
let currentView = 'calendar';

$(document).ready(function() {
    console.log('Document ready - starting initialization...');
    
    // Load initial data
    loadDashboardStats();
    updateLastUpdatedTime();
    initializeMonthSelector();
    initializeCalendar();
    
    // Initialize Select2
    $('#bulkDepartments, #bulkTeams, #teamSkills').select2({
        placeholder: 'Select items',
        allowClear: true
    });

    // View toggle handlers
    $('input[name="viewType"]').on('change', function() {
        currentView = $(this).val();
        toggleView();
    });

    // Repeat assignment checkbox
    $('#repeatAssignment').on('change', function() {
        $('#repeatOptions').toggle($(this).is(':checked'));
    });

    // Form submissions
    $('#addTeamForm').on('submit', function(e) {
        e.preventDefault();
        submitNewTeam(this);
    });

    // Create roster form handler is already initialized in the CSS section

    $('#bulkUploadForm').on('submit', function(e) {
        e.preventDefault();
        submitBulkUpload(this);
    });

    $('#assignTeamForm').on('submit', function(e) {
        e.preventDefault();
        submitAssignment(this, 'Team assigned successfully!');
    });


    $('#editAssignmentForm').on('submit', function(e) {
        e.preventDefault();
        let assignmentId = $('#editAssignmentId').val();
        updateAssignment(this, assignmentId);
    });

    // Delete confirmation
    $('#confirmDeleteAssignment').on('click', function() {
        let assignmentId = $(this).data('id');
        deleteAssignment(assignmentId);
    });

    // Department change handler
    $('#assignDepartment').on('change', function() {
        loadTeamsForDepartment($(this).val(), '#assignTeam');
    });

    $('#editDepartment').on('change', function() {
        loadTeamsForDepartment($(this).val(), '#editTeam');
    });
});

// Calendar initialization
function initializeCalendar() {
    const calendarEl = document.getElementById('calendar');
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: '',
            center: '',
            right: ''
        },
        height: 'auto',
        events: '/company/MasterTracker/team-roaster/calendar-events',
        eventClick: function(info) {
            editAssignment(info.event.id);
        },
        dateClick: function(info) {
            openQuickAssign(info.dateStr);
        },
        eventClassNames: function(arg) {
            return ['team-' + (arg.event.extendedProps.team_class || 'alpha')];
        },
        datesSet: function(dateInfo) {
            currentDate = dateInfo.start;
            updateCurrentMonthDisplay();
            loadDashboardStats();
        }
    });
    calendar.render();
}

// Month navigation functions
function initializeMonthSelector() {
    const monthSelect = $('#monthSelect');
    const currentYear = new Date().getFullYear();
    
    for (let year = currentYear - 1; year <= currentYear + 2; year++) {
        for (let month = 0; month < 12; month++) {
            const date = new Date(year, month, 1);
            const value = date.toISOString().substring(0, 7);
            const text = date.toLocaleDateString('en-US', { year: 'numeric', month: 'long' });
            monthSelect.append(`<option value="${value}">${text}</option>`);
        }
    }
    
    // Set current month
    const currentMonth = new Date().toISOString().substring(0, 7);
    monthSelect.val(currentMonth);
    updateCurrentMonthDisplay();
}

function updateCurrentMonthDisplay() {
    const monthText = currentDate.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long' 
    });
    $('#currentMonth').text(monthText);
}

function previousMonth() {
    if (currentView === 'calendar') {
        calendar.prev();
    } else {
        currentDate.setMonth(currentDate.getMonth() - 1);
        updateCurrentMonthDisplay();
        loadTableView();
    }
}

function nextMonth() {
    if (currentView === 'calendar') {
        calendar.next();
    } else {
        currentDate.setMonth(currentDate.getMonth() + 1);
        updateCurrentMonthDisplay();
        loadTableView();
    }
}

function jumpToMonth(monthValue) {
    const [year, month] = monthValue.split('-');
    const newDate = new Date(year, month - 1, 1);
    
    if (currentView === 'calendar') {
        calendar.gotoDate(newDate);
    } else {
        currentDate = newDate;
        updateCurrentMonthDisplay();
        loadTableView();
    }
}

function goToCurrentMonth() {
    const today = new Date();
    if (currentView === 'calendar') {
        calendar.gotoDate(today);
    } else {
        currentDate = today;
        updateCurrentMonthDisplay();
        loadTableView();
    }
}

// View toggle functions
function toggleView() {
    if (currentView === 'calendar') {
        $('#calendarViewContainer').show();
        $('#tableViewContainer').hide();
    } else {
        $('#calendarViewContainer').hide();
        $('#tableViewContainer').show();
        loadTableView();
    }
}


// Table view functions
function loadTableView() {
    $.ajax({
        url: '/company/MasterTracker/team-roaster/assignments/table',
        method: 'GET',
        data: {
            month: currentDate.getFullYear() + '-' + String(currentDate.getMonth() + 1).padStart(2, '0')
        },
        success: function(response) {
            if (response.status === 'success') {
                generateDepartmentTables(response.data);
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load table view.'
            });
        }
    });
}

function generateDepartmentTables(data) {
    let tablesHtml = '';
    
    Object.keys(data).forEach(department => {
        const departmentData = data[department];
        tablesHtml += `
            <div class="department-section">
                <div class="department-header">
                    <i class="fas fa-building me-2"></i>${department.charAt(0).toUpperCase() + department.slice(1)} Department
                    <span class="float-end">${departmentData.assignments?.length || 0} assignments</span>
                </div>
                <div class="department-content">
                    ${generateAssignmentTable(departmentData.assignments || [])}
                </div>
            </div>
        `;
    });
    
    $('#departmentTables').html(tablesHtml);
}

function generateAssignmentTable(assignments) {
    if (assignments.length === 0) {
        return '<p class="text-muted">No assignments for this department this month.</p>';
    }
    
    let tableHtml = `
        <div class="table-responsive">
            <table class="table assignment-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Team</th>
                        <th>Shift</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    assignments.forEach(assignment => {
        tableHtml += `
            <tr>
                <td>${new Date(assignment.assignment_date).toLocaleDateString()}</td>
                <td>
                    <span class="team-assignment assigned">${assignment.team_name}</span>
                </td>
                <td>${assignment.shift}</td>
                <td><span class="badge bg-${getStatusColor(assignment.status)}">${assignment.status}</span></td>
                <td><span class="badge bg-${getPriorityColor(assignment.priority)}">${assignment.priority}</span></td>
                <td>
                    <div class="d-flex gap-1">
                        <button type="button" class="btn btn-sm btn-warning action-btn" 
                                onclick="editAssignment(${assignment.id})" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger action-btn" 
                                onclick="deleteAssignmentConfirm(${assignment.id}, '${assignment.team_name}', '${assignment.department}', '${assignment.assignment_date}')" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    tableHtml += '</tbody></table></div>';
    return tableHtml;
}

// Team management functions
function submitNewTeam(form) {
    let formData = new FormData(form);
    
    $.ajax({
        url: '/company/MasterTracker/team-roaster/teams',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Team created successfully!',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#addTeamModal').modal('hide');
                form.reset();
                $('#teamSkills').val(null).trigger('change'); // Clear Select2
                loadTeamOptions(); // Refresh team options
                loadDashboardStats(); // Refresh stats
            }
        },
        error: function(xhr) {
            handleFormError(xhr);
        }
    });
}

function submitBulkUpload(form) {
    let formData = new FormData(form);
    
    // Show progress
    $('#uploadProgress').show();
    $('#uploadBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Processing...');
    
    $.ajax({
        url: '/company/MasterTracker/team-roaster/teams/bulk-upload',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        xhr: function() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt) {
                if (evt.lengthComputable) {
                    var percentComplete = evt.loaded / evt.total * 100;
                    $('.progress-bar').css('width', percentComplete + '%');
                }
            }, false);
            return xhr;
        },
        success: function(response) {
            if (response.status === 'success') {
                let message = `Successfully processed ${response.data.processed} teams`;
                if (response.data.errors && response.data.errors.length > 0) {
                    message += ` (${response.data.errors.length} errors skipped)`;
                }
                
                Swal.fire({
                    icon: 'success',
                    title: 'Upload Complete!',
                    html: `
                        <p>${message}</p>
                        ${response.data.errors && response.data.errors.length > 0 ? 
                            `<small class="text-muted">Check console for error details</small>` : ''
                        }
                    `,
                    showConfirmButton: true
                });
                
                if (response.data.errors && response.data.errors.length > 0) {
                    console.log('Upload errors:', response.data.errors);
                }
                
                $('#bulkUploadModal').modal('hide');
                form.reset();
                clearFileInput();
                loadTeamOptions();
                loadDashboardStats();
            }
        },
        error: function(xhr) {
            handleFormError(xhr);
        },
        complete: function() {
            $('#uploadProgress').hide();
            $('.progress-bar').css('width', '0%');
            $('#uploadBtn').prop('disabled', false).html('<i class="fas fa-upload me-1"></i>Upload Teams');
        }
    });
}

function clearFileInput() {
    $('#bulkFile').val('');
}


// Assignment functions
function openQuickAssign(date) {
    $('#assignmentDate').val(date);
    $('#assignTeamModal').modal('show');
}

function submitAssignment(form, successMessage) {
    let formData = new FormData(form);
    
    $.ajax({
        url: '/company/MasterTracker/team-roaster/assignments',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: successMessage,
                    showConfirmButton: false,
                    timer: 1500
                });
                $(form).closest('.modal').modal('hide');
                form.reset();
                refreshViews();
            }
        },
        error: function(xhr) {
            handleFormError(xhr);
        }
    });
}

function submitBulkAssignment(form) {
    let formData = new FormData(form);
    
    $.ajax({
        url: '/company/MasterTracker/team-roaster/assignments/bulk',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: `${response.data.count} assignments created successfully!`,
                    showConfirmButton: false,
                    timer: 2000
                });
                $('#bulkAssignModal').modal('hide');
                form.reset();
                refreshViews();
            }
        },
        error: function(xhr) {
            handleFormError(xhr);
        }
    });
}

function editAssignment(assignmentId) {
    $.ajax({
        url: `/company/MasterTracker/team-roaster/assignments/${assignmentId}/edit`,
        method: 'GET',
        success: function(response) {
            if (response.status === 'success') {
                const assignment = response.data;
                populateEditForm(assignment);
                $('#editAssignmentModal').modal('show');
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load assignment details.'
            });
        }
    });
}

function populateEditForm(assignment) {
    $('#editAssignmentId').val(assignment.id);
    $('#editDepartment').val(assignment.department);
    $('#editAssignmentDate').val(assignment.assignment_date);
    $('#editShift').val(assignment.shift);
    $('#editPriority').val(assignment.priority);
    $('#editAssignmentStatus').val(assignment.status);
    $('#editAssignmentNotes').val(assignment.notes);
    
    // Load teams for department and then set team
    loadTeamsForDepartment(assignment.department, '#editTeam', assignment.team_id);
}

function updateAssignment(form, assignmentId) {
    let formData = new FormData(form);
    
    $.ajax({
        url: `/company/MasterTracker/team-roaster/assignments/${assignmentId}`,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: 'Assignment updated successfully!',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#editAssignmentModal').modal('hide');
                refreshViews();
            }
        },
        error: function(xhr) {
            handleFormError(xhr);
        }
    });
}

function deleteAssignmentConfirm(id, teamName, department, date) {
    $('#deleteTeamName').text(teamName);
    $('#deleteDepartmentName').text(department);
    $('#deleteAssignmentDate').text(new Date(date).toLocaleDateString());
    $('#confirmDeleteAssignment').data('id', id);
    $('#deleteAssignmentModal').modal('show');
}

function deleteAssignment(assignmentId) {
    $.ajax({
        url: `/company/MasterTracker/team-roaster/assignments/${assignmentId}`,
        method: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: 'Assignment deleted successfully!',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#deleteAssignmentModal').modal('hide');
                refreshViews();
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to delete assignment.'
            });
        }
    });
}

// Helper functions
function loadDashboardStats() {
    $.ajax({
        url: '/company/MasterTracker/team-roaster/stats',
        method: 'GET',
        success: function(response) {
            if (response.status === 'success') {
                const stats = response.data;
                $('#totalTeams').text(stats.total_rosters || 0);
                $('#totalDepartments').text(stats.active_rosters || 0);
                $('#monthlyAssignments').text(stats.draft_rosters || 0);
                $('#coveragePercentage').text(stats.inactive_rosters || 0);
            }
        }
    });
}

// Teams are now populated directly in the HTML using Laravel Blade templating
// No need for JavaScript to populate dropdowns

// Test function now defined as global function at end of script section

// Test function to check if CSRF token is available
function testCSRFToken() {
    const token = document.querySelector('meta[name="csrf-token"]');
    console.log('CSRF Token:', token ? token.content : 'NOT FOUND');
    return token ? token.content : null;
}

// Test function to check form data
function testFormData() {
    const form = document.getElementById('createRosterForm');
    if (form) {
        const formData = new FormData(form);
        console.log('Form data:');
        for (let [key, value] of formData.entries()) {
            console.log(key, ':', value);
        }
    } else {
        console.log('Form not found!');
    }
}

function refreshViews() {
    loadDashboardStats();
    if (currentView === 'calendar') {
        calendar.refetchEvents();
    } else {
        loadTableView();
    }
    updateLastUpdatedTime();
}

function updateLastUpdatedTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-US', { 
        hour12: true, 
        hour: '2-digit', 
        minute: '2-digit' 
    });
    $('#lastUpdated').text(timeString);
}

function handleFormError(xhr) {
    const errors = xhr.responseJSON?.errors || {};
    let errorMessage = 'Please check the form for errors.';
    
    if (Object.keys(errors).length > 0) {
        errorMessage = Object.values(errors).flat().join('<br>');
    }
    
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        html: errorMessage
    });
}

function getStatusColor(status) {
    switch(status) {
        case 'completed': return 'success';
        case 'in-progress': return 'primary';
        case 'confirmed': return 'info';
        case 'scheduled': return 'warning';
        default: return 'secondary';
    }
}

function getPriorityColor(priority) {
    switch(priority) {
        case 'urgent': return 'danger';
        case 'high': return 'warning';
        case 'normal': return 'success';
        default: return 'secondary';
    }
}

// Global functions

window.exportRoaster = function() {
    const month = currentDate.getFullYear() + '-' + String(currentDate.getMonth() + 1).padStart(2, '0');
    window.open(`/company/MasterTracker/team-roaster/export?month=${month}`, '_blank');
};

// Global function for testing SweetAlert moved to CSS section for immediate availability

// Roster management functions
// submitNewRoster function removed - now using fetch API directly in form event listener

// Duplicate functions removed - now using the ones defined earlier in the file

// View Toggle Functions - moved to early JavaScript section

// Initialize DataTable for roster data
function initializeRosterDataTable() {
    // Check if DataTables is available
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('#rosterDataTable').DataTable({
            responsive: true,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[3, 'desc']], // Sort by start date descending
            columnDefs: [
                { orderable: false, targets: [9] }, // Actions column
                { className: "text-center", targets: [6, 7] }, // Working hours and status columns
            ],
            language: {
                search: "Search rosters:",
                lengthMenu: "Show _MENU_ rosters per page",
                info: "Showing _START_ to _END_ of _TOTAL_ rosters",
                infoEmpty: "No rosters found",
                infoFiltered: "(filtered from _MAX_ total rosters)",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            },
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
        });
    } else {
        console.warn('DataTables library not loaded yet');
    }
}

// Refresh calendar function
function refreshCalendar() {
    initializeCalendarView();
}

// Roster Action Functions
function viewRosterDetails(rosterId) {
    // Fetch roster details and show in modal
    fetch(`/company/MasterTracker/team-roaster/${rosterId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showRosterDetailsModal(data.roster);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load roster details'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load roster details'
            });
        });
}

function editRoster(rosterId) {
    // Fetch roster data and populate edit form
    fetch(`/company/MasterTracker/team-roaster/${rosterId}/edit`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                populateEditForm(data.roster);
                // Show edit modal (you'll need to create this)
                $('#editRosterModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load roster for editing'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load roster for editing'
            });
        });
}


function refreshRosterTable() {
    location.reload();
}

function exportRosterData() {
    // Export roster data to CSV/Excel
    window.open('/company/MasterTracker/team-roaster/export', '_blank');
}

function showRosterDetailsModal(roster) {
    const workingDays = roster.working_days ? (Array.isArray(roster.working_days) ? roster.working_days : JSON.parse(roster.working_days)) : [];
    const leaveDays = roster.leave_days ? (Array.isArray(roster.leave_days) ? roster.leave_days : JSON.parse(roster.leave_days)) : [];
    
    const detailsHTML = `
        <div class="roster-details-modal">
            <div class="details-header mb-4">
                <h4 class="text-primary mb-1">
                    <i class="fas fa-calendar-check me-2"></i>${roster.roster_name}
                </h4>
                <p class="text-muted mb-0">${roster.team?.team_name || 'N/A'} • ${roster.roster_period.charAt(0).toUpperCase() + roster.roster_period.slice(1)} Roster</p>
            </div>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="detail-item">
                        <label class="fw-bold">Start Date:</label>
                        <p class="mb-0">${new Date(roster.start_date).toLocaleDateString()}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-item">
                        <label class="fw-bold">End Date:</label>
                        <p class="mb-0">${new Date(roster.end_date).toLocaleDateString()}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-item">
                        <label class="fw-bold">Working Hours:</label>
                        <p class="mb-0">${roster.work_start_time} - ${roster.work_end_time}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-item">
                        <label class="fw-bold">Max Hours:</label>
                        <p class="mb-0">${roster.max_working_hours} hours</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-item">
                        <label class="fw-bold">Working Days:</label>
                        <div class="d-flex flex-wrap gap-1">
                            ${workingDays.map(day => `<span class="badge bg-success">${day.charAt(0).toUpperCase() + day.slice(1)}</span>`).join('')}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-item">
                        <label class="fw-bold">Status:</label>
                        <span class="badge bg-${roster.roster_status === 'active' ? 'success' : roster.roster_status === 'draft' ? 'warning' : 'danger'}">${roster.roster_status.charAt(0).toUpperCase() + roster.roster_status.slice(1)}</span>
                    </div>
                </div>
                ${roster.roster_notes ? `
                    <div class="col-12">
                        <div class="detail-item">
                            <label class="fw-bold">Notes:</label>
                            <p class="mb-0">${roster.roster_notes}</p>
                        </div>
                    </div>
                ` : ''}
            </div>
        </div>
    `;
    
    Swal.fire({
        title: 'Roster Details',
        html: detailsHTML,
        width: '600px',
        showConfirmButton: true,
        confirmButtonText: 'Close',
        confirmButtonColor: '#3085d6'
    });
}

function resetRosterForm() {
    $('#createRosterForm')[0].reset();
    $('#rosterPreviewCalendar').html(`
        <div class="text-center text-muted">
            <i class="fas fa-calendar-alt fa-3x mb-3 opacity-50"></i>
            <p class="mb-0">Calendar preview will appear here</p>
            <small class="opacity-75">Configure your roster settings to see the preview</small>
        </div>
    `);
}

// Working/Leave day conflict prevention
$('input[name="working_days[]"]').on('change', function() {
    const dayValue = $(this).val();
    const leaveCheckbox = $(`input[name="leave_days[]"][value="${dayValue}"]`);
    
    if ($(this).is(':checked') && leaveCheckbox.is(':checked')) {
        leaveCheckbox.prop('checked', false);
        Swal.fire({
            icon: 'warning',
            title: 'Conflict Detected',
            text: `${dayValue.charAt(0).toUpperCase() + dayValue.slice(1)} cannot be both a working day and leave day.`,
            showConfirmButton: false,
            timer: 2000
        });
    }
});

$('input[name="leave_days[]"]').on('change', function() {
    const dayValue = $(this).val();
    const workingCheckbox = $(`input[name="working_days[]"][value="${dayValue}"]`);
    
    if ($(this).is(':checked') && workingCheckbox.is(':checked')) {
        workingCheckbox.prop('checked', false);
        Swal.fire({
            icon: 'warning',
            title: 'Conflict Detected',
            text: `${dayValue.charAt(0).toUpperCase() + dayValue.slice(1)} cannot be both a working day and leave day.`,
            showConfirmButton: false,
            timer: 2000
        });
    }
});

// Period type change handler
$('#rosterPeriod').on('change', function() {
    const period = $(this).val();
    const startDate = $('#rosterStartDate').val();
    
    if (startDate && period) {
        const start = new Date(startDate);
        let endDate = new Date(start);
        
        if (period === 'weekly') {
            endDate.setDate(start.getDate() + 6);
        } else if (period === 'monthly') {
            endDate.setMonth(start.getMonth() + 1);
            endDate.setDate(start.getDate() - 1);
        }
        
        $('#rosterEndDate').val(endDate.toISOString().split('T')[0]);
    }
});

// Auto-refresh every 5 minutes
setInterval(function() {
    loadDashboardStats();
    updateLastUpdatedTime();
}, 300000);
</script>

@endpush

