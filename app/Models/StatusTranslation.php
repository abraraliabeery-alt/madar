<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_id',
        'locale',
        'name',
    ];

    // العلاقات
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
