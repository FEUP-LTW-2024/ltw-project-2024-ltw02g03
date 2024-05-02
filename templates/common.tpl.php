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
                <li><a href="#" onclick="filterItems('Electronics')">Electronics</a></li>
                <li><a href="#" onclick="filterItems('Clothing')">Clothing</a></li>
                <li><a href="#" onclick="filterItems('Books')">Books</a></li>
                <li><a href="#" onclick="filterItems('Furniture')">Furniture</a></li>
                <li><a href="#" onclick="filterItems('Home Appliances')">Appliances</a></li>
                <li><a href="#" onclick="filterItems('Jewelry')">Jewelry</a></li>
            </ul>
        </div>
        <div id="utility-wrap">
            <a href="/pages/chat.php">
                <button id="chat-button">
                    
                </button>
            </a>
            <button id="search-button" onclick="openSearchTab()">
                <img src="/Docs/img/9024781_gender_neuter_light_icon.png" alt="" width="30">
            </button> 
            <a href="/pages/cart.php">
                <button id="cart-button">
                    <img src="/Docs/img/9025034_shopping_cart_light_icon.png" alt="" width="30">
                </button> 
            </a>
            <?php if ($session->isLoggedIn()) { ?>
            <a id="login-register-anchor" href="/pages/profilepage.php">
            <?php }  ?>
                <button id="profile-button">
                    <img src="/Docs/img/9024845_user_circle_light_icon.png" alt="" width="30">
                    
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

    <div id="filter-box">
    <h2>Filters</h2>
    <label for="price-slider">Price Range:</label>
    <br>
    <span id="price-display"><span id="min-price"></span> <span id="max-price"></span></span>
    <div id="price-slider"></div>
    
    <br>
    <label for="category-select">Category:</label>
    <select id="category-select">
    </select>
    <br>
    <label for="brand-select">Brand:</label>
    <select id="brand-select">
    </select>
    <br>
    <label for="condition-select">Condition:</label>
    <select id="condition-select">
    </select>
    <br>
    <label for="size-select">Size:</label>
    <select id="size-select">
    </select>
    <br>
    <label for="model-select">Model:</label>
    <select id="model-select">
    </select>
    <br>
    <button onclick="applyFilters()">Apply Filters</button>
</div>

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
            <?php drawProducts($db, $limit, $category); ?>
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
function drawProducts($db, int $limit, $categoryName = null) {
    
    try {
        
        $items = $categoryName ? Item::getItemsByCategoryName($db, $limit,$categoryName) : Item::getItems($db, $limit);
        
        if ($items) {
            
            foreach($items as $row) {
                $ownerId = $row->sellerId;
                
                
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
                        <form action="../pages/chat.php" method="post" class="send-message-form">
                            <input type="hidden" name="owner_id" value="<?= $ownerId ?>">
                            <input type="hidden" name="item_id" value="<?= $row->itemId ?>">
                            <button type="submit" class="send-message-button">Enviar Mensagem</button>
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