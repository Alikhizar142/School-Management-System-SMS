<?php
session_start();
if (
  isset($_SESSION['idTeacher']) &&
  isset($_SESSION['role'])
) {

  if ($_SESSION['role'] == 'Teacher') {
    include "../DB_connection.php";
    include "data/class.php";

    $teacher_id = $_SESSION['idTeacher'];
    $classes = getAllClasses($conn);
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Teachers - Classes</title>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
      <link rel="stylesheet" href="../css/style.css">
      <link rel="icon" href="../logo.png">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>

    <body>
      <?php
      include "inc/navbar.php";
      if ($classes != 0) {
        ?>
        <div class="container mt-5">
          <h3 class="mt-3">All Classes</h3>
          <?php if (isset($_GET['error'])) { ?>
            <div class="alert alert-danger" role="alert">
              <?= $_GET['error'] ?>
            </div>
          <?php } ?>
          <?php if (isset($_GET['success'])) { ?>
            <div class="alert alert-success" role="alert">
              <?= $_GET['success'] ?>
            </div>
          <?php } ?>
          <div class="table-responsive">
            <table class="table table-bordered mt-3 n-table">
              <thead>
                <tr>
                  <th scope="col">Class ID</th>
                  <th scope="col">Class</th>
                  <th scope="col">Section</th>
                </tr>
              </thead>
              <tbody>
                <?php $i = 0;
                foreach ($classes as $class) {
                  ?>
                  <tr>
                    <th scope="row"><?= $class['idClass'] ?></th>
                    <td>
                      <a href="AttendenceTake.php?idClass=<?= $class['idClass'] ?>">
                        <?php echo $class['name']; ?>
                      </a>
                    </td>
                    <td>

                      <?php echo $class['Section']; ?>
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