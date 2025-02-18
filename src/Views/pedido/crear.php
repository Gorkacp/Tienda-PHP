<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito</title>
    <link rel="stylesheet" href="<?=BASE_URL?>/public/css/Usuario.css">
    <script src="https://www.paypal.com/sdk/js?client-id=AZGWWttOR9uZfl9ONJM_S4jfe8LeUhWITqhht2N94CqL-_wJCrYpnS9IIt2XZjVLtEiqNYD8Q7pBG0CV"></script>
</head>
<body>
<section>
    <h1>Detalles del pedido</h1>
    <h3 style="text-align: center">Introduce la dirección de envío para confirmar el pedido</h3>
    <?php if(!empty($errores)) : ?>
        <div class="errores">
            <?php foreach ($errores as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form id="pedido-form" action="<?=BASE_URL?>pedido/crear" method="POST">
        <label for="provincia">Provincia</label>
        <input type="text" name="provincia" required>

        <label for="localidad">Localidad</label>
        <input type="text" name="localidad" required>

        <label for="direccion">Dirección</label>
        <input type="text" name="direccion" required>

        <input type="submit" value="Confirmar Pedido">
    </form>

    <!-- Contenedor del botón de PayPal -->
    <div id="paypal-button-container"></div>
</section>

<script>
    // Aquí calculamos el valor total del pedido dinámicamente (ejemplo: 100.00)
    var totalPedido = 100.00; // Cambia esto por el cálculo real del total del carrito de compras

    paypal.Buttons({
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: totalPedido // Este valor es el total del pedido
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                alert('Pago completado por ' + details.payer.name.given_name);
                // Aquí se envía el formulario de pedido
                document.getElementById('pedido-form').submit();
            });
        },
        onError: function(err) {
            console.error(err);
            alert("Hubo un error al procesar el pago. Por favor, inténtalo de nuevo.");
        }
    }).render('#paypal-button-container'); // Renderizamos el botón de PayPal
</script>
</body>
</html>
