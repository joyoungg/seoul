<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SafeHouse extends Model
{
    protected $connection = 'mysql2';

    protected $primaryKey = 'uidx';
    
    protected $table = 'TB_SAFE_REAL_ESTATE';
}
