@extends('layouts.master')
@section('content')
<div class="main-content pt-4">
    <div class="breadcrumb"><h1>Site Settings</h1></div>
    <form method="post" action="{{ route('cms.setting.save') }}" enctype="multipart/form-data">
        @csrf
        <div class="card"><div class="card-body">
            <input class="form-control mb-2" name="site_name" value="{{ old('site_name', $setting->site_name ?? 'Azure Luxury') }}" placeholder="Site Name">
            <input class="form-control mb-2" name="logo_text" value="{{ old('logo_text', $setting->logo_text ?? 'AZURE') }}" placeholder="Logo Text">
            <textarea class="form-control mb-2" name="footer_about" placeholder="Footer About">{{ old('footer_about', $setting->footer_about ?? '') }}</textarea>
            <input class="form-control mb-2" name="footer_email" value="{{ old('footer_email', $setting->footer_email ?? '') }}" placeholder="Footer Email">
            <input class="form-control mb-2" name="footer_phone" value="{{ old('footer_phone', $setting->footer_phone ?? '') }}" placeholder="Footer Phone">
            <textarea class="form-control mb-2" name="footer_address" placeholder="Footer Address">{{ old('footer_address', $setting->footer_address ?? '') }}</textarea>
            <input class="form-control mb-2" name="contact_whatsapp" value="{{ old('contact_whatsapp', $setting->contact_whatsapp ?? '') }}" placeholder="WhatsApp">
            <input class="form-control mb-2" name="facebook_url" value="{{ old('facebook_url', $setting->facebook_url ?? '') }}" placeholder="Facebook URL">
            <input class="form-control mb-2" name="instagram_url" value="{{ old('instagram_url', $setting->instagram_url ?? '') }}" placeholder="Instagram URL">
            <input class="form-control mb-2" name="tiktok_url" value="{{ old('tiktok_url', $setting->tiktok_url ?? '') }}" placeholder="TikTok URL">
            <input class="form-control mb-2" name="snapchat_url" value="{{ old('snapchat_url', $setting->snapchat_url ?? '') }}" placeholder="Snapchat URL">
            <input class="form-control mb-2" name="youtube_url" value="{{ old('youtube_url', $setting->youtube_url ?? '') }}" placeholder="YouTube URL">
            <input class="form-control mb-2" name="twitter_url" value="{{ old('twitter_url', $setting->twitter_url ?? '') }}" placeholder="Twitter URL">

            <hr>
            <label class="d-block"><strong>Our Socials — reel / video for each tile</strong></label>
            <small class="text-muted d-block mb-2">Optional. Paste a YouTube embed link (https://www.youtube.com/embed/ID) or a direct .mp4 URL. When set, the reel plays inside that social's tile so the section isn't static.</small>
            <input class="form-control mb-2" name="instagram_reel_url" value="{{ old('instagram_reel_url', $setting->instagram_reel_url ?? '') }}" placeholder="Instagram reel/video URL">
            <input class="form-control mb-2" name="facebook_reel_url" value="{{ old('facebook_reel_url', $setting->facebook_reel_url ?? '') }}" placeholder="Facebook reel/video URL">
            <input class="form-control mb-2" name="tiktok_reel_url" value="{{ old('tiktok_reel_url', $setting->tiktok_reel_url ?? '') }}" placeholder="TikTok reel/video URL">
            <input class="form-control mb-2" name="snapchat_reel_url" value="{{ old('snapchat_reel_url', $setting->snapchat_reel_url ?? '') }}" placeholder="Snapchat reel/video URL">

            <hr>
            <input class="form-control mb-2" name="copyright_text" value="{{ old('copyright_text', $setting->copyright_text ?? '') }}" placeholder="Copyright Text">

            <hr>
            <label class="d-block"><strong>Shop by Category — background image</strong></label>
            <small class="text-muted d-block mb-2">Shown behind the "Shop by Category" section on the home and categories pages. Leave empty for the default dark background.</small>
            @php
                $bgPath = $setting->category_bg_path ?? null;
                $bgUrl = null;
                if ($bgPath) {
                    $bgUrl = \Illuminate\Support\Facades\Storage::disk('public')->exists($bgPath)
                        ? \Illuminate\Support\Facades\Storage::url($bgPath)
                        : (is_file(public_path($bgPath)) ? asset($bgPath) : null);
                }
            @endphp
            @if($bgUrl)
                <div class="mb-2"><img src="{{ $bgUrl }}" alt="Category background" style="max-height:120px;border-radius:8px;object-fit:cover;"></div>
                <div class="form-check mb-2">
                    <input type="checkbox" class="form-check-input" id="remove_category_bg" name="remove_category_bg" value="1">
                    <label class="form-check-label" for="remove_category_bg">Remove current background image</label>
                </div>
            @endif
            <input class="form-control mb-2" type="file" name="category_bg_file" accept=".jpg,.jpeg,.png,.webp,.gif">
        </div><div class="card-footer"><button class="btn btn-primary">Save</button></div></div>
    </form>
</div>
@endsection
