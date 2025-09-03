<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    //
 protected $table = 'attendances';

 protected $fillable = [
    'user_id',
    'date',
    'login_time',
    'logout_time'
 ];
}
