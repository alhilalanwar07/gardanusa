<?php

use Livewire\Volt\Component;

new class extends Component {
    public function with(): array
    {
        return [
            'posts' => \App\Models\Post::latest()->take(2)->get(),
        ];
    }
}; ?>

<div>
    <section id="blog" class="blog-cards section" style="padding: 40px 0px;">

        <div class="container section-title pb-4 pt-0" data-aos="fade-up" data-aos-delay="100">
            <h2>Blog</h2>
            <p>
                Berita terkini seputar teknologi dan inovasi digital dari CV. Garuda Digital Nusantara untuk membantu Anda tetap terdepan.
            </p>
        </div>
        <div class="container mt-4">

            <div class="row gy-4 ">

                @foreach ($posts as $index => $post)
                    <div class="col-xl-6 col-md-6 " data-aos="zoom-in" data-aos-delay="{{ ($index + 1) * 100 }}">
                        <div class="feature-box red d-flex flex-column justify-content-between">
                            @if($post->featured_image)
                                <div class="post-image mb-3">
                                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="img-fluid rounded">
                                </div>
                            @endif
                            <h4>{{ $post->title }}</h4>
                            <p>{{ $post->excerpt }}</p>
                            <div class="mt-4 text-end">
                                <a href="#" class="read-more">Read More <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if(count($posts) == 0)
                    <div class="col-12 text-center">
                        <p>No blog posts available yet.</p>
                    </div>
                @endif

            </div>

        </div>

    </section>
</div>