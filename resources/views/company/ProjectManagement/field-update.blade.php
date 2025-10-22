@extends('layouts.vertical', ['page_title' => 'Engineers Field Update'])

@push('styles')
<style>
    .btn-delete {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
</style>
@endpush

@section('css')
<script>
    function toggleSiteIssues() {
        const connectionNo = document.getElementById('connectionNo');
        const siteIssuesContainer = document.getElementById('siteIssuesContainer');
        
        if (!siteIssuesContainer) {
            console.error('Required elements not found');
            return;
        }
        
        if (connectionNo.checked) {
            siteIssuesContainer.style.display = 'block';
            toggleConnectionReasonsByType();
        } else {
            siteIssuesContainer.style.display = 'none';
            // Clear all checkboxes when hidden
            const checkboxes = siteIssuesContainer.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            // Hide all image upload sections when connection is possible
            hideAllImageUploadSections();
        }
    }
    
    function toggleConnectionReasonsByType() {
        const connectionType = document.getElementById('connectionType');
        const quickOdnReasons = document.getElementById('quickOdnReasons');
        const traditionalReasons = document.getElementById('traditionalReasons');
        const siteIssuesContainer = document.getElementById('siteIssuesContainer');
        
        if (!connectionType || !quickOdnReasons || !traditionalReasons) {
            console.error('Required elements not found');
            return;
        }
        
        const selectedType = connectionType.value;
        
        // Only show reasons if connection is not possible
        if (siteIssuesContainer.style.display === 'block') {
            if (selectedType === 'quick') {
                quickOdnReasons.style.display = 'block';
                traditionalReasons.style.display = 'none';
            } else if (selectedType === 'traditional') {
                quickOdnReasons.style.display = 'none';
                traditionalReasons.style.display = 'block';
            } else {
                quickOdnReasons.style.display = 'none';
                traditionalReasons.style.display = 'none';
            }
        }
    }
    
    function toggleImageUploadSections() {
        const imageUploadSections = {
            'issue1': 'fatFullUploadSection',
            'issue2': 'noPowerUploadSection', 
            'issue3': 'outOfScopeUploadSection',
            'issue4': 'polePlantingUploadSection'
        };
        
        Object.keys(imageUploadSections).forEach(issueId => {
            const checkbox = document.getElementById(issueId);
            const uploadSection = document.getElementById(imageUploadSections[issueId]);
            
            if (checkbox && uploadSection) {
                if (checkbox.checked) {
                    uploadSection.style.display = 'block';
                    // Update out of scope reasons if it's the out of scope checkbox
                    if (issueId === 'issue3') {
                        updateOutOfScopeReasons();
                    }
                } else {
                    uploadSection.style.display = 'none';
                    // Clear any uploaded files when unchecked
                    const fileInput = uploadSection.querySelector('input[type="file"]');
                    const preview = uploadSection.querySelector('.image-preview-container');
                    if (fileInput) fileInput.value = '';
                    if (preview) preview.innerHTML = '';
                }
            }
        });
        
        // Handle out of scope checkboxes for connection reasons
        const quickOdnOutOfScope = document.getElementById('quickOdnOutOfScope');
        const traditionalOutOfScope = document.getElementById('traditionalOutOfScope');
        const outOfScopeSection = document.getElementById('outOfScopeUploadSection');
        
        if (outOfScopeSection) {
            if ((quickOdnOutOfScope && quickOdnOutOfScope.checked) || (traditionalOutOfScope && traditionalOutOfScope.checked)) {
                outOfScopeSection.style.display = 'block';
                updateOutOfScopeReasons();
            } else {
                outOfScopeSection.style.display = 'none';
                const fileInput = outOfScopeSection.querySelector('input[type="file"]');
                const preview = outOfScopeSection.querySelector('.image-preview-container');
                if (fileInput) fileInput.value = '';
                if (preview) preview.innerHTML = '';
            }
        }
    }
    
    function updateOutOfScopeReasons() {
        const connectionType = document.getElementById('connectionType');
        const reasonsList = document.getElementById('outOfScopeReasonsList');
        
        if (!connectionType || !reasonsList) {
            return;
        }
        
        const selectedType = connectionType.value;
        
        if (selectedType === 'quick') {
            // Quick ODN reasons
            reasonsList.innerHTML = `
                <li>Out of scope</li>
                <li>Customer over 300m away from subbox</li>
                <li>There is no fiber within customers area</li>
            `;
        } else if (selectedType === 'traditional') {
            // Traditional reasons
            reasonsList.innerHTML = `
                <li>Out of scope for legacy/Traditional</li>
                <li>Customer over 150m away from FAT</li>
                <li>There is no fiber within customers area</li>
            `;
        } else {
            // Default reasons
            reasonsList.innerHTML = `
                <li>Site location and accessibility</li>
                <li>Distance from nearest infrastructure</li>
                <li>Terrain/obstacle conditions</li>
                <li>Any relevant environmental factors</li>
            `;
        }
    }
    
    function hideAllImageUploadSections() {
        const sections = ['fatFullUploadSection', 'noPowerUploadSection', 'outOfScopeUploadSection', 'polePlantingUploadSection'];
        sections.forEach(sectionId => {
            const section = document.getElementById(sectionId);
            if (section) {
                section.style.display = 'none';
                const fileInput = section.querySelector('input[type="file"]');
                const preview = section.querySelector('.image-preview-container');
                if (fileInput) fileInput.value = '';
                if (preview) preview.innerHTML = '';
            }
        });
    }
    
    function toggleInstallationDetails() {
        const connectionType = document.getElementById('connectionType');
        const quickOdnSection = document.getElementById('quickOdnInstallationSection');
        const traditionalSection = document.getElementById('traditionalInstallationSection');
        
        if (!connectionType || !quickOdnSection || !traditionalSection) {
            console.error('Required elements not found');
            return;
        }
        
        const selectedType = connectionType.value;
        
        if (selectedType === 'quick') {
            quickOdnSection.style.display = 'block';
            traditionalSection.style.display = 'none';
        } else if (selectedType === 'traditional') {
            quickOdnSection.style.display = 'none';
            traditionalSection.style.display = 'block';
        } else {
            // Hide both sections if no connection type is selected
            quickOdnSection.style.display = 'none';
            traditionalSection.style.display = 'none';
        }
        
        // Update out of scope reasons if the section is visible
        const outOfScopeSection = document.getElementById('outOfScopeUploadSection');
        if (outOfScopeSection && outOfScopeSection.style.display === 'block') {
            updateOutOfScopeReasons();
        }
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleInstallationDetails();
        toggleSiteIssues();
        toggleConnectionReasonsByType();
    });
</script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- DataTables and Select2 CSS are loaded via main Vite bundle -->
<!-- Leaflet CSS is loaded via CDN above -->
<style>
    .customer-row {
        cursor: pointer;
        transition: all 0.2s;
    }
    .customer-row:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }
    .form-section {
        margin-bottom: 2rem;
        padding: 1.5rem;
        border: 1px solid #e9ecef;
        border-radius: 0.5rem;
        background: #fff;
    }
    .form-section h5 {
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 0.75rem;
        margin-bottom: 1.5rem;
        color: #4b4b5a;
    }
    .file-upload {
        border: 2px dashed #dee2e6;
        border-radius: 0.5rem;
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    .file-upload:hover {
        border-color: #727cf5;
        background-color: #f8f9ff;
    }
    .image-preview {
        max-width: 100px;
        max-height: 100px;
        margin: 0.5rem;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }
    .connection-reason-section {
        border-left: 3px solid #e9ecef;
        padding-left: 1rem;
        margin-left: 0.5rem;
    }
    .connection-reason-section:hover {
        border-left-color: #727cf5;
        background-color: #f8f9ff;
        border-radius: 0.25rem;
    }
    .form-check-label.fw-bold {
        color: #4b4b5a;
    }
</style>
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Project Management</a></li>
                            <li class="breadcrumb-item active">Field Updates</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Engineers Field Update</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <h4 class="header-title mb-3">Customer List</h4>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" id="search-customers" class="form-control" placeholder="Search customers...">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-centered table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>MSISDN</th>
                                        <th>Customer Name</th>
                                        <th>GPS Address</th>
                                        <th>Location</th>
                                        <th>GPS Coordinates</th>
                                        <th>Connection Type</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $locations = ['Accra', 'Kumasi', 'Tamale', 'Takoradi', 'Cape Coast'];
                                        $connectionTypes = ['Quick ODN', 'Traditional'];
                                        $statuses = [
                                            ['class' => 'bg-warning', 'text' => 'Pending'],
                                            ['class' => 'bg-success', 'text' => 'Completed'],
                                            ['class' => 'bg-info', 'text' => 'In Progress']
                                        ];
                                    @endphp
                                    
                                    @for($i = 1; $i <= 10; $i++)
                                    @php
                                        $location = $locations[array_rand($locations)];
                                        $status = $statuses[array_rand($statuses)];
                                        $connectionType = $connectionTypes[array_rand($connectionTypes)];
                                        $gpsPrefix = strtoupper(substr($location, 0, 2));
                                        $gpsAddress = $gpsPrefix . '-' . rand(100, 999) . '-' . rand(1000, 9999);
                                        // Generate Ghanaian phone number (starts with 02, 05, or 05, then 8 digits)
                                        $prefixes = ['24', '20', '50', '54', '55', '59', '26', '56', '57', '27', '28'];
                                        $prefix = $prefixes[array_rand($prefixes)];
                                        $msid = '0' . $prefix . rand(1000000, 9999999);
                                        $lat = 5.6 + (rand(0, 100) / 100);
                                        $lng = -0.2 + (rand(0, 100) / 100);
                                        $gpsCoords = number_format($lat, 6) . ', ' . number_format($lng, 6);
                                    @endphp
                                    <tr class="customer-row" data-bs-toggle="modal" data-bs-target="#fieldUpdateModal">
                                        <td>{{ $msid }}</td>
                                        <td>
                                            <h5 class="m-0">Customer {{ $i }}</h5>
                                            <p class="text-muted mb-0">customer{{ $i }}@example.com</p>
                                        </td>
                                        <td>{{ $gpsAddress }}</td>
                                        <td>{{ $location }}, Ghana</td>
                                        <td>{{ $gpsCoords }}</td>
                                        <td>{{ $connectionType }}</td>
                                        <td>
                                            <span class="badge {{ $status['class'] }}">{{ $status['text'] }}</span>
                                        </td>
                                        <td class="d-flex gap-2">
                                            <button class="btn btn-sm btn-primary" title="Update">
                                                <i class="fas fa-edit me-1"></i> Update
                                            </button>
                                            <button class="btn btn-sm btn-danger btn-delete" 
                                                    title="Delete" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteConfirmationModal" 
                                                    data-customer-id="CUST-{{ str_pad($i, 4, '0', STR_PAD_LEFT) }}" 
                                                    data-customer-name="Customer {{ $i }}" 
                                                    onclick="event.stopPropagation();">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <ul class="pagination pagination-rounded justify-content-end">
                                    <li class="page-item">
                                        <a class="page-link" href="javascript: void(0);" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="javascript: void(0);">1</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">2</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="javascript: void(0);" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div> <!-- container -->

    <!-- Field Update Modal -->
    <div class="modal fade" id="fieldUpdateModal" tabindex="-1" aria-labelledby="fieldUpdateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="fieldUpdateModalLabel">Field Update</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="fieldUpdateForm">
                        <!-- Connection Type -->
                        <div class="form-section">
                            <h5><i class="fas fa-link me-2"></i>Connection Details</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Connection Type</label>
                                        <select class="form-select" id="connectionType" required onchange="toggleInstallationDetails(); toggleConnectionReasonsByType();">
                                            <option value="">Select Connection Type</option>
                                            <option value="quick">Quick ODN</option>
                                            <option value="traditional">Traditional</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Is connection Possible -->
                        <div class="form-section">
                            <h5><i class="fas fa-link me-2"></i>Is connection Possible</h5>
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Is connection possible?</label>
                                        <div class="d-flex gap-4 mt-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="connectionPossible" id="connectionYes" value="yes" checked onchange="toggleSiteIssues()">
                                                <label class="form-check-label" for="connectionYes">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="connectionPossible" id="connectionNo" value="no" onchange="toggleSiteIssues()">
                                                <label class="form-check-label" for="connectionNo">
                                                    No
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="siteIssuesContainer" style="display: none;">
                                        <div class="mb-3">
                                            <label class="form-label">Reason(s) <span class="text-danger">*</span></label>
                                            
                                            <!-- Quick ODN Reasons (shown when Quick ODN is selected) -->
                                            <div id="quickOdnReasons" style="display: none;">
                                                <div class="mb-3 connection-reason-section">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox" id="quickOdnOutOfScope" name="connection_reasons[]" value="Out of scope" onchange="toggleImageUploadSections()">
                                                        <label class="form-check-label fw-bold" for="quickOdnOutOfScope">
                                                            Out of scope
                                                        </label>
                                                    </div>
                                                    <div class="ms-4">
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" id="quickOdnCustomer300m" name="connection_reasons[]" value="Customer over 300m away from subbox">
                                                            <label class="form-check-label" for="quickOdnCustomer300m">
                                                                Customer over 300m away from subbox
                                                            </label>
                                                        </div>
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" id="quickOdnNoFiber" name="connection_reasons[]" value="There is no fiber within customers area">
                                                            <label class="form-check-label" for="quickOdnNoFiber">
                                                                There is no fiber within customers area
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Traditional Reasons (shown when Traditional is selected) -->
                                            <div id="traditionalReasons" style="display: none;">
                                                <div class="mb-3 connection-reason-section">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox" id="traditionalOutOfScope" name="connection_reasons[]" value="Out of scope for legacy/Traditional" onchange="toggleImageUploadSections()">
                                                        <label class="form-check-label fw-bold" for="traditionalOutOfScope">
                                                            Out of scope for legacy/Traditional
                                                        </label>
                                                    </div>
                                                    <div class="ms-4">
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" id="traditionalCustomer150m" name="connection_reasons[]" value="Customer over 150m away from FAT">
                                                            <label class="form-check-label" for="traditionalCustomer150m">
                                                                Customer over 150m away from FAT
                                                            </label>
                                                        </div>
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" id="traditionalNoFiber" name="connection_reasons[]" value="There is no fiber within customers area">
                                                            <label class="form-check-label" for="traditionalNoFiber">
                                                                There is no fiber within customers area
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Site Issues Section -->
                                            <div class="mb-3 mt-4">
                                             
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" id="issue1" name="site_issues[]" value="FAT Full" onchange="toggleImageUploadSections()">
                                                            <label class="form-check-label" for="issue1">FAT Full</label>
                                                        </div>
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" id="issue2" name="site_issues[]" value="No Power FAT/Subbox" onchange="toggleImageUploadSections()">
                                                            <label class="form-check-label" for="issue2">No Power FAT/Subbox</label>
                                                        </div>
                                                        
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" id="issue4" name="site_issues[]" value="Pole Planting Required" onchange="toggleImageUploadSections()">
                                                            <label class="form-check-label" for="issue4">Pole Planting Required</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" id="issue5" name="site_issues[]" value="No Access">
                                                            <label class="form-check-label" for="issue5">No Access</label>
                                                        </div>
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" id="issue6" name="site_issues[]" value="On Hold">
                                                            <label class="form-check-label" for="issue6">On Hold</label>
                                                        </div>
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" id="issue7" name="site_issues[]" value="Cancelled">
                                                            <label class="form-check-label" for="issue7">Cancelled</label>
                                                        </div>
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" id="issue8" name="site_issues[]" value="Rescheduled">
                                                            <label class="form-check-label" for="issue8">Rescheduled</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3 mt-3">
                                                <label for="siteIssuesNotes" class="form-label">Additional Notes</label>
                                                <textarea class="form-control" id="siteIssuesNotes" name="site_issues_notes" rows="2" placeholder="Provide additional details about the issue(s)..."></textarea>
                                            </div>
                                            
                                            <!-- FAT Full Image Upload Section -->
                                            <div id="fatFullUploadSection" class="mb-3 mt-3" style="display: none;">
                                                <div class="alert alert-warning">
                                                    <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>FAT Full - Image Evidence Required</h6>
                                                    <p class="mb-2">Please upload clear photos showing:</p>
                                                    <ul class="mb-0">
                                                        <li>Full FAT cabinet/box</li>
                                                        <li>Port availability status</li>
                                                        <li>Overall cabinet condition</li>
                                                    </ul>
                                                </div>
                                                <label class="form-label">Upload FAT Full Evidence Photos</label>
                                                <div class="file-upload mb-3" id="fatFullUploadArea" onclick="document.getElementById('fatFullUpload').click()">
                                                    <i class="fas fa-camera fa-2x text-warning mb-2"></i>
                                                    <p class="mb-1">Click to upload FAT Full evidence photos</p>
                                                    <p class="text-muted mb-0">PNG, JPG, JPEG (max. 5MB per image)</p>
                                                    <input type="file" id="fatFullUpload" class="d-none" accept="image/*" multiple>
                                                </div>
                                                <div id="fatFullPreview" class="image-preview-container d-flex flex-wrap"></div>
                                            </div>

                                            <!-- No Power FAT/Subbox Image Upload Section -->
                                            <div id="noPowerUploadSection" class="mb-3 mt-3" style="display: none;">
                                                <div class="alert alert-danger">
                                                    <h6 class="alert-heading"><i class="fas fa-power-off me-2"></i>No Power FAT/Subbox - Image Evidence Required</h6>
                                                    <p class="mb-2">Please upload clear photos showing:</p>
                                                    <ul class="mb-0">
                                                        <li>Power connection status</li>
                                                        <li>Electrical panel/box condition</li>
                                                        <li>Power meter readings if accessible</li>
                                                        <li>Overall power infrastructure</li>
                                                    </ul>
                                                </div>
                                                <label class="form-label">Upload Power Issue Evidence Photos</label>
                                                <div class="file-upload mb-3" id="noPowerUploadArea" onclick="document.getElementById('noPowerUpload').click()">
                                                    <i class="fas fa-bolt fa-2x text-danger mb-2"></i>
                                                    <p class="mb-1">Click to upload power issue evidence photos</p>
                                                    <p class="text-muted mb-0">PNG, JPG, JPEG (max. 5MB per image)</p>
                                                    <input type="file" id="noPowerUpload" class="d-none" accept="image/*" multiple>
                                                </div>
                                                <div id="noPowerPreview" class="image-preview-container d-flex flex-wrap"></div>
                                            </div>

                                            <!-- Out of Scope Image Upload Section -->
                                            <div id="outOfScopeUploadSection" class="mb-3 mt-3" style="display: none;">
                                                <div class="alert alert-info">
                                                    <h6 class="alert-heading"><i class="fas fa-map-marked-alt me-2"></i>Out of Scope - Image Evidence Required</h6>
                                                    <p class="mb-2">Please upload clear photos showing:</p>
                                                    <ul class="mb-0" id="outOfScopeReasonsList">
                                                        <li>Site location and accessibility</li>
                                                        <li>Distance from nearest infrastructure</li>
                                                        <li>Terrain/obstacle conditions</li>
                                                        <li>Any relevant environmental factors</li>
                                                    </ul>
                                                </div>
                                                <label class="form-label">Upload Out of Scope Evidence Photos</label>
                                                <div class="file-upload mb-3" id="outOfScopeUploadArea" onclick="document.getElementById('outOfScopeUpload').click()">
                                                    <i class="fas fa-map fa-2x text-info mb-2"></i>
                                                    <p class="mb-1">Click to upload out of scope evidence photos</p>
                                                    <p class="text-muted mb-0">PNG, JPG, JPEG (max. 5MB per image)</p>
                                                    <input type="file" id="outOfScopeUpload" class="d-none" accept="image/*" multiple>
                                                </div>
                                                <div id="outOfScopePreview" class="image-preview-container d-flex flex-wrap"></div>
                                            </div>

                                            <!-- Pole Planting Required Image Upload Section -->
                                            <div id="polePlantingUploadSection" class="mb-3 mt-3" style="display: none;">
                                                <div class="alert alert-success">
                                                    <h6 class="alert-heading"><i class="fas fa-tree me-2"></i>Pole Planting Required - Image Evidence Required</h6>
                                                    <p class="mb-2">Please upload clear photos showing:</p>
                                                    <ul class="mb-0">
                                                        <li>Proposed pole planting location</li>
                                                        <li>Ground conditions and soil type</li>
                                                        <li>Accessibility for pole installation</li>
                                                        <li>Distance measurements to reference points</li>
                                                    </ul>
                                                </div>
                                                <label class="form-label">Upload Pole Planting Evidence Photos</label>
                                                <div class="file-upload mb-3" id="polePlantingUploadArea" onclick="document.getElementById('polePlantingUpload').click()">
                                                    <i class="fas fa-tree fa-2x text-success mb-2"></i>
                                                    <p class="mb-1">Click to upload pole planting evidence photos</p>
                                                    <p class="text-muted mb-0">PNG, JPG, JPEG (max. 5MB per image)</p>
                                                    <input type="file" id="polePlantingUpload" class="d-none" accept="image/*" multiple>
                                                </div>
                                                <div id="polePlantingPreview" class="image-preview-container d-flex flex-wrap"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Installation Site Details - Quick ODN -->
                        <div class="form-section" id="quickOdnInstallationSection" style="display: none;">
                            <h5><i class="fas fa-map-marker-alt me-2"></i>Installation Site Details - Quick ODN</h5>
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Picture of Customer Premises</label>
                                        <div class="file-upload mb-3" id="customerPremisesUploadArea" onclick="document.getElementById('customerPremisesUpload').click()">
                                            <i class="fas fa-camera fa-2x text-primary mb-2"></i>
                                            <p class="mb-1">Click to upload customer premises photo</p>
                                            <p class="text-muted mb-0">PNG, JPG, JPEG (max. 5MB)</p>
                                            <input type="file" id="customerPremisesUpload" class="d-none" accept="image/*">
                                        </div>
                                        <div id="customerPremisesPreview" class="d-flex flex-wrap"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Cable Routing Images</label>
                                        <div class="file-upload mb-3" id="cableRoutingUploadArea" onclick="document.getElementById('cableRoutingUpload').click()">
                                            <i class="fas fa-route fa-2x text-info mb-2"></i>
                                            <p class="mb-1">Click to upload cable routing images (supports 5 images)</p>
                                            <p class="text-muted mb-0">PNG, JPG, JPEG (max. 5MB per image)</p>
                                            <input type="file" id="cableRoutingUpload" class="d-none" accept="image/*" multiple>
                                        </div>
                                        <div id="cableRoutingPreview" class="d-flex flex-wrap"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Drop Cable Length</label>
                                        <select class="form-select" required>
                                            <option value="">Select Length</option>
                                            @for($i = 5; $i <= 30; $i++)
                                                <option value="{{ $i * 10 }}">{{ $i * 10 }}m</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Dead End Qty</label>
                                        <input type="number" class="form-control" min="0" value="0" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Cable Ties Qty</label>
                                        <input type="number" class="form-control" min="0" value="0" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Wall Clips Qty</label>
                                        <input type="number" class="form-control" min="0" value="0" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Eye Bolt Qty</label>
                                        <input type="number" class="form-control" min="0" value="0" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">ONT & ATB Installation</label>
                                        <div class="file-upload mb-3" id="ontAtbUploadArea" onclick="document.getElementById('ontAtbUpload').click()">
                                            <i class="fas fa-network-wired fa-2x text-success mb-2"></i>
                                            <p class="mb-1">Click to upload ONT & ATB installation photo</p>
                                            <p class="text-muted mb-0">PNG, JPG, JPEG (max. 5MB)</p>
                                            <input type="file" id="ontAtbUpload" class="d-none" accept="image/*">
                                        </div>
                                        <div id="ontAtbPreview" class="d-flex flex-wrap"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">ONT Serial Number</label>
                                        <input type="text" class="form-control" placeholder="Enter ONT serial number" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">ONT Binding Successful</label>
                                        <div class="d-flex gap-4 mt-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="ontBinding" id="ontBindingYes" value="yes" required>
                                                <label class="form-check-label" for="ontBindingYes">Yes</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="ontBinding" id="ontBindingNo" value="no" required>
                                                <label class="form-check-label" for="ontBindingNo">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">FDT/Xbox Number</label>
                                        <input type="text" class="form-control" placeholder="Format: FDT1_L1_F8 or X2_H8_S4.2" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Customer / Sub Box Scanned</label>
                                        <div class="d-flex gap-4 mt-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="customerSubboxScanned" id="customerSubboxYes" value="yes" required>
                                                <label class="form-check-label" for="customerSubboxYes">Yes</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="customerSubboxScanned" id="customerSubboxNo" value="no" required>
                                                <label class="form-check-label" for="customerSubboxNo">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Sub Box/FAT Port</label>
                                        <select class="form-select" required>
                                            <option value="">Select Port</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Inside Out Of FAT</label>
                                        <div class="file-upload mb-3" id="subboxPortUploadArea" onclick="document.getElementById('subboxPortUpload').click()">
                                            <i class="fas fa-plug fa-2x text-primary mb-2"></i>
                                            <p class="mb-1">Click to upload Inside Out Of FAT image</p>
                                            <p class="text-muted mb-0">PNG, JPG, JPEG (max. 5MB)</p>
                                            <input type="file" id="subboxPortUpload" class="d-none" accept="image/*">
                                        </div>
                                        <div id="subboxPortPreview" class="d-flex flex-wrap"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Sub Box/FAT Image (Picture of Sub Box)</label>
                                        <div class="file-upload mb-3" id="subboxImageUploadArea" onclick="document.getElementById('subboxImageUpload').click()">
                                            <i class="fas fa-box fa-2x text-secondary mb-2"></i>
                                            <p class="mb-1">Click to upload sub box image</p>
                                            <p class="text-muted mb-0">PNG, JPG, JPEG (max. 5MB)</p>
                                            <input type="file" id="subboxImageUpload" class="d-none" accept="image/*">
                                        </div>
                                        <div id="subboxImagePreview" class="d-flex flex-wrap"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Power Testing Results Image</label>
                                        <div class="file-upload mb-3" id="powerTestingUploadArea" onclick="document.getElementById('powerTestingUpload').click()">
                                            <i class="fas fa-bolt fa-2x text-danger mb-2"></i>
                                            <p class="mb-1">Click to upload power testing results image</p>
                                            <p class="text-muted mb-0">PNG, JPG, JPEG (max. 5MB)</p>
                                            <input type="file" id="powerTestingUpload" class="d-none" accept="image/*">
                                        </div>
                                        <div id="powerTestingPreview" class="d-flex flex-wrap"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Speed Testing Images</label>
                                        <div class="file-upload mb-3" id="speedTestingUploadArea" onclick="document.getElementById('speedTestingUpload').click()">
                                            <i class="fas fa-tachometer-alt fa-2x text-info mb-2"></i>
                                            <p class="mb-1">Click to upload speed testing images</p>
                                            <p class="text-muted mb-0">PNG, JPG, JPEG (max. 5MB per image)</p>
                                            <input type="file" id="speedTestingUpload" class="d-none" accept="image/*" multiple>
                                        </div>
                                        <div id="speedTestingPreview" class="d-flex flex-wrap"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">User Acceptance Test</label>
                                        <div class="file-upload mb-3" id="userAcceptanceUploadArea" onclick="document.getElementById('userAcceptanceUpload').click()">
                                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                            <p class="mb-1">Click to upload user acceptance test image</p>
                                            <p class="text-muted mb-0">PNG, JPG, JPEG (max. 5MB)</p>
                                            <input type="file" id="userAcceptanceUpload" class="d-none" accept="image/*">
                                        </div>
                                        <div id="userAcceptancePreview" class="d-flex flex-wrap"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Additional Information</label>
                                        <textarea class="form-control" rows="3" placeholder="Enter any additional information..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Installation Site Details - Traditional -->
                        <div class="form-section" id="traditionalInstallationSection" style="display: none;">
                            <h5><i class="fas fa-map-marker-alt me-2"></i>Installation Site Details - Traditional</h5>
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Picture of Customer Premises</label>
                                        <div class="file-upload mb-3" id="traditionalCustomerPremisesUploadArea" onclick="document.getElementById('traditionalCustomerPremisesUpload').click()">
                                            <i class="fas fa-camera fa-2x text-primary mb-2"></i>
                                            <p class="mb-1">Click to upload customer premises photo</p>
                                            <p class="text-muted mb-0">PNG, JPG, JPEG (max. 5MB)</p>
                                            <input type="file" id="traditionalCustomerPremisesUpload" class="d-none" accept="image/*">
                                        </div>
                                        <div id="traditionalCustomerPremisesPreview" class="d-flex flex-wrap"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Cable Routing Images</label>
                                        <div class="file-upload mb-3" id="traditionalCableRoutingUploadArea" onclick="document.getElementById('traditionalCableRoutingUpload').click()">
                                            <i class="fas fa-route fa-2x text-info mb-2"></i>
                                            <p class="mb-1">Click to upload cable routing images</p>
                                            <p class="text-muted mb-0">PNG, JPG, JPEG (max. 5MB per image)</p>
                                            <input type="file" id="traditionalCableRoutingUpload" class="d-none" accept="image/*" multiple>
                                        </div>
                                        <div id="traditionalCableRoutingPreview" class="d-flex flex-wrap"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Drop Cable Length</label>
                                        <select class="form-select" required>
                                            <option value="">Select Length</option>
                                            @for($i = 5; $i <= 30; $i++)
                                                <option value="{{ $i * 10 }}">{{ $i * 10 }}m</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Dead End Qty</label>
                                        <input type="number" class="form-control" min="0" value="0" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Cable Ties Qty</label>
                                        <input type="number" class="form-control" min="0" value="0" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Wall Clips Qty</label>
                                        <input type="number" class="form-control" min="0" value="0" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Eye Bolt Qty</label>
                                        <input type="number" class="form-control" min="0" value="0" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">ONT & ATB Installation</label>
                                        <div class="file-upload mb-3" id="traditionalOntAtbUploadArea" onclick="document.getElementById('traditionalOntAtbUpload').click()">
                                            <i class="fas fa-network-wired fa-2x text-success mb-2"></i>
                                            <p class="mb-1">Click to upload ONT & ATB installation photo</p>
                                            <p class="text-muted mb-0">PNG, JPG, JPEG (max. 5MB)</p>
                                            <input type="file" id="traditionalOntAtbUpload" class="d-none" accept="image/*">
                                        </div>
                                        <div id="traditionalOntAtbPreview" class="d-flex flex-wrap"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">ONT Serial Number</label>
                                        <input type="text" class="form-control" placeholder="Enter ONT serial number" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">ONT Binding Successful</label>
                                        <div class="d-flex gap-4 mt-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="traditionalOntBinding" id="traditionalOntBindingYes" value="yes" required>
                                                <label class="form-check-label" for="traditionalOntBindingYes">Yes</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="traditionalOntBinding" id="traditionalOntBindingNo" value="no" required>
                                                <label class="form-check-label" for="traditionalOntBindingNo">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">FDT/Xbox Number</label>
                                        <input type="text" class="form-control" placeholder="Format: FDT1_L1_F8 or X2_H8_S4.2" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Sub Box/FAT Port</label>
                                        <select class="form-select" required>
                                            <option value="">Select Port</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Inside Out Of FAT</label>
                                        <div class="file-upload mb-3" id="traditionalSubboxPortUploadArea" onclick="document.getElementById('traditionalSubboxPortUpload').click()">
                                            <i class="fas fa-plug fa-2x text-primary mb-2"></i>
                                            <p class="mb-1">Click to upload Inside Out Of FAT image</p>
                                            <p class="text-muted mb-0">PNG, JPG, JPEG (max. 5MB)</p>
                                            <input type="file" id="traditionalSubboxPortUpload" class="d-none" accept="image/*">
                                        </div>
                                        <div id="traditionalSubboxPortPreview" class="d-flex flex-wrap"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Sub Box/FAT Image (Picture of Sub Box)</label>
                                        <div class="file-upload mb-3" id="traditionalSubboxImageUploadArea" onclick="document.getElementById('traditionalSubboxImageUpload').click()">
                                            <i class="fas fa-box fa-2x text-secondary mb-2"></i>
                                            <p class="mb-1">Click to upload sub box image</p>
                                            <p class="text-muted mb-0">PNG, JPG, JPEG (max. 5MB)</p>
                                            <input type="file" id="traditionalSubboxImageUpload" class="d-none" accept="image/*">
                                        </div>
                                        <div id="traditionalSubboxImagePreview" class="d-flex flex-wrap"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Power Testing Results Image</label>
                                        <div class="file-upload mb-3" id="traditionalPowerTestingUploadArea" onclick="document.getElementById('traditionalPowerTestingUpload').click()">
                                            <i class="fas fa-bolt fa-2x text-danger mb-2"></i>
                                            <p class="mb-1">Click to upload power testing results image</p>
                                            <p class="text-muted mb-0">PNG, JPG, JPEG (max. 5MB)</p>
                                            <input type="file" id="traditionalPowerTestingUpload" class="d-none" accept="image/*">
                                        </div>
                                        <div id="traditionalPowerTestingPreview" class="d-flex flex-wrap"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Speed Test Images</label>
                                        <div class="file-upload mb-3" id="traditionalSpeedTestingUploadArea" onclick="document.getElementById('traditionalSpeedTestingUpload').click()">
                                            <i class="fas fa-tachometer-alt fa-2x text-info mb-2"></i>
                                            <p class="mb-1">Click to upload speed test images</p>
                                            <p class="text-muted mb-0">PNG, JPG, JPEG (max. 5MB per image)</p>
                                            <input type="file" id="traditionalSpeedTestingUpload" class="d-none" accept="image/*" multiple>
                                        </div>
                                        <div id="traditionalSpeedTestingPreview" class="d-flex flex-wrap"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">User Acceptance Test</label>
                                        <div class="file-upload mb-3" id="traditionalUserAcceptanceUploadArea" onclick="document.getElementById('traditionalUserAcceptanceUpload').click()">
                                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                            <p class="mb-1">Click to upload user acceptance test image</p>
                                            <p class="text-muted mb-0">PNG, JPG, JPEG (max. 5MB)</p>
                                            <input type="file" id="traditionalUserAcceptanceUpload" class="d-none" accept="image/*">
                                        </div>
                                        <div id="traditionalUserAcceptancePreview" class="d-flex flex-wrap"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Additional Information</label>
                                        <textarea class="form-control" rows="3" placeholder="Enter any additional information..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    @push('scripts')
                    <script>
                        // Handle file upload previews
                        function handleFileUpload(input, previewId) {
                            const preview = document.getElementById(previewId);
                            preview.innerHTML = '';
                            
                            if (input.files) {
                                Array.from(input.files).forEach(file => {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        const img = document.createElement('img');
                                        img.src = e.target.result;
                                        img.className = 'image-preview';
                                        preview.appendChild(img);
                                    }
                                    reader.readAsDataURL(file);
                                });
                            }
                        }

                        // Set up event listeners for issue-specific file uploads
                        document.getElementById('fatFullUpload').addEventListener('change', function() {
                            handleFileUpload(this, 'fatFullPreview');
                        });

                        document.getElementById('noPowerUpload').addEventListener('change', function() {
                            handleFileUpload(this, 'noPowerPreview');
                        });

                        document.getElementById('outOfScopeUpload').addEventListener('change', function() {
                            handleFileUpload(this, 'outOfScopePreview');
                        });

                        document.getElementById('polePlantingUpload').addEventListener('change', function() {
                            handleFileUpload(this, 'polePlantingPreview');
                        });

                        // Set up event listeners for Quick ODN installation file uploads
                        document.getElementById('customerPremisesUpload').addEventListener('change', function() {
                            handleFileUpload(this, 'customerPremisesPreview');
                        });

                        document.getElementById('cableRoutingUpload').addEventListener('change', function() {
                            handleFileUpload(this, 'cableRoutingPreview');
                        });

                        document.getElementById('ontAtbUpload').addEventListener('change', function() {
                            handleFileUpload(this, 'ontAtbPreview');
                        });

                        document.getElementById('subboxPortUpload').addEventListener('change', function() {
                            handleFileUpload(this, 'subboxPortPreview');
                        });

                        document.getElementById('subboxImageUpload').addEventListener('change', function() {
                            handleFileUpload(this, 'subboxImagePreview');
                        });

                        document.getElementById('powerTestingUpload').addEventListener('change', function() {
                            handleFileUpload(this, 'powerTestingPreview');
                        });

                        document.getElementById('speedTestingUpload').addEventListener('change', function() {
                            handleFileUpload(this, 'speedTestingPreview');
                        });

                        document.getElementById('userAcceptanceUpload').addEventListener('change', function() {
                            handleFileUpload(this, 'userAcceptancePreview');
                        });

                        // Set up event listeners for Traditional installation file uploads
                        document.getElementById('traditionalCustomerPremisesUpload').addEventListener('change', function() {
                            handleFileUpload(this, 'traditionalCustomerPremisesPreview');
                        });

                        document.getElementById('traditionalCableRoutingUpload').addEventListener('change', function() {
                            handleFileUpload(this, 'traditionalCableRoutingPreview');
                        });

                        document.getElementById('traditionalOntAtbUpload').addEventListener('change', function() {
                            handleFileUpload(this, 'traditionalOntAtbPreview');
                        });

                        document.getElementById('traditionalSubboxPortUpload').addEventListener('change', function() {
                            handleFileUpload(this, 'traditionalSubboxPortPreview');
                        });

                        document.getElementById('traditionalSubboxImageUpload').addEventListener('change', function() {
                            handleFileUpload(this, 'traditionalSubboxImagePreview');
                        });

                        document.getElementById('traditionalPowerTestingUpload').addEventListener('change', function() {
                            handleFileUpload(this, 'traditionalPowerTestingPreview');
                        });

                        document.getElementById('traditionalSpeedTestingUpload').addEventListener('change', function() {
                            handleFileUpload(this, 'traditionalSpeedTestingPreview');
                        });

                        document.getElementById('traditionalUserAcceptanceUpload').addEventListener('change', function() {
                            handleFileUpload(this, 'traditionalUserAcceptancePreview');
                        });

                        // Handle form submission
                        document.getElementById('fieldUpdateForm').addEventListener('submit', function(e) {
                            e.preventDefault();
                            // Add form submission logic here
                            alert('Field update submitted successfully!');
                            const modal = bootstrap.Modal.getInstance(document.getElementById('fieldUpdateModal'));
                            modal.hide();
                        });
                    </script>
                    @endpush
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="fieldUpdateForm" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle text-danger mb-3" style="font-size: 48px;"></i>
                        <h5>Are you sure you want to delete this customer?</h5>
                        <p class="mb-0">Customer: <strong id="customerName"></strong></p>
                        <p>ID: <strong id="customerId"></strong></p>
                        <p class="text-danger">This action cannot be undone!</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="fas fa-trash-alt me-1"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Initialize delete confirmation modal
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deleteConfirmationModal');
            
            // When modal is shown, update the customer details
            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget; // Button that triggered the modal
                const customerId = button.getAttribute('data-customer-id');
                const customerName = button.getAttribute('data-customer-name');
                
                // Update the modal's content
                document.getElementById('customerId').textContent = customerId;
                document.getElementById('customerName').textContent = customerName;
                
                // Set up the delete button click handler
                document.getElementById('confirmDeleteBtn').onclick = function() {
                    // Here you would typically make an AJAX call to delete the customer
                    console.log('Deleting customer:', customerId);
                    
                    // For demo purposes, just close the modal and remove the row
                    const modal = bootstrap.Modal.getInstance(deleteModal);
                    modal.hide();
                    button.closest('tr').remove();
                    
                    // Show a success message (you can replace this with a toast notification)
                    alert(`Customer ${customerName} (${customerId}) has been deleted.`);
                };
            });
            
            // Clean up event listeners when modal is hidden
            deleteModal.addEventListener('hidden.bs.modal', function() {
                document.getElementById('confirmDeleteBtn').onclick = null;
            });
        });
    </script>
    @endpush
@endsection