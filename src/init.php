<?php
/**
 * Inicia la sesión y carga las rutas principales de la aplicación.
 * 
 * Este script configura el entorno de la aplicación, carga las dependencias 
 * necesarias y ejecuta la función principal de enrutamiento.
 */

session_start(); // Inicia la sesión para gestionar variables de sesión.

use Routes\Routes; // Importa la clase Routes desde el espacio de nombres Routes.

require_once '../vendor/autoload.php'; // Carga las dependencias del proyecto mediante Composer.
require_once '../config/config.php'; // Incluye el archivo de configuración de la aplicación.

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1)); // Crea una instancia para manejar variables de entorno.
$dotenv->safeload(); // Carga las variables de entorno de manera segura.

Routes::index(); // Llama al método estático index() de la clase Routes para manejar las rutas de la aplicación.
?>