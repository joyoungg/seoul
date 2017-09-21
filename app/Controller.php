<?php

namespace App;

//use App\University;
//use App\Subway;
use Carbon\Carbon;
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
        ];
        $column = [
            '신축'     => 'is_new_building',
            '풀옵션'    => 'is_full_option',
            '주차가능'   => 'have_parking_lot',
            '엘리베이터'  => 'have_elevator',
            '반려동물'   => 'allow_pet',
            '전세자금대출' => 'is_debt',
        ];
        if (isset($filter['between']['is_manager_fee'])) {
            unset($filter['between']['is_manager_fee']);
        }

        if (is_null($seoulType)) {
            $properties['error'] = 'type is null';

            return json_encode($properties);
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
        if ($seoulType === 'type') {
            $types['type'] = $type;

            return json_encode($types);
        }


        foreach ($type as $value) {

            $properties['list'][$value] = RealEstate::select('TB_HOUSE_SALE.hidx', 'subject', 'deposit', 'monthly_fee', 'is_half_underground', 'is_octop', 'floor', 'contract_type', 'real_size', 'latitude', 'longitude', 'building_type', 'room_type', 'maintenance_cost', 'img_path', 'is_new_building', 'is_full_option', 'have_parking_lot', 'have_elevator', 'allow_pet', 'is_debt')
                ->whereIn('TB_HOUSE_SALE.hidx', $hidxes)
                ->where('type_seoul', $value)
                ->leftJoin('TB_HOUSE_IMG', function ($join) {
                    $join->on('TB_HOUSE_SALE.hidx', 'TB_HOUSE_IMG.hidx');
                })
                ->where('TB_HOUSE_IMG.type', 'S')
                ->where('TB_HOUSE_IMG.usage', '1')
                ->orderBy('m_date')
                ->paginate($perPage, ['*'], 'page', $page)
                ->toArray();

        }
        $properties['result'] = 'ok';

        return json_encode($properties);
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
        $dong = Dong::where('dong', 'like', "%{$keyword}%")->get();

        // 역검색
        $subway = Subway::where('station', 'like', "%{$keyword}%")->get();

        // 대학교 검색
        $university = University::where('name', 'like', "%{$keyword}%")->get();

        // 매물번호 검색

        $list['result'] = 'ok';
        $list['list']['dong'] = $dong;
        $list['list']['subway'] = $subway;
        $list['list']['university'] = $university;

        return collect($list)->toJson();

    }

// 지도에서 (수도권 및 광역시는 구별, 나머지는 시정보로)시세정보 가져오기
    public static function getPrice()
    {

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
            '행복주택',
            '사회주택',
            '직거래',
            'Zero부동산',
        ];
        $userType = [
            '개인',
            '중개',
            '서울시',
        ];
        $seoulType = [
            '서울시',
            'sh공사',
            'lh공사',
        ];
        $start = Carbon::create('2017', '04', '01', '00', '00', '00')->format('Y-m-d H:i:s');
        $end = Carbon::create('2017', '05', '01', '00', '00', '00')->format('Y-m-d H:i:s');
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

    public static function getMarketPrice($filter, $type)
    {
        if (isset($filter['between']['is_manager_fee'])) {
            unset($filter['between']['is_manager_fee']);
        }
        // TODO 현재 시간으로 전달의 평균을 구함!!
        $loc = Dong::select('sido','gugun','dong','latitude','longitude')
        ->where(function ($query) use ($filter) {
            if (isset($filter['between'])) {
                foreach ($filter['between'] as $key => $value) {
                    $query->whereBetween(DB::raw('TB_DONG_LIST_B.' . $key), $value);
                }
            }
        });

        $lastMonth = Carbon::now()->endOfMonth()->subMonth();
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
            })->leftJoin('TB_DONG_LIST_B', function ($join) {
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
                }
            }
        }

        return collect($data)->toJson();
    }
}



