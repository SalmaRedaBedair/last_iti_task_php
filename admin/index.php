<?php
// ob_start();
session_start();
include_once 'up.php';
include_once 'connection.php';

if (!isset($_SESSION['id']))
    header("location: login.php");
?>
<?php
ob_end_clean();
include_once 'dowm.php';


?>

