<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'deadline',
        'created_by',
        'status',
        'priority',
        'type',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    // العلاقات
    public function users()
    {
        return $this->belongsToMany(User::class, 'task_user');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function gallery()
    {
        return $this->hasMany(TaskGallery::class);
    }

    public function translations()
    {
        return $this->hasMany(TaskTranslation::class);
    }
}
