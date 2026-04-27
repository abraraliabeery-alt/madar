<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityFaq extends Model
{
    use HasFactory;

    protected $fillable = ['facility_id','question','answer','order','is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function facility(){ return $this->belongsTo(Facility::class);}    

    public function scopeActive($q){ return $q->where('is_active', true);}    
}
