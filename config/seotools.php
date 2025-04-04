<?php
/**
 * Konfigurasi SEO untuk CV. Garuda Digital Nusantara
 * Tagline: Mitra Digitalisasi Terpercaya
 */

return [
    'inertia' => env('SEO_TOOLS_INERTIA', false),
    'meta' => [
        /*
         * Konfigurasi default untuk meta tags.
         */
        'defaults'       => [
            'title'        => 'CV. Garuda Digital Nusantara | Mitra Digitalisasi Terpercaya', // Judul default
            'titleBefore'  => 'CV. Garuda Digital Nusantara | ', // Judul sebelum
            'titleAfter'   => ' | Mitra Digitalisasi Terpercaya', // Judul setelah
            'description'  => 'Solusi digital terbaik untuk pengembangan website dan mobile app. Mitra Digitalisasi Terpercaya untuk pemerintahan dan bisnis.', // Deskripsi default
            'separator'    => ' - ',
            'keywords'     => ['digital', 'web development', 'mobile development', 'Kolaka', 'pemerintahan'], // Kata kunci
            'canonical'    => null, // Gunakan Url::full() secara default
            'robots'       => 'index, follow', // Atur agar mesin pencari mengindeks dan mengikuti link
        ],
        /*
         * Webmaster tags, bisa diisi jika sudah tersedia verifikasi dari Google, Bing, dll.
         */
        'webmaster_tags' => [
            'google'    => 'google-site-verification=OxHtDk213lKW9H0kNGlfGOO3h5HYNJWnWHqLnoc-0hg',
            'bing'      => null,
            'alexa'     => null,
            'pinterest' => null,
            'yandex'    => null,
            'norton'    => null,
        ],
        'add_notranslate_class' => false,
    ],
    'opengraph' => [
        /*
         * Konfigurasi default untuk OpenGraph.
         */
        'defaults' => [
            'title'       => 'CV. Garuda Digital Nusantara | Mitra Digitalisasi Terpercaya',
            'description' => 'Solusi digital terbaik untuk pengembangan website dan mobile app. Mitra Digitalisasi Terpercaya untuk pemerintahan dan bisnis.',
            'url'         => null, // Menggunakan Url::full() secara default
            'type'        => 'website',
            'site_name'   => 'CV. Garuda Digital Nusantara',
            'images'      => [
                // URL gambar default menggunakan favicon.ico dari public directory
            ],
        ],
    ],
    'twitter' => [
        /*
         * Konfigurasi default untuk Twitter Card.
         */
        'defaults' => [
            'card'        => 'summary_large_image',
            //'site'      => '@garudadigital', // Jika sudah punya handle Twitter, bisa diisi
        ],
    ],
    'json-ld' => [
        /*
         * Konfigurasi default untuk JSON-LD.
         */
        'defaults' => [
            '@context'    => 'https://schema.org',
            '@type'       => ['Organization', 'LocalBusiness'],
            'name'        => 'CV. Garuda Digital Nusantara',
            'description' => 'Solusi digital terbaik untuk pengembangan website dan mobile app. Mitra Digitalisasi Terpercaya untuk pemerintahan dan bisnis.',
            'url'         => null, // Gunakan Url::full() secara default
            'logo'        => env('APP_URL') . '/favicon-96x96.png',
            'image'       => [
                env('APP_URL') . '/favicon-96x96.png',
                env('APP_URL') . '/favicon-96x96.png',
            ],
            'telephone'   => '+628xxxxxxxxxx', // Isi dengan nomor telepon sebenarnya
            'email'       => 'info@gardanusa.tech', // Isi dengan email sebenarnya
            'address'     => [
                '@type'           => 'PostalAddress',
                'streetAddress'   => 'Jalan Example No. 123', // Isi dengan alamat sebenarnya
                'addressLocality' => 'Kolaka',
                'addressRegion'   => 'Sulawesi Tenggara',
                'postalCode'      => '93xxx', // Isi dengan kode pos sebenarnya
                'addressCountry'  => 'ID',
            ],
            'sameAs'      => [
                'https://facebook.com/gardanusa',
                'https://instagram.com/gardanusa',
                // Tambahkan profil media sosial lainnya
            ],
            'openingHoursSpecification' => [
                [
                    '@type'     => 'OpeningHoursSpecification',
                    'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                    'opens'     => '00:01',
                    'closes'    => '23:59',
                ],
            ],
            'priceRange'  => '$$',
            'serviceType' => ['Web Development', 'Mobile Development', 'Digital Solutions'],
        ],
    ],
];
