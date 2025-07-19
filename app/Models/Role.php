<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_primary',
        'is_paid',
        'price',
        'facility_id',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_paid' => 'boolean',
        'price' => 'float',
    ];

    // العلاقات
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_facility_role');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    public function translations()
    {
        return $this->hasMany(RoleTranslation::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    // علاقات إضافية حسب الحاجة...
}
