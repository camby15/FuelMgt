<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal</title>
    <link rel="shortcut icon" href="/images/logo 1.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', Arial, sans-serif;
            background: #233554;
            min-height: 100vh;
            position: relative;
        }
        .bg-illustration {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(rgba(24,32,54,0.55), rgba(24,32,54,0.70)), url('/images/bg-auth.jpg') center center/cover no-repeat;
            animation: bgFadeIn 2.5s cubic-bezier(0.4,0,0.2,1);
            filter: blur(1px);
            z-index: 1;
        }
        @keyframes bgFadeIn {
            0% {
                opacity: 0;
                transform: scale(1.06);
            }
            80% {
                opacity: 1;
                transform: scale(1.01);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
        .admin-landing-bg {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
        }
        .admin-landing-hero {
            color: #ffff;
            backdrop-filter: blur(10px);
            border-radius: 1.25rem;
            border: 1.5px solid #e1dbdb;
            padding: 2.5rem 2.2rem 2rem 2.2rem;
            background: linear-gradient(135deg, rgba(68, 9, 230, 0.924) 60%, rgba(99, 51, 212, 0.798) 70%);
            box-shadow: 0 8px 32px rgba(30,41,59,0.18), 0 1.5px 7px 0 rgba(37,99,235,0.07);
            transition: box-shadow 0.2s, border 0.2s;
            min-height: 450px;
            justify-content: center;
            animation: fadeSlideIn 0.7s cubic-bezier(0.4,0,0.2,1);
            z-index: 2;
        }     
        .admin-landing-hero h1 {
            font-size: 2.2rem;
            font-weight: 700;
            font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        }
        .admin-landing-hero p.lead {
            font-size: 1.1rem;
            font-weight: 400;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            margin-top: 1.5rem;
        }
        .admin-landing-hero ul {
            margin-top: 1.5rem;
        }
        .admin-landing-hero li {
            margin-bottom: 0.5rem;
            font-size: 1rem;
            color: #ffff;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        }
        .admin-login-card {
            border-radius: 1.25rem;
            min-width: 400px;
            min-height: 480px;
            background: linear-gradient(135deg, rgba(174, 175, 242, 0.111) 60%, rgba(187, 197, 244, 0.6) 100%);
            box-shadow: 0 8px 32px rgba(30,41,59,0.18), 0 1.5px 7px 0 rgba(37,99,235,0.07);
            backdrop-filter: blur(10px);
            border: 1.5px solid #dfe3ed;
            padding: 2.5rem 2.2rem 2rem 2.2rem;
            transition: box-shadow 0.2s, border 0.2s;
            animation: fadeSlideIn 0.7s cubic-bezier(0.4,0,0.2,1);
        }
        .admin-login-card:hover {
            box-shadow: 0 12px 40px rgba(30,41,59,0.22);
            border: 1.5px solid #2563eb;
        }
        .admin-login-card h3 {
            font-weight: 700;
            font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            color: white;
            letter-spacing: 0.03em;
            margin-bottom: 2rem;
            margin-top: 1.5rem;
            font-size: 2rem;
        }
        .admin-login-card h3:hover {
            color: rgba(17, 5, 113, 0.908);

        }
        .admin-login-card .form-label {
            font-weight: 600;
            color: #fff;
            letter-spacing: 0.02em;
            font-size: 1.06rem;
            
        }
        .admin-login-card .form-control {
            border-radius: 0.5rem !important;
            border: 1.5px solid #cbd5e1;
            padding: 0.65rem 1rem;
            margin-bottom: 10%;
            font-size: 1.08rem;
            background: transparent;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .admin-login-card .form-control:focus {
            box-shadow: 0 0 0 3px #2563eb33;
            border-color: #2563eb;
            background: #fff;
        }
        .admin-login-card .input-group .btn {
            border-radius: 0.5rem;
            border: 1.5px solid #cbd5e1;
            background: transparent;
            transition: border-color 0.2s, background 0.2s;
        }
        .admin-login-card .input-group .btn:focus,
        .admin-login-card .input-group .btn:hover {
            border-color: #2563eb;
            background: #e0e7ff;
        }
        .admin-login-card .btn-primary {
            background: linear-gradient(90deg, #2563eb 70%, #1e40af 100%);
            border: none;
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 1.08rem;
            margin-top: 2.0rem;
            padding: 1.05rem 0;
            box-shadow: 0 2px 10px #2563eb22;
            transition: background 0.2s, box-shadow 0.2s;
        }
        .admin-login-card .btn-primary:hover, .admin-login-card .btn-primary:focus {
            background: linear-gradient(90deg, #1e40af 60%, #2563eb 100%);
            box-shadow: 0 4px 18px #2563eb33;
        }
        .admin-login-card .invalid-feedback {
            color: #d32f2f;
            font-size: 0.97rem;
        }
        .admin-login-card .form-control.is-invalid {
            border-color: #d32f2f;
            background: #fff1f2;
        }
        .admin-login-card .input-group {
            align-items: center;
            position: relative;
        }
        .admin-login-card .input-group .form-control {
            border-top-right-radius: 0.5rem !important;
            border-bottom-right-radius: 0.5rem !important;
        }
        .admin-login-card .input-group .btn-eye-hidden {
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.2s;
        }
        .admin-login-card .input-group .btn-eye-visible {
            visibility: visible;
            opacity: 1;
            transition: opacity 0.2s;
        }
        .admin-login-card .input-group .btn-eye {
            position: absolute;
            right: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
            z-index: 2;
            border: none;
            background: transparent;
            padding: 0;
            margin: 0;
            color: #64748b;
            box-shadow: none;
            outline: none;
            border-radius: 50%;
            height: 1.7rem;
            width: 1.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .admin-login-card .input-group .btn-eye svg {
            width: 1rem;
            height: 1rem;
        }
        .admin-login-card .input-group .btn-eye:focus,
        .admin-login-card .input-group .btn-eye:hover {
            color: #2563eb;
            background: #e0e7ff;
        }
        .admin-login-card .position-relative .form-control {
            padding-right: 2.5rem;
        }
        @media (max-width: 991px) {
            .admin-landing-hero {
                border-radius: 1.25rem 1.25rem 0 0;
                min-height: unset;
                padding: 2rem 1.25rem 1.5rem 1.25rem;
                text-align: center;
            }
            .admin-login-card {
                border-radius: 0 0 1.25rem 1.25rem;
                min-width: unset;
            }
        }
        /* Responsive adjustments for admin login card */
        @media (max-width: 991px) {
            .admin-login-card {
                min-width: unset;
                width: 100%;
                padding: 2rem 1.2rem 1.5rem 1.2rem;
                border-radius: 1.25rem;
            }
        }
        @media (max-width: 768px) {
            .admin-login-card {
                width: 95%;
                margin: 0 auto;
                padding: 1.5rem 0.7rem 1.2rem 0.7rem;
                font-size: 0.98rem;
            }
            .admin-login-card h3 {
                font-size: 1.5rem;
            }
            .admin-login-card .form-label {
                font-size: 1rem;
            }
        }
        @media (max-width: 480px) {
            .admin-login-card {
                width: 100vw;
                min-width: unset;
                border-radius: 0;
                box-shadow: none;
                padding: 1rem 0.3rem 1rem 0.3rem;
            }
            .admin-login-card h3 {
                font-size: 1.2rem;
            }
            .admin-login-card .form-label {
                font-size: 0.97rem;
            }
            .admin-login-card .btn-primary {
                font-size: 0.97rem;
                padding: 0.5rem 0;
            }
        }
        /* Animation for admin login card */
        @keyframes fadeSlideIn {
            0% {
                opacity: 0;
                transform: translateY(40px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .shrinq-logo-bg {
            position: absolute;
            top: 2.2rem;
            left: 2.2rem;
            z-index: 10;
        }
        .shrinq-logo-img {
            width: 200px;
            height: auto;
            filter: drop-shadow(0 2px 8px rgba(30,41,59,0.08));
            opacity: 0.93;
            transition: transform 0.18s;
        }
        .shrinq-logo-img:hover {
            transform: scale(1.07) rotate(-2deg);
            opacity: 1;
        }
        #page-loader {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(24,32,54,0.86);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.6s cubic-bezier(0.4,0,0.2,1);
        }
        #page-loader.hidden {
            opacity: 0;
            pointer-events: none;
        }
        .loader-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.4rem;
        }
        .loader-logo {
            width: 250px;
            height: auto;
            filter: drop-shadow(0 4px 16px rgba(30,41,59,0.14));
            animation: loaderLogoPulse 1.5s infinite alternate cubic-bezier(0.4,0,0.2,1);
        }
        .loader-spinner {
            width: 44px;
            height: 44px;
            border: 4px solid #fff;
            border-top: 4px solid #a9eb25ff;
            border-radius: 50%;
            animation: loaderSpin 1.1s linear infinite;
            box-shadow: 0 2px 16px #2563eb22;
        }
        @keyframes loaderSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes loaderLogoPulse {
            0% { transform: scale(1); filter: brightness(1); }
            100% { transform: scale(1.09); filter: brightness(1.13); }
        }
        .footer {
            width: 100%;
            position: fixed;
            left: 0;
            bottom: 0;
            z-index: 100;
            background:transparent;
            color: #fff;
            text-align: center;
            padding: 0.7rem 0;
            font-size:1rem !important;
            font-weight: 500;
            letter-spacing: 0.02em;
            font-family: Inter, Arial, Bold, sans-serif;
            box-shadow: 0 -2px 16px #23355422;
        }
        .footer:hover {
            color: rgb(224, 0, 0) !important;
        }
    </style>
</head>
<body>
<div id="page-loader">
    <div class="loader-content">
        <img src="/images/colored.png" alt="Shrinq Loader" class="loader-logo">
        <div class="loader-spinner"></div>
    </div>
</div>
<div class="shrinq-logo-bg">
    <img src="/images/colored.png" alt="Shrinq Logo" class="shrinq-logo-img">
</div>
<div class="bg-illustration"></div>
<div class="admin-landing-bg">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="admin-landing-hero text-center text-lg-start">
                    <img src="/images/logo 1.png" alt="Admin Logo" class="mb-3" style="width: 170px; height: auto;">
                    <h1 class="fw-bold mb-2">Admin Portal</h1>
                    <p class="lead mb-4">Welcome to your admin portal. Manage users, content, and settings with professional control and security.</p>
                    <ul class="list-unstyled text-white-50 small mb-0">
                        <li><span style="color:#22c55e;">&#10003;</span> Secure Access</li>
                        <li><span style="color:#22c55e;">&#10003;</span> Real-time Analytics</li>
                        <li><span style="color:#22c55e;">&#10003;</span> Easy User Management</li>
                        <li><span style="color:#22c55e;">&#10003;</span> Content Management</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card admin-login-card p-0">
                    <div class="card-body">
                        <h3 class="text-center mb-3">Admin Login</h3>
                        <form method="POST" action="{{ route('superAdmin.login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autofocus autocomplete="username" placeholder="Enter your username">
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3 position-relative">
                                <label for="password" class="form-label">Password</label>
                                <div class="position-relative">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter your password">
                                    <button class="btn-eye btn-eye-visible" type="button" id="togglePassword" tabindex="-1" style="position:absolute;top:50%;right:0.5rem;transform:translateY(-50%);height:1.7rem;width:1.7rem;padding:0;margin:0;border-radius:50%;display:flex;align-items:center;justify-content:center;background:transparent;border:none;box-shadow:none;">
                                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.786C11.879 11.332 10.12 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.133 13.133 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zm0 1a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3z"/>
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-center mt-4">
                                <button type="submit" class="btn btn-primary btn-sm px-4">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="footer">
    &copy; {{ date('Y') }} ShrinQ Limited. All rights reserved.
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Page loader logic
    window.addEventListener('load', function() {
        setTimeout(function() {
            document.getElementById('page-loader').classList.add('hidden');
        }, 1000); // Delay for smoothness
    });
    // Password toggle logic
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const emailInput = document.getElementById('username');
        const togglePassword = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');
        let visible = false;

        // Placeholder management
        const emailPlaceholder = 'Enter your username';
        const passwordPlaceholder = 'Enter your password';
        function handleFocus(e) { e.target.placeholder = ''; }
        function handleBlur(e) {
            if (!e.target.value) {
                if (e.target === emailInput) e.target.placeholder = emailPlaceholder;
                if (e.target === passwordInput) e.target.placeholder = passwordPlaceholder;
            }
        }
        emailInput.addEventListener('focus', handleFocus);
        emailInput.addEventListener('blur', handleBlur);
        passwordInput.addEventListener('focus', handleFocus);
        passwordInput.addEventListener('blur', handleBlur);

        // Show/hide eye button based on password input
        function updateEyeButton() {
            // Always show the eye button
            togglePassword.classList.add('btn-eye-visible');
            togglePassword.classList.remove('btn-eye-hidden');
        }
        updateEyeButton();
        passwordInput.addEventListener('input', updateEyeButton);
        passwordInput.addEventListener('blur', updateEyeButton);
        passwordInput.addEventListener('focus', updateEyeButton);

        togglePassword.addEventListener('click', function() {
            visible = !visible;
            passwordInput.type = visible ? 'text' : 'password';
            eyeIcon.innerHTML = visible
                ? `<path d="M13.359 11.238a7.013 7.013 0 0 1-5.359 2.262c-2.12 0-3.879-1.168-5.168-2.457A13.133 13.133 0 0 1 1.172 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288l1.415 1.415a.5.5 0 0 1-.707.707l-1.415-1.415a7.013 7.013 0 0 1-1.567 2.243zM8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z"/>`
                : `<path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.786C11.879 11.332 10.12 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.133 13.133 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zm0 1a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3z"/>`;
        });
    });
</script>
</body>
</html>
