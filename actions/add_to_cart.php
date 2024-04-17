<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/user.class.php');

$session = new Session();
$db = getDatabaseConnection();

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /pages/login.php");
        exit();
    }

    $itemId = $_POST['item_id'];
    $userId = $_SESSION['user_id'];

    try {
        Cart::insertItem($db, $userId, $itemId, 1); 
        // Set success message
        $session->setMessage("Item successfully added to cart.", "success");
        header("Location: /pages/cart.php");
        exit();
    } catch (Exception $e) {
        // Set error message
        $session->setMessage("Error adding item to cart: " . $e->getMessage(), "error");
        header("Location: /");
        exit();
    }
} else {
    header("Location: /");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add to Cart</title>
    <link rel="stylesheet" href="../html/style.css">
    <script src="../html/script_test.js" defer></script>
</head>
<body>

<?php

drawHeader($session);
?>

<?php

drawBody($session, $db, getTotalProductsCount($db));
?>

<section id="messages">
    <?php foreach ($session->getMessages() as $message) { ?>
        <article class="<?= $message['type'] ?>">
            <?= $message['text'] ?>
        </article>
    <?php } ?>
</section>

<?php

drawFooter();
?>

</body>
</html>
