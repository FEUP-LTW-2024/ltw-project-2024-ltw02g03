<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

// Start the session
session_start();

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $session = new Session();
    $db = getDatabaseConnection();
    $user = User::getUser($db, $session->getId());

    // Retrieve form data
    $currentPassword = $_POST['currentPassword'] ?? '';
    $newPassword = $_POST['newPassword'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    // Validate input
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $session->addMessage('error', 'Please fill out all fields');
        redirectToProfilePage();
        exit();
    } else if ($newPassword !== $confirmPassword) {
        $session->addMessage('error', 'New password and confirm password do not match');
        redirectToProfilePage();
        exit();
        
    } else if (!password_verify($currentPassword, $user->password)) {
        $session->addMessage('error', 'Incorrect current password');
        redirectToProfilePage();
        exit();
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
                redirectToProfilePage();
                exit();
            } else {
                $session->addMessage('error', 'Failed to update password');
                redirectToProfilePage();
                exit();
            }
        
    }
}

function redirectToProfilePage() {
    $_SESSION['redirect_to_profile'] = true;
    header('Location: ../pages/profilepage.php');
    exit();
}
?>
