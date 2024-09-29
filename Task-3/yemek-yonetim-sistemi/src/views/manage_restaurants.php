<?php
 
session_start();

 
include '../config/database.php';

 
if (!isset($_SESSION['username'])) {
    header("Location: /views/login.php");
    exit();
}

 
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
    $stmt = $pdo->prepare("SELECT * FROM restaurants WHERE name LIKE ?");
    $stmt->execute(['%' . $search_query . '%']);
} else {
 
    $stmt = $pdo->query("SELECT * FROM restaurants");
}

$restaurants = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoranları Yönet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
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

        <h1 class="mb-4">Restoranları Yönet</h1>

        
        <form method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Restoran adı ara..." value="<?php echo htmlspecialchars($search_query); ?>">
                <button class="btn btn-primary" type="submit">Ara</button>
            </div>
        </form>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Restoran Adı</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($restaurants): ?>
                                <?php foreach ($restaurants as $restaurant): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($restaurant['id']); ?></td>
                                        <td><?php echo htmlspecialchars($restaurant['name']); ?></td>
                                        <td>
                                            <a href="/views/edit_restaurant.php?id=<?php echo $restaurant['id']; ?>" class="btn btn-warning btn-sm">Güncelle</a>
                                            <a href="/controllers/delete_restaurant.php?id=<?php echo $restaurant['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bu restoranı silmek istediğinize emin misiniz?')">Sil</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">Restoran bulunamadı.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <a href="/views/add_restaurant.php" class="btn btn-success mt-3">Yeni Restoran Ekle</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
