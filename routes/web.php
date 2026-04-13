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
use App\Livewire\ManageReorderAlerts;
use App\Livewire\ManagePurchaseOrders;
use App\Livewire\ManageCashDrawer;
use App\Livewire\ManageStockAdjustments;
use App\Livewire\ManageSuppliers;
use App\Livewire\ManageUser;
use App\Livewire\Notifications;
use App\Livewire\Settings;
use App\Livewire\SystemLogs;
use App\Livewire\Admin\ManageWebsitePages;
use App\Livewire\Admin\ManageGallery;
use App\Livewire\Admin\ManageWebsiteSettings;
use App\Helpers\StoreHelper;
use App\Livewire\Website\Home;
use App\Livewire\Website\About;
use App\Livewire\Website\Products;
use App\Livewire\Website\ProductDetail;
use App\Livewire\Website\Gallery;
use App\Livewire\Website\Contact;
use App\Livewire\Website\Testimonials;
use Illuminate\Support\Facades\Route;

// Public Website Routes
Route::get('/', Home::class)->name('website.home');
Route::get('/about', About::class)->name('website.about');
Route::get('/products', Products::class)->name('website.products');
Route::get('/product/{id}', ProductDetail::class)->name('website.product-detail');
Route::get('/testimonials', Testimonials::class)->name('website.testimonials');
Route::get('/gallery', Gallery::class)->name('website.gallery');
Route::get('/contact', Contact::class)->name('website.contact');

// Legacy home route redirect
Route::get('/welcome', function () {
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

        // Website Management Routes
        Route::get('/manage-website-pages', ManageWebsitePages::class)->name('manage.website.pages');
        Route::get('/manage-gallery', ManageGallery::class)->name('manage.gallery');
        Route::get('/manage-website-settings', ManageWebsiteSettings::class)->name('manage.website.settings');

        // New Features Routes
        Route::get('/manage-contact-submissions', \App\Livewire\Admin\ManageContactFormSubmissions::class)->name('manage.contact.submissions');
        Route::get('/manage-testimonials', \App\Livewire\Admin\ManageTestimonials::class)->name('manage.testimonials');
        Route::get('/manage-newsletter', \App\Livewire\Admin\ManageNewsletterSubscribers::class)->name('manage.newsletter');
        Route::get('/analytics', \App\Livewire\Admin\AnalyticsDashboard::class)->name('analytics.dashboard');
        Route::get('/activity-logs', \App\Livewire\Admin\ActivityLogViewer::class)->name('activity.logs');
    });

    // Routes accessible by Admin and Super-Admin
    Route::middleware('role:admin|super-admin')->group(function () {
        Route::get('/manage-suppliers', ManageSuppliers::class)->name('manage.suppliers');
        Route::get('/manage-categories', ManageCategory::class)->name('manage.categories');
        Route::get('/manage-products', ManageProduct::class)->name('manage-products');
        Route::get('/stock-adjustments', ManageStockAdjustments::class)->name('stock.adjustments');
        Route::get('/reorder-alerts', ManageReorderAlerts::class)->name('reorder.alerts');
        Route::get('/purchase-orders', ManagePurchaseOrders::class)->name('purchase.orders');
        Route::get('/manage-sales', ManageSales::class)->name('manage.sales');
        Route::get('/manage-debtors', ManageDebtors::class)->name('manage.debtors');
        Route::get('/make-sales', MakeSales::class)->name('make.sales');
        Route::get('/manage-expenditure', ManageExpenditures::class)->name('manage.expenditure');
        Route::get('/manage-returns', ManageReturns::class)->name('manage.returns');
        Route::get('/sale-report', SaleReport::class)->name('sale.report');
        Route::get('/inventory-report', InventoryReport::class)->name('inventory.report');
        Route::get('/financial-report', FinancialReport::class)->name('financial.report');
        Route::get('/daily-sales-summary', \App\Livewire\DailySalesSummary::class)->name('daily.sales.summary');
        Route::get('/product-performance', \App\Livewire\ProductPerformance::class)->name('product.performance');
        Route::get('/staff-performance', \App\Livewire\StaffPerformance::class)->name('staff.performance');
        Route::get('/category-performance', \App\Livewire\CategoryPerformance::class)->name('category.performance');
        Route::get('/expenditure-report', \App\Livewire\ExpenditureReport::class)->name('expenditure.report');
    });

    // Routes accessible by Cashier, Admin, and Super-Admin
    Route::middleware('role:cashier|admin|super-admin')->group(function () {
        Route::get('/make-sales', MakeSales::class)->name('make.sales');
        Route::get('/cash-drawer', ManageCashDrawer::class)->name('cash.drawer');
        Route::get('/manage-customers', ManageCustomers::class)->name('manage.customers');
        Route::get('/sales-dashboard', \App\Livewire\SalesDashboard::class)->name('sales.dashboard');
        Route::get('/user/profile', \App\Livewire\UserProfile::class)->name('user.profile');
        Route::get('/customer-report', CustomerReport::class)->name('customer.report');
        Route::get('/customer-report-detail/{id}', CustomerReportDetail::class)->name('customerreport.detail');
        Route::get('/manage-customers', ManageCustomers::class)->name('manage.customers');
    });
});
