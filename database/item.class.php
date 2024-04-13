<?php
  declare(strict_types = 1);
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

  //get all items
  static function getItem(PDO $db, int $id) : array {
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
}

  // get item category
  static function getItemCategory(PDO $db, int $id) : array {
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
  }

  // get item brand
  public static function getItemBrand(PDO $db, int $id) : array {
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
}

  // get item condition
  static function getItemCondition(PDO $db, int $id) : array {
    $stmt = $db->prepare('
        SELECT ItemId, ConditionId, ConditionName
        FROM ItemCondition 
        JOIN Item ON ItemCondition.ConditionId = Item.ConditionId
        WHERE ItemId = ?
    ');
    $stmt->execute(array($id));
    $conditions = array();

    while ($condition = $stmt->fetch()) {
        $conditions[] = new Condition($condition['ConditionId'], $condition['ConditionName']);
    }

    return $conditions;
  }

  //get item size
  static function getItemSize(PDO $db, int $id) : array {
    $stmt = $db->prepare('
        SELECT ItemId, SizeId, SizeName
        FROM ItemSize 
        JOIN Item ON ItemSize.SizeId = Item.SizeId
        WHERE ItemId = ?
    ');
    $stmt->execute(array($id));
    $sizes = array();

    while ($size = $stmt->fetch()) {
        $sizes[] = new Size($size['SizeId'], $size['SizeName']);
    }

    return $sizes;
  }

  // get item model
  static function getItemModel(PDO $db, int $id) : array {
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
  }

  // get item  image  
  static function getItemImage(PDO $db, int $id) : array {
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
  }


  // function to save item
  function saveItem(PDO $db, int $sellerId, string $title, string $description, float $price, string $condition, string $listingDate) : int {
    $stmt = $db->prepare('
        INSERT INTO Item (SellerId, Title, Description, Price, Condition, ListingDate)
        VALUES (?, ?, ?, ?, ?, ?)
    ');
    $stmt->execute(array($sellerId, $title, $description, $price, $condition, $listingDate));
    return $db->lastInsertId();

}
?>