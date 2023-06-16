<?php

    session_start();
    $_SESSION['init'] = true;
    $server_name = "localhost";
    $database_userName = "root";
    $database_userPassword = "";
    $dbname = "hostelallocation";
    $_SESSION['dbConnection'] = mysqli_connect($server_name, $database_userName, $database_userPassword, $dbname);
?>