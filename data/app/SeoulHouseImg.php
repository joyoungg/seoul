<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeoulHouseImg extends Model
{
    protected $table = 'TB_HOUSE_IMG';

    protected $primaryKey = 'img_idx';

    protected $guarded = [];

    public $timestamps = false;
}
