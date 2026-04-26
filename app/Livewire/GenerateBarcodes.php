<?php

namespace App\Livewire;

use App\Jobs\GenerateProductBarcodesPdf;
use App\Models\BarcodeExport;
use App\Models\Product;
use App\Traits\StoreScoped;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Picqer\Barcode\BarcodeGeneratorPNG;

#[Layout('layouts.app')]
class GenerateBarcodes extends Component
{
    use WithPagination;
    use StoreScoped;

    public $pageTitle = 'Operations';
    public $componentName = 'Generate Product Barcodes';
    public $searchQuery = '';
    public $selectedProducts = [];
    public $selectAll = false;
    public $barcodeType = 'CODE128';
    public $itemsPerPage = 12;
    public $isGenerating = false;
    public $showDownloadHistory = false;
    public $store_id;

    protected $queryString = ['searchQuery' => ['except' => '']];

    public function mount()
    {
        $this->initStoreId();
    }

    public function render()
    {
        $products = $this->baseProductsQuery()
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.generate-barcodes', [
            'products' => $products,
        ]);
    }

    public function updatedSelectAll($value)
    {
        $this->toggleSelectAll($value);
    }

    public function selectProductToggle($productId)
    {
        $isSelected = !empty($this->selectedProducts[$productId]);
        $this->selectedProducts[$productId] = !$isSelected;
    }

    public function toggleSelectAll($value = null)
    {
        $shouldSelectAll = is_null($value) ? $this->selectAll : (bool) $value;

        if ($shouldSelectAll) {
            $this->selectedProducts = $this->baseProductsQuery()
                ->pluck('id')
                ->mapWithKeys(function ($id) {
                    return [$id => true];
                })
                ->toArray();
        } else {
            $this->selectedProducts = [];
        }
    }

    public function generateBarcodes()
    {
        $selected = array_keys(array_filter($this->selectedProducts));

        if (empty($selected)) {
            session()->flash('alert', [
                'type' => 'warning',
                'title' => 'No Products Selected',
                'message' => 'Please select at least one product to generate barcodes.',
            ]);

            return;
        }

        $this->queueBarcodeExport($selected, count($selected) . ' product(s)');
    }

    public function generateSingleBarcode($productId)
    {
        $product = $this->baseProductsQuery()->whereKey($productId)->first();

        if (!$product) {
            session()->flash('alert', [
                'type' => 'warning',
                'title' => 'Product Not Found',
                'message' => 'Selected product was not found or has no barcode value.',
            ]);
            return;
        }

        $this->queueBarcodeExport([(int) $product->id], "1 product ({$product->name})");
    }

    protected function queueBarcodeExport(array $selectedIds, string $label)
    {
        try {
            $this->isGenerating = true;

            // Validate barcode type
            $validTypes = ['CODE128', 'CODE39', 'EAN13', 'UPCA'];
            if (!in_array($this->barcodeType, $validTypes)) {
                throw new \Exception('Invalid barcode type selected.');
            }

            $selected = array_values(array_unique(array_map('intval', $selectedIds)));
            if (empty($selected)) {
                throw new \Exception('No products selected for export.');
            }

            // Dispatch the job to queue
            $export = BarcodeExport::create([
                'user_id' => Auth::id(),
                'store_id' => $this->getStoreId(),
                'status' => 'queued',
                'barcode_type' => $this->barcodeType,
                'items_per_page' => (int) $this->itemsPerPage,
                'selected_count' => count($selected),
            ]);

            GenerateProductBarcodesPdf::dispatch(
                $selected,
                auth()->id(),
                $this->barcodeType,
                $this->itemsPerPage,
                $export->id,
                $this->getStoreId()
            );

            session()->flash('alert', [
                'type' => 'success',
                'title' => 'Processing Started',
                'message' => $label . ' queued. Open Download History below to download once completed.',
            ]);

            // Reset form
            $this->selectedProducts = [];
            $this->selectAll = false;
            $this->showDownloadHistory = true;
        } catch (\Exception $e) {
            \Log::error('Barcode generation error: ' . $e->getMessage());

            session()->flash('alert', [
                'type' => 'error',
                'title' => 'Generation Failed',
                'message' => 'Failed to start barcode generation: ' . $e->getMessage(),
            ]);
        } finally {
            $this->isGenerating = false;
        }
    }

    public function generateAllBarcodes()
    {
        // Get all products with barcodes
        $allProducts = $this->baseProductsQuery()
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->toArray();

        if (empty($allProducts)) {
            session()->flash('alert', [
                'type' => 'warning',
                'title' => 'No Products Found',
                'message' => 'No products with barcodes found matching your search.',
            ]);

            return;
        }

        $this->queueBarcodeExport($allProducts, count($allProducts) . ' product(s)');
    }

    public function clearSearch()
    {
        $this->searchQuery = '';
        $this->selectedProducts = [];
        $this->selectAll = false;
    }

    public function toggleDownloadHistory()
    {
        $this->showDownloadHistory = !$this->showDownloadHistory;
    }

    public function getRecentExportsProperty()
    {
        return BarcodeExport::where('user_id', auth()->id())
            ->where('store_id', $this->getStoreId())
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
    }

    public function getSelectedCountProperty()
    {
        return count(array_filter($this->selectedProducts));
    }

    public function regenerateBarcodesFromStoreAndProductIds()
    {
        $storeId = (int) $this->getStoreId();

        if (!$storeId) {
            session()->flash('alert', [
                'type' => 'error',
                'title' => 'Store Not Found',
                'message' => 'Unable to resolve current store.',
            ]);
            return;
        }

        $updated = 0;

        Product::where('store_id', $storeId)
            ->withoutTrashed()
            ->select('id')
            ->chunkById(200, function ($products) use ($storeId, &$updated) {
                foreach ($products as $product) {
                    Product::where('id', $product->id)->update([
                        'barcode' => $this->buildBarcodeValue($storeId, (int) $product->id),
                    ]);
                    $updated++;
                }
            });

        session()->flash('alert', [
            'type' => 'success',
            'title' => 'Barcodes Updated',
            'message' => "Updated {$updated} product barcode(s) using store ID + product ID.",
        ]);
    }

    public function updateSingleProductBarcode($productId)
    {
        $storeId = (int) $this->getStoreId();

        if (!$storeId) {
            session()->flash('alert', [
                'type' => 'error',
                'title' => 'Store Not Found',
                'message' => 'Unable to resolve current store.',
            ]);
            return;
        }

        $product = Product::where('store_id', $storeId)
            ->withoutTrashed()
            ->find((int) $productId);

        if (!$product) {
            session()->flash('alert', [
                'type' => 'warning',
                'title' => 'Product Not Found',
                'message' => 'The selected product does not exist in this store.',
            ]);
            return;
        }

        $barcode = $this->buildBarcodeValue($storeId, (int) $product->id);
        $product->update(['barcode' => $barcode]);

        session()->flash('alert', [
            'type' => 'success',
            'title' => 'Barcode Updated',
            'message' => "Barcode updated for {$product->name} ({$barcode}).",
        ]);
    }

    public function barcodePreview(string $barcode): ?string
    {
        if (trim($barcode) === '') {
            return null;
        }

        try {
            $generator = new BarcodeGeneratorPNG();
            $png = $generator->getBarcode($barcode, $generator::TYPE_CODE_128, 2, 40);
            return 'data:image/png;base64,' . base64_encode($png);
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function buildBarcodeValue(int $storeId, int $productId): string
    {
        return $storeId . str_pad((string) $productId, 6, '0', STR_PAD_LEFT);
    }

    protected function baseProductsQuery()
    {
        return Product::where('store_id', $this->getStoreId())
            ->whereNotNull('barcode')
            ->where('barcode', '!=', '')
            ->when($this->searchQuery, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('name', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('barcode', 'like', '%' . $this->searchQuery . '%');
                });
            });
    }
}
