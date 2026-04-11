<?php

namespace Tests\Unit\Services;

use App\Models\CashDrawerSession;
use App\Models\Expenditure;
use App\Models\Refund;
use App\Models\Sale;
use App\Models\User;
use App\Models\Store;
use App\Services\CashDrawerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CashDrawerServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CashDrawerService $service;
    protected User $user;
    protected Store $store;

    public function setUp(): void
    {
        parent::setUp();

        $this->store = Store::factory()->create();
        $this->user = User::factory()->create(['store_id' => $this->store->id]);
        $this->actingAs($this->user);

        $this->service = app(CashDrawerService::class);
    }

    #[Test]
    public function can_open_cash_drawer()
    {
        $session = $this->service->openDrawer(
            $this->store->id,
            1000.00,
            'Opening balance for day 1'
        );

        $this->assertInstanceOf(CashDrawerSession::class, $session);
        $this->assertEquals(1000.00, $session->opening_balance);
        $this->assertEquals('open', $session->status);
        $this->assertNotNull($session->opened_at);
    }

    #[Test]
    public function cannot_open_drawer_if_one_already_open()
    {
        $this->service->openDrawer($this->store->id, 1000.00);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('You already have an open cash drawer session.');

        $this->service->openDrawer($this->store->id, 2000.00);
    }

    #[Test]
    public function can_close_cash_drawer()
    {
        $this->service->openDrawer($this->store->id, 1000.00);

        $session = $this->service->closeDrawer(
            $this->store->id,
            1200.00,
            'Closing balance'
        );

        $this->assertEquals('closed', $session->status);
        $this->assertEquals(1200.00, $session->closing_balance);
        $this->assertNotNull($session->closed_at);
    }

    #[Test]
    public function cannot_close_drawer_if_none_open()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No open cash drawer session found.');

        $this->service->closeDrawer($this->store->id, 1000.00);
    }

    #[Test]
    public function can_get_open_session()
    {
        $this->service->openDrawer($this->store->id, 1000.00);

        $session = $this->service->getOpenSession($this->store->id);

        $this->assertNotNull($session);
        $this->assertEquals('open', $session->status);
    }

    #[Test]
    public function returns_null_when_no_open_session()
    {
        $session = $this->service->getOpenSession($this->store->id);

        $this->assertNull($session);
    }

    #[Test]
    public function can_check_if_session_exists()
    {
        $this->assertFalse($this->service->hasOpenSession($this->store->id));

        $this->service->openDrawer($this->store->id, 1000.00);

        $this->assertTrue($this->service->hasOpenSession($this->store->id));
    }

    #[Test]
    public function can_calculate_cash_summary()
    {
        $this->service->openDrawer($this->store->id, 1000.00);
        $openedAt = now()->subHours(2);

        // Create cash sales
        Sale::factory()->create([
            'store_id' => $this->store->id,
            'payment_method' => 'cash',
            'total_amount' => 500.00,
            'created_at' => $openedAt->addMinutes(30),
        ]);

        Sale::factory()->create([
            'store_id' => $this->store->id,
            'payment_method' => 'cash',
            'total_amount' => 300.00,
            'created_at' => $openedAt->addMinutes(60),
        ]);

        // Create cash refunds
        Refund::factory()->create([
            'store_id' => $this->store->id,
            'refund_amount' => 100.00,
            'created_at' => $openedAt->addMinutes(90),
        ]);

        // Create cash expenses
        Expenditure::factory()->create([
            'store_id' => $this->store->id,
            'payment_method' => 'cash',
            'amount' => 50.00,
            'expenditure_date' => $openedAt->toDateString(),
        ]);

        $summary = $this->service->calculateCashSummary(
            $this->store->id,
            $openedAt,
            now()
        );

        $this->assertEquals(800.00, $summary['cash_sales']);
        $this->assertEquals(100.00, $summary['cash_refunds']);
        $this->assertEquals(50.00, $summary['cash_expenses']);
    }

    #[Test]
    public function can_get_live_summary()
    {
        $openSession = $this->service->openDrawer($this->store->id, 1000.00);

        $liveSummary = $this->service->getLiveSummary($openSession);

        $this->assertArrayHasKey('cash_sales', $liveSummary);
        $this->assertArrayHasKey('cash_refunds', $liveSummary);
        $this->assertArrayHasKey('cash_expenses', $liveSummary);
        $this->assertArrayHasKey('expected_cash', $liveSummary);
        $this->assertEquals(1000.00, $liveSummary['expected_cash']);
    }

    #[Test]
    public function calculates_discrepancy_on_close()
    {
        $this->service->openDrawer($this->store->id, 1000.00);

        $session = $this->service->closeDrawer($this->store->id, 950.00);

        $this->assertEquals(-50.00, $session->discrepancy);
    }
}
