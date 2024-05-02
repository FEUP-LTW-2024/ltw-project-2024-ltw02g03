<?php
// Função para desenhar o corpo da página
function drawBody(Session $session, $db) {
    // Obtenha todas as conversas ativas do banco de dados
    $conversations = Chat::getAllConversations($db, $session->getUserId());

    // Desenhe o corpo da página aqui, exibindo as conversas
    // Por exemplo:
    echo "<h1>Conversas Ativas</h1>";
    echo "<ul>";
    foreach ($conversations as $conversation) {
        echo "<li><a href='/pages/chat.php?conversation_id={$conversation->conversationId}'>Conversa {$conversation->conversationId}</a></li>";
    }
    echo "</ul>";
}
?>