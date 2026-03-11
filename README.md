# SWEATLESS POS

SWEATLESS POS is a Laravel + Livewire point-of-sale system built for retail and multi-outlet operations. It focuses on fast sales workflows, stock control, customer management, and reporting with role-based access control and store-scoped data.

## What This Project Does

- Runs a POS flow for daily sales with receipts and barcode support
- Tracks inventory, suppliers, categories, and product costs
- Manages customers, debtors, and refunds/returns
- Records expenditures and generates financial reports
- Provides dashboards and analytics
- Supports multiple stores with store-scoped data
- Enforces role-based access (Super Admin, Admin, Cashier)

## Project Structure

```
app/
|-- Livewire/            # Livewire components (UI + actions)
|-- Models/              # Eloquent models
|-- Helpers/             # Helper utilities (e.g., StoreHelper)
|-- Traits/              # Shared traits (e.g., StoreScoped)
|-- Actions/             # Auth/Jetstream actions

resources/
|-- views/               # Blade templates
|-- css/                 # Styles
|-- js/                  # Frontend JS

routes/
|-- web.php              # Web routes and role middleware

database/
|-- migrations/          # Schema migrations
|-- seeders/             # Seed data
|-- factories/           # Model factories

public/
|-- assets/              # Public assets
|-- vendors/             # Frontend vendor libs
```

## Key Features

- Sales and receipt printing
- Inventory and stock control
- Customer and debtor management
- Supplier management
- Expenditure tracking
- Returns/refunds
- Reports and dashboards
- Store-scoped multi-tenant data
- Role-based access control

## Tech Stack

- Laravel 11.x
- Livewire 3.x
- Spatie Laravel Permission (roles/permissions)
- Jetstream + Sanctum (auth)
- Chart.js (dashboards)
- Maatwebsite Excel (export)
- Picqer Barcode Generator (barcodes)

## Requirements

- PHP 8.2+
- Composer
- Node.js + npm
- MySQL/MariaDB (or compatible)

## Installation

```bash
git clone <repository-url>
cd sweatlesspos-app

composer install
npm install

cp .env.example .env
php artisan key:generate

php artisan migrate
npm run build
```

## Development

```bash
php artisan serve
npm run dev
```

## Roles

- Super Admin: full system access and store management
- Admin: sales, inventory, reports
- Cashier: sales operations and basic reporting

## License

MIT
