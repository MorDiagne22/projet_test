<?php

namespace App\Service;

use App\Controller\PosteController;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Poste;
use App\Repository\PosteRepository;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PosteService
{
    private PosteRepository $repository;

    public function __construct(PosteRepository $repository) {
        $this->repository = $repository;
    }

    public function add(Poste $poste)
    {
        return $this->repository->save($poste);
    }
}