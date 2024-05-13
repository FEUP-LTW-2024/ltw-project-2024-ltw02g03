<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  
  
  require_once(__DIR__ . '/../database/connection.db.php');

  $db = getDatabaseConnection();

  

  require_once(__DIR__ . '/../templates/post.tpl.php');
  require_once(__DIR__ . '/../templates/common.tpl.php');
  session_start();

  // Check if redirection is needed
  if (isset($_SESSION['redirect_to_profile']) && $_SESSION['redirect_to_profile']) {
      unset($_SESSION['redirect_to_profile']); // Clear the session variable
      header('Location: ../pages/profilepage.php'); // Perform the redirection
      exit();
  }
  $session = new Session();
  

  

  drawHeader($session, $db);
  drawPostCreation($session, $db);
  drawFooter();
?>