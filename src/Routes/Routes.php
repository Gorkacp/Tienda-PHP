<?php
namespace Routes;

use Controllers\InicioController;
use Controllers\CategoriaController;
use Controllers\ProductoController;
use Controllers\UsuarioController;
use Controllers\CarritoController;
use Controllers\ErrorController;
use Controllers\PedidoController;
use Lib\Router;

class Routes {
    /**
     * Método estático para definir todas las rutas de la aplicación.
     * Registra las rutas con el método y controlador correspondiente.
     */
    public static function index() {
        // Ruta para la página de inicio
        Router::add('GET', '/', function() {
            return (new InicioController())->index();
        });

        // Ruta para ver una categoría por ID
        Router::add('GET', '/categoria/ver/?id=:id', function($id) {
            return (new CategoriaController())->ver($id);
        });

        // Rutas para crear una categoría (GET y POST)
        Router::add('GET', '/categoria/crear', function() {
            return (new CategoriaController())->crear();
        });

        Router::add('POST', '/categoria/crear', function() {
            return (new CategoriaController())->crear();
        });

        // Ruta para borrar una categoría por ID
        Router::add('GET', '/categoria/borrar/?id=:id', function($id) {
            return (new CategoriaController())->borrar($id);
        });

        // Ruta para editar una categoría por ID
        Router::add('GET', '/categoria/editar/?id=:id', function($id) {
            return (new CategoriaController())->editar($id);
        });

        // Ruta para actualizar una categoría (POST)
        Router::add('POST', '/categoria/actualizar', function() {
            return (new CategoriaController())->actualizar();
        });

        // Ruta para gestionar categorías
        Router::add('GET', '/categoria/gestionarCategorias', function() {
            return (new CategoriaController())->gestionarCategorias();
        });

        // Ruta para ver detalles de un producto por ID
        Router::add('GET', '/producto/verDetalles/?id=:id', function($id) {
            return (new ProductoController())->verDetalles($id);
        });

        // Rutas para crear un producto (GET y POST)
        Router::add('GET', '/producto/crear', function() {
            return (new ProductoController())->crear();
        });

        Router::add('POST', '/producto/crear', function() {
            return (new ProductoController())->crear();
        });

        // Ruta para borrar un producto por ID
        Router::add('GET', '/producto/borrar/?id=:id', function($id) {
            return (new ProductoController())->borrar($id);
        });

        // Ruta para editar un producto por ID
        Router::add('GET', '/producto/editar/?id=:id', function($id) {
            return (new ProductoController())->editar($id);
        });

        // Ruta para actualizar un producto (POST)
        Router::add('POST', '/producto/editar', function() {
            return (new ProductoController())->editar();
        });

        // Ruta para gestionar productos
        Router::add('GET', '/producto/gestionarProductos', function() {
            return (new ProductoController())->gestionarProductos();
        });

        // Rutas para gestionar el carrito de compras
        Router::add('GET', '/carrito/agregarProducto/?id=:id', function($id) {
            return (new CarritoController())->agregarProducto($id);
        });

        Router::add('GET', '/carrito/obtenerCarrito', function() {
            return (new CarritoController())->obtenerCarrito();
        });

        Router::add('GET', '/carrito/eliminarProducto/?id=:id', function($id) {
            return (new CarritoController())->eliminarProducto($id);
        });

        Router::add('GET', '/carrito/aumentarCantidad/?id=:id', function($id) {
            return (new CarritoController())->aumentarCantidad($id);
        });

        Router::add('GET', '/carrito/disminuirCantidad/?id=:id', function($id) {
            return (new CarritoController())->disminuirCantidad($id);
        });

        // Rutas para gestionar pedidos
        Router::add('GET', '/pedido/crear', function() {
            return (new PedidoController())->crear();
        });

        Router::add('GET', '/pedido/mostrarPedido', function() {
            return (new PedidoController())->mostrarPedido();
        });

        Router::add('GET', '/pedido/misPedidos', function() {
            return (new PedidoController())->misPedidos();
        });

        Router::add('GET', '/pedido/todosLosPedidos', function() {
            return (new PedidoController())->todosLosPedidos();
        });

        Router::add('POST', '/pedido/crear', function() {
            return (new PedidoController())->crear();
        });

        Router::add('GET', '/pedido/confirmarPedido/?id=:id', function($id) {
            return (new PedidoController())->confirmarPedido($id);
        });

        Router::add('GET', '/pedido/eliminar/?id=:id', function($id) {
            return (new PedidoController())->eliminar($id);
        });

        Router::add('GET', '/pedido/editar/?id=:id', function($id) {
            return (new PedidoController())->editar($id);
        });

        Router::add('POST', '/pedido/actualizar', function() {
            return (new PedidoController())->actualizar();
        });

        // Rutas para gestionar usuarios
        Router::add('GET', '/usuario/login', function() {
            return (new UsuarioController())->login();
        });

        Router::add('POST', '/usuario/login', function() {
            return (new UsuarioController())->login();
        });

        Router::add('POST', '/usuario/registro', function() {
            return (new UsuarioController())->registro();
        });

        Router::add('GET', '/usuario/registro', function() {
            return (new UsuarioController())->registro();
        });

        Router::add('GET', '/usuario/verTodos', function() {
            return (new UsuarioController())->verTodos();
        });

        Router::add('GET', '/usuario/logout', function() {
            return (new UsuarioController())->logout();
        });

        Router::add('GET', '/usuario/eliminar/?id=:id', function($id) {
            return (new UsuarioController())->eliminar($id);
        });

        Router::add('GET', '/usuario/editar/?id=:id', function($id) {
            return (new UsuarioController())->editar($id);
        });

        Router::add('POST', '/usuario/actualizar', function() {
            return (new UsuarioController())->actualizar();
        });

        // Ruta para mostrar el formulario de recuperación de contraseña
        Router::add('GET', '/usuario/recuperar', function() {
            return (new UsuarioController())->mostrarFormularioRecuperacion();
        });

        // Ruta para manejar la solicitud de recuperación de contraseña
        Router::add('POST', '/usuario/solicitarRecuperacion', function() {
            return (new UsuarioController())->solicitarRecuperacion();
        });

        // Ruta para mostrar el formulario de restablecimiento de contraseña
        Router::add('GET', '/usuario/restablecer', function() {
            return (new UsuarioController())->mostrarFormularioRestablecimiento();
        });

        // Ruta para manejar la solicitud de restablecimiento de contraseña
        Router::add('POST', '/usuario/restablecer', function() {
            return (new UsuarioController())->restablecerPassword();
        });

        // Ruta para manejar errores 404
        Router::add('GET', '/error', function() {
            return (new ErrorController())->error404();
        });

        // Despachar las rutas definidas
        Router::dispatch();
    }
}
?>