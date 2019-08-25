<?php
// INCLUDUJ POTRZEBNE PLIKI
include_once '../config/database.php';
include_once '../model/user.php';
// OBIEKT POŁĄCZENIA
$database = new Database();
$db = $database->getConnection();
// POBIERZ DANE, UTWORZU OBIEKT USER, PRZYPISZ MU WARTOSCI
$user = new User($db);
$user->setIdUser($_GET['code']);

$user->activate();
$user->read();

//jesli aktywacja sie powiedzie to daj znac ze jest ok, jesli nie daj znac ze sie nie powiodlo
$emailBody = "Twoje konto zostalo aktywowane";
$emailHeaders  = "MIME-Version: 1.0\r\n";
$emailHeaders .= "Content-type: text/html; charset=iso-8859-1\r\n";
$emailHeaders .= "From: KapLAN OS \n";
$emailTopic = "Aktywacja twojego konta w KapLAN OS";

mail($user->getUserEmail(), $emailTopic, $emailBody, $emailHeaders);