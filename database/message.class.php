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
        $stmt = $db->prepare('
            INSERT INTO Message (SenderId, ReceiverId, MessageText)
            VALUES (?, ?, ?)
        ');

        $stmt->execute(array($senderId, $receiverId, $messageText));

        return $db->lastInsertId();
    }


    // Delete a message
    static function deleteMessage(PDO $db, int $messageId): void
    {
        $stmt = $db->prepare('
            DELETE FROM Message
            WHERE MessageId = ?
        ');

        $stmt->execute(array($messageId));
    }


    // Get all messages sent by a user
    static function getSentMessages(PDO $db, int $senderId): array
    {
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
    }


    // Get all messages received by a user
    static function getReceivedMessages(PDO $db, int $receiverId): array
    {
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
    }

    // Get all messages between two users
    static function getMessagesBetweenUsers(PDO $db, int $user1Id, int $user2Id): array
    {
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
    }

    // Get the total number of messages sent by a user
    static function getTotalSentMessages(PDO $db, int $senderId): int
    {
        $stmt = $db->prepare('
            SELECT COUNT(*) as TotalSentMessages
            FROM Message
            WHERE SenderId = ?
        ');

        $stmt->execute(array($senderId));

        $result = $stmt->fetch();

        return $result['TotalSentMessages'];
    }

    // Get the total number of messages received by a user
    static function getTotalReceivedMessages(PDO $db, int $receiverId): int
    {
        $stmt = $db->prepare('
            SELECT COUNT(*) as TotalReceivedMessages
            FROM Message
            WHERE ReceiverId = ?
        ');

        $stmt->execute(array($receiverId));

        $result = $stmt->fetch();

        return $result['TotalReceivedMessages'];
    }

    // Get the total number of messages between two users
    static function getTotalMessagesBetweenUsers(PDO $db, int $user1Id, int $user2Id): int
    {
        $stmt = $db->prepare('
            SELECT COUNT(*) as TotalMessages
            FROM Message
            WHERE (SenderId = ? AND ReceiverId = ?) OR (SenderId = ? AND ReceiverId = ?)
        ');

        $stmt->execute(array($user1Id, $user2Id, $user2Id, $user1Id));

        $result = $stmt->fetch();

        return $result['TotalMessages'];
    }

    // Save a message
    static function saveMessage(PDO $db, int $messageId, int $senderId, int $receiverId, string $messageText, string $sendDate): void
    {
        $stmt = $db->prepare('
            UPDATE Message
            SET SenderId = ?, ReceiverId = ?, MessageText = ?, SendDate = ?
            WHERE MessageId = ?
        ');

        $stmt->execute(array($senderId, $receiverId, $messageText, $sendDate, $messageId));
    }
}
?>
