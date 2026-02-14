# Rifando - Sistema de Rifas y Boletos

Un sistema completo de gestión de rifas y venta de boletos basado en Laravel, con integración de pagos y autenticación de usuarios.

## 📋 Características

- ✅ Gestión de rifas (creación, edición, eliminación)
- 🎫 Sistema de ventas de boletos
- 💳 Integración con MercadoPago
- 👤 Autenticación y gestión de usuarios
- 👑 Sistema de roles y permisos
- 🧾 Gestión de compras y cupones
- 📊 Panel de administración
- 🎯 Métodos de pago configurables

## 🚀 Requisitos Previos

- PHP 8.3 o superior
- Composer
- Node.js 18+
- npm
- MySQL/MariaDB
- Apache (recomendado con XAMPP)

## 📦 Instalación

### 1. Clonar o descargar el proyecto

```bash
cd c:\xampp\htdocs\rifando
```

### 2. Instalar dependencias de PHP

```bash
composer install
```

### 3. Instalar dependencias de Node.js

```bash
npm install
```

### 4. Configurar variables de entorno

```bash
cp .env.example .env
```

Edita el archivo `.env` con tus datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rifando
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generar clave de aplicación

```bash
php artisan key:generate
```

### 6. Configurar base de datos

```bash
php artisan migrate
```

Para cargar datos de prueba:

```bash
php artisan db:seed
```

### 7. Construir assets

```bash
npm run build
```

Para desarrollo con hot reload:

```bash
npm run dev
```

## 🏃 Ejecutar la Aplicación

### En XAMPP

1. Coloca el proyecto en `c:\xampp\htdocs\rifando`
2. Inicia Apache en XAMPP
3. Accede a: `http://localhost/rifando/public`

### En desarrollo

```bash
php artisan serve
```

La aplicación estará disponible en `http://localhost:8000`

## 📁 Estructura del Proyecto

```
rifando/
├── app/
│   ├── Models/              # Modelos de base de datos
│   ├── Http/
│   │   ├── Controllers/     # Controladores
│   │   └── Middleware/      # Middleware
│   ├── Services/            # Servicios de negocio
│   └── Console/Commands/    # Comandos artisan
├── database/
│   ├── migrations/          # Migraciones de base de datos
│   ├── seeders/             # Sembradores de datos
│   └── factories/           # Factories para testing
├── resources/
│   ├── views/               # Vistas Blade
│   ├── css/                 # Estilos
│   └── js/                  # Scripts JavaScript
├── routes/                  # Definición de rutas
├── tests/                   # Tests unitarios y funcionales
├── config/                  # Archivos de configuración
├── storage/                 # Logs y almacenamiento
└── public/                  # Archivos públicos (CSS, JS compilados)
```

## 🗄️ Base de Datos

### Tablas principales

- **users** - Usuarios del sistema
- **roles** - Roles y permisos
- **rifas** - Información de las rifas
- **boletos** - Boletos disponibles
- **compras** - Compras realizadas
- **boleto_compra** - Relación compra-boleto
- **cupones** - Cupones de descuento
- **payment_methods** - Métodos de pago
- **payment_credentials** - Credenciales de pago

## 🔐 Autenticación

El sistema incluye autenticación por:
- Email y contraseña
- Sistema de roles y permisos

Usuarios de demo (si se ejecutan seeders):
- Email: `admin@rifando.local`
- Contraseña: (ver archivo de seeder)

## 💳 Integración de Pagos

Se encuentra configurrada la integración con **MercadoPago**. Para activarla:

1. Obtén tus credenciales en [MercadoPago](https://www.mercadopago.com)
2. Añade en `.env`:

```env
MERCADOPAGO_PUBLIC_KEY=tu_llave_publica
MERCADOPAGO_ACCESS_TOKEN=tu_token_acceso
```

## 🧪 Testing

Para ejecutar los tests:

```bash
php artisan test
```

## 📝 Migraciones

Ver y ejecutar migraciones:

```bash
# Ver estado de migraciones
php artisan migrate:status

# Realizar migraciones
php artisan migrate

# Revertir las últimas migraciones
php artisan migrate:rollback
```

## 🛠️ Comandos Útiles

```bash
# Crear un nuevo modelo con migrate
php artisan make:model NombreModelo -m

# Crear un controlador
php artisan make:controller NombreControlador

# Limpiar cache
php artisan cache:clear
php artisan config:clear

# Ver rutas disponibles
php artisan route:list
```

## 📄 Licencia

Este proyecto es privado. Todos los derechos reservados.

## 👤 Autor
https://github.com/erikab586


## 📞 Soporte 
- Email: yosoyproferika@gmail.com
## 💾 Base de Datos

La base de datos completa del sistema (estructura optimizada y datos de producción) no está incluida en este repositorio.

Para adquirirla, puedes solicitarla por un costo único de **15 USD**.

Por favor, envía un mensaje indicando que deseas adquirir la base de datos del sistema **Rifando**.

---

**Última actualización:** Febrero 2026
