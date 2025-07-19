<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'attribute_id',
        'locale',
        'name',
        'symbol',
    ];

    // العلاقات
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}
