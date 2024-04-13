<?php
declare(strict_types=1);

class Transaction
{
    public int $transactionId;
    public int $buyerId;
    public int $sellerId;
    public int $itemId;
    public string $transactionDate;

    public function __construct(int $transactionId, int $buyerId, int $sellerId, int $itemId, string $transactionDate)
    {
        $this->transactionId = $transactionId;
        $this->buyerId = $buyerId;
        $this->sellerId = $sellerId;
        $this->itemId = $itemId;
        $this->transactionDate = $transactionDate;
    }

    // Insert a transaction
    static function insertTransaction(PDO $db, int $buyerId, int $sellerId, int $itemId, string $transactionDate): int
    {
        try {
            $stmt = $db->prepare('
                INSERT INTO Transaction (BuyerId, SellerId, ItemId, TransactionDate)
                VALUES (?, ?, ?, ?)
            ');

            $stmt->execute(array($buyerId, $sellerId, $itemId, $transactionDate));

            return $db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error inserting transaction: " . $e->getMessage());
        }
    }

    // Update a transaction
    static function updateTransaction(PDO $db, int $transactionId, int $buyerId, int $sellerId, int $itemId, string $transactionDate): void
    {
        try {
            $stmt = $db->prepare('
                UPDATE Transaction
                SET BuyerId = ?, SellerId = ?, ItemId = ?, TransactionDate = ?
                WHERE TransactionId = ?
            ');

            $stmt->execute(array($buyerId, $sellerId, $itemId, $transactionDate, $transactionId));
        } catch (PDOException $e) {
            throw new Exception("Error updating transaction: " . $e->getMessage());
        }
    }

    // Delete a transaction
    static function deleteTransaction(PDO $db, int $transactionId): void
    {
        try {
            $stmt = $db->prepare('
                DELETE FROM Transaction
                WHERE TransactionId = ?
            ');

            $stmt->execute(array($transactionId));
        } catch (PDOException $e) {
            throw new Exception("Error deleting transaction: " . $e->getMessage());
        }
    }

    // Get a transaction
    static function getTransaction(PDO $db, int $transactionId): Transaction
    {
        try {
            $stmt = $db->prepare('
                SELECT *
                FROM Transaction
                WHERE TransactionId = ?
            ');

            $stmt->execute(array($transactionId));

            $transaction = $stmt->fetch();

            return new Transaction(
                $transaction['TransactionId'],
                $transaction['BuyerId'],
                $transaction['SellerId'],
                $transaction['ItemId'],
                $transaction['TransactionDate']
            );
        } catch (PDOException $e) {  
            throw new Exception("Error fetching transaction: " . $e->getMessage());
        }
    }

    // Get all transactions
    static function getAllTransactions(PDO $db): array
    {
        try {
            $stmt = $db->prepare('
                SELECT *
                FROM Transaction
            ');

            $stmt->execute();

            $transactions = array();

            while ($transaction = $stmt->fetch()) {
                $transactions[] = new Transaction(
                    $transaction['TransactionId'],
                    $transaction['BuyerId'],
                    $transaction['SellerId'],
                    $transaction['ItemId'],
                    $transaction['TransactionDate']
                );
            }

            return $transactions;
        } catch (PDOException $e) {      
            throw new Exception("Error fetching transactions: " . $e->getMessage());
        }
    }

    // Get all transactions for a buyer
    static function getBuyerTransactions(PDO $db, int $buyerId): array
    {
        try {
            $stmt = $db->prepare('
                SELECT *
                FROM Transaction
                WHERE BuyerId = ?
            ');

            $stmt->execute(array($buyerId));
            $buyerTransactions = array();

            while ($transaction = $stmt->fetch()) {
                $buyerTransactions[] = new Transaction(
                    $transaction['TransactionId'],
                    $transaction['BuyerId'],
                    $transaction['SellerId'],
                    $transaction['ItemId'],
                    $transaction['TransactionDate']
                );
            }

            return $buyerTransactions;
        } catch (PDOException $e) {    
            throw new Exception("Error fetching buyer transactions: " . $e->getMessage());
        }
    }

    // Get all transactions for a seller
    static function getSellerTransactions(PDO $db, int $sellerId): array
    {
        try {
            $stmt = $db->prepare('
                SELECT *
                FROM Transaction
                WHERE SellerId = ?
            ');

            $stmt->execute(array($sellerId));
            $sellerTransactions = array();

            while ($transaction = $stmt->fetch()) {
                $sellerTransactions[] = new Transaction(
                    $transaction['TransactionId'],
                    $transaction['BuyerId'],
                    $transaction['SellerId'],
                    $transaction['ItemId'],
                    $transaction['TransactionDate']
                );
            }

            return $sellerTransactions;
        } catch (PDOException $e) {
            throw new Exception("Error fetching seller transactions: " . $e->getMessage());
        }
    }

    // Save a transaction
    static function saveTransaction(PDO $db, Transaction $transaction): void
    {
        try {
            $stmt = $db->prepare('
                INSERT INTO Transaction (BuyerId, SellerId, ItemId, TransactionDate)
                VALUES (?, ?, ?, ?)
            ');

            $stmt->execute(array(
                $transaction->buyerId,
                $transaction->sellerId,
                $transaction->itemId,
                $transaction->transactionDate
            ));
        } catch (PDOException $e) {
            throw new Exception("Error saving transaction: " . $e->getMessage());
        }
    }
}
?>
