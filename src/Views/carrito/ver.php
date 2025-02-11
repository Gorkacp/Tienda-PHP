<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito</title>
    <link rel="stylesheet" href="<?=BASE_URL?>/public/css/carrito.css">
</head>
<body>
<section class="carrito-container">
    <div id="carrito" class="carrito container">
        <h1>Carrito</h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"><?= $_SESSION['error'] ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <?php if (isset($errores)): ?>
            <p class="errores"><?= $errores ?></p>
        <?php endif; ?>
        
        <table>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Total</th>
            </tr>
            <?php if (empty($_SESSION['carrito'])): ?>
                <tr>
                    <td colspan="3">No hay productos en el carrito</td>
                </tr>
            <?php else: ?>
                <?php foreach ($productos as $producto): ?>
                <tr>
                    <td>
                        <div class="productos-carrito">
                            <img src="<?=BASE_URL?>imagenes/<?=$producto['imagen']?>" alt="">
                            <div class="productos-carritoinfo">
                                <p><?= $producto['nombre'] ?></p>
                                <p><?= $producto['precio'] ?>€</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="cantidad-carrito">
                            <div>
                                <label><a href="<?=BASE_URL?>carrito/disminuirCantidad/?id=<?=$producto['id']?>"> - </a></label>
                                <label><?= $producto['cantidad'] ?></label>
                                <?php if ($producto['cantidad'] < $producto['stock']): ?>
                                    <label><a href="<?=BASE_URL?>carrito/aumentarCantidad/?id=<?=$producto['id']?>"> + </a></label>
                                <?php else: ?>
                                    <label class="no-stock"> + </label>
                                <?php endif; ?>
                            </div>
                            <a href="<?=BASE_URL?>carrito/eliminarProducto/?id=<?=$producto['id']?>" class="eliminar">
                                <i class="ri-delete-bin-2-line"></i>
                            </a>
                        </div>
                    </td>
                    <td><p><?= $producto['precio'] * $producto['cantidad'] ?>€</p></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>

        <div class="carrito-total">
            <div class="pagar">
                <?php if (empty($_SESSION['carrito'])): ?>
                    <p>No hay productos para realizar el pedido.</p>
                <?php else: ?>
                    <a href="<?=BASE_URL?>pedido/mostrarPedido">                        
                        <button class="button" data-text="Awesome">
                            <span class="actual-text">&nbsp;Pedido&nbsp;</span>
                            <span aria-hidden="true" class="hover-text">&nbsp;Pedido&nbsp;</span>
                        </button>
                    </a>
                <?php endif; ?>
            </div>
            
            <div class="total">
                <?php if (empty($_SESSION['carrito'])): ?>
                    <p>Total: <b>0€</b></p>
                    <p>Número de productos: 0</p>
                <?php else: ?>
                    <p>Total: <b><?=$total?>€</b></p>
                    <p>Número de productos: <?= count($_SESSION['carrito']) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
</body>
</html>