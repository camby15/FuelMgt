@extends('layouts.vertical', ['page_title' => 'Partner Management'])

@section('css')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Floating Label Styles */
        .form-floating {
            position: relative;
            margin-bottom: 1rem;
        }
        .form-floating input.form-control,
        .form-floating select.form-select,
        .form-floating textarea.form-control {
            height: 50px;
            border: 1px solid #2f2f2f;
            border-radius: 10px;
            background-color: transparent;
            font-size: 1rem;
            padding: 1rem 0.75rem;
            transition: all 0.8s;
        }
        .form-floating textarea.form-control {
            min-height: 100px;
            height: auto;
            padding-top: 1.625rem;
        }
        .form-floating label {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            padding: 1rem 0.75rem;
            color: #2f2f2f;
            transition: all 0.8s;
            pointer-events: none;
            z-index: 1;
        }
        .form-floating input.form-control:focus,
        .form-floating input.form-control:not(:placeholder-shown),
        .form-floating select.form-select:focus,
        .form-floating select.form-select:not([value=""]),
        .form-floating textarea.form-control:focus,
        .form-floating textarea.form-control:not(:placeholder-shown) {
            border-color: #033c42;
            box-shadow: none;
        }
        .form-floating input.form-control:focus ~ label,
        .form-floating input.form-control:not(:placeholder-shown) ~ label,
        .form-floating select.form-select:focus ~ label,
        .form-floating select.form-select:not([value=""]) ~ label,
        .form-floating textarea.form-control:focus ~ label,
        .form-floating textarea.form-control:not(:placeholder-shown) ~ label {
            height: auto;
            padding: 0 0.5rem;
            transform: translateY(-50%) translateX(0.5rem) scale(0.85);
            color: white;
            border-radius: 5px;
            z-index: 5;
        }
        .form-floating input.form-control:focus ~ label::before,
        .form-floating input.form-control:not(:placeholder-shown) ~ label::before,
        .form-floating select.form-select:focus ~ label::before,
        .form-floating select.form-select:not([value=""]) ~ label::before,
        .form-floating textarea.form-control:focus ~ label::before,
        .form-floating textarea.form-control:not(:placeholder-shown) ~ label::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: #033c42;
            border-radius: 5px;
            z-index: -1;
        }
        .form-floating input.form-control:focus::placeholder {
            color: transparent;
        }

        /* Dark mode styles */
        [data-bs-theme="dark"] .form-floating input.form-control,
        [data-bs-theme="dark"] .form-floating select.form-select,
        [data-bs-theme="dark"] .form-floating textarea.form-control {
            border-color: #6c757d;
            color: #e9ecef;
        }

        [data-bs-theme="dark"] .form-floating label {
            color: #adb5bd;
        }

        [data-bs-theme="dark"] .form-floating input.form-control:focus,
        [data-bs-theme="dark"] .form-floating input.form-control:not(:placeholder-shown),
        [data-bs-theme="dark"] .form-floating select.form-select:focus,
        [data-bs-theme="dark"] .form-floating select.form-select:not([value=""]),
        [data-bs-theme="dark"] .form-floating textarea.form-control:focus,
        [data-bs-theme="dark"] .form-floating textarea.form-control:not(:placeholder-shown) {
            border-color: #0dcaf0;
        }

        [data-bs-theme="dark"] .form-floating input.form-control:focus ~ label::before,
        [data-bs-theme="dark"] .form-floating input.form-control:not(:placeholder-shown) ~ label::before,
        [data-bs-theme="dark"] .form-floating select.form-select:focus ~ label::before,
        [data-bs-theme="dark"] .form-floating select.form-select:not([value=""]) ~ label::before,
        [data-bs-theme="dark"] .form-floating textarea.form-control:focus ~ label::before,
        [data-bs-theme="dark"] .form-floating textarea.form-control:not(:placeholder-shown) ~ label::before {
            background: #0dcaf0;
        }

        [data-bs-theme="dark"] select.form-select option {
            background-color: #212529;
            color: #e9ecef;
        }

        /* Action Button Styles */
        .action-btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }

        .action-btn i {
            font-size: 0.875rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                @foreach($errors->all() as $error)
                    <p class="mb-0">{{ $error }}</p>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addPartnerModal">
                                <i class="fa-solid fa-plus-circle me-1"></i> Add Partner
                            </button>
                            <button type="button" class="btn btn-secondary me-2" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                                <i class="fa-solid fa-upload me-1"></i> Bulk Upload
                            </button>
                            <a href="{{ route('company.partners.partner-download-template') }}" class="btn btn-outline-primary me-2">
                                <i class="fa-solid fa-download me-1"></i> Download Template
                            </a>
                        </div>
                    </div>
                    <h4 class="page-title">Partner's Management</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered table-striped">
                                <thead>
                                    <tr>
                                        <th>Company Name</th>
                                        <th>Contact Person</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($partners) && count($partners) > 0)
                                        @foreach($partners as $partner)
                                            <tr>
                                                <td>{{ $partner->company_name }}</td>
                                                <td>{{ $partner->contact_person }}</td>
                                                <td>{{ $partner->email }}</td>
                                                <td>{{ $partner->phone }}</td>
                                                <td>
                                                    <span class="badge {{ $partner->status ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $partner->status ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button"
                                                            class="btn btn-sm btn-info action-btn me-1"
                                                            onclick="editPartner('{{ $partner->id }}', '{{ $partner->company_name }}', '{{ $partner->contact_person }}', '{{ $partner->email }}', '{{ $partner->phone ?? '' }}')"
                                                            title="Edit">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>

                                                    <form action="{{ route('company.partners.update-status', $partner->id) }}" 
                                                          method="POST" 
                                                          class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="{{ $partner->status ? 0 : 1 }}">
                                                        <button type="submit" 
                                                                class="btn btn-sm {{ $partner->status ? 'btn-warning' : 'btn-success' }} action-btn me-1"
                                                                title="{{ $partner->status ? 'Deactivate' : 'Activate' }}">
                                                            <i class="fa-solid {{ $partner->status ? 'fa-ban' : 'fa-check' }}"></i>
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('company.partners.destroy', $partner->id) }}" 
                                                          method="POST" 
                                                          class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-danger action-btn"
                                                                title="Delete"
                                                                onclick="return confirmDelete(event, '{{ $partner->company_name }}')">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center">No partners found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Partner Modal -->
    <div class="modal fade" id="addPartnerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Partner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addPartnerForm" method="POST" action="{{ route('company.partners.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="company_name" name="company_name" placeholder=" " required>
                            <label for="company_name">Company Name</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="contact_person" name="contact_person" placeholder=" " required>
                            <label for="contact_person">Contact Person</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="addPartnerEmail" name="email" placeholder=" " value="{{ old('email') }}" required>
                            <label for="addPartnerEmail">Email Address</label>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="addPartnerPhone" name="phone" placeholder=" " value="{{ old('phone') }}">
                            <label for="addPartnerPhone">Phone Number (Optional)</label>
                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Partner</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bulk Upload Partners Modal -->
    <div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Upload Partners</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('company.partners.partner-bulk-upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fa-solid fa-info-circle me-1"></i>
                            Download the template first and fill it with your partner data. Make sure to follow the format exactly.
                        </div>

                        <div class="mb-3">
                            <label for="bulk_upload_file" class="form-label">Choose CSV File</label>
                            <input type="file" class="form-control" id="bulk_upload_file" name="bulk_upload_file" accept=".csv,.txt" required>
                            <div class="form-text">
                                Accepted formats: .csv, .txt (max 2MB)
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Partner Modal -->
    <div class="modal fade" id="editPartnerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Partner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editPartnerForm" method="POST" action="{{ route('company.partners.update', ':id') }}">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" id="editPartnerId" name="partner_id">
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="editCompanyName" name="company_name" placeholder=" " required>
                            <label for="editCompanyName">Company Name</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="editContactPerson" name="contact_person" placeholder=" " required>
                            <label for="editContactPerson">Contact Person</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="editPartnerEmail" name="email" placeholder=" " required>
                            <label for="editPartnerEmail">Email Address</label>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="editPartnerPhone" name="phone" placeholder=" ">
                            <label for="editPartnerPhone">Phone Number (Optional)</label>
                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Partner</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Function to edit partner
        function editPartner(id, companyName, contactPerson, email, phone) {
            // Update form action URL
            const form = document.getElementById('editPartnerForm');
            form.action = form.action.replace(':id', id);

            // Set partner ID
            document.getElementById('editPartnerId').value = id;

            // Populate form fields
            document.getElementById('editCompanyName').value = companyName;
            document.getElementById('editContactPerson').value = contactPerson;
            document.getElementById('editPartnerEmail').value = email;
            document.getElementById('editPartnerPhone').value = phone || '';

            // Show modal
            const editModal = new bootstrap.Modal(document.getElementById('editPartnerModal'));
            editModal.show();
        }

        // Function to confirm delete
        function confirmDelete(event, companyName) {
            event.preventDefault();
            const form = event.target.closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to delete ${companyName}?`,
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
        }

        // Show success/error messages using SweetAlert2
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonText: 'Ok'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonText: 'Ok'
            });
        @endif

        // Handle form submissions
        document.getElementById('addPartnerForm').addEventListener('submit', function(event) {
            // Add loading state to submit button
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';
        });

        document.getElementById('editPartnerForm').addEventListener('submit', function(event) {
            // Add loading state to submit button
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
        });

        // Reset form and button state when modal is hidden
        document.getElementById('addPartnerModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('addPartnerForm').reset();
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Add Partner';
        });

        document.getElementById('editPartnerModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('editPartnerForm').reset();
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Save Changes';
        });
    </script>
@endsection
