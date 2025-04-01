<?php

use Livewire\Volt\Component;

new class extends Component {
    public function with(): array
    {
        return [
            'clients' => \App\Models\Client::orderBy('created_at', 'desc')->get(),
            'clientCount' => \App\Models\Client::count(),
            'additionalNeeded' => \App\Models\Client::count() < 12 ? 12 - \App\Models\Client::count() : 0,
        ];
    }
}; ?>

<div>
    <section id="clients" class="clients section">

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="swiper init-swiper">
                <script type="application/json" class="swiper-config">
                    {
                        "loop": true,
                        "speed": 600,
                        "autoplay": {
                            "delay": 5000
                        },
                        "slidesPerView": "auto",
                        "pagination": {
                            "el": ".swiper-pagination",
                            "type": "bullets",
                            "clickable": true
                        },
                        "breakpoints": {
                            "320": {
                                "slidesPerView": 2,
                                "spaceBetween": 40
                            },
                            "480": {
                                "slidesPerView": 3,
                                "spaceBetween": 60
                            },
                            "640": {
                                "slidesPerView": 4,
                                "spaceBetween": 80
                            },
                            "992": {
                                "slidesPerView": 6,
                                "spaceBetween": 120
                            }
                        }
                    }
                </script>
                <div class="swiper-wrapper align-items-center">
                   

                    @foreach($clients as $client)
                        <div class="swiper-slide">
                            <img src="{{ asset('storage/' . $client->logo) }}" class="img-fluid" alt="{{ $client->name }}">
                        </div>
                    @endforeach

                    @for($i = 0; $i < $additionalNeeded; $i++)
                        <div class="swiper-slide">
                            <img src="{{ asset('storage/' . $clients[($i % $clientCount)]->logo) }}" class="img-fluid" alt="{{ $clients[($i % $clientCount)]->name }}">
                        </div>
                    @endfor
                </div>
                <div class="swiper-pagination"></div>
            </div>

        </div>

    </section>
</div>