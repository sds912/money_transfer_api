<?php 

namespace App\EventSubscribers;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Transaction;
use App\Entity\User;
use App\Utils\TransactionCodeGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

final class TransactionSubscriber implements EventSubscriberInterface
{
  
    private $currentUser;
    private $code;

    public function __construct(
        Security $security,
        EntityManagerInterface $manger,
        TransactionCodeGenerator $code)
    {
        $this->code = $code;
        $this->currentUser = $manger->getRepository(User::class)->findOneBy(['email'=>$security->getUser()->getUsername()]);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['transactionAuthor', EventPriorities::POST_VALIDATE],
        ];
    }

    public function transactionAuthor(ViewEvent $event)
    {
        
        $transaction = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$transaction instanceof Transaction || (Request::METHOD_POST !== $method && Request::METHOD_PUT !== $method))
        {
            return;
        }

        $transaction->setAuthor($this->currentUser);
        $transaction->setCode($this->code->generate());

    }
}