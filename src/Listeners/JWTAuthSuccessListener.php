<?php
namespace App\Listeners;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class JWTAuthSuccessListener {

    private $entityManager;
    private $security;
    

    public function __construct(
        EntityManagerInterface $entityManager,
        RequestStack $request,
        Security $security
        )
    {
        $this->entityManager = $entityManager;
        $this->security = $security;

    }
    

 
    /**
     * @param AuthenticationSuccessEvent $event
     */
   public function onAuthenticationSuccess(AuthenticationSuccessEvent $event) {
    $response = $event->getResponse();
     
    $user = $this->entityManager->getRepository(User::class)->findOneBy([
        'email'=>$this->security->getUser()->getUsername(),
        'isActive' => false]);

    $data = $event->getData();
     
    if(is_null($user) === false){

       $response->setStatusCode(Response::HTTP_LOCKED);

       unset($data['token']);

       $data['message'] = "Votre compte a été bloqué, contactez votre superviseur pour de détails";
    }
    $user = $this->entityManager->getRepository(User::class)->findOneBy([
        'email'=>$this->security->getUser()->getUsername()]);
    $data['user'] = [
        'username' => $this->security->getUser()->getUsername(),
        'role' => $this->security->getUser()->getRoles()[0],
        'name' => $user->getLName(). ' '.$user->getFName()
    ];

    $event->setData($data);
    
   





   
   }

}
