<?php  

// Get Teacher by ID
function getTeacherById($teacher_id, $conn){
   $sql = "select * from teacher t inner join user u on t.userID=u.UserID
           WHERE idTeacher=?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$teacher_id]);

   if ($stmt->rowCount() == 1) {
     $teacher = $stmt->fetch();
     return $teacher;
   }else {
    return 0;
   }
}

function getTeachersSubjects($teacher_id, $conn)
{
  $sql = "SELECT ts.TeacherId, s.idSubject, s.name AS subject_name, t.name AS teacher_name
            FROM subject s
            INNER JOIN teacher_subjects ts ON s.idSubject = ts.SubID
            INNER JOIN teacher t ON ts.TeacherId = t.idTeacher
            WHERE ts.TeacherId=?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$teacher_id]);
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if (empty($results)) {
    return 0;
  } else {
    return $results;
  }
}

function TeacherPasswordVerify($teacher_pass, $conn, $teacher_id){
  $sql = "SELECT * FROM user
          WHERE UserID=?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$teacher_id]);

  if ($stmt->rowCount() == 1) {
    $teacher = $stmt->fetch();
    $pass  = $teacher['password'];

    if ($teacher_pass===$pass) {
       return 1;
    }else {
       return 0;
    }
  }else {
   return 0;
  }
}