<?php 
session_start();
if (isset($_SESSION['idTeacher']) && isset($_SESSION['role']) && $_SESSION['role'] == 'Teacher') {

    include "../DB_connection.php";
    include "data/student.php";
    include "data/class.php";

    if (!isset($_GET['idClass'])) {
        header("Location: students.php");
        exit;
    }
    
    $idClass = $_GET['idClass'];
    $students = getStudentsByClass1($idClass, $conn);
    $class = getClassById($idClass, $conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher - Students</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php include "inc/navbar.php"; ?>

    <div class="container mt-5">
        <a href="students.php" class="btn btn-secondary mb-3"><i class="fa fa-arrow-left"></i> Back to Classes</a>
        <?php if ($students != 0) { ?>
        <div class="table-responsive">
            <table class="table table-bordered mt-3 n-table">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Username</th>
                        <th scope="col">Class</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1; 
                    foreach ($students as $student) { ?>
                    <tr>
                        <th scope="row"><?= $i++ ?></th>
                        <td><?= $student['idStudent'] ?></td>
                        <td>
                            <a href="student-grade.php?idStudent=<?= $student['idStudent'] ?>">
                                <?= $student['name'] ?>
                            </a>
                        </td>
                        <td><?= $student['username'] ?></td>
                        <td><?= $class['name'] ?></td>
                        <td>
                            <a href="student-grade-edit.php?idStudent=<?= $student['idStudent'] ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="student-grade.php?idStudent=<?= $student['idStudent'] ?>" class="btn btn-success btn-sm">Add</a>
                            <a href="student_result.php?idStudent=<?= $student['idStudent'] ?>" class="btn btn-info btn-sm">Print</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php } else { ?>
        <div class="alert alert-info mt-5" role="alert">
            No students found in this class!
        </div>
        <?php } ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>    
    <script>
        $(document).ready(function(){
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
?>
