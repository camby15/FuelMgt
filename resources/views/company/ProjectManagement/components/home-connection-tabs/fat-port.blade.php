@php
/**
 * FAT/Sub Box Port Tab Component
 * 
 * Displays port management interface
 */
@endphp

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label">Select Port Number</label>
                    <div class="btn-group d-flex" role="group">
                        @for($i = 1; $i <= 8; $i++)
                            <button type="button" class="btn btn-outline-primary port-btn {{ $i === 1 ? 'active' : '' }}" data-port="{{ $i }}">
                                Port {{ $i }}
                            </button>
                        @endfor
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Port Image</label>
                    <div class="border rounded p-3 text-center bg-light" style="min-height: 200px;">
                        <img id="portImage" src="" alt="Port Image" class="img-fluid d-none" style="max-height: 180px;">
                        <div id="portImagePlaceholder" class="text-muted">
                            <i class="fas fa-image fa-3x mb-2"></i>
                            <p>No image uploaded for this port</p>
                        </div>
                        <button class="btn btn-sm btn-outline-primary mt-2">
                            <i class="fas fa-upload me-1"></i> Upload Port Image
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label">FAT/Sub Box Overview</label>
                    <div class="border rounded p-3 text-center bg-light" style="min-height: 300px;">
                        <img id="boxImage" src="" alt="FAT/Sub Box Image" class="img-fluid d-none" style="max-height: 280px;">
                        <div id="boxImagePlaceholder" class="text-muted">
                            <i class="fas fa-network-wired fa-4x mb-3"></i>
                            <p>No overview image uploaded</p>
                        </div>
                        <button class="btn btn-sm btn-outline-primary mt-2">
                            <i class="fas fa-upload me-1"></i> Upload Overview Image
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
