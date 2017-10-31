<?php
namespace App;

use Illuminate\Database\Eloquent\Model;


class SocialHousing extends Model
{
    protected $table = 'social_housing';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    public function scopeSocial($query)
    {
        return $query->where('type', '사회주택');
    }
    public function scopeHappy($query)
    {
        return $query->where('type', '행복주택');
    }

    public static function separateAddress($flag = false)
    {
        $Housing = self::all();
        foreach ($Housing as $house) {

            $gugun = explode(' ', $house->address)[0];
            if (empty($house->gugun)) {
                $house->gugun = $gugun;
            }
            echo '<pre>';
            var_dump($house->toArray());
            echo '</pre>';
            if ($flag) {
                $house->save();
            }
        }
    }
}