<?php

namespace App\Livewire;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\Category;
use App\Models\Notification;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Auth; // Add this
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class ManageProduct extends Component
{
    use WithFileUploads;
    use WithPagination;
    use LivewireAlert;

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
            'manufacture_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'image' => 'nullable|image|max:5024', // max 5MB
        ];
    }

    public function mount()
    {
        $this->pageTitle = 'Data';
        $this->componentName = 'Products';
        $this->categories = Category::all();
        $this->suppliers = Supplier::all();
    }
    public function confirmBulkDelete()
    {
        if (count($this->selectedProducts) > 0) {
            $this->alert('warning', 'Are you sure?', [
                'text' => 'You are about to delete selected products.',
                'showConfirmButton' => true,
                'confirmButtonText' => 'Yes, Delete',
                'onConfirmed' => 'bulkDeleteProducts',
                'showCancelButton' => true,
                'cancelButtonText' => 'Cancel',
            ]);
        } else {
            $this->alert('warning', 'No products selected.');
        }
    }
    public function bulkDeleteProducts()
    {
        Product::whereIn('id', $this->selectedProducts)->delete();
        $this->selectedProducts = []; // Clear selected products
        $this->alert('success', 'Selected products deleted successfully.');
        $this->emit('refreshProductList'); // Optional: Refresh list after deletion
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
        $query = Product::query()
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

        $products = Product::with(['category', 'supplier'])->get();
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
        $this->isEditMode = true;
        $record = Product::find($id, [
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
        ]);
        // Handle image upload, if provided
        if ($this->image) {
            // Store the image in the 'customers' directory within the 'public' disk
            $imagePath = $this->image->store('products', 'public');
            // Update the customer with the image path
            $product->update(['image' => $imagePath]);
        }

        // Log Notification
        $this->logNotification('Product Created', "Product '{$product->name}' created.");
        $this->resetUI();
        $this->alert('success', 'Product Created Successfully');
    }

    public function Update()
    {
        $this->validate($this->rules());

        if ($this->selected_id) {
            $product = Product::find($this->selected_id);
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
            // Log Notification
            $this->logNotification('Product Updated', "Product '{$product->name}' updated.");
            $this->resetUI();
            $this->alert('success', message: 'Product Updated Successfully');
        }
    }
    public function Delete($id)
    {
        $this->selected_id = $id;
        $this->alert('warning', 'Are you sure you want to delete?', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'confirmedDeletion',
            'showCancelButton' => true,
            'timerProgressBar' => false,
            'width' => '400',
        ]);
    }

    public function confirmedDeletion()
    {
        $product = Product::find($this->selected_id);
        if ($product) {
            $product->delete();
            $this->alert('success', 'The product has been deleted.');
            // Log Notification
            $this->logNotification('Product Deleted', "Product '{$product->name}' deleted.");
        } else {
            $this->alert('error', 'Sorry, Something Went Wrong.');
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
