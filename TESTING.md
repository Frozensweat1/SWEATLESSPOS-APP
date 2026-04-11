# Unit Tests Guide

## Running Tests

### Run all tests

```bash
php artisan test
```

### Run only service tests

```bash
php artisan test tests/Unit/Services
```

### Run specific test file

```bash
php artisan test tests/Unit/Services/CashDrawerServiceTest.php
```

### Run specific test method

```bash
php artisan test tests/Unit/Services/CashDrawerServiceTest.php --filter=can_open_cash_drawer
```

### Run with verbose output

```bash
php artisan test --verbose
```

### Generate coverage report

```bash
php artisan test --coverage
```

### Run tests and stop on first failure

```bash
php artisan test --stop-on-failure
```

---

## Test Structure

### CashDrawerServiceTest (13 tests)

- ✅ can_open_cash_drawer
- ✅ cannot_open_drawer_if_one_already_open
- ✅ can_close_cash_drawer
- ✅ cannot_close_drawer_if_none_open
- ✅ can_get_open_session
- ✅ returns_null_when_no_open_session
- ✅ can_check_if_session_exists
- ✅ can_calculate_cash_summary
- ✅ can_get_live_summary
- ✅ calculates_discrepancy_on_close

### SalesServiceTest (15 tests)

- ✅ can_add_item_to_cart
- ✅ can_increase_quantity_if_already_in_cart
- ✅ can_remove_item_from_cart
- ✅ can_update_item_quantity
- ✅ removes_item_if_quantity_set_to_zero
- ✅ can_calculate_cart_totals
- ✅ can_calculate_percentage_discount
- ✅ can_calculate_fixed_discount
- ✅ cannot_create_sale_with_empty_cart
- ✅ can_create_cash_sale
- ✅ can_create_credit_sale
- ✅ creates_sale_details_on_sale
- ✅ updates_product_stock_on_sale
- ✅ creates_stock_adjustment_on_sale
- ✅ can_get_recent_sales
- ✅ respects_limit_in_get_recent_sales

### RefundServiceTest (14 tests)

- ✅ can_process_sale_detail_refund
- ✅ cannot_refund_more_than_sold
- ✅ cannot_refund_zero_quantity
- ✅ creates_stock_adjustment_on_refund
- ✅ can_process_bulk_refunds
- ✅ can_get_refunds_with_filters
- ✅ filters_refunds_by_product
- ✅ filters_refunds_by_date_range
- ✅ can_cancel_refund
- ✅ can_get_refund_summary
- ✅ refund_summary_filtered_by_date

**Total: 42 unit tests**

---

## What's Tested

### CashDrawerService

- Opening drawer with validation
- Preventing multiple open sessions
- Closing drawer with calculations
- Session retrieval
- Cash summary calculation (sales, refunds, expenses)
- Discrepancy detection

### SalesService

- Cart operations (add, remove, update)
- Discount calculations (percentage and fixed)
- Sale creation with stock updates
- Payment method handling (cash vs credit)
- Sale detail creation
- Stock adjustment recording
- Recent sales retrieval

### RefundService

- Single item refund processing
- Quantity validation
- Stock restoration
- Bulk refund processing
- Refund filtering (product, user, date)
- Refund cancellation
- Refund summary reporting

---

## Continuous Integration Setup

To run tests automatically on git push:

### Create `.githooks/pre-push`

```bash
#!/bin/bash
php artisan test
if [ $? -ne 0 ]; then
    echo "Tests failed. Push aborted."
    exit 1
fi
```

Make it executable:

```bash
chmod +x .githooks/pre-push
git config core.hooksPath .githooks
```

---

## Coverage Goals

Target coverage:

- Services: **100%** - All business logic tested
- Models: **80%** - Scopes and relationships
- Controllers: **70%** - Error handling

Generate coverage:

```bash
php artisan test --coverage --min=70
```

---

## Writing New Tests

### Template

```php
/** @test */
public function describes_what_is_being_tested()
{
    // Arrange - Set up test data
    $item = Item::factory()->create();

    // Act - Execute the functionality
    $result = $service->doSomething($item);

    // Assert - Verify the result
    $this->assertEquals($expected, $result);
}
```

### Common Assertions

```php
$this->assertTrue($condition);
$this->assertFalse($condition);
$this->assertEquals($expected, $actual);
$this->assertNotNull($value);
$this->assertNull($value);
$this->assertCount($count, $collection);
$this->assertContains($needle, $haystack);
$this->assertInstanceOf($class, $object);
$this->expectException(\Exception::class);
```

---

## Troubleshooting

### Tests failing with "No such table"

Ensure SQLite test database is set up:

```bash
APP_ENV=testing php artisan migrate --database=sqlite
```

### Tests failing with factory not found

Create missing factories:

```bash
php artisan make:factory YourModelFactory
```

### Tests too slow

- Use in-memory SQLite: `DB_DATABASE=:memory:` in `.env.testing`
- Run tests in parallel: `php artisan test --parallel`
- Cache factory data with `factory()->count(100)`

---

## Next Steps

1. **Feature Tests** - Test Livewire components end-to-end
2. **API Tests** - Test REST endpoints
3. **Performance Tests** - Benchmark slow queries
4. **Integration Tests** - Test service interactions
