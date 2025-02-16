<?php
namespace Repositories;

use Lib\BaseDatos;
use PDO;
use PDOException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Exception;

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
    public function delete($id): bool {
        try {
            // Iniciar una transacción
            $this->db->beginTransaction();

            // Eliminar líneas de pedidos asociados a los pedidos del usuario
            $sqlLineasPedidos = "DELETE FROM lineas_pedidos WHERE pedido_id IN (SELECT id FROM pedidos WHERE usuario_id = :id)";
            $stmtLineasPedidos = $this->db->prepara($sqlLineasPedidos);
            $stmtLineasPedidos->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtLineasPedidos->execute();
            $stmtLineasPedidos->closeCursor();

            // Eliminar pedidos asociados al usuario
            $sqlPedidos = "DELETE FROM pedidos WHERE usuario_id = :id";
            $stmtPedidos = $this->db->prepara($sqlPedidos);
            $stmtPedidos->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtPedidos->execute();
            $stmtPedidos->closeCursor();

            // Eliminar el usuario
            $sqlUsuario = "DELETE FROM usuarios WHERE id = :id";
            $stmtUsuario = $this->db->prepara($sqlUsuario);
            $stmtUsuario->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtUsuario->execute();
            $stmtUsuario->closeCursor();

            // Confirmar la transacción
            $this->db->commit();

            return true;
        } catch (PDOException $error) {
            // Revertir la transacción en caso de error
            $this->db->rollBack();
            return false;
        }
    }
    
        /**
 * Genera un token de recuperación para el usuario y lo envía por correo electrónico.
 *
 * @param string $email El email del usuario que solicita la recuperación.
 * @return bool Devuelve true si el correo se envía correctamente, false en caso de error.
 */
public function solicitarRecuperacion($email) {
    $usuario = $this->buscaMail($email);
    if ($usuario) {
        $token = bin2hex(random_bytes(16));
        $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $sql = "INSERT INTO recuperaciones (usuario_id, token, expira) VALUES (:usuario_id, :token, :expira)";
        $stmt = $this->db->prepara($sql);
        $stmt->bindValue(':usuario_id', $usuario->id, PDO::PARAM_INT);
        $stmt->bindValue(':token', $token, PDO::PARAM_STR);
        $stmt->bindValue(':expira', $expira, PDO::PARAM_STR);
        $stmt->execute();

        // Enviar el correo electrónico con el token de recuperación
        $this->enviarEmailRecuperacion($usuario->email, $usuario->nombre, $token);

        return true;
    } else {
        return false;
    }
}

/**
 * Envía un correo electrónico con el token de recuperación.
 *
 * @param string $email El email del usuario.
 * @param string $nombre El nombre del usuario.
 * @param string $token El token de recuperación.
 * @return void
 */
private function enviarEmailRecuperacion($email, $nombre, $token) {
    require '../vendor/autoload.php';

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->SMTPAuth = true;
        $mail->Username = 'gorkacarmonapino@gmail.com';
        $mail->Password = 'eqlu ahyy ykbk dftf';  // ⚠️ Reemplázalo por una variable de entorno

        $mail->setFrom('gorkacarmonapino@gmail.com', 'Tienda de Gorka');
        $mail->addReplyTo('no-reply@tienda.com', 'Tienda Soporte');
        $mail->addAddress($email, $nombre);

        $mail->Subject = 'Recuperacion de contrasena';

        $tokenSeguro = base64_encode($token);
        $urlRestablecer = BASE_URL . "usuario/restablecer?token=" . urlencode($tokenSeguro);

        $html = "<p>Hola <strong>$nombre</strong>,</p>";
        $html .= "<p>Hemos recibido una solicitud para restablecer tu contraseña.</p>";
        $html .= "<p>Haz clic en el siguiente enlace para restablecerla:</p>";
        $html .= "<p><a href='" . htmlspecialchars($urlRestablecer, ENT_QUOTES, 'UTF-8') . "' style='color: #007bff; text-decoration: none; font-weight: bold;'>Restablecer contraseña</a></p>";
        $html .= "<p>Saludos,<br>El equipo de Tienda de Gorka</p>";

        $mail->msgHTML($html);
        $mail->AltBody = "Hola $nombre,\n\nHemos recibido una solicitud para restablecer tu contraseña. Visita el siguiente enlace:\n$urlRestablecer\n\nSi no solicitaste este cambio, ignora este correo.\n\nSaludos,\nEl equipo de Tienda de Gorka";

        if (!$mail->send()) {
            throw new Exception('Error en el envío: ' . $mail->ErrorInfo);
        } else {
            echo '¡Mensaje enviado!';
        }
    } catch (Exception $e) {
        echo 'Error en el correo: ' . $e->getMessage();
    }
}


   /**
     * Verifica el token de recuperación y permite cambiar la contraseña.
     *
     * @param string $token El token de recuperación.
     * @param string $nuevaPassword La nueva contraseña.
     * @return bool Devuelve true si la contraseña se cambia correctamente, false en caso de error.
     */
    public function cambiarPasswordConToken($token, $nuevaPassword) {
        $token = base64_decode($token);
    
        $sql = "SELECT * FROM recuperaciones WHERE token = :token AND expira > NOW()";
        $stmt = $this->db->prepara($sql);
        $stmt->bindValue(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        $recuperacion = $stmt->fetch(PDO::FETCH_OBJ);
    
        if ($recuperacion) {
            $sql = "UPDATE usuarios SET password = :password WHERE id = :id";
            $stmt = $this->db->prepara($sql);
            $stmt->bindValue(':password', password_hash($nuevaPassword, PASSWORD_BCRYPT), PDO::PARAM_STR);
            $stmt->bindValue(':id', $recuperacion->usuario_id, PDO::PARAM_INT);
            $stmt->execute();
    
            $sql = "DELETE FROM recuperaciones WHERE token = :token";
            $stmt = $this->db->prepara($sql);
            $stmt->bindValue(':token', $token, PDO::PARAM_STR);
            $stmt->execute();
    
            return true;
        } else {
            return false;
        }
    }
}
?>