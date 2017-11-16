<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SafeNumber extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'TB_SAFEN_NUMBER';
}
