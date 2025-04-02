<?php

use App\Models\Testimonial;
use Livewire\Volt\Component;

new class extends Component {
    public function with(): array
    {
        return [
            'testimonials' => Testimonial::with(['client', 'project'])
                ->orderBy('created_at', 'desc')
                ->get(),
            'testimonialCount' => Testimonial::count(),
            'additionalNeeded' => Testimonial::count() < 4 ? 8 - Testimonial::count() : 0,
        ];
    }
}; ?>

<div>
    <section id="testimoni" class="testimonials section light-background">
        <div class="container section-title" data-aos="fade-up">
            <h2>Testimoni</h2>
            <p>
                Apa kata mereka tentang kami? <br>
                Berikut beberapa testimoni dari klien yang puas dengan layanan kami.
            </p>
        </div>
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="swiper init-swiper">
                <script type="application/json" class="swiper-config">
                    {
                        "loop": true,
                        "speed": 600,
                        "autoplay": {
                            "delay": 5000
                        },
                        "slidesPerView": 1,
                        "pagination": {
                            "el": ".swiper-pagination",
                            "type": "bullets",
                            "clickable": true
                        }
                    }
                </script>
                <div class="swiper-wrapper">
                    @php
                        $allTestimonials = $testimonials->toArray();
                        for($i = 0; $i < $additionalNeeded; $i++) {
                            $allTestimonials[] = $testimonials[($i % $testimonialCount)]->toArray();
                        }
                        $chunks = array_chunk($allTestimonials, 4);
                    @endphp

                    @foreach($chunks as $chunk)
                        <div class="swiper-slide">
                            <div class="testimonial-grid">
                                @foreach($chunk as $testimonial)
                                    <div class="testimonial-item">
                                        <img src="{{ asset('storage/' . $testimonial['image']) }}" class="testimonial-img" alt="{{ $testimonial['name'] }}">
                                        <h3>{{ $testimonial['name'] }}</h3>
                                        <h4>{{ $testimonial['position'] }} - {{ $testimonial['company'] }}</h4>
                                        <div class="stars">
                                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                        </div>
                                        <p class="text-justify" style="text-align: justify;">
                                            <i class="bi bi-quote quote-icon-left"></i>
                                            <span>{{ $testimonial['content'] }}</span>
                                            <i class="bi bi-quote quote-icon-right"></i>
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                <br>
                <div class="swiper-pagination"></div>
            </div>
        </div>

        <style>
            .testimonial-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                grid-template-rows: repeat(2, 1fr);
                gap: 20px;
                padding: 20px;
            }
            
            @media (max-width: 768px) {
                .testimonial-grid {
                    grid-template-columns: 1fr;
                    grid-template-rows: repeat(4, 1fr);
                }
            }
        </style>
    </section>
</div>
