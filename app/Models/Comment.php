<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        // لا توجد حقول إضافية حسب التصميم
    ];

    // العلاقات
    // يمكن إضافة العلاقات حسب الحاجة
}
