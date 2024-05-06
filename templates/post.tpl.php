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
        $userId = $session->getId();

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
                            <?php if($userId == $sellerId) { ?>
                                <form action="../actions/delete_item.php" method="post">
                                    <input type="hidden" name="item_id" value="<?= $item->itemId ?>">
                                    <button type="submit" class="cart-button-post">Delete</button>
                                </form>
                            <?php } else { ?>
                                <form action="../actions/add_to_cart.php" method="post" >
                                    <input type="hidden" name="item_id" value="<?= $item->itemId ?>">
                                    <button type="submit" class="cart-button-post">Add to Cart</button> 
                                </form>
                            <?php } ?>

                            
                        </form>
                        </div>
                    </div>
                    <div id="user-post">
                        <img id="img-user-post" src="<?= !empty($user->imageUrl) ? $user->imageUrl : "/Docs/img/9024845_user_circle_light_icon.png"?>" alt="" width="100">
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






function drawPostCreation($session, $db) {
    $images= Item::getAllImages($db);
    var_dump($images);
    
    ?>
    
     
    <main>
        <section class="publish-section">
            <h1>Publish Item</h1>
            <form method="post" action="../actions/action_add_item.php" enctype="multipart/form-data">
                <div class="publish-div">
                    <label>
                        Product Name <input type="text" name="productname" required>
                    </label>
                    <label>
                        Price  
                        <div>
                            <input id="price-input" type="number" name="price" min="0" step="0.01" required><span class="currency-symbol">€</span>
                        </div>
                    </label>
                </div>
                <div class="publish-div">
                    <label>
                        Description <input id="description-input" type="text" name="description" placeholder="Write description here" required>
                    </label>
                </div>
                <div class="publish-div">
                    <label for="model">Model
                        <select name="model" id="model" class="publish-select">
                        <option></option>
                            <?php 
                            $models = Item::getModels($db);
                            foreach($models as $model) {
                                echo '<option value="' . htmlspecialchars($model) . '">' . htmlspecialchars($model) . '</option>';
                            }
                            ?>
                        </select>
                    </label>
                    <label for="brand">Brand
                        <select name="brand" id="brand" class="publish-select">
                        <option></option>
                            <?php 
                            $brands = Item::getBrands($db);
                            foreach($brands as $brand) {
                                echo '<option value="' . htmlspecialchars($brand) . '">' . htmlspecialchars($brand) . '</option>';
                            }
                            ?>
                        </select>
                    </label>
                    <label>
                        Condition 
                        <select name="condition" class="publish-select" required>
                            <option></option>
                            <?php 
                                $conditions = Item::getConditionsObj($db);
                                foreach($conditions as $cond) {
                                    ?> <option value="<?= $cond->conditionName ?>"><?= $cond->conditionName ?></option>
                                    
                                    <?php
                                }
                            ?>
                        </select>
                    </label>
                    <label>
                        Size
                        <select name="size" class="publish-select" required>
                            <option></option>
                            <?php 
                                $conditions = Item::getSizesObj($db);
                                foreach($conditions as $cond) {
                                    ?> <option value="<?= htmlentities($cond->sizeName) ?>"><?= htmlspecialchars($cond->sizeName) ?></option>
                                    
                                    <?php
                                }
                            ?>
                        </select>
                    </label>
                </div>
                <div class="publish-div">
                    <h1>Images</h1>
                    <div class="image-container">
                        <label class="image-input">
                            <input name="images[]" type="file" accept="image/heic, image/png, image/jpeg, image/webp" multiple onchange="previewImages(event, 0)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 32 32"><path fill="currentColor" d="M29 26H3a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h6.46l1.71-2.55A1 1 0 0 1 12 4h8a1 1 0 0 1 .83.45L22.54 7H29a1 1 0 0 1 1 1v17a1 1 0 0 1-1 1M4 24h24V9h-6a1 1 0 0 1-.83-.45L19.46 6h-6.92l-1.71 2.55A1 1 0 0 1 10 9H4Z"/><path fill="currentColor" d="M16 22a6 6 0 1 1 6-6a6 6 0 0 1-6 6m0-10a4 4 0 1 0 4 4a4 4 0 0 0-4-4"/></svg>
                            <img class="preview-image" id="preview-image-0" src="" alt="">
                        </label>

                        <label class="image-input">
                            <input name="images[]" type="file" accept="image/heic, image/png, image/jpeg, image/webp" multiple onchange="previewImages(event,1)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 32 32"><path fill="currentColor" d="M29 26H3a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h6.46l1.71-2.55A1 1 0 0 1 12 4h8a1 1 0 0 1 .83.45L22.54 7H29a1 1 0 0 1 1 1v17a1 1 0 0 1-1 1M4 24h24V9h-6a1 1 0 0 1-.83-.45L19.46 6h-6.92l-1.71 2.55A1 1 0 0 1 10 9H4Z"/><path fill="currentColor" d="M16 22a6 6 0 1 1 6-6a6 6 0 0 1-6 6m0-10a4 4 0 1 0 4 4a4 4 0 0 0-4-4"/></svg>
                            <img class="preview-image" id="preview-image-1" src="" alt="">
                        </label>

                        <label class="image-input">
                            <input name="images[]" type="file" accept="image/heic, image/png, image/jpeg, image/webp" multiple onchange="previewImages(event,2)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 32 32"><path fill="currentColor" d="M29 26H3a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h6.46l1.71-2.55A1 1 0 0 1 12 4h8a1 1 0 0 1 .83.45L22.54 7H29a1 1 0 0 1 1 1v17a1 1 0 0 1-1 1M4 24h24V9h-6a1 1 0 0 1-.83-.45L19.46 6h-6.92l-1.71 2.55A1 1 0 0 1 10 9H4Z"/><path fill="currentColor" d="M16 22a6 6 0 1 1 6-6a6 6 0 0 1-6 6m0-10a4 4 0 1 0 4 4a4 4 0 0 0-4-4"/></svg>
                            <img class="preview-image" id="preview-image-2" src="" alt="">
                        </label>

                        <label class="image-input">
                            <input name="images[]" type="file" accept="image/heic, image/png, image/jpeg, image/webp" multiple onchange="previewImages(event,3)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 32 32"><path fill="currentColor" d="M29 26H3a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h6.46l1.71-2.55A1 1 0 0 1 12 4h8a1 1 0 0 1 .83.45L22.54 7H29a1 1 0 0 1 1 1v17a1 1 0 0 1-1 1M4 24h24V9h-6a1 1 0 0 1-.83-.45L19.46 6h-6.92l-1.71 2.55A1 1 0 0 1 10 9H4Z"/><path fill="currentColor" d="M16 22a6 6 0 1 1 6-6a6 6 0 0 1-6 6m0-10a4 4 0 1 0 4 4a4 4 0 0 0-4-4"/></svg>
                            <img class="preview-image" id="preview-image-3" src="" alt="">
                        </label>
                        
                    </div>
                </div>
                <div class="publish-div">
                    <h1>Categories</h1>        
                    <label>
                        <select name="category1" class="publish-select">
                            <option value="none">- None -</option>
                            <?php 
                                $categories = Item::getCategories($db);
                                foreach($categories as $categ) {
                                    ?> <option value="<?= htmlentities($categ) ?>"><?= htmlspecialchars($categ) ?></option>
                                    
                                    <?php
                                }
                            ?>
                        </select>
                    </label>
                    <label>
                        <select name="category2" class="publish-select">
                            <option value="none">- None -</option>
                            <?php 
                                foreach($categories as $categ) {
                                    ?> <option value="<?= htmlentities($categ) ?>"><?= htmlspecialchars($categ) ?></option>
                                    
                                    <?php
                                }
                            ?>
                        </select>
                    </label>
                    <label>
                        <select name="category3" class="publish-select">
                            <option value="none">- None -</option>
                            <?php 
                                foreach($categories as $categ) {
                                    ?> <option value="<?= htmlentities($categ) ?>"><?= htmlspecialchars($categ) ?></option>
                                    
                                    <?php
                                }
                            ?>
                        </select>
                    </label>
                </div>
                <button type="submit">Post</button>
            </form>
        </section>
    </main>
    
<script>
        
function previewImages(event, startIndex) {
    const files = event.target.files;
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();
        const previewImage = document.getElementById(`preview-image-${startIndex + i}`);

        reader.onloadend = function () {
            previewImage.style.display = "block";
            previewImage.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            previewImage.style.display = "none"; // Hide the preview image if no file is selected
            previewImage.src = "";
        }
    }
}

</script>


    </script>
<?php
}
?>
