<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>
    <link rel="stylesheet" href="<?=BASE_URL?>/public/css/pedidos.css">
</head>
<body>
    
<?php if(($_SESSION['login']->rol == 'admin') && (isset($_SESSION['login']))): ?>
<section>
    <h1>Gestionar Pedidos</h1>
    
    <?php if (!empty($errores)): ?>
        <div class="errores">
            <?php foreach ($errores as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($pedidos) && count($pedidos) > 0): ?>
    <table>
        <tr>
            <th>Id</th>
            <th>Coste</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Estado</th>
            <th>ID Usuario</th>
            <th>Confirmar Pedido</th>
            <th>Opciones</th>
        </tr>
        <?php foreach ($pedidos as $pedido): ?>
            <?php if((isset($_GET['id'])) && ($_GET['id'] == $pedido['id'])): ?>
            <tr>
                <form action="<?=BASE_URL?>pedido/actualizar" method="post">
                    <td><input type="text" name="data[id]" value="<?=$pedido['id']?>"></td>
                    <td><input type="text" name="data[coste]" value="<?=$pedido['coste']?>"></td>
                    <td><input type="text" name="data[fecha]" value="<?=$pedido['fecha']?>"></td>
                    <td><input type="text" name="data[hora]" value="<?=$pedido['hora']?>"></td>
                    <td>
                        <select name="data[estado]">
                            <option value="pendiente" <?= $pedido['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                            <option value="confirmado" <?= $pedido['estado'] == 'confirmado' ? 'selected' : '' ?>>Confirmado</option>
                        </select>
                    </td>
                    <td><input type="text" name="data[usuario_id]" value="<?=$pedido['usuario_id']?>"></td>
                    <td>
                        <input type="hidden" name="id" value="<?=$pedido['id']?>">
                        <input type="submit" value="Guardar">
                    </td>
                </form>
            </tr>
            <?php else: ?>
        <tr>
            <td><a href="<?=BASE_URL?>pedido/ver/?id=<?=$pedido['id']?>"><?= $pedido['id'] ?></a></td>
            <td><?= $pedido['coste'] ?>€</td>
            <td><?= $pedido['fecha'] ?></td>
            <td><?= $pedido['hora'] ?></td>
            <td><?= $pedido['estado'] ?></td>
            <td><?= $pedido['usuario_id'] ?></td>
            <td><a href="<?=BASE_URL?>pedido/confirmarPedido/?id=<?=$pedido['id']?>"><i class="ri-check-line"></i>Confirmar</a></td>
            <td><div>
                <a href="<?=BASE_URL?>pedido/eliminar/?id=<?=$pedido['id']?>"><i class="ri-delete-bin-2-line"></i>eliminar</a>
                <a href="<?=BASE_URL?>pedido/editar/?id=<?=$pedido['id']?>"><i class="ri-edit-line"></i>editar</a>
            </div></td>
        </tr>
        <?php endif; ?>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <strong class="alert_red">No tienes pedidos</strong>
    <?php endif; ?>
</section>
<?php else: ?>
    <h2>No tienes permiso para entrar en esta página</h2>
<?php endif; ?>
</body>
</html>