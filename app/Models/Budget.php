<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_name',
        'description',
        'budget_type',
        'status',
        'start_date',
        'end_date',
        'total_budget',
        'allocated_amount',
        'spent_amount',
        'remaining_amount',
        'budget_items',
        'notes',
        'facility_id',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_budget' => 'decimal:2',
        'allocated_amount' => 'decimal:2',
        'spent_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'budget_items' => 'array',
        'approved_at' => 'datetime',
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

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('budget_type', $type);
    }

    public function scopeByFacility($query, $facilityId)
    {
        return $query->where('facility_id', $facilityId);
    }

    public function scopeCurrent($query)
    {
        $now = Carbon::now();
        return $query->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
    }

    // Accessors
    public function getFormattedTotalBudgetAttribute()
    {
        return number_format($this->total_budget, 2) . ' SAR';
    }

    public function getFormattedSpentAmountAttribute()
    {
        return number_format($this->spent_amount, 2) . ' SAR';
    }

    public function getFormattedRemainingAmountAttribute()
    {
        return number_format($this->remaining_amount, 2) . ' SAR';
    }

    public function getUtilizationPercentageAttribute()
    {
        if ($this->total_budget == 0) {
            return 0;
        }
        return ($this->spent_amount / $this->total_budget) * 100;
    }

    public function getIsOverBudgetAttribute()
    {
        return $this->spent_amount > $this->total_budget;
    }

    public function getIsCurrentAttribute()
    {
        $now = Carbon::now();
        return $this->start_date <= $now && $this->end_date >= $now;
    }

    // Methods
    public function approve($approvedBy)
    {
        $this->status = 'approved';
        $this->approved_by = $approvedBy;
        $this->approved_at = Carbon::now();
        $this->save();
    }

    public function activate()
    {
        if ($this->status === 'approved') {
            $this->status = 'active';
            $this->save();
        }
    }

    public function complete()
    {
        if ($this->status === 'active') {
            $this->status = 'completed';
            $this->save();
        }
    }

    public function cancel()
    {
        if (in_array($this->status, ['draft', 'approved'])) {
            $this->status = 'cancelled';
            $this->save();
        }
    }

    public function updateSpentAmount()
    {
        // حساب المبلغ المنفق من القيود المحاسبية
        $spentAmount = AccountingEntry::where('facility_id', $this->facility_id)
            ->where('account_type', 'expense')
            ->whereBetween('entry_date', [$this->start_date, $this->end_date])
            ->sum('amount');

        $this->spent_amount = $spentAmount;
        $this->remaining_amount = $this->total_budget - $this->spent_amount;
        $this->save();
    }

    public function addBudgetItem($accountId, $amount, $description = null)
    {
        $items = $this->budget_items ?? [];
        
        $items[] = [
            'account_id' => $accountId,
            'amount' => $amount,
            'description' => $description,
            'allocated_at' => Carbon::now()->toISOString(),
        ];

        $this->budget_items = $items;
        $this->allocated_amount = collect($items)->sum('amount');
        $this->save();
    }

    public function removeBudgetItem($index)
    {
        $items = $this->budget_items ?? [];
        
        if (isset($items[$index])) {
            unset($items[$index]);
            $items = array_values($items); // إعادة ترقيم المصفوفة
            
            $this->budget_items = $items;
            $this->allocated_amount = collect($items)->sum('amount');
            $this->save();
        }
    }

    public function getBudgetItemsByAccount()
    {
        $items = $this->budget_items ?? [];
        $grouped = [];

        foreach ($items as $item) {
            $accountId = $item['account_id'];
            if (!isset($grouped[$accountId])) {
                $grouped[$accountId] = [
                    'account' => ChartOfAccount::find($accountId),
                    'total_amount' => 0,
                    'items' => []
                ];
            }
            
            $grouped[$accountId]['total_amount'] += $item['amount'];
            $grouped[$accountId]['items'][] = $item;
        }

        return $grouped;
    }

    public function canBeEdited()
    {
        return in_array($this->status, ['draft', 'approved']);
    }

    public function canBeDeleted()
    {
        return $this->status === 'draft';
    }

    // Static Methods
    public static function getBudgetTypes()
    {
        return [
            'annual' => 'سنوية',
            'quarterly' => 'ربع سنوية',
            'monthly' => 'شهرية',
            'project' => 'مشروع',
            'department' => 'قسم',
        ];
    }

    public static function getStatuses()
    {
        return [
            'draft' => 'مسودة',
            'approved' => 'معتمدة',
            'active' => 'نشطة',
            'completed' => 'مكتملة',
            'cancelled' => 'ملغية',
        ];
    }

    public static function createAnnualBudget($year, $facilityId, $createdBy)
    {
        $startDate = Carbon::create($year, 1, 1);
        $endDate = Carbon::create($year, 12, 31);

        return self::create([
            'budget_name' => "الميزانية السنوية {$year}",
            'description' => "الميزانية السنوية لعام {$year}",
            'budget_type' => 'annual',
            'status' => 'draft',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'facility_id' => $facilityId,
            'created_by' => $createdBy,
        ]);
    }

    public static function createMonthlyBudget($year, $month, $facilityId, $createdBy)
    {
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        return self::create([
            'budget_name' => "الميزانية الشهرية {$startDate->format('F Y')}",
            'description' => "الميزانية الشهرية لشهر {$startDate->format('F Y')}",
            'budget_type' => 'monthly',
            'status' => 'draft',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'facility_id' => $facilityId,
            'created_by' => $createdBy,
        ]);
    }
}