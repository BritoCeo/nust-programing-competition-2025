<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    DB::statement('DROP TABLE IF EXISTS user_roles');
    echo "Table dropped successfully\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}