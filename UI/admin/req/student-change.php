<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (isset($_SESSION['UserID']) && isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Admin') {

        if (isset($_POST['admin_pass']) && isset($_POST['new_pass']) && isset($_POST['c_new_pass']) && isset($_POST['idStudent'])) {
            
            include '../../DB_connection.php';
            include "../data/admin.php";
            include "../data/student.php";

            $admin_pass = $_POST['admin_pass'];
            $new_pass = $_POST['new_pass'];
            $c_new_pass = $_POST['c_new_pass'];
            $idStudent = $_POST['idStudent'];
            
            $data = 'idStudent=' . $idStudent;

            // Validate the inputs
            if (empty($admin_pass)) {
                $em = "Admin password is required";
                header("Location: ../student-edit.php?perror=$em&$data");
                exit;
            } else if (empty($new_pass)) {
                $em = "New password is required";
                header("Location: ../student-edit.php?perror=$em&$data");
                exit;
            } else if (empty($c_new_pass)) {
                $em = "Confirmation password is required";
                header("Location: ../student-edit.php?perror=$em&$data");
                exit;
            } else if ($new_pass !== $c_new_pass) {
                $em = "New password and confirm password do not match";
                header("Location: ../student-edit.php?perror=$em&$data");
                exit;
            } else if (adminPasswordVerify($admin_pass, $conn) == 0) {
                $em = "Incorrect admin password";
                header("Location: ../student-edit.php?perror=$em&$data");
                exit;
            } else {
                // Fetch the user ID using the student ID
                $userID = getUserIDByStudentID($idStudent, $conn);

                // Check if the user ID is found
                if ($userID === null) {
                    $em = "Student ID not found";
                    header("Location: ../student-edit.php?perror=$em&$data");
                    exit;
                }

                // Hash the new password before storing it

                // Update the password in the database
                $sql = "UPDATE user SET password = ? WHERE UserID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$new_pass, $userID]);

                $sm = "The password has been changed successfully!";
                header("Location: ../student-edit.php?psuccess=$sm&$data");
                exit;
            }
        } else {
            $em = "An error occurred";
            header("Location: ../student-edit.php?error=$em&$data");
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
