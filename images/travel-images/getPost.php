<?php
session_start();
require_once "../../src/php/config.php";
require_once "../../src/php/function.php";

if(isset($_SESSION['UserName'])) {
    if (isset($_POST['postBtn'])) {
        postImage();
    }
    if(isset($_POST['modifyBtn'])) {
        modifyImage($_POST['tool']);
    }
} else {
    echo '<script>alert("请先登录");location.href="../../src/php/post.php"</script>';
}

function postImage() {
    try {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql1 = "SELECT ImageID FROM travelimage";
        $result1 = $pdo->query($sql1);
        $num = 0;
        while($row1 = $result1->fetch()) {
            $num = max($num, $row1['ImageID']);
        }
        $num += 1;

        $sql2 = 'SELECT ISO From geocountries_regions WHERE Country_RegionName="' . $_POST['country'] . '"';
        $result2 = $pdo->query($sql2);
        while($row2 = $result2->fetch()) {
            $iso = $row2['ISO'];
        }

        $sql3 = 'SELECT GeoNameID From geocities WHERE AsciiName="' . $_POST['city'] . '"';
        $result3 = $pdo->query($sql3);
        while($row3 = $result3->fetch()) {
            $city = $row3['GeoNameID'];
        }

        $uid = getUserID($pdo);

        $temp = explode(".", $_FILES["file"]["name"]);
        $extension = end($temp);
        $path = $_POST['title'] . "." . $extension;

        $sql = "INSERT travelimage(ImageID, Title, Description, CityCode, Country_RegionCodeISO, UID, PATH, Content)" .
            " value(:imgid, :title, :des, :city, :country, :uid, :path, :content)";

        $statement = $pdo->prepare($sql);
        $statement->bindValue(':imgid', $num);
        $statement->bindValue(':title', $_POST['title']);
        $statement->bindValue(':des', $_POST['description']);
        $statement->bindValue(':city', $city);
        $statement->bindValue(':country', $iso);
        $statement->bindValue(':uid', $uid);
        $statement->bindValue(':path', $path);
        $statement->bindValue(':content', $_POST['content']);
        $statement->execute();

        //将图片添加到本地文件夹
        $allowedExts = array("gif", "jpeg", "jpg", "png");
        $temp = explode(".", $_FILES["file"]["name"]);
        $extension = end($temp);     // 获取文件后缀名
        if ((($_FILES["file"]["type"] == "image/gif")
                || ($_FILES["file"]["type"] == "image/jpeg")
                || ($_FILES["file"]["type"] == "image/jpg")
                || ($_FILES["file"]["type"] == "image/pjpeg")
                || ($_FILES["file"]["type"] == "image/x-png")
                || ($_FILES["file"]["type"] == "image/png"))
            && in_array($extension, $allowedExts)) {
            if ($_FILES["file"]["error"] > 0) {
                echo "<script>alert('错误: " . $_FILES["file"]["error"] . "')</script>";
            } else {
                // 判断当前small目录下是否存在该文件
                if (file_exists("small/" . $_POST['title'] . "." . $extension)) {
                    echo '<script>alert("文件已经存在");location.href="../../src/php/myAlbum.php"</script>';
                } else {
                    // 如果small目录下不存在该文件则将文件上传，同时上传到large目录
                    copy($_FILES["file"]["tmp_name"],"small/" . $path);
                    copy($_FILES["file"]["tmp_name"],"large/" . $path);
                    echo '<script>alert("上传成功");location.href="../../src/php/myAlbum.php"</script>';
                }
            }
        } else {
            echo '<script>alert("非法的文件格式")</script>';
        }

        if ($statement->rowCount() > 0) {
            $pdo = null;
            return true;
        }else {
            $pdo = null;
            return false;
        }
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}

function modifyImage($id) {
    try {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql0 = 'SELECT GeoNameID FROM geocities WHERE AsciiName="' . $_POST['city'] . '"';
        $result0 = $pdo->query($sql0);
        while($row0 = $result0->fetch()) {
            $city = $row0['GeoNameID'];
        }

        $sql1 = 'SELECT ISO FROM geocountries_regions WHERE Country_RegionName="' . $_POST['country'] . '"';
        $result1 = $pdo->query($sql1);
        while($row1 = $result1->fetch()) {
            $country = $row1['ISO'];
        }

        $sql = 'UPDATE travelimage SET Title="' . $_POST['title'] . '", '
        . 'Description="' . $_POST['description'] . '", '
        . 'CityCode="' . $city . '", Country_RegionCodeISO="' . $country . '", '
        . 'Content="' . $_POST['content'] . '" WHERE ImageID=' . $id;
        $result = $pdo->exec($sql);
        if($result > 0) {
            echo '<script>alert("修改数据成功");location.href="../../src/php/myAlbum.php"</script>';
        } else {
            echo '<script>alert("修改数据失败");location.href="../../src/php/myAlbum.php"</script>';
        }

        $pdo = null;
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}
?>
