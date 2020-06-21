<?php
session_start();
require_once "config.php";

    try {
        $pdo =  $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $url = "favorite.php";
        if (isset($_GET['tool'])) {
            if (deleteFavor($_SESSION['UserName'], $_GET['tool'], $pdo)) {
                echo '<script>alert("取消收藏成功");location.href="' . $url . '"</script>';
            } else {
                echo '<script>alert("取消收藏失败");location.href="' . $url . '"</script>';
            }
        }

        $url2 = "myAlbum.php";
        if (isset($_GET['deleteTool'])) {
            if (deleteMine($_SESSION['UserName'], $_GET['deleteTool'], $pdo)) {
                echo '<script>alert("删除成功");location.href="' . $url2 . '"</script>';
            } else {
                echo '<script>alert("删除失败");location.href="' . $url2 . '"</script>';
            }
        }

        $pdo = null;
    } catch (PDOException $e) {
        die( $e->getMessage() );
    }

    function deleteFavor($unm, $imgid, $pdo) {
        $sql0 = 'SELECT UID FROM traveluser WHERE UserName="' . $unm . '"';
        $result0 = $pdo->query($sql0);
        while($row = $result0->fetch()) {
            $uid = $row['UID'];
        }

        $sql1 = 'DELETE FROM travelimagefavor WHERE ImageID=' . $imgid . ' AND UID=' . $uid;
        $result1 = $pdo->query($sql1);
        if($result1->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function deleteMine($unm, $imgid, $pdo) {
        $sql0 = 'SELECT UID FROM traveluser WHERE UserName="' . $unm . '"';
        $result0 = $pdo->query($sql0);
        while($row = $result0->fetch()) {
            $uid = $row['UID'];
        }

        $sql1 = 'DELETE FROM travelimagefavor WHERE ImageID=' . $imgid . ' AND UID=' . $uid;
        $sql2 = 'DELETE FROM travelimage WHERE ImageID=' . $imgid;
        $result1 = $pdo->query($sql1);
        $result2 = $pdo->query($sql2);
        if($result2->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
?>
