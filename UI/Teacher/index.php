<?php
session_start();
if (
  isset($_SESSION['idTeacher']) &&
  isset($_SESSION['role'])
) {

  if ($_SESSION['role'] == 'Teacher') {
    include "../DB_connection.php";
    include "data/teacher.php";
    include "data/subject.php";


    $teacher_id = $_SESSION['idTeacher'];
    $teacher = getTeacherById($teacher_id, $conn);
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Teacher - Home</title>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
      <link rel="stylesheet" href="../css/style.css">
      <link rel="icon" href="../logo.png">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>

    <body>
      <?php
      include "inc/navbar.php";

      if ($teacher != 0) {
        ?>
        <div class="container mt-5">
          <div class="card" style="width: 22rem;">
            <div class="card-body">
              <h5 class="card-title text-center">@<?= $teacher['username'] ?></h5>
            </div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">Name: <?= $teacher['name'] ?></li>
              <li class="list-group-item">Username: <?= $teacher['username'] ?></li>
              <li class="list-group-item">Teacher id: <?= $teacher['idTeacher'] ?></li>
              <li class="list-group-item">Address: <?= $teacher['Address'] ?></li>
              <li class="list-group-item">Date of birth: <?= $teacher['DOB'] ?></li>
              <li class="list-group-item">Phone number: <?= $teacher['phone_no'] ?></li>
              <li class="list-group-item">Qualification: <?= $teacher['Education'] ?></li>
              <li class="list-group-item">Email address: <?= $teacher['email'] ?></li>
              <li class="list-group-item">Gender: <?= $teacher['gender'] ?></li>
              <li class="list-group-item">Date of joined: <?= $teacher['Date_of_joining'] ?></li>

              <li class="list-group-item">Subject:
                <?php
                $subjects = getTeachersSubjects($teacher['idTeacher'], $conn);

                if ($subjects !== 0) {
                  $i = 1;
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
          </div>
        </div>
        <?php
      } else {
        echo "h3";
        // header("Location: logout.php?error=An error occurred");
        // exit;
      }
      ?>

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
      <script>
        $(document).ready(function () {
          $("#navLinks li:nth-child(1) a").addClass('active');
        });
      </script>
    </body>

    </html>
    <?php

  } else {
    echo "h2";
    // header("Location: ../login.php");
    // exit;
  }
} else {
  header("Location: ../login.php");
  exit;
}

?>