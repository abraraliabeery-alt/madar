<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command?->warn('No users found. Please run UserSeeder first.');
            return;
        }

        $created = 0;

        foreach ($users as $user) {
            DB::table('notifications')->insert([
                'id' => (string) Str::uuid(),
                'type' => 'App\\Notifications\\GenericDatabaseNotification',
                'notifiable_type' => User::class,
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'title' => 'إشعار تجريبي',
                    'message' => 'تم تجهيز بيانات تجريبية لعرض المنصة (اعتماد).',
                ], JSON_UNESCAPED_UNICODE),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $created++;
        }

        $this->command?->info("Seeded {$created} notifications.");
    }
}
