<?php

namespace App\Services;

use App\Repositories\MessageRepository;
use App\Services\AttachmentService;


class MessageService{

    protected $messageRepository;
    protected $attachmentService;

    public function __construct(AttachmentService $attachmentService, MessageRepository $messageRepository)
    {
        $this->attachmentService = $attachmentService;
        $this->messageRepository = $messageRepository;
    }


    public function create($input, $latest, $thread)
    {
        return $this->messageRepository->create($input,$latest,$thread);
    }

    public function getMessagesbyThread($thread)
    {
        //pull all messages with user email and name
        //sort messages by date
        //check each message for an attachment
        //combine messages and attachments into master array

        $messages = $this->messageRepository->getMessagesbyThread($thread);

        $messages = collect($messages);
        $messages = $messages->sortByDesc('date_sent');

        $messages = $this->attachmentService->addAttachmentstoMessages($messages);

        return $messages;


    }


}