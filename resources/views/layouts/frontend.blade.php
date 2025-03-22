<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>@yield('title', config('app.name'))</title>
  <meta name="description" content="CV. Garuda Digital Nusantara">
  <meta name="keywords" content="CV. Garuda Digital Nusantara">
  <meta name="author" content="CV. Garuda Digital Nusantara">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ url('/') }}/assets-front/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ url('/') }}/assets-front/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="{{ url('/') }}/assets-front/vendor/aos/aos.css" rel="stylesheet">
  <link href="{{ url('/') }}/assets-front/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="{{ url('/') }}/assets-front/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{ url('/') }}/assets-front/css/main.css" rel="stylesheet">

  <link rel="apple-touch-icon-precomposed" sizes="57x57" href="{{url('/')}}/apple-touch-icon-57x57.png" />
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{url('/')}}/apple-touch-icon-114x114.png" />
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{url('/')}}/apple-touch-icon-72x72.png" />
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{url('/')}}/apple-touch-icon-144x144.png" />
  <link rel="apple-touch-icon-precomposed" sizes="60x60" href="{{url('/')}}/apple-touch-icon-60x60.png" />
  <link rel="apple-touch-icon-precomposed" sizes="120x120" href="{{url('/')}}/apple-touch-icon-120x120.png" />
  <link rel="apple-touch-icon-precomposed" sizes="76x76" href="{{url('/')}}/apple-touch-icon-76x76.png" />
  <link rel="apple-touch-icon-precomposed" sizes="152x152" href="{{url('/')}}/apple-touch-icon-152x152.png" />
  <link rel="icon" type="image/png" href="{{url('/')}}/favicon-196x196.png" sizes="196x196" />
  <link rel="icon" type="image/png" href="{{url('/')}}/favicon-96x96.png" sizes="96x96" />
  <link rel="icon" type="image/png" href="{{url('/')}}/favicon-32x32.png" sizes="32x32" />
  <link rel="icon" type="image/png" href="{{url('/')}}/favicon-16x16.png" sizes="16x16" />
  <link rel="icon" type="image/png" href="{{url('/')}}/favicon-128.png" sizes="128x128" />
  <meta name="application-name" content="CV. Garuda Digital Nusantara" />
  <meta name="msapplication-TileColor" content="#FFFFFF" />
  <meta name="msapplication-TileImage" content="{{url('/')}}/mstile-144x144.png" />
  <meta name="msapplication-square70x70logo" content="{{url('/')}}/mstile-70x70.png" />
  <meta name="msapplication-square150x150logo" content="{{url('/')}}/mstile-150x150.png" />
  <meta name="msapplication-wide310x150logo" content="{{url('/')}}/mstile-310x150.png" />
  <meta name="msapplication-square310x310logo" content="{{url('/')}}/mstile-310x310.png" />

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
      </strong> <span>All Rights Reserved</span></p>
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