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

  @livewireStyles
</head>

<body class="index-page">

  <livewire:layout.frontend-header />


  <main class="main">

    {{ $slot }}

  </main>

  <footer id="footer" class="footer">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-6 col-md-6 footer-about">
          <a href="/" class="logo d-flex align-items-center">
            <span class="sitename">
              CV. Garuda Digital Nusantara
            </span>
          </a>
          <div class="footer-contact pt-3">
            <p>Kec. Kolaka Kab. Kolaka</p>
            <p>Prov. Sulawesi Tenggara</p>
            <p class="mt-3"><strong>Telp.:</strong> <span>+1 5589 55488 55</span></p>
            <p><strong>Email:</strong> <span>garudadigitalnusantara@gmail.com</span></p>
          </div>
          <div class="social-links d-flex mt-4">
            <a href="#"><i class="bi bi-twitter-x"></i></a>
            <a href="#"><i class="bi bi-facebook"></i></a>
            <a href="#"><i class="bi bi-instagram"></i></a>
            <a href="#"><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-md-3 footer-links">
          <h4>Useful Links</h4>
          <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">About us</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Terms of service</a></li>
            <li><a href="#">Privacy policy</a></li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-3 footer-links">
          <h4>Our Services</h4>
          <ul>
            <li><a href="#">Web Design</a></li>
            <li><a href="#">Web Development</a></li>
            <li><a href="#">Product Management</a></li>
            <li><a href="#">Marketing</a></li>
            <li><a href="#">Graphic Design</a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span>
        {{ date('Y') }}
        <strong class="px-1 sitename">
          CV. Garuda Digital Nusantara
        </strong> <span>All Rights Reserved</span>
      </p>
    </div>

  </footer>

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
  @livewireScripts
  @livewireChartsScripts

</body>

</html>