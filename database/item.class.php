<?php
declare(strict_types=1);

class Category {
    public int $categoryId;
    public string $categoryName;

    public function __construct(int $categoryId, string $categoryName) {
        $this->categoryId = $categoryId;
        $this->categoryName = $categoryName;
    }
}

class Brand {
    public int $brandId;
    public string $brandName;

    public function __construct(int $brandId, string $brandName) {
        $this->brandId = $brandId;
        $this->brandName = $brandName;
    }
}

class Condition {
    public int $conditionId;
    public string $conditionName;

    public function __construct(int $conditionId, string $conditionName) {
        $this->conditionId = $conditionId;
        $this->conditionName = $conditionName;
    }
}

class Size {
    public int $sizeId;
    public string $sizeName;

    public function __construct(int $sizeId, string $sizeName) {
        $this->sizeId = $sizeId;
        $this->sizeName = $sizeName;
    }
}

class Model {
    public int $modelId;
    public string $modelName;

    public function __construct(int $modelId, string $modelName) {
        $this->modelId = $modelId;
        $this->modelName = $modelName;
    }
}

class Image {
    public int $imageId;
    public string $imagePath;

    public function __construct(int $imageId, string $imagePath) {
        $this->imageId = $imageId;
        $this->imagePath = $imagePath;
    }
}

class Item {
    public int $itemId;
    public int $sellerId;
    public string $title;
    public string $description;
    public float $price;
    public string $condition;
    public string $listingDate;

    public function __construct(int $itemId, int $sellerId,  string $title, string $description, float $price, string $listingDate) {
        $this->itemId = $itemId;
        $this->sellerId = $sellerId;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->listingDate = $listingDate;
    }

    // Get item by Id
    static function getItem(PDO $db, int $id) : array {
        try {
            $stmt = $db->prepare('
                SELECT ItemId, SellerId, Title, Description, Price, ListingDate
                FROM Item
                WHERE ItemId = ?
            ');
            $stmt->execute(array($id));
            $items = array();

            while ($item = $stmt->fetch()) {
                $items[] = new Item(
                    $item['ItemId'],
                    $item['SellerId'],
                    $item['Title'],
                    $item['Description'],
                    $item['Price'],
                    $item['ListingDate']
                );
            }
            return $items;
        } catch (PDOException $e) {
            throw new Exception("Error fetching items: " . $e->getMessage());
        }
    }

    // Get x Items
    static function getItems(PDO $db, int $limit) : array {
        try {
            $stmt = $db->prepare('
                SELECT ItemId, SellerId, Title, Description, Price, ListingDate
                FROM Item
                LIMIT :limit
            ');
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT); // Bind do valor do limite
            $stmt->execute();
            $items = array();

            while ($item = $stmt->fetch()) {
                $items[] = new Item(
                    $item['ItemId'],
                    $item['SellerId'],
                    $item['Title'],
                    $item['Description'],
                    $item['Price'],
                    $item['ListingDate']
                );
            }
            return $items;
        } catch (PDOException $e) {
            throw new Exception("Error fetching items: " . $e->getMessage());
        }
    }
    static function searchItems(PDO $db, string $search, int $count) : array {
        $stmt = $db->prepare('SELECT ItemId, Name, Description, Price, Condition, ListingDate 
        FROM Item WHERE Name LIKE ? LIMIT ?');
        $stmt->execute(array($search . '%', $count));
    
        $item = array();
        while ($item = $stmt->fetch()) {
          $items[] = new Item(
            $item['ArtistId'],
            $item['Name'],
            $item['Description'],
            $item['Price'],
            $item['Condition'],
            $item['ListingDate']

          );
        }
    
        return $items;
      }


    // Get item category
    static function getItemCategory(PDO $db, int $id) : array {
        try {
            $stmt = $db->prepare('
                SELECT ItemId, CategoryId, CategoryName
                FROM ItemCategory 
                JOIN ProductCategory ON ItemCategory.CategoryId = ProductCategory.CategoryId
                WHERE ItemId = ?
            ');
            $stmt->execute(array($id));
            $categories = array();

            while ($category = $stmt->fetch()) {
                $categories[] = new Category($category['CategoryId'], $category['CategoryName']);
            }

            return $categories;
        } catch (PDOException $e) {
            throw new Exception("Error fetching item categories: " . $e->getMessage());
        }
    }

    // Get item brand
    public static function getItemBrand(PDO $db, int $id) : array {
        try {
            $stmt = $db->prepare('
                SELECT BrandId, BrandName
                FROM ItemBrand 
                JOIN Item ON ItemBrand.BrandId = Item.BrandId
                WHERE ItemId = ?
                
            ');
            $stmt->execute(array($id));
            $brands = array();

            while ($brand = $stmt->fetch()) {
                $brands[] = new Brand($brand['BrandId'], $brand['BrandName']);
            }

            return $brands;
        } catch (PDOException $e) {
            throw new Exception("Error fetching item brands: " . $e->getMessage());
        }
    }

    // Get item condition
    static function getItemCondition(PDO $db, int $id) : string {
        try {
            $stmt = $db->prepare('
                SELECT ItemId, ConditionId, ConditionName
                FROM ItemCondition 
                JOIN Item ON ItemCondition.ConditionId = Item.ConditionId
                WHERE ItemId = ?
            ');
            $stmt->execute(array($id));
            

            while ($condition = $stmt->fetch()) {
                $conditions = new Condition($condition['ConditionId'], $condition['ConditionName']);
            }

            return $conditions;
        } catch (PDOException $e) {
            throw new Exception("Error fetching item conditions: " . $e->getMessage());
        }
    }

    // Get item size
    static function getItemSize(PDO $db, int $id) : array {
        echo "ola";
        try {
            $stmt = $db->prepare('
                SELECT ItemId, ItemSize.SizeId, SizeName
                FROM ItemSize 
                JOIN Item ON ItemSize.SizeId = Item.SizeId
                WHERE ItemId = ?
            ');
            $stmt->execute(array($id));
            //$sizes = array();
            

            $size = $stmt->fetch();
            //$sizes = new Size($size['SizeId'], $size['SizeName']);
            

            return $size;
        } catch (PDOException $e) {
            throw new Exception("Error fetching item sizes: " . $e->getMessage());
        }
    }

    // Get item model
    static function getItemModel(PDO $db, int $id) : array {
        try {
            $stmt = $db->prepare('
                SELECT ItemId, ModelId, ModelName
                FROM ItemModel 
                JOIN Item ON ItemModel.ModelId = Item.ModelId
                WHERE ItemId = ?
            ');
            $stmt->execute(array($id));
            $models = array();

            while ($model = $stmt->fetch()) {
                $models[] = new Model($model['ModelId'], $model['ModelName']);
            }

            return $models;
        } catch (PDOException $e) {
            throw new Exception("Error fetching item models: " . $e->getMessage());
        }
    }

    // Get item image
    static function getItemImage(PDO $db, int $id) : array {
        try {
            $stmt = $db->prepare('
                SELECT ItemId, ImageId, ImagePath
                FROM ItemImage 
                JOIN Item ON ItemImage.ImageId = Item.ImageId
                WHERE ItemId = ?
            ');
            $stmt->execute(array($id));
            $images = array();

            while ($image = $stmt->fetch()) {
                $images[] = new Image($image['ImageId'], $image['ImagePath']);
            }

            return $images;
        } catch (PDOException $e) {
            throw new Exception("Error fetching item images: " . $e->getMessage());
        }
    }

    // Save item
    static function  saveItem(PDO $db, int $sellerId, string $title, string $description, float $price, string $condition, string $listingDate) : string {
        try {
            $stmt = $db->prepare('
                INSERT INTO Item (SellerId, Title, Description, Price, `Condition`, ListingDate)
                VALUES (?, ?, ?, ?, ?, ?)
            ');
            $stmt->execute(array($sellerId, $title, $description, $price, $condition, $listingDate));
            return $db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error saving item: " . $e->getMessage());
        }
    }
}
?>
