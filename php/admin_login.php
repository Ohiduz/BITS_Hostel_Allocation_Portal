<?php
    include './conn.php';
    function validate($em, $pwd){
        if(empty($em)){
            $response="Please enter email!";
        }
        else if(empty($pwd)){
            $response="Please enter password!";
        }
        else if($em != "chiefwarden@pilani.bits-pilani.ac.in"){
            $response="Incorrect email!";
        }
        else if($pwd != "admin123"){
            $response="Incorrect password!";
        }
        else{
            $response="success";
        }
        echo $response;
    }

    $email = $_POST['email'];
    $password = $_POST['password'];
    validate($email, $password);

    
?>