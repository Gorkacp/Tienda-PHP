<?php

namespace models;

use Lib\BaseDatos;
use PDO;
use PDOException;

/**
 * Clase Usuario
 *
 * Esta clase representa un usuario en el sistema. Contiene los datos del usuario
 * como su ID, nombre, apellidos, email, contraseña, rol y proporciona métodos
 * para acceder y modificar estos valores.
 */
class Usuario
{
    /**
     * @var string|null $id El ID único del usuario. Puede ser nulo si el usuario no ha sido creado aún.
     */
    private string|null $id;

    /**
     * @var string $nombre El nombre del usuario.
     */
    private string $nombre;

    /**
     * @var string $apellidos Los apellidos del usuario.
     */
    private string $apellidos;

    /**
     * @var string $email El email del usuario.
     */
    private string $email;

    /**
     * @var string $password La contraseña del usuario. 
     */
    private string $password;

    /**
     * @var string $rol El rol del usuario (por ejemplo, "admin", "usuario").
     */
    private string $rol;

    /**
     * @var BaseDatos $db La conexión a la base de datos.
     */
    private BaseDatos $db;

    /**
     * Constructor de Usuario.
     *
     * Inicializa una nueva instancia de la clase Usuario con los parámetros proporcionados.
     *
     * @param string|null $id El ID del usuario, si está disponible.
     * @param string $nombre El nombre del usuario.
     * @param string $apellidos Los apellidos del usuario.
     * @param string $email El email del usuario.
     * @param string $password La contraseña del usuario.
     * @param string $rol El rol del usuario.
     */
    public function __construct(string|null $id, string $nombre, string $apellidos, string $email, string $password, string $rol)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->email = $email;
        $this->password = $password;
        $this->rol = $rol;
        $this->db = new BaseDatos();
    }

    // Métodos Getter y Setter

    /**
     * Obtiene el ID del usuario.
     *
     * @return string|null El ID del usuario o null si no está definido.
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Establece el ID del usuario.
     *
     * @param string $id El nuevo ID del usuario.
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * Obtiene el nombre del usuario.
     *
     * @return string El nombre del usuario.
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * Establece el nombre del usuario.
     *
     * @param string $nombre El nuevo nombre del usuario.
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * Obtiene los apellidos del usuario.
     *
     * @return string Los apellidos del usuario.
     */
    public function getApellidos(): string
    {
        return $this->apellidos;
    }

    /**
     * Establece los apellidos del usuario.
     *
     * @param string $apellidos Los nuevos apellidos del usuario.
     */
    public function setApellidos(string $apellidos): void
    {
        $this->apellidos = $apellidos;
    }

    /**
     * Obtiene el email del usuario.
     *
     * @return string El email del usuario.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Establece el email del usuario.
     *
     * @param string $email El nuevo email del usuario.
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Obtiene la contraseña del usuario.
     *
     * @return string La contraseña del usuario.
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Establece la contraseña del usuario.
     *
     * @param string $password La nueva contraseña del usuario.
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Obtiene el rol del usuario.
     *
     * @return string El rol del usuario.
     */
    public function getRol(): string
    {
        return $this->rol;
    }

    /**
     * Establece el rol del usuario.
     *
     * @param string $rol El nuevo rol del usuario.
     */
    public function setRol(string $rol): void
    {
        $this->rol = $rol;
    }

    /**
     * Crea una nueva instancia de la clase Usuario a partir de un array de datos.
     *
     * Este método es útil cuando se obtienen los datos de un usuario desde una base
     * de datos o un formulario, para crear un objeto Usuario de manera sencilla.
     *
     * @param array $data Los datos del usuario.
     * @return Usuario La nueva instancia de Usuario creada.
     */
    public static function fromArray(array $data): Usuario
    {
        return new Usuario(
            $data['id'] ?? null,
            $data['nombre'] ?? '',
            $data['apellidos'] ?? '',
            $data['email'] ?? '',
            $data['password'] ?? '',
            $data['rol'] ?? '',
        );
    }

    /**
     * Desconecta la conexión a la base de datos.
     *
     * Este método se usa para cerrar la conexión a la base de datos cuando ya no se
     * necesita, asegurando que los recursos se liberen adecuadamente.
     */
    public function desconecta()
    {
        $this->db->close();
    }
}
