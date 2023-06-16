<?php
    include './conn.php';
    function giveNameAndId(){
        $naid = array("studentName" => $_SESSION['studentName'], "studentId" => $_SESSION['studentId']);
        return $naid;
    }
    function giveHostels(){
        $theid = $_SESSION['studentId'];
        $thesquery = "SELECT * from students where id = '$theid';";
        $theres = mysqli_query($_SESSION['dbConnection'], $thesquery);
        $thes = mysqli_fetch_assoc($theres);
        $tg = $thes['gender'];
        $tbtch = $thes['batch'];
        $getHquery = "SELECT hostelName, gender from hostels h  NATURAL JOIN (SELECT hostelId, count(roomNo) from rooms where currentOccupants=0
                        GROUP BY hostelId HAVING COUNT(roomNo)>=6) r NATURAL JOIN hostelbatch hb where gender='$tg' and hb.batch='$tbtch';";
        $Hresults = mysqli_query($_SESSION['dbConnection'], $getHquery);

        $HostelsList = mysqli_fetch_all($Hresults, MYSQLI_ASSOC);
        return $HostelsList;
    }
    function addPrefer(){
        $responses = array("invalidHostel" => false, "invalidIds" => false, 
                        "preferenceAdded"=>false, "allocated" => false, "allocatedHostel" => "", "message" => "",
                        "studroom"=>0, "wingie1"=>0, "wingie2"=>0, "wingie3"=>0, "wingie4"=>0, "wingie5"=>0,
                        "wingie6"=>0, "wingie7"=>0, "wingie8"=>0, "wingie9"=>0, "wingie10"=>0, "wingie11"=>0
                    );
        $prefHostelName = $_POST['hostelName'];
        $self = $_POST['self'];
        $wingie1 = $_POST['wingie1'];
        $wingie2 = $_POST['wingie2'];
        $wingie3 = $_POST['wingie3'];
        $wingie4 = $_POST['wingie4'];
        $wingie5 = $_POST['wingie5'];
        $wingie6 = $_POST['wingie6'];
        $wingie7 = $_POST['wingie7'];
        $wingie8 = $_POST['wingie8'];
        $wingie9 = $_POST['wingie9'];
        $wingie10 = $_POST['wingie10'];
        $wingie11 = $_POST['wingie11'];

        if(empty($prefHostelName)){
            $responses['invalidHostel'] = true;
            $responses['message'] = "Please select a valid hostel!";
            return $responses;
        }
        if($self != $_SESSION['studentId']){
            $responses['invalidIds'] = true;
            $responses['message'] = "Your ID is incorrect!";
            return $responses;
        }
        if(empty($wingie1) || empty($wingie2) || empty($wingie3) || empty($wingie4) || empty($wingie5) || empty($wingie6)
            || empty($wingie7) || empty($wingie8) || empty($wingie9) || empty($wingie10) || empty($wingie11)){
            $responses['invalidIds'] = true;
            $responses['message'] = "Please fill in all fields!";
            return $responses;
        }

        $qr1 = "SELECT * from students where id = '$wingie1';";
        $res1 = mysqli_query($_SESSION['dbConnection'], $qr1);
        $qr2 = "SELECT * from students where id = '$wingie2';";
        $res2 = mysqli_query($_SESSION['dbConnection'], $qr2);
        $qr3 = "SELECT * from students where id = '$wingie3';";
        $res3 = mysqli_query($_SESSION['dbConnection'], $qr3);
        $qr4 = "SELECT * from students where id = '$wingie4';";
        $res4 = mysqli_query($_SESSION['dbConnection'], $qr4);
        $qr5 = "SELECT * from students where id = '$wingie5';";
        $res5 = mysqli_query($_SESSION['dbConnection'], $qr5);
        $qr6 = "SELECT * from students where id = '$wingie6';";
        $res6 = mysqli_query($_SESSION['dbConnection'], $qr6);
        $qr7 = "SELECT * from students where id = '$wingie7';";
        $res7 = mysqli_query($_SESSION['dbConnection'], $qr7);
        $qr8 = "SELECT * from students where id = '$wingie8';";
        $res8 = mysqli_query($_SESSION['dbConnection'], $qr8);
        $qr9 = "SELECT * from students where id = '$wingie9';";
        $res9 = mysqli_query($_SESSION['dbConnection'], $qr9);
        $qr10 = "SELECT * from students where id = '$wingie10';";
        $res10 = mysqli_query($_SESSION['dbConnection'], $qr10);
        $qr11 = "SELECT * from students where id = '$wingie11';";
        $res11 = mysqli_query($_SESSION['dbConnection'], $qr11);

        if(mysqli_num_rows($res1)<1 || mysqli_num_rows($res2)<1 || mysqli_num_rows($res3)<1 || mysqli_num_rows($res4)<1 || 
            mysqli_num_rows($res5)<1 || mysqli_num_rows($res6)<1 || mysqli_num_rows($res7)<1 || mysqli_num_rows($res8)<1 || 
            mysqli_num_rows($res9)<1 || mysqli_num_rows($res10)<1 || mysqli_num_rows($res11)<1
        ){
            $responses['invalidIds'] = true;
            $responses['message'] = "Please enter valid Ids of all members!";
            return $responses;
        }

        $hidqr = "SELECT * from hostels where hostelName='$prefHostelName';";
        $hidres = mysqli_query($_SESSION['dbConnection'], $hidqr);
        $thehid = mysqli_fetch_assoc($hidres);
        $hostelId = $thehid['hostelId'];

        //adding to preference table the hostel preference
        $insprefqr = "INSERT into preference (studentId, hostelId) values ('$self', '$hostelId');";

        //adding preferred wingies to wingies table
        $inswqr1 = "INSERT into wingies values ('$self', '$wingie1');";
        $inswqr2 = "INSERT into wingies values ('$self', '$wingie2');";
        $inswqr3 = "INSERT into wingies values ('$self', '$wingie3');";
        $inswqr4 = "INSERT into wingies values ('$self', '$wingie4');";
        $inswqr5 = "INSERT into wingies values ('$self', '$wingie5');";
        $inswqr6 = "INSERT into wingies values ('$self', '$wingie6');";
        $inswqr7 = "INSERT into wingies values ('$self', '$wingie7');";
        $inswqr8 = "INSERT into wingies values ('$self', '$wingie8');";
        $inswqr9 = "INSERT into wingies values ('$self', '$wingie9');";
        $inswqr10 = "INSERT into wingies values ('$self', '$wingie10');";
        $inswqr11 = "INSERT into wingies values ('$self', '$wingie11');";

        if(mysqli_query($_SESSION['dbConnection'], $insprefqr) && mysqli_query($_SESSION['dbConnection'], $inswqr1)
            && mysqli_query($_SESSION['dbConnection'], $inswqr2) && mysqli_query($_SESSION['dbConnection'], $inswqr3)
            && mysqli_query($_SESSION['dbConnection'], $inswqr4) && mysqli_query($_SESSION['dbConnection'], $inswqr5)
            && mysqli_query($_SESSION['dbConnection'], $inswqr6) && mysqli_query($_SESSION['dbConnection'], $inswqr7)
            && mysqli_query($_SESSION['dbConnection'], $inswqr8) && mysqli_query($_SESSION['dbConnection'], $inswqr9)
            && mysqli_query($_SESSION['dbConnection'], $inswqr10) && mysqli_query($_SESSION['dbConnection'], $inswqr11)
        ){
            $responses['preferenceAdded'] = true;

            $prefqry = "SELECT * from rooms where currentOccupants=0 and hostelId='$hostelId' and wingId=(
                SELECT wingId from rooms where currentOccupants=0 and hostelId='$hostelId' group by wingId having count(*)>=6 LIMIT 1
            ) LIMIT 6;";
            $vrres = mysqli_query($_SESSION['dbConnection'], $prefqry);

            //if preferred hostel is not available allocate from a random hostel
            if(mysqli_num_rows($vrres)<6){
                $gvrqr = "SELECT * FROM rooms WHERE currentOccupants = 0 AND wingId IN (
                    SELECT wingId FROM rooms WHERE currentOccupants = 0 GROUP BY wingId HAVING COUNT(*) >= 6
                  ) AND hostelId IN (
                    SELECT hostelId FROM rooms WHERE currentOccupants = 0 GROUP BY hostelId HAVING COUNT(*) >= 6
                  ) ORDER BY wingId, hostelId LIMIT 6;";
    
                $vrres = mysqli_query($_SESSION['dbConnection'], $gvrqr);

            }

            $aroom = mysqli_fetch_assoc($vrres);
            $rno = $aroom['roomNo'];
            $hid = $aroom['hostelId'];
            $ins1 = "INSERT into allocated values('$self', '$rno', '$hid');";
            $ins2 = "INSERT into allocated values('$wingie1', '$rno', '$hid');";
            $incqr1 = "UPDATE rooms set currentOccupants=2 where roomNo='$rno' and hostelId='$hid';";
            $responses['studroom'] = $rno;
            $responses['wingie1'] = $rno;

            $aroom = mysqli_fetch_assoc($vrres);
            $rno = $aroom['roomNo'];
            $hid = $aroom['hostelId'];
            $ins3 = "INSERT into allocated values('$wingie2', '$rno', '$hid');";
            $ins4 = "INSERT into allocated values('$wingie3', '$rno', '$hid');";
            $incqr2 = "UPDATE rooms set currentOccupants=2 where roomNo='$rno' and hostelId='$hid';";
            $responses['wingie2'] = $rno;
            $responses['wingie3'] = $rno;

            $aroom = mysqli_fetch_assoc($vrres);
            $rno = $aroom['roomNo'];
            $hid = $aroom['hostelId'];
            $ins5 = "INSERT into allocated values('$wingie4', '$rno', '$hid');";
            $ins6 = "INSERT into allocated values('$wingie5', '$rno', '$hid');";
            $incqr3 = "UPDATE rooms set currentOccupants=2 where roomNo='$rno' and hostelId='$hid';";
            $responses['wingie4'] = $rno;
            $responses['wingie5'] = $rno;

            $aroom = mysqli_fetch_assoc($vrres);
            $rno = $aroom['roomNo'];
            $hid = $aroom['hostelId'];
            $ins7 = "INSERT into allocated values('$wingie6', '$rno', '$hid');";
            $ins8 = "INSERT into allocated values('$wingie7', '$rno', '$hid');";
            $incqr4 = "UPDATE rooms set currentOccupants=2 where roomNo='$rno' and hostelId='$hid';";
            $responses['wingie6'] = $rno;
            $responses['wingie7'] = $rno;

            $aroom = mysqli_fetch_assoc($vrres);
            $rno = $aroom['roomNo'];
            $hid = $aroom['hostelId'];
            $ins9 = "INSERT into allocated values('$wingie8', '$rno', '$hid');";
            $ins10 = "INSERT into allocated values('$wingie9', '$rno', '$hid');";
            $incqr5 = "UPDATE rooms set currentOccupants=2 where roomNo='$rno' and hostelId='$hid';";
            $responses['wingie8'] = $rno;
            $responses['wingie9'] = $rno;

            $aroom = mysqli_fetch_assoc($vrres);
            $rno = $aroom['roomNo'];
            $hid = $aroom['hostelId'];
            $ins11 = "INSERT into allocated values('$wingie10', '$rno', '$hid');";
            $ins12 = "INSERT into allocated values('$wingie11', '$rno', '$hid');";
            $incqr6 = "UPDATE rooms set currentOccupants=2 where roomNo='$rno' and hostelId='$hid';";
            $responses['wingie10'] = $rno;
            $responses['wingie11'] = $rno;

            $ghn = "SELECT * from hostels where hostelId='$hid';";
            $resfhn = mysqli_query($_SESSION['dbConnection'], $ghn);
            $thehosn = mysqli_fetch_assoc($resfhn);
            $responses['allocatedHostel'] = $thehosn['hostelName'];

            if(mysqli_query($_SESSION['dbConnection'], $ins1) && mysqli_query($_SESSION['dbConnection'], $ins2)
                && mysqli_query($_SESSION['dbConnection'], $incqr1) && mysqli_query($_SESSION['dbConnection'], $ins3)
                && mysqli_query($_SESSION['dbConnection'], $ins4) && mysqli_query($_SESSION['dbConnection'], $incqr2)
                && mysqli_query($_SESSION['dbConnection'], $ins5) && mysqli_query($_SESSION['dbConnection'], $ins6)
                && mysqli_query($_SESSION['dbConnection'], $incqr3) && mysqli_query($_SESSION['dbConnection'], $ins7)
                && mysqli_query($_SESSION['dbConnection'], $ins8) && mysqli_query($_SESSION['dbConnection'], $incqr4)
                && mysqli_query($_SESSION['dbConnection'], $ins9) && mysqli_query($_SESSION['dbConnection'], $ins10)
                && mysqli_query($_SESSION['dbConnection'], $incqr5) && mysqli_query($_SESSION['dbConnection'], $ins11)
                && mysqli_query($_SESSION['dbConnection'], $ins12) && mysqli_query($_SESSION['dbConnection'], $incqr6)
            ){
                $responses['allocated'] = true;
                return $responses;
            }
            else{
                $responses['allocated'] = false;
                return $responses;
            }
        }
        else{
            return mysqli_error($_SESSION['dbConnection']);
        }
    }
    if(isset($_POST['nameNid'])){
        echo json_encode(giveNameAndId());
    }
    if(isset($_POST['hostels'])){
        echo json_encode(giveHostels());
    }
    if(isset($_POST['submitted']) && $_POST['submitted']){
        $_POST['submitted'] = false;
        echo json_encode(addPrefer());
    }
?>