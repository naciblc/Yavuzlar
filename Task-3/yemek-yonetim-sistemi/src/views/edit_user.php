<?php
session_start();
include '../config/database.php';

 
if (!isset($_SESSION['username'])) {
 
    header("Location: /views/login.php");
    exit();
}

 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = (int)$_POST['id'];
    $username = htmlspecialchars($_POST['username']);
    $role = htmlspecialchars($_POST['role']);
    $balance = (float)$_POST['balance'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

 
    if (!empty($new_password) && $new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
 
        $stmt = $pdo->prepare("UPDATE users SET username = ?, role = ?, balance = ?, password = ? WHERE id = ?");
        $stmt->execute([$username, $role, $balance, $hashed_password, $user_id]);
        
        echo "<div class='alert alert-success'>Kullanıcı bilgileri ve şifre başarıyla güncellendi.</div>";
    } else {
 
        $stmt = $pdo->prepare("UPDATE users SET username = ?, role = ?, balance = ? WHERE id = ?");
        $stmt->execute([$username, $role, $balance, $user_id]);

        echo "<div class='alert alert-success'>Kullanıcı bilgileri başarıyla güncellendi. (Şifre güncellenmedi.)</div>";
    }
}

 
$user_id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT id, username, role, balance FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "<div class='alert alert-danger'>Kullanıcı bulunamadı.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Düzenle - Admin Paneli</title>
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
        <h2>Kullanıcı Düzenle</h2>
        <form method="POST" action="edit_user.php?id=<?= $user['id'] ?>">
            <input type="hidden" name="id" value="<?= $user['id'] ?>">
            <div class="mb-3">
                <label for="username" class="form-label">Kullanıcı Adı</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="balance" class="form-label">Bakiye</label>
                <input type="number" class="form-control" id="balance" name="balance" value="<?= htmlspecialchars($user['balance']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Rol</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="admin" <?= ($user['role'] == 'admin') ? 'selected' : '' ?>>Admin</option>
                    <option value="user" <?= ($user['role'] == 'user') ? 'selected' : '' ?>>User</option>
                    <option value="firma" <?= ($user['role'] == 'firma') ? 'selected' : '' ?>>Firma</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label">Yeni Şifre</label>
                <input type="password" class="form-control" id="new_password" name="new_password">
                <small class="form-text text-muted">Yeni bir şifre girmek istiyorsanız burayı doldurun. (Boş bırakılırsa şifre güncellenmeyecek.)</small>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Yeni Şifreyi Onayla</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
            </div>
            <button type="submit" class="btn btn-primary">Güncelle</button>
            <a href="manage_users.php" class="btn btn-secondary">Geri Dön</a> <!-- Geri Dön butonunu güncelledik -->
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
