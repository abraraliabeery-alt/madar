<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'locale',
        'name',
        'description',
    ];

    // العلاقات
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
