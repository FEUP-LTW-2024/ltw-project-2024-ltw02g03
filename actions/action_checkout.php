<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $address = $_POST['address'] ?? null;
        $city = $_POST['city'] ?? null;
        $district = $_POST['district'] ?? null;
        $country = $_POST['country'] ?? null;
        
        $session = new Session();
        $userId = $session->getId();
        
        $db = getDatabaseConnection();
        User::updateUserAddress($db, $userId, $address, $city, $district, $country);
    
        header('Location: /../pages/checkout.php');
        exit();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header('Location: /../pages/checkout.php');
    exit();
}
