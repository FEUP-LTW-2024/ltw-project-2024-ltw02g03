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
    $price = floatval($_POST['price'] ?? 0); 
    $description = $_POST['description'] ?? '';
    $brandname = $_POST['brand'] ?? '';
    $modelname = $_POST['model'] ?? '';
    $conditionname = $_POST['condition'] ?? '';
    $sizename = $_POST['size'] ?? '';
    $category1name = $_POST['category1'] ?? '';
    $category2name = $_POST['category2'] ?? '';
    $category3name = $_POST['category3'] ?? '';
    $image1 = $_POST['image1'] ?? '';
    $image2 = $_POST['image2'] ?? '';
    $image3 = $_POST['image3'] ?? '';

    

    // Retrieve IDs for brand, model, condition, and size
    if($brandname != "none") {
        $brand = $brandname ? Item::getItemBrandByName($db, $brandname) : null;
    }
    
    $model = $modelname ? Item::getItemModelByName($db, $modelname) : null;

    $condition = $conditionname ? Item::getItemConditionByName($db, $conditionname) : null;
    $size = $sizename ? Item::getItemSizeByName($db, $sizename) : null;
    $categories = [];
    
    if($category1name != "none") {
        $category1 = Item::getItemCategoryByName($db, $category1name);
        if ($category1) {
            $categories[] = $category1;
        }
    }
    if($category2name != "none") {
        $category2 = Item::getItemCategoryByName($db, $category2name);
        if ($category2) {
            $categories[] = $category2;
        }
    }
    if($category3name != "none") {
        $category3 = Item::getItemCategoryByName($db, $category3name);
        if ($category3) {
            $categories[] = $category3;
        }
    }
    


    // Check if brand, model, condition, and size are found
    if (!$condition || !$size) {
        $session->addMessage('error', 'Something went wrong');
    } else {
        // Insert data into the database
        $sql = "INSERT INTO Item (SellerId, Title, Description, Price, BrandId, ModelId, ConditionId, SizeId)
                VALUES (:sellerId, :productName, :description, :price, :brandId, :modelId, :conditionId, :sizeId)";
        $stmt = $db->prepare($sql);

        if ($stmt) {
            

            if ($stmt->execute([$user->userId,$productName,$description,$price,$brand->brandId,$model->modelId,$condition->conditionId,$size->sizeId])) {
                $itemId = $db->lastInsertId();
                foreach ($categories as $category) {
                    
                        $sql = "INSERT INTO ItemCategory (ItemId, CategoryId) VALUES (:itemId, :categoryId)";
                        $stmt = $db->prepare($sql);
                        $stmt->execute([$itemId, $category->categoryId]);   
                }
                
                $session->addMessage('success', 'Successfully published');
                header("Location: ../pages/post.php?id= $itemId ");
                exit();
                
            } else {
                
                $session->addMessage('error', 'Something went wrong');
               
            }

            $stmt->closeCursor(); // Close cursor to allow next execution
        } else {
            echo "Error: " . implode(', ', $db->errorInfo());
        }
    }
}
?>
