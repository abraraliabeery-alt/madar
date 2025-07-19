<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant',
        'manager',
        'bank_emp',
        'bank_id',
        'facility_id',
        'updated_by',
        'birth',
        'salary',
        'commitments',
        'military',
        'rank',
        'employment',
        'supported',
    ];

    protected $casts = [
        'birth' => 'date',
        'salary' => 'float',
        'commitments' => 'float',
        'supported' => 'boolean',
    ];

    // العلاقات
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function translations()
    {
        return $this->hasMany(LoanTranslation::class);
    }
}
