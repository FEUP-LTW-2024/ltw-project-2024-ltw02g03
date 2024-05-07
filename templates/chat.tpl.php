<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/communication.class.php'); // Adicione a inclusão do arquivo da classe Communication

function drawChat($db, int $senderId, int $ownerId, int $itemId, array $messages) {
    $ownerName = User::getUserNameById($db, $ownerId);
    $itemName = Item::getItemNameById($db, $itemId);
?>

<main id='conversation-main'>
    <h2 ><?= htmlspecialchars($ownerName) ?> - <?= htmlspecialchars($itemName) ?></h2>
    
    <div id="chat-container">
        <div id="messages-container">
            <?php foreach ($messages as $message): ?>
                <div class="message <?= $message->senderId === $senderId ? 'sent' : 'received' ?>">
                    <p><?= htmlspecialchars($message->messageText) ?></p>
                    <span><?= $message->sendDate ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        <div id="input-container">
            <form id="message-form" action="../templates/messages/send_message.php" method="POST">
                <input type="hidden" id="receiver-id" value="<?= $ownerId ?>">
                <input type="hidden" id="item-id" name="item-id" value="<?= $itemId ?>">
                <input type="text" name="message-input" id="message-input" placeholder="Digite sua mensagem...">
                <input type="submit" id="send-button" value="Enviar">
            </form>
        </div>
    </div>
</main>

<?php } ?>
<script>
    // Function to scroll the messages container to the bottom
    function scrollMessagesToBottom() {
        var messagesContainer = document.getElementById('messages-container');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Call the function to scroll to the bottom once the messages are loaded
    window.onload = function() {
        scrollMessagesToBottom();
    };
</script>