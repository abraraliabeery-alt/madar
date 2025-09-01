<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_primary',
        'is_paid',
        'price',
        'facility_id',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_paid' => 'boolean',
        'price' => 'float',
    ];

    // العلاقات
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_facility_role');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    public function translations()
    {
        return $this->hasMany(RoleTranslation::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    /**
     * Get translation for specific locale
     */
    public function getTranslation($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations()->where('locale', $locale)->first();
    }

    /**
     * Get name for specific locale
     */
    public function getTranslatedName($locale = null)
    {
        // First try to get from direct name field
        if ($this->name) {
            return $this->name;
        }
        
        // Fallback to translation
        $translation = $this->getTranslation($locale);
        return $translation ? $translation->name : '';
    }

    /**
     * Get display name for specific locale
     */
    public function getTranslatedDisplayName($locale = null)
    {
        $translation = $this->getTranslation($locale);
        return $translation ? $translation->display_name : '';
    }

    /**
     * Get description for specific locale
     */
    public function getTranslatedDescription($locale = null)
    {
        $translation = $this->getTranslation($locale);
        return $translation ? $translation->description : '';
    }

    /**
     * Scope to find role by name
     */
    public function scopeByName($query, $name)
    {
        return $query->where('name', $name);
    }

    // علاقات إضافية حسب الحاجة...
}
