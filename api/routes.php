<?php

use App\Route;
use App\Controller;
use App\SocialHousing;

//$list = [
//    'info' => 'getMapInfo',   // 지도위에 지하철, 대학교 정보 가져오기
//    'search' => 'searchHouse',
//    'list' => 'getHouseList',
//    'house' => 'getHouseDetail',
//];


define('URL', explode('?', $_SERVER['REQUEST_URI']));
define('PATH', URL[0]);
define('QUERY_STRING', count(URL) === 2 ? URL[1] : '');
define('FILTER', Controller::splitFilter(QUERY_STRING));

$route = new Route();
$route->addRoute('/', function () {
    $this->responseBody = 'Hello';
});

$route->addRoute('/filter', function () {
    $filter = FILTER;
    if (isset($filter['in'])) {
        foreach ($filter['in'] as $key => $value) {
            $filter['_in'][$key] = explode(',', $value[0]);
        }
        unset($filter['in']);
    }
    $this->responseBody = json_encode($filter);
});

$route->addRoute('/info', function () {
    $this->responseBody = Controller::getInfo(FILTER);
});

$route->addRoute('/list', function () {
    $page = filter_input(INPUT_GET, 'page');
    $perPage = filter_input(INPUT_GET,
        'perPage',
        FILTER_VALIDATE_INT,
        [
            'options' => ['default' => 90],
        ]
    );
    $type = filter_input(INPUT_GET, 'type');
    $this->responseBody = Controller::getHouseList(FILTER, $type, $page, $perPage);
});

$route->addRoute('/house', function () {
    $hidx = filter_input(INPUT_GET, 'hidx');
    $this->responseBody = Controller::getHouseDetail($hidx);
});

$route->addRoute('/search', function () {
    $keyword = filter_input(INPUT_GET, 'keyword');
    $this->responseBody = Controller::searchKeyword($keyword);
});

$route->addRoute('/house/create', function () {
    // house에 임의로 유저 정보 삽입
    $this->responseBody = Controller::createHouseInfo();
});

$route->addRoute('/price', function () {
    $roomType = filter_input(INPUT_GET, 'roomType', FILTER_DEFAULT, [
        'options' => [
            'default' => '35이하',
        ],
    ]);
    $houseType = filter_input(INPUT_GET, 'houseType', FILTER_DEFAULT, [
        'options' => [
            'default' => '단독다가구',
        ],
    ]);
    $rentType = filter_input(INPUT_GET, 'rentType', FILTER_DEFAULT, [
        'options' => [
            'default' => '월세'
        ],
    ]);
    $type = [
        'roomType'  => $roomType,
        'houseType' => $houseType,
        'rentType'  => $rentType,
    ];
    $lastMonth = filter_input(INPUT_GET, 'date', FILTER_DEFAULT, [
        'options' => [
            'default' => ''
        ],
    ]);

    $this->responseBody = Controller::getMarketPrice(FILTER, $type, $lastMonth);
});


$route->addRoute('/address', function () {
    $this->responseBody = Controller::getAddress();
});
//$route->addRoute('/search', function () {
//    $this->responseBody = Controller::searchHouseList(FILTER);
//});

$route->addRoute('/price/type', function () {
    $this->responseBody = Controller::getPriceType();
});


$route->addRoute('/test', function () {
    $this->responseBody = Controller::test();
});

$route->addRoute('/housing', function () {
    $type = filter_input(INPUT_GET, 'type', FILTER_DEFAULT, [
        'options' => [
            'default' => '행복주택',
        ],
    ]);

    if ($type === '행복주택') {
        $this->responseBody = SocialHousing::select('idx', 'type', 'latitude', 'longitude')->happy()->get();
    } elseif ($type === '사회주택') {
        $this->responseBody = SocialHousing::select('idx', 'type', 'latitude', 'longitude')->social()->get();
    }
});

$route->addRoute('/price/market/search', function () {
    $year = filter_input(INPUT_GET, 'year', FILTER_VALIDATE_INT, [
        'options' => [
            'default' => date('Y'),
        ]
    ]);
    $month = filter_input(INPUT_GET, 'month', FILTER_VALIDATE_INT, [
        'options' => [
            'default' => date('m'),
        ]
    ]);
    $houseType = filter_input(INPUT_GET, 'houseType', FILTER_DEFAULT, [
        'options' => [
            'default' => date('단독다가구'),
        ]
    ]);
    $roomType = filter_input(INPUT_GET, 'roomType', FILTER_DEFAULT, [
        'options' => [
            'default' => date('35이하'),
        ]
    ]);
    $rentType = filter_input(INPUT_GET, 'rentType', FILTER_DEFAULT, [
        'options' => [
            'default' => date('월세'),
        ]
    ]);
    $this->responseBody = Controller::marketPriceSearch($year, $month, $houseType, $roomType, $rentType);
});
$route->addRoute('/price/delete', function () {
    $this->responseBody = '1';
});
$route->addRoute('/price/upload', function () {
    if (!isset($_FILES['price']['name'])) {
        $this->responseBody = file_get_contents('./upload.html');;
    } else {
        $this->responseBody = Controller::uploadPrice();
    }
});

$route->addRoute('/house/create', function () {
    $start = filter_input(INPUT_GET, 'start', FILTER_DEFAULT, [
        'options' => [
            \Carbon\Carbon::now()->subDay(7)->format('Y-m-d'),
        ]
    ]);
    $end = filter_input(INPUT_GET, 'end', FILTER_DEFAULT, [
        'options' => [
            \Carbon\Carbon::now()->format('Y-m-d'),
        ]
    ]);
    $this->responseBoy = Controller::createHouse($start, $end);
});

$route->dispatch(PATH);
