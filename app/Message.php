<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    protected $fillable = [
        'thread_id',
        'user_id',
        'm_id',
        'reply_id',
        'body',
        'date_sent'
    ];

    public function thread()
    {
        return $this->belongsTo('App\Thread');
    }

    public function attachment()
    {
        return $this->hasMany('App\Attachment');
    }
}
