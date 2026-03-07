<?php

use App\Livewire\AddUser;
use App\Livewire\CustomerReport;
use App\Livewire\CustomerReportDetail;
use App\Livewire\EditUser;
use App\Livewire\FinancialReport;
use App\Livewire\InventoryReport;
use App\Livewire\MakeSales;
use App\Livewire\ManageCategory;
use App\Livewire\ManageCustomers;
use App\Livewire\ManageDebtors;
use App\Livewire\ManageExpenditures;
use App\Livewire\ManageProduct;
use App\Livewire\ManageStore;
use App\Livewire\SaleReport;
use App\Livewire\ManageReturns;
use App\Livewire\ManageRoles;
use App\Livewire\ManageSales;
use App\Livewire\ManageSuppliers;
use App\Livewire\ManageUser;
use App\Livewire\Notifications;
use App\Livewire\Settings;
use App\Livewire\SystemLogs;
use App\Helpers\StoreHelper;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Group for authenticated routes
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    // Dashboard routes
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard')->middleware('role:super-admin|admin|cashier');

    Route::get('/sales-dashboard', \App\Livewire\SalesDashboard::class)
        ->name('sales.dashboard')
        ->middleware('role:super-admin|admin|cashier');

    // Users Management routes for Super-Admin only
    Route::middleware('role:super-admin')->group(function () {
        Route::get('/manage-users', ManageUser::class)->name('manage.users');
        Route::get('/add-users', AddUser::class)->name('add.users');
        Route::get('/users/{id}/edit', EditUser::class)->name('edit.user');
        Route::get('/manage-stores', ManageStore::class)->name('manage.stores');
        Route::post('/switch-store', function (\Illuminate\Http\Request $request) {
            $validated = $request->validate([
                'store_id' => ['required', 'integer', 'exists:stores,id'],
            ]);

            StoreHelper::setCurrentStore((int) $validated['store_id']);

            return back();
        })->name('switch.store');
        Route::get('/system-settings', Settings::class)->name('system.settings');
        Route::get('/manage-roles', ManageRoles::class)->name('manage.roles');
        Route::get('/system-notifications', Notifications::class)->name('manage.notifications');
        Route::get('/system-logs', SystemLogs::class)->name('system.logs');
    });

    // Routes accessible by Admin and Super-Admin
    Route::middleware('role:admin|super-admin')->group(function () {
        Route::get('/manage-suppliers', ManageSuppliers::class)->name('manage.suppliers');
        Route::get('/manage-categories', ManageCategory::class)->name('manage.categories');
        Route::get('/manage-products', ManageProduct::class)->name('manage-products');
        Route::get('/manage-sales', ManageSales::class)->name('manage.sales');
        Route::get('/manage-debtors', ManageDebtors::class)->name('manage.debtors');
        Route::get('/make-sales', MakeSales::class)->name('make.sales');
        Route::get('/manage-expenditure', ManageExpenditures::class)->name('manage.expenditure');
        Route::get('/manage-returns', ManageReturns::class)->name('manage.returns');
        Route::get('/sale-report', SaleReport::class)->name('sale.report');
        Route::get('/inventory-report', InventoryReport::class)->name('inventory.report');
        Route::get('/financial-report', FinancialReport::class)->name('financial.report');
    });

    // Routes accessible by Cashier, Admin, and Super-Admin
    Route::middleware('role:cashier|admin|super-admin')->group(function () {
        Route::get('/make-sales', MakeSales::class)->name('make.sales');
        Route::get('/manage-customers', ManageCustomers::class)->name('manage.customers');
        Route::get('/sales-dashboard', \App\Livewire\SalesDashboard::class)->name('sales.dashboard');
        Route::get('/user/profile', \App\Livewire\UserProfile::class)->name('user.profile');
        Route::get('/customer-report', CustomerReport::class)->name('customer.report');
        Route::get('/customer-report-detail/{id}', CustomerReportDetail::class)->name('customerreport.detail');
        Route::get('/manage-customers', ManageCustomers::class)->name('manage.customers');
    });
});
