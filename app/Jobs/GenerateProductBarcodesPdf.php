<?php

namespace App\Jobs;

use App\Models\BarcodeExport;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGenerator;
use Picqer\Barcode\BarcodeGeneratorPNG;

class GenerateProductBarcodesPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $productIds;
    protected $userId;
    protected $barcodeType;
    protected $itemsPerPage;
    protected $exportId;
    protected $storeId;

    public function __construct(array $productIds, $userId, $barcodeType = 'CODE128', $itemsPerPage = 12, $exportId = null, $storeId = null)
    {
        $this->productIds = $productIds;
        $this->userId = $userId;
        $this->barcodeType = $barcodeType;
        $this->itemsPerPage = $itemsPerPage;
        $this->exportId = $exportId;
        $this->storeId = $storeId;
    }

    public function handle(): void
    {
        $export = null;

        try {
            if ($this->exportId) {
                $export = BarcodeExport::find($this->exportId);
                if ($export) {
                    $export->update([
                        'status' => 'processing',
                        'error_message' => null,
                    ]);
                }
            }

            $productsQuery = Product::whereIn('id', $this->productIds)
                ->whereNotNull('barcode')
                ->where('barcode', '!=', '');

            if ($this->storeId) {
                $productsQuery->where('store_id', $this->storeId);
            }

            $products = $productsQuery
                ->select('id', 'name', 'barcode')
                ->get();

            if ($products->isEmpty()) {
                throw new \Exception('No products with barcodes found.');
            }

            $barcodes = [];
            foreach ($products as $product) {
                try {
                    $generator = new BarcodeGeneratorPNG();
                    $barcodeValue = $product->barcode;

                    $originalValue = $barcodeValue;
                    $barcodeValue = strtoupper($barcodeValue);
                    $barcodeValue = preg_replace('/[^A-Z0-9 \-\.\\/\+\%]/', '', $barcodeValue);

                    if (empty($barcodeValue)) {
                        $barcodeValue = 'ITEM-' . $product->id;
                    }

                    $typeConstant = $this->getSafeBarcodeType($barcodeValue);
                    $barcodeData = $generator->getBarcode($barcodeValue, $typeConstant);
                    $barcodes[] = [
                        'product_name' => $product->name,
                        'barcode' => $originalValue,
                        'barcode_data' => base64_encode($barcodeData),
                    ];
                } catch (\Exception $e) {
                    \Log::warning("Failed to generate barcode for product {$product->id} ({$product->barcode}): " . $e->getMessage() . " - " . get_class($e));
                }
            }

            if (empty($barcodes)) {
                throw new \Exception('Failed to generate any barcodes.');
            }

            $timestamp = now()->format('YmdHis');
            $filename = 'barcodes_' . $timestamp . '_' . ($this->exportId ?: uniqid()) . '.pdf';
            $filePath = "barcodes/{$filename}";

            $pdf = Pdf::loadView('barcodes.barcode-pdf', [
                'barcodes' => $barcodes,
                'itemsPerPage' => $this->itemsPerPage,
            ])
                ->setOption('enable_html5_parser', true)
                ->setOption('isPhpEnabled', true);

            Storage::disk('local')->put($filePath, $pdf->output());

            if ($export) {
                $export->update([
                    'status' => 'completed',
                    'filename' => $filename,
                    'file_path' => $filePath,
                    'completed_at' => now(),
                    'error_message' => null,
                ]);
            }

            \Log::info("Barcodes PDF generated successfully: {$filename}", [
                'user_id' => $this->userId,
                'product_count' => count($barcodes),
                'file' => $filename,
            ]);
        } catch (\Exception $e) {
            if ($export) {
                $export->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
            }

            \Log::error("Barcode generation failed: {$e->getMessage()}", [
                'user_id' => $this->userId,
                'product_ids' => $this->productIds,
            ]);
            throw $e;
        }
    }

    private function barcodeTypeConstant($requestedType)
    {
        $constants = [
            'CODE128' => BarcodeGenerator::TYPE_CODE_128,
            'CODE39' => BarcodeGenerator::TYPE_CODE_39,
            'EAN13' => BarcodeGenerator::TYPE_EAN_13,
            'UPCA' => BarcodeGenerator::TYPE_UPC_A,
        ];
        return $constants[$requestedType] ?? BarcodeGenerator::TYPE_CODE_128;
    }

    private function getSafeBarcodeType($barcodeValue)
    {
        $constants = $this->barcodeTypeConstant($this->barcodeType);
        if ($constants === BarcodeGenerator::TYPE_CODE_128
            && !preg_match('/^[0-9A-Z]+$/i', $barcodeValue)) {
            return BarcodeGenerator::TYPE_CODE_39;
        }
        return $constants;
    }

    public function failed(\Throwable $exception): void
    {
        if ($this->exportId) {
            $export = BarcodeExport::find($this->exportId);
            if ($export) {
                $export->update([
                    'status' => 'failed',
                    'error_message' => $exception->getMessage(),
                ]);
            }
        }

        \Log::error("GenerateProductBarcodesPdf job failed: {$exception->getMessage()}", [
            'user_id' => $this->userId,
            'product_ids' => $this->productIds,
        ]);
    }
}
