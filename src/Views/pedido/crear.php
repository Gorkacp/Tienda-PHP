<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito</title>
    <link rel="stylesheet" href="<?=BASE_URL?>/public/css/Usuario.css">
</head>
<body>
    

<section>
    <h1>Detalles del pedido</h1>
    <h3 style="text-align: center">Introduce la dirección de envio para confirmar el pedido</h3>
    <?php if(!empty($errores)) : ?>
        <div class="errores">
            <?php foreach ($errores as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form action="<?=BASE_URL?>pedido/crear" method="POST">
        <label for="provincia">Provincia</label>
        <input type="text" name="provincia" required>

        <label for="localidad">Localidad</label>
        <input type="text" name="localidad" required>

        <label for="direccion">Direccion</label>
        <input type="text" name="direccion" required>

        <input type="submit" value="Confirmar Pedido">
    </form>
</section>
</body>
</html>


