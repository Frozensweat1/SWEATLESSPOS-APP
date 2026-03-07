<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Notification;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Setting;
use App\Traits\StoreScoped;
use Livewire\Attributes\Layout;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Picqer\Barcode\BarcodeGeneratorPNG;

#[Layout('layouts.app')]
class MakeSales extends Component
{
    use StoreScoped;

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
    public $currentSale;
    public $recentSales = [];
    public $viewingSaleId = null;
    public $viewingSale = null;
    public $viewingCustomer = null;
    public $viewingSaleBarcode = null;
    public $showViewReceipt = false;
    public function mount()
    {
        $this->initStoreId();
        $this->products = Product::where('store_id', $this->getStoreId())->withoutTrashed()->get();
        $this->customers = Customer::where('store_id', $this->getStoreId())->get();
        // Fetch settings information or create default
        $this->settings = Setting::first() ?? new Setting([
            'site_name' => config('app.name', 'Sweatless POS'),
            'currency' => 'USD',
            'contact_phone' => '',
            'contact_email' => '',
            'address' => '',
            'receipt_header' => 'In God We Trust!',
        ]);
        $this->loadRecentSales();
    }

    public function loadRecentSales()
    {
        $this->recentSales = Sale::where('store_id', $this->getStoreId())
            ->with(['customer', 'saleDetails.product'])
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();
    }

    public function viewReceipt($saleId)
    {
        $this->viewingSaleId = $saleId;
        $this->viewingSale = Sale::where('store_id', $this->getStoreId())
            ->with(['customer', 'saleDetails.product'])
            ->find($saleId);

        if ($this->viewingSale) {
            $this->viewingCustomer = $this->viewingSale->customer;

            // Generate barcode for viewing sale
            $barcodeGenerator = new BarcodeGeneratorPNG();
            $this->viewingSaleBarcode = base64_encode($barcodeGenerator->getBarcode($this->viewingSale->id, $barcodeGenerator::TYPE_CODE_128));

            $this->showViewReceipt = true;
        }
    }

    public function closeViewReceipt()
    {
        $this->showViewReceipt = false;
        $this->viewingSaleId = null;
        $this->viewingSale = null;
        $this->viewingCustomer = null;
        $this->viewingSaleBarcode = null;
    }

    public function addProductToCart()
    {
        if (!$this->selectedProductId) {
            return;
        }

        $product = Product::where('store_id', $this->getStoreId())->find($this->selectedProductId);

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

        $product = Product::where('store_id', $this->getStoreId())->where('barcode', $this->barcode)->first();

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
            LivewireAlert::title('Warning')
                ->text('Cart is empty. Please add items before processing the sale.')
                ->warning()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        if (!$this->payment_method) {
            // Alert warning if payment method is not selected
            LivewireAlert::title('Warning')
                ->text('Please select a payment method before processing the sale.')
                ->warning()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        if ($this->payment_method === 'credit' && !$this->customer_id) {
            // Alert warning if credit sale without customer
            LivewireAlert::title('Warning')
                ->text('Please select a customer for credit sales.')
                ->warning()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        DB::beginTransaction();

        try {
            // Determine payment status based on payment method
            $isCredit = $this->payment_method === 'credit';

            // Step 1: Create the Sale record with payment status
            $sale = Sale::create([
                'store_id' => $this->getStoreId(),
                'customer_id' => $this->customer_id,
                'total_amount' => $this->total_amount,
                'payment_method' => $this->payment_method,
                'payment_status' => $isCredit ? 'pending' : 'paid',
                'amount_paid' => $isCredit ? 0 : $this->total_amount,
                'amount_due' => $isCredit ? $this->total_amount : 0,
                'due_date' => $isCredit ? \Carbon\Carbon::now()->addDays(30) : null,
                'user_id' => Auth::id(),
                'created_at' => Carbon::now(),
            ]);

            // Step 2: Loop through the cart, create SaleDetail records, and update stock
            foreach ($this->cart as $item) {
                $product = Product::where('store_id', $this->getStoreId())->find($item['product_id']);

                if ($product && $product->stock_quantity >= $item['quantity']) {
                    // Reduce the stock quantity
                    $product->decrement('stock_quantity', $item['quantity']);

                    // Create the SaleDetail record with product name
                    SaleDetail::create([
                        'sale_id' => $sale->id,
                        'product_id' => $item['product_id'],
                        'product_name' => $product->name,
                        'quantity' => $item['quantity'],
                        'price_per_unit' => $item['price_per_unit'],
                        'subtotal' => $item['total'],
                    ]);
                } else {
                    // Handle insufficient stock scenario
                    DB::rollBack();
                    LivewireAlert::title('Error')
                        ->text('Insufficient stock for ' . $product->name)
                        ->error()
                        ->toast()
                        ->position('top-end')
                        ->show();
                    return;
                }
            }

            // Step 3: Generate the barcode for the sale ID
            $barcodeGenerator = new BarcodeGeneratorPNG();
            $this->saleBarcode = base64_encode($barcodeGenerator->getBarcode($sale->id, $barcodeGenerator::TYPE_CODE_128));
            $this->currentSale = $sale;

            // Fetch customer details if selected
            if ($this->customer_id) {
                $this->buying_customer = Customer::where('store_id', $this->getStoreId())->find($this->customer_id);
            }

            // Step 4: Log the notification for the sale
            $this->logNotification('Sale Processed', 'Sale has been processed successfully.', 'success');

            DB::commit();

            LivewireAlert::title('Success')
                ->text('Sale Processed Successfully!')
                ->success()
                ->toast()
                ->position('top-end')
                ->show();
            $this->showReceipt = true;
        } catch (\Exception $e) {
            DB::rollBack();
            LivewireAlert::title('Error')
                ->text('An error occurred while processing the sale. Please try again.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
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
        $this->loadRecentSales();
    }

    public function resetCart()
    {
        $this->cart = [];
        $this->total_amount = 0;
        $this->barcode = '';
        $this->selectedProduct = null;
        $this->currentSale = null;
        $this->buying_customer = null;
        $this->payment_method = '';
        $this->customer_id = null;
        $this->saleBarcode = null;
    }

    public function render()
    {
        return view('livewire.make-sales', [
            'saleBarcode' => $this->saleBarcode,
            'recentSales' => $this->recentSales
        ]);
    }
}
