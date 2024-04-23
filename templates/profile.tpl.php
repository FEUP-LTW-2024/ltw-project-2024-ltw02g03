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

        // Fetch presented products by the user
        $presentedProducts = User::fetchPresentedProducts($db, $userId);
        //$image = User::getUserImage($db, $userId)[0];
        // Start rendering HTML
    
        ?>
        <main id="profilepage">
            <div id="profile-img-infos">
            <img src="/Docs/img/9024845_user_circle_light_icon.png" alt="" height="100">
                <div id="profilepage-name-loc">
                    <h1><?= htmlspecialchars($user->name()) ?></h1>
                    <h2><?= htmlspecialchars($user->city) ?>, <?= htmlspecialchars($user->district) ?>, <?= htmlspecialchars($user->country) ?></h2>
                </div>
            </div>
            <div id="left-profile-page">
                <p>description</p>
            </div>
            <div>
                <h1>Presented Products</h1>
                <div id="profile-page-items">
                    <?php foreach ($presentedProducts as $product) : ?>
                        <article class="profilepage-item">
                            <?php
                            // Assuming you have a method to get the image URL of the product
                            $image = Item::getItemImage($db, $product->itemId)[0];
                            ?>
                             <a href="/pages/post.php?id=<?= $product->itemId ?>" class="item-link">
                            <img class="profilepage-img-item" src="../<?=$image->imageUrl?>" alt="" width="100">
                            
                            <div>
                                <h1><?= htmlspecialchars($product->title) ?></h1>
                                <h2><?= htmlspecialchars($product->price) ?>€</h2>
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
