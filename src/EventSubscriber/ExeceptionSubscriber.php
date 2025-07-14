<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExeceptionSubscriber implements EventSubscriberInterface
{
    public function onExceptionEvent(ExceptionEvent $event): void
    {
        //Récupération de l'exception lié à l'évenement
        $exception = $event->getThrowable();

        //Vérification si l'exception est une instance des HttpException
        //On crée un objet qui renverra le code et le message de l'erreur
        if ($exception instanceof HttpException) {
            $data = [
                "status" => $exception->getStatusCode(),
                "message" => $exception->getMessage()
            ];
            $event->setResponse(new JsonResponse($data));
        } else { //Sinon, l'erreur n'est pas une erreur HTTP donc on renvoi une erreur générique 500 car le problème viendra de la BDD
            $data = [
                "status" => 500,
                "message" => $exception->getMessage()
            ];
            $event->setResponse(new JsonResponse($data));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ExceptionEvent::class => 'onExceptionEvent',
        ];
    }
}
