<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div>
    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

            <a href="/" class="logo d-flex align-items-center me-auto me-xl-0">
                <!-- <img src="assets/img/logo.png" alt=""> -->
                <h1 class="sitename">Gardanusa</h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="#beranda" class="active">Beranda</a></li>
                    <li><a href="#tentang">Tentang</a></li>
                    <li><a href="#produk">Produk</a></li>
                    <li><a href="#layanan">Layanan</a></li>
                    <li><a href="#testimoni">Testimoni</a></li>
                    <li><a href="#blog">Blog</a></li>
                    <li><a href="#faq">FAQ</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            <a class="btn-getstarted" href="#">Hubungi Kami
                <i class="bi bi-whatsapp"></i>
            </a>

        </div>
    </header>
</div>