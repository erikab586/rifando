# setup.ps1 - Script de configuración rápida para Windows (PowerShell)
# Ejecución: powershell -ExecutionPolicy Bypass -File setup.ps1

Write-Host "🚀 Iniciando configuración del Sistema de Rifas..." -ForegroundColor Green
Write-Host ""

# Función para imprimir mensajes
function Write-Info {
    param([string]$Message)
    Write-Host "✓ $Message" -ForegroundColor Green
}

function Write-Warn {
    param([string]$Message)
    Write-Host "⚠ $Message" -ForegroundColor Yellow
}

function Write-Error {
    param([string]$Message)
    Write-Host "✗ $Message" -ForegroundColor Red
}

# Verificar PHP
Write-Host "Verificando requisitos..."
try {
    $phpVersion = php --version 2>$null | Select-Object -First 1
    Write-Info "PHP instalado: $phpVersion"
} catch {
    Write-Error "PHP no está instalado o no está en el PATH"
    exit 1
}

# Verificar Composer
try {
    $composerVersion = composer --version 2>$null
    Write-Info "Composer encontrado"
} catch {
    Write-Error "Composer no está instalado"
    exit 1
}

# Crear .env si no existe
if (-Not (Test-Path ".env")) {
    Write-Warn ".env no existe, creando desde .env.example..."
    Copy-Item ".env.example" ".env"
    Write-Info ".env creado"
} else {
    Write-Info ".env ya existe"
}

# Instalar dependencias PHP
Write-Host ""
Write-Host "Instalando dependencias PHP..."
& composer install
Write-Info "Dependencias PHP instaladas"

# Generar APP_KEY
Write-Host ""
Write-Host "Generando APP_KEY..."
php artisan key:generate
Write-Info "APP_KEY generada"

# Crear directorios de almacenamiento
Write-Host ""
Write-Host "Configurando directorios..."
New-Item -ItemType Directory -Force -Path "storage/app/public/rifas" | Out-Null
Write-Info "Directorios configurados"

# Link de almacenamiento
if (-Not (Test-Path "public/storage")) {
    Write-Host "Creando enlace simbólico de storage..."
    php artisan storage:link
    Write-Info "Enlace simbólico creado"
} else {
    Write-Info "Enlace simbólico ya existe"
}

# Base de datos
Write-Host ""
Write-Host "Configurando base de datos..."
php artisan migrate --force 2>$null
if ($LASTEXITCODE -ne 0) {
    Write-Warn "No se pudo ejecutar migraciones automáticamente"
    Write-Warn "Ejecuta manualmente: php artisan migrate"
}

# Crear usuario admin (opcional)
Write-Host ""
$response = Read-Host "¿Deseas crear un usuario admin? (s/n)"
if ($response -eq "s" -or $response -eq "S") {
    $tinkerScript = @"
`$user = App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
]);

`$role = App\Models\Role::where('slug', 'admin')->first();
if (`$role) {
    `$user->roles()->attach(`$role);
}

echo "\n✓ Usuario admin creado:\n";
echo "Email: " . `$user->email . "\n";
echo "Password: password\n";
exit;
"@
    $tinkerScript | php artisan tinker
}

# Limpiar caché
Write-Host ""
Write-Host "Limpiando caché..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear
Write-Info "Caché limpiado"

# Éxito
Write-Host ""
Write-Host "╔════════════════════════════════════════╗" -ForegroundColor Green
Write-Host "║ ✓ Configuración completada exitosamente ║" -ForegroundColor Green
Write-Host "╚════════════════════════════════════════╝" -ForegroundColor Green
Write-Host ""

Write-Host "Próximos pasos:" -ForegroundColor Cyan
Write-Host ""
Write-Host "1. Inicia el servidor:" -ForegroundColor White
Write-Host "   php artisan serve" -ForegroundColor Yellow
Write-Host ""
Write-Host "2. Abre en el navegador:" -ForegroundColor White
Write-Host "   http://localhost:8000" -ForegroundColor Yellow
Write-Host ""
Write-Host "3. Accede al admin:" -ForegroundColor White
Write-Host "   http://localhost:8000/admin" -ForegroundColor Yellow
Write-Host "   Email: admin@example.com" -ForegroundColor White
Write-Host "   Password: password" -ForegroundColor White
Write-Host ""
Write-Host "📚 Para más información, consulta README_MASTER.md" -ForegroundColor Cyan
Write-Host ""

# Ofrecer iniciar el servidor
$startServer = Read-Host "¿Deseas iniciar el servidor ahora? (s/n)"
if ($startServer -eq "s" -or $startServer -eq "S") {
    Write-Host ""
    Write-Host "Iniciando servidor en http://localhost:8000" -ForegroundColor Green
    Write-Host "(Presiona Ctrl+C para detener)" -ForegroundColor Gray
    Write-Host ""
    php artisan serve
}
