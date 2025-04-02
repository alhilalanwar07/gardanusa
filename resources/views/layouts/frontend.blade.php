<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>@yield('title', config('app.name'))</title>
  <meta name="description" content="CV. Garuda Digital Nusantara">
  <meta name="keywords" content="CV. Garuda Digital Nusantara">
  <meta name="author" content="CV. Garuda Digital Nusantara">

  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <link href="{{ url('/') }}/assets-front/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ url('/') }}/assets-front/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="{{ url('/') }}/assets-front/vendor/aos/aos.css" rel="stylesheet">
  <link href="{{ url('/') }}/assets-front/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="{{ url('/') }}/assets-front/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <link href="{{ url('/') }}/assets-front/css/main.css" rel="stylesheet">

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

  <style>
    /* font poppins kecuali fas,far, icon dll */
    * {
      font-family: 'Poppins', sans-serif !important;
    }

    .fas,
    .far,
    .fab {
      font-family: "Font Awesome 5 Free" !important;
    }
  </style>

  @livewireStyles()
</head>

<body class="index-page">

  <livewire:layout.frontend-header />


  <main class="main">

    {{ $slot }}

  </main>

  <livewire:layout.frontend-navigation />

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{ url('/') }}/assets-front/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="{{ url('/') }}/assets-front/vendor/php-email-form/validate.js"></script>
  <script src="{{ url('/') }}/assets-front/vendor/aos/aos.js"></script>
  <script src="{{ url('/') }}/assets-front/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="{{ url('/') }}/assets-front/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="{{ url('/') }}/assets-front/vendor/purecounter/purecounter_vanilla.js"></script>

  <!-- Main JS File -->
  <script src="{{ url('/') }}/assets-front/js/main.js"></script>

  @stack('script')
  @stack('scripts')
  @livewireScripts()

</body>

</html>