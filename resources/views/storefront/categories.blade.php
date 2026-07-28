@extends('storefront.layout', ['title' => 'Categories - Azure Luxury', 'cartCount' => $cartCount])

@section('content')
    <div class="breadcrumb" style="margin-bottom:18px">
        <a class="muted" href="{{ route('storefront.index') }}">Home</a>
        <span class="muted">›</span>
        <span>Categories</span>
    </div>

    <section class="section-block">
        <div class="section-head">
            <div>
                <span class="eyebrow">Categories</span>
                <h2 class="section-title">Shop by Category</h2>
            </div>
        </div>
        <div class="category-strip">
            @foreach($categories as $category)
                <a class="category-card" href="{{ route('storefront.categories', ['category' => $category->id]) }}">
                    <img src="{{ $category->category_image_url }}" alt="{{ $category->name }}">
                    <span>{{ $category->name }} @if($category->finish_products_count) ({{ $category->finish_products_count }}) @endif</span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="section-block">
        <div class="section-head">
            <div>
                <span class="eyebrow">Products</span>
                <h2 class="section-title">{{ $selectedCategory ? $selectedCategory->name : 'All Products' }}</h2>
            </div>
        </div>
        <div class="product-grid">
            @forelse($products as $item)
                @include('storefront.partials.product-card', ['item' => $item])
            @empty
                <div class="empty-state" style="grid-column:1/-1">No products available for this category.</div>
            @endforelse
        </div>
        <div class="pager">{{ $products->links('vendor.pagination.storefront') }}</div>
    </section>
@endsection
