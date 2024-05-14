<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');

$session = new Session();

if (!$session->isLoggedIn()) {
    die(header('Location: /'));
}

$db = getDatabaseConnection();
$user = User::getUser($db, $session->getId());

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize an array to store the fields to be updated
    $fieldsToUpdate = [];

    // Retrieve form data and add non-empty fields to the update array
    if (!empty($_POST['email'])) {
        $fieldsToUpdate['Email'] = $_POST['email'];
    }
    if (!empty($_POST['username'])) {
        $fieldsToUpdate['Username'] = $_POST['username'];
    }
    if (!empty($_POST['firstName'])) {
        $fieldsToUpdate['FirstName'] = $_POST['firstName'];
    }
    if (!empty($_POST['lastName'])) {
        $fieldsToUpdate['LastName'] = $_POST['lastName'];
    }
    if (!empty($_POST['address'])) {
        $fieldsToUpdate['Address'] = $_POST['address'];
    }
    if (!empty($_POST['city'])) {
        $fieldsToUpdate['City'] = $_POST['city'];
    }
    if (!empty($_POST['district'])) {
        $fieldsToUpdate['District'] = $_POST['district'];
    }
    if (!empty($_POST['country'])) {
        $fieldsToUpdate['Country'] = $_POST['country'];
    }
    if (!empty($_POST['postalCode'])) {
        $fieldsToUpdate['PostalCode'] = $_POST['postalCode'];
    }
    if (!empty($_POST['phone'])) {
        $fieldsToUpdate['Phone'] = $_POST['phone'];
    }
    
    if (!empty($_FILES['images']['name'][0])) {
        $uploadDirectory = '../database/uploads/';
        $imageUrls = [];

        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            if (!empty($_FILES['images']['name'][$key])) {
                $file_name = $_FILES['images']['name'][$key];
                $file_tmp = $_FILES['images']['tmp_name'][$key];
                $file_type = $_FILES['images']['type'][$key];
                
                if ($file_type == 'image/jpeg' || $file_type == 'image/png') {
                    // Generate directory for the user if not exists
                    $userFolder = $uploadDirectory . 'user_' . $user->userId . '/';
                    if (!file_exists($userFolder)) {
                        mkdir($userFolder, 0777, true);
                    }
                    
                    $targetFile = $userFolder . $file_name;
                    
                    if (move_uploaded_file($file_tmp, $targetFile)) {
                        // Delete old profile picture from database if exists
                        if (!empty($user->imageUrl)) {
                            unlink($user->imageUrl);
                        }
                        $fieldsToUpdate['ImageUrl'] = $targetFile;
                    } else {
                        $session->addMessage('error', 'Failed to upload image: ' . $file_name);
                    }
                } else {
                    $session->addMessage('error', 'Only JPEG and PNG files are allowed: ' . $file_name);
                }
            }
        }
    }

    // Other form data processing goes here...
    
    // Update user's profile if there are fields to be updated
    if (!empty($fieldsToUpdate)) {
        // Construct the SET part of the SQL query dynamically
        $setClause = '';
        foreach ($fieldsToUpdate as $field => $value) {
            $setClause .= "$field = :$field, ";
        }
        // Remove the trailing comma and space
        $setClause = rtrim($setClause, ', ');

        // Prepare and execute the SQL update query
        $sql = "UPDATE User SET $setClause WHERE UserId = :userId";
        $stmt = $db->prepare($sql);

        if ($stmt) {
            // Bind parameters
            foreach ($fieldsToUpdate as $field => &$value) {
                $stmt->bindParam(":$field", $value);
            }
            // Bind UserId
            $stmt->bindParam(':userId', $user->userId);

            // Execute the query
            if ($stmt->execute()) {
                $session->addMessage('success', 'Profile updated successfully');
                header('Location: ../pages/profilepage.php');
                exit();
            } else {
                $session->addMessage('error', 'Failed to update profile');
                header('Location: ../pages/errorfailedtoupdateprofile.php');
                exit();
            }
        } else {
            $session->addMessage('error', 'Failed to prepare SQL statement');
            header('Location: ../pages/errorsqlstatement.php');
            exit();
        }
    } else {
        // No fields to update, redirect back to profile page
        header('Location: ../pages/profilepage.php');
        exit();
    }
}
?>
