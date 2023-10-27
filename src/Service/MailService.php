<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer) {
        $this->mailer = $mailer;
    }

    public function sendMail(User $user)
    {
        $email = (new Email())
            ->from($user->getEmail())
            ->to('team@devphantom')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Post crée avec succés!')
            ->text('Sending emails is fun again!')
            ->html("Une publication vient d'etre créer avec succés");

        $this->mailer->send($email);
    }
}