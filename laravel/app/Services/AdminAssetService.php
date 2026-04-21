<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

final class AdminAssetService
{
    public function stockImgDirectory(): string
    {
        return public_path('assets/stockImg');
    }

    public function receiptDirectory(): string
    {
        return public_path('assets/receipt');
    }

    public function ensureUploadDirectories(): void
    {
        foreach ([$this->stockImgDirectory(), $this->receiptDirectory()] as $dir) {
            if (! is_dir($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
        }
    }

    public function storeStockImage(UploadedFile $file): ?string
    {
        $this->ensureUploadDirectories();
        $ext = strtolower($file->getClientOriginalExtension());
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (! in_array($ext, $allowed, true)) {
            return null;
        }
        $name = bin2hex(random_bytes(8)) . '.' . $ext;
        $file->move($this->stockImgDirectory(), $name);

        return $name;
    }

    public function storeReceipt(UploadedFile $file): ?string
    {
        $this->ensureUploadDirectories();
        $ext = strtolower($file->getClientOriginalExtension());
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'];
        if (! in_array($ext, $allowed, true)) {
            return null;
        }
        $name = 'rcpt_' . bin2hex(random_bytes(8)) . '.' . $ext;
        $file->move($this->receiptDirectory(), $name);

        return $name;
    }

    public function safePathUnder(string $directory, ?string $filename): ?string
    {
        if ($filename === null || $filename === '') {
            return null;
        }
        $clean = basename($filename);
        if ($clean === '' || str_contains($clean, '..')) {
            return null;
        }
        $base = realpath($directory);
        if ($base === false) {
            return null;
        }
        $full = realpath($base . DIRECTORY_SEPARATOR . $clean);
        if ($full === false || ! str_starts_with($full, $base)) {
            return null;
        }

        return $full;
    }

    public function deleteStockImageFile(?string $filename): void
    {
        $path = $this->safePathUnder($this->stockImgDirectory(), $filename);
        if ($path !== null && is_file($path)) {
            @unlink($path);
        }
    }

    public function deleteReceiptFile(?string $filename): void
    {
        $path = $this->safePathUnder($this->receiptDirectory(), $filename);
        if ($path !== null && is_file($path)) {
            @unlink($path);
        }
    }
}
