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
                <div class="cart-item">
                  <div class="img-name-condition">
                    <img class="img-product-cart" src="https://picsum.photos/240/270?business" alt="" height="100">
                    <div class="name-condition">
                      <h1>Frigorifico Samsung</h1>
                      <p><strong>Condição:</strong> Usado</p>
                    </div>
                  </div>
                  <p><strong>Brand:</strong> Samsung</p>
                  <p>129,99€</p>
                  <button id="profile-button">
                    <img src="/Docs/img/8664938_trash_can_delete_remove_icon.png" alt="" width="20">
                  </button> 
                  
                  
                </div>
                <div class="cart-item">
                  <div class="img-name-condition">
                    <img class="img-product-cart" src="https://picsum.photos/240/270?business" alt="" height="100">
                    <div class="name-condition">
                      <h1>Frigorifico Samsung</h1>
                      <p><strong>Condição:</strong> Usado</p>
                    </div>
                  </div>
                  <p><strong>Brand:</strong> Samsung</p>
                  <p>129,99€</p>
                  <button id="profile-button">
                    <img src="/Docs/img/8664938_trash_can_delete_remove_icon.png" alt="" width="20">
                  </button> 
                  
                  
                </div>
                <div class="cart-item">
                  <div class="img-name-condition">
                    <img class="img-product-cart" src="https://picsum.photos/240/270?business" alt="" height="100">
                    <div class="name-condition">
                      <h1>Frigorifico Samsung</h1>
                      <p><strong>Condição:</strong> Usado</p>
                    </div>
                  </div>
                  <p><strong>Brand:</strong> Samsung</p>
                  <p>129,99€</p>
                  <button id="profile-button">
                    <img src="/Docs/img/8664938_trash_can_delete_remove_icon.png" alt="" width="20">
                  </button> 
                  
                  
                </div>
              </div>
              <div class="cart-total">
                <h1>Total Price:</h1> 
                <p class="total-price">100.00€</p>
                <button id="add-cart-button">
                  Pay
                </button> 
              </div>
            </section>
          </main>


<?php } ?>