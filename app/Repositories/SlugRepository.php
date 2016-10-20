<?php

namespace App\Repositories;

use App\Thread;
use App\Attachment;


class SlugRepository{

    public function makeandValidateThreadSlug($slug)
    {

        $slug = str_slug($slug);

        $count = Thread::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }

    public function getByThreadSlug($slug)
    {

        return Thread::where('slug',$slug)->firstorfail();

    }

    public function checkAttachmentSlug($name)
    {

        return Attachment::where('name',str_slug($name))->first();

    }
}
