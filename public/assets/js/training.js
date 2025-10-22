/**
 * Training & Development Module
 * Handles all client-side functionality for the training section
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Handle tab switching between file upload and web link
    const uploadTab = document.getElementById('upload-tab');
    const linkTab = document.getElementById('link-tab');
    const resourceTypeSelect = document.getElementById('resourceType');
    
    if (uploadTab && linkTab && resourceTypeSelect) {
        // Set initial state based on resource type
        function updateResourceType() {
            const selectedTab = document.querySelector('.nav-tabs .nav-link.active').id;
            if (selectedTab === 'upload-tab') {
                resourceTypeSelect.value = 'document';
            } else if (selectedTab === 'link-tab') {
                resourceTypeSelect.value = 'link';
            }
        }
        
        // Update resource type when tabs are changed
        uploadTab?.addEventListener('shown.bs.tab', function() {
            resourceTypeSelect.value = 'document';
        });
        
        linkTab?.addEventListener('shown.bs.tab', function() {
            resourceTypeSelect.value = 'link';
        });
    }
    
    // Handle access control radio buttons
    const accessRadios = document.querySelectorAll('input[name="resourceAccess"]');
    const accessContainer = document.getElementById('accessSelectionContainer');
    const accessTypeLabel = document.getElementById('accessTypeLabel');
    const accessSelect = document.getElementById('resourceAccessValue');
    
    if (accessRadios.length && accessContainer && accessTypeLabel && accessSelect) {
        accessRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'public') {
                    accessContainer.classList.add('d-none');
                } else {
                    accessContainer.classList.remove('d-none');
                    
                    // Update label and placeholder based on selection
                    switch(this.value) {
                        case 'department':
                            accessTypeLabel.textContent = 'Departments';
                            updateSelectOptions('departments');
                            break;
                        case 'role':
                            accessTypeLabel.textContent = 'Roles';
                            updateSelectOptions('roles');
                            break;
                        case 'private':
                            accessTypeLabel.textContent = 'Employees';
                            updateSelectOptions('employees');
                            break;
                    }
                }
            });
        });
        
        // Initialize with public access selected
        document.getElementById('accessPublic').checked = true;
    }
    
    // Handle expiration date toggle
    const expirationToggle = document.getElementById('setExpiration');
    const expirationContainer = document.getElementById('expirationDateContainer');
    
    if (expirationToggle && expirationContainer) {
        expirationToggle.addEventListener('change', function() {
            if (this.checked) {
                expirationContainer.classList.remove('d-none');
                // Set default expiration to 1 year from now
                const oneYearFromNow = new Date();
                oneYearFromNow.setFullYear(oneYearFromNow.getFullYear() + 1);
                document.getElementById('expirationDate').value = oneYearFromNow.toISOString().split('T')[0];
            } else {
                expirationContainer.classList.add('d-none');
            }
        });
    }
    
    // Initialize file upload dropzone
    initDropzone();
    
    // Initialize date pickers
    initDatePickers();
    
    // Initialize form validation
    initFormValidation();
});

/**
 * Initialize Dropzone for file uploads
 */
function initDropzone() {
    // Check if Dropzone is loaded
    if (typeof Dropzone === 'undefined') {
        console.warn('Dropzone.js is not loaded. File uploads will not work.');
        return;
    }
    
    // Initialize Dropzone for training materials
    if (document.getElementById('trainingMaterialsDropzone')) {
        new Dropzone("#trainingMaterialsDropzone", { 
            url: "/api/trainings/upload",
            paramName: "file",
            maxFilesize: 50, // MB
            maxFiles: 5,
            acceptedFiles: ".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png,.mp4,.mp3",
            addRemoveLinks: true,
            dictDefaultMessage: "<i class='fas fa-cloud-upload-alt fa-3x text-muted mb-3'></i><h5>Drop files here or click to upload</h5>",
            dictFallbackMessage: "Your browser does not support drag'n'drop file uploads.",
            dictFileTooBig: "File is too big ({{filesize}}MB). Max filesize: {{maxFilesize}}MB.",
            dictInvalidFileType: "You can't upload files of this type.",
            dictResponseError: "Server responded with {{statusCode}} code.",
            dictCancelUpload: "Cancel upload",
            dictUploadCanceled: "Upload canceled.",
            dictRemoveFile: "Remove file",
            dictMaxFilesExceeded: "You can't upload any more files.",
            init: function() {
                this.on("addedfile", function(file) {
                    // Show file preview
                    const preview = document.createElement('div');
                    preview.className = 'file-preview d-flex align-items-center justify-content-between p-2 border rounded mb-2';
                    preview.innerHTML = `
                        <div class="d-flex align-items-center">
                            <i class="${getFileIcon(file.name)} me-2"></i>
                            <div>
                                <div class="small fw-medium">${file.name}</div>
                                <div class="small text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-link text-danger" data-dz-remove>
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    
                    const fileList = document.getElementById('file-upload-list') || document.getElementById('file-upload-preview');
                    if (fileList) {
                        fileList.appendChild(preview);
                    }
                });
                
                this.on("removedfile", function(file) {
                    // Remove file preview
                    const fileList = document.getElementById('file-upload-list') || document.getElementById('file-upload-preview');
                    if (fileList) {
                        const previews = fileList.getElementsByClassName('file-preview');
                        for (let i = 0; i < previews.length; i++) {
                            if (previews[i].textContent.includes(file.name)) {
                                previews[i].remove();
                                break;
                            }
                        }
                    }
                });
            }
        });
    }
}

/**
 * Get appropriate icon for file type
 */
function getFileIcon(filename) {
    const ext = filename.split('.').pop().toLowerCase();
    
    switch(ext) {
        case 'pdf':
            return 'fas fa-file-pdf text-danger';
        case 'doc':
        case 'docx':
            return 'fas fa-file-word text-primary';
        case 'xls':
        case 'xlsx':
            return 'fas fa-file-excel text-success';
        case 'ppt':
        case 'pptx':
            return 'fas fa-file-powerpoint text-warning';
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
            return 'fas fa-file-image text-info';
        case 'mp4':
        case 'mov':
        case 'avi':
            return 'fas fa-file-video text-purple';
        case 'mp3':
        case 'wav':
            return 'fas fa-file-audio text-secondary';
        case 'zip':
        case 'rar':
        case '7z':
            return 'fas fa-file-archive text-muted';
        default:
            return 'fas fa-file text-muted';
    }
}

/**
 * Update select options based on type (departments, roles, employees)
 */
function updateSelectOptions(type) {
    const select = document.getElementById('resourceAccessValue');
    if (!select) return;
    
    // Clear existing options
    select.innerHTML = '';
    
    // Add a placeholder option
    const placeholder = document.createElement('option');
    placeholder.text = type === 'departments' ? 'Select departments...' : 
                      type === 'roles' ? 'Select roles...' : 'Select employees...';
    placeholder.disabled = true;
    placeholder.selected = true;
    select.appendChild(placeholder);
    
    // Simulated data - in a real app, this would be an API call
    let items = [];
    
    switch(type) {
        case 'departments':
            items = [
                { id: 1, name: 'Sales' },
                { id: 2, name: 'Marketing' },
                { id: 3, name: 'Human Resources' },
                { id: 4, name: 'IT' },
                { id: 5, name: 'Finance' },
                { id: 6, name: 'Operations' }
            ];
            break;
            
        case 'roles':
            items = [
                { id: 1, name: 'Manager' },
                { id: 2, name: 'Team Lead' },
                { id: 3, name: 'Developer' },
                { id: 4, name: 'Designer' },
                { id: 5, name: 'Analyst' },
                { id: 6, name: 'Executive' }
            ];
            break;
            
        case 'employees':
            items = [
                { id: 1, name: 'John Doe', email: 'john@example.com' },
                { id: 2, name: 'Jane Smith', email: 'jane@example.com' },
                { id: 3, name: 'Mike Johnson', email: 'mike@example.com' },
                { id: 4, name: 'Sarah Williams', email: 'sarah@example.com' },
                { id: 5, name: 'David Brown', email: 'david@example.com' }
            ];
            break;
    }
    
    // Add options to select
    items.forEach(item => {
        const option = document.createElement('option');
        option.value = item.id;
        option.text = item.name + (item.email ? ` (${item.email})` : '');
        select.appendChild(option);
    });
    
    // Initialize Select2 if available
    if (typeof $ !== 'undefined' && $.fn.select2) {
        $(select).select2({
            placeholder: type === 'departments' ? 'Select departments...' : 
                       type === 'roles' ? 'Select roles...' : 'Select employees...',
            width: '100%',
            allowClear: true,
            closeOnSelect: false
        });
    }
}

/**
 * Initialize date pickers
 */
function initDatePickers() {
    // Flatpickr is used for better date picking experience
    if (typeof flatpickr !== 'undefined') {
        // Training start date
        const startDateInput = document.getElementById('trainingStartDate');
        if (startDateInput) {
            flatpickr(startDateInput, {
                minDate: 'today',
                dateFormat: 'Y-m-d',
                onChange: function(selectedDates, dateStr, instance) {
                    // Update end date min date when start date changes
                    const endDateInput = document.getElementById('trainingEndDate');
                    if (endDateInput && selectedDates.length > 0) {
                        endDateInput._flatpickr.set('minDate', dateStr);
                        
                        // If end date is before start date, update it
                        if (endDateInput._flatpickr.selectedDates.length > 0 && 
                            endDateInput._flatpickr.selectedDates[0] < selectedDates[0]) {
                            endDateInput._flatpickr.setDate(selectedDates[0]);
                        }
                    }
                }
            });
        }
        
        // Training end date
        const endDateInput = document.getElementById('trainingEndDate');
        if (endDateInput) {
            flatpickr(endDateInput, {
                minDate: 'today',
                dateFormat: 'Y-m-d',
                onChange: function(selectedDates, dateStr, instance) {
                    // Update start date max date when end date changes
                    const startDateInput = document.getElementById('trainingStartDate');
                    if (startDateInput && selectedDates.length > 0) {
                        startDateInput._flatpickr.set('maxDate', dateStr);
                    }
                }
            });
        }
        
        // Expiration date
        const expirationDateInput = document.getElementById('expirationDate');
        if (expirationDateInput) {
            flatpickr(expirationDateInput, {
                minDate: 'today',
                dateFormat: 'Y-m-d',
                defaultDate: new Date().fp_incr(30) // Default to 30 days from now
            });
        }
    }
}

/**
 * Initialize form validation
 */
function initFormValidation() {
    // Create Training Form
    const createTrainingForm = document.getElementById('createTrainingForm');
    if (createTrainingForm) {
        createTrainingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic validation
            const title = document.getElementById('trainingTitle')?.value.trim();
            const description = document.getElementById('trainingDescription')?.value.trim();
            const type = document.getElementById('trainingType')?.value;
            const trainer = document.getElementById('trainingTrainer')?.value;
            const startDate = document.getElementById('trainingStartDate')?.value;
            const endDate = document.getElementById('trainingEndDate')?.value;
            const location = document.getElementById('trainingLocation')?.value;
            
            if (!title || !description || !type || !trainer || !startDate || !endDate || !location) {
                showAlert('Please fill in all required fields.', 'danger');
                return false;
            }
            
            // Additional validation can be added here
            
            // If validation passes, submit the form
            this.submit();
        });
    }
    
    // Add Resource Form
    const addResourceForm = document.getElementById('addResourceForm');
    if (addResourceForm) {
        addResourceForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic validation
            const title = document.getElementById('resourceTitle')?.value.trim();
            const type = document.getElementById('resourceType')?.value;
            const resourceLink = document.getElementById('resourceLink')?.value.trim();
            const isLinkTabActive = document.querySelector('#link-tab')?.classList.contains('active');
            
            if (!title || !type) {
                showAlert('Please fill in all required fields.', 'danger');
                return false;
            }
            
            // If link tab is active, validate URL
            if (isLinkTabActive && !isValidUrl(resourceLink)) {
                showAlert('Please enter a valid URL.', 'danger');
                return false;
            }
            
            // If file upload is active, check if files are selected
            if (!isLinkTabActive) {
                const dropzone = Dropzone.forElement('#resourceFileDropzone');
                if (dropzone.files.length === 0) {
                    showAlert('Please upload at least one file.', 'danger');
                    return false;
                }
            }
            
            // If validation passes, submit the form
            this.submit();
        });
    }
}

/**
 * Show alert message
 */
function showAlert(message, type = 'info') {
    // Remove any existing alerts
    const existingAlert = document.querySelector('.alert-dismissible');
    if (existingAlert) {
        existingAlert.remove();
    }
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Find the first form group to insert the alert before
    const firstFormGroup = document.querySelector('.form-floating');
    if (firstFormGroup) {
        firstFormGroup.parentNode.insertBefore(alertDiv, firstFormGroup);
    } else {
        // If no form group found, append to the form
        const form = document.querySelector('form');
        if (form) {
            form.prepend(alertDiv);
        }
    }
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        const alert = bootstrap.Alert.getOrCreateInstance(alertDiv);
        if (alert) alert.close();
    }, 5000);
}

/**
 * Validate URL
 */
function isValidUrl(string) {
    try {
        new URL(string);
        return true;
    } catch (_) {
        return false;
    }
}

// Export functions for use in other modules if needed
window.TrainingModule = {
    initDropzone,
    updateSelectOptions,
    initDatePickers,
    showAlert
};
