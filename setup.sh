#!/bin/bash
# setup.sh - Script de configuración rápida para desarrollo local

set -e

echo "🚀 Iniciando configuración del Sistema de Rifas..."
echo ""

# Colores para output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Función para imprimir mensajes
info() {
    echo -e "${GREEN}✓${NC} $1"
}

warn() {
    echo -e "${YELLOW}⚠${NC} $1"
}

error() {
    echo -e "${RED}✗${NC} $1"
}

# Verificar PHP
echo "Verificando requisitos..."
if ! command -v php &> /dev/null; then
    error "PHP no está instalado"
    exit 1
fi
info "PHP instalado: $(php --version | head -n1)"

# Verificar Composer
if ! command -v composer &> /dev/null; then
    error "Composer no está instalado"
    exit 1
fi
info "Composer encontrado"

# Crear .env si no existe
if [ ! -f .env ]; then
    warn ".env no existe, creando desde .env.example..."
    cp .env.example .env
    info ".env creado"
else
    info ".env ya existe"
fi

# Instalar dependencias PHP
echo ""
echo "Instalando dependencias PHP..."
composer install
info "Dependencias PHP instaladas"

# Generar APP_KEY
echo ""
echo "Generando APP_KEY..."
php artisan key:generate
info "APP_KEY generada"

# Crear directorio de almacenamiento
echo ""
echo "Configurando directorios..."
mkdir -p storage/app/public/rifas
chmod -R 755 storage bootstrap/cache
info "Directorios configurados"

# Link de almacenamiento
if [ ! -L public/storage ]; then
    echo "Creando enlace simbólico de storage..."
    php artisan storage:link
    info "Enlace simbólico creado"
else
    info "Enlace simbólico ya existe"
fi

# Base de datos
echo ""
echo "Configurando base de datos..."
php artisan migrate --force 2>/dev/null || {
    warn "No se pudo ejecutar migraciones automáticamente"
    warn "Ejecuta manualmente: php artisan migrate"
}

# Crear usuario admin (opcional)
echo ""
read -p "¿Deseas crear un usuario admin? (s/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Ss]$ ]]; then
    php artisan tinker << 'EOF'
$user = App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
]);

$role = App\Models\Role::where('slug', 'admin')->first();
if ($role) {
    $user->roles()->attach($role);
}

echo "\n✓ Usuario admin creado:\n";
echo "Email: " . $user->email . "\n";
echo "Password: password\n";
exit;
EOF
fi

# Limpiar caché
echo ""
echo "Limpiando caché..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear
info "Caché limpiado"

# Éxito
echo ""
echo "╔════════════════════════════════════════╗"
echo "║${GREEN} ✓ Configuración completada exitosamente${NC} ║"
echo "╚════════════════════════════════════════╝"
echo ""
echo "Próximos pasos:"
echo ""
echo "1. Inicia el servidor:"
echo "   ${YELLOW}php artisan serve${NC}"
echo ""
echo "2. Abre en el navegador:"
echo "   ${YELLOW}http://localhost:8000${NC}"
echo ""
echo "3. Accede al admin:"
echo "   ${YELLOW}http://localhost:8000/admin${NC}"
echo "   Email: admin@example.com"
echo "   Password: password"
echo ""
echo "📚 Para más información, consulta README_MASTER.md"
echo ""
