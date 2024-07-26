<?php

namespace uzziah;
//headers for allowing access to the HTTP

header('Access-control-Allow-Origin:*');
header('content-Type:application/json');

require_once 'vendor/autoload.php';

use uzziah\Database;
use uzziah\Registration;

//instantiate DB and connect

$database = new Database();
$db = $database->connect();

//instantiate blog post object
$post = new Registration($db);
$email = $_POST["email"];
$token = bin2hex(random_bytes(16));
$token_hash = hash("sha256", $token);
$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

$post->email = $email;
$post->token = $token_hash;
$post->reset_time = $expiry;

$post->resetpassword();
$to = $email;
$subject = "Password Reset";
$message ="Click <a href=\"http://example.com/reset-password.php?token=$token\">here</a> to reset your password.";
$headers = "From: no-reply@example.com";
// Send the email
$uzhh = mail($to, $subject, $message, $hearders);
// Display a success message to the user
