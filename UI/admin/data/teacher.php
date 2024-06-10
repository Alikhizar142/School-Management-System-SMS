<?php
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
function getUsername($Uid, $conn)
{
  $sql = "SELECT username FROM user WHERE UserID=?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$Uid]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result) {
    return $result['username'];
  } else {
    return 0;
  }
}
// Get Teacher by ID
function getTeacherById($teacher_id, $conn)
{
  $sql = "SELECT * FROM teacher
           WHERE idTeacher=?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$teacher_id]);

  if ($stmt->rowCount() == 1) {
    $teacher = $stmt->fetch();
    return $teacher;
  } else {
    return 0;
  }
}

// All Teachers 
function getAllTeachers($conn)
{
  $sql = "SELECT * FROM teacher inner join user on teacher.userID=user.userID";
  $stmt = $conn->prepare($sql);
  $stmt->execute();

  if ($stmt->rowCount() >= 1) {
    $teachers = $stmt->fetchAll();
    return $teachers;
  } else {
    return 0;
  }
}

// Check if the username Unique
function unameIsUnique($uname, $conn, $teacher_id = 0)
{
  $sql = "SELECT username, teacher_id FROM teachers
           WHERE username=?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$uname]);

  if ($teacher_id == 0) {
    if ($stmt->rowCount() >= 1) {
      return 0;
    } else {
      return 1;
    }
  } else {
    if ($stmt->rowCount() >= 1) {
      $teacher = $stmt->fetch();
      if ($teacher['teacher_id'] == $teacher_id) {
        return 1;
      } else {
        return 0;
      }
    } else {
      return 1;
    }
  }

}

// DELETE
function removeTeacher($id, $conn)
{
  // First, retrieve the userID associated with the teacher
  $sql_get_user_id = "SELECT userID FROM teacher WHERE idTeacher = ?";
  $stmt_get_user_id = $conn->prepare($sql_get_user_id);
  $stmt_get_user_id->execute([$id]);
  $user_id_row = $stmt_get_user_id->fetch(PDO::FETCH_ASSOC);

  if ($user_id_row) {
    $userID = $user_id_row['userID'];

    // Now, delete the teacher record
    $sql_delete_teacher = "DELETE FROM teacher WHERE idTeacher = ?";
    $stmt_delete_teacher = $conn->prepare($sql_delete_teacher);
    $re_delete_teacher = $stmt_delete_teacher->execute([$id]);

    if ($re_delete_teacher) {
      // Delete the corresponding user record
      $sql_delete_user = "DELETE FROM user WHERE UserID = ?";
      $stmt_delete_user = $conn->prepare($sql_delete_user);
      $re_delete_user = $stmt_delete_user->execute([$userID]);

      if ($re_delete_user) {
        return 1;
      } else {
        return 0;
      }
    } else {
      return 0;
    }
  } else {
    return 0;
  }
}

// Search 
function searchTeachers($key, $conn)
{
  if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $key)) 
  {
    return 0;
  }

  $key = addcslashes($key, '%_\\&');
  $key = "%" . $key . "%";

  $sql = "SELECT teacher.*, user.username FROM teacher 
            INNER JOIN user ON teacher.UserID = user.UserID
            WHERE teacher.idTeacher LIKE ? 
            OR teacher.name LIKE ?
            OR user.username LIKE ?
            OR teacher.CNIC_NO LIKE ?";

  $stmt = $conn->prepare($sql);
  $stmt->execute([$key, $key, $key, $key]);

  if ($stmt->rowCount() > 0) {
    $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $teachers;
  } else {
    return 0;
  }
}

