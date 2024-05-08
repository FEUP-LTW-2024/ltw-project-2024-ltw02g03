<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../actions/action_editprofile.php');
require_once(__DIR__ . '/../actions/action_change_password.php');

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
                <button class="button-left-prof" onclick="toggleProfileProd()">
                    Published products
                </button>
                <button onclick="toggleEditProfile()" class="button-left-prof">
                    Edit Profile
                </button>
                <button onclick="toggleChangePass()" class="button-left-prof">
                    Change Password
                </button>
                <?php if($session->isAdmin()) { ?>
                <button onclick="toggleAdminSection()" class="button-left-prof">
                    Admin options
                </button>
                <?php } ?>
            </div>
            
            <div>
                <div id="profile-presented">
                        <h1>Presented Products</h1>
                        <h2>To Sell:</h2>
                        <div id="profile-page-items">
                        <?php 
                        $activeItems=Item::getActiveItemsBySellerId($db, $userId);
                        $allItems=Item::getItemsBySellerId($db, $userId);
                        $inactiveItems =Item::getInactiveItemsBySellerId($db, $userId);
                        if(empty($activeItems)) {?> <h3>No active items</h3><?php }
                        else {
                        foreach ($activeItems as $product) : ?>
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
                            <?php endforeach; }?>
                                
                        </div>
                        <h2>Sold:</h2>
                        <div id="profile-page-items">
                        <?php
                        if(empty($inactiveItems)) {?> <h3>No sold items</h3><?php }
                        else {
                        foreach ($inactiveItems as $product) : ?>
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
                            <?php endforeach; }?>

                    </div>
                </div>
                
                <div id="edit-profile-section" style="display: none;">
                        <form  class="profile-edit" action="/actions/action_editprofile.php" method="post">
                        <h1>User</h1>
                        <div class="input-group">
                            <label class="image-input">
                                <input name="images[]" type="file" accept="image/heic, image/png, image/jpeg, image/webp" multiple onchange="previewImages(event,1)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 32 32"><path fill="currentColor" d="M29 26H3a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h6.46l1.71-2.55A1 1 0 0 1 12 4h8a1 1 0 0 1 .83.45L22.54 7H29a1 1 0 0 1 1 1v17a1 1 0 0 1-1 1M4 24h24V9h-6a1 1 0 0 1-.83-.45L19.46 6h-6.92l-1.71 2.55A1 1 0 0 1 10 9H4Z"/><path fill="currentColor" d="M16 22a6 6 0 1 1 6-6a6 6 0 0 1-6 6m0-10a4 4 0 1 0 4 4a4 4 0 0 0-4-4"/></svg>
                                <img class="preview-image" id="preview-image-1" src="" alt="">
                            </label>
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="<?= $user->email ?>">
                        </div>
                        <div class="input-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" placeholder="<?= $user->username ?>">
                        </div>
                        <div class="name-group">
                            <div class="input-group">
                                <label for="first-name">First Name</label>
                                <input type="text" id="first-name" name="firstName" placeholder="<?= $user->firstName ?>">
                            </div>
                            <div class="input-group">
                                <label for="last-name">Last Name</label>
                                <input type="text" id="last-name" name="lastName" placeholder="<?= $user->lastName ?>">
                            </div>
                        </div>
                        <div class="location-group">
                            <h1>Location</h1>
                            <div class="input-group">
                                <label for="address">Address</label>
                                <input type="text" id="address" name="address" placeholder="<?= $user->address ?>">
                            </div>
                            <div class="input-group">
                                <label for="city">City</label>
                                <input type="text" id="city" name="city" placeholder="<?= $user->city ?>">
                            </div>
                            <div class="input-group">
                                <label for="district">District</label>
                                <input type="text" id="district" name="district" placeholder="<?= $user->district ?>">
                            </div>
                            <div class="input-group">
                                <label for="country">Country</label>
                                <input type="text" id="country" name="country" placeholder="<?= $user->country ?>">
                            </div>
                            <div class="input-group">
                                <label for="postal-code">Postal Code</label>
                                <input type="text" id="postal-code" name="postalCode" placeholder="<?= $user->postalCode ?>">
                            </div>
                            <div class="input-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" placeholder="<?= $user->phone ?>">
                            </div>
                        </div>
                        
                        <div id="flex-login-regis">
                            <button type="submit">Change</button>
                        </div>
                    </form>
                </div>

                <div id="change-password" style="display: none;">
                    <form class="profile-edit" action="../actions/action_change_password.php" method="post">
                        <h1>Change Password</h1>
                        <div class="input-group">
                            <label for="current-password">Current Password</label>
                            <input type="password" id="current-password" name="currentPassword" required>
                        </div>
                        <div class="input-group">
                            <label for="new-password">New Password</label>
                            <input type="password" id="new-password" name="newPassword" required>
                        </div>
                        <div class="input-group">
                            <label for="confirm-password">Confirm New Password</label>
                            <input type="password" id="confirm-password" name="confirmPassword" required>
                        </div>
                        <div id="flex-login-regis">
                            <button type="submit">Change Password</button>
                        </div>
                    </form>
                </div>


                <div id="admin-section" style="display: none;">
                    <?php if ($session->isAdmin()): ?>
                    
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
                            <select id="userId" name="userId" class="publish-select" required>
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


                
                
                <?php endif; ?>
                </div>
            </div>
        </main>
    <?php
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>
<script>
    function toggleAdminSection() {
        var adminSection = document.getElementById("admin-section");
        var presentedProducts = document.getElementById("profile-presented");
        var profileEdit = document.getElementById("edit-profile-section");
        var changepass = document.getElementById("change-password");
        adminSection.style.display = "block";
        presentedProducts.style.display = "none";
        profileEdit.style.display = "none";
        changepass.style.display = "none";
        
    }
    function toggleProfileProd() {
        var adminSection = document.getElementById("admin-section");
        var presentedProducts = document.getElementById("profile-presented");
        var profileEdit = document.getElementById("edit-profile-section");
        var changepass = document.getElementById("change-password");
        adminSection.style.display = "none";
        presentedProducts.style.display = "block";
        profileEdit.style.display = "none";
        changepass.style.display = "none";
    }

    function toggleEditProfile() {
        var adminSection = document.getElementById("admin-section");
        var presentedProducts = document.getElementById("profile-presented");
        var profileEdit = document.getElementById("edit-profile-section");
        var changepass = document.getElementById("change-password");

        adminSection.style.display = "none";
        presentedProducts.style.display = "none";
        profileEdit.style.display = "block";
        changepass.style.display = "none";
    }

    function toggleChangePass() {
        var adminSection = document.getElementById("admin-section");
        var presentedProducts = document.getElementById("profile-presented");
        var profileEdit = document.getElementById("edit-profile-section");
        var changepass = document.getElementById("change-password");

        adminSection.style.display = "none";
        presentedProducts.style.display = "none";
        profileEdit.style.display = "none";
        changepass.style.display = "block";
    }



        
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
