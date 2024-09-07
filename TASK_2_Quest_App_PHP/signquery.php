<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header('Location: register.php'); 
    exit;
  }
  
  
  
  
  

  error_reporting(0);
  ini_set('display_errors', 0);
  
  
include "functions/functions.php"; 

if (isset($_POST['username']) && isset($_POST['password'])) {
    $nickname = $_POST['username'];
    $passwd = $_POST['password'];
    
    Login($nickname, $passwd);
    exit;
}
/*result = Login($nickname, $passwd);

/*if ( $result['count'] == 0) {
    header("location: login.php");
    exit();
}else{  
    if ($result['count'] == 1){

    header("location: rezer.php");
    exit();
    }
}
/* Sonuçları kontrol edin
echo "<pre>";
print_r($result);
echo "</pre>";
*/
?>