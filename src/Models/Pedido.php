<?php

namespace Models;
use Lib\BaseDatos;
use PDO;

/**
 * Clase Pedido
 *
 * Representa un pedido realizado en el sistema. Esta clase contiene información
 * sobre el pedido, como el usuario que lo realizó, la dirección de entrega, 
 * el coste total, el estado del pedido y la fecha y hora en la que se realizó.
 */
class Pedido {
    /**
     * @var int $id El identificador único del pedido.
     */
    private $id;

    /**
     * @var int $usuario_id El ID del usuario que realizó el pedido.
     */
    private $usuario_id;

    /**
     * @var string $provincia La provincia donde se entregará el pedido.
     */
    private $provincia;

    /**
     * @var string $localidad La localidad donde se entregará el pedido.
     */
    private $localidad;

    /**
     * @var string $direccion La dirección donde se entregará el pedido.
     */
    private $direccion;

    /**
     * @var float $coste El coste total del pedido.
     */
    private $coste;

    /**
     * @var string $estado El estado actual del pedido (por ejemplo, pendiente, enviado, etc.).
     */
    private $estado;

    /**
     * @var string $fecha La fecha en que se realizó el pedido.
     */
    private $fecha;

    /**
     * @var string $hora La hora en que se realizó el pedido.
     */
    private $hora;
    
    /**
     * @var BaseDatos $db Instancia para interactuar con la base de datos.
     */
    private $db;
    
    /**
     * Constructor de Pedido.
     *
     * Inicializa una nueva instancia de la clase Pedido y establece la conexión
     * a la base de datos.
     */
    public function __construct() {
        $this->db = new BaseDatos();
    }

    /**
     * Obtiene el ID del pedido.
     *
     * @return int El ID del pedido.
     */
    function getId() {
        return $this->id;
    }

    /**
     * Obtiene el ID del usuario que realizó el pedido.
     *
     * @return int El ID del usuario.
     */
    function getUsuario_id() {
        return $this->usuario_id;
    }

    /**
     * Obtiene la provincia donde se entregará el pedido.
     *
     * @return string La provincia.
     */
    function getProvincia() {
        return $this->provincia;
    }

    /**
     * Obtiene la localidad donde se entregará el pedido.
     *
     * @return string La localidad.
     */
    function getLocalidad() {
        return $this->localidad;
    }

    /**
     * Obtiene la dirección donde se entregará el pedido.
     *
     * @return string La dirección.
     */
    function getDireccion() {
        return $this->direccion;
    }

    /**
     * Obtiene el coste total del pedido.
     *
     * @return float El coste del pedido.
     */
    function getCoste() {
        return $this->coste;
    }

    /**
     * Obtiene el estado actual del pedido.
     *
     * @return string El estado del pedido.
     */
    function getEstado() {
        return $this->estado;
    }

    /**
     * Obtiene la fecha en que se realizó el pedido.
     *
     * @return string La fecha del pedido.
     */
    function getFecha() {
        return $this->fecha;
    }

    /**
     * Obtiene la hora en que se realizó el pedido.
     *
     * @return string La hora del pedido.
     */
    function getHora() {
        return $this->hora;
    }

    /**
     * Establece el ID del pedido.
     *
     * @param int $id El nuevo ID del pedido.
     */
    function setId($id) {
        $this->id = $id;
    }

    /**
     * Establece el ID del usuario que realizó el pedido.
     *
     * @param int $usuario_id El nuevo ID del usuario.
     */
    function setUsuario_id($usuario_id) {
        $this->usuario_id = $usuario_id;
    }

    /**
     * Establece la provincia de entrega del pedido.
     *
     * @param string $provincia La nueva provincia de entrega.
     */
    function setProvincia($provincia) {
        $this->provincia = $provincia;
    }

    /**
     * Establece la localidad de entrega del pedido.
     *
     * @param string $localidad La nueva localidad de entrega.
     */
    function setLocalidad($localidad) {
        $this->localidad = $localidad;
    }

    /**
     * Establece la dirección de entrega del pedido.
     *
     * @param string $direccion La nueva dirección de entrega.
     */
    function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    /**
     * Establece el coste total del pedido.
     *
     * @param float $coste El nuevo coste del pedido.
     */
    function setCoste($coste) {
        $this->coste = $coste;
    }

    /**
     * Establece el estado del pedido.
     *
     * @param string $estado El nuevo estado del pedido.
     */
    function setEstado($estado) {
        $this->estado = $estado;
    }

    /**
     * Establece la fecha en la que se realizó el pedido.
     *
     * @param string $fecha La nueva fecha del pedido.
     */
    function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    /**
     * Establece la hora en la que se realizó el pedido.
     *
     * @param string $hora La nueva hora del pedido.
     */
    function setHora($hora) {
        $this->hora = $hora;
    }
}
