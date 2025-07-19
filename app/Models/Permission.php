<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'pages',
    ];

    // العلاقات
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role');
    }

    public function translations()
    {
        return $this->hasMany(PermissionTranslation::class);
    }

    // علاقات إضافية حسب الحاجة...
}
