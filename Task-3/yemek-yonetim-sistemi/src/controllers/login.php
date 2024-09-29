<?php
ob_start(); 
session_start(); 
include '../config/database.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

 
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

 
    if ($user) {
        if ($user['banned'] == 1) {
 
            header("Location: /views/login.php?error=banned"); 
            exit();
        }

 
        if (password_verify($password, $user['password'])) {
 
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['restaurant_id'] = $user['restaurant_id']; 

 
            if ($user['role'] === 'admin') {
                header("Location: /views/admin.php");
                exit();
            } elseif ($user['role'] === 'firma') {
                header("Location: /views/restaurant_panel.php");
                exit();
            } else {
                header("Location: /views/home.php");
                exit();
            }
        }
    }

 
    header("Location: /views/login.php?error=invalid"); 
    exit();
}

ob_end_flush(); 
?>
