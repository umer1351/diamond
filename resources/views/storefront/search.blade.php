@extends('storefront.layout', ['title' => 'Search - Azure Luxury', 'cartCount' => $cartCount])

@section('content')
    <div class="breadcrumb" style="margin-bottom:18px">
        <a class="muted" href="{{ route('storefront.index') }}">Home</a>
        <span class="muted">›</span>
        <span>Search</span>
    </div>

    <section class="section-block">
        <div class="section-head">
            <div>
                <span class="eyebrow">Search</span>
                <h2 class="section-title">Results for "{{ $term ?: 'All Products' }}"</h2>
            </div>
        </div>

        <form class="filter-form" method="get" action="{{ route('storefront.search') }}">
            <input class="filter-input" type="text" name="q" value="{{ $term }}" placeholder="Search by tag, design no, or product">
            <button class="gold-btn" type="submit">Search</button>
        </form>
    </section>

    <section class="section-block">
        <div class="product-grid">
            @forelse($results as $item)
                @include('storefront.partials.product-card', ['item' => $item])
            @empty
                <div class="empty-state" style="grid-column:1/-1">No matching products found.</div>
            @endforelse
        </div>
        <div class="pager">{{ $results->links('vendor.pagination.storefront') }}</div>
    </section>
@endsection
