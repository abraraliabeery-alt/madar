<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandStudy extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'inputs',
        'report',
        'scenarios',
        'images',
        'status',
        'cost_usd',
        'error',
    ];

    protected $casts = [
        'inputs' => 'array',
        'scenarios' => 'array',
        'images' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
