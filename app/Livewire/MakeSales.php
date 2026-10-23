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
use App\Services\SalesService;
use App\Traits\StoreScoped;
use Livewire\Attributes\Layout;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
    public $subtotal_amount = 0;
    public $total_discount = 0;
    public $cart_discount_type = null;
    public $cart_discount_value = 0;
    public $cart_discount_amount = 0;
    public $discount_override = false;
    public $manager_email = '';
    public $manager_password = '';
    public $showDiscountAuthModal = false;
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

    protected ?SalesService $salesService = null;

    public function mount()
    {
        $this->initStoreId();
        $this->salesService = app(SalesService::class);
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

    protected function getSalesService(): SalesService
    {
        if ($this->salesService === null) {
            $this->salesService = app(SalesService::class);
        }
        return $this->salesService;
    }

    public function loadRecentSales()
    {
        $this->recentSales = $this->getSalesService()->getRecentSales($this->getStoreId(), 15);
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

        if ($product->stock_quantity <= 0) {
            LivewireAlert::title('Out of stock')
                ->text("{$product->name} is out of stock.")
                ->warning()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        if ($existingCartIndex !== false) {
            if ($this->cart[$existingCartIndex]['quantity'] >= $product->stock_quantity) {
                LivewireAlert::title('Insufficient stock')
                    ->text("Only {$product->stock_quantity} units of {$product->name} are available.")
                    ->warning()
                    ->toast()
                    ->position('top-end')
                    ->show();
                return;
            }

            $this->cart[$existingCartIndex]['quantity']++;
        } else {
            $this->cart[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'quantity' => 1,
                'available_stock' => (int) $product->stock_quantity,
                'price_per_unit' => $product->price,
                'discount_type' => null,
                'discount_value' => 0,
                'discount_amount' => 0,
                'total' => $product->price,
            ];
        }
        $this->updateTotalAmount();
    }

    public function updateQuantity($index, $newQuantity)
    {
        if (!isset($this->cart[$index])) {
            return;
        }

        $quantity = filter_var($newQuantity, FILTER_VALIDATE_INT);
        if ($quantity === false || $quantity < 1) {
            $quantity = 1;
        }

        $product = Product::where('store_id', $this->getStoreId())
            ->find($this->cart[$index]['product_id']);

        if (!$product || $product->stock_quantity <= 0) {
            unset($this->cart[$index]);
            $this->cart = array_values($this->cart);
            $this->updateTotalAmount();

            LivewireAlert::title('Out of stock')
                ->text('This product is no longer available.')
                ->warning()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        if ($quantity > $product->stock_quantity) {
            $quantity = (int) $product->stock_quantity;
            LivewireAlert::title('Insufficient stock')
                ->text("Only {$product->stock_quantity} units of {$product->name} are available.")
                ->warning()
                ->toast()
                ->position('top-end')
                ->show();
        }

        $this->cart[$index]['quantity'] = $quantity;
        $this->cart[$index]['available_stock'] = (int) $product->stock_quantity;
        $this->updateTotalAmount();
    }

    public function updateItemDiscount($index)
    {
        if (!$this->canApplyDiscount()) {
            return;
        }

        if (!isset($this->cart[$index])) {
            return;
        }

        $type = $this->cart[$index]['discount_type'] ?? null;
        $value = (float) ($this->cart[$index]['discount_value'] ?? 0);

        if ($type === 'percent') {
            $value = max(0, min(100, $value));
        } elseif ($type === 'amount') {
            $value = max(0, $value);
        } else {
            $value = 0;
        }

        $this->cart[$index]['discount_value'] = $value;
        $this->updateTotalAmount();
    }

    public function updateCartDiscount()
    {
        if (!$this->canApplyDiscount()) {
            $this->cart_discount_type = null;
            $this->cart_discount_value = 0;
            $this->cart_discount_amount = 0;
            $this->updateTotalAmount();
            return;
        }

        $value = (float) $this->cart_discount_value;
        if ($this->cart_discount_type === 'percent') {
            $this->cart_discount_value = max(0, min(100, $value));
        } elseif ($this->cart_discount_type === 'amount') {
            $this->cart_discount_value = max(0, $value);
        } else {
            $this->cart_discount_type = null;
            $this->cart_discount_value = 0;
        }

        $this->updateTotalAmount();
    }

    public function openDiscountAuthModal()
    {
        $this->showDiscountAuthModal = true;
    }

    public function closeDiscountAuthModal()
    {
        $this->showDiscountAuthModal = false;
        $this->manager_password = '';
    }

    public function authorizeDiscount()
    {
        $this->validate([
            'manager_email' => 'required|email',
            'manager_password' => 'required|string',
        ]);

        $manager = \App\Models\User::where('email', $this->manager_email)->first();
        if (!$manager || !$manager->hasRole('super-admin|admin') || !Hash::check($this->manager_password, $manager->password)) {
            LivewireAlert::title('Error')
                ->text('Invalid manager credentials.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        $this->discount_override = true;
        $this->manager_password = '';
        $this->showDiscountAuthModal = false;

        LivewireAlert::title('Authorized')
            ->text('Discounts are now enabled for this sale.')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
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
        $subtotal = 0;
        $itemDiscountTotal = 0;

        foreach ($this->cart as $index => $item) {
            $lineSubtotal = (float) $item['price_per_unit'] * (int) $item['quantity'];
            $discount = $this->calculateItemDiscount(
                $lineSubtotal,
                $item['discount_type'] ?? null,
                $item['discount_value'] ?? 0
            );

            $this->cart[$index]['discount_amount'] = $discount;
            $this->cart[$index]['total'] = $lineSubtotal - $discount;

            $subtotal += $lineSubtotal;
            $itemDiscountTotal += $discount;
        }

        $this->subtotal_amount = round($subtotal, 2);
        $cartBase = max(0, $subtotal - $itemDiscountTotal);
        $cartDiscount = $this->calculateCartDiscount($cartBase);

        $this->cart_discount_amount = $cartDiscount;
        $this->total_discount = round($itemDiscountTotal + $cartDiscount, 2);
        $this->total_amount = round(max(0, $cartBase - $cartDiscount), 2);
    }

    protected function calculateItemDiscount($lineSubtotal, $type, $value)
    {
        if (!$this->canApplyDiscount() || !$type) {
            return 0;
        }

        $value = (float) $value;

        if ($type === 'percent') {
            $value = max(0, min(100, $value));
            return round($lineSubtotal * ($value / 100), 2);
        }

        if ($type === 'amount') {
            $value = max(0, min($lineSubtotal, $value));
            return round($value, 2);
        }

        return 0;
    }

    protected function calculateCartDiscount($baseAmount)
    {
        if (!$this->canApplyDiscount() || !$this->cart_discount_type) {
            return 0;
        }

        $value = (float) $this->cart_discount_value;
        if ($this->cart_discount_type === 'percent') {
            $value = max(0, min(100, $value));
            return round($baseAmount * ($value / 100), 2);
        }

        if ($this->cart_discount_type === 'amount') {
            $value = max(0, min($baseAmount, $value));
            return round($value, 2);
        }

        return 0;
    }

    protected function canApplyDiscount()
    {
        $user = Auth::user();
        return ($user && $user->hasRole('super-admin|admin')) || $this->discount_override;
    }

    public function getDiscountAllowedProperty()
    {
        return $this->canApplyDiscount();
    }

    public function processSale()
    {
        $this->updateTotalAmount();

        if (empty($this->cart)) {
            LivewireAlert::title('Warning')
                ->text('Cart is empty. Please add items before processing the sale.')
                ->warning()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        if (!$this->payment_method) {
            LivewireAlert::title('Warning')
                ->text('Please select a payment method before processing the sale.')
                ->warning()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        if ($this->payment_method === 'credit' && !$this->customer_id) {
            LivewireAlert::title('Warning')
                ->text('Please select a customer for credit sales.')
                ->warning()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        if ($this->total_discount > 0 && !$this->canApplyDiscount()) {
            LivewireAlert::title('Error')
                ->text('You are not authorized to apply discounts.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        try {
            // Format cart items for service
            $cartItems = [];
            foreach ($this->cart as $item) {
                $cartItems[$item['product_id']] = [
                    'product_id' => $item['product_id'],
                    'price' => $item['price_per_unit'],
                    'quantity' => $item['quantity'],
                ];
            }

            // Determine amount paid
            $isCredit = $this->payment_method === 'credit';
            $amountPaid = $isCredit ? 0 : $this->total_amount;

            // Create the sale using service
            $sale = $this->getSalesService()->createSale(
                $this->getStoreId(),
                $cartItems,
                $this->customer_id,
                $this->payment_method,
                $amountPaid,
                [
                    'discount_type' => $this->cart_discount_type,
                    'discount_value' => $this->cart_discount_value,
                    'discount_amount' => $this->cart_discount_amount,
                ]
            );

            // Generate barcode for the sale
            $barcodeGenerator = new BarcodeGeneratorPNG();
            $this->saleBarcode = base64_encode($barcodeGenerator->getBarcode($sale->id, $barcodeGenerator::TYPE_CODE_128));
            $this->currentSale = $sale;

            // Fetch customer details if selected
            if ($this->customer_id) {
                $this->buying_customer = Customer::where('store_id', $this->getStoreId())->find($this->customer_id);
            }

            // Log notification
            $this->logNotification('Sale Processed', 'Sale has been processed successfully.', 'success');

            LivewireAlert::title('Success')
                ->text('Sale Processed Successfully!')
                ->success()
                ->toast()
                ->position('top-end')
                ->show();
            $this->showReceipt = true;
        } catch (\Exception $e) {
            LivewireAlert::title('Error')
                ->text($e->getMessage() ?: 'An error occurred while processing the sale. Please try again.')
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
        $this->subtotal_amount = 0;
        $this->total_discount = 0;
        $this->cart_discount_type = null;
        $this->cart_discount_value = 0;
        $this->cart_discount_amount = 0;
        $this->discount_override = false;
        $this->manager_email = '';
        $this->manager_password = '';
        $this->showDiscountAuthModal = false;
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
            'recentSales' => $this->recentSales,
            'discountAllowed' => $this->getDiscountAllowedProperty(),
        ]);
    }
}
