<?php
namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Agency;
use App\Entity\Roles;
use App\PermissionRoles;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class AgencyDataPersister implements DataPersisterInterface
{

    private $entityManager;
    private $encoder;
    private $security;
    private $request;
  

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $encoder,
        Security $security,
        RequestStack $request,
        EntityManagerInterface $entity)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->encoder = $encoder;
        $this->request = $request;
    }

    public function supports($data): bool
    {
      return $data instanceof Agency;  
    }

    /**
     * @param User $data
     */
    public function persist($data)
    {

        $currentUser = $this->security->getUser();
       
    
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    } 

   public function remove($data)
   {
    $this->entityManager->remove($data);
    $this->entityManager->flush();
   }

}