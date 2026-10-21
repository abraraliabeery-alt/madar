<?php

namespace Tests;

use App\Models\Facility;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

abstract class TestCase extends BaseTestCase
{
    protected function makeUser(string $role, array $overrides = []): User
    {
        $user = User::create(array_merge([
            'name' => 'Test ' . ucfirst($role),
            'email' => $role . '.' . uniqid() . '@test.local',
            'phone_number' => '+9665' . str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT),
            'password' => Hash::make('password123'),
            'primary_role' => $role,
            'is_active' => true,
        ], $overrides));

        $roleModel = Role::firstOrNew(['name' => $role]);
        if (!$roleModel->exists) {
            $roleModel->save();
        }
        $user->roles()->sync([$roleModel->id => ['facility_id' => null]]);

        return $user;
    }

    protected function makeFacilityUser(): User
    {
        $user = $this->makeUser('facility');
        $facility = Facility::create([
            'name' => 'Test Facility',
            'slug' => 'test-facility-' . $user->id . '-' . uniqid(),
            'is_active' => true,
        ]);
        $user->facilities()->attach($facility->id);

        return $user;
    }

    protected function makeActiveProduct(): Product
    {
        $facilityUser = $this->makeFacilityUser();
        $facility = $facilityUser->facilities()->first();

        $cityId = DB::table('cities')->insertGetId([
            'name' => 'Test City ' . uniqid(),
            'slug' => 'test-city-' . uniqid(),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $product = new Product();
        $product->forceFill([
            'facility_id' => $facility->id,
            'owner_user_id' => $facilityUser->id,
            'city_id' => $cityId,
            'is_active' => true,
            'is_verified' => true,
        ]);
        $product->save();

        return $product;
    }
}
