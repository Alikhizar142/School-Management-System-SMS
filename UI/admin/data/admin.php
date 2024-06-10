<?php

function adminPasswordVerify($admin_pass, $conn) {
    $sql = "SELECT * FROM user WHERE Role = 'Admin'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $admin = $stmt->fetch();

    if ($admin) {
        // Assuming you're using password_verify for password comparison
        if ($admin_pass === $admin["password"]) {
            return true;
        } else {
            return false;
        }
    } else {
        return false; // No admin found
    }
}
?>
