<?php
    session_start();
    if(!isset($_SESSION['init'])){
        include_once "./initialize.php";
        $_SESSION['init'] = true;
    }
    
?>