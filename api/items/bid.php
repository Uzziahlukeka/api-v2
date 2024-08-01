<?php 

namespace uzziah;

// Headers for allowing access to the HTTP
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST'); // Allow only POST method
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With'); 

// Include database and item model
require_once 'vendor/autoload.php';

use uzziah\Database;
use uzziah\Bids;

// Instantiate DB and connect
$database = new Database();
$db = $database->connect();

// Get raw posted data
$data = json_decode(file_get_contents('php://input'));

// Check if all required fields are provided
if (
    isset($data->item_id) &&
    isset($data->user_id) &&
    isset($data->bid_amount) 
) {
    // Instantiate item object with database connection
    $item = new Bids($db);
    
    // Assign data to the item object
    $item->item_id = $data->item_id;
    $item->user_id = $data->user_id;
    $item->bid_amount = $data->bid_amount;

    // bid 
    if ($item->placeBid()) {
        echo json_encode(array('message' => 'bid created'));
    } else {
        echo json_encode(array('message' => 'Item not created'));
    }
} else {
    // Required fields are missing
    echo json_encode(array('message' => 'Incomplete data'));
}

