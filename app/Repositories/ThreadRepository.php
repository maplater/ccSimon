<?php

namespace App\Repositories;

use App\Thread;
use App\Message;
use App\Thread_User;

use Illuminate\Support\Facades\DB;


class ThreadRepository{

    public function store($subject,$slug)
    {

        $thread = new Thread();

        $thread = $thread->firstorCreate(['title' => $subject, 'slug' => $slug]);


        return $thread;
    }

    public function storeThreadUser($latest,$thread)
    {
        foreach ($latest['user'] as $type => $users) {

            if (is_array($users)) {

                foreach ($users as $user) {

                    $thread_user = new Thread_User();

                    $thread_user = $thread_user->firstorCreate([
                        'thread_id' => $thread->id,
                        'user_id' => $user['id'],
                        'type' => $type
                    ]);


                }
            }
        }
    }



    public function getThread($slug)
    {
        $thread = Thread::where('slug',$slug)->first();

        return $thread;
    }

    public function checkExist($reply_id)
    {
        $message = Message::where('message_id',$reply_id)->first();

        if(!empty($message))
        {
            $thread = Thread::find($message->thread->id);

            return $thread;

        }else{
            return NULL;
        }



    }
}