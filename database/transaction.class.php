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
        $stmt = $db->prepare('
            INSERT INTO Transaction (BuyerId, SellerId, ItemId, TransactionDate)
            VALUES (?, ?, ?, ?)
        ');

        $stmt->execute(array($buyerId, $sellerId, $itemId, $transactionDate));

        return $db->lastInsertId();
    }

    // Update a transaction
    static function updateTransaction(PDO $db, int $transactionId, int $buyerId, int $sellerId, int $itemId, string $transactionDate): void
    {
        $stmt = $db->prepare('
            UPDATE Transaction
            SET BuyerId = ?, SellerId = ?, ItemId = ?, TransactionDate = ?
            WHERE TransactionId = ?
        ');

        $stmt->execute(array($buyerId, $sellerId, $itemId, $transactionDate, $transactionId));
    }


    // Delete a transaction
    static function deleteTransaction(PDO $db, int $transactionId): void
    {
        $stmt = $db->prepare('
            DELETE FROM Transaction
            WHERE TransactionId = ?
        ');

        $stmt->execute(array($transactionId));
    }

    // Get a transaction
    static function getTransaction(PDO $db, int $transactionId): Transaction
    {
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
    }

    // Get all transactions
    static function getAllTransactions(PDO $db): array
    {
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
    }

    // Get all transactions for a buyer
    public static function getBuyerTransactions(PDO $db, int $buyerId): array
{
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
}


// Get all transactions for a seller
public static function getSellerTransactions(PDO $db, int $sellerId): array
{
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
}

// Save a transaction
public static function saveTransaction(PDO $db, Transaction $transaction): void
{
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
}


}
?>
