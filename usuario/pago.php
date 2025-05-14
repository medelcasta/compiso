<form action="procesar_pago.php" method="post">
    <h2>Pago Premium</h2>
    <p>Elige tu método de pago:</p>
    <label>
        <input type="radio" name="metodo_pago" value="tarjeta" checked>
        Tarjeta de crédito
    </label>
    <label>
        <input type="radio" name="metodo_pago" value="paypal">
        PayPal
    </label>
    <label>
        <input type="radio" name="metodo_pago" value="transferencia">
        Transferencia bancaria
    </label>
    <label>
        <input type="radio" name="metodo_pago" value="efectivo">
        Efectivo
    </label>
    <p>Precio: <span id="precio">10.00</span> €</p>
    <p>Selecciona el tipo de suscripción:</p>
    <label>
        <input type="radio" name="tipo_suscripcion" value="mensual" checked>
        Mensual
    </label>
    <label>
        <input type="radio" name="tipo_suscripcion" value="anual">
        Anual
    </label>
    <p>Detalles de la suscripción:</p>
    <ul>
        <li>Acceso a todas las propiedades premium</li>
        <li>Soporte prioritario</li>
        <li>Descuentos exclusivos</li>
    </ul>
    <p>Información de contacto:</p>
    <label for="nombre">Nombre:</label>
    <input type="text" name="nombre" id="nombre" required>
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>
    <label for="telefono">Teléfono:</label>
    <input type="tel" name="telefono" id="telefono" required>
    <label for="direccion">Dirección:</label>
    <input type="text" name="direccion" id="direccion" required>
    <label for="ciudad">Ciudad:</label>
    <input type="text" name="ciudad" id="ciudad" required>
    <label for="codigo_postal">Código Postal:</label>
    <input type="text" name="codigo_postal" id="codigo_postal" required>
    <label for="pais">País:</label>
    <input type="text" name="pais" id="pais" required>
    <label for="metodo_pago">Método de pago:</label>
    <select name="metodo_pago" id="metodo_pago" required>
        <option value="tarjeta">Tarjeta de crédito</option>
        <option value="paypal">PayPal</option>
        <option value="transferencia">Transferencia bancaria</option>
        <option value="efectivo">Efectivo</option>
    </select>

    <input type="hidden" name="precio" value="<?php echo $precio; ?>">
    <button type="submit">Pagar con PayPal</button>
</form>
