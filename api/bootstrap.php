<?php
declare(strict_types=1);

// CORS + JSON bootstrap
$config = require __DIR__ . '/config.php';

header('Content-Type: application/json');
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin && (in_array('*', $config['allowed_origins'], true) || in_array($origin, $config['allowed_origins'], true))) {
    header('Access-Control-Allow-Origin: ' . ($origin ?: '*'));
    header('Vary: Origin');
}
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

function json_ok($data = []) { echo json_encode($data, JSON_UNESCAPED_SLASHES); exit; }
function json_err(int $code, string $msg) { http_response_code($code); echo json_encode(['error' => $msg]); exit; }

function pdo_or_null(array $cfg): ?PDO {
    if (strpos($cfg['dsn'] ?? '', 'YOUR_DB_HOST') !== false) return null; // not configured
    try {
        $pdo = new PDO($cfg['dsn'], $cfg['user'], $cfg['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    } catch (Throwable $e) {
        return null; // fail soft if DB not ready
    }
}

// No database used in this setup
$pdo = null;


