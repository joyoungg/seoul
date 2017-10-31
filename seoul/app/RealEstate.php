<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RealEstate extends Model
{
    use SoftDeletes;

    protected $table = 'TB_HOUSE_SALE';

    protected $primaryKey = 'hidx';

    public $timestamps = false;

    protected $dates = ['deleted_at'];

    public function realEstateImgs()
    {
        return $this->hasMany('App\RealEstateImg', 'hidx', 'hidx');
    }

}
