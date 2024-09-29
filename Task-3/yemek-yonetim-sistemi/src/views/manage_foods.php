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
    $stmt = $pdo->prepare("
        SELECT foods.*, restaurants.name AS restaurant_name 
        FROM foods 
        JOIN restaurants ON foods.restaurant_id = restaurants.id 
        WHERE foods.name LIKE ?
    ");
    $stmt->execute(['%' . $search_query . '%']);
} else {
 
    $stmt = $pdo->query("
        SELECT foods.*, restaurants.name AS restaurant_name 
        FROM foods 
        JOIN restaurants ON foods.restaurant_id = restaurants.id
    ");
}

$foods = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yemekleri Yönet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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

        <h1 class="mb-4">Yemekleri Yönet</h1>

        
        <form method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Yemek adı ara..." value="<?php echo htmlspecialchars($search_query); ?>">
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
                                <th>Yemek Adı</th>
                                <th>Fiyat</th>
                                <th>Restoran</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($foods): ?>
                                <?php foreach ($foods as $food): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($food['id']); ?></td>
                                        <td><?php echo htmlspecialchars($food['name']); ?></td>
                                        <td><?php echo htmlspecialchars($food['price']); ?> TL</td>
                                        <td><?php echo htmlspecialchars($food['restaurant_name']); ?></td>
                                        <td>
                                            <a href="/views/edit_food.php?id=<?php echo $food['id']; ?>" class="btn btn-warning btn-sm">Güncelle</a>
                                            <a href="/controllers/delete_food.php?id=<?php echo $food['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bu yemeği silmek istediğinize emin misiniz?')">Sil</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">Yemek bulunamadı.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <a href="/views/add_food_admin.php" class="btn btn-success mt-3">Yeni Yemek Ekle</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
