<?php 
namespace uzziah;

class Bids {
    private $con;
    
    public $item_id;
    public $user_id;
    public $item_price;
    public $bid_amount;
    public $bid_date;
    private $table = 'bids';

    public function __construct($db) {
        $this->con = $db;
    }

    // Method to fetch item price from Items table
    private function fetchItemPrice() {
        $query = 'SELECT item_price FROM items WHERE item_id = :item_id LIMIT 0,1';
        
        // Prepare statement
        $stmt = $this->con->prepare($query);
        
        // Bind item_id
        $stmt->bindParam(':item_id', $this->item_id);
        
        // Execute query
        $stmt->execute();
        
        // Fetch the item price
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($row) {
            $this->item_price = $row['item_price'];
            return true;
        } else {
            echo 'Error: Item not found';
            return false;
        }
    }

    // Method to check if a bid already exists
    private function bidExists() {
        $query = 'SELECT COUNT(*) FROM ' . $this->table . '
            WHERE item_id = :item_id
            AND user_id = :user_id';

        // Prepare statement
        $stmt = $this->con->prepare($query);

        // Bind parameters
        $stmt->bindParam(':item_id', $this->item_id);
        $stmt->bindParam(':user_id', $this->user_id);

        // Execute query
        $stmt->execute();

        // Check if any rows exist
         if($stmt->fetchColumn() > 0){
            return true;
        }
    }

    public function placeBid() {
        // Fetch the item price from the Items table
        if (!$this->fetchItemPrice()) {
            return false;
        }

        if ($this->bidExists()) {
            $this->updateBidPrice();
            exit();
        }

        // Create query to insert bid
        $query = 'INSERT INTO ' . $this->table . '
            SET
                item_id = :item_id,
                bid_amount = :bid_amount,
                user_id = :user_id,
                item_price = :item_price';

        // Prepare statement
        $stmt = $this->con->prepare($query);

        // Clean data
        $this->bid_amount = htmlspecialchars(strip_tags($this->bid_amount));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->bid_date = htmlspecialchars(strip_tags($this->bid_date));
        $this->item_price = htmlspecialchars(strip_tags($this->item_price));

        // Bind data
        $stmt->bindParam(':item_id', $this->item_id);
        $stmt->bindParam(':bid_amount', $this->bid_amount);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':item_price', $this->item_price);

        // Execute query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function read_data() {
        // Create query
        $query = 'SELECT 
        *
        FROM
        ' . $this->table . '
        WHERE
        item_id = :item_id
        AND user_id = :user_id
        LIMIT 0,1';

        // Prepare statement
        $stmt = $this->con->prepare($query);

        // Bind ID
        $stmt->bindParam(':item_id', $this->item_id);
        $stmt->bindParam(':user_id', $this->user_id);

        // Execute query
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            // Set properties
            $this->item_id = $row['item_id'];
            $this->user_id = $row['user_id'];
            $this->item_price = $row['item_price'];
            $this->bid_amount = $row['bid_amount'];
        } else {
            echo 'Error: No matching bid found';
        }
    }

    private function updateBidPrice() {
        // Fetch the item price from the Items table
        if (!$this->fetchItemPrice()) {
            return false;
        }

        // Create query to update the item price
        $query = 'UPDATE ' . $this->table . '
            SET
                bid_amount = :bid_amount
            WHERE
                item_id = :item_id
                AND user_id = :user_id';

        // Prepare statement
        $stmt = $this->con->prepare($query);

        // Clean data
        $this->bid_amount = htmlspecialchars(strip_tags($this->bid_amount));

        // Bind data
        $stmt->bindParam(':item_id', $this->item_id);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':bid_amount', $this->bid_amount);

        // Execute query
        if ($stmt->execute()) {
            return true;
        } else {
            return ['message'=>'echec'];
        }
    }
}
