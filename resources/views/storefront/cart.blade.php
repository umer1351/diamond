@extends('storefront.layout', ['title' => 'Cart - Azure Luxury', 'cartCount' => $cartCount])

@section('content')
    <div class="breadcrumb" style="margin-bottom:18px">
        <a class="muted" href="{{ route('storefront.index') }}">Home</a>
        <span class="muted">›</span>
        <span>Cart</span>
    </div>

    <section class="cart-layout">
        <div class="cart-card">
            <div class="section-head" style="margin-bottom:8px">
                <div>
                    <h2 class="section-title">Items in the cart ({{ $item_count }})</h2>
                </div>
                @if($items->isNotEmpty())
                    <form method="post" action="{{ route('storefront.cart.clear') }}">
                        @csrf
                        <button class="ghost-btn" type="submit">Clear Cart</button>
                    </form>
                @endif
            </div>

            @forelse($items as $item)
                <div class="cart-item">
                    <div class="cart-thumb">
                        <img src="{{ $item->image_url }}" alt="{{ $item->display_name }}">
                    </div>
                    <div>
                        <h3 style="margin:0 0 8px">{{ $item->display_name }}</h3>
                        <div class="muted" style="margin-bottom:10px">{{ $item->tag_no }} · {{ number_format($item->display_weight, 3) }} g</div>
                        @if(!empty($item->cart_size))
                            <div class="muted" style="margin-bottom:10px">{{ __('app.size') ?? 'Size' }}: <strong style="color:var(--text)">{{ $item->cart_size }}</strong></div>
                        @endif
                        <strong style="font-size:28px;color:var(--gold-deep)">{{ number_format($item->display_price, 0) }} QAR</strong>
                    </div>
                    <div class="qty-form">
                        <form method="post" action="{{ route('storefront.cart.update', $item->id) }}" class="qty-form">
                            @csrf
                            <input class="qty-input" type="number" min="1" name="quantity" value="{{ $item->cart_quantity }}">
                            <button class="gold-btn" type="submit">Update</button>
                        </form>
                        <form method="post" action="{{ route('storefront.cart.remove', $item->id) }}">
                            @csrf
                            <button class="ghost-btn" type="submit">Remove</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="empty-state">Your cart is empty right now. Browse the catalog and add a few pieces.</div>
            @endforelse
        </div>

        <aside class="summary-card">
            <h3 class="section-title" style="font-size:34px">Grand Total</h3>
            <div class="summary-line">
                <span>Subtotal</span>
                <span>{{ number_format($sub_total, 0) }} QAR</span>
            </div>
            <div class="summary-line">
                <span>Shipping</span>
                <span>{{ number_format($shipping, 0) }} QAR</span>
            </div>
            <div class="summary-line total">
                <span>Total</span>
                <span>{{ number_format($grand_total, 0) }} QAR</span>
            </div>

            <div style="display:grid;gap:12px;margin:24px 0 18px">
                <a class="gold-btn" href="{{ route('storefront.checkout') }}" style="text-align:center">Checkout</a>
                <a class="ghost-btn" href="{{ route('storefront.index') }}" style="text-align:center">Continue Shopping</a>
            </div>

            <label for="promo" style="display:block;font-weight:700;margin-bottom:10px">Got a promotional code?</label>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                <input id="promo" class="promo-input" type="text" placeholder="Enter a promo code here" style="flex:1">
                <button class="ghost-btn" type="button">Apply</button>
            </div>
            <p class="muted" style="margin:12px 0 0">Add your address during checkout to calculate shipping details.</p>
        </aside>
    </section>
@endsection
