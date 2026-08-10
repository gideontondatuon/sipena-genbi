<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageOptimizationService
{
    /**
     * Compress screenshot file and calculate unique MD5 hash for duplicate detection.
     *
     * @param UploadedFile $file
     * @param string $folder Relative path inside storage/app/public
     * @return array ['path' => string, 'hash' => string]
     */
    public function optimizeAndStore(UploadedFile $file, string $folder): array
    {
        $realPath = $file->getRealPath();
        $hash = md5_file($realPath);

        // Generate clean unique filename
        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
            $extension = 'jpg';
        }
        $filename = time() . '_' . substr($hash, 0, 10) . '.' . $extension;
        $relativeStoragePath = trim($folder, '/') . '/' . $filename;

        // Compress image using native GD if available
        $compressedData = $this->compressImageGD($realPath, $extension);

        if ($compressedData) {
            Storage::disk('public')->put($relativeStoragePath, $compressedData);
        } else {
            // Fallback to direct store if GD fails
            $path = $file->storeAs($folder, $filename, 'public');
            $relativeStoragePath = $path;
        }

        return [
            'path' => $relativeStoragePath,
            'hash' => $hash,
        ];
    }

    /**
     * Internal helper to compress image data via GD library.
     */
    private function compressImageGD(string $sourcePath, string $extension): ?string
    {
        if (!function_exists('imagecreatefromstring')) {
            return null;
        }

        try {
            $imageContent = file_get_contents($sourcePath);
            $srcImage = @imagecreatefromstring($imageContent);

            if (!$srcImage) {
                return null;
            }

            $width = imagesx($srcImage);
            $height = imagesy($srcImage);

            // Max dimension constraint (e.g. 1280px max)
            $maxDimension = 1280;
            if ($width > $maxDimension || $height > $maxDimension) {
                if ($width >= $height) {
                    $newWidth = $maxDimension;
                    $newHeight = (int) ($height * ($maxDimension / $width));
                } else {
                    $newHeight = $maxDimension;
                    $newWidth = (int) ($width * ($maxDimension / $height));
                }

                $dstImage = imagecreatetruecolor($newWidth, $newHeight);
                // Retain transparency for PNG/WebP
                imagealphablending($dstImage, false);
                imagesavealpha($dstImage, true);

                imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagedestroy($srcImage);
                $srcImage = $dstImage;
            }

            ob_start();
            if ($extension === 'png') {
                imagepng($srcImage, null, 7); // Compression 0-9
            } elseif ($extension === 'webp') {
                imagewebp($srcImage, null, 80);
            } else {
                imagejpeg($srcImage, null, 80); // JPEG quality 80%
            }
            $outputBuffer = ob_get_clean();
            imagedestroy($srcImage);

            return $outputBuffer;
        } catch (\Exception $e) {
            return null;
        }
    }
}
