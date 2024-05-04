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
    public string $imageUrl;

    public function __construct(int $imageId, string $imageUrl) {
        $this->imageId = $imageId;
        $this->imageUrl = $imageUrl;
    }
}

class Item {
    public int $itemId;
    public int $sellerId;
    public string $title;
    public string $description;
    public float $price;
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
    static function getItem(PDO $db, int $id) : ?Item {
        try {
            $stmt = $db->prepare('
                SELECT ItemId, SellerId, Title, Description, Price, ListingDate
                FROM Item
                WHERE ItemId = ?
            ');
            $stmt->execute(array($id));
    
            // Fetch the item
            $item = $stmt->fetch();
    
            // Check if item exists
            if (!$item) {
                return null;
            }
    
            // Return a new Item object
            return new Item(
                $item['ItemId'],
                $item['SellerId'],
                $item['Title'],
                $item['Description'],
                $item['Price'],
                $item['ListingDate']
            );
        } catch (PDOException $e) {
            throw new Exception("Error fetching item: " . $e->getMessage());
        }
    }
    //Get item name by ID
    static function getItemNameById(PDO $db, int $id) : string {
        try {
            $stmt = $db->prepare('SELECT Title FROM Item WHERE ItemId = ?');
            $stmt->execute(array($id));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['Title'];
        } catch (PDOException $e) {
            throw new Exception("Error fetching item name: " . $e->getMessage());
        }
    }
    static function getItemsbyName(PDO $db, string $get , int $limit): array{
        try {
            $stmt = $db->prepare('
                SELECT ItemId, SellerId, Title, Description, Price, ListingDate
                FROM Item
                WHERE Title LIKE ? 
                LIMIT ?
            ');
            $stmt->bindValue(1, '%' . $get . '%', PDO::PARAM_STR);
            $stmt->bindValue(2, $limit, PDO::PARAM_INT);
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
            throw new Exception("Error fetching items by name: " . $e->getMessage());
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
        $stmt = $db->prepare('SELECT ItemId, Name, Description, Price, ListingDate 
        FROM Item WHERE Name LIKE ? LIMIT ?');
        $stmt->execute(array($search . '%', $count));
    
        $item = array();
        while ($item = $stmt->fetch()) {
          $items[] = new Item(
            $item['ItemId'],
            $item['SellerId'],
            $item['Name'],
            $item['Description'],
            $item['Price'],
            $item['ListingDate']

          );
        }
    
        return $items;
      }
    
      static function getItemsByCategoryName(PDO $db, int $limit, string $categoryName): array {
        try {
            $stmt = $db->prepare('
                SELECT Item.ItemId, SellerId, Title, Description, Price, ListingDate
                FROM Item
                JOIN ItemCategory ON Item.ItemId = ItemCategory.ItemId
                JOIN ProductCategory ON ItemCategory.CategoryId = ProductCategory.CategoryId
                WHERE ProductCategory.CategoryName = ?
                LIMIT ?
            ');
            $stmt->bindValue(1, $categoryName, PDO::PARAM_STR);
            $stmt->bindValue(2, $limit, PDO::PARAM_INT);
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
            throw new Exception("Error fetching items by category: " . $e->getMessage());
        }
    }

    // Get total products count
    static function getCountbyCategory($db, ?string $categoryName = null) {
        try {
            if ($categoryName) {
                $stmt = $db->prepare('SELECT COUNT(*) AS total FROM Item 
                                      JOIN ItemCategory ON Item.ItemId = ItemCategory.ItemId
                                      JOIN ProductCategory ON ItemCategory.CategoryId = ProductCategory.CategoryId
                                      WHERE ProductCategory.CategoryName = ?
                                      ');
                $stmt->execute([$categoryName]);
            } else {
                $stmt = $db->query('SELECT COUNT(*) AS total FROM Item');
            }
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['total'];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return 0; 
        }
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
public static function getItemBrand(PDO $db, int $id) : ?Brand {
    try {
        $stmt = $db->prepare('
            SELECT ItemBrand.BrandId, BrandName
            FROM ItemBrand 
            JOIN Item ON ItemBrand.BrandId = Item.BrandId
            WHERE Item.ItemId = ?
            
        ');
        $stmt->execute(array($id));
        $brand = $stmt->fetch();

        if ($brand) {
            return new Brand($brand['BrandId'], $brand['BrandName']);
        } else {
            return null;
        }
    } catch (PDOException $e) {
        throw new Exception("Error fetching item brands: " . $e->getMessage());
    }
}

// Get item condition
static function getItemCondition(PDO $db, int $id) : ?Condition {
    try {
        $stmt = $db->prepare('
            SELECT ItemCondition.ConditionId, ConditionName
            FROM ItemCondition 
            JOIN Item ON ItemCondition.ConditionId = Item.ConditionId
            WHERE Item.ItemId = ?
        ');
        $stmt->execute(array($id));
        $condition = $stmt->fetch();

        if ($condition) {
            return new Condition($condition['ConditionId'], $condition['ConditionName']);
        } else {
            return null;
        }
    } catch (PDOException $e) {
        throw new Exception("Error fetching item conditions: " . $e->getMessage());
    }
}

public static function getItemBrandByName(PDO $db, string $brandname) : ?Brand {
    try {
        $stmt = $db->prepare('
            SELECT BrandId, BrandName
            FROM ItemBrand 
            WHERE BrandName = ?
        ');
        $stmt->execute([$brandname]);
        $brand = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($brand) {
            return new Brand($brand['BrandId'], $brand['BrandName']);
        } else {
            return null;
        }
    } catch (PDOException $e) {
        throw new Exception("Error fetching item brands: " . $e->getMessage());
    }
}

public static function getItemConditionByName(PDO $db, string $conditionname) : ?Condition {
    try {
        $stmt = $db->prepare('
            SELECT ConditionId, ConditionName
            FROM ItemCondition 
            WHERE ConditionName = ?
        ');
        $stmt->execute([$conditionname]);
        $condition = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($condition) {
            return new Condition($condition['ConditionId'], $condition['ConditionName']);
        } else {
            return null;
        }
    } catch (PDOException $e) {
        throw new Exception("Error fetching item conditions: " . $e->getMessage());
    }
}



// Get Category by Name
static function getItemCategoryByName(PDO $db, string $categoryName) : ?Category {
    try {
        $stmt = $db->prepare('SELECT CategoryId, CategoryName FROM ProductCategory WHERE CategoryName = ?');
        $stmt->execute([$categoryName]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($category) {
            return new Category($category['CategoryId'], $category['CategoryName']);
        } else {
            return null;
        }
    } catch (PDOException $e) {
        throw new Exception("Error fetching category: " . $e->getMessage());
    }
}






// Get item size
static function getItemSize(PDO $db, int $id) : ?Size {
    try {
        $stmt = $db->prepare('
            SELECT ItemSize.SizeId, SizeName
            FROM ItemSize 
            JOIN Item ON ItemSize.SizeId = Item.SizeId
            WHERE Item.ItemId = ?
        ');
        $stmt->execute(array($id));
        $size = $stmt->fetch();

        if ($size) {
            return new Size($size['SizeId'], $size['SizeName']);
        } else {
            return null;
        }
    } catch (PDOException $e) {
        throw new Exception("Error fetching item sizes: " . $e->getMessage());
    }
}

// Get item model
static function getItemModel(PDO $db, int $id) : ?Model {
    try {
        $stmt = $db->prepare('
            SELECT ItemModel.ModelId, ModelName
            FROM ItemModel 
            JOIN Item ON ItemModel.ModelId = Item.ModelId
            WHERE Item.ItemId = ?
            
        ');
        $stmt->execute(array($id));
        $model = $stmt->fetch();

        if ($model) {
            return new Model($model['ModelId'], $model['ModelName']);
        } else {
            return null;
        }
    } catch (PDOException $e) {
        throw new Exception("Error fetching item models: " . $e->getMessage());
    }
    }

    // Get item image
    static function getItemImage(PDO $db, int $id) : array {
        try {
            $stmt = $db->prepare('
                SELECT ItemImage.ImageId, ImageUrl
                FROM ItemImage 
                JOIN ProductImage ON ItemImage.ImageId = ProductImage.ImageId
                WHERE ItemImage.ItemId = ?
            ');
            $stmt->execute(array($id));
            $images = array();

            while ($image = $stmt->fetch()) {
                $images[] = new Image($image['ImageId'], $image['ImageUrl']);
            }

            return $images;
        } catch (PDOException $e) {
            throw new Exception("Error fetching item images: " . $e->getMessage());
        }
    }

    // Save item 
    static function saveItem(PDO $db, int $sellerId, string $title, string $description, float $price, string $listingDate, array $imageUrls) : string {
        try {
            $stmt = $db->prepare('
                INSERT INTO Item (SellerId, Title, Description, Price, ListingDate)
                VALUES (?, ?, ?, ?, ?)
            ');
            $stmt->execute(array($sellerId, $title, $description, $price, $listingDate));
            $itemId = $db->lastInsertId();

            foreach ($imageUrls as $imageUrl) {
                $stmt = $db->prepare('INSERT INTO ProductImage (ImageUrl) VALUES (?)');
                $stmt->execute([$imageUrl]);
                $imageId = $db->lastInsertId();

                $stmt = $db->prepare('INSERT INTO ItemImage (ItemId, ImageId) VALUES (?, ?)');
                $stmt->execute([$itemId, $imageId]);
            }

            return $itemId;
        } catch (PDOException $e) {
            throw new Exception("Error saving item: " . $e->getMessage());
        }
    }
    // Get items by title
static function searchItemsByTitle(PDO $db, string $title, int $count) : array {
    try {
        $stmt = $db->prepare('
            SELECT ItemId, SellerId, Title, Description, Price, ListingDate
            FROM Item
            WHERE Title LIKE ? 
            LIMIT ?
        ');
        $stmt->bindValue(1, '%' . $title . '%', PDO::PARAM_STR);
        $stmt->bindValue(2, $count, PDO::PARAM_INT);
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
        throw new Exception("Error fetching items by title: " . $e->getMessage());
    }
}

//Get all Brands
static function getBrands(PDO $db) : array {
    try {
        $stmt = $db->query('SELECT BrandName FROM ItemBrand');
        $brands = array();

        while ($brand = $stmt->fetch()) {
            $brands[] = $brand['BrandName'];
        }

        return $brands;
    } catch (PDOException $e) {
        throw new Exception("Error fetching brands: " . $e->getMessage());
    }

}

//Get all Conditions
static function getConditions(PDO $db) : array {
    try {
        $stmt = $db->query('SELECT ConditionName FROM ItemCondition');
        $conditions = array();

        while ($condition = $stmt->fetch()) {
            $conditions[] =  $condition['ConditionName'];
        }

        return $conditions;
    } catch (PDOException $e) {
        throw new Exception("Error fetching conditions: " . $e->getMessage());
    }


}

//Get all Sizes
static function getSizes(PDO $db) : array {
    try {
        $stmt = $db->query('SELECT SizeName FROM ItemSize');
        $sizes = array();

        while ($size = $stmt->fetch()) {
            $sizes[] = $size['SizeName'];
        }

        return $sizes;
    } catch (PDOException $e) {
        throw new Exception("Error fetching sizes: " . $e->getMessage());
    }

}

//Get all Models
static function getModels(PDO $db) : array {
    try {
        $stmt = $db->query('SELECT  ModelName FROM ItemModel');
        $models = array();

        while ($model = $stmt->fetch()) {
            $models[] = ($model['ModelName']);
        }

        return $models;
    } catch (PDOException $e) {
        throw new Exception("Error fetching models: " . $e->getMessage());
    }

}

//Get all Categories
static function getCategories(PDO $db) : array {
    try {
        $stmt = $db->query('SELECT CategoryName FROM ProductCategory');
        $categories = array();

        while ($category = $stmt->fetch()) {
            $categories[] = $category['CategoryName'];
        }

        return $categories;
    } catch (PDOException $e) {
        throw new Exception("Error fetching categories: " . $e->getMessage());
    }

}


static function getConditionsObj(PDO $db): array {
    try {
        $stmt = $db->query('SELECT ConditionId, ConditionName FROM ItemCondition');
        $conditions = array();

        while ($condition = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $conditions[] = new Condition($condition['ConditionId'], $condition['ConditionName']);
        }

        return $conditions;
    } catch (PDOException $e) {
        throw new Exception("Error fetching conditions: " . $e->getMessage());
    }
}


static function getSizesObj(PDO $db): array {
    try {
        $stmt = $db->query('SELECT SizeId, SizeName FROM ItemSize');
        $sizes = array();

        while ($size = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sizes[] = new Size($size['SizeId'], $size['SizeName']);
        }

        return $sizes;
    } catch (PDOException $e) {
        throw new Exception("Error fetching sizes: " . $e->getMessage());
    }
}

// Get Brand by Name

// Get Model by Name
static function getItemModelByName(PDO $db, string $modelName) : ?Model {
    try {
        $stmt = $db->prepare('SELECT ModelId, ModelName FROM ItemModel WHERE ModelName = ?');
        $stmt->execute([$modelName]);
        $model = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($model) {
            return new Model($model['ModelId'], $model['ModelName']);
        } else {
            return null;
        }
    } catch (PDOException $e) {
        throw new Exception("Error fetching model: " . $e->getMessage());
    }
}

// Get Condition by Name


// Get Size by Name
static function getItemSizeByName(PDO $db, string $sizeName) : ?Size {
    try {
        $stmt = $db->prepare('SELECT SizeId, SizeName FROM ItemSize WHERE SizeName = ?');
        $stmt->execute([$sizeName]);
        $size = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($size) {
            return new Size($size['SizeId'], $size['SizeName']);
        } else {
            return null;
        }
    } catch (PDOException $e) {
        throw new Exception("Error fetching size: " . $e->getMessage());
    }
}


}
?>