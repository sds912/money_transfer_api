<?php 

namespace App\EventSubscribers;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Response;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\PartnerAccount;
use App\Entity\User;
use App\Utils\AccountNumberGenerator;
use App\Utils\ContractGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class CreateAccountSubscriber implements EventSubscriberInterface
{
   private $tokenStorage;
   private $accountNumberGenerator;
   private $contractGenerator;

    public function __construct(
       TokenStorageInterface $tokenStorage,
       AccountNumberGenerator $accountNumberGenerator,
       ContractGenerator $contractGenerator
       )
    {
       $this->tokenStorage = $tokenStorage;
       $this->accountNumberGenerator = $accountNumberGenerator;
       $this->contractGenerator = $contractGenerator;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['checkPartnerAccountData', EventPriorities::POST_VALIDATE],
        ];
    }

    public function checkPartnerAccountData(ViewEvent $event)
    {
        
        $account = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$account instanceof PartnerAccount || (Request::METHOD_POST !== $method && Request::METHOD_PUT !== $method))
        {
            return;
        }

        $currentUser = $this->tokenStorage->getToken()->getUser();

        if (!$currentUser instanceof User)
        {
            return;
        }

        $account->setAccountNumber($this->accountNumberGenerator->generate());
        $account->setCreator($currentUser);
        $account->getDeposits()[0]->setCreator($currentUser);
        $amount = (int) $account->getDeposits()[0]->getAmount();
        if($amount < 500000)
        {
            throw new HttpException(HttpFoundationResponse::HTTP_CONFLICT,'Le montant initial doit etre supérieur ou égal à 500000');
        }
        $account->setBalance($amount);



       // $this->contractGenerator->generate($account->getOwner(), $account, $currentUser);

     

       

        
    }
}