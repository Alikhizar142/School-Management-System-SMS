<?php
session_start();
if (
  isset($_SESSION['UserID']) &&
  isset($_SESSION['role'])
) {

  if ($_SESSION['role'] == 'Admin') {
    include "../DB_connection.php";
    include "data/student.php";
    include "data/subject.php";

    if (isset($_GET['student_id'])) {

      $student_id = $_GET['student_id'];

      $student = getStudentById($student_id, $conn);
      ?>
      <!DOCTYPE html>
      <html lang="en">

      <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin - Student</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/style.css">
        <link rel="icon" href="../logo.png">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      </head>

      <body>
        <?php
        include "inc/navbar.php";
        if ($student != 0) {
          ?>
          <div class="container mt-5">
            <div class="card" style="width: 22rem;">
              <div class="card-body">
                <h5 class="card-title text-center">@<?= $student['username'] ?></h5>
              </div>
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><b>Student ID</b>: <?= $student['idStudent'] ?></li>
                <li class="list-group-item"><b>Name</b>: <?= $student['name'] ?></li>
                <li class="list-group-item"><b>Username</b>: <?= $student['username'] ?></li>
                <li class="list-group-item"><b>Address</b>: <?= $student['Address'] ?></li>
                <li class="list-group-item"><b>Date of birth</b>: <?= $student['DOB'] ?></li>
                <li class="list-group-item"><b>Gender</b>: <?= $student['Gender'] ?></li>
                <li class="list-group-item"> <b>Grade</b>:<?= $student['subjects'] ?></li>
                <li class="list-group-item"> <b>Class</b>: <?= $student['class_name'] ?></li>
                <li class="list-group-item"><b>Section</b>:<?= $student['Section'] ?></li>
                <li class="list-group-item"><b>Parent name</b>: <?= $student['parent_name'] ?></li>
                <li class="list-group-item"><b>Parent Email</b>: <?= $student['parent_email'] ?></li>
                <li class="list-group-item"><b>Parent phone number</b>: <?= $student['parent_phoneNo'] ?></li>
                <li class="list-group-item"><b>Parent CNIC No</b>: <?= $student['parent_cnic'] ?></li>
              </ul>
              <div class="card-body">
                <a href="student.php" class="card-link">Go Back</a>
              </div>
            </div>
          </div>
        <?php
        } else {
          header("Location: student.php");
          exit;
        }
        ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
          $(document).ready(function () {
            $("#navLinks li:nth-child(3) a").addClass('active');
          });
        </script>

      </body>

      </html>
    <?php

    } else {
      header("Location: student.php");
      exit;
    }

  } else {
    header("Location: ../login.php");
    exit;
  }
} else {
  header("Location: ../login.php");
  exit;
}

?>