@push('javascript')
<!-- SweetAlert2 Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2
    }).format(amount || 0);
}

// Format date
function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
}

// Calculate days between dates
function getDaysBetween(startDate, endDate) {
    if (!startDate || !endDate) return 0;
    const diffTime = Math.abs(new Date(endDate) - new Date(startDate));
    return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
}

// Format status badge
function getStatusBadge(status) {
    const statusMap = {
        'not_started': { class: 'bg-secondary', text: 'Not Started' },
        'in_progress': { class: 'bg-primary', text: 'In Progress' },
        'on_hold': { class: 'bg-warning', text: 'On Hold' },
        'completed': { class: 'bg-success', text: 'Completed' },
        'cancelled': { class: 'bg-danger', text: 'Cancelled' }
    };
    const statusInfo = statusMap[status] || { class: 'bg-secondary', text: 'Unknown' };
    return `<span class="badge ${statusInfo.class}">${statusInfo.text}</span>`;
}

// Handle export project report
document.addEventListener('click', function(e) {
    if (e.target.closest('#exportProjectReport')) {
        e.preventDefault();
        
        // Show loading state
        const exportBtn = e.target.closest('#exportProjectReport');
        const originalHtml = exportBtn.innerHTML;
        exportBtn.disabled = true;
        exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Exporting...';
        
        // Get the project ID from the view modal
        const viewModal = document.getElementById('viewProjectModal');
        const projectId = viewModal ? viewModal.dataset.projectId : null;
        
        if (!projectId) {
            console.error('No project ID found for export');
            Swal.fire({
                icon: 'error',
                title: 'Export Failed',
                text: 'Unable to export: Project ID not found',
                confirmButtonColor: '#3b7ddd',
                confirmButtonText: 'OK'
            });
            exportBtn.disabled = false;
            exportBtn.innerHTML = originalHtml;
            return;
        }
        
        // Create form to submit with CSRF token
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = '/company/projects/export-csv';
        form.style.display = 'none';
        
        // Add project ID
        const projectIdInput = document.createElement('input');
        projectIdInput.type = 'hidden';
        projectIdInput.name = 'project_id';
        projectIdInput.value = projectId;
        form.appendChild(projectIdInput);
        
        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
        
        // Add form to document and submit
        document.body.appendChild(form);
        form.submit();
        
        // Clean up
        setTimeout(() => {
            document.body.removeChild(form);
            
            // Re-enable the button
            exportBtn.disabled = false;
            exportBtn.innerHTML = originalHtml;
            
            // Show success message
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Export Started',
                    text: 'Your export will begin downloading shortly.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        }, 500);
    }
});

// Handle view project button click
document.addEventListener('click', function(e) {
    if (e.target.closest('.view-project')) {
        e.preventDefault();
        const button = e.target.closest('.view-project');
        const projectId = button.dataset.id;
        
        // Store the project ID in the modal for export and edit
        const viewModal = document.getElementById('viewProjectModal');
        viewModal.dataset.projectId = projectId;
        
        // Set up the edit button to trigger the edit modal
        const editBtn = document.getElementById('editProjectBtn');
        if (editBtn) {
            editBtn.onclick = function() {
                const editModal = new bootstrap.Modal(document.getElementById('editProjectModal'));
                const editButton = document.querySelector(`.edit-project[data-id="${projectId}"]`);
                if (editButton) {
                    editButton.click();
                }
                bootstrap.Modal.getInstance(viewModal).hide();
            };
        }
        
        // Get all the data from data attributes
        const projectData = {
            id: projectId,
            name: button.dataset.name || 'Untitled Project',
            code: button.dataset.projectCode || 'N/A',
            description: button.dataset.description || 'No description available.',
            startDate: button.dataset.startDate,
            endDate: button.dataset.endDate,
            budget: parseFloat(button.dataset.budget) || 0,
            progress: parseInt(button.dataset.progress) || 0,
            status: button.dataset.status || 'not_started',
            manager: button.dataset.managerName || 'Not assigned',
            type: button.dataset.projectType || 'Not specified'
        };
        
        // Update the modal with project data
        document.getElementById('viewProjectName').textContent = projectData.name;
        document.getElementById('viewProjectCode').textContent = `#${projectData.code}`;
        document.getElementById('viewProjectDescription').textContent = projectData.description;
        document.getElementById('viewProjectManager').textContent = projectData.manager;
        document.getElementById('viewProjectType').textContent = projectData.type;
        document.getElementById('viewProjectBudget').textContent = formatCurrency(projectData.budget);
        
        // Update progress
        const progressBar = document.getElementById('viewProjectProgressBar');
        const progressText = document.getElementById('viewProjectProgressText');
        progressBar.style.width = `${projectData.progress}%`;
        progressBar.setAttribute('aria-valuenow', projectData.progress);
        progressText.textContent = projectData.progress;
        
        // Update status
        document.getElementById('viewProjectStatus').innerHTML = getStatusBadge(projectData.status);
        
        // Update timeline
        const daysLeft = projectData.endDate ? 
            getDaysBetween(new Date(), projectData.endDate) : 0;
        const timelineText = projectData.endDate ? 
            `${formatDate(projectData.startDate)} - ${formatDate(projectData.endDate)}` : 'No end date';
            
        document.getElementById('viewProjectTimeline').textContent = timelineText;
        document.getElementById('viewProjectDaysLeft').textContent = daysLeft >= 0 ? daysLeft : 0;
        
        // Store the current project ID for export
        currentProjectId = projectId;
        
        // Show the modal
        const modal = new bootstrap.Modal(document.getElementById('viewProjectModal'));
        modal.show();
    }
});

// Add event listeners for modal close events to refresh data dynamically
document.addEventListener('DOMContentLoaded', function() {
    const viewModal = document.getElementById('viewProjectModal');
    const editModal = document.getElementById('editProjectModal');
    
    // Track if any changes were made that require refresh
    let needsRefresh = false;
    
    // Listen for view modal close events
    if (viewModal) {
        viewModal.addEventListener('hidden.bs.modal', function() {
            console.log('View modal closed - refreshing project data...');
            // Always refresh when view modal closes to ensure data is up-to-date
            refreshProjectData();
        });
    }
    
    // Listen for edit modal close events
    if (editModal) {
        editModal.addEventListener('hidden.bs.modal', function() {
            console.log('Edit modal closed');
            // Always refresh after edit modal closes (since editing might have occurred)
            console.log('Edit modal closed - refreshing project data...');
            refreshProjectData();
        });
    }
    
    // Listen for form submissions to mark that refresh is needed
    document.addEventListener('submit', function(e) {
        if (e.target.id === 'editProjectForm' || e.target.id === 'createProjectForm') {
            needsRefresh = true;
            console.log('Form submitted - will refresh on modal close');
        }
    });
    
    // Listen for delete form submissions
    document.addEventListener('submit', function(e) {
        if (e.target.classList.contains('delete-project-form')) {
            needsRefresh = true;
            console.log('Delete form submitted - will refresh on modal close');
        }
    });
});

// Function to refresh project data dynamically - Immediate refresh
function refreshProjectData() {
    console.log('=== REFRESHING PROJECT DATA ===');
    
    // Show a subtle loading indicator on the refresh button
    const reloadBtn = document.getElementById('reloadDataBtn');
    if (reloadBtn) {
        reloadBtn.disabled = true;
        reloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Refreshing...';
    }
    
    // Immediate page reload - no delay
    console.log('Refreshing page immediately...');
    window.location.reload();
}

// Function to filter projects based on search term
function filterProjects(searchTerm) {
    console.log('=== FILTERING PROJECTS ===');
    console.log('Search term:', searchTerm);
    
    const projectsTableBody = document.getElementById('projectsTableBody');
    if (!projectsTableBody) {
        console.error('Projects table body not found');
        return;
    }
    
    const rows = projectsTableBody.querySelectorAll('tr');
    let visibleCount = 0;
    let totalCount = 0;
    
    rows.forEach((row, index) => {
        // Skip the "No projects found" row if it exists
        if (row.querySelector('td[colspan]')) {
            return;
        }
        
        totalCount++;
        
        // Get project data from the row
        const projectName = row.querySelector('strong')?.textContent?.toLowerCase() || '';
        const projectCode = row.querySelector('small')?.textContent?.toLowerCase() || '';
        const projectDescription = row.querySelectorAll('small')[1]?.textContent?.toLowerCase() || '';
        const managerName = row.querySelector('.badge.bg-light')?.textContent?.toLowerCase() || '';
        
        // Check if search term matches any of the project data
        const matches = searchTerm === '' || 
            projectName.includes(searchTerm) ||
            projectCode.includes(searchTerm) ||
            projectDescription.includes(searchTerm) ||
            managerName.includes(searchTerm);
        
        if (matches) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Show/hide "No projects found" message
    let noProjectsRow = projectsTableBody.querySelector('tr td[colspan]');
    if (!noProjectsRow) {
        // Create "No projects found" row if it doesn't exist
        const newRow = document.createElement('tr');
        newRow.id = 'noProjectsRow';
        newRow.innerHTML = `
            <td colspan="9" class="text-center py-4">
                <div class="text-muted">
                    <i class="fas fa-search fa-2x mb-2"></i>
                    <p class="mb-0">No projects found matching "${searchTerm}"</p>
                </div>
            </td>
        `;
        projectsTableBody.appendChild(newRow);
        noProjectsRow = newRow;
    }
    
    // Show "No projects found" if no matches
    if (visibleCount === 0 && searchTerm !== '') {
        noProjectsRow.style.display = '';
        noProjectsRow.querySelector('p').textContent = `No projects found matching "${searchTerm}"`;
    } else {
        noProjectsRow.style.display = 'none';
    }
    
    console.log(`Filtered results: ${visibleCount} of ${totalCount} projects visible`);
    
    // Show search results summary
    if (searchTerm !== '') {
        showSearchResults(visibleCount, totalCount, searchTerm);
    } else {
        hideSearchResults();
    }
}

// Function to show search results summary
function showSearchResults(visibleCount, totalCount, searchTerm) {
    let resultsDiv = document.getElementById('searchResults');
    if (!resultsDiv) {
        resultsDiv = document.createElement('div');
        resultsDiv.id = 'searchResults';
        resultsDiv.className = 'alert alert-info alert-dismissible fade show mt-3';
        resultsDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-search me-2"></i>
                    <span id="searchResultsText"></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        // Insert after the search bar
        const searchContainer = document.querySelector('.input-group');
        if (searchContainer) {
            searchContainer.parentNode.insertBefore(resultsDiv, searchContainer.nextSibling);
        }
    }
    
    const resultsText = document.getElementById('searchResultsText');
    if (resultsText) {
        if (visibleCount === 0) {
            resultsText.textContent = `No projects found matching "${searchTerm}"`;
        } else {
            resultsText.textContent = `Found ${visibleCount} of ${totalCount} projects matching "${searchTerm}"`;
        }
    }
    
    resultsDiv.style.display = '';
}

// Function to hide search results summary
function hideSearchResults() {
    const resultsDiv = document.getElementById('searchResults');
    if (resultsDiv) {
        resultsDiv.style.display = 'none';
    }
}

// Handle edit project button click
document.addEventListener('DOMContentLoaded', function() {
    // Edit project button click handler
    document.addEventListener('click', function(e) {
        // Check if the clicked element has the edit-project class or is a child of it
        const editBtn = e.target.closest('.edit-project');
        if (!editBtn) return;
        
        e.preventDefault();
        
        // Get data attributes
        const id = editBtn.dataset.id;
        const name = editBtn.dataset.name;
        const description = editBtn.dataset.description;
        const typeId = editBtn.dataset.typeId;
        const managerId = editBtn.dataset.managerId;
        const startDate = editBtn.dataset.startDate;
        const endDate = editBtn.dataset.endDate;
        const budget = editBtn.dataset.budget;
        const progress = editBtn.dataset.progress;
        const status = editBtn.dataset.status;
        
        console.log('Edit button clicked:', { id, name, status });
        
        // Set form values
        document.getElementById('editProjectId').value = id;
        document.getElementById('editProjectName').value = name || '';
        document.getElementById('editProjectDescription').value = description || '';
        document.getElementById('editStartDate').value = startDate || '';
        document.getElementById('editEndDate').value = endDate || '';
        document.getElementById('editProjectBudget').value = budget || '';
        document.getElementById('editProjectProgress').value = progress || 0;
        document.getElementById('editProjectProgressValue').textContent = (progress || 0) + '%';
        
        // Set dropdowns
        if (typeId) document.getElementById('editProjectType').value = typeId;
        if (managerId) document.getElementById('editProjectManager').value = managerId;
        
        // Set status radio button
        if (status) {
            const statusId = 'editStatus' + status.charAt(0).toUpperCase() + status.slice(1).replace('_', '');
            const statusRadio = document.getElementById(statusId);
            if (statusRadio) statusRadio.checked = true;
        }
        
        // Set form action
        document.getElementById('editProjectForm').action = '/company/projects/' + id;
        
        // Debug: Check if modal element exists
        const modalElement = document.getElementById('editProjectModal');
        console.log('Modal element:', modalElement);
        
        if (!modalElement) {
            console.error('Error: Could not find editProjectModal element');
            return;
        }
        
        // Debug: Check if Bootstrap is loaded
        console.log('Bootstrap Modal:', typeof bootstrap?.Modal);
        
        // Show the modal
        const modal = new bootstrap.Modal(modalElement);
        console.log('Modal instance:', modal);
        modal.show();
    });
});
    
    
// Initialize tooltips (Vanilla JavaScript)
// Note: Bootstrap 5 tooltips require manual initialization
// For now, we'll skip this since jQuery is not available
</script>

<style>
    .action-btn {
        opacity: 1 !important;
        visibility: visible !important;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        padding: 0 8px;
        background-color: #0dcaf0 !important;
        border-color: #0dcaf0 !important;
        color: #fff !important;
        cursor: pointer;
        border-radius: 4px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .action-btn:hover {
        opacity: 0.9 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.15);
    }
    
    .action-btn i {
        font-size: 14px;
        color: #fff !important;
    }

    .btn.btn-info.action-btn {
        background-color: #0dcaf0 !important;
        border-color: #0dcaf0 !important;
        color: #fff !important;
    }
</style>
<script>

    // Global flag to prevent multiple SweetAlert2 instances
    window.isAlertShowing = false;
    let currentPage = 1;
    let projectsData = [];

console.log('=== PROJECTS BLADE.JS LOADED ===');
    console.log('Document ready - initializing project management');
console.log('SweetAlert2 loaded:', typeof Swal !== 'undefined');

    // Test if form exists
console.log('Create project form exists:', document.getElementById('createProjectForm') ? 'YES' : 'NO');

    // Utility function to show SweetAlert safely
    function showSafeSweetAlert(options) {
        if (!window.isAlertShowing) {
            window.isAlertShowing = true;
            return Swal.fire(options).finally(() => {
                window.isAlertShowing = false;
            });
        }
    }



    // Handle Create Project Form with SweetAlert
    document.addEventListener('submit', function(e) {
        if (e.target.id === 'createProjectForm') {
            e.preventDefault();
            
            console.log('=== CREATE PROJECT FORM SUBMITTED ===');
            console.log('SweetAlert available:', typeof Swal !== 'undefined');
            
            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Creating...`;
            
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
                    return response.json();
                }
                throw new Error('Network response was not ok');
            })
            .then(data => {
                if (data.success) {
                    // Show success message
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message || 'Project created successfully',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#28a745'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Reload the page to show the new project
                                window.location.reload();
                            }
                        });
                    } else {
                        // Fallback to normal form submission
                        form.submit();
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
                                text: data.message || 'Failed to create project',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#dc3545'
                            });
                        } else {
                            alert(data.message || 'Failed to create project');
                        }
                    }
                    
                    // Reset button state
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Create Project';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Reset button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Create Project';
                
                // Show error message
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to create project. Please try again.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#dc3545'
                    });
                } else {
                    alert('Failed to create project. Please try again.');
                }
            });
        }
    });


    // Handle Edit Project Form Submission - Using the same pattern as reports.blade.php
    document.addEventListener('submit', function(e) {
        console.log('=== FORM SUBMISSION DETECTED ===');
        console.log('Form ID:', e.target.id);
        console.log('Form element:', e.target);
        
        if (e.target.id === 'editProjectForm') {
            e.preventDefault();
            
            console.log('=== EDIT PROJECT FORM SUBMITTED ===');
            console.log('SweetAlert available:', typeof Swal !== 'undefined');
            
            const form = e.target;
            const projectName = form.querySelector('#editProjectName').value;
            
            console.log('Project name:', projectName);
            
            // Simple test - if SweetAlert is not available, submit normally
            if (typeof Swal === 'undefined') {
                console.log('SweetAlert not available, submitting normally');
                form.submit();
                return;
            }
            
            // Show confirmation dialog first (same pattern as reports)
            Swal.fire({
                title: 'Update Project?',
                text: `Are you sure you want to update "${projectName}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!',
                cancelButtonText: 'Cancel',
                backdrop: false,
                allowOutsideClick: true
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log('User confirmed, showing loading...');
                    
                    // Show SweetAlert loading (same pattern as reports)
                    Swal.fire({
                        title: 'Updating...',
                        text: `Updating project "${projectName}"`,
                        icon: 'info',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        backdrop: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit the form normally (same pattern as reports)
                    setTimeout(() => {
                        form.submit();
                        
                        // Show success message after a short delay (same pattern as reports)
                        setTimeout(() => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Project Updated!',
                                text: `Project "${projectName}" has been updated successfully.`,
                                timer: 3000,
                                showConfirmButton: false,
                                timerProgressBar: true,
                                backdrop: false
                            });
                            
                            // Reload page after success to show updated data
                            setTimeout(() => {
                                window.location.reload();
                            }, 3500);
                        }, 1500);
                    }, 500);
                } else {
                    console.log('User cancelled');
                }
            });
        }
    });

    // Handle Progress Slider Change in Edit Modal (Vanilla JavaScript)
    document.addEventListener('input', function(e) {
        if (e.target.id === 'editProjectProgress') {
            const progress = e.target.value;
            const progressValue = document.getElementById('editProjectProgressValue');
            if (progressValue) {
                progressValue.textContent = progress + '%';
            }
        }
        
        // Handle Progress Slider Change in Create Modal
        if (e.target.id === 'projectProgress') {
            const progress = e.target.value;
            const progressValue = document.getElementById('projectProgressValue');
            if (progressValue) {
                progressValue.textContent = progress + '%';
            }
        }
    });

    // Direct click handler for edit buttons (Vanilla JavaScript)
    document.addEventListener('click', function(e) {
        if (e.target.closest('.edit-project')) {
            e.preventDefault();
            
            console.log('=== EDIT BUTTON CLICKED ===');
            const button = e.target.closest('.edit-project');
            const projectId = button.dataset.id;
            console.log('Project ID:', projectId);
            
            // Show the modal
            const modal = document.getElementById('editProjectModal');
            
            // Populate form fields
            document.getElementById('editProjectId').value = projectId;
            document.getElementById('editProjectName').value = button.dataset.name || '';
            document.getElementById('editProjectDescription').value = button.dataset.description || '';
            document.getElementById('editStartDate').value = button.dataset.startDate || '';
            document.getElementById('editEndDate').value = button.dataset.endDate || '';
            document.getElementById('editProjectBudget').value = button.dataset.budget || '';
            document.getElementById('editProjectProgress').value = button.dataset.progress || 0;
            document.getElementById('editProjectProgressValue').textContent = (button.dataset.progress || 0) + '%';
            document.getElementById('editProjectType').value = button.dataset.projectType || '';
            document.getElementById('editProjectManager').value = button.dataset.managerId || '';
        
        // Set status radio button
            const status = button.dataset.status;
            if (status) {
                const statusId = 'editStatus' + status.charAt(0).toUpperCase() + status.slice(1).replace('_', '');
                const statusRadio = document.getElementById(statusId);
                if (statusRadio) {
                    statusRadio.checked = true;
                }
            }
        
        // Set form action
            const form = modal.querySelector('form');
            if (form) {
                form.action = '/company/projects/' + projectId;
            }
            
            // Show the modal using Bootstrap
            const bootstrapModal = new bootstrap.Modal(modal);
            bootstrapModal.show();
        }
    });


    // Handle Delete Project Form with SweetAlert (Vanilla JavaScript)
    document.addEventListener('submit', function(e) {
        if (e.target.classList.contains('delete-project-form')) {
            e.preventDefault();
            
            const form = e.target;
            const projectName = form.closest('tr').querySelector('strong').textContent;
            
            console.log('=== DELETE FORM SUBMITTED ===');
            console.log('Project Name:', projectName);
            console.log('SweetAlert available:', typeof Swal !== 'undefined');
            
            // Simple test - if SweetAlert is not available, use browser confirm
            if (typeof Swal === 'undefined') {
                console.log('SweetAlert not available, using browser confirm');
                if (confirm(`Are you sure you want to delete "${projectName}"?`)) {
                    form.submit();
                }
                return;
            }
            
            // Try SweetAlert
            try {
                Swal.fire({
            title: 'Are you sure?',
                    text: `You are about to delete "${projectName}". This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                        // If confirmed, submit the form normally
                form.submit();
            }
        });
            } catch (error) {
                console.error('SweetAlert error:', error);
                // Fallback to browser confirm
                if (confirm(`Are you sure you want to delete "${projectName}"?`)) {
                    form.submit();
                }
            }
        }
    });

    // Enhanced reload button handler (Vanilla JavaScript)
    const reloadBtn = document.getElementById('reloadDataBtn');
    if (reloadBtn) {
        reloadBtn.addEventListener('click', function() {
            console.log('Manual refresh button clicked');
            refreshProjectData();
        });
    }

    // Search functionality for projects
    const searchInput = document.getElementById('projectsSearch');
    if (searchInput) {
        // Real-time search as user types
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            console.log('Searching for:', searchTerm);
            filterProjects(searchTerm);
        });
        
        // Handle Enter key
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const searchTerm = this.value.toLowerCase().trim();
                console.log('Search submitted:', searchTerm);
                filterProjects(searchTerm);
            }
        });
        
        // Handle Escape key to clear search
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                this.value = '';
                filterProjects('');
                this.focus();
            }
        });
        
        // Add clear button functionality
        const searchContainer = searchInput.closest('.input-group');
        if (searchContainer) {
            // Add clear button if it doesn't exist
            let clearBtn = searchContainer.querySelector('.btn-clear-search');
            if (!clearBtn) {
                clearBtn = document.createElement('button');
                clearBtn.className = 'btn btn-outline-secondary btn-clear-search';
                clearBtn.type = 'button';
                clearBtn.innerHTML = '<i class="fas fa-times"></i>';
                clearBtn.title = 'Clear search';
                clearBtn.style.display = 'none';
                
                clearBtn.addEventListener('click', function() {
                    searchInput.value = '';
                    filterProjects('');
                    searchInput.focus();
                    this.style.display = 'none';
                });
                
                searchContainer.appendChild(clearBtn);
            }
            
            // Show/hide clear button based on input
            searchInput.addEventListener('input', function() {
                const clearBtn = searchContainer.querySelector('.btn-clear-search');
                if (clearBtn) {
                    clearBtn.style.display = this.value.trim() ? '' : 'none';
                }
            });
        }
    }
    

    // Reset create project form when modal is hidden
    document.addEventListener('DOMContentLoaded', function() {
        const createModal = document.getElementById('createProjectModal');
        if (createModal) {
            createModal.addEventListener('hidden.bs.modal', function() {
                // Reset the form
                const form = document.getElementById('createProjectForm');
                if (form) {
                    form.reset();
                }
                
                // Reset progress slider to 0
                const progressSlider = document.getElementById('projectProgress');
                const progressValue = document.getElementById('projectProgressValue');
                if (progressSlider && progressValue) {
                    progressSlider.value = 0;
                    progressValue.textContent = '0%';
                }
                
                // Reset status to "Not Started"
                const statusNotStarted = document.getElementById('statusNotStarted');
                if (statusNotStarted) {
                    statusNotStarted.checked = true;
                }
            });
        }
    });

    // Initialize tooltips (Vanilla JavaScript)
    // Note: This requires Bootstrap 5 tooltips to be initialized
    // For now, we'll skip this since jQuery is not available

    // Test SweetAlert on page load
    console.log('=== TESTING SWEETALERT ===');
    console.log('SweetAlert available:', typeof Swal !== 'undefined');
    
    // Simple test - show a SweetAlert after 2 seconds to test if it works
    setTimeout(function() {
        if (typeof Swal !== 'undefined') {
            console.log('SweetAlert is loaded and working!');
        } else {
            console.error('SweetAlert is NOT loaded!');
        }
    }, 2000);

    // Handle session messages (same pattern as reports)
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
</script>
@endpush

<!-- Projects Tab -->
<div class="tab-pane fade {{ session('active_tab') === 'tasks' ? '' : 'show active' }}" id="projects" role="tabpanel" aria-labelledby="projects-tab">
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center w-50">
            <div class="input-group">
                <span class="input-group-text bg-transparent"><i class="fas fa-search text-muted"></i></span>
                <input type="text" id="projectsSearch" class="form-control" placeholder="Search projects by name or code...">
            </div>
            <!-- Status Filter - Commented out for now, will implement after break -->
            <!--
            <div class="dropdown ms-2">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="statusFilter" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-filter me-1"></i> Status
                </button>
                <ul class="dropdown-menu" aria-labelledby="statusFilter">
                    <li><a class="dropdown-item status-filter active" href="#" data-status="">All Statuses</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item status-filter" href="#" data-status="not_started"><span class="badge bg-secondary me-2">•</span> Not Started</a></li>
                    <li><a class="dropdown-item status-filter" href="#" data-status="in_progress"><span class="badge bg-primary me-2">•</span> In Progress</a></li>
                    <li><a class="dropdown-item status-filter" href="#" data-status="on_hold"><span class="badge bg-warning me-2">•</span> On Hold</a></li>
                    <li><a class="dropdown-item status-filter" href="#" data-status="completed"><span class="badge bg-success me-2">•</span> Completed</a></li>
                    <li><a class="dropdown-item status-filter" href="#" data-status="cancelled"><span class="badge bg-danger me-2">•</span> Cancelled</a></li>
                </ul>
            </div>
            -->
        </div>
        <div>
            <button id="reloadDataBtn" class="btn btn-outline-secondary me-2" title="Reload Data">
                <i class="fas fa-sync-alt me-1"></i> Refresh
            </button>
            <button class="btn btn-primary" 
                    data-bs-toggle="modal" 
                    data-bs-target="#createProjectModal">
                <i class="fas fa-plus me-1"></i> New Project
            </button>
        </div>
    </div>
    
    <div class="table-responsive rounded-3 border">
        <table id="projectsTable" class="table table-hover align-middle mb-0" style="width:100%">
            <thead class="table-light">
                <tr>
                    <th width="40">#</th>
                    <th>Project</th>
                    <th width="150">Manager</th>
                    <th width="120">Start Date</th>
                    <th width="120">End Date</th>
                    <th width="120">Budget</th>
                    <th width="180">Progress</th>
                    <th width="120">Status</th>
                    <th width="80" class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody id="projectsTableBody">
                @php
                    $projects = \App\Models\ProjectManagement\Project::where('company_id', session('selected_company_id'))
                        ->with(['manager'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
                @endphp
                
                @forelse($projects as $index => $project)
                <tr>
                    <td>{{ $projects->firstItem() + $index }}</td>
                    <td>
                        <div>
                            <strong>{{ $project->name }}</strong>
                            <br>
                            <small class="text-muted">{{ $project->project_code }}</small>
                            @if($project->description)
                                <br>
                                <small class="text-muted">{{ Str::limit($project->description, 50) }}</small>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark">
                            {{ $project->manager ? $project->manager->fullname : 'Unassigned' }}
                        </span>
                    </td>
                    <td>{{ $project->start_date ? $project->start_date->format('M d, Y') : '-' }}</td>
                    <td>{{ $project->end_date ? $project->end_date->format('M d, Y') : '-' }}</td>
                    <td>
                        <strong>${{ number_format($project->budget, 0) }}</strong>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                <div class="progress-bar bg-{{ $project->status_color }}" 
                                     role="progressbar" 
                                     style="width: {{ $project->progress }}%"
                                     aria-valuenow="{{ $project->progress }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                </div>
                            </div>
                            <small class="text-muted">{{ $project->progress }}%</small>
                        </div>
                    </td>
                    <td>
                        @php
                            $statusColors = [
                                'not_started' => 'secondary',
                                'in_progress' => 'primary', 
                                'on_hold' => 'warning',
                                'completed' => 'success',
                                'cancelled' => 'danger'
                            ];
                            $statusLabels = [
                                'not_started' => 'Not Started',
                                'in_progress' => 'In Progress', 
                                'on_hold' => 'On Hold',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled'
                            ];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$project->status] ?? 'secondary' }}">
                            {{ $statusLabels[$project->status] ?? 'Unknown' }}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="btn-group" role="group">
                            <button type="button" 
                                    class="btn btn-sm btn-outline-primary view-project" 
                                    data-id="{{ $project->id }}"
                                    data-name="{{ $project->name }}"
                                    data-project-code="{{ $project->project_code }}"
                                    data-description="{{ $project->description }}"
                                    data-manager-name="{{ $project->manager ? $project->manager->fullname : 'Not assigned' }}"
                                    data-project-type="{{ $project->project_type ?? 'Not specified' }}"
                                    data-start-date="{{ $project->start_date ? $project->start_date->format('Y-m-d') : '' }}"
                                    data-end-date="{{ $project->end_date ? $project->end_date->format('Y-m-d') : '' }}"
                                    data-budget="{{ $project->budget }}"
                                    data-progress="{{ $project->progress }}"
                                    data-status="{{ $project->status }}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#viewProjectModal"
                                    title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" 
                                    class="btn btn-sm btn-outline-warning edit-project" 
                                    data-id="{{ $project->id }}"
                                    data-name="{{ addslashes($project->name) }}"
                                    data-description="{{ addslashes($project->description) }}"
                                    data-project-type="{{ $project->project_type }}"
                                    data-manager-id="{{ $project->project_manager_id }}"
                                    data-start-date="{{ $project->start_date ? $project->start_date->format('Y-m-d') : '' }}"
                                    data-end-date="{{ $project->end_date ? $project->end_date->format('Y-m-d') : '' }}"
                                    data-budget="{{ $project->budget }}"
                                    data-progress="{{ $project->progress }}"
                                    data-status="{{ $project->status }}"
                                    title="Edit Project">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="/company/projects/{{ $project->id }}" style="display: inline;" class="delete-project-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger action-btn" title="Delete Project">
                                <i class="fas fa-trash"></i>
                            </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-4">
                        <div class="text-muted">
                            <i class="fas fa-folder-open fa-2x mb-2"></i>
                            <p class="mb-0">No projects found. Create your first project!</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
                            </div>
    
    <!-- Pagination -->
    @if($projects->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="text-muted">
            Showing {{ $projects->firstItem() }} to {{ $projects->lastItem() }} of {{ $projects->total() }} projects
                        </div>
        <div>
            {{ $projects->links() }}
    </div>
    </div>
    @endif
</div>

<!-- View Project Modal -->
<div class="modal fade" id="viewProjectModal" tabindex="-1" aria-labelledby="viewProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewProjectModalLabel">Project Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-sm me-3">
                                <span class="avatar-title bg-primary text-white rounded-circle">
                                    <i class="fas fa-project-diagram"></i>
                                </span>
                            </div>
                            <div>
                        <h4 id="viewProjectName" class="mb-1"></h4>
                                <small class="text-muted" id="viewProjectCode"></small>
                            </div>
                        </div>
                        <p id="viewProjectDescription" class="text-muted mb-3"></p>
                        
                        <div class="mb-3">
                            <h6>Project Progress</h6>
                            <div class="progress" style="height: 12px;">
                                <div id="viewProjectProgressBar" class="progress-bar" role="progressbar" style="width: 0%;" 
                                     aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <small class="text-muted">Progress: <span id="viewProjectProgressText" class="fw-bold">0</span>%</small>
                                <small class="text-muted">Due in <span id="viewProjectDaysLeft" class="fw-bold">0</span> days</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Project Details
                                </h6>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-tie me-2 text-primary"></i>
                                            <div>
                                                <small class="text-muted d-block">Project Manager</small>
                                                <span id="viewProjectManager" class="fw-medium"></span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-tag me-2 text-info"></i>
                                            <div>
                                                <small class="text-muted d-block">Project Type</small>
                                                <span id="viewProjectType" class="fw-medium"></span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-wallet me-2 text-success"></i>
                                            <div>
                                                <small class="text-muted d-block">Budget</small>
                                                <span id="viewProjectBudget" class="fw-medium"></span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="far fa-calendar-alt me-2 text-warning"></i>
                                            <div>
                                                <small class="text-muted d-block">Timeline</small>
                                                <span id="viewProjectTimeline" class="fw-medium"></span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-flag me-2 text-secondary"></i>
                                            <div>
                                                <small class="text-muted d-block">Status</small>
                                                <span id="viewProjectStatus"></span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <h6>Project Milestones</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Milestone</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Progress</th>
                                    </tr>
                                </thead>
                                <tbody id="viewProjectMilestones">
                                    <!-- Milestones will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-between w-100">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Close
                    </button>
                    <div>
                        <button type="button" class="btn btn-outline-primary me-2" id="editProjectBtn">
                            <i class="fas fa-edit me-1"></i> Edit Project
                        </button>
                        <button type="button" class="btn btn-primary" id="exportProjectReport">
                            <i class="fas fa-file-export me-1"></i> Export as CSV
                </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Project Modal -->
<div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProjectModalLabel">Edit Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProjectForm" action="" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="editProjectId" name="id">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editProjectName" class="form-label">Project Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editProjectName" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editProjectType" class="form-label">Project Type <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editProjectType" name="project_type" placeholder="e.g., Home Connection, High Rise, GPON" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editStartDate" class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="editStartDate" name="start_date" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editEndDate" class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="editEndDate" name="end_date" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editProjectDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editProjectDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="editProjectManager" class="form-label">Project Manager <span class="text-danger">*</span></label>
                            <select class="form-select" id="editProjectManager" name="project_manager_id" required>
                                <option value="">Select Manager</option>
                                @php
                                    $editManagers = \App\Models\CompanySubUser::where('company_id', session('selected_company_id'))
                                        ->whereIn('status', ['active', 1])
                                        ->orderBy('fullname')
                                        ->get();
                                @endphp
                                @foreach($editManagers as $manager)
                                    <option value="{{ $manager->id }}">{{ $manager->fullname ?? $manager->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="editProjectBudget" class="form-label">Budget ($) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="editProjectBudget" name="budget" min="0" step="0.01" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="editProjectProgress" class="form-label">Progress <span class="text-muted">(% complete)</span></label>
                            <input type="range" class="form-range" id="editProjectProgress" name="progress" min="0" max="100" step="1">
                            <div class="d-flex justify-content-between">
                                <small>0%</small>
                                <small>100%</small>
                            </div>
                            <div class="text-center">
                                <span id="editProjectProgressValue" class="badge bg-primary">0%</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="editStatusNotStarted" value="not_started">
                                <label class="form-check-label" for="editStatusNotStarted">
                                    Not Started
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="editStatusInProgress" value="in_progress">
                                <label class="form-check-label" for="editStatusInProgress">
                                    In Progress
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="editStatusOnHold" value="on_hold">
                                <label class="form-check-label" for="editStatusOnHold">
                                    On Hold
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="editStatusCompleted" value="completed">
                                <label class="form-check-label" for="editStatusCompleted">
                                    Completed
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="editStatusCancelled" value="cancelled">
                                <label class="form-check-label" for="editStatusCancelled">
                                    Cancelled
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Project Modal -->
<div class="modal fade" id="deleteProjectModal" tabindex="-1" aria-labelledby="deleteProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteProjectModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the project <strong id="deleteProjectName"></strong>?</p>
                <p class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i>This action cannot be undone.</p>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="confirmDelete" required>
                    <label class="form-check-label" for="confirmDelete">
                        Yes, I understand that this will permanently delete the project and all associated data.
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn" disabled>
                    <i class="fas fa-trash-alt me-1"></i> Delete Project
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Create Project Modal -->
<div class="modal fade" id="createProjectModal" tabindex="-1" aria-labelledby="createProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createProjectModalLabel">Create New Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createProjectForm" action="{{ route('company.projects.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="projectName" class="form-label">Project Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="projectName" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="projectType" class="form-label">Project Type <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="projectType" name="project_type" placeholder="e.g., Home Connection, High Rise, GPON" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="startDate" class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="startDate" name="start_date" required>
                        </div>
                        <div class="col-md-6">
                            <label for="endDate" class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="endDate" name="end_date" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="projectDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="projectDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="projectManager" class="form-label">Project Manager <span class="text-danger">*</span></label>
                            <select class="form-select" id="projectManager" name="project_manager_id" required>
                                <option value="">Select Manager</option>
                                @php
                                    $managers = \App\Models\CompanySubUser::where('company_id', session('selected_company_id'))
                                        ->whereIn('status', ['active', 1])
                                        ->orderBy('fullname')
                                        ->get();
                                @endphp
                                @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}">{{ $manager->fullname ?? $manager->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="projectBudget" class="form-label">Budget ($) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="projectBudget" name="budget" min="0" step="0.01" required>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="projectProgress" class="form-label">Initial Progress <span class="text-muted">(% complete)</span></label>
                            <input type="range" class="form-range" id="projectProgress" name="progress" min="0" max="100" step="1" value="0">
                            <div class="d-flex justify-content-between">
                                <small>0%</small>
                                <small>100%</small>
                            </div>
                            <div class="text-center">
                                <span id="projectProgressValue" class="badge bg-primary">0%</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusNotStarted" value="not_started" checked>
                                <label class="form-check-label" for="statusNotStarted">
                                    Not Started
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusInProgress" value="in_progress">
                                <label class="form-check-label" for="statusInProgress">
                                    In Progress
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusOnHold" value="on_hold">
                                <label class="form-check-label" for="statusOnHold">
                                    On Hold
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusCompleted" value="completed">
                                <label class="form-check-label" for="statusCompleted">
                                    Completed
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusCancelled" value="cancelled">
                                <label class="form-check-label" for="statusCancelled">
                                    Cancelled
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Project</button>
                </div>
            </form>
        </div>
    </div>
</div>
