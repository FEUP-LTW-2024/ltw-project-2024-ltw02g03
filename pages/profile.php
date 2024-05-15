<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  if (!$session->isLoggedIn()) {
    header('Location: /pages/login.php');
    exit();
}


  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../templates/common.tpl.php');
  require_once(__DIR__ . '/../templates/customer.tpl.php');

  $db = getDatabaseConnection();

  $user = User::getUser($db, $session->getId());

  drawHeader($session, $db);
  drawProfileForm($user);
  drawFooter();
?>