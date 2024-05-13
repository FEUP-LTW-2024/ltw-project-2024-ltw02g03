<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');

// Start the session
session_start();

// Check if redirection is needed
if (isset($_SESSION['redirect_to_profile']) && $_SESSION['redirect_to_profile']) {
    unset($_SESSION['redirect_to_profile']); // Clear the session variable
    header('Location: ../pages/profilepage.php'); // Perform the redirection
    exit();
}

// Include necessary files and define functions
require_once(__DIR__ . '/../templates/profile.tpl.php');
require_once(__DIR__ . '/../templates/common.tpl.php');

$db = getDatabaseConnection();

// Draw header, profile content, and footer
drawHeader($session, $db);
drawProfile($session, $db);
drawFooter();
?>
