<?php
session_start();
if (isset($_SESSION['UserID']) && isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Admin') {
        $name = $_POST['name'];
        $username = $_POST['username'];
        $idTeacher = $_POST['idTeacher'];
        $address = $_POST['Address'];
        $phone_no = $_POST['phone_no'];
        $education = $_POST['Education'];
        $email = $_POST['email'];
        $gender = $_POST['gender'];
        $dob = $_POST['DOB'];
        $subjects = $_POST['subjects'];

        if (
            isset($_POST['name']) &&
            isset($_POST['username']) &&
            isset($_POST['idTeacher']) &&
            isset($_POST['Address']) &&
            isset($_POST['phone_no']) &&
            isset($_POST['Education']) &&
            isset($_POST['email']) &&
            isset($_POST['gender']) &&
            isset($_POST['DOB']) &&
            isset($_POST['subjects'])
        ) {

            include '../../DB_connection.php';
            include "../data/teacher.php";

            $name = $_POST['name'];
            $uname = $_POST['username'];
            $address = $_POST['Address'];
            $phone_number = $_POST['phone_no'];
            $Education = $_POST['Education'];
            $email_address = $_POST['email'];
            $gender = $_POST['gender'];
            $date_of_birth = $_POST['DOB'];
            $teacher_id = $_POST['idTeacher'];

            if (empty($name) || empty($uname) || empty($address) || empty($phone_number) || empty($Education) || empty($email_address) || empty($gender) || empty($date_of_birth)) {
                $em = "All fields are required";
                header("Location: ../teacher-edit.php?error=$em&idTeacher=$teacher_id");
                exit;
            } else {
                $subjects = $_POST['subjects'];
                if (is_array($subjects)) {
                    foreach ($subjects as $subject_id) {
                        echo $subject_id . "<br>";
                    }
                } else {
                    echo "No subjects selected or subjects is not an array.";

                }
                // Update teacher's username
                $sql1 = "UPDATE user SET username = ? WHERE UserID = (SELECT userID FROM teacher WHERE idTeacher = ?)";
                $stmt1 = $conn->prepare($sql1);
                $stmt1->execute([$uname, $teacher_id]);

                // Update teacher's details
                $sql2 = "UPDATE teacher SET
                            name = ?, gender = ?, email = ?, phone_no = ?, Education = ?, Address = ?, DOB = ?
                            WHERE idTeacher = ?";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->execute([$name, $gender, $email_address, $phone_number, $Education, $address, $date_of_birth, $teacher_id]);

                // Update teacher's courses
                // Delete existing courses
                $sql3 = "DELETE FROM teacher_subjects WHERE TeacherId = ?";
                $stmt3 = $conn->prepare($sql3);
                $stmt3->execute([$teacher_id]);

                // Insert new courses
                foreach ($subjects as $subject_id) {
                    $sql4 = "INSERT INTO teacher_subjects (TeacherId, SubID) VALUES (?, ?)";
                    $stmt4 = $conn->prepare($sql4);
                    $stmt4->execute([$teacher_id, $subject_id]);
                }

                $sm = "Successfully updated!";
                header("Location: ../teacher-edit.php?success=$sm&idTeacher=$teacher_id");
                exit;
            }
        } else {
            $em = "An error occurred";
            header("Location: ../teacher.php?error=$em");
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