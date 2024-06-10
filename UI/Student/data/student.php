<?php 

// Get Student By Id 
function getStudentById($id, $conn) {
  try {
      $sql = "SELECT s.*, 
                     p.name AS parent_name,p.email AS parent_email, p.phone_no AS parent_phoneNo, p.CNIC_NO AS parent_cnic, 
                     u.username,
                     c.name AS class_name,
                     GROUP_CONCAT(sub.name SEPARATOR ', ') AS subjects
              FROM student s
              LEFT JOIN parent p ON s.ParentId = p.idParent
              LEFT JOIN user u ON s.userID = u.UserID
              LEFT JOIN registration r ON s.idStudent = r.StdId
              LEFT JOIN class c ON r.ClassId = c.idClass
              LEFT JOIN subject sub ON FIND_IN_SET(sub.idSubject, r.SubID)
              WHERE s.idStudent = ?
              GROUP BY s.idStudent";

      $stmt = $conn->prepare($sql);
      $stmt->execute([$id]);

      if ($stmt->rowCount() == 1) {
          $student = $stmt->fetch(PDO::FETCH_ASSOC);
          return $student;
      } else {
          return 0;
      }
  } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
      return 0;
  }
}


function studentPasswordVerify($student_pass, $conn, $student_id){
  $sql = "SELECT * FROM user
          WHERE UserID=?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$student_id]);

  if ($stmt->rowCount() == 1) {
    $student = $stmt->fetch();
    $pass  = $student['password'];

    if ($student_pass===$pass) {
       return 1;
    }else {
       return 0;
    }
  }else {
   return 0;
  }
}