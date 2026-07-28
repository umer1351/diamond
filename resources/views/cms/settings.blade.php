@extends('layouts.master')
@section('content')
<div class="main-content pt-4">
    <div class="breadcrumb"><h1>Site Settings</h1></div>
    <form method="post" action="{{ route('cms.setting.save') }}">
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
            <input class="form-control mb-2" name="copyright_text" value="{{ old('copyright_text', $setting->copyright_text ?? '') }}" placeholder="Copyright Text">
        </div><div class="card-footer"><button class="btn btn-primary">Save</button></div></div>
    </form>
</div>
@endsection
