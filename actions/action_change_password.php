<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$session = new Session();



// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = getDatabaseConnection();
    $user = User::getUser($db, $session->getId());

    // Retrieve form data
    $currentPassword = $_POST['currentPassword'] ?? '';
    $newPassword = $_POST['newPassword'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    // Validate input
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $session->addMessage('error', 'Please fill out all fields');
    } else if ($newPassword !== $confirmPassword) {
        $session->addMessage('error', 'New password and confirm password do not match');
    } else if (!password_verify($currentPassword, $user->password)) {
        $session->addMessage('error', 'Incorrect current password');
    } else {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the user's password in the database
        $sql = "UPDATE User SET Password = :password WHERE UserId = :userId";
        $stmt = $db->prepare($sql);

        
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':userId', $user->userId);

            if ($stmt->execute()) {
                $session->addMessage('success', 'Password changed successfully');
            } else {
                $session->addMessage('error', 'Failed to update password');
            }
        
    }
    header('Location: ../pages/profilepage.php');
    exit();
}
?>
