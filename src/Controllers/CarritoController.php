<?php

namespace Controllers;

use Models\Producto;
use Models\Categoria;
use Lib\Pages;
use Services\ProductoService;
use Repositories\ProductoRepository;

/**
 * Clase CarritoController
 *
 * Esta clase maneja la lógica para las acciones relacionadas con el carrito de compras.
 * Incluye métodos para agregar, eliminar, actualizar productos y obtener el total del carrito.
 */
class CarritoController {
    private Producto $producto;
    private Categoria $categoria;
    private Pages $pages;
    private ProductoService $productoService;

    /**
     * Constructor de CarritoController.
     *
     * Inicializa las instancias de Producto, Categoria, Pages y ProductoService.
     */
    public function __construct() {
        $this->productoService = new ProductoService(new ProductoRepository());
        $this->producto = new Producto();
        $this->categoria = new Categoria();
        $this->pages = new Pages();
    }

    /**
     * Agrega un producto al carrito.
     *
     * Este método agrega un producto al carrito de compras. Si el producto ya está en el carrito, se aumenta la cantidad.
     * Verifica el stock disponible antes de agregar o aumentar la cantidad.
     * Redirige a la página del carrito.
     *
     * @param int $id El ID del producto.
     * 
     * @return void
     */
    public function agregarProducto() {
        $productoId = $_GET['id'];
        $this->producto->setId($productoId);
        $producto = $this->productoService->getById($productoId);
        if ($producto) {
            if (!isset($_SESSION['carrito'][$productoId])) {
                if ($producto['stock'] > 0) {
                    $_SESSION['carrito'][$productoId] = $producto;
                    $_SESSION['carrito'][$productoId]['cantidad'] = 1;
                } else {
                    $_SESSION['error'] = "No hay suficiente stock para agregar este producto.";
                }
            } else {
                $cantidadActual = $_SESSION['carrito'][$productoId]['cantidad'];
                if ($cantidadActual < $producto['stock']) {
                    $_SESSION['carrito'][$productoId]['cantidad']++;
                } else {
                    // Manejar el caso cuando se intenta agregar más productos de los que hay en stock
                    // Por ejemplo, mostrar un mensaje de error
                    $_SESSION['error'] = "No hay suficiente stock para agregar más de este producto.";
                }
            }
        }
        header('Location: '.BASE_URL.'carrito/obtenerCarrito/');
    }

    /**
     * Elimina un producto del carrito.
     *
     * Este método elimina un producto del carrito de compras y redirige a la página del carrito.
     *
     * @param int $id El ID del producto.
     * 
     * @return void
     */
    public function eliminarProducto($id) {
        $productoId = $id;
        if (isset($_SESSION['carrito'][$productoId])) {
            unset($_SESSION['carrito'][$productoId]);
        }
        header('Location: '.BASE_URL.'carrito/obtenerCarrito/');
    }

    /**
     * Obtiene los productos del carrito y muestra la página del carrito.
     *
     * Este método recupera los productos guardados en la sesión y calcula el total.
     * Luego, renderiza la página del carrito con los productos y el total.
     *
     * @return void
     */
    public function obtenerCarrito() {
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
        $productos = $_SESSION['carrito'];
        $total = $this->obtenerTotalCarrito();
        $this->pages->render('carrito/ver', ['productos' => $productos, 'total' => $total]);
    }

    /**
     * Vacía el carrito.
     *
     * Este método elimina todos los productos del carrito.
     *
     * @return void
     */
    public function vaciarCarrito() {
        unset($_SESSION['carrito']);
    }

    /**
     * Aumenta la cantidad de un producto en el carrito.
     *
     * Este método incrementa la cantidad de un producto en el carrito y redirige a la página del carrito.
     *
     * @param int $id El ID del producto.
     * 
     * @return void
     */
    public function aumentarCantidad($id) {
        $productoId = $id;
        if (isset($_SESSION['carrito'][$productoId])) {
            $_SESSION['carrito'][$productoId]['cantidad']++;
        }
        header('Location: '.BASE_URL.'carrito/obtenerCarrito/');
    }

    /**
     * Disminuye la cantidad de un producto en el carrito.
     *
     * Este método decrementa la cantidad de un producto en el carrito. Si la cantidad llega a 0, el producto es eliminado.
     * Redirige a la página del carrito.
     *
     * @param int $id El ID del producto.
     * 
     * @return void
     */
    public function disminuirCantidad($id) {
        $productoId = $id;
        if (isset($_SESSION['carrito'][$productoId])) {
            $_SESSION['carrito'][$productoId]['cantidad']--;
            if ($_SESSION['carrito'][$productoId]['cantidad'] == 0) {
                unset($_SESSION['carrito'][$productoId]);
            }
        }
        header('Location: '.BASE_URL.'carrito/obtenerCarrito/');
    }

    /**
     * Obtiene el total del carrito.
     *
     * Este método calcula el total de todos los productos en el carrito sumando el precio por la cantidad.
     *
     * @return float El total del carrito.
     */
    public function obtenerTotalCarrito() {
        $total = 0;
        if (isset($_SESSION['carrito'])) {
            foreach ($_SESSION['carrito'] as $producto) {
                $total += $producto['precio'] * $producto['cantidad'];
            }
        }
        return $total;
    }
}
?>
