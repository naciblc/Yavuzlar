<?php

include '../config/database.php';

 
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

 
if (isset($_POST['cancel_order'])) {
    $order_id = (int)$_POST['order_id'];

 
    $stmt = $pdo->prepare("UPDATE orders SET status = 'İptal Edildi' WHERE id = ?");
    $stmt->execute([$order_id]);

    header("Location: orders.php");
    exit();
}

 
if (isset($_POST['add_comment'])) {
    $order_id = (int)$_POST['order_id'];
    $restaurant_id = (int)$_POST['restaurant_id'];
    $comment = htmlspecialchars($_POST['comment']);
    $rating = (int)$_POST['rating'];

 
    $stmt = $pdo->prepare("INSERT INTO comments (user_id, order_id, restaurant_id, comment, rating) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $order_id, $restaurant_id, $comment, $rating]);

 
    $stmt = $pdo->prepare("UPDATE restaurants SET rating = (SELECT AVG(rating) FROM comments WHERE restaurant_id = ?) WHERE id = ?");
    $stmt->execute([$restaurant_id, $restaurant_id]);

    include 'successs.php';
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siparişlerim</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/public/css/style.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2>Siparişlerim</h2>

    <div class="list-group">
        <?php foreach ($orders as $order): ?>
            <div class="list-group-item">
                <h5 class="d-flex justify-content-between align-items-center">
                    Sipariş ID: <?= htmlspecialchars($order['id']) ?>
                    <span class="badge bg-primary"><?= htmlspecialchars($order['status']) ?></span>
                </h5>
                <p>Toplam: <?= htmlspecialchars($order['total']) ?> TL</p>
                <p>Tarih: <?= htmlspecialchars($order['created_at']) ?></p>
                <p><strong>Yemek Notu:</strong> <?= htmlspecialchars($order['note'] ?? 'Not yok') ?></p>

                <?php if ($order['status'] === 'Beklemede'): ?>
                    <form method="POST" action="orders.php" class="d-inline">
                        <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['id']) ?>">
                        <button type="submit" name="cancel_order" class="btn btn-danger btn-sm">İptal Et</button>
                    </form>
                <?php endif; ?>

                <?php
 
                $stmt = $pdo->prepare("SELECT * FROM comments WHERE order_id = ?");
                $stmt->execute([$order['id']]);
                $commentData = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>

                <?php if ($order['status'] === 'Teslim Edildi' && !$commentData): ?>
                    <!-- Yorum Formu -->
                    <form method="POST" action="orders.php" class="mt-3">
                        <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['id']) ?>">
                        <input type="hidden" name="restaurant_id" value="<?= htmlspecialchars($order['restaurant_id']) ?>">
                        <div class="mb-3">
                            <label for="comment_<?= htmlspecialchars($order['id']) ?>" class="form-label">Yorum</label>
                            <textarea id="comment_<?= htmlspecialchars($order['id']) ?>" name="comment" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="rating_<?= htmlspecialchars($order['id']) ?>" class="form-label">Puan (1-10)</label>
                            <input type="number" id="rating_<?= htmlspecialchars($order['id']) ?>" name="rating" class="form-control" min="1" max="10" required>
                        </div>
                        <button type="submit" name="add_comment" class="btn btn-primary">Yorum ve Puan Ekle</button>
                    </form>
                <?php elseif ($commentData): ?>
                    <!-- Yorum ve Puanı Göster -->
                    <div class="mt-3">
                        <h6>Yorumunuz:</h6>
                        <p><?= htmlspecialchars($commentData['comment']) ?></p>
                        <p><strong>Puan:</strong> <?= htmlspecialchars($commentData['rating']) ?>/10</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <a href="home.php" class="btn btn-secondary mt-3">Sipariş Sayfasına Dön</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
