<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="ShrinQ" />
    <meta name="description" content="OTP Verification" />
    <link rel="icon" href="{{ asset('images/logo 1.png') }}" />
    <link rel="stylesheet" href="{{ asset('style/auth-2.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/mediaQuery.css') }}" />
    <link href="https://fonts.cdnfonts.com/css/poppins" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons (optional for icons like bi-save, bi-x-circle) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Stak | Contract</title>

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
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="logo text-center mb-4">
                            <img class="brand img-fluid" src="{{ asset('images/STACK LOGO-01.png') }}" alt="STAK Logo"
                                style="max-width: 150px; height: auto;" />
                        </div>
                        <hr>

                        <form id="addContractForm" action="{{ route('client.form.submit') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="name" id="contract_name"
                                            placeholder=" " required>
                                        <label for="contract_name" class="required-field">Contract Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="customer_name"
                                            id="customer_name" placeholder=" " required>
                                        <label for="customer_name" class="required-field">Customer Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" name="email" id="email"
                                            placeholder=" " required>
                                        <label for="email" class="required-field">Email</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" name="start_date" id="start_date"
                                            placeholder=" " required>
                                        <label for="start_date" class="required-field">Start Date</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" name="end_date" id="end_date"
                                            placeholder=" " required>
                                        <label for="end_date" class="required-field">End Date</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="file" class="form-control" name="signature_file"
                                            accept="image/*" id="signature_file" placeholder=" " required>
                                        <label for="signature_file">Upload Signature</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" name="notes" id="notes" placeholder=" " style="height: 100px"></textarea>
                                        <label for="notes">Notes</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="auto_renewal"
                                            id="auto_renewal">
                                        <label class="form-check-label" for="auto_renewal">
                                            Auto Renewal
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <div class="w-100">
                                    <button type="submit" id='submit' class="btn btn-primary w-100">
                                        Submit Contract
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Check for success, error messages, and validation errors -->
    <script>
        document.getElementById('addContractForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const submitButton = document.getElementById('submit');
    submitButton.disabled = true;
    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Submitting...';

    const form = event.target;
    const formData = new FormData(form);

    // Debug: Log form data
    console.log([...formData.entries()]);

    // Validate dates
    const startDate = new Date(document.getElementById('start_date').value);
    const endDate = new Date(document.getElementById('end_date').value);

    if (endDate <= startDate) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Date',
            text: 'End date must be after start date',
        });
        submitButton.disabled = false;
        submitButton.innerHTML = 'Submit Contract';
        return;
    }

    fetch(form.action, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                "Accept": "application/json",
            },
            body: formData,
        })
        .then(async response => {
            const data = await response.json();
            
            if (!response.ok) {
                // Handle validation errors
                if (response.status === 422 && data.errors) {
                    const errorMessages = Object.values(data.errors).join('\n');
                    throw new Error(errorMessages);
                }
                throw new Error(data.message || 'Network response was not ok');
            }
            
            return data;
        })
        .then(data => {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message || 'Contract submitted successfully',
            });
            form.reset();
            submitButton.innerHTML = 'Submitted';
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Submission Failed',
                text: error.message || 'An error occurred while submitting the form',
            });
            submitButton.disabled = false;
            submitButton.innerHTML = 'Submit Contract';
        });
});
    </script>






</html>
