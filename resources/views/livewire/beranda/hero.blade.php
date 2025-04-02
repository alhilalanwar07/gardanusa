<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div>
    <section id="beranda" class="hero section">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content" data-aos="fade-up" data-aos-delay="200">
                        <div class="company-badge mb-4">
                            <i class="bi bi-gear-fill me-2"></i>
                            Mitra Digitalisasi Terpercaya
                        </div>

                        <h1 class="mb-4">
                            CV. Garuda Digital <br>
                            Nusantara <br>
                            <span class="accent-text">Solusi Digital Terbaik</span>
                        </h1>

                        <p class="mb-4 mb-md-5" style="text-align: justify;">
                            Solusi digital terbaik untuk pengembangan website dan mobile app. 
                            Mitra digitalisasi terpercaya di semua sektor,
                            mulai dari pemerintahan, bisnis, UMKM, startup, hingga korporat.
                        </p>

                        <div class="hero-buttons">
                            <a href="#about" class="btn btn-primary me-0 me-sm-2 mx-1">Konsultasi Gratis</a>
                            <a href="https://www.youtube.com/watch?v=yZLILMmYLUI" class="btn btn-link mt-2 mt-sm-0 glightbox">
                                <i class="bi bi-play-circle me-1"></i>
                                Lihat Portfolio
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="hero-image" data-aos="zoom-out" data-aos-delay="300">
                        <img src="{{url('/')}}/assets-front/img/illustration-1.webp" alt="Garuda Digital Nusantara Hero Image" class="img-fluid">

                        <div class="customers-badge">
                            <div class="customer-avatars">
                                <img src="{{url('/')}}/assets-front/img/avatar-1.webp" alt="Klien Pemerintahan" class="avatar">
                                <span class="avatar more">50+</span>
                            </div>
                            <p class="mb-0 mt-2">Lebih dari 50 klien puas dengan layanan digital kami</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row stats-row gy-4 mt-5" data-aos="fade-up" data-aos-delay="500">
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="bi bi-trophy"></i>
                        </div>
                        <div class="stat-content">
                            <h4>100+ Proyek</h4>
                            <p class="mb-0">Web & Mobile Apps</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="bi bi-briefcase"></i>
                        </div>
                        <div class="stat-content">
                            <h4>50+ Klien</h4>
                            <p class="mb-0">Puas & Percaya</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <div class="stat-content">
                            <h4>24/7</h4>
                            <p class="mb-0">Dukungan Teknis</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="bi bi-award"></i>
                        </div>
                        <div class="stat-content">
                            <h4>5+ Tahun</h4>
                            <p class="mb-0">Pengalaman</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>