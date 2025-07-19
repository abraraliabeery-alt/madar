<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
    ];

    // العلاقات
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function translations()
    {
        return $this->hasMany(BankTranslation::class);
    }
}
