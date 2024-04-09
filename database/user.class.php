<?php
  declare(strict_types = 1);

  class User {
    public int $id;
    public string $name;

    public function __construct(int $id, string $name)
    { 
      $this->id = $id;
      $this->name = $name;
    }

    static function getUsers(PDO $db, int $count) : array {
      $stmt = $db->prepare('SELECT UserId, Name FROM User LIMIT ?');
      $stmt->execute(array($count));
  
      $users = array();
      while ($user = $stmt->fetch()) {
        $users[] = new User(
          $user['UserId'],
          $user['Name']
        );
      }
  
      return $users;
    }

    static function searchUsers(PDO $db, string $search, int $count) : array {
      $stmt = $db->prepare('SELECT UserId, Name FROM User WHERE Name LIKE ? LIMIT ?');
      $stmt->execute(array($search . '%', $count));
  
      $users = array();
      while ($user = $stmt->fetch()) {
        $users[] = new user(
          $user['UserId'],
          $user['Name']
        );
      }
  
      return $users;
    }


    static function getUser(PDO $db, int $id) : User {
      $stmt = $db->prepare('SELECT UserId, Name FROM User WHERE UserId = ?');
      $stmt->execute(array($id));
  
      $user = $stmt->fetch();
  
      return new User(
        $user['UserId'], 
        $user['Name']
      );
    }  
  }
?>