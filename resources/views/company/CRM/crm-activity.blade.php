<style>
    .timeline {
        position: relative;
        padding: 20px 0;
    }

    .timeline-item {
        padding-left: 40px;
        position: relative;
        margin-bottom: 20px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-date {
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 5px;
    }

    .timeline-content {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 4px;
    }

    .deal-status-card {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    .stat-card {
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }
    /* Dashboard Cards */
.stat-card {
    background: #fff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 20px;
    height: 100%;
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    font-size: 24px;
    margin-bottom: 10px;
}

.sales .stat-icon { color: #6777ef; }
.conversion .stat-icon { color: #17a2b8; }
.deal .stat-icon { color: #ffc107; }
.pipeline .stat-icon { color: #28a745; }

.stat-title {
    font-size: 14px;
    color: #6c757d;
    margin-bottom: 5px;
    font-weight: 500;
}

.stat-value-sale {
    font-size: 24px;
    font-weight: 600;
    color: white;
    margin-bottom: 5px;
}

.stat-change {
    font-size: 12px;
    color: #6c757d;
}

.text-success { color: #28a745; }
.text-danger { color: #dc3545; }
.text-muted { color: #6c757d; }

/* Filter Section */
.dashboard-filter {
    margin-bottom: 20px;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.dashboard-filter .card-body {
    padding: 15px;
}

.stat-card {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.stat-icon {
    font-size: 24px;
    margin-bottom: 15px;
    display: inline-block;
    padding: 15px;
    border-radius: 50%;
    color: #fff;
}

.stat-card.tasks .stat-icon {
    background-color: #3b5de7;
}

.stat-card.calls .stat-icon {
    background-color: #45cb85;
}

.stat-card.meetings .stat-icon {
    background-color: #17a2b8;
}

.stat-card.events .stat-icon {
    background-color: #ffc107;
}

.stat-title {
    font-size: 14px;
    color: #6c757d;
    margin-bottom: 5px;
    font-weight: 500;
}

.stat-value {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 15px;
    color: #495057;
}

.stat-progress {
    margin-top: 10px;
}

.stat-progress small {
    font-size: 12px;
    color: #6c757d;
    display: block;
    margin-top: 5px;
}
#small{
    color: white !important;
}
.selected-contacts-container {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin-top: 10px;
    min-height: 20px;
}
.contact-tag {
    background-color: #e9ecef;
    padding: 5px 10px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    font-size: 14px;
}
.contact-tag-remove {
    margin-left: 5px;
    cursor: pointer;
    color: #6c757d;
}
.contact-tag-remove:hover {
    color: #dc3545;
}
</style>


<div class="row">
    <!-- Total Sales Card -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card sales">
            <i class="stat-icon fas fa-chart-line"></i>
            <div class="stat-title">Task</div>
            <div class="stat-value-sale" id="taskCount">0</div>

            <div class="stat-progress">
                {{-- <div class="progress" style="height: 5px;">
                    <div id="taskProgress" class="progress-bar bg-primary" style="width: 0%"></div>
                </div>
                <small id="small">Completion: <span id="taskCompletionRate">0</span>%</small> --}}
            </div>
        </div>
    </div>

    <!-- Conversion Rate Card -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card conversion">
            <i class="stat-icon fas fa-percent"></i>
            <div class="stat-title">Calls</div>
            <div class="stat-value-sale" id="callCount">0</div>
            <div class="stat-progress">
                {{-- <div class="progress" style="height: 5px;">
                    <div id="callProgress" class="progress-bar bg-success" style="width: 0%"></div>
                </div>
                <small id="small">Completion: <span id="callCompletionRate">0</span>%</small> --}}
            </div>
        </div>
    </div>

    <!-- Average Deal Size Card -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card deal">
            <i class="stat-icon fas fa-dollar-sign"></i>
            <div class="stat-title">Meeting</div>
            <div class="stat-value-sale" id="meetingCount">0</div>
            <div class="stat-progress">
                {{-- <div class="progress" style="height: 5px;">
                    <div id="meetingProgress" class="progress-bar bg-info" style="width: 0%"></div>
                </div>
                <small id="small">Completion: <span id="meetingCompletionRate">0</span>%</small> --}}
            </div>
        </div>
    </div>

    <!-- Pipeline Value Card -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card pipeline">
            <i class="stat-icon fas fa-filter"></i>
            <div class="stat-title">Event</div>
            <div class="stat-value-sale" id="eventCount">0</div>
            <div class="stat-progress">
                {{-- <div class="progress" style="height: 5px;">
                    <div id="eventProgress" class="progress-bar bg-info" style="width: 0%"></div>
                </div> --}}
                {{-- <small id="smal l">Completion: <span id="eventCompletionRate">0</span>%</small> --}}
            </div>
        </div>
    </div>
</div>




<div class="d-flex justify-content-end mb-3 my-3">
    <div class="btn-group">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
            <i class="fas fa-tasks me-1"></i> New Task
        </button>
        <button type="button" class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#scheduleCallModal">
            <i class="fas fa-phone-alt me-1"></i> Schedule Call
        </button>
        <button type="button" class="btn btn-info ms-2" data-bs-toggle="modal" data-bs-target="#scheduleMeetingModal">
            <i class="fas fa-video me-1"></i> Schedule Meeting
        </button>
        <button type="button" class="btn btn-secondary ms-2" data-bs-toggle="modal" data-bs-target="#addEventModal">
            <i class="fas fa-calendar me-1"></i> Add Event
        </button>
    </div>
</div>


<div class="row">



    <!-- Recent Activities -->
    <div class="col-12 mt-4">
        <div class="card crm-card">
            <div class="card-body">
             
                <div class="d-flex justify-content-between align-items-center mb-3 ">
                    <h4 class="header-title mb-0">Recent Activities</h4>
                    
                    <div class="d-flex align-items-center gap-2">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="searchActivities" placeholder="Search Activitiess...">
                            <label for="searchActivity">Search Activity</label>
                        </div>
                        
                        <div class="form-floating">
                            <select class="form-select col-md-6" id="filterActivities" aria-label="Filter Activities">
                                <option value="">All Activities</option>
                                <option value="task">Task</option>
                                <option value="call">Call</option>
                                <option value="meeting">Meeting</option>
                                <option value="event">Event</option>
                            </select>
                            <label for="filterActivities">Filter Activities</label>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-centered table-hover" id="activities-table">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">No.</th>
                                <th>Type</th>
                                <th>Title</th>
                                <th>Related To / Contact(s) / participant(s)</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                        </tbody>
                    </table>

                    <nav aria-label="Page navigation example">
                        <ul class="pagination" id="activities-pagination">
                    
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="addTaskModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addTaskForm" action="{{ route('company.crm.activities.store') }}" method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="type" value="task">
                    
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="taskTitle" name="title" required placeholder="Enter task title">
                            <label for="taskTitle">Task Title</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-floating">
                            <select class="form-select" id="relatedToName" name="relatedToName">
                                <!-- Options will be loaded here -->
                            </select>
                            <label for="relatedToName">Related To</label>
                        </div>
                        {{-- <div class="selected-contacts-container" id="selectedContacts"></div> --}}
                        <input type="hidden" id="relatedTo" name="related_to" value="">
                        <input type="hidden" id="relatedToType" name="related_to_type" value="contact">
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="dueDate" name="start_date" required>
                                <label for="dueDate">Due Date</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" id="priority" name="priority" required>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                                <label for="priority">Priority</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Description"></textarea>
                            <label for="description">Description</label>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="addTask">Add Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Call Modal -->
<div class="modal fade" id="scheduleCallModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Call</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="scheduleCallForm" action="{{ route('company.crm.activities.store') }}" method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="type" value="call">
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="callTitle" name="callTitle" required>
                            <label for="callTitle">Call Title</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <select class="form-select" id="contact" name="contact">
                                <option value="">Select contact...</option>
                                <!-- Options will be loaded here -->
                            </select>
                            <label for="contact">Contact</label>
                            <div class="selected-contacts-container" id="selectedContact"></div>
                            <input type="hidden" id="contact_values" name="contact_values" value="">
                        </div>
                        {{-- <div class="selected-contacts-container" id="selectedContact"></div> --}}
                        {{-- <input type="hidden" id="contact_values" name="contact_values" value=""> --}}
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="datetime-local" class="form-control" id="callDate"  name="callDate" required>
                                <label for="callDate">Call Date & Time</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" id="callType" name="callType">
                                    <option value="phone">Phone Call</option>
                                    <option value="video">Video Call</option>
                                </select>
                                <label for="callType">Call Type</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" id="callNotes" name="callNotes" rows="3"></textarea>
                            <label for="callNotes">Notes</label>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="schedulecall">Schedule Call</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Meeting Modal -->
<div class="modal fade" id="scheduleMeetingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Meeting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="scheduleMeetingForm" action="{{ route('company.crm.activities.store') }}" method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="type" value="meeting">
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="meetingTitle" name="meetingTitle"  required>
                            <label for="meetingTitle">Meeting Title</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <select class="form-select" id="meetingParticipants" name="meetingParticipants">
                                <option value="">Select Participants</option>
                              
                            </select>
                            <label for="meetingParticipants">Participants</label>
                            <div class="selected-contacts-container" id="selectedMeetingParticipants"></div>
                            <input type="hidden" id="meetingParticipantsValues" name="meeting_participants" value="">

                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="datetime-local" class="form-control" id="meetingDate" name="meetingDate" required>
                                <label for="meetingDate">Meeting Date & Time</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="meetingDuration" name="meetingDuration" required>
                                <label for="meetingDuration">Duration (minutes)</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" id="meetingAgenda" name="meetingAgenda" rows="3"></textarea>
                            <label for="meetingAgenda">Agenda</label>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="schedulemeeting">Schedule Meeting</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addEventForm" action="{{ route('company.crm.activities.store') }}" method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="type" value="event">
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="eventName" name="eventName" required>
                            <label for="eventName">Event Name</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <select class="form-select" id="eventType" name="eventType">
                                <option value="">Select Event Type</option>
                                <option value="meeting">Meeting</option>
                                <option value="call">Call</option>
                                <option value="task">Task</option>
                                <option value="other">Other</option>
                            </select>
                            <label for="eventType">Event Type</label>
                        </div>
                    </div>

                    <!-- Add this to your Event modal form, after the Event Type field -->
    <div class="mb-3">
        <div class="form-floating">
            <select class="form-select" id="eventParticipants" name="eventParticipants">
                <option value="">Select Participants...</option>
            </select>
            <label for="eventParticipants">Participants</label>
        </div>
        <div class="selected-contacts-container" id="selectedEventParticipants"></div>
        <input type="hidden" id="eventParticipantsValues" name="event_participants" value="">
    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="datetime-local" class="form-control" id="eventStart" name="eventStart" required>
                                <label for="eventStart">Start Date & Time</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="datetime-local" class="form-control" id="eventEnd" name="eventEnd" required>
                                <label for="eventEnd">End Date & Time</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" id="eventDescription" name="eventDescription" rows="3"></textarea>
                            <label for="eventDescription">Description</label>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="addnewevent">Add Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>











<div class="modal fade" id="editTaskModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editTaskForm" action="{{ route('company.crm.activities.update', ['activity' => ':id']) }}" method="POST" class="needs-validation" novalidate>
                    @method('PUT')
                    <input type="hidden" name="id" id="editTaskId">
                    <input type="hidden" name="type" value="task">
                    
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="editTaskTitle" name="title" required placeholder="Enter task title">
                            <label for="editTaskTitle">Task Title</label>
                        </div>
                    </div>

                 

                    <div class="mb-3">
                        <div class="form-floating">
                            <select class="form-select" id="editRelatedToName" name="editRelatedToName">
                                <!-- Options will be loaded here -->
                            </select>
                            <label for="editRelatedToName">Related To</label>
                        </div>
                        <div class="selected-contacts-container" id="selectededitRelatedToName"></div>
                        <input type="hidden" id="editRelatedTo" name="related_to" value="">
                        <input type="hidden" id="edit_related_to_type" name="edit_related_to_type" value="contact">
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="editDueDate" name="start_date" required>
                                <label for="editDueDate">Due Date</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" id="editPriority" name="priority" required>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                                <label for="editPriority">Priority</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" id="editDescription" name="description" rows="3" placeholder="Description"></textarea>
                            <label for="editDescription">Description</label>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="updateTask">Update Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="editCallModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Call</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editCallForm" action="{{ route('company.crm.activities.update', ['activity' => ':id']) }}" method="POST" class="needs-validation" novalidate>
                    @method('PUT')
                    <input type="hidden" name="id" id="editCallId">
                    <input type="hidden" name="type" value="call">
                    
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="editCallTitle" name="title" required>
                            <label for="editCallTitle">Call Title</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <select class="form-select" id="editContact">
                                <option value="">Select contact...</option>
                                <!-- Options will be loaded dynamically -->
                            </select>
                            <label for="editContact">Contact</label>
                        </div>
                        <div class="selected-contacts-container" id="selectededitContact"></div>
                        <input type="hidden" id="editContactValues" name="contact" value="">
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="datetime-local" class="form-control" id="editCallDate" name="start_date" required>
                                <label for="editCallDate">Call Date & Time</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" id="editCallType" name="call_event_type">
                                    <option value="phone">Phone Call</option>
                                    <option value="video">Video Call</option>
                                </select>
                                <label for="editCallType">Call Type</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" id="editCallNotes" name="description" rows="3"></textarea>
                            <label for="editCallNotes">Notes</label>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="updateCall">Update Call</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="editMeetingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Meeting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editMeetingForm" action="{{ route('company.crm.activities.update', ['activity' => ':id']) }}" method="POST" class="needs-validation" novalidate>
                    @method('PUT')
                    <input type="hidden" name="id" id="editMeetingId">
                    <input type="hidden" name="type" value="meeting">
                    
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="editMeetingTitle" name="title" required>
                            <label for="editMeetingTitle">Meeting Title</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <select class="form-select" id="editMeetingParticipants">
                                <option value="">Select participants...</option>
                                <!-- Options will be loaded dynamically -->
                            </select>
                            <label for="editMeetingParticipants">Participants</label>
                        </div>
                        <div class="selected-contacts-container" id="selectededitMeetingParticipants"></div>
                        <input type="hidden" id="editMeetingParticipantsValues" name="participants" value="">
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="datetime-local" class="form-control" id="editMeetingDate" name="start_date" required>
                                <label for="editMeetingDate">Meeting Date & Time</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="editMeetingDuration" name="duration" required>
                                <label for="editMeetingDuration">Duration (minutes)</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" id="editMeetingAgenda" name="description" rows="3"></textarea>
                            <label for="editMeetingAgenda">Agenda</label>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="updateMeeting">Update Meeting</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="editEventModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editEventForm" action="{{ route('company.crm.activities.update', ['activity' => ':id']) }}" method="POST" class="needs-validation" novalidate>
                    @method('PUT')
                    <input type="hidden" name="id" id="editEventId">
                    <input type="hidden" name="type" value="event">
                    
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="editEventName" name="title" required>
                            <label for="editEventName">Event Name</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <select class="form-select" id="editEventType" name="call_event_type">
                                <option value="">Select Event Type</option>
                                <option value="meeting">Meeting</option>
                                <option value="call">Call</option>
                                <option value="task">Task</option>
                                <option value="other">Other</option>
                            </select>
                            <label for="editEventType">Event Type</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-floating">
                            <select class="form-select" id="editEventParticipants">
                                <option value="">Select participants...</option>
                                <!-- Options will be loaded dynamically -->
                            </select>
                            <label for="editEventParticipants">Participants</label>
                        </div>
                        <div class="selected-contacts-container" id="selectededitEventParticipants"></div>
                        <input type="text" id="editEventParticipantsValues" name="event_participants" value="">
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="datetime-local" class="form-control" id="editEventStart" name="start_date" required>
                                <label for="editEventStart">Start Date & Time</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="datetime-local" class="form-control" id="editEventEnd" name="end_date">
                                <label for="editEventEnd">End Date & Time</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" id="editEventDescription" name="description" rows="3"></textarea>
                            <label for="editEventDescription">Description</label>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="updateEvent">Update Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




<!-- Add custom styles -->
<style>
    /* Activity Calendar Styles */
    #activityCalendar {
        background: #ffffff;
        border-radius: 0.5rem;
        padding: 1rem;
    }

    /* Activity Stats Styles */
    .activity-stats {
        padding: 1rem 0;
    }

    .activity-stat-item {
        padding: 0.5rem 0;
    }

    .stat-label {
        color: #6c757d;
        font-size: 0.875rem;
    }

    .stat-value {
        font-weight: 600;
        font-size: 1.25rem;
    }

    /* Table Styles */
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    .table td {
        vertical-align: middle;
    }

    /* Modal Styles */
    .modal-content {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        background-color: #f8f9fa;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }

    /* Dark Mode Styles */
    .bg-dark {
        background-color: #212529 !important;
    }

    .text-light {
        color: #f8f9fa !important;
    }

    .bg-dark .text-light {
        color: black !important;
    }

    /* Form Control Styles */
    .form-control:focus,
    .form-select:focus {
        border-color: #727cf5;
        box-shadow: 0 0 0 0.2rem rgba(114, 124, 245, 0.25);
    }

    /* Button Styles */
    .btn-group .btn {
        padding: 0.5rem 1rem;
        font-weight: 500;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .btn-group {
            flex-direction: column;
            width: 100%;
        }

        .btn-group .btn {
            width: 100%;
            margin-left: 0 !important;
            margin-bottom: 0.5rem;
        }

        .btn-group .btn:last-child {
            margin-bottom: 0;
        }
    }

    /* Floating Label Styles */
    .form-floating {
        position: relative;
        margin-bottom: 1rem;
    }
    .form-floating input.form-control,
    .form-floating select.form-select,
    .form-floating textarea.form-control {
        height: 50px;
        border: 1px solid #2f2f2f;
        border-radius: 10px;
        background-color: transparent;
        font-size: 1rem;
        padding: 1rem 0.75rem;
        transition: all 0.8s;
    }
    .form-floating textarea.form-control {
        min-height: 100px;
        height: auto;
        padding-top: 1.625rem;
    }
    .form-floating label {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        padding: 1rem 0.75rem;
        color: #2f2f2f;
        transition: all 0.8s;
        pointer-events: none;
        z-index: 1;
    }
    .form-floating input.form-control:focus,
    .form-floating select.form-select:focus,
    .form-floating textarea.form-control:focus {
        border-color: #033c42;
        box-shadow: none;
    }
    .form-floating input.form-control:focus ~ label,
    .form-floating select.form-select:focus ~ label,
    .form-floating textarea.form-control:focus ~ label {
        height: auto;
        padding: 0 0.5rem;
        transform: translateY(-50%) translateX(0.5rem) scale(0.85);
        color: white;
        border-radius: 5px;
        z-index: 5;
    }
    .form-floating input.form-control:focus ~ label::before,
    .form-floating select.form-select:focus ~ label::before,
    .form-floating textarea.form-control:focus ~ label::before {
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
    .form-floating input.form-control:not(:placeholder-shown) ~ label,
    .form-floating select.form-select:not([value=""]) ~ label,
    .form-floating textarea.form-control:not(:placeholder-shown) ~ label {
        height: auto;
        padding: 0 0.5rem;
        transform: translateY(-50%) translateX(0.5rem) scale(0.85);
        color: white;
        border-radius: 5px;
        z-index: 5;
    }
    .form-floating input.form-control:not(:placeholder-shown) ~ label::before,
    .form-floating select.form-select:not([value=""]) ~ label::before,
    .form-floating textarea.form-control:not(:placeholder-shown) ~ label::before {
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
    .modal-body {
        background: none;
        padding: 1.5rem;
    }
</style>

<!-- Add necessary JavaScript -->
{{-- @push('scripts') --}}



<!-- Before closing body -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@5.11.3/main.min.js'></script>

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />

<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>


    function showSafeSweetAlert(options) {
            if (!window.isAlertShowing) {
                window.isAlertShowing = true;
                return Swal.fire(options).finally(() => {
                    window.isAlertShowing = false;
                });
            }
        }

    $(document).ready(function() {




        function resetActivityForm(modalId) {
    // Configuration for all modal types
    const configs = {
        'addTaskModal': {
            select: '#relatedToName',
            container: '#selectedRelatedToName', 
            hidden: '#relatedTo',
            form: '#addTaskForm'
        },
        'scheduleCallModal': {
            select: '#contact',
            container: '#selectedContact',
            hidden: '#contact_values',
            form: '#scheduleCallForm'
        },
        'scheduleMeetingModal': {
            select: '#meetingParticipants',
            container: '#selectedMeetingParticipants',
            hidden: '#meetingParticipantsValues',
            form: '#scheduleMeetingForm'
        },
        'addEventModal': {
            select: '#eventParticipants',
            container: '#selectedEventParticipants',
            hidden: '#eventParticipantsValues',
            form: '#addEventForm'
        }
    };

    const config = configs[modalId];

    // 1. NUCLEAR RESET (using .html(""))
    $(config.container).html(""); // ← This is the key change
    
    // 2. Reset select element
    $(config.select).val('')
                   .prop('selectedIndex', 0)
                   .trigger('change');
    
    // 3. Clear hidden input
    $(config.hidden).val('');
    
    // 4. Handle Select2 if used
    if ($(config.select).hasClass('select2-hidden-accessible')) {
        $(config.select).val(null).trigger('change.select2');
    }
    
    // 5. Reset form (optional)
    $(config.form)[0].reset();
    
    console.log(`Complete reset for ${modalId}`);
}

// Usage in AJAX success:
// success: function(response) {
//     if (response.success) {
//         resetActivityForm('addEventModal'); // Pass the correct modal ID
//         $('#addEventModal').modal('hide');
//     }
// }
// Reset when modals close (in case submission fails)
$('#addTaskModal, #scheduleCallModal, #scheduleMeetingModal, #addEventModal').on('hidden.bs.modal', function() {
    const modalId = $(this).attr('id');
    if (modalId === 'addTaskModal') resetActivityForm('addTaskModal');
    else if (modalId === 'scheduleCallModal') resetActivityForm('scheduleCallModal');
    else if (modalId === 'scheduleMeetingModal') resetActivityForm('scheduleMeetingModal');
    else resetActivityForm('addEventModal');
});



    $(document).off('click', '#addTask').on('click', '#addTask', function(e) {
    e.preventDefault();

   let related_to = $("#relatedTo").val();

//    console.log(related_to,"related_to");



 
  
    let submitBtn = $(this);
    submitBtn.prop("disabled", true).text("Saving...");

    let urlc = $("#addTaskForm").attr("action");  // Get form action URL
    let formData = new FormData($("#addTaskForm")[0]);  // Collect form data
    formData.append("relatedToName_values", related_to); 

    $.ajax({
        url: urlc,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        success: function(response) {
            console.log('Server response:', response);
            submitBtn.prop("disabled", false).text("Add New Task");

            if (response.success) {
                fetchActivitiesData();


                resetActivityForm('addTaskModal'); 
   
                showSafeSweetAlert({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message || 'Task added successfully',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                       $(".selected-contacts-container").html('');
                       $("#relatedTo").val('')
                   

                 
                //  $('#addTaskModal').modal('hide').on('hidden.bs.modal', function () {
                //     $('body').removeClass('modal-open');
                //     $('.modal-backdrop').remove();
                //     $('body').css('overflow', 'auto');

                //  });

              

                 


                    }
                });
            } else {
                console.error('Error response:', response);
                showSafeSweetAlert({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message[0] || 'Failed to add new Task. Please try again.',
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', { status, error, response: xhr.responseJSON });
            submitBtn.prop("disabled", false).text("Add Task");

            let errorMessage = 'Failed to add new task. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
            }

            showSafeSweetAlert({
                icon: 'error',
                title: 'Error!',
                html: errorMessage,
                confirmButtonText: 'OK'
            });
        }
    });
});







$(document).off('click', '#schedulecall').on('click', '#schedulecall', function(e) {
    e.preventDefault();


    let contact_values = $("#contact_values").val();
    console.log(contact_values,"contact_values");



  
    let submitBtn = $(this);
    submitBtn.prop("disabled", true).text("Scheduling...");

    let urlc = $("#scheduleCallForm").attr("action");  // Get form action URL
    let formData = new FormData($("#scheduleCallForm")[0]);  // Collect form data
    formData.append("contact_values", contact_values);

    $.ajax({
        url: urlc,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        success: function(response) {
            console.log('Server responsehhhhhh:', response);
            submitBtn.prop("disabled", false).text("Schedule Call");

            if (response.success) {
                fetchActivitiesData();
                showSafeSweetAlert({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message || 'Schedule added successfully',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        
                        $("#callTitle").val('');
                        $("#callDate").val('');
                        $("#callType").val('phone');
                        $("#contact").val('');
                        $("#selectedContact").html('');
                        $("#contact_values").val('');
                        $(".selected-contacts-container").html('');
                    }
                });
            } else {
                console.error('Error response:', response);
                showSafeSweetAlert({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message[0] || 'Failed to Schedule Call. Please try again.',
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', { status, error, response: xhr.responseJSON });
            submitBtn.prop("disabled", false).text("Schedule Call");

            let errorMessage = 'Failed to Schedule call. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
            }

            showSafeSweetAlert({
                icon: 'error',
                title: 'Error!',
                html: errorMessage,
                confirmButtonText: 'OK'
            });
        }
    });
});









$(document).off('click', '#schedulemeeting').on('click', '#schedulemeeting', function(e) {
    e.preventDefault();
  
    let submitBtn = $(this);
    submitBtn.prop("disabled", true).text("Scheduling Meeting...");

   
    let meetingParticipants_values = $("#meetingParticipantsValues").val();
 
    console.log(meetingParticipants_values,"mmm");
    
 


    let urlc = $("#scheduleMeetingForm").attr("action");  // Get form action URL
    let formData = new FormData($("#scheduleMeetingForm")[0]);  // Collect form data
    formData.append("meeting_participants_values", meetingParticipants_values);

    $.ajax({
        url: urlc,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        success: function(response) {
            fetchActivitiesData();
            console.log('Server response:', response);
            submitBtn.prop("disabled", false).text("Schedule Meeting");

            if (response.success) {
                showSafeSweetAlert({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message || 'Schedule added successfully',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        
                        $(".selected-contacts-container").html('');
                        $("#meetingParticipants").val('');
                        $("#meetingParticipantsValues").val('');
                        $("#meetingTitle").val('');
                        $("#meetingDate").val('');
                        $("#meetingDuration").val('');
                        $("#meetingAgenda").val('');
                       
                    }
                });
            } else {
                console.error('Error response:', response);
                showSafeSweetAlert({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message[0] || 'Failed to Schedule. Please try again.',
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', { status, error, response: xhr.responseJSON });
            submitBtn.prop("disabled", false).text("Schedule Meeting");

            let errorMessage = 'Failed to Schedule Meeting. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
            }

            showSafeSweetAlert({
                icon: 'error',
                title: 'Error!',
                html: errorMessage,
                confirmButtonText: 'OK'
            });
        }
    });
});






$(document).off('click', '#addnewevent').on('click', '#addnewevent', function(e) {
    e.preventDefault();
    


// Try direct ID selector (more reliable)
let eventParticipants_values = $("#eventParticipantsValues").val();
// console.log(eventParticipants_values,"direct access");

  
    let submitBtn = $(this);
    submitBtn.prop("disabled", true).text("Adding...");

    let urlc = $("#addEventForm").attr("action");  // Get form action URL
    let formData = new FormData($("#addEventForm")[0]);  // Collect form data
    formData.append("eventParticipants_values", eventParticipants_values);
    

    $.ajax({
        url: urlc,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        success: function(response) {
            console.log('Server response:', response);
            submitBtn.prop("disabled", false).text("Add Event");

            if (response.success) {
                fetchActivitiesData();
                showSafeSweetAlert({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message || 'Event added successfully',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                         $(".selected-contacts-container").html('');

                         $("#eventParticipants").val('');
                         $("#eventParticipantsValues").val('');
                         $("#eventName").val('');
                         $("#eventStart").val('');
                         $("#eventEnd").val('');
                         $("#eventDescription").val('');
                         $("#eventType").val('meeting');
           
                    }
                });
            } else {
                console.error('Error response:', response);
                showSafeSweetAlert({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message[0] || 'Failed to add Event. Please try again.',
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', { status, error, response: xhr.responseJSON });
            submitBtn.prop("disabled", false).text("Add Event");

            let errorMessage = 'Failed to add Event. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
            }

            showSafeSweetAlert({
                icon: 'error',
                title: 'Error!',
                html: errorMessage,
                confirmButtonText: 'OK'
            });
        }
    });
});





// function fetchActivitiesData(page = 1) {



$("#searchActivities").keyup(function(){
    fetchActivitiesData();
});



$("#filterActivities").on("change", function(){
    fetchActivitiesData();
})



// Page change function 

function fetchActivitiesData(page = 1) {
    
    let filterActivity = $("#filterActivities").val();
    let searchActivity = $("#searchActivities").val();

    console.log(filterActivity, searchActivity);

    $.ajax({
        url: `/company/crm/activities/all?page=${page}`, 
        method: 'POST',
        data: {
            filter: filterActivity,
            search:searchActivity
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log("activity res", response);
            let tableBody = '';
            
            if (response.data.length === 0) {
                tableBody = `<tr>
                    <td colspan="7" class="text-center">
                        <div class="text-muted">
                            <i class="fas fa-tasks fa-3x mb-3"></i>
                            <p>No activities found. Start by creating a new activity.</p>
                        </div>
                    </td>
                </tr>`;
            } else {
                let i = 1;
                if(response.current_page == 1){
                    i = 1;
                }else if(response.pagination.current_page > 1){
                    i = (response.pagination.current_page - 1) * response.pagination.per_page + 1;
                }

                response.data.forEach(activity => {
                    // Determine icon class based on type
                    let typeIconClass = '';
                    switch(activity.type) {
                        case 'task': 
                            typeIconClass = 'fa-tasks text-primary'; 
                            break;
                        case 'call': 
                            typeIconClass = 'fa-phone-alt text-success'; 
                            break;
                        case 'meeting': 
                            typeIconClass = 'fa-video text-info'; 
                            break;
                        case 'event': 
                            typeIconClass = 'fa-calendar text-secondary'; 
                            break;
                        default: 
                            typeIconClass = 'fa-tasks text-muted';
                    }
                    
                    // Determine status badge class
                    let statusBadgeClass = '';
                    switch(activity.status) {
                        case 'completed': 
                            statusBadgeClass = 'bg-success'; 
                            break;
                        case 'in_progress': 
                            statusBadgeClass = 'bg-info'; 
                            break;
                        case 'pending': 
                            statusBadgeClass = 'bg-warning'; 
                            break;
                        default: 
                            statusBadgeClass = 'bg-danger';
                    }
                    
                    // Determine priority badge class
                    let priorityBadgeClass = '';
                    switch(activity.priority) {
                        case 'high': 
                            priorityBadgeClass = 'bg-danger'; 
                            break;
                        case 'medium': 
                            priorityBadgeClass = 'bg-warning'; 
                            break;
                        case 'low': 
                            priorityBadgeClass = 'bg-info'; 
                            break;
                        default: 
                            priorityBadgeClass = 'bg-secondary';
                    }
                    
                    // Format date
                    let dueDate = activity.start_date ? 
                        new Date(activity.start_date).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        }) : 'N/A';
                    
                    // All day badge
                    let allDayBadge = activity.all_day ? 
                        '<span class="badge bg-secondary ms-1">All Day</span>' : '';
                    
                    // Format contacts display
                    let contactsDisplay = 'N/A';
                    const contactsValue = activity.related_to || activity.participants || activity.contact;
                    
                    if (contactsValue) {
                        const contactsArray = contactsValue.split(',');
                        if (contactsArray.length === 1) {
                            contactsDisplay = contactsArray[0].trim();
                        } else if (contactsArray.length > 1) {
                            const firstContact = contactsArray[0].trim();
                            const othersCount = contactsArray.length - 1;
                            contactsDisplay = `${firstContact} +${othersCount} other${othersCount > 1 ? 's' : ''}`;
                        }
                    }
                    
                    tableBody += `<tr>
                        <td>${i}</td>
                        <td>
                            <i class="fas ${typeIconClass}"></i>
                        </td>
                        <td>${activity.title}</td>
                        <td>${contactsDisplay}</td>
                        <td>
                            ${dueDate}
                            ${allDayBadge}
                        </td>
                        <td>
                            <span class="badge ${statusBadgeClass}">
                                ${activity.status.charAt(0).toUpperCase() + activity.status.slice(1)}
                            </span>
                        </td>
                        <td>
                            <span class="badge ${priorityBadgeClass}">
                                ${activity.priority.charAt(0).toUpperCase() + activity.priority.slice(1)}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex">
                                <i class="fas fa-edit text-primary me-3 editactivity" 
                                   data-bs-toggle="moda" 
                                   data-bs-target="#editActivityMod"
                                   data-activity-id="${activity.id}"
                                   style="cursor: pointer; font-size: 1.5rem; transition: transform 0.2s;"
                                   onmouseover="this.style.transform='scale(1.1)'"></i>
                                <i class="fas fa-check text-success me-3 complete-activity" 
                                   data-id="${activity.id}"
                                   style="cursor: pointer; font-size: 1.5rem; transition: transform 0.2s;"
                                   onmouseover="this.style.transform='scale(1.1)'"></i>
                                <i class="fas fa-trash text-danger delete-activity" 
                                   data-id="${activity.id}"
                                   style="cursor: pointer; font-size: 1.5rem; transition: transform 0.2s;"
                                   onmouseover="this.style.transform='scale(1.1)'"></i>
                            </div>
                        </td>
                    </tr>`;
                    i++;
                });
            }

            $('#activities-table tbody').html(tableBody);

            // Generate Pagination
            let paginationHtml = '';
            
            paginationHtml += `<li class="page-item ${response.pagination.prev_page_url ? '' : 'disabled'}">
                <a class="page-link" href="#" data-page="${response.pagination.current_page - 1}">Previous</a>
            </li>`;

            for (let i = 1; i <= response.pagination.last_page; i++) {
                paginationHtml += `<li class="page-item ${response.pagination.current_page === i ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>`;
            }
            
            paginationHtml += `<li class="page-item ${response.pagination.next_page_url ? '' : 'disabled'}">
                <a class="page-link" href="#" data-page="${response.pagination.current_page + 1}">Next</a>
            </li>`;

            $('#activities-pagination').html(paginationHtml);
            
            // Initialize tooltips for action buttons
            $('[data-toggle="tooltip"]').tooltip();
        },
        error: function(xhr, status, error) {
            console.error("Error:", status, error, xhr.responseText);
            // Show error message to user
            alert('Failed to load activities data. Please try again.');
        }
    });
}

function changePageActivities(event, page) {
    event.preventDefault();

    alert("ggg");
    fetchActivitiesData(page);
}



$(document).on('click', '#activities-pagination .page-link', function(e) {
    e.preventDefault();
    const page = $(this).data('page');
    if (page) {
        fetchActivitiesData(page);
    }
});



fetchActivitiesData();




$(document).on('click', '.delete-activity', function() {
    let activityId = $(this).data('id');
    
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
                url: `/company/crm/activities/${activityId}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    fetchActivitiesData();
                    showSafeSweetAlert({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message[0] || 'Activity has been deleted successfully.',
                    confirmButtonText: 'OK'
                });
                    // fetchActivitiesData();
                },
                error: function(xhr, status, error) {
                    console.error("Error:", status, error, xhr.responseText);
                    showSafeSweetAlert({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message[0] || 'Failed to Delete Activity. Please try again.',
                    confirmButtonText: 'OK'
                });
                }
            });
        }
    });
});





$(document).on('click', '.complete-activity', function(e) {
    e.preventDefault();
    let activityId = $(this).data('id');
    
    // Get fresh CSRF token from meta tag
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    Swal.fire({
        title: 'Complete Activity?',
        text: "Are you sure you want to mark this activity as completed?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, complete it!',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return $.ajax({
                url: `/company/crm/activities/${activityId}/complete`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                dataType: 'json'
            }).then(response => {
                if (!response.success) {
                    throw new Error(response.message || 'Failed to complete activity');
                }
                return response;
            }).catch(error => {
                Swal.showValidationMessage(
                    `Request failed: ${error.responseJSON?.message || error.statusText || 'Unknown error'}`
                );
                return false;
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            Swal.fire({
                title: 'Completed!',
                text: 'Activity marked as completed.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
            fetchActivitiesData(); // Refresh the activities list
        }
    });
});


$(document).on("click", ".editactivity", function() {
    let id = $(this).attr("data-activity-id");
    
    // Show loading indicator
    $('#loadingIndicator').show();
    
    // Fetch activity data
    $.ajax({
        url: `/company/crm/activities/${id}`,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const activity = response.data;
                
                // Determine which modal to show based on activity type
                switch(activity.type) {
                    case 'task':
                        populateTaskModal(activity);
                        $('#editTaskModal').modal('show');
                        break;
                    case 'call':
                        populateCallModal(activity);
                        $('#editCallModal').modal('show');
                        break;
                    case 'meeting':
                        populateMeetingModal(activity);
                        $('#editMeetingModal').modal('show');
                        break;
                    case 'event':
                        populateEventModal(activity);
                        $('#editEventModal').modal('show');
                        break;
                    default:
                        console.error('Unknown activity type:', activity.type);
                }
            } else {
                showToast('error', 'Failed to fetch activity data');
            }
        },
        error: function(xhr, status, error) {
            showToast('error', 'An error occurred while fetching activity data');
            console.error('Error:', error);
        },
        complete: function() {
            $('#loadingIndicator').hide();
        }
    });
});


// Task Update Handler
$('#editTaskForm').on('submit', function(e) {
    e.preventDefault();
    handleActivityUpdate($(this), 'Task');
});

// Call Update Handler
$('#editCallForm').on('submit', function(e) {
    e.preventDefault();
    handleActivityUpdate($(this), 'Call');
});

// Meeting Update Handler
$('#editMeetingForm').on('submit', function(e) {
    e.preventDefault();
    handleActivityUpdate($(this), 'Meeting');
});

// Event Update Handler
$('#editEventForm').on('submit', function(e) {
    e.preventDefault();
    handleActivityUpdate($(this), 'Event');
});

// Universal Update Function with SweetAlert
function handleActivityUpdate(form, activityType) {
    const activityId = form.find('input[name="id"]').val();
    const submitBtn = form.find('button[type="submit"]');
    const originalBtnText = submitBtn.html();

    // Show loading state
    submitBtn.prop('disabled', true).html(`
        <span class="spinner-border spinner-border-sm" role="status"></span>
        Updating...
    `);

    Swal.fire({
        title: 'Are you sure?',
        text: `Update this ${activityType.toLowerCase()}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, update it!'
    }).then((result) => {
        if (result.isConfirmed) {
            performUpdateRequest(form, activityId, activityType, submitBtn, originalBtnText);
        } else {
            submitBtn.prop('disabled', false).html(originalBtnText);
        }
    });
}

function performUpdateRequest(form, activityId, activityType, submitBtn, originalBtnText) {

    $.ajax({
        url: `/company/crm/activities/${activityId}`,
        type: 'PUT',
        data: form.serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log(response);
            
          fetchActivitiesData();
          fetchActivityStats();

          submitBtn.prop('disabled', false).text(`Update ${activityType}`);


            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: `${activityType} has been updated.`,
                    timer: 2000,
                    showConfirmButton: false
                });
                form.closest('.modal').modal('hide');
                refreshActivities();
            } else {
                showError(response.message || `${activityType} update failed`);
            }
        },
        error: function(xhr) {
            const errorMsg = xhr.responseJSON?.message || 
                          xhr.statusText || 
                          `${activityType} update failed`;
            showError(errorMsg);
        },
        complete: function() {
            submitBtn.prop('disabled', false).html(originalBtnText);
        }
    });
}

function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message,
        timer: 3000
    });
}

function refreshActivities() {
    // Implement your refresh logic here
    if ($.fn.DataTable.isDataTable('#activitiesTable')) {
        $('#activitiesTable').DataTable().ajax.reload(null, false);
    }
    // Or trigger custom event
    $(document).trigger('activities:updated');
}
// Toast notification helper
function showToast(type, message) {
    const toast = $(`
        <div class="toast align-items-center text-white bg-${type} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `);
    
    $('#toastContainer').append(toast);
    new bootstrap.Toast(toast[0]).show();
    
    toast.on('hidden.bs.toast', function() {
        $(this).remove();
    });
}










// First, let's create a reusable function for the common contact loading logic
function populateContacts(selectSelector, savedValues, hiddenInputSelector) {
    if (savedValues) {
        const savedEmails = savedValues.split(',');
        
        // Get all available contacts from the select
        const allContacts = [];
        $(selectSelector + ' option').each(function() {
            if ($(this).val()) {
                allContacts.push({
                    value: $(this).val(),
                    text: $(this).text(),
                    fullname: $(this).data('fullname'),
                    display: $(this).data('display')
                });
            }
        });
        
        // Find matching contacts
        const selectedContacts = [];
        savedEmails.forEach(email => {
            const found = allContacts.find(c => c.value === email.trim());
            if (found) {
                selectedContacts.push(found);
            }
        });
        
        // Update the display
        updateSelectedContactsDisplay(selectSelector, selectedContacts);
        updateHiddenInput(selectSelector, hiddenInputSelector, selectedContacts);
    }
}

// Now update all modal population functions:


function populateTaskModal(activity) {
    $('#editTaskId').val(activity.id);
    $('#editTaskTitle').val(activity.title);
    $('#editDueDate').val(formatDateForInput(activity.start_date));
    $('#editPriority').val(activity.priority);
    $('#editDescription').val(activity.description_agenda_notes);
    $('#editTaskForm').attr('action', `/company/crm/activities/${activity.id}`);
    
    // Clear existing selections
    $('#selectededitRelatedToName').empty();
    $('#editRelatedTo').val('');
    
    // If there are related contacts, populate them
    if (activity.related_to) {
        const relatedEmails = activity.related_to.split(',');
        relatedEmails.forEach(email => {
            const option = $(`#editRelatedToName option[value="${email.trim()}"]`);
            if (option.length) {
                const fullname = option.data('fullname');
                const display = option.data('display');
                
                $('#selectededitRelatedToName').append(`
                    <div class="contact-tag" data-hidden="#editRelatedTo">
                        ${fullname} (${display})
                        <span class="contact-tag-remove" data-value="${email.trim()}">×</span>
                    </div>
                `);
                
                // Update hidden input
                const currentValues = $('#editRelatedTo').val() ? $('#editRelatedTo').val().split(',') : [];
                currentValues.push(email.trim());
                $('#editRelatedTo').val(currentValues.join(','));
            }
        });
    }
}


function populateCallModal(activity) {
    // Set basic fields
    $('#editCallId').val(activity.id);
    $('#editCallTitle').val(activity.title);
    $('#editCallDate').val(formatDateTimeForInput(activity.start_date));
    $('#editCallType').val(activity.call_event_type);
    $('#editCallNotes').val(activity.description_agenda_notes);
    $('#editCallForm').attr('action', `/company/crm/activities/${activity.id}`);
    
    // Clear existing contact selections
    $('#selectededitContact').empty();
    $('#editContactValues').val('');
    
    // Populate contacts if they exist
    if (activity.contact) {
        const contactPhones = activity.contact.split(',');
        contactPhones.forEach(phone => {
            const trimmedPhone = phone.trim();
            const option = $(`#editContact option[value="${trimmedPhone}"]`);
            
            if (option.length) {
                const fullname = option.data('fullname');
                const display = option.data('display');
                
                $('#selectededitContact').append(`
                    <div class="contact-tag" data-hidden="#editContactValues">
                        ${fullname} (${display})
                        <span class="contact-tag-remove" data-value="${trimmedPhone}">×</span>
                    </div>
                `);
                
                // Update hidden input
                const currentValues = $('#editContactValues').val() ? 
                    $('#editContactValues').val().split(',') : [];
                currentValues.push(trimmedPhone);
                $('#editContactValues').val(currentValues.join(','));
            }
        });
    }
}
function populateMeetingModal(activity) {
    // Set basic fields
    $('#editMeetingId').val(activity.id);
    $('#editMeetingTitle').val(activity.title);
    $('#editMeetingDate').val(formatDateTimeForInput(activity.start_date));
    $('#editMeetingDuration').val(activity.duration);
    $('#editMeetingAgenda').val(activity.description_agenda_notes);
    $('#editMeetingForm').attr('action', `/company/crm/activities/${activity.id}`);
    
    // Clear existing participant selections
    $('#selectededitMeetingParticipants').empty();
    $('#editMeetingParticipantsValues').val('');
    
    // Populate participants if they exist
    if (activity.participants) {
        const participantEmails = activity.participants.split(',');
        participantEmails.forEach(email => {
            const trimmedEmail = email.trim();
            const option = $(`#editMeetingParticipants option[value="${trimmedEmail}"]`);
            
            if (option.length) {
                const fullname = option.data('fullname');
                const display = option.data('display');
                
                $('#selectededitMeetingParticipants').append(`
                    <div class="contact-tag" data-hidden="#editMeetingParticipantsValues">
                        ${fullname} (${display})
                        <span class="contact-tag-remove" data-value="${trimmedEmail}">×</span>
                    </div>
                `);
                
                // Update hidden input
                const currentValues = $('#editMeetingParticipantsValues').val() ? 
                    $('#editMeetingParticipantsValues').val().split(',') : [];
                currentValues.push(trimmedEmail);
                $('#editMeetingParticipantsValues').val(currentValues.join(','));
            }
        });
    }
}

function populateEventModal(activity) {
    // Set basic fields
    $('#editEventId').val(activity.id);
    $('#editEventName').val(activity.title);
    $('#editEventType').val(activity.call_event_type);
    $('#editEventStart').val(formatDateTimeForInput(activity.start_date));
    $('#editEventEnd').val(formatDateTimeForInput(activity.end_date));
    $('#editEventDescription').val(activity.description_agenda_notes);
    $('#editEventForm').attr('action', `/company/crm/activities/${activity.id}`);
    
    // Clear existing participant selections
    $('#selectededitEventParticipants').empty();
    $('#editEventParticipantsValues').val('');

    
    
    // Populate participants if they exist
    if (activity.participants) {
        const participantEmails = activity.participants.split(',');
        participantEmails.forEach(email => {
            const trimmedEmail = email.trim();
            const option = $(`#editEventParticipants option[value="${trimmedEmail}"]`);
            
            if (option.length) {
                const fullname = option.data('fullname');
                const display = option.data('display');
                
                $('#selectededitEventParticipants').append(`
                    <div class="contact-tag" data-hidden="#editEventParticipantsValues">
                        ${fullname} (${display})
                        <span class="contact-tag-remove" data-value="${trimmedEmail}">×</span>
                    </div>
                `);
                
                // Update hidden input
                const currentValues = $('#editEventParticipantsValues').val() ? 
                    $('#editEventParticipantsValues').val().split(',') : [];
                if (!currentValues.includes(trimmedEmail)) {
                    currentValues.push(trimmedEmail);
                    $('#editEventParticipantsValues').val(currentValues.join(','));
                }
            }
        });
    }
}
// Update the helper functions to work with the new structure
function updateSelectedContactsDisplay(selectSelector, contacts) {
    const container = $(selectSelector).next('.selected-contacts-container');
    container.empty();
    
    contacts.forEach(contact => {
        container.append(`
            <div class="contact-tag">
                ${contact.fullname} (${contact.display})
                <span class="contact-tag-remove" data-value="${contact.value}">×</span>
            </div>
        `);
    });
}

function updateHiddenInput(selectSelector, hiddenInputSelector, contacts) {
    const hiddenInput = $(hiddenInputSelector);
    hiddenInput.val(contacts.map(contact => contact.value).join(','));
    console.log(`Updated ${hiddenInput.attr('name')} with values:`, hiddenInput.val());
}



// Helper function to format date for date input
function formatDateForInput(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toISOString().split('T')[0];
}

// Helper function to format datetime for datetime-local input
function formatDateTimeForInput(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    // Convert to local datetime string in format YYYY-MM-DDTHH:MM
    return new Date(date.getTime() - (date.getTimezoneOffset() * 60000))
        .toISOString()
        .slice(0, 16);
}

// Helper function to show toast notifications
function showToast(type, message) {
    // Implement your toast notification system here
    const toast = $(`<div class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>`);
    
    $('#toastContainer').append(toast);
    new bootstrap.Toast(toast[0]).show();
    
    // Remove toast after it hides
    toast.on('hidden.bs.toast', function() {
        $(this).remove();
    });
}





function fetchActivityStats() {
        $.ajax({
            url: '/company/crm/activities/statistics',
            method: 'POST',
            // dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                // Show loading state if needed
                $('.activity-stats').addClass('loading');
            },
            success: function(response) {

                // console.log(response);
                // alert("ff");
                if (response.success) {
                    updateActivityStats(response.data);
                } else {
                    console.error('Failed to load stats:', response.message);
                    showErrorAlert('Failed to load activity statistics');
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                showErrorAlert('Error fetching activity data');
            },
            complete: function() {
                $('.activity-stats').removeClass('loading');
            }
        });
    }

    // Update the UI with stats
    function updateActivityStats(data) {
        // Update counts
        $('#taskCount').text(data.by_type.task);
        $('#callCount').text(data.by_type.call);
        $('#meetingCount').text(data.by_type.meeting);
        $('#eventCount').text(data.by_type.event);
        $('#totalActivities').text(data.total_activities);
        $('#completionRate').text(data.completion_rate);
        
        // Calculate percentages for progress bars
        const maxCount = Math.max(
            data.by_type.task,
            data.by_type.call,
            data.by_type.meeting,
            data.by_type.event
        ) || 1; // Avoid division by zero
        
        // Update progress bars
        $('#taskProgress').css('width', (data.by_type.task / maxCount * 100) + '%');
        $('#callProgress').css('width', (data.by_type.call / maxCount * 100) + '%');
        $('#meetingProgress').css('width', (data.by_type.meeting / maxCount * 100) + '%');
        $('#eventProgress').css('width', (data.by_type.event / maxCount * 100) + '%');
        $('#completionProgress').css('width', data.completion_rate + '%');
        
        // Add animation
        $('.progress-bar').each(function() {
            $(this).css('transition', 'width 1s ease-in-out');
        });
    }

    // Show error alert
    function showErrorAlert(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message,
            timer: 3000,
            showConfirmButton: false
        });
    }

    // Initial load
    fetchActivityStats();
    


function load_sub_users_contact() {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: `/company/crm/activities/sub_users_contact`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (!response.data || !Array.isArray(response.data)) {
                    console.error("Invalid data format received from API");
                    reject("Invalid data format");
                    return;
                }

                // Initialize all select fields
                initMultiSelect('#relatedToName', response.data, 'email', 'email');
                initMultiSelect('#contact', response.data, 'email', 'email');
                initMultiSelect('#meetingParticipants', response.data, 'email', 'email');
                initMultiSelect('#eventParticipants', response.data, 'email', 'email');
                initMultiSelect('#editRelatedToName', response.data, 'email', 'email');
                initMultiSelect('#editContact', response.data, 'email', 'email');
                initMultiSelect('#editMeetingParticipants', response.data, 'email', 'email');
                initMultiSelect('#editEventParticipants', response.data, 'email', 'email');


                resolve(response.data);
            },
            error: function(err) {
                console.error("AJAX Error:", err);
                reject(err);
            }
        });
    });
}

function initMultiSelect(selector, data, valueField, displayField) {
    // Verify the select element exists


    if (!$(selector).length) {
        console.error(`Element ${selector} not found`);
        return;
    }

    console.log(`Initializing ${selector} with data:`, data); 

    // Verify data is valid
    if (!data || !Array.isArray(data) || data.length === 0) {
        console.error(`No valid data provided for ${selector}`);
        return;
    }

    let options = '<option value="">Select...</option>';
    
    try {
        for(let i = 0; i < data.length; i++) {
            // Verify the required fields exist in the data
            if (!data[i][valueField] || !data[i][displayField] || !data[i].fullname) {
                console.warn(`Missing required fields in item ${i}`, data[i]);
                continue;
            }

            options += `
                <option value="${data[i][valueField]}" 
                        data-fullname="${data[i].fullname}"
                        data-display="${data[i][displayField]}">
                    ${data[i].fullname} - ${data[i][displayField]}
                </option>`;
        }

        $(selector).html(options);
        console.log(`Initialized ${selector} with ${data.length} options`);
        
        // Add container if it doesn't exist
        if (!$(selector).next('.selected-contacts-container').length) {
            $(selector).after('<div class="selected-contacts-container" id="selected' + selector.replace('#','') + '"></div>');
        }
        
        // Add hidden input if it doesn't exist
        const hiddenInputName = selector.replace('#','') + '_values';
        if (!$(selector).next('.selected-contacts-container').next(`input[name="${hiddenInputName}"]`).length) {
            $(selector).next('.selected-contacts-container').after(`<input type="hidden" name="${hiddenInputName}" value="">`);
        }
    } catch (e) {
        console.error(`Error initializing ${selector}:`, e);
    }
}



$(document).ready(function() {
    // Load contacts immediately
    load_sub_users_contact();

    // Initialize contact selection for all modals
    $('.modal').on('shown.bs.modal', function () {
        const selectFields = [
            { selector: '#relatedToName', hidden: '#relatedTo' },
            { selector: '#contact', hidden: '#contact_values' },
            { selector: '#meetingParticipants', hidden: '#meetingParticipantsValues' },
            { selector: '#eventParticipants', hidden: '#eventParticipantsValues' },
            { selector: '#editRelatedToName', hidden: '#editRelatedTo' },
            { selector: '#editContact', hidden: '#editContactValues' },
            { selector: '#editMeetingParticipants', hidden: '#editMeetingParticipantsValues' },
            { selector: '#editEventParticipants', hidden: '#editEventParticipantsValues' }
        ];

        selectFields.forEach(field => {
            if ($(field.selector).length) {
                // Clear previous handlers
                $(field.selector).off('change.contactSelect');
                $(field.selector).on('change.contactSelect', function () {
                    const selectedOption = $(this).find('option:selected');
                    const value = selectedOption.val();
                    const display = selectedOption.data('display');
                    const fullname = selectedOption.data('fullname');

                    if (!value) return;

                    const container = $(this).next('.selected-contacts-container');
                    const hiddenInput = $(field.hidden);

                    let currentValues = hiddenInput.val() ? hiddenInput.val().split(',') : [];

                    if (currentValues.includes(value)) {
                        showSafeSweetAlert({
                            icon: 'warning',
                            title: 'Duplicate Contact',
                            text: `${fullname} (${display}) is already selected`,
                            timer: 2000
                        });
                        $(this).val('');
                        return;
                    }

                    // ✅ Append with data-hidden to allow proper removal
                    container.append(`
                        <div class="contact-tag" data-hidden="${field.hidden}">
                            ${fullname} (${display})
                            <span class="contact-tag-remove" data-value="${value}">×</span>
                        </div>
                    `);

                    currentValues.push(value);
                    hiddenInput.val(currentValues.join(','));

                    $(this).val('');
                });

                // Set initial values when modal opens
                const container = $(field.selector).next('.selected-contacts-container');
                const hiddenInput = $(field.hidden);
                const initialValues = container.find('.contact-tag').map(function () {
                    return $(this).find('.contact-tag-remove').data('value');
                }).get();
                hiddenInput.val(initialValues.join(','));
            }
        });
    });

    // ✅ Remove contact and update corresponding hidden input
    $(document).on('click', '.contact-tag-remove', function () {
        const valueToRemove = $(this).data('value');
        const tag = $(this).closest('.contact-tag');
        const hiddenSelector = tag.data('hidden');
        const hiddenInput = $(hiddenSelector);

        console.log("hi", tag, valueToRemove, hiddenSelector, hiddenInput);

        let currentValues = hiddenInput.val() ? hiddenInput.val().split(',') : [];
        currentValues = currentValues.filter(val => val !== valueToRemove);
        hiddenInput.val(currentValues.join(','));

        tag.remove();
    });

  
});







function updateSelectedContactsDisplay(selector, contacts) {
    const container = $(selector).next('.selected-contacts-container');
    container.empty();
    
    contacts.forEach(contact => {
        container.append(`
            <div class="contact-tag">
                ${contact.fullname} (${contact.display})
                <span class="contact-tag-remove" data-value="${contact.value}">×</span>
            </div>
        `);
    });
}

// function updateHiddenInput(selector, contacts) {
//     const hiddenInput = $(selector).next('.selected-contacts-container').next('input[type="hidden"]');
//     hiddenInput.val(contacts.map(contact => contact.value).join(','));
//     console.log(`Updated ${hiddenInput.attr('name')} with values:`, hiddenInput.val());
// }








});
</script>

{{-- @endpush --}}