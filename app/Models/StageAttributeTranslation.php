<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StageAttributeTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'stage_attribute_id',
        'locale',
        'name',
        'symbol',
    ];

    public function attribute()
    {
        return $this->belongsTo(StageAttribute::class, 'stage_attribute_id');
    }
}
