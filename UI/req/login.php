<?php
session_start();

if (
    isset($_POST['uname']) &&
    isset($_POST['pass']) &&
    isset($_POST['role'])
) {

    include "../DB_connection.php";

    $uname = $_POST['uname'];
    $pass = $_POST['pass'];
    $role = $_POST['role'];

    if (empty($uname)) {
        $em = "Username is required";
        header("Location: ../login.php?error=$em");
        exit;
    } else if (empty($pass)) {
        $em = "Password is required";
        header("Location: ../login.php?error=$em");
        exit;
    } else if (empty($role)) {
        $em = "An error Occurred";
        header("Location: ../login.php?error=$em");
        exit;
    } else {
        if ($role == '1') {
            $sql = "SELECT * FROM user WHERE username = ?";
            $role = "Admin";
        } else if ($role == '2') {
            $sql = "SELECT * FROM user WHERE username = ?";
            $role = "Teacher";
        } else if ($role == '3') {
            $sql = "SELECT * FROM user WHERE username = ?";
            $role = "Student";
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute([$uname]);

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() >= 1) {
            $isAuthenticated = false;

            foreach ($users as $user) {
                $username = $user['username'];
                $password = $user['password'];
                $chkRole = $user['Role'];

                if ($role !== $chkRole) {
                    $em = 'Please Select Valid "Login as"';
                    header("Location: ../login.php?error=$em");
                    exit;
                }

                if ($username === $uname && $pass === $password) {
                    $_SESSION['role'] = $role;
                    $isAuthenticated = true;

                    if ($role == 'Admin') {
                        $id = $user['UserID'];
                        $_SESSION['UserID'] = $id;
                        header("Location: ../admin/index.php");
                        exit;
                    } else if ($role == 'Student') {
                        $sql2 = "SELECT idStudent FROM student WHERE UserID = ?";
                        $stmt2 = $conn->prepare($sql2);
                        $stmt2->execute([$user['UserID']]);
                        if ($stmt2->rowCount() == 1) {
                            $id = $stmt2->fetch(PDO::FETCH_ASSOC);
                            $_SESSION['idStudent'] = $id['idStudent'];
                            header("Location: ../Student/index.php");
                            exit;
                        } else {
                            $em = "An error Occurred";
                            header("Location: ../login.php?error=$em");
                            exit;
                        }
                    } else if ($role == 'Teacher') {
                        $sql2 = "SELECT idTeacher FROM teacher WHERE userID = ?";
                        $stmt2 = $conn->prepare($sql2);
                        $stmt2->execute([$user['UserID']]);
                        if ($stmt2->rowCount() == 1) {
                            $id = $stmt2->fetch(PDO::FETCH_ASSOC);
                            $_SESSION['idTeacher'] = $id['idTeacher'];
                            header("Location: ../Teacher/index.php");
                            exit;
                        } else {
                            $em = "An error Occurred";
                            header("Location: ../login.php?error=$em");
                            exit;
                        }
                    }
                }
            }

            if (!$isAuthenticated) {
                $em = "Incorrect Username or Password";
                header("Location: ../login.php?error=$em");
                exit;
            }
        } else {
            $em = "No user found";
            header("Location: ../login.php?error=$em");
            exit;
        }
    }
} else {
    header("Location: ../login.php");
    exit;
}
?>
