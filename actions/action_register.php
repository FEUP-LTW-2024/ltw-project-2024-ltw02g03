<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');

$db = getDatabaseConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repeatPassword = $_POST['repeat_password'];

    if ($password !== $repeatPassword) {
        $session->addMessage('error', 'Passwords do not match.');
        header("Location: /pages/register.php");
        exit();
    }
    $joinDate = date("Y-m-d"); 
    // Outros campos opcionais
    $address = $_POST['address'] ?? null;
    $city = $_POST['city'] ?? null;
    $district = $_POST['district'] ?? null;
    $country = $_POST['country'] ?? null;
    $postalCode = $_POST['postalCode'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $imageUrl = $_POST['imageUrl'] ?? null;
    $admin = false;

    try {
        
        $userId = User::registerUser($db, $firstName, $lastName, $username, $email, $password, $joinDate, $address, $city, $district, $country, $postalCode, $phone, $imageUrl, $admin);
        $session->addMessage('success', 'Register successful!');
        header("Location: /pages"); 
        exit();
    
    } catch (Exception $e) {
        $session->addMessage('error', 'Invalid form submission!');
        header("Location: /pages/register.php");

        exit();
    }
} else {
   
    header("Location: /pages/register.php");
    exit();
}

?>
