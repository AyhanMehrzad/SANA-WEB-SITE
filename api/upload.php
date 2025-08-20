<?php
require __DIR__ . '/bootstrap.php';

// Upload image directly into assets/pic
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    json_err(405, 'POST required');
}

if (!isset($_FILES['file'])) {
    json_err(400, 'No file');
}

$file = $_FILES['file'];
if ($file['error'] !== UPLOAD_ERR_OK) {
    json_err(400, 'Upload error');
}

$allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);
if (!isset($allowed[$mime])) {
    json_err(400, 'Unsupported type');
}

$ext = $allowed[$mime];
$safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', $file['name']);
if (!preg_match('/\.' . preg_quote($ext, '/') . '$/i', $safeName)) {
    $safeName .= '.' . $ext;
}

$targetDir = realpath(__DIR__ . '/../assets/pic');
if (!$targetDir) json_err(500, 'Pic dir not found');

// Ensure unique filename
$dest = $targetDir . DIRECTORY_SEPARATOR . $safeName;
$base = pathinfo($safeName, PATHINFO_FILENAME);
$i = 1;
while (file_exists($dest)) {
    $dest = $targetDir . DIRECTORY_SEPARATOR . $base . '_' . $i++ . '.' . $ext;
}

if (!move_uploaded_file($file['tmp_name'], $dest)) {
    json_err(500, 'Save failed');
}

// Build URL
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? '');
$prefix = dirname($_SERVER['SCRIPT_NAME'] ?? '') !== '/' ? dirname($_SERVER['SCRIPT_NAME']) : '';
$url = $baseUrl . $prefix . '/../assets/pic/' . rawurlencode(basename($dest));

json_ok(['ok' => true, 'file' => basename($dest), 'url' => $url]);


