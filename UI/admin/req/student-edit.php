<?php 
session_start();
if (isset($_SESSION['UserID']) && 
    isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Admin') {

        if (isset($_POST['name']) &&
            isset($_POST['username']) &&
            isset($_POST['address']) &&
            isset($_POST['parent_email']) &&
            isset($_POST['idStudent']) &&
            isset($_POST['gender']) &&
            isset($_POST['DOB']) &&
            isset($_POST['parent_name']) &&
            isset($_POST['parent_phoneNo']) &&
            isset($_POST['class_name'])) {
            
            include '../../DB_connection.php';
            include "../data/student.php";

            $name = $_POST['name'];
            $uname = $_POST['username'];
            $address = $_POST['address'];
            $gender = $_POST['gender'];
            $email_address = $_POST['parent_email'];
            $date_of_birth = $_POST['DOB'];
            $parent_name = $_POST['parent_name'];
            $parent_phone_number = $_POST['parent_phoneNo'];
            $idStudent = $_POST['idStudent'];
            $grade = $_POST['class_name'];
            echo $idStudent;
            $data = 'idStudent=' . $idStudent;

            if (empty($name)) {
                $em = "Student name is required";
                header("Location: ../student-edit.php?error=$em&$data");
                exit;
            } else if (empty($uname)) {
                $em = "Username is required";
                header("Location: ../student-edit.php?error=$em&$data");
                exit;
            } else if (unameIsUnique($uname, $conn,$idStudent)==0) {
                $em = "Username is taken! Try another";
                header("Location: ../student-edit.php?error=$em&$data");
                exit;
            } else if (empty($address)) {
                $em = "Address is required";
                header("Location: ../student-edit.php?error=$em&$data");
                exit;
            } else if (empty($gender)) {
                $em = "Gender is required";
                header("Location: ../student-edit.php?error=$em&$data");
                exit;
            } else if (empty($email_address)) {
                $em = "Email address is required";
                header("Location: ../student-edit.php?error=$em&$data");
                exit;
            } else if (empty($date_of_birth)) {
                $em = "Date of birth is required";
                header("Location: ../student-edit.php?error=$em&$data");
                exit;
            } else if (empty($parent_name)) {
                $em = "Parent name is required";
                header("Location: ../student-edit.php?error=$em&$data");
                exit;
            } else if (empty($parent_phone_number)) {
                $em = "Parent phone number is required";
                header("Location: ../student-edit.php?error=$em&$data");
                exit;
            }  else {
                // Begin transaction
                $conn->beginTransaction();

                try {

                    echo"here";
                    // Update student table
                    $sql_student = "UPDATE student SET
                                    name = ?,  Address = ?, Gender = ?, DOB = ?
                                    WHERE idStudent = ?";
                    $stmt_student = $conn->prepare($sql_student);
                    $stmt_student->execute([$name, $address, $gender,$date_of_birth,$idStudent]);

                    // Get the idParent from students table
                    $sql_get_parent_id = "SELECT ParentId, UserID FROM student WHERE idStudent = ?";
                    $stmt_get_parent_id = $conn->prepare($sql_get_parent_id);
                    $stmt_get_parent_id->execute([$idStudent]);
                    $row = $stmt_get_parent_id->fetch(PDO::FETCH_ASSOC);
                    $parent_id = $row['ParentId'];
                    $user_id = $row['UserID'];

                    // Update parent table
                    $sql_parent = "UPDATE parent SET
                                   name = ?, phone_no = ?,email= ?
                                   WHERE idParent = ?";
                    $stmt_parent = $conn->prepare($sql_parent);
                    $stmt_parent->execute([$parent_name, $parent_phone_number,$email_address,$parent_id]);

                    // Update user table
                    $sql_user = "UPDATE user SET
                                 username = ?
                                 WHERE UserID = ?";
                    $stmt_user = $conn->prepare($sql_user);
                    $stmt_user->execute([$uname, $user_id]);

                    // Commit transaction
                    $conn->commit();

                    $sm = "Successfully updated!";
                    header("Location: ../student-edit.php?success=$sm&$data");
                    exit;

                } catch (Exception $e) {
                    // Rollback transaction in case of error
                    $conn->rollBack();
                    $em = "An error occurred: " . $e->getMessage();
                    header("Location: ../student-edit.php?error=$em&$data");
                    exit;
                }
            }
        } else {
            $em = "An error occurred";
            header("Location: ../student.php?error=$em");
            exit;
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
