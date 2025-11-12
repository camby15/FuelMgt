<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="author" content="ShrinQ" />
        <meta name="description" content="OTP Verification" />
        <link rel="icon" href="{{ asset('images/logoMgt-1.PNG') }}" />
        <link rel="stylesheet" href="{{ asset('style/auth-2.css') }}" />
        <link rel="stylesheet" href="{{ asset('style/mediaQuery.css') }}" />
        <link href="https://fonts.cdnfonts.com/css/poppins" rel="stylesheet" />
        <!-- SweetAlert2 CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <title>StationMgt | Verify OTP</title>
    </head>

    <body id="company-body">
        <div class="picture-roll">
            <div class="slider"></div>
        </div>

        <div class="form-box">
            <div class="logo-container">
                <img src="{{ asset('images/logoMgt.PNG') }}" alt="Company Logo" class="login-logo">
            </div>
            <form id="VerifyOTP" class="input-group" method="POST" action="{{ route('auth.verify.token') }}">
                @csrf
                <h2 id="intro">Enter OTP</h2>
                <p id="instructions">Please enter the OTP sent to your email or phone.</p>

                <div class="company-email">
                    <input type="text" name="token" value="{{ old('token') }}" required />
                    <label>Enter OTP</label>
                    @if ($errors->has('token'))
                        <span class="error">{{ $errors->first('token') }}</span>
                    @endif
                </div>

                <button type="submit" class="submit">Verify OTP</button>

                <div class="login-link">
                    <p>
                        Didn't receive the OTP?
                        <a href="{{ route('auth.otp.resend') }}">Resend OTP</a>
                    </p>
                </div>
            </form>
        </div>

        <!-- Check for success and error messages, and trigger SweetAlert -->
        <script>
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
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
        </script>
    </body>
</html>
