<?php
// Inclua o arquivo de sessão e as classes necessárias
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/chat.class.php');
require_once(__DIR__ . '/../templates/common.tpl.php');

// Verifique se o usuário está logado, caso contrário, redirecione para a página de login
$session = new Session();
if (!$session->isLoggedIn()) {
    header("Location: /pages/login.php");
    exit;
}

$db = getDatabaseConnection();
drawHeader($session);
drawBody($session, $db);
drawFooter();
?>