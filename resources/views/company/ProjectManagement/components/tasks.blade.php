<div class="tab-pane fade {{ session('active_tab') === 'tasks' ? 'show active' : '' }}" id="tasks" role="tabpanel" aria-labelledby="tasks-tab">
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <h5>Tasks</h5>
        <button type="button" class="btn btn-gesl-primary" data-bs-toggle="modal" data-bs-target="#createTaskModal">
            <i class="fas fa-plus me-1"></i> Assign New Task
        </button>
    </div>

    @php
        $tasks = \App\Models\ProjectManagement\Task::where('company_id', Session::get('selected_company_id'))
            ->with(['project', 'assignedTeam'])
            ->orderBy('created_at', 'desc')
            ->get();
    @endphp

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Project</th>
                    <th>Assigned To</th>
                    <th>Due Date</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($tasks->count() > 0)
                    @foreach($tasks as $task)
                <tr>
                    <td>
                            <div>
                                    <strong>{{ $task->title }}</strong>
                                    @if($task->description)
                                        <br><small class="text-muted">{{ Str::limit($task->description, 50) }}</small>
                                    @endif
                        </div>
                    </td>
                            <td>{{ $task->project_name ?? 'N/A' }} <small class="text-muted">(ID: {{ $task->project_id }})</small></td>
                            <td>{{ $task->team_name ?? 'N/A' }}</td>
                            <td>{{ $task->formatted_due_date ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'success') }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $task->status === 'completed' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($task->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <!-- Edit Button -->
                                    <button type="button" class="btn btn-sm btn-outline-primary action-btn edit-task-btn" title="Edit"
                                            data-task-id="{{ $task->id }}"
                                            data-task-title="{{ $task->title }}"
                                            data-task-description="{{ $task->description }}"
                                            data-project-id="{{ $task->project_id }}"
                                            data-team-id="{{ $task->assigned_team_id }}"
                                            data-due-date="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}"
                                            data-priority="{{ $task->priority }}"
                                            data-status="{{ $task->status }}"
                                            data-progress="{{ $task->progress }}"
                                            data-notes="{{ $task->notes }}">
                                        <i class="fa-solid fa-edit"></i>
                                    </button>

                                    <!-- Status Update Button -->
                                    <form method="POST" action="{{ route('company.tasks.status', $task->id) }}" style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="{{ $task->status === 'completed' ? 'pending' : 'completed' }}">
                                        <button type="submit" class="btn btn-sm btn-outline-{{ $task->status === 'completed' ? 'warning' : 'success' }} action-btn update-status-btn" title="{{ $task->status === 'completed' ? 'Mark as Pending' : 'Mark as Completed' }}"
                                                data-task-id="{{ $task->id }}"
                                                data-task-title="{{ $task->title }}">
                                            <i class="fa-solid fa-{{ $task->status === 'completed' ? 'clock' : 'check' }}"></i>
                                        </button>
                                    </form>

                                    <!-- Delete Button -->
                                    <form method="POST" action="{{ route('company.tasks.destroy', $task->id) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-danger action-btn delete-task-btn"
                                                style="background-color: #dc3545 !important; border-color: #dc3545 !important; color: white !important;"
                                                onmouseover="this.style.backgroundColor='#c82333'"
                                                onmouseout="this.style.backgroundColor='#dc3545'"
                                                title="Delete"
                                                data-task-title="{{ $task->title }}">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                        </div>
                    </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center">No tasks found</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Create Task Modal -->
    <div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTaskModalLabel">Assign New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createTaskForm" method="POST" action="{{ route('company.tasks.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="taskTitle" class="form-label">Task Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="taskTitle" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="projectId" class="form-label">Project <span class="text-danger">*</span></label>
                                <select class="form-control @error('project_id') is-invalid @enderror" id="projectId" name="project_id" required>
                                    <option value="">Select Project</option>
                                    @php
                                        $projects = \App\Models\ProjectManagement\Project::where('company_id', Session::get('selected_company_id'))
                                            ->orderBy('name', 'asc')
                                            ->get();
                                    @endphp
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" data-end-date="{{ $project->end_date ? $project->end_date->format('Y-m-d') : '' }}"
                                                {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="assignedTo" class="form-label">Assign To Team <span class="text-danger">*</span></label>
                                <select class="form-control @error('assigned_team_id') is-invalid @enderror" id="assignedTo" name="assigned_team_id" required>
                                    <option value="">Select Team</option>
                                    @php
                                        $teams = \App\Models\TeamParing::where('company_id', Session::get('selected_company_id'))
                                            ->orderBy('team_name', 'asc')
                                            ->get();
                                    @endphp
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}" {{ old('assigned_team_id') == $team->id ? 'selected' : '' }}>
                                            {{ $team->team_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_team_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="dueDate" class="form-label">Due Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                                       id="dueDate" name="due_date" value="{{ old('due_date') }}" required>
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="priority" class="form-label">Priority</label>
                                <select class="form-control @error('priority') is-invalid @enderror" id="priority" name="priority">
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="taskDescription" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="taskDescription" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="taskNotes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="taskNotes" name="notes" rows="2">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-gesl-primary" id="createTaskBtn">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editTaskForm" method="POST" action="{{ route('company.tasks.update', ':id') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editTaskId" name="task_id">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editTaskTitle" class="form-label">Task Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="editTaskTitle" name="title" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="editProjectId" class="form-label">Project <span class="text-danger">*</span></label>
                                <select class="form-select @error('project_id') is-invalid @enderror" id="editProjectId" name="project_id" required>
                                    <option value="">Select Project</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" data-end-date="{{ $project->end_date ? $project->end_date->format('Y-m-d') : '' }}">
                                            {{ $project->name }} (ID: {{ $project->id }})
                                        </option>
                                    @endforeach
                                </select>
                                <!-- Debug: Available projects count: {{ $projects->count() }} -->
                                @error('project_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editAssignedTo" class="form-label">Assign To Team <span class="text-danger">*</span></label>
                                <select class="form-select @error('assigned_team_id') is-invalid @enderror" id="editAssignedTo" name="assigned_team_id" required>
                                    <option value="">Select Team</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}">
                                            {{ $team->team_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_team_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="editDueDate" class="form-label">Due Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                                       id="editDueDate" name="due_date" required>
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editPriority" class="form-label">Priority</label>
                                <select class="form-select @error('priority') is-invalid @enderror" id="editPriority" name="priority">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="editStatus" class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="editStatus" name="status">
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="editTaskDescription" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="editTaskDescription" name="description" rows="3"></textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="editTaskNotes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="editTaskNotes" name="notes" rows="2"></textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-gesl-primary">Update Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('script')
@parent
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Task Management Functions
window.editTask = function(id, title, description, projectId, teamId, dueDate, priority, status, progress, notes) {
    console.log('editTask called with:', {id, title, description, projectId, teamId, dueDate, priority, status, progress, notes});
    
    // Update form action URL
    const form = document.getElementById('editTaskForm');
    if (!form) {
        console.error('editTaskForm not found');
        return;
    }
    form.action = form.action.replace(':id', id);

    // Set task ID
    const taskIdField = document.getElementById('editTaskId');
    if (!taskIdField) {
        console.error('editTaskId field not found');
        return;
    }
    taskIdField.value = id;

    // Populate form fields with error checking
    const titleField = document.getElementById('editTaskTitle');
    if (titleField) {
        titleField.value = title;
        console.log('Set title field:', title);
    } else {
        console.error('editTaskTitle field not found');
    }
    
    const descField = document.getElementById('editTaskDescription');
    if (descField) {
        descField.value = description || '';
        console.log('Set description field:', description);
    } else {
        console.error('editTaskDescription field not found');
    }
    
    // Project field will be set in the setTimeout after modal is shown
    
    // All other fields will be set in the setTimeout after modal is shown

    // Show modal first
    const editModal = new bootstrap.Modal(document.getElementById('editTaskModal'));
    editModal.show();
    
    // Wait for modal to be shown before populating fields
    setTimeout(() => {
        console.log('Modal should be visible now, populating fields...');
        
        // Double-check that the modal is actually visible
        const modal = document.getElementById('editTaskModal');
        if (!modal || !modal.classList.contains('show')) {
            console.warn('Modal is not fully shown yet, waiting a bit more...');
            setTimeout(() => {
                populateEditFields();
            }, 200);
            return;
        }
        
        populateEditFields();
    }, 300);
    
    function populateEditFields() {
        
        // Populate fields after modal is shown
        const titleField = document.getElementById('editTaskTitle');
        if (titleField) titleField.value = title;
        
        const descField = document.getElementById('editTaskDescription');
        if (descField) descField.value = description || '';
        
        // Debug: Check what elements exist with editProjectId
        const allElementsWithId = document.querySelectorAll('#editProjectId');
        console.log('All elements with editProjectId:', allElementsWithId);
        
        const projectField = document.querySelector('select#editProjectId');
        if (projectField) {
            console.log('=== PROJECT FIELD DEBUG ===');
            console.log('Project field found:', projectField);
            console.log('Project field options:', projectField.options);
            
            // Check if options exist
            if (projectField.options) {
                console.log('Available project options:', Array.from(projectField.options).map(opt => ({value: opt.value, text: opt.text})));
            } else {
                console.error('Project field options are undefined!');
            }
            
            console.log('Trying to set project field to:', projectId, 'Type:', typeof projectId);
            
            // Convert projectId to string to match option values
            const projectIdStr = String(projectId);
            console.log('Converted project ID to string:', projectIdStr);
            
            // Check if the projectId exists in the options
            let projectExists = false;
            if (projectField.options) {
                projectExists = Array.from(projectField.options).some(opt => opt.value === projectIdStr);
                console.log('Project ID exists in options:', projectExists);
            } else {
                console.error('Cannot check project existence - options are undefined');
            }
            
            // Try to set the value using string version
            projectField.value = projectIdStr;
            console.log('Set project field to:', projectIdStr, 'Current value:', projectField.value);
            
            // If the value wasn't set, try to find and select the option manually
            if (projectField.value !== projectIdStr) {
                console.warn('Project field value was not set correctly. Trying manual selection...');
                const targetOption = projectField.querySelector(`option[value="${projectIdStr}"]`);
                if (targetOption) {
                    targetOption.selected = true;
                    // Trigger change event to ensure the selection is registered
                    projectField.dispatchEvent(new Event('change', { bubbles: true }));
                    console.log('Manually selected project option:', projectIdStr);
                } else {
                    console.error('Project option not found for ID:', projectIdStr);
                    if (projectField.options) {
                        console.log('Available option values:', Array.from(projectField.options).map(opt => opt.value));
                    } else {
                        console.error('Cannot show available options - options are undefined');
                    }
                }
            } else {
                console.log('✅ Project field set successfully!');
            }
            console.log('=== END PROJECT FIELD DEBUG ===');
        } else {
            console.error('editProjectId field not found');
        }
        
        const teamField = document.getElementById('editAssignedTo');
        if (teamField) teamField.value = teamId;
        
        const dueDateField = document.getElementById('editDueDate');
        if (dueDateField) dueDateField.value = dueDate;
        
        const priorityField = document.getElementById('editPriority');
        if (priorityField) priorityField.value = priority;
        
        const statusField = document.getElementById('editStatus');
        if (statusField) statusField.value = status;
        
        const notesField = document.getElementById('editTaskNotes');
        if (notesField) notesField.value = notes || '';
        
        // Auto-populate due date based on selected project (after fields are set)
        if (projectField && dueDateField) {
            // Find the selected option by value
            const selectedOption = projectField.querySelector(`option[value="${projectId}"]`);
            if (selectedOption) {
                const endDate = selectedOption.getAttribute('data-end-date');
                if (endDate && endDate !== '') {
                    dueDateField.value = endDate;
                    console.log('Auto-populated due date from project:', endDate);
                }
            } else {
                console.log('Selected project option not found for projectId:', projectId);
            }
        }
    }
}

// Make functions globally accessible
window.confirmDelete = function(event, taskTitle) {
    console.log('confirmDelete called with:', taskTitle);
    event.preventDefault();
    const form = event.target.closest('form');

    console.log('SweetAlert available:', typeof Swal !== 'undefined');
    
    Swal.fire({
        title: 'Are you sure?',
        text: `Do you want to delete "${taskTitle}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
    return false;
};

window.updateTaskStatus = function(event, taskId, taskTitle) {
    event.preventDefault();
    const form = event.target.closest('form');
    const status = form.querySelector('input[name="status"]').value;
    const statusText = status === 'completed' ? 'completed' : 'pending';

    Swal.fire({
        title: 'Update Task Status',
        text: `Mark "${taskTitle}" as ${statusText}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, update it!'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
    return false;
};

// Auto-populate due date when project is selected
document.addEventListener('DOMContentLoaded', function() {
    const projectSelect = document.getElementById('projectId');
    const dueDateInput = document.getElementById('dueDate');
    
    if (projectSelect && dueDateInput) {
        projectSelect.addEventListener('change', function() {
            console.log('Project changed!');
            const selectedOption = this.options[this.selectedIndex];
            const endDate = selectedOption.getAttribute('data-end-date');
            
            if (endDate && endDate !== '') {
                dueDateInput.value = endDate;
                console.log('Set due date to:', endDate);
            } else {
                dueDateInput.value = '';
                console.log('Cleared due date');
            }
        });
    } else {
        console.log('Project select or due date input element not found');
    }

    // Add form submission debugging
    const editTaskForm = document.getElementById('editTaskForm');
    if (editTaskForm) {
        editTaskForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent normal form submission
            
            console.log('Edit task form submitted!');
            console.log('Form action:', this.action);
            
            // Add loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Updating...';
            }
            
            // Submit form via AJAX
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => {
                if (response.ok) {
                    // Check if response is JSON
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        // If not JSON, assume success and return a success object
                        return { success: true, message: 'Task updated successfully' };
                    }
                }
                throw new Error('Network response was not ok');
            })
            .then(data => {
                console.log('Edit response data:', data);
                
                // Hide the modal first
                const modal = bootstrap.Modal.getInstance(document.getElementById('editTaskModal'));
                if (modal) {
                    modal.hide();
                }
                
                if (data.success) {
                    // Show success message
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Task Updated!',
                            text: data.message || 'Task has been updated successfully',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#28a745',
                            timer: 3000,
                            timerProgressBar: true
                        }).then((result) => {
                            if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                                // Reload the page to show the updated task
                                window.location.reload();
                            }
                        });
                    } else {
                        // Fallback to normal form submission
                        alert('Task updated successfully!');
                        window.location.reload();
                    }
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        let errorMessage = 'Please fix the following errors:\n';
                        for (const field in data.errors) {
                            errorMessage += `• ${data.errors[field].join(', ')}\n`;
                        }
                        
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: errorMessage,
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#dc3545'
                            });
                        } else {
                            alert(errorMessage);
                        }
                    } else {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message || 'Failed to update task',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#dc3545'
                            });
                        } else {
                            alert(data.message || 'Failed to update task');
                        }
                    }
                    
                    // Reset button state
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = 'Update Task';
                    }
                }
            })
            .catch(error => {
                console.error('Error updating task:', error);
                
                // Hide the modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editTaskModal'));
                if (modal) {
                    modal.hide();
                }
                
                // Reset button state
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Update Task';
                }
                
                // Show error message
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to update task. Please try again.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#dc3545'
                    });
                } else {
                    alert('Failed to update task. Please try again.');
                }
            });
        });
    }

    // Auto-populate due date for edit modal
    const editProjectSelect = document.getElementById('editProjectId');
    const editDueDateInput = document.getElementById('editDueDate');
    
    if (editProjectSelect && editDueDateInput) {
        editProjectSelect.addEventListener('change', function() {
            console.log('Edit project changed!');
            const selectedOption = this.options[this.selectedIndex];
            const endDate = selectedOption.getAttribute('data-end-date');
            
            if (endDate && endDate !== '') {
                editDueDateInput.value = endDate;
                console.log('Set edit due date to:', endDate);
            } else {
                editDueDateInput.value = '';
                console.log('Cleared edit due date');
            }
        });
    } else {
        console.log('Edit project select element not found');
    }

    // Event listeners for action buttons
    // Edit task buttons
    document.querySelectorAll('.edit-task-btn').forEach(button => {
        button.addEventListener('click', function() {
            const taskId = this.getAttribute('data-task-id');
            const taskTitle = this.getAttribute('data-task-title');
            const taskDescription = this.getAttribute('data-task-description');
            const projectId = this.getAttribute('data-project-id');
            const teamId = this.getAttribute('data-team-id');
            const dueDate = this.getAttribute('data-due-date');
            const priority = this.getAttribute('data-priority');
            const status = this.getAttribute('data-status');
            const progress = this.getAttribute('data-progress');
            const notes = this.getAttribute('data-notes');
            
            console.log('Edit button clicked with data:', {
                taskId, taskTitle, taskDescription, projectId, teamId, dueDate, priority, status, progress, notes
            });
            editTask(taskId, taskTitle, taskDescription, projectId, teamId, dueDate, priority, status, progress, notes);
        });
    });

    // Delete task buttons
    document.querySelectorAll('.delete-task-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const taskTitle = this.getAttribute('data-task-title');
            confirmDelete(event, taskTitle);
        });
    });

    // Update status buttons
    document.querySelectorAll('.update-status-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const taskId = this.getAttribute('data-task-id');
            const taskTitle = this.getAttribute('data-task-title');
            updateTaskStatus(event, taskId, taskTitle);
        });
    });

    // Handle create task form submission with SweetAlert
    const createTaskForm = document.getElementById('createTaskForm');
    if (createTaskForm) {
        createTaskForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            console.log('=== CREATE TASK FORM SUBMITTED ===');
            console.log('SweetAlert available:', typeof Swal !== 'undefined');
            
            const form = e.target;
            const taskTitle = form.querySelector('#taskTitle').value;
            const submitBtn = form.querySelector('#createTaskBtn');
            
            console.log('Task title:', taskTitle);
            
            // Show loading state
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Creating...';
            
            // Prepare form data
            const formData = new FormData(form);
            
            // Submit form via fetch to handle response
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (response.ok) {
                    // Check if response is JSON
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        // If not JSON, assume success and return a success object
                        return { success: true, message: 'Task created successfully' };
                    }
                }
                throw new Error('Network response was not ok');
            })
            .then(data => {
                console.log('Response data:', data);
                
                // Hide the modal first
                const modal = bootstrap.Modal.getInstance(document.getElementById('createTaskModal'));
                if (modal) {
                    modal.hide();
                }
                
                if (data.success) {
                    // Show success message
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Task Created!',
                            text: data.message || `Task "${taskTitle}" has been created successfully`,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#28a745',
                            timer: 3000,
                            timerProgressBar: true
                        }).then((result) => {
                            if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                                // Reload the page to show the new task
                                window.location.reload();
                            }
                        });
                    } else {
                        // Fallback to normal form submission
                        alert('Task created successfully!');
                        window.location.reload();
                    }
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        let errorMessage = 'Please fix the following errors:\n';
                        for (const field in data.errors) {
                            errorMessage += `• ${data.errors[field].join(', ')}\n`;
                        }
                        
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: errorMessage,
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#dc3545'
                            });
                        } else {
                            alert(errorMessage);
                        }
                    } else {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message || 'Failed to create task',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#dc3545'
                            });
                        } else {
                            alert(data.message || 'Failed to create task');
                        }
                    }
                    
                    // Reset button state
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Hide the modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('createTaskModal'));
                if (modal) {
                    modal.hide();
                }
                
                // Reset button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                
                // Show error message
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to create task. Please try again.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#dc3545'
                    });
                } else {
                    alert('Failed to create task. Please try again.');
                }
            });
        });
    }

    // Reset forms when modals are hidden
    document.getElementById('createTaskModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('createTaskForm').reset();
        const createBtn = document.getElementById('createTaskBtn');
        if (createBtn) {
            createBtn.disabled = false;
            createBtn.innerHTML = 'Create Task';
        }
    });

    document.getElementById('editTaskModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('editTaskForm').reset();
    });

    // Handle session messages (same pattern as projects)
    @if(session('success'))
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#28a745',
                timer: 3000,
                timerProgressBar: true
            });
        }
    @endif

    @if(session('error'))
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
        }
    @endif
});
</script>
@endsection