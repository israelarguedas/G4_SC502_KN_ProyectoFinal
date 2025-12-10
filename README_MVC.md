# TicoTrips - Estructura MVC

Este proyecto ha sido convertido a una arquitectura MVC (Modelo-Vista-Controlador) siguiendo las mejores prácticas de desarrollo PHP.

## Estructura del Proyecto

```
G4_SC502_KN_ProyectoFinal/
├── app/
│   ├── config/
│   │   ├── autoload.php       # Sistema de autocarga de clases
│   │   ├── database.php       # Conexión a base de datos (Singleton)
│   │   └── logs.php           # Sistema de logging
│   ├── controllers/
│   │   ├── AuthController.php        # Autenticación y registro
│   │   ├── BusinessController.php    # Gestión de negocios
│   │   ├── HomeController.php        # Página principal
│   │   ├── ProfileController.php     # Perfil de usuario
│   │   └── ReservationController.php # Reservaciones
│   ├── models/
│   │   ├── Auth.php           # Modelo de autenticación
│   │   ├── Business.php       # Modelo de negocios
│   │   ├── Reservation.php    # Modelo de reservaciones
│   │   ├── Review.php         # Modelo de reseñas
│   │   └── User.php           # Modelo de usuarios
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── header.php     # Encabezado común
│   │   │   └── footer.php     # Pie de página común
│   │   ├── auth/
│   │   │   ├── login.php      # Vista de inicio de sesión
│   │   │   └── register.php   # Vista de registro
│   │   ├── home/
│   │   │   └── index.php      # Página principal
│   │   ├── business/
│   │   ├── profile/
│   │   └── reservations/
│   ├── public/
│   │   ├── css/               # Archivos CSS
│   │   ├── js/                # Archivos JavaScript
│   │   └── images/            # Imágenes
│   └── logs/                  # Archivos de log
├── index.php                  # Front Controller
├── .htaccess                  # Configuración de Apache
└── README_MVC.md              # Este archivo

## Archivos Antiguos (Respaldo)

Los siguientes archivos fueron respaldados con el sufijo `_old`:
- index_old.php
- authenticate.php (funcionalidad movida a AuthController)
- login.php (movida a app/views/auth/login.php)
- register.php (movida a app/views/auth/register.php)
```

## Componentes Principales

### 1. Front Controller (index.php)

El punto de entrada único de la aplicación. Maneja todo el enrutamiento y carga los controladores apropiados.

**Ejemplo de URLs:**
- `index.php` - Página principal
- `index.php?action=login` - Login
- `index.php?action=register` - Registro
- `index.php?controller=business&action=showPromotions` - Promociones
- `index.php?controller=profile&action=show` - Perfil de usuario

### 2. Autoload (app/config/autoload.php)

Sistema de carga automática de clases. Busca y carga automáticamente las clases de controllers, models y config cuando se necesitan.

### 3. Database (app/config/database.php)

Implementación del patrón Singleton para la conexión a base de datos. Asegura una única instancia de conexión PDO en toda la aplicación.

**Uso:**
```php
$pdo = Database::getInstance()->getConnection();
```

### 4. Logger (app/config/logs.php)

Sistema de registro de eventos y errores.

**Uso:**
```php
Logger::info("Usuario registrado: " . $email);
Logger::error("Error en la base de datos: " . $e->getMessage());
Logger::warning("Acceso denegado");
```

## Controladores

### AuthController
Maneja autenticación y registro de usuarios.

**Métodos:**
- `showLogin()` - Muestra formulario de login
- `login()` - Procesa login
- `showRegister()` - Muestra formulario de registro
- `register()` - Procesa registro
- `logout()` - Cierra sesión

### BusinessController
Gestiona negocios, aplicaciones y cupones.

**Métodos:**
- `showApplication()` - Formulario de aplicación de negocio
- `submitApplication()` - Procesa aplicación
- `showPromotions()` - Muestra cupones activos
- `manageCoupons()` - Gestión de cupones del negocio

### ReservationController
Gestiona reservaciones.

**Métodos:**
- `index()` - Lista de servicios disponibles
- `create()` - Formulario de reservación
- `store()` - Guarda reservación
- `myReservations()` - Reservaciones del usuario

### ProfileController
Gestiona perfil de usuario.

**Métodos:**
- `show()` - Muestra perfil
- `update()` - Actualiza perfil

### HomeController
Controlador de la página principal.

**Métodos:**
- `index()` - Página de inicio
- `search()` - Búsqueda de servicios

## Modelos

Cada modelo encapsula la lógica de acceso a datos para una entidad específica:

- **Auth**: Autenticación, creación y validación de usuarios
- **User**: Gestión de perfiles de usuario
- **Business**: Gestión de negocios, ubicaciones, cupones
- **Reservation**: Gestión de reservaciones
- **Review**: Gestión de reseñas y calificaciones

## Vistas

Las vistas están organizadas por funcionalidad:

- **layouts/**: Componentes compartidos (header, footer)
- **auth/**: Vistas de autenticación
- **home/**: Página principal
- **business/**: Vistas relacionadas con negocios
- **profile/**: Vistas de perfil de usuario
- **reservations/**: Vistas de reservaciones

## Migración desde Estructura Antigua

### Cambios Principales

1. **Configuración centralizada**: `config.php` → `app/config/database.php`
2. **Funciones globales**: `functions.php` → Métodos en modelos
3. **Lógica de autenticación**: `authenticate.php` → `AuthController`
4. **Vistas**: Archivos raíz → `app/views/`
5. **Assets**: `assets/` → `app/public/`

### Cómo Actualizar Enlaces

**Antes:**
```php
<a href="login.php">Login</a>
<a href="reservations.php">Reservaciones</a>
```

**Ahora:**
```php
<a href="index.php?action=login">Login</a>
<a href="index.php?controller=reservation&action=index">Reservaciones</a>
```

## Configuración

### Base de Datos

Editar `app/config/database.php`:

```php
private $db_host = 'localhost';
private $db_name = 'tico_trips_db';
private $db_user = 'root';
private $db_pass = 'plat20';
```

### Apache (.htaccess)

El archivo `.htaccess` maneja el rewriting de URLs y seguridad básica.

## Ventajas de la Estructura MVC

1. **Separación de responsabilidades**: Código más organizado y mantenible
2. **Reutilización**: Modelos y vistas reutilizables
3. **Testabilidad**: Más fácil de probar componentes individuales
4. **Escalabilidad**: Más fácil agregar nuevas funcionalidades
5. **Seguridad**: Mejor control de acceso y validación
6. **Logging**: Sistema centralizado de registro de eventos

## Próximos Pasos Recomendados

1. Migrar las vistas restantes (business, profile, reservations)
2. Implementar validación de formularios en controllers
3. Agregar middleware para autenticación
4. Implementar CSRF protection
5. Agregar pruebas unitarias
6. Documentar API endpoints
7. Optimizar queries de base de datos

## Desarrollo

Para agregar una nueva funcionalidad:

1. Crear el **Modelo** en `app/models/`
2. Crear el **Controlador** en `app/controllers/`
3. Crear las **Vistas** en `app/views/`
4. Actualizar el routing en `index.php` si es necesario

## Soporte

Para dudas o problemas con la nueva estructura, revisar los logs en `app/logs/`.
