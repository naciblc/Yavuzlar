<?php session_start();


if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {

    header('Location: login.php'); 
    exit;
}
$user_role = $_SESSION['user_role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anasayfa</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">

    <button onclick="soru()">Soruları Çöz</button>
    <?php if ($user_role === 'admin'): ?>
    <button onclick="admin()">Admin Paneli</button>
    <?php endif; ?>
    <button onclick="score()">ScoreBoard</button>
    <button onclick="logout()">Çıkış Yap</button>
    


    </div>
    <script src="script.js"></script>
</body>
</html>