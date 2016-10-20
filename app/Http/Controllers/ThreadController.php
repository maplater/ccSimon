<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests;
use App\Services\ThreadService;

class ThreadController extends Controller
{
    public function receive(Request $input, ThreadService $threadService)
    {

        // check if thread exists - i.e. check if thread has in-reply id
        // if not create thread, create users
        // if yes pull thread
        // if does exist, parse stripped html and create message
        // if does not exist, parse body-html and create messages
        // send email with thread
        /*Log::info("Success");
        die();*/
        $threadService->handle($input);

    }

    public function show($slug, ThreadService $threadService)
    {
        $thread = $threadService->getThread($slug);

        $data = $threadService->show($thread);

        return view('threads.show', compact('data','thread'));


    }
}
