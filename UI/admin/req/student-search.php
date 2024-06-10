<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../../DB_connection.php';

if (isset($_SESSION['UserID']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Admin') {
        include '../data/student.php'; 

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $class_id = $_POST['class'];
            $students = getStudentsByClass($class_id, $conn); 
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Student Search Results</title>
    <link rel="icon" href="../../logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
</head>
<body>
    <div class="container mt-5">
        <a href="../class_wise_student.php" class="btn btn-dark">Go Back</a>
        <?php if (!empty($students)) { ?>
            <h3 class="mt-3 text-center">Student Search Results </h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Parent Name</th>
                        <th>Parent Phone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student) { ?>
                        <tr>
                            <td><?= $student['idStudent'] ?></td>
                            <td><?= $student['name'] ?></td>
                            <td><?= $student['parent_name'] ?></td>
                            <td><?= $student['phone_no'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="alert alert-info" role="alert">
                No students found for the selected class.
            </div>
        <?php } ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
    } 
    else {
        header("Location: ../login.php");
        exit;
    }
?>
