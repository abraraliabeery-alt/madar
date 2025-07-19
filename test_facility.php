<?php

require_once 'vendor/autoload.php';

use App\Models\Facility;
use App\Models\Category;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Testing Facility model...\n";

    $facilities = Facility::with(['category'])->get();
    echo "Total facilities: " . $facilities->count() . "\n";

    foreach ($facilities as $facility) {
        $categoryName = $facility->category ? $facility->category->name : 'No category';
        echo "- " . $facility->name . " -> Category: " . $categoryName . "\n";
    }

    echo "\nTesting Facility with status...\n";
    $facility = Facility::first();
    if ($facility) {
        echo "Facility: " . $facility->name . "\n";

        // Test statuses relationship
        $statuses = $facility->statuses;
        echo "Statuses count: " . $statuses->count() . "\n";

        // Test status relationship
        $status = $facility->status();
        if ($status) {
            echo "Current status: " . $status->name . "\n";
        } else {
            echo "No status found\n";
        }

    } else {
        echo "No facilities found\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
