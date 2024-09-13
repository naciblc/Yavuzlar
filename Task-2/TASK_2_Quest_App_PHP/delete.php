<?php
require 'functions/db.php';

if (isset($_GET['id'])) {
    $questionId = (int)$_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM Questions WHERE id = :id");
    $stmt->execute([':id' => $questionId]);

    echo '<script>alert("Soru Silindi");
    window.location.href = "admin.php";
    </script>';
} else {
    echo 'Soru ID belirtilmedi.';
}
?>
