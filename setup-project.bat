@echo off
REM Laravel Product Search - Windows Setup Script
REM Author: Ramon Mendes (dwmom@hotmail.com)
REM Description: Automated setup script for Laravel product search project with Docker (Windows)

echo.
echo ===============================================================================
echo  Laravel Product Search - Automated Setup (Windows)
echo ===============================================================================
echo.

REM Check if Docker is installed
docker --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Docker is not installed. Please install Docker Desktop first.
    pause
    exit /b 1
)

REM Check if Docker Compose is available
docker-compose --version >nul 2>&1
if %errorlevel% neq 0 (
    docker compose version >nul 2>&1
    if %errorlevel% neq 0 (
        echo [ERROR] Docker Compose is not available. Please install Docker Compose.
        pause
        exit /b 1
    )
)

REM Check if Docker daemon is running
docker info >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Docker daemon is not running. Please start Docker Desktop.
    pause
    exit /b 1
)

echo [SUCCESS] Docker is installed and running
echo.

REM Setup environment file
echo [INFO] Setting up environment file...
if not exist ".env" (
    if exist ".env.example" (
        copy .env.example .env
        echo [SUCCESS] Created .env file from .env.example
    ) else (
        echo [WARNING] .env.example not found. Please ensure you have the correct .env file.
    )
) else (
    echo [SUCCESS] .env file already exists
)
echo.

REM Install Composer dependencies
echo [INFO] Installing Composer dependencies...
if exist "vendor\bin\sail" (
    call vendor\bin\sail composer install --no-interaction
) else (
    where composer >nul 2>&1
    if %errorlevel% equ 0 (
        composer install --no-interaction
    ) else (
        echo [ERROR] Composer is not installed and vendor\bin\sail not found.
        pause
        exit /b 1
    )
)
echo [SUCCESS] Composer dependencies installed
echo.

REM Install NPM dependencies
echo [INFO] Installing NPM dependencies...
if exist "vendor\bin\sail" (
    call vendor\bin\sail npm install
) else (
    where npm >nul 2>&1
    if %errorlevel% equ 0 (
        npm install
    ) else (
        echo [ERROR] NPM is not installed and vendor\bin\sail not found.
        pause
        exit /b 1
    )
)
echo [SUCCESS] NPM dependencies installed
echo.

REM Generate application key
echo [INFO] Generating application key...
if exist "vendor\bin\sail" (
    call vendor\bin\sail artisan key:generate
) else (
    echo [ERROR] Laravel Sail not found. Please run composer install first.
    pause
    exit /b 1
)
echo [SUCCESS] Application key generated
echo.

REM Setup database
echo [INFO] Setting up database (migrations and seeders)...
if exist "vendor\bin\sail" (
    call vendor\bin\sail artisan migrate --seed
) else (
    echo [ERROR] Laravel Sail not found.
    pause
    exit /b 1
)
echo [SUCCESS] Database setup completed (15 brands, 15 categories, 100 products created)
echo.

REM Build frontend assets
echo [INFO] Building frontend assets...
if exist "vendor\bin\sail" (
    call vendor\bin\sail npm run build
) else (
    where npm >nul 2>&1
    if %errorlevel% equ 0 (
        npm run build
    ) else (
        echo [ERROR] NPM not available for building assets.
        pause
        exit /b 1
    )
)
echo [SUCCESS] Frontend assets built successfully
echo.

REM Start Docker containers
echo [INFO] Starting Docker containers...
if exist "vendor\bin\sail" (
    call vendor\bin\sail up -d
) else (
    if exist "docker-compose.yml" (
        docker-compose up -d
    ) else if exist "compose.yml" (
        docker-compose up -d
    ) else (
        echo [ERROR] No Docker configuration found (sail or docker-compose.yml).
        pause
        exit /b 1
    )
)
echo [SUCCESS] Docker containers started successfully
echo.

REM Ask if user wants to run tests
echo.
set /p run_tests="Do you want to run tests now? (y/n): "
if /i "%run_tests%"=="y" (
    echo [INFO] Running tests...
    if exist "vendor\bin\sail" (
        call vendor\bin\sail artisan test
    ) else (
        echo [ERROR] Laravel Sail not found for running tests.
    )
)
echo.

REM Show access information
echo.
echo ===============================================================================
echo                        🎉 SETUP COMPLETED SUCCESSFULLY! 🎉
echo ===============================================================================
echo.
echo 📋 Access Information:
echo    🌐 Application:    http://localhost:8080
echo    📧 Mailpit:        http://localhost:8025
echo    🔍 Meilisearch:    http://localhost:7700
echo.
echo 🛠️  Useful Commands:
echo    Stop containers:   vendor\bin\sail down
echo    View logs:         vendor\bin\sail logs
echo    Run tests:         vendor\bin\sail artisan test
echo    Access container:  vendor\bin\sail shell
echo.
echo 👨‍💻 Developed by: Ramon Mendes (dwmom@hotmail.com)
echo 📂 Repository: https://github.com/RamonSouzaDev/products-brands-and-store
echo.
echo Press any key to exit...
pause >nul
