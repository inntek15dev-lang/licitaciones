<?php

/**
 * T-CAS: Connectivity Assurance Helper Script
 * Validates DB and Service connectivity based on LACG-ENV-v2.0
 */

function checkDatabase($host, $port, $database, $username, $password)
{
    echo "Checking Database connection ($host:$port)... ";
    try {
        $dsn = "mysql:host=$host;port=$port;dbname=$database";
        $pdo = new PDO($dsn, $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        echo "SUCCESS.\n";
        return true;
    } catch (PDOException $e) {
        echo "FAILED: " . $e->getMessage() . "\n";
        return false;
    }
}

function checkService($name, $host, $port)
{
    echo "Checking Service $name ($host:$port)... ";
    $connection = @fsockopen($host, $port, $errno, $errstr, 2);
    if ($connection) {
        echo "SUCCESS.\n";
        fclose($connection);
        return true;
    } else {
        echo "FAILED: $errstr ($errno)\n";
        return false;
    }
}

// Load .env manually for standalone check
$envFile = __DIR__ . '/../../../../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0)
            continue;
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim($value));
    }
}

$dbHost = getenv('DB_HOST') ?: '127.0.0.1';
$dbPort = getenv('DB_PORT') ?: '3306';
$dbName = getenv('DB_DATABASE');
$dbUser = getenv('DB_USERNAME');
$dbPass = getenv('DB_PASSWORD');

$dbStatus = checkDatabase($dbHost, $dbPort, $dbName, $dbUser, $dbPass);

// Check Vite
checkService('Vite HMR', '127.0.0.1', 5173);

// Check Redis if configured
if ($redisHost = getenv('REDIS_HOST')) {
    checkService('Redis', $redisHost, getenv('REDIS_PORT') ?: 6379);
}

if (!$dbStatus) {
    echo "\n[RECOVERY SUGGESTION]: If running in Laragon, ensure MySQL is started. If in Docker, try host 'db_container' or 'host.docker.internal'.\n";
}
