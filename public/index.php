<?php
/*****************************************************
 * Front Controller - index.php
 * 
 * Punto de entrada único de la aplicación.
 * 
 * Este archivo recibe todas las peticiones HTTP gracias al .htaccess
 * y las redirige al controlador correspondiente mediante el Router.
 * 
 * Flujo:
 * 1. Carga el bootstrap (configuración, autoload, variables de entorno)
 * 2. Define las rutas de la aplicación
 * 3. Encuentra la ruta que coincide con la petición actual
 * 4. Despacha la petición al controlador apropiado
 * 
 * @author Adrián Anta Bellido
 * @version 1.0
 * 
 * FIN TAREA
*/
require_once __DIR__ . '/../bootstrap.php';

/*****************************************************
 * TAREA 2
 * 
 * Incluye el uso de las clases necesarias. 
 * 
 * FIN TAREA
*/
use App\Core\Router;
use App\Core\Dispatcher;
use App\Controllers\IndexController;
use App\Controllers\ContactoController;
use App\Controllers\AuthController;
use App\Middleware\AuthMiddleware;

$router = new Router();

// Configurar el basePath para que el Router sepa quitar /contactos_mvc/public de las URIs
$scriptDir = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
$router->setBasePath($scriptDir);

// --- Rutas Públicas (no requieren autenticación) ---
$router->get('/', [IndexController::class, 'indexAction']);

// Rutas de autenticación
$router->get('/auth/login', [AuthController::class, 'loginAction']);
$router->post('/auth/login', [AuthController::class, 'loginPostAction']);
$router->get('/auth/registro', [AuthController::class, 'registroAction']);
$router->post('/auth/registro', [AuthController::class, 'registroPostAction']);
$router->get('/auth/logout', [AuthController::class, 'logoutAction']);

// Rutas de visualización de contactos (públicas - todos pueden ver)
$router->get('/contactos', [ContactoController::class, 'indexAction']);
$router->get('/contactos/ver/{id}', [ContactoController::class, 'showAction']);

// --- Rutas Protegidas (requieren autenticación) ---
// Solo usuarios autenticados pueden crear, editar y eliminar contactos
$router->get('/contactos/crear', [ContactoController::class, 'createAction'], [AuthMiddleware::class]);
$router->post('/contactos/crear', [ContactoController::class, 'storeAction'], [AuthMiddleware::class]);
$router->get('/contactos/editar/{id}', [ContactoController::class, 'editAction'], [AuthMiddleware::class]);
$router->post('/contactos/editar/{id}', [ContactoController::class, 'updateAction'], [AuthMiddleware::class]);
$router->post('/contactos/eliminar/{id}', [ContactoController::class, 'deleteAction'], [AuthMiddleware::class]);


// --- Proceso de Despacho ---
$route = $router->match($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

$dispatcher = new Dispatcher();
$dispatcher->dispatch($route);