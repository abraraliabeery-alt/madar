<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadActivity extends Model
{
    const TYPES = [
        'note' => 'ملاحظة',
        'call' => 'مكالمة',
        'meeting' => 'اجتماع',
        'follow_up' => 'متابعة مجدولة',
    ];

    protected $fillable = [
        'lead_id',
        'user_id',
        'type',
        'body',
        'follow_up_at',
    ];

    protected $casts = [
        'follow_up_at' => 'datetime',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
