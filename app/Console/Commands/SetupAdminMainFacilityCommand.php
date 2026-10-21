<?php

namespace App\Console\Commands;

use App\Models\Facility;
use App\Models\FacilityCategory;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SetupAdminMainFacilityCommand extends Command
{
    protected $signature = 'facility:setup-admin-main {phone : User phone number with or without country code}';

    protected $description = 'Find an admin by phone and ensure they own the primary hybrid facility.';

    public function handle(): int
    {
        $phone = $this->argument('phone');
        $phone = $this->normalizePhone($phone);

        $user = User::query()
            ->where('phone_number', $phone)
            ->first();

        if (! $user) {
            $this->error("User not found with phone: {$phone}");
            return self::FAILURE;
        }

        $this->ensureRole('admin');
        $this->ensureRole('facility');

        if (! $user->hasRole('admin')) {
            $user->assignRole('admin');
            $this->info('Assigned admin role.');
        }

        if (! $user->hasRole('facility')) {
            $user->assignRole('facility');
            $this->info('Assigned facility role.');
        }

        $facility = $user->mainFacility();

        if (! $facility) {
            $this->error('Unable to create main facility.');
            return self::FAILURE;
        }

        $hybridCategory = FacilityCategory::query()
            ->where('platform_mode', 'lifecycle')
            ->first();

        if (! $hybridCategory) {
            $hybridCategory = FacilityCategory::create([
                'name' => 'hybrid',
                'display_name' => 'شركة قابضة / مطور',
                'description' => 'منشأة هجينة تجمع بين العقارات والمقاولات',
                'is_active' => true,
                'platform_mode' => 'lifecycle',
            ]);
            $this->info('Created hybrid facility category.');
        }

        $facility->update([
            'facility_category_id' => $hybridCategory->id,
            'is_primary' => true,
            'is_active' => true,
            'is_verified' => true,
        ]);

        $user->facilities()->syncWithoutDetaching($facility->id);

        $this->info("User #{$user->id} ({$user->name}) is now admin.");
        $this->info("Facility #{$facility->id} ({$facility->name}) is primary and hybrid.");

        return self::SUCCESS;
    }

    private function ensureRole(string $name): void
    {
        Role::firstOrCreate(
            ['name' => $name],
            ['display_name' => ucfirst($name), 'description' => 'Auto-created role']
        );
    }

    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (Str::startsWith($phone, '00')) {
            $phone = substr($phone, 2);
        }

        return $phone;
    }
}
