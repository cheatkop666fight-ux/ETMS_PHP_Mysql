<?php
if (session_start() === PHP_SESSION_NONE){
    session_start();
}

if (!isset($_SESSION["user_id"])){
    header("Location: ../auth/login.php");
    exit;
}

?>