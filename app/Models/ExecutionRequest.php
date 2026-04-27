<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExecutionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id',
        'project_id',
        'product_id',
        'type',
        'status',
        'priority',
        'budget_min',
        'budget_max',
        'due_date',
        'data',
    ];

    protected $casts = [
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
        'due_date' => 'date',
        'data' => 'array',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function translations()
    {
        return $this->hasMany(ExecutionRequestTranslation::class);
    }

    public function bids()
    {
        return $this->hasMany(ExecutionBid::class);
    }

    public function getTranslatedTitle($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        $translation = $this->translations->firstWhere('locale', $locale);
        return $translation ? $translation->title : null;
    }
}
