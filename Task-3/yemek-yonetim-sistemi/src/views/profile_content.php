<?php

include '../config/database.php';

 
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username, balance, profile_image FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_image'])) {
    $image = $_FILES['profile_image'];
    $image_path = 'uploads/' . $user_id . '_' . $image['name'];

 
    if (move_uploaded_file($image['tmp_name'], $image_path)) {
        $stmt = $pdo->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
        $stmt->execute([$image_path, $user_id]);
        $_SESSION['message'] = "Profil resmi başarıyla güncellendi!";
    } else {
        $_SESSION['error'] = "Profil resmi güncellenirken bir hata oluştu.";
    }
}

 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_password'])) {
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$new_password, $user_id]);
    $_SESSION['message'] = "Şifre başarıyla güncellendi!";
}

 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['balance'])) {
    $balance = (float)$_POST['balance'];
    $stmt = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
    $stmt->execute([$balance, $user_id]);
    $_SESSION['message'] = "Bakiye başarıyla yüklendi!";
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilim</title>

    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    
    <style>
        body {
            background-color: #f7f7f7;
        }
        .container {
            margin-top: 50px;
        }
        .profile-info {
            text-align: center;
            margin-bottom: 40px;
        }
        .profile-info img {
            border-radius: 50%;
            border: 3px solid #ddd;
            margin-bottom: 20px;
        }
        .profile-info h3 {
            font-weight: bold;
            font-size: 1.6rem; /* Daha büyük yazı */
            color: #343a40; /* Yazı rengi */
        }
        .profile-info h3 span {
            color: #007bff; /* Kullanıcı adı ve bakiye için vurgu rengi */
        }
        .profile-info h3#username {
            font-size: 2rem; /* Kullanıcı adı için ekstra büyük font */
        }
        .form-control {
            border-radius: 0.5rem;
        }
        .btn {
            border-radius: 0.5rem;
        }
        .alert {
            font-size: 1.2rem;
        }
        .card-title {
            font-size: 1.5rem;
            color: #007bff;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="text-center mb-5">Profilim</h1>

   
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success text-center"><?= $_SESSION['message'] ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php elseif (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger text-center"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="profile-info">
        <img src="<?= htmlspecialchars($user['profile_image']) ?>" alt="Profil Resmi" width="150" height="150">
        <h3 id="username">Kullanıcı Adı: <span><?= htmlspecialchars($user['username']) ?></span></h3>
        <h3>Bakiye: <span><?= number_format($user['balance'], 2) ?> TL</span></h3>
    </div>

    
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h4 class="card-title">Profil Resmi Güncelle</h4>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="file" name="profile_image" id="profile_image" class="form-control-file">
                </div>
                <button type="submit" class="btn btn-primary mt-2">Güncelle</button>
            </form>
        </div>
    </div>

    
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h4 class="card-title">Şifre Güncelle</h4>
            <form method="POST">
                <div class="form-group">
                    <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Yeni Şifre">
                </div>
                <button type="submit" class="btn btn-warning mt-2">Şifreyi Güncelle</button>
            </form>
        </div>
    </div>

    
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h4 class="card-title">Bakiye Yükle</h4>
            <form method="POST">
                <div class="form-group">
                    <input type="number" name="balance" id="balance" class="form-control" step="0.01" placeholder="Yüklenecek Bakiye">
                </div>
                <button type="submit" class="btn btn-success mt-2">Bakiye Yükle</button>
            </form>
        </div>
    </div>

</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
