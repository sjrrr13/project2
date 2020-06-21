<?php
//获取导航栏下拉列表的函数
function getDropDown()
{
    return '<Label class="drop">My Account</Label>
                <div class="dropdown-content">
                <a href="post.php"><img src="../../images/icons/post.jpg" alt="post" class="icon">
                    Post</a>
                <a href="myAlbum.php"><img src="../../images/icons/album.jpg" alt="post" class="icon">
                    My Album</a>
                <a href="favorite.php"><img src="../../images/icons/favorite.jpg" alt="post" class="icon">
                    My Favorite</a>
                <a href="logOut.php"><img src="../../images/icons/login.jpg" alt="post" class="icon">
                    Log Out</a>
            </div>';
}

//获取图片描述的函数
function getDescription($row, $pdo) {
    if($row['Description'] == null) {
        return "No Description";
    } else {
        return $row['Description'];
    }
}

//建立图片链接的函数
function constructDetailLink($id, $label) {
    $link = '<a href="detail.php?id=' . $id . '">';
    $link .= $label;
    $link .= '</a>';
    return $link;
}

//获取热门图片的函数
function getPopular($pdo) {
    $popular = array();
    $sql = 'SELECT DISTINCT ImageID FROM travelimagefavor';
    $result = $pdo->query($sql);

    while ($row1 = $result->fetch()) {
        $imageId = $row1['ImageID'];
        $sql2 = 'SELECT * FROM travelimagefavor WHERE ImageID=' . $imageId;
        $result2 = $pdo->query($sql2);
        $favorNum = $result2->rowCount();
        $popular[$imageId] = $favorNum;
    }

    arsort($popular);
    return $popular;
}

//获取城市名称的函数
function getCity($row, $pdo) {
    if ($row['CityCode']) {
        $sql = 'SELECT AsciiName FROM geocities WHERE GeoNameID=' . $row['CityCode'];
    } elseif($row['Latitude'] != "") {
        $sql = 'SELECT AsciiName FROM geocities WHERE Latitude=' . $row['Latitude'] . ' AND Longitude=' . $row['Longitude'];
    } else {
        return "Unknown";
    }
    $result = $pdo->query($sql);
    if($result->rowCount() > 0) {
        while ($row1 = $result->fetch()) {
            return $row1['AsciiName'];
        }
    } else {
        return "Unknown";
    }
}

//获取国家名称的函数
function getCountry($row, $pdo) {
    if($row['Country_RegionCodeISO']) {
        $sql = 'SELECT Country_RegionName FROM geocountries_regions WHERE ISO=' . "'" . $row['Country_RegionCodeISO'] . "'";
        $result = $pdo->query($sql);
        if($result->rowCount() > 0) {
            while ($row1 = $result->fetch()) {
                return $row1['Country_RegionName'];
            }
        } else {
            return "Unknown";
        }
    } else {
        return "Unknown";
    }
}

//将不重复元素添加到数组的函数
function insertArray($item, $arr) {
    if(!in_array($item, $arr)) {
        array_push($arr, $item);
    }
    return $arr;
}

//获取用户ID编号的函数
function getUserID($pdo) {
    $sql = 'SELECT UID FROM traveluser WHERE UserName="' . $_SESSION['UserName'] . '"';
    $result = $pdo->query($sql);

    while($row = $result->fetch()) {
        $uid = $row['UID'];
    }
    return $uid;
}
?>