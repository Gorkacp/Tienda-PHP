<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles</title>
    <link rel="stylesheet" href="<?=BASE_URL?>/public/css/detalle.css">
</head>
<body>
<section class="detalles">
    <div class="detalle">
        <div class="img-detalle">
            <img src="<?=BASE_URL?>public/imagenes/<?=$producto['imagen']?>" alt="<?=$producto['nombre']?>">
        </div>
        <div class="detalle-contenido">
            <h2><?= $producto['nombre'] ?></h2>
            <p><?= $producto['descripcion'] ?></p>
            <div class="precio-detalles">
                <p class="preciodetalle">Precio: <b><?=$producto['precio'] ?>€</b></p>
                <a class="añadirCarrito button" href="<?=BASE_URL?>carrito/agregarProducto/?id=<?=$producto['id']?>">
                    <div class="text">Añadir</div>
                    <span class="icon">
                        <i class="ri-shopping-cart-line"></i>
                    </span>
                </a>
            </div>
        </div>
    </div>
</section>
</body>
</html>