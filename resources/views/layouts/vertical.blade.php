<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @include('layouts.shared/title-meta', ['title' => $page_title])
        @yield('css')
        @include('layouts.shared/head-css', ['mode' => $mode ?? '', 'demo' => $demo ?? ''])
        <!-- Font Awesome CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        @vite(['resources/js/head.js'])
        <!-- Bootstrap JS Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <style>
            /* Enhanced Floating Label Styles */
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
                transition: all 0.3s ease;
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
                transition: all 0.3s ease;
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
                background-color: transparent;
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
                border-color: #0d6efd;
            }

            [data-bs-theme="dark"] .form-floating input.form-control:focus ~ label,
            [data-bs-theme="dark"] .form-floating input.form-control:not(:placeholder-shown) ~ label,
            [data-bs-theme="dark"] .form-floating select.form-select:focus ~ label,
            [data-bs-theme="dark"] .form-floating select.form-select:not([value=""]) ~ label,
            [data-bs-theme="dark"] .form-floating textarea.form-control:focus ~ label,
            [data-bs-theme="dark"] .form-floating textarea.form-control:not(:placeholder-shown) ~ label {
                color: #fff;
            }

            [data-bs-theme="dark"] .form-floating input.form-control:focus ~ label::before,
            [data-bs-theme="dark"] .form-floating input.form-control:not(:placeholder-shown) ~ label::before,
            [data-bs-theme="dark"] .form-floating select.form-select:focus ~ label::before,
            [data-bs-theme="dark"] .form-floating select.form-select:not([value=""]) ~ label::before,
            [data-bs-theme="dark"] .form-floating textarea.form-control:focus ~ label::before,
            [data-bs-theme="dark"] .form-floating textarea.form-control:not(:placeholder-shown) ~ label::before {
                background: #0d6efd;
            }

            /* Ensure select dropdowns have proper styling */
            .form-floating select.form-select {
                padding-top: 1.625rem;
                padding-bottom: 0.625rem;
            }

            /* Style for file inputs */
            .form-floating .form-control[type="file"] {
                padding-top: 2rem;
            }

            /* Style for checkboxes in medical section */
            .form-check-input:checked {
                background-color: #033c42;
                border-color: #033c42;
            }

            [data-bs-theme="dark"] .form-check-input:checked {
                background-color: #0d6efd;
                border-color: #0d6efd;
            }
        </style>
    </head>

    <body>
        <div class="wrapper">
            @include('layouts.shared/topbar')

            @include('layouts.shared/left-sidebar')

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
                <div class="content">
                    <!-- Start Content-->
                    @yield('content')
                </div>
                @include('layouts.shared/footer')
            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->
        </div>

        @include('layouts.shared/right-sidebar')
        @include('layouts.shared/footer-script')
        @vite(['resources/js/app.js', 'resources/js/layout.js'])
        @stack('javascript')
    </body>
</html>
