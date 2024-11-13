<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Notification; // Import the Notification model
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Setting;
use Livewire\Attributes\Layout;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Picqer\Barcode\BarcodeGeneratorPNG;

#[Layout('layouts.app')]
class MakeSales extends Component
{
    use LivewireAlert;

    public $cart = [];
    public $barcode = '';
    public $selectedProduct = null;
    public $selectedProductId;
    public $products = [];
    public $customers = [];
    public $customer_id;
    public $payment_method;
    public $total_amount = 0;
    public $showReceipt = false;
    public $saleBarcode;
    public $settings;
    public $buying_customer;
    public function mount()
    {
        $this->products = Product::all();
        $this->customers = Customer::all();
        // Fetch settings information
        $this->settings = Setting::first();
    }

    public function addProductToCart()
    {
        if (!$this->selectedProductId) {
            return;
        }

        $product = Product::find($this->selectedProductId);

        if (!$product instanceof Product) {
            $this->selectedProductId = null;
            return;
        }

        $this->addOrUpdateCart($product);
        $this->selectedProductId = null;
    }

    public function addProductByBarcode()
    {
        if (!$this->barcode) {
            return;
        }

        $product = Product::where('barcode', $this->barcode)->first();

        if (!$product) {
            $this->barcode = null;
            return;
        }

        $this->addOrUpdateCart($product);
        $this->barcode = null;
    }

    private function addOrUpdateCart($product)
    {
        $existingCartIndex = array_search($product->id, array_column($this->cart, 'product_id'));

        if ($existingCartIndex !== false) {
            $this->cart[$existingCartIndex]['quantity']++;
            $this->cart[$existingCartIndex]['total'] += $product->price;
        } else {
            $this->cart[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'quantity' => 1,
                'price_per_unit' => $product->price,
                'total' => $product->price,
            ];
        }
        $this->updateTotalAmount();
    }

    public function updateQuantity($index, $newQuantity)
    {
        if ($newQuantity > 0) {
            $this->cart[$index]['quantity'] = $newQuantity;
            $this->cart[$index]['total'] = $this->cart[$index]['price_per_unit'] * $newQuantity;
            $this->updateTotalAmount();
        }
    }
    public function removeProductFromCart($index)
    {
        if (isset($this->cart[$index])) {
            unset($this->cart[$index]);
            $this->cart = array_values($this->cart);
            $this->updateTotalAmount();
        }
    }
    public function updateTotalAmount()
    {
        $this->total_amount = collect($this->cart)->sum('total');
    }

    public function processSale()
    {
        if (empty($this->cart)) {
            // Alert warning if the cart is empty
            $this->alert('warning', 'Cart is empty. Please add items before processing the sale.');
            return;
        }

        DB::beginTransaction();

        try {
            // Step 1: Create the Sale record
            $sale = Sale::create([
                'customer_id' => $this->customer_id,
                'total_amount' => $this->total_amount,
                'payment_method' => $this->payment_method,
                'user_id' => Auth::id(),
                'created_at' => Carbon::now(),
            ]);

            // Step 2: Loop through the cart, create SaleDetail records, and update stock
            foreach ($this->cart as $item) {
                $product = Product::find($item['product_id']);

                if ($product && $product->stock_quantity >= $item['quantity']) {
                    // Reduce the stock quantity
                    $product->decrement('stock_quantity', $item['quantity']);

                    // Create the SaleDetail record
                    SaleDetail::create([
                        'sale_id' => $sale->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price_per_unit' => $item['price_per_unit'],
                        'subtotal' => $item['total'],
                    ]);
                } else {
                    // Handle insufficient stock scenario
                    DB::rollBack();
                    $this->alert('error', "Insufficient stock for {$product->name}");
                    return;
                }
            }

            // Step 3: Generate the barcode for the sale ID
            $barcodeGenerator = new BarcodeGeneratorPNG();
            $this->saleBarcode = base64_encode($barcodeGenerator->getBarcode($sale->id, $barcodeGenerator::TYPE_CODE_128));

            // Fetch customer details if selected
            if ($this->customer_id) {
                $this->buying_customer = Customer::find($this->customer_id);
            }

            // Step 4: Log the notification for the sale
            $this->logNotification('Sale Processed', 'Sale has been processed successfully.', 'success');

            DB::commit();

            $this->alert('success', 'Sale Processed Successfully!');
            $this->showReceipt = true;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', 'An error occurred while processing the sale. Please try again.');
        }
    }
    protected function logNotification($title, $message, $type)
    {
        Notification::create([
            'user_id' => Auth::id(),
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    public function closeModal()
    {
        $this->showReceipt = false;
        $this->resetCart();
    }

    public function resetCart()
    {
        $this->cart = [];
        $this->total_amount = 0;
        $this->barcode = '';
        $this->selectedProduct = null;
    }

    public function render()
    {
        return view('livewire.make-sales', [
            'saleBarcode' => $this->saleBarcode
        ]);
    }
}
