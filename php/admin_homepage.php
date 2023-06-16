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

    function getTableH(){
        $recordsH = array();

        $ihqr = "CREATE PROCEDURE if not exists `GetHostels`()
                    READS SQL DATA
                    BEGIN
                    select * FROM hostels;
                    END;";
        $ires = mysqli_query($_SESSION['dbConnection'], $ihqr);
                
        $hqr = "CALL GetHostels();";
        $resh = mysqli_query($_SESSION['dbConnection'], $hqr);

        while($rowh = mysqli_fetch_assoc($resh)){
            $recordsH[] = $rowh;
        }
        return $recordsH;
    }

    function getTableA(){
        $recordsA = array();

        $iaqr = "CREATE PROCEDURE if not exists `GetAllocation`()
                    READS SQL DATA
                    BEGIN
                    SELECT * from allocated natural join hostels;
                    END;";
        $iares = mysqli_query($_SESSION['dbConnection'], $iaqr);

        $aqr = "CALL GetAllocation();";
        $resa = mysqli_query($_SESSION['dbConnection'], $aqr);

        while($rowa = mysqli_fetch_assoc($resa)){
            $recordsA[] = $rowa;
        }
        return $recordsA;
    }
    function getTableR(){
        $recordsR = array();

        $irqr = "CREATE PROCEDURE if not exists `GetFreeRooms`()
                    READS SQL DATA
                    BEGIN
                    SELECT * from rooms natural join hostels where currentOccupants=0;
                    END;";
        $irres = mysqli_query($_SESSION['dbConnection'], $irqr);

        $rqr = "CALL GetFreeRooms();";
        $resr = mysqli_query($_SESSION['dbConnection'], $rqr);

        while($rowr = mysqli_fetch_assoc($resr)){
            $recordsR[] = $rowr;
        }
        return $recordsR;
    }
    function getTableW(){
        $recordsW = array();

        $iwqr = "CREATE PROCEDURE if not exists `GetWingies`()
                    READS SQL DATA
                    BEGIN
                        SELECT * from allocated a inner JOIN students b on a.studentId=b.id inner JOIN hostels c on 
                        a.hostelId=c.hostelId inner JOIN rooms d on a.roomNo=d.roomNo and a.hostelId=d.hostelId 
                        order by a.hostelId, wingId, a.roomNo;
                    END;";
        $iwres = mysqli_query($_SESSION['dbConnection'], $iwqr);

        $wqr = "CALL GetWingies();";
        $resw = mysqli_query($_SESSION['dbConnection'], $wqr);

        while($roww = mysqli_fetch_assoc($resw)){
            $recordsW[] = $roww;
        }
        return $recordsW;
    }

    if(isset($_POST['getCount'])){
        echo json_encode(giveCount());
    }
    if(isset($_POST['fillTableH'])){
        echo json_encode(getTableH());
    }
    if(isset($_POST['fillTableA'])){
        echo json_encode(getTableA());
    }
    if(isset($_POST['fillTableR'])){
        echo json_encode(getTableR());
    }
    if(isset($_POST['fillTableW'])){
        echo json_encode(getTableW());
    }
?>