<?php

use App\Database\DB;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv::createImmutable(dirname(__FILE__, 2));
$dotenv->load(); // so it throws exception if .env file is missing
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER']);

// Connect using the Medoo wrapper
$db = DB::connect();

echo "Running migrations...\n";

$sql = file_get_contents(__DIR__ . '/migrations/001_create_users_table.sql');
$db->query($sql);
echo "Users table created.\n";

$sql = file_get_contents(__DIR__ . '/migrations/002_create_requests_table.sql');
$db->query($sql);
echo "Requests table created.\n";

$seed = file_get_contents(__DIR__ . '/seeds/users_seed.sql');
$db->query($seed);

$seed = file_get_contents(__DIR__ . '/seeds/requests_seed.sql');
$db->query($seed);

echo "Seed data inserted.\n";
