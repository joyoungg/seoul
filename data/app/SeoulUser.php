<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SeoulUser extends \TCG\Voyager\Models\User
{
    use Notifiable;

    protected $table = 'users';

}
