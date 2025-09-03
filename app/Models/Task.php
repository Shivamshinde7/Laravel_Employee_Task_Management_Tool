<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
    protected $fillable = [
    'title',
    'description',
    'channel_id',
    'user_id',
    'assigned_user_id',
    'status',
    'deadline_date',
];

public function user() {
    return $this->belongsTo(User::class, 'user_id');
}

public function assignedUser() {
    return $this->belongsTo(User::class, 'assigned_user_id');
}


}
