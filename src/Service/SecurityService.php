<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityService
{
    private UserRepository $repository;
    private MailerInterface $mailer;

    public function __construct(
        UserRepository $repository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        MailerInterface $mailer
    ) {
        $this->repository = $repository;
        $this->mailer = $mailer;
    }

    public function register(User $user)
    {

        if($user->getPlainPassword()){
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $user->getPlainPassword()
            );
            $user->setPassword($hashedPassword);
        }


        $email = (new Email())
            ->from('mordiagne@example.com')
            ->to('mordiagne1960@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Post crée avec succés!')
            ->text('Sending emails is fun again!')
            ->html('Post crée avec succés');

        $this->mailer->send($email);
        
        return $this->repository->save($user);
    }
}