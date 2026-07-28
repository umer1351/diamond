<article class="product-card">
    <a href="{{ route('storefront.show', $item->id) }}" class="product-thumb">
        <img src="{{ $item->image_url }}" alt="{{ $item->display_name }}">
        <img class="hover-image" src="{{ $item->hover_image_url }}" alt="{{ $item->display_name }}">
        <span class="wishlist-badge">♡</span>
        <span class="product-thumb-caption">
            <span class="thumb-name">{{ $item->display_name }}</span>
            <span class="thumb-price">{{ number_format($item->display_price, 0) }} QAR</span>
        </span>
    </a>
    <div class="product-body">
        <h3><a href="{{ route('storefront.show', $item->id) }}">{{ $item->display_name }}</a></h3>
        <div class="product-meta">
            <span>{{ $item->design_no ?: $item->tag_no }}</span>
            <span>{{ number_format($item->display_weight, 3) }} g</span>
        </div>
        <div class="product-meta" style="margin-top:8px">
            <span>Stock: {{ (int) ($item->stock_quantity ?? $item->stock_level ?? 0) }}</span>
            <span>{{ $item->product->name ?? 'Category' }}</span>
        </div>
        <div class="price-row">
            <strong>{{ number_format($item->display_price, 0) }}</strong>
            <span>QAR</span>
        </div>
        <div class="product-actions">
            <form method="post" action="{{ route('storefront.cart.add', $item->id) }}">
                @csrf
                <button class="gold-btn" type="submit" style="flex:1">{{ __('app.add_to_cart') }}</button>
                <a class="ghost-btn" href="{{ route('storefront.show', $item->id) }}">{{ __('app.view') }}</a>
            </form>
        </div>
    </div>
</article>
