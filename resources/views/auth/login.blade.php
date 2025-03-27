<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Login</title>

    <!-- Existing Assets -->
    <link type="text/css" href="{{ url('/') }}/vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <link type="text/css" href="{{ url('/') }}/vendor/notyf/notyf.min.css" rel="stylesheet">
    <link type="text/css" href="{{ url('/') }}/css/volt.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif !important;

        }

        .fas,
        .far,
        .fab {
            font-family: "Font Awesome 5 Free" !important;
        }

        body,
        html {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        .gradient-section {
            background-image: url('{{ url('/') }}/assets/img/bg-tech.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .rocket-image {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .custom-checkbox .form-check-input:checked {
            background-color: #FF4757;
            border-color: #FF4757;
        }

        .login-container {
            display: flex;
            min-height: 100vh;
        }

        .login-image-section,
        .login-form-section {
            min-height: 100vh;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(255, 71, 87, 0.25);
            border-color: #FF4757;
            background-color: #fff;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 10px 0 0 10px;
        }

        .btn-sign-in {
            background: linear-gradient(45deg, #FF4757, #FF6B81);
            border: none;
            padding: 12px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(255, 71, 87, 0.35);
            transition: all 0.3s ease;
        }

        .btn-sign-in:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 71, 87, 0.45);
        }

        .form-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .floating-label {
            position: relative;
            margin-bottom: 20px;
        }

        .floating-label input {
            padding-left: 45px;
        }

        .floating-label .icon {
            position: absolute;
            left: 15px;
            top: 14px;
            color: #adb5bd;
        }

        /* Hide image section on mobile */
        @media (max-width: 767.98px) {
            .login-image-section {
                display: none;
            }

            .login-form-section {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <!-- Left Section (Image) -->
        <div class="login-image-section gradient-section p-5 text-center text-white d-flex flex-column justify-content-center col-md-6 d-none d-md-flex">
            <img src="{{url('/')}}/favicon-96x96.png"
                alt="Gardanuda"
                class="rocket-image img-fluid mb-4"
                style="max-width: 350px; margin: 0 auto;">
            <h2 class="mb-3">Hey there!</h2>
            <p class="lead mb-4">Welcome back. You are just one step away to your feed.</p>
        </div>

        <!-- Right Section (Form) -->
        <div class="login-form-section col-12 col-md-6 p-5 d-flex flex-column justify-content-center">
            <div class="w-100" style="max-width: 600px; margin: 0 auto;">
                <div class="text-center mb-5">
                    <img src="{{url('/')}}/favicon-96x96.png" alt="Garudanusa Logo" class="mb-3" width="80">
                    <h2 class="fw-bold mb-0" style="background: linear-gradient(45deg, #2D98DA, #FF4757); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">GARDANUSA</h2>
                    <p class="text-muted">CV. Garuda Digital Nusantara</p>
                    <div class="d-flex justify-content-center">
                        <div style="width: 60px; height: 4px; background: linear-gradient(45deg, #2D98DA, #FF4757); border-radius: 2px;"></div>
                    </div>
                </div>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="floating-label mb-4">
                        <i class="fas fa-envelope icon"></i>
                        <input type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            name="email"
                            placeholder="Your email address"
                            value="{{ old('email') }}"
                            required>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="floating-label mb-4">
                        <i class="fas fa-lock icon"></i>
                        <input type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            name="password"
                            placeholder="Your password"
                            required>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-danger btn-sign-in w-100 rounded-pill mb-3">
                        Masuk <i class="fas fa-arrow-right ms-1"></i>
                    </button>
                </form>

                <!-- Footer -->
                <div class="footer mt-auto pt-4 text-center">
                    <hr class="my-4">
                    <div class="d-flex justify-content-center">
                        <div>
                            <p class="mb-0 text-muted small">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                        </div>
                        <div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Existing JS Assets -->
    <script src="{{ url('/') }}/vendor/@popperjs/core/dist/umd/popper.min.js"></script>
    <script src="{{ url('/') }}/vendor/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="{{ url('/') }}/vendor/notyf/notyf.min.js"></script>
    <script src="{{ url('/') }}/assets/js/volt.js"></script>
</body>

</html>