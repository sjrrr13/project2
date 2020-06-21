<?php
session_start();
require_once "config.php";

function validLogin(){
    try {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        $sql = "SELECT * FROM traveluser WHERE UserName=:user and Pass=:pass ";

        $statement = $pdo->prepare($sql);
        $statement->bindValue(':user', $_POST['userName']);
        $statement->bindValue(':pass', $_POST['password']);
        $statement->execute();
        if ($statement->rowCount() > 0) {
            $pdo = null;
            return true;
        }else {
            $pdo = null;
            return false;
        }

    } catch (PDOException $e) {
        return false;
    }
}

$url1 = 'index.php';
$url2 = '../html/logIn.html';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(validLogin()){
        $_SESSION['UserName'] = $_POST['userName'];
        header('location: ' . $url1);
    } else {
        echo '<script>alert("登录失败：错误的用户名或密码");location.href="' . $url2 . '";</script>';
    }
} else {
    echo '<script>alert("连接出现问题：请重试");location.href="' . $url2 . '";</script>';
}
?>
