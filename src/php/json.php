<?php
require_once "config.php";
require_once "function.php";
$city_country = array();
$countryNum = array();

try {
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $popular = getPopular($pdo);

    foreach ($popular as $x => $x_value) {
        $sql = 'SELECT * FROM travelimage WHERE ImageID=' . $x;
        $result = $pdo->query($sql);
        while ($row = $result->fetch()) {
            insert($row, $pdo);
        }
    }


        while(count($countryNum) < 15) {
            $sql1 = 'SELECT ISO, Country_RegionName FROM geocountries_regions ORDER BY RAND() LIMIT 10';
            $result1 = $pdo->query($sql1);

            while ($row1 = $result1->fetch()) {
                $newCountry = $row1['Country_RegionName'];
                $newID = $row1['ISO'];
                if(!in_array($newCountry,$countryNum)) {
                    $sql2 = 'SELECT DISTINCT AsciiName FROM geocities WHERE Country_RegionCodeISO="' .
                    $newID . '" Order BY RAND() LIMIT 8';
                    $result2 = $pdo->query($sql2);
                    while ($row2 = $result2->fetch()) {
                        $newCity = $row2['AsciiName'];
                        if($newCity != "Unknown" && $newCountry != "Unknown") {
                            $city_country[$newCity] = $newCountry;
                        }
                    }
                    array_push($countryNum, $newCountry);
                }
            }
        }

    $pdo = null;
} catch(PDOException $e) {
    die( $e->getMessage() );
}

function insert($row, $pdo) {
    global $city_country, $countryNum;
    $city = getCity($row, $pdo);
    $country = getCountry($row, $pdo);
    if($city != "Unknown" && $country != "Unknown") {
        $city_country[$city] = $country;
        if(!in_array($country, $countryNum)) {
            array_push($countryNum, $country);
        }
    }
}

// 转换成json数据存储格式
$jsonCityToCountry = json_encode($city_country);
echo $jsonCityToCountry;
?>