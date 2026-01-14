@echo off
REM Fix Laravel Storage Permissions in Docker Container
REM Run this after setup to fix permission issues

echo.
echo ================================================
echo  Fixing Laravel Storage Permissions
echo ================================================
echo.

docker-compose exec laravel.test chmod -R 775 storage bootstrap/cache
docker-compose exec laravel.test chown -R www-data:www-data storage bootstrap/cache

if %errorlevel% equ 0 (
    echo.
    echo [SUCCESS] Permissions fixed!
    echo [INFO] Try accessing http://localhost:8080 now
    echo.
) else (
    echo.
    echo [ERROR] Failed to fix permissions.
    echo [INFO] You may need to manually fix permissions in the container:
    echo        docker-compose exec laravel.test bash
    echo        chown -R www-data:www-data storage bootstrap/cache
    echo.
)

echo Press any key to exit...
pause >nul