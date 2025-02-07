<?php

namespace Controllers;
use Lib\Pages;
use Controllers\ProductoController;

/**
 * Clase InicioController
 *
 * Esta clase maneja la lógica para la página de inicio de la aplicación.
 * Se encarga de cargar y mostrar los productos aleatorios en la vista de inicio.
 */
class InicioController{
    
    private Pages $pages;
    private ProductoController $productoController;
    
    /**
     * Constructor de InicioController.
     *
     * Inicializa las instancias de Pages y ProductoController para gestionar la vista y los productos.
     */
    function __construct(){
        $this->pages = new Pages();
        $this->productoController = new ProductoController();
    }

    /**
     * Muestra la página de inicio con productos aleatorios.
     *
     * Este método obtiene un conjunto de productos aleatorios utilizando el ProductoController 
     * y los pasa a la vista de inicio para su renderizado.
     *
     * @return void
     */
    public function index(): void{
        $productos = $this->productoController->getRandom();
        $this->pages->render('inicio/index', ['productos' => $productos]);
    }
}
