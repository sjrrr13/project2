<?php
require_once "config.php";

function validRegister(){
    try {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql0 = 'SELECT * FROM traveluser WHERE UserName="' . $_POST['userName'] . '"';
        echo '<script>alert(' . $sql0 . ')</script>';
        $result0 = $pdo->query($sql0);
        if($result0->rowCount() > 0) {
            return 2;
        } else {
            $sql1 = "select UID from traveluser";
            $result = $pdo->query($sql1);
            $newnum = $result->rowCount() + 1;
            $date = date("Y-m-d H:i:s");

            $sql2 = "insert traveluser(UID, Email, UserName, Pass, State, DateJoined, DateLastModified) value(:uid, :email, :user, :pass, :state, :date, :datemodified)";

            $statement = $pdo->prepare($sql2);
            $statement->bindValue(':uid', $newnum);
            $statement->bindValue(':email', $_POST['email']);
            $statement->bindValue(':user', $_POST['userName']);
            $statement->bindValue(':pass', $_POST['password']);
            $statement->bindValue(':state', 1);
            $statement->bindValue(':date', $date);
            $statement->bindValue(':datemodified', $date);
            $statement->execute();
            if ($statement->rowCount() > 0) {
                $pdo = null;
                return 1;
            } else {
                $pdo = null;
                return 0;
            }
        }
    } catch (PDOException $e) {
        return false;
    }
}

$url1 = '../html/logIn.html';
$url2 = '../html/register.html';
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $res = validRegister();
    if($res == 1){
        echo '<script>alert("注册成功！请登录");location.href="' . $url1 . '";</script>';
    } else if($res == 2) {
        echo '<script>alert("该用户名已存在");location.href="' . $url2 . '";</script>';
    } else {
        echo '<script>alert("注册失败");location.href="' . $url2 . '";</script>';

    }
} else {
    echo '<script>alert("连接出现问题：请重试");location.href="' . $url2 . '";</script>';
}
?>