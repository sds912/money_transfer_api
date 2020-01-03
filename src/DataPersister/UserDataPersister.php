<?php
namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\PermissionRoles;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class UserDataPersister implements DataPersisterInterface
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
      return $data instanceof User;  
    }

    /**
     * @param User $data
     */
    public function persist($data)
    {
        $currentUser = $this->security->getUser();
        $password = $data->getUsername();

        if($currentUser->getRoles() != PermissionRoles::SUPER_ADMIN)
        {
            if (PermissionRoles::SUPER_ADMIN != $data->getUserRoles()) 
            { 
               $data->addSupervisor($currentUser);
            }else{
                throw new HttpException(Response::HTTP_UNAUTHORIZED, "You can not Change Super Admin Attributes");
            }
        }

        if($currentUser->getRoles() == PermissionRoles::OWNER)
        {
            if ($data->getRoles() != PermissionRoles::AGENCY_ADMIN || $data->getRoles() != PermissionRoles::AGENCY_CASHIER)
            {
                throw new HttpException(Response::HTTP_UNAUTHORIZED, "You can only create agency admin or agency cashier");
                
            }
        }

        $data->setPassword($this->encoder->encodePassword($data, $password));
        $data->eraseCredentials();
    
        $this->entityManager->persist($data);

        $this->entityManager->flush();
    } 

   public function remove($data)
   {
    $this->entityManager->remove($data);
    $this->entityManager->flush();
   }

}