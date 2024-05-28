<?php
session_start();

// Check if the user is logged in and has the Admin role
if (isset($_SESSION['UserID']) && isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Admin') {

        include "../DB_connection.php";
        include "data/class.php";
        include "data/subject.php"; // Include the file that contains the getAllSubjects function

        // Initialize form field variables
        $fname = '';
        $address = '';
        $pfn = '';
        $pln = '';
        $ppn = '';
        $email = '';
        $cnic = '';

        // Fetch all classes and subjects
        $classes = getAllClasses($conn);
        $subjects = getAllSubjects($conn);

        // Prepopulate form fields if available in GET request
        //    if (isset($_GET['name'])) $fname = $_GET['name'];
        //    if (isset($_GET['address'])) $address = $_GET['address'];
        //    if (isset($_GET['pfn'])) $pfn = $_GET['pfn'];
        //    if (isset($_GET['pln'])) $pln = $_GET['pln'];
        //    if (isset($_GET['ppn'])) $ppn = $_GET['ppn'];
        //    if (isset($_GET['email'])) $email = $_GET['email'];
        //    if (isset($_GET['cnic'])) $cnic = $_GET['cnic'];
        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin - Add Student</title>
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

                <form method="post" class="shadow p-3 mt-5 form-w" action="req/student-add.php">
                    <h3>Add New Student</h3>
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
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Student Name</label>
                            <input type="text" class="form-control"  name="name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control"  name="address">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" name="date_of_birth">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gender</label><br>
                            <input type="radio" value="Male" checked name="gender"> Male
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" value="Female" name="gender"> Female
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Class</label>
                            <select name="Class" class="form-select">
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?= $class['idClass'] ?>-<?= $class['Section'] ?>">
                                        <?= $class['idClass'] ?> - <?= $class['name'] ?> - <?= $class['Section'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <div class="col-md-6 mb-3">
                            <label class="form-label">Year</label>
                            <select name="year" class="form-select">
                                <?php for ($year = 2024; $year <= 2030; $year++): ?>
                                    <option value="<?= $year ?>"><?= $year ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Subjects</label>
                                <div id="subjectList">
                                    <?php foreach ($subjects as $subject): ?>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="subjects[]"
                                                value="<?= $subject['idSubject'] ?>">
                                            <label class="form-check-label"><?= $subject['name'] ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="pass" id="passInput">
                                <button class="btn btn-secondary" id="gBtn">Random</button>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Parent Name</label>
                            <input type="text" class="form-control" name="parent_name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Parent Phone Number</label>
                            <input type="text" class="form-control" name="parent_phone_number">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Parent CNIC</label>
                            <input type="text" class="form-control" name="parent_cnic">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Parent Email Address</label>
                            <input type="text" class="form-control" name="email_address">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                $(document).ready(function () {
                    $("#navLinks li:nth-child(3) a").addClass('active');
                });

                function makePass(length) {
                    var result = '';
                    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                    var charactersLength = characters.length;
                    for (var i = 0; i < length; i++) {
                        result += characters.charAt(Math.floor(Math.random() * charactersLength));
                    }
                    var passInput = document.getElementById('passInput');
                    passInput.value = result;
                }

                var gBtn = document.getElementById('gBtn');
                gBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    makePass(4);
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