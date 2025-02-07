<?php

namespace Utils;

/**
 * Clase Utils
 * 
 * Contiene métodos utilitarios para la aplicación.
 */
class Utils
{
    /**
     * Elimina una sesión.
     * 
     * @param string $name Nombre de la sesión a eliminar.
     * @return void
     */
    public static function deleteSession($name): void {
        if (isset($_SESSION[$name])) {
            $_SESSION[$name] = null;
            unset($_SESSION[$name]);
        }
    }

    /**
     * Verifica si el usuario es administrador.
     * 
     * @return bool Devuelve true si el usuario es administrador, de lo contrario false.
     */
    public static function isAdmin(): bool {
        if (isset($_SESSION['admin'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Obtiene la hora actual.
     * 
     * @return string Hora actual en formato H:i:s.
     */
    public static function getHora() {
        return date("H:i:s");
    }

    /**
     * Obtiene la fecha actual.
     * 
     * @return string Fecha actual en formato Y-m-d.
     */
    public static function getFecha() {
        return date("Y-m-d");
    }
}