<?php
namespace Services;
use Repositories\CategoriaRepository;

/**
 * Clase CategoriaService
 * 
 * Proporciona servicios para la gestión de categorías en el sistema.
 * 
 * @package Services
 */
class CategoriaService {
    private $categoriaRepository;

    /**
     * Constructor de la clase CategoriaService.
     *
     * @param CategoriaRepository $categoriaRepository Repositorio de categorías.
     */
    public function __construct(CategoriaRepository $categoriaRepository) {
        $this->categoriaRepository = $categoriaRepository;
    }

    /**
     * Obtiene todas las categorías.
     *
     * @return array Lista de categorías.
     */
    public function getAll() {
        return $this->categoriaRepository->getAll();
    }

    /**
     * Obtiene una categoría por su ID.
     *
     * @param int $id ID de la categoría.
     * @return mixed Datos de la categoría o null si no se encuentra.
     */
    public function getById($id) {
        return $this->categoriaRepository->getById($id);
    }

    /**
     * Guarda una nueva categoría.
     *
     * @param string $nombre Nombre de la categoría.
     * @return bool True si se guardó correctamente, false en caso contrario.
     */
    public function save($nombre) {
        return $this->categoriaRepository->save($nombre);
    }

    /**
     * Elimina una categoría por su ID.
     *
     * @param int $id ID de la categoría a eliminar.
     * @return bool True si se eliminó correctamente, false en caso contrario.
     */
    public function delete($id) {
        return $this->categoriaRepository->delete($id);
    }

    /**
     * Edita una categoría existente.
     *
     * @param int $id ID de la categoría.
     * @param string $nombre Nuevo nombre de la categoría.
     * @return bool True si se actualizó correctamente, false en caso contrario.
     */
    public function editar($id, $nombre) {
        return $this->categoriaRepository->update($id, $nombre);
    }

    /**
     * Actualiza una categoría existente (alias de editar).
     *
     * @param int $id ID de la categoría.
     * @param string $nombre Nuevo nombre de la categoría.
     * @return bool True si se actualizó correctamente, false en caso contrario.
     */
    public function update($id, $nombre) {
        return $this->categoriaRepository->update($id, $nombre);
    }
}
