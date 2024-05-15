<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  require_once(__DIR__ . '/../database/connection.db.php');

  $session = new Session();

  if (!$session->isLoggedIn()) {
    header('Location: /pages/login.php');
    exit();
}

  $db = getDatabaseConnection();

  

  require_once(__DIR__ . '/../templates/post.tpl.php');
  require_once(__DIR__ . '/../templates/common.tpl.php');
  session_start();

  if (isset($_SESSION['redirect_to_profile']) && $_SESSION['redirect_to_profile']) {
      unset($_SESSION['redirect_to_profile']); 
      header('Location: ../pages/profilepage.php'); 
      exit();
  }
  $session = new Session();
  

  

  drawHeader($session, $db);
  drawPostCreation($session, $db);
  drawFooter();
?>