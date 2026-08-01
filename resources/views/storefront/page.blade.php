@extends('storefront.layout', ['title' => ($page->meta_title ?: $page->title) . ' - Azure Luxury', 'cartCount' => $cartCount, 'cmsSetting' => $cmsSetting])

@section('content')
    <div class="breadcrumb" style="margin-bottom:18px">
        <a class="muted" href="{{ route('storefront.index') }}">Home</a>
        <span class="muted">›</span>
        <span>{{ $page->title }}</span>
    </div>
    <section class="surface" style="padding:34px;max-width:980px;margin:0 auto">
        <a class="ghost-btn" href="{{ route('storefront.index') }}" style="display:inline-flex;align-items:center;gap:8px;margin-bottom:18px;width:max-content"><i class="fa fa-arrow-left" aria-hidden="true"></i> {{ __('app.home') ?? 'Home' }}</a>
        <br>
        <span class="eyebrow">CMS Page</span>
        <h1 class="display-title" style="font-size:56px;margin-top:10px">{{ $page->title }}</h1>
        @if($page->meta_description)
            <p class="muted">{{ $page->meta_description }}</p>
        @endif
        <div style="margin-top:24px;line-height:1.9">
            {!! nl2br(e($page->body ?? '')) !!}
        </div>
    </section>
@endsection
