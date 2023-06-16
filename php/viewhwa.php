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

    function validateHostel(){
        $vlres="";
        if(empty($_POST['hid'])){
            $vlres="Please select a valid hostel!";
            return $vlres;
        }
        $vlres="success";
        return $vlres;
    }

    function getTable(){
        $records = array();
        $hid = $_POST['hid'];

        $iqr = "CREATE PROCEDURE if not exists `ViewHostelwiseAllocation`(in hostel_Id int)
                    READS SQL DATA
                    BEGIN
                    SELECT * from allocated a inner join students b on a.studentId=b.id WHERE a.hostelId=hostel_Id ORDER by roomNo;
                    END;";
        $ires = mysqli_query($_SESSION['dbConnection'], $iqr);

        $qr = "CALL ViewHostelwiseAllocation('$hid');";
        $res = mysqli_query($_SESSION['dbConnection'], $qr);

        while($row = mysqli_fetch_assoc($res)){
            $records[] = $row;
        }
        return $records;
    }

    if(isset($_POST['getCount'])){
        echo json_encode(giveCount());
    }
    if(isset($_POST['vldthst']) && $_POST['vldthst']){
        echo validateHostel();
    }
    if(isset($_POST['fillTable']) && $_POST['fillTable']){
        echo json_encode(getTable());
    }
?>