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
        $model = Item::getItemModel($db, $itemId);
        $sellerId = $item->sellerId;
        $user = User::getUser($db, $sellerId);
        
        
   
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
                        <div class="user-info">
                            <h1>Username: <?= htmlspecialchars($user->username) ?></h1>
                            <div class="location-info">
                                <h2>Location:</h2>
                                <p><?= !empty($user->address) ? htmlspecialchars($user->address) : " - " ?></p>
                                <p><?= !empty($user->city) ? htmlspecialchars($user->city) : " - " ?></p>
                                <p><?= !empty($user->district) ? htmlspecialchars($user->district) : " - " ?></p>
                                <p><?= !empty($user->country) ? htmlspecialchars($user->country) : " - " ?></p>

                            </div>
                            <h2>Phone: <?= !empty($user->phone) ? htmlspecialchars($user->phone)  : " - "?></h2>
                            <h2>Email: <?= !empty($user->email) ? htmlspecialchars($user->email) : " - "?></h2>
                        </div>
                    </div>

                    
                    <div id="specs-post">
                        <p class="spec-type">Brand:</p> 
                        <p class="spec"><?= !empty($brand->brandName) ? htmlspecialchars($brand->brandName) : " - " ?></p>
                        <p class="spec-type">Condition:</p> 
                        <p class="spec"><?= !empty($condition->conditionName) ? htmlspecialchars($condition->conditionName) : " - " ?></p>
                        <p class="spec-type">Size:</p> 
                        <p class="spec"><?= !empty($size->sizeName) ? htmlspecialchars($size->sizeName) : " - " ?></p>
                        <p class="spec-type">Model:</p> 
                        <p class="spec"><?= !empty($model->modelName) ? htmlspecialchars($model->modelName) : " - " ?></p>
                    </div>
                </aside>
                <div id="description-post">
                    <div>
                        <h2 id="description-post-h2">Description</h2>
                        <p><?= !empty($item->description) ? htmlspecialchars($item->description) : "No description" ?></p>
                    </div>
                    <p id="date-post"><?= !empty($item->listingDate) ? htmlentities($item->listingDate) : " No Date " ?></p>
                </div>
            </section>
        </main>
        <?php
    } catch (PDOException $e) {
        echo "Error fetching item details: " . $e->getMessage();
    }
}
?>