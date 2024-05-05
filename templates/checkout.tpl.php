<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/cart.class.php');
require_once(__DIR__ . '/../database/item.class.php'); 
require_once(__DIR__ . '/../database/user.class.php');



function drawCheckout(Session $session, $db, $item, $userId) {
    $itemId = $item->itemId;
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
                        <option value="" selected disabled>Selecione...</option>
                        <option value="credit_card">Cartão de Crédito</option>
                        <option value="paypal">PayPal</option>
                    </select><br>
                    
                    <div id="credit_card_fields" style="display: none;">
                        <label for="card_number">Número do Cartão:</label>
                        <input type="text" id="card_number" name="card_number"required ><br>
                        <label for="card_holder">Nome no Cartão:</label>
                        <input type="text" id="card_holder" name="card_holder"required><br>
                        <label for="expiration_date">Data de Expiração:</label>
                        <input type="text" id="expiration_date" name="expiration_date"required><br>
                        <label for="cvv">CVV:</label>
                        <input type="text" id="cvv" name="cvv"required><br>
                    </div>
                    
                    <div id="paypal_fields" style="display: none;">
                        <label for="paypal_email">Endereço de Email do PayPal:</label>
                        <input type="email" id="paypal_email" name="paypal_email"required><br>
                    </div>
                    <input type="hidden" name="checkout_submitted" value="1">
                    <input type="hidden" name="item_ids[]" value="<?= $itemId ?>">

                    <button type="submit" id="finalizar_compra_btn" onclick="simulatePurchaseAndRedirect()">Finalizar Compra</button>
                </form>
            </section>
        </main>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('payment_method').addEventListener('change', function() {
        var paymentMethod = this.value;
        
        var allFields = document.querySelectorAll('#credit_card_fields input, #paypal_fields input');
        allFields.forEach(function(field) {
            field.removeAttribute('required');
        });
        
        if (paymentMethod === 'credit_card') {
            document.getElementById('credit_card_fields').style.display = 'block';
            document.getElementById('paypal_fields').style.display = 'none';
            
            var creditCardFields = document.querySelectorAll('#credit_card_fields input');
            creditCardFields.forEach(function(field) {
                field.setAttribute('required', '');
            });
        } else if (paymentMethod === 'paypal') {
            document.getElementById('credit_card_fields').style.display = 'none';
            document.getElementById('paypal_fields').style.display = 'block';
            
            var paypalFields = document.querySelectorAll('#paypal_fields input');
            paypalFields.forEach(function(field) {
                field.setAttribute('required', '');
            });
        } else {
            document.getElementById('credit_card_fields').style.display = 'none';
            document.getElementById('paypal_fields').style.display = 'none';
        }
    });
});

        </script>
    <?php
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>
