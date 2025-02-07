<?php

namespace Models;
use Lib\BaseDatos;

/**
 * Clase Producto
 *
 * Esta clase representa un producto en el sistema. Un producto tiene información
 * relacionada como su ID, nombre, descripción, precio, stock disponible, categoría
 * a la que pertenece, oferta aplicada e imagen.
 */
class Producto {
    /**
     * @var int $id El ID único del producto.
     */
    private $id;

    /**
     * @var string $nombre El nombre del producto.
     */
    private $nombre;

    /**
     * @var string $descripcion La descripción detallada del producto.
     */
    private $descripcion;

    /**
     * @var float $precio El precio del producto.
     */
    private $precio;

    /**
     * @var int $categoria_id El ID de la categoría a la que pertenece el producto.
     */
    private $categoria_id;

    /**
     * @var int $stock La cantidad de stock disponible para el producto.
     */
    private $stock;

    /**
     * @var string $oferta La oferta que se aplica al producto.
     */
    private $oferta;

    /**
     * @var string $imagen La imagen asociada al producto.
     */
    private $imagen;

    /**
     * @var BaseDatos $db Instancia para interactuar con la base de datos.
     */
    private $db;

    /**
     * Constructor de Producto.
     *
     * Inicializa una nueva instancia de la clase Producto con los parámetros
     * proporcionados. Si algún parámetro es nulo, se asignará el valor por defecto
     * a la propiedad correspondiente.
     *
     * @param int|null $id El ID del producto.
     * @param string|null $nombre El nombre del producto.
     * @param string|null $descripcion La descripción del producto.
     * @param float|null $precio El precio del producto.
     * @param int|null $stock La cantidad de stock del producto.
     * @param string|null $oferta La oferta aplicada al producto.
     * @param int|null $categoria_id El ID de la categoría del producto.
     * @param string|null $imagen La imagen del producto.
     */
    public function __construct($id = null, $nombre = null, $descripcion = null, $precio = null, $stock = null, $oferta = null, $categoria_id = null, $imagen = null) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->stock = $stock;
        $this->oferta = $oferta;
        $this->categoria_id = $categoria_id;
        $this->imagen = $imagen;
        $this->db = new BaseDatos();
    }

    // Métodos Getter y Setter

    /**
     * Obtiene el ID del producto.
     *
     * @return int El ID del producto.
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Establece el ID del producto.
     *
     * @param int $id El nuevo ID del producto.
     */
    public function setId($id): void {
        $this->id = $id;
    }

    /**
     * Obtiene el nombre del producto.
     *
     * @return string El nombre del producto.
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Establece el nombre del producto.
     *
     * @param string $nombre El nuevo nombre del producto.
     */
    public function setNombre($nombre): void {
        $this->nombre = $nombre;
    }

    /**
     * Obtiene la descripción del producto.
     *
     * @return string La descripción del producto.
     */
    public function getDescripcion() {
        return $this->descripcion;
    }

    /**
     * Establece la descripción del producto.
     *
     * @param string $descripcion La nueva descripción del producto.
     */
    public function setDescripcion($descripcion): void {
        $this->descripcion = $descripcion;
    }

    /**
     * Obtiene el precio del producto.
     *
     * @return float El precio del producto.
     */
    public function getPrecio() {
        return $this->precio;
    }

    /**
     * Establece el precio del producto.
     *
     * @param float $precio El nuevo precio del producto.
     */
    public function setPrecio($precio): void {
        $this->precio = $precio;
    }

    /**
     * Obtiene la cantidad de stock disponible del producto.
     *
     * @return int La cantidad de stock disponible.
     */
    public function getStock() {
        return $this->stock;
    }

    /**
     * Establece la cantidad de stock disponible del producto.
     *
     * @param int $stock La nueva cantidad de stock disponible.
     */
    public function setStock($stock): void {
        $this->stock = $stock;
    }

    /**
     * Obtiene la oferta aplicada al producto.
     *
     * @return string La oferta aplicada al producto.
     */
    public function getOferta() {
        return $this->oferta;
    }

    /**
     * Establece la oferta aplicada al producto.
     *
     * @param string $oferta La nueva oferta del producto.
     */
    public function setOferta($oferta): void {
        $this->oferta = $oferta;
    }

    /**
     * Obtiene el ID de la categoría del producto.
     *
     * @return int El ID de la categoría del producto.
     */
    public function getCategoriaId() {
        return $this->categoria_id;
    }

    /**
     * Establece el ID de la categoría del producto.
     *
     * @param int $categoria_id El nuevo ID de la categoría del producto.
     */
    public function setCategoriaId($categoria_id): void {
        $this->categoria_id = $categoria_id;
    }

    /**
     * Obtiene la imagen asociada al producto.
     *
     * @return string La imagen del producto.
     */
    public function getImagen() {
        return $this->imagen;
    }

    /**
     * Establece la imagen asociada al producto.
     *
     * @param string $imagen La nueva imagen del producto.
     */
    public function setImagen($imagen): void {
        $this->imagen = $imagen;
    }
}
