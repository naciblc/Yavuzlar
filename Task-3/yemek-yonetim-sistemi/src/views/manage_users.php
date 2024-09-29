<?php
 
session_start();

 
include '../config/database.php';

 
if (!isset($_SESSION['username'])) {
 
    header("Location: /views/login.php");
    exit();
}

 
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcıları Yönet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet"> 
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
            <img src="s.jpg" alt="Logo" width="30" height="30" class="d-inline-block align-text-top"> Admin Paneli</a>
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

        <h1 class="mb-4">Kullanıcıları Yönet</h1>
        
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kullanıcı Adı</th>
                                <th>Rol</th>
                                <th>Durum</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['role']); ?></td>
                                <td><?php echo $user['banned'] ? 'Yasaklı' : 'Aktif'; ?></td>
                                <td>
                                    <a href="/views/edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm">Güncelle</a>
                                    <a href="/controllers/delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?')">Sil</a>

                                    <?php if ($user['banned']): ?>
                                        <a href="/controllers/unban_user.php?id=<?php echo $user['id']; ?>" class="btn btn-success btn-sm">Aktif Et</a>
                                    <?php else: ?>
                                        <a href="/controllers/ban_user.php?id=<?php echo $user['id']; ?>" class="btn btn-dark btn-sm" onclick="return confirm('Bu kullanıcıyı banlamak istediğinize emin misiniz?')">Banla</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <a href="/views/add_user.php" class="btn btn-success mt-3">Yeni Kullanıcı Ekle</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
