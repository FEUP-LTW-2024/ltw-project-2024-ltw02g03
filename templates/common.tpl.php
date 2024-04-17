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
    <link rel="stylesheet" href="../html/style.css">
    <script src="../html/script_test.js" defer></script>
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
        <a id="cart-register-anchor" href="/pages/cart.php">Login/Register</a>
        <button id="profile-button">

            <img src="/Docs/img/9024845_user_circle_light_icon.png" alt="" width="30">
        </button> 
        <a id="login-register-anchor" href="/pages/login.php">Login/Register</a>
    </div>
</header>

<?php } ?>

<?php function drawBody(Session $session, $db, int $limit) { ?>
<section id="recomended">
    <h1>Produtos Recomendados</h1>  
    <h2><?php echo $limit; ?> produtos</h2>
    <div id="index-products">
        <?php drawProducts($db, $limit); ?>
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
function drawProducts($db,int $limit) {
    try {
        $items = Item::getItems($db, $limit);
        
        
        if ($items) {
          foreach($items as $row) {
            
            $condition = Item::getItemCondition($db, $row->itemId);
            $brand = Item::getItemBrand($db, $row->itemId);            
            $image = Item::getItemImage($db, $row->itemId);
            
            ?>
            <article id="index-product">
              <img id="img-product" src="<?= $image[0]->imageUrl ?>" alt="" style="width: 50%; height: auto;">
              <h1><?= htmlspecialchars($row->title) ?></h1>
              <h2><?= htmlspecialchars($row->description) ?></h2>
              <p><?= number_format($row->price, 2) ?>€</p>
              <p>Condition: <?= htmlspecialchars($condition->conditionName) ?></p>
              <p>Brand: <?= htmlspecialchars($brand->brandName) ?></p>
              <button class="add-cart-button">Adicionar ao carrinho</button>
            </article>
            <?php
        }
        } else {
            echo "<p>No items found.</p>";
        }
    } 
    catch (PDOException $e) {
        echo "Error fetching items: " . $e->getMessage();
    }
}
?>
