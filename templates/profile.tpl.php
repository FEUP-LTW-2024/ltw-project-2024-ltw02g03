<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/user.class.php');

function drawProfile(Session $session, $db)
{
    
    try {
        // Redirect if user is not logged in
        if (!$session->isLoggedIn()) {
            header('Location: /pages/login.php');
            exit();
        }

        // Get user ID from session
        $userId = $session->getId();

        // Get user data
        $user = User::getUser($db, $userId);

        $presentedProducts = User::fetchPresentedProducts($db, $userId);
        
    
        ?>
        <main id="profilepage">
            <div id="profile-img-infos">
                <img id="profile-img" src="<?= !empty($user->imageUrl) ? htmlspecialchars($user->imageUrl) : "../Docs/img/9024845_user_circle_light_icon.png" ?>" alt="" height="100">
                <div id="profilepage-name-loc">
                    <h1><?= htmlspecialchars($user->name()) ?></h1>
                    <h2><?= !empty($user->city) ? htmlspecialchars($user->city) : " - " ?>, <?= !empty($user->district) ? htmlspecialchars($user->district) : " - " ?>, <?= !empty($user->country) ? htmlspecialchars($user->country) : " - "?></h2>
                </div>
            </div>
            <div id="left-profile-page">
                <?php if ($session->isAdmin()): ?>
                    <div id="admin-section">
                        <h2>Admin Section</h2>
                        <form action="/../actions/action_create_fields.php" method="post">
                            <label for="brandName">New Brand:</label>
                            <input type="text" id="brandName" name="brandName" required>
                            <button type="submit" name="createBrand">Create Brand</button>
                        </form>
                        <form action="/../actions/action_create_fields.php" method="post">
                            <label for="conditionName">New Condition:</label>
                            <input type="text" id="conditionName" name="conditionName" required>
                            <button type="submit" name="createCondition">Create Condition</button>
                        </form>

                        <form action="/../actions/action_create_fields.php" method="post">
                            <label for="sizeName">New Size:</label>
                            <input type="text" id="sizeName" name="sizeName" required>
                            <button type="submit" name="createSize">Create Size</button>
                        </form>

                        <form action="/../actions/action_create_fields.php" method="post">
                            <label for="modelName">New Model:</label>
                            <input type="text" id="modelName" name="modelName" required>
                            <button type="submit" name="createModel">Create Model</button>
                        </form>

                        <form action="/../actions/action_create_fields.php" method="post">
                            <label for="categoryName">New Category:</label>
                            <input type="text" id="categoryName" name="categoryName" required>
                            <button type="submit" name="createCategory">Create Category</button>
                        </form>
                        <form action="/../actions/action_create_fields.php" method="post">
                            <label for="userId">Select User:</label>
                            <select id="userId" name="userId" required>
                                <?php
                                $users = User::getAllUsers($db);
                                foreach ($users as $user) {
                                    if(!$user->admin){
                                        echo "<option value=\"{$user->userId}\">{$user->firstName} {$user->lastName}</option>";
                                    }
                                    
                                }
                                ?>
                            </select>
                            <button type="submit" name="elevateAdmin">Elevate User to Admin</button>
                    </form>
                    <form action="/templates/route.php?action=remove-category" method="post">
                        <label for="category-select">Remove Category:</label>
                        <select id="category-select" name="category" class="publish-select">
                            <?php
                            $categories = Item::getCategories($db);
                            foreach ($categories as $category) {
                                echo "<option value=\"{$category}\">{$category}</option>";
                            }
                            ?>
                        </select>
                        <button type="submit" name="removeCategory">Remove Category</button>
                    </form>
                    <form action="/templates/route.php?action=remove-model" method="post">
                        <label for="model-select">Remove Model:</label>
                        <select id="model-select" name="model" class="publish-select">
                            <?php
                            $models = Item::getModels($db);
                            foreach ($models as $model) {
                                echo "<option value=\"{$model}\">{$model}</option>";
                            }
                            ?>
                        </select>
                        <button type="submit" name="removeModel">Remove Model</button>
                    </form>
                    <form action="/templates/route.php?action=remove-condition" method="post">
                        <label for="condition-select">Remove Condition:</label>
                        <select id="condition-select" name="condition" class="publish-select">
                            <?php
                            $conditions = Item::getConditions($db);
                            foreach ($conditions as $condition) {
                                echo "<option value=\"{$condition}\">{$condition}</option>";
                            }
                            ?>
                        </select>
                        <button type="submit" name="removeCondition">Remove Condition</button>
                    </form>
                    <form action="/templates/route.php?action=remove-size" method="post">
                        <label for="size-select">Remove Size:</label>
                        <select id="size-select" name="size" class="publish-select">
                            <?php
                            $sizes = Item::getSizes($db);
                            foreach ($sizes as $size) {
                                echo "<option value=\"{$size}\">{$size}</option>";
                            }
                            ?>
                        </select>
                        <button type="submit" name="removeSize">Remove Size</button>
                    </form>
                    <form action="/templates/route.php?action=remove-brand" method="post">
                        <label for="brand-select">Remove Brand:</label>
                        <select id="brand-select" name="brand" class="publish-select">
                            <?php
                            $brands = Item::getBrands($db);
                            foreach ($brands as $brand) {
                                echo "<option value=\"{$brand}\">{$brand}</option>";
                            }
                            ?>
                        </select>
                        <button type="submit" name="removeBrand">Remove Brand</button>
                    </form>


                </div>
                
                <?php endif; ?>
                <p>Description</p>
            </div>
            
            <div>
                <h1>Presented Products</h1>
                <div id="profile-page-items">
                    <?php foreach ($presentedProducts as $product) : ?>
                        <article class="">
                            <?php
                            $image = Item::getItemImage($db, $product->itemId)[0];
                            ?>
                            <a href="/pages/post.php?id=<?= $product->itemId ?>" class="profilepage-item">
                                <img class="profilepage-img-item" src="../<?=$image->imageUrl?>" alt="" width="100">
                                <div class="profilepage-title-price-item">
                                    <h1><?= htmlspecialchars($product->title) ?></h1>
                                    <h2><?= $product->price ?>€</h2>
                                </div>
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    <?php
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>
