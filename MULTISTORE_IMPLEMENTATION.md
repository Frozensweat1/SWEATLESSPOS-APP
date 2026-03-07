# Multi-Store POS System Implementation Guide

## Overview

This POS application has been configured to support multiple stores (branches/locations) under a single dashboard. Each store operates independently while maintaining centralized administration.

## Database Schema Changes

### New Table: `stores`

```sql
- id (Primary Key)
- name (unique)
- address
- phone
- email
- logo_path
- initial_balance (decimal)
- is_active (boolean)
- created_at
- updated_at
```

### Updated Tables (Added `store_id` foreign key)

- `users` - Each user is linked to one store
- `products` - Products are store-specific
- `categories` - Categories are store-specific
- `customers` - Customers are store-specific
- `suppliers` - Suppliers are store-specific
- `sales` - Sales transactions are store-specific

## Model Relationships

### Store Model

```php
$store->users();          // hasMany
$store->products();       // hasMany
$store->categories();     // hasMany
$store->customers();      // hasMany
$store->suppliers();      // hasMany
$store->sales();          // hasMany
```

### User Model

```php
$user->store();           // belongsTo Store
$user->sales();           // hasMany Sales
$user->expenditures();    // hasMany Expenditures
```

### Product, Category, Customer, Supplier Models

Each now includes:

```php
belongsTo(Store::class);  // Belongs to a store
```

## Implementation in Livewire Components

### 1. Import the StoreHelper and StoreScoped Trait

```php
use App\Helpers\StoreHelper;
use App\Traits\StoreScoped;

class MakeSales extends Component
{
    use StoreScoped;

    public $store_id;

    public function mount()
    {
        $this->initStoreId();  // Initialize store_id from current store
    }
}
```

### 2. Filter Queries by Current Store

```php
// Instead of:
$products = Product::all();

// Use:
$products = Product::where('store_id', $this->store_id)->get();

// Or with relationship:
$products = StoreHelper::getCurrentStore()->products;
```

### 3. When Creating Records

```php
// Always include store_id when creating new records:
Product::create([
    'name' => $name,
    'price' => $price,
    'store_id' => $this->store_id,  // ← Add this
    ...
]);
```

## Helper Functions

### `StoreHelper::getCurrentStore()`

Returns the Store model for the authenticated user's current store.

```php
$store = StoreHelper::getCurrentStore();
echo $store->name;
```

### `StoreHelper::getCurrentStoreId()`

Returns just the store ID.

```php
$storeId = StoreHelper::getCurrentStoreId();
```

### `StoreHelper::setCurrentStore($store)`

Set the current store in session (if user has access).

```php
StoreHelper::setCurrentStore($store);
```

### `StoreHelper::userHasStoreAccess($store)`

Check if authenticated user can access a specific store.

```php
if (StoreHelper::userHasStoreAccess($store)) {
    // Allow access
}
```

## Complete Component Example

```php
<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Helpers\StoreHelper;
use App\Traits\StoreScoped;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ManageProduct extends Component
{
    use StoreScoped;

    public $store_id;
    public $products = [];

    public function mount()
    {
        $this->initStoreId();  // Get current store ID
        $this->loadProducts();
    }

    public function loadProducts()
    {
        // Auto-filter by current store
        $this->products = Product::where('store_id', $this->store_id)
            ->get();
    }

    public function createProduct($name, $price)
    {
        Product::create([
            'name' => $name,
            'price' => $price,
            'store_id' => $this->store_id,  // ← Essential!
        ]);

        $this->loadProducts();
    }

    public function render()
    {
        return view('livewire.manage-product');
    }
}
```

## Layouts Integration

Update layout header to display current store:

```blade
<h4 style="margin: 5px 0; font-weight: bold;">
    @if ($currentStore = \App\Helpers\StoreHelper::getCurrentStore())
        {{ $currentStore->name }}
    @else
        {{ config('app.name') }}
    @endif
</h4>
```

## Migration to Run

```bash
php artisan migrate
```

This will:

1. Create the `stores` table
2. Add `store_id` foreign keys to all relevant tables
3. Create proper indexes and cascade delete rules

## Next Steps

### For Each Livewire Component:

1. Add `use StoreScoped;` trait
2. Add `public $store_id;` property
3. Call `$this->initStoreId();` in `mount()`
4. Filter all queries with `->where('store_id', $this->store_id)`
5. Include `store_id` when creating/updating records

### For Dashboard/Admin Features:

1. Create Store Management component
2. Create User-Store Assignment feature
3. Add Store selector in header/navigation
4. Create Store Reports and Analytics

### For Settings:

Update Settings model to be store-scoped:

- Logo, address, phone, email per store
- Tax rates per store
- Receipt settings per store

## Security Considerations

1. **Always verify store access** before allowing operations:

```php
if (!StoreHelper::userHasStoreAccess($storeId)) {
    abort(403, 'Unauthorized access to this store');
}
```

2. **Use query scoping** consistently to prevent data leakage

3. **Validate store_id** in form requests

4. **Add middleware** to automatically set store context:

```php
middleware(['store.scope']);
```

## Benefits of This Approach

✅ **Isolated Data**: Each store's data is completely isolated
✅ **Single Dashboard**: Manage multiple stores from one admin panel
✅ **Scalability**: Easy to add new stores without code changes
✅ **User Management**: Controls which staff work at which store
✅ **Audit Trail**: All operations linked to specific store
✅ **Financial Reporting**: Generate per-store reports
✅ **Inventory Management**: Track inventory per location

## Example Query Patterns

```php
// Get all products for current store
Product::where('store_id', $this->store_id)->get();

// Get today's sales for current store
Sale::where('store_id', $this->store_id)
    ->whereDate('created_at', today())
    ->get();

// Get store's total revenue
StoreHelper::getCurrentStore()->sales()->sum('total_amount');

// Get store's customers
StoreHelper::getCurrentStore()->customers()->get();

// Get store's inventory value
StoreHelper::getCurrentStore()->products()
    ->sum(DB::raw('stock_quantity * price'));
```

## Support & Troubleshooting

If data appears from wrong store:

1. Check that queries include `->where('store_id', $this->store_id)`
2. Verify `$this->store_id` is properly initialized in mount()
3. Check browser session doesn't have stale store_id

If store_id is null:

1. Verify user has a store_id assigned
2. Check `StoreHelper::getCurrentStore()` returns valid store
3. Ensure migrations have run successfully

---

**Last Updated**: March 6, 2026
**Status**: Multi-Store Foundation Complete
**Next Phase**: Component Migration & Testing
