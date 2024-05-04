<?php

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/item.class.php');

// Verifique se um formulário para criar uma nova marca foi enviado
if (isset($_POST['createBrand'])) {
    $db = getDatabaseConnection();
    $brandName = $_POST['brandName'];

    try {
        Item::createBrand($db, $brandName);
        echo "Brand created successfully!";
    } catch (Exception $e) {
        echo "Error creating brand: " . $e->getMessage();
    }
}

// Verifique se um formulário para criar uma nova condição foi enviado
if (isset($_POST['createCondition'])) {
    $db = getDatabaseConnection();
    $conditionName = $_POST['conditionName'];

    try {
        Item::createCondition($db, $conditionName);
        echo "Condition created successfully!";
    } catch (Exception $e) {
        echo "Error creating condition: " . $e->getMessage();
    }
}

// Verifique se um formulário para criar um novo tamanho foi enviado
if (isset($_POST['createSize'])) {
    $db = getDatabaseConnection();
    $sizeName = $_POST['sizeName'];

    try {
        Item::createSize($db, $sizeName);
        echo "Size created successfully!";
    } catch (Exception $e) {
        echo "Error creating size: " . $e->getMessage();
    }
}

// Verifique se um formulário para criar uma nova categoria foi enviado
if (isset($_POST['createCategory'])) {
    $db = getDatabaseConnection();
    $categoryName = $_POST['categoryName'];

    try {
        Item::createCategory($db, $categoryName);
        echo "Category created successfully!";
    } catch (Exception $e) {
        echo "Error creating category: " . $e->getMessage();
    }
}

// Verifique se um formulário para criar um novo modelo de item foi enviado
if (isset($_POST['createModel'])) {
    $db = getDatabaseConnection();
    $modelName = $_POST['modelName'];

    try {
        Item::createModel($db, $modelName);
        echo "Model created successfully!";
    } catch (Exception $e) {
        echo "Error creating model: " . $e->getMessage();
    }
}

?>
