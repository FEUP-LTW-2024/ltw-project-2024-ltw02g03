<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/user.class.php');

function drawPost(Session $session, $db, int $itemId) { 
    
    try {
        $item = Item::getItem($db, $itemId);
        
          
        if (!$item) {
            echo "<p>Item not found.</p>";
            return;
        }

        $condition = Item::getItemCondition($db, $itemId);
        $brand = Item::getItemBrand($db, $itemId);    
        $image = Item::getItemImage($db, $itemId);
        $size = Item::getItemSize($db, $itemId);
        //$user = User::getUser($db, $item->sellerId);
        
        ?>
        <main>
            <section id="post">
                <div id="product-img-post">
                    <button class="img-button"><</button>
                    <img src="../<?= $image[0]->imageUrl ?>" alt="">
                    <button class="img-button">></button>  
                </div>
                <aside id="user-aside">
                    <div id="price-post">
                        <h2 id="product-title-post"><?= htmlspecialchars($item->title) ?></h2>
                        <div id="post-price-button">
                            <h1><?= number_format($item->price, 2) ?>€</h1>
                            <form action="../actions/add_to_cart.php" method="post" >
                            <input type="hidden" name="item_id" value="<?= $item->itemId ?>">
                            <button type="submit" class="cart-button-post">Add to Cart</button> 
                        </form>
                        </div>
                    </div>
                    <div id="user-post">
                        <img src="/Docs/img/9024845_user_circle_light_icon.png" alt="" width="100">
                        <h1><?= htmlspecialchars($item->sellerUsername) ?></h1>
                        <h2><?= htmlspecialchars($item->contactNumber) ?></h2>
                        <h3><?= htmlspecialchars($item->sellerLocation) ?></h3>
                    </div>
                    <div id="specs-post">
                        <p class="spec-type">Brand:</p> 
                        <p class="spec"><?= htmlspecialchars($brand->brandName) ?></p>
                        <p class="spec-type">Condition:</p> 
                        <p class="spec"><?= htmlspecialchars($condition->conditionName) ?></p>
                        <p class="spec-type">Size:</p> 
                        <p class="spec"><?= htmlspecialchars($item->size) ?></p>
                        <p class="spec-type">Model:</p> 
                        <p class="spec"><?= htmlspecialchars($item->model) ?></p>
                    </div>
                </aside>
                <div id="description-post">
                    <div>
                        <h2 id="description-post-h2">Description</h2>
                        <p><?= htmlspecialchars($item->description) ?></p>
                    </div>
                    <p id="date-post"><?= $item->postDate ?></p>
                </div>
            </section>
        </main>
        <?php
    } catch (PDOException $e) {
        echo "Error fetching item details: " . $e->getMessage();
    }
}
?>