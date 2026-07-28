@extends('layouts.master')
@section('content')
<div class="main-content pt-4">
    <div class="breadcrumb"><h1>CMS Pages</h1></div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header"><h5>{{ request('page_id') ? 'Edit Page' : 'Add Page' }}</h5></div>
                <form method="post" action="{{ route('cms.page.save') }}">
                    @csrf
                    <div class="card-body">
                        <input type="hidden" name="id" value="{{ old('id', $editingPage->id ?? '') }}">
                        <input class="form-control mb-2" name="slug" value="{{ old('slug', $editingPage->slug ?? '') }}" placeholder="about-us / privacy-policy / terms-conditions" required>
                        <input class="form-control mb-2" name="title" value="{{ old('title', $editingPage->title ?? '') }}" placeholder="Page Title" required>
                        <input class="form-control mb-2" name="meta_title" value="{{ old('meta_title', $editingPage->meta_title ?? '') }}" placeholder="Meta Title">
                        <textarea class="form-control mb-2" name="meta_description" placeholder="Meta Description">{{ old('meta_description', $editingPage->meta_description ?? '') }}</textarea>
                        <textarea class="form-control mb-2" name="body" placeholder="Page Body" rows="10">{{ old('body', $editingPage->body ?? '') }}</textarea>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary">Save Page</button>
                        <a class="btn btn-light" href="{{ route('cms.pages') }}">Reset</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header"><h5>Existing Pages</h5></div>
                <div class="card-body">
                    @foreach($pages as $page)
                        <div class="border rounded p-3 mb-2 d-flex justify-content-between">
                            <div>
                                <strong>{{ $page->title }}</strong><br>
                                <small class="text-muted">{{ $page->slug }}</small>
                            </div>
                            <div>
                                <a href="{{ route('cms.pages', ['page_id' => $page->id]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
