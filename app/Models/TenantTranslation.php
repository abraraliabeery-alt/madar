<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'tenant_id',
        'locale',
        'name',
        'notes',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
