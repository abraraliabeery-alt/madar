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
        'phone',
        'email',
        'name',
        'email_verified_at',
        'password',
        'avatar',
        'profile_picture',
        'bio',
        'location',
        'date_of_birth',
        'gender',
        'phone_verified_at',
        'last_login_at',
        'two_factor_enabled',
        'notification_settings',
        'privacy_settings',
        'preferences',
        'security_settings',
        'password_changed_at',
        'bank_account',
        'role_id',
        'facility_id',
        'bank_id',
        'latitude',
        'longitude',
        'google_maps_url',
        'primary_role',
        'referral_code',
        'referred_by_user_id',
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
        'notification_email',
        'notification_sms',
        'notification_push',
        'notification_frequency',
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
            'phone_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password_changed_at' => 'datetime',
            'date_of_birth' => 'date',
            'is_multilanguage_enabled' => 'boolean',
            'two_factor_enabled' => 'boolean',
            'latitude' => 'float',
            'longitude' => 'float',
            'notification_email' => 'boolean',
            'notification_sms' => 'boolean',
            'notification_push' => 'boolean',
            'notification_settings' => 'array',
            'privacy_settings' => 'array',
            'preferences' => 'array',
            'security_settings' => 'array',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                do {
                    $code = strtoupper(\Illuminate\Support\Str::random(8));
                } while (self::where('referral_code', $code)->exists());
                $user->referral_code = $code;
            }
        });
    }

    // العلاقات
    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'facility_user');
    }

    /**
     * Return the main facility for the user; for admins, create one if missing.
     */
    public function mainFacility()
    {
        $facility = $this->facilities()->first();

        if ($facility) {
            return $facility;
        }

        if ($this->hasRole('admin')) {
            return $this->createMainFacility();
        }

        return null;
    }

    private function createMainFacility()
    {
        $facility = Facility::firstOrCreate(
            ['owner_user_id' => $this->id],
            [
                'name' => $this->name . ' (Main)',
                'slug' => \Illuminate\Support\Str::slug($this->name . '-main-' . $this->id),
                'is_active' => true,
                'is_verified' => true,
                'is_primary' => true,
                'owner_user_id' => $this->id,
            ]
        );

        $this->facilities()->syncWithoutDetaching($facility->id);

        return $facility;
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

    public function clientProjects()
    {
        return $this->hasMany(Project::class, 'client_user_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function customerBanks()
    {
        return $this->belongsToMany(Bank::class, 'bank_customer')->withTimestamps();
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
        return $this->morphedByMany(Product::class, 'favoritable', 'favorites', 'user_id', 'favoritable_id');
    }

    public function favoriteFacilities()
    {
        return $this->morphedByMany(Facility::class, 'favoritable', 'favorites', 'user_id', 'favoritable_id');
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function ownedContracts()
    {
        return $this->hasMany(Contract::class, 'owner_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
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
     * Assign a role to the user
     *
     * @param string $roleName
     * @return bool
     */
    public function assignRole($roleName)
    {
        $role = Role::where('name', $roleName)->first();

        if (!$role) {
            return false;
        }

        $this->roles()->syncWithoutDetaching([$role->id => ['facility_id' => null]]);

        if (is_null($this->primary_role)) {
            $this->primary_role = $roleName;
            $this->save();
        }

        return true;
    }

    /**
     * Remove a role from the user
     *
     * @param string $roleName
     * @return bool
     */
    public function removeRole($roleName)
    {
        $role = Role::where('name', $roleName)->first();

        if (!$role) {
            return false;
        }

        $this->roles()->detach($role->id);

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
            if ($role->permissions()->whereHas('translations', function($query) use ($permission) {
                $query->where('name', $permission);
            })->exists()) {
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

    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function referredBy()
    {
        return $this->belongsTo(User::class, 'referred_by_user_id');
    }

    public function referredUsers()
    {
        return $this->hasMany(User::class, 'referred_by_user_id');
    }

    public function referralLink()
    {
        if (empty($this->referral_code)) {
            do {
                $code = strtoupper(\Illuminate\Support\Str::random(8));
            } while (self::where('referral_code', $code)->exists());
            $this->referral_code = $code;
            $this->save();
        }

        return url('/register?ref=' . $this->referral_code);
    }

    // علاقات إضافية حسب الحاجة...
}
