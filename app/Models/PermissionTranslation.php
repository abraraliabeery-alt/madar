<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'permission_id',
        'name',
        'locale',
    ];

    // العلاقات
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
