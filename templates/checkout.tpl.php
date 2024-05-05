<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/cart.class.php');
require_once(__DIR__ . '/../database/item.class.php'); 
require_once(__DIR__ . '/../database/user.class.php');


function drawCheckout(Session $session, $db, $item, $userId) {
    try {
        // Obtém as informações do usuário do banco de dados
        $user = User::getUser($db, $userId);

        ?>
        <main>
            <section id="checkout">
                <h2>Checkout</h2>
                
                <!-- Formulário de checkout -->
                <form action="/../actions/action_checkout.php" method="post">
                    <!-- Informações de envio -->
                    <h3>Informações de Envio</h3>
                    <label for="address">Endereço:</label>
                    <input type="text" id="address" name="address" value="<?= htmlspecialchars($user->address) ?>" required><br>
                    <label for="city">Cidade:</label>
                    <input type="text" id="city" name="city" value="<?= htmlspecialchars($user->city) ?>" required><br>
                    <label for="district">Distrito:</label>
                    <input type="text" id="district" name="district" value="<?= htmlspecialchars($user->district) ?>" required><br>
                    <label for="country">País:</label>
                    <input type="text" id="country" name="country" value="<?= htmlspecialchars($user->country) ?>" required><br>
                    
                    <h3>Opções de Pagamento</h3>
                    <label for="payment_method">Método de Pagamento:</label>
                    <select id="payment_method" name="payment_method" required>
                        <option value="credit_card">Cartão de Crédito</option>
                        <option value="paypal">PayPal</option>
                    </select><br>
                    
                    <div id="credit_card_fields" style="display: none;">
                        <label for="card_number">Número do Cartão:</label>
                        <input type="text" id="card_number" name="card_number"><br>
                    </div>
                    
                    <div id="paypal_fields" style="display: none;">
                        <label for="paypal_email">Endereço de Email do PayPal:</label>
                        <input type="email" id="paypal_email" name="paypal_email"><br>
                    </div>

                    <!-- Botão de Envio -->
                    <button type="submit">Finalizar Compra</button>
                </form>
            </section>
        </main>

        <?php
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>
<script>
    document.getElementById('payment_method').addEventListener('change', function() {
        var paymentMethod = this.value;
        
        if (paymentMethod === 'credit_card') {
            document.getElementById('credit_card_fields').style.display = 'block';
            document.getElementById('paypal_fields').style.display = 'none';
        } else if (paymentMethod === 'paypal') {
            document.getElementById('credit_card_fields').style.display = 'none';
            document.getElementById('paypal_fields').style.display = 'block';
        } else {
            document.getElementById('credit_card_fields').style.display = 'none';
            document.getElementById('paypal_fields').style.display = 'none';
        }
    });
</script>

    