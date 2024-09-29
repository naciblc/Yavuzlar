<?php

include '../config/database.php'; // Veritabanı bağlantısını dahil et

 
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'firma') {
    header("Location: /views/login.php");
    exit();
}

 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $restaurant_id = $_SESSION['restaurant_id']; // Oturumdan restoran ID'sini al

 
    $stmt = $pdo->prepare("INSERT INTO foods (name, description, price, restaurant_id) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$name, $description, $price, $restaurant_id])) {
        $success_message = "Yemek başarıyla eklendi.";
    } else {
        $error_message = "Yemek eklenirken bir hata oluştu.";
    }
}

 
$stmt = $pdo->prepare("SELECT * FROM foods WHERE restaurant_id = ?");
$stmt->execute([$_SESSION['restaurant_id']]);
$foods = $stmt->fetchAll(PDO::FETCH_ASSOC);

 
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_stmt = $pdo->prepare("DELETE FROM foods WHERE id = ? AND restaurant_id = ?");
    if ($delete_stmt->execute([$delete_id, $_SESSION['restaurant_id']])) {
        $success_message = "Yemek başarıyla silindi.";
        include 'successsx.php';
        exit();
    } else {
        $error_message = "Yemek silinirken bir hata oluştu.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yemek Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>Yemek Ekle</h1>
    
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="name" class="form-label">Yemek Adı</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Açıklama</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Fiyat</label>
            <input type="number" class="form-control" id="price" name="price" step="0.01" required>
        </div>
        <button type="submit" class="btn btn-primary">Ekle</button>
        <a href="/views/restaurant_panel.php" class="btn btn-secondary">Geri Dön</a>
    </form>

    <h2 class="mt-4">Mevcut Yemekler</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Yemek Adı</th>
                <th>Açıklama</th>
                <th>Fiyat</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($foods) > 0): ?>
                <?php foreach ($foods as $food): ?>
                    <tr>
                        <td><?= htmlspecialchars($food['name']) ?></td>
                        <td><?= htmlspecialchars($food['description']) ?></td>
                        <td><?= htmlspecialchars($food['price']) ?> TL</td>
                        <td>
                            <a href="?delete_id=<?= $food['id'] ?>" class="btn btn-danger" onclick="return confirm('Bu yemeği silmek istediğinize emin misiniz?');">Sil</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">Hiç yemek yok.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
