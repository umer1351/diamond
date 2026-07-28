@extends('layouts.master')
@section('content')
<div class="main-content pt-4">
    <div class="breadcrumb"><h1>Homepage Banners</h1></div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header"><h5>Hero / Video / Promo / Gift Sections</h5></div>
                <form method="post" action="{{ route('cms.section.save') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <input type="hidden" name="id" value="{{ old('id', $editingSection->id ?? '') }}">
                        <input class="form-control mb-2" name="key" value="{{ old('key', $editingSection->key ?? '') }}" placeholder="hero_banner / hero_video / promo_banner / gift_collection" required>
                        <input class="form-control mb-2" name="title" value="{{ old('title', $editingSection->title ?? '') }}" placeholder="Title">
                        <input class="form-control mb-2" name="subtitle" value="{{ old('subtitle', $editingSection->subtitle ?? '') }}" placeholder="Subtitle">
                        <textarea class="form-control mb-2" name="body" placeholder="Body">{{ old('body', $editingSection->body ?? '') }}</textarea>
                        <input class="form-control mb-2" name="button_text" value="{{ old('button_text', $editingSection->button_text ?? '') }}" placeholder="Button Text">
                        <input class="form-control mb-2" name="button_url" value="{{ old('button_url', $editingSection->button_url ?? '') }}" placeholder="Button URL">
                        <label class="d-block mt-2 mb-1"><strong>Video URL</strong> (YouTube link or direct .mp4/.webm)</label>
                        <input class="form-control mb-1" name="media_url" value="{{ old('media_url', $editingSection->media_url ?? '') }}" placeholder="https://www.youtube.com/watch?v=... or https://.../video.mp4">
                        <small class="text-muted d-block mb-2">To change the top homepage video, edit key <strong>hero_video</strong> and either paste a Video URL above <em>or</em> upload an MP4/WebM below. Uploading a file replaces the URL.</small>
                        <input class="form-control mb-2" type="file" name="image_file" accept="image/*,video/mp4,video/webm">
                        <input class="form-control mb-2" name="sort_order" type="number" value="{{ old('sort_order', $editingSection->sort_order ?? 0) }}">
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary">Save Section</button>
                        <a class="btn btn-light" href="{{ route('cms.homepage') }}">Reset</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header"><h5>Existing Banners</h5></div>
                <div class="card-body">
                    @foreach($sections as $section)
                        <div class="border rounded p-3 mb-2 d-flex justify-content-between">
                            <div>
                                <strong>{{ $section->key }}</strong><br>
                                <small class="text-muted">{{ $section->title }}</small>
                            </div>
                            <div>
                                <a href="{{ route('cms.homepage', ['section_id' => $section->id]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
