<?php

namespace App\Services;

use App\Repositories\AttachmentRepository;


class AttachmentService{

    protected $attachmentRepository;

    public function __construct(AttachmentRepository $attachmentRepository)
    {
        $this->attachmentRepository = $attachmentRepository;
    }

    public function addAttachmentstoMessages($messages)
    {
        $messages = $this->attachmentRepository->addAttachmentstoMessages($messages);

        return $messages;
    }

    public function getAttachmentsbyMessage($messages)
    {
        $attachments = $this->attachmentRepository->getAttachmentsbyMessage($messages);

        return $attachments;
    }

    public function storeImages($latest, $message_id){

        $this->attachmentRepository->storeImages($latest,$message_id);
    }

    public function storeLinks($latest, $message_id){

        $this->attachmentRepository->storeLinks($latest,$message_id);
    }

    public function storeMaps($latest, $message_id){

        $this->attachmentRepository->storeMaps($latest,$message_id);
    }

    public function storeVideos($latest, $message_id){

        $this->attachmentRepository->storeVideos($latest,$message_id);
    }

}