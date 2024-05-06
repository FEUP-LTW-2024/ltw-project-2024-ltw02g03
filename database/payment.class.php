<?php
declare(strict_types=1);

class Payment
{
    public int $paymentId;
    public int $buyerId;
    public int $sellerId;
    public int $itemId;
    public string $paymentDate;

    public function __construct(int $paymentId, int $buyerId, int $sellerId, int $itemId, string $paymentDate)
    {
        $this->paymentId = $paymentId;
        $this->buyerId = $buyerId;
        $this->sellerId = $sellerId;
        $this->itemId = $itemId;
        $this->paymentDate = $paymentDate;
    }
    

    // Insert a new payment
    static function insertPayment(PDO $db, int $buyerId, int $sellerId, int $itemId,string $address,string $city, string $district,string $country, string $postalCode ,string $paymentDate): String
    {
        try {
            $stmt = $db->prepare('
                INSERT INTO Payment (BuyerId, SellerId, ItemId,address,city,district,country,postalCode, PaymentDate)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ');

            $stmt->execute(array($buyerId, $sellerId, $itemId, $address, $city, $district, $country, $postalCode, $paymentDate));

            return $db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error inserting payment: " . $e->getMessage());
        }
    }

    // Update a payment
    static function updatePayment(PDO $db, int $paymentId, int $buyerId, int $sellerId, int $itemId, string $paymentDate): void
    {
        try {
            $stmt = $db->prepare('
                UPDATE Payment
                SET BuyerId = ?, SellerId = ?, ItemId = ?, PaymentDate = ?
                WHERE PaymentId = ?
            ');

            $stmt->execute(array($buyerId, $sellerId, $itemId, $paymentDate, $paymentId));
        } catch (PDOException $e) {
            throw new Exception("Error updating payment: " . $e->getMessage());
        }
    }

    // Delete a payment
    static function deletePayment(PDO $db, int $paymentId): void
    {
        try {
            $stmt = $db->prepare('
                DELETE FROM Payment
                WHERE PaymentId = ?
            ');

            $stmt->execute(array($paymentId));
        } catch (PDOException $e) {
            throw new Exception("Error deleting payment: " . $e->getMessage());
        }
    }

    // Get a payment
    static function getPayment(PDO $db, int $paymentId): Payment
    {
        try {
            $stmt = $db->prepare('
                SELECT *
                FROM Payment
                WHERE PaymentId = ?
            ');

            $stmt->execute(array($paymentId));

            $payment = $stmt->fetch();

            return new Payment(
                $payment['PaymentId'],
                $payment['BuyerId'],
                $payment['SellerId'],
                $payment['ItemId'],
                $payment['PaymentDate']
            );
        } catch (PDOException $e) {  
            throw new Exception("Error fetching payment: " . $e->getMessage());
        }
    }

    // Get all payments
    static function getAllPayments(PDO $db): array
    {
        try {
            $stmt = $db->prepare('
                SELECT *
                FROM Payment
            ');

            $stmt->execute();

            $payments = array();

            while ($payment = $stmt->fetch()) {
                $payments[] = new Payment(
                    $payment['PaymentId'],
                    $payment['BuyerId'],
                    $payment['SellerId'],
                    $payment['ItemId'],
                    $payment['PaymentDate']
                );
            }

            return $payments;
        } catch (PDOException $e) {      
            throw new Exception("Error fetching payments: " . $e->getMessage());
        }
    }

    // Get all payments for a buyer
    static function getBuyerPayments(PDO $db, int $buyerId): array
    {
        try {
            $stmt = $db->prepare('
                SELECT *
                FROM Payment
                WHERE BuyerId = ?
            ');

            $stmt->execute(array($buyerId));
            $buyerPayments = array();

            while ($payment = $stmt->fetch()) {
                $buyerPayments[] = new Payment(
                    $payment['PaymentId'],
                    $payment['BuyerId'],
                    $payment['SellerId'],
                    $payment['ItemId'],
                    $payment['PaymentDate']
                );
            }

            return $buyerPayments;
        } catch (PDOException $e) {    
            throw new Exception("Error fetching buyer payments: " . $e->getMessage());
        }
    }

    // Get all payments for a seller
    static function getSellerPayments(PDO $db, int $sellerId): array
    {
        try {
            $stmt = $db->prepare('
                SELECT *
                FROM Payment
                WHERE SellerId = ?
            ');

            $stmt->execute(array($sellerId));
            $sellerPayments = array();

            while ($payment = $stmt->fetch()) {
                $sellerPayments[] = new Payment(
                    $payment['PaymentId'],
                    $payment['BuyerId'],
                    $payment['SellerId'],
                    $payment['ItemId'],
                    $payment['PaymentDate']
                );
            }

            return $sellerPayments;
        } catch (PDOException $e) {
            throw new Exception("Error fetching seller payments: " . $e->getMessage());
        }
    }

    // Save a payment
    static function savePayment(PDO $db, Payment $payment): void
    {
        try {
            $stmt = $db->prepare('
                INSERT INTO Payment (BuyerId, SellerId, ItemId, PaymentDate)
                VALUES (?, ?, ?, ?)
            ');

            $stmt->execute(array(
                $payment->buyerId,
                $payment->sellerId,
                $payment->itemId,
                $payment->paymentDate
            ));
        } catch (PDOException $e) {
            throw new Exception("Error saving payment: " . $e->getMessage());
        }
    }
}
?>
