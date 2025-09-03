<?php

namespace App\Models;
use Laravel\Sanctum\HasApiTokens;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Message;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
  use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'Username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function channels()
{
    return $this->belongsToMany(Channel::class, 'channel_user');
}

public function directMessagesWith($otherUserId)
{
    return Message::where(function ($q) use ($otherUserId) {
        $q->where('user_id', $this->id)
          ->where('receiver_id', $otherUserId);
    })->orWhere(function ($q) use ($otherUserId) {
        $q->where('user_id', $otherUserId)
          ->where('receiver_id', $this->id);
    })->with('sender')->orderBy('created_at')->get(); // ← get() is required!
}

}
