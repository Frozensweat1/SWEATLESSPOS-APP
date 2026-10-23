<?php

namespace App\Http\Controllers;

use App\Helpers\StoreHelper;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Services\SalesService;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OfflinePosController extends Controller
{
    private const PAYMENT_METHODS = ['cash', 'credit_card', 'mobile_money', 'credit'];

    public function page()
    {
        return view('offline-pos');
    }

    // Fresh CSRF token for syncing from a page that may have been cached long ago.
    public function token(): JsonResponse
    {
        return $this->noStore(['token' => csrf_token()]);
    }

    public function catalog(): JsonResponse
    {
        $storeId = (int) StoreHelper::getCurrentStoreId();

        $products = Product::where('store_id', $storeId)
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->orderBy('name')
            ->get(['id', 'name', 'barcode', 'sku', 'price', 'stock_quantity'])
            ->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'barcode' => $p->barcode,
                'sku' => $p->sku,
                'price' => (float) $p->price,
                'stock' => (int) $p->stock_quantity,
            ]);

        $customers = Customer::where('store_id', $storeId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'phone']);

        return $this->noStore([
            'store_id' => $storeId,
            'generated_at' => now()->toIso8601String(),
            'currency' => config('app.currency_symbol', 'GHS'),
            'products' => $products,
            'customers' => $customers,
        ]);
    }

    public function sync(Request $request, SalesService $service): JsonResponse
    {
        $data = $request->validate([
            'sales' => ['required', 'array', 'min:1', 'max:50'],
            'sales.*.uuid' => ['required', 'uuid'],
            'sales.*.store_id' => ['required', 'integer'],
            'sales.*.items' => ['required', 'array', 'min:1', 'max:100'],
            'sales.*.items.*.product_id' => ['required', 'integer'],
            'sales.*.items.*.quantity' => ['required', 'integer', 'min:1', 'max:100000'],
            'sales.*.customer_id' => ['nullable', 'integer'],
            'sales.*.payment_method' => ['required', 'in:' . implode(',', self::PAYMENT_METHODS)],
            'sales.*.sold_at' => ['nullable', 'date'],
        ]);

        $storeId = (int) StoreHelper::getCurrentStoreId();
        $results = [];

        foreach ($data['sales'] as $sale) {
            $results[] = ['uuid' => $sale['uuid']] + $this->syncOne($service, $sale, $storeId);
        }

        return $this->noStore(['results' => $results]);
    }

    private function syncOne(SalesService $service, array $sale, int $storeId): array
    {
        if ($existing = Sale::where('client_uuid', $sale['uuid'])->first()) {
            return ['status' => 'synced', 'sale_id' => $existing->id, 'duplicate' => true];
        }

        if ((int) $sale['store_id'] !== $storeId) {
            return ['status' => 'failed', 'message' => 'This sale was captured for a different store. Switch to that store and sync again.'];
        }

        $customerId = $sale['customer_id'] ?? null;
        if ($customerId && ! Customer::where('store_id', $storeId)->whereKey($customerId)->exists()) {
            return ['status' => 'failed', 'message' => 'Customer no longer exists.'];
        }
        if ($sale['payment_method'] === 'credit' && ! $customerId) {
            return ['status' => 'failed', 'message' => 'Credit sales need a customer.'];
        }

        // Prices always come from the server, never from the device.
        $quantities = [];
        foreach ($sale['items'] as $item) {
            $quantities[$item['product_id']] = ($quantities[$item['product_id']] ?? 0) + $item['quantity'];
        }
        $prices = Product::where('store_id', $storeId)->whereIn('id', array_keys($quantities))->pluck('price', 'id');

        $cartItems = [];
        $total = 0;
        foreach ($quantities as $productId => $qty) {
            if (! $prices->has($productId)) {
                return ['status' => 'failed', 'message' => 'A product in this sale no longer exists.'];
            }
            $price = (float) $prices[$productId];
            $cartItems[$productId] = ['product_id' => $productId, 'price' => $price, 'quantity' => $qty];
            $total += $price * $qty;
        }

        $soldAt = isset($sale['sold_at']) ? Carbon::parse($sale['sold_at']) : null;
        if ($soldAt && $soldAt->isFuture()) {
            $soldAt = now();
        }

        try {
            $created = $service->createSale(
                $storeId,
                $cartItems,
                $customerId ? (int) $customerId : null,
                $sale['payment_method'],
                $sale['payment_method'] === 'credit' ? 0.0 : round($total, 2),
                [],
                $sale['uuid'],
                $soldAt
            );
        } catch (QueryException $e) {
            if ($existing = Sale::where('client_uuid', $sale['uuid'])->first()) {
                return ['status' => 'synced', 'sale_id' => $existing->id, 'duplicate' => true];
            }
            report($e);

            return ['status' => 'failed', 'message' => 'Could not save this sale. Try again.'];
        } catch (\Exception $e) {
            return ['status' => 'failed', 'message' => $e->getMessage()];
        }

        return ['status' => 'synced', 'sale_id' => $created->id];
    }

    private function noStore(array $payload): JsonResponse
    {
        return response()->json($payload)->header('Cache-Control', 'no-store, private');
    }
}
