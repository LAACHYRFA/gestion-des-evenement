<?php
require "config.php";
session_start();

if(isset($_GET['id'])){
 $id = $_GET['id'];


 $stmt= $pdo->prepare("SELECT title,location FROM  events where id = :id ");
 $stmt->execute([
  ':id' => $id
 ]);
 $event = $stmt->fetch(PDO::FETCH_ASSOC);

 if($event){


$sql = "UPDATE events SET nbPlaces = nbPlaces -1 where id = :id and nbPlaces > 0";
$stmt = $pdo->prepare($sql);
$stmt->execute([
 ':id' => $id
]);
echo "vous avez réserver :" .  ($event['title'] .  " " . "a" .  " " . $event['location'] ) ;
}
}
