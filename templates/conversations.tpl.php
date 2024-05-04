<?php
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/communication.class.php');

function drawConversations(Session $session, $db) {
    $userId = $session->getId();

    $conversations = Communication::getAllChats($db);

    echo "<h1>" . htmlentities("Conversas Ativas") . "</h1>";
    echo "<ul>";
    foreach ($conversations as $conversation) {
        $otherUserId = ($userId == $conversation['senderId']) ? $conversation['receiverId'] : $conversation['senderId'];
        $item = $conversation['itemId'];
        $otherName = htmlentities(User::getUsernameById($db, $otherUserId));
        $itemName = htmlentities(Item::getItemNameById($db, $item));
        ?>
        <li>
            <a href='/pages/chat.php?receiver_id=<?= $otherUserId ?>'>
                <h3><?= htmlentities("Conversa com {$otherName} sobre {$itemName}") ?></h3>
            </a>
            <form action="/pages/chat.php" method="post">
                <input type="hidden" name="owner_id" value="<?= $otherUserId ?>">
                <input type="hidden" name="item_id" value="<?= $item ?>">
                <button type="submit">Abrir Chat</button>
            </form>
        </li>
        <?php
    }
    echo "</ul>";
}
?>