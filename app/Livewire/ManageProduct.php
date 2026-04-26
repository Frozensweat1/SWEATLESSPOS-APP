<?php

namespace App\Livewire;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\Category;
use App\Models\Notification;
use App\Traits\StoreScoped;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Illuminate\Support\Facades\Auth; // Add this
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Illuminate\Support\Facades\DB;
use Picqer\Barcode\BarcodeGeneratorPNG;

#[Layout('layouts.app')]
class ManageProduct extends Component
{
    use WithFileUploads;
    use WithPagination;
    use StoreScoped;

    protected $paginationTheme = 'bootstrap'; // Using Bootstrap for pagination UI

    public $currentImage, $searchengine, $selected_id, $pageTitle, $componentName;
    public $name, $barcode, $sku, $description, $price, $cost_price, $stock_quantity;
    public $supplier_id, $manufacture_date, $expiry_date, $image, $category_id;
    public $isEditMode = false, $categoryFilter, $supplierFilter, $dateFrom, $dateTo;
    public $categories, $suppliers, $perPage = 10;
    public $selectedProducts = [];
    public $selectAll = false;
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:products,name,' . $this->selected_id,
            'barcode' => 'required|string|max:255|unique:products,barcode,' . $this->selected_id,
            'sku' => 'nullable|string|max:255|unique:products,sku,' . $this->selected_id,
            'description' => 'required|string|max:1020',
            'price' => 'required|numeric',
            'cost_price' => 'required|numeric',
            'stock_quantity' => 'required|numeric',
            'category_id' => 'required|numeric|not_in:Elegir',
            'supplier_id' => 'required|numeric|not_in:Elegir',
            'manufacture_date' => 'required|date',
            'expiry_date' => 'required|date',
            'image' => 'nullable|image|max:5024', // max 5MB
        ];
    }

    public function mount()
    {
        $this->initStoreId();
        $this->pageTitle = 'Data';
        $this->componentName = 'Products';
        $this->categories = Category::where('store_id', $this->getStoreId())->get();
        $this->suppliers = Supplier::where('store_id', $this->getStoreId())->get();
    }
    public function confirmBulkDelete()
    {
        if (count($this->selectedProducts) > 0) {
            LivewireAlert::title('Are you sure?')
                ->html('You are about to delete selected products.')
                ->warning()
                ->onConfirm('bulkDeleteProducts')
                ->withConfirmButton('Yes, Delete')
                ->withCancelButton('Cancel')
                ->show();
        } else {
            LivewireAlert::warning('No products selected.')
                ->toast()
                ->position('top-end')
                ->show();
        }
    }
    public function bulkDeleteProducts()
    {
        Product::where('store_id', $this->getStoreId())->whereIn('id', $this->selectedProducts)->delete();
        $this->selectedProducts = [];
        LivewireAlert::title('Success')
            ->text('Selected products deleted successfully.')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function updatedSelectAll($value)
    {
        $this->selectedProducts = $value ? $this->getProductsProperty()->pluck('id')->toArray() : [];
    }

    public function updatedSelectedProducts()
    {
        $this->selectAll = count($this->selectedProducts) === $this->getProductsProperty()->count();
    }
    public function getProductsProperty()
    {
        $query = Product::where('store_id', $this->getStoreId())
            ->withoutTrashed()
            ->with(['category', 'supplier'])
            ->when($this->searchengine, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->searchengine . '%')
                        ->orWhere('barcode', 'like', '%' . $this->searchengine . '%');
                });
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('category_id', $this->categoryFilter);
            })
            ->when($this->supplierFilter, function ($query) {
                $query->where('supplier_id', $this->supplierFilter);
            })
            ->when($this->dateFrom && $this->dateTo, function ($query) {
                $query->whereBetween('created_at', [$this->dateFrom, $this->dateTo]);
            })->orderBy('created_at', 'desc');
        return $query->paginate($this->perPage);
    }

    public function export($format)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Products');

        $sheet->fromArray([
            ['Name', 'Barcode', 'SKU', 'Description', 'Price', 'Cost Price', 'Stock Quantity', 'Category', 'Supplier', 'Manufacture Date', 'Expiry Date']
        ], null, 'A1');

        $products = Product::where('store_id', $this->getStoreId())->withoutTrashed()->with(['category', 'supplier'])->get();
        foreach ($products as $index => $product) {
            $sheet->fromArray([
                $product->name,
                $product->barcode,
                $product->sku,
                $product->description,
                $product->price,
                $product->cost_price,
                $product->stock_quantity,
                $product->category->name ?? 'N/A',
                $product->supplier->name ?? 'N/A',
                $product->manufacture_date,
                $product->expiry_date
            ], null, 'A' . ($index + 2));
        }

        $filename = 'products.' . strtolower($format);
        $path = storage_path("app/public/$filename");

        $writer = $format === 'CSV' ? new Csv($spreadsheet) : new Xlsx($spreadsheet);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend();
    }

    public function regenerateAllBarcodes()
    {
        $storeId = (int) $this->getStoreId();

        Product::where('store_id', $storeId)
            ->withoutTrashed()
            ->select('id')
            ->chunkById(200, function ($products) use ($storeId) {
                foreach ($products as $product) {
                    $barcode = $this->buildBarcodeValue($storeId, (int) $product->id);

                    Product::where('id', $product->id)->update([
                        'barcode' => $barcode,
                    ]);
                }
            });

        LivewireAlert::title('Success')
            ->text('All product barcodes were regenerated from store ID and product ID.')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
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
    public function create()
    {
        $this->reset(
            'name',
            'barcode',
            'sku',
            'description',
            'price',
            'cost_price',
            'stock_quantity',
            'category_id',
            'supplier_id',
            'manufacture_date',
            'expiry_date',
            'image'
        );
        $this->resetUI();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isEditMode = true;
    }

    public function closeModal()
    {
        $this->isEditMode = false;
        $this->resetUI();
    }

    public function Edit($id)
    {
        $this->resetUI();
        $this->isEditMode = true;
        $record = Product::where('store_id', $this->getStoreId())
            ->find($id, [
                'id',
                'name',
                'barcode',
                'sku',
                'description',
                'price',
                'cost_price',
                'stock_quantity',
                'category_id',
                'supplier_id',
                'manufacture_date',
                'expiry_date',
                'image'
            ]);
        $this->name = $record->name;
        $this->barcode = $record->barcode;
        $this->sku = $record->sku;
        $this->description = $record->description;
        $this->price = $record->price;
        $this->cost_price = $record->cost_price;
        $this->stock_quantity = $record->stock_quantity;
        $this->category_id = $record->category_id;
        $this->supplier_id = $record->supplier_id;
        $this->manufacture_date = $record->manufacture_date;
        $this->expiry_date = $record->expiry_date;
        $this->currentImage = $record->image;
        $this->selected_id = $record->id;
        $this->openModal();
    }

    public function Store()
    {
        $this->validate($this->rules());
        if (!$this->sku) {
            $this->sku = Product::generateSKU($this->name);
        }
        $product = Product::create([
            'name' => $this->name,
            'barcode' => $this->barcode,
            'sku' => $this->sku,
            'description' => $this->description,
            'price' => $this->price,
            'cost_price' => $this->cost_price,
            'stock_quantity' => $this->stock_quantity,
            'category_id' => $this->category_id,
            'supplier_id' => $this->supplier_id,
            'manufacture_date' => $this->manufacture_date,
            'expiry_date' => $this->expiry_date,
            'store_id' => $this->getStoreId(),
        ]);
        // Handle image upload, if provided
        if ($this->image) {
            // Store the image in the 'customers' directory within the 'public' disk
            $imagePath = $this->image->store('products', 'public');
            // Update the customer with the image path
            $product->update(['image' => $imagePath]);
        }

        $initialQty = (int) $product->stock_quantity;
        if ($initialQty !== 0) {
            StockAdjustment::create([
                'store_id' => $this->getStoreId(),
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => 'initial',
                'quantity_before' => 0,
                'quantity_after' => $initialQty,
                'change_qty' => $initialQty,
                'reason' => 'Initial stock',
                'notes' => 'Product created with opening stock.',
            ]);
        }

        // Log Notification
        $this->logNotification('Product Created', "Product '{$product->name}' created.");
        $this->resetUI();
        LivewireAlert::title('Success')
            ->text('Product Created Successfully')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function Update()
    {
        $this->validate($this->rules());

        if ($this->selected_id) {
            $product = Product::where('store_id', $this->getStoreId())->find($this->selected_id);
            $beforeQty = $product ? (int) $product->stock_quantity : 0;
            $data = [
                'name' => $this->name,
                'barcode' => $this->barcode,
                'sku' => $this->sku,
                'description' => $this->description,
                'price' => $this->price,
                'cost_price' => $this->cost_price,
                'stock_quantity' => $this->stock_quantity,
                'category_id' => $this->category_id,
                'supplier_id' => $this->supplier_id,
                'manufacture_date' => $this->manufacture_date,
                'expiry_date' => $this->expiry_date,
            ];
            if ($this->image) {
                $data['image'] = $this->image->store('products', 'public');
            }
            $product->update($data);

            $afterQty = (int) $data['stock_quantity'];
            if ($beforeQty !== $afterQty) {
                StockAdjustment::create([
                    'store_id' => $this->getStoreId(),
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'manual_update',
                    'quantity_before' => $beforeQty,
                    'quantity_after' => $afterQty,
                    'change_qty' => $afterQty - $beforeQty,
                    'reason' => 'Manual stock update',
                    'notes' => "Updated from {$beforeQty} to {$afterQty}.",
                ]);
            }
            // Log Notification
            $this->logNotification('Product Updated', "Product '{$product->name}' updated.");
            $this->resetUI();
            LivewireAlert::title('Success')
                ->text('Product Updated Successfully')
                ->success()
                ->toast()
                ->position('top-end')
                ->show();
        }
    }
    public function Delete($id)
    {
        $this->selected_id = $id;
        LivewireAlert::title('Are you sure you want to delete?')
            ->warning()
            ->onConfirm('confirmedDeletion')
            ->withConfirmButton('Yes, Delete')
            ->withCancelButton('Cancel')
            ->show();
    }

    public function confirmedDeletion()
    {
        $product = Product::where('store_id', $this->getStoreId())->find($this->selected_id);
        if ($product) {
            $product->delete();
            LivewireAlert::title('Deleted')
                ->text('The product has been deleted.')
                ->success()
                ->toast()
                ->position('top-end')
                ->show();
            // Log Notification
            $this->logNotification('Product Deleted', "Product '{$product->name}' deleted.");
        } else {
            LivewireAlert::title('Error')
                ->text('Sorry, Something Went Wrong.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
        }
    }

    protected $listeners = [
        'confirmedDeletion',
        'bulkDeleteProducts'
    ];

    protected function logNotification($title, $message)
    {
        $authUser = Auth::user();

        Notification::create([
            'user_id' => $authUser->id,
            'title' => $title,
            'message' => $message . " Action by {$authUser->name} (ID: {$authUser->id}).",
            'type' => 'info',
            'is_read' => false,
        ]);
    }

    public function resetUI()
    {
        $this->reset(['name', 'barcode', 'sku', 'description', 'price', 'cost_price', 'stock_quantity', 'manufacture_date', 'expiry_date', 'image', 'category_id', 'supplier_id', 'currentImage', 'selectedProducts', 'selected_id']);
    }
    public function render()
    {
        return view('livewire.manage-product',        [
            'products' => $this->products,
        ]);
    }
}
