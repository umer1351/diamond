@extends('storefront.layout', ['title' => ($page->meta_title ?: $page->title) . ' - Azure Luxury', 'cartCount' => $cartCount, 'cmsSetting' => $cmsSetting])

@section('content')
    <section class="surface" style="padding:34px;max-width:980px;margin:0 auto">
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
