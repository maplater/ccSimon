<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $table = 'attachments';

    protected $fillable = [
        'message_id',
        'name',
        'content',
        'type'
    ];

    public function thread()
    {
        return $this->belongsTo('App\Thread');
    }

    public function message()
    {
        return $this->belongsTo('App\Message');
    }
}
