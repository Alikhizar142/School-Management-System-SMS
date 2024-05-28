<?php 
session_start();
if (isset($_SESSION['UserID']) && 
    isset($_SESSION['role'])     &&
    isset($_GET['idClass'])) {

  if ($_SESSION['role'] == 'Admin') {
     include "../DB_connection.php";
     include "data/class.php";

     $id = $_GET['idClass'];
     if (removeClass($id, $conn)) {
     	$sm = "Successfully deleted!";
        header("Location: class.php?success=$sm");
        exit;
     }else {
        $em = "Unknown error occurred";
        header("Location: class.php?error=$em");
        exit;
     }


  }else {
    header("Location: class.php");
    exit;
  } 
}else {
	header("Location: class.php");
	exit;
} 