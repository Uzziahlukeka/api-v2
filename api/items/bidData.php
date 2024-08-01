<?php

namespace uzziah;
//headers for allowing access to the HTTP

header('Access-control-Allow-Origin:*');
header('content-Type:application/json');


require_once 'vendor/autoload.php';
use uzziah\Database;
use uzziah\Bids;

//instantiate DB and connect

$database=new Database();
$db=$database->connect();

//instantiate blog post object
$post=new BIds($db);

//Get ID
$item_id = $_GET['item_id'] ?? null;
$user_id = $_GET['user_id'] ?? null;

// Validate data
if ($item_id === null || $user_id === null) {
    echo json_encode(['message' => 'Item ID or User ID not provided']);
    exit;
}

$post->item_id=$item_id;
$post->user_id=$user_id;
//get post
$post->read_data();

//create array 
$post_arr=array(
    'item_id'=>$post->item_id,
    'user_id'=>$post->user_id,
    'item_price'=>$post->item_price,
    'bid_amount'=>$post->bid_amount
);

//make json 
print_r (json_encode($post_arr));
