<?php 
use Utils\Utils;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="<?=BASE_URL?>/public/css/Usuario.css">
</head>
<body>
    <section>
        <h1>Recuperar Contraseña</h1>
        <?php if(isset($_SESSION['mensaje'])): ?>
            <strong class="exito"><?= $_SESSION['mensaje'] ?></strong>
            <?php unset($_SESSION['mensaje']); ?>
        <?php elseif(isset($_SESSION['errores'])): ?>
            <strong class="errores"><?= $_SESSION['errores'] ?></strong>
            <?php unset($_SESSION['errores']); ?>
        <?php endif; ?>
        <form action="<?=BASE_URL?>usuario/solicitarRecuperacion/" method="POST">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" required>

            <input type="submit" value="Enviar" required>
        </form>
    </section>
</body>
</html>