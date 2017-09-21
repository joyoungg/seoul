<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

// 법정동 코드
class Dong extends Model
{
    protected $table = 'TB_DONG_LIST_B';
    protected $primaryKey = 'bdong_idx';
}
