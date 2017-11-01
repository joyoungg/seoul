<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'TB_HOUSE_SALE';

    protected $primaryKey = 'hidx';

    public $timestamps = false;


    public function scopeLive($query)
    {
        $query->where('status', 'LIVE')
            ->where('pp_op_validation', 1)
            ->whereIn('status_code', ['0101', '0102', '0103']);
    }
}
