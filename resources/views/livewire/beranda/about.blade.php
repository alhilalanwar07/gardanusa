<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div>
    <section id="about" class="about section">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row gy-4 align-items-center justify-content-between">
                <div class="col-xl-5" data-aos="fade-up" data-aos-delay="200">
                    <span class="about-meta">ABOUT US</span>
                    <h2 class="about-title">CV. Garuda Digital Nusantara</h2>
                    <p class="about-description">
                        CV. Garuda Digital Nusantara hadir sebagai solusi digital terbaik untuk pengembangan website dan mobile app di Kolaka. 
                        Sebagai mitra digitalisasi terpercaya, kami mendukung pemerintahan, bisnis, UMKM, startup, dan korporat dalam melakukan transformasi digital yang inovatif.
                    </p>
                    <div class="row feature-list-wrapper">
                        <div class="col-md-6">
                            <ul class="feature-list">
                                <li><i class="bi bi-check-circle-fill"></i> Transformasi Digital Terintegrasi</li>
                                <li><i class="bi bi-check-circle-fill"></i> Pengembangan Website Profesional</li>
                                <li><i class="bi bi-check-circle-fill"></i> Mobile App Berkualitas</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="feature-list">
                                <li><i class="bi bi-check-circle-fill"></i> Solusi Khusus untuk semua sektor</li>
                                <li><i class="bi bi-check-circle-fill"></i> Inovasi Teknologi Terdepan</li>
                                <li><i class="bi bi-check-circle-fill"></i> Layanan Pelanggan 24/7</li>
                            </ul>
                        </div>
                    </div>

                    <div class="info-wrapper">
                        <div class="row gy-4">
                            <div class="col-lg-5">
                                <div class="profile d-flex align-items-center gap-3">
                                    <img src="{{url('/')}}/assets-front/img/avatar-1.webp" alt="CEO Profile" class="profile-image">
                                    <div>
                                        <h4 class="profile-name">Alhilal Anwar</h4>
                                        <p class="profile-position">CEO &amp; Founder</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="contact-info d-flex align-items-center gap-2">
                                    <i class="bi bi-telephone-fill"></i>
                                    <div>
                                        <p class="contact-label">Kontak Kami</p>
                                        <p class="contact-label fw-bold">garudadigitalnusantara@gmail.com</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="image-wrapper">
                        <div class="images position-relative" data-aos="zoom-out" data-aos-delay="400">
                            <img src="{{url('/')}}/assets-front/img/about-5.webp" alt="Digital Transformation" class="img-fluid main-image rounded-4">
                            <img src="{{url('/')}}/assets-front/img/about-2.webp" alt="Team Collaboration" class="img-fluid small-image rounded-4">
                        </div>
                        <div class="experience-badge floating">
                            <h3>15+ <span>Years</span></h3>
                            <p>Pengalaman dalam Digitalisasi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>