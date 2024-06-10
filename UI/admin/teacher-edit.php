<?php
session_start();
if (
  isset($_SESSION['UserID']) &&
  isset($_SESSION['role']) &&
  isset($_GET['idTeacher'])
) {

  if ($_SESSION['role'] == 'Admin') {

    include "../DB_connection.php";
    include "data/subject.php";
    include "data/class.php";
    include "data/teacher.php";
    $subjects = getAllSubjects($conn);


    $teacher_id = $_GET['idTeacher'];
    $teacher = getTeacherById($teacher_id, $conn);

    if ($teacher == 0) {
      header("Location: teacher.php");
      exit;
    }


    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Admin - Edit Teacher</title>
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

        <form method="post" class="shadow p-3 mt-5 form-w" action="req/teacher-edit.php">
          <h3>Edit Teacher</h3>
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
            <input type="text" class="form-control" value="<?= $teacher['name'] ?>" name="name">
          </div>
          <div class="mb-3">
            <label class="form-label">Username</label>
            <?php
            $username = getUsername($teacher['userID'], $conn);
            if ($username == 0) {
              $username = "";
            }
            ?>
            <input type="text" class="form-control" value="<?= $username ?>" name="username">
          </div>
          <div class="mb-3">
            <label class="form-label">address</label>
            <input type="text" class="form-control" value="<?= $teacher['Address'] ?>" name="Address">
          </div>
          <div class="mb-3">
            <label class="form-label">Date of birth</label>
            <input type="date" class="form-control" value="<?= $teacher['DOB'] ?>" name="DOB">
          </div>
          <div class="mb-3">
            <label class="form-label">Phone number</label>
            <input type="text" class="form-control" value="<?= $teacher['phone_no'] ?>" name="phone_no">
          </div>
          <div class="mb-3">
            <label class="form-label">Education</label>
            <input type="text" class="form-control" value="<?= $teacher['Education'] ?>" name="Education">
          </div>
          <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="text" class="form-control" value="<?= $teacher['email'] ?>" name="email">
          </div>
          <div class="mb-3">
            <label class="form-label">Gender</label><br>
            <input type="radio" value="Male" <?php if ($teacher['gender'] == 'Male' || $teacher['gender'] == 'male')
              echo 'checked'; ?> name="gender"> Male
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" value="Female" <?php if ($teacher['gender'] == 'Female' || $teacher['gender'] == 'female')
              echo 'checked'; ?> name="gender">
            Female
          </div>
          <input type="text" value="<?= $teacher['idTeacher'] ?>" name="idTeacher" hidden>

          <div class="mb-3">
            <label class="form-label">Subject</label>
            <div class="row row-cols-5">
              <?php

              $subject_ids = getTeachersSubjects($teacher['idTeacher'], $conn);

              foreach ($subjects as $subject) {
                $checked = 0;
                if (is_array($subject_ids) && count($subject_ids) > 0) {
                  foreach ($subject_ids as $subject_id) {
                    if ($subject_id['idSubject'] == $subject['idSubject']) {
                      $checked = 1;
                    }
                  }
                }
                ?>
                <div class="col">
                  <input type="checkbox" name="subjects[]" <?php if ($checked)
                    echo "checked"; ?>
                    value="<?= $subject['idSubject'] ?>">
                  <?= $subject['name'] ?>
                </div>
              <?php }

              ?>

            </div>
          </div>
          <button type="submit" class="btn btn-primary">
            Update</button>
        </form>

        <form method="post" class="shadow p-3 my-5 form-w" action="req/teacher-change.php" id="change_password">
          <h3>Change Password</h3>
          <hr>
          <?php if (isset($_GET['perror'])) { ?>
            <div class="alert alert-danger" role="alert">
              <?= $_GET['perror'] ?>
            </div>
          <?php } ?>
          <?php if (isset($_GET['psuccess'])) { ?>
            <div class="alert alert-success" role="alert">
              <?= $_GET['psuccess'] ?>
            </div>
          <?php } ?>

          <div class="mb-3">
            <div class="mb-3">
              <label class="form-label">Admin password</label>
              <input type="password" class="form-control" name="admin_pass">
            </div>

            <label class="form-label">New password </label>
            <div class="input-group mb-3">
              <input type="text" class="form-control" name="new_pass" id="passInput">
            </div>

          </div>
          <input type="text" value="<?= $teacher['idTeacher'] ?>" name="idTeacher" hidden>

          <div class="mb-3">
            <label class="form-label">Confirm new password </label>
            <input type="text" class="form-control" name="c_new_pass" id="passInput2">
          </div>
          <button type="submit" class="btn btn-primary">
            Change</button>
        </form>
      </div>


    </body>

    </html>
    <?php

  } else {
    header("Location: teacher.php");
    exit;
  }
} else {
  header("Location: teacher.php");
  exit;
}

?>