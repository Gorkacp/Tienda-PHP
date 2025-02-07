<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Confirmado</title>
</head>
<body>
    <h1>¡Su pedido está confirmado!</h1>
    <p>Señor <?php echo htmlspecialchars($nombre); ?>,</p>
    <p>Le informamos que su pedido está ya en reparto.</p>
    <p>Detalles del pedido:</p>
    <ul>
        <li>ID del pedido: <?php echo htmlspecialchars($idPedido); ?></li>
        <li>
            <table>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                </tr>
                <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?= htmlspecialchars($producto['nombre']) ?></td>
                    <td><?= htmlspecialchars($producto['precio']) ?>€</td>
                </tr>
                <?php endforeach; ?>
            </table>
        </li>
        <li>Fecha: <?php echo htmlspecialchars($fecha) . " " . htmlspecialchars($hora); ?></li>
    </ul>
    <p>Un saludo,</p>
    <p>Tienda de Gorka Carmona Pino</p>
</body>
</html>