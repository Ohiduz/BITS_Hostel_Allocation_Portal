<?php
    
    include './conn.php';
    function validate($id, $name, $email, $password, $batch, $gender){
        if(empty($id) || empty($name) || empty($email) || empty($password) || empty($batch) || empty($gender)){
            $response="Please fill in all details!";
        }
        else{
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $response="Please enter a valid email!";
            }
            else{
                $batch = intval($batch);
                $idch = "SELECT * from students where id = '$id';";
                $res = mysqli_query($_SESSION['dbConnection'], $idch);
                if(mysqli_num_rows($res)>0){
                    $response="You already have an account!";
                }
                else{
                    $emch = "SELECT * from students where email = '$email';";
                    $res = mysqli_query($_SESSION['dbConnection'], $emch);
                    if(mysqli_num_rows($res)>0){
                        $response="This email is already registered!";
                    }
                    else{
                        $iqry = "INSERT into students values ('$id','$name','$email','$password','$batch','$gender');";
                        if(mysqli_query($_SESSION['dbConnection'], $iqry)){
                            $response="success";
                        }
                        else{
                            $response="Failed to register!";
                        }  
                    }
                }  
            }
        }
        echo $response;
    }

    $id = $_POST['id'];
    $name = $_POST['name'];
    $batch = $_POST['batch'];
    if(isset($_POST['gender'])) $gender = $_POST['gender']; 
    else $gender="";
    $email = $_POST['email'];
    $password = $_POST['password'];
    validate($id, $name, $email, $password, $batch, $gender);

    
?>