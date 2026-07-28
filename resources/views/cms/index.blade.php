@extends('layouts.master')
@section('content')
<div class="main-content pt-4">
    <div class="breadcrumb">
        <h1>CMS Dashboard</h1>
        <ul><li>Home</li><li>CMS</li></ul>
    </div>
    <div class="separator-breadcrumb border-top"></div>

    <div class="row">
        <div class="col-md-3"><a class="card p-3 mb-3" href="{{ route('cms.settings') }}"><strong>Site Settings</strong><div class="text-muted">Logo text, footer, contacts</div></a></div>
        <div class="col-md-3"><a class="card p-3 mb-3" href="{{ route('cms.homepage') }}"><strong>Homepage Banners</strong><div class="text-muted">Hero, promo, gift sections</div></a></div>
        <div class="col-md-3"><a class="card p-3 mb-3" href="{{ route('cms.pages') }}"><strong>CMS Pages</strong><div class="text-muted">About, privacy, terms</div></a></div>
        <div class="col-md-3"><a class="card p-3 mb-3" href="{{ route('cms.catalog') }}"><strong>Catalog Sources</strong><div class="text-muted">Products & categories</div></a></div>
    </div>
</div>
@endsection
