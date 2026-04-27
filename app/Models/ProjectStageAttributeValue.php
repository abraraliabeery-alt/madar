<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStageAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_stage_id',
        'stage_attribute_id',
        'value',
    ];

    public function stage()
    {
        return $this->belongsTo(ProjectStage::class, 'project_stage_id');
    }

    public function attribute()
    {
        return $this->belongsTo(StageAttribute::class, 'stage_attribute_id');
    }
}
