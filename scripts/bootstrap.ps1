<#
Bootstrap script for Windows PowerShell to help collaborators set up the project quickly.
Run from the repo root: `.	ools\bootstrap.ps1` or `powershell -ExecutionPolicy Bypass -File .\scripts\bootstrap.ps1`
#>

Write-Host "Starting bootstrap..." -ForegroundColor Cyan

if (-not (Test-Path -Path ".env")) {
    Write-Host "Copying .env.example to .env" -ForegroundColor Yellow
    Copy-Item -Path .env.example -Destination .env -Force
} else {
    Write-Host ".env already exists, skipping copy" -ForegroundColor Green
}

Write-Host "Running composer install..." -ForegroundColor Cyan
composer install

Write-Host "Generating APP_KEY..." -ForegroundColor Cyan
php artisan key:generate

Write-Host "Running migrations..." -ForegroundColor Cyan
php artisan migrate --force

Write-Host "Running seeders (EmpleadoSeeder)..." -ForegroundColor Cyan
php artisan db:seed --class=EmpleadoSeeder --force

Write-Host "Running npm install..." -ForegroundColor Cyan
npm install

Write-Host "Building assets (npm run build)..." -ForegroundColor Cyan
npm run build

Write-Host "Creating storage symlink..." -ForegroundColor Cyan
php artisan storage:link

Write-Host "Bootstrap complete. You can run 'php artisan serve' or configure Apache to point to the 'public' folder." -ForegroundColor Green
