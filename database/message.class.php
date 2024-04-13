<?php
declare(strict_types=1);

class Message
{
    public int $messageId;
    public int $senderId;
    public int $receiverId;
    public string $messageText;
    public string $sendDate;

    public function __construct(int $messageId, int $senderId, int $receiverId, string $messageText, string $sendDate)
    {
        $this->messageId = $messageId;
        $this->senderId = $senderId;
        $this->receiverId = $receiverId;
        $this->messageText = $messageText;
        $this->sendDate = $sendDate;
    }

    // Insert a new message
    static function insertMessage(PDO $db, int $senderId, int $receiverId, string $messageText): int
    {
        try {
            $stmt = $db->prepare('
                INSERT INTO Message (SenderId, ReceiverId, MessageText)
                VALUES (?, ?, ?)
            ');

            $stmt->execute(array($senderId, $receiverId, $messageText));

            return $db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error inserting message: " . $e->getMessage());
        }
    }


    // Delete a message
    static function deleteMessage(PDO $db, int $messageId): void
    {
        try {
            $stmt = $db->prepare('
                DELETE FROM Message
                WHERE MessageId = ?
            ');

            $stmt->execute(array($messageId));
        } catch (PDOException $e) {
            throw new Exception("Error deleting message: " . $e->getMessage());
        }
    }


    // Get all messages sent by a user
    static function getSentMessages(PDO $db, int $senderId): array
    {
        try {
            $stmt = $db->prepare('
                SELECT *
                FROM Message
                WHERE SenderId = ?
            ');

            $stmt->execute(array($senderId));

            $messages = array();

            while ($message = $stmt->fetch()) {
                $messages[] = new Message(
                    $message['MessageId'],
                    $message['SenderId'],
                    $message['ReceiverId'],
                    $message['MessageText'],
                    $message['SendDate']
                );
            }

            return $messages;
        } catch (PDOException $e) {
            throw new Exception("Error fetching sent messages: " . $e->getMessage());
        }
    }


    // Get all messages received by a user
    static function getReceivedMessages(PDO $db, int $receiverId): array
    {
        try {
            $stmt = $db->prepare('
                SELECT *
                FROM Message
                WHERE ReceiverId = ?
            ');

            $stmt->execute(array($receiverId));

            $messages = array();

            while ($message = $stmt->fetch()) {
                $messages[] = new Message(
                    $message['MessageId'],
                    $message['SenderId'],
                    $message['ReceiverId'],
                    $message['MessageText'],
                    $message['SendDate']
                );
            }

            return $messages;
        } catch (PDOException $e) {
            throw new Exception("Error fetching received messages: " . $e->getMessage());
        }
    }

    // Get all messages between two users
    static function getMessagesBetweenUsers(PDO $db, int $user1Id, int $user2Id): array
    {
        try {
            $stmt = $db->prepare('
                SELECT *
                FROM Message
                WHERE (SenderId = ? AND ReceiverId = ?) OR (SenderId = ? AND ReceiverId = ?)
            ');

            $stmt->execute(array($user1Id, $user2Id, $user2Id, $user1Id));

            $messages = array();

            while ($message = $stmt->fetch()) {
                $messages[] = new Message(
                    $message['MessageId'],
                    $message['SenderId'],
                    $message['ReceiverId'],
                    $message['MessageText'],
                    $message['SendDate']
                );
            }

            return $messages;
        } catch (PDOException $e) {
            throw new Exception("Error fetching messages between users: " . $e->getMessage());
        }
    }

    // Get the total number of messages sent by a user
    static function getTotalSentMessages(PDO $db, int $senderId): int
    {
        try {
            $stmt = $db->prepare('
                SELECT COUNT(*) as TotalSentMessages
                FROM Message
                WHERE SenderId = ?
            ');

            $stmt->execute(array($senderId));

            $result = $stmt->fetch();

            return $result['TotalSentMessages'];
        } catch (PDOException $e) {
            throw new Exception("Error fetching total sent messages: " . $e->getMessage());
        }
    }

    // Get the total number of messages received by a user
    static function getTotalReceivedMessages(PDO $db, int $receiverId): int
    {
        try {
            $stmt = $db->prepare('
                SELECT COUNT(*) as TotalReceivedMessages
                FROM Message
                WHERE ReceiverId = ?
            ');

            $stmt->execute(array($receiverId));

            $result = $stmt->fetch();

            return $result['TotalReceivedMessages'];
        } catch (PDOException $e) {
            throw new Exception("Error fetching total received messages: " . $e->getMessage());
        }
    }

    // Get the total number of messages between two users
    static function getTotalMessagesBetweenUsers(PDO $db, int $user1Id, int $user2Id): int
    {
        try {
            $stmt = $db->prepare('
                SELECT COUNT(*) as TotalMessages
                FROM Message
                WHERE (SenderId = ? AND ReceiverId = ?) OR (SenderId = ? AND ReceiverId = ?)
            ');

            $stmt->execute(array($user1Id, $user2Id, $user2Id, $user1Id));

            $result = $stmt->fetch();

            return $result['TotalMessages'];
        } catch (PDOException $e) {
            throw new Exception("Error fetching total messages between users: " . $e->getMessage());
        }
    }

    // Save a message
    static function saveMessage(PDO $db, int $messageId, int $senderId, int $receiverId, string $messageText, string $sendDate): void
    {
        try {
            $stmt = $db->prepare('
                INSERT INTO Message (SenderId, ReceiverId, MessageText, SendDate)
                VALUES (?, ?, ?, ?)
                WHERE MessageId = ?
            ');

            $stmt->execute(array($senderId, $receiverId, $messageText, $sendDate, $messageId));
        } catch (PDOException $e) {
            throw new Exception("Error saving message: " . $e->getMessage());
        }
    }
}
?>
