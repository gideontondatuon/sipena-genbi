<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageOptimizationService
{
    /**
     * Compress screenshot file and calculate unique hash based on original filename.
     *
     * @param UploadedFile $file
     * @param string $folder Relative path inside storage/app/public
     * @return array ['path' => string, 'hash' => string, 'original_name' => string]
     */
    public function optimizeAndStore(UploadedFile $file, string $folder): array
    {
        $realPath = $file->getRealPath();
        
        // Deteksi duplikasi: Hanya nama file asli yang sama yang dideteksi sebagai duplikat
        $originalName = trim($file->getClientOriginalName());
        $normalizedName = strtolower($originalName);
        $hash = md5($normalizedName);

        // Tentukan ekstensi target
        $origExt = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $targetExtension = in_array($origExt, ['webp']) ? 'webp' : 'jpg';

        $filename = time() . '_' . substr(md5(uniqid((string)mt_rand(), true)), 0, 8) . '.' . $targetExtension;
        $relativeStoragePath = trim($folder, '/') . '/' . $filename;

        // Kompresi otomatis dengan GD (Resize cerdas & hemat ruang hingga 90%, kualitas tetap tajam)
        $compressedData = $this->compressImageGD($realPath, $origExt, $targetExtension);

        if ($compressedData) {
            Storage::disk('public')->put($relativeStoragePath, $compressedData);
        } else {
            // Fallback jika GD gagal
            $path = $file->storeAs($folder, $filename, 'public');
            $relativeStoragePath = $path;
        }

        return [
            'path' => $relativeStoragePath,
            'hash' => $hash,
            'original_name' => $originalName,
        ];
    }

    /**
     * Internal helper to compress image data via GD library.
     * Mengubah screenshot resolusi tinggi menjadi ukuran hemat ruang dengan teks tetap sangat jelas.
     */
    private function compressImageGD(string $sourcePath, string $origExtension, string $targetExtension): ?string
    {
        if (!function_exists('imagecreatefromstring')) {
            return null;
        }

        try {
            $imageContent = @file_get_contents($sourcePath);
            if (!$imageContent) {
                return null;
            }

            $srcImage = @imagecreatefromstring($imageContent);
            if (!$srcImage) {
                return null;
            }

            $width = imagesx($srcImage);
            $height = imagesy($srcImage);

            if ($width <= 0 || $height <= 0) {
                imagedestroy($srcImage);
                return null;
            }

            // Batas resolusi proporsional: Maksimum 1600px pada sisi terpanjang
            // Resolusi ini menjamin tulisan di screenshot IG (like, komentar, username) tetap tajam & terbaca jelas
            $maxDimension = 1600;
            $newWidth = $width;
            $newHeight = $height;

            if ($width > $maxDimension || $height > $maxDimension) {
                if ($width >= $height) {
                    $newWidth = $maxDimension;
                    $newHeight = (int) round($height * ($maxDimension / $width));
                } else {
                    $newHeight = $maxDimension;
                    $newWidth = (int) round($width * ($maxDimension / $height));
                }
            }

            $dstImage = imagecreatetruecolor($newWidth, $newHeight);

            if ($targetExtension === 'webp') {
                imagealphablending($dstImage, false);
                imagesavealpha($dstImage, true);
            } else {
                // Untuk JPEG, beri latar belakang putih agar PNG transparan tidak menjadi hitam
                $white = imagecolorallocate($dstImage, 255, 255, 255);
                imagefilledrectangle($dstImage, 0, 0, $newWidth, $newHeight, $white);
            }

            imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($srcImage);

            ob_start();
            if ($targetExtension === 'webp' && function_exists('imagewebp')) {
                imagewebp($dstImage, null, 82);
            } else {
                // Kompresi JPEG dengan kualitas 82% (keseimbangan optimal kejernihan teks dan kompresi ukuran file)
                imagejpeg($dstImage, null, 82);
            }
            $outputBuffer = ob_get_clean();
            imagedestroy($dstImage);

            return $outputBuffer ?: null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
