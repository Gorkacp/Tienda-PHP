<?php

namespace Models;
use Lib\BaseDatos;

/**
 * Clase Categoria
 *
 * Representa una categoría en el sistema, con propiedades como el ID y el nombre.
 * Esta clase interactúa con la base de datos para almacenar y recuperar información relacionada con las categorías.
 */
class Categoria
{
    /**
     * @var int $id El identificador único de la categoría.
     */
    private $id;

    /**
     * @var string $nombre El nombre de la categoría.
     */
    private $nombre;

    /**
     * @var BaseDatos $db Instancia de la clase BaseDatos para interactuar con la base de datos.
     */
    private BaseDatos $db;

    /**
     * Constructor de la clase Categoria.
     *
     * Inicializa una nueva instancia de la clase Categoria y establece la conexión a la base de datos.
     */
    public function __construct()
    {
        $this->db = new BaseDatos();
    }

    /**
     * Obtiene el ID de la categoría.
     *
     * Este método devuelve el identificador único de la categoría.
     *
     * @return int El ID de la categoría.
     */
    public function getId(){
        return $this->id;
    }

    /**
     * Establece el ID de la categoría.
     *
     * Este método permite asignar un nuevo ID a la categoría.
     *
     * @param int $id El nuevo ID para la categoría.
     * @return void
     */
    public function setId($id): void{
        $this->id = $id;
    }

    /**
     * Obtiene el nombre de la categoría.
     *
     * Este método devuelve el nombre de la categoría.
     *
     * @return string El nombre de la categoría.
     */
    public function getNombre(){
        return $this->nombre;
    }

    /**
     * Establece el nombre de la categoría.
     *
     * Este método permite asignar un nuevo nombre a la categoría.
     *
     * @param string $nombre El nuevo nombre de la categoría.
     * @return void
     */
    public function setNombre($nombre): void{
        $this->nombre = $nombre;
    }
}
