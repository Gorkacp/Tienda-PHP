<?php 
use Utils\Utils;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="<?=BASE_URL?>/public/css/Usuario.css">
</head>
<body>
    <section>
        <h1>Restablecer Contraseña</h1>
        <?php if(isset($mensaje)): ?>
            <strong class="exito"><?= $mensaje ?></strong>
        <?php elseif(isset($errores)): ?>
            <strong class="errores"><?= $errores ?></strong>
        <?php endif; ?>
        <form action="<?=BASE_URL?>usuario/restablecer" method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>" required>
            <label for="password">Nueva Contraseña</label>
            <input type="password" name="password" id="password" required>

            <input type="submit" value="Restablecer">
        </form>
    </section>
</body>
</html>