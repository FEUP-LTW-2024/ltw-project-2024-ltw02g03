<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/user.class.php');
function drawPost(Session $session,$db) { ?>  
    <main>

        <section id="post">
            <div id="product-img-post">
                <button class="img-button"><</button>
                <img src="https://picsum.photos/240/270?business" alt="">
                <button class="img-button">></button>  
            </div>

              <aside id="user-aside">
                <div id="price-post">
                    <h2 id="product-title-post">Monitor 140hz</p>
                    <h1>1000€</h1>
                </div>
                
                <div id="user-post">
                    <img src="/Docs/img/9024845_user_circle_light_icon.png" alt="" width="100">
                    <h1>Username</h1>
                    <h2>+351 966 555 555</h2>
                    <h3>Portugal</h3>
                    <h3>Guarda, Guarda</h3>
                </div>

                <div id="specs-post">
                    <p>Brand:</p> 
                    <p class="spec">MSI</p>
                    <p>Condition:</p> 
                    <p class="spec">Factory New</p>
                    <p>Size:</p> 
                    <p class="spec">Large</p>
                    <p>Model:</p> 
                    <p class="spec">G32yk200</p>
                </div>
                
            </aside>

            <div id="description-post">
                
                    
                <h2 id="description-post-h2">Description</h2>
                        
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                    when an unknown printer took a galley of type and scrambled it to make a type 
                    specimen book. It has survived not only five centuries, but also the leap into
                    electronic
                </p>
                <p id="date-post">12/04/2023</p>
            </div>

              
              
        </section>
    </main>


<?php } ?>