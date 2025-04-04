<?php

use App\Models\Post;
use Livewire\Volt\Component;

new class extends Component {
    public Post $blog;
    public $relatedPosts;
    
    public function mount(string $slug): void
    {
        $this->blog = Post::where('slug', $slug)->firstOrFail();

        $this->relatedPosts = Post::where('id', '!=', $this->blog->id)
            ->when($this->blog->category, function($query) {
                return $query->where('category_id', $this->blog->category_id);
            })
            ->limit(3)
            ->latest()
            ->get();
    }
}; ?>

<div>
    <section class="blog-detail section">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row">
                <div class="col-lg-12 mx-auto">
                    <div class="post-meta mb-3">
                        <span class="date">{{ $blog->created_at->format('d M Y') }}</span>
                        @if($blog->category)
                            <span class="mx-2">|</span>
                            <span class="category">{{ $blog->category->name ?? 'Uncategorized' }}</span>
                        @endif
                    </div>
                    <h1 class="blog-title mb-4">{{ $blog->title }}</h1>
                    <div class="author d-flex align-items-center mb-4">
                        <div class="author-img me-3">
                            <img src="{{ $blog->author->profile_photo ?? url('/assets-front/img/avatar-1.webp') }}" alt="Author" class="rounded-circle" width="50">
                        </div>
                        <div class="author-info">
                            <h6 class="mb-0">{{ $blog->author->name ?? 'Admin' }}</h6>
                            <small>{{ $blog->author->position ?? 'Content Writer' }}</small>
                        </div>
                    </div>
                </div>
            </div>

            @if($blog->featured_image)
            <div class="row">
                <div class="col-lg-12 mx-auto">
                    <div class="featured-image" data-aos="zoom-out" data-aos-delay="200">
                        <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="{{ $blog->title }}" class="img-fluid rounded">
                    </div>
                </div>
            </div>
            @endif

            <div class="row">
                <div class="col-lg-12 mx-auto">
                    <div class="blog-content mb-5 text-justify" data-aos="fade-up" data-aos-delay="300" style="text-align: justify;">
                        {!! $blog->content !!}
                    </div>

                    @if($blog->tags && count($blog->tags) > 0)
                    <div class="blog-tags mb-5">
                        <h5 class="mb-3">Tags</h5>
                        <div class="tags">
                            @foreach($blog->tags as $tag)
                                <a href="#" class="badge bg-light text-dark me-2 mb-2 py-2 px-3">{{ $tag->name }}</a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="blog-share mb-3">
                        <h5 class="mb-3">Bagikan Artikel</h5>
                        <div class="social-share">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" class="btn btn-outline-primary me-2 mb-2" target="_blank">
                                <i class="bi bi-facebook me-1"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ $blog->title }}" class="btn btn-outline-info me-2 mb-2" target="_blank">
                                <i class="bi bi-twitter me-1"></i> Twitter
                            </a>
                            <a href="https://wa.me/?text={{ $blog->title }} {{ url()->current() }}" class="btn btn-outline-success me-2 mb-2" target="_blank">
                                <i class="bi bi-whatsapp me-1"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            @if($relatedPosts->count() > 0)
            <div class="related-posts mt-3 pt-3 border-top">
                <div class="row">
                    <div class="col-12">
                        <h3 class="mb-4">Artikel Terkait</h3>
                    </div>
                    @foreach($relatedPosts as $post)
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="{{ 100 * $loop->iteration }}">
                        <div class="card border-0 shadow-sm h-100">
                            @if($post->featured_image)
                            <img src="{{ asset('storage/' . $post->featured_image) }}" class="card-img-top" alt="{{ $post->title }}">
                            @endif
                            <div class="card-body">
                                <div class="post-meta small mb-2">
                                    <span class="date">{{ $post->created_at->format('d M Y') }}</span>
                                </div>
                                <h5 class="card-title">{{ $post->title }}</h5>
                                <p class="card-text">{{ \Illuminate\Support\Str::limit(strip_tags($post->content), 100) }}</p>
                                <a href="{{ route('user.blog', $post->slug) }}" class="btn btn-link p-0">Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </section>
</div>
