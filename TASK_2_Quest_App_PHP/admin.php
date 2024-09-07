<?php
require 'functions/db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question = $_POST['question'];
    $options = json_encode($_POST['options']); 
    $correctAnswer = $_POST['correct_answer'];


    $stmt = $pdo->prepare("INSERT INTO Questions (question, options, correct_answer) VALUES (:question, :options, :correct_answer)");
    $stmt->execute([
        ':question' => $question,
        ':options' => $options,
        ':correct_answer' => $correctAnswer
    ]);

    echo '<script>alert("Soru Eklendi")
    window.location.href = "admin.php";
    </script>';
} else {

    echo '<link rel="stylesheet" href="styles.css">
    <div class="container">
    <form method="POST" action="submit_quiz.php">
    
    
        Soru: <input type="text" name="question" required><br>
        Şık 1: <input type="text" name="options[]" required><br>
        Şık 2: <input type="text" name="options[]" required><br>
        Şık 3: <input type="text" name="options[]" required><br>
        Şık 4: <input type="text" name="options[]" required><br>
        Doğru Cevap (0-3): <input type="number" name="correct_answer" min="0" max="3" required><br>
        <button type="submit" value="Soru Ekle">Soru Ekle</button>
        <button onclick="anasayfa()">Ana Sayfa</button>
        
    </form>
    <script src="script.js"></script>
    </div>';
}
?>
