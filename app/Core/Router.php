<?php
/*****************************************************
 * TAREA 1
 * 
 * Router - Enrutador de la aplicación
 * 
 * Registra y coincide rutas HTTP con sus controladores asociados.
 * Soporta parámetros dinámicos en las URLs como {id}, {slug}, etc.
 * 
 * FIN TAREA
*/

/*****************************************************
 * TAREA 2
 * 
 * Incluye el espacio de nombres. 
 * 
 * FIN TAREA
*/
namespace App\Core;

class Router
{
    /*****************************************************
     * TAREA 3
     * 
     * Comenta adecuadamente cada uno de los métodos de la clase.
     * 
     * FIN TAREA
    */
    private $routes = [];
    private $basePath = '';

    public function setBasePath($basePath) {
        $this->basePath = rtrim($basePath, '/');
    }

    public function get(string $path, array $handler, array $middlewares = []): void {
        $this->addRoute('GET', $path, $handler, $middlewares);
    }

    public function post(string $path, array $handler, array $middlewares = []): void {
        $this->addRoute('POST', $path, $handler, $middlewares);
    }


    private function addRoute(string $method, string $path, array $handler, array $middlewares = []): void
    {
        $normalizedPath = $this->basePath . '/' . ltrim($path, '/');
        $pattern = $this->convertPathToRegex($normalizedPath);

        $this->routes[] = [
            'method'      => strtoupper($method),
            'pattern'     => $pattern,
            'handler'     => $handler,
            'middlewares' => $middlewares,
        ];
    }

    private function convertPathToRegex($path)
    {
        $pattern = preg_quote($path, '#');
        $pattern = preg_replace('/\\\{([a-zA-Z_][a-zA-Z0-9_]*)\\\}/', '(?P<$1>[^/]+)', $pattern);
        return '#^' . $pattern . '$#';
    }

    public function match(string $method, string $uri): ?array
    {
        $method = strtoupper($method);
        $path   = $this->cleanUri($uri); 

       /*****************************************************
        * TAREA 4
        * 
        * Implementar el algoritmo de coincidencia de ruta.
        * 
        * FIN TAREA
       */
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return [
                    'handler'     => $route['handler'],
                    'params'      => $params,
                    'middlewares' => $route['middlewares'] ?? []
                ];
            }
        }
        
        return null;
    }

    private function cleanUri($uri)
    {
        $path = parse_url($uri, PHP_URL_PATH);
        $path = '/' . trim($path, '/');
        

        if ($this->basePath && strpos($path, $this->basePath) === 0) {
            $path = substr($path, strlen($this->basePath));
            $path = '/' . trim($path, '/');
        }
        
        return $path === '' ? '/' : $path;
    }
}