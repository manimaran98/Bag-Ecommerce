<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Services\AdminAssetService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ReceiptDownloadController extends Controller
{
    public function __construct(
        private readonly AdminAssetService $assets
    ) {}

    public function show(Request $request): StreamedResponse|Response
    {
        $fileName = basename((string) $request->query('payment_resit', ''));
        if ($fileName === '') {
            abort(404);
        }

        $full = $this->assets->safePathUnder($this->assets->receiptDirectory(), $fileName);
        if ($full === null || ! is_readable($full)) {
            abort(404);
        }

        $user = $request->user();
        if ($user === null) {
            abort(403);
        }

        $allowed = $user->isAdmin();
        if (! $allowed) {
            $allowed = Purchase::query()
                ->where('id', $user->id)
                ->where('payment_resit', $fileName)
                ->exists();
        }

        if (! $allowed) {
            abort(403);
        }

        $mime = @mime_content_type($full) ?: 'application/octet-stream';
        $safeName = str_replace(["\r", "\n", '"'], '', $fileName);

        return response()->streamDownload(function () use ($full): void {
            readfile($full);
        }, $safeName, [
            'Content-Type' => $mime,
            'Cache-Control' => 'private',
        ]);
    }
}
