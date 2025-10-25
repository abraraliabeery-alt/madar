<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AccountingPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_name',
        'start_date',
        'end_date',
        'period_type',
        'status',
        'is_current',
        'notes',
        'facility_id',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];

    // العلاقات
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function accountingEntries()
    {
        return $this->hasMany(AccountingEntry::class, 'period_id');
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    public function scopeByFacility($query, $facilityId)
    {
        return $query->where('facility_id', $facilityId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('period_type', $type);
    }

    // Accessors
    public function getDurationAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function getFormattedPeriodAttribute()
    {
        return $this->start_date->format('Y-m-d') . ' - ' . $this->end_date->format('Y-m-d');
    }

    // Methods
    public function canBeClosed()
    {
        return $this->status === 'open' && 
               $this->accountingEntries()->count() > 0;
    }

    public function close()
    {
        if (!$this->canBeClosed()) {
            return false;
        }

        $this->status = 'closed';
        $this->save();

        return true;
    }

    public function lock()
    {
        $this->status = 'locked';
        $this->save();
    }

    public function unlock()
    {
        if ($this->status === 'locked') {
            $this->status = 'open';
            $this->save();
        }
    }

    public function isDateInPeriod($date)
    {
        $date = Carbon::parse($date);
        return $date->between($this->start_date, $this->end_date);
    }

    public function getFinancialSummary()
    {
        $entries = $this->accountingEntries()->get();
        
        $summary = [
            'total_revenue' => 0,
            'total_expenses' => 0,
            'net_income' => 0,
            'total_assets' => 0,
            'total_liabilities' => 0,
            'total_equity' => 0,
        ];

        foreach ($entries as $entry) {
            $amount = $entry->amount;
            
            if ($entry->account_type === 'revenue') {
                if ($entry->entry_type === 'credit') {
                    $summary['total_revenue'] += $amount;
                }
            } elseif ($entry->account_type === 'expense') {
                if ($entry->entry_type === 'debit') {
                    $summary['total_expenses'] += $amount;
                }
            } elseif ($entry->account_type === 'asset') {
                if ($entry->entry_type === 'debit') {
                    $summary['total_assets'] += $amount;
                } else {
                    $summary['total_assets'] -= $amount;
                }
            } elseif ($entry->account_type === 'liability') {
                if ($entry->entry_type === 'credit') {
                    $summary['total_liabilities'] += $amount;
                } else {
                    $summary['total_liabilities'] -= $amount;
                }
            } elseif ($entry->account_type === 'equity') {
                if ($entry->entry_type === 'credit') {
                    $summary['total_equity'] += $amount;
                } else {
                    $summary['total_equity'] -= $amount;
                }
            }
        }

        $summary['net_income'] = $summary['total_revenue'] - $summary['total_expenses'];

        return $summary;
    }

    // Static Methods
    public static function getPeriodTypes()
    {
        return [
            'monthly' => 'شهري',
            'quarterly' => 'ربع سنوي',
            'yearly' => 'سنوي',
            'custom' => 'مخصص',
        ];
    }

    public static function getStatuses()
    {
        return [
            'open' => 'مفتوح',
            'closed' => 'مغلق',
            'locked' => 'مقفل',
        ];
    }

    public static function createMonthlyPeriods($year, $facilityId, $createdBy)
    {
        $periods = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::create($year, $month, 1);
            $endDate = $startDate->copy()->endOfMonth();
            
            $periods[] = self::create([
                'period_name' => $startDate->format('F Y'),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'period_type' => 'monthly',
                'status' => 'open',
                'is_current' => $month === Carbon::now()->month && $year === Carbon::now()->year,
                'facility_id' => $facilityId,
                'created_by' => $createdBy,
            ]);
        }
        
        return $periods;
    }

    public static function createQuarterlyPeriods($year, $facilityId, $createdBy)
    {
        $periods = [];
        $quarters = [
            ['Q1', 1, 3],
            ['Q2', 4, 6],
            ['Q3', 7, 9],
            ['Q4', 10, 12],
        ];
        
        foreach ($quarters as [$quarter, $startMonth, $endMonth]) {
            $startDate = Carbon::create($year, $startMonth, 1);
            $endDate = Carbon::create($year, $endMonth)->endOfMonth();
            
            $periods[] = self::create([
                'period_name' => $quarter . ' ' . $year,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'period_type' => 'quarterly',
                'status' => 'open',
                'is_current' => false,
                'facility_id' => $facilityId,
                'created_by' => $createdBy,
            ]);
        }
        
        return $periods;
    }
}