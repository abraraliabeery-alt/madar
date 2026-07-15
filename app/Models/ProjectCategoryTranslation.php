<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectCategoryTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_category_id',
        'locale',
        'name',
        'description',
    ];

    public function projectCategory()
    {
        return $this->belongsTo(ProjectCategory::class);
    }
}
