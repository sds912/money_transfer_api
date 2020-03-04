<?php

namespace App\Listeners;

use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTCreatedListener {

    private $entityManager;

    public function __construct(RequestStack $request, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
 * @param JWTCreatedEvent $event
 *
 * @return void
 */

public function onJWTCreated(JWTCreatedEvent $event)
{

    $expiration = new \DateTime('+1 day');
    $expiration->setTime(2, 0, 0);
    $payload        = $event->getData();
    $payload['exp'] = $expiration->getTimestamp();
    $event->setData($payload);
        
}

}



