<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $table = 'threads';

    protected $fillable = [
        'title',
        'slug'
    ];

    public function messages()
    {
        return $this->hasMany('App\Message');
    }


}
