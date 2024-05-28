<?php

// All Students 
function getAllStudents($conn)
{
  $sql = "SELECT * FROM student s inner join user u on s.userID=u.UserID";
  $stmt = $conn->prepare($sql);
  $stmt->execute();

  if ($stmt->rowCount() >= 1) {
    $students = $stmt->fetchAll();
    return $students;
  } else {
    return 0;
  }
}
// new by khizar
function getAllStudentsWithDetails($conn)
{
  $sql = "SELECT 
          student.idStudent,
          student.name AS student_name,
          parent.name AS parent_name,
          parent.phone_no,
          GROUP_CONCAT(DISTINCT class.name ORDER BY class.name SEPARATOR ', ') AS class_name
          FROM 
          student
          INNER JOIN 
          registration ON student.idStudent = registration.StdId
          INNER JOIN 
          parent ON student.ParentId = parent.idParent
          INNER JOIN 
          class ON registration.ClassId = class.idClass
          GROUP BY 
          student.idStudent, student.name, parent.name, parent.phone_no
          ";

  $stmt = $conn->prepare($sql);
  $stmt->execute();

  if ($stmt->rowCount() >= 1) {
    $students = $stmt->fetchAll();
    return $students;
  } else {
    return 0;
  }
}
// DELETE
function removeStudent($id, $conn)
{
  $sql = "DELETE FROM student
           WHERE idStudent=?";
  $stmt = $conn->prepare($sql);
  $re = $stmt->execute([$id]);
  if ($re) {
    return 1;
  } else {
    return 0;
  }
}

// Get Student By Id  (modfied)
function getStudentById($id, $conn)
{
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



// Check if the username Unique
function unameIsUnique($uname, $conn, $student_id)
{
  // Prepare SQL statement to check if username exists in the user table
  $sqlUser = "SELECT UserID FROM user WHERE username=?";
  $stmtUser = $conn->prepare($sqlUser);
  $stmtUser->execute([$uname]);

  // Check if a username is found in the user table
  if ($stmtUser->rowCount() >= 1) {
    $user = $stmtUser->fetch();
    $user_id = $user['UserID'];

    // If student_id is provided, check if it matches the user_id in the student table
    if ($student_id !== null) {
      $sqlStudent = "SELECT idStudent FROM student WHERE UserID=?";
      $stmtStudent = $conn->prepare($sqlStudent);
      $stmtStudent->execute([$user_id]);
      echo $student_id;
      // Check if a matching user_id is found in the student table
      if ($stmtStudent->rowCount() >= 1) {
        $student = $stmtStudent->fetch();
        if ($student['idStudent'] == $student_id) {
          return 1; // Username and user_id match the student_id
        } else {
          return 0; // Username exists but does not match the student_id
        }
      } else {
        return 0; // Username exists in user table but no matching user_id in student table
      }
    } else {
      return 0; // Username exists and no student_id is provided, so not unique
    }
  } else {
    // If no username is found in the user table, it is unique
    return 1;
  }
}


// modified khizar
function searchStudents($key, $conn)
{
  // Escape special characters to prevent SQL injection
  $key = "%" . preg_replace('/(?<!\\\)([%_])/', '\\\$1', $key) . "%";

  // Check if key matches any parent name
  $parentSql = "SELECT DISTINCT student.*, parent.name AS parent_name, parent.phone_no, class.name AS class_name
                FROM student
                INNER JOIN parent ON student.ParentId = parent.idParent
                INNER JOIN registration ON student.idStudent = registration.stdId
                INNER JOIN class ON registration.ClassId = class.idClass
                WHERE parent.name LIKE ?";
  $parentStmt = $conn->prepare($parentSql);
  $parentStmt->execute([$key]);

  if ($parentStmt->rowCount() >= 1) {
    $students = $parentStmt->fetchAll();
    return $students;
  } else {
    // Search by student ID or name if key doesn't match parent name
    $studentSql = "SELECT DISTINCT student.*, parent.name AS parent_name, parent.phone_no, class.name AS class_name
                    FROM student
                    INNER JOIN parent ON student.ParentId = parent.idParent
                    INNER JOIN registration ON student.idStudent = registration.stdId
                    INNER JOIN class ON registration.ClassId = class.idClass
                    WHERE student.idStudent LIKE ? 
                    OR student.name LIKE ?";
    $studentStmt = $conn->prepare($studentSql);
    $studentStmt->execute([$key, $key]);

    if ($studentStmt->rowCount() >= 1) {
      $students = $studentStmt->fetchAll();
      return $students;
    } else {
      return 0;
    }
  }
}


// new by khizar
function getStudentsByClass1($class_id, $conn)
{
  $sql = "
  SELECT DISTINCT *
  FROM student s
  INNER JOIN registration r ON s.idStudent = r.StdId
  INNER JOIN user u ON s.userID = u.UserID
  WHERE r.ClassId = ?
  GROUP BY s.idStudent";

  $stmt = $conn->prepare($sql);
  $stmt->execute([$class_id]);

  if ($stmt->rowCount() >= 1) {
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $students;
  } else {
    return 0;
  }
}


function getUserIDByStudentID($student_id, $conn)
{
  // Prepare SQL statement to get user_id from the student table
  $sql = "SELECT UserID FROM student WHERE idStudent = ?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$student_id]);

  // Check if a user_id is found
  if ($stmt->rowCount() >= 1) {
    $student = $stmt->fetch();
    return $student['UserID'];
  } else {
    // Return null or an appropriate value if no user_id is found
    return null;
  }
}
