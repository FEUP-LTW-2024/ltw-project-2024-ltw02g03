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
        <link rel="icon" href="../Docs/img/Eco.png" type="image/png">
        <script src="../javascript/login_script.js" defer></script>
        <script src="../javascript/message.js" defer></script>
        <script src="../javascript/script.js" defer></script>
        <script src="../javascript/filter_items.js" defer></script>
        <script src="../javascript/scroll.js" defer></script>
    </head>
    <body>
    
    <header>
        <h1><a href="/pages">EcoExchange</a></h1>
        <div id="header-list">
            <ul>
                <li><a href="#" onclick="filterItems('Electronics')">Electronics</a></li>
                <li><a href="#" onclick="filterItems('Clothing')">Clothing</a></li>
                <li><a href="#" onclick="filterItems('Books')">Books</a></li>
                <li><a href="#" onclick="filterItems('Furniture')">Furniture</a></li>
                <li><a href="#" onclick="filterItems('Home Appliances')">Appliances</a></li>
                <li><a href="#" onclick="filterItems('Jewelry')">Jewelry</a></li>
            </ul>
        </div>
        <div id="utility-wrap">
            <button id="search-button" onclick="openSearchTab()">
                <img src="/Docs/img/9024781_gender_neuter_light_icon.png" alt="" width="30">
            </button> 
            <a href="/pages/cart.php">
                <button id="cart-button">
                    <img src="/Docs/img/9025034_shopping_cart_light_icon.png" alt="" width="30">
                </button> 
            </a>
            
            <button id="profile-button">
                <img src="/Docs/img/9024845_user_circle_light_icon.png" alt="" width="30">
            </button> 
            <div id="login-register">
            <?php if (!$session->isLoggedIn()) { ?>
            <a id="login-register-anchor" href="/pages/login.php">Login/Register</a>
            <?php } else { ?>
            
            <a id="logout-anchor" href="/actions/action_logout.php">Logout</a>
            </div>
            <?php } ?>
        </div>
        
        
           
    </header>
     <div id="message-container">
                <section id="messages">
                <?php foreach ($session->getMessages() as $message) { ?>
                    <article class="<?= $message['type'] ?>",>
                        <?= $message['text'] ?>
                    </article>
                    
                <?php } ?>
            </section>
        </div>

    
    <div id="search-tab" style="display: none;">
        <form action="/pages/search.php" method="GET" id="search-form">
            <input id="input-search-header" type="text" name="query" placeholder="Search for products...">
            <button type="submit">Search</button>
        </form>
    </div>

    <?php } ?>

<?php function drawBody(Session $session, $db, int $limit, $category = null) { ?>

    <aside id="filter-box">
        <h2>Filters</h2>
        <label for="price-range">Price Range:</label>
        <input type="range" id="price-range" name="price-range" min="0" max="1000" step="10">
        <br>
        <label for="category-select">Category:</label>
        <select id="category-select">
            <option value="Electronics">Electronics</option>
            <option value="Clothing">Clothing</option>
            <option value="Books">Books</option>
            <option value="Furniture">Furniture</option>
            <option value="Home Appliances">Home Appliances</option>
            <option value="Jewelry">Jewelry</option>
        </select>
        <br>
        <button onclick="applyFilters()">Apply Filters</button>
</aside>
    <main>
    <section id="recomended">
        <h1>Produtos Recomendados</h1>  
        <h2><?php echo $limit; ?> produtos</h2>
        <div id="index-products">
            <?php drawProducts($db, $limit, $category); ?>
        </div>
    </section>

    
<?php } ?>

<?php function drawFooter() { ?>
    

    <footer id="footer-page">
        <p>2024 &copy; EcoExchange</p>
    </footer>
    <script src="scroll.js"></script>
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
function drawProducts($db, int $limit, $categoryName = null) {
    
    try {
        
        $items = $categoryName ? Item::getItemsByCategoryName($db, $limit,$categoryName) : Item::getItems($db, $limit);
        
        if ($items) {
            
            foreach($items as $row) {
                
                $condition = Item::getItemCondition($db, $row->itemId);
                $brand = Item::getItemBrand($db, $row->itemId);            
                $image = Item::getItemImage($db, $row->itemId);
                
                ?>
                <a href="/pages/post.php?id=<?= $row->itemId ?>" class="item-link">
                    <article id="index-product">
                        <div id=img-product>
                            <img id="" src="<?= $image[0]->imageUrl ?>" alt="" style="width: 90%; height: 90%;">
                        </div>
                        <h1><?= htmlspecialchars($row->title) ?></h1>
                        <h2><?= htmlspecialchars($row->description) ?></h2>
                        <p><?= number_format($row->price, 2) ?>€</p>
                        <p>Condition: <?= htmlspecialchars($condition->conditionName) ?></p>
                        <p>Brand: <?= htmlspecialchars($brand->brandName) ?></p>
                        <form action="../actions/add_to_cart.php" method="post" class="add-to-cart-form">
                            <input type="hidden" name="item_id" value="<?= $row->itemId ?>">
                            <button type="submit" class="add-cart-button">Add to Cart</button> 
                        </form>
                    </article>
                </a>
                <?php
            }
        } else {
            echo "<p>No items found.</p>";
        }
    } catch (PDOException $e) {
        echo "Error fetching items: " . $e->getMessage();
    }
    ?>
    </main>
<?php }?>
<script>
function openSearchTab() {
    var searchTab = document.getElementById("search-tab");
    if (searchTab.style.display === "none") {
        searchTab.style.display = "block";
    } else {
        searchTab.style.display = "none";
    }
}
</script>
