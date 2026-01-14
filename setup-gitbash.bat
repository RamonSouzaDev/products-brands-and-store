@echo off
REM Laravel Product Search - Git Bash Setup Script (Windows)
REM Author: Ramon Mendes (dwmom@hotmail.com)
REM Uses docker-compose directly instead of Sail for Git Bash compatibility

echo.
echo ===============================================================================
echo  Laravel Product Search - Git Bash Setup (Windows)
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
        echo [ERROR] Docker Compose is not available.
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

REM Start Docker containers using docker-compose
echo [INFO] Starting Docker containers...
if exist "compose.yaml" (
    docker-compose -f compose.yaml up -d
) else if exist "docker-compose.yml" (
    docker-compose up -d
) else (
    echo [ERROR] No Docker compose file found.
    pause
    exit /b 1
)
echo [SUCCESS] Docker containers started successfully
echo.

REM Wait a bit for containers to be fully ready
echo [INFO] Waiting for containers to be ready...
sleep 5

REM Check if laravel.test container is running
docker-compose ps laravel.test | findstr "Up" >nul
if %errorlevel% neq 0 (
    echo [ERROR] Laravel app container is not running. Checking container status...
    docker-compose ps
    echo [INFO] Waiting additional time for container to start...
    sleep 10
    docker-compose ps laravel.test | findstr "Up" >nul
    if %errorlevel% neq 0 (
        echo [ERROR] Laravel app container failed to start. Please check Docker logs.
        pause
        exit /b 1
    )
)
echo [SUCCESS] Containers ready
echo.

REM Install Composer dependencies
echo [INFO] Installing Composer dependencies...
docker-compose exec -T laravel.test composer install --no-interaction
if %errorlevel% neq 0 (
    echo [WARNING] Composer install failed, but continuing...
)
echo [SUCCESS] Composer dependencies processed
echo.

REM Install NPM dependencies
echo [INFO] Installing NPM dependencies...
docker-compose exec -T laravel.test npm install
if %errorlevel% neq 0 (
    echo [WARNING] NPM install failed, but continuing...
)
echo [SUCCESS] NPM dependencies processed
echo.

REM Generate application key
echo [INFO] Generating application key...
docker-compose exec -T laravel.test php artisan key:generate
if %errorlevel% neq 0 (
    echo [WARNING] Key generation failed, but continuing...
)
echo [SUCCESS] Application key processed
echo.

REM Setup database
echo [INFO] Setting up database (migrations and seeders)...
docker-compose exec -T laravel.test php artisan migrate --seed
if %errorlevel% neq 0 (
    echo [WARNING] Database setup failed, but continuing...
)
echo [SUCCESS] Database setup processed
echo.

REM Build frontend assets
echo [INFO] Building frontend assets...
docker-compose exec -T laravel.test npm run build
if %errorlevel% neq 0 (
    echo [WARNING] Asset build failed, but continuing...
)
echo [SUCCESS] Frontend assets processed
echo.

REM Ask if user wants to run tests
echo.
set /p run_tests="Do you want to run tests now? (y/n): "
if /i "%run_tests%"=="y" (
    echo [INFO] Running tests...
    docker-compose exec -T laravel.test php artisan test
    if %errorlevel% neq 0 (
        echo [WARNING] Tests failed, but setup is complete.
    )
)
echo.

REM Show access information
echo.
echo ===============================================================================
echo                        🎉 SETUP COMPLETED! 🎉
echo ===============================================================================
echo.
echo 📋 Access Information:
echo    🌐 Application:    http://localhost:8080
echo    📧 Mailpit:        http://localhost:8025
echo    🔍 Meilisearch:    http://localhost:7700
echo.
echo 🛠️  Useful Commands:
echo    Stop containers:   docker-compose down
echo    View logs:         docker-compose logs
echo    Run tests:         docker-compose exec laravel.test php artisan test
echo    Access container:  docker-compose exec laravel.test bash
echo.
echo 👨‍💻 Developed by: Ramon Mendes (dwmom@hotmail.com)
echo 📂 Repository: https://github.com/RamonSouzaDev/products-brands-and-store
echo.
echo Press any key to exit...
pause >nul