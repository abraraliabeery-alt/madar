<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketIndex extends Model
{
    protected $fillable = [
        'city_id',
        'property_type',
        'avg_rent_yearly',
        'avg_sale_sqm',
        'period',
        'source',
    ];

    protected $casts = [
        'avg_rent_yearly' => 'decimal:2',
        'avg_sale_sqm' => 'decimal:2',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * يجلب مؤشر مدينة معيّنة لنوع عقار — يرجع للمتوسط العام عند غياب النوع
     */
    public static function forCity(?int $cityId, ?string $propertyType = null): ?self
    {
        if (!$cityId) {
            return null;
        }

        $query = static::where('city_id', $cityId)->latest('period');

        if ($propertyType) {
            $index = (clone $query)->where('property_type', $propertyType)->first();
            if ($index) {
                return $index;
            }
        }

        return $query->where('property_type', 'all')->first();
    }
}
