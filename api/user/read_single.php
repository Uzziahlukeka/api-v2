<?php
namespace uzziah;
//headers for allowing access to the HTTP

header('Access-control-Allow-Origin:*');
header('content-Type:application/json');

require_once 'vendor/autoload.php';
use uzziah\Database;
use uzziah\Registration;

//instantiate DB and connect

$database=new Database();
$db=$database->connect();

//instantiate blog post object
$post=new Registration($db);

//Get ID
$post->name=isset($_GET['name']) ? $_GET['name'] : die();
//isset then = ? , : else 

//get post
$post->read_single();

//create array 
$post_arr=array(
    'id'=>$post->id,
    'name'=>$post->name,
    'email'=>$post->email,
    'create_at'=>$post->createAt,
    'password'=>$post->passw   
);

//make json 
print_r (json_encode($post_arr));
