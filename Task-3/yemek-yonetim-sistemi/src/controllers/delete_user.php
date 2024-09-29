<?php
session_start();
include '../config/database.php';

 
if ($_SESSION['role'] !== 'admin') {
    header('Location: unauthorized.php');
    exit();
}

 
if (isset($_GET['id'])) {
    $user_id = (int)$_GET['id'];

 
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if ($user && $user['role'] === 'admin') {
        echo "Admin kullanıcıları silemezsiniz.";
        exit();
    }

 
    $stmt = $pdo->prepare("DELETE FROM orders WHERE user_id = ?");
    $stmt->execute([$user_id]);

 
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);

 
    header('Location: /views/manage_users.php?success=deleted');
    exit();
} else {
 
    header('Location: /views/manage_users.php?error=missing_id');
    exit();
}
?>
