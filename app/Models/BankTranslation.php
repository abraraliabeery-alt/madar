<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_id',
        'locale',
        'name',
    ];

    // العلاقات
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
