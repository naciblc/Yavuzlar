<?php
session_start();
include '../config/database.php';

 
$stmt = $pdo->query("SELECT * FROM discount_codes");
$discount_codes = $stmt->fetchAll(PDO::FETCH_ASSOC);

 
if (!isset($_SESSION['username'])) {
    header("Location: /views/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İndirim Kodları - Yönetim Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet"> <!-- Kendi stil dosyanızı ekleyin -->
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="s.jpg" alt="Logo" width="30" height="30" class="d-inline-block align-text-top"> Admin Paneli
            </a>
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

        <h1 class="mb-4">İndirim Kodlarını Yönet</h1>
        
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Kod</th>
                                <th>İndirim Yüzdesi (%)</th>
                                <th>Restoran ID</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($discount_codes as $discount): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($discount['code']); ?></td>
                                <td><?php echo htmlspecialchars($discount['discount_percentage']); ?></td>
                                <td><?php echo htmlspecialchars($discount['restaurant_id']); ?></td>
                                <td>
                                    <form method="POST" action="admin_delete_discount.php" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $discount['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bu indirim kodunu silmek istediğinize emin misiniz?')">Sil</button>
                                    </form>
                                    <a href="admin_edit_discount.php?id=<?php echo $discount['id']; ?>" class="btn btn-warning btn-sm">Güncelle</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <a href="admin_add_discount.php" class="btn btn-success mt-3">Yeni İndirim Kodu Ekle</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
