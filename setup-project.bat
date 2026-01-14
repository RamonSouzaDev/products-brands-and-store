@echo off
setlocal enabledelayedexpansion
REM Laravel Product Search - Windows Setup Script
REM Author: Ramon Mendes (dwmom@hotmail.com)

echo.
echo ===============================================================================
echo  Laravel Product Search - Automated Setup (Windows)
echo ===============================================================================
echo.

REM Check Docker
docker --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Docker is not installed. Please install Docker Desktop first.
    timeout /t 5
    exit /b 1
)

docker info >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Docker daemon is not running. Please start Docker Desktop.
    timeout /t 5
    exit /b 1
)
echo [SUCCESS] Docker is installed and running
echo.

REM Setup .env
echo [INFO] Setting up environment file...
if not exist ".env" (
    copy .env.example .env >nul
    echo [SUCCESS] Created .env from .env.example
)

REM Add missing env vars
(findstr /C:"WWWUSER=" .env >nul 2>&1) || echo WWWUSER=1000>> .env
(findstr /C:"WWWGROUP=" .env >nul 2>&1) || echo WWWGROUP=1000>> .env
(findstr /C:"APP_PORT=" .env >nul 2>&1) || echo APP_PORT=8080>> .env

echo [SUCCESS] Environment configured
echo.

REM Install Composer
echo [INFO] Installing Composer dependencies...
where composer >nul 2>&1
if %errorlevel% equ 0 (
    composer install --no-interaction 2>&1
) else (
    docker run --rm -v "!cd!:/app" -w /app composer:latest composer install --no-interaction 2>&1
    if !errorlevel! neq 0 (
        echo [ERROR] Composer installation failed.
        timeout /t 5
        exit /b 1
    )
)

if not exist "vendor" (
    echo [ERROR] vendor directory not created.
    timeout /t 5
    exit /b 1
)
echo [SUCCESS] Composer dependencies installed
echo.

REM Start containers
echo [INFO] Starting Docker containers...
docker-compose up -d 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Failed to start containers.
    timeout /t 5
    exit /b 1
)
echo [SUCCESS] Docker containers started
echo.

REM Wait
echo [INFO] Waiting for containers to be ready...
timeout /t 15 /nobreak
echo.

REM NPM install
echo [INFO] Installing NPM dependencies...
docker-compose run --rm laravel.test npm install 2>&1
echo [SUCCESS] NPM dependencies installed
echo.

REM App key
echo [INFO] Generating application key...
docker-compose exec -T laravel.test php artisan key:generate 2>&1
echo [SUCCESS] Application key configured
echo.

REM Database
echo [INFO] Running migrations and seeders...
docker-compose exec -T laravel.test php artisan migrate:fresh --seed 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Database setup failed.
    timeout /t 5
    exit /b 1
)
echo [SUCCESS] Database ready ^(15 brands, 15 categories, 100 products^)
echo.

REM Build assets
echo [INFO] Building frontend assets...
docker-compose exec -T laravel.test npm run build 2>&1
echo [SUCCESS] Frontend assets built
echo.

REM Success message
echo.
echo ===============================================================================
echo                    SETUP COMPLETED SUCCESSFULLY!
echo ===============================================================================
echo.
echo Access your application:
echo    Application:  http://localhost:8080
echo    Mailpit:      http://localhost:8025
echo    Meilisearch:  http://localhost:7700
echo.
echo Useful commands:
echo    Stop:         docker-compose down
echo    Logs:         docker-compose logs -f laravel.test
echo    Tests:        docker-compose exec laravel.test php artisan test
echo    Shell:        docker-compose exec laravel.test bash
echo.
echo By: Ramon Mendes - dwmom@hotmail.com
echo.
pause
