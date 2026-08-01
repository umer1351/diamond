@extends('storefront.layout', ['title' => 'Contact Us - Azure Luxury', 'cartCount' => $cartCount])

@section('content')
    <div class="breadcrumb" style="margin-bottom:18px">
        <a class="muted" href="{{ route('storefront.index') }}">Home</a>
        <span class="muted">›</span>
        <span>Contact Us</span>
    </div>

    <section class="checkout-layout">
        <div class="checkout-card">
            <div class="section-head">
                <div>
                    <span class="eyebrow">Contact</span>
                    <h2 class="section-title">Get in Touch</h2>
                </div>
            </div>
            <p class="section-copy">{{ $cmsSetting->footer_about ?? 'Reach out to our team for orders, custom requests, or support.' }}</p>
            <div class="footer-links" style="margin-top:18px">
                <div><span class="inline-icon">✉</span>{{ $cmsSetting->footer_email ?? 'info@azure-fashion.com' }}</div>
                <div><span class="inline-icon">☎</span>{{ $cmsSetting->footer_phone ?? '+974 72 23 23 24' }}</div>
                <div><span class="inline-icon">⌂</span>{{ $cmsSetting->footer_address ?? 'Qatar' }}</div>
            </div>
        </div>

        <aside class="summary-card">
            <h3 class="section-title" style="font-size:34px">Support Pages</h3>
            <div class="footer-links">
                <a href="{{ route('storefront.page', 'about-us') }}">About Us</a>
                <a href="{{ route('storefront.page', 'privacy-policy') }}">Privacy and Security</a>
                <a href="{{ route('storefront.page', 'shipping-returns') }}">Shipping & returns</a>
                <a href="{{ route('storefront.page', 'terms-conditions') }}">Terms and Conditions</a>
            </div>
        </aside>
    </section>
@endsection
