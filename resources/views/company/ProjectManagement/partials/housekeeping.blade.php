<!-- Housekeeping Tasks Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-centered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Task Type</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Due Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Sample Task 1 -->
                    <tr>
                        <td>#HK-1001</td>
                        <td><span class="badge bg-info">Daily Cleaning</span></td>
                        <td>Building A - Floor 3</td>
                        <td><span class="badge bg-success">Completed</span></td>
                        <td>Henry Martey</td>
                        <td>Today, 10:00 AM</td>
                        <td class="text-nowrap">
                            <button class="btn btn-icon btn-sm btn-outline-primary me-1" title="View Details" data-bs-toggle="modal" data-bs-target="#viewTaskModal1">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-icon btn-sm btn-outline-success me-1" title="Edit" data-bs-toggle="modal" data-bs-target="#editTaskModal1">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-icon btn-sm btn-outline-danger me-1" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteTaskModal1">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Sample Task 2 -->
                    <tr>
                        <td>#HK-1002</td>
                        <td><span class="badge bg-warning">Trash Removal</span></td>
                        <td>Building B - Lobby</td>
                        <td><span class="badge bg-warning">In Progress</span></td>
                        <td>Patrick Asare</td>
                        <td>Today, 2:00 PM</td>
                        <td class="text-nowrap">
                            <button class="btn btn-icon btn-sm btn-outline-primary me-1" title="View Details" data-bs-toggle="modal" data-bs-target="#viewTaskModal2">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-icon btn-sm btn-outline-success me-1" title="Edit" data-bs-toggle="modal" data-bs-target="#editTaskModal2">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-icon btn-sm btn-outline-danger me-1" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteTaskModal2">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
