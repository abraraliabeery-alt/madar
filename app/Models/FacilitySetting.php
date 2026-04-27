<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilitySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id',
        'seo_title',
        'seo_description',
        'logo_path',
        'favicon_path',
        'primary_color',
        'secondary_color',
        'social_links',
        'options',
    ];

    protected $casts = [
        'social_links' => 'array',
        'options' => 'array',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
