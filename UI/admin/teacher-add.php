<?php
session_start();
if (
  isset($_SESSION['UserID']) &&
  isset($_SESSION['role'])
) {

  if ($_SESSION['role'] == 'Admin') {

    include "../DB_connection.php";
    include "data/subject.php";
    $subjects = getAllSubjects($conn);

    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Admin - Add Teacher</title>
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
      <div class="container mt-5">
        <a href="teacher.php" class="btn btn-dark">Go Back</a>

        <form method="post" class="shadow p-3 mt-5 form-w" action="req/teacher-add.php">
          <h3>Add New Teacher</h3>
          <hr>
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
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" name="name">
          </div>
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" class="form-control" name="username">
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group mb-3">
              <input type="text" class="form-control" name="pass" id="passInput">
            </div>

          </div>
          <div class="mb-3">
            <label class="form-label">Address</label>
            <input type="text" class="form-control" name="Address">
          </div>
          <div class="mb-3">
            <label class="form-label">Phone Number</label>
            <input type="text" class="form-control" name="phone_no">
          </div>
          <div class="mb-3">
            <label class="form-label">Education</label>
            <input type="text" class="form-control" name="Education">
          </div>
          <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="text" class="form-control" name="email">
          </div>
          <div class="mb-3">
            <label class="form-label">CNIC</label>
            <input type="text" class="form-control" name="cnic">
          </div>
          <div class="mb-3">
            <label class="form-label">Gender</label><br>
            <input type="radio" value="Male" checked name="gender"> Male
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" value="Female" name="gender"> Female
          </div>
          <div class="mb-3">
            <label class="form-label">Date of Birth</label>
            <input type="date" class="form-control" value="" name="DOB">
          </div>
          <div class="mb-3">
            <label class="form-label">Date of Joining</label>
            <input type="date" class="form-control" value="" name="DOJ">
          </div>
          <div class="mb-3">
            <label class="form-label">Subject</label>
            <div class="row row-cols-5">
              <?php foreach ($subjects as $subject): ?>
                <div class="col">
                  <input type="checkbox" name="subjects[]" value="<?= $subject['idSubject'] ?>">
                  <?= $subject['name'] ?>
                </div>
              <?php endforeach ?>

            </div>
          </div>
          <button type="submit" class="btn btn-primary">Add</button>
        </form>
      </div>

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>

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