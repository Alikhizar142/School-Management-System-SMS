<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if (isset($_SESSION['idTeacher']) && 
    isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Teacher') {

    	

if (isset($_POST['old_pass']) &&
    isset($_POST['new_pass'])   &&
    isset($_POST['c_new_pass']) ) {
    
    include '../../DB_connection.php';
    include "../data/teacher.php";

    $old_pass = $_POST['old_pass'];
    $new_pass = $_POST['new_pass'];
    $c_new_pass = $_POST['c_new_pass'];
    $teacher_id = $_SESSION['idTeacher'];
    $userID=getTeacherById($teacher_id,$conn);
    $userID=$userID['UserID'];
    // echo $userID;

    if (empty($old_pass)) {
		$em  = "Old password is required";
		header("Location: ../pass.php?perror=$em");
		exit;
	}else if (empty($new_pass)) {
		$em  = "New password is required";
		header("Location: ../pass.php?perror=$em");
		exit;
	}else if (empty($c_new_pass)) {
		$em  = "Confirmation password is required";
		header("Location: ../pass.php?perror=$em");
		exit;
	}else if ($new_pass !== $c_new_pass) {
        $em  = "New password and confirm password does not match";
        header("Location: ../pass.php?perror=$em");
        exit;
    }else if (TeacherPasswordVerify($old_pass, $conn, $userID)==0) {
        $em  = "Incorrect old password";
        header("Location: ../pass.php?perror=$em");
        exit;
    }else {
        $sql = "UPDATE user SET
                password = ?
                WHERE UserID=?";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$new_pass, $userID]);
        $sm = "The password has been changed successfully!";
        header("Location: ../pass.php?psuccess=$sm");
        exit;
	}
    
  }else {
  	$em = "An error occurred";
    header("Location: ../pass.php?error=$em");
    exit;
}

  }else {
    header("Location: ../../logout.php");
    exit;
  } 
}else {
	header("Location: ../../logout.php");
	exit;
} 
