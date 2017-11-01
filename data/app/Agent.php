<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'TB_AGENCY';

    protected $primaryKey = 'aidx';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'uidx', 'uidx');
    }
}
