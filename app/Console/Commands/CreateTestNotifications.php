<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Booking;
use App\Models\Product;
use App\Notifications\BookingCreated;
use App\Notifications\BookingStatusChanged;
use App\Notifications\NewProductAdded;

class CreateTestNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:create-test {user_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test notifications for a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        
        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error('User not found!');
                return;
            }
        } else {
            $user = User::where('primary_role', 'client')->first();
            if (!$user) {
                $this->error('No client user found!');
                return;
            }
        }

        $this->info("Creating test notifications for user: {$user->name}");

        // Create a test booking notification
        $booking = Booking::first();
        if ($booking) {
            $user->notify(new BookingCreated($booking));
            $this->info('Created booking notification');
        }

        // Create a test booking status change notification
        if ($booking) {
            $user->notify(new BookingStatusChanged($booking, 'pending', 'confirmed'));
            $this->info('Created booking status change notification');
        }

        // Create a test product notification
        $product = Product::first();
        if ($product) {
            $user->notify(new NewProductAdded($product));
            $this->info('Created new product notification');
        }

        // Create some generic notifications using DatabaseNotification
        $user->notifications()->create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'App\\Notifications\\GenericNotification',
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => $user->id,
            'data' => [
                'type' => 'welcome',
                'message' => 'مرحباً بك في منصة عقار! نتمنى لك تجربة ممتعة.'
            ],
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user->notifications()->create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'App\\Notifications\\GenericNotification',
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => $user->id,
            'data' => [
                'type' => 'profile_updated',
                'message' => 'تم تحديث ملفك الشخصي بنجاح'
            ],
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->info('Created generic notifications');
        $this->info('Test notifications created successfully!');
    }
}
