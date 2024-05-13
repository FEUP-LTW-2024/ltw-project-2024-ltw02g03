<?php
declare(strict_types=1);

class Review
{
    public int $reviewId;
    public int $userId;
    public int $itemId;
    public float $rating;
    public string $comment;
    public string $reviewDate;

    public function __construct(int $reviewId, int $userId, int $itemId, float $rating, string $comment, string $reviewDate)
    {
        $this->reviewId = $reviewId;
        $this->userId = $userId;
        $this->itemId = $itemId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->reviewDate = $reviewDate;
    }
    
    // Insert a new review
    static function insertReview(PDO $db, int $userId, int $itemId, float $rating, string $comment)
    {
        try {
            $stmt = $db->prepare('
                INSERT INTO Review (UserId, ItemId, Rating, Comment, ReviewDate)
                VALUES (?, ?, ?, ?,datetime("now"))
            ');

            $stmt->execute(array($userId, $itemId, $rating, $comment));

            return $db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error inserting review: " . $e->getMessage());
        }
    }

    
}
?>
