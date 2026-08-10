# Script Automated FTP Uploader for SIPENA GenBI
param (
    [string]$FtpPassword = "#Gideon1709"
)

$ftpHost = "ftpupload.net"
$ftpUser = "if0_42582056"
$localZipPath = "SIPENA_GENBI_HOSTING_OK.zip"

Write-Host "=============================================" -ForegroundColor Cyan
Write-Host " AUTOMATED DEPLOYMENT TOOL TO INFINITYFREE" -ForegroundColor Cyan
Write-Host "=============================================" -ForegroundColor Cyan
Write-Host "Host: $ftpHost"
Write-Host "User: $ftpUser"
Write-Host "Target Remote File: /htdocs/$localZipPath"

if (-not (Test-Path $localZipPath)) {
    Write-Host "Making ZIP package..." -ForegroundColor Yellow
    Compress-Archive -Path 'app', 'bootstrap', 'config', 'database', 'public', 'resources', 'routes', '.htaccess', 'artisan', 'composer.json' -DestinationPath $localZipPath -Force
}

try {
    $webClient = New-Object System.Net.WebClient
    $webClient.Credentials = New-Object System.Net.NetworkCredential($ftpUser, $FtpPassword)
    $uri = "ftp://$ftpHost/htdocs/$localZipPath"
    
    Write-Host "Uploading $localZipPath to InfinityFree server..." -ForegroundColor Yellow
    $webClient.UploadFile($uri, $localZipPath)
    
    Write-Host "`nSUCCESS! $localZipPath has been uploaded to /htdocs/ on InfinityFree!" -ForegroundColor Green
    Write-Host "Now go to InfinityFree File Manager, open /htdocs/ and click Extract!" -ForegroundColor Green
} catch {
    Write-Host "`nFTP Authentication Error (530 Login Failed)." -ForegroundColor Red
    Write-Host "Silakan periksa kembali Password Hosting di Dashboard InfinityFree (Menu Account Settings -> Account Password)." -ForegroundColor Yellow
}
