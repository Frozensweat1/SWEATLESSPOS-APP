# Product Barcode Generation Feature

## Overview
This feature allows users to generate and export product barcodes as PDF files using Laravel's job queue system for efficient, non-blocking processing.

## Components Created

### 1. **Job Class: GenerateProductBarcodesPdf**
**Location:** `app/Jobs/GenerateProductBarcodesPdf.php`

Handles the background processing of barcode PDF generation:
- Fetches products with barcodes from database
- Generates barcode images using `picqer/php-barcode-generator`
- Renders PDF using DomPDF
- Stores PDF file in `storage/app/barcodes/`
- Logs success/failure for audit trails

**Supported Barcode Types:**
- CODE128 (Recommended)
- CODE39
- EAN13
- UPCA

### 2. **Livewire Component: GenerateBarcodes**
**Location:** `app/Livewire/GenerateBarcodes.php`

Provides interactive UI for barcode generation:
- Search products by name or barcode
- Select individual or all products
- Configure barcode type and items per page
- Dispatch jobs to queue
- Real-time feedback via alerts

**Key Methods:**
- `selectProductToggle()` - Toggle individual product selection
- `toggleSelectAll()` - Toggle select all checkbox
- `generateBarcodes()` - Dispatch selected products to queue
- `generateAllBarcodes()` - Generate barcodes for all matching products

### 3. **Blade View: generate-barcodes.blade.php**
**Location:** `resources/views/livewire/generate-barcodes.blade.php`

User interface with:
- Search and filter section
- Barcode settings (type, items per page)
- Product selection table
- Action buttons (Generate Selected, Generate All)
- Status display showing selected item count

### 4. **PDF Template: barcode-pdf.blade.php**
**Location:** `resources/views/barcodes/barcode-pdf.blade.php`

Renders barcode PDF with:
- Grid layout (3-4 barcodes per row)
- Auto page breaks at configured intervals
- Product name and barcode code
- Timestamp and total count
- Print-optimized styling

## Database Changes

### New Queue Tables
- `jobs` - Queue job records
- `failed_jobs` - Failed job tracking

Applied via migrations:
```bash
php artisan migrate
```

## Installation & Setup

### 1. Install Dependencies
```bash
composer require barryvdh/laravel-dompdf
```

### 2. Create Files
All files have been created:
- ✅ Job class
- ✅ Livewire component
- ✅ Blade views
- ✅ Routes
- ✅ Navigation link

### 3. Run Migrations
```bash
php artisan migrate
```

### 4. Configure Queue
Update `.env` to use database queue driver:
```env
QUEUE_CONNECTION=database
```

Or use sync for testing:
```env
QUEUE_CONNECTION=sync
```

## Usage

### Via Web UI
1. Navigate to **Product Management > Generate Barcodes**
2. Search for products (optional)
3. Select products or click "Generate All"
4. Configure barcode type if needed
5. Click "Generate Selected" button
6. PDF will be queued and generated in background

### Via Command Line
```php
use App\Jobs\GenerateProductBarcodesPdf;

GenerateProductBarcodesPdf::dispatch(
    [1, 2, 3], // Product IDs
    auth()->id(), // User ID
    'CODE128', // Barcode type
    12 // Items per page
);
```

## Queue Configuration

### Running Queue Worker (Production)
```bash
# Start queue worker
php artisan queue:work

# Process one job and exit (for cron jobs)
php artisan queue:work --once

# Process specific queue
php artisan queue:work --queue=default
```

### Managing Failed Jobs
```bash
# List failed jobs
php artisan queue:failed

# Retry specific failed job
php artisan queue:retry {id}

# Retry all failed jobs
php artisan queue:retry all

# Delete failed job
php artisan queue:forget {id}
```

## File Storage

Generated PDFs are stored in:
```
storage/app/barcodes/barcodes_[timestamp].pdf
```

Users should configure a download mechanism or expose via route.

## Security

- ✅ Role-based access control (`admin|super-admin`)
- ✅ User tracking via `auth()->id()` in job
- ✅ Validation on barcode types
- ✅ Logging of all operations
- ✅ Protection against invalid product IDs

## Performance Considerations

1. **Background Processing:** Uses Laravel jobs queue
   - Prevents UI blocking
   - Scalable with multiple workers

2. **Database Queries Optimized:**
   - Only fetches products with barcodes
   - Limits to selected products
   - Single query per job

3. **PDF Generation:**
   - DomPDF efficiently renders to PDF
   - SVG barcodes for crisp output
   - Grid layout minimizes pages

## Troubleshooting

### Jobs not processing
```bash
# Check if worker is running
php artisan queue:work

# Check failed jobs
php artisan queue:failed

# Use sync driver for debugging
# Set QUEUE_CONNECTION=sync in .env
```

### PDF not generating
- Ensure all products have `barcode` field populated
- Check `storage/app/barcodes/` directory exists
- Verify DomPDF is installed: `composer show barryvdh/laravel-dompdf`

### Missing route error
- Verify route registered in `routes/web.php`
- Check user has `admin` or `super-admin` role
- Verify middleware is correct

## Feature Enhancements (Future)

- [ ] Download generated PDFs via authenticated route
- [ ] Email PDFs to users
- [ ] Custom barcode label templates
- [ ] Batch scheduling
- [ ] Barcode label printer integration
- [ ] Barcode format validation
- [ ] Import/export barcode data

## Related Files
- Job: `app/Jobs/GenerateProductBarcodesPdf.php`
- Component: `app/Livewire/GenerateBarcodes.php`
- View: `resources/views/livewire/generate-barcodes.blade.php`
- PDF Template: `resources/views/barcodes/barcode-pdf.blade.php`
- Route: `routes/web.php` (line with `/generate-barcodes`)
- Navigation: `resources/views/layouts/partials/sidebar.blade.php`

## Dependencies

- **laravel/framework**: ^11 (Job, Queue classes)
- **livewire/livewire**: ^3 (Component framework)
- **picqer/php-barcode-generator**: ^3 (Barcode generation)
- **barryvdh/laravel-dompdf**: ^3.1 (PDF rendering)
- **jantinnerezo/livewire-alert**: ^4.0 (User notifications)
