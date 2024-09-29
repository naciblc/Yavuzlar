<?php
session_start();

include '../config/database.php';

if (!isset($_SESSION['username'])) {
    header("Location: /views/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: /views/manage_foods.php");
    exit();
}

$food_id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM foods WHERE id = ?");
$stmt->execute([$food_id]);

header("Location: /views/manage_foods.php?deleted=1");
exit();
?>