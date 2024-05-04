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
                <img id="profile-img" src="<?= !empty($user->imageUrl) ? htmlspecialchars($user->imageUrl) : "../Docs/img/9024845_user_circle_light_icon.png" ?>" alt="" height="100">
                <div id="profilepage-name-loc">
                    <h1><?= htmlspecialchars($user->name()) ?></h1>
                    <h2><?= !empty($user->city) ? htmlspecialchars($user->city) : " - " ?>, <?= !empty($user->district) ? htmlspecialchars($user->district) : " - " ?>, <?= !empty($user->country) ? htmlspecialchars($user->country) : " - "?></h2>
                </div>
            </div>
            <div id="left-profile-page">
                <?php if ($session->isAdmin()): ?>
                    <!-- Conteúdo exclusivo para administradores -->
                    <div id="admin-section">
                        <h2>Admin Section</h2>
                        <form action="/pages/create_fields.php" method="post">
                            <!-- Input para criar uma nova marca -->
                            <label for="brandName">New Brand:</label>
                            <input type="text" id="brandName" name="brandName" required>
                            <button type="submit" name="createBrand">Create Brand</button>
                        </form>

                        <form action="/pages/create_fields.php" method="post">
                            <!-- Input para criar uma nova condição -->
                            <label for="conditionName">New Condition:</label>
                            <input type="text" id="conditionName" name="conditionName" required>
                            <button type="submit" name="createCondition">Create Condition</button>
                        </form>

                        <form action="/pages/create_fields.php" method="post">
                            <!-- Input para criar um novo tamanho -->
                            <label for="sizeName">New Size:</label>
                            <input type="text" id="sizeName" name="sizeName" required>
                            <button type="submit" name="createSize">Create Size</button>
                        </form>

                        <form action="/pages/create_fields.php" method="post">
                            <!-- Input para criar um novo modelo -->
                            <label for="modelName">New Model:</label>
                            <input type="text" id="modelName" name="modelName" required>
                            <button type="submit" name="createModel">Create Model</button>
                        </form>

                        <form action="/pages/create_fields.php" method="post">
                            <!-- Input para criar uma nova categoria -->
                            <label for="categoryName">New Category:</label>
                            <input type="text" id="categoryName" name="categoryName" required>
                            <button type="submit" name="createCategory">Create Category</button>
                        </form>
                        <form action="/pages/create_fields.php" method="post">
                        <!-- Dropdown para selecionar um usuário para elevar a administrador -->
                        <label for="userId">Select User:</label>
                        <select id="userId" name="userId" required>
                            <?php
                            // Aqui você precisará buscar os usuários existentes do banco de dados
                            // e exibi-los como opções no dropdown
                            $users = User::getAllUsers($db);
                            foreach ($users as $user) {
                                echo "<option value=\"{$user->userId}\">{$user->firstName} {$user->lastName}</option>";
                            }
                            ?>
                        </select>
                        <button type="submit" name="elevateAdmin">Elevate User to Admin</button>
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
