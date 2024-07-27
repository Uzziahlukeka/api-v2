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

$token_hash = $_GET["token"];

if ($post->findByResetToken($token_hash)) {

    $post_arr=array(
        'id'=>$post->id,
        'token'=>$post->token,
        'reset_time'=>$post->reset_time
    );
    //make json 
    print_r (json_encode($post_arr));
    } else {
        echo json_encode(['message' => 'unable to reset the token']);
    }
