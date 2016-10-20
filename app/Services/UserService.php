<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\User;

class UserService{

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function saveAndPullUsers($latest)
    {
        return $this->userRepository->saveAndPullUsers($latest);

    }

    public function getUsersbyThread($thread)
    {
        return $this->userRepository->getUsersbyThread($thread);

    }

}