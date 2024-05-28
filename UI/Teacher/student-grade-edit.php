<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// Check if the user is logged in as a teacher
if (isset($_SESSION['idTeacher']) && isset($_SESSION['role']) && $_SESSION['role'] == 'Teacher') {
    // Include necessary files
    $db_connection = "../DB_connection.php";
    $data_student = "data/student.php";
    $data_class = "data/class.php";
    $data_subject = "data/subject.php";
    $data_teacher = "data/teacher.php";

    // Check if included files exist
    if (file_exists($db_connection) && file_exists($data_student) && file_exists($data_class) && file_exists($data_subject) && file_exists($data_teacher)) {
        include $db_connection;
        include $data_student;
        include $data_class;
        include $data_subject;
        include $data_teacher;

        // Check if idStudent is set in the URL
        if (!isset($_GET['idStudent'])) {
            header("Location: students.php");
            exit;
        }

        // Get student details
        $idStudent = $_GET['idStudent'];
        $student = getStudentById($idStudent, $conn);
        $idTeacher = $_SESSION['idTeacher'];
        $teacher = getTeacherById($idTeacher, $conn);
        
        // Get the subjects the student is enrolled in
        $enrolled_subjects = getSubjectsByStudentId($idStudent, $conn);

        // Get exams from the exam table
        $exams = $conn->query("SELECT idExam, ExamType FROM exam")->fetchAll(PDO::FETCH_ASSOC);
        $classID = getClassIDByName($student['class_name'], $conn);
       
        // Define a variable to hold the success message
        $success_message = "";

        // Get existing marks for the student
        $existing_marks = $conn->query("SELECT SubID, ExamId, TotalMarks, ObtainedMarks FROM marks WHERE StdId = $idStudent")->fetchAll(PDO::FETCH_ASSOC);
        $marks_data = [];
        foreach ($existing_marks as $mark) {
            $marks_data[$mark['SubID']] = $mark;
        }
        
    } else {
        // Handle missing files
        echo "Some required files are missing.";
        exit;
    }
} else {
    // Redirect if user is not logged in as a teacher
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Marks for <?php echo $student['name']; ?></title>
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
            <h2>Edit Marks for <?php echo $student['name']; ?></h2>
            <a href="students_of_class.php?idClass=<?= $classID["idClass"] ?>" class="btn btn-secondary">Back</a>
        </div>
        <div class="card shadow p-4">
            <?php 
            // Check if there is a success message
            if (isset($_GET['success']) && !empty($_GET['success'])) { 
                $success_message = $_GET['success'];
            ?>
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
                <h5 class="text-center">Edit Marks</h5>
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
                        <?php foreach($enrolled_subjects as $subject) { 
                            $totalMarks = isset($marks_data[$subject['idSubject']]) ? $marks_data[$subject['idSubject']]['TotalMarks'] : '';
                            $obtainedMarks = isset($marks_data[$subject['idSubject']]) ? $marks_data[$subject['idSubject']]['ObtainedMarks'] : '';
                        ?>
                            <tr>
                                <td><?php echo $subject['idSubject']; ?></td>
                                <td><?php echo $subject['name']; ?></td>
                                <td>
                                    <label for="total-marks-<?php echo $subject['idSubject']; ?>" class="form-label visually-hidden">Total Marks</label>
                                    <input type="number" min="0" max="100" class="form-control" name="total-marks-<?php echo $subject['idSubject']; ?>" id="total-marks-<?php echo $subject['idSubject']; ?>" value="<?php echo $totalMarks; ?>">
                                </td>
                                <td>
                                    <label for="marks-<?php echo $subject['idSubject']; ?>" class="form-label visually-hidden">Obtain Marks</label>
                                    <input type="number" min="0" max="100" class="form-control" name="marks-<?php echo $subject['idSubject']; ?>" id="marks-<?php echo $subject['idSubject']; ?>" value="<?php echo $obtainedMarks; ?>">
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="text-center">
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
