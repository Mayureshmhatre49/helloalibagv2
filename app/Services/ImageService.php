<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    protected int $maxWidth = 1920;
    protected int $thumbWidth = 400;
    protected int $thumbHeight = 300;
    protected int $quality = 80;

    /**
     * Process and store an uploaded image.
     * Returns ['path' => ..., 'thumbnail' => ...].
     */
    public function store(UploadedFile $file, string $directory = 'listings'): array
    {
        $filename = Str::uuid() . '.webp';
        $thumbFilename = 'thumb_' . $filename;

        // Process main image
        $image = Image::make($file);

        if ($image->width() > $this->maxWidth) {
            $image->resize($this->maxWidth, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        // Save as WebP
        $mainPath = $directory . '/' . $filename;
        Storage::disk('public')->put($mainPath, $image->encode('webp', $this->quality)->encoded);

        // Generate thumbnail
        $thumb = Image::make($file);
        $thumb->fit($this->thumbWidth, $this->thumbHeight);
        $thumbPath = $directory . '/thumbnails/' . $thumbFilename;
        Storage::disk('public')->put($thumbPath, $thumb->encode('webp', $this->quality)->encoded);

        return [
            'path' => '/storage/' . $mainPath,
            'thumbnail' => '/storage/' . $thumbPath,
        ];
    }

    /**
     * Delete an image and its thumbnail.
     */
    public function delete(string $path): void
    {
        $relativePath = str_replace('/storage/', '', $path);
        Storage::disk('public')->delete($relativePath);

        // Try deleting thumbnail
        $dir = dirname($relativePath);
        $filename = basename($relativePath);
        $thumbPath = $dir . '/thumbnails/thumb_' . $filename;
        Storage::disk('public')->delete($thumbPath);
    }
}
