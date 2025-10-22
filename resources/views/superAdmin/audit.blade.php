@extends('layouts.vertical-admin', ['page_title' => 'Audit Logs'])
@section('head')
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f4f6fa; }
        .audit-table th, .audit-table td { vertical-align: middle; }
        .audit-table tbody tr { transition: box-shadow 0.2s, background 0.2s; }
        .audit-table tbody tr:hover {
            background: #f1f8fe;
            box-shadow: 0 2px 16px 0 rgba(33,150,243,0.07);
            cursor: pointer;
        }
        .audit-avatar {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e3e8ee;
            margin-right: 0.75rem;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .audit-search-bar {
            max-width: 320px;
            border-radius: 20px;
            background: #f8fafc;
            border: 1px solid #e3e8ee;
        }
        .audit-filters .form-select, .audit-filters .form-control {
            min-width: 120px;
            border-radius: 16px;
            background: #f8fafc;
            border: 1px solid #e3e8ee;
        }
        .badge-status {
            font-size: 0.96em;
            padding: 0.5em 1em;
            border-radius: 12px;
            letter-spacing: 0.01em;
        }
        .badge.bg-success.badge-status {
            background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%) !important;
            color: #fff !important;
            box-shadow: 0 1px 6px rgba(67,233,123,0.10);
        }
        .badge.bg-danger.badge-status {
            background: linear-gradient(90deg, #fa709a 0%, #fee140 100%) !important;
            color: #fff !important;
            box-shadow: 0 1px 6px rgba(250,112,154,0.12);
        }
        .badge.bg-light.badge-status, .badge.bg-light.text-dark {
            background: #e3e8ee !important;
            color: #495057 !important;
        }
        .audit-card {
            border-radius: 18px;
            box-shadow: 0 2px 18px 0 rgba(33,150,243,0.06);
            border: none;
        }
        .audit-header-title {
            font-weight: 700;
            letter-spacing: 0.01em;
            color: #1976d2;
        }
        .audit-pagination .page-link {
            border-radius: 50%;
            margin: 0 2px;
            color: #1976d2;
            border: 1px solid #e3e8ee;
            background: #f8fafc;
        }
        .audit-pagination .page-item.active .page-link {
            background: #1976d2;
            color: #fff;
            border: none;
        }
        .audit-bulk-btn {
            border-radius: 16px;
            font-weight: 500;
        }
        .audit-modal-content {
            border-radius: 18px;
            box-shadow: 0 4px 32px 0 rgba(33,150,243,0.10);
        }
        .audit-modal-header { border-bottom: 0; }
        .audit-modal-title { color: #1976d2; font-weight: 600; }
        .audit-detail-label { color: #6c757d; font-size: 0.97em; }
        .audit-detail-value { font-size: 1.06em; font-weight: 500; }
    </style>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card audit-card">
                <div class="card-body">
                    <!-- Audit Log Detail Modal -->
                    <div class="modal fade" id="auditDetailModal" tabindex="-1" aria-labelledby="auditDetailModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content audit-modal-content">
                                <div class="modal-header audit-modal-header">
                                    <h5 class="modal-title audit-modal-title" id="auditDetailModalLabel">Audit Log Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" id="auditDetailContent">
                                    <!-- Details will be injected via JS -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
                        <h4 class="audit-header-title mb-0"><i class="bi bi-shield-check text-primary me-2"></i>Audit Logs</h4>
                        <div class="d-flex gap-2 audit-filters">
                            <input type="text" class="form-control audit-search-bar" placeholder="Search user or action...">
                            <select class="form-select" aria-label="Status filter">
                                <option selected>Status</option>
                                <option value="success">Success</option>
                                <option value="failed">Failed</option>
                            </select>
                            <input type="date" class="form-control" aria-label="Date filter">
                            <input type="date" class="form-control" aria-label="End date filter">
                            <button class="btn btn-outline-secondary btn-sm audit-bulk-btn" id="exportAuditBtn"><i class="bi bi-download"></i> Export</button>
                        </div>
                    </div>
                    <div class="table-responsive rounded shadow-sm">
                        <form id="auditBulkForm">
                            <table class="table table-centered table-hover audit-table mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th><input type="checkbox" id="selectAllAudit"></th>
                                        <th>Date</th>
                                        <th>User</th>
                                        <th>Action</th>
                                        <th>Status</th>
                                        <th>IP</th>
                                        <th>Reviewed</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Example row -->
                                    <tr class="audit-row" data-details='{"date":"2025-04-23 03:24","user":"Jane Admin","email":"jane@company.com","action":"Updated Settings","desc":"Changed password policy","status":"Success","ip":"192.168.1.10","user_agent":"Chrome on macOS"}'>
                                        <td><input type="checkbox" class="audit-checkbox"></td>
                                        <td>2025-04-23 <span class="text-muted small ms-1">03:24</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="/images/avatar2.jpg" class="audit-avatar" alt="Jane Admin">
                                                <div>
                                                    <span class="fw-semibold">Jane Admin</span><br>
                                                    <span class="text-muted small">jane@company.com</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-primary"><i class="bi bi-gear me-1"></i>Updated Settings</span>
                                            <br><span class="text-muted small">Changed password policy</span>
                                        </td>
                                        <td><span class="badge bg-success badge-status"><i class="bi bi-check-circle me-1"></i>Success</span></td>
                                        <td><span class="text-muted small">192.168.1.10</span></td>
                                        <td><span class="badge bg-light badge-status text-dark">No</span></td>
                                    </tr>
                                    <tr class="audit-row" data-details='{"date":"2025-04-22 18:10","user":"John User","email":"john@company.com","action":"Deleted User","desc":"Removed user: mike@company.com","status":"Failed","ip":"192.168.1.22","user_agent":"Firefox on Windows"}'>
                                        <td><input type="checkbox" class="audit-checkbox"></td>
                                        <td>2025-04-22 <span class="text-muted small ms-1">18:10</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="/images/avatar3.jpg" class="audit-avatar" alt="John User">
                                                <div>
                                                    <span class="fw-semibold">John User</span><br>
                                                    <span class="text-muted small">john@company.com</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-danger"><i class="bi bi-trash me-1"></i>Deleted User</span>
                                            <br><span class="text-muted small">Removed user: mike@company.com</span>
                                        </td>
                                        <td><span class="badge bg-danger badge-status"><i class="bi bi-x-circle me-1"></i>Failed</span></td>
                                        <td><span class="text-muted small">192.168.1.22</span></td>
                                        <td><span class="badge bg-light badge-status text-dark">No</span></td>
                                    </tr>
                                    <!-- More rows here -->
                                </tbody>
                            </table>
                            <div class="mt-2 d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm audit-bulk-btn" id="markReviewedBtn"><i class="bi bi-flag"></i> Mark as Reviewed</button>
                                <button type="button" class="btn btn-outline-danger btn-sm audit-bulk-btn" id="deleteAuditBtn"><i class="bi bi-trash"></i> Delete</button>
                            </div>
                        </form>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">Showing 1-10 of 120 entries | Logs kept for 90 days</div>
                        <nav>
                            <ul class="pagination pagination-sm mb-0 audit-pagination">
                                <li class="page-item disabled"><a class="page-link" href="#">Prev</a></li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">Next</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // Row click opens details modal
    document.querySelectorAll('.audit-row').forEach(function(row) {
        row.addEventListener('click', function(e) {
            if(e.target.tagName === 'INPUT') return; // Don't trigger on checkbox
            const d = JSON.parse(this.dataset.details);
            document.getElementById('auditDetailContent').innerHTML = `
                <div class='row mb-3'>
                    <div class='col-md-6 mb-2'>
                        <span class='audit-detail-label'>Date</span><br>
                        <span class='audit-detail-value'>${d.date}</span>
                    </div>
                    <div class='col-md-6 mb-2'>
                        <span class='audit-detail-label'>Status</span><br>
                        <span class='audit-detail-value'>${d.status}</span>
                    </div>
                </div>
                <div class='row mb-3'>
                    <div class='col-md-6 mb-2'>
                        <span class='audit-detail-label'>User</span><br>
                        <span class='audit-detail-value'>${d.user} &lt;${d.email}&gt;</span>
                    </div>
                    <div class='col-md-6 mb-2'>
                        <span class='audit-detail-label'>IP Address</span><br>
                        <span class='audit-detail-value'>${d.ip}</span>
                    </div>
                </div>
                <div class='row mb-3'>
                    <div class='col-md-6 mb-2'>
                        <span class='audit-detail-label'>Action</span><br>
                        <span class='audit-detail-value'>${d.action}</span>
                    </div>
                    <div class='col-md-6 mb-2'>
                        <span class='audit-detail-label'>User Agent</span><br>
                        <span class='audit-detail-value'>${d.user_agent}</span>
                    </div>
                </div>
                <div class='mb-2'>
                    <span class='audit-detail-label'>Description</span><br>
                    <span class='audit-detail-value'>${d.desc}</span>
                </div>
            `;
            var modal = new bootstrap.Modal(document.getElementById('auditDetailModal'));
            modal.show();
        });
    });
    // Select/Deselect all
    document.getElementById('selectAllAudit')?.addEventListener('change', function() {
        document.querySelectorAll('.audit-checkbox').forEach(cb => cb.checked = this.checked);
    });
    // Mark as reviewed (demo)
    document.getElementById('markReviewedBtn')?.addEventListener('click', function() {
        document.querySelectorAll('.audit-checkbox:checked').forEach(cb => {
            cb.closest('tr').querySelector('td:last-child .badge').textContent = 'Yes';
            cb.closest('tr').querySelector('td:last-child .badge').classList.remove('bg-light','text-dark');
            cb.closest('tr').querySelector('td:last-child .badge').classList.add('bg-success','text-white');
        });
    });
    // Delete selected (demo)
    document.getElementById('deleteAuditBtn')?.addEventListener('click', function() {
        document.querySelectorAll('.audit-checkbox:checked').forEach(cb => {
            cb.closest('tr').remove();
        });
    });
    // Export logs (demo)
    document.getElementById('exportAuditBtn')?.addEventListener('click', function() {
        alert('Export logs as CSV (to be implemented)');
    });
</script>
@endsection
