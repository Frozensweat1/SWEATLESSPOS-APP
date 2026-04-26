<?php

namespace App\Http\Controllers;

use App\Models\BarcodeExport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BarcodeController extends Controller
{
    /**
     * Download generated barcode PDF
     */
    public function download($filename)
    {
        // Validate filename format (prevent directory traversal)
        if (!preg_match('/^barcodes_\d+\.pdf$/', $filename)) {
            return abort(400, 'Invalid filename');
        }

        $filepath = "barcodes/{$filename}";

        // Check if file exists
        if (!Storage::disk('local')->exists($filepath)) {
            return abort(404, 'File not found');
        }

        // Get file content
        $file = Storage::disk('local')->get($filepath);

        return new StreamedResponse(function () use ($file) {
            echo $file;
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    /**
     * Download generated barcode PDF by export record.
     */
    public function downloadExport(BarcodeExport $export)
    {
        if ($export->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($export->status !== 'completed' || !$export->file_path) {
            abort(404, 'File is not ready for download');
        }

        if (!Storage::disk('local')->exists($export->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('local')->download(
            $export->file_path,
            $export->filename ?? basename($export->file_path),
            ['Content-Type' => 'application/pdf']
        );
    }

    /**
     * List all generated barcodes
     */
    public function list()
    {
        $files = Storage::disk('local')->files('barcodes');

        $barcodes = array_map(function ($file) {
            return [
                'name' => basename($file),
                'size' => Storage::disk('local')->size($file),
                'modified' => Storage::disk('local')->lastModified($file),
                'url' => route('barcode.download', basename($file)),
            ];
        }, $files);

        // Sort by modified date, newest first
        usort($barcodes, function ($a, $b) {
            return $b['modified'] <=> $a['modified'];
        });

        return response()->json([
            'success' => true,
            'files' => $barcodes,
        ]);
    }

    /**
     * Delete a generated barcode file
     */
    public function delete($filename)
    {
        // Validate filename format
        if (!preg_match('/^barcodes_\d+\.pdf$/', $filename)) {
            return abort(400, 'Invalid filename');
        }

        $filepath = "barcodes/{$filename}";

        if (Storage::disk('local')->exists($filepath)) {
            Storage::disk('local')->delete($filepath);

            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'File not found',
        ], 404);
    }
}
