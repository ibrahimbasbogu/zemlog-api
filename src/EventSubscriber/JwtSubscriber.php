<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class JwtSubscriber implements EventSubscriberInterface
{
    protected EntityManagerInterface $entityManager;

    protected UserRepository $userRepository;

    protected TranslatorInterface $translator;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->translator = $translator;
    }

    public function onExpired(JWTExpiredEvent $event)
    {
        throw new HttpException(401,'Expired JWT Token');
    }

    public function onNotFound(JWTNotFoundEvent $event)
    {
        $message = $event->getException()->getPrevious()->getMessage();
        throw new HttpException(403, $message);
    }

    public function onInvalid(JWTInvalidEvent $event)
    {
        $message = $event->getException()->getPrevious()->getMessage();
        throw new HttpException(401, $message);
    }

    public function onAuthenticationFailure(AuthenticationFailureEvent $event)
    {
        $message = $event->getException()->getPrevious()->getMessage();
        throw new HttpException(401, $message);
    }

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event)
    {
        /** @var User $user */
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $event->setData([
            'token' => $event->getData()['token'],
            'roles' => $event->getUser()->getRoles(),
        ]);
    }

    public static function getSubscribedEvents()
    {
        return [
            'lexik_jwt_authentication.on_jwt_not_found' => 'onNotFound',
            'lexik_jwt_authentication.on_jwt_expired' => 'onExpired',
            'lexik_jwt_authentication.on_jwt_invalid' => 'onInvalid',
            'lexik_jwt_authentication.on_authentication_failure' => 'onAuthenticationFailure',
            'lexik_jwt_authentication.on_authentication_success' => 'onAuthenticationSuccess',
        ];
    }
}