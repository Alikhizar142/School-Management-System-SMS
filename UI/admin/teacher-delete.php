<?php
session_start();
if (
   isset($_SESSION['UserID']) &&
   isset($_SESSION['role']) &&
   isset($_GET['idTeacher'])
) {

   if ($_SESSION['role'] == 'Admin') {
      include "../DB_connection.php";
      include "data/teacher.php";

      $id = $_GET['idTeacher'];
      if (removeTeacher($id, $conn)) {
         $sm = "Successfully deleted!";
         header("Location: teacher.php?success=$sm");
         exit;
      } else {
         $em = "Unknown error occurred";
         header("Location: teacher.php?error=$em");
         exit;
      }
   } else {
      header("Location: teacher.php");
      exit;
   }
} else {
   header("Location: teacher.php");
   exit;
}