<?php

$destZip = 'C:/Users/GIDEON/Desktop/SIPENA_GENBI_DEPLOYMENT.zip';

if (file_exists($destZip)) {
    @unlink($destZip);
}

$zip = new ZipArchive();
if ($zip->open($destZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    die("Gagal membuat zip\n");
}

$baseDir = __DIR__;
$sourceDirs = ['app', 'bootstrap', 'config', 'database', 'public', 'resources', 'routes', 'vendor'];
$sourceFiles = ['.htaccess', 'artisan', 'composer.json', 'composer.lock', '.env.example'];

foreach ($sourceFiles as $f) {
    $fullPath = $baseDir . '/' . $f;
    if (file_exists($fullPath) && !is_link($fullPath)) {
        $zip->addFile($fullPath, $f);
    }
}

foreach ($sourceDirs as $dir) {
    $dirPath = $baseDir . '/' . $dir;
    if (!is_dir($dirPath)) continue;

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dirPath, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($iterator as $fileInfo) {
        if ($fileInfo->isDir() || $fileInfo->isLink()) continue;

        $realPath = $fileInfo->getRealPath();
        if (!$realPath || is_link($realPath)) continue;

        $relativePath = substr($realPath, strlen($baseDir) + 1);
        $relativePath = str_replace('\\', '/', $relativePath);

        $zip->addFile($realPath, $relativePath);
    }
}

$zip->close();
echo "SUCCESS! Created SIPENA_GENBI_DEPLOYMENT.zip (" . filesize($destZip) . " bytes)\n";
