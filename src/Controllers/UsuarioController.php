<?php

namespace Controllers;
use Models\Usuario;
use Lib\Pages;
use Utils\Utils;
use Services\UsuarioService;
use Repositories\UsuarioRepository;

class UsuarioController {
    private Pages $pages;
    private UsuarioService $usuarioService;
    private $errores = [];
    private UsuarioRepository $usuarioRepository;

    // Constructor de la clase UsuarioController
    public function __construct()
    {
        $this->pages = new Pages();
        $this->usuarioService = new UsuarioService(new UsuarioRepository());
    }

    // Método para ver todos los usuarios
    public function verTodos(){
        $usuarios = $this->usuarioService->verTodos();
        $this->pages->render('/usuario/verTodos', ['usuarios' => $usuarios]);
    }

    // Método para validar el formulario de usuario
    private function validarFormulario($data) {
        $nombre = filter_var($data['nombre'],);
        $apellidos = filter_var($data['apellidos'],);
        $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        $password = filter_var($data['password'],);
    
        // Validación de regex
        $nombreRegex = "/^[a-zA-ZáéíóúÁÉÍÓÚ ]*$/";
        $emailRegex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
        $passwordRegex = "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/"; // Al menos una letra, un número y mínimo 8 caracteres
    
        if (empty($nombre) || !preg_match($nombreRegex, $nombre)) {
            $this->errores[] = 'El nombre solo debe contener letras y espacios.';
        }
        if (empty($apellidos) || !preg_match($nombreRegex, $apellidos)) {
            $this->errores[] = 'Los apellidos solo deben contener letras y espacios.';
        }
        if (empty($email) || !preg_match($emailRegex, $email)) {
            $this->errores[] = 'El correo electrónico no es válido.';
        }
        if (empty($password) || !preg_match($passwordRegex, $password)) {
            $this->errores[] = 'La contraseña debe tener al menos una letra, un número y un mínimo de 8 caracteres.';
        }

        if (empty($this->errores)) {
            return [
                'nombre' => $nombre,
                'apellidos' => $apellidos,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT, ['cost'=>4])
            ];
        } else {
            return $this->errores;
        }
    }

    // Método para validar el inicio de sesión
    private function validarLogin($data) {
        $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        $password = filter_var($data['password'],);
    
        // Validación de regex
        $emailRegex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
        $passwordRegex = "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/"; // Al menos una letra, un número y mínimo 8 caracteres
    
        if (!preg_match($emailRegex, $email)) {
            $this->errores[] = 'El correo electrónico no es válido.';
        }
        if (!preg_match($passwordRegex, $password)) {
            $this->errores[] = 'La contraseña debe tener al menos una letra, un número y un mínimo de 8 caracteres.';
        }
    
        if (empty($this->errores)) {
            return [
                'email' => $email,
                'password' => $password
            ];
        } else {
            return false;
        }
    }

    // Método para registrar un nuevo usuario
    public function registro(){
        if (($_SERVER['REQUEST_METHOD']) === 'POST'){
            if ($_POST['data']){
                $registrado = $this->validarFormulario($_POST['data']);

                if ($registrado != ""){
                    if (is_array($registrado)) {
                        $usuario = Usuario::fromArray($registrado);
                        $save = $this->usuarioService->create($usuario);
                        $registrado = "";
                        if ($save){
                            $_SESSION['register'] = "complete";
                        } else {
                            $_SESSION['register'] = "failed";
                        }
                    } else {
                        $_SESSION['register'] = "failed";
                    }
                }
                else {
                    $_SESSION['register'] = "failed";
                }
    
            }
        }
    
        $this->pages->render('/usuario/registro', ['errores' => $this->errores]);
    }

    // Método para iniciar sesión
    public function login(){
        if (($_SERVER['REQUEST_METHOD']) === 'POST'){
            if ($_POST['data']){
                $login = $this->validarLogin($_POST['data']);
    
                if ($login !== false) {
                    $usuario = Usuario::fromArray($login);
                    $verify = $this->usuarioService->login($usuario);
    
                    if ($verify != false){
                        $_SESSION['login'] = $verify;
                        header("Location: " . BASE_URL); // Redirigir al inicio después de iniciar sesión
                        exit();
                    } else {
                        $_SESSION['login'] = "failed";
                    }
                } else {
                    $_SESSION['login'] = "failed";
                }
            } else {
                $_SESSION['login'] = "failed";
            }
        }
    
        $this->pages->render('/usuario/login', ['errores' => $this->errores]);
    }

    // Método para cerrar sesión
    public function logout(){
        Utils::deleteSession('login');

        header("Location:".BASE_URL);
    }

    // Método para eliminar un usuario
    public function eliminar($id){
        $this->usuarioService->delete($id);
        header("Location:".BASE_URL."usuario/verTodos");
    }

    // Método para editar un usuario
    public function editar($id){
        $usuarios = $this->usuarioService->verTodos();
        $this->pages->render('/usuario/verTodos', ['usuarios' => $usuarios, 'id' => $id]);
    }

    // Método para validar la edición de un usuario
    public function validarEditar($data){
        $id = filter_var($data['id'],);
        $nombre = filter_var($data['nombre'],);
        $apellidos = filter_var($data['apellidos'],);
        $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        $rol = filter_var($data['rol'],);
    
        $nombreRegex = "/^[a-zA-ZáéíóúÁÉÍÓÚ ]*$/";
        $emailRegex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
        $passwordRegex = "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/"; 
    
        if (!preg_match($nombreRegex, $nombre)) {
            $this->errores[] = 'El nombre solo debe contener letras y espacios.';
        }
        if (!preg_match($nombreRegex, $apellidos)) {
            $this->errores[] = 'Los apellidos solo deben contener letras y espacios.';
        }
        if (!preg_match($emailRegex, $email)) {
            $this->errores[] = 'El correo electrónico no es válido.';
        }

        if (empty($this->errores)) {
            return [
                'id' => $id,
                'nombre' => $nombre,
                'apellidos' => $apellidos,
                'email' => $email,
                'rol' => $rol
            ];
        } else {
            return $this->errores;
        }
    }

    // Método para actualizar un usuario
    public function actualizar(){
        if (($_SERVER['REQUEST_METHOD']) === 'POST'){
            if ($_POST['data']){
                $registrado = $this->validarEditar($_POST['data']);

                if ($registrado != "") {
                    $usuario = Usuario::fromArray($registrado);

                    $save = $this->usuarioService->update($usuario);
                    if ($save){
                        $_SESSION['register'] = "complete";
                    } else {
                        $_SESSION['register'] = "failed";
                    }
                } else {
                    $_SESSION['register'] = "failed";
                }
                $usuario->desconecta();
            }
        }
        header("Location:".BASE_URL."usuario/verTodos");
    }

    // Método para mostrar el formulario de recuperación de contraseña
    public function mostrarFormularioRecuperacion() {
        $this->pages->render('usuario/recuperar');
    }

    // Método para manejar la solicitud de recuperación de contraseña
    public function solicitarRecuperacion() {
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $resultado = $this->usuarioService->solicitarRecuperacion($email);
    
        if ($resultado) {
            $_SESSION['mensaje'] = "Se ha enviado un correo de recuperación.";
        } else {
            $_SESSION['errores'] = "No se pudo enviar el correo de recuperación.";
        }
    
        header("Location: " . BASE_URL . "usuario/recuperar");
        exit();
    }

    
     // Método para mostrar el formulario de restablecimiento de contraseña
     public function mostrarFormularioRestablecimiento($param = null, $queryParams = []) {
        $token = $queryParams['token'] ?? null;
        
        if (!$token) {
            $_SESSION['errores'] = "El token es requerido.";
            header("Location: " . BASE_URL . "usuario/recuperar");
            exit();
        }
        
        // Mensaje de depuración
        error_log("Token recibido: " . $token);
        
        $this->pages->render('usuario/restablecer', ['token' => $token]);
    }

    // Método para manejar la solicitud de restablecimiento de contraseña
    public function restablecerPassword() {
        $token = $_POST['token'];
        $nuevaPassword = $_POST['password'];
    
        // Validar token
        if (empty($token)) {
            $_SESSION['errores'] = "El token es requerido.";
            header("Location: " . BASE_URL . "usuario/recuperar");
            exit();
        }
        // Validar nueva contraseña
        if (strlen($nuevaPassword) < 8) {
            $_SESSION['errores'] = "La contraseña debe tener al menos 8 caracteres.";
            header("Location: " . BASE_URL . "usuario/restablecer?token=" . urlencode($token));
            exit();
        }
        // Llamar al servicio para restablecer la contraseña
        $resultado = $this->usuarioService->restablecerPassword($token, $nuevaPassword);
    
        if ($resultado) {
            $_SESSION['mensaje'] = "Su contraseña ha sido restablecida correctamente.";
            header("Location: " . BASE_URL . "usuario/login");
        } else {
            $_SESSION['errores'] = "No se pudo restablecer la contraseña. El token es inválido o ha expirado.";
            header("Location: " . BASE_URL . "usuario/restablecer?token=" . urlencode($token));
        }
        exit();
    }
}
?>