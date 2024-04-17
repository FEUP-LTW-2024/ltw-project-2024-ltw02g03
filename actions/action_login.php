<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');

$db = getDatabaseConnection();

if (isset($_POST['email'], $_POST['password'])) {
    $user = User::getUserWithPassword($db, $_POST['email'], $_POST['password']);

    if ($user) {
      
        $session->setId($user->userId);
        $session->setName($user->name());
        $session->addAdmin($user->admin);
        $session->addMessage('success', 'Login successful!');
        header('Location: /'); 
        exit();
    } else {
      $session->addMessage('error', 'Wrong email or password!');
  }
} else {
  $session->addMessage('error', 'Invalid form submission!');
}

header('Location: ../pages/login.php'); 
exit();
?>
