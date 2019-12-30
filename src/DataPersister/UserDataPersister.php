<?php
namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class UserDataPersister implements DataPersisterInterface
{

    private $entityManager;
    private $encoder;
    private $security;
  

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $encoder,
        Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->encoder = $encoder;
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

        if (!in_array("ROLE_SUPER_ADMIN", $data->getRoles())) 
        { 
           $data->addSupervisor($currentUser);
        }
        if($data->getPassword())
        {
            $data->setPassword(
                $this->encoder->encodePassword($data, $data->getPassword())
            );
            $data->eraseCredentials();
        }
        $this->entityManager->persist($data);

        $this->entityManager->flush();
    } 

   public function remove($data)
   {
    $this->entityManager->remove($data);
    $this->entityManager->flush();
   }

}