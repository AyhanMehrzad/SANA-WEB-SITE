<?php
require __DIR__ . '/bootstrap.php';

// Simple read-only gallery from assets/pic
$picDir = realpath(__DIR__ . '/../assets/pic');
if (!$picDir) json_ok(['items' => []]);

$files = array_values(array_filter(scandir($picDir), function ($f) use ($picDir) {
    return preg_match('/\.(jpe?g|png|gif|webp)$/i', $f) && is_file($picDir . DIRECTORY_SEPARATOR . $f);
}));

$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? '');
$prefix = dirname($_SERVER['SCRIPT_NAME'] ?? '') !== '/' ? dirname($_SERVER['SCRIPT_NAME']) : '';

$items = array_map(function ($f) use ($baseUrl, $prefix) {
    return [
        'file' => $f,
        'url' => $baseUrl . $prefix . '/../assets/pic/' . rawurlencode($f),
    ];
}, $files);

json_ok(['items' => $items]);


