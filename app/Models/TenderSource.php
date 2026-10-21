<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenderSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country',
        'base_url',
        'list_url',
        'is_active',
        'config',
        'last_crawled_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'config' => 'array',
        'last_crawled_at' => 'datetime',
    ];

    public function discoveries()
    {
        return $this->hasMany(TenderDiscovery::class, 'source_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
