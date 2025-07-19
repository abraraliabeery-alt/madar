<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'owner_id',
        'start_date',
        'end_date',
        'facility_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // العلاقات
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function translations()
    {
        return $this->hasMany(ContractTranslation::class);
    }

    public function buildings()
    {
        return $this->hasMany(Building::class);
    }
}
