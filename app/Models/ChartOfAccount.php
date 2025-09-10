<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChartOfAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'account_code',
        'account_name_ar',
        'account_name_en',
        'account_type',
        'account_category',
        'normal_balance',
        'parent_account_id',
        'level',
        'is_active',
        'is_system',
        'description',
        'currency',
        'opening_balance',
        'current_balance',
        'facility_id',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_system' => 'boolean',
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
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

    public function parentAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'parent_account_id');
    }

    public function childAccounts()
    {
        return $this->hasMany(ChartOfAccount::class, 'parent_account_id');
    }

    public function accountingEntries()
    {
        return $this->hasMany(AccountingEntry::class, 'account_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('account_type', $type);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('account_category', $category);
    }

    public function scopeByFacility($query, $facilityId)
    {
        return $query->where('facility_id', $facilityId);
    }

    public function scopeParentAccounts($query)
    {
        return $query->whereNull('parent_account_id');
    }

    public function scopeChildAccounts($query)
    {
        return $query->whereNotNull('parent_account_id');
    }

    // Accessors
    public function getAccountNameAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->account_name_ar : $this->account_name_en;
    }

    public function getFormattedBalanceAttribute()
    {
        return number_format($this->current_balance, 2) . ' ' . $this->currency;
    }

    public function getAccountPathAttribute()
    {
        $path = $this->account_code . ' - ' . $this->account_name;
        
        if ($this->parentAccount) {
            $path = $this->parentAccount->account_path . ' > ' . $path;
        }
        
        return $path;
    }

    // Methods
    public function updateBalance($amount, $entryType)
    {
        if ($this->normal_balance === 'debit') {
            if ($entryType === 'debit') {
                $this->current_balance += $amount;
            } else {
                $this->current_balance -= $amount;
            }
        } else {
            if ($entryType === 'credit') {
                $this->current_balance += $amount;
            } else {
                $this->current_balance -= $amount;
            }
        }
        
        $this->save();
    }

    public function getBalanceForPeriod($startDate, $endDate)
    {
        $entries = $this->accountingEntries()
            ->whereBetween('entry_date', [$startDate, $endDate])
            ->get();

        $balance = $this->opening_balance;

        foreach ($entries as $entry) {
            if ($this->normal_balance === 'debit') {
                if ($entry->entry_type === 'debit') {
                    $balance += $entry->amount;
                } else {
                    $balance -= $entry->amount;
                }
            } else {
                if ($entry->entry_type === 'credit') {
                    $balance += $entry->amount;
                } else {
                    $balance -= $entry->amount;
                }
            }
        }

        return $balance;
    }

    public function canBeDeleted()
    {
        return !$this->is_system && 
               $this->accountingEntries()->count() === 0 && 
               $this->childAccounts()->count() === 0;
    }

    // Static Methods
    public static function getAccountTypes()
    {
        return [
            'asset' => 'الأصول',
            'liability' => 'الخصوم',
            'equity' => 'حقوق الملكية',
            'revenue' => 'الإيرادات',
            'expense' => 'المصروفات',
            'cost_of_sales' => 'تكلفة المبيعات',
        ];
    }

    public static function getAccountCategories()
    {
        return [
            'current_asset' => 'الأصول المتداولة',
            'fixed_asset' => 'الأصول الثابتة',
            'current_liability' => 'الخصوم المتداولة',
            'long_term_liability' => 'الخصوم طويلة الأجل',
            'equity' => 'حقوق الملكية',
            'operating_revenue' => 'إيرادات التشغيل',
            'other_revenue' => 'إيرادات أخرى',
            'operating_expense' => 'مصروفات التشغيل',
            'administrative_expense' => 'مصروفات إدارية',
            'financial_expense' => 'مصروفات مالية',
            'cost_of_sales' => 'تكلفة المبيعات',
        ];
    }

    public static function createDefaultAccounts($facilityId, $createdBy)
    {
        $defaultAccounts = [
            // الأصول
            [
                'account_code' => '1000',
                'account_name_ar' => 'الأصول',
                'account_name_en' => 'Assets',
                'account_type' => 'asset',
                'account_category' => 'current_asset',
                'normal_balance' => 'debit',
                'level' => 1,
                'is_system' => true,
            ],
            [
                'account_code' => '1100',
                'account_name_ar' => 'النقدية',
                'account_name_en' => 'Cash',
                'account_type' => 'asset',
                'account_category' => 'current_asset',
                'normal_balance' => 'debit',
                'parent_account_id' => null,
                'level' => 2,
                'is_system' => true,
            ],
            [
                'account_code' => '1200',
                'account_name_ar' => 'البنوك',
                'account_name_en' => 'Banks',
                'account_type' => 'asset',
                'account_category' => 'current_asset',
                'normal_balance' => 'debit',
                'parent_account_id' => null,
                'level' => 2,
                'is_system' => true,
            ],
            [
                'account_code' => '1300',
                'account_name_ar' => 'الذمم المدينة',
                'account_name_en' => 'Accounts Receivable',
                'account_type' => 'asset',
                'account_category' => 'current_asset',
                'normal_balance' => 'debit',
                'parent_account_id' => null,
                'level' => 2,
                'is_system' => true,
            ],
            // الخصوم
            [
                'account_code' => '2000',
                'account_name_ar' => 'الخصوم',
                'account_name_en' => 'Liabilities',
                'account_type' => 'liability',
                'account_category' => 'current_liability',
                'normal_balance' => 'credit',
                'level' => 1,
                'is_system' => true,
            ],
            [
                'account_code' => '2100',
                'account_name_ar' => 'الذمم الدائنة',
                'account_name_en' => 'Accounts Payable',
                'account_type' => 'liability',
                'account_category' => 'current_liability',
                'normal_balance' => 'credit',
                'parent_account_id' => null,
                'level' => 2,
                'is_system' => true,
            ],
            // حقوق الملكية
            [
                'account_code' => '3000',
                'account_name_ar' => 'حقوق الملكية',
                'account_name_en' => 'Equity',
                'account_type' => 'equity',
                'account_category' => 'equity',
                'normal_balance' => 'credit',
                'level' => 1,
                'is_system' => true,
            ],
            [
                'account_code' => '3100',
                'account_name_ar' => 'رأس المال',
                'account_name_en' => 'Capital',
                'account_type' => 'equity',
                'account_category' => 'equity',
                'normal_balance' => 'credit',
                'parent_account_id' => null,
                'level' => 2,
                'is_system' => true,
            ],
            // الإيرادات
            [
                'account_code' => '4000',
                'account_name_ar' => 'الإيرادات',
                'account_name_en' => 'Revenue',
                'account_type' => 'revenue',
                'account_category' => 'operating_revenue',
                'normal_balance' => 'credit',
                'level' => 1,
                'is_system' => true,
            ],
            [
                'account_code' => '4100',
                'account_name_ar' => 'إيرادات المبيعات',
                'account_name_en' => 'Sales Revenue',
                'account_type' => 'revenue',
                'account_category' => 'operating_revenue',
                'normal_balance' => 'credit',
                'parent_account_id' => null,
                'level' => 2,
                'is_system' => true,
            ],
            [
                'account_code' => '4200',
                'account_name_ar' => 'إيرادات الإيجار',
                'account_name_en' => 'Rental Revenue',
                'account_type' => 'revenue',
                'account_category' => 'operating_revenue',
                'normal_balance' => 'credit',
                'parent_account_id' => null,
                'level' => 2,
                'is_system' => true,
            ],
            // المصروفات
            [
                'account_code' => '5000',
                'account_name_ar' => 'المصروفات',
                'account_name_en' => 'Expenses',
                'account_type' => 'expense',
                'account_category' => 'operating_expense',
                'normal_balance' => 'debit',
                'level' => 1,
                'is_system' => true,
            ],
            [
                'account_code' => '5100',
                'account_name_ar' => 'مصروفات التشغيل',
                'account_name_en' => 'Operating Expenses',
                'account_type' => 'expense',
                'account_category' => 'operating_expense',
                'normal_balance' => 'debit',
                'parent_account_id' => null,
                'level' => 2,
                'is_system' => true,
            ],
        ];

        foreach ($defaultAccounts as $account) {
            self::create(array_merge($account, [
                'facility_id' => $facilityId,
                'created_by' => $createdBy,
            ]));
        }
    }
}