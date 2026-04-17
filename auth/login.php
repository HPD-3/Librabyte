<?php
include('../config/db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username === 'admin' && $password === '12') {
        $_SESSION['user_id'] = 0; 
        $_SESSION['is_admin'] = true;
        header("Location: ../admin/admindash.php"); 
        exit();
    }

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $hashed);
        $stmt->fetch();
        if (password_verify($password, $hashed)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['is_admin'] = false;
            header("Location: ../dashboard.php");
            exit();
        }
    }

    echo "<script>alert('Login failed.'); window.location.href = '../index.php';</script>";
}
?>
