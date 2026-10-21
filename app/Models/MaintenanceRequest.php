<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenanceRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'facility_id',
        'product_id',
        'tenant_id',
        'assigned_to',
        'created_by',
        'title',
        'description',
        'type',
        'priority',
        'status',
        'cost',
        'scheduled_at',
        'completed_at',
    ];

    protected $casts = [
        'scheduled_at' => 'date',
        'completed_at' => 'datetime',
        'cost' => 'decimal:2',
    ];

    public const TYPES = ['maintenance', 'cleaning', 'other'];
    public const PRIORITIES = ['low', 'medium', 'high', 'urgent'];
    public const STATUSES = ['open', 'in_progress', 'completed', 'cancelled'];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'in_progress']);
    }
}
