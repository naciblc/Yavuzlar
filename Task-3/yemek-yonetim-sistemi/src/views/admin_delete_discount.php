<?php
session_start();
include '../config/database.php';

if (isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM discount_codes WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: admin_view_discounts.php");
    exit();
}
?>
