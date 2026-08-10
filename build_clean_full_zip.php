<?php

$destZip = 'C:/Users/GIDEON/AppData/Local/Temp/SIPENA_GENBI_FULL_100.zip';
$downloadsZip = 'C:/Users/GIDEON/Downloads/SIPENA_GENBI_LENGKAP_100.zip';

if (file_exists($destZip)) {
    @unlink($destZip);
}
if (file_exists($downloadsZip)) {
    @unlink($downloadsZip);
}

$zip = new ZipArchive();
if ($zip->open($destZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    die("Gagal membuat zip\n");
}

$baseDir = __DIR__;
$sourceDirs = ['app', 'bootstrap', 'config', 'database', 'public', 'resources', 'routes', 'storage', 'vendor'];
$sourceFiles = ['.htaccess', '.env.example', 'artisan', 'composer.json', 'composer.lock', 'package.json'];

foreach ($sourceFiles as $f) {
    $fullPath = $baseDir . '/' . $f;
    if (file_exists($fullPath)) {
        $zip->addFile($fullPath, $f);
    }
}

foreach ($sourceDirs as $d) {
    $dirPath = $baseDir . '/' . $d;
    if (!is_dir($dirPath)) continue;

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dirPath, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($baseDir) + 1);
            $relativePath = str_replace('\\', '/', $relativePath);
            $zip->addFile($filePath, $relativePath);
        }
    }
}

$zip->close();

@copy($destZip, $downloadsZip);
@copy($destZip, 'D:/Project Laravel/sipena-genbi/SIPENA_GENBI_HOSTING_OK.zip');
@copy($destZip, 'C:/Users/GIDEON/Downloads/SIPENA_GENBI_HOSTING_OK.zip');

echo "SUCCESS! " . round(filesize($destZip) / 1024 / 1024, 2) . " MB created.\n";
