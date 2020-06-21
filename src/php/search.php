<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>Search</title>
    <link href="../css/reset.css" rel="stylesheet" type="text/css">
    <link href="../css/nav.css" rel="stylesheet" type="text/css">
    <link href="../css/search.css" rel="stylesheet" type="text/css">
    <link href="../css/footer2.css" id="footerCss" rel="stylesheet" type="text/css">
</head>
<body onload="chooseCss()">
<header>
    <nav>
        <a href="index.php" class="nav">Home</a>
        <a href="browse.php" class="nav">Browse</a>
        <a href="search.php" class="current">Search</a>
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
    <div class="search_area">
        <form>
        <label><input name="method" type="radio" value="Filter By Title" checked>Filter By Title</label>
        <input name="search_title" type="text">
        <label><input name="method" type="radio" value="Filter By Description">Filter By Description</label>
        <textarea name="search_description" rows="5"></textarea>
        <input type="submit" value="Filter">
        </form>
    </div>
    <div class="result_area">
            <?php
            require_once "config.php";
            require_once "function.php";

            try {
                $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if(isset($_GET['method'])) {
                    if($_GET['method'] == "Filter By Title") {
                        if(isset($_GET['search_title'])) {
                            outputTitle($_GET['search_title'], $pdo);
                        }
                    }
                    if($_GET['method'] == "Filter By Description") {
                        if(isset($_GET['search_description'])) {
                            outputDescription($_GET['search_description'], $pdo);
                        }
                    }
                }

                $pdo = null;
            } catch (PDOException $e) {
                die( $e->getMessage() );
            }

            function outputTitle($title, $pdo) {
                $sql = 'SELECT * FROM travelimage WHERE Title LIKE "%' . $title . '%"';
                $result = $pdo->query($sql);

                if($result->rowCount() > 0) {
                    while($row = $result->fetch()) {
                        outputResult($row, $pdo);
                    }
                } else {
                    echo '<script>alert("未搜索到图片")</script>';
                }
            }

            function outputDescription($des, $pdo) {
                $sql = 'SELECT * FROM travelimage WHERE Description LIKE "%' . $des . '%"';
                $result = $pdo->query($sql);

                if($result->rowCount() > 0) {
                    while($row = $result->fetch()) {
                        outputResult($row, $pdo);
                    }
                } else {
                    echo '<script>alert("未搜索到图片")</script>';
                }
            }

            function outputResult($row, $pdo) {
                echo '<div class="result">';
                $img = '<img src="../../images/travel-images/small/' . $row['PATH'] . '">';
                echo constructDetailLink($row['ImageID'], $img);
                echo '<div class="description">';
                echo '<div class="title">' . $row['Title'] . '</div>';
                echo '<div class="detail">';
                echo $row['Description'];
                echo '</div>'; //end class=detail
                echo '</div>'; //end class=description
                echo '</div>'; //end class=result
            }
            ?>
    </div>
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