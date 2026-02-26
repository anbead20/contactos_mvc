<?php
/*****************************************************
 * TAREA 1: Documentación del archivo
 * 
 * Bootstrap del proyecto - Archivo de arranque
 * 
 * Este archivo inicializa el entorno de la aplicación:
 * - Carga las rutas y configuración del proyecto
 * - Activa el autoload de Composer para cargar clases automáticamente
 * - Carga las variables de entorno desde .env
 * - Configura el manejo de errores según el entorno (desarrollo/producción)
 * - Establece configuraciones de seguridad y logging
 * - Crea los directorios necesarios si no existen
 * 
 * FIN TAREA
*/

/*****************************************************
 * TAREA 2: Mover constantes al archivo de configuración
 * 
 * En lugar de definir aquí las constantes APP_ROOT, APP_DIR, etc.,
 * las movemos al archivo app/config/config.php para separar la configuración
 * de la lógica de arranque.
 * 
 * Esto hace el código más organizado y mantenible. Las constantes que 
 * antes estaban aquí ahora se definen en:
 * 
 * define('APP_ROOT',   realpath(__DIR__ . '/../'));
 * define('APP_DIR',    APP_ROOT . '/app');
 * define('PUBLIC_DIR', APP_ROOT . '/public');
 * define('VENDOR_DIR', APP_ROOT . '/vendor');
 * define('VIEWS_DIR',  APP_ROOT . '/views');
 * 
 * FIN TAREA
*/
require_once __DIR__ . '/app/config/config.php';

/*****************************************************
 * TAREA 3: Incluir autoload y configuración
 * 
 * Cargamos dos archivos esenciales:
 * - vendor/autoload.php: El autoloader de Composer que permite usar clases sin require_once
 *   Gracias al PSR-4 configurado, las clases del namespace App\ se cargan automáticamente
 * - app/config/parametros.php: Constantes específicas de la aplicación (paginación, límites, etc.)
 * 
 * require_once APP_DIR . '/config/parametros.php';
 * require_once VENDOR_DIR . '/autoload.php';
 *    
 * FIN TAREA
*/
require_once VENDOR_DIR . '/autoload.php';
require_once APP_DIR . '/config/parametros.php';

/*****************************************************
 * TAREA 4: Carga de helpers globales
 * 
 * Este bloque incluye funciones helper que pueden usarse en cualquier parte de la aplicación.
 * Solo se carga si el archivo existe, así no da error si aún no lo hemos creado.
 * 
 * Los helpers son funciones útiles como formatear fechas, limpiar texto, etc.
 * 
 * FIN TAREA      
*/
if (file_exists(APP_DIR . '/helpers/helpers.php')) {
    require_once APP_DIR . '/helpers/helpers.php';
}


/*****************************************************
 * TAREA 5: Carga y validación de variables de entorno
 * 
 * La librería phpdotenv carga las variables desde el archivo .env:
 * 
 * - createImmutable(APP_ROOT): Crea una instancia de Dotenv apuntando a la raíz del proyecto
 * - load(): Carga todas las variables del .env en $_ENV
 * - required(['DBHOST', ...]): Valida que existan estas variables críticas de base de datos
 * - notEmpty(): Asegura que no estén vacías
 * 
 * Si falta alguna variable requerida, muestra un error y la aplicación no arranca.
 * Esto evita que la app funcione con configuración incompleta.
 * 
 * FIN TAREA      
*/
use Dotenv\Dotenv;
try {
    $dotenv = Dotenv::createImmutable(APP_ROOT);
    $dotenv->load();
    $dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER'])->notEmpty();
} catch (Exception $e) {
    die('Fallo crítico en configuración: ' . $e->getMessage());
}


/*****************************************************
 * TAREA 6: Configuración del entorno y manejo de errores
 * 
 * Define cómo se manejan los errores según el entorno (desarrollo o producción):
 * 
 * En desarrollo (APP_ENV=dev):
 * - error_reporting(E_ALL): Reporta TODOS los errores para facilitar la depuración
 * - display_errors=1: Muestra los errores directamente en pantalla
 * - Whoops: Librería que muestra errores con interfaz visual bonita y detallada,
 *   incluyendo stack trace, código fuente y valores de variables
 * 
 * En producción (APP_ENV=production):
 * - Filtra errores deprecated y strict (menos ruido)
 * - display_errors=0: Oculta los errores al usuario por seguridad
 * - Los errores se guardan solo en logs, no se muestran
 * 
 * FIN TAREA      
*/

/*****************************************************
 * TAREA 7: Configuración adicional de seguridad y logs
 * 
 * Configuraciones importantes para seguridad y registro de errores:
 * 
 * - log_errors=1: Activa el registro de errores en archivo de log
 * - error_log: Define la ruta donde se guardan los logs de errores PHP
 * - session.cookie_httponly=1: Las cookies de sesión NO son accesibles desde JavaScript,
 *   esto previene ataques XSS que intenten robar sesiones
 * - session.use_strict_mode=1: Rechaza IDs de sesión no inicializados por el servidor,
 *   previene ataques de fijación de sesión
 * - date_default_timezone_set: Define la zona horaria para fechas y horas,
 *   usa la variable de entorno TIMEZONE o Europe/Madrid por defecto
 * 
 * IMPORTANTE: Estas configuraciones deben ir ANTES del manejo de errores con Whoops
 * para evitar que los headers ya se hayan enviado.
 * 
 * FIN TAREA      
*/
ini_set('log_errors', 1);
ini_set('error_log', APP_ROOT . '/logs/php_errors.log');
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
date_default_timezone_set($_ENV['TIMEZONE'] ?? 'Europe/Madrid');

// Ahora configuramos el manejo de errores según el entorno
define('APP_ENV', $_ENV['APP_ENV'] ?? 'production');
if (APP_ENV === 'dev' || APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
} else {
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
    ini_set('display_errors', 0);
}

/**
 * MANTENIMIENTO DE DIRECTORIOS
 * 
 * Crea automáticamente los directorios necesarios si no existen.
 * Esto es muy útil cuando alguien clona el proyecto por primera vez desde Git,
 * ya que Git no sube carpetas vacías.
 * 
 * Directorios creados:
 * - logs/: Para guardar errores y registros de la aplicación
 * - cache/: Para archivos temporales y caché
 * - public/uploads/contactos/: Para las fotos de perfil de contactos
 * 
 * El 0755 son los permisos Unix:
 * - 7 (propietario): lectura, escritura y ejecución
 * - 5 (grupo): lectura y ejecución
 * - 5 (otros): lectura y ejecución
 */
$requiredDirs = [APP_ROOT . '/logs', APP_ROOT . '/cache', PUBLIC_DIR . '/uploads/contactos'];
foreach ($requiredDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}


/**
 * URL BASE PARA VISTAS
 * 
 * Construye la URL base del proyecto para usar en enlaces y recursos.
 * Esta URL se usa en las vistas para generar enlaces y cargar assets (CSS, JS, imágenes).
 * 
 * El proceso:
 * 1. Detecta automáticamente si usa HTTP o HTTPS
 * 2. Obtiene el host (ej: localhost o tudominio.com)
 * 3. Calcula el directorio base quitando /public de la ruta del script
 * 4. Elimina barras finales para evitar URLs con doble barra //
 * 
 * Solo se ejecuta en contexto web (no CLI).
 * 
 * Ejemplo de resultado: http://localhost/practica_contactos_mvc
 */
if (php_sapi_name() !== 'cli') {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptDir = str_replace('/public', '', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    
    // Eliminamos barras finales sobrantes para evitar el error de doble barra //
    $baseUrl = rtrim($protocol . $host . $scriptDir, '/\\');
    define('BASE_URL', $baseUrl);
} else {
    // En modo CLI definimos una URL base por defecto
    define('BASE_URL', 'http://localhost');
}
