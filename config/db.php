<?php
// Load .env values for local environments that do not auto-inject vars.
$envPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (is_array($lines)) {
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || strpos($line, '#') === 0 || strpos($line, '=') === false) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            if ($key !== '' && getenv($key) === false) {
                putenv($key . '=' . $value);
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
}

// Prefer Railway/MySQL env vars when deployed, fallback to local Laragon values.
$host = getenv('MYSQLHOST') ?: getenv('DB_HOST') ?: 'localhost';
$user = getenv('MYSQLUSER') ?: getenv('DB_USER') ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: getenv('DB_PASS') ?: '';
$dbname = getenv('MYSQLDATABASE') ?: getenv('DB_NAME') ?: 'library_system';
$port = (int) (getenv('MYSQLPORT') ?: getenv('DB_PORT') ?: 3306);

// Some hosts expose a single connection URL instead of discrete MYSQL* vars.
$isRailwayRuntime = getenv('RAILWAY_ENVIRONMENT') !== false || getenv('RAILWAY_PROJECT_ID') !== false;
$mysqlUrl = $isRailwayRuntime
    ? (getenv('MYSQL_URL') ?: getenv('MYSQL_PUBLIC_URL') ?: getenv('DATABASE_URL'))
    : (getenv('MYSQL_PUBLIC_URL') ?: getenv('MYSQL_URL') ?: getenv('DATABASE_URL'));
if ($mysqlUrl) {
    $parsed = parse_url($mysqlUrl);
    if (is_array($parsed)) {
        $host = $parsed['host'] ?? $host;
        $user = $parsed['user'] ?? $user;
        $pass = $parsed['pass'] ?? $pass;
        $port = isset($parsed['port']) ? (int) $parsed['port'] : $port;
        if (!empty($parsed['path'])) {
            $parsedDb = ltrim($parsed['path'], '/');
            if ($parsedDb !== '') {
                $dbname = $parsedDb;
            }
        }
    }
}

$conn = new mysqli($host, $user, $pass, $dbname, $port);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset('utf8mb4');
?>
