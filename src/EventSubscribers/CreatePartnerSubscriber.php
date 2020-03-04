<?php 

namespace App\EventSubscribers;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Response;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Partner;
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

final class CreatePartnerSubscriber implements EventSubscriberInterface
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
        
        $partner = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$partner instanceof Partner || (Request::METHOD_POST !== $method && Request::METHOD_PUT !== $method))
        {
            return;
        }

        $currentUser = $this->tokenStorage->getToken()->getUser();

        if (!$currentUser instanceof User)
        {
            return;
        }

        $partner->getAccounts()[0]->setAccountNumber($this->accountNumberGenerator->generate());
        $partner->getAccounts()[0]->setCreator($currentUser);
        $partner->getAccounts()[0]->getDeposits()[0]->setCreator($currentUser);
        $amount = (int) $partner->getAccounts()[0]->getDeposits()[0]->getAmount();
        if($amount < 500000)
        {
            throw new HttpException(HttpFoundationResponse::HTTP_CONFLICT,'Le montant initial doit etre supérieur ou égal à 500000');
        }
        $partner->getAccounts()[0]->setBalance($amount);



       // $this->contractGenerator->generate($account->getOwner(), $account, $currentUser);

     

       

        
    }
}