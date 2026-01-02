<?php

class Router {
    protected $routes = [];

    // Registra rutas GET
    public function get($path, $handler) {
        $this->routes['GET'][$path] = $handler;
    }

    // Registra rutas POST
    public function post($path, $handler) {
        $this->routes['POST'][$path] = $handler;
    }

    // Ejecuta la ruta actual
    public function dispatch($uri, $method) {
        // Limpiamos la URI (quitamos query params como ?id=1)
        $path = parse_url($uri, PHP_URL_PATH);
        
        // Verificamos si la ruta existe para ese método
        if (isset($this->routes[$method][$path])) {
            $handler = $this->routes[$method][$path];
            
            // Separamos Controlador@Metodo
            [$controllerName, $methodName] = explode('@', $handler);
            
            // Importamos el controlador dinámicamente
            require_once __DIR__ . "/../controllers/$controllerName.php";
            
            // Instanciamos y llamamos al método
            $controller = new $controllerName();
            $controller->$methodName();
        } else {
            // Manejo básico de error 404
            header("HTTP/1.0 404 Not Found");
            echo "404 - Página no encontrada";
        }
    }
}