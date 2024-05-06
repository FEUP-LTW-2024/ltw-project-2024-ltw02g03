<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/item.class.php');

$session = new Session();
$db = getDatabaseConnection();

if (!$session->isLoggedIn()) {
    header('Location: /pages/login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["item_id"])) {
        $itemId = intval($_POST["item_id"]);
        
        $userId = $session->getId();
        $sellerId = Item::getSellerId($db, $itemId);
        
        if ($userId === $sellerId) {
            Item::deleteItem($db, $itemId);
            $session->addMessage('success', 'Item deleted successfully!');
            header('Location: /pages');
            exit();
        } else {
            $session->addMessage('error', 'You are not the seller of this item!');
            header('Location: /pages');
            exit();
        }
    } else {
        $session->addMessage('error', 'Invalid form submission!');
        header('Location: /pages');
        exit();
    }
} else {
    $session->addMessage('error', 'Invalid request method!');
    header('Location: /pages/error.php');
    exit();
}
?>
