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
      <?php include "inc/navbar.php"; ?>

      <div class="container mt-5">
        <a href="index.php" class="btn btn-secondary mb-3">
          <i class="fa fa-arrow-left"></i> Back to Dashboard
        </a>
        <h3 class="mt-3">Select Your Class</h3>
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
        <?php if ($classes != 0) { ?>
          <div class="table-responsive">
            <table class="table table-bordered mt-3 n-table">
              <thead class="table-dark">
                <tr>
                  <th scope="col">Class ID</th>
                  <th scope="col">Class</th>
                  <th scope="col">Section</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($classes as $class) { ?>
                  <tr>
                    <th scope="row"><?= $class['idClass'] ?></th>
                    <td>
                      <a href="students_of_class.php?idClass=<?= $class['idClass'] ?>">
                        <?= $class['name'] ?>
                      </a>
                    </td>
                    <td><?= $class['Section'] ?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        <?php } else { ?>
          <div class="alert alert-info mt-5" role="alert">
            No classes found!
          </div>
        <?php } ?>
      </div>

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
    header("Location: ../login.php");
    exit;
  }
} else {
  header("Location: ../login.php");
  exit;
}
?>
