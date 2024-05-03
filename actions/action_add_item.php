<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/item.class.php');
$session = new Session();

if (!$session->isLoggedIn()) {
    die(header('Location: /'));
}

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');

$db = getDatabaseConnection();

$user = User::getUser($db, $session->getId());

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $productName = $_POST['productname'] ?? '';
    $price = floatval($_POST['price'] ?? 0); // Ensure it's a float
    $description = $_POST['description'] ?? '';
    $brandname = $_POST['brand'] ?? '';
    $modelname = $_POST['model'] ?? '';
    $conditionname = $_POST['condition'] ?? '';
    $sizename = $_POST['size'] ?? '';

    // Retrieve IDs for brand, model, condition, and size
    $brand = $brandname ? Item::getItemBrandByName($db, $brandname) : null;
    $model = $modelname ? Item::getItemModelByName($db, $modelname) : null;
    $condition = $conditionname ? Item::getItemConditionByName($db, $conditionname) : null;
    $size = $sizename ? Item::getItemSizeByName($db, $sizename) : null;

    // Check if brand, model, condition, and size are found
    if (!$brand || !$model || !$condition || !$size) {
        echo "Error: Brand, model, condition, or size not found.";
    } else {
        // Insert data into the database
        $sql = "INSERT INTO Item (SellerId, Title, Description, Price, BrandId, ModelId, ConditionId, SizeId)
                VALUES (:sellerId, :productName, :description, :price, :brandId, :modelId, :conditionId, :sizeId)";
        $stmt = $db->prepare($sql);

        if ($stmt) {
        

            if ($stmt->execute([$user->userId,$productName,$description,$price,$brand->brandId,$model->modelId,$condition->conditionId,$size->sizeId])) {
                echo "Item added successfully.";
            } else {
                echo "Error: " . implode(', ', $stmt->errorInfo());
            }

            $stmt->closeCursor(); // Close cursor to allow next execution
        } else {
            echo "Error: " . implode(', ', $db->errorInfo());
        }
    }
}
?>
