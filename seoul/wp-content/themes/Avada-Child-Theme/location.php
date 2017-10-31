<?php
/**
 * Template name: location view
 */

use GuzzleHttp\Client;
use App\Dong;


$dong = Dong::where('sido', '서울특별시')->groupBy('gugun')->get();

foreach ($dong as $key => $value) {
    $list[$value['gugun']] = [
        'sido' => $value['sido'],
        'gugun' => $value['gugun'],
        'latitude' => $value['latitude'],
        'longitude' => $value['longitude'],
    ];
}

$gu = [
    "도봉구" => '',
    "노원구" => '',
    "강북구" => '',
    "중랑구" => '',
    "성북구" => '',
    "강동구" => '',
    "광진구" => '',
    "성동구" => '',
    "송파구" => '',
    "강남구" => '',
    "서초구" => '',
    "용산구" => '',
    "종로구" => '',
    "중구" => '',
    "은평구" => '',
    "서대문구" => '',
    "마포구" => '',
    "영등포구" => '',
    "동대문구" => '',
    "동작구" => '',
    "관악구" => '',
    "금천구" => '',
    "구로구" => '',
    "양천구" => '',
    "강서구" => '',
];


$queryString = "?filter=latitude:37.4938168~37.5238694||longitude:126.9833093~127.0380555&zoomLevel=5&center={\"latitude\": 37.50884627837613, \"longitude\": 127.0106769199523}&pageSize=90&page=1&type=공공임대";

parse_str($queryString, $param);
echo '<pre>';
print_r($param);
die();


echo '<pre>';
print_r($list);
die();
?>
<html>
<body>
<table>

    <?php foreach ($dong as $key => $value): ?>
        <tr>
            <td><?= $value['sido'] ?> <?= $value['gugun'] ?></td>
            <td>http://seoul.peterpanz.com/house/search?center={"latitude": <?= $value['latitude'] ?>,
                "longitude": <?= $value['longitude'] ?>}
            </td>
        </tr>
    <?php endforeach; ?>
</table>


<div class="map">
    <img id="seoulMap" src="http://seoul.peterpanz.com/wp-content/themes/Avada-Child-Theme/images/seoulmap.jpg"
         alt="서울시지도" usemap="#seoul_map">
    <map id="main_image" name="seoul_map">
        "도봉구"200,26,235,39
        href='http://seoul.peterpanz.com/house/search?center={"latitude": 37.668768, "longitude": 127.047163}'>
        "노원구"239,63,274,75
        href='http://seoul.peterpanz.com/house/search?center={"latitude": 37.6543543, "longitude": 127.0564716}'>
        "강북구"189,53,223,67
        href='http://seoul.peterpanz.com/house/search?center={"latitude": 37.63974, "longitude": 127.025488}'>
        "중랑구"254,97,288,109
        href='http://seoul.peterpanz.com/house/search?center={"latitude": 37.6063242, "longitude": 127.0925842}'>
        "성북구"191,96,225,108
        href='http://seoul.peterpanz.com/house/search?center={"latitude": 37.5893982, "longitude": 127.0167494}'>
        "강동구"302,146,336,159
        href='http://seoul.peterpanz.com/house/search?center={"latitude": 37.530126, "longitude": 127.1237708}'>
        "광진구"250,153,284,165
        href='http://seoul.peterpanz.com/house/search?center={"latitude": 37.538617, "longitude": 127.082375}'>
        "성동구"210,150,243,163
        href='http://seoul.peterpanz.com/house/search?center={"latitude": 37.563475, "longitude": 127.036838}'>
        "송파구"271,190,304,204
        href='http://seoul.peterpanz.com/house/search?center={"latitude": 37.514592, "longitude": 127.105863}'>
        "강남구"227,206,262,220
        href='http://seoul.peterpanz.com/house/search?center={"latitude": 37.517408, "longitude": 127.047313}'>
        "서초구"186,215,219,229
        href='http://seoul.peterpanz.com/house/search?center={"latitude": 37.4837522, "longitude": 127.0067046}'>
        "용산구"162,164,196,177
        href='http://seoul.peterpanz.com/house/search?center={"latitude": 37.532527, "longitude": 126.990487}'>
        "종로구"153,102,188,114
        href='http://seoul.peterpanz.com/house/search?center={"latitude": 37.573025, "longitude": 126.979638}'>
        "중구"175,137,199,150
        href='http://seoul.peterpanz.com/house/search?center={"latitude": 37.563842, "longitude": 126.9976}'>
        "은평구"118,82,152,95
        href='http://seoul.peterpanz.com/house/search?center={"latitude": 37.60278, "longitude": 126.9291627}'>
        "서대문구"110,122,155,135
        href='http://seoul.peterpanz.com/house/search?center={"latitude": 37.579225, "longitude": 126.9368}'>
        "마포구"118,145,151,159
        href='http://seoul.peterpanz.com/house/search?center={"latitude": 37.5663244, "longitude": 126.901491}'>
        "영등포구"102,178,147,192
        href='http://seoul.peterpanz.com/house/search?center={"latitude": 37.5264324, "longitude": 126.8960076}'>
        "동대문구"224,118,268,131
        href='http://seoul.peterpanz.com/house/search?center={"latitude": 37.5744934, "longitude": 127.0397652}'>
        "동작구"137,196,170,208
        href='http://seoul.peterpanz.com/house/search?center={"latitude": 37.51245, "longitude": 126.9395}'>
        "관악구"137,233,171,246
        href='http://seoul.peterpanz.com/house/search?center={"latitude": 37.4781548, "longitude": 126.9514847}'>
        "금천구"92,238,127,252
        href='http://seoul.peterpanz.com/house/search?center={"latitude": 37.4570783, "longitude": 126.8957011}'>
        "구로구"51,205,85,219
        href='http://seoul.peterpanz.com/house/search?center={"latitude": 37.495468, "longitude": 126.8875436}'>
        "양천구"57,179,91,193
        href='http://seoul.peterpanz.com/house/search?center={"latitude": 37.51701, "longitude": 126.8666435}'>
        "강서구"28,137,61,151
        href='http://seoul.peterpanz.com/house/search?center={"latitude": 37.5509358, "longitude": 126.8496421}'>
    </map>
</div>


</body>
</html>


<?php

die();


//var_dump(RealEstate::First());
get_header();

$address = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING, [
    'options' => [
        'default' => '서울시 강북구 수유동 280-38'
    ]
]);

define('API_URL', 'https://dapi.kakao.com');
define('APP_KEY', '3233b88e9574a4956ee1c8c6797ba632');

$url = [
    'address' => "/v2/local/search/address.json",
];
$param = [
    'query' => $address,
    'page' => 1,
    'size' => 20,
];


$client = new Client([
    'base_uri' => API_URL,
]);

$res = $client->request('GET', $url['address'] . '?' . http_build_query($param), [
    'headers' => [
        'Authorization' => 'KakaoAK ' . APP_KEY,
        'Accept' => 'application/json',
    ]
]);

//var_dump($res->getBody()->getContents());
$data = json_decode($res->getBody()->getContents(), true);


$location = [
    'address' => $data['documents'][0]['address_name'],
    'latitude' => $data['documents'][0]['x'],
    'logitude' => $data['documents'][0]['y'],
];
echo '<pre>';
print_r($location);
?>


