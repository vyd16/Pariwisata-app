<?php


require_once __DIR__ . '/../lib/env.php';

try {
    // Database credentials
    $host = env('DB_HOST', '');
    $dbname = env('DB_NAME', '');
    $username = env('DB_USER', '');
    $password = env('DB_PASS', '');

    // DSN
    $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";

    // PDO options
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    // Create PDO instance
    $pdo = new PDO($dsn, $username, $password, $options);

} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

function base_url(string $path = ''): string
{
    // Check for HTTPS - also check X-Forwarded-Proto for reverse proxies like ngrok
    $isHttps = (
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
        (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
        (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on') ||
        (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
    );

    $scheme = $isHttps ? 'https' : 'http';

    $host = $_SERVER['HTTP_HOST'];

    $base = $scheme . '://' . $host . '/wisata-app';

    return rtrim($base, '/') . '/' . ltrim($path, '/');
}

