<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if (isset($_SESSION['idTeacher']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Teacher') {
       include "../DB_connection.php";
       include "data/student.php";
       include "data/class.php";
       include "data/subject.php";
       include "data/teacher.php";

       if (!isset($_GET['idStudent'])) {
           header("Location: students.php");
           exit;
       }

       $idStudent = $_GET['idStudent'];
       $student = getStudentById($idStudent, $conn);
       $idTeacher = $_SESSION['idTeacher'];
       $teacher = getTeacherById($idTeacher, $conn);
       
       $idSubject = 0;
       if (isset($_POST['idSubject'])) {
           $idSubject = $_POST['idSubject'];
       }

       // Get the subjects the student is enrolled in
       $enrolled_subjects = getSubjectsByStudentId($idStudent, $conn);

       // Get exams from the exam table
       $exams = $conn->query("SELECT idExam, ExamType FROM exam")->fetchAll(PDO::FETCH_ASSOC);
       $classID = getClassIDByName($student['class_name'], $conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adding Marks for <?php echo $student['name']; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php include "inc/navbar.php"; ?>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Adding Marks for <?php echo $student['name']; ?></h2>
            <a href="students_of_class.php?idClass=<?= $classID["idClass"] ?>" class="btn btn-secondary">Back</a>
        </div>
        <div class="card shadow p-4">
            <?php if (!empty($success_message)) { ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $success_message; ?>
                </div>
            <?php } ?>
            <form method="post" action="req/save_marks.php?idStudent=<?php echo $student['idStudent']; ?>">
                <div class="mb-3">
                    <ul class="list-group">
                        <li class="list-group-item"><b>ID: </b> <?php echo $student['idStudent'] ?></li>
                        <li class="list-group-item"><b>First Name: </b> <?php echo $student['name'] ?></li>
                        <li class="list-group-item"><b>Class: </b> 
                        <?php echo $student['class_name'] ?></li>
                    </ul>
                </div>
                <h5 class="text-center">Add Marks</h5>
                <label class="form-label">Exam</label>
                <select class="form-control" name="exam">
                    <?php foreach($exams as $exam) { ?>
                        <option value="<?php echo $exam['idExam']; ?>"><?php echo $exam['ExamType']; ?></option>
                    <?php } ?>
                </select>
                <br>
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Subject ID</th>
                            <th>Subject Name</th>
                            <th>Total Marks</th>
                            <th>Obtain Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($enrolled_subjects as $subject) { ?>
                            <tr>
                                <td><?php echo $subject['idSubject']; ?></td>
                                <td><?php echo $subject['name']; ?></td>
                                <td>
                                    <label for="total-marks-<?php echo $subject['idSubject']; ?>" class="form-label visually-hidden">Total Marks</label>
                                    <input type="number" min="0" max="100" class="form-control" name="total-marks-<?php echo $subject['idSubject']; ?>" id="total-marks-<?php echo $subject['idSubject']; ?>" value="<?php echo isset($_POST['total-marks-' . $subject['idSubject']]) ? $_POST['total-marks-' . $subject['idSubject']] : ''; ?>">
                                </td>
                                <td>
                                    <label for="marks-<?php echo $subject['idSubject']; ?>" class="form-label visually-hidden">Obtain Marks</label>
                                    <input type="number" min="0" max="100" class="form-control" name="marks-<?php echo $subject['idSubject']; ?>" id="marks-<?php echo $subject['idSubject']; ?>" value="<?php echo isset($_POST['marks-' . $subject['idSubject']]) ? $_POST['marks-' . $subject['idSubject']] : ''; ?>">
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>  
        </div>
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
} else {
    header("Location: ../login.php");
    exit;
} 
?>
