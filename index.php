<?php
// Front Controller - index.php

// Iniciar sesión
session_start();

// Cargar configuración y autoload
require_once __DIR__ . '/app/config/autoload.php';
require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/config/logs.php';

// Obtener parámetros de la URL
$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

// Mapeo de controladores
$controllerMap = [
    'home' => 'HomeController',
    'auth' => 'AuthController',
    'profile' => 'ProfileController',
    'business' => 'BusinessController',
    'reservation' => 'ReservationController',
    'admin' => 'AdminController',
    'search' => 'SearchController'
];

// Mapeo de acciones especiales (sin controlador específico)
$specialActions = [
    'login' => ['controller' => 'AuthController', 'method' => 'login'],
    'register' => ['controller' => 'AuthController', 'method' => 'register'],
    'logout' => ['controller' => 'AuthController', 'method' => 'logout']
];

try {
    // Manejar acciones especiales
    if (isset($specialActions[$action])) {
        $controllerName = $specialActions[$action]['controller'];
        $methodName = $specialActions[$action]['method'];
        
        if (class_exists($controllerName)) {
            $controllerInstance = new $controllerName();
            
            if (method_exists($controllerInstance, $methodName)) {
                // Para login y register, determinar si mostrar vista o procesar
                if (($action === 'login' || $action === 'register') && $_SERVER['REQUEST_METHOD'] === 'GET') {
                    $showMethod = 'show' . ucfirst($action);
                    if (method_exists($controllerInstance, $showMethod)) {
                        $controllerInstance->$showMethod();
                    } else {
                        $controllerInstance->$methodName();
                    }
                } else {
                    $controllerInstance->$methodName();
                }
            } else {
                throw new Exception("Método no encontrado: $methodName");
            }
        } else {
            throw new Exception("Controlador no encontrado: $controllerName");
        }
    }
    // Manejar rutas normales con controlador
    elseif (isset($controllerMap[$controller])) {
        $controllerName = $controllerMap[$controller];
        
        if (class_exists($controllerName)) {
            $controllerInstance = new $controllerName();
            
            if (method_exists($controllerInstance, $action)) {
                $controllerInstance->$action();
            } else {
                throw new Exception("Acción no encontrada: $action en $controllerName");
            }
        } else {
            throw new Exception("Controlador no encontrado: $controllerName");
        }
    }
    // Ruta por defecto
    else {
        $homeController = new HomeController();
        $homeController->index();
    }
    
} catch (Exception $e) {
    // Log del error
    Logger::error("Error en routing: " . $e->getMessage());
    
    // Mostrar página de error amigable con detalle del error
    http_response_code(404);
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Error - TicoTrips</title>
        <script src='https://cdn.tailwindcss.com'></script>
    </head>
    <body class='bg-gray-100 flex items-center justify-center min-h-screen'>
        <div class='text-center'>
            <h1 class='text-6xl font-bold text-gray-800 mb-4'>404</h1>
            <p class='text-xl text-gray-600 mb-4'>Página no encontrada</p>
            <p class='text-sm text-red-600 mb-8'>" . htmlspecialchars($e->getMessage()) . "</p>
            <a href='index.php' class='bg-teal-600 text-white px-6 py-3 rounded-lg hover:bg-teal-700 transition'>
                Volver al inicio
            </a>
        </div>
    </body>
    </html>";
}
