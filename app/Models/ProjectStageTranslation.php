<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStageTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_stage_id',
        'locale',
        'name',
        'description',
    ];

    public function stage()
    {
        return $this->belongsTo(ProjectStage::class, 'project_stage_id');
    }
}
