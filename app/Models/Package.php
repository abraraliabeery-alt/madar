<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        // لا توجد حقول إضافية حسب التصميم
    ];

    // العلاقات
    public function translations()
    {
        return $this->hasMany(PackageTranslation::class);
    }
}
