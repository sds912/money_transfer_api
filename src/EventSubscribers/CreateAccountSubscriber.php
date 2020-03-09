<?php 

namespace App\EventSubscribers;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Response;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\PartnerAccount;
use App\Entity\Contract;
use App\Utils\AccountNumberGenerator;
use App\Utils\ContractGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;

final class CreateAccountSubscriber implements EventSubscriberInterface
{
  
   private $accountNumberGenerator;
   private $contractGenerator;
   private $currentUser;
   private $contract;
   private $manager;

    public function __construct(
       TokenStorageInterface $tokenStorage,
       AccountNumberGenerator $accountNumberGenerator,
       ContractGenerator $contractGenerator,
       Security $security,
       EntityManagerInterface $manager)
    {
       $this->tokenStorage = $tokenStorage;
       $this->accountNumberGenerator = $accountNumberGenerator;
       $this->contractGenerator = $contractGenerator;
       $this->currentUser = $security->getUser();
       $this->contract = new Contract();
       $this->manager = $manager;
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


       

        $account->setAccountNumber($this->accountNumberGenerator->generate());
        $account->setCreator($this->currentUser);
        $account->getDeposits()[0]->setCreator($this->currentUser);
        $amount = (int) $account->getDeposits()[0]->getAmount();

        if($amount < 500000)
        {
            throw new HttpException(HttpFoundationResponse::HTTP_CONFLICT,'Le montant initial doit etre supérieur ou égal à 500000');
        }
        $account->setBalance($amount);
       $this->contract->setPartner($account->getOwner());
       $this->contract->setAccount($account);
       $this->contract->setCreator($this->currentUser);
       $this->manager->persist($this->contract);
       $this->manager->flush();
       
       $this->contractGenerator->generate($account->getOwner(), $account, $this->currentUser);
  
    }
}