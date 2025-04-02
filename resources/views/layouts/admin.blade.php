<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>
        @yield('title', config('app.name'))
    </title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ url('/') }}/assets/img/logo/favicon.ico" type="image/x-icon" />
    <link rel="icon" type="image/png" href="{{url('/')}}/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{url('/')}}/favicon.svg" />
    <link rel="shortcut icon" href="{{url('/')}}/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{url('/')}}/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="Gardanusa" />
    <link rel="manifest" href="{{url('/')}}/site.webmanifest" />
    <link rel="manifest" href="{{url('/')}}/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{url('/')}}/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">


    <script src="{{ url('/') }}/assets/js/plugin/webfont/webfont.min.js" data-navigate-track></script>

    <link rel="stylesheet" href="{{ url('/') }}/assets/css/fonts.min.css" />
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/plugins.min.css" />
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/kaiadmin.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="{{ url('/') }}/assets-front/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

    @stack('styles')
    @livewireStyles
</head>

<body>
    <div class="wrapper">
        <livewire:layout.admin-navigation />

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="white">
                        <a href="#" class="logo">
                            <img src="{{url('/')}}/favicon-96x96.png" alt="Gardanusa" class="navbar-brand" height="20" />
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                    <!-- End Logo Header -->
                </div>
                <!-- Navbar Header -->
                <livewire:layout.admin-header />
                <!-- End Navbar -->
            </div>

            <div class="container">
                {{ $slot }}
            </div>

            <footer class="footer">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="copyright">
                        {{ date('Y') }}, made with <i class="fa fa-heart heart text-danger"></i> by
                        <a href="#">YOU</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <!--   Core JS Files   -->
    <script src="{{ url('/') }}/assets/js/core/jquery-3.7.1.min.js" data-navigate-track></script>
    <script src="{{ url('/') }}/assets/js/core/popper.min.js" data-navigate-track></script>
    <script src="{{ url('/') }}/assets/js/core/bootstrap.min.js" data-navigate-track></script>

    <!-- jQuery Scrollbar -->
    <script src="{{ url('/') }}/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js" data-navigate-track></script>

    <!-- Chart JS -->
    <script src="{{ url('/') }}/assets/js/plugin/chart.js/chart.min.js" data-navigate-track></script>

    <!-- jQuery Sparkline -->
    <script src="{{ url('/') }}/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js" data-navigate-track></script>

    <!-- Bootstrap Notify -->
    <script src="{{ url('/') }}/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js" data-navigate-track></script>

    <!-- Sweet Alert -->
    <script src="{{ url('/') }}/assets/js/plugin/sweetalert/sweetalert.min.js" data-navigate-track></script>

    <!-- Kaiadmin JS -->
    <script src="{{ url('/') }}/assets/js/kaiadmin.min.js" data-navigate-track></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js" data-navigate-track></script>

    @stack('script')
    @stack('scripts')
    @livewireScripts
    @livewireChartsScripts
</body>

</html>