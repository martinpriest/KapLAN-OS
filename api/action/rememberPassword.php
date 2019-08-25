<?php
function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../model/user.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));
$user = new User($db);
$user->setUserLogin($data->userLogin);

if ($user->loginExists()) {
    $user->read();

    $password = randomPassword();
    $user->setUserPassword($password);
    $user->changePassword();

    $emailBody = "Twoje nowe haslo to: {$password}";
    $emailHeaders  = "MIME-Version: 1.0\r\n";
    $emailHeaders .= "Content-type: text/html; charset=iso-8859-1\r\n";
    $emailHeaders .= "From: KapLAN OS \n";
    $emailTopic = "Wygenerowano nowe haslo";

    mail($user->getUserEmail(), $emailTopic, $emailBody, $emailHeaders);
    http_response_code(200);
    exit(json_encode(array("message" => "Wygenerowano na maila nowe haslo")));
} else {
    http_response_code(400);
    exit(json_encode(array("message" => "Uzytkownik nie istnieje")));
}