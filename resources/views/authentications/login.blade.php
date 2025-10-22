<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="author" content="ShrinQ" />
        <meta name="description" content="Login Form" />
        <link rel="icon" href="{{ asset('images/logo 1.png') }}" />
        <link rel="stylesheet" href="{{ asset('style/auth-2.css') }}" />
        <link rel="stylesheet" href="{{ asset('style/mediaQuery.css') }}" />

        <link href="https://fonts.cdnfonts.com/css/poppins" rel="stylesheet" />

        <!-- SweetAlert2 CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <title>Stak | Login</title>
    </head>
    <body id="company-body">
        <div class="picture-roll">
            <div class="slider"></div>
        </div>
        <div class="form-box">
            <div class="logo-container">
                <img src="{{ asset('images/gesl_logo.png') }}" alt="Company Logo" class="login-logo">
            </div>
            <form id="Signin" class="input-group" method="POST" action="{{ route('auth.otp.request') }}">
                @csrf
                <h2 id="intro">Account Login</h2>
                <div class="company-email">
                    <input type="text" name="contact" value="{{ old('contact') }}" required />
                    <label>Email or Telephone</label>
                    @if ($errors->has('contact'))
                        <span class="error">{{ $errors->first('contact') }}</span>
                    @endif
                </div>
                <button type="submit" class="submit">Login</button>
                <div class="login-link">
                    <p>
                        Don't have an account?
                        <a href="{{ route('auth.auth') }}">Sign up</a>
                    </p>
                </div>
            </form>
        </div>
        <!--
    <!-- SweetAlert for notifications -->
        <script>
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'OK'
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
