<?php
    use Controllers\CategoriaController;
    $categoria = (new CategoriaController())->obtenerCategorias();

    // Verificar si el usuario intenta acceder a una sección de administración sin ser admin
    if (isset($_GET['url']) && strpos($_GET['url'], 'gestionar') !== false) {
        if (!isset($_SESSION['login']) || $_SESSION['login']->rol !== 'admin') {
            header('Location: ' . BASE_URL . 'error/error.php');
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda</title>
    <link rel="stylesheet" href="<?=BASE_URL?>/public/css/header.css">
    <!-- Agregar Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Incluye jQuery para la manipulación de eventos -->
</head>
<body>

    <header>
        <nav class="navbar">
            <div class="navbar-container">
                <!-- Menú de Inicio y Categorías (Izquierda) -->
                <div class="menu-left">
                    <ul>
                        <li><a href="<?= BASE_URL ?>">Inicio</a></li>
                        <li class="submenu" id="categorias">
                            <a href="javascript:void(0);">Categorías</a>
                            <ul class="submenu-items">
                                <?php foreach ($categoria as $categorias): ?>
                                    <li><a href="<?=BASE_URL?>categoria/ver/?id=<?=$categorias['id']?>"><?=$categorias['nombre']?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    </ul>
                </div>

                <!-- Sección de inicio de sesión o datos del usuario (Derecha) -->
                <div class="user-actions">
                    <?php if (!isset($_SESSION['login']) OR $_SESSION['login'] == 'failed'): ?>
                        <a href="<?=BASE_URL?>usuario/login/">Iniciar Sesión</a>
                        <a href="<?=BASE_URL?>usuario/registro/">Registrarse</a>
                    <?php else: ?>
                        <p class="user-name"><?=$_SESSION['login']->nombre?> <?=$_SESSION['login']->apellidos?></p>
                        <a href="<?=BASE_URL?>usuario/logout/">Cerrar Sesión</a>
                        <a href="<?=BASE_URL?>pedido/misPedidos/">Mis Pedidos</a>
                    <?php endif; ?>
                        <a href="<?=BASE_URL?>carrito/obtenerCarrito/" class="cart-link">
                            <i class="fas fa-shopping-cart"></i> <!-- Icono de carrito -->
                                <?= isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0 ?> <!-- Número de productos -->
                        </a>
                    <?php if (isset($_SESSION['login']) && is_object($_SESSION['login'])): ?>
                        <?php if ($_SESSION['login']->rol == 'admin'): ?>
                            <a href="<?=BASE_URL?>usuario/verTodos/">Gestionar Usuarios</a>
                            <a href="<?=BASE_URL?>categoria/gestionarCategorias/">Gestionar Categorias</a>
                            <a href="<?=BASE_URL?>producto/gestionarProductos/">Gestionar Productos</a>
                            <a href="<?=BASE_URL?>pedido/todosLosPedidos/">Gestionar Pedidos</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>

</body>
</html>