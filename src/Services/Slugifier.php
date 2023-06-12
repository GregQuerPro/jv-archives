<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class Slugifier
{

    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function slugifyUserName(UserInterface $user): UserInterface
    {
        $username = $user->getUsername();
        $username = strtolower($username);
        $user->setSlug($this->slugger->slug($username));
        return $user;
    }
}