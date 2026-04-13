# SWEATLESS POS

SWEATLESS POS is a Laravel + Livewire point-of-sale system built for retail and multi-outlet operations. It focuses on fast sales workflows, stock control, customer management, and reporting with role-based access control and store-scoped data.

## What This Project Does

- Runs a POS flow for daily sales with receipts and barcode support
- Tracks inventory, suppliers, categories, and product costs
- Manages customers, debtors, and refunds/returns
- Records expenditures and generates financial reports
- Provides comprehensive reports and dashboards
- Supports multiple stores with store-scoped data
- Enforces role-based access (Super Admin, Admin, Cashier)

## Key Features

### Sales & Transactions
- Point-of-sale (POS) interface with barcode scanner support
- Product search and quick add to cart
- Multiple payment methods (Cash, Mobile Money, Credit Card)
- Automatic receipt generation with printable format
- PDF receipt download
- Sales management with filtering by date, payment method
- Refunds and returns processing
- Customer association with sales (Guest or registered)
- Multi-currency support (GHS - Ghana Cedis default)

### Inventory Management
- Product management with SKU, barcode, images
- Category and supplier management
- Stock quantity tracking with low stock alerts
- Stock adjustment history
- Reorder alerts and purchase order management
- Product variants support (future expansion ready)
- Expiry date tracking for perishables

### Customer & Debtor Management
- Customer registration and profiles
- Customer purchase history
- Debtor management with payment tracking
- Customer tiers (VIP, Premium, Regular based on spending)

### Financial Management
- Expenditure tracking by category
- Cash drawer management (opening/closing)
- Complete financial reports

### Reports & Analytics
- **Sales Report** - Transaction listing with filters, cashier info
- **Inventory Report** - Stock levels, low stock alerts, expiring soon
- **Financial Report** - Revenue, costs, profit, margins, payment breakdowns
- **Customer Report** - Customer tiers, total orders, avg order value, last purchase
- **Daily Sales Summary** - Daily/weekly/monthly grouping, payment method breakdown
- **Product Performance** - Top sellers, slow movers, revenue, profit per product
- **Staff Performance** - Sales by staff, transaction count, refunds handled
- **Category Performance** - Revenue, cost, profit by product category
- **Expenditure Report** - Date-wise expenditure tracking by category

All reports feature:
- Date range filtering
- Export to Excel (CSV/XLSX)
- Summary statistics and visualizations

### User & Access Management
- Role-based access control (Super Admin, Admin, Cashier)
- User creation and assignment to stores
- Permission management per role

### Multi-Store Support
- Store creation and management
- Store-scoped data isolation
- Global vs store-specific settings
- Per-store configuration (logo, site name, receipt footer)

### Dashboard
- General overview dashboard for admins
- Sales analytics dashboard with charts
- Real-time sales metrics

## Project Structure

```
app/
|-- Livewire/                    # Livewire components (UI + actions)
|   |-- Admin/                   # Admin-specific components
|   |-- Website/                # Public website components
|   |-- *.php                   # Core POS components
|-- Models/                      # Eloquent models
|-- Helpers/                     # Helper utilities (e.g., StoreHelper)
|-- Traits/                      # Shared traits (e.g., StoreScoped)

resources/
|-- views/                       # Blade templates
|   |-- layouts/                # Layout files
|   |-- livewire/               # Livewire component views

routes/
|-- web.php                     # Web routes and role middleware

database/
|-- migrations/                 # Schema migrations
|-- seeders/                    # Seed data

public/
|-- assets/                     # Public assets
|-- vendors/                    # Frontend vendor libs
```

## Tech Stack

- Laravel 11.x
- Livewire 3.x
- Spatie Laravel Permission (roles/permissions)
- Jetstream + Sanctum (auth)
- Chart.js (dashboards)
- Maatwebsite Excel (export)
- PhpSpreadsheet (Excel generation)
- Picqer Barcode Generator (barcodes)
- html2pdf (PDF generation)

## Requirements

- PHP 8.2+
- Composer
- Node.js + npm
- MySQL/MariaDB (or compatible)

## Installation

```bash
# Clone the repository
git clone <repository-url>
cd sweatlesspos-app

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Build assets
npm run build

# Start the development server
php artisan serve
```

## Development

```bash
# Start Laravel server
php artisan serve

# Start Vite dev server
npm run dev
```

## Roles & Permissions

| Feature | Super Admin | Admin | Cashier |
|---------|-------------|-------|---------|
| Store Management | ✓ | - | - |
| User Management | ✓ | - | - |
| Product Management | ✓ | ✓ | - |
| Category Management | ✓ | ✓ | - |
| Supplier Management | ✓ | ✓ | - |
| Make Sales | ✓ | ✓ | ✓ |
| View Sales | ✓ | ✓ | ✓ |
| Refunds/Returns | ✓ | ✓ | - |
| Expenditures | ✓ | ✓ | - |
| Reports | ✓ | ✓ | ✓ |
| Dashboard | ✓ | ✓ | ✓ |

## API Endpoints (if needed)

The application uses Laravel Sanctum for API token authentication. Generate tokens from user profile.

## License

MIT