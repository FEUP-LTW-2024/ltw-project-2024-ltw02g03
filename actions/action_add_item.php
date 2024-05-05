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

    // Retrieve IDs for brand, model, condition, and size
    $brand = $brandname ? Item::getItemBrandByName($db, $brandname) : null;
    $model = $modelname ? Item::getItemModelByName($db, $modelname) : null;
    $condition = $conditionname ? Item::getItemConditionByName($db, $conditionname) : null;
    $size = $sizename ? Item::getItemSizeByName($db, $sizename) : null;
    $categories = [];
    
    if ($category1name != "none") {
        $category1 = Item::getItemCategoryByName($db, $category1name);
        if ($category1) {
            $categories[] = $category1;
        }
    }
    if ($category2name != "none") {
        $category2 = Item::getItemCategoryByName($db, $category2name);
        if ($category2) {
            $categories[] = $category2;
        }
    }
    if ($category3name != "none") {
        $category3 = Item::getItemCategoryByName($db, $category3name);
        if ($category3) {
            $categories[] = $category3;
        }
    }

    // Check if brand, model, condition, and size are found
    if (!$brand || !$model || !$condition || !$size) {
        $session->addMessage('error', 'Something went wrong');
        header('Location: ../pages/error.php');
        exit();
    }

    // Insert data into the database
    $sql = "INSERT INTO Item (SellerId, Title, Description, Price, BrandId, ModelId, ConditionId, SizeId)
            VALUES (:sellerId, :productName, :description, :price, :brandId, :modelId, :conditionId, :sizeId)";
    $stmt = $db->prepare($sql);

    if ($stmt) {
        if ($stmt->execute([$user->userId, $productName, $description, $price, $brand->brandId, $model->modelId, $condition->conditionId, $size->sizeId])) {
            $itemId = $db->lastInsertId();
            
            // Process image uploads
            $uploadDirectory = '../database/uploads/';
            $imageUrls = [];
            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                $file_name = $_FILES['images']['name'][$key];
                $file_tmp = $_FILES['images']['tmp_name'][$key];
                $file_type = $_FILES['images']['type'][$key];
                
                if ($file_type == 'image/jpeg' || $file_type == 'image/png') {
                    $itemFolder = $uploadDirectory . 'item_' . $itemId . '/';
                    
                    if (!file_exists($itemFolder)) {
                        mkdir($itemFolder, 0777, true);
                    }
                    
                    $targetFilePath = $itemFolder . $file_name;
                    
                    if (move_uploaded_file($file_tmp, $targetFilePath)) {
                        $imageUrls[] = $targetFilePath;
                    } else {
                        $session->addMessage('error', 'Failed to upload image: ' . $file_name);
                    }
                } else {
                    $session->addMessage('error', 'Only JPEG and PNG files are allowed: ' . $file_name);
                }
            }

            // Save image URLs in the database
            foreach ($imageUrls as $imageUrl) {
                $sql = "INSERT INTO ProductImage (ImageUrl) VALUES (:imageUrl)";
                $stmt = $db->prepare($sql);
                if ($stmt) {
                    if ($stmt->execute([$imageUrl])) {
                        $imageId = $db->lastInsertId();
                        
                        $sql = "INSERT INTO ItemImage (ItemId, ImageId) VALUES (:itemId, :imageId)";
                        $stmt = $db->prepare($sql);
                        if ($stmt) {
                            if ($stmt->execute([$itemId, $imageId])) {
                            } else {
                                $session->addMessage('error', 'Failed to associate image with item');
                            }
                        } else {
                            $session->addMessage('error', 'Failed to prepare SQL statement for associating image with item');
                        }
                    } else {
                        $session->addMessage('error', 'Failed to save image URL in the database');
                    }
                } else {
                    $session->addMessage('error', 'Failed to prepare SQL statement for saving image URL');
                }
            }
            
            // Associate item with categories
            foreach ($categories as $category) {
                $sql = "INSERT INTO ItemCategory (ItemId, CategoryId) VALUES (:itemId, :categoryId)";
                $stmt = $db->prepare($sql);
                if ($stmt) {
                    if ($stmt->execute([$itemId, $category->categoryId])) {
                        $session->addMessage('success', 'Item associated with category successfully');
                    } else {
                        $session->addMessage('error', 'Failed to associate item with category');
                    }
                } else {
                    $session->addMessage('error', 'Failed to prepare SQL statement for associating item with category');
                }
            }

            header("Location: ../pages/post.php?id=$itemId");
            exit();
        } else {
            $session->addMessage('error', 'Failed to insert item into the database');
            header('Location: ../pages/error.php');
            exit();
        }
    } else {
        $session->addMessage('error', 'Failed to prepare SQL statement for inserting item');
        header('Location: ../pages/error.php');
        exit();
    }
}
?>
