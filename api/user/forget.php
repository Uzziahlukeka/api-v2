<?php

namespace uzziah;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once 'vendor/autoload.php';

use uzziah\Database;
use uzziah\Registration;

$database = new Database();
$db = $database->connect();
$post = new Registration($db);

$email = $_GET["email"];
$token = bin2hex(random_bytes(16));
$token_hash = hash("sha256", $token);
$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

$post->email = $email;
$post->token = $token_hash;
$post->reset_time = $expiry;

if ($post->resetPassword()) {
    $post_arr=array(
        'email'=>$email,
        'token'=>$token_hash,
        'reset_time'=>$expiry 
    );
    
    //make json 
    print_r (json_encode($post_arr));
    } else {
        echo json_encode(['message' => 'unable to set a token']);
    }
