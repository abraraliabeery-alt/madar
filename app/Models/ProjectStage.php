<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'key',
        'status',
        'order',
        'started_at',
        'completed_at',
        'meta',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'meta' => 'array',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function translations()
    {
        return $this->hasMany(ProjectStageTranslation::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(StageAttribute::class, 'project_stage_attribute_values')
            ->withPivot('value')
            ->withTimestamps();
    }
}
