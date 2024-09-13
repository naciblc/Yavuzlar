<?php
require 'functions/db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role']; 

    try {
        $stmt = $pdo->prepare("INSERT INTO Users (nickname, passwd, role) VALUES (:username, :password, :role)");
        $stmt->execute([
            ':username' => $username,
            ':password' => $password,
            ':role' => $role
        ]);

        echo '<script>alert("Üye Başarıyla Eklendi")
        window.location.href = "index.php";
        </script>';
    } catch (PDOException $e) {
        echo "Veritabanı hatası: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Üye Ekle</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .container {
            width: 300px;
            margin: 0 auto;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="password"], button {
            padding: 8px;
            font-size: 14px;
            width: 100%;
        }
        .radio-group {
            margin-top: 10px;
        }
        .radio-group label {
            margin-left: 5px;
        }
        button {
            margin-top: 20px;
            padding: 10px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Üye Ekle</h1>
        <form method="POST" action="uye.php">
            <label for="username">Kullanıcı Adı:</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Şifre:</label>
            <input type="password" name="password" id="password" required>

            <div class="radio-group">
                <label>Rol:</label>
                <input type="radio" name="role" value="student" id="student" required>
                <label for="student">Öğrenci</label>
                
                <input type="radio" name="role" value="admin" id="admin" required>
                <label for="admin">Admin</label>
            </div>

            <button type="submit">Üye Ekle</button>
            <button type="button" onclick="window.location.href='index.php'">Ana Sayfa</button>
        </form>
    </div>
</body>
</html>
