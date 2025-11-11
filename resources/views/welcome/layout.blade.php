<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta content="StationMgt" name="description" />
        <meta content="StationMgt" name="Panga Limited" />
        <title>StationMgt</title>
        <link rel="stylesheet" href="{{ asset('style/style.css') }}" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- webpage logo icon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('images/PangaTranparent.png') }}" />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <!-- Include SweetAlert CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

        <!-- Include SweetAlert JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link
            href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
            rel="stylesheet" />
    </head>
    <body>
        @include('welcome.navbar')

        @yield('content')

        <script src="https://kit.fontawesome.com/f0f7be09c0.js" crossorigin="anonymous"></script>
        <script src="{{ asset('style/script.js') }}"></script>
    </body>
</html>
