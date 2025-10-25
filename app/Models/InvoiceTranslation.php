<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'locale',
        'notes',
        'payment_terms',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
