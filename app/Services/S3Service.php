<?php

namespace App\Services;
class S3Service
{
    public function __construct()
    {
    }

    public function uploadImage(string $localFilePath, string $folder = 'profiles'): array
    {
        $file = new \Illuminate\Http\File($localFilePath);
        $disk = env('FILESYSTEM_DISK', 's3');
        $path = \Illuminate\Support\Facades\Storage::disk($disk)->putFile($folder, $file);

        if ($path === false) {
            throw new \Exception("Failed to upload image to S3. Check AWS credentials and bucket permissions.");
        }

        return [
            'url' => \Illuminate\Support\Facades\Storage::disk($disk)->url($path),
            'public_id' => $path,
        ];
    }

    public function extractPublicId(?string $url): ?string
    {
        if (!$url) return null;
        
        $disk = env('FILESYSTEM_DISK', 's3');
        $dummyUrl = \Illuminate\Support\Facades\Storage::disk($disk)->url('dummy');
        $baseUrl = substr($dummyUrl, 0, -5);
        
        if (str_starts_with($url, $baseUrl)) {
            return substr($url, strlen($baseUrl));
        }

        $parsed = parse_url($url, PHP_URL_PATH);
        return $parsed ? ltrim($parsed, '/') : null;
    }

    public function deleteImage(?string $publicIdOrUrl): void
    {
        if (!$publicIdOrUrl) {
            return;
        }

        $disk = env('FILESYSTEM_DISK', 's3');

        if (filter_var($publicIdOrUrl, FILTER_VALIDATE_URL)) {
            $publicIdOrUrl = $this->extractPublicId($publicIdOrUrl);
            if (!$publicIdOrUrl) return;
        }

        \Illuminate\Support\Facades\Storage::disk($disk)->delete($publicIdOrUrl);
    }
}
