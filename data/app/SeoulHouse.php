<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class SeoulHouse extends Model
{
    protected $table = 'TB_HOUSE_SALE';

    protected $primaryKey = 'hidx';

    protected $guarded = [];

    public $timestamps = false;
}
