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
    function tryAdding(){
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

        if(empty($_POST['wid'])){
            $response="Please enter wing id!";
            return $response;
        }
        $wid = intval($_POST['wid']);
        
        $chexis = "SELECT * from rooms where roomNo='$rno' and hostelId='$hid';";
        $reschexis = mysqli_query($_SESSION['dbConnection'], $chexis);
        if(mysqli_num_rows($reschexis)>0){
            $response="A room with this room number already exists!";
            return $response;
        }

        $wcap = "SELECT wingId, count(roomNo) as nofr from rooms where hostelId='$hid' and wingId='$wid' group by wingId;";
        $reswcap = mysqli_query($_SESSION['dbConnection'], $wcap);
        $thecnt = mysqli_fetch_assoc($reswcap);
        if($thecnt && $thecnt['nofr']>=6){
            $response = "This wing already has 6 rooms!";
            return $response;
        }

        $iins = "CREATE PROCEDURE if not exists `InsertRoom`(in roomNo int, in hostelId int, in wingId int)
                    MODIFIES SQL DATA
                    BEGIN
                    INSERT into rooms (roomNo, hostelId, wingId) values (roomNo, hostelId, wingId);
                    END;";
        $iinsres = mysqli_query($_SESSION['dbConnection'], $iins);

        $insroom = "CALL InsertRoom('$rno', '$hid', '$wid');";
        $resins = mysqli_query($_SESSION['dbConnection'], $insroom);
        if($resins){
            $response="success";
            return $response;
        }
        $response="Room could not be added";
        return $response;
    }

    if(isset($_POST['getCount'])){
        echo json_encode(giveCount());
    }
    if(isset($_POST['submitted'])){
        echo tryAdding();
    }
?>