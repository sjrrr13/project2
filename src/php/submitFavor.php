<?php
session_start();
require_once "config.php";

$url = 'favorite.php';
if(isset($_SESSION['UserName'])){
    if(isset($_GET['tool'])) {
        $res = addFavor();
        if($res > 0) {
            if($res == 1) {
                echo '<script>alert("收藏成功！");location.href="' . $url . '";</script>';
            } else {
                echo '<script>alert("您已经收藏过这张图片了");location.href="' . $url . '";</script>';
            }
        } else {
            echo '<script>alert("收藏失败,请稍后重试");location.href="' . $url . '";</script>';
        }
    }
} else {
        echo '<script>alert("收藏失败,请登录");location.href="' . $url . '";</script>';
}

function addFavor() {
    try {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $tip = 0;

        $sql0 = 'SELECT FavorID From travelimagefavor';
        $result0 = $pdo->query($sql0);
        $num = 0;
        while($row0 = $result0->fetch()) {
            $num = max($num, $row0['FavorID']);
        }
        $num += 1;

        $sql1 = 'SELECT UID From traveluser WHERE UserName="' . $_SESSION['UserName'] . '"';
        $result1 = $pdo->query($sql1);
        while($row1 = $result1->fetch()) {
            $uid = $row1['UID'];
        }

        $judge = 'SELECT * FROM travelimagefavor WHERE ImageID=' . $_GET['tool'] . ' AND UID=' . $uid;
        $judgeResult = $pdo->query($judge);

        if($judgeResult->rowCount() > 0) {
            $tip = 2;
            return $tip;
        } else {
            $sql = 'insert travelimagefavor(FavorID, UID, ImageID) value(:favid, :uid, :imgid)';

            $statement = $pdo->prepare($sql);
            $statement->bindValue(':favid', $num);
            $statement->bindValue(':uid', $uid);
            $statement->bindValue(':imgid', $_GET['tool']);
            $statement->execute();
            if ($statement->rowCount() > 0) {
                $tip = 1;
            }
        }

        $pdo = null;
        return $tip;
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}
?>