@extends('storefront.layout', ['title' => 'Checkout - Azure Luxury', 'cartCount' => $cartCount, 'cmsSetting' => $cmsSetting])

@section('content')
    <div class="breadcrumb" style="margin-bottom:18px">
        <a class="muted" href="{{ route('storefront.index') }}">Home</a>
        <span class="muted">›</span>
        <a class="muted" href="{{ route('storefront.cart') }}">Cart</a>
        <span class="muted">›</span>
        <span>Checkout</span>
    </div>

    <section class="checkout-layout">
        <div class="checkout-card">
            <div class="section-head">
                <div>
                    <span class="eyebrow">Checkout</span>
                    <h2 class="section-title">Shipping Details</h2>
                </div>
            </div>

            @if($errors->has('paypal'))
                <div class="muted" style="margin-bottom:12px;color:#c0392b">{{ $errors->first('paypal') }}</div>
            @endif
            @if($errors->has('qpay'))
                <div class="muted" style="margin-bottom:12px;color:#c0392b">{{ $errors->first('qpay') }}</div>
            @endif

            <div class="muted" style="margin-bottom:16px">
                Enter your shipping details, then pay securely with PayPal.
            </div>

            <form method="post" action="{{ route('storefront.paypal.pay') }}" style="display:grid;gap:14px">
                @csrf
                <div class="checkout-fields">
                    <input class="field" name="customer_name" value="{{ old('customer_name') }}" placeholder="Full name" required>
                    <input class="field" name="phone" value="{{ old('phone') }}" placeholder="Phone number" required>
                    <input class="field" name="email" type="email" value="{{ old('email') }}" placeholder="Email address">
                    <input class="field" name="city" value="{{ old('city') }}" placeholder="City" required>
                    <textarea class="field full" name="address" placeholder="Address" rows="5" required>{{ old('address') }}</textarea>
                    <textarea class="field full" name="notes" placeholder="Order notes" rows="4">{{ old('notes') }}</textarea>
                </div>

                @if($paypalConfigured ?? false)
                    <button class="gold-btn" type="submit" style="padding:18px 20px">
                        Pay with PayPal
                    </button>
                    <div class="muted" style="font-size:12px;text-align:center">
                        Charged in {{ $paypalCurrency ?? 'USD' }} (converted from QAR) via PayPal's secure checkout.
                    </div>
                @else
                    <div class="muted" style="color:#c0392b">
                        PayPal is not configured yet. Add PAYPAL_CLIENT_ID and PAYPAL_SECRET in the .env file.
                    </div>
                @endif

                @if($qpayConfigured ?? false)
                    <button class="gold-btn" type="submit" formaction="{{ route('storefront.checkout.place') }}"
                        style="padding:14px 20px;background:transparent;border:1px solid rgba(0,0,0,.2);color:inherit">
                        Continue with QPay instead
                    </button>
                @endif
            </form>
        </div>

        <aside class="summary-card">
            <h3 class="section-title" style="font-size:34px">Order Summary</h3>
            @foreach($items as $item)
                <div class="summary-line">
                    <span>{{ $item->display_name }} x {{ $item->cart_quantity }}</span>
                    <span>{{ number_format($item->cart_total, 0) }} QAR</span>
                </div>
            @endforeach
            <div class="summary-line">
                <span>Subtotal</span>
                <span>{{ number_format($sub_total, 0) }} QAR</span>
            </div>
            <div class="summary-line total">
                <span>Grand Total</span>
                <span>{{ number_format($grand_total, 0) }} QAR</span>
            </div>
        </aside>
    </section>
@endsection
