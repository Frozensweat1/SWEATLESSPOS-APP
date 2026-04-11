<?php

namespace Tests\Unit\Services;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\StockAdjustment;
use App\Models\Store;
use App\Models\User;
use App\Services\SalesService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalesServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SalesService $service;
    protected User $user;
    protected Store $store;
    protected Product $product;
    protected Customer $customer;

    public function setUp(): void
    {
        parent::setUp();

        $this->store = Store::factory()->create();
        $this->user = User::factory()->create(['store_id' => $this->store->id]);
        $this->product = Product::factory()->create([
            'store_id' => $this->store->id,
            'stock_quantity' => 100,
            'price' => 50.00,
        ]);
        $this->customer = Customer::factory()->create(['store_id' => $this->store->id]);

        $this->actingAs($this->user);
        $this->service = app(SalesService::class);
    }

    /** @test */
    public function can_add_item_to_cart()
    {
        $cart = [];

        $cart = $this->service->addToCart($cart, $this->product->id, 50.00, 2);

        $this->assertArrayHasKey($this->product->id, $cart);
        $this->assertEquals(2, $cart[$this->product->id]['quantity']);
    }

    /** @test */
    public function can_increase_quantity_if_already_in_cart()
    {
        $cart = [];
        $cart = $this->service->addToCart($cart, $this->product->id, 50.00, 1);
        $cart = $this->service->addToCart($cart, $this->product->id, 50.00, 2);

        $this->assertEquals(3, $cart[$this->product->id]['quantity']);
    }

    /** @test */
    public function can_remove_item_from_cart()
    {
        $cart = [$this->product->id => ['product_id' => $this->product->id, 'quantity' => 2]];

        $cart = $this->service->removeFromCart($cart, $this->product->id);

        $this->assertArrayNotHasKey($this->product->id, $cart);
    }

    /** @test */
    public function can_update_item_quantity()
    {
        $cart = [$this->product->id => ['product_id' => $this->product->id, 'quantity' => 2]];

        $cart = $this->service->updateCartItemQuantity($cart, $this->product->id, 5);

        $this->assertEquals(5, $cart[$this->product->id]['quantity']);
    }

    /** @test */
    public function removes_item_if_quantity_set_to_zero()
    {
        $cart = [$this->product->id => ['product_id' => $this->product->id, 'quantity' => 2]];

        $cart = $this->service->updateCartItemQuantity($cart, $this->product->id, 0);

        $this->assertArrayNotHasKey($this->product->id, $cart);
    }

    /** @test */
    public function can_calculate_cart_totals()
    {
        $cart = [
            $this->product->id => ['price' => 50.00, 'quantity' => 2],
        ];

        $totals = $this->service->calculateCartTotals($cart);

        $this->assertEquals(100.00, $totals['subtotal']);
        $this->assertEquals(1, $totals['item_count']);
        $this->assertEquals(2, $totals['total_quantity']);
    }

    /** @test */
    public function can_calculate_percentage_discount()
    {
        $discount = $this->service->calculateDiscount(100.00, 'percentage', 10);

        $this->assertEquals(10.00, $discount['discount_amount']);
        $this->assertEquals(90.00, $discount['total']);
    }

    /** @test */
    public function can_calculate_fixed_discount()
    {
        $discount = $this->service->calculateDiscount(100.00, 'fixed', 15.00);

        $this->assertEquals(15.00, $discount['discount_amount']);
        $this->assertEquals(85.00, $discount['total']);
    }

    /** @test */
    public function cannot_create_sale_with_empty_cart()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cart is empty.');

        $this->service->createSale(
            $this->store->id,
            [],
            $this->customer->id,
            'cash',
            0
        );
    }

    /** @test */
    public function can_create_cash_sale()
    {
        $cartItems = [
            $this->product->id => [
                'product_id' => $this->product->id,
                'price' => 50.00,
                'quantity' => 2,
            ]
        ];

        $sale = $this->service->createSale(
            $this->store->id,
            $cartItems,
            $this->customer->id,
            'cash',
            100.00
        );

        $this->assertInstanceOf(Sale::class, $sale);
        $this->assertEquals(100.00, $sale->total_amount);
        $this->assertEquals('paid', $sale->payment_status);
        $this->assertEquals('cash', $sale->payment_method);
        $this->assertEquals(100.00, $sale->amount_paid);
        $this->assertEquals(0, $sale->amount_due);
    }

    /** @test */
    public function can_create_credit_sale()
    {
        $cartItems = [
            $this->product->id => [
                'product_id' => $this->product->id,
                'price' => 50.00,
                'quantity' => 2,
            ]
        ];

        $sale = $this->service->createSale(
            $this->store->id,
            $cartItems,
            $this->customer->id,
            'credit',
            0
        );

        $this->assertEquals('pending', $sale->payment_status);
        $this->assertEquals('credit', $sale->payment_method);
        $this->assertEquals(0, $sale->amount_paid);
        $this->assertEquals(100.00, $sale->amount_due);
        $this->assertNotNull($sale->due_date);
    }

    /** @test */
    public function creates_sale_details_on_sale()
    {
        $cartItems = [
            $this->product->id => [
                'product_id' => $this->product->id,
                'price' => 50.00,
                'quantity' => 2,
            ]
        ];

        $sale = $this->service->createSale(
            $this->store->id,
            $cartItems,
            $this->customer->id,
            'cash',
            100.00
        );

        $saleDetails = $sale->saleDetails;
        $this->assertCount(1, $saleDetails);
        $this->assertEquals(2, $saleDetails[0]->quantity);
        $this->assertEquals(50.00, $saleDetails[0]->price);
    }

    /** @test */
    public function updates_product_stock_on_sale()
    {
        $initialStock = $this->product->stock_quantity;

        $cartItems = [
            $this->product->id => [
                'product_id' => $this->product->id,
                'price' => 50.00,
                'quantity' => 5,
            ]
        ];

        $this->service->createSale(
            $this->store->id,
            $cartItems,
            $this->customer->id,
            'cash',
            250.00
        );

        $this->product->refresh();
        $this->assertEquals($initialStock - 5, $this->product->stock_quantity);
    }

    /** @test */
    public function creates_stock_adjustment_on_sale()
    {
        $cartItems = [
            $this->product->id => [
                'product_id' => $this->product->id,
                'price' => 50.00,
                'quantity' => 3,
            ]
        ];

        $sale = $this->service->createSale(
            $this->store->id,
            $cartItems,
            $this->customer->id,
            'cash',
            150.00
        );

        $adjustment = StockAdjustment::where('reference_id', $sale->id)->first();
        $this->assertNotNull($adjustment);
        $this->assertEquals('sale', $adjustment->adjustment_type);
        $this->assertEquals(-3, $adjustment->quantity_change);
    }

    /** @test */
    public function can_get_recent_sales()
    {
        Sale::factory(3)->create(['store_id' => $this->store->id]);

        $recentSales = $this->service->getRecentSales($this->store->id, 15);

        $this->assertCount(3, $recentSales);
    }

    /** @test */
    public function respects_limit_in_get_recent_sales()
    {
        Sale::factory(5)->create(['store_id' => $this->store->id]);

        $recentSales = $this->service->getRecentSales($this->store->id, 2);

        $this->assertCount(2, $recentSales);
    }
}
