<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //

   protected $table = 'messages';

   protected $fillable =  [
        'user_id',
        'channel_id',
        'receiver_id',
        'content',
        // 'is_read',
        'attachment',
    ];
   

    public function sender() {
    return $this->belongsTo(User::class, 'user_id');
}

public function channel() {
    return $this->belongsTo(Channel::class);
}

}
