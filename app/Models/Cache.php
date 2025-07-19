<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cache extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'expiration',
    ];

    protected $casts = [
        'expiration' => 'integer',
    ];

    // العلاقات
    // يمكن إضافة العلاقات حسب الحاجة
}
