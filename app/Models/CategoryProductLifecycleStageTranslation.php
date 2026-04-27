<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryProductLifecycleStageTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'stage_id',
        'locale',
        'name',
        'description',
    ];

    public function stage()
    {
        return $this->belongsTo(CategoryProductLifecycleStage::class, 'stage_id');
    }
}
