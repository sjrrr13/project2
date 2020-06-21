<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>Detail</title>
    <link href="../css/reset.css" rel="stylesheet" type="text/css">
    <link href="../css/nav.css" rel="stylesheet" type="text/css">
    <link href="../css/detail.css" rel="stylesheet" type="text/css">
    <link id="footerCss" href="../css/footer2.css" rel="stylesheet" type="text/css">
</head>
<body onload="chooseCss()">
<div class="whole">
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
        <?php
        require_once "config.php";
        require_once "function.php";

        function output() {
            try {
                $imageId = $_GET["id"];
                $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql1 = 'SELECT * FROM travelimage WHERE ImageID=' . $imageId;
                $result1 = $pdo->query($sql1);


                while ($row1 = $result1->fetch()) {
                    detail($row1, $pdo, $imageId, $_SESSION['UserName']);
                }

                $pdo = null;
            } catch (PDOException $e) {
                die( $e->getMessage() );
            }
        }

        function detail($row, $pdo, $img, $unm) {
            $uid = getUserID($pdo);
            $sql = 'SELECT * FROM travelimagefavor WHERE ImageID=' . $img . ' AND UID=' . $uid;
            $result = $pdo->query($sql);
            $added = false;
            if($result->rowCount() > 0) {
                $added = true;
            }

            echo '<div class="title">';
            echo $row['Title'] . "<span>by " . getUser($row, $pdo) . "</span>";
            echo '</div>'; //end class=title
            echo '<div class="show_area">';
            echo '<img id="image" src="../../images/travel-images/large/' . $row['PATH'] . '">';
            echo '<div class="detail">';
            echo '<table><tr><th>Country:</th><td>' . getCountry($row, $pdo) . '</td></tr>';
            echo '<tr><th>City:</th><td>' . getCity($row, $pdo) . '</td></tr>';
            echo '<tr><th>Content:</th><td>' . $row['Content'] . '</td></tr></table>';
            echo '<form name="favorite" action="submitFavor.php">';
            echo '<input name="tool" value="' . $row['ImageID'] . '">';
            if($added) {
                echo '<input type="button" value="Add To Favorite" onclick="alert(\'' . '您已经收藏过该图片了' . '\')">';
            } else {
                echo '<input type="submit" value="Add To Favorite">';
            }
            echo '</form>';
            echo '<div class="liked">Like Number: <span>' . getFavorNum($row, $pdo) . '</span></div>';
            echo'</div>'; //end class=detail
            echo'</div>'; //end class=show_area
            echo '<div class="description">';
            echo '<span>Description:&nbsp;&nbsp;</span>';
            echo getDescription($row, $pdo);
            echo '</div>'; //end class=description
        }

        function getUser($row, $pdo) {
            if($row['UID']){
                $sql = 'SELECT UserName FROM traveluser WHERE UID=' . $row['UID'];
                $result = $pdo->query($sql);
                if($result->rowCount() > 0) {
                    while ($row1 = $result->fetch()) {
                        return $row1['UserName'];
                    }
                } else {
                    return "Nobody";
                }
            } else {
                return "Nobody";
            }

        }

        function getFavorNum($row, $pdo) {
            $sql = 'SELECT * FROM travelimagefavor WHERE ImageID=' . $row['ImageID'];
            $result = $pdo->query($sql);
            $favorNum = $result->rowCount();
            return $favorNum;
        }

        output();
        ?>
</section>
</div>
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