<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_id',
        'name',
        'locale',
    ];

    // العلاقات
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
