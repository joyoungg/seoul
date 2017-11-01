<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HouseImg extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'TB_HOUSE_IMG';

    protected $primaryKey = 'img_idx';

    public $timestamps = false;

}
