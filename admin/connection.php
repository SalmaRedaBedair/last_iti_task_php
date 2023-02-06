<?php
try {
    $connection=new PDO('mysql:host=localhost;dbname=task','root','');
}catch(Exception $e){
    echo $e->getMessage();
    exit;

}
?>