<?php
    $_SESSION['init'] = true;
    $server_name = "localhost";
    $database_userName = "root";
    $database_userPassword = "";
    // $_SESSION['dbConnection'] = mysqli_connect($server_name, $database_userName, $database_userPassword);

    $connection = mysqli_connect($server_name, $database_userName, $database_userPassword);
    if (!$connection) {
        die("Failed ". mysqli_connect_error());
    }
    $delquery = "DROP DATABASE IF EXISTS hostelallocation";
    mysqli_query($connection, $delquery);
    
    $query = "CREATE DATABASE hostelallocation";
    if (!mysqli_query($connection, $query)) {
        echo "Error:" . mysqli_error($connection);
    } 
    else {
        $dbname = "hostelallocation";
        $_SESSION['dbConnection'] = mysqli_connect($server_name, $database_userName, $database_userPassword, $dbname);
        if(createStudentsTable()){
            fillStudentsTable();
        }
        if(createHostelsTable()){
            fillHostelsTable();
        }
        if(createHostelBatchTable()){
            fillHostelBatchTable();
        }
        if(createRoomsTable()){
            fillRoomsTable();
        }
        createPreferenceTable();
        createWingiesTable();
        createAllocatedTable();

        
        if(createFullHostelOccupancyTable()){
            fillFullHostelOccupancyTable();
        }
    }

    $maketriggerquery = "CREATE TRIGGER if not exists check_room_occupancy AFTER INSERT ON allocated
                            FOR EACH ROW
                            BEGIN
                            DECLARE total_rooms INT;
                            DECLARE occupied_rooms INT;
                            
                            SELECT COUNT(*) INTO total_rooms FROM rooms WHERE hostelId = NEW.hostelId;
                            SELECT COUNT(*) INTO occupied_rooms FROM allocated WHERE hostelId = NEW.hostelId;
                            
                            IF total_rooms = occupied_rooms THEN
                                -- All rooms are occupied, update hostel occupancy status
                                INSERT INTO fullOccupancy (hostelId, isFullyOccupied) VALUES (NEW.hostelId, true) 
                                    ON DUPLICATE KEY UPDATE isFullyOccupied = true;
                            END IF;
                            END;";
    $restrig = mysqli_query($_SESSION['dbConnection'], $maketriggerquery);

    function createStudentsTable(){
        $studquery = "CREATE TABLE students (
            id varchar(20) PRIMARY KEY,
            name varchar(50),
            email varchar(50),
            password varchar(30),
            batch int,
            gender varchar(15)
        );";
        if (!mysqli_query($_SESSION['dbConnection'], $studquery)) {
            echo "Error:" . mysqli_error($_SESSION['dbConnection']);
            return false;
        } else {
            return true;
        }
    }
    function createRoomsTable(){
        $roomquery = "CREATE TABLE rooms (
            roomNo int, 
            hostelId int,
            wingId int,
            currentOccupants int default 0,
            PRIMARY KEY (roomNo, hostelId),
            FOREIGN KEY (hostelId) REFERENCES hostels(hostelId) on DELETE CASCADE
        );";
        if (!mysqli_query($_SESSION['dbConnection'], $roomquery)) {
            echo "Error:" . mysqli_error($_SESSION['dbConnection']);
            return false;
        } else {
            return true;
        }
    }
    function createHostelsTable(){
        $hostelquery = "CREATE TABLE hostels (
            hostelId int PRIMARY KEY,
            hostelName varchar(20),
            gender varchar(15)
        );";
        if (!mysqli_query($_SESSION['dbConnection'], $hostelquery)) {
            echo "Error:" . mysqli_error($_SESSION['dbConnection']);
            return false;
        } else {
            return true;
        }
    }
    function createHostelBatchTable(){
        $hostelbatchquery = "CREATE TABLE hostelbatch (
            hostelId int,
            batch int,
            PRIMARY KEY (hostelId, batch),
            FOREIGN KEY (hostelId) REFERENCES hostels(hostelId) ON DELETE CASCADE
        );";
        if (!mysqli_query($_SESSION['dbConnection'], $hostelbatchquery)) {
            echo "Error:" . mysqli_error($_SESSION['dbConnection']);
            return false;
        } else {
            return true;
        }
    }
    function createPreferenceTable(){
        $preferencequery = "CREATE TABLE preference (
            studentId varchar(20) PRIMARY KEY,
            hostelId int,
            addedPreferenceAt timestamp default current_timestamp,
            FOREIGN KEY (studentId) REFERENCES students(id) ON DELETE CASCADE,
            FOREIGN KEY (hostelId) REFERENCES hostels(hostelId) ON DELETE CASCADE
        );";
        if (!mysqli_query($_SESSION['dbConnection'], $preferencequery)) {
            echo "Error:" . mysqli_error($_SESSION['dbConnection']);
            return false;
        } else {
            return true;
        }
    }
    function createWingiesTable(){
        $wingiesquery = "CREATE TABLE wingies (
            studentId varchar(20),
            wingieId varchar(20),
            PRIMARY KEY (studentId, wingieId),
            FOREIGN KEY (studentId) REFERENCES students(id) ON DELETE CASCADE,
            FOREIGN KEY (wingieId) REFERENCES students(id) ON DELETE CASCADE
        );";
        if (!mysqli_query($_SESSION['dbConnection'], $wingiesquery)) {
            echo "Error:" . mysqli_error($_SESSION['dbConnection']);
            return false;
        } else {
            return true;
        }
    }
    function createAllocatedTable(){
        $allocatedquery = "CREATE TABLE allocated (
            studentId varchar(20),
            roomNo int, 
            hostelId int,
            PRIMARY KEY (studentId),  
            FOREIGN KEY (studentId) REFERENCES students(id) ON DELETE CASCADE,
            FOREIGN KEY (roomNo) REFERENCES rooms(roomNo) ON DELETE CASCADE,
            FOREIGN KEY (hostelId) REFERENCES hostels(hostelId) ON DELETE CASCADE
        );";
        if (!mysqli_query($_SESSION['dbConnection'], $allocatedquery)) {
            echo "Error:" . mysqli_error($_SESSION['dbConnection']);
            return false;
        } else {
            return true;
        }
    }
    function createFullHostelOccupancyTable(){
        //This table is not part of the main schema; its only for the demo of a trigger
        $fho = "CREATE TABLE fullOccupancy (
                    hostelId INT PRIMARY KEY,
                    isFullyOccupied BOOLEAN default false
                );";
        if (!mysqli_query($_SESSION['dbConnection'], $fho)){ 
            echo "Error:" . mysqli_error($_SESSION['dbConnection']);
            return false;
        } else {
            return true;
        }
    }
    function fillFullHostelOccupancyTable(){
        $fillfho = "INSERT into fullOccupancy (hostelId) values (1), (2), (3), (4), (5), (6), (7), (8), (9), (10), 
            (11), (12), (13), (14);";
        if (!mysqli_query($_SESSION['dbConnection'], $fillfho)) {
            echo "Error:" . mysqli_error($_SESSION['dbConnection']);
            return false;
        } else {
            return true;
        }
    }
    function fillStudentsTable(){
        $fillstudquery = "INSERT INTO students VALUES 
            ('2021A7PS2005P', 'Ohiduz', 'f20212005@pilani.bits-pilani.ac.in', '2005', 2021, 'Male'),
            ('2021A7PS0001P', 'John', 'f20210001@pilani.bits-pilani.ac.in', '0001', 2021, 'Male'),
            ('2021A7PS2002P', 'George', 'f20210002@pilani.bits-pilani.ac.in', '0002', 2021, 'Male'),
            ('2021A7PS2003P', 'Lylia', 'f20210003@pilani.bits-pilani.ac.in', '0003', 2021, 'Female'),
            ('2021A7PS2004P', 'Lunox', 'f20210004@pilani.bits-pilani.ac.in', '0004', 2021, 'Female'),
            ('2020A7PS0005P', 'Moskov', 'f20200005@pilani.bits-pilani.ac.in', '0005', 2020, 'Male'),
            ('2020A7PS0006P', 'Harith', 'f20200006@pilani.bits-pilani.ac.in', '0006', 2020, 'Male'),
            ('2020A7PS0007P', 'Kagura', 'f20200007@pilani.bits-pilani.ac.in', '0007', 2020, 'Female'),
            ('2020A7PS0008P', 'Eudora', 'f20200008@pilani.bits-pilani.ac.in', '0008', 2020, 'Female'),
            ('2020A7PS0009P', 'Gusion', 'f20200009@pilani.bits-pilani.ac.in', '0009', 2020, 'Male'),
            ('2020A7PS0010P', 'Lancelot', 'f20200010@pilani.bits-pilani.ac.in', '0010', 2020, 'Male'),
            ('2021A7PS0011P', 'Leomord', 'f20210011@pilani.bits-pilani.ac.in', '0011', 2021, 'Male'),
            ('1', 'Julian', 'julian@gmail.com', '1', 2021, 'Male'),
            ('2', 'Terizla', 'terizla@gmail.com', '2', 2021, 'Male'),
            ('3', 'Claude', 'claude@gmail.com', '3', 2021, 'Male'),
            ('4', 'Cecil', 'cecil@gmail.com', '4', 2021, 'Male'),
            ('5', 'Martis', 'martis@gmail.com', '5', 2021, 'Male'),
            ('6', 'Harley', 'harley@gmail.com', '6', 2021, 'Male'),
            ('7', 'Yin', 'yin@gmail.com', '7', 2021, 'Male'),
            ('8', 'Argus', 'argus@gmail.com', '8', 2021, 'Male'),
            ('9', 'Clint', 'clint@gmail.com', '9', 2021, 'Male'),
            ('10', 'Faramis', 'faramis@gmail.com', '10', 2021, 'Male'),
            ('11', 'Grock', 'grock@gmail.com', '11', 2021, 'Male'),
            ('12', 'Belrick', 'belrick@gmail.com', '12', 2021, 'Male'),
            ('21', 'Lesley', 'lesley@gmail.com', '21', 2021, 'Female'),
            ('22', 'Luo Yi', 'luoyi@gmail.com', '22', 2021, 'Female'),
            ('23', 'Lolita', 'lolita@gmail.com', '23', 2021, 'Female'),
            ('24', 'Aurora', 'aurora@gmail.com', '24', 2021, 'Female'),
            ('25', 'Karrie', 'karrie@gmail.com', '25', 2021, 'Female'),
            ('26', 'Fanny', 'fanny@gmail.com', '26', 2021, 'Female'),
            ('27', 'Hanabi', 'hanabi@gmail.com', '27', 2021, 'Female'),
            ('28', 'Wanwan', 'wanwan@gmail.com', '28', 2021, 'Female'),
            ('29', 'Carmilla', 'carmilla@gmail.com', '29', 2021, 'Female'),
            ('30', 'Kadita', 'kadita@gmail.com', '30', 2021, 'Female'),
            ('31', 'Odette', 'odette@gmail.com', '31', 2021, 'Female'),
            ('32', 'Karina', 'karina@gmail.com', '32', 2021, 'Female'),
            ('41', 'Hayabusa', 'hayabusa@gmail.com', '41', 2019, 'Male'),
            ('42', 'Badang', 'badang@gmail.com', '42', 2019, 'Male'),
            ('43', 'Helcurt', 'helcurt@gmail.com', '43', 2019, 'Male'),
            ('44', 'Hanzo', 'hanzo@gmail.com', '44', 2019, 'Male'),
            ('45', 'Zilong', 'zilong@gmail.com', '45', 2019, 'Male')                  
            
        ;";
        if (!mysqli_query($_SESSION['dbConnection'], $fillstudquery)) {
            echo "Error:" . mysqli_error($_SESSION['dbConnection']);
            return false;
        } else {
            return true;
        }
    }
    function fillHostelsTable(){
        $fillhostelquery = "INSERT into hostels values 
            (1, 'Ram', 'Male'),
            (2, 'Budh', 'Male'),
            (3, 'Krishna', 'Male'),
            (4, 'Gandhi', 'Male'),
            (5, 'Shankar', 'Male'),
            (6, 'Vyas', 'Male'),
            (7, 'SR', 'Male'),
            (8, 'Vishwakarma', 'Male'),
            (9, 'Bhagirath', 'Male'),
            (10, 'Ashok', 'Male'),
            (11, 'Rana Pratap', 'Male'),
            (12, 'CVR', 'Male'),
            (13, 'Malviya', 'Male'),
            (14, 'Meera', 'Female')
        ;";
        if (!mysqli_query($_SESSION['dbConnection'], $fillhostelquery)) {
            echo "Error:" . mysqli_error($_SESSION['dbConnection']);
            return false;
        } else {
            return true;
        }
    }
    function fillHostelBatchTable(){
        $fillhbquery = "INSERT into hostelbatch values
            (1, 2021),
            (2, 2021), (2, 2020),
            (3, 2022), (3, 2020),
            (4, 2022), (4, 2020),
            (5, 2021), (5, 2020),
            (6, 2021), (6, 2020),
            (7, 2022),
            (8, 2022), (8, 2019),
            (9, 2019), (9, 2018),
            (10, 2019), (10, 2018),
            (11, 2019), (11, 2018),
            (12, 2019), (12, 2018),
            (13, 2017), (13, 2016),
            (14, 2022), (14, 2021), (14, 2020), (14, 2019), (14, 2018), (14, 2017), (14, 2016)
        ;";
        if (!mysqli_query($_SESSION['dbConnection'], $fillhbquery)) {
            echo "Error:" . mysqli_error($_SESSION['dbConnection']);
            return false;
        } else {
            return true;
        }
    }
    function fillRoomsTable(){
        for($hostelid=1; $hostelid<=14; $hostelid++){
            for($i=0; $i<100; $i++){
                $roomno = $i + 1;
                $wingid = intdiv($i, 6) + 1;
                $addrooms = "INSERT into rooms (roomNo, hostelId, wingId) values
                    ('$roomno', '$hostelid', '$wingid');";
                if (!mysqli_query($_SESSION['dbConnection'], $addrooms)) {
                    echo "Error:" . mysqli_error($_SESSION['dbConnection']);
                    return false;
                }
            }
        }
        return true;
    }
?>