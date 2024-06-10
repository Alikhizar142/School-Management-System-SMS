<?php
session_start();
if (
  isset($_SESSION['UserID']) &&
  isset($_SESSION['role'])
) {

  if ($_SESSION['role'] == 'Admin') {
    include "../DB_connection.php";
    include "data/teacher.php";
    include "data/subject.php";
    include "data/class.php";

    if (isset($_GET['idTeacher'])) {

      $teacher_id = $_GET['idTeacher'];

      $teacher = getTeacherById($teacher_id, $conn);


      ?>
      <!DOCTYPE html>
      <html lang="en">

      <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin - Teachers</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/style.css">
        <link rel="icon" href="../logo.png">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      </head>

      <body>s
        <?php
        include "inc/navbar.php";
        if ($teacher != 0) {
          ?>
          <div class="container mt-5">
            <div class="card" style="width: 22rem;">
              <div class="card-body">
                <h5 class="card-title text-center">@<?= $teacher['name'] ?></h5>
              </div>
              <ul class="list-group list-group-flush">
                <li class="list-group-item">First name: <?= $teacher['name'] ?></li>
                <li class="list-group-item">Username: 
                  <?php
                  $username = getUsername($teacher['userID'], $conn);
                  if ($username !== 0) {
                    echo $username;
                  } else {
                    echo "No data found.";
                  }
                  ?>
                </li>

                <li class="list-group-item">Employee number: <?= $teacher['idTeacher'] ?></li>
                <li class="list-group-item">Address: <?= $teacher['Address'] ?></li>
                <li class="list-group-item">Date of birth: <?= $teacher['DOB'] ?></li>
                <li class="list-group-item">Phone number: <?= $teacher['CNIC_NO'] ?></li>
                <li class="list-group-item">Qualification: <?= $teacher['Education'] ?></li>
                <li class="list-group-item">Email address: <?= $teacher['email'] ?></li>
                <li class="list-group-item">Gender: <?= $teacher['gender'] ?></li>
                <li class="list-group-item">Date of joined: <?= $teacher['Date_of_joining'] ?></li>

                <li class="list-group-item">Subjects:
                  <?php
                  $subjects = getTeachersSubjects($teacher['idTeacher'], $conn);

                  if ($subjects !== 0) {
                    $i=1;
                    foreach ($subjects as $subject) {
                      if ($i > 1) {
                      echo ",";
                      }
                      echo $subject['subject_name'];
                      $i++;
                    }
                  } else {
                    echo "No data found.";
                  }
                  ?>
                </li>
              </ul>
              <div class="card-body">
                <a href="teacher.php" class="card-link">Go Back</a>
              </div>
            </div>
          </div>
          <?php
        } else {
          header("Location: teacher.php");
          exit;
        }
        ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
          $(document).ready(function () {
            $("#navLinks li:nth-child(2) a").addClass('active');
          });
        </script>

      </body>

      </html>
      <?php

    } else {
      header("Location: teacher.php");
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