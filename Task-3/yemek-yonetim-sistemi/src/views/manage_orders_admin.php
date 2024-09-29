<?php
 
session_start();

 
include '../config/database.php';

 
if (!isset($_SESSION['username'])) {
    header("Location: /views/login.php");
    exit();
}

 
$stmt = $pdo->query("SELECT o.id, o.status, r.name AS restaurant_name, u.username 
                      FROM orders o 
                      JOIN restaurants r ON o.restaurant_id = r.id 
                      JOIN users u ON o.user_id = u.id");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Yönetimi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Paneli</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Hoş geldiniz, <?php echo htmlspecialchars($_SESSION['username']); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white" href="/controllers/logout.php">Çıkış Yap</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        
        <a href="admin.php" class="btn btn-secondary mb-3">Bir Önceki Sayfaya Dön</a>

        <h1 class="mb-4">Siparişleri Yönet</h1>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Restoran</th>
                        <th>Kullanıcı</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($orders): ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['id']); ?></td>
                                <td><?php echo htmlspecialchars($order['restaurant_name']); ?></td>
                                <td><?php echo htmlspecialchars($order['username']); ?></td>
                                <td>
                                    <form method="POST" action="update_order_status.php" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                                        <select name="status" onchange="this.form.submit()">
                                            <option value="Pending" <?php if ($order['status'] === 'Pending') echo 'selected'; ?>>Beklemede</option>
                                            <option value="Preparing" <?php if ($order['status'] === 'Preparing') echo 'selected'; ?>>Hazırlanıyor</option>
                                            <option value="Completed" <?php if ($order['status'] === 'Completed') echo 'selected'; ?>>Tamamlandı</option>
                                            <option value="Canceled" <?php if ($order['status'] === 'Canceled') echo 'selected'; ?>>İptal</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <a href="/controllers/cancel_order.php?id=<?php echo $order['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bu siparişi iptal etmek istediğinize emin misiniz?')">İptal Et</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Sipariş bulunamadı.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
