<?php

namespace Tests\Unit\Services;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Refund;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\StockAdjustment;
use App\Models\Store;
use App\Models\User;
use App\Services\RefundService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RefundServiceTest extends TestCase
{
    use RefreshDatabase;

    protected RefundService $service;
    protected User $user;
    protected Store $store;
    protected Product $product;
    protected Sale $sale;
    protected SaleDetail $saleDetail;

    public function setUp(): void
    {
        parent::setUp();

        $this->store = Store::factory()->create();
        $this->user = User::factory()->create(['store_id' => $this->store->id]);
        $this->product = Product::factory()->create([
            'store_id' => $this->store->id,
            'stock_quantity' => 50,
            'price' => 100.00,
        ]);

        $this->sale = Sale::factory()->create([
            'store_id' => $this->store->id,
            'total_amount' => 300.00,
        ]);

        $this->saleDetail = SaleDetail::factory()->create([
            'sale_id' => $this->sale->id,
            'product_id' => $this->product->id,
            'quantity' => 3,
            'price' => 100.00,
            'subtotal' => 300.00,
        ]);

        $this->actingAs($this->user);
        $this->service = app(RefundService::class);
    }

    /** @test */
    public function can_process_sale_detail_refund()
    {
        $initialStock = $this->product->stock_quantity;

        $refund = $this->service->processSaleDetailRefund(
            $this->store->id,
            $this->saleDetail->id,
            2,
            'Customer requested return'
        );

        $this->assertInstanceOf(Refund::class, $refund);
        $this->assertEquals(2, $refund->quantity);
        $this->assertEquals(200.00, $refund->refund_amount);

        $this->product->refresh();
        $this->assertEquals($initialStock + 2, $this->product->stock_quantity);
    }

    /** @test */
    public function cannot_refund_more_than_sold()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid refund quantity.');

        $this->service->processSaleDetailRefund(
            $this->store->id,
            $this->saleDetail->id,
            5,
            'Invalid quantity'
        );
    }

    /** @test */
    public function cannot_refund_zero_quantity()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid refund quantity.');

        $this->service->processSaleDetailRefund(
            $this->store->id,
            $this->saleDetail->id,
            0,
            'Zero quantity'
        );
    }

    /** @test */
    public function creates_stock_adjustment_on_refund()
    {
        $this->service->processSaleDetailRefund(
            $this->store->id,
            $this->saleDetail->id,
            1,
            'Test refund'
        );

        $adjustment = StockAdjustment::where('adjustment_type', 'refund')->first();
        $this->assertNotNull($adjustment);
        $this->assertEquals(1, $adjustment->quantity_change);
    }

    /** @test */
    public function can_process_bulk_refunds()
    {
        $saleDetail2 = SaleDetail::factory()->create([
            'sale_id' => $this->sale->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'price' => 50.00,
        ]);

        $refundData = [
            ['sale_detail_id' => $this->saleDetail->id, 'quantity' => 1, 'reason' => 'Defective'],
            ['sale_detail_id' => $saleDetail2->id, 'quantity' => 1, 'reason' => 'Wrong item'],
        ];

        $refunds = $this->service->processBulkRefunds($this->store->id, $refundData);

        $this->assertCount(2, $refunds);
    }

    /** @test */
    public function can_get_refunds_with_filters()
    {
        Refund::factory(5)->create(['store_id' => $this->store->id]);

        $refunds = $this->service->getRefunds($this->store->id, [], 10);

        $this->assertCount(5, $refunds);
    }

    /** @test */
    public function filters_refunds_by_product()
    {
        $product2 = Product::factory()->create(['store_id' => $this->store->id]);

        Refund::factory()->create([
            'store_id' => $this->store->id,
            'product_id' => $this->product->id,
        ]);

        Refund::factory()->create([
            'store_id' => $this->store->id,
            'product_id' => $product2->id,
        ]);

        $refunds = $this->service->getRefunds(
            $this->store->id,
            ['product_id' => $this->product->id],
            10
        );

        $this->assertCount(1, $refunds);
    }

    /** @test */
    public function filters_refunds_by_date_range()
    {
        $oldDate = now()->subDays(10);

        Refund::factory()->create([
            'store_id' => $this->store->id,
            'created_at' => $oldDate,
        ]);

        Refund::factory()->create([
            'store_id' => $this->store->id,
            'created_at' => now(),
        ]);

        $refunds = $this->service->getRefunds(
            $this->store->id,
            [
                'start_date' => now()->subDays(5),
                'end_date' => now(),
            ],
            10
        );

        $this->assertCount(1, $refunds);
    }

    /** @test */
    public function can_cancel_refund()
    {
        $refund = $this->service->processSaleDetailRefund(
            $this->store->id,
            $this->saleDetail->id,
            1,
            'Test refund'
        );

        $this->product->refresh();
        $stockAfterRefund = $this->product->stock_quantity;

        $this->service->cancelRefund($this->store->id, $refund->id);

        $this->product->refresh();
        $this->assertEquals($stockAfterRefund - 1, $this->product->stock_quantity);
        $this->assertFalse(Refund::find($refund->id));
    }

    /** @test */
    public function can_get_refund_summary()
    {
        Refund::factory()->create([
            'store_id' => $this->store->id,
            'refund_amount' => 100.00,
        ]);

        Refund::factory()->create([
            'store_id' => $this->store->id,
            'refund_amount' => 50.00,
        ]);

        $summary = $this->service->getRefundSummary($this->store->id);

        $this->assertEquals(2, $summary['total_refunds']);
        $this->assertEquals(150.00, $summary['total_refund_amount']);
    }

    /** @test */
    public function refund_summary_filtered_by_date()
    {
        $oldRefund = Refund::factory()->create([
            'store_id' => $this->store->id,
            'refund_amount' => 100.00,
            'created_at' => now()->subDays(10),
        ]);

        $newRefund = Refund::factory()->create([
            'store_id' => $this->store->id,
            'refund_amount' => 50.00,
            'created_at' => now(),
        ]);

        $summary = $this->service->getRefundSummary(
            $this->store->id,
            now()->subDays(5),
            now()
        );

        $this->assertEquals(1, $summary['total_refunds']);
        $this->assertEquals(50.00, $summary['total_refund_amount']);
    }
}
