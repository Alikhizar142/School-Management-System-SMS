<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

if (isset($_SESSION['idStudent']) && isset($_SESSION['role']) && $_SESSION['role'] == 'Student') {
    include "../DB_connection.php";
    include "data/student.php";
    include "data/subject.php";

    $idStudent = $_SESSION['idStudent'];
    $student = getStudentById($idStudent, $conn);
    
    // Get the subjects the student is enrolled in
    $enrolled_subjects = getSubjectsByStudentId($idStudent, $conn);

    // Get existing marks for the student
    $existing_marks = $conn->query("SELECT SubID, ExamId, TotalMarks, ObtainedMarks FROM marks WHERE StdId = $idStudent")->fetchAll(PDO::FETCH_ASSOC);
    $marks_data = [];
    foreach ($existing_marks as $mark) {
        $marks_data[$mark['SubID']] = $mark;
    }

    // Check if there are any existing marks
    if (count($existing_marks) > 0) {
        // Get the selected exam type from the marks data (assuming all marks are for the same exam)
        $exam_id = $existing_marks[0]['ExamId'];
        $exam_type_result = $conn->query("SELECT ExamType FROM exam WHERE idExam = $exam_id")->fetch(PDO::FETCH_ASSOC);
        $exam_type = $exam_type_result ? $exam_type_result['ExamType'] : "N/A";

        // Calculate total marks and obtained marks
        $total_marks_sum = 0;
        $obtained_marks_sum = 0;
        foreach ($marks_data as $mark) {
            $total_marks_sum += $mark['TotalMarks'];
            $obtained_marks_sum += $mark['ObtainedMarks'];
        }
    } else {
        $exam_type = "N/A";
        $total_marks_sum = 0;
        $obtained_marks_sum = 0;
    }
} else {
    // Redirect if user is not logged in as a student
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Result - <?php echo htmlspecialchars($student['name'] ?? ''); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="icon" href="../logo.png">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .school-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .school-logo {
            max-width: 100px;
            margin-bottom: 10px;
        }
        .result-table th, .result-table td {
            vertical-align: middle;
        }
        .result-table {
            margin-top: 20px;
        }
        .result-table th {
            background-color: #343a40;
            color: #fff;
        }
        .btn-primary, .btn-secondary {
            margin: 10px;
        }
        .print-button, .back-button {
            margin-right: 10px;
        }
        @media print {
            .print-button, .back-button {
                display: none;
            }
            .container {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="school-header">
            <img src="../logo.png" alt="School Logo" class="school-logo">
            <h1>KKA</h1>
            <p><strong>Exam Type:</strong> <?php echo htmlspecialchars($exam_type ?? ''); ?></p>
        </div>
        <h2 class="mb-3">Student Result - <?php echo htmlspecialchars($student['name'] ?? ''); ?></h2>
        <div class="mb-3">
            <ul class="list-group">
                <li class="list-group-item"><b>Student ID: </b> <?php echo htmlspecialchars($student['idStudent'] ?? ''); ?></li>
                <li class="list-group-item"><b>Student Name: </b> <?php echo htmlspecialchars($student['name'] ?? ''); ?></li>
                <li class="list-group-item"><b>Class: </b> <?php echo htmlspecialchars($student['class_name'] ?? ''); ?></li>
            </ul>
        </div>
        <h5>Marks</h5>
        <table class="table table-bordered result-table">
            <thead class="table-dark">
                <tr>
                    <th>Subject ID</th>
                    <th>Subject Name</th>
                    <th>Total Marks</th>
                    <th>Obtained Marks</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($enrolled_subjects as $subject) { 
                    $totalMarks = $marks_data[$subject['idSubject']]['TotalMarks'] ?? '';
                    $obtainedMarks = $marks_data[$subject['idSubject']]['ObtainedMarks'] ?? '';
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($subject['idSubject']); ?></td>
                        <td><?php echo htmlspecialchars($subject['name']); ?></td>
                        <td><?php echo htmlspecialchars($totalMarks); ?></td>
                        <td><?php echo htmlspecialchars($obtainedMarks); ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <th colspan="2" class="text-end">Total</th>
                    <th><?php echo htmlspecialchars($total_marks_sum); ?></th>
                    <th><?php echo htmlspecialchars($obtained_marks_sum); ?></th>
                </tr>
            </tbody>
        </table>
        <div class="mt-3 text-center">
            <a href="javascript:window.print()" class="btn btn-primary print-button">Print Result</a>
            <a href="javascript:history.back()" class="btn btn-secondary back-button">Back</a>
        </div>
    </div>
</body>
</html>
