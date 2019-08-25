<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if (isset($_SESSION['userActive'])) {
    http_response_code(200);
    exit(json_encode(array("message" => "Jestes zalogowany", "userType" => $_SESSION['userType'])));
} else {
    http_response_code(400);
    // $message_arr=array();
    // $message_arr["message"] = "Nie jestes zalogowany";
    // echo json_encode($message_arr));
    exit(json_encode(array("message" => "Nie jestes zalogowany")));
}