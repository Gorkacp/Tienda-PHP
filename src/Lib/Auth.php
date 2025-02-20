<?php 
namespace Lib;

class Auth {
    public static function checkAdmin() {
        if (!isset($_SESSION['login']) || $_SESSION['login']->rol !== 'admin') {
            header('Location: ' . BASE_URL . 'usuario/login');
            exit;
        }
    }

    public static function checkLogin() {
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASE_URL . 'usuario/login');
            exit;
        }
    }
}


?>