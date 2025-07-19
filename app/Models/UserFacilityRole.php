<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFacilityRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'facility_id',
        'role_id',
    ];

    // العلاقات
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
