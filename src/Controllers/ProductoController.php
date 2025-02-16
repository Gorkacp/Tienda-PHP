<?php

namespace Controllers;
use Lib\Pages;
use Services\ProductoService;
use Repositories\ProductoRepository;
use Services\PedidoService;
use Repositories\PedidoRepository;

/**
 * Clase ProductoController
 *
 * Esta clase maneja la lógica para las acciones relacionadas con los productos.
 */
class ProductoController {
    private Pages $pages;
    private ProductoService $productoService;
    private PedidoService $pedidoService;

    /**
     * Constructor de ProductoController.
     *
     * Inicializa una nueva instancia de la clase ProductoController.
     */
    public function __construct() {
        $this->pages = new Pages();
        $this->productoService = new ProductoService(new ProductoRepository());
        $this->pedidoService = new PedidoService(new PedidoRepository());
    }

    /**
     * Obtiene productos de manera aleatoria.
     *
     * @return array Los productos obtenidos aleatoriamente.
     */
    public function getRandom() {
        $productos = $this->productoService->getRandom();
        return $productos;
    }

    /**
     * Obtiene todos los productos.
     *
     * @return array Los productos obtenidos.
     */
    public function gestionarProductos() {
        $productos = $this->productoService->getAll();
        $this->pages->render('producto/gestionarProductos', ['productos' => $productos]);
    }

    /**
     * Obtiene todos los productos (API).
     */
    public function getAll() {
        $productos = $this->productoService->getAll();
        echo json_encode($productos);
    }

    /**
     * Obtiene un producto por su ID (API).
     *
     * @param int $id ID del producto.
     */
    public function getById($id) {
        $producto = $this->productoService->getById($id);
        echo json_encode($producto);
    }

    /**
     * Crea un nuevo producto (API).
     */
    public function create() {
        $data = json_decode(file_get_contents("php://input"), true);
    
        if (is_null($data)) {
            echo json_encode(['success' => false, 'message' => 'Datos JSON inválidos']);
            return;
        }
    
        if (!isset($data['categoria_id'], $data['nombre'], $data['descripcion'], $data['precio'], $data['stock'], $data['imagen'])) {
            echo json_encode(['success' => false, 'message' => 'Faltan datos en la solicitud']);
            return;
        }
    
        $result = $this->productoService->save(
            $data['categoria_id'],
            $data['nombre'],
            $data['descripcion'],
            $data['precio'],
            $data['stock'],
            $data['imagen']
        );
    
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Producto creado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear el producto']);
        }
    }

    /**
     * Actualiza un producto existente (API).
     *
     * @param int $id ID del producto.
     */
    public function update($id) {
        $data = json_decode(file_get_contents("php://input"), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['success' => false, 'message' => 'Datos JSON inválidos']);
            return;
        }

        if (!isset($data['nombre'], $data['descripcion'], $data['precio'], $data['categoria_id'], $data['imagen'])) {
            echo json_encode(['success' => false, 'message' => 'Faltan datos en la solicitud']);
            return;
        }

        $result = $this->productoService->update(
            $id,
            $data['nombre'],
            $data['descripcion'],
            $data['precio'],
            $data['categoria_id'],
            $data['imagen']
        );

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Producto actualizado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el producto']);
        }
    }

    
    /**
     * Elimina un producto por su ID (API).
     *
     * @param int $id ID del producto.
     */
    public function delete($id) {
        $result = $this->productoService->delete($id);
    
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Producto eliminado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el producto']);
        }
    }

    /**
     * Crea un nuevo producto.
     */
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $precio = $_POST['precio'];
            $stock = $_POST['stock'];
            $oferta = $_POST['oferta'];
            $categoriaId = $_POST['categoria_id'];

            $imagen = null;
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
                $imagen = $_FILES['imagen'];
                $nombreImagen = uniqid() . '-' . $imagen['name'];
                $rutaImagen = __DIR__ . '/../../public/imagenes/' . $nombreImagen;

                if (move_uploaded_file($imagen['tmp_name'], $rutaImagen)) {
                    $imagen = $nombreImagen;
                } else {
                    // Manejar el error si la imagen no se pudo mover
                    $this->pages->render('producto/crear', ['error' => 'No se pudo guardar la imagen.']);
                    return;
                }
            }

            $this->productoService->save($categoriaId, $nombre, $descripcion, $precio, $stock, $imagen);

            header('Location: ' . BASE_URL . 'producto/gestionarProductos');
            exit(); // Asegúrate de detener el script después de la redirección
        } else {
            $this->pages->render('producto/crear');
        }
    }

    /**
     * Elimina un producto por su ID.
     *
     * @param int $id ID del producto.
     */
    public function borrar($id) {
        $this->productoService->delete($id);
    
        header('Location: ' . BASE_URL . 'producto/gestionarProductos');
        exit(); // Asegúrate de detener el script después de la redirección
    }

    /**
     * Edita un producto existente.
     */
    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $precio = $_POST['precio'];
            $categoriaId = $_POST['categoria_id'];
            $imagen = $_POST['imagen'];

            $this->productoService->update($id, $nombre, $descripcion, $precio, $categoriaId, $imagen);

            header('Location: ' . BASE_URL);
        } else {
            $id = $_GET['id'];
            $producto = $this->productoService->getById($id);
            $this->pages->render('producto/editar', ['producto' => $producto]);
        }
    }

    /**
     * Obtiene los detalles de un producto.
     *
     * @param int $id ID del producto.
     * @return array Los detalles del producto.
     */
    public function verDetalles($id) {
        $producto = $this->productoService->getById($id);
        $this->pages->render('producto/verDetalles', ['producto' => $producto]);
    }
}