<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread_User extends Model
{
    protected $table = 'thread_user';

    protected $fillable = [
        'thread_id',
        'user_id',
        'type'
    ];
}
