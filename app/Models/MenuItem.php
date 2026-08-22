<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'panel',
        'key',
        'label_key',
        'label_override',
        'route_name',
        'url',
        'icon',
        'sort_order',
        'enabled',
        'visibility',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'visibility' => 'array',
        'sort_order' => 'integer',
    ];
}
