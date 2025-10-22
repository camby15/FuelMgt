@extends('layouts.vertical', ['page_title' => 'Quality Audit'])

@php
// Sample data - This would normally come from your controller
$sampleAudit = (object)[
    'id' => 1,
    'site_id' => 101,
    'engineer_id' => 45,
    'status' => 'pending_review',
    'created_at' => now(),
    'updated_at' => now(),
    'photos' => [
        (object)[
            'id' => 1,
            'url' => 'https://via.placeholder.com/800x600?text=Site+Entrance',
            'thumb_url' => 'https://via.placeholder.com/300x200?text=Site+Entrance',
            'notes' => 'Front entrance of the site',
            'created_at' => now()->subHours(2),
            'type' => 'site'
        ],
        (object)[
            'id' => 2,
            'url' => 'https://via.placeholder.com/800x600?text=Equipment+Installation',
            'thumb_url' => 'https://via.placeholder.com/300x200?text=Equipment+Installation',
            'notes' => 'Main equipment installation',
            'created_at' => now()->subHours(1),
            'type' => 'installation'
        ],
        (object)[
            'id' => 3,
            'url' => 'https://via.placeholder.com/800x600?text=Final+Connection',
            'thumb_url' => 'https://via.placeholder.com/300x200?text=Final+Connection',
            'notes' => 'Final connection point',
            'created_at' => now()->subMinutes(30),
            'type' => 'connection'
        ]
    ]
];
@endphp

@section('css')
<!-- Dropzone css -->
<link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Select2 css -->
<link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Quality Audit</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="site-select" class="form-label">Select Site</label>
                            <select class="form-select" id="site-select">
                                <option value="">Select a site...</option>
                                <option value="101" selected>Site #101 - 123 Main St</option>
                                <option value="102">Site #102 - 456 Oak Ave</option>
                                <option value="103">Site #103 - 789 Pine Blvd</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="engineer-select" class="form-label">Engineer</label>
                            <select class="form-select" id="engineer-select">
                                <option value="">Select engineer...</option>
                                <option value="45" selected>John Smith (JS-45)</option>
                                <option value="46">Sarah Johnson (SJ-23)</option>
                                <option value="47">Mike Brown (MB-12)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Audit Date</label>
                            <input type="text" class="form-control" id="audit-date" value="{{ date('Y-m-d') }}" readonly>
                        </div>
                    </div>

                    <!-- Photo Evidence Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <h5 class="mb-0 me-2">Engineer's Photo Evidence</h5>
                                    <span class="badge bg-{{ count($sampleAudit->photos) > 0 ? 'success' : 'danger' }}">
                                        {{ count($sampleAudit->photos) }} {{ Str::plural('Photo', count($sampleAudit->photos)) }}
                                    </span>
                                </div>
                                <div class="text-muted">
                                    <i class="uil uil-info-circle me-1"></i> Submitted by Engineer
                                </div>
                            </div>
                            <div class="border rounded p-3" id="photo-evidence-container">
                                @php $audit = $sampleAudit; @endphp
                                @if(isset($audit) && count($audit->photos) > 0)
                                    <div class="row g-3" id="engineer-photos">
                                        @foreach($audit->photos as $photo)
                                            <div class="col-md-3 col-6">
                                                <div class="card h-100">
                                                        <a href="#" class="photo-view" 
                                                           data-bs-toggle="modal" 
                                                           data-bs-target="#photoModal" 
                                                           data-img-src="{{ $photo->url }}"
                                                           data-photo-id="{{ $photo->id }}"
                                                           data-notes="{{ $photo->notes }}"
                                                           data-timestamp="{{ $photo->created_at->format('M j, Y g:i A') }}">
                                                            <img src="{{ $photo->thumb_url }}" class="card-img-top img-thumbnail" alt="Photo evidence">
                                                        </a>
                                                    <div class="card-body p-2">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <small class="text-muted">
                                                                <i class="uil uil-clock me-1"></i> {{ $photo->created_at->diffForHumans() }}
                                                            </small>
                                                            <span class="badge bg-{{ $photo->type === 'site' ? 'primary' : ($photo->type === 'installation' ? 'info' : 'success') }}">
                                                                {{ ucfirst($photo->type) }}
                                                            </span>
                                                        </div>
                                                        @if($photo->notes)
                                                            <p class="small mb-0 mt-1">{{ $photo->notes }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4" id="no-photos-message">
                                        <i class="uil uil-image-slash text-danger mb-2" style="font-size: 3rem;"></i>
                                        <p class="text-danger fw-bold mb-2">Photo Evidence Required</p>
                                        <p class="text-muted">No photo evidence has been submitted by the engineer. This is required for connection completion.</p>
                                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="request-photos-btn">
                                            <i class="uil uil-envelope-upload me-1"></i> Request Photos
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Quality Auditor's Notes Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">Auditor's Notes</h5>
                            <div class="border rounded p-3">
                                <div class="mb-3">
                                    <label for="auditor-notes" class="form-label">Your Notes</label>
                                    <textarea class="form-control" id="auditor-notes" rows="3" 
                                        placeholder="Add your notes about the quality of work based on the evidence provided..."></textarea>
                                </div>
                                <div class="dropzone" id="auditor-photo-dropzone">
                                    <div class="dz-message needsclick">
                                        <i class="h1 text-muted uil-camera"></i>
                                        <h5>Add Additional Photos (Optional)</h5>
                                        <p class="text-muted font-13">
                                            Upload additional photos if needed for reference.
                                        </p>
                                    </div>
                                </div>
                                <div id="auditor-photo-preview" class="mt-3 row g-2">
                                    <!-- Auditor's uploaded photos will appear here -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quality Check Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">Quality Check</h5>
                            <div class="border rounded p-3">
                                <div class="alert alert-info">
                                    <i class="uil uil-info-circle me-1"></i> Please review all evidence and mark the quality check status below.
                                </div>
                                
                                <div class="form-check form-switch mb-3">
                                    <input type="checkbox" class="form-check-input" id="quality-passed" style="width: 2.5em; height: 1.5em;">
                                    <label class="form-check-label fw-bold ms-2" for="quality-passed">Quality Check Passed</label>
                                </div>
                                
                                <!-- Rework Section -->
                                <div id="rework-section" class="border rounded p-3 bg-light" style="display: none;">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="uil uil-exclamation-triangle text-danger me-2 fs-4"></i>
                                        <h5 class="text-danger mb-0">Rework Required</h5>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="rework-reason" class="form-label fw-medium">Primary Reason for Rework <span class="text-danger">*</span></label>
                                        <select class="form-select" id="rework-reason" required>
                                            <option value="">-- Select a reason --</option>
                                            <option value="safety_concerns">🚨 Safety Concerns</option>
                                            <option value="incomplete_work">🔧 Incomplete Work</option>
                                            <option value="quality_issues">⚠️ Quality Issues</option>
                                            <option value="incorrect_installation">❌ Incorrect Installation</option>
                                            <option value="documentation_issues">📄 Documentation Issues</option>
                                            <option value="other">❓ Other (Specify)</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3" id="other-reason-container" style="display: none;">
                                        <label for="other-reason" class="form-label fw-medium">Please specify the reason</label>
                                        <input type="text" class="form-control" id="other-reason" placeholder="Enter the specific reason for rework...">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="rework-notes" class="form-label fw-medium">Detailed Instructions <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="rework-notes" rows="3" required 
                                                  placeholder="Provide clear and detailed instructions for the rework..."></textarea>
                                        <div class="form-text">Be specific about what needs to be corrected.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Severity Level</label>
                                        <div class="btn-group w-100" role="group">
                                            <input type="radio" class="btn-check" name="severity" id="severity-low" value="low" autocomplete="off">
                                            <label class="btn btn-outline-warning" for="severity-low">Low</label>
                                            
                                            <input type="radio" class="btn-check" name="severity" id="severity-medium" value="medium" autocomplete="off" checked>
                                            <label class="btn btn-outline-warning" for="severity-medium">Medium</label>
                                            
                                            <input type="radio" class="btn-check" name="severity" id="severity-high" value="high" autocomplete="off">
                                            <label class="btn btn-outline-danger" for="severity-high">High</label>
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-warning">
                                        <i class="uil uil-exclamation-triangle me-1"></i>
                                        Marking as rework will return this task to the assignment pool with the selected priority.
                                    </div>
                                </div>

                                <!-- Validation Section -->
                                <div class="border-top pt-3 mt-4" id="validation-section">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h5 class="mb-1">Final Validation</h5>
                                            <p class="text-muted small mb-0">Complete the validation to finalize this audit</p>
                                        </div>
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-outline-secondary me-2 btn-ripple" id="save-draft-btn">
                                                <i class="uil uil-save me-1"></i> Save Draft
                                            </button>
                                            <button type="button" class="btn btn-success btn-ripple" id="validate-btn" disabled>
                                                <i class="uil uil-check-circle me-1"></i> Validate & Complete
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-warning d-flex align-items-center">
                                        <i class="uil uil-shield-check text-warning me-2" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <h6 class="alert-heading mb-1">Supervisor/QA Approval Required</h6>
                                            <p class="mb-0">This action will mark the site as completed and trigger final billing.</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Completion Checklist -->
                                    <div class="mb-4">
                                        <h6 class="border-bottom pb-2 mb-3">Completion Checklist</h6>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input checklist-item" type="checkbox" id="check-photos" required>
                                            <label class="form-check-label fw-medium" for="check-photos">
                                                All required photo evidence is present and clear
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input checklist-item" type="checkbox" id="check-standards" required>
                                            <label class="form-check-label fw-medium" for="check-standards">
                                                Work meets all quality standards and specifications
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input checklist-item" type="checkbox" id="check-safety" required>
                                            <label class="form-check-label fw-medium" for="check-safety">
                                                All safety protocols were followed during the work
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input checklist-item" type="checkbox" id="check-docs" required>
                                            <label class="form-check-label fw-medium" for="check-docs">
                                                All required documentation is complete and accurate
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <!-- Digital Signature -->
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Digital Signature <span class="text-danger">*</span></label>
                                        <div class="border rounded p-3 text-center" style="min-height: 120px;" id="signature-pad">
                                            <canvas id="signature-canvas" style="width: 100%; height: 100px; touch-action: none;"></canvas>
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-sm btn-outline-secondary me-2 btn-ripple" id="clear-signature">
                                                    <i class="uil uil-eraser me-1"></i> Clear
                                                </button>
                                                <small class="text-muted">Sign in the box above</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="validator-name" class="form-label">Your Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="validator-name" placeholder="Enter your full name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="validator-role" class="form-label">Your Role <span class="text-danger">*</span></label>
                                            <select class="form-select" id="validator-role" required>
                                                <option value="">Select your role</option>
                                                <option value="supervisor">Supervisor</option>
                                                <option value="quality_auditor">Quality Auditor</option>
                                                <option value="project_manager">Project Manager</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label for="validation-notes" class="form-label">Final Notes (Optional)</label>
                                            <textarea class="form-control" id="validation-notes" rows="3" 
                                                      placeholder="Add any final notes or comments about this validation..."></textarea>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="validation-confirm" required>
                                                <label class="form-check-label" for="validation-confirm">
                                                    I confirm that I have reviewed all evidence and this work meets our quality standards
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Validation Status Section -->
                                <div id="validation-status" class="mt-2">
                                    <div class="alert alert-info mb-0" role="alert">
                                        <i class="uil uil-info-circle me-2"></i>
                                        <span id="validation-status-text">Awaiting validation...</span>
                                    </div>
                                </div>
                                
                                <!-- Validation Success Modal -->
                                <div class="modal fade" id="validationSuccessModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-body text-center p-5">
                                                <div class="mb-4">
                                                    <div class="avatar-lg mx-auto mb-4">
                                                        <div class="avatar-title bg-success bg-soft text-success rounded-circle display-5">
                                                            <i class="uil uil-check-circle"></i>
                                                        </div>
                                                    </div>
                                                    <h4 class="text-success mb-3">Validation Complete!</h4>
                                                    <p class="text-muted mb-4">The quality audit has been successfully validated and submitted.</p>
                                                    <div class="d-flex justify-content-center">
                                                        <div class="spinner-border text-success me-2" role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                        <span>Redirecting...</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-light me-2">
                                <i class="uil uil-times me-1"></i> Cancel
                            </button>
                            <button type="button" class="btn btn-primary" id="save-audit" disabled>
                                <i class="uil uil-save me-1"></i> Save Audit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- container -->

<!-- Photo Modal -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Photo Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalPhoto" src="" class="img-fluid mb-3" alt="Full size photo" style="max-height: 60vh;">
                <div class="text-start">
                    <p><strong>Uploaded:</strong> <span id="photoTimestamp"></span></p>
                    <p><strong>Notes:</strong> <span id="photoNotes">No additional notes</span></p>
                    <div class="mb-3">
                        <label for="auditorComment" class="form-label">Your Comment</label>
                        <textarea class="form-control" id="auditorComment" rows="2" placeholder="Add your comment about this photo..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="savePhotoComment">Save Comment</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="validationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Validation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to validate this quality audit? This action cannot be undone.</p>
                <div class="mb-3">
                    <label for="validator-name" class="form-label">Your Name</label>
                    <input type="text" class="form-control" id="validator-name" required>
                </div>
                <div class="mb-3">
                    <label for="validator-notes" class="form-label">Additional Notes (Optional)</label>
                    <textarea class="form-control" id="validator-notes" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirm-validation">Confirm Validation</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<!-- Dropzone css -->
<link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Select2 css -->
<link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Signature Pad CSS -->
<style>
    #signature-pad {
        background-color: #f8f9fa;
        border: 1px dashed #dee2e6;
    }
    #signature-canvas {
        border: 1px solid #dee2e6;
        background-color: white;
        border-radius: 4px;
    }
    .checklist-item:not(:checked) + label {
        color: #6c757d;
    }
    .checklist-item:checked + label {
        color: #198754;
        text-decoration: line-through;
    }
    .signature-required:after {
        content: " *";
        color: #dc3545;
    }
    
    /* Button Enhancements */
    .btn {
        transition: all 0.2s ease-in-out;
        position: relative;
        overflow: hidden;
    }
    
    .btn:not(:disabled) {
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .btn:not(:disabled):hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    
    .btn:not(:disabled):active {
        transform: translateY(0);
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .btn-primary:not(:disabled) {
        background: linear-gradient(135deg, #4b6cb7 0%, #182848 100%);
        border: none;
    }
    
    .btn-success:not(:disabled) {
        background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
        border: none;
    }
    
    .btn-danger:not(:disabled) {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        border: none;
    }
    
    .btn-outline-secondary:not(:disabled):hover {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
    }
    
    /* Ripple effect */
    .btn-ripple {
        position: relative;
        overflow: hidden;
    }
    
    .btn-ripple:after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 5px;
        height: 5px;
        background: rgba(255, 255, 255, 0.5);
        opacity: 0;
        border-radius: 100%;
        transform: scale(1, 1) translate(-50%, -50%);
        transform-origin: 50% 50%;
    }
    
    .btn-ripple:not(:disabled):active:after {
        animation: ripple 0.6s ease-out;
    }
    
    @keyframes ripple {
        0% {
            transform: scale(0, 0);
            opacity: 0.5;
        }
        100% {
            transform: scale(20, 20);
            opacity: 0;
        }
    }
</style>
@endsection

@section('script')
<!-- Dropzone js -->
<script src="{{ asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
<!-- Select2 js -->
<script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
<!-- Signature Pad -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<!-- Lightbox -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

<script>
    // Initialize Dropzone
    Dropzone.autoDiscover = false;
    
    // Global variables
    let currentPhotoId = null;
    
    $(document).ready(function() {
        // Initialize select2
        $('#site-select, #engineer-select, #rework-reason').select2({
            width: '100%'
        });

        // Initialize Dropzone for photo uploads
        var photoDropzone = new Dropzone("#photo-dropzone", {
            url: "#", // Set your upload endpoint
            maxFilesize: 5, // MB
            maxFiles: 10,
            acceptedFiles: 'image/*',
            addRemoveLinks: true,
            dictDefaultMessage: "<i class='uil uil-cloud-upload'></i> <h4>Drop files here or click to upload</h4>",
            dictRemoveFile: "Remove",
            dictFileTooBig: "",
            init: function() {
                this.on("addedfile", function(file) {
                    updatePhotoPreview();
                    updateSaveButtonState();
                });
                this.on("removedfile", function(file) {
                    updatePhotoPreview();
                    updateSaveButtonState();
                });
            }
        });

        // Handle quality passed checkbox
        $('#quality-passed').change(function() {
            const isChecked = $(this).is(':checked');
            if (isChecked) {
                $('#rework-section').slideUp();
                $('html, body').animate({
                    scrollTop: $('#validation-section').offset().top - 20
                }, 300);
            } else {
                $('#rework-section').slideDown();
                // Scroll to rework section when opened
                $('html, body').animate({
                    scrollTop: $('#rework-section').offset().top - 20
                }, 300);
            }
            updateSaveButtonState();
        });
        
        // Handle other reason field
        $('#rework-reason').change(function() {
            if ($(this).val() === 'other') {
                $('#other-reason-container').slideDown();
            } else {
                $('#other-reason-container').slideUp();
            }
            updateSaveButtonState();
        });
        
        // Handle severity level selection
        $('input[name="severity"]').change(function() {
            $('label[for^="severity-"]').removeClass('active');
            $(`label[for="${$(this).attr('id')}"]`).addClass('active');
        });
        
        // Initialize with medium severity selected
        $('label[for="severity-medium"]').addClass('active');

        // Update photo preview
        function updatePhotoPreview() {
            const previewContainer = $('#photo-preview');
            previewContainer.empty();
            
            const files = photoDropzone.files;
            if (files.length > 0) {
                files.forEach(function(file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = `
                            <div class="col-md-2">
                                <div class="position-relative">
                                    <img src="${e.target.result}" class="img-fluid rounded border" alt="Preview">
                                    <button type="button" class="btn btn-xs btn-danger position-absolute top-0 end-0 m-1" data-dz-remove>
                                        <i class="uil uil-times"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                        previewContainer.append(preview);
                    };
                    reader.readAsDataURL(file);
                });
            }
            
            // Update validation message
            updateValidationStatus();
        }

        // Update validation status message
        function updateValidationStatus() {
            const files = photoDropzone.files;
            const validationStatus = $('#validation-status');
            
            if (files.length === 0) {
                validationStatus.html(`
                    <div class="alert alert-warning mb-0" role="alert">
                        <i class="uil uil-exclamation-triangle me-2"></i> Please upload at least one photo as evidence of the completed work.
                    </div>
                `);
                return false;
            }
            
            if (!$('#quality-passed').is(':checked')) {
                validationStatus.html(`
                    <div class="alert alert-warning mb-0" role="alert">
                        <i class="uil uil-exclamation-triangle me-2"></i> Please complete the quality check before validation.
                    </div>
                `);
                return false;
            }
            
            validationStatus.html(`
                <div class="alert alert-success mb-0" role="alert">
                    <i class="uil uil-check-circle me-2"></i> All requirements met. You can now validate this audit.
                </div>
            `);
            return true;
        }

        // Update save button state
        function updateSaveButtonState() {
            const files = photoDropzone.files;
            const qualityPassed = $('#quality-passed').is(':checked');
            const reworkReason = $('#rework-reason').val();
            
            // Enable save button if we have at least one photo and either:
            // 1. Quality passed, or
            // 2. Quality failed and we have a rework reason
            const canSave = files.length > 0 && (qualityPassed || (!qualityPassed && reworkReason));
            
            $('#save-audit').prop('disabled', !canSave);
            $('#validate-btn').prop('disabled', !(canSave && qualityPassed));
        }

        // Initialize signature pad
        let signaturePad;
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('signature-canvas');
            signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgba(255, 255, 255, 0)',
                penColor: '#000000',
                minWidth: 1,
                maxWidth: 2.5
            });
            
            // Make signature pad responsive
            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
            }
            // Simulate API call delay
            setTimeout(() => {
                console.log('Saving draft:', formData);
                
                // Show success feedback
                $btn.removeClass('btn-outline-secondary').addClass('btn-success');
                $btn.html('<i class="uil uil-check me-1"></i> Draft Saved');
                
                // Revert button state after delay
                setTimeout(() => {
                    $btn.removeClass('btn-success').addClass('btn-outline-secondary');
                    $btn.html(originalText);
                    $btn.prop('disabled', false);
                }, 2000);
                
                toastr.success('Draft saved successfully');
            }, 1000);
        });
        
        // Handle final validation with loading state
        $('#validate-btn').click(function() {
            const $btn = $(this);
            const originalText = $btn.html();
            
            if (!updateValidationButtonState()) {
                // Add shake animation for invalid form
                $btn.addClass('animate__animated animate__headShake');
                setTimeout(() => $btn.removeClass('animate__animated animate__headShake'), 1000);
                toastr.error('Please complete all required fields');
                return;
            }
            
            // Show loading state
            $btn.prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Validating...');
            
            // Simulate API call delay
            setTimeout(() => {
                const formData = {
                quality_passed: true,
                validator_name: $('#validator-name').val().trim(),
                validator_role: $('#validator-role').val(),
                validation_notes: $('#validation-notes').val().trim(),
                status: 'completed',
                completed_at: new Date().toISOString()
            };
            
                // In a real app, this would be an AJAX call to your backend
                console.log('Submitting validation:', formData);
                
                // Show success state
                $btn.removeClass('btn-success').addClass('btn-success');
                $btn.html('<i class="uil uil-check-circle me-1"></i> Validated!');
                
                // Show success message with delay
                toastr.success('Validation submitted successfully');
                
                // In a real app, you would redirect or update the UI
                setTimeout(() => {
                    // Show completion animation
                    $btn.html('<i class="uil uil-check-circle me-1"></i> Completed!');
                    
                    // Show success modal or redirect
                    const successModal = new bootstrap.Modal(document.getElementById('validationSuccessModal'));
                    successModal.show();
                    
                    // Redirect after delay
                    setTimeout(() => {
                        window.location.href = '';
                    }, 2000);
                    
                }, 1000);
        });
        
        // Handle rework submission
        function submitRework() {
            const formData = {
                quality_passed: false,
                rework_reason: $('#rework-reason').val(),
                rework_notes: $('#rework-notes').val(),
                other_reason: $('#other-reason').val(),
                severity: $('input[name="severity"]:checked').val(),
                status: 'rework_required',
                assigned_back: true
            };
            
            // In a real app, this would be an AJAX call to your backend
            console.log('Submitting rework:', formData);
            
            // Show success message
            toastr.success('Rework request submitted');
            
            // In a real app, you would redirect or update the UI
            setTimeout(() => {
                alert('Task has been sent back for rework. This page will now refresh.');
                window.location.reload();
            }, 1000);
        }

        // Update save button state on any relevant field change
        $('#quality-passed, #rework-reason').change(updateSaveButtonState);
        
        // Initial state
        updateSaveButtonState();
        updateValidationStatus();
        
        // Check if photo evidence exists
        function checkPhotoEvidence() {
            const hasPhotos = $('#engineer-photos .col-md-3').length > 0;
            if (!hasPhotos) {
                $('#photo-evidence-container').addClass('border-danger');
                $('#no-photos-message').removeClass('d-none');
                return false;
            }
            $('#photo-evidence-container').removeClass('border-danger');
            return true;
        }
        
        // Request photos from engineer
        $('#request-photos-btn').click(function() {
            // In a real app, this would trigger an email/notification to the engineer
            const engineerName = $('#engineer-select option:selected').text();
            alert(`Request for additional photos has been sent to ${engineerName}`);
            $(this).html('<i class="uil uil-check-circle me-1"></i> Request Sent').prop('disabled', true);
        });
        
        // Initial check
        checkPhotoEvidence();
        
        // Handle photo modal
        $('#photoModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const photoUrl = button.data('img-src');
            const photoId = button.data('photo-id');
            const notes = button.data('notes');
            const timestamp = button.data('timestamp');
            
            currentPhotoId = photoId;
            
            const modal = $(this);
            modal.find('#modalPhoto').attr('src', photoUrl);
            modal.find('#photoTimestamp').text(timestamp || 'Not available');
            modal.find('#photoNotes').text(notes || 'No notes provided');
        });
        
        // Handle save comment
        $('#savePhotoComment').click(function() {
            const comment = $('#auditorComment').val().trim();
            if (comment && currentPhotoId) {
                // Here you would typically save the comment via AJAX
                console.log(`Saving comment for photo ${currentPhotoId}:`, comment);
                
                // Show success message
                toastr.success('Comment saved successfully');
                
                // Close the modal after a short delay
                setTimeout(() => {
                    $('#photoModal').modal('hide');
                }, 1000);
            }
        });
    });
</script>
@endsection