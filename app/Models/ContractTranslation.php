<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'locale',
        'title',
        'content',
        'file',
    ];

    // العلاقات
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
