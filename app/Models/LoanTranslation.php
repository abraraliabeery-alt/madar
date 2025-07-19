<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'locale',
        'agency',
    ];

    // العلاقات
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
