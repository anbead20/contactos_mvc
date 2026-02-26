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
 * @author Tu Nombre
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

$router = new Router();

// --- Definición de Rutas ---
$router->get('/', [IndexController::class, 'indexAction']);
$router->get('/contactos', [ContactoController::class, 'indexAction']);
$router->get('/contactos/ver/{id}', [ContactoController::class, 'showAction']);
$router->get('/contactos/crear', [ContactoController::class, 'createAction']);
$router->post('/contactos/crear', [ContactoController::class, 'storeAction']);
/*****************************************************
 * TAREA 3
 * 
 * Incluye las rutas necesarias para la edición y el borrado. 
 * 
 * FIN TAREA
*/
$router->get('/contactos/editar/{id}', [ContactoController::class, 'editAction']);
$router->post('/contactos/editar/{id}', [ContactoController::class, 'updateAction']);
$router->post('/contactos/borrar/{id}', [ContactoController::class, 'deleteAction']);


// --- Proceso de Despacho ---
$route = $router->match($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

$dispatcher = new Dispatcher();
$dispatcher->dispatch($route);