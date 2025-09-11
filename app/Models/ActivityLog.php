<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'platform',
        'browser',
        'device_type',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    // العلاقات
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Accessors
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getDeviceTypeAttribute($value)
    {
        if (!$value) {
            $userAgent = $this->user_agent ?? '';
            if (preg_match('/Mobile|Android|iPhone|iPad/', $userAgent)) {
                return 'mobile';
            } elseif (preg_match('/Tablet|iPad/', $userAgent)) {
                return 'tablet';
            } else {
                return 'desktop';
            }
        }
        return $value;
    }
}
