<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class RealEstateImg extends Model
{
    protected $table = 'TB_HOUSE_IMG';
    protected $primaryKey = 'img_idx';

    public function realEstate()
    {
        return $this->belongsTo('App\RealEstate', 'hidx', 'hidx');
    }
}
