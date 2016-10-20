<?php

namespace App\Repositories;

use App\Attachment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AttachmentRepository{

    public function addAttachmentstoMessages($messages)
    {
        $masterMessages = null;


        foreach($messages as $message){

            $masterMessages[] = $message;

            $attachments = Attachment::where('message_id',$message->id);

            foreach($attachments as $attachment){
                $masterMessages[] = $attachment;
            }

        }

        return $masterMessages;
    }

    public function getAttachmentsbyMessage($messages)
    {
        $masterAttachments = null;

        foreach($messages as $message){

            //$attachments = Attachment::where('message_id',$message->id);

            $attachments = DB::table('attachments')->where('message_id',$message->id)->get();


            foreach($attachments as $attachment){
                $masterAttachments[] = $attachment;
            }

        }
        return $masterAttachments;

    }

    public function storeImages($latest, $message_id)
    {
        foreach($latest['images'] as $imagesrc)
        {

            $filename = md5(time() . str_slug($imagesrc));

            Storage::put($filename,$imagesrc);

            $attachment = new Attachment();

            $attachment->message_id = $message_id;
            $attachment->name = $filename;
            $attachment->type = 'Images';

            $attachment->save();
        }

    }

    public function storeLinks($latest, $message_id)
    {
        foreach($latest['links'] as $link)
        {

            $attachment = new Attachment();

            $attachment->message_id = $message_id;
            $attachment->content = $link;
            $attachment->type = 'Links';

            $attachment->save();
        }

    }

    public function storeVideos($latest, $message_id)
    {
        foreach($latest['videos'] as $video)
        {

            $attachment = new Attachment();

            $attachment->message_id = $message_id;
            $attachment->content = $video;
            $attachment->type = 'Videos';

            $attachment->save();
        }

    }

    public function storeMaps($latest, $message_id)
    {
        foreach($latest['maps'] as $map)
        {

            $attachment = new Attachment();

            $attachment->message_id = $message_id;
            $attachment->content = $map;
            $attachment->type = 'Maps';

            $attachment->save();
        }

    }

}