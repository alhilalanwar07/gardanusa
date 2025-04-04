<?php

use App\Models\Post;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;
use Illuminate\Support\Str;
use Livewire\Volt\Component;

new class extends Component {
    public Post $blog;
    public $relatedPosts;

    public function mount(string $slug): void
    {
        // Load the main blog post with author and tags
        $this->blog = Post::with(['category', 'tags', 'user'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Set SEO meta tags
        $this->setSeoMeta();

        // Load related posts
        $this->relatedPosts = Post::query()
            ->where('id', '!=', $this->blog->id)
            ->where('status', 'published')
            ->latest()
            ->limit(3)
            ->get();
    }

    protected function setSeoMeta(): void
    {
        // Use meta_description if available, otherwise create from content
        $description = $this->blog->meta_description ?? Str::limit(strip_tags($this->blog->content), 160);
        $title = $this->blog->meta_title ?? $this->blog->title;

        // Set canonical URL
        $canonicalUrl = route('user.blog', ['slug' => $this->blog->slug]);

        // Featured image full URL
        $imageUrl = $this->blog->featured_image
            ? asset('storage/' . $this->blog->featured_image)
            : null;

        // Get tags as array
        $tags = $this->blog->tags->pluck('name')->toArray();

        // Basic SEO meta tags
        SEOMeta::setTitle($title)
            ->setDescription($description)
            ->setCanonical($canonicalUrl)
            ->addMeta('article:published_time', $this->blog->published_at->toW3CString())
            ->addMeta('article:modified_time', $this->blog->updated_at->toW3CString())
            ->addMeta('article:section', $this->blog->category->name ?? 'Uncategorized');

        SEOMeta::addMeta('og:image', $imageUrl);
        SEOMeta::addMeta('og:image:width', '1200');
        SEOMeta::addMeta('og:image:height', '630');

        if (!empty($tags)) {
            SEOMeta::addKeyword($tags);
        }

        // Open Graph - for Facebook, LinkedIn, etc.
        OpenGraph::setTitle($title)
            ->setDescription($description)
            ->setType('article')
            ->setUrl($canonicalUrl)
            ->setSiteName(config('app.name'))
            ->setArticle([
                'published_time' => $this->blog->published_at->toW3CString(),
                'modified_time' => $this->blog->updated_at->toW3CString(),
                'section' => $this->blog->category->name ?? 'Uncategorized',
                'tag' => $tags
            ]);

        if ($imageUrl) {
            // Adding multiple image sizes for better compatibility
            OpenGraph::addImage([
                'url' => $imageUrl,
                'width' => 1200,
                'height' => 630
            ]);
        }

        // Twitter Card
        TwitterCard::setType('summary_large_image')
            ->setTitle($title)
            ->setDescription($description)
            ->setUrl($canonicalUrl);

        if ($imageUrl) {
            TwitterCard::setImage($imageUrl);
        }

        // JSON-LD Schema.org structured data
        JsonLd::setType('Article')
            ->setTitle($title)
            ->setDescription($description)
            ->setUrl($canonicalUrl)
            ->addValue('datePublished', $this->blog->published_at->toW3CString())
            ->addValue('dateModified', $this->blog->updated_at->toW3CString())
            ->addValue('author', [
                '@type' => 'Person',
                'name' => $this->blog->user->name ?? 'Admin'
            ])
            ->addValue('publisher', [
                '@type' => 'Organization',
                'name' => config('app.name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('assets-front/img/logo.png')
                ]
            ]);

        if ($imageUrl) {
            JsonLd::addImage($imageUrl);
        }
    }
}; ?>

<div>
    @push('meta')
    <!-- Meta Tags Khusus Facebook/WhatsApp -->
    @if($blog->featured_image)
    <meta property="og:image" content="{{ asset('storage/' . $blog->featured_image) }}" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:alt" content="{{ $blog->title }}" />
    @endif
    @endpush
    <section class="blog-detail section">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row">
                <div class="col-lg-12 mx-auto">
                    <div class="post-meta mb-3">
                        <span class="date">{{ $blog->published_at->format('d M Y') }}</span>
                        @if($blog->category)
                        <span class="mx-2">|</span>
                        <span class="category">{{ $blog->category->name ?? 'Uncategorized' }}</span>
                        @endif
                    </div>
                    <h1 class="blog-title mb-4">{{ $blog->title }}</h1>
                    <div class="author d-flex align-items-center mb-4">
                        <div class="author-img me-3">
                            <img src="{{ $blog->user->profile_photo_url ?? url('/assets-front/img/avatar-1.webp') }}" alt="Author" class="rounded-circle" width="50">
                        </div>
                        <div class="author-info">
                            <h6 class="mb-0">{{ $blog->user->name ?? 'Admin' }}</h6>
                            <small>{{ $blog->user->position ?? 'Content Writer' }}</small>
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

            <div class="row mt-3">
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
                            @php
                            $shareUrl = urlencode(route('user.blog', ['slug' => $blog->slug]));
                            $shareTitle = urlencode($blog->title);
                            $shareImage = urlencode(asset('storage/' . $blog->featured_image));
                            @endphp

                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" class="btn btn-outline-primary me-2 mb-2" target="_blank" rel="noopener">
                                <i class="bi bi-facebook me-1"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}" class="btn btn-outline-info me-2 mb-2" target="_blank" rel="noopener">
                                <i class="bi bi-twitter me-1"></i> Twitter
                            </a>
                            <a href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}" class="btn btn-outline-success me-2 mb-2" target="_blank" rel="noopener">
                                <i class="bi bi-whatsapp me-1"></i> WhatsApp
                            </a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ $shareUrl }}&title={{ $shareTitle }}" class="btn btn-outline-primary me-2 mb-2" target="_blank" rel="noopener">
                                <i class="bi bi-linkedin me-1"></i> LinkedIn
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if($relatedPosts->count() > 0)
            <div class="related-posts mt-3 pt-3 border-top">
                <div class="row">
                    <div class="col-12">
                        <h3 class="mb-4">Artikel Terbaru</h3>
                    </div>
                    @foreach($relatedPosts as $post)
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="{{ 100 * $loop->iteration }}">
                        <div class="card border-0 shadow-sm h-100">
                            @if($post->featured_image)
                            <a href="{{ route('user.blog', $post->slug) }}">
                                <img src="{{ asset('storage/' . $post->featured_image) }}" class="card-img-top" alt="{{ $post->title }}">
                            </a>
                            @endif
                            <div class="card-body">
                                <div class="post-meta small mb-2">
                                    <span class="date">{{ $post->published_at->format('d M Y') }}</span>
                                    @if($post->category)
                                    <span class="mx-1">|</span>
                                    <span class="category">{{ $post->category->name }}</span>
                                    @endif
                                </div>
                                <h5 class="card-title">{{ $post->title }}</h5>
                                <p class="card-text">{{ $post->excerpt ?: Str::limit(strip_tags($post->content), 100) }}</p>
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