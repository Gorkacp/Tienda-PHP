<?php
namespace Repositories;

use Lib\BaseDatos;
use PDO;

/**
 * Clase ProductoRepository
 *
 * Esta clase maneja todas las interacciones con la tabla 'productos' en la base de datos.
 * Incluye operaciones CRUD (crear, leer, actualizar y eliminar) para los productos.
 */
class ProductoRepository {
    private BaseDatos $db;

    /**
     * Constructor de ProductoRepository.
     * Inicializa la conexión a la base de datos.
     */
    public function __construct() {
        $this->db = new BaseDatos();
    }

    /**
     * Obtiene todos los productos de la base de datos.
     *
     * @return array Lista de todos los productos.
     */
    public function getAll() {
        $sql = "SELECT * FROM productos";
        $this->db->consulta($sql);
        $this->db->close();
        return $this->db->extraer_todos();
    }

    /**
     * Obtiene 5 productos aleatorios de la base de datos.
     *
     * @return array Lista de 5 productos aleatorios.
     */
    public function getRandom() {
        $sql = "SELECT * FROM productos ORDER BY RAND() LIMIT 5";
        $this->db->consulta($sql);
        $this->db->close();
        return $this->db->extraer_todos();
    }

    /**
     * Obtiene los productos de una categoría específica.
     *
     * @param int $categoriaId ID de la categoría de los productos a obtener.
     * @return array Lista de productos pertenecientes a la categoría.
     */
    public function getByCategoria($categoriaId) {
        $sql = "SELECT * FROM productos WHERE categoria_id = :categoriaId";
        $stmt = $this->db->prepara($sql);
        $stmt->bindParam(':categoriaId', $categoriaId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->db->close();
        return $result;
    }

    /**
     * Obtiene un producto por su ID.
     *
     * @param int $id ID del producto a obtener.
     * @return array Producto con el ID proporcionado.
     */
    public function getById($id) {
        $sql = "SELECT * FROM productos WHERE id = :id";
        $stmt = $this->db->prepara($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->db->close();
        return $producto;
    }

    /**
     * Guarda un nuevo producto en la base de datos.
     *
     * @param int $categoriaId ID de la categoría del producto.
     * @param string $nombre Nombre del producto.
     * @param string $descripcion Descripción del producto.
     * @param float $precio Precio del producto.
     * @param int $stock Cantidad de stock disponible.
     * @param string $imagen Ruta de la imagen del producto.
     * @return bool Retorna true si el producto se guardó correctamente, false si hubo un error.
     */
    public function save($categoriaId, $nombre, $descripcion, $precio, $stock, $imagen) {
        $sql = "INSERT INTO productos (categoria_id, nombre, descripcion, precio, stock, imagen) 
                VALUES (:categoriaId, :nombre, :descripcion, :precio, :stock, :imagen)";
    
        $stmt = $this->db->prepara($sql);
        $stmt->bindParam(':categoriaId', $categoriaId, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
        $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
        $stmt->bindParam(':imagen', $imagen, PDO::PARAM_STR);
    
        $save = $stmt->execute();
        $this->db->close();
    
        return $save;
    }

    /**
     * Elimina un producto de la base de datos por su ID.
     *
     * @param int $productId ID del producto a eliminar.
     */
    public function delete($productId) {
        $sql = "DELETE FROM productos WHERE id = :id";
        $stmt = $this->db->prepara($sql);
        $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        $this->db->close();
    }

    /**
     * Elimina todos los productos asociados a una categoría.
     *
     * @param int $categoriaId ID de la categoría.
     * @return bool True si se eliminaron correctamente, false en caso contrario.
     */
    public function deleteByCategoriaId($categoriaId) {
        // Implementa la lógica para eliminar productos por categoría
        // Ejemplo de consulta SQL:
        // DELETE FROM productos WHERE categoria_id = :categoriaId
    }

    /**
     * Actualiza los datos de un producto en la base de datos.
     *
     * @param int $productId ID del producto a actualizar.
     * @param string $nombre Nuevo nombre del producto.
     * @param string $descripcion Nueva descripción del producto.
     * @param float $precio Nuevo precio del producto.
     * @param int $categoriaId ID de la categoría del producto.
     * @param string $imagen Nueva ruta de la imagen del producto.
     */
    public function update($productId, $nombre, $descripcion, $precio, $categoriaId, $imagen) {
        $sql = "UPDATE productos SET nombre = :nombre, descripcion = :descripcion, precio = :precio, categoria_id = :categoriaId, imagen = :imagen WHERE id = :id";
        $stmt = $this->db->prepara($sql);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
        $stmt->bindParam(':categoriaId', $categoriaId, PDO::PARAM_INT);
        $stmt->bindParam(':imagen', $imagen, PDO::PARAM_STR);
        $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        $this->db->close();
    }
}
?>
