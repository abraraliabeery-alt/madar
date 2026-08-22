<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingProductRequest extends Model
{
    public const STATUS_NEW = 'new';
    public const STATUS_CONTACTED = 'contacted';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_CLOSED = 'closed';
    public const STATUS_REJECTED = 'rejected';

    public const PRIORITY_LOW = 'low';
    public const PRIORITY_NORMAL = 'normal';
    public const PRIORITY_HIGH = 'high';
    public const PRIORITY_URGENT = 'urgent';

    public static function statuses(): array
    {
        return [
            self::STATUS_NEW => __('admin.product_requests.statuses.new'),
            self::STATUS_CONTACTED => __('admin.product_requests.statuses.contacted'),
            self::STATUS_IN_PROGRESS => __('admin.product_requests.statuses.in_progress'),
            self::STATUS_RESOLVED => __('admin.product_requests.statuses.resolved'),
            self::STATUS_CLOSED => __('admin.product_requests.statuses.closed'),
            self::STATUS_REJECTED => __('admin.product_requests.statuses.rejected'),
        ];
    }

    public static function priorities(): array
    {
        return [
            self::PRIORITY_LOW => __('admin.product_requests.priorities.low'),
            self::PRIORITY_NORMAL => __('admin.product_requests.priorities.normal'),
            self::PRIORITY_HIGH => __('admin.product_requests.priorities.high'),
            self::PRIORITY_URGENT => __('admin.product_requests.priorities.urgent'),
        ];
    }

    protected $fillable = [
        'name',
        'phone',
        'description',
        'extracted',
        'status',
        'assigned_to',
        'admin_notes',
        'priority',
        'contacted_at',
        'closed_at',
        'source',
    ];

    protected $casts = [
        'extracted' => 'array',
        'contacted_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function scopeStatus($query, $status)
    {
        return $status ? $query->where('status', $status) : $query;
    }

    public function scopePriority($query, $priority)
    {
        return $priority ? $query->where('priority', $priority) : $query;
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $userId ? $query->where('assigned_to', $userId) : $query;
    }

    public function scopeSearch($query, $term)
    {
        if (!$term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', '%' . $term . '%')
              ->orWhere('phone', 'like', '%' . $term . '%')
              ->orWhere('description', 'like', '%' . $term . '%');
        });
    }

    public function scopeOpen($query)
    {
        return $query->whereNotIn('status', [self::STATUS_CLOSED, self::STATUS_REJECTED, self::STATUS_RESOLVED]);
    }

    public function isOpen(): bool
    {
        return !in_array($this->status, [self::STATUS_CLOSED, self::STATUS_REJECTED, self::STATUS_RESOLVED], true);
    }

    public function statusLabel(): string
    {
        return self::statuses()[$this->status] ?? $this->status;
    }

    public function priorityLabel(): string
    {
        return self::priorities()[$this->priority] ?? $this->priority;
    }
}
