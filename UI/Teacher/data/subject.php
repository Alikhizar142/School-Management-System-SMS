<?php 

// All Subjects
function getAllSubjects($conn){
   $sql = "SELECT * FROM subjects";
   $stmt = $conn->prepare($sql);
   $stmt->execute();

   if ($stmt->rowCount() >= 1) {
     $subjects = $stmt->fetchAll();
     return $subjects;
   }else {
   	return 0;
   }
}

// Get Subjects by ID
function getSubjectById($subject_id, $conn){
   $sql = "SELECT * FROM subjects
           WHERE subject_id=?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$subject_id]);

   if ($stmt->rowCount() == 1) {
     $subject = $stmt->fetch();
     return $subject;
   }else {
   	return 0;
   }
}


// Get Subjects by ID
function getSubjectByGrade($grade, $conn){
   $sql = "SELECT * FROM subjects
           WHERE grade=?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$grade]);

   if ($stmt->rowCount() > 0) {
     $subject = $stmt->fetchAll();
     return $subject;
   }else {
    return 0;
   }
}


// get subjects by student ID new by khizar
function getSubjectsByStudentId($student_id, $conn) {
  $sql = "SELECT subject.idSubject, subject.name 
          FROM registration 
          JOIN subject ON registration.SubID = subject.idSubject 
          WHERE registration.StdId = :student_id";
  
  $stmt = $conn->prepare($sql);
  
  $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
  
  $stmt->execute();
  
  $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
  return $subjects;
}
 ?>