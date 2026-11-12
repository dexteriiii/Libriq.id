<?php

// Forward Vercel requests to public/index.php
// Pastikan folder cache di /tmp tersedia untuk Vercel Serverless
$storagePaths = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($storagePaths as $path) {
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }
}

require __DIR__ . '/../public/index.php';
