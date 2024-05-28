<?php 
// All classes
function getAllClasses($conn){
   $sql = "SELECT * FROM class";
   $stmt = $conn->prepare($sql);
   $stmt->execute();

   if ($stmt->rowCount() >= 1) {
     $classes = $stmt->fetchAll();
     return $classes;
   }else {
    return 0;
   }
}


// Get class by ID
function getClassById($class_id, $conn){
   $sql = "SELECT * FROM class
           WHERE idClass=?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$class_id]);

   if ($stmt->rowCount() == 1) {
     $class = $stmt->fetch();
     return $class;
   }else {
    return 0;
   }
}

// DELETE
function removeClass($id, $conn){
   $sql  = "DELETE FROM class
           WHERE idClasss=?";
   $stmt = $conn->prepare($sql);
   $re   = $stmt->execute([$id]);
   if ($re) {
     return 1;
   }else {
    return 0;
   }
}

function getStudentsByClass($class_id, $conn) {
  $sql = "SELECT s.idStudent, s.fname, s.lname, s.username
          FROM student s 
          JOIN registration r ON s.idStudent = r.StdId 
          WHERE r.ClassId = ?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$class_id]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getClassIDByName($class_name, $conn) {
  $sql = "SELECT * FROM class WHERE name = ?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$class_name]);

  if ($stmt->rowCount() == 1) {
    $class = $stmt->fetch();
    return $class;
  } else {
    return 0;
  }
}
