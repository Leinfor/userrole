<?php
session_start();
$_SESSION = array(); // Clear array data properties
session_destroy();   // Terminate active engine
header("location: login.php");
exit();
?>
