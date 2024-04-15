<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  if (!$session->isLoggedIn()) die(header('Location: /'));

  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../database/item.class.php');

  if (trim($_POST['title']) === '') {
    $session->addMessage('error', 'Item title cannot be empty');
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
  }

  $db = getDatabaseConnection();

  $item = Item::getItem($db, $_POST['id']);

  if ($item) {
    $item->title = $_POST['title'];
    $item->saveItem($db);
    $session->addMessage('success', 'Item title updated');
    header('Location: ../pages/item.php?id=' . $_POST['id']);
  } else {
    $session->addMessage('error', 'Item does not exist');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  }

?>