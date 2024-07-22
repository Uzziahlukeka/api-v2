<?php

namespace uzziah;
//headers for allowing access to the HTTP

header('Access-control-Allow-Origin:*');
header('content-Type:application/json');

require_once 'vendor/autoload.php';
use uzziah\Database;
use uzziah\Items;

//instantiate DB and connect

$database=new Database();
$db=$database->connect();

//instantiate blog post object
$post=new Items($db);

//Get ID
$post->item_name=isset($_GET['item_name']) ? $_GET['item_name'] : die(json_encode(['message' => 'Item name not provided']));

//get post
$post->read_single();

//create array 
$post_arr=array(
    'item_name'=>$post->item_name,
    'item_photo'=>$post->item_photo ,
    'item_description'=>$post->item_description,
    'item_price'=>$post->item_price,
    'user_id'=>$post->user_id
);

//make json 
print_r (json_encode($post_arr));
