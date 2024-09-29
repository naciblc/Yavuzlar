<?php
session_start();

include '../config/database.php';


if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("UPDATE users SET banned = 1 WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: /views/manage_users.php?message=Kullanıcı başarıyla banlandı");
    exit();
} else {
    header("Location: /views/manage_users.php?error=Geçersiz kullanıcı ID'si");
    exit();
}
?>
