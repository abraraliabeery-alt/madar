<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
    ];

    // العلاقات
    public function taskGallery()
    {
        return $this->hasMany(TaskGallery::class, 'img_id');
    }
}
