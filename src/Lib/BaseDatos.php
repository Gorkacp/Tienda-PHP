<?php
namespace Lib;
use PDO;
use PDOException;
use Exception;

class BaseDatos{
    /** @var PDO Conexión a la base de datos */
    private $conexion;
    
    /** @var mixed Resultado de la consulta SQL */
    private mixed $resultado; //mixed novedad en PHP cualquier valor
    
    /** @var string Servidor de la base de datos */
    private string $servidor;
    
    /** @var string Usuario de la base de datos */
    private string $usuario;
    
    /** @var string Contraseña de la base de datos */
    private string $pass;
    
    /** @var string Nombre de la base de datos */
    private string $base_datos;

    /**
     * Constructor de la clase BaseDatos.
     * Inicializa los parámetros de conexión utilizando variables de entorno.
     */
    function __construct(){
        $this->servidor = $_ENV['SERVIDOR'];
        $this->usuario = $_ENV['USUARIO'];
        $this->pass = $_ENV['PASSWORD'];
        $this->base_datos = $_ENV['BASE_DATOS'];

        $this->conexion = $this->conectar();
    }

    /**
     * Establece la conexión con la base de datos.
     *
     * @return PDO Retorna la conexión a la base de datos.
     * @throws PDOException Si ocurre un error durante la conexión.
     */
    private function conectar(): PDO {
        try {
            $opciones = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES Utf8",
                PDO::MYSQL_ATTR_FOUND_ROWS => true
            );

            $conexion = new PDO("mysql:host={$this->servidor};dbname={$this->base_datos}", $this->usuario, $this->pass, $opciones);
            return $conexion;
        } catch(PDOException $e){
            echo "Ha surgido un error y no se puede conectar a la base de datos. Detalle: " . $e->getMessage();
            exit;
        }
    }

    /**
     * Realiza una consulta SQL a la base de datos.
     *
     * @param string $consultasQL Consulta SQL a ejecutar.
     */
    public function consulta(string $consultasQL): void
    {
        $this->resultado = $this->conexion->query($consultasQL);
    }

    /**
     * Extrae el siguiente registro de la consulta SQL.
     *
     * @return mixed El siguiente registro como un arreglo asociativo, o false si no hay más registros.
     */
    public function extraer_registro(): mixed
    {
        return ( $fila = $this->resultado->fetch(PDO::FETCH_ASSOC))? $fila:false;
    }

    /**
     * Extrae todos los registros de la consulta SQL.
     *
     * @return array Un arreglo de todos los registros obtenidos.
     */
    public function extraer_todos(): array
    {
        return $this->resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Devuelve el número de filas afectadas por la última consulta.
     *
     * @return int El número de filas afectadas.
     */
    public function filasAfectadas(): int
    {
        return $this->resultado->rowCount();
    }

    /**
     * Cierra la conexión con la base de datos.
     */
    public function close(){
        if ($this->conexion !== null) {
            $this->conexion = null;
        }
    }

    /**
     * Prepara una sentencia SQL para su ejecución.
     *
     * @param string $pre La sentencia SQL a preparar.
     * @return PDOStatement La sentencia preparada.
     */
    public function prepara($pre){
        return $this->conexion->prepare($pre);
    }

    /**
     * Inicia una transacción en la base de datos.
     */
    public function empezarTransaccion(){
        $this->conexion->beginTransaction();
    }

    /**
     * Ejecuta una transacción en la base de datos.
     */
    public function ejecutarTransaccion(){
        $this->conexion->commit();
    }

    /**
     * Deshace la transacción actual.
     */
    public function rollback(){
        $this->conexion->rollBack();
    }

    /**
     * Obtiene el último ID insertado en la base de datos.
     *
     * @return string El ID del último registro insertado.
     */
    public function lastInsertId(){
        return $this->conexion->lastInsertId();
    }
}
?>
