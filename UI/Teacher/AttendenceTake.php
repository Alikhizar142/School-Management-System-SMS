<?php
session_start();
if (isset($_SESSION['idTeacher']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Teacher') {
        include "../DB_connection.php";
        include "data/student.php";
        include "data/class.php";

        if (!isset($_GET['idClass'])) {
            header("Location: students.php");
            exit;
        }
        $class_id = $_GET['idClass'];
        $students = getStudentsByClass1($class_id, $conn);
        $class = getClassById($class_id, $conn);
        ?>


        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Teacher - Students Attendance</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
            <link rel="stylesheet" href="../css/style.css">
            <link rel="icon" href="../logo.png">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        </head>

        <body>
            <?php include "inc/navbar.php"; ?>
            <div class="container mt-5">
                <h2 class="text-center fw-bold">Attendance for <?= $class['name'] ?></h2>
                <form action="req/attendance_submit.php?idClass=<?= $_GET['idClass'] ?>" method="post">
                    <div class="col-md-2">
                        <label for="attendance_date" style="font-weight: bold;">Select Attendance Date:</label>
                        <input type="date" id="attendance_date" class="form-control" name="attendance_date">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mt-3 n-table">
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
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Present</th>
                                    <th scope="col">Absent</th>
                                    <th scope="col">Late</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                if (!empty($students)) {
                                    foreach ($students as $student) {
                                        ?>
                                        <tr>
                                            <th scope="row"><?= $i ?></th>
                                            <td><?= $student['idStudent'] ?></td>
                                            <td><?= $student['name'] ?></td>
                                            <td><?= $student['username'] ?></td>
                                            <td>
                                                <input type="radio" name="status_<?= $student['idStudent'] ?>" value="P" required>
                                            </td>
                                            <td>
                                                <input type="radio" name="status_<?= $student['idStudent'] ?>" value="A">
                                            </td>
                                            <td>
                                                <input type="radio" name="status_<?= $student['idStudent'] ?>" value="L">
                                            </td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>No students found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Attendance</button>
                </form>
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