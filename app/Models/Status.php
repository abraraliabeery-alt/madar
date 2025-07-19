<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'color',
        'icon',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Polymorphic relationship - specify the pivot table name
    public function products()
    {
        return $this->morphedByMany(Product::class, 'statusable', 'statusables');
    }

    public function facilities()
    {
        return $this->morphedByMany(Facility::class, 'statusable', 'statusables');
    }

    public function bookings()
    {
        return $this->morphedByMany(Booking::class, 'statusable', 'statusables');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // Accessors
    public function getColorClassAttribute()
    {
        return $this->color ?: 'primary';
    }

    public function getIconClassAttribute()
    {
        return $this->icon ?: 'fas fa-circle';
    }
}
