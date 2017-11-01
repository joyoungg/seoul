<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WithoutFee extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'TB_HOUSE_WITHOUT_FEE';

    public $timestamps = false;
}
