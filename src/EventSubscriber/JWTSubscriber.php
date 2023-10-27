<?php

namespace App\EventSubscriber;

use App\Entity\AuditLog;
use App\Service\AuditLogService;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class JWTSubscriber implements EventSubscriberInterface
{
    private $userRepo;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepo = $userRepository;
    }
    
    public function onLexikJwtAuthenticationOnAuthenticationSuccess($event): void
    {
        $user = $event->getUser();
        
        $event->setData([
            'id' => $user->getId(),
            'fullName' => $user->getFullName(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'token' => $event->getData()["token"]
        ]);
    }


    public static function getSubscribedEvents(): array
    {
        return [
            'lexik_jwt_authentication.on_authentication_success' => 'onLexikJwtAuthenticationOnAuthenticationSuccess',
        ];
    }
}