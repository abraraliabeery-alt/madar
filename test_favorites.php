<?php

/**
 * Simple test file to verify favorites functionality
 * This file can be run to test the favorites system
 */

require_once 'vendor/autoload.php';

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Product;
use App\Models\Facility;
use Illuminate\Support\Facades\DB;

echo "Testing Favorites Functionality\n";
echo "===============================\n\n";

try {
    // Test 1: Check if favorites table exists
    echo "1. Checking if favorites table exists...\n";
    $tableExists = DB::getSchemaBuilder()->hasTable('favorites');
    echo "   Table exists: " . ($tableExists ? "YES" : "NO") . "\n\n";

    if (!$tableExists) {
        echo "   Creating favorites table...\n";
        // Run the migration
        $artisan = $app->make('Illuminate\Contracts\Console\Kernel');
        $artisan->call('migrate', ['--path' => 'database/migrations/2025_01_01_000000_create_favorites_table.php']);
        echo "   Migration completed.\n\n";
    }

    // Test 2: Check if we have users, products, and facilities
    echo "2. Checking data availability...\n";
    $userCount = User::count();
    $productCount = Product::count();
    $facilityCount = Facility::count();
    
    echo "   Users: $userCount\n";
    echo "   Products: $productCount\n";
    echo "   Facilities: $facilityCount\n\n";

    if ($userCount == 0 || $productCount == 0 || $facilityCount == 0) {
        echo "   Need to seed data first. Run: php artisan db:seed\n\n";
        exit;
    }

    // Test 3: Test adding a favorite
    echo "3. Testing favorite functionality...\n";
    $user = User::first();
    $product = Product::first();
    $facility = Facility::first();

    echo "   User: " . $user->name . "\n";
    echo "   Product: " . $product->name . "\n";
    echo "   Facility: " . $facility->name . "\n\n";

    // Add product to favorites
    $user->favoriteProducts()->attach($product->id);
    echo "   Added product to favorites\n";

    // Add facility to favorites
    $user->favoriteFacilities()->attach($facility->id);
    echo "   Added facility to favorites\n\n";

    // Test 4: Check favorites count
    echo "4. Checking favorites count...\n";
    $favoriteProductsCount = $user->favoriteProducts()->count();
    $favoriteFacilitiesCount = $user->favoriteFacilities()->count();
    
    echo "   Favorite Products: $favoriteProductsCount\n";
    echo "   Favorite Facilities: $favoriteFacilitiesCount\n\n";

    // Test 5: Test removing favorites
    echo "5. Testing remove functionality...\n";
    $user->favoriteProducts()->detach($product->id);
    $user->favoriteFacilities()->detach($facility->id);
    echo "   Removed favorites\n\n";

    // Final check
    $finalProductCount = $user->favoriteProducts()->count();
    $finalFacilityCount = $user->favoriteFacilities()->count();
    
    echo "6. Final check...\n";
    echo "   Favorite Products: $finalProductCount\n";
    echo "   Favorite Facilities: $finalFacilityCount\n\n";

    if ($finalProductCount == 0 && $finalFacilityCount == 0) {
        echo "✅ All tests passed! Favorites functionality is working correctly.\n";
    } else {
        echo "❌ Some tests failed. Please check the implementation.\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
