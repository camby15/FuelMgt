<!-- Edit Resource Modals -->
@foreach([
    1 => ['name' => 'Employee Handbook 2023', 'type' => 'PDF', 'size' => '2.4 MB', 'uploaded' => 'Sep 1, 2023', 'access' => 'public', 'access_level' => 'Public', 'badge' => 'success', 'description' => 'Comprehensive guide for all employees covering company policies, benefits, and procedures.'],
    2 => ['name' => 'Sales Training Manual', 'type' => 'DOCX', 'size' => '1.8 MB', 'uploaded' => 'Sep 15, 2023', 'access' => 'role', 'access_level' => 'Sales Team', 'badge' => 'info', 'description' => 'Training materials for the sales team including product information and sales techniques.'],
    3 => ['name' => 'Customer Service Training', 'type' => 'Video', 'size' => '45.2 MB', 'uploaded' => 'Aug 20, 2023', 'access' => 'department', 'access_level' => 'Customer Service', 'badge' => 'warning', 'description' => 'Video training for customer service representatives.']
] as $i => $resource)
<div class="modal fade" id="editResourceModal{{ $i }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Resource: {{ $resource['name'] }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editResourceForm{{ $i }}">
                    <div class="mb-3">
                        <label for="editResourceName{{ $i }}" class="form-label">Resource Name</label>
                        <input type="text" class="form-control" id="editResourceName{{ $i }}" value="{{ $resource['name'] }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="editResourceDescription{{ $i }}" class="form-label">Description</label>
                        <textarea class="form-control" id="editResourceDescription{{ $i }}" rows="3">{{ $resource['description'] }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Access Control</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="editResourceAccess{{ $i }}" id="editAccessPublic{{ $i }}" value="public" {{ $resource['access'] === 'public' ? 'checked' : '' }}>
                            <label class="form-check-label" for="editAccessPublic{{ $i }}">
                                Public (All employees)
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="editResourceAccess{{ $i }}" id="editAccessDepartment{{ $i }}" value="department" {{ $resource['access'] === 'department' ? 'checked' : '' }}>
                            <label class="form-check-label" for="editAccessDepartment{{ $i }}">
                                Specific Department
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="editResourceAccess{{ $i }}" id="editAccessRole{{ $i }}" value="role" {{ $resource['access'] === 'role' ? 'checked' : '' }}>
                            <label class="form-check-label" for="editAccessRole{{ $i }}">
                                Specific Role
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="editResourceAccess{{ $i }}" id="editAccessPrivate{{ $i }}" value="private" {{ $resource['access'] === 'private' ? 'checked' : '' }}>
                            <label class="form-check-label" for="editAccessPrivate{{ $i }}">
                                Private (Specific Employees)
                            </label>
                        </div>
                    </div>
                    <div class="mb-3" id="editAccessValueContainer{{ $i }}" style="display: {{ in_array($resource['access'], ['department', 'role', 'private']) ? 'block' : 'none' }};">
                        <label for="editResourceAccessValue{{ $i }}" class="form-label" id="editAccessTypeLabel{{ $i }}">
                            @if($resource['access'] === 'department') Select Departments
                            @elseif($resource['access'] === 'role') Select Roles
                            @elseif($resource['access'] === 'private') Select Employees
                            @else Select
                            @endif
                        </label>
                        <select class="form-select select2" id="editResourceAccessValue{{ $i }}" multiple>
                            @if($resource['access'] === 'department')
                                <option value="1" selected>Sales</option>
                                <option value="2">Marketing</option>
                                <option value="3">IT</option>
                            @elseif($resource['access'] === 'role')
                                <option value="1" selected>Sales Representative</option>
                                <option value="2">Sales Manager</option>
                                <option value="3">Account Executive</option>
                            @elseif($resource['access'] === 'private')
                                <option value="1" selected>John Doe</option>
                                <option value="2">Jane Smith</option>
                                <option value="3">Mike Johnson</option>
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editResourceExpiration{{ $i }}" class="form-label">Expiration Date (Optional)</label>
                        <input type="date" class="form-control" id="editResourceExpiration{{ $i }}">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger me-auto" data-bs-dismiss="modal">
                    <i class="fas fa-trash-alt me-1"></i> Delete
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize access control radio buttons
    document.querySelectorAll('input[name^="editResourceAccess"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const id = this.name.replace('editResourceAccess', '');
            const container = document.getElementById(`editAccessValueContainer${id}`);
            const label = document.getElementById(`editAccessTypeLabel${id}`);
            const select = document.getElementById(`editResourceAccessValue${id}`);
            
            if (!container || !label) return;
            
            if (this.value === 'public') {
                container.style.display = 'none';
            } else {
                container.style.display = 'block';
                
                // Update label and options based on selection
                if (this.value === 'department') {
                    label.textContent = 'Select Departments';
                    // Populate with departments
                    select.innerHTML = `
                        <option value="1">Sales</option>
                        <option value="2">Marketing</option>
                        <option value="3">IT</option>
                        <option value="4">HR</option>
                        <option value="5">Finance</option>
                    `;
                } else if (this.value === 'role') {
                    label.textContent = 'Select Roles';
                    // Populate with roles
                    select.innerHTML = `
                        <option value="1">Manager</option>
                        <option value="2">Team Lead</option>
                        <option value="3">Employee</option>
                        <option value="4">Intern</option>
                    `;
                } else if (this.value === 'private') {
                    label.textContent = 'Select Employees';
                    // Populate with employees
                    select.innerHTML = `
                        <option value="1">John Doe</option>
                        <option value="2">Jane Smith</option>
                        <option value="3">Mike Johnson</option>
                        <option value="4">Sarah Williams</option>
                    `;
                }
                
                // Reinitialize Select2 if available
                if (typeof $ !== 'undefined' && $.fn.select2) {
                    $(select).select2({
                        theme: 'bootstrap-5',
                        placeholder: 'Select...',
                        width: '100%',
                        allowClear: true,
                        closeOnSelect: false
                    });
                }
            }
        });
        
        // Trigger change event for pre-selected radio buttons
        if (radio.checked) {
            radio.dispatchEvent(new Event('change'));
        }
    });
    
    // Initialize delete button handlers
    document.querySelectorAll('[data-bs-target^="#editResourceModal"]').forEach(button => {
        const modalId = button.getAttribute('data-bs-target');
        const modalEl = document.querySelector(modalId);
        if (!modalEl) return;
        
        const modal = new bootstrap.Modal(modalEl);
        
        // Add click handler for delete button
        const deleteBtn = modalEl.querySelector('.btn-danger');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function() {
                if (confirm('Are you sure you want to delete this resource? This action cannot be undone.')) {
                    // Add delete logic here
                    console.log('Deleting resource', modalId);
                    modal.hide();
                    // Show success message
                    alert('Resource deleted successfully');
                }
            });
        }
        
        // Add click handler for save button
        const saveBtn = modalEl.querySelector('.btn-primary');
        if (saveBtn) {
            saveBtn.addEventListener('click', function() {
                // Add save logic here
                const form = modalEl.querySelector('form');
                const formData = new FormData(form);
                console.log('Saving resource', Object.fromEntries(formData));
                modal.hide();
                // Show success message
                alert('Resource updated successfully');
            });
        }
    });
});
</script>
@endpush
