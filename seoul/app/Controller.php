<?php

namespace App;

//use App\University;
//use App\Subway;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Controller
{
    public static $lat;
    public static $lng;

    public function __construct()
    {
    }

// test 코드
    public static function getData($sql, $json = true)
    {
        if ($json) {
            return json_encode(DB::select(DB::raw($sql)));
        } else {
            return DB::select(DB::raw($sql));
        }
    }


// 매물상페 페이지
    public static function getHouseDetail($hidx)
    {
        $house = [
            'result' => 'ng',
            'error'  => null,
            'house'  => null,
        ];
        try {
            $house['house'] = RealEstate::where('status', 'LIVE')
                ->findOrFail($hidx);
            $house['house']['images'] = RealEstateImg::where('hidx', $hidx)
                ->where('type', 'M')
                ->get();
            $house['house']['detail'] = RealEstateFacility::select('type', 'facility')
                ->where('hidx', $hidx)
                ->get();
            $house['result'] = 'ok';
        } catch (ModelNotFoundException $me) {
            $house['house'] = null;
            $house['error'] = $me->getMessage();
        } catch (\Exception $e) {
            $house['house'] = null;
            $house['error'] = $e->getMessage();
        }

        return json_encode($house);
    }

// 지하철, 대학 위치 정보 가져오기
    public static function getInfo($filter)
    {
        $lat = Controller::$lat;
        $lng = Controller::$lng;

        $subway = Subway::where(function ($query) use ($lat, $lng) {
            $query->whereBetween('latitude', [$lat[0], $lat[1]])
                ->whereBetween('longitude', [$lng[0], $lat[1]]);
        })
            ->groupBy(['latitude', 'longitude'])
            ->get();
        $univ = University::where(function ($query) use ($lat, $lng) {
            $query->whereBetween('latitude', [$lat[0], $lat[1]])
                ->whereBetween('longitude', [$lng[0], $lat[1]]);
        })
            ->groupBy(['latitude', 'longitude'])
            ->get();


        $data = [
            'loc'        => [
                'lat' => $lat,
                'lng' => $lng,
            ],
            'subway'     => $subway,
            'university' => $univ,
        ];

        return json_encode($data);
    }

// 매물리스트 가져오기
    public static function getHouseList($filter, $seoulType = null, $page = 1, $perPage = 90)
    {
        $properties = [
            'result' => 'ng',
            'error'  => '',
            'image'  => [
                'empty' => "http://seoul.peterpanz.com/wp-content/uploads/2015/02/empty.png"
            ],
        ];
        $column = [
            '신축'     => 'is_new_building',
            '풀옵션'    => 'is_full_option',
            '주차가능'   => 'have_parking_lot',
            '엘리베이터'  => 'have_elevator',
            '반려동물'   => 'allow_pet',
            '전세자금대출' => 'is_debt',
        ];

        if (is_null($seoulType)) {
            $properties['error'] = 'type is null';

            return json_encode($properties);
        }

        if ($seoulType === 'type') {
            $types = [
                'type' => [
                    '공공임대',
                    'Zero부동산',
                    '직거래',
                ]
            ];

            return json_encode($types);
        }

        if ($filter['zoomLevel'] > 7) {
            if ($seoulType === '공공임대') {
                $properties = self::getMapLocation($properties, $seoulType, $filter);

                return json_encode($properties, JSON_HEX_TAG);
            }
        }


        if (isset($filter['between']['is_manager_fee'])) {
            unset($filter['between']['is_manager_fee']);
        }


        if (isset($filter['between']['is_manager_fee'])) {
            if (isset($filter['between']['monthly_fee'])) {
                $filter['between']['`monthly_fee`+`maintenance_cost`'] = $filter['between']['monthly_fee'];
                unset($filter['between']['monthly_fee']);
            }
            unset($filter['between']['is_manager_fee']);
        }

        // 추가옵션 항목
        if (isset($filter['in']['additional_options'])) {
            $options = explode(',', $filter['in']['additional_options'][0]);
            unset($filter['in']['additional_options']);
        }

        // 최대 금액이 전체일때 999 에서 변환
        if (isset($filter['between']['deposit'][1]) && $filter['between']['deposit'][1] === '999') {
            $filter['between']['deposit'][1] = strval(9999 * 10000 * 10000);    // 9999억
        }
        if (isset($filter['between']['`monthly_fee`+`maintenance_cost`'][1]) && $filter['between']['`monthly_fee`+`maintenance_cost`'][1] === '999') {
            $filter['between']['`monthly_fee`+`maintenance_cost`'][1] = strval(9999 * 10000 * 10000);    // 9999억
        }
        if (isset($filter['between']['monthly_fee'][1]) && $filter['between']['monthly_fee'][1] === '999') {
            $filter['between']['monthly_fee'][1] = strval(9999 * 10000 * 10000);    // 9999억
        }
        if (isset($filter['in']['room_type'])) {
            if (mb_strpos($filter['in']['room_type'][0], '원룰') !== -1) {
                $filter['in']['room_type'][0] .= ',1.5룸';
            }
            if (mb_strpos($filter['in']['room_type'][0], '쓰리룸') !== -1) {
                $filter['in']['room_type'][0] .= ',쓰리룸+';
            }
        }

        $house = RealEstate::select('hidx');
        $house = $house->where(function ($query) use ($filter, $seoulType) {
            if (isset($filter['between'])) {
                foreach ($filter['between'] as $key => $value) {
                    $query->whereBetween(DB::raw($key), $value);
                }
            }
            if (isset($filter['in'])) {
                foreach ($filter['in'] as $key => $value) {
                    $query->whereIn($key, explode(',', $value[0]));
                }
            }
            $query->where('status', 'LIVE');
            if (!empty($seoulType) && $seoulType !== 'type') {
                $query->where('type_seoul', $seoulType);
            }
            $query->where(DB::raw('date(`m_date`)'), '>', Carbon::now()->subMonth(6)->format('Y-m-d'));
        })
            ->where(function ($query) use ($filter) {
                if (isset($filter['or'])) {
                    foreach ($filter['or'] as $key => $value) {
                        foreach ($value as $key1 => $value1) {
                            $query->orWhere(function ($query1) use ($key, $value1) {
                                $query1->where($key, '>=', intval($value1[0]) * 3.3);
                                $query1->where($key, '<=', intval($value1[1]) * 3.3);
                            });
                        }
                    }
                }
            });
        if (isset($options)) {
            $house->where(function ($query) use ($options, $column) {
                foreach ($options as $option) {
                    $query->where($column[$option], 1);
                }
            });
        }

        $hidxes = $house->pluck('hidx');


        $type = RealEstate::whereIn('hidx', $hidxes)
            ->groupBy('type_seoul')
            ->pluck('type_seoul');


        foreach ($type as $value) {
            $properties['list'][$value] = RealEstate::select('TB_HOUSE_SALE.hidx', 'subject', 'deposit', 'monthly_fee', 'is_half_underground', 'is_octop', 'floor', 'contract_type', 'real_size', 'latitude', 'longitude', 'building_type', 'room_type', 'maintenance_cost', 'img_path', 'is_new_building', 'is_full_option', 'have_parking_lot', 'have_elevator', 'allow_pet', 'is_debt', 'idx_naver', 'is_safe', 'is_zero')
                ->whereIn('TB_HOUSE_SALE.hidx', $hidxes)
                ->where('type_seoul', $value)
                ->leftJoin('TB_HOUSE_IMG', function ($join) {
                    $join->on('TB_HOUSE_SALE.hidx', 'TB_HOUSE_IMG.hidx');
                })
                ->where('TB_HOUSE_IMG.type', 'S')
                ->where('TB_HOUSE_IMG.usage', '1')
                ->whereNull('TB_HOUSE_IMG.deleted_at')
                ->orderBy('m_date')
                ->paginate($perPage, ['*'], 'page', $page)
                ->toArray();
        }

        if ($seoulType === '공공임대') {    // 공공임대 하드코딩
            $socialHousing = SocialHousing::select('*')
                ->where(function ($query) use ($filter) {
                    if (isset($filter['between'])) {
                        foreach ($filter['between'] as $key => $value) {
                            if ($key === 'latitude' || $key == 'longitude') {
                                $query->whereBetween(DB::raw($key), $value);
                            }
                        }
                    }
                });
            $socialHousingList = [];
            $desc = '';
            foreach ($socialHousing->get()->toArray() as $key => $value) {
                $bg_color = '';
                if (trim($value['type']) === '사회주택') {
                    $desc = "tooltip-data='착한 임대인'";
                    $bg_color = 'bg-blue';
                } else if (trim($value['type']) === '행복주택') {
                    $desc = "tooltip-data='서민을 위한 주택";
                    $bg_color = 'bg-pink';
                }
                $value['html'] = "<div class='socialHousing {$bg_color}'>";
                $value['html'] .= "<h3 {$desc}'>{$value['type']}</h3>";
//                $value['html'] .= "<img src='http://seoul.peterpanz.com/wp-content/uploads/2015/02/img_01.jpg'/>";
                $value['html'] .= "<hr>";
                $value['html'] .= "<ul>";
                if (!empty($value['title'])) {
                    $value['html'] .= "<li class='title'>단지: {$value['title']}</li>";
                }
                if (!empty($value['title'])) {
                    $value['html'] .= "<li class='title'>형태: 아파트</li>";
                }
                if (!empty($value['supply'])) {
                    $value['html'] .= "<li class='supply'>세대수: {$value['supply']}</li>";
                }
                if (!empty($value['operator'])) {
                    $value['html'] .= "<li class='supply'>시행처: {$value['operator']}</li>";
                }
                if (!empty($value['status'])) {
                    $value['html'] .= "<li class='supply'>입주일: {$value['status']}</li>";
                }
                if (!empty($value['title'])) {
                    $value['html'] .= "<li class='title'>주소: {$value['title']}</li>";
                }
                $value['html'] .= "</ul>";
                $value['html'] .= "</div>";
                $socialHousingList[] = $value;
            }
            $properties['list']['공공임대'] = $socialHousingList;
        }

        $properties['result'] = 'ok';


        /*
        $w3r_one = array('php',"'MySQL'",'"SQL"','<?PHP ?>');
        echo "Normal: ", json_encode($w3r_one), "\n";
        echo "<hr>";
        echo "Tags: ",   json_encode($w3r_one, JSON_HEX_TAG), "\n";
        echo "<hr>";
        echo "Apos: ",   json_encode($w3r_one, JSON_HEX_APOS), "\n";
        echo "<hr>";
        echo "Quot: ",   json_encode($w3r_one, JSON_HEX_QUOT), "\n";
        echo "<hr>";
        echo "Amp: ",    json_encode($w3r_one, JSON_HEX_AMP), "\n";
        echo "<hr>";
        echo "All: ",    json_encode($w3r_one, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP), "\n\n";
        echo "<hr>";
        $w3r_two = array();
        echo "Empty array output as array: ", json_encode($w3r_two), "\n";
        echo "<hr>";
        echo "Empty array output as object: ", json_encode($w3r_two, JSON_FORCE_OBJECT), "\n\n";
        $w3r_three = array(array(1,2,3));
        echo "output of the above Non-associative array as array: ", json_encode($w3r_three), "\n";
        echo "<hr>";
        echo "output of the above Non-associative array as object: ", json_encode($w3r_three, JSON_FORCE_OBJECT), "\n\n";
        echo "<hr>";
        $w3r_four = array('PHP' => 'examples', 'MySQL' => 'With PHP');
        echo "output of the associative array as always an object: ", json_encode($w3r_four), "\n";
        echo "<hr>";
        echo "output of the associative array as always an object: ", json_encode($w3r_four, JSON_FORCE_OBJECT), "\n\n";
        die();
        */

        return json_encode($properties, JSON_HEX_TAG);
//        return json_encode($properties);
    }

    // 검색어 입력시 리턴되는 값
    public static function searchKeyword($keyword)
    {
        $list = [
            'result' => 'ng',
            'error'  => null,
            'list'   => [
                'dong'       => [],
                'subway'     => [],
                'university' => [],
                'house'      => [],
            ],
        ];
        if (mb_strlen($keyword) < 2) {
            $list['error'] = 'length is ' . mb_strlen($keyword);

            return $list;
        }

        if (is_numeric($keyword) && mb_strlen($keyword) > 5) {  // 매물번호는 무조건 6자리 이상의 숫자로만 구성
            try {
                $list['list']['house'] = RealEstate::select('hidx')
                    ->where('hidx', $keyword)
                    ->firstOrFail()['hidx'];
                $list['result'] = 'ok';
            } catch (\Exception $e) {
                $list['list']['house'] = null;
                $list['error'] = '존재하지 않는 매물번호입니다';
            }

            return collect($list)->toJson();
        }

        // 동검색
        $dong = Dong::where('dong', 'like', "%{$keyword}%")->get()->toArray();

        // 역검색
        $subway = Subway::where('station', 'like', "%{$keyword}%")->get()->toArray();
        if (empty($subway)) {
            $subway = Subway::where(DB::raw('CONCAT(station, "역")'), 'like', "%{$keyword}%")->get()->toArray();
        }

        // 대학교 검색
        $university = University::where('name', 'like', "%{$keyword}%")->get()->toArray();

        // 매물번호 검색

        $list['result'] = 'ok';
        $list['list']['dong'] = $dong;
        $list['list']['subway'] = $subway;
        $list['list']['university'] = $university;

        return collect($list)->toJson();

    }

    public static function returnError($message)
    {
        echo json_encode([
            'result' => 'ng',
            'error'  => $message,
        ]);
        die();
    }


    public static function splitFilter($queryString)
    {
        $queryStringArray = explode('&', urldecode($queryString));
        $queryStringTemp = [
            'filter'    => '',
            'center'    => '',
            'zoomLevel' => '',
            'type'      => '',
        ];
        foreach ($queryStringArray as $query) {
            $pos = mb_strpos($query, '=');
            $key = substr($query, 0, $pos);
            $queryStringTemp[$key] = mb_substr($query, $pos + 1);
        }

        $range = ['latitude', 'longitude', 'deposit', 'monthly_fee', 'is_manager_fee'];
        $array = ['building_type', 'contract_type', 'room_type', 'additional_options',];
        $floorType = [
            '반지하' => 'is_underground',
            '옥탑'  => 'is_octop',
        ];
        $sizeRange = [      // 범위에서 최소값에서 min 하고 최대값에서 Max하여 범위 결정한 다음 between에 넣기
            '5평 이하'    => [0, 5],
            '5평 ~ 10평' => [5, 10],
            '10평 이상'   => [10, 999999],
        ];
//        $queryString
//                   = <<<TEXT
//latitude:37.4389868~37.5452901||longitude:126.8859612~127.049369||checkDeposit:10000~5000000000||checkMonth:100~500000000||buildingType;["빌라/주택","오피스텔","아파트","상가/사무실"]||contractType;["매매","전세","월세","단기임대"]||roomType;["원룸","투룸","쓰리룸"]||roomCount_etc;["반지하","5층 이상","1층 ~ 5층","옥탑"]||realSize;["5평 이하","5평 ~ 10평","10평 이상"]||additional_options;["신축","엘리베이터","반려동물","풀옵션","주차가능","전세자금대출"]||isManagerFee;["add"]
//TEXT;
        $filter = [];
        if (isset($queryStringTemp['center'])) {
            $filter['center'] = $queryStringTemp['center'];
        }
        if (isset($queryStringTemp['zoomLevel'])) {
            $filter['zoomLevel'] = $queryStringTemp['zoomLevel'];
        }
        if (isset($queryStringTemp['type'])) {
            $filter['type'] = $queryStringTemp['type'];
        }

        $queryString = str_replace('filter=', '', $queryStringTemp['filter']);
        $list = explode('||', $queryString);

        foreach ($list as $key => $value) {
            $split = preg_split('(<[=>]?|>=?|:|;)', $value);
            $column = Controller::columnChecker($split[0]);
            $split[1] = isset($split[1]) ? Controller::removeChar($split[1]) : '';
            if ($column === 'roomCount_etc') {
                $temp = explode(',', $split[1]);
                foreach ($temp as $temp_key => $temp_value) {   // array 필터나 array_map 으로 변
                    if (array_key_exists($temp_value, $floorType)) {
                        $filter['where'][$floorType[$temp_value]] = 1;
                    }
                }
            } elseif ($column === 'real_size') {
                $temp = explode(',', $split[1]);
                foreach ($temp as $temp_key => $temp_value) {   // array 필터나 array_map 으로 변
                    if (array_key_exists($temp_value, $sizeRange)) {
                        $filter['or'][$column][] = $sizeRange[$temp_value];
                    }
                }
            } elseif (in_array($column, $range)) {
                $filter['between'][$column] = explode(RANGE_DIVIDER, $split[1]);
            } elseif (in_array($column, $array)) {
                $filter['in'][$column] = explode(ARRAY_DIVIDER, $split[1]);
            }
        }
        // static으로 위치 가져가
        Controller::$lat = isset($filter['between']['latitude']) ? $filter['between']['latitude'] : null;
        Controller::$lng = isset($filter['between']['longitude']) ? $filter['between']['longitude'] : null;

        // roomCount_etc 은 숫자 이거나 따로 필터를 해야함
        //realSize 은 숫자로 between 으로 계산함

        return $filter;
    }

    public static function columnChecker($column)
    {
        $convertList = [
            'checkDeposit' => 'deposit',
            'checkMonth'   => 'monthly_fee',
        ];
        if (array_key_exists($column, $convertList)) {
            return $convertList[$column];
        } else {
            return snake_case($column);
        }
    }

    public static function removeChar($data)
    {
        $data = str_replace('[', '', $data);
        $data = str_replace(']', '', $data);
        $data = str_replace('"', '', $data);

        return $data;
    }

    public static function createHouseInfo()
    {
        $seoul = [
            '직거래',
            '직거래',
            '직거래',
            'Zero부동산',
        ];
        $userType = [
            '개인',
            '중개',
            '개인',
        ];
        $seoulType = [
            '서울시',
            'sh공사',
            'lh공사',
        ];
        $start = Carbon::create('2017', '04', '01', '00', '00', '00')->format('Y-m-d H:i:s');
        $end = Carbon::create('2017', '06', '01', '00', '00', '00')->format('Y-m-d H:i:s');
        $houses = RealEstate::where('status', 'LIVE')
            ->where('c_date', '>', $start)
            ->where('c_date', '<', $end)
//            ->where('type_seoul', '중개')
            ->pluck('hidx');

        $dump = '';
        foreach ($houses as $hidx) {
            $house = RealEstate::where('hidx', $hidx)->first();
            $type = $userType[rand(0, 2)];
            $isNaver = rand(0, 1);
            if ($type === '중개') {
                $info = [
                    '중개사'  => '피터팬의 좋은방 구하기',
                    '대표자'  => '유광연',
                    '대표번호' => '02-2088-5036',
                    '주소'   => '서울시 강남구 논현동 11-6 2층',
                ];
                $tempSeoul = $seoul[3];
            } elseif ($type === '서울시') {
                $info = [
                    '공급단체' => $seoulType[rand(0, 2)],
                    '대표번호' => '02-0000-0000',
                ];
                $tempSeoul = $seoul[rand(0, 1)];
            } else {
                $info = [
                    '아이디' => str_random(10),
                ];
                $tempSeoul = $seoul[2];
            }
            $user = [
                'type'           => $type,
                'mapping_number' => '0505' . rand(100000000, 999999999),
                'info'           => $info,
            ];
            $house->user = json_encode($user);
            $house->is_safe = $tempSeoul === '직거래' ? 1 : 0;
            $house->is_zero = $tempSeoul === 'Zero부동산' ? 1 : 0;
            $house->idx_naver = $isNaver === 1 ? rand(1000, 100000) : null;
            $house->type_seoul = $tempSeoul;
            $house->save();
            $dump .= $house->toArray()['hidx'] . '<br>';
        }

        return $dump;
    }

    public static function getMarketPrice($filter, $type, $lastMonth)
    {
        $result = [
            'result' => 'ng',
            'error'  => "",
        ];
        if (isset($filter['between']['is_manager_fee'])) {
            unset($filter['between']['is_manager_fee']);
        }

        // TODO 현재 시간으로 전달의 평균을 구함!!


        /*
         * 위치정보를 먼저 줌
         * 위치 안에 있는 구들을 찾음
         */

//        $lastMonth = Carbon::now()->startOfMonth()->subMonth()->format('Y-m-d');
        if (empty($lastMonth)) {
//            $lastMonth = Carbon::now()->startOfMonth()->subMonth(1)->format('Y-m-d');
            $lastMonth = MarketPrice::groupBy('date')
                ->orderBy('date', 'desc')
                ->first()->value('date');
        }

        $prices = MarketPrice::select(DB::raw(' `market_prices`.`idx`, `market_prices`.`house_type`, `market_prices`.`rent_type`, `market_prices`.`room_type`, `market_prices`.`area`, `market_prices`.`gugun`, `market_prices`.`dong`, `market_prices`.`deposit`, `market_prices`.`monthly_fee`, `market_prices`.`date`, `TB_DONG_LIST_B`.`latitude`, `TB_DONG_LIST_B`.`longitude`'))
            ->where('date', $lastMonth)->where(function ($query) use ($filter, $type) {
                if ($filter['zoomLevel'] >= 6) { // 구단위
//                    $query->where(DB::raw('market_prices.dong'), '');
                    $query->groupBy(['sido', 'gugun']);
                } else { //if ($filter['zoomLevel'] >= 5) {//동단위
                    $query->where(DB::raw('market_prices.dong'), '!=', '');
                }

                if (isset($type['roomType'])) {
                    $query->where(DB::raw('market_prices.room_type'), $type['roomType']);
                }
                if (isset($type['houseType'])) {
                    $query->where(DB::raw('market_prices.house_type'), $type['houseType']);
                }
                if (isset($type['rentType'])) {
                    $query->where(DB::raw('market_prices.rent_type'), $type['rentType']);
                }

            })->leftJoin('TB_DONG_LIST_B', function ($join) {
                $join->on('market_prices.gugun', 'TB_DONG_LIST_B.gugun');
                $join->on('market_prices.dong', 'TB_DONG_LIST_B.dong');
            });


        // 구단위 일때 강제로 평균 보여주기
        if ($filter['zoomLevel'] >= 6) { // 구단위
            $prices->select(DB::raw(' `market_prices`.`idx`, `market_prices`.`house_type`, `market_prices`.`rent_type`, `market_prices`.`room_type`, `market_prices`.`area`, `market_prices`.`gugun`, "" as `dong`, avg(`market_prices`.`deposit`) as `deposit`, avg(`market_prices`.`monthly_fee`) as `monthly_fee`, `market_prices`.`date`, `TB_DONG_LIST_B`.`latitude`, `TB_DONG_LIST_B`.`longitude`'));
            $prices->groupBy(['market_prices.sido', 'market_prices.gugun']);
        }
//        dd($prices->toSql());
        $prices = $prices->get()->toArray();

        $list = [];
        foreach ($prices as $key => $price) {
            $price['deposit'] = number_format($price['deposit']);
            $price['monthly_fee'] = number_format($price['monthly_fee']);
            $html = '';
            $html .= "<div class='socialHousing'>";
            $html .= "<h3>{$price['gugun']} {$price['dong']}</h3> ";
            $html .= "<hr style='margin: 10px 0;'>";
            $html .= "<ul>";
            if ($price['rent_type'] === '전세') {
                $html .= "<li style='text-align: center'>보증금: {$price['deposit']}</li>";
            } else {
                $html .= "<li style='text-align: center'>보증금: {$price['deposit']}</li>";
                $html .= "<li style='text-align: center'>월세: {$price['monthly_fee']}</li>";
            }
            $html .= "<li style='text-align: right; font-size: 11px;'> <small>(단위: 만원)</small></li>";
            $html .= "</ul>";
            $html .= "<div>";
            $list[$key]['html'] = $html;
            $list[$key]['location']['latitude'] = $price['latitude'];
            $list[$key]['location']['longitude'] = $price['longitude'];
        }


        if (count($prices) > 0) {
            $result['result'] = 'ok';
            $result['data'] = $list;
        }

        return json_encode($result, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);

        /*
                $houses = RealEstate::withTrashed()
                    ->select('TB_HOUSE_SALE.sido', 'TB_HOUSE_SALE.sigungu', 'TB_HOUSE_SALE.dong', 'TB_HOUSE_SALE.deposit', 'TB_HOUSE_SALE.monthly_fee', 'TB_DONG_LIST_B.latitude', 'TB_DONG_LIST_B.longitude')
                    ->where(function ($query) use ($filter, $lastMonth, $type) {
                        if (isset($filter['between'])) {
                            foreach ($filter['between'] as $key => $value) {
                                $query->whereBetween(DB::raw('TB_HOUSE_SALE.' . $key), $value);
                            }
                        }
        //                $query->where('c_date', '>=', $lastMonth->format('Y') . '-01-01');
                        $query->where('c_date', '<', $lastMonth->format('Y-m-d'));
                        $query->where('contract_type', $type);
                    })->
                    leftJoin('TB_DONG_LIST_B', function ($join) {
                        $join->on('TB_HOUSE_SALE.sido', 'TB_DONG_LIST_B.sido');
                        $join->on('TB_HOUSE_SALE.sigungu', 'TB_DONG_LIST_B.gugun');
                        $join->on('TB_HOUSE_SALE.dong', 'TB_DONG_LIST_B.dong');
                    });
                $list = [];
                foreach ($houses->get()->toArray() as $house) {
        //            echo "<pre>";
        //            print_r($house);
        //            echo "</pre>";
        //            echo "==================================";
                    if (!isset($list[$house['sido']][$house['sigungu']][$house['dong']])) {
                        $list[$house['sido']][$house['sigungu']][$house['dong']] = [
                            'deposit'     => [],
                            'monthly_fee' => [],
                            'count'       => 0,
                        ];
                    }
                    $list[$house['sido']][$house['sigungu']][$house['dong']]['count']++;
                    $list[$house['sido']][$house['sigungu']][$house['dong']]['deposit'][] = intval($house['deposit']);
                    $list[$house['sido']][$house['sigungu']][$house['dong']]['monthly_fee'][] = intval($house['monthly_fee']);
                    $list[$house['sido']][$house['sigungu']][$house['dong']]['longitude'] = $house['longitude'];
                    $list[$house['sido']][$house['sigungu']][$house['dong']]['latitude'] = $house['latitude'];

                    sort($list[$house['sido']][$house['sigungu']][$house['dong']]['deposit']);
                    sort($list[$house['sido']][$house['sigungu']][$house['dong']]['monthly_fee']);
                }
                $data = [];
                foreach ($list as $key => $value) {
                    foreach ($value as $key1 => $value1) {
                        foreach ($value1 as $key2 => $value2) {
                            $pos = intval($value2['count'] / 2);

                            $data[$key][$key1][$key2]['location']['longitude'] = $value2['longitude'];
                            $data[$key][$key1][$key2]['location']['latitude'] = $value2['latitude'];
                            $data[$key][$key1][$key2]['price']['count'] = $value2['count'];
                            $data[$key][$key1][$key2]['price']['deposit']['median'] = $list[$key][$key1][$key2]['deposit'][$pos];
                            $data[$key][$key1][$key2]['price']['deposit']['max'] = max($list[$key][$key1][$key2]['deposit']);
                            $data[$key][$key1][$key2]['price']['deposit']['min'] = min($list[$key][$key1][$key2]['deposit']);
                            $data[$key][$key1][$key2]['price']['deposit']['avg'] = array_sum($list[$key][$key1][$key2]['deposit']) / intval($value2['count']);
                            $data[$key][$key1][$key2]['price']['monthly_fee']['median'] = $list[$key][$key1][$key2]['monthly_fee'][$pos];
                            $data[$key][$key1][$key2]['price']['monthly_fee']['max'] = max($list[$key][$key1][$key2]['monthly_fee']);
                            $data[$key][$key1][$key2]['price']['monthly_fee']['min'] = min($list[$key][$key1][$key2]['monthly_fee']);
                            $data[$key][$key1][$key2]['price']['monthly_fee']['avg'] = array_sum($list[$key][$key1][$key2]['monthly_fee']) / intval($value2['count']);

                            $data[$key][$key1][$key2]['html'] = "<li>{$data[$key][$key1][$key2]['price']['deposit']['median']}/{$data[$key][$key1][$key2]['price']['monthly_fee']['median']}</li>";
                        }
                    }
                }

                return json_encode($data, JSON_HEX_TAG);
        */
    }

    public
    static function getAddress()
    {
        $houses = SocialHousing::select('idx', 'address', 'latitude', 'longitude')->get();
        $client = new Client();

        foreach ($houses as $house) {
            echo '<pre>';
            if (empty($house->address)) {
                continue;
            }
            $url = "https://apis.daum.net/local/geo/addr2coord?apikey=827484948fa4965b36fd08b764630848&output=json&q={$house->address}";
            $content = $client->get($url)->getbody()->getcontents();
            $content = json_decode($content, true);
            echo $house->address;
            echo '<pre>';
            if (isset($content['channel']['item'][0])) {
                $loc = [
                    'lat' => $content['channel']['item'][0]['lat'],
                    'lng' => $content['channel']['item'][0]['lng'],

                ];
                $house->latitude = $loc['lat'];
                $house->longitude = $loc['lng'];
                echo "lat: {$house->lattitude} / lng: {$house->longitude}";
//                $house->save();
            } else {
                echo "lat: - / lng: -";
            }
            echo '</pre>';
            echo '<hr>';
        }

        return false;
    }

    public
    static function getPriceType()
    {
        $type1 = [
            'type'  => [
                'houseType',
            ],
            'value' => [
                '단독다가구',
                '연립다세대',
                '아파트',
                '오피스텔'
            ],
        ];

        $type2 = [
            'type'  => [
                'roomType',
            ],
            'value' => [
                '35이하',
                '50이하',
            ]
        ];

        $type3 = [
            'type'  => [
                'rentType',
            ],
            'value' => [
                '월세',
                '전세',
            ]
        ];

        $types = [
            $type1,
            $type2,
            $type3,
        ];

        return json_encode($types, JSON_HEX_TAG);
    }

    public
    static function getLocationName($filter)
    {
        $loc = Dong::select('sido', 'gugun', 'dong', 'latitude', 'longitude')
            ->where(function ($query) use ($filter) {
                if (isset($filter['between'])) {
                    foreach ($filter['between'] as $key => $value) {
                        $query->whereBetween(DB::raw('TB_DONG_LIST_B.' . $key), $value);
                    }
                }
            })->where(function ($query) use ($filter) {
                if ($filter['zoomLevel'] >= 10) { // 시단위
                    $query->where('gugun', '');
                    $query->where('dong', '');
                } elseif ($filter['zoomLevel'] >= 7) {//구단위
                    $query->where('gugun', '!=', '');
                    $query->where('dong', '');
                } else {
                    $query->where('gugun', '!=', '');
                    $query->where('dong', '!=', '');
                }
                $query->where('ri', '');
            });

        return $loc->get()->toArray();
    }

    private
    static function getMapLocation($properties, $seoulType, $filter)
    {
        $properties['result'] = 'ok';
        $properties['list'][$seoulType]['data'] = self::getLocationName($filter);
        foreach ($properties['list'][$seoulType]['data'] as $key => $value) {
            $html = '';
            if ($value['dong']) {
                $html = $value['dong'];
            } elseif ($value['gugun']) {
                $html = $value['gugun'];
            } elseif ($value['sido']) {
                $html = $value['sido'];
            }
            $properties['list'][$seoulType]['data'][$key]['html'] = "<div class='socialHousing'>";
            $properties['list'][$seoulType]['data'][$key]['html'] .= "<h3>";
            $properties['list'][$seoulType]['data'][$key]['html'] .= $html;
            $properties['list'][$seoulType]['data'][$key]['html'] .= "<h3>";
            $properties['list'][$seoulType]['data'][$key]['html'] .= "</h3>";
        }
        if ($seoulType === '공공임대') {
            $properties['list'][$seoulType] = $properties['list'][$seoulType]   ['data'];
        }

        return $properties;
    }

    public
    static function test()
    {
        $test = SocialHousing::separateAddress();
        return $test;
    }


    public
    static function marketPriceSearch($year, $month, $houseType, $roomType, $rentType)
    {
        $marketPrice = MarketPrice::where(function ($query) use ($year, $month) {
            $query->where(DB::raw('EXTRACT(YEAR FROM `date`)'), $year);
            $query->where(DB::raw('EXTRACT(MONTH FROM `date`)'), $month);
        })->where(function ($query) use ($houseType, $roomType, $rentType) {
            $query->where('house_type', $houseType);
            $query->where('room_type', $roomType);
            $query->where('rent_type', $rentType);
        });

        return $marketPrice->get()->toJson();
    }

    public static function uploadPrice()
    {

        $date = Carbon::now()->subMonth()->format('Y-m') . '-01';
        if (isset($_POST['date'])) {
            $date = $_POST['date'];
        }

        $uploaddir = __DIR__ . '/csv/';
        $uploadfile = $uploaddir . basename($_FILES['price']['name']);

        echo '<pre>';
        if (move_uploaded_file($_FILES['price']['tmp_name'], $uploadfile)) {
            echo "파일이 유효하고, 성공적으로 업로드 되었습니다.\n";
        } else {
            print "파일 업로드 공격의 가능성이 있습니다!\n";
        }

        if (($handle = fopen($uploadfile, 'r')) !== false) {
            $cnt = 0;
            $temp = $houseType = $roomType = '';
            $output = '<table>';
            while (($data = fgetcsv($handle)) !== false) {
                if ($cnt++ < 3) {
                    $houseType = $data[0] ?: $houseType;
                    $roomType = $data[1] ?: $roomType;
                    continue;
                }
                if (mb_strlen($data[0]) > 10) {
                    continue;
                }

                if (empty($data[0])) {
                    $data[0] = $temp;
                }

                $output .= '<tr>';
                foreach ($data as $value) {
                    $output .= sprintf('<td>%s</td>', $value);
                }
                MarketPrice::create([
                    'house_type'  => $houseType,
                    'room_type'   => $roomType,
                    'rent_type'   => '월세',
                    'sido'        => '서울특별시',
                    'gugun'       => $data[0],
                    'dong'        => $data[1],
                    'deposit'     => intval($data[2]),
                    'monthly_fee' => intval($data[3]),
                    'date'        => $date,
                ]);
                MarketPrice::create([
                    'house_type'  => $houseType,
                    'room_type'   => $roomType,
                    'rent_type'   => '전세',
                    'sido'        => '서울특별시',
                    'gugun'       => $data[0],
                    'dong'        => $data[1],
                    'deposit'     => intval($data[4]),
                    'monthly_fee' => 0,
                    'date'        => $date,
                ]);
                $output .= '</tr>';
                $temp = $data[0];
            }
            fclose($handle);
            $output .= '</table>';
            $output .= "<h1>입력개수: {$cnt}</h1>";
        }


//        print "</pre>";
//        return fgetcsv(__DIR__ . '/csv/' . $_FILES['price']['name']);


        return $output;
    }

    public static function createHouse($start, $end)
    {

        return '';
    }

}



