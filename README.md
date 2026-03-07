# SWEATLESS POS

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## About SWEATLESS POS

A modern, comprehensive Laravel + Livewire point-of-sale system designed to automate sales and managerial processes for supermarkets, retail stores, and multi-outlet businesses. Supports both wholesale and retail operations with enterprise-grade features.

## Technology Stack

- **Framework**: Laravel 11.x - The PHP web application framework with expressive, elegant syntax
- **UI Components**: Livewire 3.x - A full-stack framework for building dynamic interfaces without leaving PHP
- **Authentication**: Laravel Jetstream with Sanctum API authentication
- **Database ORM**: Eloquent with full relationship support
- **Authorization**: Spatie Laravel Permissions for role-based access control
- **CSS Framework**: Tailwind CSS 3.x
- **Build Tool**: Vite with Laravel Vite Plugin
- **Excel Export**: Maatwebsite Excel for spreadsheet generation
- **Barcode Generation**: Picqer PHP Barcode Generator
- **Testing**: PHPUnit 11.x

## Key Features

- **Sales Management**: Create, edit, and manage sales transactions with real-time cart operations
- **Inventory Management**: Track product stock, categories, suppliers, and expiry dates
- **Customer Relations**: Manage customer profiles, purchase history, and detailed reports
- **Financial Reports**: Generate comprehensive sales, revenue, and expense reports
- **User Management**: Role-based access control (Super Admin, Admin, Cashier)
- **Expenditure Tracking**: Record and track business expenses
- **Return Management**: Process product returns and refunds
- **System Logs**: Comprehensive audit trail of all operations
- **Excel Export**: Export data to CSV/Excel formats
- **Barcode Support**: Generate and scan product barcodes

## Requirements

- PHP 8.2+
- Composer
- Node.js & npm
- MySQL/MariaDB or compatible database
- Modern web browser

## Getting Started

### Installation

```bash
# Clone the repository
git clone <repository-url>
cd sweatlesspos-app

# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Set up environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# Build frontend assets
npm run build
```

### Development

```bash
# Run development server
php artisan serve

# Run Vite dev server (in another terminal)
npm run dev

# Run tests
php artisan test
```

## Project Structure

```
app/
├── Models/              # Eloquent models
├── Livewire/           # Livewire components
├── Http/
│   └── Controllers/    # HTTP controllers
└── Exports/            # Excel export classes

routes/
├── web.php             # Web routes with role-based middleware
├── api.php             # API routes
└── console.php         # Console commands

resources/
├── views/              # Blade view templates
├── css/                # Tailwind CSS
└── js/                 # JavaScript assets

database/
├── migrations/         # Database migrations
├── seeders/           # Database seeders
└── factories/         # Model factories
```

## Role-Based Access Control

- **Super Admin**: Full system access, user management, system settings
- **Admin**: Sales, inventory, and financial management
- **Cashier**: Sales operations and basic reporting

## Contributing

Contributions are welcome! Please ensure your changes follow Laravel coding standards and include appropriate tests.

## Security Vulnerabilities

If you discover a security vulnerability, please email security@sweatlesspos.local instead of using the issue tracker.

## License

The SWEATLESS POS application is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
