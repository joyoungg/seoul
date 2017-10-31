<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// 법정동 코드
class MarketPrice extends Model
{
//    protected $table = 'TB_DONG_LIST_B';
    protected $primaryKey = 'idx';

    protected $fillable = [
        'house_type',
        'room_type',
        'rent_type',
        'sido',
        'gugun',
        'dong',
        'deposit',
        'monthly_fee',
        'date',
    ];

}
