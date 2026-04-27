<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StageAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'stage_key',
        'type',
        'required',
        'icon',
        'symbol',
        'show_in_stage_card',
        'order',
    ];

    protected $casts = [
        'required' => 'boolean',
        'show_in_stage_card' => 'boolean',
    ];

    public function translations()
    {
        return $this->hasMany(StageAttributeTranslation::class);
    }

    public function projectValues()
    {
        return $this->hasMany(ProjectStageAttributeValue::class);
    }
}
