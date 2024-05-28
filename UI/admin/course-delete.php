<?php 
session_start();
if (isset($_SESSION['UserID']) && 
    isset($_SESSION['role'])     &&
    isset($_GET['idSubject'])) {

  if ($_SESSION['role'] == 'Admin') {
     include "../DB_connection.php";
     include "data/subject.php";

     $id = $_GET['idSubject'];
     if (removeCourse($id, $conn)) {
     	$sm = "Successfully deleted!";
        header("Location: course.php?success=$sm");
        exit;
     }else {
        $em = "Unknown error occurred";
        header("Location: course.php?error=$em");
        exit;
     }


  }else {
    header("Location: course.php");
    exit;
  } 
}else {
	header("Location: course.php");
	exit;
} 