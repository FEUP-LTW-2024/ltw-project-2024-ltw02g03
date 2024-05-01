<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/search.tpl.php');




$db = getDatabaseConnection();

if(isset($_GET['category'])) {
    $categoryName = $_GET['category'];
} else {
    $categoryName = null;
}

$totalProducts = Item::getCountbyCategory($db, $categoryName);
$items = Item::getItemsByCategoryName($db, $totalProducts,$categoryName);



drawHeader($session);
drawFilteredProducts($db, $totalProducts, $items, $categoryName);
drawFooter();
?>
