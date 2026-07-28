@extends('layouts.master')
@section('content')
<div class="main-content pt-4">
    <div class="breadcrumb"><h1>Catalog Sources</h1></div>
    <div class="row">
        <div class="col-md-6">
            <div class="card p-4 mb-4">
                <h5>Manage Products</h5>
                <p class="text-muted">Frontend products come from backend `finish_products` and product master data.</p>
                <a class="btn btn-primary" href="{{ url('finish-product') }}">Go to Products</a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-4 mb-4">
                <h5>Manage Categories</h5>
                <p class="text-muted">Frontend categories come from product master categories.</p>
                <a class="btn btn-primary" href="{{ url('product-categories') }}">Go to Categories</a>
            </div>
        </div>
    </div>
</div>
@endsection
