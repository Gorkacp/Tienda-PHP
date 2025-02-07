<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda</title>
    <link rel="stylesheet" href="<?=BASE_URL?>/public/css/productos.css">
</head>
<body>
<section>
    <h1>Productos Destacados</h1>
    <div class="productos-grid">
        <?php foreach ($productos as $producto): ?>
            <div class="producto-card">
                <a href="<?=BASE_URL?>producto/verDetalles/?id=<?=$producto['id']?>">
                    <img src="<?=BASE_URL?>imagenes/<?=$producto['imagen']?>" class="producto-img">
                </a>
                <h3><?=$producto['nombre']?></h3>
                <h2><?=$producto['precio']?>€</h2>
                <a href="<?=BASE_URL?>carrito/agregarProducto/?id=<?=$producto['id']?>" class="btn-agregar">Añadir al carrito</a>
            </div>
        <?php endforeach; ?>
    </div>
</section>
</body>
</html>