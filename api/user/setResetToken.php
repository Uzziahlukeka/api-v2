<?php
namespace uzziah;
//headers for allowing access to the HTTP

header('Access-control-Allow-Origin:*');
header('content-Type:application/json');
header('Access-Control-Allow-Methods:PUT');//methode for the API 
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

//set ID to update
$id=$data->id;
$password=$data->password;
//update post
if($post->updatePassword($id,$password)){
    echo json_encode(
        array('message'=>'password uzh update')
    );

}else{
    echo json_encode(
        array('message'=>'user not update')
    );
}