<?php
namespace Repositories;

use Lib\BaseDatos;
use Lib\Pages;
use PDO;
use PDOException;

class PedidoRepository {
    private BaseDatos $db;
    private Pages $pages;

    /**
     * Constructor de la clase PedidoRepository.
     * Inicializa la conexión con la base de datos y las páginas.
     */
    public function __construct() {
        $this->db = new BaseDatos();
        $this->pages = new Pages();
    }

    /**
     * Obtiene todos los pedidos ordenados por ID descendente.
     *
     * @return array Array de pedidos.
     */
    public function getAll() {
        $sql = "SELECT * FROM pedidos ORDER BY id DESC";
        $this->db->consulta($sql);
        $this->db->close();
        return $this->db->extraer_todos();
    }

    /**
     * Obtiene un pedido por su ID.
     *
     * @param int $id El ID del pedido.
     * @return array|null Array con los detalles del pedido o null en caso de error.
     */
    public function getById($id) {
        $query = "SELECT pedidos.*, usuarios.email, usuarios.nombre 
                  FROM pedidos 
                  JOIN usuarios ON pedidos.usuario_id = usuarios.id 
                  WHERE pedidos.id = :id";
        $stmt = $this->db->prepara($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene todos los pedidos de un usuario específico.
     *
     * @param int $usuarioId El ID del usuario.
     * @return array Array de pedidos del usuario.
     */
    public function getByUsuario($usuarioId) {
        $sql = "SELECT * FROM pedidos WHERE usuario_id = :usuarioId ORDER BY id DESC";
        $stmt = $this->db->prepara($sql);
        $stmt->bindParam(':usuarioId', $usuarioId, PDO::PARAM_INT);
        $stmt->execute();
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->db->close();
        return $pedidos;
    }

    /**
     * Elimina un pedido y sus líneas asociadas.
     *
     * @param int $id El ID del pedido.
     * @return bool True si se eliminó correctamente, false en caso contrario.
     */
    public function delete($id) {
        $sql = "DELETE FROM lineas_pedidos WHERE pedido_id = :id";
        $stmt = $this->db->prepara($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        $sql = "DELETE FROM pedidos WHERE id = :id";
        $stmt = $this->db->prepara($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $delete = $stmt->execute();
        $this->db->close();
        return $delete;
    }

    /**
     * Actualiza los detalles de un pedido.
     *
     * @param int $id El ID del pedido a actualizar.
     * @param string $fecha La nueva fecha del pedido.
     * @param string $hora La nueva hora del pedido.
     * @param string $coste El nuevo coste del pedido.
     * @param string $estado El nuevo estado del pedido.
     * @param int $usuario_id El nuevo ID del usuario asociado.
     * @return bool True si la actualización fue exitosa, false en caso contrario.
     */
    public function editar($id, $fecha, $hora, $coste, $estado, $usuario_id) {
        $sql = "UPDATE pedidos SET fecha = :fecha, hora = :hora ,coste = :coste, estado = :estado, usuario_id = :usuario_id WHERE id = :id";
        $stmt = $this->db->prepara($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $stmt->bindParam(':hora', $hora, PDO::PARAM_STR);
        $stmt->bindParam(':coste', $coste, PDO::PARAM_STR);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $update = $stmt->execute();
        $this->db->close();
        return $update;
    }

    /**
     * Obtiene los productos asociados a un pedido.
     *
     * @param int $pedidoId El ID del pedido.
     * @return array Array de productos del pedido.
     */
    public function getProductosPedido($pedidoId) {
        $sql = "SELECT p.* FROM productos p
                INNER JOIN lineas_pedidos lp ON p.id = lp.producto_id
                WHERE lp.pedido_id = :pedidoId";
        $stmt = $this->db->prepara($sql);
        $stmt->bindParam(':pedidoId', $pedidoId, PDO::PARAM_INT);
        $stmt->execute();
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->db->close();
        return $productos;
    }

    /**
     * Obtiene la cantidad de un producto en un pedido específico.
     *
     * @param int $pedidoId El ID del pedido.
     * @param int $productoId El ID del producto.
     * @return int La cantidad de ese producto en el pedido.
     */
    public function getCantidadProducto($pedidoId, $productoId) {
        $sql = "SELECT cantidad FROM lineas_pedidos WHERE pedido_id = :pedidoId AND producto_id = :productoId";
        $stmt = $this->db->prepara($sql);
        $stmt->bindParam(':pedidoId', $pedidoId, PDO::PARAM_INT);
        $stmt->bindParam(':productoId', $productoId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->db->close();
        return $result ? $result['cantidad'] : 0;
    }

    /**
     * Calcula el total del pedido a partir del carrito de compras.
     *
     * @param array $carrito Array con los productos del carrito.
     * @return float El total calculado.
     */
    public function calcularTotal($carrito) {
        $total = 0;
        foreach ($carrito as $elemento) {
            $total += $elemento['precio'] * $elemento['cantidad'];
        }
        return $total;
    }

    /**
     * Guarda un nuevo pedido en la base de datos.
     *
     * @param int $usuarioId El ID del usuario asociado al pedido.
     * @param string $provincia La provincia del pedido.
     * @param string $localidad La localidad del pedido.
     * @param string $direccion La dirección de envío.
     * @param float $coste El coste total del pedido.
     * @param string $estado El estado del pedido.
     * @param string $fecha La fecha del pedido.
     * @param string $hora La hora del pedido.
     * @param array $carrito Array con los productos del carrito.
     * @return int El ID del nuevo pedido.
     */
    public function save($usuarioId, $provincia, $localidad, $direccion, $coste, $estado, $fecha, $hora, $carrito) {
        try {
            $this->db->beginTransaction();

            // Insertar el pedido
            $pedidoId = $this->insertPedido($usuarioId, $provincia, $localidad, $direccion, $coste, $estado, $fecha, $hora);

            // Insertar las líneas del pedido
            $this->insertLineasPedido($pedidoId, $carrito);

            $this->db->commit();
            return $pedidoId;
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Inserta un nuevo pedido en la base de datos.
     *
     * @param int $usuarioId El ID del usuario asociado al pedido.
     * @param string $provincia La provincia del pedido.
     * @param string $localidad La localidad del pedido.
     * @param string $direccion La dirección de envío.
     * @param float $coste El coste total del pedido.
     * @param string $estado El estado del pedido.
     * @param string $fecha La fecha del pedido.
     * @param string $hora La hora del pedido.
     * @return int El ID del nuevo pedido.
     */
    private function insertPedido($usuarioId, $provincia, $localidad, $direccion, $coste, $estado, $fecha, $hora) {
        $sqlPedido = "INSERT INTO pedidos (usuario_id, provincia, localidad, direccion, coste, estado, fecha, hora) VALUES (:usuarioId, :provincia, :localidad, :direccion, :coste, :estado, :fecha, :hora)";
        $stmtPedido = $this->db->prepara($sqlPedido);
        $stmtPedido->bindParam(':usuarioId', $usuarioId, PDO::PARAM_INT);
        $stmtPedido->bindParam(':provincia', $provincia, PDO::PARAM_STR);
        $stmtPedido->bindParam(':localidad', $localidad, PDO::PARAM_STR);
        $stmtPedido->bindParam(':direccion', $direccion, PDO::PARAM_STR);
        $stmtPedido->bindParam(':coste', $coste, PDO::PARAM_STR);
        $stmtPedido->bindParam(':estado', $estado, PDO::PARAM_STR);
        $stmtPedido->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $stmtPedido->bindParam(':hora', $hora, PDO::PARAM_STR);
        $stmtPedido->execute();
    
        // Obtener el ID del nuevo pedido
        return $this->db->lastInsertId();
    }

    /**
     * Inserta las líneas del pedido en la base de datos.
     *
     * @param int $pedidoId El ID del pedido.
     * @param array $carrito Array con los productos del carrito.
     */
    private function insertLineasPedido($pedidoId, $carrito) {
        foreach ($carrito as $producto) {
            $productoId = $producto['id'];
            $cantidad = $producto['cantidad'];

            $sqlLineaPedido = "INSERT INTO lineas_pedidos (pedido_id, producto_id, unidades) VALUES (:pedidoId, :productoId, :unidades)";
            $stmtLineaPedido = $this->db->prepara($sqlLineaPedido);
            $stmtLineaPedido->bindParam(':pedidoId', $pedidoId, PDO::PARAM_INT);
            $stmtLineaPedido->bindParam(':productoId', $productoId, PDO::PARAM_INT);
            $stmtLineaPedido->bindParam(':unidades', $cantidad, PDO::PARAM_INT);
            $stmtLineaPedido->execute();
        }
    }

    public function confirmarPedido($pedidoId) {
        $sql = "UPDATE pedidos SET estado = 'confirmado' WHERE id = :id";
        $stmt = $this->db->prepara($sql);
        $stmt->bindParam(':id', $pedidoId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>