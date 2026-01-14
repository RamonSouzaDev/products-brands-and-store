# Laravel Product Search - Senior Level Implementation

<div align="center">

<h1>Hello 👋, I'm Ramon Mendes - Software Developer </h1>

<h3>A back-end developer passionate about technology</h3>

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4-blue.svg)](https://php.net)
[![Livewire](https://img.shields.io/badge/Livewire-3.x-pink.svg)](https://livewire.laravel.com)

- 🔭 I am currently working on [Back-end project development](https://github.com/RamonSouzaDev/To-Do-List-)
- 🌱 I'm currently learning **Software Architecture and Engineering**
- 📫 How to reach me **dwmom@hotmail.com**

</div>

## 📋 Project Overview

This is a Laravel application that implements a product search mechanism with combined filters using Livewire. Built with senior-level architecture patterns including Repository Pattern, Service Layer, and DTOs for clean, maintainable code.

## ✨ Features

- 🔍 **Advanced Search** - Real-time product search by name
- 🏷️ **Multi-Filter Support** - Filter by categories and brands (multiple selection)
- 🔗 **URL Persistence** - Search parameters persist on page refresh
- 🎨 **Orange & Grey Theme** - Custom color scheme with orange accents and grey elements
- 🏗️ **Senior Architecture** - Repository Pattern, Service Layer, DTOs
- ✅ **Comprehensive Tests** - Feature and unit tests included
- 🔄 **CI/CD Pipeline** - GitHub Actions with automated testing and quality checks
- 🐳 **Docker Ready** - Laravel Sail for consistent development environment

## 🏛️ Architecture Highlights

This project demonstrates senior-level development practices:

- **DTO Pattern** (`ProductFilterDTO`) - Type-safe filter parameter management
- **Repository Pattern** - Clean data access abstraction
- **Service Layer** (`ProductService`) - Business logic separation
- **Query Scopes** - Reusable, chainable query logic
- **Eager Loading** - Optimized database queries (N+1 prevention)
- **Livewire 3** - Modern reactive components with URL persistence

## 🚀 Quick Start

### Prerequisites

- [Docker Desktop](https://www.docker.com/products/docker-desktop) installed
- Git

### ⚡ Automated Setup (Recommended)

For the fastest setup experience, use our automated scripts:

**Linux/Mac:**
```bash
git clone https://github.com/RamonSouzaDev/products-brands-and-store.git
cd products-brands-and-store
./setup-project.sh
```

**Windows (Git Bash/MINGW64):**
```bash
git clone https://github.com/RamonSouzaDev/products-brands-and-store.git
cd products-brands-and-store
./setup-gitbash.bat
```

**Windows (PowerShell/CMD):**
```cmd
git clone https://github.com/RamonSouzaDev/products-brands-and-store.git
cd products-brands-and-store
setup-project.bat
```

**Simple Version (Linux/Mac/WSL2 only):**
```bash
git clone https://github.com/RamonSouzaDev/products-brands-and-store.git
cd products-brands-and-store
./setup-simple.sh
```

The script will automatically:
- ✅ Check Docker installation
- ✅ Setup environment file
- ✅ Start Docker containers
- ✅ Install Composer and NPM dependencies
- ✅ Generate application key
- ✅ Run migrations and seed database (15 brands, 15 categories, 100 products)
- ✅ Build frontend assets
- ✅ Optionally run tests

### Manual Installation

If you prefer manual setup, follow these steps:

1. **Clone the repository:**
   ```bash
   git clone https://github.com/RamonSouzaDev/products-brands-and-store.git
   cd products-brands-and-store
   ```

2. **Copy environment file:**
   ```bash
   cp .env.example .env
   ```

3. **Start Docker containers:**
   ```bash
   ./vendor/bin/sail up -d
   ```

   > **Note**: On Windows, use `vendor\bin\sail` or create an alias: `alias sail='./vendor/bin/sail'`

4. **Install dependencies:**
   ```bash
   ./vendor/bin/sail composer install
   ./vendor/bin/sail npm install
   ```

5. **Generate application key:**
   ```bash
   ./vendor/bin/sail artisan key:generate
   ```

6. **Run migrations and seed database:**
   ```bash
   ./vendor/bin/sail artisan migrate --seed
   ```
   This creates 15 brands, 15 categories, and 100 sample products.

7. **Build frontend assets:**
   ```bash
   ./vendor/bin/sail npm run build
   ```

### Accessing the Application

- **Application**: [http://localhost:8080](http://localhost:8080)
- **Mailpit** (Email testing): [http://localhost:8025](http://localhost:8025)
- **Meilisearch** (Search engine): [http://localhost:7700](http://localhost:7700)

### Setup Script Options

The automated setup scripts support additional options:

**Linux/Mac (setup-project.sh):**
```bash
# Show help
./setup-project.sh --help

# Run only tests (skip full setup)
./setup-project.sh --test-only

# Start only containers (skip installation)
./setup-project.sh --start-only
```

### Platform-Specific Notes

**🐧 WSL2 Users:**
- Laravel Sail requires WSL2, not WSL1
- If you can't access Windows files from WSL, run: `./setup-wsl.sh` for guidance
- Alternative: Use Git Bash script from Windows side

**🪟 Git Bash/MINGW64 Users:**
- Laravel Sail doesn't work in Git Bash - use `setup-gitbash.bat` instead
- This script uses docker-compose directly for compatibility

**🐳 Docker Requirements:**
- Docker Desktop must be running
- Ensure adequate RAM allocation (4GB recommended)
- Ports 8080, 8025, 7700 should be available

### Docker Build (Alternative to Sail)

For production deployments or CI/CD, use the included Dockerfile. The CI/CD pipeline automatically builds and pushes images to GitHub Container Registry.

#### Pull from GitHub Container Registry:
```bash
# Login to GitHub Container Registry (replace YOUR_USERNAME with your GitHub username)
echo $GITHUB_TOKEN | docker login ghcr.io -u YOUR_USERNAME --password-stdin

# Pull the latest image (replace your-username with your lowercase GitHub username)
docker pull ghcr.io/your-username/products-brands-and-store:latest

# Run the application
docker run -p 8080:80 ghcr.io/your-username/products-brands-and-store:latest
```

**Note**: Replace `your-username` with your actual GitHub username in lowercase.

#### Build Locally:
```bash
# Build the image
docker build -t laravel-products .

# Run with SQLite (default)
docker run -p 8080:80 laravel-products

# Or with MySQL
docker run -e DB_CONNECTION=mysql -e DB_HOST=host.docker.internal -p 8080:80 laravel-products
```

#### Available Tags:
- `latest` - Latest build from main branch
- `main` - Build from main branch
- `main-<commit-sha>` - Specific commit builds

### Troubleshooting Permission Issues

If you encounter permission errors after setup:

**Git Bash/Windows:**
```bash
./fix-permissions.bat
```

**Manual Fix:**
```bash
docker-compose exec laravel.test chown -R www-data:www-data storage bootstrap/cache
docker-compose exec laravel.test chmod -R 775 storage bootstrap/cache
```

**Rebuild Assets After Changes:**
```bash
./rebuild-assets.bat
```

## 🔄 CI/CD Pipeline

This project includes a comprehensive CI/CD pipeline with GitHub Actions that runs on every push and pull request:

### Automated Checks:
- ✅ **Unit & Feature Tests** - PHPUnit with 80%+ coverage requirement
- ✅ **Code Quality** - PHPStan static analysis (level 5)
- ✅ **Code Style** - Laravel Pint code formatting
- ✅ **Security Audit** - Composer security checks
- ✅ **Frontend Build** - Asset compilation testing
- ✅ **Docker Build** - Container image building

### Local Quality Checks:

```bash
# Run all tests with coverage
composer test

# Static analysis
composer analyse

# Code formatting
composer format

# Security audit
composer audit
```

## 🧪 Running Tests

Execute the automated test suite:

```bash
./vendor/bin/sail artisan test
```

Run specific test file:

```bash
./vendor/bin/sail artisan test --filter ProductFilteringTest
```

## 📁 Project Structure

```
app/
├── DTOs/
│   └── ProductFilterDTO.php          # Filter parameter encapsulation
├── Livewire/
│   └── ProductList.php                # Main product listing component
├── Models/
│   ├── Brand.php                      # Brand model with relationships
│   ├── Category.php                   # Category model with relationships
│   └── Product.php                    # Product model with query scopes
├── Repositories/
│   ├── Contracts/
│   │   └── ProductRepositoryInterface.php
│   └── Eloquent/
│       └── ProductRepository.php      # Eloquent implementation
└── Services/
    └── ProductService.php             # Business logic layer

database/
├── factories/                         # Model factories for testing
├── migrations/                        # Database schema
└── seeders/                          # Initial data seeders

tests/
└── Feature/
    └── ProductFilteringTest.php      # Comprehensive feature tests
```

## 🎯 Key Features Explained

### Combined Filters
- Search by product name (AND)
- Filter by one or more categories (OR within categories)
- Filter by one or more brands (OR within brands)
- All filters work together (AND between filter types)

### URL Persistence
Filter parameters are stored in the URL using Livewire's `#[Url]` attribute:
- `?q=search-term` - Search query
- `?cat[]=1&cat[]=2` - Selected categories
- `?brand[]=1&brand[]=2` - Selected brands

Refresh the page and your filters remain active!

### Clear Filters
Click "Clear All" to reset all filters and return to the full product list.

## 🛠️ Development

### Stopping the Environment

```bash
./vendor/bin/sail down
```

### Rebuilding Containers

```bash
./vendor/bin/sail build --no-cache
./vendor/bin/sail up -d
```

### Running Development Server (with hot reload)

```bash
./vendor/bin/sail npm run dev
```

### Troubleshooting

If you encounter permission issues with Docker on Windows:

1. **Storage Permissions**: Ensure proper permissions on `storage/` and `bootstrap/cache/` directories
2. **View Cache**: Clear compiled views cache: `php artisan view:clear`
3. **Livewire Cache**: Clear Livewire cache: `php artisan livewire:clear`
4. **Full Cache Clear**: `php artisan cache:clear && php artisan config:clear && php artisan view:clear`

## 📝 Code Quality

This project follows Laravel and PHP best practices:

- ✅ **PSR-12** coding standards
- ✅ **Type declarations** on all methods
- ✅ **Strict typing** where applicable
- ✅ **Meaningful variable names**
- ✅ **Single Responsibility Principle**
- ✅ **Dependency Injection**
- ✅ **Interface-based programming**

## 🤝 Contributing

This is a demonstration project showcasing senior-level Laravel development practices. Feel free to explore the codebase and learn from the architecture patterns implemented.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

<div align="center">
  <p>Built with ❤️ by <strong>Ramon Mendes</strong> using Laravel 12, Livewire 3, and PHP 8.4</p>
  <p>
    <a href="https://github.com/RamonSouzaDev">GitHub</a> •
    <a href="mailto:dwmom@hotmail.com">Email</a> •
    <a href="https://github.com/RamonSouzaDev/products-brands-and-store">View on GitHub</a>
  </p>
</div>
