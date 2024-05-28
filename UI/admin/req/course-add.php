<?php 
session_start();
if (isset($_SESSION['UserID']) && 
    isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Admin') {
    	

if (isset($_POST['course_name'])) {
    
    include '../../DB_connection.php';

    $course_name = $_POST['course_name'];
  

  if (empty($course_name))
   {
		$em  = "course name is required";
		header("Location: ../course-add.php?error=$em");
		exit;
	}
  else {
        // check if the class already exists
        $sql_check = "SELECT * FROM subject 
                      WHERE name=? ";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->execute([$course_name]);
        if ($stmt_check->rowCount() > 0) {
           $em  = "The course is already exists";
           header("Location: ../course-add.php?error=$em");
           exit;
        }else {
          $sql  = "INSERT INTO
                 subject(name)
                 VALUES(?)";
          $stmt = $conn->prepare($sql);
          $stmt->execute([$course_name]);
          $sm = "New course created successfully";
          header("Location: ../course-add.php?success=$sm");
          exit;
        } 
	}
    
  }
  
  else {
  	$em = "An error occurred";
    header("Location: ../course-add.php?error=$em");
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
