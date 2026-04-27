<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryProductLifecycleStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'key',
        'order',
        'is_default',
        'is_terminal',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_terminal' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function translations()
    {
        return $this->hasMany(CategoryProductLifecycleStageTranslation::class, 'stage_id');
    }
}
