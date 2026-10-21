<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Owner extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'facility_id',
        'name',
        'phone',
        'email',
        'national_id',
        'is_company',
        'contact_person',
        'address',
        'commission_rate',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_company' => 'boolean',
        'is_active' => 'boolean',
        'commission_rate' => 'decimal:2',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'owner_id');
    }
}
