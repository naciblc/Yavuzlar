<?php
include '../config/database.php';

 
$restaurants_stmt = $pdo->query("SELECT id, name FROM restaurants");
$restaurants = $restaurants_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $restaurant_id = $role === 'firma' ? $_POST['restaurant_id'] : null; 

 
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

 
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role, restaurant_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $hashedPassword, $role, $restaurant_id]); 

 
    header("Location: /views/manage_users.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Ekle</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .custom-card {
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }
        .custom-btn {
            background-color: #28a745;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            cursor: pointer;
        }
        .custom-btn:hover {
            background-color: #218838;
        }
    </style>
    <script>
        function toggleRestaurantSelect() {
            const roleSelect = document.getElementById('role');
            const restaurantSelect = document.getElementById('restaurant_id');
            restaurantSelect.disabled = roleSelect.value !== 'firma'; // "firma" dışında seçimi devre dışı bırak
            if (restaurantSelect.disabled) {
                restaurantSelect.value = ''; // Seçimi temizle
            }
        }

        document.addEventListener('DOMContentLoaded', toggleRestaurantSelect); // Sayfa yüklendiğinde kontrol et
    </script>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="custom-card">
                    <h3 class="text-center mb-4">Yeni Kullanıcı Ekle</h3>
                    <form action="add_user.php" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Kullanıcı Adı</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Şifre</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Rol</label>
                            <select class="form-select" id="role" name="role" required onchange="toggleRestaurantSelect()">
                                <option value="admin">Admin</option>
                                <option value="firma">Firma</option>
                                <option value="uye">Üye</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="restaurant_id" class="form-label">Restoran Seçin</label>
                            <select class="form-select" id="restaurant_id" name="restaurant_id" required disabled>
                                <option value="">Seçiniz...</option>
                                <?php foreach ($restaurants as $restaurant): ?>
                                    <option value="<?= $restaurant['id'] ?>"><?= htmlspecialchars($restaurant['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="custom-btn">Kullanıcı Ekle</button>
                        </div>
                    </form>

                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success mt-3">
                            Kullanıcı başarıyla eklendi!
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
