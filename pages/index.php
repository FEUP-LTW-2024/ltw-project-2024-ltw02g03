<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../templates/common.tpl.php');

function getTotalProductsCount($db) {
    try {
        $stmt = $db->query('SELECT COUNT(*) AS total FROM Item');
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return 0; 
    }
}

$db = getDatabaseConnection();

if(isset($_GET['category'])) {
    $category = $_GET['category'];
    echo $category;
} else {
    $category = null;
}

$totalProducts = getTotalProductsCount($db);

drawHeader($session);

drawBody($session, $db, $totalProducts);

drawFooter();
?>
