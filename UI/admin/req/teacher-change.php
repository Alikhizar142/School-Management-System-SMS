<?php
session_start();
if (
  isset($_SESSION['UserID']) &&
  isset($_SESSION['role'])
) {

  if ($_SESSION['role'] == 'Admin') {


    if (
      isset($_POST['admin_pass']) &&
      isset($_POST['new_pass']) &&
      isset($_POST['c_new_pass']) &&
      isset($_POST['idTeacher'])
    ) {

      include '../../DB_connection.php';
      include "../data/teacher.php";
      include "../data/admin.php";

      $admin_pass = $_POST['admin_pass'];
      $new_pass = $_POST['new_pass'];
      $c_new_pass = $_POST['c_new_pass'];

      $teacher_id = $_POST['idTeacher'];
      $id = $_SESSION['UserID'];


      $data = 'idTeacher=' . $teacher_id . '#change_password';
      $Cond = 1;
      $admin_O_pass = "";
      $sql = "select password from user where UserID=?";

      $stmt = $conn->prepare($sql);
      $stmt->execute([$id]);

      if ($stmt->rowCount() > 0) {
        // Fetch the result as an associative array
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $admin_O_pass = $row["password"];
      }

      if (empty($admin_pass)) {
        $em = "Admin password is required";
        header("Location: ../teacher-edit.php?perror=$em&$data");
        exit;
      } else if (empty($new_pass)) {
        $em = "New password is required";
        header("Location: ../teacher-edit.php?perror=$em&$data");
        exit;
      } else if (empty($c_new_pass)) {
        $em = "Confirmation password is required";
        header("Location: ../teacher-edit.php?perror=$em&$data");
        exit;
      } else if ($new_pass !== $c_new_pass) {
        $em = "New password and confirm password does not match";
        header("Location: ../teacher-edit.php?perror=$em&$data");
        exit;
      } else if ($admin_pass !== $admin_O_pass) {
        $em = "Incorrect admin password";
        header("Location: ../teacher-edit.php?perror=$em&$data");
        exit;
      } else {
        $sql2 = "UPDATE teacher t
                INNER JOIN user u ON t.userID = u.UserID
                SET u.password=?
                WHERE t.idTeacher = ?";

        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute([$new_pass, $teacher_id]);
        $sm2 = "The password has been changed successfully!";


        header("Location: ../teacher-edit.php?psuccess=$sm2&$data");
        exit;
      }

    } else {
      $em = "An error occurred";
      header("Location: ../teacher-edit.php?perror=$em&$data");
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
