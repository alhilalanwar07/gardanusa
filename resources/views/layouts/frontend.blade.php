<!DOCTYPE html>
<html lang="en">

<head>
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-JF8TZJN2NF"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-JF8TZJN2NF');
  </script>
  <script>
    (function(w, d, s, l, i) {
      w[l] = w[l] || [];
      w[l].push({
        'gtm.start': new Date().getTime(),
        event: 'gtm.js'
      });
      var f = d.getElementsByTagName(s)[0],
        j = d.createElement(s),
        dl = l != 'dataLayer' ? '&l=' + l : '';
      j.async = true;
      j.src =
        'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
      f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-W2P7DDW3');
  </script>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', config('app.name'))</title>
  <meta name="description" content="CV. Garuda Digital Nusantara - Digitalisasi bisnis Anda dengan solusi IT terbaik.">
  <meta name="keywords" content="CV. Garuda Digital Nusantara, IT Solution, Web Development, Software Development">
  <meta name="author" content="CV. Garuda Digital Nusantara">
  <meta name="robots" content="index, follow">
  <meta name="google-site-verification" content="OxHtDk213lKW9H0kNGlfGOO3h5HYNJWnWHqLnoc-0hg" />

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:title" content="CV. Garuda Digital Nusantara">
  <meta property="og:description" content="Digitalisasi bisnis Anda dengan solusi IT terbaik.">
  <meta property="og:image" content="{{ url('/') }}/favicon-96x96.png">
  <meta property="og:url" content="{{ url('/') }}">
  <meta property="og:site_name" content="CV. Garuda Digital Nusantara">

  <!-- Twitter Meta Tags -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="CV. Garuda Digital Nusantara">
  <meta name="twitter:description" content="Digitalisasi bisnis Anda dengan solusi IT terbaik.">
  <meta name="twitter:image" content="{{ url('/') }}/favicon-96x96.png">

  <!-- Preconnect & Preload Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet" media="print" onload="this.onload=null;this.removeAttribute('media');">

  <!-- CSS -->
  <link rel="stylesheet" href="{{ url('/') }}/assets-front/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{ url('/') }}/assets-front/vendor/bootstrap-icons/bootstrap-icons.css">
  <link rel="stylesheet" href="{{ url('/') }}/assets-front/vendor/aos/aos.css">
  <link rel="stylesheet" href="{{ url('/') }}/assets-front/vendor/glightbox/css/glightbox.min.css">
  <link rel="stylesheet" href="{{ url('/') }}/assets-front/vendor/swiper/swiper-bundle.min.css">
  <link rel="stylesheet" href="{{ url('/') }}/assets-front/css/main.css">

  @stack('meta')

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ url('/') }}/favicon-96x96.png" sizes="96x96">
  <link rel="icon" type="image/svg+xml" href="{{ url('/') }}/favicon.svg">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ url('/') }}/apple-touch-icon.png">
  <link rel="shortcut icon" href="{{ url('/') }}/favicon.ico">
  <link rel="manifest" href="{{ url('/') }}/manifest.json">
  <meta name="theme-color" content="#ffffff">

  <!-- JSON-LD Structured Data -->
  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "CV. Garuda Digital Nusantara",
      "url": "{{ url('/') }}",
      "logo": "{{ url('/') }}/favicon-96x96.png",
      "sameAs": [
        "https://www.facebook.com/gardanusa",
        "https://www.instagram.com/gardanusa",
        "https://www.linkedin.com/company/gardanusa",
        "https://www.tiktok.com/@gardanusa"
      ],
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+62 812-3456-7890",
        "contactType": "Customer Service",
        "areaServed": "ID",
        "availableLanguage": "Bahasa Indonesia"
      },
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Kec. Kolaka",
        "addressLocality": "Kolaka",
        "addressRegion": "Sulawesi Tenggara",
        "postalCode": "93511",
        "addressCountry": "ID"
      }
    }
  </script>


  <style>
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
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W2P7DDW3"
      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

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