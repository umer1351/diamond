<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsHomeSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'title',
        'subtitle',
        'body',
        'button_text',
        'button_url',
        'image_path',
        'media_type',
        'media_url',
        'sort_order',
        'is_active',
    ];
}
