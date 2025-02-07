<?php
namespace Services;
use Repositories\PedidoRepository;

/**
 * Clase PedidoService
 * 
 * Esta clase proporciona servicios relacionados con los pedidos, utilizando un repositorio de pedidos.
 * Permite realizar operaciones como obtener pedidos, eliminar, editar, guardar pedidos y obtener productos de un pedido.
 * 
 * @package Services
 */
class PedidoService {
    
    /**
     * @var PedidoRepository $pedidoRepository Instancia del repositorio de pedidos.
     */
    private $pedidoRepository;

    /**
     * Constructor de la clase PedidoService.
     * 
     * @param PedidoRepository $pedidoRepository Instancia del repositorio de pedidos.
     */
    public function __construct(PedidoRepository $pedidoRepository) {
        $this->pedidoRepository = $pedidoRepository;
    }

    /**
     * Obtiene todos los pedidos.
     * 
     * @return array Lista de todos los pedidos.
     */
    public function getAll() {
        return $this->pedidoRepository->getAll();
    }

    /**
     * Obtiene un pedido por su ID.
     * 
     * @param int $id El ID del pedido.
     * 
     * @return mixed El pedido encontrado o null si no existe.
     */
    public function getById($id) {
        return $this->pedidoRepository->getById($id);
    }

    /**
     * Obtiene los pedidos de un usuario específico.
     * 
     * @param int $usuarioId El ID del usuario.
     * 
     * @return array Lista de pedidos del usuario.
     */
    public function getByUsuario($usuarioId) {
        return $this->pedidoRepository->getByUsuario($usuarioId);
    }

    /**
     * Elimina un pedido por su ID.
     * 
     * @param int $id El ID del pedido.
     * 
     * @return bool Retorna verdadero si el pedido fue eliminado correctamente, falso en caso contrario.
     */
    public function delete($id) {
        return $this->pedidoRepository->delete($id);
    }

    /**
     * Edita un pedido.
     * 
     * @param int $id El ID del pedido.
     * @param string $fecha La nueva fecha del pedido.
     * @param string $hora La nueva hora del pedido.
     * @param float $coste El nuevo coste del pedido.
     * @param string $estado El nuevo estado del pedido.
     * @param int $usuario_id El ID del usuario asociado al pedido.
     * 
     * @return bool Retorna verdadero si la edición fue exitosa, falso en caso contrario.
     */
    public function editar($id, $fecha, $hora, $coste, $estado, $usuario_id) {
        return $this->pedidoRepository->editar($id, $fecha, $hora, $coste, $estado, $usuario_id);
    }

    /**
     * Guarda un nuevo pedido.
     * 
     * @param int $usuarioId El ID del usuario que realiza el pedido.
     * @param string $provincia La provincia del destino del pedido.
     * @param string $localidad La localidad del destino del pedido.
     * @param string $direccion La dirección completa del destino.
     * @param float $coste El coste total del pedido.
     * @param string $estado El estado del pedido.
     * @param string $fecha La fecha en que se realizó el pedido.
     * @param string $hora La hora en que se realizó el pedido.
     * @param array $carrito Los productos añadidos al carrito de compras.
     * 
     * @return mixed El ID del nuevo pedido o falso en caso de error.
     */
    public function save($usuarioId, $provincia, $localidad, $direccion, $coste, $estado, $fecha, $hora, $carrito) {
        return $this->pedidoRepository->save($usuarioId, $provincia, $localidad, $direccion, $coste, $estado, $fecha, $hora, $carrito);
    }

    /**
     * Obtiene los productos asociados a un pedido.
     * 
     * @param int $pedidoId El ID del pedido.
     * 
     * @return array Lista de productos asociados al pedido.
     */
    public function getProductosPedido($pedidoId) {
        return $this->pedidoRepository->getProductosPedido($pedidoId);
    }

    /**
     * Calcula el coste total del carrito de compras.
     * 
     * @param array $carrito El carrito de compras con los productos.
     * 
     * @return float El coste total del carrito.
     */
    public function getTotalCarrito($carrito) {
        return $this->pedidoRepository->calcularTotal($carrito);
    }

    /**
     * Obtiene la cantidad de un producto en un pedido.
     * 
     * @param int $pedidoId El ID del pedido.
     * @param int $productoId El ID del producto.
     * 
     * @return int La cantidad del producto en el pedido.
     */
    public function getCantidadProducto($pedidoId, $productoId) {
        return $this->pedidoRepository->getCantidadProducto($pedidoId, $productoId);
    }

    /**
     * Confirma un pedido por su ID.
     * 
     * @param int $pedidoId El ID del pedido.
     * 
     * @return bool Retorna verdadero si el pedido fue confirmado correctamente, falso en caso contrario.
     */
    public function confirmarPedido($pedidoId) {
        return $this->pedidoRepository->confirmarPedido($pedidoId);
    }
}
