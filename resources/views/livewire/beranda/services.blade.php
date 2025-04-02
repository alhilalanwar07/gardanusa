<?php

use Livewire\Volt\Component;
use App\Models\Service;

new class extends Component {
    public function with(): array
    {
        return [
            'services' => Service::orderBy('created_at', 'desc')->get()
        ];
    }
}; ?>

<div>
    <section id="layanan" class="services section light-background">

        <div class="container section-title" data-aos="fade-up">
            <h2>Layanan</h2>
            <p>Kami menyediakan berbagai layanan profesional untuk memenuhi kebutuhan Anda</p>
        </div>

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row g-4">
                @foreach($services as $index => $service)
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                    <div class="service-card d-flex">
                        <div class="icon flex-shrink-0">
                            <i class="bi {{ $service->icon }}"></i>
                        </div>
                        <div >
                            <h3>{{ $service->title }}</h3>
                            <p class="text-justify" style="text-align: justify">{{ $service->description }}</p>
                            <a href="#" class="read-more">Read More <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>

    </section>
</div>
