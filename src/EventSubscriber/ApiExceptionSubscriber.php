<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Serializer\SerializerInterface;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $json = $this->serializer->serialize($event, 'json');
        $resHeaders = ['Content-Type' => 'application/json'];

        if ($event->getThrowable() instanceof HttpExceptionInterface) {
            $code = $event->getThrowable()->getStatusCode();

            if ($event->getThrowable() instanceof UnauthorizedHttpException) {
                $resHeaders = array_merge($resHeaders, $event->getThrowable()->getHeaders());
            }

        } else {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        $response = new JsonResponse($json, $code, $resHeaders, true);
        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}