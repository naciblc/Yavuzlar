<?php
session_start();
include '../config/database.php';

 
if ($_SESSION['role'] !== 'admin') {
    header('Location: unauthorized.php');
    exit();
}

 
if (isset($_GET['id'])) {
    $restaurant_id = (int)$_GET['id'];

    try {
 
        $pdo->beginTransaction();

 
        $stmt = $pdo->prepare("DELETE FROM orders WHERE restaurant_id = ?");
        $stmt->execute([$restaurant_id]);

 
        $stmt = $pdo->prepare("DELETE FROM comments WHERE restaurant_id = ?");
        $stmt->execute([$restaurant_id]);

 
 
 

 
        $stmt = $pdo->prepare("DELETE FROM restaurants WHERE id = ?");
        $stmt->execute([$restaurant_id]);

 
        $pdo->commit();

 
        header('Location: /views/manage_restaurants.php?success=deleted');
        exit();
    } catch (PDOException $e) {
 
        $pdo->rollBack();
        echo "Bir hata meydana geldi: " . $e->getMessage();
    }
} else {
 
    header('Location: /views/manage_restaurants.php?error=missing_id');
    exit();
}
?>
