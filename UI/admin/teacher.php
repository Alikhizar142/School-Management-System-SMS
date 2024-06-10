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
    $teachers = getAllTeachers($conn);
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

    <body>
      <?php
      include "inc/navbar.php";
      if ($teachers != 0) {
        ?>
        <div class="container mt-5">
          <a href="teacher-add.php" class="btn btn-dark">Add New Teacher</a>

          <form action="teacher-search.php" class="mt-3 n-table" method="get">
            <div class="input-group mb-3">
              <input type="text" class="form-control" name="searchKey"
                placeholder="Search by Name...">
              <button class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i>
              </button>
            </div>
          </form>

          <?php if (isset($_GET['error'])) { ?>
            <div class="alert alert-danger mt-3 n-table" role="alert">
              <?= $_GET['error'] ?>
            </div>
          <?php } ?>

          <?php if (isset($_GET['success'])) { ?>
            <div class="alert alert-info mt-3 n-table" role="alert">
              <?= $_GET['success'] ?>
            </div>
          <?php } ?>

          <div class="table-responsive">
            <table class="table table-bordered mt-3 n-table">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">ID</th>
                  <th scope="col">Name</th>
                  <th scope="col">Username</th>
                  <th scope="col">Subject</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i = 0;
                foreach ($teachers as $teacher) {
                  $i++; ?>
                  <tr>
                    <th scope="row"><?= $i ?></th>
                    <td><?= $teacher['idTeacher'] ?></td>
                    <td><a href="teacher-view.php?idTeacher=<?= $teacher['idTeacher'] ?>">
                        <?= $teacher['name'] ?></a></td>
                    <td><?= $teacher['username'] ?></td>
                    <td>
                      <?php

                      $subjects = getTeachersSubjects($teacher['idTeacher'], $conn);

                      if ($subjects !== 0) {
                        foreach ($subjects as $subject) {
                          echo $subject['subject_name'] . "<br>";
                        }
                      } else {
                        echo "No data found.";
                      }

                      ?>
                    </td>
                    <td>
                      <a href="teacher-edit.php?idTeacher=<?= $teacher['idTeacher'] ?>" class="btn btn-warning">Edit</a>
                      <a href="teacher-delete.php?idTeacher=<?= $teacher['idTeacher'] ?>" class="btn btn-danger">Delete</a>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        <?php } else { ?>
          <div class="alert alert-info .w-450 m-5" role="alert">
            Empty!
          </div>
        <?php } ?>
      </div>

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
    header("Location: ../login.php");
    exit;
  }
} else {
  header("Location: ../login.php");
  exit;
}

?>