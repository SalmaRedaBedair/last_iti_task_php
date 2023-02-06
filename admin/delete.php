<?php
include_once 'connection.php';
session_start();
if (!isset($_SESSION['id']))
    header("location: login.php");
    
if(isset($_GET['code'])){
    $code=$_GET['code'];
}else{
    echo "<h1 align='center'>wrong page !!!!</h1>";
    exit();
}
$result2 = $connection->query("select * FROM `students` where code = $code");
$value = $result2->fetch(PDO::FETCH_ASSOC);
$image_name = $value['image'];
$path2 = "uploads/images/$image_name";
unlink($path2);
$result = $connection->query("DELETE FROM `students` where code = $code");
header('location: students.php');
?>