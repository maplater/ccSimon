<?php

namespace App\Repositories;

use App\Message;
use Illuminate\Support\Facades\DB;

class MessageRepository{


    public function create($input, $latest, $thread)
    {
        $message = new Message();

        $message->thread_id = $thread->id;
        $message->user_id = $latest['user']['from'][0]['id'];
        $message->message_id = $input['Message-Id'];
        if($input['In-Reply-To']){$message->reply_id = $input['In-Reply-To'];}
        $message->body = $latest['html'];
        $message->date_sent = $latest['date'];

        $message->save();

        return $message;
    }

    public function getMessagesbyThread($thread)
    {

        $messages = DB::table('messages')
            ->join('users', function($join) use ($thread){
                $join->on('messages.user_id', '=', 'users.id')
                    ->where('messages.thread_id','=',$thread->id);
            })
            ->select('messages.*','users.name', 'users.email')
            ->get();

        return $messages;
    }


}