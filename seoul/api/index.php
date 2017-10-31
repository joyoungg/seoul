<?php
header('Access-Control-Allow-Origin: *');
//require __DIR__ . '/vendor/autoload.php';
require '../seoul/wp-config.php';

define('DIVIDER', '||');        //query string divider
define('RANGE_DIVIDER', '~');        //query string divider
define('ARRAY_DIVIDER', ';');
define('ZOOMLEVEL', 8);         // default zoom level




$type      = filter_input(INPUT_GET, 'type');   // type함수가 아닌 php self로 받아서 라우팅하기!!

$filter    = filter_input(INPUT_GET, 'filter');
$zoomlevel = filter_input(INPUT_GET, 'zoomLevel');
$center    = filter_input(INPUT_GET, 'center');


// type 을 받아서 클로저로 라우트 만들기!!
// 각각해당하는 라우팅에서 콘트롤러 호출하고 값 리턴해주기!!!!
//
//$filterList = splitFilter($filter);     // where 로 조건문 걸 filter 완성! -> realSize만 or 후 between 으로 걸어야함
// 이후에는 조건문으로 쿼리 문 만드기!!


//if (empty($type)) {
//    returnError('TYPE is required');
//}




// http://www.peterpanz.com/?filter=latitude:37.4846368~37.4973466&longitude:127.009641~127.0506014&zoomLevel=10&center=%7B%22y%22:37.4909935,%22_lat%22:37.4909935,%22x%22:127.0301195,%22_lng%22:127.0301195%7D

if ($type === 'mapInfo') {
    $filter = filter_input(INPUT_GET, 'filter');
    $filter = explode('||', $filter);

    $lat = explode('~', explode(':', $filter[0])[1]);
    $lng = explode('~', explode(':', $filter[1])[1]);

//    echo getMapInfo($lat, $lng);
}


// routes
require './routes.php';
