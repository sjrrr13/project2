<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>Browse</title>
    <link href="../css/reset.css" rel="stylesheet" type="text/css">
    <link href="../css/nav.css" rel="stylesheet" type="text/css">
    <link href="../css/browse.css" rel="stylesheet" type="text/css">
    <link href="../css/footer2.css" id="footerCss" rel="stylesheet" type="text/css">
</head>
<body onload="initCountry()">
<header>
    <nav>
        <a href="index.php" class="nav">Home</a>
        <a href="browse.php" class="current">Browse</a>
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
    <aside>
        <div class="search">
            <form name="searchForm">
            <input type="search" name="searchTitle" placeholder="Search By Title">
            <input type="image" src="../../images/icons/search.jpg">
            </form>
        </div>
        <div class="shortcut">
            <label>Hot Country</label>
            <?php
            require_once "function.php";
            require_once "config.php";

            try {
                    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $countries = array();
                    $popular = getPopular($pdo);

                foreach ($popular as $x => $x_value) {
                    $sql = 'SELECT * FROM travelimage WHERE ImageID=' . $x;
                    $result = $pdo->query($sql);

                    while ($row = $result->fetch()) {
                        global $countries;
                        $country = getCountry($row, $pdo);
                        if($country != "Unknown") {
                            $countries = insertArray($country, $countries);
                        }
                    }
                }

                hotCountry($countries, count($countries));

                $pdo = null;
                } catch(PDOException $e) {
                    die( $e->getMessage() );
                }

                function hotCountry($arr, $num) {
                    echo "<ul><form name='hotCountry'>";
                    for($i = 0; $i < $num - 1; $i++){
                        echo "<li><input type='submit' name='country' value='" . $arr[$i] . "'></li>";
                    }
                    echo "<li class='last'><input type='submit' name='country' value='" . $arr[$num - 1] . "'></li>";
                    echo "</form></ul>";
                }
            ?>
        </div>
        <div class="shortcut">
            <label>Hot City</label>
                <?php
                try {
                    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $cities = array();
                    $popular = getPopular($pdo);

                    foreach ($popular as $x => $x_value) {
                        $sql = 'SELECT * FROM travelimage WHERE ImageID=' . $x;
                        $result = $pdo->query($sql);

                        while ($row = $result->fetch()) {
                            global $cities;
                            $city = getCity($row, $pdo);
                            if($city != "Unknown") {
                                $cities = insertArray($city, $cities);
                            }
                        }
                    }

                    hotCity($cities, count($cities));

                    $pdo = null;
                } catch(PDOException $e) {
                    die( $e->getMessage() );
                }

                function hotCity($arr, $num) {
                    echo "<ul><form name='hotCity'>";
                    for($i = 0; $i < $num - 1; $i++){
                        echo "<li><input type='submit' name='city' value='" . $arr[$i] . "'></li>";
                    }
                    echo "<li class='last'><input type='submit' name='city' value='" . $arr[$num - 1] . "'></li>";
                    echo "</form></ul>";
                }
                ?>
        </div>
        <div class="shortcut">
            <label>Hot Content</label>
                <?php
                try {
                    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $contents = array();
                    $popular = getPopular($pdo);

                    foreach ($popular as $x => $x_value) {
                        $sql = 'SELECT * FROM travelimage WHERE ImageID=' . $x;
                        $result = $pdo->query($sql);

                        while ($row = $result->fetch()) {
                            global $contents;
                            $content = $row['Content'];
                            $contents = insertArray($content, $contents);
                        }
                    }

                    hotContent($contents, count($contents));

                    $pdo = null;
                } catch(PDOException $e) {
                    die( $e->getMessage() );
                }

                function hotContent($arr, $num) {
                    echo "<ul><form name='hotCity'>";
                    for($i = 0; $i < $num - 1; $i++){
                        echo "<li><input type='submit' name='content' value='" . $arr[$i] . "'></li>";
                    }
                    echo "<li class='last'><input type='submit' name='content' value='" . $arr[$num - 1] . "'></li>";
                    echo "</form></ul>";
                }
                ?>
        </div>
    </aside>
    <div class="right">
        <div class="filter">
            <form name="multipleSearch">
            <select id="country" name="chooseCountry" onchange="changeCity()">
                <option value="0">-Choose a country-</option>
            </select>
            <select id="city" name="chooseCity">
                <option value="0">-Choose a city-</option>
            </select>
            <select id="content" name="chooseContent">
                <option value="0">-Choose a content-</option>
                <option value="scenery">Scenery</option>
                <option value="building">Building</option>
                <option value="Animal">Animal</option>
                <option value="People">People</option>
            </select>
            <input type="submit" name="filter" value="Filter">
            </form>
        </div>
            <?php
            require_once "config.php";

            try {
                $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if(isset($_GET["searchTitle"])) {
                    if($_GET["searchTitle"] != "") {
                        outputTitle($_GET["searchTitle"], $pdo);
                    } else {
                        echo '<script>alert("请输入标题")</script>';
                    }
                }

                if(isset($_GET["filter"])) {
                    outputMultiple($pdo);
                }

                if(isset($_GET['country'])) {
                    outputCountry($_GET['country'], $pdo);
                }

                if(isset($_GET['city'])) {
                    outputCity($_GET['city'], $pdo);
                }

                if(isset($_GET['content'])) {
                    outputContent($_GET['content'], $pdo
                    );
                }

                $pdo = null;
             } catch(PDOException $e) {
                    die( $e->getMessage() );
                }

            function outputTitle($title, $pdo) {
                $sql = 'SELECT * FROM travelimage WHERE Title LIKE "%' . $title . '%"';
                $result = $pdo->query($sql);
                $num = $result->rowCount();

                if($num > 0) {
                        outputImage($result, $pdo, $num);
                } else {
                    echo '<script>alert("未搜索到图片")</script>';
                }
            }

            function outputMultiple($pdo) {
                if($_GET['chooseCountry'] != "0" && $_GET['chooseCity'] != "0" ) {
                    $sql0 = 'SELECT GeoNameID FROM geocities WHERE AsciiName="' . $_GET['chooseCity'] . '"';
                    $result0 = $pdo->query($sql0);

                    while($row0 = $result0->fetch()) {
                        $cityCode = $row0['GeoNameID'];
                    }

                    $sql = 'SELECT PATH, ImageID FROM travelimage WHERE CityCode="' . $cityCode . '"';
                    if($_GET['chooseContent'] != "0") {
                        $sql .= ' AND Content="' . $_GET['chooseContent'] . '"';
                    }
                    $result = $pdo->query($sql);
                    $num = $result->rowCount();
                    if($result->rowCount() > 0){
                        outputImage($result, $pdo, $num);
                    } else {
                        echo '<script>alert("该城市还没有图片")</script>';
                    }
                } else {
                    echo '<script>alert("请选择筛选条件")</script>';
                }
            }

            function outputCountry($item, $pdo) {
                $sql0 = 'SELECT ISO FROM geocountries_regions WHERE Country_RegionName="' . $item . '"';
                $result0 = $pdo->query($sql0);
                while($row0 = $result0->fetch()) {
                    $target = $row0['ISO'];
                }

                if($target != "") {
                    $sql = 'SELECT * FROM travelimage WHERE Country_RegionCodeISO="' . $target . '"';
                    $result = $pdo->query($sql);
                    $num = $result->rowCount();
                    outputImage($result, $pdo, $num);
                } else {
                    echo '<script>alert("未搜索到图片")</script>';
                }
            }

            function outputCity($item, $pdo) {
                $sql0 = 'SELECT GeoNameID FROM geocities WHERE AsciiName="' . $item . '"';
                $result0 = $pdo->query($sql0);
                while($row0 = $result0->fetch()) {
                    $target = $row0['GeoNameID'];
                }

                if($target != "") {
                    $sql = 'SELECT * FROM travelimage WHERE CityCode="' . $target . '"';
                    $result = $pdo->query($sql);
                    $num = $result->rowCount();
                    outputImage($result, $pdo, $num);
                } else {
                    echo '<script>alert("未搜索到图片")</script>';
                }
            }

            function outputContent($item, $pdo) {
                $sql = 'SELECT * FROM travelimage WHERE Content="' . $item . '"';
                $result = $pdo->query($sql);
                $num = $result->rowCount();
                outputImage($result, $pdo, $num);
            }

            function outputImage($result, $pdo, $num) {
                $results = array();
                $pages = array();
                $totalPage = ceil($num / 12) > 5 ? 5 : ceil($num / 12);
                if($totalPage > 1) {
                    while ($row = $result->fetch()) {
                        array_push($results, $row['ImageID']);
                    }
                    for($i = 0; $i <$totalPage - 1; $i++) {
                        $pages[$i] = array();
                        for($j = $i * 12; $j < ($i + 1) * 12; $j++) {
                             array_push($pages[$i], $results[$j]);
                        }
                    }
                    $pages[$totalPage - 1] = array();
                    $num = min(count($results), ($totalPage - 1) * 12 + 12);
                    for($k = ($totalPage - 1) * 12; $k < $num; $k++) {
                        array_push($pages[$totalPage - 1], $results[$k]);
                    }

                    echo '<div class="images" id="images" style="display: inline;">';
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
                        echo '</div>';
                    }
                    echo '</div>'; //end class=images

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
                    echo '<div class="images">';
                    echo '<div class="container">';
                    while ($row = $result->fetch()) {
                        outputSingle($row['ImageID'], $pdo);
                    }
                    echo '</div>'; //end class=container
                    echo '</div>'; //end class=images
                }
            }

            function outputSingle($id, $pdo) {
                $sql = 'SELECT PATH FROM travelimage WHERE ImageID=' . $id;
                $result = $pdo->query($sql);
                while($row = $result->fetch()) {
                    $path = $row['PATH'];
                }

                echo "<div class='item'>";
                $img = '<img src="../../images/travel-images/small/' . $path . '">';
                echo constructDetailLink($id, $img);
                echo "</div>";
            }
            ?>
    </div>
</section>
<footer>
    Copyright&copy;2020web应用基础19302010035佘家瑞
</footer>
<script src="turnPage.js"></script>
<script>
    let cities = {};
    getJson();
    let length = Object.keys(cities).length;

    function initCountry() {
        let countryObj = document.getElementById("country");
        for(let key in cities){
            let option = new Option(key, key);
            countryObj.add(option);
        }
    }

    function changeCity() {
        let countryName = document.getElementById("country").value;
        let city = document.getElementById("city");
        city.innerHTML = "";
        for(let key in cities){
            if(key == countryName){
                let value = cities[key];
                for(let i in value){
                    let option = new Option(value[i], value[i]);
                    city.add(option);
                }
            }
        }
    }

    function getJson() {
        let xhr = new XMLHttpRequest();
        xhr.open('GET', 'json.php', false);
        xhr.onreadystatechange = function() {
            if(xhr.readyState == 4 && xhr.status==200) {
                let resText = xhr.responseText;
                let jsonObj = JSON.parse(resText);
                loadJson(jsonObj);
            }
        };
        xhr.send(null);
    }

    function loadJson(_json) {
        Object.keys(_json).forEach(key => {
            let country = _json[key];
            if(cities[country]){
                cities[country].push(key);
            } else {
                cities[country] = [key];
            }
        });
    }

    function chooseCss() {
        let height = window.screen.availHeight;
        let imgHeight = document.body.clientHeight;
        console.log(imgHeight);
        let css = document.getElementById("footerCss");
        if(height > imgHeight) {
            css.setAttribute("href", "../css/footer1.css");
        } else {
            css.setAttribute("href", "../css/footer2.css");
        }
    }

    chooseCss();
</script>
</body>
</html>