@extends('layouts.vertical', ['page_title' => 'Edit Individual Customer'])

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
        transition: all 0.6s;
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
        transform: translateY(-60%) translateX(0.5rem) scale(0.85);
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

    /* Reset and simplify select styles */
    .form-floating select.form-select {
        display: block;
        width: 100%;
        height: 50px;
        padding: 1rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #2f2f2f;
        background-color: transparent;
        border: 1px solid #2f2f2f;
        border-radius: 10px;
        transition: all 0.8s;
        appearance: none;
        background: url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'><path fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/></svg>") no-repeat right 0.75rem center/16px 12px;
    }

    [data-bs-theme="dark"] .form-floating select.form-select {
        background: url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'><path fill='none' stroke='%23adb5bd' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/></svg>") no-repeat right 0.75rem center/16px 12px;
        background-color: transparent;
    }

    .form-floating select.form-select:focus {
        border-color: #033c42;
        outline: 0;
        box-shadow: none;
    }

    .form-floating select.form-select ~ label {
        padding: 1rem 0.75rem;
    }

    /* Modal body styles */
    .modal-body {
        background: none;
        padding: 1.5rem;
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

    /* Section Header Styles */
    .section-header {
        padding: 1rem;
        margin: 1.5rem 0 1rem;
        border-left: 4px solid #033c42;
        background-color: rgba(3, 60, 66, 0.05);
        border-radius: 0 0.375rem 0.375rem 0;
    }

    .section-header h5 {
        margin: 0;
        color: #033c42;
        font-weight: 600;
    }

    [data-bs-theme="dark"] .section-header {
        background-color: rgba(255, 255, 255, 0.05);
        border-left-color: #0dcaf0;
    }

    [data-bs-theme="dark"] .section-header h5 {
        color: #0dcaf0;
    }
</style>

@section('content')
<div class="modal fade show" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" style="display: block;" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCustomerModalLabel">Edit Individual Customer</h5>
                <a href="{{ route('company.customers.index') }}" class="btn-close" aria-label="Close"></a>
            </div>
            <form action="{{ route('company.customers.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Personal Information Section -->
                    <div class="section-header">
                        <h5>Personal Information</h5>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $customer->name) }}" required placeholder=" ">
                                <label for="name">Name</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="title" id="title">
                                    <option value="">Select Title</option>
                                    <option value="Mr." {{ old('title', $customer->title) == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                                    <option value="Mrs." {{ old('title', $customer->title) == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                                    <option value="Ms." {{ old('title', $customer->title) == 'Ms.' ? 'selected' : '' }}>Ms.</option>
                                    <option value="Dr." {{ old('title', $customer->title) == 'Dr.' ? 'selected' : '' }}>Dr.</option>
                                    <option value="Prof." {{ old('title', $customer->title) == 'Prof.' ? 'selected' : '' }}>Prof.</option>
                                </select>
                                <label for="title">Title</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" name="email" id="email" value="{{ old('email', $customer->email) }}" required placeholder=" ">
                                <label for="email">Email</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="tel" class="form-control" name="phone" id="phone" value="{{ old('phone', $customer->phone) }}" required placeholder=" ">
                                <label for="phone">Phone</label>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-floating mb-3">
                                <textarea class="form-control" name="address" id="address" placeholder=" " required>{{ old('address', $customer->address) }}</textarea>
                                <label for="address">Address</label>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Details Section -->
                    <div class="section-header">
                        <h5>Personal Details</h5>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $customer->date_of_birth) }}" placeholder=" ">
                                <label for="date_of_birth">Date of Birth</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="gender" id="gender">
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender', $customer->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $customer->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender', $customer->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                <label for="gender">Gender</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="marital_status" id="marital_status">
                                    <option value="">Select Marital Status</option>
                                    <option value="Single" {{ old('marital_status', $customer->marital_status) == 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ old('marital_status', $customer->marital_status) == 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Divorced" {{ old('marital_status', $customer->marital_status) == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="Widowed" {{ old('marital_status', $customer->marital_status) == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                                <label for="marital_status">Marital Status</label>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Management Section -->
                    <div class="section-header">
                        <h5>Customer Management</h5>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="customer_category" id="customer_category" required>
                                    <option value="">Select Category</option>
                                    <option value="VIP" {{ old('customer_category', $customer->customer_category) == 'VIP' ? 'selected' : '' }}>VIP</option>
                                    <option value="Regular" {{ old('customer_category', $customer->customer_category) == 'Regular' ? 'selected' : '' }}>Regular</option>
                                    <option value="New" {{ old('customer_category', $customer->customer_category) == 'New' ? 'selected' : '' }}>New</option>
                                </select>
                                <label for="customer_category">Customer Category</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" name="value" id="value" value="{{ old('value', $customer->value) }}" step="0.01" min="0" placeholder=" ">
                                <label for="value">Value</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="status" id="status3" required>
                                    <option value="">Select Status</option>
                                    <option value="Active" {{ old('status', $customer->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ old('status', $customer->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="Pending" {{ old('status', $customer->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Suspended" {{ old('status', $customer->status) == 'Suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                                <label for="status">Status</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="source_of_acquisition" id="source_of_acquisition" value="{{ old('source_of_acquisition', $customer->source_of_acquisition) }}" placeholder=" ">
                                <label for="source_of_acquisition">Source of Acquisition</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="change_type" id="change_type">
                                    <option value="">Select Change Type</option>
                                    <option value="New" {{ old('change_type', $customer->change_type) == 'New' ? 'selected' : '' }}>New</option>
                                    <option value="Existing" {{ old('change_type', $customer->change_type) == 'Existing' ? 'selected' : '' }}>Existing</option>
                                    <option value="Upgrade" {{ old('change_type', $customer->change_type) == 'Upgrade' ? 'selected' : '' }}>Upgrade</option>
                                    <option value="Downgrade" {{ old('change_type', $customer->change_type) == 'Downgrade' ? 'selected' : '' }}>Downgrade</option>
                                </select>
                                <label for="change_type">Change Type</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="assigned_branch" id="assigned_branch" value="{{ old('assigned_branch', $customer->assigned_branch) }}" placeholder=" ">
                                <label for="assigned_branch">Assigned Branch</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="channel" id="channel" value="{{ old('channel', $customer->channel) }}" placeholder=" ">
                                <label for="channel">Channel</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="mode_of_communication" id="mode_of_communication" value="{{ old('mode_of_communication', $customer->mode_of_communication) }}" placeholder=" ">
                                <label for="mode_of_communication">Mode of Communication</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-between w-100">
                        <div>
                            <a href="{{ route('company.customers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back to List
                            </a>
                        </div>
                        <div>
                           
                            <button type="submit" class="btn btn-primary" id="updateCustomerBtn">
                                <span class="normal-state">
                                    <i class="fas fa-save me-1"></i> Update Customer
                                </span>
                                <span class="loading-state d-none">
                                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                    Updating...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add a semi-transparent backdrop -->
<div class="modal-backdrop fade show"></div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Clear existing error messages when input changes
        $('input, select, textarea').on('input change', function() {
            $(this).removeClass('is-invalid');
            $(this).closest('.form-floating').find('.invalid-feedback').remove();
        });

        // Reset button handler
        $('button[type="reset"]').on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Reset Form?',
                text: 'This will reset all fields to their original values. Are you sure?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reset it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('form')[0].reset();
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();
                }
            });
        });

        // Form submission handler
        $('form').on('submit', function(e) {
            e.preventDefault();
            
            // Clear all previous error messages
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            
            // Prevent multiple submissions
            var form = $(this);
            if (form.data('submitted') === true) {
                return false;
            }
            
            // Mark form as submitted and show loading state
            form.data('submitted', true);
            var submitBtn = $('#updateCustomerBtn');
            submitBtn.prop('disabled', true)
                    .find('.normal-state').addClass('d-none')
                    .end()
                    .find('.loading-state').removeClass('d-none');
            
            var url = form.attr('action');
            var formData = form.serialize();
            
            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message || 'Customer updated successfully',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = '{{ route('company.customers.index') }}';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'An unexpected error occurred'
                        });
                    }
                },
                error: function(xhr) {
                    console.log('Error Response:', xhr.responseText);
                    
                    try {
                        var response = xhr.responseJSON;
                        
                        // Handle validation errors
                        if (xhr.status === 422 && response.errors) {
                            // Display validation errors under each field
                            Object.keys(response.errors).forEach(function(field) {
                                var input = $('[name="' + field + '"]');
                                var message = response.errors[field][0];
                                
                                input.addClass('is-invalid');
                                input.closest('.form-floating').append(
                                    '<div class="invalid-feedback">' + message + '</div>'
                                );
                            });

                            // Scroll to the first error
                            var firstError = $('.is-invalid').first();
                            if (firstError.length) {
                                $('html, body').animate({
                                    scrollTop: firstError.offset().top - 100
                                }, 500);
                            }

                            // Show a summary alert
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: 'Please check the form for errors and try again.'
                            });
                        } else {
                            // Handle other types of errors
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message || 'An unexpected error occurred while processing your request'
                            });
                        }
                    } catch (e) {
                        console.error('Error parsing error response', e);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'An unexpected error occurred while processing your request'
                        });
                    }
                },
                complete: function() {
                    // Re-enable submit button and restore normal state
                    form.data('submitted', false);
                    submitBtn.prop('disabled', false)
                            .find('.loading-state').addClass('d-none')
                            .end()
                            .find('.normal-state').removeClass('d-none');
                }
            });
            
            return false;
        });
    });
</script>
@endsection