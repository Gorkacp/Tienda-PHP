<?php
namespace Services;

use Repositories\ProductoRepository;

/**
 * Clase ProductoService
 * 
 * Servicio para manejar la lógica de negocio relacionada con los productos.
 */
class ProductoService {
    /**
     * @var ProductoRepository Repositorio de productos
     */
    private $productoRepository;

    /**
     * Constructor de ProductoService
     * 
     * @param ProductoRepository $productoRepository Instancia del repositorio de productos
     */
    public function __construct(ProductoRepository $productoRepository) {
        $this->productoRepository = $productoRepository;
    }

    /**
     * Obtiene todos los productos
     * 
     * @return array Lista de productos
     */
    public function getAll() {
        return $this->productoRepository->getAll();
    }

    /**
     * Obtiene un conjunto de productos aleatorios
     * 
     * @return array Lista de productos aleatorios
     */
    public function getRandom() {
        return $this->productoRepository->getRandom();
    }

    /**
     * Obtiene productos por categoría
     * 
     * @param int $categoriaId ID de la categoría
     * @return array Lista de productos de la categoría especificada
     */
    public function getByCategoria($categoriaId) {
        return $this->productoRepository->getByCategoria($categoriaId);
    }

    /**
     * Obtiene un producto por su ID
     * 
     * @param int $id ID del producto
     * @return array|null Datos del producto o null si no se encuentra
     */
    public function getById($id) {
        return $this->productoRepository->getById($id);
    }

    /**
     * Guarda un nuevo producto en la base de datos
     * 
     * @param int $categoriaId ID de la categoría del producto
     * @param string $nombre Nombre del producto
     * @param string $descripcion Descripción del producto
     * @param float $precio Precio del producto
     * @param int $stock Cantidad disponible
     * @param string|null $imagen URL o nombre de la imagen del producto
     * @return bool True si se guardó correctamente, false en caso contrario
     */
    public function save($categoriaId, $nombre, $descripcion, $precio, $stock, $imagen) {
        return $this->productoRepository->save($categoriaId, $nombre, $descripcion, $precio, $stock, $imagen);
    }

    /**
     * Elimina un producto de la base de datos
     * 
     * @param int $productId ID del producto a eliminar
     * @return bool True si se eliminó correctamente, false en caso contrario
     */
    public function delete($productId) {
        return $this->productoRepository->delete($productId);
    }

    /**
     * Actualiza los datos de un producto
     * 
     * @param int $productId ID del producto
     * @param string $nombre Nombre del producto
     * @param string $descripcion Descripción del producto
     * @param float $precio Precio del producto
     * @param int $categoriaId ID de la categoría
     * @param string|null $imagen URL o nombre de la imagen del producto
     * @return bool True si se actualizó correctamente, false en caso contrario
     */
    public function update($productId, $nombre, $descripcion, $precio, $categoriaId, $imagen) {
        return $this->productoRepository->update($productId, $nombre, $descripcion, $precio, $categoriaId, $imagen);
    }
}
