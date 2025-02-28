<?php

namespace Controllers;

use Repositories\PedidoRepository;
use Repositories\ProductoRepository;
use Services\ProductoService;
use Utils\Utils;
use Lib\Pages;
use Services\PedidoService;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use TCPDF;


/**
 * Clase PedidoController
 *
 * Esta clase maneja la lógica para las acciones relacionadas con los pedidos.
 */
class PedidoController {
    private Pages $pages;
    private PedidoService $pedidoService;
    private ProductoService $productoService; 

    /**
     * Constructor de PedidoController.
     *
     * Inicializa una nueva instancia de la clase PedidoController.
     */
    public function __construct() {
        $this->pages = new Pages();
        $this->pedidoService = new PedidoService(new PedidoRepository());
        $this->productoService = new ProductoService(new ProductoRepository());
    }

    /**
     * Obtiene todos los pedidos.
     *
     * @return array Los pedidos obtenidos.
     */
public function mostrarPedido() {
    if (!isset($_SESSION['login'])) {
        $this->pages->render('usuario/login', ['errores' => 'No hay productos en el carrito']);
    } elseif (isset($_SESSION['login']) && count($_SESSION['carrito']) >= 1) {
        $carrito = $_SESSION['carrito'];
        $totalCarrito = $this->pedidoService->getTotalCarrito($carrito);
        $this->pages->render('pedido/crear', ['totalCarrito' => $totalCarrito]);
    } elseif (isset($_SESSION['login']) && count($_SESSION['carrito']) == 0) {
        $this->pages->render('carrito/ver', ['errores' => 'No hay productos en el carrito']);
    }
}

    /**
     * Obtiene todos los pedidos.
     *
     * @return array Los pedidos obtenidos.
     */
    public function todosLosPedidos(){
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASE_URL . 'usuario/login');
        }
        else {
            $usuario = $_SESSION['login'];
            if ($usuario->rol == 'admin') {
                $pedidos = $this->pedidoService->getAll();
                $this->pages->render('pedido/gestionarPedidos', ['pedidos' => $pedidos]);
            }
            else {
                header('Location: ' . BASE_URL . 'usuario/login');
            }
        }
    }

    /**
     * Obtiene todos los pedidos de un usuario.
     *
     * @return array Los pedidos obtenidos.
     */
    public function misPedidos(){
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASE_URL . 'usuario/login');
        }
        else {
            $usuario = $_SESSION['login'];
            $pedidos = $this->pedidoService->getByUsuario($usuario->id);
            $this->pages->render('pedido/misPedidos', ['pedidos' => $pedidos]);
        }
    }

    /**
     * Obtiene todos los pedidos de un usuario.
     *
     * @return array Los pedidos obtenidos.
     */
    public function validarPedido($provincia, $localidad, $direccion) {
        $errores = [];
        if (empty($provincia) || strlen($provincia) < 2) {
            $errores['provincia'] = 'La provincia no puede estar vacía y debe tener al menos 2 caracteres';
        }
        if (empty($localidad) || strlen($localidad) < 2) {
            $errores['localidad'] = 'La localidad no puede estar vacía y debe tener al menos 2 caracteres';
        }
        if (empty($direccion) || strlen($direccion) < 5) {
            $errores['direccion'] = 'La dirección no puede estar vacía y debe tener al menos 5 caracteres';
        }
        return $errores;
    }

    /**
     * Obtiene todos los pedidos de un usuario.
     *
     * @return array Los pedidos obtenidos.
     */
    public function crear() {
        if (!isset($_SESSION['login']) || empty($_SESSION['carrito'])) {
            header('Location: ' . BASE_URL . 'usuario/login');
            exit;
        }
    
        $provincia = isset($_POST['provincia']) ? $_POST['provincia'] : false;
        $localidad = isset($_POST['localidad']) ? $_POST['localidad'] : false;
        $direccion = isset($_POST['direccion']) ? $_POST['direccion'] : false;
    
        $errores = $this->validarPedido($provincia, $localidad, $direccion);
    
        if (!empty($errores)) {
            $this->pages->render('pedido/crear', ['errores' => $errores]);
            exit;
        }
    
        $usuario = $_SESSION['login'];
        $carrito = $_SESSION['carrito'];
        $total = $this->pedidoService->getTotalCarrito($carrito);
        $pedido = $this->pedidoService->save($usuario->id, $provincia, $localidad, $direccion, $total, 'pendiente', Utils::getFecha(), Utils::getHora(), $carrito);
    
        if ($pedido) {
            unset($_SESSION['carrito']);
            $this->enviarEmail($pedido); // Llamada al método enviarEmail
            header('Location: ' . BASE_URL . 'pedido/misPedidos');
            exit;
        } else {
            $errores[] = 'No se pudo crear el pedido.';
            $this->pages->render('pedido/crear', ['errores' => $errores]);
        }
    }

    /**
     * Obtiene todos los pedidos de un usuario.
     *
     * @return array Los pedidos obtenidos.
     */
    public function eliminar($id){
        $usuario = $_SESSION['login'];

        if ($usuario->rol == 'admin') {
            $this->pedidoService->delete($id);
            header('Location: ' . BASE_URL . 'pedido/todosLosPedidos');
        }
    }

    /**
     * Obtiene todos los pedidos de un usuario.
     *
     * @return array Los pedidos obtenidos.
     */
    public function editar($id){
        $pedidos = $this->pedidoService->getAll();
        $this->pages->render('pedido/gestionarPedidos', ['pedidos' => $pedidos, 'id' => $id]);
    }

    /**
     * Obtiene todos los pedidos de un usuario.
     *
     * @return array Los pedidos obtenidos.
     */
    public function validarPedidoActualizado($data) {
        $errores = [];
        if (empty($data['coste']) || !is_numeric($data['coste'])) {
            $errores['coste'] = 'El coste es requerido y debe ser un número';
        }

        $estadosPermitidos = ['pendiente', 'confirmado'];
        if (empty($data['estado']) || !in_array($data['estado'], $estadosPermitidos)) {
            $errores['estado'] = 'El estado es requerido y debe ser "pendiente" o "confirmado"';
        }

        if (empty($data['fecha']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['fecha'])) {
            $errores['fecha'] = 'La fecha es requerida y debe estar en el formato YYYY-MM-DD';
        }

        if (empty($data['hora']) || !preg_match('/^\d{2}:\d{2}:\d{2}$/', $data['hora'])) {
            $errores['hora'] = 'La hora es requerida y debe estar en el formato HH:MM:SS';
        }

        return $errores;
    }

    /**
     * Obtiene todos los pedidos de un usuario.
     *
     * @return array Los pedidos obtenidos.
     */
    public function actualizar() {
        $usuario = $_SESSION['login'];
        $pedido = $_POST['data'];
        $id = $pedido['id'];
        $coste = $pedido['coste'];
        $fecha = $pedido['fecha'];
        $hora = $pedido['hora'];
        $estado = $pedido['estado'];
        $usuario_id = $pedido['usuario_id'];
        
        if ($usuario->rol == 'admin') {
            $data = $_POST['data'];
            $errores = $this->validarPedidoActualizado($data);
    
            if (!empty($errores)) {
                $pedido = $this->pedidoService->getById($id);
                $this->pages->render('pedido/editarPedido', ['pedido' => $pedido, 'errores' => $errores]);
            } else {
                $this->pedidoService->editar($id, $coste, $fecha, $hora, $estado, $usuario_id);
                header('Location: ' . BASE_URL . 'pedido/todosLosPedidos');
                exit();
            }
        }
    }

    /**
     * Obtiene todos los pedidos de un usuario.
     *
     * @return array Los pedidos obtenidos.
     */
    public function confirmarPedido($id) {
        $usuario = $_SESSION['login'];

        if ($usuario->rol == 'admin') {
            $this->pedidoService->confirmarPedido($id);
            $this->enviarEmail($id);
        }
    }

    


    /**
     * Envia un correo electrónico al cliente con un PDF adjunto.
     * @return void
     */
    public function enviarEmail($id) {
        // Importar las clases de PHPMailer al espacio de nombres global
        require '../vendor/autoload.php';
        
        // Crear una nueva instancia de PHPMailer
        $mail = new PHPMailer(true);
        
        try {
            // Decirle a PHPMailer que use SMTP
            $mail->isSMTP();
            // Desactivar la depuración SMTP para evitar mostrar detalles extensos
            $mail->SMTPDebug = SMTP::DEBUG_OFF; // Desactiva la depuración
            // Establecer el nombre del servidor de correo
            $mail->Host = 'smtp.gmail.com';
            // Número de puerto SMTP:
            $mail->Port = 465;
            // Establecer el mecanismo de encriptación a usar:
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            // Si se debe usar autenticación SMTP
            $mail->SMTPAuth = true;
            // Nombre de usuario para la autenticación SMTP - usa la dirección de correo completa para Gmail
            $mail->Username = 'gorkacarmonapino@gmail.com';
            // Contraseña para la autenticación SMTP
            $mail->Password = 'eqlu ahyy ykbk dftf';  // Considera usar una variable de entorno para la seguridad
            // Establecer el correo del remitente
            $mail->setFrom('gorkacarmonapino@gmail.com', 'Tienda de Gorka');
            // Establecer una dirección de respuesta alternativa
            $mail->addReplyTo('no-reply@tienda.com', 'Tienda Soporte');
            // Establecer a quién se debe enviar el mensaje
            $mail->addAddress($_SESSION['login']->email, $_SESSION['login']->nombre);
            // Establecer la línea de asunto
            $mail->Subject = 'Factura de su pedido';
    
            // Generar el PDF
            $pdf = new TCPDF();
            $pdf->AddPage();
            $pdf->SetFont('helvetica', '', 12);
            $html = '<h1>Factura del Pedido</h1>';
            $html .= '<p>Nombre: ' . $_SESSION['login']->nombre . '</p>';
            $html .= '<p>ID del Pedido: ' . $id . '</p>';
            $html .= '<p>Fecha: ' . Utils::getFecha() . '</p>';
            $html .= '<p>Hora: ' . Utils::getHora() . '</p>';
            $html .= '<p>Productos:</p>';
            $productos = $this->pedidoService->getProductosPedido($id);
            foreach ($productos as $producto) {
                $html .= '<p>' . $producto['nombre'] . ' - ' . $producto['precio'] . '€</p>';
            }
            $pdf->writeHTML($html, true, false, true, false, '');
            $pdfContent = $pdf->Output('', 'S');
    
            // Adjuntar el PDF al correo
            $mail->addStringAttachment($pdfContent, 'factura.pdf');
    
            // Establecer el cuerpo del mensaje
            $mail->Body = 'Adjunto encontrará la factura de su pedido.';
    
            // Enviar el mensaje, comprobar errores
            if (!$mail->send()) {
                echo 'Error en el correo: ' . $mail->ErrorInfo;
            } else {
                header('Location: ' . BASE_URL . 'pedido/misPedidos');
                exit;
            }
        } catch (Exception $e) {
            echo 'Error en el correo: ' . $mail->ErrorInfo;
        }
    }
}    