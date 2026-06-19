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
        'client_user_id',
        'project_type',
        'city_id',
        'neighborhood_id',
        'street_id',
        'address',
        'request_type',
        'scope_of_work',
        'finishing_level',
        'land_area',
        'built_area',
        'floors_count',
        'rooms_count',
        'bathrooms_count',
        'budget_min',
        'budget_max',
        'start_date',
        'duration_days',
        'requirements',
        'attachments',
        'status',
        'bid_deadline',
        'qa_deadline',
        'site_visit_date',
        'awarded_execution_bid_id',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'land_area' => 'float',
        'built_area' => 'float',
        'budget_min' => 'float',
        'budget_max' => 'float',
        'start_date' => 'date',
        'attachments' => 'array',
        'bid_deadline' => 'date',
        'qa_deadline' => 'date',
        'site_visit_date' => 'date',
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

    public function client()
    {
        return $this->belongsTo(User::class, 'client_user_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function neighborhood()
    {
        return $this->belongsTo(Neighborhood::class);
    }

    public function street()
    {
        return $this->belongsTo(Street::class);
    }

    public function translations()
    {
        return $this->hasMany(ProjectTranslation::class);
    }

    public function attachmentsFiles()
    {
        return $this->hasMany(ProjectAttachment::class);
    }

    public function stages()
    {
        return $this->hasMany(ProjectStage::class)->orderBy('order');
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'project_attribute_values')
            ->withPivot('value')
            ->withTimestamps();
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
