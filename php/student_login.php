<?php
    
    include './conn.php';
    function validate($em, $pwd){
        if(empty($em)){
            $response="Please enter email!";
        }
        else if(empty($pwd)){
            $response="Please enter password!";
        }
        else{
            $qry="SELECT * from students where email='$em';";
            $res=mysqli_query($_SESSION['dbConnection'], $qry);
            
            if(mysqli_num_rows($res)<1){
                $response="User with this email doesn't exist!";
            }
            else{
                $student=mysqli_fetch_assoc($res);
                if($pwd != $student['password'])
                    $response="Incorrect password!";
                else{
                    $response="success";
                    $_SESSION['studentName'] = $student['name'];
                    $_SESSION['studentId'] = $student['id'];
                    
                }
            }
        }
        echo $response;
    }

    $email = $_POST['email'];
    $password = $_POST['password'];
    validate($email, $password);

    
?>