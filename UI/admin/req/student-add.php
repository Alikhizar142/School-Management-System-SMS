<?php 
session_start();
if (isset($_SESSION['UserID']) && 
    isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Admin') {
        
        if (isset($_POST['name']) &&
            isset($_POST['address'])  &&
            isset($_POST['gender'])   && 
            isset($_POST['date_of_birth']) &&
            isset($_POST['parent_name'])  && // Changed from 'parent_name'
            isset($_POST['parent_phone_number']) &&
            isset($_POST['parent_cnic']) &&
            isset($_POST['email_address']) &&
            isset($_POST['Class']) && // Changed from 'class'
            isset($_POST['year']) &&
            isset($_POST['subjects']) &&
            isset($_POST['username']) &&
            isset($_POST['pass'])) {

            include '../../DB_connection.php';
            include "../data/student.php";
            $name = $_POST['name']; // Changed from 'name'
            $address = $_POST['address'];
            $gender = $_POST['gender'];
            $date_of_birth = $_POST['date_of_birth'];
            $parent_name = $_POST['parent_name']; // Changed from 'parent_name'
            $parent_phone_number = $_POST['parent_phone_number'];
            $parent_cnic = $_POST['parent_cnic'];
            $email_address = $_POST['email_address'];
            $year = $_POST['year'];
            $subjects = $_POST['subjects'];
            $username = $_POST['username'];
            $pass = $_POST['pass'];
            $classData = explode('-', $_POST['Class']);
            $classId = $classData[0];
            $section = $classData[1];
            $date_of_birth = date('Y-m-d', strtotime($_POST['date_of_birth']));
            // Prepare data for passing in URL
            $data = '&name='.$name.'&address='.$address.'&gender='.$gender.'&parent_name='.$parent_name.'&parent_phone_number='.$parent_phone_number.'&parent_cnic='.$parent_cnic.'&email_address='.$email_address.'&Class='.$classId.'&year='.$year.'&username='.$username.'&pass='.$pass;
            if (empty($name) || empty($address) || empty($gender) || empty($date_of_birth) || empty($parent_name) || empty($parent_phone_number) || empty($parent_cnic) || empty($email_address) || empty($classId) || empty($year) || empty($subjects) || empty($username) || empty($pass)) {
                $em = "All fields are required";
                header("Location: ../student-add.php?error=$em$data");
                exit;
            } else {
                try {
                    $conn->beginTransaction();

                    // Check if parent CNIC already exists
                    $sqlCheckParent = "SELECT idParent FROM parent WHERE CNIC_NO = ?";
                    $stmtCheckParent = $conn->prepare($sqlCheckParent);
                    $stmtCheckParent->execute([$parent_cnic]);
                    
                    if ($stmtCheckParent->rowCount() > 0) {
                        // Parent CNIC exists, get the parent_id
                        $parentRow = $stmtCheckParent->fetch(PDO::FETCH_ASSOC);
                        $parentId = $parentRow['idParent'];
                    } else {
                        // Parent CNIC does not exist, insert new record into parent table
                        $sqlParent = "INSERT INTO parent (name, phone_no, CNIC_No, email) VALUES (?,?,?,?)";
                        $stmtParent = $conn->prepare($sqlParent);
                        $stmtParent->execute([$parent_name, $parent_phone_number, $parent_cnic, $email_address]);
                        
                        $parentId = $conn->lastInsertId(); // Get the last inserted parent ID
                    }
                    // Insert into user table
                    $sqlUser = "INSERT INTO user (username, password, role) VALUES (?,?,?)";
                    $stmtUser = $conn->prepare($sqlUser);
                    $stmtUser->execute([$username, $pass, 'Student']);
                    $UserID = $conn->lastInsertId();
                    // Insert into student table
                    $sqlStudent = "INSERT INTO student (name, UserID, Address, Gender, DOB, ParentId) VALUES (?,?,?,?,?,?)"; // Removed the extra comma after ParentId
                    $stmtStudent = $conn->prepare($sqlStudent);
                    $stmtStudent->execute([$name, $UserID, $address, $gender, $date_of_birth, $parentId]);
                    
                    $studentId = $conn->lastInsertId(); // Get the last inserted student ID
                    
                    // Insert into registration table
                    $sqlRegistration = "INSERT INTO registration (StdId, ClassId, SubID, Section, Session) VALUES (?,?,?,?,?)";
                    $stmtRegistration = $conn->prepare($sqlRegistration);
                    
                    foreach ($subjects as $subject) {
                        $stmtRegistration->execute([$studentId, $classId, $subject, $section, $year]);
                    }
                    
                    
                    $conn->commit();
                    
                    $sm = "New student registered successfully";
                    header("Location: ../student-add.php?success=$sm");
                    exit;
                } catch (Exception $e) {
                    $conn->rollBack();
                    $em = "An error occurred while registering the student: " . $e->getMessage();
                    header("Location: ../student-add.php?error=$em$data");
                    exit;
                }
                
                
            }
        } else {
            $em = "All fields are required";
            // header("Location: ../student-add.php?error=$em");
            // exit;
        }
    } else {
        header("Location: ../../logout.php");
        exit;
    }
} else {
    header("Location: ../../logout.php");
    exit;
}
?>
