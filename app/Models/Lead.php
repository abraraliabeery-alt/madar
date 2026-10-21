<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes;

    const STAGE_NEW = 'new';
    const STAGE_CONTACTED = 'contacted';
    const STAGE_VIEWING = 'viewing';
    const STAGE_NEGOTIATION = 'negotiation';
    const STAGE_WON = 'won';
    const STAGE_LOST = 'lost';

    const STAGES = [
        'new' => 'جديد',
        'contacted' => 'تم التواصل',
        'viewing' => 'معاينة',
        'negotiation' => 'تفاوض',
        'won' => 'تم الإغلاق',
        'lost' => 'مفقود',
    ];

    const SOURCES = [
        'website' => 'الموقع',
        'phone' => 'اتصال',
        'walkin' => 'زيارة مكتب',
        'referral' => 'ترشيح',
        'social' => 'تواصل اجتماعي',
        'other' => 'أخرى',
    ];

    protected $fillable = [
        'facility_id',
        'name',
        'phone',
        'email',
        'source',
        'stage',
        'interest_type',
        'product_id',
        'assigned_to',
        'budget',
        'notes',
    ];

    protected $casts = [
        'budget' => 'decimal:2',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function activities()
    {
        return $this->hasMany(LeadActivity::class)->latest();
    }

    public function nextFollowUp()
    {
        return $this->activities()
            ->whereNotNull('follow_up_at')
            ->where('follow_up_at', '>=', now())
            ->orderBy('follow_up_at');
    }

    public function stageLabel(): string
    {
        return self::STAGES[$this->stage] ?? $this->stage;
    }
}
