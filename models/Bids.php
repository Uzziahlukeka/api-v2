<?php 

class Bids{

    private $con;
    public $bid_id;
    public $item_id;
    public $item_price;
    public $bid_amount;
    public $user_id;
    public $bid_date;
    private $table='bids';
    public function __construct($db){
        $this->con=$db;
    }

    public function placeBid() {
      

        if (!$this->item_price) {
            echo 'Error: Item not found';
            return false;
        }

        // Compare bid amount with item price
        if ($this->bid_amount < $this->item_price) {
            echo 'Low bid';
            return false;
        }

        // Create query to insert bid
        $query = 'INSERT INTO ' . $this->table . '
            SET
                item_id = :item_id,
                bid_amount = :bid_amount,
                user_id = :user_id,
                bid_date = :bid_date';

        // Prepare statement
        $stmt = $this->con->prepare($query);

        // Clean data
        $this->bid_amount = htmlspecialchars(strip_tags($this->bid_amount));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->bid_date = htmlspecialchars(strip_tags($this->bid_date));

        // Bind data
        $stmt->bindParam(':item_id', $this->item_id);
        $stmt->bindParam(':bid_amount', $this->bid_amount);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':bid_date', $this->bid_date);

        // Execute query
        if ($stmt->execute()) {
            return true;
        } else {
            echo 'Error: ' . $stmt->error;
            return false;
        }
    }


    

}