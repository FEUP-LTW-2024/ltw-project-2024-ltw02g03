<?php 
declare(strict_types = 1); 
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/user.class.php');


function drawHeader(Session $session) { ?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>EcoExhange</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="html/style.css">
</head>
<body>

<header>
    <h1><a href="#">EcoExchange</a></h1>
    <div id="header-list">
        <ul>
            <li><a>Eletrodomésticos</a></li>
            <li><a>Livros</a></li>
            <li><a>Roupa </a></li>
            <li><a>Móveis</a></li>
            <li><a>Informática</a></li>
        </ul>
    </div>
    <div id="utility-wrap">
        <button id="search-button">
            <img src="/Docs/img/9024781_gender_neuter_light_icon.png" alt="" width="30">
        </button> 
        <button id="cart-button">
            <img src="/Docs/img/9025034_shopping_cart_light_icon.png" alt="" width="30">
        </button>  
        <button id="profile-button">
            <img src="/Docs/img/9024845_user_circle_light_icon.png" alt="" width="30">
        </button> 
    </div>
</header>

<?php } ?>

<?php function drawBody(Session $session, $db) { ?>
<section id="recomended">
    <h1>Produtos Recomendados</h1>  
    <h2>15 produtos</h2>
    <div id="index-products">
        <?php drawProducts($db); ?>
    </div>
</section>

<section id="messages">
    <?php foreach ($session->getMessages() as $message) { ?>
        <article class="<?= $message['type'] ?>">
            <?= $message['text'] ?>
        </article>
    <?php } ?>
</section>

<main>
<?php } ?>

<?php function drawFooter() { ?>
</main>

<footer id="footer-page">
    <p>2024 &copy; EcoExchange</p>
</footer>
</body>
</html>
<?php } ?>

<?php function drawLogoutForm(Session $session) { ?>
<form action="../actions/action_logout.php" method="post" class="logout">
    <a href="../pages/profile.php"><?= $session->getName() ?></a>
    <button type="submit">Logout</button>
</form>
<?php } ?>

<?php
function drawProducts($db) {
    try {
        $stmt = $db->prepare('SELECT * FROM Item');
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($items) {
            foreach ($items as $row) {
                // Retrieve additional information about the item
                $conditions = Item::getItemCondition($db, $row['ItemId']);
                $brands = Item::getItemBrand($db, $row['ItemId']);
                $sizes = Item::getItemSize($db, $row['ItemId']);
                $models = Item::getItemModel($db, $row['ItemId']);

                // Output the item HTML
                ?>
                <article id="index-product">
                    <img id="img-product" src="https://picsum.photos/240/270?business" alt="">
                    <h1><?= htmlspecialchars($row['Title']) ?></h1>
                    <h2><?= htmlspecialchars($row['Description']) ?></h2>
                    <p><?= htmlspecialchars($row['Price']) ?>€</p>
                    <p><strong>Condição:</strong> <?= htmlspecialchars($conditions[0]->conditionName) ?></p>
                    <!-- Assuming there's only one condition per item -->
                    <button class="add-cart-button">Adicionar ao carrinho</button>
                </article>
                <?php
            }
        } else {
            // Handle case where there are no items in the database
            echo "<p>No items found.</p>";
        }
    } catch (PDOException $e) {
        // Handle any database errors
        echo "Error fetching items: " . $e->getMessage();
    }
}
?>
