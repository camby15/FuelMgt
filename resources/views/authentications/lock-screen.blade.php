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
        <!-- SweetAlert2 CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <title>Stak | Lock Screen</title>
    </head>

    <body id="company-body">
        <div class="picture-roll">
            <div class="slider"></div>
        </div>

        <div class="form-box">
            <form id="Signin" class="input-group" method="POST" action="{{ route('verify-pin') }}">
                @csrf
                <h2 id="intro">Lock Screen</h2>
                <p id="instructions">Please enter your pin to Exit Lock Screen</p>

                <div class="company-email">
                    <input type="password" name="pin" required />
                    <label>Enter Pin</label>
                </div>
                
                <button type="submit" class="submit">Unlock</button>
                
                <div class="login-link">
                    <p>
                        Forgotten Pin?
                        <a href="{{ route('lock.logout') }}">Login here</a>
                    </p>
                </div>
                
                <!-- Check for success, error messages, and validation errors -->
                <script>
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
                            title: 'Oops...',
                            text: '{{ session('error') }}',
                            confirmButtonText: 'Try Again'
                        });
                    @endif
                
                    @if ($errors->any())
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html: '{!! implode("<br>", $errors->all()) !!}',
                            confirmButtonText: 'Fix Errors'
                        });
                    @endif
</script>