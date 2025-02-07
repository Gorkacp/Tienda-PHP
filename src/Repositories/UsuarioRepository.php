<?php
namespace Repositories;

use Lib\BaseDatos;
use PDO;
use PDOException;

/**
 * Clase UsuarioRepository para gestionar las operaciones de base de datos relacionadas con los usuarios.
 */
class UsuarioRepository {
    private BaseDatos $db;

    /**
     * Constructor que inicializa la conexión a la base de datos.
     */
    public function __construct() {
        $this->db = new BaseDatos();
    }

    /**
     * Crea un nuevo usuario en la base de datos.
     *
     * @param object $usuario Objeto que contiene los datos del usuario.
     * @return bool Devuelve true si el usuario se crea correctamente, false en caso de error.
     */
    public function create($usuario): bool {
        $id = $usuario->getId();
        $nombre = $usuario->getNombre();
        $apellidos = $usuario->getApellidos();
        $email = $usuario->getEmail();
        $password = $usuario->getPassword();
        $rol = ($usuario->getRol() == 'admin') ? 'admin' : 'user';

        try {
            $ins = $this->db->prepara("INSERT INTO usuarios (id, nombre, apellidos, email, password, rol) values (:id, :nombre, :apellidos, :email, :password, :rol)");
            $ins->bindValue(':id', $id);
            $ins->bindValue(':nombre', $nombre);
            $ins->bindValue(':apellidos', $apellidos);
            $ins->bindValue(':email', $email);
            $ins->bindValue(':password', $password);
            $ins->bindValue(':rol', $rol);
            $ins->execute();

            return true;
        } catch (PDOException $error) {
            return false;
        } finally {
            $ins->closeCursor();
            $ins = null;
        }
    }

    /**
     * Obtiene todos los usuarios de la base de datos.
     *
     * @return array Lista de usuarios.
     */
    public function verTodos() {
        $sql = "SELECT * FROM usuarios";
        $this->db->consulta($sql);
        $this->db->close();
        return $this->db->extraer_todos();
    }

    /**
     * Realiza el login de un usuario verificando su email y contraseña.
     *
     * @param object $usuario Objeto que contiene el email y la contraseña del usuario.
     * @return mixed El objeto usuario si el login es exitoso, o false si falla.
     */
    public function login($usuario) {
        $email = $usuario->getEmail();
        $password = $usuario->getPassword();

        try {
            $datosUsuario = $this->buscaMail($email);

            if ($datosUsuario !== false && $datosUsuario !== null) {
                $verify = password_verify($password, $datosUsuario->password);
                return $verify ? $datosUsuario : false;
            } else {
                return false;
            }
        } catch (PDOException $error) {
            return false;
        }
    }

    /**
     * Busca un usuario por su correo electrónico.
     *
     * @param string $email El email del usuario a buscar.
     * @return mixed El usuario si se encuentra, o null si no se encuentra.
     */
    public function buscaMail($email) {
        $select = $this->db->prepara("SELECT * FROM usuarios WHERE email=:email");
        $select->bindValue(':email', $email, PDO::PARAM_STR);

        try {
            $select->execute();
            if ($select && $select->rowCount() == 1) {
                return $select->fetch(PDO::FETCH_OBJ);
            } else {
                return null;
            }
        } catch (PDOException $err) {
            return false;
        }
    }

    /**
     * Obtiene un usuario por su ID.
     *
     * @param int $id El ID del usuario a buscar.
     * @return array El usuario correspondiente.
     */
    public function getById($id) {
        $sql = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepara($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->db->close();
        return $usuario;
    }

    /**
     * Actualiza los datos de un usuario en la base de datos.
     *
     * @param object $usuario Objeto con los datos actualizados del usuario.
     * @return bool Devuelve true si la actualización fue exitosa, false en caso de error.
     */
    public function update($usuario) {
        $id = $usuario->getId();
        $nombre = $usuario->getNombre();
        $apellidos = $usuario->getApellidos();
        $email = $usuario->getEmail();
        $rol = $usuario->getRol();

        try {
            $ins = $this->db->prepara("UPDATE usuarios SET nombre=:nombre, apellidos=:apellidos, email=:email, rol=:rol WHERE id=:id");
            $ins->bindValue(':id', $id);
            $ins->bindValue(':nombre', $nombre);
            $ins->bindValue(':apellidos', $apellidos);
            $ins->bindValue(':email', $email);
            $ins->bindValue(':rol', $rol);
            $ins->execute();

            return true;
        } catch (PDOException $error) {
            return false;
        } finally {
            $ins->closeCursor();
            $ins = null;
        }
    }

    /**
     * Elimina un usuario por su ID.
     *
     * @param int $id El ID del usuario a eliminar.
     * @return array El usuario eliminado.
     */
    public function delete($id) {
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepara($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->db->close();
        return $usuario;
    }
}
?>