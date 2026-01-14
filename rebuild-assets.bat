@echo off
REM Quick rebuild of frontend assets for Laravel project

echo.
echo ================================================
echo  Rebuilding Frontend Assets
echo ================================================
echo.

docker-compose exec laravel.test npm run build

if %errorlevel% equ 0 (
    echo.
    echo [SUCCESS] Assets rebuilt successfully!
    echo [INFO] Refresh your browser to see changes
    echo.
) else (
    echo.
    echo [ERROR] Failed to rebuild assets.
    echo [INFO] Make sure containers are running: docker-compose ps
    echo.
)

echo Press any key to exit...
pause >nul