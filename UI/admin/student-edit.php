<?php 
session_start();
if (isset($_SESSION['UserID']) && 
    isset($_SESSION['role']) && 
    isset($_GET['idStudent'])) {

    if ($_SESSION['role'] == 'Admin') {

        include "../DB_connection.php";
        include "data/subject.php";
        include "data/student.php";
        $subjects = getAllSubjects($conn);

        $student_id = $_GET['idStudent'];
        $student = getStudentById($student_id, $conn);

        if ($student == 0) {
            header("Location: student.php");
            exit;
        }
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit Student</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php include "inc/navbar.php"; ?>
    <div class="container mt-5">
        <a href="student.php" class="btn btn-dark">Go Back</a>

        <form method="post" class="shadow p-3 mt-5 form-w" action="req/student-edit.php">
            <h3>Edit Student Info</h3>
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
                <label class="form-label">Student Name</label>
                <input type="text" class="form-control" value="<?= $student['name'] ?>" name="name">
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" class="form-control" value="<?= $student['Address'] ?>" name="address">
            </div>
            <div class="mb-3">
                <label class="form-label">Date of Birth</label>
                <input type="date" class="form-control" value="<?= $student['DOB'] ?>" name="DOB">
            </div>
            <div class="mb-3">
                <label class="form-label">Gender</label><br>
                <input type="radio" value="Male" <?= $student['Gender'] == 'Male' ? 'checked' : '' ?> name="gender"> Male
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" value="Female" <?= $student['Gender'] == 'Female' ? 'checked' : '' ?> name="gender"> Female
            </div>

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" value="<?= $student['username'] ?>" name="username">
            </div>
            <input type="hidden" value="<?= $student['idStudent'] ?>" name="idStudent">

            <div class="mb-3">
                <label class="form-label">Class</label>
                <input type="text" class="form-control" value="<?= $student['class_name'] ?>" name="class_name">
            </div>

            <div class="mb-3">
                <label class="form-label">Parent Name</label>
                <input type="text" class="form-control" value="<?= $student['parent_name'] ?>" name="parent_name">
            </div>
            <div class="mb-3">
                <label class="form-label">Parent Phone Number</label>
                <input type="text" class="form-control" value="<?= $student['parent_phoneNo'] ?>" name="parent_phoneNo">
            </div>
            <div class="mb-3">
                <label class="form-label">Parent Email</label>
                <input type="text" class="form-control" value="<?= $student['parent_email'] ?>" name="parent_email">
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>

        <form method="post" class="shadow p-3 my-5 form-w" action="req/student-change.php" id="change_password">
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
                <label class="form-label">Admin Password</label>
                <input type="password" class="form-control" name="admin_pass">
            </div>
            <div class="mb-3">
                <label class="form-label">New Password</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="new_pass" id="passInput">
                </div>
            </div>
            <input type="hidden" value="<?= $student['idStudent'] ?>" name="idStudent">
            <div class="mb-3">
                <label class="form-label">Confirm New Password</label>
                <input type="text" class="form-control" name="c_new_pass" id="passInput2">
            </div>
            <button type="submit" class="btn btn-primary">Change</button>
        </form>
    </div>

</body>
</html>
<?php 
    } else {
        header("Location: student.php");
        exit;
    } 
} else {
    header("Location: student.php");
    exit;
} 
?>
