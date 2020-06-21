<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link href="../css/reset.css" rel="stylesheet" type="text/css">
    <link href="../css/nav.css" rel="stylesheet" type="text/css">
    <link href="../css/home.css" rel="stylesheet" type="text/css">
</head>
<body>
<!--回到顶部按钮的链接-->
<a href="top"></a>
<header>
    <!--所有页面导航栏设置如下，仅根据页面修改class值-->
    <nav>
        <a href="index.php" class="current">Home</a>
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
<section class="content">
    <div class="big">
        <img src="../../images/travel-images/large/6114904363.jpg" alt="photo">
    </div>
    <div class="images" id="images">
        <?php
        require_once "config.php";

        //输出图片的总函数
        function outputInfo() {
            try {
                $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $popular = getPopular($pdo);
                if(count($popular) < 6) {
                    foreach ($popular as $x=>$x_value) {
                        $sql3 = 'SELECT * FROM travelimage WHERE ImageID=' . $x;
                        $result3 = $pdo->query($sql3);
                        while($row3 = $result3->fetch()) {
                            outputSingleInfo($row3, $pdo);
                        }
                    }
                    outputRandom(6 - count($popular), $pdo);
                } else {
                    $time = 0;
                    foreach ($popular as $x=>$x_value) {
                        $sql3 = 'SELECT * FROM travelimage WHERE ImageID=' . $x;
                        $result3 = $pdo->query($sql3);
                        while($row3 = $result3->fetch()) {
                            outputSingleInfo($row3, $pdo);
                        }
                        $time++;
                        if($time == 6) {
                            break;
                        }
                    }
                }

                $pdo = null;
            } catch (PDOException $e) {
                die( $e->getMessage() );
            }
        }

        //输出单张图片
        function outputSingleInfo($row, $pdo) {
            echo '<div class="block">';
            $img = '<img src="../../images/travel-images/small/' . $row['PATH'] . '">';
            echo constructDetailLink($row['ImageID'], $img);
            echo '<div>';
            echo '<span class="title">' . $row['Title'] . '</span><br>';
            echo '<div class = info>';
            echo getDescription($row, $pdo);
            echo '</div>'; //end class=info
            echo '</div>';
            echo '</div>'; // end class=block
        }

        //随机输出图片的函数
        function outputRandom($num, $pdo) {
            $sql = 'SELECT * FROM travelimage ORDER BY RAND() LIMIT ' . $num;
            $result = $pdo->query($sql);

            while($row = $result->fetch()) {
                outputSingleInfo($row, $pdo);
            }
        }

        if(!isset($_GET['id'])){
            outputInfo();
        } else {
            try {
                $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                outputRandom(6, $pdo);
            } catch (PDOException $e) {
                die( $e->getMessage() );
            }
        }
        ?>
    </div>
</section>
<footer>
    <div >
        使用条款<br><br>
        隐私保护
    </div>
    <div>
        帮助<br><br>
        联系我们
    </div>
    <div>
        <img src="../../images/icons/QRcode.png" alt="QRcode">
    </div>
    <div class="bottom">
        Copyright&copy;2020web应用基础19302010035佘家瑞
    </div>
</footer>
<div class="button">
    <a href="index.php?id=1"><img src="../../images/icons/refresh.jpg" alt="refresh"></a>
    <a href="#top" id="top"><img src="../../images/icons/top.jpg" alt="top"></a>
</div>
</body>
</html>
