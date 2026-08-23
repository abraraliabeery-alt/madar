<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class MakeAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('phone_number', '0550880798')->first();

        if ($user) {
            $user->update([
                'primary_role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            $role = Role::where('name', 'admin')->first();
            if ($role) {
                $user->roles()->sync([$role->id => ['facility_id' => null]]);
            }
        }
    }
}
