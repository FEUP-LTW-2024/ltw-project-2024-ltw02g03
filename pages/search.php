<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/item.class.php');

// Get the search query from the URL parameter
$searchQuery = $_GET['query'] ?? '';

if (isset($_GET['search'])) {
    // Sanitize the search term
    $search = trim($_GET['search']);

    // Validate the search term
    if (!empty($search)) {
        // Import the Item class if not already imported
        require_once 'path_to_item_class.php';

        try {
            // Search for items by title
            $items = Item::searchItemsByTitle($db, $search, 10); // Change 10 to your desired limit
            //var_dump($items);  debug
            // Display search results
            if (!empty($items)) {
                foreach ($items as $item) {
                    // Display item details
                    echo "Item ID: " . $item->itemId . "<br>";
                    echo "Title: " . $item->title . "<br>";
                    echo "Description: " . $item->description . "<br>";
                    echo "Price: " . $item->price . "<br>";
                    echo "Listing Date: " . $item->listingDate . "<br><br>";
                }
            } else {
                echo "No items found matching the search term.";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Please enter a search term.";
    }
}
?>
