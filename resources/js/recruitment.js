// Add Quill editor for notes
if (!window.Quill) {
    const script = document.createElement('script');
    script.src = 'https://cdn.quilljs.com/1.3.7/quill.min.js';
    script.onload = function() {
        // Initialize Quill editor for notes
        const editor = new Quill('#interview-notes', {
            theme: 'snow',
            placeholder: 'Add interview notes...'
        });
    };
    document.head.appendChild(script);
}

// Handle application action buttons
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // View Application Details
    document.querySelectorAll('.view-application-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const applicationId = this.dataset.applicationId;
            const candidateName = this.dataset.candidateName;
            const position = this.dataset.position;
            const cvUrl = this.dataset.cvUrl;
            
            // Show loading state
            const modal = new bootstrap.Modal(document.getElementById('viewApplicationModal'));
            document.querySelector('#viewApplicationModal .modal-body').innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
            
            // Update modal content
            const modalBody = document.querySelector('#viewApplicationModal .modal-body');
            modalBody.innerHTML = `
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <div class="avatar-xxl mx-auto mb-3">
                            <img src="${cvUrl || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(candidateName)}" alt="${candidateName}" class="img-fluid rounded-circle">
                        </div>
                        <h5 class="mb-1">${candidateName}</h5>
                        <p class="text-muted mb-0">${position}</p>
                    </div>
                    <div class="col-md-8">
                        <div class="mb-3">
                            <h6 class="mb-2">Application Details</h6>
                            <p class="text-muted">Applied for position: ${position}</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="mb-2">Actions</h6>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary btn-sm" onclick="window.open('${cvUrl}', '_blank')">
                                    <i class="fas fa-download me-1"></i> Download CV
                                </button>
                                <button class="btn btn-outline-success btn-sm schedule-interview-btn" 
                                        data-application-id="${applicationId}" 
                                        data-candidate-name="${candidateName}" 
                                        data-position="${position}">
                                    <i class="fas fa-calendar-plus me-1"></i> Schedule Interview
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Show modal
            modal.show();
        });
    });

    // Schedule Interview
    document.querySelectorAll('.schedule-interview-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const applicationId = this.dataset.applicationId;
            const candidateName = this.dataset.candidateName;
            const position = this.dataset.position;
            
            // Set application details in modal
            const modal = new bootstrap.Modal(document.getElementById('scheduleInterviewModal'));
            document.getElementById('candidate_name').value = candidateName;
            document.getElementById('job_position').value = position;
            document.getElementById('application_id').value = applicationId;
            
            // Show modal
            modal.show();
        });
    });

    // Handle interview scheduling form submission
    document.getElementById('scheduleInterviewForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const modal = bootstrap.Modal.getInstance(document.getElementById('scheduleInterviewModal'));
        
        // Show loading state
        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Scheduling...';
        
        // Submit form
        fetch('/api/interviews', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Show success message
            const modalBody = document.querySelector('#scheduleInterviewModal .modal-body');
            modalBody.innerHTML = `
                <div class="alert alert-success">
                    Interview scheduled successfully! Candidate will be notified via email.
                </div>
            `;
            
            // Close modal after 2 seconds
            setTimeout(() => {
                modal.hide();
                // Refresh interviews table
                const interviewsTable = document.querySelector('#interviews-datatable');
                if (interviewsTable) {
                    interviewsTable._DT.data.reload();
                }
            }, 2000);
        })
        .catch(error => {
            console.error('Error:', error);
            const modalBody = document.querySelector('#scheduleInterviewModal .modal-body');
            modalBody.innerHTML = `
                <div class="alert alert-danger">
                    Failed to schedule interview. Please try again.
                </div>
            `;
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        });
    });

    // Download CV
    document.querySelectorAll('.download-cv-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const cvUrl = this.dataset.cvUrl;
            window.open(cvUrl, '_blank');
        });
    });

    // Handle job posting actions
    document.querySelectorAll('.job-posting-action').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const action = this.dataset.action;
            const jobId = this.dataset.jobId;
            
            if (action === 'edit') {
                // Handle edit job posting
                window.location.href = `/jobs/${jobId}/edit`;
            } else if (action === 'archive') {
                // Show confirmation dialog
                if (confirm('Are you sure you want to archive this job posting?')) {
                    // Archive job posting
                    fetch(`/api/jobs/${jobId}/archive`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Refresh jobs table
                        const jobsTable = document.querySelector('#positions-datatable');
                        if (jobsTable) {
                            jobsTable._DT.data.reload();
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            } else if (action === 'publish') {
                // Show confirmation dialog
                if (confirm('Are you sure you want to publish this job posting?')) {
                    // Publish job posting
                    fetch(`/api/jobs/${jobId}/publish`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Refresh jobs table
                        const jobsTable = document.querySelector('#positions-datatable');
                        if (jobsTable) {
                            jobsTable._DT.data.reload();
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            }
        });
    });
});
