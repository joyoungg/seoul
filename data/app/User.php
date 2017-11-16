<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'TB_USER';

    protected $primaryKey = 'uidx';

    public $timestamps = false;

    public function getSafeNumberByMobile()
    {
        $number = SafeNumber::where('user_number', str_replace('-', '', $this->mobile));

        return $number->value('safe_number');
    }

    public function agency()
    {
        return $this->hasOne(Agent::class, 'uidx', 'master_id');
    }

    public function houses()
    {
        return $this->hasMany(House::class, 'uidx', 'uidx');
    }

}
