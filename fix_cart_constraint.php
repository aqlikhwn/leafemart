<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Current Cart Table Indexes ===\n\n";

$indexes = DB::select('SHOW INDEX FROM carts');
$uniqueIndexes = [];

foreach ($indexes as $index) {
    if (!isset($uniqueIndexes[$index->Key_name])) {
        $uniqueIndexes[$index->Key_name] = [];
    }
    $uniqueIndexes[$index->Key_name][] = $index->Column_name;
    echo "Index: {$index->Key_name} | Column: {$index->Column_name} | Unique: " . ($index->Non_unique ? 'NO' : 'YES') . "\n";
}

echo "\n=== Grouped Indexes ===\n\n";
foreach ($uniqueIndexes as $name => $columns) {
    echo "$name: " . implode(', ', $columns) . "\n";
}

// Check for old constraint
$hasOld = isset($uniqueIndexes['carts_user_id_product_id_unique']);
$hasNew = isset($uniqueIndexes['carts_user_product_variation_unique']);

echo "\n=== Status ===\n";
echo "Old constraint (user_id, product_id): " . ($hasOld ? 'EXISTS - PROBLEM!' : 'Not found - Good') . "\n";
echo "New constraint (user_id, product_id, variation): " . ($hasNew ? 'EXISTS - Good' : 'Not found - PROBLEM!') . "\n";

if ($hasOld) {
    echo "\n>>> Dropping old constraint...\n";
    DB::statement('DROP INDEX carts_user_id_product_id_unique ON carts');
    echo "Old constraint dropped!\n";
}

if (!$hasNew) {
    echo "\n>>> Creating new constraint...\n";
    // Update nulls first
    DB::statement("UPDATE carts SET variation = '' WHERE variation IS NULL");
    DB::statement('CREATE UNIQUE INDEX carts_user_product_variation_unique ON carts (user_id, product_id, variation(191))');
    echo "New constraint created!\n";
}

echo "\n=== Final Verification ===\n";
$indexes = DB::select('SHOW INDEX FROM carts');
foreach ($indexes as $index) {
    echo "Index: {$index->Key_name} | Column: {$index->Column_name}\n";
}

echo "\nDone!\n";
