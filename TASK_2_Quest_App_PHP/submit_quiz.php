<?php
require 'functions/db.php'; 

session_start(); 


if (empty($_POST)) {
    die('Hatalı istek! Sorular gönderilmedi.');
}

$user_id = $_SESSION['user_id']; 
$score = 0; 

 
foreach ($_POST as $key => $user_answer) {

    $question_id = str_replace('question_', '', $key);


    $stmt = $pdo->prepare("SELECT correct_answer FROM Questions WHERE id = :id");
    $stmt->execute([':id' => $question_id]);
    $question = $stmt->fetch();


    if ($question && $question['correct_answer'] == $user_answer) {
        $is_correct = 1;
        $score++;
    } else {
        $is_correct = 0;
    }


    $stmt = $pdo->prepare("INSERT INTO Submissions (user_id, question_id, is_correct) VALUES (:user_id, :question_id, :is_correct)");
    $stmt->execute([
        ':user_id' => $user_id,
        ':question_id' => $question_id,
        ':is_correct' => $is_correct
    ]);
}


echo "Quiz Sonucu: $score / " . count($_POST);


?>
<script> alert("Quiz Sonucu: <?php echo $score; ?> / <?php echo count($_POST); ?>");
window.location.href = "scoreboard.php";</script>
