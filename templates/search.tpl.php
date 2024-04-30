<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/user.class.php');


function drawSearchedProducts($db, array $items, string $searchQuery) { ?>   
    <main id="main-search">
        <h1>Results for: <?= htmlspecialchars($searchQuery) ?></h1>
        <?php if (!empty($items)) : ?>
            <?php foreach ($items as $item) : 
                $image = Item::getItemImage($db, $item->itemId)[0]; 
                $condition = Item::getItemCondition($db, $item->itemId);
                ?>
                <a href="/pages/post.php?id=<?= $item->itemId ?>">
                    <article class="search-item">  
                    

                        
                        <div class="img-title-condition">
                            
                            <img src="../<?= $image->imageUrl ?>" alt="<?= $item->title ?>" style="max-width: 15em; height: auto;">
                            <div class="title-cond">
                                <div>
                                    <h1><?= htmlspecialchars($item->title) ?></h1>
                                    <h2 class="date-search"><?= $item->listingDate ?></h2>
                                </div>
                                <p><strong>Condition:</strong> <?= htmlspecialchars($condition->conditionName) ?></p>
                            </div>
                        </div>
                
                        <p class="price-search"><?= number_format($item->price, 2) ?>€</p>
                </article>
             </a>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No items found for your search query.</p>
        <?php endif; ?>
    </main>
    
<?php } 

function drawFilteredProducts($db,int $limit, array $items,$categoryName) { ?>   
    <main id="main-search">
        <h2><?php echo $limit; ?> produtos</h2>
        <h1>Results for: <?= htmlspecialchars($categoryName) ?></h1>
        <?php if (!empty($items)) : ?>
            <?php foreach ($items as $item) : 
                $image = Item::getItemImage($db, $item->itemId)[0]; 
                $condition = Item::getItemCondition($db, $item->itemId);
                ?>
                <article class="search-item">  
                    <div class="img-title-condition">
                        
                        <img src="../<?= $image->imageUrl ?>" alt="<?= $item->title ?>" style="max-width: 15em; height: auto;">
                        <div class="title-cond">
                            <div>
                                <h1><?= htmlspecialchars($item->title) ?></h1>
                                <h2 class="date-search"><?= $item->listingDate ?></h2>
                            </div>
                            <p><strong>Condition:</strong> <?= htmlspecialchars($condition->conditionName) ?></p>
                        </div>
                    </div>
                    <p class="price-search"><?= number_format($item->price, 2) ?>€</p>
                </article>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No items found for your search query.</p>
        <?php endif; ?>
    </main>
    
<?php } 


