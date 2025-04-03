<?php

use Livewire\Volt\Component;
use App\Models\Product;

new class extends Component {
    public function with(): array
    {
        return [
            'products' => Product::orderBy('created_at', 'desc')->get()
        ];
    }
}; ?>

<div>
    <section id="produk" class="features section" >
        <div class="container section-title pb-4" data-aos="fade-up">
            <h2>Produk</h2>
            <p>Produk unggulan kami dengan kualitas terbaik untuk memenuhi kebutuhan Anda</p>
        </div>

        <div class="container " data-aos="fade-up" data-aos-delay="100">

            <div class="d-flex justify-content-center mb-3">
                <ul class="nav nav-tabs" data-aos="fade-up" data-aos-delay="100">
                    @foreach($products as $index => $product)
                    <li class="nav-item">
                        <a class="nav-link {{ $index === 0 ? 'active show' : '' }}" data-bs-toggle="tab" data-bs-target="#features-tab-{{ $index + 1 }}">
                            <h4>{{ $product->title }}</h4>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="tab-content" data-aos="fade-up" data-aos-delay="200">
                @foreach($products as $index => $product)
                <div class="tab-pane fade {{ $index === 0 ? 'active show' : '' }}" id="features-tab-{{ $index + 1 }}">
                    <div class="row">
                        <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0 d-flex flex-column justify-content-between">
                            <div>
                                <h3>{{ $product->title }}</h3>
                                <div class="fst-italic text-justify" style="text-align: justify;">
                                    {{ $product->desc }}
                                </div>
                            </div>
                            @if($product->link)
                            <div class="mt-3">
                                <a href="{{ $product->link }}" class="btn-learn-more" target="_blank">Lihat Detail <i class="bi bi-arrow-right"></i></a>
                            </div>
                            @endif
                        </div>
                        <div class="col-lg-6 order-1 order-lg-2 text-center">
                            @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" class="img-fluid">
                            @else
                            <img src="{{url('/')}}/assets-front/img/features-illustration-1.webp" alt="Default Image" class="img-fluid">
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>

    </section>
</div>