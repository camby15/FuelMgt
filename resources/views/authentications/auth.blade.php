<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="author" content="ShrinQ Limited" />
        <meta name="description" content="" />
        <link rel="icon" href="{{ asset('images/logo 1.png') }}" />
        <link rel="stylesheet" href="{{ asset('style/mediaQuery.css') }}" />
        <link rel="stylesheet" href="{{ asset('style/auth-1.css') }}" />
        <link href="https://fonts.cdnfonts.com/css/poppins" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('style/style.css') }}" />
        <title>Stak | Auth</title>
    </head>

    <body>
        <div class="welcome-box">
            <h1>SELECT YOUR ACCOUNT</h1>
            <h2>Let's get you started</h2>
            <a href="{{ route('auth.company') }}" class="buttons">Sign Up as a Company</a>
            <a href="{{ route('auth.individual') }}" class="buttons">Sign Up as an Individual</a>
            <span>OR</span>
            <a href="{{ route('auth.login') }}" class="buttons">Login</a>
        </div>
    </body>
</html>
