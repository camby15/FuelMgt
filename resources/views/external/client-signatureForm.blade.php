<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="ShrinQ" />
    <meta name="description" content="OTP Verification" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="icon" href="{{ asset('images/logo 1.png') }}" />
    <link rel="stylesheet" href="{{ asset('style/auth-2.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/mediaQuery.css') }}" />
    <link href="https://fonts.cdnfonts.com/css/poppins" rel="stylesheet" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Stak | Signature</title>

    <style>
        body {
            padding: 20px;
            background-color: #f8f9fa;
        }

        .form-container {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .required-field::after {
            content: " *";
            color: red;
        }
    </style>
</head>

<body id="company-body">
    <div class="picture-roll">
        <div class="slider"></div>
    </div>

    <div class="form-box">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg">
                    <div class="form-container">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="logo text-center mb-4">
                            <img class="brand img-fluid" src="{{ asset('images/STACK LOGO-01.png') }}" alt="STAK Logo"
                                style="max-width: 100px; height: auto;" />
                        </div>
                        <hr>

                        <!-- Signature Form -->
<div style="max-height: 70vh;  auto;">
<form id="addContractForm" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-3">
        <!-- Email Input -->
        <div class="col-md-11">
            <div class="form-floating">
                <input type="text" class="form-control" name="name" id="name"
                    placeholder=" " required>
                <label for="email" class="required-field">Name</label>
            </div>
            <input type="hidden" name="contract_id" id="contract_id" value="{{ $id }}">
            <input type="hidden" name="email" id="email" value="{{ $email }}">
        </div>

        <!-- Signature Option Selector -->
        <div class="col-md-12 mt-3">
            <label class="form-label fw-semibold">Choose Signature Type:</label>
            <div class="d-flex gap-3 align-items-center">
                <div class="form-check form-check-inline p-0 m-0">
                    <input class="btn-check" type="radio" name="signature_option" id="drawSignature" value="draw" autocomplete="off" checked>
                    <label class="btn btn-outline-primary px-4 py-2" for="drawSignature">
                        <i class="bi bi-pencil me-2"></i>Draw Signature
                    </label>
                </div>
                <div class="form-check form-check-inline p-0 m-0">
                    <input class="btn-check" type="radio" name="signature_option" id="uploadSignature" value="upload" autocomplete="off">
                    <label class="btn btn-outline-secondary px-4 py-2" for="uploadSignature">
                        <i class="bi bi-upload me-2"></i>Upload Signature
                    </label>
                </div>
            </div>
            </div>
        </div>

        <!-- Signature Pad -->
        <div class="col-md-12 mt-3" id="signaturePadContainer">
            <div class="mb-2">
                <label class="form-label required-field fw-semibold">Draw Signature</label>
            </div>
            <canvas id="signature-pad" width="600" height="200"
                style="border:1px solid #000; border-radius: 5px; background-color: #f8f9fa;"></canvas>
            <input type="hidden" name="signature" id="signature">
            <div class="mt-2 d-flex justify-content-between">
                <button type="button" id="clear" class="btn btn-outline-danger btn-sm d-flex align-items-center">
                    <i class="bi bi-x-circle me-1"></i> Clear Signature
                </button>
            </div>
        </div>

        <!-- Upload Signature File -->
        <div class="col-md-12 mt-3 d-none" id="uploadContainer">
            <label class="form-label required-field fw-semibold mb-2">Upload Signature File</label>
            <input type="file" name="signature_file" id="signature_file" class="form-control" accept="image/*,application/pdf">
        </div>
    </div>

    <div class="d-flex justify-content-between mt-4">
        <div class="w-100">
            <button type="submit" id="submit" class="btn btn-primary w-100">
                <i class="bi bi-check2-circle"></i> Submit Contract
            </button>
        </div>
    </div>
</form>
</div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    <script>
    const canvas = document.getElementById('signature-pad');
    const signaturePad = new SignaturePad(canvas);

    // Clear canvas
    $('#clear').click(() => signaturePad.clear());

    // Toggle visibility based on signature option
    $('input[name="signature_option"]').on('change', function () {
        const option = $(this).val();
        if (option === 'draw') {
            $('#signaturePadContainer').removeClass('d-none');
            $('#uploadContainer').addClass('d-none');
        } else {
            $('#signaturePadContainer').addClass('d-none');
            $('#uploadContainer').removeClass('d-none');
        }
    });

    // Set up CSRF token for AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Form submission
    $('#addContractForm').on('submit', function (e) {
        e.preventDefault();

        const name = $('#name').val();
        const contractId = $('#contract_id').val();
        const email = $('#email').val();
        const signatureOption = $('input[name="signature_option"]:checked').val();
        const submitButton = $('#submit');

        const formData = new FormData();
        formData.append('name', name);
        formData.append('contract_id', contractId);
        formData.append('email', email);
        formData.append('signature_option', signatureOption);

        // Validate based on selected option
        if (signatureOption === 'draw') {
            if (signaturePad.isEmpty()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Signature',
                    text: 'Please draw your signature before submitting.'
                });
                return;
            }
            formData.append('signature', signaturePad.toDataURL());
        } else {
            const file = $('#signature_file')[0].files[0];
            if (!file) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing File',
                    text: 'Please upload a signature file before submitting.'
                });
                return;
            }
            formData.append('signature_file', file);
        }

        // Show loading state
        submitButton.prop('disabled', true);
        submitButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...');

        $.ajax({
            url: "{{ route('client.signature.Form.submit') }}",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Contract submitted successfully.'
                }).then(() => {
                    $('#addContractForm')[0].reset();
                    signaturePad.clear();
                    $('#signaturePadContainer').removeClass('d-none');
                    $('#uploadContainer').addClass('d-none');
                    $('#drawSignature').prop('checked', true);
                    submitButton.prop('disabled', true);
                    submitButton.html('<i class="bi bi-check2-circle"></i> Submitted');
                });
            },
            error: function (xhr) {
                console.error(xhr);
                let message = 'Something went wrong.';
                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message
                });
            },
            complete: function () {
                if (!submitButton.prop('disabled')) {
                    submitButton.prop('disabled', false);
                    submitButton.html('<i class="bi bi-check2-circle"></i> Submit Contract');
                }
            }
        });
    });
</script>


</body>

</html>
