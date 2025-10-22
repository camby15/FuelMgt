<!-- Facility Management Modal -->
<div class="modal fade" id="facilityModal" tabindex="-1" aria-labelledby="facilityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="facilityModalLabel">New Facility Request</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="facilityForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="requestType" class="form-label">Request Type</label>
                            <select class="form-select" id="requestType" required>
                                <option value="">Select type</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="repair">Repair</option>
                                <option value="inspection">Inspection</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="facilityPriority" class="form-label">Priority</label>
                            <select class="form-select" id="facilityPriority" required>
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="facilityLocation" class="form-label">Location</label>
                            <input type="text" class="form-control" id="facilityLocation" required>
                        </div>
                        <div class="col-12">
                            <label for="facilityDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="facilityDescription" rows="3" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Housekeeping Modal -->
<div class="modal fade" id="housekeepingModal" tabindex="-1" aria-labelledby="housekeepingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="housekeepingModalLabel">New Housekeeping Task</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="housekeepingForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <!-- Task Details Section -->
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Task Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="taskTitle" class="form-label">Task Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="taskTitle" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="taskType" class="form-label">Task Type <span class="text-danger">*</span></label>
                                        <select class="form-select" id="taskType" required>
                                            <option value="">Select task type</option>
                                            <option value="cleaning">General Cleaning</option>
                                            <option value="deep_cleaning">Deep Cleaning</option>
                                            <option value="sanitization">Sanitization</option>
                                            <option value="waste">Waste Disposal</option>
                                            <option value="pest_control">Pest Control</option>
                                            <option value="laundry">Laundry Service</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                                        <select class="form-select" id="priority" required>
                                            <option value="low">Low</option>
                                            <option value="medium" selected>Medium</option>
                                            <option value="high">High</option>
                                            <option value="urgent">Urgent</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="taskDescription" class="form-label">Description</label>
                                        <textarea class="form-control" id="taskDescription" rows="3" placeholder="Provide detailed description of the task"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Location Section -->
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Location Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="building" class="form-label">Building <span class="text-danger">*</span></label>
                                        <select class="form-select" id="building" required>
                                            <option value="">Select building</option>
                                            <option value="building_a">Building A</option>
                                            <option value="building_b">Building B</option>
                                            <option value="building_c">Building C</option>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="floor" class="form-label">Floor <span class="text-danger">*</span></label>
                                            <select class="form-select" id="floor" required>
                                                <option value="">Select floor</option>
                                                <option value="ground">Ground Floor</option>
                                                <option value="first">1st Floor</option>
                                                <option value="second">2nd Floor</option>
                                                <option value="third">3rd Floor</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="room" class="form-label">Room/Area</label>
                                            <input type="text" class="form-control" id="room" placeholder="e.g., Conference Room, Lobby, etc.">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="locationNotes" class="form-label">Location Notes</label>
                                        <textarea class="form-control" id="locationNotes" rows="2" placeholder="Any specific location details"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <!-- Scheduling Section -->
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Scheduling</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="taskDate" class="form-label">Task Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="taskDate" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="startTime" class="form-label">Start Time <span class="text-danger">*</span></label>
                                            <input type="time" class="form-control" id="startTime" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="endTime" class="form-label">End Time</label>
                                            <input type="time" class="form-control" id="endTime">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="recurringTask">
                                            <label class="form-check-label" for="recurringTask">
                                                Recurring Task
                                            </label>
                                        </div>
                                    </div>
                                    <div id="recurringOptions" style="display: none;">
                                        <div class="mb-3">
                                            <label class="form-label">Repeat</label>
                                            <select class="form-select" id="repeatFrequency">
                                                <option value="daily">Daily</option>
                                                <option value="weekly">Weekly</option>
                                                <option value="biweekly">Bi-weekly</option>
                                                <option value="monthly">Monthly</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Ends</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="endOptions" id="endAfter" checked>
                                                <label class="form-check-label" for="endAfter">
                                                    After
                                                    <input type="number" class="form-control d-inline-block ms-2" style="width: 80px;" value="1" min="1"> occurrences
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="endOptions" id="endOnDate">
                                                <label class="form-check-label" for="endOnDate">
                                                    On
                                                    <input type="date" class="form-control d-inline-block ms-2" style="width: 150px;">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Assignment Section -->
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Assignment</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="assignedTo" class="form-label">Assign To <span class="text-danger">*</span></label>
                                        <select class="form-select" id="assignedTo" required>
                                            <option value="">Select staff member</option>
                                            <option value="staff1">John Doe (Housekeeping Staff)</option>
                                            <option value="staff2">Jane Smith (Senior Cleaner)</option>
                                            <option value="staff3">Mike Johnson (Sanitation Expert)</option>
                                            <option value="staff4">Sarah Williams (Team Lead)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="supervisor" class="form-label">Supervisor</label>
                                        <select class="form-select" id="supervisor">
                                            <option value="">Select supervisor</option>
                                            <option value="super1">Robert Brown</option>
                                            <option value="super2">Emily Davis</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Additional Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="requiredSupplies" class="form-label">Required Supplies/Equipment</label>
                                        <select class="form-select" id="requiredSupplies" multiple>
                                            <option value="cleaning_supplies">Cleaning Supplies</option>
                                            <option value="sanitizers">Sanitizers</option>
                                            <option value="vacuum">Vacuum Cleaner</option>
                                            <option value="mop">Mop & Bucket</option>
                                            <option value="gloves">Protective Gloves</option>
                                            <option value="mask">Face Mask</option>
                                        </select>
                                        <small class="text-muted">Hold Ctrl/Cmd to select multiple items</small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="specialInstructions" class="form-label">Special Instructions</label>
                                        <textarea class="form-control" id="specialInstructions" rows="2" placeholder="Any special instructions or requirements"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="taskAttachments" class="form-label">Attachments</label>
                                        <input class="form-control" type="file" id="taskAttachments" multiple>
                                        <small class="text-muted">Upload any relevant files or images (max 5MB each)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-light">Save as Draft</button>
                    <button type="submit" class="btn btn-info text-white">Create Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Inventory Modal -->
<div class="modal fade" id="inventoryModal" tabindex="-1" aria-labelledby="inventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="inventoryModalLabel">Inventory Management</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="inventoryForm">
                <div class="modal-body">
                    <ul class="nav nav-tabs mb-3" id="inventoryTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="add-tab" data-bs-toggle="tab" data-bs-target="#addTab" type="button" role="tab" aria-controls="addTab" aria-selected="true">Add Stock</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="remove-tab" data-bs-toggle="tab" data-bs-target="#removeTab" type="button" role="tab" aria-controls="removeTab" aria-selected="false">Remove Stock</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="inventoryTabsContent">
                        <div class="tab-pane fade show active" id="addTab" role="tabpanel" aria-labelledby="add-tab">
                            <div class="mb-3">
                                <label for="itemName" class="form-label">Item Name</label>
                                <input type="text" class="form-control" id="itemName" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="quantity" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="unit" class="form-label">Unit</label>
                                    <select class="form-select" id="unit" required>
                                        <option value="pcs">Pieces</option>
                                        <option value="kg">Kilograms</option>
                                        <option value="l">Liters</option>
                                        <option value="m">Meters</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="removeTab" role="tabpanel" aria-labelledby="remove-tab">
                            <div class="mb-3">
                                <label for="removeItem" class="form-label">Select Item</label>
                                <select class="form-select" id="removeItem">
                                    <option value="">Select an item</option>
                                    <!-- Items will be populated dynamically -->
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="removeQuantity" class="form-label">Quantity to Remove</label>
                                <input type="number" class="form-control" id="removeQuantity">
                            </div>
                            <div class="mb-3">
                                <label for="reason" class="form-label">Reason</label>
                                <textarea class="form-control" id="reason" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">Process</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Transport Modal -->
<div class="modal fade" id="transportModal" tabindex="-1" aria-labelledby="transportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="transportModalLabel">Transport Request</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="transportForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="vehicleType" class="form-label">Vehicle Type</label>
                        <select class="form-select" id="vehicleType" required>
                            <option value="">Select vehicle type</option>
                            <option value="truck">Truck</option>
                            <option value="van">Van</option>
                            <option value="car">Car</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="pickupLocation" class="form-label">Pickup Location</label>
                        <input type="text" class="form-control" id="pickupLocation" required>
                    </div>
                    <div class="mb-3">
                        <label for="destination" class="form-label">Destination</label>
                        <input type="text" class="form-control" id="destination" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="pickupDate" class="form-label">Pickup Date</label>
                            <input type="datetime-local" class="form-control" id="pickupDate" required>
                        </div>
                        <div class="col-md-6">
                            <label for="returnDate" class="form-label">Return Date (if applicable)</label>
                            <input type="datetime-local" class="form-control" id="returnDate">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Request Transport</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Task Modal -->
<div class="modal fade" id="viewTaskModal1" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Task Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h6>Task #HK-1001</h6>
                    <p class="mb-1"><strong>Type:</strong> <span class="badge bg-info">Daily Cleaning</span></p>
                    <p class="mb-1"><strong>Location:</strong> Building A - Floor 3</p>
                    <p class="mb-1"><strong>Status:</strong> <span class="badge bg-success">Completed</span></p>
                    <p class="mb-1"><strong>Assigned To:</strong> Henry Martey</p>
                    <p class="mb-1"><strong>Due:</strong> Today, 10:00 AM</p>
                    <p class="mb-1"><strong>Description:</strong></p>
                    <p>Daily cleaning of all common areas including restrooms, hallways, and break rooms.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal1" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTaskForm1">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Task Type</label>
                        <select class="form-select" required>
                            <option value="cleaning">Daily Cleaning</option>
                            <option value="sanitization">Sanitization</option>
                            <option value="waste">Waste Disposal</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" class="form-control" value="Building A - Floor 3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" required>
                            <option value="pending">Pending</option>
                            <option value="in-progress">In Progress</option>
                            <option value="completed" selected>Completed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Assigned To</label>
                        <input type="text" class="form-control" value="Henry Martey" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Due Date & Time</label>
                        <input type="datetime-local" class="form-control" value="2025-08-20T10:00">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" rows="3">Daily cleaning of all common areas including restrooms, hallways, and break rooms.</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteTaskModal1" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this task?</p>
                <p class="mb-0"><strong>Task #HK-1001:</strong> Daily Cleaning - Building A - Floor 3</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete1">Delete Task</button>
            </div>
        </div>
    </div>
</div>

<!-- Housekeeping View Task Modal -->
<div class="modal fade" id="housekeepingViewTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <div class="d-flex align-items-center w-100">
                    <div class="flex-grow-1">
                        <h5 class="modal-title mb-0">
                            <i class="fas fa-broom me-2"></i>Task #HK-1002 - Daily Cleaning
                            <span class="badge bg-white text-info ms-2">In Progress</span>
                        </h5>
                        <div class="small mt-1">
                            <i class="far fa-calendar-alt me-1"></i> Created: Aug 20, 2025 | 
                            <i class="far fa-user me-1 ms-2"></i> Created By: Admin User
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Left Column -->
                    <div class="col-md-8 p-4">
                        <!-- Task Details -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-info-circle me-2 text-info"></i>Task Details
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex mb-2">
                                        <div class="text-muted" style="width: 120px;">Task Type:</div>
                                        <div>
                                            <span class="badge bg-info bg-opacity-10 text-info">
                                                <i class="fas fa-broom me-1"></i> Daily Cleaning
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex mb-2">
                                        <div class="text-muted" style="width: 120px;">Priority:</div>
                                        <div>
                                            <span class="badge bg-warning bg-opacity-10 text-warning">
                                                <i class="fas fa-flag me-1"></i> High
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex mb-2">
                                        <div class="text-muted" style="width: 120px;">Status:</div>
                                        <div>
                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                <i class="fas fa-circle-check me-1"></i> In Progress
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex mb-2">
                                        <div class="text-muted" style="width: 100px;">Start:</div>
                                        <div>Aug 20, 2025 - 09:00 AM</div>
                                    </div>
                                    <div class="d-flex mb-2">
                                        <div class="text-muted" style="width: 100px;">Due:</div>
                                        <div>Aug 20, 2025 - 12:00 PM</div>
                                    </div>
                                    <div class="d-flex mb-2">
                                        <div class="text-muted" style="width: 100px;">Duration:</div>
                                        <div>3 hours</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Location Details -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-map-marker-alt me-2 text-danger"></i>Location Details
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex mb-2">
                                        <div class="text-muted" style="width: 120px;">Building:</div>
                                        <div>Building B</div>
                                    </div>
                                    <div class="d-flex mb-2">
                                        <div class="text-muted" style="width: 120px;">Floor:</div>
                                        <div>2nd Floor</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex mb-2">
                                        <div class="text-muted" style="width: 100px;">Room/Area:</div>
                                        <div>Main Office Area</div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="text-muted" style="width: 100px;">Location Notes:</div>
                                        <div>Corner office and adjacent open space</div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 bg-light p-3 rounded">
                                <div id="taskMap" style="height: 200px; background-color: #f8f9fa; border-radius: 5px; display: flex; align-items: center; justify-content: center;">
                                    <div class="text-center">
                                        <i class="fas fa-map-marked-alt fa-3x text-muted mb-2"></i>
                                        <p class="mb-0 text-muted">Location Map Preview</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Task Description -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-align-left me-2 text-primary"></i>Description & Instructions
                            </h6>
                            <div class="p-3 bg-light rounded">
                                <h6>Task Description:</h6>
                                <p>Daily cleaning of all common areas including restrooms, hallways, and break rooms. Pay special attention to high-touch surfaces.</p>
                                
                                <h6 class="mt-4">Special Instructions:</h6>
                                <ul class="mb-0">
                                    <li>Use eco-friendly cleaning products only</li>
                                    <li>Disinfect all door handles and light switches</li>
                                    <li>Vacuum all carpeted areas</li>
                                    <li>Empty all trash and recycling bins</li>
                                    <li>Report any maintenance issues found</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Attachments -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-paperclip me-2 text-secondary"></i>Attachments
                            </h6>
                            <div class="d-flex flex-wrap gap-2">
                                <div class="border rounded p-2 d-flex align-items-center" style="width: 200px;">
                                    <div class="bg-light p-2 rounded me-2">
                                        <i class="far fa-file-pdf text-danger fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="text-truncate small" title="Cleaning Checklist.pdf">Cleaning Checklist.pdf</div>
                                        <div class="text-muted small">250 KB</div>
                                    </div>
                                    <a href="#" class="ms-2 text-muted"><i class="fas fa-download"></i></a>
                                </div>
                                <div class="border rounded p-2 d-flex align-items-center" style="width: 200px;">
                                    <div class="bg-light p-2 rounded me-2">
                                        <i class="far fa-image text-success fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="text-truncate small" title="Floor_Plan_2nd_Floor.png">Floor_Plan_2nd_Floor.png</div>
                                        <div class="text-muted small">1.2 MB</div>
                                    </div>
                                    <a href="#" class="ms-2 text-muted"><i class="fas fa-download"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-4 bg-light p-4">
                        <!-- Assigned To -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h6 class="card-title border-bottom pb-2 mb-3">
                                    <i class="fas fa-user-tie me-2 text-primary"></i>Assigned To
                                </h6>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-sm me-3">
                                        <span class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                            <i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">John Doe</h6>
                                        <p class="text-muted small mb-0">Housekeeping Staff</p>
                                    </div>
                                    <a href="#" class="text-muted"><i class="fas fa-envelope"></i></a>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-sm me-3">
                                        <span class="avatar-title bg-warning bg-opacity-10 text-warning rounded-circle">
                                            <i class="fas fa-user-shield"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">Sarah Johnson</h6>
                                        <p class="text-muted small mb-0">Supervisor</p>
                                    </div>
                                    <a href="#" class="text-muted"><i class="fas fa-envelope"></i></a>
                                </div>
                            </div>
                        </div>

                        <!-- Task Progress -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h6 class="card-title border-bottom pb-2 mb-3">
                                    <i class="fas fa-tasks me-2 text-success"></i>Task Progress
                                </h6>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Completion</span>
                                        <span>65%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 65%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Time Spent</span>
                                        <span>1h 45m / 3h 0m</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 58%;" aria-valuenow="58" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <button class="btn btn-sm btn-outline-success me-2">
                                        <i class="fas fa-check-circle me-1"></i> Mark Complete
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit me-1"></i> Update Progress
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Task History -->
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title border-bottom pb-2 mb-3">
                                    <i class="fas fa-history me-2 text-secondary"></i>Activity Log
                                </h6>
                                <div class="activity-feed">
                                    <div class="d-flex mb-3">
                                        <div class="avatar-xs flex-shrink-0 me-2">
                                            <span class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                                <i class="fas fa-user"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">Task Updated</h6>
                                            <p class="small text-muted mb-0">Marked as In Progress</p>
                                            <div class="small text-muted">Today, 9:15 AM</div>
                                        </div>
                                    </div>
                                    <div class="d-flex mb-3">
                                        <div class="avatar-xs flex-shrink-0 me-2">
                                            <span class="avatar-title bg-warning bg-opacity-10 text-warning rounded-circle">
                                                <i class="fas fa-user-shield"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">Task Assigned</h6>
                                            <p class="small text-muted mb-0">Assigned to John Doe</p>
                                            <div class="small text-muted">Today, 8:30 AM</div>
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="avatar-xs flex-shrink-0 me-2">
                                            <span class="avatar-title bg-info bg-opacity-10 text-info rounded-circle">
                                                <i class="fas fa-plus"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">Task Created</h6>
                                            <p class="small text-muted mb-0">By Admin User</p>
                                            <div class="small text-muted">Yesterday, 4:45 PM</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <div class="d-flex justify-content-between w-100">
                    <div>
                        <button type="button" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-print me-1"></i> Print
                        </button>
                        <button type="button" class="btn btn-outline-secondary">
                            <i class="fas fa-download me-1"></i> Export
                        </button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Close
                        </button>
                        <button type="button" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> Edit Task
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Housekeeping Edit Task Modal -->
<div class="modal fade" id="housekeepingEditTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Edit Housekeeping Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="housekeepingEditTaskForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Task Type</label>
                        <select class="form-select" required>
                            <option value="cleaning">Daily Cleaning</option>
                            <option value="sanitization">Sanitization</option>
                            <option value="waste">Waste Disposal</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" class="form-control" value="Building B - Floor 2" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" required>
                            <option value="pending">Pending</option>
                            <option value="in-progress">In Progress</option>
                            <option value="completed" selected>Completed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Assigned To</label>
                        <input type="text" class="form-control" value="John Doe" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Due Date & Time</label>
                        <input type="datetime-local" class="form-control" value="2025-08-20T12:00">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" rows="3">Daily cleaning of all common areas including restrooms, hallways, and break rooms.</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Housekeeping Delete Confirmation Modal -->
<div class="modal fade" id="housekeepingDeleteTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this task?</p>
                <p class="mb-0"><strong>Task #HK-1002:</strong> Daily Cleaning - Building B - Floor 2</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="housekeepingConfirmDelete">Delete Task</button>
            </div>
        </div>
    </div>
</div>

<!-- Security Modal -->
<div class="modal fade" id="securityModal" tabindex="-1" aria-labelledby="securityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="securityModalLabel">Security Incident Report</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="securityForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="incidentType" class="form-label">Incident Type</label>
                        <select class="form-select" id="incidentType" required>
                            <option value="theft">Theft</option>
                            <option value="unauthorized">Unauthorized Access</option>
                            <option value="safety">Safety Hazard</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="incidentLocation" class="form-label">Location</label>
                        <input type="text" class="form-control" id="incidentLocation" required>
                    </div>
                    <div class="mb-3">
                        <label for="incidentTime" class="form-label">Date & Time</label>
                        <input type="datetime-local" class="form-control" id="incidentTime" required>
                    </div>
                    <div class="mb-3">
                        <label for="incidentDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="incidentDescription" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="witnesses" class="form-label">Witnesses (if any)</label>
                        <input type="text" class="form-control" id="witnesses">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark">Submit Report</button>
                </div>
            </form>
        </div>
    </div>
</div>
