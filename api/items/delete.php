<?php
namespace uzziah;
//headers for allowing access to the HTTP

header('Access-control-Allow-Origin:*');
header('content-Type:application/json');
header('Access-Control-Allow-Methods:DELETE');//methode for the API 
header('Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-with'); 


require_once 'vendor/autoload.php';
use uzziah\Database;
use uzziah\Items;

//instantiate DB and connect

$database=new Database();
$db=$database->connect();

//instantiate blog post object
$post=new Items($db);

//get raw posted data
$data=json_decode(file_get_contents('php://input'));

//set ID to update
$post->item_name=$data->item_name;

//DELETE post

if($post->delete()){
    echo json_encode(
        array('message'=>'item deleted')
    );
} else{
    echo json_encode(
        array('message'=>'item not deleted')
    );
}