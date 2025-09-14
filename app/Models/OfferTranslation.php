<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_id',
        'locale',
        'offer_title',
        'offer_description',
        'terms_conditions',
    ];

    // العلاقات
    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}