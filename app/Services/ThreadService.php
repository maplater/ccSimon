<?php

namespace App\Services;

use App\Repositories\ThreadRepository;
use App\Services\SlugService;
use Illuminate\Support\Facades\Log;
use App\Services\ParseService;
use App\Services\UserService;
use App\Services\MessageService;
use App\Services\AttachmentService;

class ThreadService{

    protected $threadRepository;
    protected $slugService;
    protected $parseService;
    protected $userService;
    protected $messageService;
    protected $attachmentService;


    public function __construct(AttachmentService $attachmentService,ThreadRepository $threadRepository, SlugService $slugService, ParseService $parseService,UserService $userService, MessageService $messageService)
    {
        $this->threadRepository = $threadRepository;
        $this->slugService = $slugService;
        $this->parseService = $parseService;
        $this->userService = $userService;
        $this->messageService = $messageService;
        $this->attachmentService = $attachmentService;
    }

    public function handle($input)
    {
        //check if thread exists already
        //
        if(isset($input['In-Reply-To']))
        {
            //check if thread exists
            $thread = $this->checkExist($input['In-Reply-To']);

            if($thread){

               $this->store($input, $thread);

            }else{
                //if no, then store()

                $thread = $this->makeThread($input);

                $this->store($input, $thread);


            }






        }else
        {

            $thread = $this->makeThread($input);

            $this->store($input, $thread);;
        }
    }

    public function store($input, $thread)
    {
        
        //get latest message and user
        $latest = $this->parseService->getLatest($input);

        //create or pull user
        $latest = $this->userService->saveAndPullUsers($latest);

        //store users_threads table
        $this->threadRepository->storeThreadUser($latest,$thread);


        $message = $this->messageService->create($input, $latest, $thread);


        if(!empty($latest['images'])){$this->attachmentService->storeImages($latest, $message->id);}

        if(!empty($latest['links'])){$this->attachmentService->storeLinks($latest, $message->id);}

        if(!empty($latest['maps'])){$this->attachmentService->storeMaps($latest, $message->id);}

        if(!empty($latest['videos'])){$this->attachmentService->storeVideos($latest, $message->id);}

        //create message(s)
        //save attachments
        //send email



    }

    /*public function update($input, $thread)
    {
        //get latest message and user
        $latest = $this->parseService->getLatest($input);


        //create or pull user

        $latest = $this->userService->saveAndPullUsers($latest);

        //store users_threads table
        $this->threadRepository->storeThreadUser($latest,$thread);

        $this->messageService->create($input, $latest, $thread);



    }*/

    public function send($input)
    {
        //send to emailservice

    }

    public function show($thread)
    {
        //get array of users
        //get array of messages,images,attachements


        $data = null;

        $data['users'] = $this->userService->getUsersbyThread($thread);

        $data['messages'] = $this->messageService->getMessagesbyThread($thread);

        $data['attachments'] = $this->attachmentService->getAttachmentsbyMessage($data['messages']);

        $data['attachments_types'] = $this->getAttachmentsList($data['attachments']);

        return $data;

    }

    public function getAttachmentsList($attachments)
    {
        $list = array();


        foreach($attachments as $attachment)
        {
            if(in_array($attachment->type,$list)){

            }else{
                $list[] = $attachment->type;
            }
        }

        return $list;

    }

    public function checkExist($reply_id)
    {

        $thread = $this->threadRepository->checkExist($reply_id);

        return $thread;
    }

    public function getThread($slug)
    {
        return $this->threadRepository->getThread($slug);

    }

    public function date_compare($a, $b)
    {
        $t1 = strtotime($a['date_sent']);
        $t2 = strtotime($b['date_sent']);
        return $t1 - $t2;
    }

    public function makeThread($input)
    {
        $slug = $this->slugService->makeandValidateThreadSlug($input['Subject']);

        $thread = $this->threadRepository->store($input['Subject'],$slug);

        return $thread;
    }




}