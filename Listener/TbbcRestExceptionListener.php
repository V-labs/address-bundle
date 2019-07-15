<?php

namespace Vlabs\AddressBundle\Listener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\EventListener\ExceptionListener;
use Symfony\Component\HttpKernel\KernelEvents;
use Tbbc\RestUtil\Error\ErrorResolverInterface;

/**
 * Class TbbcRestExceptionListener
 * @package Vlabs\AddressBundle\Listener
 */
class TbbcRestExceptionListener extends ExceptionListener
{
    private $errorResolver;

    private $handling;

    public function __construct(ErrorResolverInterface $errorResolver, $controller, LoggerInterface $logger = null)
    {
        $this->handling = false;
        $this->errorResolver = $errorResolver;
        parent::__construct($controller, $logger);
    }

    /**
     * @param GetResponseForExceptionEvent $event
     * @return void
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (true === $this->handling) {
            return;
        }

        $exception = $event->getException();

        $error = $this->errorResolver->resolve($exception);
        if (null == $error) {
            return;
        }

        $this->handling = true;

        $response = new Response(json_encode($error->toArray()), $error->getHttpStatusCode(), array(
            'Content-Type' => 'application/json'
        ));

        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::EXCEPTION => array('onKernelException', 10),
        );
    }
}