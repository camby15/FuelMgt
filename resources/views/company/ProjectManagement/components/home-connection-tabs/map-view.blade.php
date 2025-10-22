@php
/**
 * Map View Tab Component
 * 
 * Displays an interactive map of connections and teams
 */
@endphp

<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""
>
<style>
    #map {
        height: 600px;
        width: 100%;
        transition: all 0.3s ease;
        border-radius: 0.375rem;
        background: #f8f9fa;
        position: relative;
        cursor: grab;
    }
    
    #map:active {
        cursor: grabbing;
    }
    
    .leaflet-container {
        cursor: grab !important;
    }
    
    .leaflet-container:active {
        cursor: grabbing !important;
    }
    
    .leaflet-dragging .leaflet-container {
        cursor: grabbing !important;
    }
    @media (max-width: 768px) {
        #map {
            height: 400px;
        }
    }
    
    /* Improved popup styling */
    .leaflet-popup-content {
        margin: 0;
        width: 280px !important;
    }
    .leaflet-popup-content-wrapper {
        border-radius: 8px;
        box-shadow: 0 3px 14px rgba(0,0,0,0.15);
    }
    
    /* Loading state */
    #map-loading {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.95);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }
    
    .map-loading-spinner {
        width: 3rem;
        height: 3rem;
        border: 0.25em solid rgba(13, 110, 253, 0.2);
        border-right-color: #0d6efd;
        border-radius: 50%;
        animation: spinner 0.75s linear infinite;
        margin-bottom: 1rem;
    }
    
    @keyframes spinner {
        to { transform: rotate(360deg); }
    }
    
    /* Map controls */
    #map-controls .btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        background: white;
        color: #495057;
        border: 1px solid #dee2e6;
        box-shadow: 0 1px 5px rgba(0,0,0,0.1);
        transition: all 0.2s;
    }
    
    /* Coordinate search */
    #coordinate-search {
        width: 200px;
        border-radius: 4px;
        border: 1px solid #dee2e6;
        padding: 6px 12px;
        font-size: 14px;
        box-shadow: 0 1px 5px rgba(0,0,0,0.1);
    }
    
    #coordinate-search:focus {
        outline: none;
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .coordinate-search-container {
        position: relative;
        margin-bottom: 8px;
    }
    
    .coordinate-search-container button {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #6c757d;
        cursor: pointer;
        padding: 4px 8px;
    }
    
    .coordinate-search-container button:hover {
        color: #0d6efd;
    }
    
    #map-controls .btn:hover {
        background: #f8f9fa;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    
    #map-controls .btn.active {
        background: #0d6efd;
        color: white;
        border-color: #0a58ca;
    }
    
    /* Filter buttons */
    #mapFilters .btn {
        transition: all 0.2s;
    }
    
    #mapFilters .btn.active {
        background: #0d6efd;
        color: white;
        border-color: #0a58ca;
    }
</style>

<div class="card border-0 shadow-sm h-100 d-flex flex-column">
    <div class="card-header bg-white border-bottom d-flex flex-wrap justify-content-between align-items-center py-2">
        <h5 class="mb-0"><i class="fas fa-map-marked-alt me-2"></i>Map View</h5>
        <div class="btn-group btn-group-sm mt-2 mt-md-0" role="group" id="mapFilters">
            <button type="button" class="btn btn-outline-primary active" data-filter="connection">
                <i class="fas fa-plug me-1"></i> Connections
            </button>
            <button type="button" class="btn btn-outline-secondary" data-filter="team">
                <i class="fas fa-users me-1"></i> Teams
            </button>
            <button type="button" class="btn btn-outline-secondary" data-filter="issue">
                <i class="fas fa-exclamation-triangle me-1"></i> Issues
            </button>
        </div>
    </div>
    <div class="row g-0 h-100">
    <!-- Map Column -->
    <div class="col-md-8 position-relative" style="height: 600px;">
        <div id="map-loading" class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center bg-light" style="z-index: 2000; pointer-events: none;">
            <div class="text-center">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">Loading map...</p>
            </div>
        </div>
        
        <!-- Map Container - This should be empty for Leaflet to work -->
        <div id="map" class="h-100 w-100"></div>
        
        <!-- Map Controls - Outside map div -->
        <div id="map-controls" class="position-absolute top-0 end-0 m-3 d-flex flex-column gap-2" style="z-index: 1000; pointer-events: auto;">
            <!-- Coordinate Search -->
            <div class="coordinate-search-container shadow-sm">
                <input type="text" id="coordinate-search" placeholder="e.g. 51.505, -0.09" 
                       title="Enter coordinates as 'lat, lng' or 'lat lng'"
                       class="form-control form-control-sm">
                <button id="searchCoordinateBtn" title="Search coordinates">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            
            <div class="btn-group-vertical shadow-sm">
                <button id="zoomInBtn" class="btn btn-light" title="Zoom In" data-bs-toggle="tooltip" data-bs-placement="left">
                    <i class="fas fa-plus"></i>
                </button>
                <button id="zoomOutBtn" class="btn btn-light" title="Zoom Out" data-bs-toggle="tooltip" data-bs-placement="left">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
            <button id="locateMeBtn" class="btn btn-light shadow-sm" title="Locate Me" data-bs-toggle="tooltip" data-bs-placement="left">
                <i class="fas fa-location-arrow"></i>
            </button>
            <button id="fullscreenBtn" class="btn btn-light shadow-sm" title="Toggle Fullscreen" data-bs-toggle="tooltip" data-bs-placement="left">
                <i class="fas fa-expand"></i>
            </button>
        </div>
        
        <!-- Map Legend - Outside map div -->
        <div id="map-legend" class="position-absolute bottom-0 start-0 m-3 bg-white p-2 rounded shadow-sm" style="z-index: 1000; font-size: 0.85rem; pointer-events: auto;">
            <div class="fw-bold mb-1">Legend</div>
            <div class="d-flex align-items-center mb-1">
                <span style="display: inline-block; width: 12px; height: 12px; background-color: #28a745; border-radius: 50%; margin-right: 6px;"></span>
                <span>Customer</span>
            </div>
            <div class="d-flex align-items-center mb-1">
                <span style="display: inline-block; width: 12px; height: 12px; background-color: #007bff; border-radius: 50%; margin-right: 6px;"></span>
                <span>Team</span>
            </div>
            <div class="d-flex align-items-center">
                <span style="display: inline-block; width: 12px; height: 12px; background-color: #dc3545; border-radius: 50%; margin-right: 6px;"></span>
                <span>Issue</span>
            </div>
        </div>
    </div>
    
    <!-- Connections Sidebar -->
    <div class="col-md-4 border-start bg-white" style="height: 600px; overflow-y: auto;">
        <div class="p-3 border-bottom">
            <h5 class="mb-0 d-flex justify-content-between align-items-center">
                <span id="sidebarTitle"><i id="sidebarTitleIcon" class="fas fa-plug me-2"></i>All Connections</span>
                <span class="badge bg-primary rounded-pill" id="sidebarCount">0</span>
            </h5>
        </div>
        <div id="connectionsList" class="list-group list-group-flush">
            <!-- Sidebar list will be loaded here based on active filter -->
            <div class="text-center py-4 text-muted">
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 mb-0" id="sidebarLoadingText">Loading...</p>
            </div>
        </div>
        </div><!-- End of card-body -->
    </div><!-- End of card -->
</div>

<!-- Connection Details Modal -->
<div class="modal fade" id="connectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plug me-2"></i>
                    <span id="connectionTitle">Connection Details</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <p><strong>Customer ID:</strong> <span id="connectionId">-</span></p>
                    <p><strong>Status:</strong> <span id="connectionStatus" class="badge">-</span></p>
                    <p><strong>Address:</strong> <span id="connectionAddress">-</span></p>
                    <p><strong>Last Active:</strong> <span id="connectionLastActive">-</span></p>
                </div>
                <div class="alert alert-light">
                    <p class="mb-0" id="connectionDetails">-</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Close
                </button>
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-external-link-alt me-1"></i> View Full Details
                </button>
            </div>
        </div>
    </div>
</div>

@push('javascript')
<script
    src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""
></script>
<script>
// Check if Leaflet is loaded
if (typeof L === 'undefined') {
    console.error('Leaflet not loaded. Check your includes.');
}

function initMap() {
    const mapElement = document.getElementById('map');
    if (!mapElement) return;
    
    try {
        // Initialize map with all interaction options enabled
        const map = L.map('map', {
            center: [5.6037, -0.1870],
            zoom: 13,
            dragging: true,              // Enable dragging/panning
            touchZoom: true,              // Enable touch zoom
            scrollWheelZoom: true,        // Enable scroll wheel zoom
            doubleClickZoom: true,        // Enable double click zoom
            boxZoom: true,                // Enable box zoom
            keyboard: true,               // Enable keyboard navigation
            zoomControl: true,            // Show zoom controls
            attributionControl: true      // Show attribution
        });
        
        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: ' OpenStreetMap contributors',
            maxZoom: 19,
            minZoom: 6
        }).addTo(map);
        
        // Store map instance globally
        window.map = map;
        
        // Hide loading indicator
        const loading = document.getElementById('map-loading');
        if (loading) loading.style.display = 'none';
        
        // Initialize connections list with sample data
        const activeConnections = markersData.filter(marker => 
            marker.type === 'connection' && marker.status === 'active'
        );
        updateConnectionsList(activeConnections);
        
        // Force resize
        setTimeout(() => map.invalidateSize(), 100);

        // Once map exists, initialize markers, controls and listeners
        addMarkers();
        initMapControls();
        setupEventListeners();
        
        return map;
    } catch (error) {
        console.error('Map error:', error);
        return null;
    }
}

// Helper function to get status color
function getStatusColor(status) {
    if (!status) return 'secondary';
    
    const statusLower = status.toLowerCase();
    
    switch(statusLower) {
        case 'active':
        case 'completed':
            return 'success';
        case 'pending':
        case 'scheduled':
            return 'warning';
        case 'inactive':
        case 'cancelled':
            return 'danger';
        case 'in_progress':
        case 'processing':
            return 'info';
        case 'on_hold':
        case 'hold':
            return 'secondary';
        default:
            return 'primary';
    }
}

// Function to update connections list
function updateConnectionsList(connections) {
    const container = document.getElementById('connectionsList');
    const countElement = document.getElementById('sidebarCount');
    
    if (!connections || connections.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                <p class="mb-0">No active connections found</p>
            </div>
        `;
        countElement.textContent = '0';
        return;
    }
    
    countElement.textContent = connections.length;
    
    const listItems = connections.map(conn => `
        <div class="list-group-item list-group-item-action" 
             data-lat="${conn.lat}" 
             data-lng="${conn.lng}"
             style="cursor: pointer;">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">${conn.title}</h6>
                <small class="badge bg-${getStatusColor(conn.status)}">
                    ${conn.status ? conn.status.charAt(0).toUpperCase() + conn.status.slice(1) : 'Unknown'}
                </small>
            </div>
            <p class="mb-1 small text-muted">
                <i class="fas fa-map-marker-alt me-1"></i> ${conn.address || 'Location not specified'}
            </p>
        </div>
    `).join('');
    
    container.innerHTML = listItems;
    
    // Add click handlers to list items
    container.querySelectorAll('.list-group-item').forEach(item => {
        item.addEventListener('click', function() {
            const lat = parseFloat(this.dataset.lat);
            const lng = parseFloat(this.dataset.lng);
            if (window.map && !isNaN(lat) && !isNaN(lng)) {
                window.map.setView([lat, lng], 15);
                // Optional: Highlight the marker on the map
                // You can implement this based on your marker implementation
            }
        });
    });
}

// Update sidebar based on active filter and data
function updateSidebarList(filter, data) {
    const titleEl = document.getElementById('sidebarTitle');
    const iconEl = document.getElementById('sidebarTitleIcon');
    const countEl = document.getElementById('sidebarCount');
    const container = document.getElementById('connectionsList');
    const loadingText = document.getElementById('sidebarLoadingText');

    if (loadingText) loadingText.textContent = 'Loading...';

    let items = [];
    if (filter === 'connection') {
        titleEl.innerHTML = '<i id="sidebarTitleIcon" class="fas fa-plug me-2"></i>All Connections';
        items = data.connections || []; // Show ALL connections, not just active
        updateConnectionsList(items);
        return;
    }

    if (filter === 'team') {
        titleEl.innerHTML = '<i id="sidebarTitleIcon" class="fas fa-users me-2"></i>Teams';
        items = data.teams || [];
        countEl.textContent = items.length;
        if (items.length === 0) {
            container.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                    <p class="mb-0">No teams found</p>
                </div>
            `;
            return;
        }
        container.innerHTML = items.map(t => `
            <div class="list-group-item list-group-item-action" ${t.lat!=null && t.lng!=null ? `data-lat="${t.lat}" data-lng="${t.lng}" style="cursor:pointer;"` : ''}>
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1">${t.title}</h6>
                    <small class="text-${(t.status||'').toLowerCase()==='active'?'success':'secondary'}">${t.status || ''}</small>
                </div>
                <small class="text-muted d-block">
                    ${t.location ? `<i class=\"fas fa-map-marker-alt me-1\"></i> ${t.location.charAt(0).toUpperCase() + t.location.slice(1)}` : ''}
                </small>
                <small class="text-muted d-block">
                    ${t.contact ? `<i class=\"fas fa-phone me-1\"></i> ${t.contact}` : ''}
                </small>
            </div>
        `).join('');
        // Center map on team when clicked if coordinates available
        container.querySelectorAll('.list-group-item[data-lat][data-lng]').forEach(item => {
            item.addEventListener('click', function() {
                const lat = parseFloat(this.dataset.lat);
                const lng = parseFloat(this.dataset.lng);
                if (window.map && !isNaN(lat) && !isNaN(lng)) {
                    window.map.setView([lat, lng], 15);
                }
            });
        });
        return;
    }

    if (filter === 'issue') {
        titleEl.innerHTML = '<i id="sidebarTitleIcon" class="fas fa-exclamation-triangle me-2"></i>Issues';
        items = data.issues || [];
        countEl.textContent = items.length;
        if (items.length === 0) {
            container.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                    <p class="mb-0">No issues found</p>
                </div>
            `;
            return;
        }
        container.innerHTML = items.map(i => `
            <div class="list-group-item">
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1">${i.title}</h6>
                    <small class="badge bg-${(i.severity||'medium')==='high'?'danger':(i.severity==='medium'?'warning':'info')}">${(i.severity||'').toUpperCase()}</small>
                </div>
                <p class="mb-1 small text-muted">${i.description || ''}</p>
                <small class="text-muted">${i.reported || ''}</small>
            </div>
        `).join('');
        return;
    }
}

// Initialize on DOM ready and also on tab show if present
document.addEventListener('DOMContentLoaded', function() {
    const mapElementExists = !!document.getElementById('map');
    if (mapElementExists) {
        initMap();
        loadMarkersData();
        setTimeout(() => {
            const loading = document.getElementById('map-loading');
            if (loading) {
                loading.style.opacity = '0';
                setTimeout(() => { loading.style.display = 'none'; }, 300);
            }
        }, 500);
    }

    // Handle tab switching if this component is inside a tab
    const mapTab = document.querySelector('[data-bs-target$="map-view"], [data-bs-target="#map-view"]');
    if (mapTab) {
        mapTab.addEventListener('shown.bs.tab', function() {
            if (window.map) {
                setTimeout(() => window.map.invalidateSize(), 100);
            } else {
                initMap();
            }
            loadMarkersData();
        });
    }
    // Listen for customer deletions to refresh map and sidebar
    window.addEventListener('customerDeleted', function(e) {
        try {
            if (typeof loadMarkersData === 'function') {
                loadMarkersData();
            } else {
                // Fallback: re-initialize if needed
                if (typeof initMap === 'function') initMap();
            }
        } catch (err) {
            console.warn('customerDeleted refresh failed', err);
        }
    });
});
    
    // Dynamic markers populated from backend
    let markersData = [];

    async function loadMarkersData() {
        try {
            const response = await fetch(`{{ route('project-management.map-data') }}`, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin'
            });
            if (!response.ok) throw new Error(`Failed to load map data (${response.status})`);
            const contentType = response.headers.get('content-type') || '';
            if (!contentType.includes('application/json')) {
                throw new Error('Unexpected response (not JSON). Check authentication/company session.');
            }
            const payload = await response.json();
            const list = [];
            const connections = payload?.data?.connections || [];
            const teams = payload?.data?.teams || [];
            const issues = payload?.data?.issues || [];
            connections.forEach(c => list.push(c));
            teams.forEach(t => list.push(t));
            issues.forEach(i => list.push(i));
            markersData = list;

            // Update sidebar according to current filter
            const activeFilter = document.querySelector('#mapFilters .btn.active')?.dataset.filter || 'connection';
            updateSidebarList(activeFilter, { connections, teams, issues });

            // Refresh markers on map
            addMarkers();
        } catch (e) {
            console.error('Map data load error:', e);
            // Ensure UI isn't stuck in loading state
            updateConnectionsList([]);
            const loading = document.getElementById('map-loading');
            if (loading) loading.style.display = 'none';
            if (typeof showToast === 'function') {
                showToast('Map data error', e.message || 'Failed to load map data', 'danger');
            }
        }
    }
    
    // Custom icons with better visibility
    const icons = {
        connection: L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            className: 'connection-marker'
        }),
        connectionNoGPS: L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-orange.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            className: 'connection-marker-no-gps'
        }),
        team: L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            className: 'team-marker'
        }),
        issue: L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            className: 'issue-marker'
        })
    };
    
    // Marker layer groups
    const markerLayers = {
        connection: L.layerGroup(),
        team: L.layerGroup(),
        issue: L.layerGroup()
    };
    
    // Add markers to the map with better performance
    function addMarkers() {
        // Show loading state
        const loading = document.getElementById('map-loading');
        loading.style.display = 'flex';
        
        // Use requestAnimationFrame for smoother UI updates
        requestAnimationFrame(() => {
            // Clear existing markers
            Object.values(markerLayers).forEach(layer => layer.clearLayers());
            
            // Get active filter
            const activeFilter = document.querySelector('#mapFilters .btn.active')?.dataset.filter || 'all';
            const visibleMarkers = [];
            
            // Process markers in chunks to prevent UI freezing
            const processChunk = (startIndex, chunkSize) => {
                const endIndex = Math.min(startIndex + chunkSize, markersData.length);
                
                for (let i = startIndex; i < endIndex; i++) {
                    const markerData = markersData[i];
                    if (activeFilter !== 'all' && markerData.type !== activeFilter) continue;
                    
                    // Use the appropriate icon based on marker type
                    const iconKey = markerData.type;
                    
                    const marker = L.marker([markerData.lat, markerData.lng], {
                        icon: icons[iconKey],
                        title: markerData.title,
                        riseOnHover: true
                    });
                    
                    // Create dynamic popup content based on marker type
                    let popupContent = `
                        <div class="p-2">
                            <div class="d-flex align-items-center mb-2">
                                <div class="flex-shrink-0 me-2">
                                    <i class="fas ${getMarkerIcon(markerData.type)} fa-2x text-${getMarkerColor(markerData.type)}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 text-truncate" style="max-width: 200px">${markerData.title}</h6>
                                    <small class="text-muted">${markerData.id}</small>
                                </div>
                            </div>
                            <div class="map-detail-item">
                                <strong>Status:</strong>
                                <span class="badge bg-${getStatusBadgeClass(markerData.status || 'active')}">
                                    ${(markerData.status || 'active').toUpperCase()}
                                </span>
                            </div>`;

                    // Connection specific fields
                    // Show address info for connections
                    if (markerData.type === 'connection' && markerData.address) {
                        popupContent += `
                            <div class="map-detail-item">
                                <strong>Address:</strong>
                                <span>${markerData.address}</span>
                            </div>`;
                    }
                    
                    if (markerData.plan) {
                        popupContent += `
                            <div class="map-detail-item">
                                <strong>Plan:</strong>
                                <span>${markerData.plan}</span>
                            </div>`;
                    }
                    
                    // Team specific fields
                    if (markerData.type === 'team') {
                        // Show team location
                        if (markerData.location) {
                            popupContent += `
                                <div class="map-detail-item">
                                    <strong>Location:</strong>
                                    <span>${markerData.location.charAt(0).toUpperCase() + markerData.location.slice(1)}</span>
                                </div>`;
                        }
                        
                        if (markerData.contact) {
                            popupContent += `
                                <div class="map-detail-item">
                                    <strong>Contact:</strong>
                                    <a href="tel:${markerData.contact}">${markerData.contact}</a>
                                </div>`;
                        }
                        
                        if (markerData.members) {
                            popupContent += `
                                <div class="map-detail-item">
                                    <strong>Team Size:</strong>
                                    <span>${markerData.members} members</span>
                                </div>`;
                        }
                        
                        if (markerData.currentJob) {
                            popupContent += `
                                <div class="map-detail-item">
                                    <strong>Current Job:</strong>
                                    <span>${markerData.currentJob || 'Available'}</span>
                                </div>`;
                        }
                        
                        if (markerData.vehicle) {
                            popupContent += `
                                <div class="map-detail-item">
                                    <strong>Vehicle:</strong>
                                    <span>${markerData.vehicle}</span>
                                </div>`;
                        }
                    }
                    
                    // Issue specific fields
                    if (markerData.severity) {
                        popupContent += `
                            <div class="map-detail-item">
                                <strong>Severity:</strong>
                                <span class="badge bg-${markerData.severity === 'high' ? 'danger' : markerData.severity === 'medium' ? 'warning' : 'info'}">
                                    ${markerData.severity.toUpperCase()}
                                </span>
                            </div>
                            <div class="map-detail-item">
                                <strong>Reported:</strong>
                                <span>${markerData.reported}</span>
                            </div>`;
                        if (markerData.reporter) {
                            popupContent += `
                                <div class="map-detail-item">
                                    <strong>Reporter:</strong>
                                    <span>${markerData.reporter}</span>
                                </div>`;
                        }
                    }
                    
                    // Common fields - Skip address for connections since it's already shown above
                    if (markerData.address && markerData.type !== 'connection') {
                        popupContent += `
                            <div class="map-detail-item">
                                <strong>Address:</strong>
                                <span>${markerData.address}</span>
                            </div>`;
                    }
                    
                    if (markerData.lastActive && !markerData.members) {
                        popupContent += `
                            <div class="map-detail-item">
                                <strong>Last Active:</strong>
                                <span>${markerData.lastActive}</span>
                            </div>`;
                    }
                    
                    
                    marker.bindPopup(popupContent, {
                        maxWidth: 300,
                        minWidth: 250,
                        className: `map-popup ${markerData.type}-popup`
                    });
                    
                    marker.addTo(markerLayers[markerData.type]);
                    visibleMarkers.push(marker);
                }
                
                // Process next chunk if needed
                if (endIndex < markersData.length) {
                    setTimeout(() => processChunk(endIndex, chunkSize), 0);
                } else {
                    // All markers processed
                    finalizeMarkers(visibleMarkers, activeFilter);
                }
            };
            
            // Start processing in chunks of 20 markers
            processChunk(0, 20);
        });
        
        // Helper functions for marker styling
        function getMarkerIcon(type) {
            const icons = {
                connection: 'fa-plug',
                team: 'fa-users',
                issue: 'fa-exclamation-triangle'
            };
            return icons[type] || 'fa-map-marker-alt';
        }
        
        function getMarkerColor(type) {
            const colors = {
                connection: 'success',
                team: 'primary',
                issue: 'danger'
            };
            return colors[type] || 'secondary';
        }
        
        function getStatusBadgeClass(status) {
            const classes = {
                active: 'success',
                connected: 'success',
                on: 'success',
                onSite: 'primary',
                open: 'warning',
                inProgress: 'info',
                closed: 'secondary',
                inactive: 'secondary',
                disconnected: 'danger',
                error: 'danger',
                high: 'danger',
                medium: 'warning',
                low: 'info'
            };
            return classes[status.toLowerCase()] || 'secondary';
        }
        
        function finalizeMarkers(visibleMarkers, activeFilter) {
            // Add active layers to map
            if (activeFilter === 'all') {
                Object.values(markerLayers).forEach(layer => layer.addTo(map));
            } else {
                markerLayers[activeFilter].addTo(map);
            }
            
            // Fit map to bounds of visible markers
            if (visibleMarkers.length > 0) {
                const group = new L.featureGroup(visibleMarkers);
                map.fitBounds(group.getBounds().pad(0.1), {
                    maxZoom: 16,
                    animate: true,
                    duration: 1
                });
            }
            
            // Hide loading state
            document.getElementById('map-loading').style.display = 'none';
            
            // Initialize tooltips
            if (window.bootstrap && window.bootstrap.Tooltip) {
                document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                    new bootstrap.Tooltip(el);
                });
            }
        }
    }
    
    // Coordinate search functionality
    function initCoordinateSearch() {
        const searchInput = document.getElementById('coordinate-search');
        const searchButton = document.getElementById('searchCoordinateBtn');
        
        function searchCoordinates() {
            const input = searchInput.value.trim();
            if (!input) return;
            
            // Try to parse different coordinate formats
            let lat, lng;
            
            // Format: lat,lng or lat, lng
            const commaMatch = input.match(/^\s*([-+]?[0-9]*\.?[0-9]+)\s*[,]\s*([-+]?[0-9]*\.?[0-9]+)\s*$/);
            
            // Format: lat lng
            const spaceMatch = !commaMatch && input.match(/^\s*([-+]?[0-9]*\.?[0-9]+)\s+([-+]?[0-9]*\.?[0-9]+)\s*$/);
            
            if (commaMatch) {
                lat = parseFloat(commaMatch[1]);
                lng = parseFloat(commaMatch[2]);
            } else if (spaceMatch) {
                lat = parseFloat(spaceMatch[1]);
                lng = parseFloat(spaceMatch[2]);
            }
            
            if (isNaN(lat) || isNaN(lng)) {
                geocodePlaceName(input);
                return;
            }
            
            // Validate latitude and longitude ranges
            if (lat < -90 || lat > 90) {
                showToast('Invalid latitude', 'Latitude must be between -90 and 90', 'error');
                return;
            }
            
            if (lng < -180 || lng > 180) {
                showToast('Invalid longitude', 'Longitude must be between -180 and 180', 'error');
                return;
            }
            
            // Pan and zoom to the coordinates
            if (!window.map) return;
            window.map.flyTo([lat, lng], 15, {
                duration: 1,
                easeLinearity: 0.25
            });
            
            // Add a marker at the searched location
            if (window.searchMarker && window.map) {
                window.map.removeLayer(window.searchMarker);
            }
            
            window.searchMarker = L.marker([lat, lng], {
                icon: L.divIcon({
                    html: '<i class="fas fa-map-pin" style="color: #dc3545; font-size: 32px;"></i>',
                    iconSize: [32, 32],
                    iconAnchor: [16, 32],
                    popupAnchor: [0, -32],
                    className: 'search-marker'
                })
            }).addTo(window.map);
            
            // Show a popup with the coordinates
            window.searchMarker.bindPopup(`
                <div class="text-center">
                    <strong>Searched Location</strong><br>
                    ${lat.toFixed(6)}, ${lng.toFixed(6)}
                </div>
            `).openPopup();
            
            // Clear the input
            searchInput.value = '';
            
            // Show success message
            showToast('Location found', `Centered on ${lat.toFixed(6)}, ${lng.toFixed(6)}`, 'success');
        }
        
        // Handle search button click
        searchButton.addEventListener('click', searchCoordinates);
        
        // Handle Enter key in search input
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchCoordinates();
            }
        });

        // Geocode a place name using Nominatim (OpenStreetMap)
        async function geocodePlaceName(query) {
            try {
                const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&addressdetails=1&limit=1`;
                const response = await fetch(url, { headers: { 'Accept': 'application/json' } });
                if (!response.ok) throw new Error('Geocoding request failed');
                const results = await response.json();
                if (!Array.isArray(results) || results.length === 0) {
                    showToast('Location not found', `Could not find "${query}"`, 'danger');
                    return;
                }
                const place = results[0];
                const lat = parseFloat(place.lat);
                const lng = parseFloat(place.lon);
                if (isNaN(lat) || isNaN(lng) || !window.map) {
                    showToast('Location error', 'Invalid geocoding result', 'danger');
                    return;
                }
                window.map.flyTo([lat, lng], 13, { duration: 1, easeLinearity: 0.25 });
                if (window.searchMarker && window.map) {
                    window.map.removeLayer(window.searchMarker);
                }
                window.searchMarker = L.marker([lat, lng], {
                    icon: L.divIcon({
                        html: '<i class="fas fa-map-pin" style="color: #0d6efd; font-size: 32px;"></i>',
                        iconSize: [32, 32],
                        iconAnchor: [16, 32],
                        popupAnchor: [0, -32],
                        className: 'search-marker'
                    })
                }).addTo(window.map);
                window.searchMarker.bindPopup(`
                    <div class="text-start">
                        <strong>${place.display_name || query}</strong><br>
                        ${lat.toFixed(6)}, ${lng.toFixed(6)}
                    </div>
                `).openPopup();
                searchInput.value = '';
                showToast('Location found', `Centered on ${place.display_name || query}`, 'success');
            } catch (err) {
                console.error('Geocoding error:', err);
                showToast('Geocoding failed', 'Please try again later', 'danger');
            }
        }
    }
    
    // Show toast notification
    function showToast(title, message, type = 'info') {
        const typeMap = { error: 'danger', danger: 'danger', success: 'success', warning: 'warning', info: 'info', primary: 'primary' };
        const bsType = typeMap[type] || 'info';
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${bsType} border-0 position-fixed bottom-0 end-0 m-3`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <strong>${title}</strong><br>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        
        document.body.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        // Remove toast after it's hidden
        toast.addEventListener('hidden.bs.toast', function() {
            toast.remove();
        });
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            bsToast.hide();
        }, 5000);
    }
    
    // Fullscreen control
    function toggleFullScreen() {
        const elem = document.documentElement;
        if (!document.fullscreenElement) {
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.webkitRequestFullscreen) { /* Safari */
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) { /* IE11 */
                elem.msRequestFullscreen();
            }
            document.getElementById('fullscreenBtn').innerHTML = '<i class="fas fa-compress"></i>';
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) { /* Safari */
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) { /* IE11 */
                document.msExitFullscreen();
            }
            document.getElementById('fullscreenBtn').innerHTML = '<i class="fas fa-expand"></i>';
        }
    }

    // Initialize map controls
    function initMapControls() {
        if (!window.map) return;
        const map = window.map;
        // Initialize coordinate search
        initCoordinateSearch();
        // Zoom controls with better UX
        const zoomIn = document.getElementById('zoomInBtn');
        const zoomOut = document.getElementById('zoomOutBtn');
        
        zoomIn.addEventListener('click', () => {
            map.zoomIn();
            updateZoomButtons();
        });
        
        zoomOut.addEventListener('click', () => {
            map.zoomOut();
            updateZoomButtons();
        });
        
        // Update zoom button states based on current zoom level
        function updateZoomButtons() {
            const zoom = map.getZoom();
            zoomIn.disabled = zoom >= map.getMaxZoom();
            zoomOut.disabled = zoom <= map.getMinZoom();
        }
        
        map.on('zoomend', updateZoomButtons);
        updateZoomButtons();
        
        // Locate me button with better feedback
        const locateBtn = document.getElementById('locateMeBtn');
        locateBtn.addEventListener('click', () => {
            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser');
                return;
            }
            
            locateBtn.disabled = true;
            locateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const { latitude, longitude } = position.coords;
                    map.flyTo([latitude, longitude], 15, {
                        duration: 1,
                        easeLinearity: 0.25
                    });
                    
                    // Add a temporary marker at user's location
                    const userMarker = L.marker([latitude, longitude], {
                        icon: L.divIcon({
                            html: '<i class="fas fa-circle-user fa-2x text-primary"></i>',
                            iconSize: [24, 24],
                            className: 'user-location-marker'
                        })
                    }).addTo(map);
                    
                    // Remove the marker after 30 seconds
                    setTimeout(() => {
                        map.removeLayer(userMarker);
                    }, 30000);
                    
                    locateBtn.innerHTML = '<i class="fas fa-location-arrow"></i>';
                    locateBtn.disabled = false;
                },
                (error) => {
                    console.error('Geolocation error:', error);
                    let message = 'Unable to retrieve your location.';
                    if (error.code === error.PERMISSION_DENIED) {
                        message = 'Location permission was denied. Please enable it in your browser settings.';
                    } else if (error.code === error.POSITION_UNAVAILABLE) {
                        message = 'Location information is unavailable.';
                    } else if (error.code === error.TIMEOUT) {
                        message = 'The request to get your location timed out.';
                    }
                    alert(message);
                    locateBtn.innerHTML = '<i class="fas fa-location-arrow"></i>';
                    locateBtn.disabled = false;
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        });
        
        // Fullscreen button
        document.getElementById('fullscreenBtn').addEventListener('click', toggleFullScreen);
        
        // Handle fullscreen change events
        document.addEventListener('fullscreenchange', updateFullscreenButton);
        document.addEventListener('webkitfullscreenchange', updateFullscreenButton);
        document.addEventListener('mozfullscreenchange', updateFullscreenButton);
        document.addEventListener('MSFullscreenChange', updateFullscreenButton);
        
        function updateFullscreenButton() {
            const fullscreenBtn = document.getElementById('fullscreenBtn');
            fullscreenBtn.innerHTML = document.fullscreenElement ? 
                '<i class="fas fa-compress"></i>' : 
                '<i class="fas fa-expand"></i>';
        }
        
        // Filter buttons with better feedback
        document.querySelectorAll('#mapFilters .btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const isActive = this.classList.contains('active');
                if (isActive) return; // Already active
                
                // Update UI
                document.querySelectorAll('#mapFilters .btn').forEach(b => {
                    b.classList.remove('active', 'btn-primary');
                    b.classList.add('btn-outline-secondary');
                });
                
                this.classList.remove('btn-outline-secondary');
                this.classList.add('active', 'btn-primary');
                
                // Show loading state
                const loading = document.getElementById('map-loading');
                loading.style.display = 'flex';
                
                // Add markers with a small delay for better UX
                setTimeout(() => {
                    addMarkers();
                    const activeFilter = this.dataset.filter;
                    // Use last loaded data to update sidebar if available
                    const connections = (markersData || []).filter(m => m.type === 'connection');
                    const teams = (markersData || []).filter(m => m.type === 'team');
                    const issues = (markersData || []).filter(m => m.type === 'issue');
                    updateSidebarList(activeFilter, { connections, teams, issues });
                    loading.style.display = 'none';
                }, 300);
            });
        });
    }
    
    // Handle view details button click
    function setupEventListeners() {
        // Event listeners removed since View Full Details button was removed
        // Users can still close popups with the X button or by clicking outside
    }
    
    // Map-dependent initialization is handled within initMap() after map creation
    
    // Window resize handler is already defined in the main DOMContentLoaded event
</script>
@endpush
