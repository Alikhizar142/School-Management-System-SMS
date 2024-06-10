<?php
session_start();

if (
    isset($_SESSION['UserID']) &&
    isset($_SESSION['role'])
) {
    if ($_SESSION['role'] == 'Admin') {
        if (
            isset($_POST['name']) &&
            isset($_POST['username']) &&
            isset($_POST['pass']) &&
            isset($_POST['Address']) &&
            isset($_POST['phone_no']) &&
            isset($_POST['Education']) &&
            isset($_POST['email']) &&
            isset($_POST['cnic']) &&
            isset($_POST['gender']) &&
            isset($_POST['DOB']) &&
            isset($_POST['DOJ']) &&
            isset($_POST['subjects'])
        ) {
            include '../../DB_connection.php';

            $name = $_POST['name'];
            $username = $_POST['username'];
            $pass = $_POST['pass'];
            $Address = $_POST['Address'];
            $phone_no = $_POST['phone_no'];
            $Education = $_POST['Education'];
            $email = $_POST['email'];
            $cnic = $_POST['cnic'];
            $gender = $_POST['gender'];
            $DOB = $_POST['DOB'];
            $DOJ = $_POST['DOJ'];
            $subjects = $_POST['subjects'];

            $data = "name=$name&username=$username&Address=$Address&phone_no=$phone_no&Education=$Education&email=$email&cnic=$cnic&gender=$gender&DOB=$DOB&DOJ=$DOJ";

            // Validation
            if (empty($name)) {
                $em = "Name is required";
                header("Location: ../teacher-add.php?error=$em&$data");
                exit;
            } else if (empty($username)) {
                $em = "Username is required";
                header("Location: ../teacher-add.php?error=$em&$data");
                exit;
            } else if (!unameIsUnique($username, $conn)) {
                $em = "Username is taken! Try another";
                header("Location: ../teacher-add.php?error=$em&$data");
                exit;
            } else if (empty($pass)) {
                $em = "Password is required";
                header("Location: ../teacher-add.php?error=$em&$data");
                exit;
            } else if (empty($Address)) {
                $em = "Address is required";
                header("Location: ../teacher-add.php?error=$em&$data");
                exit;
            } else if (empty($phone_no)) {
                $em = "Phone number is required";
                header("Location: ../teacher-add.php?error=$em&$data");
                exit;
            } else if (empty($Education)) {
                $em = "Education is required";
                header("Location: ../teacher-add.php?error=$em&$data");
                exit;
            } else if (empty($email)) {
                $em = "Email address is required";
                header("Location: ../teacher-add.php?error=$em&$data");
                exit;
            } else if (empty($cnic)) {
                $em = "CNIC is required";
                header("Location: ../teacher-add.php?error=$em&$data");
                exit;
            } else if (!cnicIsUnique($cnic, $conn)) {
                $em = "CNIC is already in use! Try another";
                header("Location: ../teacher-add.php?error=$em&$data");
                exit;
            } else if (empty($gender)) {
                $em = "Gender is required";
                header("Location: ../teacher-add.php?error=$em&$data");
                exit;
            } else if (empty($DOB)) {
                $em = "Date of birth is required";
                header("Location: ../teacher-add.php?error=$em&$data");
                exit;
            } else if (empty($DOJ)) {
                $em = "Date of joining is required";
                header("Location: ../teacher-add.php?error=$em&$data");
                exit;
            } else {
                try {
                    $conn->beginTransaction();

                    // Insert into user table
                    $sql = "INSERT INTO user (username, password, Role) VALUES (?, ?, 'Teacher')";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$username, $pass]);
                    $userID = $conn->lastInsertId();

                    // Insert into teacher table
                    $sql = "INSERT INTO teacher (userID, name, gender, email, phone_no, Education, Address, DOB, CNIC_NO, Date_of_joining)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$userID, $name, $gender, $email, $phone_no, $Education, $Address, $DOB, $cnic, $DOJ]);
                    $teacherID = $conn->lastInsertId();

                    // Insert into teacher_subjects table
                    $sql = "INSERT INTO teacher_subjects (TeacherId, SubID) VALUES (?, ?)";
                    $stmt = $conn->prepare($sql);
                    foreach ($subjects as $subject) {
                        $stmt->execute([$teacherID, $subject]);
                    }

                    $conn->commit();

                    $sm = "New teacher registered successfully";
                    header("Location: ../teacher-add.php?success=$sm");
                    exit;
                } catch (Exception $e) {
                    $conn->rollBack();
                    $em = "An error occurred: " . $e->getMessage();
                    header("Location: ../teacher-add.php?error=$em&$data");
                    exit;
                }
            }
        } else {
            $em = "All Fields are required";
            header("Location: ../teacher-add.php?error=$em");
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

// Function to check if username is unique
function unameIsUnique($username, $conn)
{
    $sql = "SELECT COUNT(*) FROM user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username]);
    return $stmt->fetchColumn() == 0;
}

// Function to check if CNIC is unique
function cnicIsUnique($cnic, $conn)
{
    $sql = "SELECT COUNT(*) FROM teacher WHERE CNIC_NO = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$cnic]);
    return $stmt->fetchColumn() == 0;
}
?>