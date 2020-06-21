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
<div class="wrap">
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
        My Favorite
    </div>
        <?php
        require_once "config.php";
        require_once "function.php";

        try{
            $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $uid = getUserID($pdo);

            $sql1 = 'SELECT * FROM travelimagefavor WHERE UID=' . $uid;
            $result1 = $pdo->query($sql1);
            $num = $result1->rowCount();

            if($num == 0) {
                echo '<script>alert("没有收藏的图片")</script>';
            } else {
                $imgResults = array();
                while($row1 = $result1->fetch()) {
                    $sql = 'SELECT * FROM travelimage WHERE ImageID=' . $row1['ImageID'];
                    $result = $pdo->query($sql);
                    array_push($imgResults, $result);
                }
                outputFavorImage($imgResults, $pdo, $num);
            }

            $pdo = null;
        } catch (PDOException $e) {
            die( $e->getMessage() );
        }

        function outputFavorImage($imgResults, $pdo, $num) {
            $results = array();
            $pages = array();
            $totalPage = ceil($num / 4) > 5 ? 5 : ceil($num / 4);
            if($totalPage > 1) {
                for($i = 0; $i < count($imgResults); $i++){
                    while ($row = $imgResults[$i]->fetch()) {
                        array_push($results, $row['ImageID']);
                    }
                }

                for($i = 0; $i <$totalPage - 1; $i++) {
                    $pages[$i] = array();
                    for($j = $i * 4; $j < ($i + 1) * 4; $j++) {
                        array_push($pages[$i], $results[$j]);
                    }
                }
                $pages[$totalPage - 1] = array();
                $nums = min(count($results), ($totalPage - 1) * 4 + 4);
                for($k = ($totalPage - 1) * 4; $k < $nums; $k++) {
                    array_push($pages[$totalPage - 1], $results[$k]);
                }

                echo '<div class="container" id="1">';
                for($j = 0; $j < count($pages[0]); $j++) {
                    outputSingle($pages[0][$j], $pdo);
                }
                echo '</div>'; //end id=1
                for($i = 1; $i < $totalPage; $i++) {
                    $k = $i + 1;
                    echo '<div class="container" id="'. $k. '" style="display: none;">';
                    for($j = 0; $j < count($pages[$i]); $j++) {
                        outputSingle($pages[$i][$j], $pdo);
                    }
                    echo '</div>'; //end class=container
                }

                echo '<div class="page">';
                echo '<a href="javascript:void(0);" onclick="pre()" id="pre" >&nbsp &lt &nbsp</a>';
                echo '<a href="javascript:void(0);" style="color:blue" onclick="flip(event)" id="11" >&nbsp 1 &nbsp</a>';
                for($i = 1; $i < $totalPage; $i++) {
                    $k = $i + 11;
                    $l = $i + 1;
                    echo '<a href="javascript:void(0);" onclick="flip(event)" id="' . $k .'">&nbsp' . $l . '&nbsp</a>';
                }
                echo '<a href="javascript:void(0);" onclick="next(event)" id="next" >&nbsp &gt &nbsp</a>';
                echo '</div>'; //end class=page
            } else {
                echo '<div class="container">';
                for($i = 0; $i < count($imgResults); $i++){
                    while ($row = $imgResults[$i]->fetch()) {
                        outputSingle($row['ImageID'], $pdo);
                    }
                }
                echo '</div>'; //end class=container
            }
        }

        function outputSingle($id, $pdo) {
            $sql = 'SELECT * FROM travelimage WHERE ImageID=' . $id;
            $result = $pdo->query($sql);

            while($row = $result->fetch()) {
                echo '<div class="block" onload="chooseCss()">';
                $img = '<img src="../../images/travel-images/small/' . $row['PATH'] . '">';
                echo constructDetailLink($row['ImageID'], $img);
                echo '<div class="content">';
                echo '<div class="title">' . $row['Title'] . '</div>';
                echo '<div class="info">';
                echo $row['Description'] . '</div>';
                echo '<div class="button">';
                echo '<form action="operate.php"><input name="tool" value="' . $row['ImageID'] . '">';
                echo '<input type="submit" value="Delete"></form>';
                echo '</div>'; //end class=button
                echo '</div>'; //end class=content
                echo '</div>'; //end class=block
            }
        }
        ?>
</section>
<footer>
    Copyright&copy;2020web应用基础19302010035佘家瑞
</footer>
</div>
<script src="turnPage.js"></script>
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