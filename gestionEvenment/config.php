<?php
$host = "localhost";
$dbname = "gestion_réservations";
$username = "root";
$password = "";

try {
$pdo = new PDO("mysql:host=$host;dbname=$dbname",$username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch (pdoException $th){
 echo "erreur de connection :" . $th->getmessage();
}