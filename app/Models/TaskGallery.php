<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskGallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'img_id',
    ];

    // العلاقات
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function gallery()
    {
        return $this->belongsTo(Gallery::class, 'img_id');
    }
}
