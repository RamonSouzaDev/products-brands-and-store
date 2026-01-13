#!/bin/bash

# Laravel Product Search - Setup Script
# Author: Ramon Mendes (dwmom@hotmail.com)
# Description: Automated setup script for Laravel product search project with Docker

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Function to check if command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Function to check Docker
check_docker() {
    print_status "Checking Docker installation..."

    if ! command_exists docker; then
        print_error "Docker is not installed. Please install Docker Desktop first."
        exit 1
    fi

    if ! command_exists docker-compose; then
        print_error "Docker Compose is not installed. Please install Docker Compose first."
        exit 1
    fi

    # Check if Docker daemon is running
    if ! docker info >/dev/null 2>&1; then
        print_error "Docker daemon is not running. Please start Docker Desktop."
        exit 1
    fi

    print_success "Docker is installed and running"
}

# Function to check if .env exists, create from .env.example if not
setup_env() {
    print_status "Setting up environment file..."

    if [ ! -f ".env" ]; then
        if [ -f ".env.example" ]; then
            cp .env.example .env
            print_success "Created .env file from .env.example"
        else
            print_warning ".env.example not found. Please ensure you have the correct .env file."
        fi
    else
        print_success ".env file already exists"
    fi
}

# Function to install Composer dependencies
install_composer_deps() {
    print_status "Installing Composer dependencies..."

    if [ -f "vendor/bin/sail" ]; then
        ./vendor/bin/sail composer install --no-interaction
    else
        # Fallback to direct composer if sail is not available
        if command_exists composer; then
            composer install --no-interaction
        else
            print_error "Composer is not installed and vendor/bin/sail not found."
            exit 1
        fi
    fi

    print_success "Composer dependencies installed"
}

# Function to install NPM dependencies
install_npm_deps() {
    print_status "Installing NPM dependencies..."

    if [ -f "vendor/bin/sail" ]; then
        ./vendor/bin/sail npm install
    else
        # Fallback to direct npm if sail is not available
        if command_exists npm; then
            npm install
        else
            print_error "NPM is not installed and vendor/bin/sail not found."
            exit 1
        fi
    fi

    print_success "NPM dependencies installed"
}

# Function to generate application key
generate_app_key() {
    print_status "Generating application key..."

    if [ -f "vendor/bin/sail" ]; then
        ./vendor/bin/sail artisan key:generate
    else
        print_error "Laravel Sail not found. Please run composer install first."
        exit 1
    fi

    print_success "Application key generated"
}

# Function to run database migrations and seeders
setup_database() {
    print_status "Setting up database (migrations and seeders)..."

    if [ -f "vendor/bin/sail" ]; then
        ./vendor/bin/sail artisan migrate --seed
    else
        print_error "Laravel Sail not found."
        exit 1
    fi

    print_success "Database setup completed (15 brands, 15 categories, 100 products created)"
}

# Function to build frontend assets
build_assets() {
    print_status "Building frontend assets..."

    if [ -f "vendor/bin/sail" ]; then
        ./vendor/bin/sail npm run build
    else
        # Fallback to direct npm
        if command_exists npm; then
            npm run build
        else
            print_error "NPM not available for building assets."
            exit 1
        fi
    fi

    print_success "Frontend assets built successfully"
}

# Function to start Docker containers
start_containers() {
    print_status "Starting Docker containers..."

    if [ -f "vendor/bin/sail" ]; then
        ./vendor/bin/sail up -d
    elif [ -f "docker-compose.yml" ] || [ -f "compose.yml" ]; then
        docker-compose up -d
    else
        print_error "No Docker configuration found (sail or docker-compose.yml)."
        exit 1
    fi

    print_success "Docker containers started successfully"
}

# Function to run tests
run_tests() {
    print_status "Running tests..."

    if [ -f "vendor/bin/sail" ]; then
        ./vendor/bin/sail artisan test
    else
        print_error "Laravel Sail not found for running tests."
        return 1
    fi
}

# Function to show access information
show_access_info() {
    echo ""
    print_success "🎉 Project setup completed successfully!"
    echo ""
    echo "📋 Access Information:"
    echo "   🌐 Application:    http://localhost:8080"
    echo "   📧 Mailpit:        http://localhost:8025"
    echo "   🔍 Meilisearch:    http://localhost:7700"
    echo ""
    echo "🛠️  Useful Commands:"
    echo "   Stop containers:   ./vendor/bin/sail down"
    echo "   View logs:         ./vendor/bin/sail logs"
    echo "   Run tests:         ./vendor/bin/sail artisan test"
    echo "   Access container:  ./vendor/bin/sail shell"
    echo ""
    echo "👨‍💻 Developed by: Ramon Mendes (dwmom@hotmail.com)"
    echo "📂 Repository: https://github.com/RamonSouzaDev/products-brands-and-store"
}

# Main execution function
main() {
    echo ""
    echo "🚀 Laravel Product Search - Automated Setup"
    echo "=========================================="
    echo ""

    # Pre-flight checks
    check_docker
    setup_env

    # Installation steps
    install_composer_deps
    install_npm_deps
    generate_app_key
    setup_database
    build_assets

    # Start the application
    start_containers

    # Optional: Run tests
    echo ""
    read -p "Do you want to run tests now? (y/n): " -n 1 -r
    echo ""
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        run_tests
    fi

    # Show final information
    show_access_info
}

# Handle command line arguments
case "${1:-}" in
    "--help"|"-h")
        echo "Laravel Product Search - Setup Script"
        echo ""
        echo "Usage: $0 [options]"
        echo ""
        echo "Options:"
        echo "  --help, -h          Show this help message"
        echo "  --test-only         Only run tests (skip full setup)"
        echo "  --start-only        Only start containers (skip installation)"
        echo ""
        echo "Examples:"
        echo "  $0                  Full setup (default)"
        echo "  $0 --test-only      Run only tests"
        echo "  $0 --start-only     Start only containers"
        exit 0
        ;;
    "--test-only")
        check_docker
        run_tests
        exit $?
        ;;
    "--start-only")
        check_docker
        start_containers
        show_access_info
        exit $?
        ;;
    *)
        main
        ;;
esac
