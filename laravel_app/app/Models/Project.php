<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_url',
        'link',
        'category',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];
}
