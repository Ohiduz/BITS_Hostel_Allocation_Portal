<?php
    include './conn.php';
    
    function giveStudentData(){
        $sname = $_SESSION['studentName'];
        $sid = $_SESSION['studentId'];
        $sdata = array ('id' => $sid, 'name' => $sname,
             'setPreference' => false, 'preferredHostel' => '', 'allocated' => false, 'allocatedHostel' => '');
        
        $sq = "SELECT * from wingies where studentId='$sid' or wingieId='$sid';";
        $res = mysqli_query($_SESSION['dbConnection'], $sq);
        if(mysqli_num_rows($res)<1){
            return $sdata;
        }
        $arow = mysqli_fetch_assoc($res);
        $theS = $arow['studentId'];
        $sdata['setPreference'] = true;
        $hq = "SELECT * from hostels a inner join preference b on a.hostelId = b.hostelId where b.studentId='$theS';";
        $hqres = mysqli_query($_SESSION['dbConnection'], $hq);
        $hqrow = mysqli_fetch_assoc($hqres);
        $sdata['preferredHostel'] = $hqrow['hostelName'];

        $challoc = "SELECT * from allocated where studentId='$sid';";
        $challres = mysqli_query($_SESSION['dbConnection'], $challoc);
        if(mysqli_num_rows($challres)<1){
            return $sdata;
        }
        $thealoc = mysqli_fetch_assoc($challres);
        $rn = $thealoc['roomNo'];
        $hid = $thealoc['hostelId'];
        $sdata['allocated'] = true;

        $fdquery = "SELECT * from allocated a inner join hostels b on a.hostelId = b.hostelId where a.studentId='$sid';";
        $fdres = mysqli_query($_SESSION['dbConnection'], $fdquery);
        $fdrow = mysqli_fetch_assoc($fdres);
        $sdata['allocatedHostel'] = $fdrow['hostelName'].': Room No - '.$rn;

        return $sdata;
    }

    if(isset($_POST['studentData'])){
        // header('Content-Type: application/json');
        echo json_encode(giveStudentData());
    }
        
?>