<?php
    namespace Controllers;
    use Lib\Pages;

    /**
     * Clase ErrorController
     *
     * Esta clase maneja la lógica relacionada con la gestión de errores en la aplicación,
     * como la visualización de una página de error 404.
     */
    class ErrorController {

        private Pages $pages;

        /**
         * Constructor de ErrorController.
         *
         * Inicializa una nueva instancia de la clase Pages para gestionar las vistas.
         */
        function __construct(){
            $this->pages = new Pages();
        }

        /**
         * Muestra la página de error 404.
         *
         * Este método es invocado cuando se encuentra una página no encontrada. 
         * Muestra la vista de error 404 con el título 'Página no encontrada'.
         *
         * @return void
         */
        public function error404(): void {
            $this->pages->render('error/error', ['título' => 'Página no encontrada']);
        }
        
    }
?>
