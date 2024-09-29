<?php
 
session_start();

 
include '../config/database.php';

 
if (isset($_GET['id'])) {
    $id = $_GET['id'];

 
    $stmt = $pdo->prepare("UPDATE users SET banned = 0 WHERE id = ?");
    $stmt->execute([$id]);

 
    header("Location: /views/manage_users.php?message=Kullanıcının yasağı başarıyla kaldırıldı");
    exit();
} else {
    header("Location: /views/manage_users.php?error=Geçersiz kullanıcı ID'si");
    exit();
}
?>
