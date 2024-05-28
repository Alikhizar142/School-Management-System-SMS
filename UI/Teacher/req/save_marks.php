<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

if (isset($_SESSION['idTeacher']) && isset($_SESSION['role']) && $_SESSION['role'] == 'Teacher') {
    include "../../DB_connection.php";
    include "../data/student.php";
    include "../data/class.php";
    include "../data/subject.php";
    include "../data/teacher.php";

    if (!isset($_GET['idStudent'])) {
        header("Location: ../student-grade-edit.php");
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
    
    // Define a variable to hold the success message
    $success_message = "";

    // Process form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $marks = array();
        foreach ($enrolled_subjects as $subject) {
            $marks[$subject['idSubject']] = $_POST['marks-' . $subject['idSubject']];
        }
    
        // Insert/update marks into the "marks" table
        foreach ($marks as $subjectId => $mark) {
            // Prepare the SQL query to insert or update marks
            $sql = "INSERT INTO marks (ExamId, StdId, ClassId, SubID, TotalMarks, ObtainedMarks) 
                    VALUES (:examId, :studentId, :classId, :subjectId, :totalMarks, :obtainedMarks)
                    ON DUPLICATE KEY UPDATE ObtainedMarks = :obtainedMarks";
            
            // Prepare and execute the statement
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':examId', $_POST['exam']);
            $stmt->bindParam(':studentId', $idStudent);
            $stmt->bindParam(':classId', $classID["idClass"]);
            $stmt->bindParam(':subjectId', $subjectId);
            $stmt->bindParam(':totalMarks', $_POST['total-marks-' . $subjectId]); // Assuming the total marks are sent via POST
            $stmt->bindParam(':obtainedMarks', $mark);
            $stmt->execute();
        }
        
        // Set the success message
        $success_message = "Marks have been successfully updated!";
    }

    // Redirect to the edit page with the success message
    header("Location: ../student-grade-edit.php?idStudent=$idStudent&success=".urlencode($success_message));
    exit;
} else {
    // Redirect if the user is not logged in as a teacher
    header("Location: ../login.php");
    exit;
}
?>
