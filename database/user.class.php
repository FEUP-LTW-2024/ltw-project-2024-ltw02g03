<?php
declare(strict_types=1);

class User {
    public int $id;
    public string $name;

    public function __construct(int $id, string $name)
    { 
        $this->id = $id;
        $this->name = $name;
    }
    
    // Get all users
    static function getUsers(PDO $db, int $count) : array {
        try {
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
        } catch (PDOException $e) {
            throw new Exception("Error fetching users: " . $e->getMessage());
        }
    }

    // Search for users by name
    static function searchUsers(PDO $db, string $search, int $count) : array {
        try {
            $stmt = $db->prepare('SELECT UserId, Name FROM User WHERE Name LIKE ? LIMIT ?');
            $stmt->execute(array($search . '%', $count));
    
            $users = array();
            while ($user = $stmt->fetch()) {
                $users[] = new User(
                    $user['UserId'],
                    $user['Name']
                );
            }
    
            return $users;
        } catch (PDOException $e) {
            throw new Exception("Error searching users: " . $e->getMessage());
        }
    }

    // Get a user by ID
    static function getUser(PDO $db, int $id) : User {
        try {
            $stmt = $db->prepare('SELECT UserId, Name FROM User WHERE UserId = ?');
            $stmt->execute(array($id));
    
            $user = $stmt->fetch();
    
            return new User(
                $user['UserId'], 
                $user['Name']
            );
        } catch (PDOException $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }  

    // Insert a new user
    static function insertUser(PDO $db, string $name) : int {
        try {
            $stmt = $db->prepare('INSERT INTO User (Name) VALUES (?)');
            $stmt->execute(array($name));
    
            return $db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error inserting user: " . $e->getMessage());
        }
    }

    // Update a user
    static function updateUser(PDO $db, int $id, string $name) : void {
        try {
            $stmt = $db->prepare('UPDATE User SET Name = ? WHERE UserId = ?');
            $stmt->execute(array($name, $id));
        } catch (PDOException $e) {
            throw new Exception("Error updating user: " . $e->getMessage());
        }
    }

    // Delete a user
    static function deleteUser(PDO $db, int $id) : void {
        try {
            $stmt = $db->prepare('DELETE FROM User WHERE UserId = ?');
            $stmt->execute(array($id));
        } catch (PDOException $e) {
            throw new Exception("Error deleting user: " . $e->getMessage());
        }
    }

    // Save a user
    static function saveUser(PDO $db, string $name) : int {
        try {
            $stmt = $db->prepare('INSERT INTO User (Name) VALUES (?)');
            $stmt->execute(array($name));
            return $db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error saving user: " . $e->getMessage());
        }
    }
}
?>
