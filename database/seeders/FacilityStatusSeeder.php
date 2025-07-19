<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;
use App\Models\Status;
use App\Models\User;

class FacilityStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = Facility::all();
        $statuses = Status::all();
        $users = User::all();

        foreach ($facilities as $facility) {
            $status = $statuses->random();
            $user = $users->random();

            $facility->statuses()->attach($status->id, [
                'notes' => 'تم تعيين الحالة تلقائياً',
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
