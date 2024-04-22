<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/user.class.php');


function drawSearchedProducts() { ?>   
    <main id="main-search">
        <h1>Result for [searched input]</h1>
        <article class="search-item">  
              <div class="img-title-condition">
                <img  src="https://picsum.photos/240/270?business" alt="">
                <div class="title-cond">
                    <div>
                        <h1>Frigorifico Samsung</h1>
                        <h2 class="date-search">21/02/2024</h2>
                    </div>
                    <p><strong>Condição:</strong> Usado</p>
                </div>
              </div>
              <p class="price-search">129,99€</p>
                   
    </article>

    <article class="search-item">  
              <div class="img-title-condition">
                <img  src="https://picsum.photos/240/270?business" alt="">
                <div class="title-cond">
                    <div>
                        <h1>Frigorifico Samsung</h1>
                        <h2 class="date-search">21/02/2024</h2>
                    </div>
                    <p><strong>Condição:</strong> Usado</p>
                </div>
              </div>
              <p class="price-search">129,99€</p>
                   
    </article>
    <article class="search-item">  
              <div class="img-title-condition">
                <img  src="https://picsum.photos/240/270?business" alt="">
                <div class="title-cond">
                    <div>
                        <h1>Frigorifico Samsung</h1>
                        <h2 class="date-search">21/02/2024</h2>
                    </div>
                    <p><strong>Condição:</strong> Usado</p>
                </div>
              </div>
              <p class="price-search">129,99€</p>
                   
    </article>
    <article class="search-item">  
              <div class="img-title-condition">
                <img  src="https://picsum.photos/240/270?business" alt="">
                <div class="title-cond">
                    <div>
                        <h1>Frigorifico Samsung</h1>
                        <h2 class="date-search">21/02/2024</h2>
                    </div>
                    <p><strong>Condição:</strong> Usado</p>
                </div>
              </div>
              <p class="price-search">129,99€</p>
                   
    </article>
    </main>
    
<?php } ?>


