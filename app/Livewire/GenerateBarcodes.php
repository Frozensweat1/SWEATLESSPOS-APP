<?php

namespace App\Livewire;

use App\Jobs\GenerateProductBarcodesPdf;
use App\Models\Product;
use Illuminate\Support\Facades\Queue;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class GenerateBarcodes extends Component
{
    use WithPagination;

    public $pageTitle = 'Operations';
    public $componentName = 'Generate Product Barcodes';
    public $searchQuery = '';
    public $selectedProducts = [];
    public $selectAll = false;
    public $barcodeType = 'CODE128';
    public $itemsPerPage = 12;
    public $isGenerating = false;
    public $showDownloadHistory = false;

    protected $queryString = ['searchQuery' => ['except' => '']];

    public function mount()
    {
        $this->initStoreId();
    }

    public function initStoreId()
    {
        // Initialize store context if using multi-store
        if (method_exists($this, 'getStoreId')) {
            $this->store_id = $this->getStoreId();
        }
    }

    public function render()
    {
        $products = Product::where('barcode', '!=', null)
            ->when($this->searchQuery, function ($query) {
                $query->where('name', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('barcode', 'like', '%' . $this->searchQuery . '%');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.generate-barcodes', [
            'products' => $products,
        ]);
    }

    public function selectProductToggle($productId)
    {
        unset($this->selectedProducts[$productId]);
    }

    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedProducts = Product::where('barcode', '!=', null)
                ->when($this->searchQuery, function ($query) {
                    $query->where('name', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('barcode', 'like', '%' . $this->searchQuery . '%');
                })
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

        try {
            $this->isGenerating = true;

            // Validate barcode type
            $validTypes = ['CODE128', 'CODE39', 'EAN13', 'UPCA'];
            if (!in_array($this->barcodeType, $validTypes)) {
                throw new \Exception('Invalid barcode type selected.');
            }

            // Dispatch the job to queue
            GenerateProductBarcodesPdf::dispatch(
                $selected,
                auth()->id(),
                $this->barcodeType,
                $this->itemsPerPage
            );

            session()->flash('alert', [
                'type' => 'success',
                'title' => 'Processing Started',
                'message' => count($selected) . ' product(s) queued for barcode generation. Check your downloads shortly.',
            ]);

            // Reset form
            $this->selectedProducts = [];
            $this->selectAll = false;
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
        $allProducts = Product::where('barcode', '!=', null)
            ->when($this->searchQuery, function ($query) {
                $query->where('name', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('barcode', 'like', '%' . $this->searchQuery . '%');
            })
            ->pluck('id')
            ->mapWithKeys(function ($id) {
                return [$id => true];
            })
            ->toArray();

        if (empty($allProducts)) {
            session()->flash('alert', [
                'type' => 'warning',
                'title' => 'No Products Found',
                'message' => 'No products with barcodes found matching your search.',
            ]);

            return;
        }

        $this->selectedProducts = $allProducts;
        $this->generateBarcodes();
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
}
