<?php
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/user.class.php');
$session = new Session();

// Verifique se um formulário para criar uma nova marca foi enviado
if (isset($_POST['createBrand'])) {
    $db = getDatabaseConnection();
    $brandName = $_POST['brandName'];

    try {
        $brand=Item::createBrand($db, $brandName);
        if($brand==0)
            $session->addMessage('success', 'Brand created successfully!');
        else
            $session->addMessage('error', 'Brand already exists!');
    } catch (Exception $e) {
        $session->addMessage('error', 'Error removing model!');        
    }
    header('Location: /../pages/profilepage.php');
}

if (isset($_POST['createCondition'])) {
    $db = getDatabaseConnection();
    $conditionName = $_POST['conditionName'];

    try {
        $condition=Item::createCondition($db, $conditionName);
        if($condition==0)
            $session->addMessage('success', 'Condition created successfully!');
        else
            $session->addMessage('error', 'Condition already exists!');
    } catch (Exception $e) {
        $session->addMessage('error', 'Error creating condition!');
    }
    header('Location: /../pages/profilepage.php');

}

if (isset($_POST['createSize'])) {
    $db = getDatabaseConnection();
    $sizeName = $_POST['sizeName'];

    try {
        $size=Item::createSize($db, $sizeName);
        if($size==0)
            $session->addMessage('success', 'Size created successfully!');
        else
            $session->addMessage('error', 'Size already exists!');
    } catch (Exception $e) {
        $session->addMessage('error', 'Error creating size!');
    }
    header('Location: /../pages/profilepage.php');

}

// Verifique se um formulário para criar uma nova categoria foi enviado
if (isset($_POST['createCategory'])) {
    $db = getDatabaseConnection();
    $categoryName = $_POST['categoryName'];

    try {
        $category=Item::createCategory($db, $categoryName);
        if($category==0)
            $session->addMessage('success', 'Category created successfully!');
        else
            $session->addMessage('error', 'Category already exists!');
    } catch (Exception $e) {
        $session->addMessage('error', 'Error creating category!');
    }
    header('Location: /../pages/profilepage.php');

}

// Verifique se um formulário para criar um novo modelo de item foi enviado
if (isset($_POST['createModel'])) {
    $db = getDatabaseConnection();
    $modelName = $_POST['modelName'];

    try {
        $model=Item::createModel($db, $modelName);
        if($model==0)
            $session->addMessage('success', 'Model created successfully!');
        else
            $session->addMessage('error', 'Model already exists!');
    } catch (Exception $e) {
        $session->addMessage('error', 'Error creating model!');
    }
    header('Location: /../pages/profilepage.php');

}

// Verifique se um formulário para elevar um usuário a administrador foi enviado
if (isset($_POST['elevateAdmin'])) {
    $db = getDatabaseConnection();
    $userId = $_POST['userId'];
    try {
        User::elevateUserToAdmin($db, $userId);
        $session->addMessage('success', 'User elevated to admin successfully!');
    } catch (Exception $e) {
        $session->addMessage('error', 'Error elevating user to admin!');
    }
    header('Location: /../pages/profilepage.php');

}


?>