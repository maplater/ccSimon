<?php

namespace App\Services;

use EmailReplyParser\EmailReplyParser;
use Illuminate\Support\Facades\Log;
use EmailReplyParser\Parser\EmailParser;
use EmailReplyParser\Email;
use PHPHtmlParser\Dom;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ParseService
{



    public function __construct()
    {

    }

    public function getLatest($input)
    {
        //get text from stripped-text
        //get images from stripped-html
        //get user
        //get attachments
        
        $latest['text'] = $this->getLatestText($input);
        $latest['html'] = $this->getLatestHTML($input);
        $latest['images'] = $this->getLatestImages($input);
        $latest['user'] = $this->getLatestUser($input);
        $latest['date'] = $this->getLatestDate($input);
        $latest['links'] = $this->getLatestLinks($input);
        $latest['maps'] = $this->getLatestMaps($input);
        $latest['videos'] = $this->getLatestVideos($input);

        return $latest;
    }

    public function getLatestVideos($input)
    {
        $text = $input['stripped-html'];

        $regex = '#((?:www\.)?(?:youtube\.com\/(?:watch\?v=|embed\/|v\/)|youtu\.be\/|youtube\-nocookie\.com\/embed\/)([a-zA-Z0-9-]*))#i';
        preg_match_all($regex, $text, $matches);
        $matches = array_unique($matches[0]);
        usort($matches, function($a, $b) {
            return strlen($b) - strlen($a);
        });
        ;
        return $matches;
    }
    public function getLatestMaps($input)
    {

        $context_parts_normalized = array();
        $string = array();
        $string_array = array();

        $context_parts = array_reverse(explode(" ",$input['stripped-text']));


        $zipKey = "";
        foreach($context_parts as $key=>$str) {
            if(strlen($str)===5 && is_numeric($str)) {
                $zipKey = $key;
                break;
            }
        }

        if(is_numeric($zipKey)) {

            $context_parts_cleaned = array_slice($context_parts, $zipKey);
            $context_parts_normalized = array_reverse($context_parts_cleaned);
        }

        $houseNumberKey = "";
        foreach($context_parts_normalized as $key=>$str) {
            if(strlen($str)>1 && strlen($str)<6 && is_numeric($str)) {
                $houseNumberKey = $key;
                break;
            }
        }

        if(is_numeric($houseNumberKey)) {
            $address_parts = array_slice($context_parts_normalized, $houseNumberKey);
            $string = implode(' ', $address_parts);
        }

        if(!empty($string)){
            $string = str_replace(" ","+",$string);
            $string_array[] = $string;
        }


        return $string_array;
    }
    public function getLatestLinks($input)
    {
        $links = array();
        $dom = new Dom();
        $dom->load($input['stripped-html']);
        $linksrc = $dom->find('a');

        foreach($linksrc as $src){

            $links[]= $src->href;
        }

        return $links;
    }
    public function getLatestDate($input)
    {
        return Carbon::createFromTimestamp($input['timestamp'])->toDateTimeString();
    }

    public function getLatestHTML($input)
    {

        $html = $input['stripped-html'];

        return $html;
    }

    public function getLatestUser($input)
    {
        //to
        //cc


        $f = $input['from'];
        $count = 0;
        $f = explode("<",$f);
        if(count($f) > 1){

            $from[$count]['name'] = trim($f[0]);
            $from[$count]['email'] = trim(str_replace(">","",$f[1]));

        }else{

            $from[$count]['name'] = null;
            $from[$count]['email'] = trim(str_replace(">","",$f[0]));

        }

        $user['from'] = $from;


        $t = $input['from'] . "," . $input['To'];
        $t = explode(",", $t);
        $count = 0;

        foreach($t as $tstring){
            if(strpos($tstring,env('MAILGUN_EMAIL')) === false){
                $tstring = explode("<",$tstring);

                if(count($tstring) > 1){


                    $to[$count]['name'] = trim($tstring[0]);
                    $to[$count]['email'] = trim(str_replace(">","",$tstring[1]));



                }else{

                    $to[$count]['name'] = null;
                    $to[$count]['email'] = trim(str_replace(">","",$tstring[0]));

                }

                $count++;
            }
        }

        $user['to'] = $to;


        $cc = NULL;
        $c = $input['Cc'];

        $c = explode(",", $c);
        $count = 0;
        foreach($c as $cstring){
            if(strpos($cstring,env('MAILGUN_EMAIL')) === false){
                $cstring = explode("<",$cstring);
                if(count($cstring) > 1){

                    $cc[$count]['name'] = trim($cstring[0]);
                    $cc[$count]['email'] = trim(str_replace(">","",$cstring[1]));

                }else{

                    $cc[$count]['name'] = null;
                    $cc[$count]['email'] = trim(str_replace(">","",$cstring[0]));

                }
                $count++;
            }
        }

        $user['cc'] = $cc;

        return $user;


    }

    public function getLatestImages($input)
    {

        $images = NULL;
        $dom = new Dom();
        $dom->load($input['stripped-html']);
        $imgsrc = $dom->find('img');

        foreach($imgsrc as $src){

            $images[]= $src->src;
        }

        return $images;
    }
    public function getLatestText($input)
    {

        return $input['stripped-text'];
    }
    
    
    
    public function parseBodyHTMLtoMessagesandEmails($bodyHTML)
    {

        $email = (new EmailParser())->parse($bodyHTML);

        Log::info("______________________");

        foreach($email->getFragments() as $fragment){
            $message = $fragment->getContent();

            $message = preg_replace('~On(.*?)wrote:(.*?)$~si', '', $message);

            Log::info($message);

        }




        die();

        /*$email = EmailReplyParser::read($bodyHTML);


        $fragment = current($email->getFragments());
        //$message=preg_replace('~On(.*?)wrote:(.*?)$~si', '', $message);

        Log::info($fragment);*/

        die();
        return $messages;
    }
}