<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NaverStatus extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'TB_NAVER_STATUS';

    public $timestamps = false;
}
