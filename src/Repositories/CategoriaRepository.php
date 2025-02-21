<?php
namespace Repositories;

use Lib\BaseDatos;
use PDO;
use PDOException;

/**
 * Clase CategoriaRepository
 *
 * Esta clase proporciona métodos para interactuar con la tabla de categorías en la base de datos.
 * Permite realizar operaciones como obtener todas las categorías, obtener una categoría por ID,
 * guardar una nueva categoría, eliminar una categoría y actualizar los datos de una categoría.
 */
class CategoriaRepository {
    /**
     * @var BaseDatos $db La conexión a la base de datos.
     */
    private BaseDatos $db;

    /**
     * Constructor de CategoriaRepository.
     *
     * Inicializa una nueva instancia de la clase y establece la conexión a la base de datos.
     */
    public function __construct() {
        $this->db = new BaseDatos();
    }

    /**
     * Obtiene todas las categorías de la base de datos.
     *
     * Este método ejecuta una consulta SQL para obtener todas las categorías registradas en la base de datos.
     * 
     * @return array Un arreglo asociativo con todas las categorías.
     */
    public function getAll() {
        $this->db->consulta("SELECT * FROM categorias");
        $this->db->close();
        return $this->db->extraer_todos();
    }

    /**
     * Obtiene una categoría por su ID.
     *
     * Este método busca una categoría específica en la base de datos usando su ID.
     *
     * @param int $id El ID de la categoría a buscar.
     * @return array|null Un arreglo asociativo con los datos de la categoría, o null si no se encuentra.
     */
    public function getById($id) {
        $sql = "SELECT * FROM categorias WHERE id = :id";

        try {
            $stmt = $this->db->prepara($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $categoria = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $error) {
            $categoria = null;
        }

        $this->db->close();
        return $categoria;
    }

    /**
     * Guarda una nueva categoría en la base de datos.
     *
     * Este método inserta una nueva categoría en la base de datos con el nombre proporcionado.
     *
     * @param object $categoria El objeto categoría que contiene el nombre a guardar.
     * @return bool True si la categoría se guarda correctamente, false si ocurre un error.
     */
    public function save($categoria): bool {
        $nombre = $categoria->getNombre();

        try {
            $ins = $this->db->prepara("INSERT INTO categorias (id, nombre) values (null, :nombre)");
            $ins->bindValue(':nombre', $nombre);

            $ins->execute();

            $result = true;
        } catch (PDOException $error){
            $result = false;
        }

        $ins->closeCursor();
        $ins = null;

        return $result;
    }

    /**
     * Elimina una categoría por su ID y también elimina los productos asociados.
     *
     * Este método elimina una categoría de la base de datos usando su ID.
     *
     * @param int $id El ID de la categoría a eliminar.
     * @return bool True si la categoría se elimina correctamente, false si ocurre un error.
     */
    public function delete($id): bool {
        try {
            // Iniciar una transacción
            $this->db->beginTransaction();
    
            // Crear una categoría ficticia
            $ficticiaNombre = 'Categoría Ficticia';
            $insFicticia = $this->db->prepara("INSERT INTO categorias (id, nombre) values (null, :nombre)");
            $insFicticia->bindValue(':nombre', $ficticiaNombre);
            $insFicticia->execute();
            $ficticiaId = $this->db->lastInsertId();
            $insFicticia->closeCursor();
    
            // Actualizar productos asociados a la categoría eliminada para que apunten a la categoría ficticia
            $updProductos = $this->db->prepara("UPDATE productos SET categoria_id = :ficticiaId WHERE categoria_id = :id");
            $updProductos->bindValue(':ficticiaId', $ficticiaId);
            $updProductos->bindValue(':id', $id);
            $updProductos->execute();
            $updProductos->closeCursor();
    
            // Eliminar la categoría
            $delCategoria = $this->db->prepara("DELETE FROM categorias WHERE id = :id");
            $delCategoria->bindValue(':id', $id);
            $delCategoria->execute();
            $delCategoria->closeCursor();
    
            // Confirmar la transacción
            $this->db->commit();
    
            $result = true;
        } catch (PDOException $error) {
            // Revertir la transacción en caso de error
            $this->db->rollBack();
            $result = false;
        }
    
        $insFicticia = null;
        $updProductos = null;
        $delCategoria = null;
    
        return $result;
    }

    /**
     * Actualiza el nombre de una categoría.
     *
     * Este método actualiza el nombre de una categoría existente en la base de datos usando su ID.
     *
     * @param int $id El ID de la categoría a actualizar.
     * @param string $nombre El nuevo nombre de la categoría.
     * @return bool True si la categoría se actualiza correctamente, false si ocurre un error.
     */
    public function update($id, $nombre): bool {
        try {
            $upd = $this->db->prepara("UPDATE categorias SET nombre = :nombre WHERE id = :id");
            $upd->bindValue(':id', $id);
            $upd->bindValue(':nombre', $nombre);

            $upd->execute();

            $result = true;
        } catch (PDOException $error){
            $result = false;
        }

        $upd->closeCursor();
        $upd = null;

        return $result;
    }
}
