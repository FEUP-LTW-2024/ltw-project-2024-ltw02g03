<?php


require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/item.class.php');
function getBrands($db) {
    
    $brands = Item::getBrands($db);

    header('Content-Type: application/json');
    echo json_encode($brands);
}


function getConditions($db) {
    
    $conditions = Item::getConditions($db);


    header('Content-Type: application/json');
    echo json_encode($conditions);
}


function getSizes($db) {
    
    $sizes = Item::getSizes($db);


    header('Content-Type: application/json');
    echo json_encode($sizes);
}

function getModels($db) {
    $models = Item::getModels($db);

    
    header('Content-Type: application/json');
    echo json_encode($models);
}
function getCategories($db) {
    $categories = Item::getCategories($db);

    
    header('Content-Type: application/json');
    echo json_encode($categories);
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    $db = getDatabaseConnection();
    $action = $_GET['action'];
    
    if ($action === 'get-brands') {
        getBrands($db);
    } elseif ($action === 'get-conditions') {
        getConditions($db);
    } elseif ($action === 'get-sizes') {
        getSizes($db);
    } elseif ($action === 'get-models') {
        getModels($db);
    }
    elseif ($action === 'get-categories') {
        getCategories($db); 
    }else {
        http_response_code(404);
        echo "Not Found";
    }
} else {
    http_response_code(404);
    echo "Not Found";
}