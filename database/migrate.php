<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Database\DB;

// Connect using the Medoo wrapper
$db = DB::connect();

echo "Running migrations...\n";

$sql = file_get_contents(__DIR__ . '/migrations/001_create_users_table.sql');
$db->query($sql);
echo "Users table created.\n";

$seed = file_get_contents(__DIR__ . '/seeds/users_seed.sql');
$db->query($seed);
echo "Seed data inserted.\n";
