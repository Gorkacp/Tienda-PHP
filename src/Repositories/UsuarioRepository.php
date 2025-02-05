<?php
namespace Repositories;
use Lib\BaseDatos;
use PDO;
use PDOException;


class UsuarioRepository {
    private BaseDatos $db;

    public function __construct() {
        $this->db = new BaseDatos();
            
    }

    public function create($usuario): bool{
        $id = $usuario->getId();
        $nombre = $usuario->getNombre();
        $apellidos = $usuario->getApellidos();
        $email = $usuario->getEmail();
        $password = $usuario->getPassword();
        if ($usuario->getRol() == 'admin'){
            $rol = 'admin';
        } else{
            $rol = 'user';
        }

        try {
            $ins = $this->db->prepara("INSERT INTO usuarios (id, nombre, apellidos, email, password, rol) values (:id, :nombre, :apellidos, :email, :password, :rol)");

            $ins->bindValue(':id', $id);
            $ins->bindValue(':nombre', $nombre);
            $ins->bindValue(':apellidos', $apellidos);
            $ins->bindValue(':email', $email);
            $ins->bindValue(':password', $password);
            $ins->bindValue(':rol', $rol);

            $ins->execute();

            $result = true;
        } catch (PDOException $error){
            $result = false;
        }

        $ins->closeCursor();
        $ins=null;

        return $result;
    }


    public function verTodos(){
        $sql = "SELECT * FROM usuarios";
        $this->db->consulta($sql);
        $this->db->close();
        return $this->db->extraer_todos();
    }

    public function login($usuario){
        $email = $usuario->getEmail();
        $password = $usuario->getPassword();

        try {
            $datosUsuario = $this->buscaMail($email);

            if ($datosUsuario !== false && $datosUsuario !== null){
                $verify = password_verify($password, $datosUsuario->password);

                if ($verify){
                    $result = $datosUsuario;
                } else {
                    $result = false;
                }
            } else {
                $result = false;
            }
        } catch (PDOException $error){
            $result = false;
        }

        return $result;
    }

    public function buscaMail($email){
        $select = $this->db->prepara("SELECT * FROM usuarios WHERE email=:email");
        $select->bindValue(':email', $email, PDO::PARAM_STR);

        try {
            $select->execute();
            if ($select && $select->rowCount() == 1){
                $usuario = $select->fetch(PDO::FETCH_OBJ);

                if ($usuario !== false) {
                    $result = $usuario;
                } else {
                    $result = null;
                }
            } else {
                $result = null;
            }
        } catch (PDOException $err){
            $result = false;
        }
        return $result;
    }

    public function getById($id){
        $sql = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepara($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->db->close();
        return $usuario;
    }

    public function update($usuario){
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

            $result = true;
        } catch (PDOException $error){
            $result = false;
        }

        $ins->closeCursor();
        $ins=null;

        return $result;
    }

    public function delete($id){
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepara($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->db->close();
        return $usuario;
    }

}