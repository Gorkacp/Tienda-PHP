<?php
namespace Services;

use Repositories\UsuarioRepository;

/**
 * Servicio para la gestión de usuarios.
 */
class UsuarioService {
    /**
     * @var UsuarioRepository Repositorio de usuarios
     */
    private $usuarioRepository;

    /**
     * Constructor de la clase UsuarioService.
     * 
     * @param UsuarioRepository $usuarioRepository Instancia del repositorio de usuarios.
     */
    public function __construct(UsuarioRepository $usuarioRepository) {
        $this->usuarioRepository = $usuarioRepository;
    }

    /**
     * Crea un nuevo usuario.
     * 
     * @param array $usuario Datos del usuario a crear.
     * @return mixed Resultado de la creación del usuario.
     */
    public function create($usuario) {
        return $this->usuarioRepository->create($usuario);
    }

    /**
     * Obtiene todos los usuarios.
     * 
     * @return array Lista de todos los usuarios.
     */
    public function verTodos() {
        return $this->usuarioRepository->verTodos();
    }

    /**
     * Inicia sesión con los datos de un usuario.
     * 
     * @param array $usuario Datos del usuario para el inicio de sesión.
     * @return mixed Resultado del proceso de inicio de sesión.
     */
    public function login($usuario) {
        return $this->usuarioRepository->login($usuario);
    }

    /**
     * Busca un usuario por su correo electrónico.
     * 
     * @param string $email Correo electrónico del usuario a buscar.
     * @return mixed Datos del usuario si existe, null si no se encuentra.
     */
    public function buscaMail($email) {
        return $this->usuarioRepository->buscaMail($email);
    }

    /**
     * Obtiene un usuario por su ID.
     * 
     * @param int $id ID del usuario.
     * @return mixed Datos del usuario encontrado o null si no existe.
     */
    public function getById($id) {
        return $this->usuarioRepository->getById($id);
    }

    /**
     * Actualiza los datos de un usuario.
     * 
     * @param array $usuario Datos actualizados del usuario.
     * @return mixed Resultado de la actualización.
     */
    public function update($usuario) {
        return $this->usuarioRepository->update($usuario);
    }

    /**
     * Elimina un usuario por su ID.
     * 
     * @param int $id ID del usuario a eliminar.
     * @return mixed Resultado de la eliminación del usuario.
     */
    public function delete($id) {
        return $this->usuarioRepository->delete($id);
    }
}
