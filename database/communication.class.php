<?php
declare(strict_types=1);

class Communication
{
    public int $communicationId;
    public int $senderId;
    public int $receiverId;
    public string $messageText;
    public string $sendDate;

    public function __construct(int $communicationId, int $senderId, int $receiverId, string $messageText, string $sendDate)
    {
        $this->communicationId = $communicationId;
        $this->senderId = $senderId;
        $this->receiverId = $receiverId;
        $this->messageText = $messageText;
        $this->sendDate = $sendDate;
    }

    // Insert a new communication
    static function insertCommunication(PDO $db, int $senderId, int $receiverId, string $messageText): int
    {
        try {
            $stmt = $db->prepare('
                INSERT INTO Communication (SenderId, ReceiverId, MessageText)
                VALUES (?, ?, ?)
            ');

            $stmt->execute(array($senderId, $receiverId, $messageText));

            return $db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error inserting communication: " . $e->getMessage());
        }
    }


    // Delete a communication
    static function deleteCommunication(PDO $db, int $communicationId): void
    {
        try {
            $stmt = $db->prepare('
                DELETE FROM Communication
                WHERE CommunicationId = ?
            ');

            $stmt->execute(array($communicationId));
        } catch (PDOException $e) {
            throw new Exception("Error deleting communication: " . $e->getMessage());
        }
    }


    // Get all communications sent by a user
    static function getSentCommunications(PDO $db, int $senderId): array
    {
        try {
            $stmt = $db->prepare('
                SELECT *
                FROM Communication
                WHERE SenderId = ?
            ');

            $stmt->execute(array($senderId));

            $communications = array();

            while ($communication = $stmt->fetch()) {
                $communications[] = new Communication(
                    $communication['CommunicationId'],
                    $communication['SenderId'],
                    $communication['ReceiverId'],
                    $communication['MessageText'],
                    $communication['SendDate']
                );
            }

            return $communications;
        } catch (PDOException $e) {
            throw new Exception("Error fetching sent communications: " . $e->getMessage());
        }
    }


    // Get all communications received by a user
    static function getReceivedCommunications(PDO $db, int $receiverId): array
    {
        try {
            $stmt = $db->prepare('
                SELECT *
                FROM Communication
                WHERE ReceiverId = ?
            ');

            $stmt->execute(array($receiverId));

            $communications = array();

            while ($communication = $stmt->fetch()) {
                $communications[] = new Communication(
                    $communication['CommunicationId'],
                    $communication['SenderId'],
                    $communication['ReceiverId'],
                    $communication['MessageText'],
                    $communication['SendDate']
                );
            }

            return $communications;
        } catch (PDOException $e) {
            throw new Exception("Error fetching received communications: " . $e->getMessage());
        }
    }

    // Get all communications between two users
    static function getCommunicationsBetweenUsers(PDO $db, int $user1Id, int $user2Id): array
    {
        try {
            $stmt = $db->prepare('
                SELECT *
                FROM Communication
                WHERE (SenderId = ? AND ReceiverId = ?) OR (SenderId = ? AND ReceiverId = ?)
            ');

            $stmt->execute(array($user1Id, $user2Id, $user2Id, $user1Id));

            $communications = array();

            while ($communication = $stmt->fetch()) {
                $communications[] = new Communication(
                    $communication['CommunicationId'],
                    $communication['SenderId'],
                    $communication['ReceiverId'],
                    $communication['MessageText'],
                    $communication['SendDate']
                );
            }

            return $communications;
        } catch (PDOException $e) {
            throw new Exception("Error fetching communications between users: " . $e->getMessage());
        }
    }

    // Get the total number of communications sent by a user
    static function getTotalSentCommunications(PDO $db, int $senderId): int
    {
        try {
            $stmt = $db->prepare('
                SELECT COUNT(*) as TotalSentCommunications
                FROM Communication
                WHERE SenderId = ?
            ');

            $stmt->execute(array($senderId));

            $result = $stmt->fetch();

            return $result['TotalSentCommunications'];
        } catch (PDOException $e) {
            throw new Exception("Error fetching total sent communications: " . $e->getMessage());
        }
    }

    // Get the total number of communications received by a user
    static function getTotalReceivedCommunications(PDO $db, int $receiverId): int
    {
        try {
            $stmt = $db->prepare('
                SELECT COUNT(*) as TotalReceivedCommunications
                FROM Communication
                WHERE ReceiverId = ?
            ');

            $stmt->execute(array($receiverId));

            $result = $stmt->fetch();

            return $result['TotalReceivedCommunications'];
        } catch (PDOException $e) {
            throw new Exception("Error fetching total received communications: " . $e->getMessage());
        }
    }

    // Get the total number of communications between two users
    static function getTotalCommunicationsBetweenUsers(PDO $db, int $user1Id, int $user2Id): int
    {
        try {
            $stmt = $db->prepare('
                SELECT COUNT(*) as TotalCommunications
                FROM Communication
                WHERE (SenderId = ? AND ReceiverId = ?) OR (SenderId = ? AND ReceiverId = ?)
            ');

            $stmt->execute(array($user1Id, $user2Id, $user2Id, $user1Id));

            $result = $stmt->fetch();

            return $result['TotalCommunications'];
        } catch (PDOException $e) {
            throw new Exception("Error fetching total communications between users: " . $e->getMessage());
        }
    }

    // Save a communication
    static function saveCommunication(PDO $db, int $communicationId, int $senderId, int $receiverId, string $messageText, string $sendDate): void
    {
        try {
            $stmt = $db->prepare('
                INSERT INTO Communication (SenderId, ReceiverId, MessageText, SendDate)
                VALUES (?, ?, ?, ?)
                WHERE CommunicationId = ?
            ');

            $stmt->execute(array($senderId, $receiverId, $messageText, $sendDate, $communicationId));
        } catch (PDOException $e) {
            throw new Exception("Error saving communication: " . $e->getMessage());
        }
    }
}
?>
