


--CREATE THE DATABASE
    CREATE DATABASE hostelallocation;

--CREATE THE TABLES
    --STUDENTS TABLE
        CREATE TABLE students (
            id varchar(20) PRIMARY KEY,
            name varchar(50),
            email varchar(50),
            password varchar(30),
            batch int,
            gender varchar(15)
        );

    --ROOMS TABLE
        CREATE TABLE rooms (
            roomNo int, 
            hostelId int,
            wingId int,
            currentOccupants int default 0,
            PRIMARY KEY (roomNo, hostelId),
            FOREIGN KEY (hostelId) REFERENCES hostels(hostelId) on DELETE CASCADE
        );

    --HOSTELS TABLE
        CREATE TABLE hostels (
            hostelId int PRIMARY KEY,
            hostelName varchar(20),
            gender varchar(15)
        );

    --HOSTELBATCH TABLE
        CREATE TABLE hostelbatch (
            hostelId int,
            batch int,
            PRIMARY KEY (hostelId, batch),
            FOREIGN KEY (hostelId) REFERENCES hostels(hostelId) ON DELETE CASCADE
        );
    
    --PREFERENCE TABLE
        CREATE TABLE preference (
            studentId varchar(20) PRIMARY KEY,
            hostelId int,
            addedPreferenceAt timestamp default current_timestamp,
            FOREIGN KEY (studentId) REFERENCES students(id) ON DELETE CASCADE,
            FOREIGN KEY (hostelId) REFERENCES hostels(hostelId) ON DELETE CASCADE
        );

    --WINGIES TABLE
        CREATE TABLE wingies (
            studentId varchar(20),
            wingieId varchar(20),
            PRIMARY KEY (studentId, wingieId),
            FOREIGN KEY (studentId) REFERENCES students(id) ON DELETE CASCADE,
            FOREIGN KEY (wingieId) REFERENCES students(id) ON DELETE CASCADE
        );

    --ALLOCATED TABLE
        CREATE TABLE allocated (
            studentId varchar(20),
            roomNo int, 
            hostelId int,
            PRIMARY KEY (studentId),  
            FOREIGN KEY (studentId) REFERENCES students(id) ON DELETE CASCADE,
            FOREIGN KEY (roomNo) REFERENCES rooms(roomNo) ON DELETE CASCADE,
            FOREIGN KEY (hostelId) REFERENCES hostels(hostelId) ON DELETE CASCADE
        );
    
    --FULLHOSTELOCCUPANCY TABLE
    --This table is not part of the main schema; Its only to make use of a trigger 
        CREATE TABLE fullOccupancy (
            hostelId INT PRIMARY KEY,
            isFullyOccupied BOOLEAN default false
        );



--THE TRIGGER (Fired when all rooms of a hostel are occupied)
    DELIMITER $$
    CREATE TRIGGER if not exists check_room_occupancy AFTER INSERT ON allocated
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
        END $$
    DELIMITER $$



--INITIALIZE THE TABLES WITH SOME VALUES

--INITIALIZE students TABLE
    INSERT INTO students VALUES 
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
    ;


--INITIALIZE hostels TABLE
    INSERT into hostels values 
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
    ;


--INITIALIZE hostelbatch TABLE
    INSERT into hostelbatch values
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
    ;


--INITIALIZE rooms TABLE
    --The following statement is run in php in a loop that iterates through all hostels and iterates through 100 rooms
    -- $roomno, $hostelid, and $wingid are variables in php storing loop variables
    --You cannot execute this query independently
    INSERT into rooms (roomNo, hostelId, wingId) values ('$roomno', '$hostelid', '$wingid');


--STUDENT REGISTRATION
    --The following is executed after necessary validations when the user clicks sign up 
    --The parameters are variables (not strings)
    --You cannot execute this query independently
    INSERT into students values ('$id','$name','$email','$password','$batch','$gender');


--ADMIN HOMEPAGE

    --This is used to retrieve and display (on the admin's homepage) the number of students currently registered 
    SELECT count(*) as numstd from students;

    --This is used to retrieve and display (on the admin's homepage) the total number of hostels 
    SELECT count(*) as numhst from hostels;


    --PROCEDURE to display details of all hostels in "view hostels" section
    DELIMITER $$
        CREATE PROCEDURE if not exists `GetHostels`()
        READS SQL DATA
        BEGIN
        select * FROM hostels;
        END $$
    DELIMITER $$
    --calling the procedure
    CALL GetHostels();


    --PROCEDURE to display details of allocation of all students in "view allocation details" section
    DELIMITER $$
        CREATE PROCEDURE if not exists `GetAllocation`()
        READS SQL DATA
        BEGIN
        SELECT * from allocated natural join hostels;
        END $$
    DELIMITER $$
    --calling the procedure
    CALL GetAllocation();


    --PROCEDURE to display all free rooms in the "view free rooms" section
    DELIMITER $$
        CREATE PROCEDURE if not exists `GetFreeRooms`()
        READS SQL DATA
        BEGIN
        SELECT * from rooms natural join hostels where currentOccupants=0;
        END $$
    DELIMITER $$
    --calling the procedure
    CALL GetFreeRooms();


    --PROCEDURE to display details of wingies
    DELIMITER $$
        CREATE PROCEDURE if not exists `GetWingies`()
        READS SQL DATA
        BEGIN
            SELECT * from allocated a inner JOIN students b on a.studentId=b.id inner JOIN hostels c on 
            a.hostelId=c.hostelId inner JOIN rooms d on a.roomNo=d.roomNo and a.hostelId=d.hostelId 
            order by a.hostelId, wingId, a.roomNo;
        END $$
    DELIMITER $$
    --calling the procedure
    CALL GetWingies();


--ADD ROOM PAGE
    --PROCEDURE to insert a new room
    DELIMITER $$
        CREATE PROCEDURE if not exists `InsertRoom`(in roomNo int, in hostelId int, in wingId int)
        MODIFIES SQL DATA
        BEGIN
        INSERT into rooms (roomNo, hostelId, wingId) values (roomNo, hostelId, wingId);
        END $$
    DELIMITER $$
    --calling the procedure ; the parameters are variables
    CALL InsertRoom('$rno', '$hid', '$wid');


--REMOVE ROOM PAGE
    --PROCEDURE to remove an existing room
    DELIMITER $$
        CREATE PROCEDURE if not exists `RemoveRoom`(in room_No int, in hostel_Id int)
        MODIFIES SQL DATA
        BEGIN
        DELETE from rooms where roomNo=room_No and hostelId=hostel_Id;
        END $$
    DELIMITER $$
    --calling the procedure ; the parameters are variables
    CALL RemoveRoom('$rno', '$hid');


--UPDATE ROOM PAGE
    --PROCEDURE to update the status of a room
    DELIMITER $$
        CREATE PROCEDURE if not exists `UpdateRoom`(in room_No int, in hostel_Id int, in stat int)
        MODIFIES SQL DATA
        BEGIN
        UPDATE rooms set currentOccupants=stat where roomNo=room_No and hostelId=hostel_Id;
        END $$
    DELIMITER $$
    --calling the procedure; the parameters are variables
    CALL UpdateRoom('$rno', '$hid', '$stat');


--VIEW HOSTELWISE ALLOCATION PAGE
    --PROCEDURE to view the allocation details of one hostel 
    DELIMITER $$
        CREATE PROCEDURE if not exists `ViewHostelwiseAllocation`(in hostel_Id int)
        READS SQL DATA
        BEGIN
        SELECT * from allocated a inner join students b on a.studentId=b.id WHERE a.hostelId=hostel_Id ORDER by roomNo;
        END $$
    DELIMITER $$
    --calling the procedure; the parameter is a variable
    CALL ViewHostelwiseAllocation('$hid');


--STUDENT HOMEPAGE

    --The following query is used to check whether the student has set his preference or not
    --$sid is a variable storing the id of the currently logged in student. You cannot run the query independently
    SELECT * from wingies where studentId='$sid' or wingieId='$sid'; 

    --The following is used to find the hostel that the student has preferred and display in his homepage
    --The query will not run independently as a variable is involved
    SELECT * from hostels a inner join preference b on a.hostelId = b.hostelId where b.studentId='$theS';

    --The following is used to check whether the student has been allocated a room or not
    SELECT * from allocated where studentId='$sid';

    --The following is used to retrieve the room and hostel that the student got allocated to.
    --Though the query returns all columns we extract the necessary fields only and display accordingly
    SELECT * from allocated a inner join hostels b on a.hostelId = b.hostelId where a.studentId='$sid';


--PREFERENCE PAGE

    --The following query is used to show the list of hostels the student can select from as his preference.
    --It only shows those hostels which have atleast 6 rooms (1 wing) vacant and are for students of his batch and his gender.
    SELECT hostelName, gender from hostels h  NATURAL JOIN (SELECT hostelId, count(roomNo) from rooms where currentOccupants=0
        GROUP BY hostelId HAVING COUNT(roomNo)>=6) r NATURAL JOIN hostelbatch hb where gender='$tg' and hb.batch='$tbtch';

    --This adds the preference of the student after he has selected his hostel and wingies
    INSERT into preference (studentId, hostelId) values ('$self', '$hostelId');

    --This adds the current student and the wingies he has entered into the wingies table. 
    --This is repeated for all 11 wingies of the student. $self is the current student, $wingie1 is one of the wingies
    INSERT into wingies values ('$self', '$wingie1');


--THE ALLOCATION

    --This query retrieves 6 rooms from a vacant wing of the hostel the student has preferred. 
    --We are only getting the vacant and compatible hostels now and storing in a variable.
    --We will then allocate all 12 students in each of these 6 rooms
    SELECT * from rooms where currentOccupants=0 and hostelId='$hostelId' and wingId=(
        SELECT wingId from rooms where currentOccupants=0 and hostelId='$hostelId' group by wingId having count(*)>=6 LIMIT 1
    ) LIMIT 6;

    --If preferred hostel is not available then the following query is used to allocate from any other vacant hostel
    SELECT * FROM rooms WHERE currentOccupants = 0 AND wingId IN (
        SELECT wingId FROM rooms WHERE currentOccupants = 0 GROUP BY wingId HAVING COUNT(*) >= 6
    ) AND hostelId IN (
        SELECT hostelId FROM rooms WHERE currentOccupants = 0 GROUP BY hostelId HAVING COUNT(*) >= 6
    ) ORDER BY wingId, hostelId LIMIT 6;

    --After retrieving 6 vacant rooms, queries such as the following are executed corresponding to all 12 students and all 6 rooms
    INSERT into allocated values('$self', '$rno', '$hid');
    INSERT into allocated values('$wingie1', '$rno', '$hid');
    --and the occupancy status of each room is updated as follows
    UPDATE rooms set currentOccupants=2 where roomNo='$rno' and hostelId='$hid';


