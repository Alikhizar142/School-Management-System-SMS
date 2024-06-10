<?php
session_start();
if (isset($_SESSION['idTeacher']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Teacher') {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!isset($_POST['attendance_date']) || empty($_POST['attendance_date'])) {
                $sm = "Please Select Date First";
                // header("Location: ../Attendence.php?error=$sm");
                header("Location: ../AttendenceTake.php?idClass=" . $_GET['idClass'] . "&error=" . urlencode($sm));
                exit;
            }
            include "../../DB_connection.php";

            $attendance_date = $_POST['attendance_date'];
            $class_id = $_GET['idClass'];

            foreach ($_POST as $key => $value) {
                if (strpos($key, 'status_') === 0) {
                    $student_id = substr($key, 7);
                    $status = $_POST[$key];

                    // Check if attendance record exists
                    $checkQuery = "SELECT * FROM attendence WHERE StdID = :student_id AND StdClass = :class_id AND Date = :attendance_date";
                    $stmtCheck = $conn->prepare($checkQuery);
                    $stmtCheck->bindValue(":student_id", $student_id, PDO::PARAM_INT);
                    $stmtCheck->bindValue(":class_id", $class_id, PDO::PARAM_INT);
                    $stmtCheck->bindValue(":attendance_date", $attendance_date, PDO::PARAM_STR);
                    $stmtCheck->execute();
                    $resultCheck = $stmtCheck->fetch();

                    if ($resultCheck) {
                        // Update existing attendance record
                        $updateQuery = "UPDATE attendence SET Status = :status WHERE StdID = :student_id AND StdClass = :class_id AND Date = :attendance_date";
                        $stmtUpdate = $conn->prepare($updateQuery);
                        $stmtUpdate->bindValue(":status", $status, PDO::PARAM_STR);
                        $stmtUpdate->bindValue(":student_id", $student_id, PDO::PARAM_INT);
                        $stmtUpdate->bindValue(":class_id", $class_id, PDO::PARAM_INT);
                        $stmtUpdate->bindValue(":attendance_date", $attendance_date, PDO::PARAM_STR);
                        $stmtUpdate->execute();
                    } else {
                        // Insert new attendance record
                        $insertQuery = "INSERT INTO attendence (StdID, StdClass, Date, Status) VALUES (:student_id, :class_id, :attendance_date, :status)";
                        $stmtInsert = $conn->prepare($insertQuery);
                        $stmtInsert->bindValue(":student_id", $student_id, PDO::PARAM_INT);
                        $stmtInsert->bindValue(":class_id", $class_id, PDO::PARAM_INT);
                        $stmtInsert->bindValue(":attendance_date", $attendance_date, PDO::PARAM_STR);
                        $stmtInsert->bindValue(":status", $status, PDO::PARAM_STR);
                        $stmtInsert->execute();
                    }

                    $stmtCheck->closeCursor();
                }
            }
            $sm = "Attendence Recorded successfully";
            header("Location: ../Attendence.php?success=$sm");
            exit;
        } else {
            // Redirect to an error page or previous page if form was not submitted properly
            $sm = "Attendence is not Recorded successfully";
            header("Location: ../Attendence.php?error=$sm");
            exit;
        }
    } else {
        header("Location: ../login.php");
        exit;
    }
} else {
    header("Location: ../login.php");
    exit;
}
?>