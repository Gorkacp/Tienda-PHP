<?php
namespace Lib;

/**
 * Clase Router que gestiona las rutas y las peticiones HTTP.
 * Permite añadir rutas y métodos, así como despachar las peticiones 
 * a la función correspondiente.
 */
class Router {

    /** @var array Arreglo que almacena las rutas configuradas */
    private static $routes = [];

    /**
     * Añade una nueva ruta al sistema.
     * 
     * @param string $method El método HTTP para la ruta (GET, POST, etc.).
     * @param string $action La ruta que se debe interceptar.
     * @param Callable $controller El controlador que se ejecutará para esta ruta.
     * 
     * @return void
     */
    public static function add(string $method, string $action, Callable $controller): void {
        // Se limpia la acción de cualquier barra inclinada adicional.
        $action = trim($action, '/');
        
        // Se almacena la ruta con su método y controlador en el arreglo de rutas.
        self::$routes[$method][$action] = $controller;
    }

    /**
     * Procesa la petición entrante, obteniendo la ruta y ejecutando el controlador correspondiente.
     * 
     * Este método obtiene el sufijo de la URL y selecciona la ruta adecuada.
     * Si la URL contiene un parámetro numérico al final, se asigna a `$param` y se modifica la ruta.
     * Finalmente, se ejecuta el controlador asociado a la ruta.
     * 
     * @return void
     */
    public static function dispatch(): void {
        // Se obtiene el método de la solicitud HTTP (GET, POST, etc.).
        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';

        // Se obtiene la acción de la URL, eliminando el prefijo '/Tienda-PHP'.
        $action = preg_replace('/Tienda-PHP/', '', isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '');
        
        // Se limpia la acción de cualquier barra inclinada adicional.
        $action = trim($action, '/');

        $param = null;
        
        // Se busca si la acción contiene un parámetro numérico al final de la URL.
        preg_match('/[0-9]+$/', $action, $match);
        
        if (!empty($match)) {
            // Si se encuentra un parámetro, se asigna a `$param`.
            $param = $match[0];
            // Se reemplaza el parámetro en la acción por un marcador ':id'.
            $action = preg_replace('/' . $match[0] . '/', ':id', $action);
        }

        // Se obtiene la función asociada a la ruta y método.
        $fn = self::$routes[$method][$action] ?? null;
        
        if ($fn) {
            // Si se encuentra la ruta, se ejecuta el controlador.
            $callback = self::$routes[$method][$action];
            echo \call_user_func($callback, $param);
        } else {
            // Si no se encuentra la ruta, se redirige al error 404.
            
        }
    }
}
?>
