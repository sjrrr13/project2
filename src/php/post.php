<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>Post</title>
    <link href="../css/reset.css" rel="stylesheet" type="text/css">
    <link href="../css/nav.css" rel="stylesheet" type="text/css">
    <link href="../css/post.css" rel="stylesheet" type="text/css">
    <link href="../css/footer2.css" rel="stylesheet" type="text/css">

</head>
<body onload="initCountry()">
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
    if(isset($_GET['tool'])) {
        getModify($_GET['tool']);
    } else {
        getPane();
    }

    function getPane() {
        $code = '<form action="../../images/travel-images/getPost.php" method="post" enctype="multipart/form-data">
        <div class="container">
            <div id="tip">Picture not uploaded</div>
            <div class="preview" id="content"></div>
            <div class="button"><span>Choose a picture</span><br>
                <input type="file" name="file" accept="image/*" onchange="uploadImg(event,this)">
            </div>
        </div>
        <div class="info">
            <div class="tip">Title</div>
            <input type="text" name="title" id="title" class="description">
            <div class="tip">Description</div>
            <textarea class="description" id="des" name="description" rows="3"></textarea>
            <div class="tip">Country</div>
            <select class="description" id="country" name="country" onchange="changeCity()">
                <option value="0">-Choose a country-</option>
            </select>
            <div class="tip">City</div>
            <select class="description" id="city" name="city">
                <option value="0">-Choose a city-</option>
            </select>
            <div class="tip">Content</div>
     
            <select class="description" id="content" name="content">
                <option value="0" selected>-Choose a content-</option>
                <option value="scenery">Scenery</option>
                <option value="city">City</option>
                <option value="people">People</option>
                <option value="animal">Animal</option>
                <option value="building">Building</option>
                <option value="wonder">Wonder</option>
                <option value="other">Other</option>
            </select>
            <input type="submit" name="postBtn" value="Upload" onclick="return check()">
        </div>
    </form>';
        echo $code;
    }

    function getModify($id) {
        try{
            $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = 'SELECT * FROM travelimage WHERE ImageID=' . $id;
            $result = $pdo->query($sql);

            while($row = $result->fetch()) {
                $path = $row['PATH'];
                $title = $row['Title'];
                $des = $row['Description'];
                $country = getCountry($row, $pdo);
                $city = getCity($row, $pdo);
                $content = $row['Content'];
            }
        } catch (PDOException $e) {
            die( $e->getMessage() );
        }

        echo '<form action="../../images/travel-images/getPost.php" method="post" enctype="multipart/form-data">';
        echo '<div class="container">';
        echo '<div class="preview" id="content">';
        $img = '<img src="../../images/travel-images/small/' . $path . '">';
        echo $img;
        echo '</div>'; //end class=preview
        echo '</div>'; //end class=container
        echo '<div class="info">';
        echo '<div class="tip">Title</div>';
        echo '<input type="text" name="title" id="title" class="description" value="' . $title . '">';
        echo '<div class="tip">Description</div>';
        echo '<textarea class="description" id="des" name="description" rows="3">' . $des . '</textarea>';
        echo '<div class="tip">Country</div>';
        echo '<select class="description" id="country" name="country" onchange="changeCity()">';
        echo '<option value="' . $country . '">' . $country . '</option>';
        echo '</select>';
        echo '<div class="tip">City</div>';
        echo '<select class="description" id="city" name="city">';
        echo '<option value="' . $city . '">' . $city . '</option>';
        echo '</select>';
        echo '<div class="tip">Content</div>';
        echo '<select class="description" id="content" name="content">';
        echo '<option value="scenery">Scenery</option>';
        echo '<option value="city">City</option>';
        echo '<option value="people">People</option>';
        echo '<option value="animal">Animal</option>';
        echo '<option value="building">Building</option>';
        echo '<option value="wonder">Wonder</option>';
        echo '<option value="other">Other</option>';
        echo '</select>';
        echo '<script> let item = document.getElementById("content");
              for(let i = 0; i < 7; i++) {
                  item.options[i].selected = item.options[i].value == ' . $content . '} </script>';
        echo '<input type="text" id="tool" name="tool" value="' . $id . '">';
        echo '<input type="submit" name="modifyBtn" value="Modify" onclick="return check()">';
        echo '</div>';
        echo '</form>';
    }
    ?>

</section>
<footer>
    Copyright&copy;2020web应用基础19302010035佘家瑞
</footer>
<script src="city.js"></script>
<script>
    let URL = window.URL || window.webkitURL || window.mozURL;
    let countryObj = document.getElementById("country");
    let city = document.getElementById("city");
    let title = document.getElementById("title");
    let des = document.getElementById("des");
    let content = document.getElementById("content");

    function uploadImg(e,dom) {
        document.getElementById("content").innerHTML = "";
        var e = event || e;
        let fileObj = dom instanceof HTMLElement ? dom.files[0] : $(dom)[0].files[0];
        console.log(e);
        console.log(dom);
        let container = document.querySelector('.preview');
        let img = new Image();
        img.src = URL.createObjectURL(fileObj);
        console.log(img.src);
        img.onload = function() {
            container.appendChild(img)
        };
        document.getElementById("tip").style.display = "none";
    }

    function initCountry() {
        for(let key in cities){
            let option = new Option(key, key);
            countryObj.add(option);
        }
    }

    function changeCity() {
        let countryName = document.getElementById("country").value;
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

    function check() {
        let canPost = true;
        if(title == "" || des == "" || countryObj.value == "0" || city.value == "0" || content.value == "0") {
            alert("请输入完整的内容后再提交");
            canPost = false;
        }
        return canPost;
    }
</script>
</body>
</html>