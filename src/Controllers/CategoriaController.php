<?php

namespace Controllers;

use Lib\Pages;
use Utils\Utils;
use Models\Categoria;
use Services\ProductoService;
use Repositories\ProductoRepository;
use Services\CategoriaService;
use Repositories\CategoriaRepository;

/**
 * Clase CategoriaController
 *
 * Esta clase maneja la lógica para las acciones relacionadas con las categorías,
 * incluyendo la visualización, gestión, creación, edición y eliminación de categorías.
 */
class CategoriaController{
    private Pages $pages;
    private ProductoService $productoService;
    private CategoriaService $categoriaService;

    /**
     * Constructor de CategoriaController.
     *
     * Inicializa las instancias de Pages, ProductoService y CategoriaService.
     */
    public function __construct() {
        $this->pages = new Pages();
        $this->productoService = new ProductoService(new ProductoRepository());
        $this->categoriaService = new CategoriaService(new CategoriaRepository());
    }

    /**
     * Obtiene todas las categorías.
     *
     * Este método recupera todas las categorías disponibles en el sistema.
     *
     * @return array Las categorías obtenidas.
     */
    public function obtenerCategorias() {
        return $this->categoriaService->getAll();
    }

    /**
     * Obtiene los productos de una categoría específica.
     *
     * Este método obtiene todos los productos asociados a una categoría por su ID
     * y los pasa a la vista correspondiente.
     *
     * @param int $id El ID de la categoría.
     * 
     * @return void
     */
    public function ver($id) {
        $categoriaId = $id;
        $productos = $this->productoService->getByCategoria($categoriaId);
        $this->pages->render('categoria/ver', ['productos' => $productos]);
    }

    /**
     * Muestra la interfaz para gestionar las categorías.
     *
     * Este método recupera todas las categorías y las muestra en la página de gestión de categorías.
     *
     * @return void
     */
    public function gestionarCategorias() {
        $categorias = $this->categoriaService->getAll();
        $this->pages->render('categoria/gestionarCategorias', ['categorias' => $categorias]);
    }

    /**
     * Crea una nueva categoría.
     *
     * Este método crea una nueva categoría a partir de los datos enviados por POST y redirige a la página principal.
     * Si no se envía ningún dato, muestra el formulario para crear una categoría.
     *
     * @return void
     */
    public function crear() {
        if (isset($_POST['nombre'])) {
            $categoria = new Categoria();
            $categoria->setNombre($_POST['nombre']);
            $this->categoriaService->save($categoria);

            header('Location: ' . BASE_URL);
        } else {
            $this->pages->render('categoria/crear');
        }
    }

    /**
     * Elimina una categoría por su ID.
     *
     * Este método elimina una categoría del sistema según su ID y luego muestra la lista actualizada de categorías.
     *
     * @param int $id El ID de la categoría a eliminar.
     * 
     * @return void
     */
    public function borrar($id) {
        $this->categoriaService->delete($id);
        $this->gestionarCategorias();
    }

    /**
     * Muestra el formulario para editar una categoría.
     *
     * Este método obtiene los detalles de una categoría y muestra el formulario de edición correspondiente.
     *
     * @param int $id El ID de la categoría que se va a editar.
     * 
     * @return void
     */
    public function editar($id) {
        $categoria = $this->categoriaService->getById($id);
        $this->pages->render('categoria/gestionarCategorias', ['categoria' => $categoria]);
    }

    /**
     * Actualiza los detalles de una categoría.
     *
     * Este método actualiza los datos de una categoría con la nueva información enviada por POST
     * y redirige a la página de gestión de categorías.
     *
     * @return void
     */
    public function actualizar() {
        if (isset($_POST['data'])) {
            $data = $_POST['data'];
            $id = $data['id'];
            $nombre = $data['nombre'];
            $this->categoriaService->update($id, $nombre);

            $this->gestionarCategorias();
        } 
    }
}
?>
