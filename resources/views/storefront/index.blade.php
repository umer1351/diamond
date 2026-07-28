@extends('storefront.layout', ['title' => 'Azure Luxury Storefront', 'cartCount' => $cartCount])

@section('content')
    @php($useArabicCopy = app()->getLocale() === 'ar')
    @php($isYoutubeVideo = \Illuminate\Support\Str::contains((string) $heroVideoUrl, ['youtube.com/embed', 'youtu.be']))
    <section class="video-stage">
        <div class="video-frame">
            @if($isYoutubeVideo)
                <iframe src="{{ $heroVideoUrl }}" title="Jewellery video" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe>
            @else
                <video autoplay muted loop playsinline poster="{{ optional($cmsImages->get('hero_banner'))['image_url'] ?? ($newArrivals->first()->image_url ?? asset('assets/images/photo-wide-4.jpg')) }}">
                    <source src="{{ $heroVideoUrl }}" type="video/mp4">
                </video>
            @endif
        </div>

        <section class="home-category-strip" aria-label="{{ __('app.categories') }}">
            @forelse($categories->take(10) as $category)
                <a class="home-category-item" href="{{ route('storefront.categories', ['category' => $category->id]) }}">
                    <span class="home-category-thumb">
                        <img src="{{ $category->category_image_url }}" alt="{{ $category->name }}">
                    </span>
                    <span class="home-category-name">{{ $category->name }}</span>
                </a>
            @empty
                <div class="empty-state">No active categories found.</div>
            @endforelse
        </section>
    </section>

    <div class="toolbar @if(!empty($categoryBg)) has-bg @endif" id="catalog" @if(!empty($categoryBg)) style="background-image:url('{{ $categoryBg }}')" @endif>
        <div>
            <span class="eyebrow">{{ $useArabicCopy ? __('app.storefront_catalog') : (optional($cmsSections->get('catalog_banner'))->subtitle ?? __('app.storefront_catalog')) }}</span>
            <h2 class="section-title">{{ $useArabicCopy ? __('app.shop_by_category') : (optional($cmsSections->get('catalog_banner'))->title ?? __('app.shop_by_category')) }}</h2>
        </div>
        <form class="filter-form" method="get" action="{{ route('storefront.search') }}">
            <input class="filter-input" type="text" name="q" value="{{ $searchTerm ?? request('q') }}" placeholder="{{ __('app.search_placeholder') }}">
            <select class="filter-select" name="product_id">
                <option value="">{{ __('app.all_categories') }}</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) request('product_id') === (string) $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <button class="gold-btn" type="submit">{{ __('app.filter') }}</button>
        </form>
    </div>

    {{-- One section per category: banner + a single row of 4 products + a "More" link. --}}
    @forelse($homeCategories as $category)
        <section class="section-block category-section">
            <div class="promo-banner category-banner">
                <img src="{{ $category->category_image_url }}" alt="{{ $category->name }}">
                <div class="promo-copy">
                    <span class="eyebrow">{{ __('app.shop_by_category') }}</span>
                    <h2>{{ $category->name }}</h2>
                    <a class="gold-btn" href="{{ route('storefront.categories', ['category' => $category->id]) }}" style="width:max-content">{{ __('app.view_all') ?? 'View All' }}</a>
                </div>
            </div>
            <div class="product-grid">
                @foreach($category->preview_products as $item)
                    @include('storefront.partials.product-card', ['item' => $item])
                @endforeach
            </div>
            <div class="section-more">
                <a class="ghost-btn" href="{{ route('storefront.categories', ['category' => $category->id]) }}">
                    {{ __('app.more') ?? 'More' }} {{ $category->name }} →
                </a>
            </div>
        </section>
    @empty
        <section class="section-block">
            <div class="empty-state">No active categories found.</div>
        </section>
    @endforelse

    {{-- Socials: follow us on Instagram, Facebook, TikTok and Snapchat. --}}
    <section class="section-block" id="socials">
        <div class="section-head">
            <div>
                <span class="eyebrow">{{ __('app.follow_us') ?? 'Follow Us' }}</span>
                <h2 class="section-title">{{ __('app.our_socials') ?? 'Our Socials' }}</h2>
            </div>
        </div>
        <div class="socials-grid">
            <a class="social-tile" href="{{ $cmsSetting->instagram_url ?: '#' }}" @if($cmsSetting->instagram_url) target="_blank" rel="noopener" @endif>
                <span class="social-ico"><i class="fa fa-instagram" aria-hidden="true"></i></span>
                <strong>Instagram</strong>
                <span>Reels, posts & stories</span>
            </a>
            <a class="social-tile" href="{{ $cmsSetting->facebook_url ?: '#' }}" @if($cmsSetting->facebook_url) target="_blank" rel="noopener" @endif>
                <span class="social-ico"><i class="fa fa-facebook" aria-hidden="true"></i></span>
                <strong>Facebook</strong>
                <span>Latest updates</span>
            </a>
            <a class="social-tile" href="{{ ($cmsSetting->tiktok_url ?? null) ?: '#' }}" @if($cmsSetting->tiktok_url ?? null) target="_blank" rel="noopener" @endif>
                <span class="social-ico"><i class="fa fa-play" aria-hidden="true"></i></span>
                <strong>TikTok</strong>
                <span>Watch our videos</span>
            </a>
            <a class="social-tile" href="{{ ($cmsSetting->snapchat_url ?? null) ?: '#' }}" @if($cmsSetting->snapchat_url ?? null) target="_blank" rel="noopener" @endif>
                <span class="social-ico"><i class="fa fa-snapchat-ghost" aria-hidden="true"></i></span>
                <strong>Snapchat</strong>
                <span>Behind the scenes</span>
            </a>
        </div>
    </section>

    <section class="section-block">
        <div class="feature-strip">
            <div class="feature-card">
                <div class="feature-icon">↗</div>
                <div>
                    <strong>{{ optional($cmsSections->get('feature_quality'))->title ?? 'Elegant quality' }}</strong>
                    <p class="muted" style="margin:6px 0 0">Clean premium cards and detail pages for every live product.</p>
                </div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">◌</div>
                <div>
                    <strong>{{ optional($cmsSections->get('feature_secure'))->title ?? 'Secure shopping' }}</strong>
                    <p class="muted" style="margin:6px 0 0">Session-based cart and checkout flow with validation.</p>
                </div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">✦</div>
                <div>
                    <strong>{{ optional($cmsSections->get('feature_gift'))->title ?? 'Gift-ready layout' }}</strong>
                    <p class="muted" style="margin:6px 0 0">Reference-inspired sections for home, product and checkout views.</p>
                </div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⟡</div>
                <div>
                    <strong>{{ optional($cmsSections->get('feature_backend'))->title ?? 'Backend linked' }}</strong>
                    <p class="muted" style="margin:6px 0 0">New records in `finish_products` automatically appear here.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
