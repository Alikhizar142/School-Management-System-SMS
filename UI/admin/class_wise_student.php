<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_SESSION['UserID']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Admin') {
        include 'data/class.php'; // Updated path to include functions file
        include '../DB_connection.php';

        $classes = getAllClasses($conn);
        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin - Search Students</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
            <link rel="stylesheet" href="../css/style.css">
            <link rel="icon" href="../logo.png">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        </head>

        <body>
            <?php include "inc/navbar.php"; ?>
            <div class="container mt-5">
                <h3 class="mt-3">Select Class and Section</h3> <!-- Heading for selecting class and section -->
                <form method="POST" action="req/student-search.php">
                    <div class="mb-3">
                        <label class="form-label">Class</label>
                        <select name="class" class="form-control">
                            <?php foreach ($classes as $class) { ?>
                                <option value="<?= $class['idClass'] ?>">
                                    <?= $class['name'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php
                    // Check if Sections exist in the class table
                    $hasSections = false;
                    foreach ($classes as $class) {
                        if (!empty($class['Section'])) {
                            $hasSections = true;
                            break;
                        }
                    }
                    // If Sections exist, display dropdown for Section
                    if ($hasSections) {
                        ?>
                        <div class="mb-3">
                            <label class="form-label">Section</label>
                            <select name="Section" class="form-control">
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                            </select>
                        </div>

                    <?php } else { ?>
                        <div class="alert alert-danger" role="alert">
                            No Sections found in the class table.
                        </div>
                    <?php } ?>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>


            </div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                $(document).ready(function () {
                    $("#navLinks li:nth-child(6) a").addClass('active');
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