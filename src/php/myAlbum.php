<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>Favorite</title>
    <link href="../css/reset.css" rel="stylesheet" type="text/css">
    <link href="../css/nav.css" rel="stylesheet" type="text/css">
    <link href="../css/my.css" rel="stylesheet" type="text/css">
    <link href="../css/footer2.css" id="footerCss" rel="stylesheet" type="text/css">
</head>
<body onload="chooseCss()">
<header>
    <nav>
        <a href="index.php" class="nav">Home</a>
        <a href="browse.php" class="nav">Browse</a>
        <a href="search.php" class="nav">Search</a>
        <div class="dropdown">
            <?php
            require_once "function.php";
            session_start();
            if(isset($_SESSION['UserName'])) {
                echo getDropDown();
            } else {
                echo '<a id="log" href="../html/logIn.html">Log In</a>';
            }
            ?>
        </div>
    </nav>
</header>
<section>
    <div class="fav">
        My Album
    </div>
    <?php
    require_once "config.php";
    require_once "function.php";

    try{
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $uid = getUserID($pdo);

        $sql1 = 'SELECT * FROM travelimage WHERE UID=' . $uid;
        $result1 = $pdo->query($sql1);

        if($result1->rowCount() > 0) {
            while($row1 = $result1->fetch()) {
                outputMyImage($row1, $pdo);
            }
        } else {
            echo '<script>alert("您还未上传任何照片")</script>';
        }

        $pdo = null;
    } catch (PDOException $e) {
        die( $e->getMessage() );
    }

    function outputMyImage($row, $pdo) {
        echo '<div class="block">';
        $img = '<img src="../../images/travel-images/small/' . $row['PATH'] . '">';
        echo constructDetailLink($row['ImageID'], $img);
        echo '<div class="content">';
        echo '<div class="title">' . $row['Title'] . '</div>';
        echo '<div class="info">';
        echo $row['Description'] . '</div>';
        echo '<div class="button">';
        echo '<form action="post.php"><input name="tool" value="' . $row['ImageID'] . '">';
        echo '<input type="submit" value="Modify"></form>';
        echo '<form action="operate.php"><input name="deleteTool" value="' . $row['ImageID'] . '">';
        echo '<input type="submit" value="Delete"></form>';
        echo '</div>'; //end class=button
        echo '</div>'; //end class=content
        echo '</div>'; //end class=block
    }
    ?>
</section>
<footer>
    Copyright&copy;2020web应用基础19302010035佘家瑞
</footer>
<script>
    function chooseCss() {
        let height = window.screen.availHeight;
        let imgHeight = document.body.clientHeight;
        let css = document.getElementById("footerCss");
        if(height > imgHeight) {
            css.setAttribute("href", "../css/footer1.css");
        } else {
            css.setAttribute("href", "../css/footer2.css");
        }
    }
</script>
</body>
</html>