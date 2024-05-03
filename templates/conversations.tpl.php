<?php
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/communication.class.php');

function drawContacts(Session $session, $db) {
    $userId = $session->getId();

    $conversations = Communication::getAllChats($db);

    echo "<h1>Conversas Ativas</h1>";
    echo "<ul>";
    foreach ($conversations as $conversation) {
        $otherUserId = ($userId == $conversation['senderId']) ? $conversation['receiverId'] : $conversation['senderId'];
            echo "<li><a href='/pages/chat.php?receiver_id={$otherUserId}'>Conversa com {$otherUserId}</a></li>";
    }
    echo "</ul>";
}
?>
