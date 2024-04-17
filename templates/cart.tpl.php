<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/user.class.php');
function drawCart(Session $session,$db) { ?>  
    <main>
    <section id="cart">
        <div class="cart-items">
        <div class="cart-item">Product 1</div>
        <div class="cart-item">Product 2</div>
        <div class="cart-item">Product 3</div>
        </div>
        <div class="cart-total">
        Total Price: <span class="total-price">$100.00</span>
        </div>
    </section>
    </main>

<?php } ?>