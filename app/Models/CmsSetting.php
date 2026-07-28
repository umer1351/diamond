<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'logo_text',
        'footer_about',
        'footer_email',
        'footer_phone',
        'footer_address',
        'contact_whatsapp',
        'facebook_url',
        'instagram_url',
        'youtube_url',
        'twitter_url',
        'tiktok_url',
        'snapchat_url',
        'category_bg_path',
        'copyright_text',
        'is_active',
    ];
}
