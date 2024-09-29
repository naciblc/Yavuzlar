<?php



include '../config/database.php'; // Veritabanı bağlantısını dahil et

 
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'firma') {
    header("Location: /views/login.php"); // Giriş yapmamışsa login sayfasına yönlendir
    exit;
}

 
$status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : '';

 
$query = "
    SELECT o.*, u.username AS customer_name, c.comment, c.rating 
    FROM orders o 
    LEFT JOIN users u ON o.user_id = u.id 
    LEFT JOIN comments c ON o.id = c.order_id 
    WHERE o.restaurant_id = ?";

if ($status_filter) {
    $query .= " AND o.status = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$_SESSION['restaurant_id'], $status_filter]);
} else {
    $stmt = $pdo->prepare($query . " ORDER BY o.created_at DESC");
    $stmt->execute([$_SESSION['restaurant_id']]);
}

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = (int)$_POST['order_id'];
    $status = htmlspecialchars($_POST['status']);
    
    $update_stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $update_stmt->execute([$status, $order_id]);

 
    $_SESSION['message'] = "Sipariş durumu güncellendi.";
    
 
 
    

    include 'success.php';
    
    exit; // Yönlendirme sonrası işlemi durdurmak için
}

?>



<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran Yönetim Paneli</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Restoran Yönetim Paneli</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <!-- Sipariş Durumu Filtreleme -->
    <form method="GET" action="restaurant_panel.php" class="mb-3">
        <div class="form-group">
            <label for="status_filter">Sipariş Durumu Filtrele:</label>
            <select name="status_filter" id="status_filter" class="form-control" onchange="this.form.submit()">
                <option value="">Tüm Siparişler</option>
                <option value="Hazırlanıyor" <?= $status_filter === 'Hazırlanıyor' ? 'selected' : '' ?>>Hazırlanıyor</option>
                <option value="Yola Çıktı" <?= $status_filter === 'Yola Çıktı' ? 'selected' : '' ?>>Yola Çıktı</option>
                <option value="Teslim Edildi" <?= $status_filter === 'Teslim Edildi' ? 'selected' : '' ?>>Teslim Edildi</option>
                <option value="İptal Edildi" <?= $status_filter === 'İptal Edildi' ? 'selected' : '' ?>>İptal Edildi</option>
            </select>
        </div>
    </form>

    <h3>Aktif Siparişler</h3>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Müşteri Adı</th>
                <th>Toplam Fiyat</th>
                <th>Durum</th>
                <th>Yorum ve Puan</th>
                <th>Güncelle</th>
                <th>İptal Et</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="7">Bu duruma ait sipariş yok.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['id']) ?></td>
                        <td><?= htmlspecialchars($order['customer_name']) ?></td>
                        <td><?= htmlspecialchars($order['total_price']) ?> TL</td>
                        <td><?= htmlspecialchars($order['status']) ?></td>
                        <td>
                            <?php if (!empty($order['comment']) && !empty($order['rating'])): ?>
                                <p><strong>Yorum:</strong> <?= htmlspecialchars($order['comment']) ?></p>
                                <p><strong>Puan:</strong> <?= htmlspecialchars($order['rating']) ?>/10</p>
                            <?php else: ?>
                                <p>Yorum yok</p>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST" action="restaurant_panel.php">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                <select name="status" required>
                                    <option value="" disabled selected>Durum Seçin</option>
                                    <option value="Hazırlanıyor">Hazırlanıyor</option>
                                    <option value="Yola Çıktı">Yola Çıktı</option>
                                    <option value="Teslim Edildi">Teslim Edildi</option>
                                </select>
                                <button type="submit" class="btn btn-primary">Güncelle</button>
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="restaurant_panel.php">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                <input type="hidden" name="status" value="İptal Edildi">
                                <button type="submit" class="btn btn-danger">İptal Et</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
