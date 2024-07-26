<?php 
namespace uzziah;
//require 'api/Auth/oauth_config.php';
//headers for allowing access to the HTTP

header('Access-control-Allow-Origin:*');
header('content-Type:application/json');
header('Access-Control-Allow-Methods:POST');//methode for the API 
header('Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-with'); 

require_once 'vendor/autoload.php';
use uzziah\Database;
use uzziah\Registration;


//instantiate DB and connect

$database=new Database();
$db=$database->connect();

//instantiate blog post object
$post=new Registration($db);

//get raw posted data
$data=json_decode(file_get_contents('php://input'));

// assign it to the post
$post->id=uniqid('user_',true);
$post->name=$data->name;
$post->email=$data->email;
$post->passw=$data->passw;

//create post
$result=$post->create();

if ($result['success']) {
    echo json_encode([
        'message' => 'User created',
        'name' => $post->name,
        'id' => $post->id
    ]);
} else{
    echo json_encode([
        'message' => $result['message']
    ]);
}