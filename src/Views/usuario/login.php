<?php 
use Utils\Utils;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="<?=BASE_URL?>/public/css/Usuario.css">
</head>
<body>
    <section>
        <h1>Login</h1>
        <?php if(isset($_SESSION['login']) && $_SESSION['login'] == 'complete'): ?>
            <strong class="exito">Login completado correctamente</strong>
        <?php elseif(isset($_SESSION['login']) && $_SESSION['login'] == 'failed'):?>
            <strong class="errores">No se ha podido iniciar sesión, el correo o la contraseña no son correctos</strong>
            <?php Utils::deleteSession('login'); ?>
            <?php if (!empty($errores)): ?>
            <div class="errores">
                <?php foreach ($errores as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php endif;?>
        <?php if(!isset($_SESSION['login']) OR $_SESSION['login'] == 'failed'):?>
        <form action="<?=BASE_URL?>usuario/login/" method="POST">
            <label for="email">Email</label>
            <input type="text" name="data[email]" id="email" required>

            <label for="password">Contraseña</label>
            <input type="password" name="data[password]" id="password" required>

            <p>¿No tienes una cuenta? <a href="<?=BASE_URL?>usuario/registro">Regístrate aquí</a></p>
            <p>¿Olvidaste tu contraseña? <a href="<?=BASE_URL?>usuario/recuperar">Recupérala aquí</a></p>

            <input type="submit" value="Login" required>
        </form>
    </section>

    <?php endif;?>
</body>
</html>