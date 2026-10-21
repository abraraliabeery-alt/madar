<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenderDiscovery extends Model
{
    use HasFactory;

    protected $table = 'tender_discoveries';

    protected $fillable = [
        'source_id',
        'title',
        'reference',
        'url',
        'closing_date',
        'description',
        'raw_data',
        'is_imported',
        'imported_facility_tender_id',
        'published_at',
    ];

    protected $casts = [
        'closing_date' => 'date',
        'is_imported' => 'boolean',
        'raw_data' => 'array',
        'published_at' => 'datetime',
    ];

    public function source()
    {
        return $this->belongsTo(TenderSource::class, 'source_id');
    }

    public function importedFacilityTender()
    {
        return $this->belongsTo(FacilityTender::class, 'imported_facility_tender_id');
    }

    public function scopeImported($query)
    {
        return $query->where('is_imported', true);
    }

    public function scopeOpen($query)
    {
        return $query->where('closing_date', '>=', now());
    }
}
