<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'latitude',
        'longitude',
        'google_maps_url',
        'facility_id',
        'image',
        'seller_user_id',
        'project_type',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    // العلاقات
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_user_id');
    }

    public function translations()
    {
        return $this->hasMany(ProjectTranslation::class);
    }

    public function stages()
    {
        return $this->hasMany(ProjectStage::class)->orderBy('order');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function (Project $project) {
            $defaultStages = [
                ['key' => 'idea', 'order' => 1],
                ['key' => 'feasibility', 'order' => 2],
                ['key' => 'design', 'order' => 3],
                ['key' => 'permits', 'order' => 4],
                ['key' => 'construction', 'order' => 5],
                ['key' => 'sales_marketing', 'order' => 6],
                ['key' => 'post_sale', 'order' => 7],
            ];

            foreach ($defaultStages as $stage) {
                $project->stages()->create([
                    'key' => $stage['key'],
                    'order' => $stage['order'],
                    'status' => 'not_started',
                ]);
            }
        });
    }
}
