<?php
    include './conn.php';

    function giveCount(){
        $stdhst = array ("std"=>0, "hst"=>0);

        $fq = "SELECT count(*) as numstd from students;";
        $res1 = mysqli_query($_SESSION['dbConnection'], $fq);
        $rec1 = mysqli_fetch_assoc($res1);
        $stdhst['std'] = $rec1['numstd'];

        $sq = "SELECT count(*) as numhst from hostels;";
        $res2 = mysqli_query($_SESSION['dbConnection'], $sq);
        $rec2 = mysqli_fetch_assoc($res2);
        $stdhst['hst'] = $rec2['numhst'];

        return $stdhst;
    }
    function tryUpdating(){
        $response="";
        $rno = $_POST['rno'];
        if(empty($_POST['rno'])){
            $response="Please enter room number!";
            return $response;
        }
        if(empty($_POST['hid'])){
            $response="Please select a valid hostel!";
            return $response;
        }
        $hid = intval($_POST['hid']);

        if(empty($_POST['stat'])){
            $response="Please select status of room!";
            return $response;
        }
        $stat = intval($_POST['stat']) - 1;
        
        $chexis = "SELECT * from rooms where roomNo='$rno' and hostelId='$hid';";
        $reschexis = mysqli_query($_SESSION['dbConnection'], $chexis);
        if(mysqli_num_rows($reschexis)<1){
            $response="This room doesn't exist!";
            return $response;
        }

        $iupd = "CREATE PROCEDURE if not exists `UpdateRoom`(in room_No int, in hostel_Id int, in stat int)
                    MODIFIES SQL DATA
                    BEGIN
                    UPDATE rooms set currentOccupants=stat where roomNo=room_No and hostelId=hostel_Id;
                    END;";
        $iupdred = mysqli_query($_SESSION['dbConnection'], $iupd);

        $updroom = "CALL UpdateRoom('$rno', '$hid', '$stat');";
        $resupd = mysqli_query($_SESSION['dbConnection'], $updroom);
        if($resupd){
            $response="success";
            return $response;
        }
        $response="Room status could not be updated!";
        return $response;
    }

    if(isset($_POST['getCount'])){
        echo json_encode(giveCount());
    }
    if(isset($_POST['submitted'])){
        echo tryUpdating();
    }
?>