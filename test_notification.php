<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Product;
use App\Models\Booking;
use App\Notifications\BookingCreated;
use App\Notifications\NewProductAdded;
use App\Notifications\BookingStatusChanged;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing Notification System...\n";

// Get a test user
$user = User::where('primary_role', 'client')->first();
if (!$user) {
    echo "No client users found. Please run migrations and seeders first.\n";
    exit;
}

echo "Found user: {$user->name}\n";

// Get a test product
$product = Product::first();
if (!$product) {
    echo "No products found. Please run migrations and seeders first.\n";
    exit;
}

echo "Found product: {$product->name}\n";

// Test booking created notification
echo "\nTesting BookingCreated notification...\n";
$booking = Booking::create([
    'user_id' => $user->id,
    'product_id' => $product->id,
    'facility_id' => $product->facility_id,
    'booking_date' => now()->addDays(7),
    'total_amount' => 25000,
    'status' => 'pending',
    'payment_status' => 'pending',
]);

$user->notify(new BookingCreated($booking));
echo "✓ BookingCreated notification sent\n";

// Test new product notification
echo "\nTesting NewProductAdded notification...\n";
$user->notify(new NewProductAdded($product));
echo "✓ NewProductAdded notification sent\n";

// Test status change notification
echo "\nTesting BookingStatusChanged notification...\n";
$user->notify(new BookingStatusChanged($booking, 'pending', 'confirmed'));
echo "✓ BookingStatusChanged notification sent\n";

// Check notifications
$notifications = $user->notifications;
echo "\nTotal notifications: {$notifications->count()}\n";
echo "Unread notifications: {$user->unreadNotifications->count()}\n";

foreach ($notifications->take(3) as $notification) {
    echo "- {$notification->data['message']} ({$notification->created_at->diffForHumans()})\n";
}

echo "\nNotification system test completed successfully!\n";
