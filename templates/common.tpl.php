<?php 
declare(strict_types = 1); 
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/payment.class.php');

function drawHeader(Session $session, $db) { ?>

    <!DOCTYPE html>
    <html lang="en-US">
    <head>
        <title>EcoExhange</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../html/style.css">
        <link rel="icon" href="../Docs/img/Eco.png" type="image/png">
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.7.0/nouislider.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.7.0/nouislider.min.js"></script>
        <script src="../javascript/login_script.js" defer></script>
        <script src="../javascript/message.js" defer></script>
        <script src="../javascript/script.js" defer></script>
        <script src="../javascript/filter_items.js" defer></script>
        <script src="../javascript/chat.js" defer></script>
    </head>
    <body>
    
    <header>
        <h1><a href="/pages">EcoExchange</a></h1>
        <div id="header-list">
        <ul>
            <?php 
            $categories = Item::getCategories($db); 
            $limite = 7;
            foreach($categories as $category) {
                $limite--;
                if($limite===0) {break;}
                if($category == "Home Appliances") {
                    echo "<li><a href='#' onclick='filterItems(\"$category\")'>Appliances</a></li>";
                } else {
                    echo "<li><a href='#' onclick='filterItems(\"$category\")'>$category</a></li>";
                }
            }
            ?>
        </ul>
        </div>
        <div id="utility-wrap">
            <a href="/pages/conversations.php" class="utility-wrap-anchor">
                <button class="header-button">
                    <img src="/Docs/img/9042672_message_icon.png" alt="" width="27">
                </button>
            </a>

            <button class="header-button" onclick="openSearchTab()">
                <img src="/Docs/img/9024781_gender_neuter_light_icon.png" alt="" width="30">
            </button> 
            
            <a href="/pages/cart.php" class="utility-wrap-anchor">
                <button class="header-button">
                    <img src="/Docs/img/9025034_shopping_cart_light_icon.png" alt="" width="30">
                </button> 
            </a>

            <?php if ($session->isLoggedIn()) { ?>
            <a id="login-register-anchor" href="/pages/profilepage.php" >
            <?php }  ?>
                <button class="header-button" id="profile-button">
                <?php if($session->isLoggedIn())  {
                    $userId = $session->getId();
                    $user = User::getUser($db, $userId); ?>
                    <img id="profile-pic-header" src="<?= !empty($user->imageUrl) ? htmlspecialchars($user->imageUrl) : "../Docs/img/9024845_user_circle_light_icon.png" ?>" alt="" width="1.8em">
                    <?php } else { ?>
                        <a id="login-register-anchor" href="/pages/login.php">
                        <img src="/Docs/img/9024845_user_circle_light_icon.png" alt="" width="30">
                        </a>
                    <?php } ?>
                </button> 
                <?php if ($session->isLoggedIn()) { ?> </a> <?php }  ?>
            <div id="login-register">
            <?php if (!$session->isLoggedIn()) { ?>
            <a id="login-register-anchor" href="/pages/login.php">Login/Register</a>
            <?php } else { ?>
                
                
                <ul id="options-header">
                    <li>
                        <a id="logout-anchor" href="/pages/postcreation.php">Publish Item</a>
                    </li>
                    <li>
                        <a id="logout-anchor" href="/actions/action_logout.php">Logout</a>
                    </li>
                </ul>
                
                
            
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
        <button id="filter-search-tab">Filters</button>
    </div>


    <aside id="filter-box">
        <h2>Filters</h2>
        <label for="price-slider">Price Range:</label>
        <br>
        <span id="price-display"><span id="min-price"></span> <span id="max-price"></span></span>
        <div id="price-slider"></div>
        
        <br>
        <label for="category-select">Category:</label>
        <select id="category-select" class="publish-select">
            <option value="">Selecione a categoria...</option>
        </select>
        <br>
        <label for="brand-select">Brand:</label>
        <select id="brand-select" class="publish-select">
            <option value="">Selecione a marca...</option>
        </select>
        <br>
        <label for="condition-select">Condition:</label>
        <select id="condition-select" class="publish-select">
            <option value="">Selecione a condição...</option>
        </select>
        <br>
        <label for="size-select">Size:</label>
        <select id="size-select" class="publish-select">
            <option value="">Selecione o tamanho...</option>
        </select>
        <br>
        <label for="model-select">Model:</label>
        <select id="model-select" class="publish-select">
            <option value="">Selecione o modelo...</option>
        </select>
        <br>
        <button onclick="applyFilters()">Apply Filters</button>
    </aside>

    

    <?php } ?>




<?php function drawBody(Session $session, $db, int $limit, $category = null) { ?>
    <main>
        <div>
            <header>
                <h1 id="home-welcome">Welcome to <span id="ecox-home">EcoExchange</span></h1>
            </header>
            <img id="home-img" src="https://live.staticflickr.com/7023/6806424715_4c1cb053ef_o.jpg">
            
        </div>
    
    
    <section id="recomended">
        <h1>Produtos Recomendados</h1>  
        <h2><?php echo $limit; ?> produtos</h2>
        <div id="index-products">
            <?php drawProducts($session,$db, $limit, $category); ?>
        </div>
    </section>

    
<?php } ?>

<?php function drawFooter() { ?>
    

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
function drawProducts(Session $session, $db, int $limit, $categoryName = null) {
    
    try {
        $myId = $session->getId();
        $items = Item::getAllActiveItems($db, $limit, $categoryName);
        if ($items) {
            
            foreach($items as $row) {
                $ownerId = $row->sellerId;
                
                
                $condition = Item::getItemCondition($db, $row->itemId);
                $brand = Item::getItemBrand($db, $row->itemId);            
                $image = Item::getItemImage($db, $row->itemId);
                
                ?>
                
                    <article >
                        <a id="index-product" href="/pages/post.php?id=<?= $row->itemId ?>" class="item-link">
                            <div id=img-product>
                                <img  src="<?= $image[0]->imageUrl ?>" alt="" style="width: 90%; height: 90%;">
                            </div>
                            <h1><?= htmlspecialchars($row->title) ?></h1>
                            <h2><?= htmlspecialchars($row->description) ?></h2>
                            <p><?= number_format($row->price, 2) ?>€</p>
                            <p>Condition: <?= htmlspecialchars($condition->conditionName) ?></p>
                            
                
                            <p>Brand: <?= !empty($brand) ? htmlspecialchars($brand->brandName) : "  -  "?></p>
                            <?php 
                            if($myId != $ownerId) { ?>
                            <form action="../actions/add_to_cart.php" method="post" class="add-to-cart-form">
                                <input type="hidden" name="item_id" value="<?= $row->itemId ?>">
                                <button type="submit" class="add-cart-button">Add to Cart</button> 
                            </form>
                            <?php } else {?>
                                <form action="../actions/delete_item.php" method="post" class="add-to-cart-form">
                                        <input type="hidden" name="item_id" value="<?= $row->itemId ?>">
                                        <button type="submit" id="delete-cart-button" class="add-cart-button">Delete</button>
                                    </form>
                            <?php } ?>
                        </a>
                    </article>
                
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
<?php } ?>


<script>
function openSearchTab() {
    var searchTab = document.getElementById("search-tab");
    if (searchTab.style.display === "none") {
        searchTab.style.display = "block";
    } else {
        searchTab.style.display = "none";
    }
}

document.addEventListener("DOMContentLoaded", function() {
    // Your script here
var filterButton = document.getElementById("filter-search-tab");

    // Toggle the display of the filter box when the button is clicked
    filterButton.addEventListener("click", function() {
        
        var filterBox = document.getElementById("filter-box");
        if (filterBox.style.display === "none") {
            filterBox.style.display = "block";
        } else {
            filterBox.style.display = "none";
        }
    });
});

</script>