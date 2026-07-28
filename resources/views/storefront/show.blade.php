@extends('storefront.layout', ['title' => $product->display_name . ' - Azure Luxury', 'cartCount' => $cartCount])

@section('content')
    <div class="breadcrumb" style="margin-bottom:18px">
        <a class="muted" href="{{ route('storefront.index') }}">Home</a>
        <span class="muted">›</span>
        <span>{{ $product->display_name }}</span>
    </div>

    <section class="detail-layout">
        <div class="surface" style="padding:18px">
            <div class="detail-gallery">
                <div class="thumb-list">
                    @foreach(array_slice($product->gallery, 0, 2) as $index => $image)
                        <button class="thumb-button @if($index === 0) active @endif" type="button" data-gallery-thumb="{{ $image }}">
                            <img src="{{ $image }}" alt="{{ $product->display_name }}">
                        </button>
                    @endforeach
                </div>
                <div class="main-visual">
                    <img id="mainProductImage" src="{{ $product->gallery[0] ?? $product->image_url }}" alt="{{ $product->display_name }}">
                </div>
            </div>
        </div>

        <div class="detail-panel surface">
            <span class="eyebrow">{{ $product->tag_no }}</span>
            <h1 class="display-title" style="font-size:58px">{{ $product->display_name }}</h1>
            <div class="detail-info-line">
                <div class="muted">SKU: {{ $product->tag_no }}</div>
            </div>
            @if(filled($product->display_description))
                <p class="muted" style="margin-top:16px">{{ $product->display_description }}</p>
            @endif
            <div class="detail-price">{{ number_format($product->display_price, 0) }} <span style="font-size:24px">QAR</span></div>

            @php($stockQty = (int) ($product->stock_quantity ?? 0))
            <div class="detail-info-line" style="margin-top:14px">
                @if($stockQty > 0)
                    <span class="stock-pill in">{{ __('app.in_stock') ?? 'In stock' }}: {{ $stockQty }}</span>
                @else
                    <span class="stock-pill out">{{ __('app.out_of_stock') ?? 'Out of stock' }}</span>
                @endif
            </div>

            <form method="post" action="{{ route('storefront.cart.add', $product->id) }}" style="display:grid;gap:14px;margin-top:18px">
                @csrf

                @if($product->sizes_enabled)
                    <div>
                        <label for="size" style="display:block;font-weight:700;margin-bottom:10px">{{ __('app.size') ?? 'Size' }}</label>
                        @if(count($product->size_options) > 0)
                            <select id="size" name="size" class="note-box" style="min-height:auto" required>
                                @foreach($product->size_options as $sizeOption)
                                    <option value="{{ $sizeOption }}">{{ $sizeOption }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="hidden" name="size" value="Free Size">
                            <div class="stock-pill in">{{ __('app.free_size') ?? 'Free Size' }}</div>
                        @endif
                    </div>
                @endif

                <div>
                    <label for="product-note" style="display:block;font-weight:700;margin-bottom:10px">Notes</label>
                    <textarea id="product-note" name="note" class="note-box" placeholder="Enter your note here..."></textarea>
                </div>

                <div class="detail-info-line">
                    <label for="quantity" style="font-weight:700">Quantity</label>
                    <input id="quantity" name="quantity" type="number" min="1" value="1" class="qty-input" @if($stockQty > 0) max="{{ $stockQty }}" @endif>
                </div>
                <button class="gold-btn" type="submit" style="width:100%;padding:18px 20px" @if($stockQty <= 0) disabled @endif>
                    {{ $stockQty > 0 ? 'Add To Cart' : (__('app.out_of_stock') ?? 'Out of stock') }}
                </button>
                <a class="ghost-btn" href="{{ route('storefront.cart') }}" style="text-align:center">Open Cart</a>
            </form>
        </div>
    </section>

    <section class="section-block">
        <div class="section-head">
            <div>
                <span class="eyebrow">You May Also Like</span>
                <h2 class="section-title">Related Products</h2>
            </div>
        </div>
        <div class="related-grid">
            @foreach($related as $item)
                @include('storefront.partials.product-card', ['item' => $item])
            @endforeach
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.querySelectorAll('[data-gallery-thumb]').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('mainProductImage').src = this.getAttribute('data-gallery-thumb');
                document.querySelectorAll('[data-gallery-thumb]').forEach(function (thumb) {
                    thumb.classList.remove('active');
                });
                this.classList.add('active');
            });
        });
    </script>
@endsection
