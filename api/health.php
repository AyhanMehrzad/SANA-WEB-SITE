<?php
require __DIR__ . '/bootstrap.php';

$status = ['ok' => true, 'php' => PHP_VERSION, 'time' => gmdate('c')];
json_ok($status);


