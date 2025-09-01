<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'guard_name',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // العلاقات
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role');
    }

    public function translations()
    {
        return $this->hasMany(PermissionTranslation::class);
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
     * Get the active permissions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get permission group name based on name prefix
     */
    public function getGroupName()
    {
        if (!$this->name) {
            return 'Other';
        }
        
        $parts = explode('.', $this->name);
        return ucfirst($parts[0] ?? 'Other');
    }

    /**
     * Scope to group permissions by their prefix
     */
    public function scopeGrouped($query)
    {
        return $query->with('translations')->get()->groupBy(function ($permission) {
            return $permission->getGroupName();
        });
    }
}
