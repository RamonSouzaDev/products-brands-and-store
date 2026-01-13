#!/bin/bash

# Laravel Product Search - Simple Setup Script
# Author: Ramon Mendes (dwmom@hotmail.com)

echo "🚀 Laravel Product Search - Simple Setup"
echo "========================================"

# Check Docker
echo "[INFO] Checking Docker..."
docker --version >/dev/null 2>&1 || { echo "[ERROR] Docker not installed"; exit 1; }
docker-compose --version >/dev/null 2>&1 || docker compose version >/dev/null 2>&1 || { echo "[ERROR] Docker Compose not available"; exit 1; }
docker info >/dev/null 2>&1 || { echo "[ERROR] Docker daemon not running"; exit 1; }
echo "[SUCCESS] Docker is ready"

# Setup .env
echo "[INFO] Setting up environment..."
if [ ! -f ".env" ] && [ -f ".env.example" ]; then
    cp .env.example .env
    echo "[SUCCESS] Created .env file"
else
    echo "[SUCCESS] .env file exists"
fi

# Start containers FIRST
echo "[INFO] Starting Docker containers..."
if [ -f "vendor/bin/sail" ]; then
    ./vendor/bin/sail up -d
elif [ -f "docker-compose.yml" ]; then
    docker-compose up -d
else
    echo "[ERROR] No Docker config found"
    exit 1
fi
echo "[SUCCESS] Containers started"

# Install dependencies
echo "[INFO] Installing Composer dependencies..."
./vendor/bin/sail composer install --no-interaction
echo "[SUCCESS] Composer installed"

echo "[INFO] Installing NPM dependencies..."
./vendor/bin/sail npm install
echo "[SUCCESS] NPM installed"

# Setup application
echo "[INFO] Generating app key..."
./vendor/bin/sail artisan key:generate
echo "[SUCCESS] App key generated"

echo "[INFO] Setting up database..."
./vendor/bin/sail artisan migrate --seed
echo "[SUCCESS] Database ready"

echo "[INFO] Building assets..."
./vendor/bin/sail npm run build
echo "[SUCCESS] Assets built"

# Run tests?
echo ""
echo "Run tests? (y/n): "
read -n 1 answer
if [ "$answer" = "y" ] || [ "$answer" = "Y" ]; then
    echo ""
    echo "[INFO] Running tests..."
    ./vendor/bin/sail artisan test
fi

echo ""
echo "🎉 Setup complete!"
echo ""
echo "🌐 Application: http://localhost:8080"
echo "📧 Mailpit:     http://localhost:8025"
echo "🔍 Meilisearch: http://localhost:7700"
echo ""
echo "👨‍💻 Ramon Mendes (dwmom@hotmail.com)"