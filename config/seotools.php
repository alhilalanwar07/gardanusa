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
            'titleBefore'  => false, // Jika ingin menampilkan default title sebelum judul halaman
            'description'  => 'Solusi digital terbaik untuk pengembangan website dan mobile app di Kolaka. Mitra Digitalisasi Terpercaya untuk pemerintahan dan bisnis.', // Deskripsi default
            'separator'    => ' - ',
            'keywords'     => ['digital', 'web development', 'mobile development', 'Kolaka', 'pemerintahan'], // Kata kunci
            'canonical'    => null, // Gunakan Url::full() secara default
            'robots'       => 'index, follow', // Atur agar mesin pencari mengindeks dan mengikuti link
        ],
        /*
         * Webmaster tags, bisa diisi jika sudah tersedia verifikasi dari Google, Bing, dll.
         */
        'webmaster_tags' => [
            'google'    => 'google-site-verification=TXXASz3Wrgzqyc4q2D_1c_3qlHfCehoQkFkN2SfzX_s',
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
            'description' => 'Solusi digital terbaik untuk pengembangan website dan mobile app di Kolaka. Mitra Digitalisasi Terpercaya untuk pemerintahan dan bisnis.',
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
            'title'       => 'CV. Garuda Digital Nusantara | Mitra Digitalisasi Terpercaya',
            'description' => 'Solusi digital terbaik untuk pengembangan website dan mobile app di Kolaka. Mitra Digitalisasi Terpercaya untuk pemerintahan dan bisnis.',
            'url'         => null, // Gunakan Url::full() secara default
            'type'        => 'WebPage',
            'images'      => [
                // Tambahkan URL gambar default jika 
            ],
        ],
    ],
];
