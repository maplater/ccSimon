<?php

namespace App\Repositories;

use App\User;
use Illuminate\Support\Facades\DB;


class UserRepository{

    public function saveAndPullUsers($latest)
    {
        foreach($latest['user'] as $type => $userarray) {
            $count = 0;
            if(is_array($userarray)) {

                foreach ($userarray as $data) {

                    $user = new User();

                    $user = $user->firstorCreate(['email' => $data['email']]);

                    $user->name = $data['name'];

                    $user->save();
                    $latest['user'][$type][$count]['id'] = $user->id;

                    $count++;

                }
            }

        }

        return $latest;

    }

    public function getUsersbyThread($thread)
    {
        $users = DB::table('threads')
            ->join('thread_user', function($join) use ($thread){
                $join->on('threads.id', '=', 'thread_user.thread_id')
                    ->where('thread_user.type','=', 'to')
                    ->where('threads.id','=',$thread->id);
            })
            ->join('users', 'thread_user.user_id','=', 'users.id')
            ->select('users.*')
            ->get();

        return $users;

    }

}