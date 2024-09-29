<?php
session_start();

 
include '../config/database.php';

 
if (!isset($_SESSION['username'])) {
 
    header("Location: /views/login.php");
    exit();
}

 
$stmt = $pdo->query("SELECT c.id, c.comment, c.rating, c.created_at, u.username, r.name AS restaurant_name 
                    FROM comments c
                    JOIN users u ON c.user_id = u.id
                    JOIN restaurants r ON c.restaurant_id = r.id
                    ORDER BY c.created_at DESC");
$comments = $stmt->fetchAll();

 
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
    $stmt->execute([$delete_id]);
    header("Location: manage_comments.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yorum Yönetimi</title>
    <!-- Bootstrap CDN -->
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
        <a href="javascript:history.back()" class="btn btn-secondary mb-3">Bir Önceki Sayfaya Dön</a> 

        <h2>Yorum Yönetimi</h2>

        <table class="table table-bordered table-hover mt-3">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Kullanıcı Adı</th>
                    <th>Restoran</th>
                    <th>Yorum</th>
                    <th>Puan</th>
                    <th>Tarih</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comments as $comment): ?>
                    <tr>
                        <td><?= htmlspecialchars($comment['id']) ?></td>
                        <td><?= htmlspecialchars($comment['username']) ?></td>
                        <td><?= htmlspecialchars($comment['restaurant_name']) ?></td>
                        <td><?= htmlspecialchars($comment['comment']) ?></td>
                        <td><?= htmlspecialchars($comment['rating']) ?></td>
                        <td><?= htmlspecialchars($comment['created_at']) ?></td>
                        <td>
                            <a href="manage_comments.php?delete_id=<?= $comment['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bu yorumu silmek istediğinizden emin misiniz?')">Sil</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
``
