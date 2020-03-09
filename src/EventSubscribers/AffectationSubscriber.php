<?php 

namespace App\EventSubscribers;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Affectation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

final class AffectationSubscriber implements EventSubscriberInterface
{
  
    private $encoder;
    private $currentUser;

    public function __construct(UserPasswordEncoderInterface $encoder, Security $security,EntityManagerInterface $manger)
    {
        $this->encoder = $encoder;
        $this->currentUser = $manger->getRepository(User::class)->findOneBy(['email'=>$security->getUser()->getUsername()]);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['affectation', EventPriorities::POST_VALIDATE],
        ];
    }

    public function affectation(ViewEvent $event)
    {
        
        $affectation = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$affectation instanceof Affectation || (Request::METHOD_POST !== $method && Request::METHOD_PUT !== $method))
        {
            return;
        }
        $affectation->getEmployee()->setPassword($this->encoder->encodePassword($affectation->getEmployee(), $affectation->getEmployee()->getPhone()));
        $affectation->getEmployee()->setPartnerAccount($affectation->getAccount());
        $this->currentUser->addSupervisorUser($affectation->getEmployee());
    }
}