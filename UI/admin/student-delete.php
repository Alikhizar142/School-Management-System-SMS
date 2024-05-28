<?php 
session_start();
if (isset($_SESSION['UserID']) && 
    isset($_SESSION['role'])     &&
    isset($_GET['idStudent'])) {

  if ($_SESSION['role'] == 'Admin') {
     include "../DB_connection.php";
     include "data/student.php";

     $id = $_GET['idStudent'];
     if (removeStudent($id, $conn)) {
     	$sm = "Successfully deleted!";
        header("Location: student.php?success=$sm");
        exit;
     }else {
        $em = "Unknown error occurred";
        header("Location: student.php?error=$em");
        exit;
     }


  }else {
    header("Location: student.php");
    exit;
  } 
}else {
	header("Location: teacher.php");
	exit;
} 