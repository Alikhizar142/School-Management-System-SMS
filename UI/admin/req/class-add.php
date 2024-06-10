<?php 
session_start();
if (isset($_SESSION['UserID']) && 
    isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Admin') {
    	

if (isset($_POST['name']) &&
    isset($_POST['Section'])) {
    
    include '../../DB_connection.php';

    $Section = $_POST['Section'];
    $name = $_POST['name'];

  if (empty($Section)) {
		$em  = "Section is required";
		header("Location: ../class-add.php?error=$em");
		exit;
	}else if (empty($name)) {
		$em  = "name is required";
		header("Location: ../class-add.php?error=$em");
		exit;
	}else {
        // check if the class already exists
        $sql_check = "SELECT * FROM class 
                      WHERE name=? AND Section=?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->execute([$name, $Section]);
        if ($stmt_check->rowCount() > 0) {
           $em  = "The class already exists";
           header("Location: ../class-add.php?error=$em");
           exit;
        }else {
          $sql  = "INSERT INTO
                 class(name, Section)
                 VALUES(?,?)";
          $stmt = $conn->prepare($sql);
          $stmt->execute([$name, $Section]);
          $sm = "New class created successfully";
          header("Location: ../class-add.php?success=$sm");
          exit;
        } 
	}
    
  }else {
  	$em = "An error occurred";
    header("Location: ../class-add.php?error=$em");
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
