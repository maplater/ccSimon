<?php

namespace App\Services;

use App\Repositories\SlugRepository;

class SlugService{

    protected $slugRepository;

    public function __construct(SlugRepository $slugRepository)
    {
        $this->slugRepository = $slugRepository;
    }


    public function makeandValidateThreadSlug($slug)
    {
        return $this->slugRepository->makeandValidateThreadSlug($slug);
    }

    public function getByThreadSlug($slug)
    {

        return $this->slugRepository->getByThreadSlug($slug);

    }

    public function validateAttachmentSlug($name)
    {
        return $this->slugRepository->checkAttachmentSlug($name);
    }
}