<?php

namespace Lib;

/**
 * Clase que maneja la renderización de las páginas.
 * 
 * Esta clase se encarga de cargar y mostrar las vistas de las páginas solicitadas,
 * incluyendo las plantillas de cabecera y pie de página. Permite pasar parámetros
 * para personalizar el contenido de las vistas.
 */
class Pages
{
    /**
     * Renderiza la página solicitada.
     * 
     * Este método construye la ruta a la vista correspondiente, incluye las plantillas
     * de cabecera y pie de página, y pasa los parámetros a la vista si se proporcionan.
     *
     * @param string $pageName Nombre del archivo de la vista a renderizar.
     * @param array|null $params Parámetros opcionales a pasar a la vista (por defecto es null).
     * 
     * @return void
     */
    public function render(string $pageName, array $params = null): void
    {
        // Si se proporcionan parámetros, se extraen para hacerlas accesibles como variables.
        if ($params !== null) {
            extract($params);
        }

        // Construye la ruta a la carpeta de vistas.
        $viewsPath = dirname(__DIR__, 1) . "/Views/";

        // Incluye la plantilla de cabecera, la vista solicitada y la plantilla de pie de página.
        require_once $viewsPath . "Layout/header.php";
        require_once $viewsPath . "$pageName.php";
        require_once $viewsPath . "Layout/footer.php";
    }
}
?>

