<?php
declare(strict_types=1);

class User {
    public int $userId;
    public string $firstName;
    public string $lastName;
    public string $username;
    public string $email;
    public string $password;
    public string $joinDate;
    public ?string $address;
    public ?string $city;
    public ?string $district;
    public ?string $country;
    public ?string $postalCode;
    public ?string $phone;
    public ?string $imageUrl;
    public bool $admin;

    public function __construct(
        int $userId,
        string $firstName,
        string $lastName,
        string $username,
        string $email,
        string $password,
        string $joinDate,
        ?string $address,
        ?string $city,
        ?string $district,
        ?string $country,
        ?string $postalCode,
        ?string $phone,
        ?string $imageUrl,
        bool $admin
    )
    { 
        $this->userId = $userId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->joinDate = $joinDate;
        $this->address = $address;
        $this->city = $city;
        $this->district = $district;
        $this->country = $country;
        $this->postalCode = $postalCode;
        $this->phone = $phone;
        $this->imageUrl = $imageUrl;
        $this->admin = $admin;
    }

    // Get the full name of the user
    function name() {
        return $this->firstName . ' ' . $this->lastName;
      }
    
    // Get a User
    static function getUser(PDO $db, int $id) : User{
        $stmt= $db->prepare('SELECT * 
        FROM User 
        WHERE UserId = ?
        ');
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return new User(
            $user['UserId'],
            $user['FirstName'],
            $user['LastName'],
            $user['Username'],
            $user['Email'],
            $user['Password'],
            $user['JoinDate'],
            $user['Address'],
            $user['City'],
            $user['District'],
            $user['Country'],
            $user['PostalCode'],
            $user['Phone'],
            $user['ImageUrl'],
            $user['Admin']
        );
    }


    // Get all users
    static function getUsers(PDO $db, int $count) : array {
        try {
            $stmt = $db->prepare('SELECT UserId, FirstName, LastName, Username, Email, Password, JoinDate, Address, City, District, Country, PostalCode, Phone, ImageUrl, Admin 
            FROM User LIMIT ?
            ');
            $stmt->execute([$count]);
            
            
            $admin = (bool) $user['Admin'];
            $users = [];
            while ($user = $stmt->fetch()) {
                $users[] = new User(
                    $user['UserId'],
                    $user['FirstName'],
                    $user['LastName'],
                    $user['Username'],
                    $user['Email'],
                    $user['Password'],
                    $user['JoinDate'],
                    $user['Address'],
                    $user['City'],
                    $user['District'],
                    $user['Country'],
                    $user['PostalCode'],
                    $user['Phone'],
                    $user['ImageUrl'],
                    $admin
                );
            }
    
            return $users;
        } catch (PDOException $e) {
            throw new Exception("Error fetching users: " . $e->getMessage());
        }
    }



    static function searchUsers(PDO $db, string $search, int $count) : array {
        try {
            $stmt = $db->prepare('SELECT UserId, FirstName, LastName, Username, Email, Password, JoinDate, Address, City, District, Country, PostalCode, Phone, ImageUrl, Admin FROM User WHERE FirstName LIKE ? OR LastName LIKE ? LIMIT ?');
            $stmt->execute([$search . '%', $search . '%', $count]);
    
            $users = [];
            while ($user = $stmt->fetch()) {
                $users[] = new User(
                    $user['UserId'],
                    $user['FirstName'],
                    $user['LastName'],
                    $user['Username'],
                    $user['Email'],
                    $user['Password'],
                    $user['JoinDate'],
                    $user['Address'],
                    $user['City'],
                    $user['District'],
                    $user['Country'],
                    $user['PostalCode'],
                    $user['Phone'],
                    $user['ImageUrl'],
                    $user['Admin']
                );
            }
    
            return $users;
        } catch (PDOException $e) {
            throw new Exception("Error searching users: " . $e->getMessage());
        }
    }

    // Get user with password
    static function getUserWithPassword(PDO $db, string $email, string $password) : ?User {
        try {
            $stmt = $db->prepare('SELECT UserId, FirstName, LastName, Username, Email, Password, JoinDate, Address, City, District, Country, PostalCode, Phone, ImageUrl, Admin 
            FROM User 
            WHERE lower(Email) = ? AND Password = ?
            ');
            //adaptar com o metodo indicado
            $stmt->execute([strtolower($email), sha1($password)]);
    
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($user) {
                return new User(
                    $user['UserId'],
                    $user['FirstName'],
                    $user['LastName'],
                    $user['Username'],
                    $user['Email'],
                    $user['Password'],
                    $user['JoinDate'],
                    $user['Address'],
                    $user['City'],
                    $user['District'],
                    $user['Country'],
                    $user['PostalCode'],
                    $user['Phone'],
                    $user['ImageUrl'],
                    $user['Admin']
                );
            } else {
                return null;
            }
        } catch (PDOException $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }


    // Get a user by ID
    static function getUserById(PDO $db, int $userId) : ?User {
        try {
            $stmt = $db->prepare('SELECT UserId, FirstName, LastName, Username, Email, Password, JoinDate, Address, City, District, Country, PostalCode, Phone, ImageUrl, Admin FROM User WHERE UserId = ?');
            $stmt->execute([$userId]);
    
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($user) {
                return new User(
                    $user['UserId'],
                    $user['FirstName'],
                    $user['LastName'],
                    $user['Username'],
                    $user['Email'],
                    $user['Password'],
                    $user['JoinDate'],
                    $user['Address'],
                    $user['City'],
                    $user['District'],
                    $user['Country'],
                    $user['PostalCode'],
                    $user['Phone'],
                    $user['ImageUrl'],
                    $user['Admin']
                );
            } else {
                return null;
            }
        } catch (PDOException $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }  

    // Insert a new user
    static function insertUser(PDO $db, string $firstName, string $lastName, string $username, string $email, string $password, ?string $address, ?string $city, ?string $district, ?string $country, ?string $postalCode, ?string $phone, ?string $imageUrl, bool $admin) : int {
        try {
            $joinDate = date("Y-m-d");
            $stmt = $db->prepare('INSERT INTO User (FirstName, LastName, Username, Email, Password, JoinDate, Address, City, District, Country, PostalCode, Phone, ImageUrl, Admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$firstName, $lastName, $username, $email, $password, $joinDate, $address, $city, $district, $country, $postalCode, $phone, $imageUrl, $admin]);
    
            return $db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error inserting user: " . $e->getMessage());
        }
    }

    // Update a user
    static function updateUser(PDO $db, int $userId, string $firstName, string $lastName, string $username, string $email, string $password, ?string $address, ?string $city, ?string $district, ?string $country, ?string $postalCode, ?string $phone, ?string $imageUrl, bool $admin) : void {
        try {
            $stmt = $db->prepare('UPDATE User SET FirstName = ?, LastName = ?, Username = ?, Email = ?, Password = ?, Address = ?, City = ?, District = ?, Country = ?, PostalCode = ?, Phone = ?, ImageUrl = ?, Admin = ? WHERE UserId = ?');
            $stmt->execute([$firstName, $lastName, $username, $email, $password, $address, $city, $district, $country, $postalCode, $phone, $imageUrl, $admin, $userId]);
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
   static function saveUser(PDO $db, string $firstName, string $lastName, string $username, string $email, string $password, ?string $address, ?string $city, ?string $district, ?string $country, ?string $postalCode, ?string $phone, ?string $imageUrl, bool $admin) : int {
        try {
            $stmt = $db->prepare('INSERT INTO User (FirstName, LastName, Username, Email, Password, Address, City, District, Country, PostalCode, Phone, ImageUrl, Admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$firstName, $lastName, $username, $email, $password, $address, $city, $district, $country, $postalCode, $phone, $imageUrl, $admin]);
    
            return $db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error saving user: " . $e->getMessage());
        }
    }

}
?>
