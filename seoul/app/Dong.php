<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// 법정동 코드
class Dong extends Model
{
    protected $table = 'TB_DONG_LIST_B';
    protected $primaryKey = 'bdong_idx';

    /**
     * @param $query
     * @param array $location
     * @return mixed
     */
    public function scopeLocateGeoCoding($query, Array $location)
    {
        $list = [
            'sido'  => '',
            'gugun' => '',
            'dong'  => '',
            'ri'    => '',
        ];
        foreach ($list as $key => $value) {
            if (!isset($location[$key])) {
                throw new \Exception('missing ' . $value);

            }
        }

        $list['sido'] = $this->filterSido($location['sido']);
        $list['gugun'] = $this->filterGugun($location['gugun']);
        $list['dong'] = $this->filterDong($location['dong']);
        $list['ri'] = $this->filterRi($location['ri']);
        return $query->where(function ($q) use ($location, $list) {
            foreach ($list as $key => $value) {
                if (array_key_exists($key, $list)) {
                    $q->where($key, $value);
                }
            }
        });
    }

    private function filterSido($sido)
    {
        $convert = [
            '서울특별시' => [
                '서울',
                '서울시',
                '서울특별시',
            ]
        ];

        foreach ($convert as $key => $value) {
            if (in_array($sido, $value, true)) {
                return $key;
            }
        }

        return $sido;
    }

    private function filterGugun($gugun)
    {
        $convert = [
            '서울특별시' => [
                '서울',
                '서울시',
                '서울특별시',
            ]
        ];

        foreach ($convert as $key => $value) {
            if (in_array($gugun, $value, true)) {
                return $key;
            }
        }

        return $gugun;
    }

    private function filterDong($dong)
    {
        $convert = [
            '서울특별시' => [
                '서울',
                '서울시',
                '서울특별시',
            ]
        ];

        foreach ($convert as $key => $value) {
            if (in_array($dong, $value, true)) {
                return $key;
            }
        }

        return $dong;
    }

    private function filterRi($ri)
    {
        $convert = [
            '서울특별시' => [
                '서울',
                '서울시',
                '서울특별시',
            ]
        ];

        foreach ($convert as $key => $value) {
            if (in_array($ri, $value, true)) {
                return $key;
            }
        }

        return $ri;
    }
}
