<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    // إضافة softDeletes إذا كان مطلوبًا
    // use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'is_multilanguage_enabled',
        'phone_number',
        'email',
        'name',
        'email_verified_at',
        'password',
        'avatar',
        'bank_account',
        'role_id',
        'facility_id',
        'bank_id',
        'latitude',
        'longitude',
        'google_maps_url',
        'primary_role',
        'facebook',
        'twitter',
        'instagram',
        'linkedin',
        'snapchat',
        'tiktok',
        'pinterest',
        'youtube',
        'whatsapp_number',
        'telegram',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_multilanguage_enabled' => 'boolean',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    // العلاقات
    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'facility_user');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_facility_role');
    }

    public function translations()
    {
        return $this->hasMany(UserTranslation::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'owner_user_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function favoriteProducts()
    {
        return $this->belongsToMany(Product::class, 'user_favorites', 'user_id', 'product_id');
    }

    public function favoriteFacilities()
    {
        return $this->belongsToMany(Facility::class, 'user_favorites', 'user_id', 'facility_id');
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * Check if user has a specific role
     *
     * @param string $role
     * @return bool
     */
    public function hasRole($role)
    {
        // Check primary role first
        if ($this->primary_role === $role) {
            return true;
        }

        // Check roles relationship
        return $this->roles()->where('name', $role)->exists();
    }

    /**
     * Check if user has any of the given roles
     *
     * @param array $roles
     * @return bool
     */
    public function hasAnyRole($roles)
    {
        if (is_string($roles)) {
            return $this->hasRole($roles);
        }

        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has all of the given roles
     *
     * @param array $roles
     * @return bool
     */
    public function hasAllRoles($roles)
    {
        foreach ($roles as $role) {
            if (!$this->hasRole($role)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if user has a specific permission
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        // Check through roles
        foreach ($this->roles as $role) {
            if ($role->permissions()->where('name', $permission)->exists()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has any of the given permissions
     *
     * @param array $permissions
     * @return bool
     */
    public function hasAnyPermission($permissions)
    {
        if (is_string($permissions)) {
            return $this->hasPermission($permissions);
        }

        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has all of the given permissions
     *
     * @param array $permissions
     * @return bool
     */
    public function hasAllPermissions($permissions)
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    // علاقات إضافية حسب الحاجة...
}
