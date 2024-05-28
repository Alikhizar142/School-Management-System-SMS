<?php
ini_set('display_errors', 1);
error_reporting(E_ALL); 
session_start();
if (isset($_SESSION['idStudent']) && 
    isset($_SESSION['role']))
     {
   if ($_SESSION['role'] == 'Student') 
    {
       include "../DB_connection.php";
       include "data/student.php";
       include "data/subject.php";
       $idStudent = $_SESSION['idStudent'];

       $student = getStudentById($idStudent, $conn);
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Student - Home</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="icon" href="../logo.png">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php 
        include "inc/navbar.php";
     ?>
     <?php 
        if ($student != 0) {
     ?>
     <div class="container mt-5">
         <div class="card" style="width: 22rem;">
          <div class="card-body">
            <h5 class="card-title text-center">@<?=$student['username']?></h5>
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item">Name: <?=$student['name']?></li>
            <li class="list-group-item">Username: <?=$student['username']?></li>
            <li class="list-group-item">Address: <?=$student['Address']?></li>
            <li class="list-group-item">Date of birth: <?=$student['DOB']?></li>
            <!-- <li class="list-group-item">Email address: <?=$student['email_address']?></li> -->
            <li class="list-group-item">Gender: <?=$student['Gender']?></li>

            <li class="list-group-item">Grade: 
            <?=$student['class_name']?>
            </li>
            <li class="list-group-item">Parent  name: <?=$student['parent_name']?></li>
            <li class="list-group-item">Parent phone number: <?=$student['parent_phoneNo']?></li>
          </ul>
        </div>
     </div>
     <?php 
        }else {
           header("Location: student.php");
          echo "here33";
           exit;
        }
     ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>	
   <script>
        $(document).ready(function(){
             $("#navLinks li:nth-child(1) a").addClass('active');
        });
    </script>
</body>
</html>
<?php 

  }else {
    echo "here1";
    header("Location: ../login.php");
    exit;
  } 
}
else {
  echo "here2";
	header("Location: ../login.php");
	exit;
} 

?>