<?php
  declare(strict_types = 1);

  class Album {
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

    static function getItem(PDO $db, int $id) : array {
      $stmt = $db->prepare('
        SELECT ItemId,UserId, Title, Description, Price,ListingDate
        FROM (User JOIN Item USING (UserId) ) JOIN 
        WHERE UserId = ?
        GROUP BY ItemId
      ');
      $stmt->execute(array($id));
  
      $Item = array();
  
      while ($Item = $stmt->fetch()) {
        $Item[] = new Album(
          $Item['AlbumId'], 
          $Item['Title'],
          $Item['tracks'],
          
          $Item['ArtistId']
        );
    }
    return $Item;
  }
    static function getItemCategory(PDO $db, int $id) : array {
      $stmt = $db->prepare('
        SELECT ItemId, CategoryId, CategoryName
        FROM ItemCategory JOIN ProductCategory
        WHERE ItemId = ?
        ');
    
    $stmt->execute(array($id));

    $albums = array();

    while ($album = $stmt->fetch()) {
      $albums[] = new Album(
        $album['AlbumId'], 
        $album['Title'],
        $album['tracks'],
        intval(round($album['length'] / 1000 / 60)),
        $album['ArtistId']
      );
    }

    return $albums;
  }

    static function getAlbum(PDO $db, int $id) : Album {
      $stmt = $db->prepare('
        SELECT AlbumId, Title, COUNT(*) AS tracks, SUM(Milliseconds) AS length, ArtistId 
        FROM Album JOIN Track USING (AlbumId) 
        WHERE AlbumId = ?
        GROUP BY AlbumId
      ');
      $stmt->execute(array($id));
  
      $album = $stmt->fetch();
  
      return new Album(
        $album['AlbumId'], 
        $album['Title'], 
        $album['tracks'],
        intval(round($album['length'] / 1000 / 60)),
        $album['ArtistId']
      );
    }

    function save(PDO $db) {
      $stmt = $db->prepare('
        UPDATE ALBUM SET Title = ?
        WHERE AlbumId = ?
      ');

      $stmt->execute(array($this->title, $this->id));
    }
  
  }
?>